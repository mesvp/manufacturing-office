<?php

namespace App\Http\Controllers\FinishedGood;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FinishedGood\FinishedGoodGatepass;
use App\Models\FinishedGood\FinishedGoodGatepassApprove;
use App\Models\Production\{Production_For_Sales, Production_For_Stock,ProductionData,ProductionBatch,Production, ProductionApprove};
use App\Models\FactoryCreater\{Factory_Product, Factory_Sub_Product, Factory_Sub_Sub_Product, Factory_Organisation,Factory_Uom,Factory_Address_Detail,prj_organisation,Factory_Plant_Machinery};
use App\Models\Master\Plant\{Master_Company_Name, Master_Manufacturing_unit, Master_BU, Master_Work_Order_Status};
use App\Models\Master\{Master_Plant_Machinery,Prj_Subproject,Prj_Project,Module_Bsns_Unit,Prj_Inventory,Pur_Address};
use App\Models\orderRequirement\{Order_Requirement_Sales, Order_Requirement_Stock};
use App\Models\{CheckBox, Admin,PlantStock,Forwarded_Data, Department_Assign};
use App\Models\BOM\{BOM, BOM_Material};
use App\Models\MaterialManagement\{MaterialManagement_Add_Material};
use App\Models\ProductCategories\{ProductCategories_Add_Product, ProductCategories_Add_Product_Other, ProductCategories_Approve};
use Session;

