<?php

namespace App\Http\Controllers\MaterialManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\{Master_Material_data};
use App\Models\{Admin, Department_Assign, Forwarded_Data};
use App\Models\MaterialManagement\{MaterialManagement_Add_Material, Material_Management_approve};
use App\Models\Master\Plant\Master_Quality_Check;
use App\Models\Master\RawMaterial\{Master_Gate_Pass_Required, Master_HSN_Code, Master_Material_Name};
use App\Models\FactoryCreater\Factory_Uom;
use Session;


class MaterialManagementApproveController extends Controller
{
    public function MaterialApproveList(Request $request)
    {
        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));

        $EXT = Session::get('EXT');
        $STEP = Session::get('STEP');

        $query = new MaterialManagement_Add_Material;

        if ($fromdate && $todate) {
            $query = $query->whereBetween('created_at', [$fromdate, $todate]);
        }

        if (isset($EXT[4]['Forward']) && isset($EXT[4]['approver'])) {

            $query = $query->where(function ($query) use ($EXT) {
                $query->where('Approve_status', null)->where('Forward_Status', 0)->whereRaw("Approve_Step IN (" . implode(",", $EXT[4]['approver']) . ")");
            })
                ->orWhere(function ($query) {
                    $query->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1');
                })
                ->orWhereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1')
                ->orderBy('id', 'DESC');
        } elseif (isset($EXT[4]['Forward'])) {
            $query = $query->where('Forward_Status', 1)->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD")')->orderBy('id', 'DESC');
        } elseif (isset($EXT[4]['approver'])) {
            $query = $query->where('Approve_status', null)->where(['Forward_Status' => 0, 'status' => 0])->WhereRaw("Approve_Step IN (" . implode(",", $EXT[4]['approver']) . ")")->orderBy('id', 'DESC');
        }

        $materialManagment = $query->get();
        $materialManagment_arr = [];
        foreach ($materialManagment as $val) {
            if ($val->Forward_Status != 1) {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="4" AND step="' . $val->Approve_Step . '")')->get();
            } else {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data` WHERE DataID="' . $val->id . '" AND DepartmentID=4 AND status=0)')->get();
            }
            $val->user = Admin::find($val->userID);
            $val->uomss = Factory_Uom::find($val->UOM);
            $val->mtaerialdetails = Master_Material_data::find($val->Material_Name);
            $val->qualityCheck = Master_Quality_Check::find($val->Quality_Check);
            $val->GatePassRequired = Master_Gate_Pass_Required::find($val->Gate_Pass);

            $materialManagment_arr[] = $val;
        }

        return view('MaterialManagement/MaterialApproveList', ['materialManagment' => $materialManagment_arr, 'fromdate' => $fromdate, 'todate' => $dateto]);
    }

    public function MaterialApproveView($id, $type)
    {
        $appro = Material_Management_approve::where('Material_Management_id', $id)->get();
        $approves = [];
        foreach ($appro as $val) {
            $val->user = Admin::find($val->userID);
            array_push($approves, $val);
        }

        $edit = MaterialManagement_Add_Material::find($id);
        $matname=Master_Material_data::select('prj_material.material_name')
                      ->where('prj_material.id',$edit->Material_Name)->first();
        // $edit = MaterialManagement_Add_Material::select('materialmanagement_add_material.*')
        //        ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
        //        ->where('materialmanagement_add_material.id',$id)->get();

        $uom = Factory_Uom::all();
        $Quality_Check = Master_Quality_Check::all();
        $Gate_Pass_Required = Master_Gate_Pass_Required::all();
        $employeeName = Admin::where('role', 1)->whereRaw('id IN (SELECT userID FROM employee_department where Departments="4")')->get();
        //$employeeName = Admin::where('role', 1)->get();

        $nextID = $this->next($id, $type);

        return view('MaterialManagement/MaterialApprove', ['edit' => $edit, 'uom' => $uom, 'Quality_Check' => $Quality_Check, 'Gate_Pass_Required' => $Gate_Pass_Required, 'approves' => $approves, 'nextID' => $nextID, 'employeeName' => $employeeName,'matname'=>$matname]);
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
        $EXT = Session::get('EXT');

        if (!empty($request->during_approval)) {
            MaterialManagement_Add_Material::where('id', $request->approveID)->update(['Approve_status' => $request->during_approval]);
            Material_Management_approve::where('Material_Management_id', $request->approveID)->where('status', 1)->update(['status' => 0]);
        }

        $check = MaterialManagement_Add_Material::find($request->approveID);
        if ($request->during_approval === 'APPROVE') {
            $status = Forwarded_Data::where('DataID', $request->approveID)->update(['status' => 1]);
            MaterialManagement_Add_Material::where('id', $request->approveID)->update(['Forward_Status' => 0]);

            $DepartStepcount2 = Department_Assign::where(['departments' => 4, 'step' => 2])->count();
            $DepartStepcount3 = Department_Assign::where(['departments' => 4, 'step' => 3])->count();

            if ($check->Approve_Step == 1 && $DepartStepcount2 > 0) {
                MaterialManagement_Add_Material::where('id', $request->approveID)->update(['Approve_Step' => 2, 'Approve_status' => null]);
            }

            if ($check->Approve_Step == 2 &&  $DepartStepcount3 > 0) {
                MaterialManagement_Add_Material::where('id', $request->approveID)->update(['Approve_Step' => 3, 'Approve_status' => null]);
            }
        }

        if ($request->during_approval === 'FORWARD') {
            Forwarded_Data::where(['DepartmentID' => 4, 'DataID' => $request->approveID])->update(['status' => 1]);
            MaterialManagement_Add_Material::where('id', $request->approveID)->update(['Forward_Status' => 1]);

            $forward = new Forwarded_Data;
            $forward->userID = auth()->user()->id;
            $forward->Forward_To_id = $request->Forward_To;
            $forward->DepartmentID = 4;
            $forward->DataID = $request->approveID;
            $forward->status = 0;

            $forward->save();
        }

        $approve = new Material_Management_approve;
        $approve->userID = auth()->user()->id;
        if (auth()->user()->role == 0) {
            $approve->role = 'Admin';
        } elseif ($check->Approve_status == 'OBJECT') {
            $approve->role = 'Inputer';
        } elseif (isset($EXT[4]['approver'])) {
            $approve->role = 'Approver';
        } else {
            $approve->role = 'Viewer';
        }
        $approve->Material_Management_id = $request->approveID;
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
        $approve->device_name = $request->header('User-Agent');
        $approve->days_for_holding = $request->days_for_holding;
        $approve->Forward_To = $request->Forward_To;

        $approve->save();

        if ($request->during_approval == '' && $request->pre_post_approval == '') {
            MaterialManagement_Add_Material::where('id', $request->approveID)->update(['Approve_status' => null]);
            return redirect('MaterialManagement/MaterialList')->with('success', 'successfully.....');
        } elseif (($request->pre_post_approval == 'AUDIT' || $request->pre_post_approval == 'INTIMATION' || $request->pre_post_approval == 'QUERY') && $request->non_acting == 1) {
            return redirect('MaterialManagement/MaterialList')->with('success', 'successfull.....');
        } else {
            return redirect('MaterialManagement/MaterialApproveList')->with('success', 'Approved successfully.....');
        }
    }

    public function CheckHoldExpiry()
    {
        $Approve = Material_Management_approve::all();

        return response()->json($Approve);
    }

    public function UpdateStatus(Request $request)
    {
        $Material_Management_id = $request->input('Material_Management_id');
        $userID = $request->input('userID');

        $approves = Material_Management_approve::where('Material_Management_id', $Material_Management_id)->where('userID', $userID)->update(['status' => 0]);
        $factory =  MaterialManagement_Add_Material::where('id', $Material_Management_id)->update(['Approve_status' => null]);

        $approve = new Material_Management_approve;
        $approve->role = 'AUTO';
        $approve->Material_Management_id = $Material_Management_id;
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
        $EXT = Session::get('EXT');
        $currentDate = now();

        $approves = Material_Management_approve::where('Material_Management_id', $id)->where('action', 'HOLD')->update(['days_for_holding' => $currentDate, 'status' => 0]);
        $factory =  MaterialManagement_Add_Material::where('id', $id)->update(['Approve_status' => null]);

        $approve = new Material_Management_approve;
        $approve->userID = auth()->user()->id;
        if (auth()->user()->role == 0) {
            $approve->role = 'Admin';
        } elseif (isset($EXT[4]['approver'])) {
            $approve->role = 'Approver';
        } elseif (isset($EXT[4]['inputer'])) {
            $approve->role = 'Inputer';
        } else {
            $approve->role = 'Viewer';
        }
        $approve->Material_Management_id = $id;
        $approve->status = 1;
        $approve->action = 'Hold Released';
        $approve->comment_text = $request->comment_text;
        $approve->ip_address = $request->getClientIp();
        $approve->device_name = $request->server('HTTP_USER_AGENT');
        $approve->save();

        return redirect('MaterialManagement/MaterialList')->with('success', 'Hold Released successfully.....');
    }
}
