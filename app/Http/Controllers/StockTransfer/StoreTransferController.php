<?php

namespace App\Http\Controllers\StockTransfer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StoreRequistion\{Store_Requistion, Store_Requistion_Material, Store_Requistion_approve};
use App\Models\StoreTransfer\{Mrn_Stock_Transfer,Mrn_Stock_Transfer_Detail,Mrn_Stock_Transfer_Approve};
use App\Models\SerialNumber\{FactorySerialNumber,FactorySerialNumberDetail};
use App\Models\FinishedGood\{FinishedGoodGatepass,FinishedGoodGatepassApprove,Finished_good_gatepasses_detail};
use App\Models\Master\RawMaterial\{ Master_Raw_Material,Master_Raw_Material_Detail};

class StoreTransferController extends Controller
{
    
     public function StoreStockTransfer(Request $request)
        {
            //return $request->all();
            // Restore all input arrays from old input if available
            $res = [
                'serial_no' => old('serial_no', $request->input('serial_no', [])),
                'supplier' => old('supplier', $request->input('supplier', [])),
                'supplier_id' => old('supplier_id', $request->input('supplier_id', [])),
                'dop' => old('dop', $request->input('dop', [])),
                'make' => old('make', $request->input('make', [])),
                'brand' => old('brand', $request->input('brand', [])),
            ];
            $sample = null;
            $isNew = false;
            
            $serialNumbers = array_map('trim', $res['serial_no'] ?? []);
            // If duplicate serial numbers, restore all input fields
            if (count($serialNumbers) !== count(array_unique($serialNumbers))) {
                return back()
                    ->withInput()
                    ->withErrors(['serial_no' => 'Duplicate serial numbers found in your submission. No records were saved.']);
            }
            // Check serial numbers across all three tables with detailed conflict tracking
            $currentTransferId = null;
            if (!empty($request->edit)) {
                $currentTransfer = Mrn_Stock_Transfer::where('tr_id', $request->edit)->first();
                if ($currentTransfer) {
                    $currentTransferId = $currentTransfer->id;
                }
            }

            $conflictDetails = [];

            // 1. Check factory_serial_numbers table
            $factorySerials = FactorySerialNumberDetail::whereIn('factory_serial_number_details.sl_no', $serialNumbers)
                ->leftJoin('factory_serial_numbers', 'factory_serial_number_details.sl_id', '=', 'factory_serial_numbers.id')
                ->where(function($query) {
                    $query->where('factory_serial_numbers.Approve_status', '!=', 'REJECT')
                        ->orWhereNull('factory_serial_numbers.Approve_status');
                })
                ->pluck('factory_serial_number_details.sl_no')
                ->toArray();

            if (!empty($factorySerials)) {
                $conflictDetails['Factory Serial Numbers'] = $factorySerials;
            }

            // 2. Check mrn_stock_transfer table (excluding current record if editing)
            $mrnSerials = Mrn_Stock_Transfer_Detail::whereIn('serial_no', $serialNumbers)
                ->leftJoin('mrn_stock_transfer', 'mrn_stock_transfer_details.mrn_st_id', '=', 'mrn_stock_transfer.id')
                ->where(function($query) {
                    $query->where('mrn_stock_transfer.Approve_status', '!=', 'REJECT')
                        ->orWhereNull('mrn_stock_transfer.Approve_status');
                })
                ->when($currentTransferId, function($query) use ($currentTransferId) {
                    return $query->where('mrn_st_id', '!=', $currentTransferId);
                })
                ->pluck('serial_no')
                ->toArray();

            if (!empty($mrnSerials)) {
                $conflictDetails['MRN Stock Transfer'] = $mrnSerials;
            }

            // 3. Check finished_good_gatepasses table
            $finishedGoodSerials = Finished_good_gatepasses_detail::whereIn('finished_good_gatepasses_details.serial_no', $serialNumbers)
                ->leftJoin('finished_good_gatepasses', 'finished_good_gatepasses_details.fg_id', '=', 'finished_good_gatepasses.id')
                ->where(function($query) {
                    $query->where('finished_good_gatepasses.Approve_status', '!=', 'REJECT')
                        ->orWhereNull('finished_good_gatepasses.Approve_status');
                })
                ->pluck('finished_good_gatepasses_details.serial_no')
                ->toArray();

            if (!empty($finishedGoodSerials)) {
                $conflictDetails['Manual FG'] = $finishedGoodSerials;
            }

            // Build detailed error message
            if (!empty($conflictDetails)) {
                $errorMessage = 'Serial number conflicts found in the following sections: ';
                $conflictMessages = [];
                
                foreach ($conflictDetails as $section => $serials) {
                    $conflictMessages[] = $section . ': ' . implode(', ', $serials);
                }
                
                $errorMessage .= implode(' | ', $conflictMessages);

                return back()
                    ->withInput()
                    ->withErrors(['serial_no' => $errorMessage]);
            }
            // Check if Master_Raw_Material_Detail has trn_status = 1
            $masterRawMaterial = Master_Raw_Material_Detail::where('id', $request->edit)->first();
            $isUpdateMode = $masterRawMaterial && $masterRawMaterial->trn_status == 1;
            
            if ($isUpdateMode) {
                // Update mode: Find existing record by tr_id
                $sample = Mrn_Stock_Transfer::where('tr_id', $request->edit)->first();
                
                if (!$sample) {
                    // If no existing record found, create new one
                    $sample = new Mrn_Stock_Transfer;
                    $sample->userID = auth()->user()->id;
                    $sample->Forward_Status = 0;
                    $sample->Approve_Step = 1;
                    $isNew = true;
                } else {
                    // Found existing record, will update it
                    $isNew = false;
                }
            } else {
                // Normal mode: Original logic
                // 1. If edit is given, try fetching existing master and detail
                if (!empty($request->edit)) {
                    $existing = Mrn_Stock_Transfer::find($request->edit);

                    if ($existing) {
                        $detailExists = Mrn_Stock_Transfer_Detail::where('mrn_st_id', $existing->id)->exists();

                        if ($detailExists) {
                            $sample = $existing;
                            Mrn_Stock_Transfer::where('id', $sample->id)->update(['Approve_Step' => 1]);
                        }
                    }
                }

                // 2. If not found or detail missing, create a fresh entry
                if (!$sample) {
                    $sample = new Mrn_Stock_Transfer;
                    $sample->userID = auth()->user()->id;
                    $sample->Forward_Status = 0;
                    $sample->Approve_Step = 1;
                    $isNew = true;
                }
            }

            // 3. Assign main fields
            if (!$isUpdateMode) {
                Master_Raw_Material_Detail::where('id', $request->edit)->update(['trn_status' => 1]);
            }
            
            $sample->Material = $request->Material;
            $sample->Material_id = $request->Material_id;
            $sample->tr_id = $request->edit;
            $sample->Prj_Material_id = $request->Prj_Material_id;
            $sample->UOM = $request->UOM;
            $sample->purchahedate = $request->purchahedate;
            $sample->purchase_qty = $request->purchase_qty;
            $sample->Organization_Name = $request->Organization_Name;
            $sample->Godown_Name = $request->Godown_Name;
            $sample->remarks = $request->remarks;
            $sample->status = 0;
            
            // If update mode, set Approve_status to blank
            if ($isUpdateMode) {
                $sample->Approve_status = null;
            }
            
            $sample->save(); // Save master to get ID

            // 4. Handle serial number details based on mode
            if ($isUpdateMode) {
              
                        Mrn_Stock_Transfer_Detail::where('mrn_st_id', $sample->id)->delete();

                        // Insert serial number details
                        if (!empty($res['serial_no'])) {
                            foreach ($res['serial_no'] as $key => $val) {
                                $serialNo = trim($res['serial_no'][$key]);

                                if (empty($serialNo)) continue;

                                // Prevent duplicate serial_no for this master
                                $exists = Mrn_Stock_Transfer_Detail::where('mrn_st_id', $sample->id)
                                    ->where('serial_no', $serialNo)
                                    ->exists();

                                if (!$exists) {
                                    $Stage = new Mrn_Stock_Transfer_Detail;
                                    $Stage->mrn_st_id = $sample->id;
                                    $Stage->serial_no = $serialNo;
                                    $Stage->tr_id = $request->edit;
                                    $Stage->supplier = $res['supplier'][$key] ?? '';
                                    $Stage->supplier_id = $res['supplier_id'][$key] ?? '';
                                    $Stage->dop = $res['dop'][$key] ?? '';
                                    $Stage->make = $res['make'][$key] ?? '';
                                    $Stage->brand = $res['brand'][$key] ?? '';
                                    $Stage->save();
                                }
                            }
                        }
            } else {
                // Normal mode: Delete previous details and insert new ones
                Mrn_Stock_Transfer_Detail::where('mrn_st_id', $sample->id)->delete();

                // Insert serial number details
                if (!empty($res['serial_no'])) {
                    foreach ($res['serial_no'] as $key => $val) {
                        $serialNo = trim($res['serial_no'][$key]);

                        if (empty($serialNo)) continue;

                        // Prevent duplicate serial_no for this master
                        $exists = Mrn_Stock_Transfer_Detail::where('mrn_st_id', $sample->id)
                            ->where('serial_no', $serialNo)
                            ->exists();

                        if (!$exists) {
                            $Stage = new Mrn_Stock_Transfer_Detail;
                            $Stage->mrn_st_id = $sample->id;
                            $Stage->serial_no = $serialNo;
                            $Stage->tr_id = $request->edit;
                            $Stage->supplier = $res['supplier'][$key] ?? '';
                            $Stage->supplier_id = $res['supplier_id'][$key] ?? '';
                            $Stage->dop = $res['dop'][$key] ?? '';
                            $Stage->make = $res['make'][$key] ?? '';
                            $Stage->brand = $res['brand'][$key] ?? '';
                            $Stage->save();
                        }
                    }
                }
            }

            // 6. Approval record creation or reset
            if (!$isUpdateMode) {
                // Only handle approval records when not in update mode
                if ($isNew || (!empty($request->edit) && isset($existing) && $existing)) {
                    // For existing edited ones: reset approval if previously approved
                    if (!$isNew && $existing->Approve_status != '') {
                        Mrn_Stock_Transfer::where('id', $request->edit)->update(['Approve_status' => null]);
                        Mrn_Stock_Transfer_Approve::where('Store_Transfer_id', $request->edit)
                            ->where('status', 1)
                            ->update(['status' => 0]);
                    }

                    // Insert new approval record
                    $approve = new Mrn_Stock_Transfer_Approve;
                    $approve->userID = auth()->user()->id;
                    $approve->role = auth()->user()->role == 0 ? 'Admin' : 'Inputer';
                    $approve->Store_Transfer_id = $sample->id; // use actual ID, not request->edit
                    $approve->tr_id = $request->edit; // use actual ID, not request->edit
                    $approve->status = 1;
                    $approve->action = 'Request Raised';
                    $approve->ip_address = $request->getClientIp();
                    $approve->device_name = $request->header('User-Agent');
                    $approve->save();
                }
            }

            return redirect('StockTransfer/TransferRequestList')->with('success', 'Saved successfully.');
        }