class FinishedGoodApproverGatepassController extends Controller
{
    public function FinishedGoodApproveList(Request $request)
    {
        //return $request->all();
        $admindata=Admin::all_admin();
        $Orgs = prj_organisation::all();
        $orgdata=prj_organisation::all_org();
        $BUdata = Module_Bsns_Unit::all_bu();
        $BU = Module_Bsns_Unit::all();
        $Manufacturing_unit = Factory_Address_Detail::select('prj_project.*')
        ->leftJoin('prj_project','factory_address_details.name_of_unit','=','prj_project.id')
        ->where('Approve_status','APPROVE')
        ->groupBy('prj_project.pname')
        ->get();
        $Manufacturing_unitdata = Prj_Project::all_mu();
        $plant_name = Factory_Plant_Machinery::select('prj_subproject.*','prj_organisation.organisation','prj_organisation.id as orgid')
		->leftJoin('prj_subproject', 'factory_plant_machineries.Plant_Name', '=', 'prj_subproject.id')
		->leftJoin('factory_address_details', 'factory_plant_machineries.factory_id', '=', 'factory_address_details.id')
		->leftJoin('prj_organisation', 'factory_address_details.organization', '=', 'prj_organisation.id')
		->whereIn('factory_address_details.name_of_unit',$Manufacturing_unit->pluck('id'))
		->where('factory_address_details.Approve_status', 'APPROVE')
		->whereNotNull('prj_subproject.spname')
		->get();
        $plant_namedata = Prj_Subproject::all_pm();
        $MAT_DATA = ProductCategories_Add_Product::where('Approve_status', 'APPROVE')->get();
        $Raw_Material = [];
        $Raw_Materialdata = [];
        foreach ($MAT_DATA as $Val) {
            if (isset($Val->Raw_Material)) {
                $Val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                    ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                    ->where('materialmanagement_add_material.id',$Val->Raw_Material)
                    ->first();
                $Raw_Material[$Val->Raw_Material] = $Val;
                $Raw_Materialdata[$Val->Raw_Material]= $Val->RawMaterial->matname;

            }
        }
        $Filtered_Array = array_values($Raw_Material);
        $UOM = Factory_Uom::all();
        $uom_data=Factory_Uom::all_uom();
        /////////////////
        $EXT = Session::get('EXT');

        $query = FinishedGoodGatepass::query();

        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');

        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));
         //dd($todate,$fromdate);
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
        if (isset($EXT[22]['Forward']) && isset($EXT[22]['approver']))
         {
            $query = $query->where(function ($query) use ($EXT) {
                $query->where('Approve_status', null)->where('Forward_Status', 0)->whereRaw("Approve_Step IN (" . implode(",", $EXT[22]['approver']) . ")");
            })
                ->orWhere(function ($query) {
                    $query->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1');
                })
                ->orWhereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1')
                ->orderBy('id', 'DESC');
        }
        elseif (isset($EXT[22]['Forward']))
         {
            $query = $query->where('Forward_Status', 1)->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD")')->orderBy('id', 'DESC');
        }
        elseif (isset($EXT[22]['approver']))
        {

            $query = $query->where('Approve_status', null)->where(['Forward_Status' => 0, 'status' => 0])->WhereRaw("Approve_Step IN (" . implode(",", $EXT[22]['approver']) . ")")->orderBy('id', 'DESC');
        }
        $production= $query->get();
       /////////////////////////////////
        return view('FinishedGood/FinishedGoodApproverList',compact('Orgs','BU','Manufacturing_unit','plant_name','UOM','production','Filtered_Array','admindata','uom_data','orgdata','BUdata','Manufacturing_unitdata','plant_namedata','Raw_Materialdata','RequestBy','Raw_Material','RequestBy'));
    }
    // public function view($id)
    // {
    //     $type=1;
    //     return view('Production/mainview',compact('id','type'));
    // }

    public function approve(Request $request)
    {
        $EXT = Session::get('EXT');

        if (!empty($request->during_approval)) {
            FinishedGoodGatepass::where('id', $request->approveID)->update(['Approve_status' => $request->during_approval]);
            FinishedGoodGatepassApprove::where('FinishedGoodID', $request->approveID)->where('status', 1)->update(['status' => 0]);
          //  echo "ravi";
        }
        //die;
        $check = FinishedGoodGatepass::find($request->approveID);
        if ($request->during_approval === 'APPROVE') {
            $status = Forwarded_Data::where('DataID', $request->approveID)->update(['status' => 1]);
            FinishedGoodGatepass::where('id', $request->approveID)->update(['Forward_Status' => 0]);

            $DepartStepcount2 = Department_Assign::where(['departments' => 22, 'step' => 2])->count();
            $DepartStepcount3 = Department_Assign::where(['departments' => 22, 'step' => 3])->count();
            $prod=true;
            if ($check->Approve_Step == 1 && $DepartStepcount2 > 0) {
                FinishedGoodGatepass::where('id', $request->approveID)->update(['Approve_Step' => 2, 'Approve_status' => null]);
                $prod=false;
            }

            if ($check->Approve_Step == 2 &&  $DepartStepcount3 > 0) {
                FinishedGoodGatepass::where('id', $request->approveID)->update(['Approve_Step' => 3, 'Approve_status' => null]);
                $prod=false;
            }
            if($prod==true)
            {
                $prodstock=new PlantStock;
                $count=$prodstock->where(['plantID'=>$check->Plant_Name,'materialID'=>$check->Material_id,'type'=>1,'Manufacturing_Unit'=>$check->Unit_Name])->count();
                if($count>0)
                {
                    $prodstock=$prodstock->where(['plantID'=>$check->Plant_Name,'materialID'=>$check->Material_id,'type'=>1,'Manufacturing_Unit'=>$check->Unit_Name]);
                    $prodstock=$prodstock->first();
                    //dd($data);
                }

                $prodstock->Manufacturing_Unit=$check->Unit_Name;
                $prodstock->plantID=$check->Plant_Name;
                $prodstock->materialID=$check->Material_id;
                $prodstock->stock=$count>0?($check->Quantity+$prodstock->stock):$check->Quantity;
                $prodstock->type=1;
                $prodstock->save();
            }
        }

        if ($request->during_approval === 'REJECT') {
            $prod=FinishedGoodGatepass::find($request->approveID);
            // $data=ProductionData::where('productionID',$request->approveID)->get();
            // foreach($data as $value)
            // {
            //     PlantStock::stock($prod->Plant_Name,$value->RawMaterial_id, $value->TotalQty);
            // }

        }
        if ($request->during_approval === 'RECHECK') {
            $prod=FinishedGoodGatepass::find($request->approveID);
            // $data=ProductionData::where('productionID',$request->approveID)->get();
            // foreach($data as $value)
            // {
            //     PlantStock::stock($prod->Plant_Name,$value->RawMaterial_id, $value->TotalQty);
            // }

        }
        if ($request->during_approval === 'FORWARD') {
            Forwarded_Data::where(['DepartmentID' => 22, 'DataID' => $request->approveID])->update(['status' => 1]);
            FinishedGoodGatepass::where('id', $request->approveID)->update(['Forward_Status' => 1]);

            $forward = new Forwarded_Data;
            $forward->userID = auth()->user()->id;
            $forward->Forward_To_id = $request->Forward_To;
            $forward->DepartmentID = 22;
            $forward->DataID = $request->approveID;
            $forward->status = 0;

            $forward->save();
        }

        $approve = new FinishedGoodGatepassApprove;
        $approve->userID = auth()->user()->id;
        if (auth()->user()->role == 0) {
            $approve->role = 'Admin';
        } elseif (isset($EXT[22]['Inputer'])) {
            $approve->role = 'Inputer';
        } elseif (isset($EXT[22]['approver'])) {
            $approve->role = 'Approver';
        } else {
            $approve->role = 'Viewer';
        }
        $approve->FinishedGoodID = $request->approveID;
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
            FinishedGoodGatepass::where('id', $request->approveID)->update(['Approve_status' => null]);
            return redirect('FinishedGood/Finished_Good_List')->with('success', 'successfully.....');
        } elseif (($request->pre_post_approval == 'AUDIT' || $request->pre_post_approval == 'INTIMATION' || $request->pre_post_approval == 'QUERY') && $request->non_acting == 1) {
            return redirect('FinishedGood/Finished_Good_List')->with('success', 'successfull.....');
        } else {
            return redirect('FinishedGood/Finished_Good_Approver_List')->with('success', 'Approved successfully.....');
        }
    }
    public function Release_Hold(Request $request, $id)
    {
        $EXT = Session::get('EXT');
        $currentDate = now();

        $approvesss = FinishedGoodGatepassApprove::where('FinishedGoodID', $id)->where('action', 'HOLD')->update(['days_for_holding' => $currentDate, 'status' => 0]);
        $factory =  FinishedGoodGatepass::where('id', $id)->update(['Approve_status' => null]);

        $approve = new FinishedGoodGatepassApprove;
        $approve->userID = auth()->user()->id;
        if (auth()->user()->role == 0) {
            $approve->role = 'Admin';
        } elseif (isset($EXT[22]['approver'])) {
            $approve->role = 'Approver';
        } elseif (isset($EXT[22]['inputer'])) {
            $approve->role = 'Inputer';
        } else {
            $approve->role = 'Viewer';
        }
        $approve->FinishedGoodID = $id;
        $approve->status = 1;
        $approve->action = 'Hold Released';
        $approve->comment_text = $request->comment_text;
        $approve->ip_address = $request->getClientIp();
        $approve->device_name = $request->server('HTTP_USER_AGENT');
        $approve->save();


        return redirect('FinishedGood/Finished_Good_List')->with('success', 'Hold Released successfully.....');
    }

    // public function AddProductionPage($id = null)
    // {
    //     // $Product = Factory_Product::all();
    //     // $Sub_Product = Factory_Sub_Product::all();
    //     // $Sub_Sub_Product = Factory_Sub_Sub_Product::all();
    //     $Organization = Factory_Organisation::all();
    //     $BU = Master_BU::all();
    //     $Manufacturing_unit = Master_Manufacturing_unit::all();
    //     $plant_name = Master_Plant_Machinery::all();
    //     // $Company_Name = Master_Company_Name::all();
    //     // $Work_Order_Status = Master_Work_Order_Status::all();
    //     // $Sales_Order_No = Order_Requirement_Sales::all();
    //     // $Stock_Order_No = Order_Requirement_Stock::all();

    //     // $editSales = Production_For_Sales::find($id);
    //     // $editStock = Production_For_Stock::find($id);
    //     $MAT_DATA = ProductCategories_Add_Product::where('Approve_status', 'APPROVE')->get();
    //     $Raw_Material = [];
    //     foreach ($MAT_DATA as $Val) {
    //         if (isset($Val->Raw_Material)) {
    //             $Val->RawMaterial = MaterialManagement_Add_Material::find($Val->Raw_Material);
    //             $Raw_Material[$Val->Raw_Material] = $Val;
    //         }
    //     }
    //     $Filtered_Array = array_values($Raw_Material);
    //     $UOM = Factory_Uom::all();
    //     $edit=[];
    //     if(!empty($id))
    //     {
    //         $edit=Production::find($id);
    //     }

    //     return view('Production/Production', ['UOM'=>$UOM,'Raw_Material'=>$Filtered_Array,'Organization' => $Organization, 'BU' => $BU,  'plant_name' => $plant_name,'Manufacturing_unit'=>$Manufacturing_unit,'edit'=>$edit]);
    // }

    // public function delete_Sales($id)
    // {
    //     Production_For_Sales::find($id)->delete();

    //     return back()->with('success', 'Deleted Successfully...');
    // }

    // public function delete_Stock($id)
    // {
    //     Production_For_Stock::find($id)->delete();

    //     return back()->with('success', 'Deleted Successfully...');
    // }
    // public function MaterialData(Request $request)
    // {
    //     $BOM = BOM::where(['Raw_Material_FG' => $request->MaterialId, 'Approve_status' => 'APPROVE'])->orderBy('id', 'DESC')->first();
    //     $Materials = [];
    //     if (isset($BOM) && $BOM != '') {
    //         $MaterialData = BOM_Material::where('BOM_ID', $BOM->id)->get();
    //         foreach ($MaterialData as $Val) {
    //             if (isset($Val->Material)) {
    //                 $Val->RawMaterial = MaterialManagement_Add_Material::find($Val->Material);
    //                 $plantstock= PlantStock::where(['plantID'=>$request->PlantID,'materialID'=>$Val->RawMaterial->id])->get()->first();
    //                 $Val->RawMaterial->UOM_name=Factory_Uom::find($Val->RawMaterial->UOM);
    //                 $Val->Plantstock=$plantstock->stock??0;
    //                 $Val->editdata=ProductionData::where(['productionID'=>$request->productionID,'RawMaterial_id'=>$Val->Material])->get()->first();
    //                 $Materials[] = $Val;
    //             }
    //         }
    //     }
    //     // echo "<pre>";
    //     // print_r($Materials);
    //     // echo "</pre>";
    //     return view('Production/material',compact('Materials'));
    // }

    public function ExportApproverFinishedGood(Request $request)
    {
        ini_set('memory_limit', '-1');

        $admindata = Admin::all_admin();
        $Organization = prj_organisation::all();
        $orgdata = prj_organisation::all_org();
        $BUdata = Module_Bsns_Unit::all_bu();
        $BU = Module_Bsns_Unit::all();
        $Manufacturing_unit = Prj_Project::all();
        $Manufacturing_unitdata = Prj_Project::all_mu();
        $plant_name = Prj_Subproject::all();
        $plant_namedata = Prj_Subproject::all_pm();
        $MAT_DATA = ProductCategories_Add_Product::where('Approve_status', 'APPROVE')->get();

        $Raw_Material = [];
        $Raw_Materialdata = [];
        foreach ($MAT_DATA as $Val) {
            if (isset($Val->Raw_Material)) {
                $Val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                    ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
                    ->where('materialmanagement_add_material.id', $Val->Raw_Material)
                    ->first();
                $Raw_Material[$Val->Raw_Material] = $Val;
                $Raw_Materialdata[$Val->Raw_Material] = $Val->RawMaterial->matname;
            }
        }
        $Filtered_Array = array_values($Raw_Material);
        $UOM = Factory_Uom::all();
        $uom_data = Factory_Uom::all_uom();
        $EXT = Session::get('EXT');

        $query = FinishedGoodGatepass::query();
        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($dateto)));

        if ($fromdate && $todate) {
            $query->whereBetween('created_at', [$fromdate, $todate]);
        }

        if (isset($EXT[22]['Forward']) && isset($EXT[22]['approver'])) {
            $query->where(function ($query) use ($EXT) {
                $query->where('Approve_status', null)->where('Forward_Status', 0)
                    ->whereRaw("Approve_Step IN (" . implode(",", $EXT[22]['approver']) . ")");
            })->orWhereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1')
            ->orderBy('id', 'DESC');
        } elseif (isset($EXT[22]['Forward'])) {
            $query->where('Forward_Status', 1)->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD")')->orderBy('id', 'DESC');
        } elseif (isset($EXT[22]['approver'])) {
            $query->where('Approve_status', null)->where(['Forward_Status' => 0, 'status' => 0])
                ->whereRaw("Approve_Step IN (" . implode(",", $EXT[22]['approver']) . ")")
                ->orderBy('id', 'DESC');
        }

        $production = $query->get();

        $Checkbox = CheckBox::where('userID', auth()->user()->id)->where('tableID', 22)->get();
        $Checkbox_Arr = $Checkbox->pluck('CheckBox')->toArray();

        $data = [];
        foreach ($production as $key => $value) {
            $rowData = [
                "SL. No." => $key + 1,
                "Creator Name" => $admindata[$value->userID] ?? '',
                "Manufacturing Unit" => $Manufacturing_unitdata[$value->Unit_Name] ?? '',
                "Plant Name" => $plant_namedata[$value->Plant_Name] ?? '',
                "Organization Name" => $orgdata[$value->Organization_Name] ?? '',
                "Production Date" => $value->Transaction_Date ?? '',
                "Finished Good(FG)" => $Raw_Materialdata[$value->Material_id] ?? '',
                "UOM" => $value->UOM ?? '',
                "Rate" => $value->Rate ?? '',
                "Quantity" => $value->Quantity ?? '',
                "Date & Time" =>  isset($value->created_at) ? date('d-m-Y h:i A', strtotime($value->created_at)) : '',
                "Status" => $value->Approve_status == null ? 'PENDING' : $value->Approve_status,
                "Pending With" => Pending_With(22, $value) ?? ''
            ];

            if (!empty($Checkbox_Arr)) {
                $filteredRow = array_intersect_key($rowData, array_flip($Checkbox_Arr));
                $data[] = $filteredRow;
            } else {
                $data[] = $rowData;
            }
        }

        $file = "FinishedGooddata" . date("d-m-Y") . ".csv";
        $this->collectionExport($data, $file);
    }

    public function collectionExport($data, $file)
    {
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=" . $file);

        $fp = fopen('php://output', 'w');
        $header = false;
        foreach ($data as $row) {
            if (!$header) {
                fputcsv($fp, array_keys($row));
                $header = true;
            }
            fputcsv($fp, $row);
        }
        fclose($fp);
    }

}
