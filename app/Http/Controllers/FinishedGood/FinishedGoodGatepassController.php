<?php

namespace App\Http\Controllers\FinishedGood;

use App\Http\Controllers\Controller;
use App\Models\Master\{Master_Plant_Machinery,Prj_Subproject,Prj_Project,Module_Bsns_Unit,Prj_Inventory,Pur_Address,Crmwtp_Product_Detail,Prj_Supllier};
use App\Models\FinishedGood\{FinishedGoodGatepass,FinishedGoodGatepassApprove,Finished_good_gatepasses_detail};
use App\Models\FactoryCreater\{Factory_Uom,Factory_Address_Detail,prj_organisation,Factory_Plant_Machinery};
use App\Models\BOM\BOM;
use App\Models\Master\BOM\Master_GST_Percentage;
use App\Models\MaterialManagement\MaterialManagement_Add_Material;
use App\Models\{CheckBox, Admin, Forwarded_Data, Department_Assign};
use App\Models\ProductCategories\{ProductCategories_Add_Product, ProductCategories_Add_Product_Other, ProductCategories_Approve};
use App\Models\StoreTransfer\{Mrn_Stock_Transfer,Mrn_Stock_Transfer_Detail,Mrn_Stock_Transfer_Approve};
use App\Models\SerialNumber\{FactorySerialNumber,FactorySerialNumberDetail};

use Illuminate\Http\Request;

