<?php

namespace App\Http\Controllers\SampleFreeGood;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\Plant\{Master_Manufacturing_unit,Master_BU};
use App\Models\Master\{Master_Plant_Machinery,Prj_Subproject,Prj_Project,Module_Bsns_Unit,Prj_Inventory,Pur_Address,Fin_Customer};
use App\Models\SampleFreeGood\{SampleFreeGood, SampleFreeGood_data,SampleFreeGoodApprove};
use App\Models\FactoryCreater\{Factory_Organisation, Factory_Product, Factory_Uom,prj_organisation,Factory_Address_Detail};
use App\Models\{CheckBox, Admin, PlantStock,Forwarded_Data,Department_Assign};
use App\Models\BOM\{BOM, BOM_Material};
use App\Models\MaterialManagement\{MaterialManagement_Add_Material};
use App\Models\Master\RawMaterial\Master_Raw_Material;
use Session;


class SampleFreeGoodApproveController extends Controller
{
    public function SampleFreeGood_approve(Request $request)
    {
        $admindata=Admin::all_admin();
        $Organization = prj_organisation::all();
        $orgdata=prj_organisation::all_org();
        $BUdata = Module_Bsns_Unit::all_bu();
        $BU = Module_Bsns_Unit::all();
        $Manufacturing_unit = prj_project::all();
        $Manufacturing_unitdata = prj_project::all_mu();
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
        //////////////////////
        $EXT = Session::get('EXT');
        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));

        $query = new SampleFreeGood;

        if ($fromdate && $todate) {
            $query->whereBetween('created_at', [$fromdate, $todate]);
        }

        if (isset($EXT[10]['Forward']) && isset($EXT[10]['approver']))
         {
            $query = $query->where(function ($query) use ($EXT) {
                $query->where('Approve_status', null)->where('Forward_Status', 0)->whereRaw("Approve_Step IN (" . implode(",", $EXT[10]['approver']) . ")");
            })
                ->orWhere(function ($query) {
                    $query->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1');
                })
                ->orWhereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1')
                ->orderBy('id', 'DESC');
        } 
        elseif (isset($EXT[10]['Forward']))
         {       
           
            $query = $query->where('Forward_Status', 1)->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD")')->orderBy('id', 'DESC');
        } 
        elseif (isset($EXT[10]['approver'])) 
        {
            $query = $query->where('Approve_status', null)->where(['Forward_Status' => 0, 'status' => 0])->WhereRaw("Approve_Step IN (" . implode(",", $EXT[10]['approver']) . ")")->orderBy('id', 'DESC');
        }
        $Sample_data= $query->get();
       
        return view('SampleFreeGood/ApproverList',compact('Sample_data','Organization','BU','Manufacturing_unit','plant_name','UOM','Filtered_Array','admindata','uom_data','orgdata','BUdata','Manufacturing_unitdata','plant_namedata','Raw_Materialdata'));

       
    }

    public function view($id)
    {
        $type=1;
        return view('SampleFreeGood/mainview',compact('id','type'));
    }
    public function approve(Request $request)
    {
        $EXT = Session::get('EXT');

        if (!empty($request->during_approval)) {
            SampleFreeGood::where('id', $request->approveID)->update(['Approve_status' => $request->during_approval]);
            SampleFreeGoodApprove::where('SampleFreeGoodID', $request->approveID)->where('status', 1)->update(['status' => 0]);
          //  echo "ravi";
        }
        //die;
        $check = SampleFreeGood::find($request->approveID);
        if ($request->during_approval === 'APPROVE') {
            $status = Forwarded_Data::where('DataID', $request->approveID)->update(['status' => 1]);
            SampleFreeGood::where('id', $request->approveID)->update(['Forward_Status' => 0]);

            $DepartStepcount2 = Department_Assign::where(['departments' => 10, 'step' => 2])->count();
            $DepartStepcount3 = Department_Assign::where(['departments' => 10, 'step' => 3])->count();

            if ($check->Approve_Step == 1 && $DepartStepcount2 > 0) {
                SampleFreeGood::where('id', $request->approveID)->update(['Approve_Step' => 2, 'Approve_status' => null]);
            }

            if ($check->Approve_Step == 2 &&  $DepartStepcount3 > 0) {
                SampleFreeGood::where('id', $request->approveID)->update(['Approve_Step' => 3, 'Approve_status' => null]);
            }
        }

        if ($request->during_approval === 'REJECT') {
            if ($check->Plant_Name != '') {
                
                PlantStock::stock_vendor($check->Plant_Name,$check->Raw_Material,$check->Manufacturing_Unit,$check->Quantity,1);

            } else {
               
                Master_Raw_Material::stock($check->Organization_Name,$check->Godown_Name,$check->Raw_Material,$check->Quantity,1);
            }
            // $prod=SampleFreeGood::find($request->approveID);
            // $data=SampleFreeGoodData::where('SampleFreeGoodID',$request->approveID)->get();
            // foreach($data as $value)
            // {
            //     PlantStock::stock($prod->Plant_Name,$value->RawMaterial_id, $value->TotalQty);
            // }

           // MaterialManagement_Add_Material::where('id', $check->Raw_Material)->update(['Used_Status' => 0]);
        }
        if ($request->during_approval === 'RECHECK') {
            if ($check->Plant_Name != '') {
                
                PlantStock::stock_vendor($check->Plant_Name,$check->Raw_Material,$check->Manufacturing_Unit,$check->Quantity,1);

            } else {
               
                Master_Raw_Material::stock($check->Organization_Name,$check->Godown_Name,$check->Raw_Material,$check->Quantity,1);
            }
            // $prod=SampleFreeGood::find($request->approveID);
            // $data=SampleFreeGoodData::where('SampleFreeGoodID',$request->approveID)->get();
            // foreach($data as $value)
            // {
            //     PlantStock::stock($prod->Plant_Name,$value->RawMaterial_id, $value->TotalQty);
            // }

           // MaterialManagement_Add_Material::where('id', $check->Raw_Material)->update(['Used_Status' => 0]);
        }
        if ($request->during_approval === 'FORWARD') {
            Forwarded_Data::where(['DepartmentID' => 10, 'DataID' => $request->approveID])->update(['status' => 1]);
            SampleFreeGood::where('id', $request->approveID)->update(['Forward_Status' => 1]);

            $forward = new Forwarded_Data;
            $forward->userID = auth()->user()->id;
            $forward->Forward_To_id = $request->Forward_To;
            $forward->DepartmentID = 10;
            $forward->DataID = $request->approveID;
            $forward->status = 0;

            $forward->save();
        }

        $approve = new SampleFreeGoodApprove;
        $approve->userID = auth()->user()->id;
        if (auth()->user()->role == 0) {
            $approve->role = 'Admin';
        } elseif (isset($EXT[10]['Inputer'])) {
            $approve->role = 'Inputer';
        } elseif (isset($EXT[10]['approver'])) {
            $approve->role = 'Approver';
        } else {
            $approve->role = 'Viewer';
        }
        $approve->SampleFreeGoodID = $request->approveID;
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
            SampleFreeGood::where('id', $request->approveID)->update(['Approve_status' => null]);
            return redirect('SampleFreeGood/SampleFreeGoodList')->with('success', 'successfully.....');
        } elseif (($request->pre_post_approval == 'AUDIT' || $request->pre_post_approval == 'INTIMATION' || $request->pre_post_approval == 'QUERY') && $request->non_acting == 1) {
            return redirect('SampleFreeGood/SampleFreeGoodList')->with('success', 'successfull.....');
        } else {
            return redirect('SampleFreeGood/SampleFreeGoodApproveList')->with('success', 'Approved successfully.....');
        }
    }

}
