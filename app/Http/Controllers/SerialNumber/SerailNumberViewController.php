<?php

namespace App\Http\Controllers\SerialNumber;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Production\{Production_For_Sales, Production_For_Stock,ProductionData,ProductionBatch,Production,ProductionApprove};
use App\Models\FactoryCreater\{Factory_Product, Factory_Sub_Product, Factory_Sub_Sub_Product, Factory_Organisation,Factory_Uom,Factory_Address_Detail,prj_organisation,Factory_Master_Shift};
use App\Models\Master\Plant\{Master_Company_Name, Master_Manufacturing_unit, Master_BU, Master_Work_Order_Status};
use App\Models\Master\{Master_Plant_Machinery,Prj_Subproject,Prj_Project,Module_Bsns_Unit,Prj_Inventory,Pur_Address};
use App\Models\orderRequirement\{Order_Requirement_Sales, Order_Requirement_Stock};
use App\Models\{CheckBox, Admin,PlantStock};
use App\Models\BOM\{BOM, BOM_Material};
use App\Models\MaterialManagement\{MaterialManagement_Add_Material};
use App\Models\SerialNumber\{FactorySerialNumber,FactorySerialNumberDetail,FactorySerialApprove};
use Session;


class SerailNumberViewController extends Controller
{
    public function SerialnumberList(Request $request)
    {
        $EXT = Session::get('EXT');

        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));

        if (isset($EXT[5]['inputer'])) {
            $query = FactorySerialNumber::orderBy('id', 'DESC');
        } else {
            $query = FactorySerialNumber::where('status', 0)->orderBy('id', 'DESC');
        }

        if ($fromdate && $todate) {
            $query->whereBetween('serial_date', [$fromdate, $todate]);
        }



        $serailnumber = $query->get();

        $SerialList_arr = [];
        $approved = [];
        $REJECT = [];
        $RECHECK = [];
        $OBJECT = [];
        $HOLD = [];
        $pending = [];
        foreach ($serailnumber as $val) {
            if ($val->Forward_Status != 1) {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="8" AND step="' . $val->Approve_Step . '")')->get();
            } else {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data` WHERE DataID="' . $val->id . '" AND DepartmentID=8 AND status=0)')->get();
            }
            $val->user = Admin::find($val->userID);
            $val->HoldStatus = FactorySerialApprove::where('Product_id', $val->id)->where('action', 'HOLD')->where('status', 1)->where('userID', auth()->user()->id)->count();
         
            $SerialList_arr[] = $val;

            if ($val->Approve_status == 'APPROVE') {
                $approved[] = $val;
            } elseif ($val->Approve_status == 'REJECT') {
                $REJECT[] = $val;
            } elseif ($val->Approve_status == 'RECHECK') {
                $RECHECK[] = $val;
            } elseif ($val->Approve_status == 'OBJECT') {
                $OBJECT[] = $val;
            } elseif ($val->Approve_status == 'HOLD') {
                $HOLD[] = $val;
            } else {
                $pending[] = $val;
            }
        }
        
        $Product = Factory_Product::all();
        $Sub_Product = Factory_Sub_Product::all();
        $Sub_Sub_Product = Factory_Sub_Sub_Product::all();
        $Organization = Factory_Organisation::all();
        $Manufacturing_Unit = Master_Manufacturing_unit::all();
        $BU = Master_BU::all();
        $Plant_Name = Master_Plant_Machinery::all();

        $Dropdown = FactorySerialNumber::orderBy('id', 'DESC')->get();
        $Dropdown_arr = array();
        foreach ($Dropdown as $val) {
            $val->user = Admin::find($val->userID);
            $val->product = Factory_Product::find($val->Product);
            $val->subproduct = Factory_Sub_Product::find($val->Sub_Product);
            $val->subsubproduct = Factory_Sub_Sub_Product::find($val->Sub_Sub_Product);
            $val->Organization_Name = Factory_Organisation::find($val->Organization_Name);
            $val->Manufacturing_Unit = Master_Manufacturing_unit::find($val->Manufacturing_Unit);
            $val->BU = Master_BU::find($val->BU);
            $val->Plant_Name = Master_Plant_Machinery::find($val->Plant_Name);

            array_push($Dropdown_arr, $val);
        }

        return view('SerialNumber/SerailNumberList', ['SerialList' => $SerialList_arr, 'DropdownData' => $Dropdown_arr, 'approved' => $approved, 'REJECT' => $REJECT, 'RECHECK' => $RECHECK, 'OBJECT' => $OBJECT, 'HOLD' => $HOLD, 'pending' => $pending, 'fromdate' => $fromdate, 'todate' => $dateto, 'Product' => $Product, 'Sub_Product' => $Sub_Product, 'Sub_Sub_Product' => $Sub_Sub_Product, 'Organization' => $Organization, 'Manufacturing_Unit' => $Manufacturing_Unit, 'BU' => $BU, 'Plant_Name' => $Plant_Name]);
    }
    public function AddSerialNumber($id = null)
    {
        $Organization = prj_organisation::all();
        $Shift=Factory_Master_Shift::all();
        $BU = Module_Bsns_Unit::all();
        $Manufacturing_unit = Factory_Address_Detail::select('prj_project.*')
        ->leftJoin('prj_project','factory_address_details.name_of_unit','=','prj_project.id')
        ->where('Approve_status','APPROVE')
        ->groupBy('prj_project.pname')
        ->get(); 

        $previousSerialNumbers = FactorySerialNumberDetail::orderBy('id', 'desc')->take(5)->get();

        $plant_name = Prj_Subproject::all();
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
        $UOM = Factory_Uom::all();
        $edit=[];
        if(!empty($id))
        {
            $edit=Production::find($id);
        }

        return view('SerialNumber/AddSerialNumber', ['UOM'=>$UOM,'Raw_Material'=>$Filtered_Array,'Organization' => $Organization, 'BU' => $BU,  'plant_name' => $plant_name,'Manufacturing_unit'=>$Manufacturing_unit,'edit'=>$edit,'Shift'=>$Shift,'previousSerialNumbers'=>$previousSerialNumbers]);
    }
    public function AddSerialNumber1(){

        return view('SerialNumber/AddSerialNumber1');
    }
    public function SerialNumber_View($id, $type)
    {
        $appro = FactorySerialApprove::where('Product_id', $id)->get();
        $approves = [];
        foreach ($appro as $val) {
            $val->user = Admin::find($val->userID);
            array_push($approves, $val);
        }

        $product = Factory_Product::all();
        $subproduct = Factory_Sub_Product::all();
        $subsubproduct = Factory_Sub_Sub_Product::all();
        $Organization_Name = Factory_Organisation::all();
        $Manufacturing_Unit = Master_Manufacturing_unit::all();
        $BU = Master_BU::all();
        $Plant_Name = Master_Plant_Machinery::all();
        //$Raw_Material = MaterialManagement_Add_Material::where('Approve_status', 'APPROVE')->get();
        $Raw_Material = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                     ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                     ->where('Approve_status', 'APPROVE')->get();
        $UOM = Factory_Uom::all();
        //$edit = FactorySerialNumber::find($id);
        $edit=FactorySerialNumber::select('factory_serial_numbers.*','mstr_emp.fullname as empname')
                        ->leftJoin('mstr_emp','factory_serial_numbers.userID','=','mstr_emp.id')
                        ->where('factory_serial_numbers.id',$id)
                        ->first();
        $slnumbers=FactorySerialNumberDetail::select('factory_serial_number_details.*')->where('sl_id',$id)->get();
        $editother = array();
        $otherCount = 0;

        $nextID = $this->next($id, $type);

        return view('SerialNumber/SerialNumber_View', ['edit' => $edit, 'editother' => $editother, 'otherCount' => $otherCount, 'product' => $product, 'subproduct' => $subproduct, 'subsubproduct' => $subsubproduct, 'Organization_Name' => $Organization_Name, 'Manufacturing_Unit' => $Manufacturing_Unit, 'BU' => $BU, 'Plant_Name' => $Plant_Name, 'approves' => $approves, 'Raw_Material' => $Raw_Material, 'UOM' => $UOM,'slnumbers'=>$slnumbers,'nextID' => $nextID]);
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
    public function Release_Hold(Request $request, $id)
    {
        $EXT = Session::get('EXT');
        $currentDate = now();

        $approvesss = ProductionApprove::where('productionID', $id)->where('action', 'HOLD')->update(['days_for_holding' => $currentDate, 'status' => 0]);
        $factory =  Production::where('id', $id)->update(['Approve_status' => null]);

        $approve = new ProductionApprove;
        $approve->userID = auth()->user()->id;
        if (auth()->user()->role == 0) {
            $approve->role = 'Admin';
        } elseif (isset($EXT[17]['approver'])) {
            $approve->role = 'Approver';
        } elseif (isset($EXT[17]['inputer'])) {
            $approve->role = 'Inputer';
        } else {
            $approve->role = 'Viewer';
        }
        $approve->productionID = $id;
        $approve->status = 1;
        $approve->action = 'Hold Released';
        $approve->comment_text = $request->comment_text;
        $approve->ip_address = $request->getClientIp();
        $approve->device_name = $request->server('HTTP_USER_AGENT');
        $approve->save();


        return redirect('Production/ProductionList')->with('success', 'Hold Released successfully.....');
    }
    public function view($id)
    {
        $type=0;
        return view('Production/mainview',compact('id','type'));
    }
    public function action(Request $request,$type=0) {
        $edit=Production::find($request->id);
        if($type==1)
        {
            $employeeName=Admin::where('role',1)->get();
            return view('Production/approveraction',compact('edit','employeeName')); 
        }
        else{
            return view('Production/inputeraction',compact('edit'));
        }
        
    }
    public function trail(Request $request) {
        $edit=Production::find($request->id);
        $approves=ProductionApprove::where('productionID',$request->id)->get();
        $admin=Admin::all_admin();
        return view('Production/trail',compact('edit','approves','admin'));
    }
    public function formview(Request $request)
    {
      
        $Organization = prj_organisation::all();
        $BU = Module_Bsns_Unit::all();
        $Manufacturing_unit = prj_project::all();
        $plant_name = Prj_Subproject::all();
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
        $UOM = Factory_Uom::all();
        $edit=Production::find($request->id);
        $batch=ProductionBatch::where('productionID',$request->id)->get();
        return view('Production/view', ['UOM'=>$UOM,'Raw_Material'=>$Filtered_Array,'Organization' => $Organization, 'BU' => $BU,  'plant_name' => $plant_name,'Manufacturing_unit'=>$Manufacturing_unit,'edit'=>$edit,'batch'=>$batch]);
    }
    

  
    public function MaterialData(Request $request)
    {
        $BOM = BOM::where(['Raw_Material_FG' => $request->MaterialId, 'Approve_status' => 'APPROVE'])->orderBy('id', 'DESC')->first();
        $Materials = [];
        if (isset($BOM) && $BOM != '') {
            $MaterialData = BOM_Material::where('BOM_ID', $BOM->id)->get();
            foreach ($MaterialData as $Val) {
                if (isset($Val->Material)) {
                    //$Val->RawMaterial = MaterialManagement_Add_Material::find($Val->Material);
                    $Val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                    ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                    ->where('materialmanagement_add_material.id',$Val->Material)
                    ->first();

                    $plantstock= PlantStock::where(['plantID'=>$request->PlantID,'materialID'=>$Val->RawMaterial->id])->get()->first();
                    $Val->RawMaterial->UOM_name=Factory_Uom::find($Val->RawMaterial->UOM);
                    $Val->Plantstock=$plantstock->stock??0;
                    $Val->editdata=ProductionData::where(['productionID'=>$request->productionID,'RawMaterial_id'=>$Val->Material])->get()->first();
                    $Materials[] = $Val;
                }
            }
            //return $Materials;
        }
        // echo "<pre>";
        // print_r($Materials);
        // echo "</pre>";
        return view('Production/material',compact('Materials'));
    }
    public function MaterialData_view(Request $request)
    {
        $BOM = BOM::where(['Raw_Material_FG' => $request->MaterialId, 'Approve_status' => 'APPROVE'])->orderBy('id', 'DESC')->first();
        $Materials = [];
        if (isset($BOM) && $BOM != '') {
            $MaterialData = BOM_Material::where('BOM_ID', $BOM->id)->get();
            foreach ($MaterialData as $Val) {
                if (isset($Val->Material)) {
                    //$Val->RawMaterial = MaterialManagement_Add_Material::find($Val->Material);
                    $Val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                    ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                    ->where('materialmanagement_add_material.id',$Val->Material)
                    ->first();

                    $plantstock= PlantStock::where(['plantID'=>$request->PlantID,'materialID'=>$Val->RawMaterial->id])->get()->first();
                    $Val->RawMaterial->UOM_name=Factory_Uom::find($Val->RawMaterial->UOM);
                    $Val->Plantstock=$plantstock->stock??0;
                    $Val->editdata=ProductionData::where(['productionID'=>$request->productionID,'RawMaterial_id'=>$Val->Material])->get()->first();
                    $Materials[] = $Val;
                }
            }
            //return $Materials;
        }
        // echo "<pre>";
        // print_r($Materials);
        // echo "</pre>";
        return view('Production/material_view',compact('Materials'));
    }
    public function ExportData(Request $request)
    {
        $ProductList = FactorySerialNumber::select('factory_serial_numbers.*','factory_serial_numbers.Organization_Name as orgname')->get();
        $ProductList_arr = array();
        foreach ($ProductList as $val) {
            if ($val->Forward_Status != 1) {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="8" AND step="' . $val->Approve_Step . '")')->get();
            } else {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data` WHERE DataID="' . $val->id . '" AND DepartmentID=8 AND status=0)')->get();
            }
            $val->user = Admin::find($val->userID);
            $val->product = Factory_Product::find($val->Product);
            $val->subproduct = Factory_Sub_Product::find($val->Sub_Product);
            $val->subsubproduct = Factory_Sub_Sub_Product::find($val->Sub_Sub_Product);
            $val->Organization_Name = Factory_Organisation::find($val->Organization_Name);
            $val->Manufacturing_Unit = Master_Manufacturing_unit::find($val->Manufacturing_Unit);
            $val->BU = Master_BU::find($val->BU);
            $val->Plant_Name = Master_Plant_Machinery::find($val->Plant_Name);
            $val->Raw_Material = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                                    ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                                    ->where('materialmanagement_add_material.id',$val->Raw_Material)->first();
            //$val->UOM = Factory_Uom::find($val->UOM);

            array_push($ProductList_arr, $val);
        }

        $Checkbox = CheckBox::where('userID', auth()->user()->id)->where('tableID', 8)->get();

        $Checkbox_Arr = [];
        foreach ($Checkbox as $val) {
            $valuee = $val->CheckBox;
            array_push($Checkbox_Arr, $valuee);
        }

        $d = [];
        foreach ($ProductList_arr as $key => $val) {
            $rowData = [
                "SL. No." => $key + 1,
                "Creater Name" => isset($val->user->fullname) && $val->user->fullname != '' ? $val->user->fullname : '',
                "Organization Name" => $val->orgname,
                "FG Watt" => isset($val->fg_watt) && $val->fg_watt != '' ? $val->fg_watt : '',
                "Bus Bar" => isset($val->bus_bar) && $val->bus_bar != '' ? $val->bus_bar : '',
                "Serial Date" => isset($val->serial_date) && $val->serial_date != '' ? $val->serial_date : '',
                "Shift Code" => isset($val->Shift_Name) && $val->Shift_Name != '' ? $val->Shift_Name : '',
                "TPCON" => isset($val->TPCON) && $val->TPCON != '' ? $val->TPCON : '',
                "Sl No. From" => isset($val->sl_no_from) && $val->sl_no_from != '' ? $val->sl_no_from : '',
                "Sl No. TO" => isset($val->sl_no_to) && $val->sl_no_to != '' ? $val->sl_no_to : '',
                "Status" => ($val->Approve_status == 'APPROVE') ? 'APPROVED' : (($val->Approve_status == 'REJECT') ? 'REJECTED' : (($val->Approve_status == 'RECHECK') ? 'RECHECK' : (($val->Approve_status == 'OBJECT') ? 'OBJECT' : (($val->Approve_status == 'HOLD') ? 'HOLD' :
                    'Pending')))),
                "Pending With" => ($val->Approve_status === 'FORWARD' || ($val->Approve_status == '' && isset($val->status) && $val->status != 1)) ?
                    'Pending With ' . (function () use ($val) {
                        $names = [];
                        if ($val->PendingWith != null) {
                            foreach ($val->PendingWith as $name) {
                                if (isset($name->fullname) && $name->fullname != '') {
                                    $names[] = $name->fullname;
                                }
                            }
                        }
                        return implode(', ', $names);
                    })() : (($val->Approve_status === 'RECHECK' || $val->Approve_status === 'OBJECT') ?
                        (isset($val->user->fullname) && $val->user->fullname != '' ? 'Pending With ' . $val->user->fullname : '') : ''),
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

        $file = "Serialnumber_Data.csv";
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
    public function getCheckBoxData(Request $request)
    {
        $userID = auth()->user()->id;
        $id = $request->input('ID');

        $data = CheckBox::where('userID', $userID)->where('tableID', $id)->get();

        return response()->json(['success' => true, 'columns' => $data->pluck('CheckBox')]);
    }
}
