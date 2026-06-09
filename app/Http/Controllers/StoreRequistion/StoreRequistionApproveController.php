<?php

namespace App\Http\Controllers\StoreRequistion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StoreRequistion\{Store_Requistion, Store_Requistion_Material, Store_Requistion_approve};
use App\Models\Master\Plant\{Master_Manufacturing_unit, Master_Customer_Name, Master_BU};
use App\Models\FactoryCreater\{Factory_Organisation, Factory_Uom,prj_organisation,unitname,Factory_Address_Detail};
use App\Models\Master\{Master_Plant_Machinery,Prj_Subproject,Prj_Project,Module_Bsns_Unit,Prj_Inventory,Pur_Address};
use App\Models\{Admin, Forwarded_Data, Department_Assign};
use App\Models\MaterialManagement\{MaterialManagement_Add_Material};
use App\Models\Master\RawMaterial\Master_Godown_Name;
use App\Models\BOM\{BOM, BOM_Material};
use Session;


class StoreRequistionApproveController extends Controller
{
    public function StoreRequistion_approve(Request $request)
    {
        $EXT = Session::get('EXT');

        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));

        $query = new Store_Requistion;

        if ($fromdate && $todate) {
            $query->whereBetween('created_at', [$fromdate, $todate]);
        }

        if (isset($EXT[15]['Forward']) && isset($EXT[15]['approver'])) {       
            $query = $query->where(function ($query) use ($EXT) {
                $query->where('Approve_status', null)->where('Forward_Status', 0)->whereRaw("Approve_Step IN (" . implode(",", $EXT[15]['approver']) . ")");
            })
                ->orWhere(function ($query) {
                    $query->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1');
                })
                ->orWhereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1')
                ->orderBy('id', 'DESC');
        } elseif (isset($EXT[15]['Forward'])) {       
            $query = $query->where('Forward_Status', 1)->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD")')->orderBy('id', 'DESC');
        } elseif (isset($EXT[15]['approver'])) {
            $query = $query->where('Approve_status', null)->where(['Forward_Status' => 0, 'status' => 0])->WhereRaw("Approve_Step IN (" . implode(",", $EXT[15]['approver']) . ")")->orderBy('id', 'DESC');
        }

        $store = $query->get();

        $store_arr = array();
        foreach ($store as  $val) {
            if ($val->Forward_Status != 1)
             {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="15" AND step="' . $val->Approve_Step . '")')->get();
            } else {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data` WHERE DataID="' . $val->id . '" AND DepartmentID=15 AND status=0)')->get();
            }
            $val->user = Admin::find($val->userID);
            $val->Organization_Name = prj_organisation::find($val->Organization_Name);
            $val->Manufacturing_Unit = prj_project::find($val->Manufacturing_Unit);
            $val->Plant_Name = Prj_Subproject::find($val->Plant_Name);
            $val->Godown_Name = Prj_Inventory::find($val->Godown_Name);
            $val->HoldStatus = Store_Requistion_approve::where('Store_Requistion_id', $val->id)->where('action', 'HOLD')->where('status', 1)->where('userID', auth()->user()->id)->count();
            //$val->Raw_Material = MaterialManagement_Add_Material::find($val->Raw_Material);
            $val->Raw_Material = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
            ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
            ->where('materialmanagement_add_material.id',$val->Raw_Material)
            ->first();

            //$val->UOM = Factory_Uom::find($val->UOM);

            array_push($store_arr, $val);
        }

        return view('StoreRequistion/StoreRequistionApproveList', ['store' => $store_arr, 'fromdate' => $fromdate, 'todate' => $dateto]);
    }

    public function view_approve($id, $type)
    {
        $appro = Store_Requistion_approve::where('Store_Requistion_id', $id)->get();
        $approves = [];
        foreach ($appro as $val) {
            $val->user = Admin::find($val->userID);
            array_push($approves, $val);
        }

        $Organization_Name = prj_organisation::all();
        $Manufacturing_Unit = prj_project::all();
        $Plant_Name = Prj_Subproject::all();
        $UOM = Factory_Uom::all();
        $Godown_Name = Prj_Inventory::all();
        $employeeName = Admin::where('role', 1)->whereRaw('id IN (SELECT userID FROM employee_department where Departments="15")')->get();
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
        $edit = Store_Requistion::find($id);
        $Materials = array();
        if (isset($edit->id) && $edit->id != '') {
            $Materials = Store_Requistion_Material::where('Store_Requistion_id', $edit->id)->get();
        }

        $nextID = $this->next($id, $type);

        return view('StoreRequistion/StoreRequistionApprove', ['edit' => $edit, 'Organization_Name' => $Organization_Name, 'Manufacturing_Unit' => $Manufacturing_Unit, 'Plant_Name' => $Plant_Name, 'Raw_Material' => $Filtered_Array, 'UOM' => $UOM, 'Godown_Name' => $Godown_Name, 'Materials' => $Materials, 'approves' => $approves, 'nextID' => $nextID, 'employeeName' => $employeeName]);
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
        // echo "<pre>";
        // print_r($request->all());
        // echo "</pre>";
        
        $EXT = Session::get('EXT');

        if (!empty($request->during_approval)) {
            Store_Requistion::where('id', $request->approveID)->update(['Approve_status' => $request->during_approval]);
            Store_Requistion_approve::where('Store_Requistion_id', $request->approveID)->where('status', 1)->update(['status' => 0]);
          //  echo "ravi";
        }
        //die;
        $check = Store_Requistion::find($request->approveID);
        if ($request->during_approval === 'APPROVE') {
            $status = Forwarded_Data::where('DataID', $request->approveID)->update(['status' => 1]);
            Store_Requistion::where('id', $request->approveID)->update(['Forward_Status' => 0]);

            $DepartStepcount2 = Department_Assign::where(['departments' => 15, 'step' => 2])->count();
            $DepartStepcount3 = Department_Assign::where(['departments' => 15, 'step' => 3])->count();

            if ($check->Approve_Step == 1 && $DepartStepcount2 > 0) {
                Store_Requistion::where('id', $request->approveID)->update(['Approve_Step' => 2, 'Approve_status' => null]);
            }

            if ($check->Approve_Step == 2 &&  $DepartStepcount3 > 0) {
                Store_Requistion::where('id', $request->approveID)->update(['Approve_Step' => 3, 'Approve_status' => null]);
            }
        }

        if ($request->during_approval === 'REJECT') {
            MaterialManagement_Add_Material::where('id', $check->Raw_Material)->update(['Used_Status' => 0]);
        }

        if ($request->during_approval === 'FORWARD') {
            Forwarded_Data::where(['DepartmentID' => 15, 'DataID' => $request->approveID])->update(['status' => 1]);
            Store_Requistion::where('id', $request->approveID)->update(['Forward_Status' => 1]);

            $forward = new Forwarded_Data;
            $forward->userID = auth()->user()->id;
            $forward->Forward_To_id = $request->Forward_To;
            $forward->DepartmentID = 15;
            $forward->DataID = $request->approveID;
            $forward->status = 0;

            $forward->save();
        }

        $approve = new Store_Requistion_approve;
        $approve->userID = auth()->user()->id;
        if (auth()->user()->role == 0) {
            $approve->role = 'Admin';
        } elseif (isset($EXT[15]['Inputer'])) {
            $approve->role = 'Inputer';
        } elseif (isset($EXT[15]['approver'])) {
            $approve->role = 'Approver';
        } else {
            $approve->role = 'Viewer';
        }
        $approve->Store_Requistion_id = $request->approveID;
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
            Store_Requistion::where('id', $request->approveID)->update(['Approve_status' => null]);
            return redirect('StoreRequistion/StoreRequistionList')->with('success', 'successfully.....');
        } elseif (($request->pre_post_approval == 'AUDIT' || $request->pre_post_approval == 'INTIMATION' || $request->pre_post_approval == 'QUERY') && $request->non_acting == 1) {
            return redirect('StoreRequistion/StoreRequistionList')->with('success', 'successfull.....');
        } else {
            return redirect('StoreRequistion/StoreRequistionApproveList')->with('success', 'Approved successfully.....');
        }
    }


    public function CheckHoldExpiry()
    {
        $Approve = Store_Requistion_approve::all();

        return response()->json($Approve);
    }

    public function UpdateStatus(Request $request)
    {
        $Store_Requistion_id = $request->input('Store_Requistion_id');
        $userID = $request->input('userID');

        $approves = Store_Requistion_approve::where('Store_Requistion_id', $Store_Requistion_id)->where('userID', $userID)->update(['status' => 0]);
        $factory =  Store_Requistion::where('id', $Store_Requistion_id)->update(['Approve_status' => null]);

        $approve = new Store_Requistion_approve;
        $approve->role = 'AUTO';
        $approve->Store_Requistion_id = $Store_Requistion_id;
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

        $approvesss = Store_Requistion_approve::where('Store_Requistion_id', $id)->where('action', 'HOLD')->update(['days_for_holding' => $currentDate, 'status' => 0]);
        $factory =  Store_Requistion::where('id', $id)->update(['Approve_status' => null]);

        $approve = new Store_Requistion_approve;
        $approve->userID = auth()->user()->id;
        if (auth()->user()->role == 0) {
            $approve->role = 'Admin';
        } elseif (isset($EXT[15]['approver'])) {
            $approve->role = 'Approver';
        } elseif (isset($EXT[15]['inputer'])) {
            $approve->role = 'Inputer';
        } else {
            $approve->role = 'Viewer';
        }
        $approve->Store_Requistion_id = $id;
        $approve->status = 1;
        $approve->action = 'Hold Released';
        $approve->comment_text = $request->comment_text;
        $approve->ip_address = $request->getClientIp();
        $approve->device_name = $request->server('HTTP_USER_AGENT');
        $approve->save();


        return redirect('StoreRequistion/StoreRequistionList')->with('success', 'Hold Released successfully.....');
    }
}