class FinishedGoodGatepassController extends Controller
{
    public function FinishedGood(Request $request,$id = null)
    {
        $query = FinishedGoodGatepass::orderBy('id', 'DESC');
        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));
        if ($fromdate && $todate) {
            $query->whereBetween('created_at', [$fromdate, $todate]);
        }
        if ($fromdate) {
            $query->whereBetween('created_at', [$fromdate, $todate]);
        }
		$RequestBy = '';
        if ($request->has('Request_By') && $request->input('Request_By') != '') {
            $RequestBy = $request->input('Request_By');
            if ($RequestBy !== 'all') {
                $query->where('userID', $RequestBy);
            }
        }
        if(isset($request->Organization) && $request->Organization!='')
        {
            $query= $query->where('Organization_Name',$request->Organization);
        }
        if(isset($request->Raw_Material) && $request->Raw_Material!='')
        {
            $query= $query->where('Material_id',$request->Raw_Material);
        }
		if(isset($request->Cost_Center) && $request->Cost_Center!='')
        {
            $query= $query->where('Unit_Name',$request->Cost_Center);
        }
		if(isset($request->Sub_Cost_Center) && $request->Sub_Cost_Center!='')
		{
			$query= $query->where('Plant_Name',$request->Sub_Cost_Center);
		}
        $FinishedGood=$query->get();

        $Manufacturing_unit = Factory_Address_Detail::select('prj_project.*')
        ->leftJoin('prj_project','factory_address_details.name_of_unit','=','prj_project.id')
        ->where('Approve_status','APPROVE')
        ->groupBy('prj_project.pname')
        ->get();
        $Manufacturing_unitdata = Prj_Project::all_mu();
        $plant_namedata = Prj_Subproject::all_pm();
        $admindata=Admin::all_admin();
        $empNames = Admin::where('role', 1)->get();
        $plant_name = Factory_Plant_Machinery::select('prj_subproject.*','prj_organisation.organisation','prj_organisation.id as orgid')
		->leftJoin('prj_subproject', 'factory_plant_machineries.Plant_Name', '=', 'prj_subproject.id')
		->leftJoin('factory_address_details', 'factory_plant_machineries.factory_id', '=', 'factory_address_details.id')
		->leftJoin('prj_organisation', 'factory_address_details.organization', '=', 'prj_organisation.id')
		->whereIn('factory_address_details.name_of_unit',$Manufacturing_unit->pluck('id'))
		->where('factory_address_details.Approve_status', 'APPROVE')
		->whereNotNull('prj_subproject.spname')
		->get();
        //$Godown_Name = Prj_Inventory::select('prj_inventory.*')->where('godown_type','209')->get();
        $Godown_Name = Prj_Inventory::select('prj_inventory.*')->where('godown_type','69')->get();
		// $plant_name = Prj_Subproject::whereIn('pid', $Manufacturing_unit->pluck('id'))->get();
        $UOM = Factory_Uom::all();
        $SUPPLIER = Prj_Supllier::all();
        $GST = Master_GST_Percentage::all();
        $Organization=prj_organisation::all_org();
        $Orgs=prj_organisation::all();
        $MAT_DATA = ProductCategories_Add_Product::where('Approve_status', 'APPROVE')->get();
        $Raw_Material = [];
        $Raw_Materialdata = [];
        foreach ($MAT_DATA as $Val) {
            if (isset($Val->Raw_Material)) {
                //$Val->RawMaterial = MaterialManagement_Add_Material::find($Val->Raw_Material);
                $Val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*', 'prj_material.material_name as matname')
                ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
                ->join('crmwtp_product_details', 'materialmanagement_add_material.Material_Name', '=', 'crmwtp_product_details.matrl_id')
                ->where('materialmanagement_add_material.id', $Val->Raw_Material)
                ->where('materialmanagement_add_material.Approve_status', 'APPROVE')
                ->first();
            // return $slnocheck = Crmwtp_Product_Detail::where('matrl_id', $Val->Raw_Material)
            //     ->where(function($q){
            //         $q->where('sl_no_req', 'yes');
            //     })
            //     ->first();
            if ($Val->RawMaterial) {
                $Raw_Material[$Val->Raw_Material] = $Val;
                $Raw_Materialdata[$Val->Raw_Material] = $Val->RawMaterial->matname;
            }
                // $Val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                //     ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                //     ->where('materialmanagement_add_material.id',$Val->Raw_Material)
                //     ->first();

                // $Raw_Material[$Val->Raw_Material] = $Val;
                // $Raw_Materialdata[$Val->Raw_Material]= $Val->RawMaterial->matname;
            }
        }
        $Filtered_Array = array_values($Raw_Material);

        foreach ($FinishedGood as $val) {
            $val->user = Admin::find($val->userID);
            if ($val->Forward_Status != 1) {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="22" AND step="' . $val->Approve_Step . '")')->get();
            } else {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data` WHERE DataID="' . $val->id . '" AND DepartmentID=22 AND `status`=0)')->get();
            }
        }
        // dd($val->PendingWith);
        return view('FinishedGood/FinishedGood',compact('FinishedGood','Organization','plant_name','Filtered_Array','Raw_Materialdata','Raw_Material','GST','Manufacturing_unit','Orgs','admindata','Manufacturing_unitdata','plant_namedata','RequestBy','UOM','fromdate','todate','empNames','RequestBy','SUPPLIER','Godown_Name'));
    }
    public function checkSerialRequirement(Request $request)
    {

        $materialId = $request->input('material_id');
        $prj_Material = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                    ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                    ->where('materialmanagement_add_material.id',$materialId)
                    ->first();
         $slnocheck = Crmwtp_Product_Detail::where('matrl_id', $prj_Material->Material_Name)
            ->where('sl_no_req', 'yes')
            ->first();

        if ($slnocheck) {
            return response()->json(['requires_serial' => true]);
        } else {
            return response()->json(['requires_serial' => false]);
        }
    }
    public function EditFinishedGoodGatepass(Request $request,$id){
        $Organization = prj_organisation::all();
        $Manufacturing_unit = Prj_Project::all();
        $plant_name = Prj_Subproject::all();
        $MAT_DATA = ProductCategories_Add_Product::where('Approve_status', 'APPROVE')->get();
        $GST = Master_GST_Percentage::all();
        $Raw_Material = [];
        foreach ($MAT_DATA as $Val) {
            if (isset($Val->Raw_Material)) {
                //$Val->RawMaterial = MaterialManagement_Add_Material::find($Val->Raw_Material);
                $Val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                    ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                    ->where('materialmanagement_add_material.id',$Val->Raw_Material)
                    ->first();
                $Raw_Material[$Val->Raw_Material] = $Val;
            }
        }
        $SUPPLIER = Prj_Supllier::all();
        //$Godown_Name = Prj_Inventory::select('prj_inventory.*')->where('godown_type','209')->get();
        $Godown_Name = Prj_Inventory::select('prj_inventory.*')->where('godown_type','69')->get();
        $finishedgooddetails=Finished_good_gatepasses_detail::select('finished_good_gatepasses_details.*','prj_supplier.supplier_name as Supplier','prj_supplier.id as supplier_id')
                ->leftjoin('prj_supplier','finished_good_gatepasses_details.supplier_id','=','prj_supplier.id')
                            ->where('fg_id', $id)->get();
        $Filtered_Array = array_values($Raw_Material);
        $UOM = Factory_Uom::all();
        $edit=FinishedGoodGatepass::find($id);
        $uname = Admin::where('id', $edit->userID)->value('fullname');
        return view('FinishedGood/EditFinishedGood', ['UOM'=>$UOM,'Raw_Material'=>$Filtered_Array,'Organization' => $Organization,'plant_name' => $plant_name,'Manufacturing_unit'=>$Manufacturing_unit,'GST'=>$GST,'edit'=>$edit,'id'=>$id,'uname'=>$uname,'finishedgooddetails'=>$finishedgooddetails,'SUPPLIER'=>$SUPPLIER,'Godown_Name'=>$Godown_Name]);

    }
    public function FinishedGood_store(Request $request)
        {
            // Trim and collect serial numbers from the request
            $serialNumbers = array_map('trim', $request->input('serial_no', []));

            if (count($serialNumbers) !== count(array_unique($serialNumbers))) {
                return redirect()->back()
                    ->withInput()
                    ->with('active_tab', 'formdata')
                    ->with('error', 'Duplicate serial numbers found in your submission. No records were saved.');
            }

            // Check serial numbers across all three tables
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
                    ->with('active_tab', 'formdata')
                    ->with('error', $errorMessage);
            }



            // Save the Finished Good Gatepass
            $FinishedGoodGatepass = new FinishedGoodGatepass();
            $FinishedGoodGatepass->userID = auth()->user()->id;
            $FinishedGoodGatepass->Approve_Step = 1;
            $FinishedGoodGatepass->Unit_Name = $request->Unit_Name;
            $FinishedGoodGatepass->Plant_Name = $request->Plant_Name;
            $FinishedGoodGatepass->Godown_Name = $request->Godown_Name;
            $FinishedGoodGatepass->Organization_Name = $request->Organization_Name;
            $FinishedGoodGatepass->Transaction_Date = $request->Transaction_Date;
            $FinishedGoodGatepass->Material_id = $request->Raw_Material;
            $FinishedGoodGatepass->HSN_Code = $request->HSN_Code;
            $FinishedGoodGatepass->UOM = $request->UOM;
            $FinishedGoodGatepass->Rate = $request->Rate;
            $FinishedGoodGatepass->Quantity = $request->Quantity;
            $FinishedGoodGatepass->gst = $request->gst;
            $FinishedGoodGatepass->Total_amount = $request->Total_amount;
            $FinishedGoodGatepass->remarks = $request->remarks;
            $FinishedGoodGatepass->save();

            // Generate unique ID after save
            $FinishedGoodGatepass->uniqID = 'FG' . str_pad($FinishedGoodGatepass->id, 4, '0', STR_PAD_LEFT);
            $FinishedGoodGatepass->save();

            // Insert new serial number entries
            $supplier_ids = $request->input('supplier_id', []);
            $dops = $request->input('dop', []);
            $makes = $request->input('make', []);
            $brands = $request->input('brand', []);

            foreach ($serialNumbers as $key => $serialNo) {
                if (empty($serialNo)) continue;

                $detail = new Finished_good_gatepasses_detail();
                $detail->fg_id = $FinishedGoodGatepass->id;
                $detail->serial_no = $serialNo;
                $detail->supplier_id = $supplier_ids[$key] ?? null;
                $detail->dop = $dops[$key] ?? null;
                $detail->make = $makes[$key] ?? null;
                $detail->brand = $brands[$key] ?? null;
                $detail->save();
            }

            // Add approval trail
            $approve = new FinishedGoodGatepassApprove();
            $approve->userID = auth()->user()->id;
            if (auth()->user()->role == 0) {
                $approve->role = 'Admin';
            } else {
                $approve->role = 'Inputer';
            }

            $approve->FinishedGoodID = $FinishedGoodGatepass->id;
            $approve->status = 1;

            if (!empty($request->during_approval)) {
                $approve->action = $request->during_approval;
            } elseif (!empty($request->pre_post_approval)) {
                $approve->pre_post_approval = $request->pre_post_approval;
            } else {
                $approve->action = 'Request Raised';
            }

            $approve->comment_text = $request->remarks;
            $approve->ip_address = $request->getClientIp();
            $approve->device_name = $request->server('HTTP_USER_AGENT');
            $approve->days_for_holding = $request->days_for_holding;
            $approve->Forward_To = $request->Forward_To;
            $approve->save();

            return redirect('FinishedGood/Finished_Good_List')->with('success', 'Added Successfully...');
        }


    public function updateFinishedGoodGatepass(Request $request, $id)
{
    //return $request->all();
    $FinishedGoodGatepass = FinishedGoodGatepass::findOrFail($id);

    // Collect arrays from request
    $serialNumbers = array_map('trim', $request->input('serial_no', []));
    $supplier_ids = $request->input('supplier_id', []);
    $dops         = $request->input('dop', []);
    $makes        = $request->input('make', []);
    $brands       = $request->input('brand', []);

    // 1. Check for duplicate serial numbers
    if (count($serialNumbers) !== count(array_unique($serialNumbers))) {
        return redirect()->back()
            ->withInput()
            ->with('active_tab', 'formdata')
            ->withErrors(['Duplicate serial numbers found in your submission.']);
    }

    // 2. Check for existing serials in approved/pending records across all tables (excluding current FG)
    $conflictDetails = [];

    // 2.1. Check factory_serial_numbers table
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

    // 2.2. Check mrn_stock_transfer table
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

    // 2.3. Check finished_good_gatepasses table (excluding current record)
    $finishedGoodSerials = Finished_good_gatepasses_detail::whereIn('finished_good_gatepasses_details.serial_no', $serialNumbers)
        ->leftJoin('finished_good_gatepasses', 'finished_good_gatepasses_details.fg_id', '=', 'finished_good_gatepasses.id')
        ->where('finished_good_gatepasses_details.fg_id', '!=', $id) // Exclude current record
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
            ->with('active_tab', 'formdata')
            ->withErrors([$errorMessage]);
    }

    // 3. Update main FG record
    $FinishedGoodGatepass->userID = auth()->user()->id;
    $FinishedGoodGatepass->Approve_Step = 1;
    $FinishedGoodGatepass->Unit_Name = $request->Unit_Name;
    $FinishedGoodGatepass->Plant_Name = $request->Plant_Name;
    $FinishedGoodGatepass->Organization_Name = $request->Organization;
    $FinishedGoodGatepass->Godown_Name = $request->Godown_Name;
    $FinishedGoodGatepass->Transaction_Date = $request->Transaction_Date;
    $FinishedGoodGatepass->Material_id = $request->Raw_Material;
    $FinishedGoodGatepass->HSN_Code = $request->hsn;
    $FinishedGoodGatepass->UOM = $request->UOM;
    $FinishedGoodGatepass->Rate = $request->Rate;
    $FinishedGoodGatepass->Quantity = $request->Quantity;
    $FinishedGoodGatepass->gst = $request->gst;
    $FinishedGoodGatepass->Total_amount = $request->Total_amount;
    $FinishedGoodGatepass->remarks = $request->Remarks;

    // Reset approval status
    FinishedGoodGatepass::where('id', $id)->update(['Approve_status' => null]);
    FinishedGoodGatepassApprove::where('FinishedGoodID', $id)->where('status', 1)->update(['status' => 0]);
    $FinishedGoodGatepass->save();

    // 4. Update details - delete old, insert new
    Finished_good_gatepasses_detail::where('fg_id', $id)->delete();

    foreach ($serialNumbers as $key => $serialNo) {
        if (empty($serialNo)) continue;
        $detail = new Finished_good_gatepasses_detail();
        $detail->fg_id = $id;
        $detail->serial_no = $serialNo;
        $detail->supplier_id = $supplier_ids[$key] ?? null;
        $detail->dop = $dops[$key] ?? null;
        $detail->make = $makes[$key] ?? null;
        $detail->brand = $brands[$key] ?? null;
        $detail->save();
    }

    // 5. Approval trail
    if ($FinishedGoodGatepass) {
        $approve = new FinishedGoodGatepassApprove;
        $approve->userID = auth()->user()->id;
        $EXT = session('EXT');

        if (auth()->user()->role == 0) {
            $approve->role = 'Admin';
        } elseif (isset($EXT[22]['Inputer'])) {
            $approve->role = 'Inputer';
        } elseif (isset($EXT[22]['approver'])) {
            $approve->role = 'Approver';
        } else {
            $approve->role = 'Viewer';
        }

        $approve->FinishedGoodID = $FinishedGoodGatepass->id;
        $approve->status = 0;
        $approve->action = $request->during_approval ?? $request->pre_post_approval ?? 'Verified';
        $approve->comment_text = $request->remarks;
        $approve->ip_address = $request->getClientIp();
        $approve->device_name = $request->server('HTTP_USER_AGENT');
        $approve->days_for_holding = $request->days_for_holding;
        $approve->Forward_To = $request->Forward_To;
        $approve->save();
    }

    // 6. Redirect to listing page on success
    return redirect('FinishedGood/Finished_Good_List')->with('success', 'Updated Successfully...');
}




    public function ExportFinishedGood(Request $request)
    {
        ini_set('memory_limit', '-1');
        $Checkbox = CheckBox::where('userID', auth()->user()->id)->where('tableID', 22)->get();
        $Checkbox_Arr = [];
        foreach ($Checkbox as $val) {
            $valuee = $val->CheckBox;
            array_push($Checkbox_Arr, $valuee);
        }
        $d = [];
        $FinishedGood=$this->FinishedGood($request,$export=0);
        // extract($FinishedGood);
        // pre($FinishedGood['FinishedGood']);
        // die;

        foreach ($FinishedGood['FinishedGood'] as $key => $value) {
            $rowData = [
                "SL. No." => $key + 1,
                "Creator Name" => $FinishedGood['admindata'][$value->userID]??'',
                "Manufacturing Unit" => $FinishedGood['Manufacturing_unitdata'][$value->Unit_Name]??'',
                "Plant Name" => $FinishedGood['plant_namedata'][$value->Plant_Name]??'',
                "Organization Name" => $FinishedGood['Organization'][$value->Organization_Name]??'',
                "Production Date" => isset($value->Transaction_Date) ? date('d-m-Y', strtotime($value->Transaction_Date)) : '',

                "Finished Good(FG)" =>$FinishedGood['Raw_Materialdata'][$value->Material_id]??'',
                "HSN" => $value->HSN_Code??'',
                "UOM" => $value->UOM??'',
                "QTY" => $value->Quantity??'',
                "Status" => $value->Approve_status==null?'PENDING':$value->Approve_status,
                "Pending With" => Pending_With(22,$value)??'',
                "Creation Date & Time" => isset($value->created_at) ? date('d-m-Y h:i A', strtotime($value->created_at)) : ''
            ];

            if (count($Checkbox_Arr) > 0) {
                $filteredRow = [];
                foreach ($rowData as $field => $value) {
                    if (in_array($field, $Checkbox_Arr)) {
                        $filteredRow[$field] = $value;
                    }
                }
                $d[] = $filteredRow;
            } else {
                $d[] = $rowData;
            }
        }
        //pre($d,true);
        $file = "FinishedGooddata".date("d-m-Y").".csv";
        $this->collectionExport($d, $file);
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

    public function CheckSerialNumber(Request $request)
    {
        $serial_no = trim($request->input('serial_no', ''));
        $current_id = $request->input('current_id', null);

        // If blank, always valid
        if ($serial_no === '') {
            return response()->json(['valid' => true]);
        }

        // Check in finished_good_gatepasses_detail (excluding current record if editing)
        $exists = \App\Models\FinishedGood\Finished_good_gatepasses_detail::where('serial_no', $serial_no)
            ->when($current_id, function($query) use ($current_id) {
                $query->where('fg_id', '!=', $current_id);
            })
            ->exists();

        if ($exists) {
            return response()->json([
                'valid' => false,
                'message' => 'Already exists in another record.'
            ]);
        }

        // If not found, valid
        return response()->json(['valid' => true]);
    }
}
