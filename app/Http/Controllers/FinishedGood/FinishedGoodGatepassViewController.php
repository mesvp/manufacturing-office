<?php

namespace App\Http\Controllers\FinishedGood;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FinishedGood\{FinishedGoodGatepass,FinishedGoodGatepassApprove,Finished_good_gatepasses_detail};
use App\Models\Production\{Production_For_Sales, Production_For_Stock,ProductionData,Production,ProductionApprove};
use App\Models\FactoryCreater\{Factory_Product, Factory_Sub_Product, Factory_Sub_Sub_Product, Factory_Organisation,Factory_Uom,Factory_Address_Detail,prj_organisation,Factory_Master_Shift};
use App\Models\Master\Plant\{Master_Company_Name, Master_Manufacturing_unit, Master_BU, Master_Work_Order_Status};
use App\Models\Master\{Master_Plant_Machinery,Prj_Subproject,Prj_Project,Module_Bsns_Unit,Prj_Inventory,Pur_Address};
use App\Models\orderRequirement\{Order_Requirement_Sales, Order_Requirement_Stock};
use App\Models\{CheckBox, Admin,PlantStock};
use App\Models\BOM\{BOM, BOM_Material};
use App\Models\MaterialManagement\{MaterialManagement_Add_Material};
use App\Models\ProductCategories\{ProductCategories_Add_Product, ProductCategories_Add_Product_Other, ProductCategories_Approve};
use App\Models\SerialNumber\{FactorySerialNumber,FactorySerialNumberDetail,FactorySerialApprove};
use Session;

class FinishedGoodGatepassViewController extends Controller
{
    public function view($id)
    {
        $Organization = prj_organisation::all();
        $BU = Module_Bsns_Unit::all();
        $Manufacturing_unit = Prj_Project::all();
        $plant_name = Prj_Subproject::all();
        $MAT_DATA = ProductCategories_Add_Product::where('Approve_status', 'APPROVE')->get();
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
        $finishedgooddetails=Finished_good_gatepasses_detail::select('finished_good_gatepasses_details.*','prj_supplier.supplier_name as Supplier','prj_supplier.id as supplier_id')
                ->leftjoin('prj_supplier','finished_good_gatepasses_details.supplier_id','=','prj_supplier.id')
                            ->where('fg_id', $id)->get();
        $Filtered_Array = array_values($Raw_Material);
        $UOM = Factory_Uom::all();
        $Godown_Name=prj_inventory::all();
        $edit = FinishedGoodGatepass::find($id);
        $uname = Admin::where('id', $edit->userID)->value('fullname');
        return view('FinishedGood/FinishedGoodView', ['UOM'=>$UOM,'Raw_Material'=>$Filtered_Array,'Organization' => $Organization,'plant_name' => $plant_name,'Manufacturing_unit'=>$Manufacturing_unit,'edit'=>$edit,'id'=>$id,'uname'=>$uname,'finishedgooddetails'=>$finishedgooddetails,'Godown_Name'=>$Godown_Name]);
    }
    public function approverview($id)
    {
        $Organization = prj_organisation::all();
        $BU = Module_Bsns_Unit::all();
        $Manufacturing_unit = Prj_Project::all();
        $plant_name = Prj_Subproject::all();
        $MAT_DATA = ProductCategories_Add_Product::where('Approve_status', 'APPROVE')->get();
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
        $finishedgooddetails=Finished_good_gatepasses_detail::select('finished_good_gatepasses_details.*','prj_supplier.supplier_name as Supplier','prj_supplier.id as supplier_id')
                ->leftjoin('prj_supplier','finished_good_gatepasses_details.supplier_id','=','prj_supplier.id')
                            ->where('fg_id', $id)->get();
        $Filtered_Array = array_values($Raw_Material);
        $UOM = Factory_Uom::all();
        $edit=FinishedGoodGatepass::find($id);
        $Godown_Name=prj_inventory::all();
        $uname = Admin::where('id', $edit->userID)->value('fullname');
        return view('FinishedGood/approveview', ['UOM'=>$UOM,'Raw_Material'=>$Filtered_Array,'Organization' => $Organization,'plant_name' => $plant_name,'Manufacturing_unit'=>$Manufacturing_unit,'edit'=>$edit,'id'=>$id,'uname'=>$uname,'finishedgooddetails'=>$finishedgooddetails,'Godown_Name'=>$Godown_Name]);

        // $type=1;
        // return view('FinishedGood/approveview',compact('id','type'));
    }
    public function trail(Request $request) {
        $edit=FinishedGoodGatepass::find($request->id);
        $approves=FinishedGoodGatepassApprove::where('FinishedGoodID',$request->id)->get();
        $admin=Admin::all_admin();
        return view('FinishedGood/trail',compact('edit','approves','admin'));
    }
    public function action(Request $request) {
        $edit=FinishedGoodGatepass::find($request->id);

            $employeeName=Admin::where('role',1)->get();
            return view('FinishedGood/approveraction',compact('edit','employeeName'));

    }
    public function inputeraction(Request $request) {
        $edit=FinishedGoodGatepass::find($request->id);

            $employeeName=Admin::where('role',1)->get();
            return view('FinishedGood/inputeraction',compact('edit','employeeName'));

    }

    public function ExportFinishedGoodView($id)
    {
        ini_set('memory_limit', '-1');
        $d = [];
        $FinishedGood=FinishedGoodGatepass::find($id);
        $org_name=prj_organisation::where('id', $FinishedGood->Organization_Name)->value('organisation');
        $Manufacturing_unit = Prj_Project::where('id', $FinishedGood->Unit_Name)->value('pname');
        $plant_name = Prj_Subproject::where('id', $FinishedGood->Plant_Name)->value('spname');
        $RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                    ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                    ->where('materialmanagement_add_material.id',$FinishedGood->Material_id)
                    ->first();
		$uname = Admin::where('id', $FinishedGood->userID)->value('fullname');
            $rowData = [
                "Req No." => $FinishedGood->uniqID??'',
				"Creator Name" => $uname??'',
                "Unit Name" => $Manufacturing_unit??'',
                "Plant Name" => $plant_name??'',
                "Organization Name" => $org_name??'',
				"Transaction Date" => isset($FinishedGood->Transaction_Date) ? date('d-m-Y', strtotime($FinishedGood->Transaction_Date)) : '',
                "Material Name" =>$RawMaterial->matname??'',
                "HSN Code" => $FinishedGood->HSN_Code??'',
                "UOM" => $FinishedGood->UOM??'',
                "Rate" => $FinishedGood->Rate??'',
                "Quantity" => $FinishedGood->Quantity??'',
                "GST" => $FinishedGood->GST??'',
                "Total Amount" => $FinishedGood->Total_amount??'',
                "Status" => $FinishedGood->Approve_status==null?'PENDING':$FinishedGood->Approve_status,
                "Pending With" => Pending_With(22,$FinishedGood)??'',
                "Creation Date & Time" => isset($FinishedGood->created_at) ? date('d-m-Y h:i A', strtotime($FinishedGood->created_at)) : ''
            ];
            $d[] = $rowData;
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
}
