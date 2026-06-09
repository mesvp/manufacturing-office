<?php

namespace App\Http\Controllers\RawMaterial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\Plant\{Master_Manufacturing_unit};
use App\Models\RawMaterial\{RawMaterial_stock, RawMaterial, RawMaterial_data, RawMaterial_approve};
use App\Models\FactoryCreater\{Factory_Organisation, Factory_Uom};
use App\Models\Master\RawMaterial\{Master_Godown_Name, Master_Raw_Material, Master_OB, Master_Rack_No, Master_Sub_Rack_No, Master_Bin_No, Master_Sub_Bin_No};
use App\Models\Master\Gatepass\Master_employee_names;
use App\Models\{CheckBox, Admin, Department_Assign, Forwarded_Data};
use App\Models\MaterialManagement\{MaterialManagement_Add_Material};
use Session;


class RawMaterialApproveController extends Controller
{
    public function RawMaterial_approve(Request $request)
    {
        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));

        $EXT = Session::get('EXT');
        $STEP = Session::get('STEP');

        $query = new RawMaterial_stock;

        if ($fromdate && $todate) {
            $query = $query->whereBetween('created_at', [$fromdate, $todate]);
        }

        if (isset($EXT[6]['Forward']) && isset($EXT[6]['approver'])) {
            $query = $query->where(function ($query) use ($EXT) {
                $query->where('Approve_status', null)->where('Forward_Status', 0)->whereRaw("Approve_Step IN (" . implode(",", $EXT[6]['approver']) . ")");
            })
                ->orWhere(function ($query) {
                    $query->whereRaw('id IN (SELECT DataID FROM Forwarded_Data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1');
                })
                ->orWhereRaw('id IN (SELECT DataID FROM Forwarded_Data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1')
                ->orderBy('id', 'DESC');
        } elseif (isset($EXT[6]['Forward'])) {
            $query = $query->where('Forward_Status', 1)->whereRaw('id IN (SELECT DataID FROM Forwarded_Data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD")')->orderBy('id', 'DESC');
        } elseif (isset($EXT[6]['approver'])) {
            $query = $query->where('Approve_status', null)->where(['Forward_Status' => 0, 'status' => 0])->WhereRaw("Approve_Step IN (" . implode(",", $EXT[6]['approver']) . ")")->orderBy('id', 'DESC');
        }

        $rowStock = $query->get();

        $rowStock_arr = array();
        foreach ($rowStock as $val) {
            $val->raw = RawMaterial::where('RawMaterial_stock_id', $val->id)->first();
            if (isset($val->raw)) {
                if ($val->Forward_Status != 1) {
                    $val->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `Department_Assign` WHERE departments="6" AND step="' . $val->Approve_Step . '")')->get();
                } else {
                    $val->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `Forwarded_Data` WHERE DataID="' . $val->id . '" AND DepartmentID=6 AND status=0)')->get();
                }
                $val->user = Admin::find($val->userID);
                $val->rawss = RawMaterial_data::where('RawMaterial_id', $val->raw->id)->first();
                $val->Organization = Factory_Organisation::find($val->raw->Organization);
                $val->Manufacturing_Unit = Master_Manufacturing_unit::find($val->raw->Manufacturing_Unit);
                $val->Godown_Name = Master_Godown_Name::find($val->raw->Godown_name);
                if ($val->rawss != '') {
                    $val->Raw_Material = MaterialManagement_Add_Material::find($val->rawss->Raw_Material);
                    $val->OB = Master_OB::find($val->rawss->OB);
                    $val->UOM = Factory_Uom::find($val->rawss->UOM);
                }
            }

            array_push($rowStock_arr, $val);
        }

        return view('RawMaterial/RawMaterialApproveList', ['rowStock' => $rowStock_arr, 'fromdate' => $fromdate, 'todate' => $dateto]);
    }

    public function view_approve($id, $type)
    {
        $approvestatus = RawMaterial_approve::where('RawMaterial_stock__id', $id)->where('status', '1')->first();

        $appro = RawMaterial_approve::where('RawMaterial_stock__id', $id)->orderBy('status', 'ASC')->get();
        $approves = [];
        foreach ($appro as $val) {
            $val->user = Admin::find($val->userID);
            array_push($approves, $val);
        }

        $Organization = Factory_Organisation::all();
        $Manufacturing_Unit = Master_Manufacturing_unit::all();
        $Godown_Name = Master_Godown_Name::all();
        $Raw_Material = MaterialManagement_Add_Material::where('Approve_status', 'APPROVE')->get();
        $OB = Master_OB::all();
        $Rack_No = Master_Rack_No::all();
        $Sub_Rack_No = Master_Sub_Rack_No::all();
        $Bin_No = Master_Bin_No::all();
        $Sub_Bin_No = Master_Sub_Bin_No::all();
        $UOM = Factory_Uom::all();
        $employeeName = Admin::where('role', 1)->whereRaw('id IN (SELECT userID FROM Employee_Department where Departments="6")')->get();

        $edit = RawMaterial_stock::find($id);
        $raw = array();
        $raw_arr = array();
        $raw_count = '';
        $raw_data_count = '';
        if (isset($edit->id) && $edit->id != '') {
            $raw = RawMaterial::where('RawMaterial_stock_id', $edit->id)->get();
            $raw_count = RawMaterial::where('RawMaterial_stock_id', $edit->id)->count();
            foreach ($raw as $val) {
                $val->raw_data = RawMaterial_data::where('RawMaterial_id', $val->id)->get();
                $raw_data_count = RawMaterial_data::where('RawMaterial_id', $val->id)->count();

                array_push($raw_arr, $val);
            }
        }

        $nextID = $this->next($id, $type);

        return view('RawMaterial/RawMaterialApprove', ['edit' => $edit, 'raw' => $raw_arr, 'Manufacturing_Unit' => $Manufacturing_Unit, 'Organization' => $Organization, 'raw_count' => $raw_count, 'raw_data_count' => $raw_data_count, 'Godown_Name' => $Godown_Name, 'Raw_Material' => $Raw_Material, 'OB' => $OB, 'Rack_No' => $Rack_No, 'Sub_Rack_No' => $Sub_Rack_No, 'Bin_No' => $Bin_No, 'Sub_Bin_No' => $Sub_Bin_No, 'UOM' => $UOM, 'approvestatus' => $approvestatus, 'approves' => $approves, 'employeeName' => $employeeName, 'nextID' => $nextID]);
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
            RawMaterial_stock::where('id', $request->approveID)->update(['Approve_status' => $request->during_approval]);
            RawMaterial_approve::where('RawMaterial_stock__id', $request->approveID)->where('status', 1)->update(['status' => 0]);
        }

        $check = RawMaterial_stock::find($request->approveID);
        if ($request->during_approval === 'APPROVE') {
            $status = Forwarded_Data::where('DataID', $request->approveID)->update(['status' => 1]);
            RawMaterial_stock::where('id', $request->approveID)->update(['Forward_Status' => 0]);

            $DepartStepcount2 = Department_Assign::where(['departments' => 6, 'step' => 2])->count();
            $DepartStepcount3 = Department_Assign::where(['departments' => 6, 'step' => 3])->count();

            if ($check->Approve_Step == 1 && $DepartStepcount2 > 0) {
                RawMaterial_stock::where('id', $request->approveID)->update(['Approve_Step' => 2, 'Approve_status' => null]);
            }

            if ($check->Approve_Step == 2 &&  $DepartStepcount3 > 0) {
                RawMaterial_stock::where('id', $request->approveID)->update(['Approve_Step' => 3, 'Approve_status' => null]);
            }
        }

        if ($request->during_approval === 'REJECT') {
            $RawMaterial = RawMaterial::where('RawMaterial_stock_id', $request->approveID)->get();
            foreach ($RawMaterial as $Value) {
                $data = RawMaterial_data::where('RawMaterial_id', $Value->id)->get();
                foreach ($data as $DataVal) {
                    MaterialManagement_Add_Material::where('id', $DataVal->Raw_Material)->update(['Used_Status_RM' => 0]);
                }
            }
        }

        if ($request->during_approval === 'FORWARD') {
            Forwarded_Data::where(['DepartmentID' => 6, 'DataID' => $request->approveID])->update(['status' => 1]);
            RawMaterial_stock::where('id', $request->approveID)->update(['Forward_Status' => 1]);

            $forward = new Forwarded_Data;
            $forward->userID = auth()->user()->id;
            $forward->Forward_To_id = $request->Forward_To;
            $forward->DepartmentID = 6;
            $forward->DataID = $request->approveID;
            $forward->status = 0;

            $forward->save();
        }

        $approve = new RawMaterial_approve;
        $approve->userID = auth()->user()->id;
        if (auth()->user()->role == 0) {
            $approve->role = 'Admin';
        } elseif ($check->Approve_status == 'OBJECT') {
            $approve->role = 'Inputer';
        } elseif (isset($EXT[6]['approver'])) {
            $approve->role = 'Approver';
        } else {
            $approve->role = 'Viewer';
        }
        $approve->RawMaterial_stock__id = $request->approveID;
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
            RawMaterial_stock::where('id', $request->approveID)->update(['Approve_status' => null]);
            return redirect('RawMaterial/RawMaterialList')->with('success', 'successfully.....');
        } elseif (($request->pre_post_approval == 'AUDIT' || $request->pre_post_approval == 'INTIMATION' || $request->pre_post_approval == 'QUERY') && $request->non_acting == 1) {
            return redirect('RawMaterial/RawMaterialList')->with('success', 'successfull.....');
        } else {
            return redirect('RawMaterial/RawMaterialApproveList')->with('success', 'Approved successfully.....');
        }
    }

    public function CheckHoldExpiry()
    {
        $Approve = RawMaterial_approve::all();

        return response()->json($Approve);
    }

    public function UpdateStatus(Request $request)
    {
        $RawMaterial_stock__id = $request->input('RawMaterial_stock__id');
        $userID = $request->input('userID');

        $approves = RawMaterial_approve::where('RawMaterial_stock__id', $RawMaterial_stock__id)->where('userID', $userID)->update(['status' => 0]);
        $factory =  RawMaterial_stock::where('id', $RawMaterial_stock__id)->update(['Approve_status' => null]);

        $approve = new RawMaterial_approve;
        $approve->role = 'AUTO';
        $approve->RawMaterial_stock__id = $RawMaterial_stock__id;
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

        $approves = RawMaterial_approve::where('RawMaterial_stock__id', $id)->where('action', 'HOLD')->update(['days_for_holding' => '', 'status' => 0]);
        $factory =  RawMaterial_stock::where('id', $id)->update(['Approve_status' => null]);

        $approve = new RawMaterial_approve;
        $approve->userID = auth()->user()->id;
        if (auth()->user()->role == 0) {
            $approve->role = 'Admin';
        } elseif (isset($EXT[6]['approver'])) {
            $approve->role = 'Approver';
        } elseif (isset($EXT[6]['inputer'])) {
            $approve->role = 'Inputer';
        } else {
            $approve->role = 'Viewer';
        }
        $approve->RawMaterial_stock__id = $id;
        $approve->status = 1;
        $approve->action = 'Hold Released';
        $approve->comment_text = $request->comment_text;
        $approve->ip_address = $request->getClientIp();
        $approve->device_name = $request->server('HTTP_USER_AGENT');
        $approve->save();

        return redirect('RawMaterial/RawMaterialList')->with('success', 'Hold Released successfully.....');
    }
}
