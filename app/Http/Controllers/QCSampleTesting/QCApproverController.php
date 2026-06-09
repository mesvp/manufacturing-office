<?php

namespace App\Http\Controllers\QCSampleTesting;

use App\Http\Controllers\Controller;
use App\Models\Production\ProductionBatch;
use Illuminate\Http\Request;
use App\Models\Master\Plant\{Master_Manufacturing_unit, Master_BU};
use App\Models\Master\RawMaterial\{Master_Raw_Material};
use App\Models\QCSampleTesting\{QCFinishedGoodResult, QCFinishedGood,QCFinishedGoodApprove};
use App\Models\FactoryCreater\{ Factory_Organisation,Factory_Uom,prj_organisation,Factory_Address_Detail};
use App\Models\BOM\{BOM, BOM_Material};
use App\Models\Master\{Master_Plant_Machinery,Prj_Subproject,Prj_Project,Module_Bsns_Unit,Prj_Inventory,Pur_Address};
use App\Models\MaterialManagement\{MaterialManagement_Add_Material};
use App\Models\{Admin,Forwarded_Data,Department_Assign};
use Session;
use DB;

class QCApproverController extends Controller
{
    public function STDFinishedGoodsList(Request $request)
    {
        $Organization = prj_organisation::all();
        $BU = Module_Bsns_Unit::all();
        $Manufacturing_unit = prj_project::all();
        $plant_name = Prj_Subproject::all();
        $UOM = Factory_Uom::all();
        $admin=Admin::where('role',1)->get();
        $BOM_DATA = BOM::where('Approve_status', 'APPROVE')->get();
        $Raw_Material = [];
        $Raw_Materialdata=[];
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
        $batch=DB::select("SELECT batch_no FROM `production_batch` WHERE productionID IN(SELECT id FROM `production` WHERE Approve_status='APPROVE') group by batch_no");
        //////////////////////////////////
        $uom_data=Factory_Uom::all_uom();
        $orgdata=prj_organisation::all_org();
        $BUdata = Module_Bsns_Unit::all_bu();
        $Manufacturing_unitdata = prj_project::all_mu();
        $plant_namedata = Prj_Subproject::all_pm();
        $admindata=Admin::all_admin();
        //$batch=ProductionBatch::where('productionID',$request->id)->get();
       
        //'edit'=>$edit,'batch'=>$batch
       
        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));

        $query = QCFinishedGood::where('status',0)->orderBy('id', 'DESC');
        $EXT = Session::get('EXT');
        if ($fromdate && $todate) {
            $query->whereBetween('created_at', [$fromdate, $todate]);
        }
        
        if (isset($EXT[9]['Forward']) && isset($EXT[9]['approver']))
         {
            $query = $query->where(function ($query) use ($EXT) {
                $query->where('Approve_status', null)->where('Forward_Status', 0)->whereRaw("Approve_Step IN (" . implode(",", $EXT[9]['approver']) . ")");
            })
                ->orWhere(function ($query) {
                    $query->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1');
                })
                ->orWhereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1')
                ->orderBy('id', 'DESC');
        } 
        elseif (isset($EXT[9]['Forward']))
         {       
            $query = $query->where('Forward_Status', 1)->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD")')->orderBy('id', 'DESC');
        } 
        elseif (isset($EXT[9]['approver'])) 
        {
            
            $query = $query->where('Approve_status', null)->where(['Forward_Status' => 0, 'status' => 0])->WhereRaw("Approve_Step IN (" . implode(",", $EXT[9]['approver']) . ")")->orderBy('id', 'DESC');
        }

        $STD_arr = $query->get();
        return view('QCSampleTesting/ApproverList', ['UOM'=>$UOM,'Organization' => $Organization, 'BU' => $BU,  'plant_name' => $plant_name,'Manufacturing_unit'=>$Manufacturing_unit,'admin'=>$admin,'batch'=>$batch,'Filtered_Array'=>$Filtered_Array ,'STD_data' => $STD_arr,'Raw_Materialdata'=>$Raw_Materialdata,'uom_data'=>$uom_data,'orgdata'=>$orgdata,'BUdata'=>$BUdata,'Manufacturing_unitdata'=>$Manufacturing_unitdata,'plant_namedata'=>$plant_namedata,'admindata'=>$admindata]);
    }
    public function view($id)
    {
        $type=1;
        return view('QCSampleTesting/mainview',compact('id','type'));
    }
    public function approve(Request $request)
    {
        $EXT = Session::get('EXT');

        if (!empty($request->during_approval)) {
            QCFinishedGood::where('id', $request->approveID)->update(['Approve_status' => $request->during_approval]);
            QCFinishedGoodApprove::where('QCFinishedGoodID', $request->approveID)->where('status', 1)->update(['status' => 0]);
          //  echo "ravi";
        }
        //die;
        $check = QCFinishedGood::find($request->approveID);
        if ($request->during_approval === 'APPROVE') {
            $status = Forwarded_Data::where('DataID', $request->approveID)->update(['status' => 1]);
            QCFinishedGood::where('id', $request->approveID)->update(['Forward_Status' => 0]);

            $DepartStepcount2 = Department_Assign::where(['departments' => 9, 'step' => 2])->count();
            $DepartStepcount3 = Department_Assign::where(['departments' => 9, 'step' => 3])->count();

            if ($check->Approve_Step == 1 && $DepartStepcount2 > 0) {
                QCFinishedGood::where('id', $request->approveID)->update(['Approve_Step' => 2, 'Approve_status' => null]);
            }

            if ($check->Approve_Step == 2 &&  $DepartStepcount3 > 0) {
                QCFinishedGood::where('id', $request->approveID)->update(['Approve_Step' => 3, 'Approve_status' => null]);
            }
        }

        if ($request->during_approval === 'REJECT') {
            QCFinishedGoodResult::where('QCFinishedGoodID',$request->approveID)->delete();
           // MaterialManagement_Add_Material::where('id', $check->Raw_Material)->update(['Used_Status' => 0]);
        }

        if ($request->during_approval === 'FORWARD') {
            Forwarded_Data::where(['DepartmentID' => 9, 'DataID' => $request->approveID])->update(['status' => 1]);
            QCFinishedGood::where('id', $request->approveID)->update(['Forward_Status' => 1]);

            $forward = new Forwarded_Data;
            $forward->userID = auth()->user()->id;
            $forward->Forward_To_id = $request->Forward_To;
            $forward->DepartmentID = 9;
            $forward->DataID = $request->approveID;
            $forward->status = 0;

            $forward->save();
        }

        $approve = new QCFinishedGoodApprove;
        $approve->userID = auth()->user()->id;
        if (auth()->user()->role == 0) {
            $approve->role = 'Admin';
        } elseif (isset($EXT[9]['Inputer'])) {
            $approve->role = 'Inputer';
        } elseif (isset($EXT[9]['approver'])) {
            $approve->role = 'Approver';
        } else {
            $approve->role = 'Viewer';
        }
        $approve->QCFinishedGoodID = $request->approveID;
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
            QCFinishedGood::where('id', $request->approveID)->update(['Approve_status' => null]);
            return redirect('QCSampleTesting/STDFinishedGoodsList')->with('success', 'successfully.....');
        } elseif (($request->pre_post_approval == 'AUDIT' || $request->pre_post_approval == 'INTIMATION' || $request->pre_post_approval == 'QUERY') && $request->non_acting == 1) {
            return redirect('QCSampleTesting/STDFinishedGoodsList')->with('success', 'successfull.....');
        } else {
            return redirect('QCSampleTesting/STDFinishedGoodsApproverList')->with('success', 'Approved successfully.....');
        }
    }

   



    
}