     public function checkSerialNumber(Request $request)
    {
        $serialNo = trim($request->input('serial_no'));
        if (empty($serialNo)) {
            return response()->json(['valid' => false, 'message' => 'Serial number is required.']);
        }

        // 1. Check FactorySerialNumberDetail with status
        $factoryDetail = \App\Models\SerialNumber\FactorySerialNumberDetail::where('sl_no', $serialNo)
            ->leftJoin('factory_serial_numbers', 'factory_serial_number_details.sl_id', '=', 'factory_serial_numbers.id')
            ->select('factory_serial_numbers.Approve_status')
            ->first();
        if ($factoryDetail) {
            if ($factoryDetail->Approve_status === 'REJECT') {
                return response()->json(['valid' => true, 'message' => 'Serial number [' . $serialNo . '] is rejected and can be used.']);
            } else {
                return response()->json(['valid' => false, 'message' => 'Serial number [' . $serialNo . '] already exists in Factory Serial Numbers.']);
            }
        }
        

        // 2. Check Mrn_Stock_Transfer_Detail with status
        $mrnDetail = \App\Models\StoreTransfer\Mrn_Stock_Transfer_Detail::where('serial_no', $serialNo)
            ->leftJoin('mrn_stock_transfer', 'mrn_stock_transfer_details.mrn_st_id', '=', 'mrn_stock_transfer.id')
            ->select('mrn_stock_transfer.Approve_status')
            ->first();
        if ($mrnDetail) {
            if ($mrnDetail->Approve_status === 'REJECT') {
                return response()->json(['valid' => true, 'message' => 'Serial number [' . $serialNo . '] is rejected and can be used.']);
            } else {
                return response()->json(['valid' => false, 'message' => 'Serial number [' . $serialNo . '] already exists in MRN Stock Transfer.']);
            }
        }

        // 3. Check Finished_good_gatepasses_detail with status
        $fgDetail = \App\Models\FinishedGood\Finished_good_gatepasses_detail::where('serial_no', $serialNo)
            ->leftJoin('finished_good_gatepasses', 'finished_good_gatepasses_details.fg_id', '=', 'finished_good_gatepasses.id')
            ->select('finished_good_gatepasses.Approve_status')
            ->first();
        if ($fgDetail) {
            if ($fgDetail->Approve_status === 'REJECT') {
                return response()->json(['valid' => true, 'message' => 'Serial number [' . $serialNo . '] is rejected and can be used.']);
            } else {
                return response()->json(['valid' => false, 'message' => 'Serial number [' . $serialNo . '] already exists in Manual FG.']);
            }
        }

        return response()->json(['valid' => true, 'message' => 'Serial number [' . $serialNo . '] is valid and can be used.']);
    }


}
