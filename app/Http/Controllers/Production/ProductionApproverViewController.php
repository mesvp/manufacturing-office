<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Production\{Production_For_Sales, Production_For_Stock,ProductionData,ProductionBatch,Production, ProductionApprove};
use App\Models\FactoryCreater\{Factory_Product, Factory_Sub_Product, Factory_Sub_Sub_Product, Factory_Organisation,Factory_Uom,Factory_Address_Detail,prj_organisation};
use App\Models\Master\Plant\{Master_Company_Name, Master_Manufacturing_unit, Master_BU, Master_Work_Order_Status};
use App\Models\Master\{Master_Plant_Machinery,Prj_Subproject,Prj_Project,Module_Bsns_Unit,Prj_Inventory,Pur_Address};
use App\Models\orderRequirement\{Order_Requirement_Sales, Order_Requirement_Stock};
use App\Models\{CheckBox, Admin,PlantStock,Forwarded_Data, Department_Assign};
use App\Models\BOM\{BOM, BOM_Material};
use App\Models\MaterialManagement\{MaterialManagement_Add_Material};
use App\Models\SerialNumber\{FactorySerialNumber,FactorySerialNumberDetail};
use Session;
use Illuminate\Support\Facades\DB;

class ProductionApproverViewController extends Controller
{
    public function ProductionList(Request $request)
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
        $UOM = Factory_Uom::all();
        $uom_data=Factory_Uom::all_uom();
        /////////////////
        $EXT = Session::get('EXT');
        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));

        $query = new Production;

        if ($fromdate && $todate) {
            $query->whereBetween('created_at', [$fromdate, $todate]);
        }

        if (isset($EXT[17]['Forward']) && isset($EXT[17]['approver']))
         {
            $query = $query->where(function ($query) use ($EXT) {
                $query->where('Approve_status', null)->where('Forward_Status', 0)->whereRaw("Approve_Step IN (" . implode(",", $EXT[17]['approver']) . ")");
            })
                ->orWhere(function ($query) {
                    $query->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1');
                })
                ->orWhereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1')
                ->orderBy('id', 'DESC');
        } 
        elseif (isset($EXT[17]['Forward']))
         {       
            $query = $query->where('Forward_Status', 1)->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD")')->orderBy('id', 'DESC');
        } 
        elseif (isset($EXT[17]['approver'])) 
        {
            
            $query = $query->where('Approve_status', null)->where(['Forward_Status' => 0, 'status' => 0])->WhereRaw("Approve_Step IN (" . implode(",", $EXT[17]['approver']) . ")")->orderBy('id', 'DESC');
        }
        $production= $query->get();
       /////////////////////////////////
        
        return view('Production/ProductionApproverList',compact('Organization','BU','Manufacturing_unit','plant_name','UOM','production','Filtered_Array','admindata','uom_data','orgdata','BUdata','Manufacturing_unitdata','plant_namedata','Raw_Materialdata'));
    }
    public function view($id)
    {
        $type=1;
        return view('Production/mainview',compact('id','type'));
    }
    public function approve(Request $request)
    {
        $EXT = Session::get('EXT');

        if (!empty($request->during_approval)) {
            Production::where('id', $request->approveID)->update(['Approve_status' => $request->during_approval]);
            ProductionApprove::where('productionID', $request->approveID)->where('status', 1)->update(['status' => 0]);
          //  echo "ravi";
        }
        //die;
        $check = Production::find($request->approveID);
        if ($request->during_approval === 'APPROVE') {
            $status = Forwarded_Data::where('DataID', $request->approveID)->update(['status' => 1]);
            Production::where('id', $request->approveID)->update(['Forward_Status' => 0]);

            $DepartStepcount2 = Department_Assign::where(['departments' => 17, 'step' => 2])->count();
            $DepartStepcount3 = Department_Assign::where(['departments' => 17, 'step' => 3])->count();
            $prod=true;
            if ($check->Approve_Step == 1 && $DepartStepcount2 > 0) {
                Production::where('id', $request->approveID)->update(['Approve_Step' => 2, 'Approve_status' => null]);
                $prod=false;
            }

            if ($check->Approve_Step == 2 &&  $DepartStepcount3 > 0) {
                Production::where('id', $request->approveID)->update(['Approve_Step' => 3, 'Approve_status' => null]);
                $prod=false;
            }
            if($prod==true)
            {
                $prodstock=new PlantStock;
                $count=$prodstock->where(['plantID'=>$check->Plant_Name,'materialID'=>$check->Raw_Material,'type'=>1,'Manufacturing_Unit'=>$check->Unit_Name])->count();
                if($count>0)
                {
                    $prodstock=$prodstock->where(['plantID'=>$check->Plant_Name,'materialID'=>$check->Raw_Material,'type'=>1,'Manufacturing_Unit'=>$check->Unit_Name]);
                    $prodstock=$prodstock->first();
                    //dd($data);
                }
                $prodstock->Manufacturing_Unit=$check->Unit_Name;
                $prodstock->plantID=$check->Plant_Name;
                $prodstock->materialID=$check->Raw_Material;
                $prodstock->stock=$count>0?($check->Quantity+$prodstock->stock):$check->Quantity;
                $prodstock->type=1;
                $prodstock->save();
            }
        }

        if ($request->during_approval === 'REJECT') {
            $prod=Production::find($request->approveID);
            $data=ProductionData::where('productionID',$request->approveID)->get();
            $batchdata=productionBatch::where('productionID',$request->approveID)->get();
            foreach($data as $value)
            {
                PlantStock::stock($prod->Plant_Name,$value->RawMaterial_id, $value->TotalQty);
            }
            foreach($batchdata as $val){
                 $fetchserialnumber=FactorySerialNumberDetail::select('factory_serial_number_details.*')
                ->leftJoin('factory_serial_numbers','factory_serial_number_details.sl_id','=','factory_serial_numbers.id')
                ->where('sl_no', $val->serail_check)
                ->where('factory_serial_numbers.serial_date', $prod->Production_Date)
                ->where('factory_serial_numbers.Shift_Name', $prod->Shift)
                ->first();
                if(isset($fetchserialnumber)){
                //FactorySerialNumberDetail::where('sl_no', $fetchserialnumber->sl_no)->update(['status' => '']);
                FactorySerialNumberDetail::where('sl_no', $fetchserialnumber->sl_no)->update(['status' => DB::raw('NULL')]);
                }
            }


           // MaterialManagement_Add_Material::where('id', $check->Raw_Material)->update(['Used_Status' => 0]);
        }
        if ($request->during_approval === 'RECHECK') {
            $prod=Production::find($request->approveID);
            $data=ProductionData::where('productionID',$request->approveID)->get();
            foreach($data as $value)
            {
                PlantStock::stock($prod->Plant_Name,$value->RawMaterial_id, $value->TotalQty);
            }

           // MaterialManagement_Add_Material::where('id', $check->Raw_Material)->update(['Used_Status' => 0]);
        }
        if ($request->during_approval === 'FORWARD') {
            Forwarded_Data::where(['DepartmentID' => 17, 'DataID' => $request->approveID])->update(['status' => 1]);
            Production::where('id', $request->approveID)->update(['Forward_Status' => 1]);

            $forward = new Forwarded_Data;
            $forward->userID = auth()->user()->id;
            $forward->Forward_To_id = $request->Forward_To;
            $forward->DepartmentID = 17;
            $forward->DataID = $request->approveID;
            $forward->status = 0;

            $forward->save();
        }

        $approve = new ProductionApprove;
        $approve->userID = auth()->user()->id;
        if (auth()->user()->role == 0) {
            $approve->role = 'Admin';
        } elseif (isset($EXT[17]['Inputer'])) {
            $approve->role = 'Inputer';
        } elseif (isset($EXT[17]['approver'])) {
            $approve->role = 'Approver';
        } else {
            $approve->role = 'Viewer';
        }
        $approve->productionID = $request->approveID;
        $approve->status = 1;
        if ($request->during_approval != '') {
            $approve->action = $request->during_approval;
        } elseif ($request->pre_post_approval != '') {
            $approve->pre_post_approval = $request->pre_post_approval;
        } else {
            $approve->action = 'Replied';
        }
        $approve->comment_text = $request->comment_text;
        $approve->ip_address = $request->getClientIp();
        $approve->device_name = $request->server('HTTP_USER_AGENT');
        $approve->days_for_holding = $request->days_for_holding;
        $approve->Forward_To = $request->Forward_To;

        $approve->save();

        if ($request->during_approval == '' && $request->pre_post_approval == '') {
            Production::where('id', $request->approveID)->update(['Approve_status' => null]);
            return redirect('Production/ProductionList')->with('success', 'successfully.....');
        } elseif (($request->pre_post_approval == 'AUDIT' || $request->pre_post_approval == 'INTIMATION' || $request->pre_post_approval == 'QUERY') && $request->non_acting == 1) {
            return redirect('Production/ProductionList')->with('success', 'successfull.....');
        } else {
            return redirect('Production/ProductionApproverList')->with('success', 'Approved successfully.....');
        }
    }

    public function AddProductionPage($id = null)
    {
        // $Product = Factory_Product::all();
        // $Sub_Product = Factory_Sub_Product::all();
        // $Sub_Sub_Product = Factory_Sub_Sub_Product::all();
        $Organization = Factory_Organisation::all();
        $BU = Master_BU::all();
        $Manufacturing_unit = Master_Manufacturing_unit::all();
        $plant_name = Master_Plant_Machinery::all();
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
                $Val->RawMaterial = MaterialManagement_Add_Material::find($Val->Raw_Material_FG);
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

        return view('Production/Production', ['UOM'=>$UOM,'Raw_Material'=>$Filtered_Array,'Organization' => $Organization, 'BU' => $BU,  'plant_name' => $plant_name,'Manufacturing_unit'=>$Manufacturing_unit,'edit'=>$edit]);
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
                    $Val->RawMaterial = MaterialManagement_Add_Material::find($Val->Material);
                    $plantstock= PlantStock::where(['plantID'=>$request->PlantID,'materialID'=>$Val->RawMaterial->id])->get()->first();
                    $Val->RawMaterial->UOM_name=Factory_Uom::find($Val->RawMaterial->UOM);
                    $Val->Plantstock=$plantstock->stock??0;
                    $Val->editdata=ProductionData::where(['productionID'=>$request->productionID,'RawMaterial_id'=>$Val->Material])->get()->first();
                    $Materials[] = $Val;
                }
            }
        }
        // echo "<pre>";
        // print_r($Materials);
        // echo "</pre>";
        return view('Production/material',compact('Materials'));
    }
}
