<?php

namespace App\Http\Controllers\SerialNumber;

use App\Http\Controllers\Controller;
use App\Models\PlantStock;
use Illuminate\Http\Request;
use App\Models\Production\{Production_For_Sales, Production_For_Stock,ProductionData,ProductionBatch,Production,ProductionApprove};
use App\Models\SerialNumber\{FactorySerialNumber,FactorySerialNumberDetail};
use App\Models\StoreTransfer\{Mrn_Stock_Transfer,Mrn_Stock_Transfer_Detail,Mrn_Stock_Transfer_Approve};
use App\Models\FinishedGood\{FinishedGoodGatepass,FinishedGoodGatepassApprove,Finished_good_gatepasses_detail};

use Session;

class SerailNumberController extends Controller
{
    public function store(Request $request)
        {
            // return $request->all();
            $orgname = $request->org_name;
            $chk_tpcon = trim($request->TPCON) == 'YES' ? 'TG' : '';
            $orgCodes = [
                "Surya International" => "SS",
                "Suryam International Private Limited" => "SM",
                "Wattplus Energy Pvt Ltd" => "WE",
                "Surya International Enterprise Private Limited" => "SI",
                "Surya International Technology Private Limited" => "ST",
                "IYRO INDUSTRIES PRIVATE LIMITED" => "IY",
                "Moneymela Fintech pvt. Ltd" => "MM",
                "Solarative solution pvt ltd" => "SR",
                "WATTPLUS INFRA PVT LTD" => "WI",
                "SURYA CEF JV" => "SC",
                "Surya Infrastructure" => "SF",
                "ASVA INNOVATION" => "AI",
                "SIIPL-SIEPL JV" => "SJ"
            ];

            // Default to an empty string if the organization name is not found
            $orgcode = $orgCodes[$orgname] ?? '';
            $fgWatt = $request->fg_watt;
            $busBar = $request->bus_bar;
            $serialDate = $request->serial_date;
            $shiftName = $request->Shift_Name;
            $slNoFrom = ltrim($request->sl_no_from, '0');
            $slNoTo = ltrim($request->sl_no_to, '0');

            // Extract the necessary parts
            $dateParts = date("ymd", strtotime($serialDate));
            $chk_busBar = $request->TPCON == 'NO' ? $busBar : '';
            $staticPart = $orgcode. $chk_tpcon . $fgWatt . $chk_busBar . $dateParts . $shiftName;

            // Generate the serial numbers
            $serialNumbers = [];
            for ($i = $slNoFrom; $i <= $slNoTo; $i++) {
                $serialNumbers[] = $staticPart . str_pad($i, 4, '0', STR_PAD_LEFT);
            }
            //return $serialNumbers;

            // Debug: Print generated serial numbers
            logger('Generated Serial Numbers: ' . implode(', ', $serialNumbers));

            // Check serial numbers across all three tables with detailed conflict tracking
            $conflictDetails = [];

            // 1. Check factory_serial_numbers table (excluding current record if editing)
            $currentFactoryId = null;
            if (isset($request->edit) && $request->edit != '') {
                $currentFactoryId = $request->edit;
            }

            $factorySerials = FactorySerialNumberDetail::whereIn('factory_serial_number_details.sl_no', $serialNumbers)
                ->leftJoin('factory_serial_numbers', 'factory_serial_number_details.sl_id', '=', 'factory_serial_numbers.id')
                ->where(function($query) {
                    $query->where('factory_serial_numbers.Approve_status', '!=', 'REJECT')
                        ->orWhereNull('factory_serial_numbers.Approve_status');
                })
                ->when($currentFactoryId, function($query) use ($currentFactoryId) {
                    return $query->where('factory_serial_number_details.sl_id', '!=', $currentFactoryId);
                })
                ->pluck('factory_serial_number_details.sl_no')
                ->toArray();

            if (!empty($factorySerials)) {
                $conflictDetails['Factory Serial Numbers'] = $factorySerials;
            }

            // 2. Check mrn_stock_transfer table
            $mrnSerials = Mrn_Stock_Transfer_Detail::whereIn('mrn_stock_transfer_details.serial_no', $serialNumbers)
                ->leftJoin('mrn_stock_transfer', 'mrn_stock_transfer_details.mrn_st_id', '=', 'mrn_stock_transfer.id')
                ->where(function($query) {
                    $query->where('mrn_stock_transfer.Approve_status', '!=', 'REJECT')
                        ->orWhereNull('mrn_stock_transfer.Approve_status');
                })
                ->pluck('mrn_stock_transfer_details.serial_no')
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

                return redirect()->back()
                    ->withInput()
                    ->withErrors(['serial_no' => $errorMessage]);
            }

            // Original logic for handling factory serial numbers (keeping existing logic intact)
            // Check if serial numbers already exist for the given serialDate
            $existingSerialNumbers = FactorySerialNumberDetail::select('sl_no', 'sl_id')
                ->whereIn('sl_no', $serialNumbers)
                ->get();
            $checkdate = null;
            foreach ($existingSerialNumbers as $val) {
                $checkdate = FactorySerialNumber::select('serial_date', 'Approve_status', 'id')
                    ->where('id', $val->sl_id)
                    ->orderBy('id', 'DESC')
                    ->first();
            }

