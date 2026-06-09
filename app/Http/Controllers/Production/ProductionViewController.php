<?php

namespace App\Http\Controllers\Production;

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


class ProductionViewController extends Controller
{
     public function ProductionList(Request $request,$export=0)
    {
        $admindata=Admin::all_admin();
        $Organization = prj_organisation::all();
        $orgdata=prj_organisation::all_org();
        $BUdata = Module_Bsns_Unit::all_bu();
        $BU = Module_Bsns_Unit::all();
        $Manufacturing_unit = Prj_Project::all();
        $Manufacturing_unitdata = Prj_Project::all_mu();
        $plant_name = Prj_Subproject::all();
        $plant_namedata = Prj_Subproject::all_pm();
        
        // Get shift data
        $shifts = Factory_Master_Shift::all();
        $shiftdata = [];
        foreach ($shifts as $shift) {
            $shiftdata[$shift->shift_code] = $shift->shift;
        }
        $BOM_DATA = BOM::where('Approve_status', 'APPROVE')->get();
        $Raw_Material = [];
        $Raw_Materialdata = [];
        foreach ($BOM_DATA as $Val) {
            if (isset($Val->Raw_Material_FG)) {
                //$Val->RawMaterial = MaterialManagement_Add_Material::find($Val->Raw_Material_FG);
                $Val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                    ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                    ->where('materialmanagement_add_material.id',$Val->Raw_Material_FG)
                    ->first();

                $Raw_Material[$Val->Raw_Material_FG] = $Val;
                $Raw_Materialdata[$Val->Raw_Material_FG]= $Val->RawMaterial->matname;
            }
        }
        $Filtered_Array = array_values($Raw_Material);
        // $UOM = Factory_Uom::all();

        /////////////////
        $EXT = Session::get('EXT');
        if (isset($EXT[17]['inputer'])) {
            $query = Production::orderBy('id', 'DESC');
        } else {
            $query = Production::where('status', 0)->orderBy('id', 'DESC');
        }
        if(isset($request->typeaction))
        {
            if($request->typeaction=='Pendings')
            {
                $query= $query->where('Approve_status',null);
            }
            elseif($request->typeaction=='ALL')
            {
                
            }
            else{
                $query= $query->where('Approve_status',$request->typeaction);
            }

        }
        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = null;
        if (!empty($dateto)) {
            $todate = date('Y-m-d', strtotime('+1 day', strtotime($dateto)));
        }
        if (!empty($fromdate) && !empty($todate)) {
            $query->whereBetween('created_at', [$fromdate, $todate]);
        }
        if(isset($request->Unit_Name) && $request->Unit_Name!='')
        {
            $query= $query->where('Unit_Name',$request->Unit_Name);
        }
        if(isset($request->Plant_Name) && $request->Plant_Name!='')
        {
            $query= $query->where('Plant_Name',$request->Plant_Name);
        }
        if(isset($request->Organization) && $request->Organization!='')
        {
            $query= $query->where('Organization_Name',$request->Organization);
        }
        if(isset($request->BU) && $request->BU!='')
        {
            $query= $query->where('BU_Name',$request->BU);
        }
        if(isset($request->shift) && $request->shift!='')
        {
            $query= $query->where('Shift',$request->shift);
        }
        if(isset($request->Production_Date) && $request->Production_Date!='')
        {
            $query= $query->where('Production_Date',$request->Production_Date);
        }
        if(isset($request->Raw_Material) && $request->Raw_Material!='')
        {
            $query= $query->where('Raw_Material',$request->Raw_Material);
        }
        
        // Return lightweight table rows only for AJAX tab refreshes.
        $isAjaxTabRequest = $request->ajax() && $request->isMethod('post') && isset($request->typeaction);

        // Use ->get() for export and AJAX tab requests, pagination for normal view.
        if($export==1 || $isAjaxTabRequest)
        {
            $production=$query->get();
        }
        else
        {
            $production=$query->paginate(10)->appends(request()->query());
        }
        
        if($export==1)
        {
            return compact('Organization','BU','Manufacturing_unit','plant_name','production','Filtered_Array','admindata','orgdata','BUdata','Manufacturing_unitdata','plant_namedata','Raw_Materialdata','shifts','shiftdata');
        }
        if($isAjaxTabRequest)
        {
            return view('Production/table',compact('Organization','BU','Manufacturing_unit','plant_name','production','Filtered_Array','admindata','orgdata','BUdata','Manufacturing_unitdata','plant_namedata','Raw_Materialdata','shifts','shiftdata'));
        }
        
        return view('Production/ProductionList',compact('Organization','BU','Manufacturing_unit','plant_name','production','Filtered_Array','admindata','orgdata','BUdata','Manufacturing_unitdata','plant_namedata','Raw_Materialdata','shifts','shiftdata'));
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
    public function AddProductionPage($id = null)
    {
        // $Product = Factory_Product::all();
        // $Sub_Product = Factory_Sub_Product::all();
        // $Sub_Sub_Product = Factory_Sub_Sub_Product::all();
        $Organization = prj_organisation::all();
        $BU = Module_Bsns_Unit::all();
        //$Manufacturing_unit = Master_Manufacturing_unit::all();
        $Manufacturing_unit = Factory_Address_Detail::select('prj_project.*')
        ->leftJoin('prj_project','factory_address_details.name_of_unit','=','prj_project.id')
        ->where('Approve_status','APPROVE')
        ->groupBy('prj_project.pname')
        ->get(); 
        $Shift=Factory_Master_Shift::all();

        $plant_name = Prj_Subproject::all();
        // $Company_Name = Master_Company_Name::all();
        // $Work_Order_Status = Master_Work_Order_Status::all();
        // $Sales_Order_No = Order_Requirement_Sales::all();
        // $Stock_Order_No = Order_Requirement_Stock::all();

        // $editSales = Production_For_Sales::find($id);
        // $editStock = Production_For_Stock::find($id);
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

        return view('Production/Production', ['UOM'=>$UOM,'Raw_Material'=>$Filtered_Array,'Organization' => $Organization, 'BU' => $BU,  'plant_name' => $plant_name,'Manufacturing_unit'=>$Manufacturing_unit,'edit'=>$edit,'Shift'=>$Shift]);
    }

    public function delete_Sales($id)
    {
        Production_For_Sales::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function delete_Stock($id)
    {
        Production_For_Stock::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
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
    public function ExportProduction(Request $request)
    {
        ini_set('memory_limit', '-1');
        //$employeedata = Gatepass_Employee::orderBy('id', 'DESC')->get();
        $Checkbox = CheckBox::where('userID', auth()->user()->id)->where('tableID', 3011)->get();
        $Checkbox_Arr = [];
        foreach ($Checkbox as $val) {
            $valuee = $val->CheckBox;
            array_push($Checkbox_Arr, $valuee);
        }
        $d = [];
        $production=$this->ProductionList($request,$export=1);
        //extract($production);
        // pre($production['production']);
        // die;
         
        foreach ($production['production'] as $key => $value) {
            $rowData = [
                "SL. No." => $key + 1,
                "Creator Name" => $production['admindata'][$value->userID]??'',
                "Manufacturing Unit" =>  $production['Manufacturing_unitdata'][$value->Unit_Name]??'',
                 "Plant Name" =>$production['plant_namedata'][$value->Plant_Name]??'',
                 "Organization Name" => $production['orgdata'][$value->Organization_Name]??'',
                 "BU Name" => $production['BUdata'][$value->BU_Name]??'',
                 "Shift" => $production['shiftdata'][$value->Shift]??$value->Shift,
                 "Production Date" =>$value->Production_Date??'',
                 "Finished Good(FG)" =>$production['Raw_Materialdata'][$value->Raw_Material]??'',
                 "UOM" => $value->UOM??'',
                 "Rate" => $value->Rate??'',
                 "Quantity" =>$value->Quantity??'',
                 "Batch No" => get_batch($value->id)??'',
                 "Date & Time" => $value->created_at??'',
                 "Status" => $value->Approve_status==null?'PENDING':$value->Approve_status,
                 "Pending With" => Pending_With(17,$value)??'',
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
        $file = "Productiondata".date("d-m-Y").".csv";
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
    public function getserialnumberdetails($id, Request $request)
    {
        $shiftcode=$request->shiftid;
        $assetdetails=FactorySerialNumber::select('factory_serial_number_details.*')
            ->leftJoin('factory_serial_number_details','factory_serial_numbers.id','=','factory_serial_number_details.sl_id')
            ->where('factory_serial_numbers.Shift_Name',$shiftcode)
            ->where('factory_serial_numbers.serial_date',$id)
            ->where('factory_serial_numbers.Approve_status','APPROVE')
            ->get();
        //print_r($assetdetails);
        return response()->json($assetdetails);
    }
}
