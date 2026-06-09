<?php

namespace App\Http\Controllers\StockTransfer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StoreRequistion\{Store_Requistion, Store_Requistion_Material, Store_Requistion_approve};
use App\Models\Master\Plant\{Master_Manufacturing_unit, Master_Customer_Name, Master_BU};
use App\Models\FactoryCreater\{Factory_Organisation, Factory_Uom,prj_organisation,unitname,Factory_Address_Detail};
use App\Models\Master\{Master_Plant_Machinery,Prj_Subproject,Prj_Project,Module_Bsns_Unit,Prj_Inventory,Pur_Address};
use App\Models\MaterialManagement\{MaterialManagement_Add_Material};
use App\Models\BOM\{BOM, BOM_Material};
use App\Models\{CheckBox, Admin,PlantStock,Forwarded_Data, Department_Assign};
use App\Models\StoreTransfer\{Mrn_Stock_Transfer,Mrn_Stock_Transfer_Detail,Mrn_Stock_Transfer_Approve};
use App\Models\Master\RawMaterial\{Master_Godown_Name, Master_Raw_Material,Master_Raw_Material_Detail, Master_OB, Master_Received_QTY, Master_Rack_No, Master_Sub_Rack_No, Master_Bin_No, Master_Sub_Bin_No, Master_Gate_Pass_Required, Master_HSN_Code, Master_Material_Name};

use Session;