            if ($checkdate) {
                if ($checkdate->Approve_status == 'REJECT') {
                    // If status is REJECT, continue to the next record
                    if (isset($request->edit) && $request->edit != '') {
                        // $prod = Production::where('id', $request->edit)->first();
                        // Production::where('id', $request->edit)->update(['Approve_status' => null]);
                        // ProductionApprove::where('productionID', $request->edit)->where('status', 1)->update(['status' => 0]);
                    } else {
                        $prod = new FactorySerialNumber;
                        $prod->userID = auth()->user()->id;
                        if (!isset($request->draft)) {
                            $prod->Approve_Step = 1;
                        }
                    }
                    $prod->status = 0;
                    $prod->Organization_Name = $request->Organization_Name;
                    $prod->fg_watt = $request->fg_watt;
                    $prod->bus_bar = $request->bus_bar;
                    $prod->serial_date = $request->serial_date;
                    $prod->Shift_Name = $request->Shift_Name;
                    $prod->sl_no_from = $request->sl_no_from;
                    $prod->sl_no_to = $request->sl_no_to;
                    $prod->remarks = $request->remarks ?? '';
                    $prod->TPCON = $request->TPCON ?? '';
                    $prod->save();
                    $insert_id = $prod->id;
                    $input = $request->input();
                    if (isset($serialNumbers)) {
                        foreach ($serialNumbers as $key => $value) {
                            $prod_data = new FactorySerialNumberDetail;
                            $prod_data->sl_id = $insert_id;
                            $prod_data->sl_no = $value;
                            $prod_data->save();
                        }
                    }
                    return redirect('SerialNumber/SerialnumberList')->with('success', 'Added Successfully....');
                } elseif (!empty($checkdate->serial_date)) {
                    // If status is not REJECT and serial_date is present, set $insert to false
                    return redirect()->back()->withErrors(['Serial numbers already exist for the given serial date.']);
                }
            } else {
                if (isset($request->edit) && $request->edit != '') {
                    // $prod = Production::where('id', $request->edit)->first();
                    // Production::where('id', $request->edit)->update(['Approve_status' => null]);
                    // ProductionApprove::where('productionID', $request->edit)->where('status', 1)->update(['status' => 0]);
                } else {
                    $prod = new FactorySerialNumber;
                    $prod->userID = auth()->user()->id;
                    if (!isset($request->draft)) {
                        $prod->Approve_Step = 1;
                    }
                }
                $prod->status = 0;
                $prod->Organization_Name = $request->Organization_Name;
                $prod->fg_watt = $request->fg_watt;
                $prod->bus_bar = $request->bus_bar;
                $prod->serial_date = $request->serial_date;
                $prod->Shift_Name = $request->Shift_Name;
                $prod->sl_no_from = $request->sl_no_from;
                $prod->sl_no_to = $request->sl_no_to;
                $prod->remarks = $request->remarks ?? '';
                $prod->TPCON = $request->TPCON ?? '';
                $prod->save();
                $insert_id = $prod->id;
                $input = $request->input();
                
                if (isset($serialNumbers)) {
                    foreach ($serialNumbers as $key => $value) {
                        $prod_data = new FactorySerialNumberDetail;
                        $prod_data->sl_id = $insert_id;
                        $prod_data->sl_no = $value;
                        $prod_data->save();
                    }
                }
                return redirect('SerialNumber/SerialnumberList')->with('success', 'Added Successfully....');
            }
        }

    public function store1(Request $request)
    {
        //return $request->all();
        $prod = new FactorySerialNumber;
        if ($request->file('demo_attach') != '') {
            $name = $request->file('demo_attach')->getClientOriginalName();
            $prod->demo_attach = $request->file('demo_attach')->storeAs('DemoAttach', $name, 'public');

        }
        $prod->save();
    }


    
    public function AddSales(Request $request)
    {
        if ($request->edit) {
            $Sales = Production_For_Sales::find($request->edit);
        } else {
            $Sales = new Production_For_Sales;
            $Sales->userID = auth()->user()->id;
        }
        $Sales->Work_Order_Type = 'Sales';
        $Sales->Sales_Order_No = $request->Sales_Order_No;
        $Sales->Organization = $request->Organization;
        $Sales->BU = $request->BU;
        $Sales->Company_Name = $request->Company_Name;
        $Sales->Expected_Date = $request->Expected_Date;
        $Sales->Unit_Name = $request->Unit_Name;
        $Sales->Plant_Name = $request->Plant_Name;
        $Sales->Work_Order_Status = $request->Work_Order_Status;
        $Sales->remarks = $request->remarks;

        $Sales->save();

        $Sales->Work_Order_No = 'WON' . str_pad($Sales->id, 4, '0', STR_PAD_LEFT) . 'SALES';
        $Sales->Work_Order_Name = 'WON' . str_pad($Sales->id, 4, '0', STR_PAD_LEFT) . 'SALES';
        $Sales->save();

        return redirect('Production/ProductionList')->with('success', 'Added Successfully....');
    }

    public function AddStock(Request $request)
    {
        if ($request->edit) {
            $Stock = Production_For_Stock::find($request->edit);
        } else {
            $Stock = new Production_For_Stock;
            $Stock->userID = auth()->user()->id;
        }
        $Stock->Work_order_Type = 'Stock';
        $Stock->Stock_Order_No = $request->Stock_Order_No;
        $Stock->Organization = $request->Organization;
        $Stock->BU = $request->BU;
        $Stock->Company_Name = $request->Company_Name;
        $Stock->Expected_Date = $request->Expected_Date;
        $Stock->Unit_Name = $request->Unit_Name;
        $Stock->Plant_Name = $request->Plant_Name;
        $Stock->Work_Order_Status = $request->Work_Order_Status;
        $Stock->remarks = $request->remarks;

        $Stock->save();

        $Stock->Work_Order_No = 'WON' . str_pad($Stock->id, 4, '0', STR_PAD_LEFT) . 'STOCK';
        $Stock->Work_Order_Name = 'WON' . str_pad($Stock->id, 4, '0', STR_PAD_LEFT) . 'STOCK';
        $Stock->save();

        return redirect('Production/ProductionList')->with('success', 'Added Successfully....');
    }
}