class StoreTransferApproveController extends Controller
{
    public function StoreTransfer_approve(Request $request)
        {
            $EXT = Session::get('EXT');

            $dateto = $request->input('to_date');
            $fromdate = $request->input('from_date');
            $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));

            $query = Master_Raw_Material_Detail::select('master_raw_material_details.*','mrn_stock_transfer.userID','mrn_stock_transfer.Forward_Status','mrn_stock_transfer.Approve_status','mrn_stock_transfer.Approve_Step','mrn_stock_transfer.status')
                ->leftJoin('mrn_stock_transfer','master_raw_material_details.id','=','mrn_stock_transfer.tr_id')
                ->where('master_raw_material_details.entry', 1)
                ->whereIn('master_raw_material_details.Material', function ($q) {
                    $q->select('materialmanagement_add_material.id')
                    ->from('materialmanagement_add_material')
                    ->join('crmwtp_product_details', 'materialmanagement_add_material.Material_Name', '=', 'crmwtp_product_details.matrl_id')
                    ->groupBy('materialmanagement_add_material.id');
                })
                ->orderBy('master_raw_material_details.id', 'DESC');

             if ($fromdate && $dateto) {
                $query->whereBetween('master_raw_material_details.Mrn_Date', [$fromdate, $dateto]);
             }

            if (isset($EXT[23]['Forward']) && isset($EXT[23]['approver'])) {       
                $query = $query->where(function ($query) use ($EXT) {
                    $query->where('mrn_stock_transfer.Approve_status', null)
                        ->where('mrn_stock_transfer.Forward_Status', 0)
                        ->whereRaw("mrn_stock_transfer.Approve_Step IN (" . implode(",", $EXT[23]['approver']) . ")");
                })
                    ->orWhere(function ($query) {
                        $query->whereRaw('master_raw_material_details.id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0)')
                            ->where(function($q) {
                                $q->whereNull('mrn_stock_transfer.Approve_status')
                                    ->orWhere('mrn_stock_transfer.Approve_status', 'FORWARD');
                            })
                            ->where('mrn_stock_transfer.Forward_Status', 1);
                    })
                    ->orWhere(function ($query) {
                        $query->whereRaw('master_raw_material_details.id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0)')
                            ->where(function($q) {
                                $q->whereNull('mrn_stock_transfer.Approve_status')
                                    ->orWhere('mrn_stock_transfer.Approve_status', 'FORWARD');
                            })
                            ->where('mrn_stock_transfer.Forward_Status', 1);
                    })
                    ->orderBy('master_raw_material_details.id', 'DESC');
            } 
            elseif (isset($EXT[23]['Forward'])) {       
                $query = $query->where('mrn_stock_transfer.Forward_Status', 1)
                            ->whereRaw('master_raw_material_details.id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0)')
                            ->where(function($q) {
                                $q->whereNull('mrn_stock_transfer.Approve_status')
                                    ->orWhere('mrn_stock_transfer.Approve_status', 'FORWARD');
                            })
                            ->orderBy('master_raw_material_details.id', 'DESC');
            } elseif (isset($EXT[23]['approver'])) {
                $query = $query->where('mrn_stock_transfer.Approve_status', null)
                            ->where('mrn_stock_transfer.Forward_Status', 0)
                            ->where('mrn_stock_transfer.status', 0)
                            ->whereRaw("mrn_stock_transfer.Approve_Step IN (" . implode(",", $EXT[23]['approver']) . ")")
                            ->orderBy('master_raw_material_details.id', 'DESC');
            }

            $store = $query->get();

            $store_arr = array();
            foreach ($store as  $val) {
                if ($val->Forward_Status != 1) {
                    $val->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="23" AND step="' . $val->Approve_Step . '")')->get();
                } else {
                    $val->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data` WHERE DataID="' . $val->id . '" AND DepartmentID=23 AND status=0)')->get();
                }
                $val->user = Admin::find($val->userID);
                $val->Organization_Name = prj_organisation::find($val->Organization_Name);
                $val->Manufacturing_Unit = prj_project::find($val->Manufacturing_Unit);
                $val->Plant_Name = Prj_Subproject::find($val->Plant_Name);
                $val->Godown_Name = Prj_Inventory::find($val->Godown_Name);
                $val->HoldStatus = Store_Requistion_approve::where('Store_Requistion_id', $val->id)->where('action', 'HOLD')->where('status', 1)->where('userID', auth()->user()->id)->count();
                
                $val->Raw_Material = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                ->where('materialmanagement_add_material.id',$val->Material)
                ->first();

                array_push($store_arr, $val);
            }

            return view('StockTransfer/StoreTransferApproveList', ['store' => $store_arr, 'fromdate' => $fromdate, 'todate' => $dateto]);
        }

    public function view_approve($id, $type)
    {
        $appro = Mrn_Stock_Transfer_Approve::where('tr_id', $id)->get();
        $approves = [];
        foreach ($appro as $val) {
            $val->user = Admin::find($val->userID);
            array_push($approves, $val);
        }

        $Organization_Name = prj_organisation::all();
        $Manufacturing_Unit = prj_project::all();
        $Plant_Name = Prj_Subproject::all();
        $UOM = Factory_Uom::all();
        $Godown_Name = Prj_Inventory::all();
        $employeeName = Admin::where('role', 1)->whereRaw('id IN (SELECT userID FROM employee_department where Departments="23")')->get();
        $BOM_DATA = BOM::where('Approve_status', 'APPROVE')->get();
        $Raw_Material = [];
        foreach ($BOM_DATA as $Val) {
            if (isset($Val->Raw_Material_FG)) {
                //$Val->RawMaterial = MaterialManagement_Add_Material::find($Val->Raw_Material_FG);
                $Val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                    ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                    ->where('materialmanagement_add_material.id',$Val->Raw_Material_FG)
                    ->first();
                $Raw_Material[$Val->Raw_Material_FG] = $Val;
            }
        }
        $Filtered_Array = array_values($Raw_Material);
        //$edit = Store_Requistion::find($id);
        $edit=Master_Raw_Material_Detail::select(
                'master_raw_material_details.*',
                'prj_material.material_name as matname',
                'prj_material.uom',
                'prj_material.id as material_id',
                'mrn_stock_transfer.Organization_Name as transfer_org_name',
                'mrn_stock_transfer.Godown_Name as transfer_godown_name'
            )
                ->leftJoin('materialmanagement_add_material','master_raw_material_details.Material','=','materialmanagement_add_material.id')
                ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                ->leftJoin('mrn_stock_transfer','master_raw_material_details.id','=','mrn_stock_transfer.tr_id')
                ->where('master_raw_material_details.id','=',$id)->first();
        
        // Prioritize transfer table values over master data
        if ($edit) {
            $edit->Organization_Name = $edit->transfer_org_name ?? $edit->Organization_Name;
            $edit->Godown_Name = $edit->transfer_godown_name ?? $edit->Godown_Name;
        }
        $Materials = array();
        if (isset($edit->id) && $edit->id != '') {
            $Materials = Mrn_Stock_Transfer_Detail::where('tr_id', $edit->id)->get();
            $remarks=Mrn_Stock_Transfer::select('remarks')->where('tr_id',$edit->id)->first();
        }

        $nextID = $this->next($id, $type);

        return view('StockTransfer/StoreTransferApprove', ['edit' => $edit, 'Organization_Name' => $Organization_Name, 'Manufacturing_Unit' => $Manufacturing_Unit, 'Plant_Name' => $Plant_Name, 'Raw_Material' => $Filtered_Array, 'UOM' => $UOM, 'Godown_Name' => $Godown_Name, 'Materials' => $Materials, 'approves' => $approves, 'nextID' => $nextID, 'employeeName' => $employeeName,'remarks'=>$remarks]);
    }

    function next($id, $type)
    {
        $datra = Session::get('nexdata');
        if (isset($datra)) {
            $datra = $datra[$type];
            $key = array_search($id, $datra);
            if (isset($datra[$key + 1])) {
                return $datra[$key + 1] . '/' . $type;
            }
        }
        return '';
    }

    public function approve(Request $request)
    {
        //return $request->all();
        $EXT = Session::get('EXT');

        if (!empty($request->during_approval)) {
            $updated = Mrn_Stock_Transfer::where('tr_id', $request->approveID)->update(['Approve_status' => $request->during_approval]);
            Mrn_Stock_Transfer_Approve::where('tr_id', $request->approveID)->where('status', 1)->update(['status' => 0]);
        }
        $project = Master_Raw_Material_Detail::where('id', $request->approveID)->first();
        $check = Mrn_Stock_Transfer::where('tr_id', $request->approveID)->first();  
        // if ($request->during_approval === 'APPROVE') {
        //     $status = Forwarded_Data::where('DataID', $request->approveID)->update(['status' => 1]);
        //     Mrn_Stock_Transfer::where('tr_id', $request->approveID)->update(['Forward_Status' => 0]);

        //     $DepartStepcount2 = Department_Assign::where(['departments' => 23, 'step' => 2])->count();
        //     $DepartStepcount3 = Department_Assign::where(['departments' => 23, 'step' => 3])->count();

        //     if ($check->Approve_Step == 1 && $DepartStepcount2 > 0) {
        //         Mrn_Stock_Transfer::where('tr_id', $request->approveID)->update(['Approve_Step' => 2, 'Approve_status' => null]);
        //     }

        //     if ($check->Approve_Step == 2 &&  $DepartStepcount3 > 0) {
        //         Mrn_Stock_Transfer::where('tr_id', $request->approveID)->update(['Approve_Step' => 3, 'Approve_status' => null]);
        //     }
        // }

        if ($request->during_approval === 'APPROVE') {
        $status = Forwarded_Data::where('DataID', $request->approveID)->update(['status' => 1]);
        Mrn_Stock_Transfer::where('tr_id', $request->approveID)->update(['Forward_Status' => 0]);
        
        $DepartStepcount2 = Department_Assign::where(['departments' => 23, 'step' => 2])->count();
        $DepartStepcount3 = Department_Assign::where(['departments' => 23, 'step' => 3])->count();
        
        $finalApproval = true;
        
        if ($check->Approve_Step == 1 && $DepartStepcount2 > 0) {
            Mrn_Stock_Transfer::where('tr_id', $request->approveID)->update(['Approve_Step' => 2, 'Approve_status' => null]);
            $finalApproval = false;
        }
        
        if ($check->Approve_Step == 2 && $DepartStepcount3 > 0) {
            Mrn_Stock_Transfer::where('tr_id', $request->approveID)->update(['Approve_Step' => 3, 'Approve_status' => null]);
            $finalApproval = false;
        }
        
        // Final approval - execute stock transfer
        if ($finalApproval == true) {
            // Deduct from Master_Raw_Material
            $masterRawMaterial = Master_Raw_Material::where('id', $project->raw_mat_id)->first();
            if ($masterRawMaterial) {
                $masterRawMaterial->Quantity -= $project->Quantity;
                $masterRawMaterial->save();
            }
            
            // Check if PlantStock record exists
            $plantStock = PlantStock::where([
                'Manufacturing_Unit' => $project->Prj_Id,
                'plantID' => $project->Subprj_Id,
                'materialID' => $project->Material,
                'type' => 1
            ])->first();
            
            if ($plantStock) {
                // Record exists - update existing stock
                $plantStock->stock += $project->Quantity;
                $plantStock->save();
            } else {
                // Record doesn't exist - create new PlantStock record
                $newPlantStock = new PlantStock();
                $newPlantStock->Manufacturing_Unit = $project->Prj_Id;
                $newPlantStock->plantID = $project->Subprj_Id;
                $newPlantStock->materialID = $project->Material;
                $newPlantStock->stock = $project->Quantity;
                $newPlantStock->type = 1;
                $newPlantStock->save();
            }
        }
    }

    // if ($request->during_approval === 'REJECT') {
    //     $status = Forwarded_Data::where('DataID', $request->approveID)->update(['status' => 2]);
    //     Mrn_Stock_Transfer::where('tr_id', $request->approveID)->update(['Forward_Status' => 0]);
        
    //     // Check if this transfer was already processed (final approval completed)
    //     $wasProcessed = Mrn_Stock_Transfer::where('tr_id', $request->approveID)
    //                                     ->where('Approve_status', 'COMPLETED')
    //                                     ->exists();
        
    //     if ($wasProcessed) {
    //         // Only reverse the Master_Raw_Material quantity
    //         // Do NOT touch PlantStock as per your requirement
    //         $masterRawMaterial = Master_Raw_Material::where('id', $project->raw_mat_id)->first();
    //         if ($masterRawMaterial) {
    //             $masterRawMaterial->Quantity += $project->Quantity;
    //             $masterRawMaterial->save();
    //         }
            
    //         // PlantStock quantity is intentionally NOT changed during rejection
    //         // as per your custom requirement
    //     }
    // }


        if ($request->during_approval === 'FORWARD') {
            Forwarded_Data::where(['DepartmentID' => 23, 'DataID' => $request->approveID])->update(['status' => 1]);
            Mrn_Stock_Transfer::where('tr_id', $request->approveID)->update(['Forward_Status' => 1]);

            $forward = new Forwarded_Data;
            $forward->userID = auth()->user()->id;
            $forward->Forward_To_id = $request->Forward_To;
            $forward->DepartmentID = 23;
            $forward->DataID = $request->approveID;
            $forward->status = 0;

            $forward->save();
        }

        $approve = new Mrn_Stock_Transfer_Approve;
        $approve->userID = auth()->user()->id;
        if (auth()->user()->role == 0) {
            $approve->role = 'Admin';
        } elseif (isset($EXT[23]['Inputer'])) {
            $approve->role = 'Inputer';
        } elseif (isset($EXT[23]['approver'])) {
            $approve->role = 'Approver';
        } else {
            $approve->role = 'Viewer';
        }
        $aprvid=Mrn_Stock_Transfer::select('id')->where('tr_id',$request->approveID)->first();
        $approve->Store_Transfer_id = $aprvid->id;
        $approve->status = 1;
        if ($request->during_approval != '') {
            $approve->action = $request->during_approval;
        } elseif ($request->pre_post_approval != '') {
            $approve->pre_post_approval = $request->pre_post_approval;
        } else {
            $approve->action = 'Replied';
        }
        $approve->comment_text = $request->comment_text;
        $approve->tr_id = $request->approveID;
        $approve->ip_address = $request->getClientIp();
        $approve->device_name = $request->server('HTTP_USER_AGENT');
        $approve->days_for_holding = $request->days_for_holding;
        $approve->Forward_To = $request->Forward_To;

        $approve->save();

        if ($request->during_approval == '' && $request->pre_post_approval == '') {
            Mrn_Stock_Transfer::where('tr_id', $request->approveID)->update(['Approve_status' => null]);
            return redirect('StockTransfer/TransferRequestList')->with('success', 'successfully.....');
        } elseif (($request->pre_post_approval == 'AUDIT' || $request->pre_post_approval == 'INTIMATION' || $request->pre_post_approval == 'QUERY') && $request->non_acting == 1) {
            return redirect('StockTransfer/TransferRequestList')->with('success', 'successfull.....');
        } else {
            return redirect('StockTransfer/ApprovalList')->with('success', 'Approved successfully.....');
        }
    }


    public function CheckHoldExpiry()
    {
        $Approve = Mrn_Stock_Transfer_Approve::all();

        return response()->json($Approve);
    }

    public function UpdateStatus(Request $request)
    {
        $Store_Transfer_id = $request->input('Store_Transfer_id');
        $userID = $request->input('userID');

        $approves = Mrn_Stock_Transfer_Approve::where('Store_Transfer_id', $Store_Transfer_id)->where('userID', $userID)->update(['status' => 0]);
        $factory =  Mrn_Stock_Transfer::where('id', $Store_Transfer_id)->update(['Approve_status' => null]);

        $approve = new Mrn_Stock_Transfer_Approve;
        $approve->role = 'AUTO';
        $approve->Store_Transfer_id = $Store_Transfer_id;
        $approve->status = 1;
        $approve->action = 'Hold Released';
        $approve->comment_text = $request->comment_text;
        $approve->ip_address = $request->getClientIp();
        $approve->device_name = $request->server('HTTP_USER_AGENT');
        $approve->save();

        $response = array(
            'success' => true,
            'message' => 'Updated successfully.'
        );

        return response()->json($response);
    }


    public function Release_Hold(Request $request, $id)
    {
        //return $id;
        $EXT = Session::get('EXT');
        $currentDate = now();

        $approvesss = Mrn_Stock_Transfer_Approve::where('tr_id', $id)->where('action', 'HOLD')->update(['days_for_holding' => $currentDate, 'status' => 0]);
        $factory =  Mrn_Stock_Transfer::where('tr_id', $id)->update(['Approve_status' => null]);

        $approve = new Mrn_Stock_Transfer_Approve;
        $approve->userID = auth()->user()->id;
        if (auth()->user()->role == 0) {
            $approve->role = 'Admin';
        } elseif (isset($EXT[15]['approver'])) {
            $approve->role = 'Approver';
        } elseif (isset($EXT[15]['inputer'])) {
            $approve->role = 'Inputer';
        } else {
            $approve->role = 'Viewer';
        }
        $aprvid=Mrn_Stock_Transfer::select('id')->where('tr_id',$id)->first();
        $approve->Store_Transfer_id = $aprvid->id;
        $approve->tr_id = $id;
        $approve->status = 1;
        $approve->action = 'Hold Released';
        $approve->comment_text = $request->comment_text;
        $approve->ip_address = $request->getClientIp();
        $approve->device_name = $request->server('HTTP_USER_AGENT');
        $approve->save();


        return redirect('StockTransfer/TransferRequestList')->with('success', 'Hold Released successfully.....');
    }
    public function ExportaprvData(Request $request)
    {
        $EXT = Session::get('EXT');

        $query = Master_Raw_Material_Detail::select('master_raw_material_details.*','mrn_stock_transfer.userID','mrn_stock_transfer.Forward_Status','mrn_stock_transfer.Approve_status','mrn_stock_transfer.Approve_Step','mrn_stock_transfer.status')
                ->leftJoin('mrn_stock_transfer','master_raw_material_details.id','=','mrn_stock_transfer.tr_id')
                ->where('master_raw_material_details.entry', 1)
                ->whereIn('master_raw_material_details.Material', function ($q) {
                    $q->select('materialmanagement_add_material.id')
                    ->from('materialmanagement_add_material')
                    ->join('crmwtp_product_details', 'materialmanagement_add_material.Material_Name', '=', 'crmwtp_product_details.matrl_id')
                    ->groupBy('materialmanagement_add_material.id');
                })
                ->orderBy('master_raw_material_details.id', 'DESC');

        // Apply date filters
        $fromdate = $request->input('from_date');
        $todate = $request->input('to_date');

        if (!empty($fromdate) && !empty($todate)) {
            $query->whereBetween('master_raw_material_details.Mrn_Date', [$fromdate, $todate]);
        }

        // Apply Material Filter
        $RawMaterialss = $request->input('Raw_Material', '');
        if (!empty($RawMaterialss) && $RawMaterialss !== 'all') {
            $query->where('master_raw_material_details.Material', $RawMaterialss);
        }

         if (isset($EXT[23]['Forward']) && isset($EXT[23]['approver'])) {       
                $query = $query->where(function ($query) use ($EXT) {
                    $query->where('mrn_stock_transfer.Approve_status', null)
                        ->where('mrn_stock_transfer.Forward_Status', 0)
                        ->whereRaw("mrn_stock_transfer.Approve_Step IN (" . implode(",", $EXT[23]['approver']) . ")");
                })
                    ->orWhere(function ($query) {
                        $query->whereRaw('master_raw_material_details.id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0)')
                            ->where(function($q) {
                                $q->whereNull('mrn_stock_transfer.Approve_status')
                                    ->orWhere('mrn_stock_transfer.Approve_status', 'FORWARD');
                            })
                            ->where('mrn_stock_transfer.Forward_Status', 1);
                    })
                    ->orWhere(function ($query) {
                        $query->whereRaw('master_raw_material_details.id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0)')
                            ->where(function($q) {
                                $q->whereNull('mrn_stock_transfer.Approve_status')
                                    ->orWhere('mrn_stock_transfer.Approve_status', 'FORWARD');
                            })
                            ->where('mrn_stock_transfer.Forward_Status', 1);
                    })
                    ->orderBy('master_raw_material_details.id', 'DESC');
            } 
            elseif (isset($EXT[23]['Forward'])) {       
                $query = $query->where('mrn_stock_transfer.Forward_Status', 1)
                            ->whereRaw('master_raw_material_details.id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0)')
                            ->where(function($q) {
                                $q->whereNull('mrn_stock_transfer.Approve_status')
                                    ->orWhere('mrn_stock_transfer.Approve_status', 'FORWARD');
                            })
                            ->orderBy('master_raw_material_details.id', 'DESC');
            } elseif (isset($EXT[23]['approver'])) {
                $query = $query->where('mrn_stock_transfer.Approve_status', null)
                            ->where('mrn_stock_transfer.Forward_Status', 0)
                            ->where('mrn_stock_transfer.status', 0)
                            ->whereRaw("mrn_stock_transfer.Approve_Step IN (" . implode(",", $EXT[23]['approver']) . ")")
                            ->orderBy('master_raw_material_details.id', 'DESC');
            }

            $store = $query->get();

        // Get checkbox preferences
        $Checkbox = CheckBox::where('userID', auth()->user()->id)->where('tableID', 2310)->get();
        $Checkbox_Arr = $Checkbox->pluck('CheckBox')->toArray();

        // Define all possible columns with their labels
        $allColumns = [
            'SL. No.' => 'SL. No.',
            'Creater Name' => 'Creater Name',
            'Date & Time' => 'Date & Time',
            'Material' => 'Material',
            'UOM' => 'UOM',
            'Purchase Date' => 'Purchase Date',
            'Purchase Qty' => 'Purchase Qty',
            'Status' => 'Status',
            'Pending With' => 'Pending With',
        ];

        // Determine which columns to show
        $columnsToShow = empty($Checkbox_Arr) ? array_keys($allColumns) : $Checkbox_Arr;

        $exportData = [];
        
        foreach ($store as $key => $val) {
            // Set pending with based on status
            if ($val->Forward_Status != 1) {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="23" AND step="' . $val->Approve_Step . '")')->get();
            } else {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data` WHERE DataID="' . $val->id . '" AND DepartmentID=23 AND status=0)')->get();
            }

            // Load related data
            $val->user = Admin::find($val->userID);
            $val->Organization_Name = prj_organisation::find($val->Organization_Name);
            $val->Manufacturing_Unit = prj_project::find($val->Manufacturing_Unit);
            $val->Plant_Name = Prj_Subproject::find($val->Plant_Name);
            $val->Godown_Name = Prj_Inventory::find($val->Godown_Name);
            $val->HoldStatus = Mrn_Stock_Transfer_Approve::where('tr_id', $val->id)
                ->where('action', 'HOLD')
                ->where('status', 1)
                ->where('userID', auth()->user()->id)
                ->count();

            // Get material details
            $val->Material = MaterialManagement_Add_Material::select(
                'materialmanagement_add_material.*',
                'prj_material.material_name as matname'
            )
            ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
            ->where('materialmanagement_add_material.id', $val->Material)
            ->groupBy('materialmanagement_add_material.id')
            ->first();

            // Determine transfer status
            $transfersts = ($val->trn_status == '0') ? "Not Transfer" : "Transfer";

            // Get pending with names
            $pendingWithNames = [];
            if ($val->PendingWith != null) {
                foreach ($val->PendingWith as $name) {
                    if (isset($name->fullname) && $name->fullname != '') {
                        $pendingWithNames[] = $name->fullname;
                    }
                }
            }

            // Determine pending with text
            $pendingWithText = '';
            if ($val->Approve_status === 'FORWARD' || ($val->Approve_status == '' && isset($val->status) && $val->status != 1)) {
                $pendingWithText = 'Pending With ' . implode(', ', $pendingWithNames);
            } elseif ($val->Approve_status === 'RECHECK' || $val->Approve_status === 'OBJECT') {
                $pendingWithText = (isset($val->user->fullname) && $val->user->fullname != '') ? 'Pending With ' . $val->user->fullname : '';
            }

            // Build row data with all possible columns
            $rowData = [
                "SL. No." => $key + 1,
                "Creater Name" => $val->user->fullname ?? '',
                "Date & Time" => $val->created_at ? date('d-m-Y H:i:s A', strtotime($val->created_at)) : '',
                "Material" => $val->Material->matname ?? '',
                "UOM" => $val->Material->UOM ?? '',
                "Purchase Date" => $val->Mrn_Date ?? '',
                "Purchase Qty" => $val->Quantity ?? '',
                "Status" => ($val->Approve_status == 'APPROVE') ? 'APPROVED' : (($val->Approve_status == 'REJECT') ? 'REJECTED' : (($val->Approve_status == 'RECHECK') ? 'RECHECK' : (($val->Approve_status == 'OBJECT') ? 'OBJECT' : (($val->Approve_status == 'HOLD') ? 'HOLD' : 'Pending')))),
                "Pending With" => $pendingWithText,
            ];

            // Filter row data based on selected columns
            $filteredRow = [];
            foreach ($columnsToShow as $column) {
                if (array_key_exists($column, $rowData)) {
                    $filteredRow[$column] = $rowData[$column];
                }
            }

            $exportData[] = $filteredRow;
        }

        // Ensure we always have data to export, even if empty
        if (empty($exportData)) {
            // Create empty row with selected columns
            $emptyRow = [];
            foreach ($columnsToShow as $column) {
                $emptyRow[$column] = '';
            }
            $exportData[] = $emptyRow;
        }

        $file = "StoreTransfer_data.csv";
        $this->collectionExport($exportData, $file);
    }
     public function collectionExport($d, $file)
    {
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=" . $file);

        $fp = fopen('php://output', 'w');
        $header = null;
        foreach ($d as $k => $row1) {

            if (!$header) {

                fputcsv($fp, array_keys($row1));
                fputcsv($fp, $row1);
                $header = true;
            } else {
                fputcsv($fp, $row1);
            }
        }
        fclose($fp);
    }
}
