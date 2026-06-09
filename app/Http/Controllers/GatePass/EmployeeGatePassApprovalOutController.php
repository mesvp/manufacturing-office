<?php

namespace App\Http\Controllers\Gatepass;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{CheckBox, Admin,PlantStock, Department_Assign};
use App\Models\Master\{Master_Request_Type, Master_Department, Master_Person_To_Meet, Prj_Subproject, Prj_Project, Master_Request_Through, Master_Contact_Person, Master_Type, Master_Type_Details};
use App\Models\GatePass\{Gatepass_Employee, EmployeeGatePassApproval,Forwarded_Data_Gatepass};
use App\Models\MaterialManagement\{MaterialManagement_Add_Material, MaterialManagement_Add_Material_Stock};
use App\Models\FactoryCreater\{Factory_Organisation, Factory_Uom,prj_organisation,Factory_Address_Detail,Factory_Plant_Machinery};

use Session;
use Illuminate\Support\Facades\Storage;
use \PDF;
use App\Models\BOM\BOM;

class EmployeeGatePassApprovalOutController extends Controller
{
    public function EmployeeGatepassOut_ApproveList(Request $request)
    {
        $CUSTEXT = Session::get('CUSTEXT');
        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));
        if (isset($CUSTEXT[2]['Forward']) || isset($CUSTEXT[2]['approver'])) {
            $query = Gatepass_Employee::query();
        } else {
            return view('GatePass.Employee_Gatepass_Out_Approval', ['employeedata' => [], 'DropdownData' => [], 'empName' => [], 'fromdate' => $fromdate, 'todate' => $dateto, 'RequestNos' => '', 'RequestBys' => '', 'GatePassNos' => '', 'RequestOutTimes' => '', 'RequestInTimes' => '', 'Manufacturing_unitdata' => [], 'plant_namedata' => [], 'CostCenter' => '', 'sub_CostCenter' => '', 'SecurityGuards' => '', 'PersonWithVehicles' => '', 'Projects' => [], 'Sub_Projects' => []]);
        }

        if ($fromdate && $todate) {
            $query->whereBetween('created_at', [$fromdate, $todate]);
        }

        $CostCenter = '';
        if ($request->has('Cost_Center') && $request->input('Cost_Center') != '') {
            $CostCenter = $request->input('Cost_Center');
            if ($CostCenter !== 'all') {
                $query->where('project_id', $CostCenter);
            }
        }

        $sub_CostCenter = '';
        if ($request->has('Sub_Cost_Center') && $request->input('Sub_Cost_Center') != '') {
            $sub_CostCenter = $request->input('Sub_Cost_Center');
            if ($sub_CostCenter !== 'all') {
                $query->where('subproject_id', $sub_CostCenter);
            }
        }

        $SecurityGuards = '';
        if ($request->has('Security_Guard') && $request->input('Security_Guard') != '') {
            $SecurityGuards = $request->input('Security_Guard');
            if ($SecurityGuards !== 'all') {
                $query->where('out_sec_guard', $SecurityGuards);
            }
        }

        $PersonWithVehicles = '';
        if ($request->has('Person_With_Vehicle') && $request->input('Person_With_Vehicle') != '') {
            $PersonWithVehicles = $request->input('Person_With_Vehicle');
            if ($PersonWithVehicles !== 'all') {
                $query->where('prsn_vehicle', $PersonWithVehicles);
            }
        }

        $RequestNo = '';
        if ($request->has('Request_No') && $request->input('Request_No') != '') {
            $RequestNo = $request->input('Request_No');
            if ($RequestNo !== 'all') {
                $query->where('out_request_no', $RequestNo);
            }
        }

        $RequestBy = '';
        if ($request->has('Request_By') && $request->input('Request_By') != '') {
            $RequestBy = $request->input('Request_By');
            if ($RequestBy !== 'all') {
                $query->where('out_request_by', $RequestBy);
            }
        }

        $GatePassNo = '';
        if ($request->has('Gate_Pass_No') && $request->input('Gate_Pass_No') != '') {
            $GatePassNo = $request->input('Gate_Pass_No');
            if ($GatePassNo !== 'all') {
                $query->where('gate_pass_no', $GatePassNo);
            }
        }

        $RequestOutTime = '';
		if ($request->has('Request_Out_Time') && $request->input('Request_Out_Time') != '') {
			$RequestOutTime = date('Y-m-d\TH:i', strtotime($request->input('Request_Out_Time')));
			$query->where('request_out_time', 'like', '%' . date('d-m-Y h:i A', strtotime($request->input('Request_Out_Time'))) . '%');
		}

		$RequestInTime = '';
		if ($request->has('Request_In_Time') && $request->input('Request_In_Time') != '') {
			$RequestInTime = date('Y-m-d\TH:i', strtotime($request->input('Request_In_Time')));
			$query->where('request_in_time', 'like', '%' . date('d-m-Y h:i A', strtotime($request->input('Request_In_Time'))) . '%');
		}

        // Handle "out" approvals
        if (isset($CUSTEXT[2]['Forward']) && isset($CUSTEXT[2]['approver'])) {
            $query = $query->where(function ($query) use ($CUSTEXT) {
                $query->where('Out_Approve_status', null)->where('Out_Forward_Status', 0)->whereRaw("Out_Approve_Step IN (" . implode(",", $CUSTEXT[2]['approver']) . ")");
            })
            ->orWhere(function ($query) {
                $query->whereRaw('out_request_no IN (SELECT DataID FROM forwarded_data_gatepass WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Out_Approve_status IS NULL OR Out_Approve_status="FORWARD") AND `Out_Forward_Status` = 1');
            })
            ->orWhereRaw('out_request_no IN (SELECT DataID FROM forwarded_data_gatepass WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Out_Approve_status IS NULL OR Out_Approve_status="FORWARD") AND `Out_Forward_Status` = 1')
            ->orderBy('id', 'DESC');
        } elseif (isset($CUSTEXT[2]['Forward'])) {
            $query = $query->where('Out_Forward_Status', 1)->whereRaw('out_request_no IN (SELECT DataID FROM forwarded_data_gatepass WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Out_Approve_status IS NULL OR Out_Approve_status="FORWARD")')->orderBy('id', 'DESC');
        } elseif (isset($CUSTEXT[2]['approver'])) {
            $query = $query->where('Out_Approve_status', null)->where(['Out_Forward_Status' => 0, 'status' => 0])->WhereRaw("Out_Approve_Step IN (" . implode(",", $CUSTEXT[2]['approver']) . ")")->orderBy('id', 'DESC');
        }

        $employeedata = $query->get();
        $ForDropdown = Gatepass_Employee::orderBy('id', 'DESC')->get();

        $ForDropdown_arr = array();
        foreach ($ForDropdown as $val) {
            $val->emp_name = Admin::find($val->employee_name);
            $val->request_type = Master_Request_Type::find($val->request_type);
            array_push($ForDropdown_arr, $val);
        }

        $empName = Admin::where('role', 1)->get();
        $Manufacturing_unitdata = Prj_Project::all_mu();
        $plant_namedata = Prj_Subproject::all_pm();
        $Projects = Factory_Address_Detail::select('prj_project.*')
        ->leftJoin('prj_project','factory_address_details.name_of_unit','=','prj_project.id')
        ->where('Approve_status','APPROVE')
        ->groupBy('prj_project.pname')
        ->get();
        // $Sub_Projects = Prj_Subproject::whereIn('pid', $Projects->pluck('id'))->get();
		$Sub_Projects = Factory_Plant_Machinery::select('prj_subproject.*','prj_organisation.organisation','prj_organisation.id as orgid')
		->leftJoin('prj_subproject', 'factory_plant_machineries.Plant_Name', '=', 'prj_subproject.id')
		->leftJoin('factory_address_details', 'factory_plant_machineries.factory_id', '=', 'factory_address_details.id')
		->leftJoin('prj_organisation', 'factory_address_details.organization', '=', 'prj_organisation.id')
		->whereIn('factory_address_details.name_of_unit',$Projects->pluck('id'))
		->where('factory_address_details.Approve_status', 'APPROVE')
		->whereNotNull('prj_subproject.spname')
		->get();
        foreach ($employeedata as $val) {
            if ($val->Out_Forward_Status != 1) {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="2" AND step="' . $val->Out_Approve_Step . '")')->get();
            } else {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data_gatepass` WHERE DataID="' . $val->out_request_no . '" AND DepartmentID=2 AND `status`=0)')->get();
            }
        }
        return view('GatePass.Employee_Gatepass_Out_Approval', ['employeedata' => $employeedata, 'DropdownData' => $ForDropdown_arr, 'empName' => $empName, 'fromdate' => $fromdate, 'todate' => $dateto, 'RequestNos' => $RequestNo, 'RequestBys' => $RequestBy, 'GatePassNos' => $GatePassNo, 'RequestOutTimes' => $RequestOutTime, 'RequestInTimes' => $RequestInTime, 'Manufacturing_unitdata' => $Manufacturing_unitdata, 'plant_namedata' => $plant_namedata, 'CostCenter' => $CostCenter, 'sub_CostCenter' => $sub_CostCenter, 'SecurityGuards' => $SecurityGuards, 'PersonWithVehicles' => $PersonWithVehicles, 'Projects' => $Projects, 'Sub_Projects' => $Sub_Projects]);
    }

    public function Out_approve(Request $request)
    {
        $CUSTEXT = Session::get('CUSTEXT');
        if (!empty($request->during_approval)) {
            Gatepass_Employee::where('out_request_no', $request->req_no)->update(['Out_Approve_status' => $request->during_approval]);
            EmployeeGatePassApproval::where('GatepassID', $request->req_no)->where('status', 1)->update(['status' => 0]);
        }
        $check = Gatepass_Employee::find($request->approveID);
        if ($request->during_approval === 'APPROVE') {
            $status = Forwarded_Data_Gatepass::where('DataID', $request->req_no)->update(['status' => 1]);
            Gatepass_Employee::where('out_request_no', $request->req_no)->update(['Out_Forward_Status' => 0]);

            $DepartStepcount2 = Department_Assign::where(['departments' => 2, 'step' => 2])->count();
            $DepartStepcount3 = Department_Assign::where(['departments' => 2, 'step' => 3])->count();
            $prod=true;
            if ($check->Out_Approve_Step == 1 && $DepartStepcount2 > 0) {
                Gatepass_Employee::where('out_request_no', $request->req_no)->update(['Out_Approve_Step' => 2, 'Out_Approve_status' => null]);
                $prod=false;
            }

            if ($check->Out_Approve_Step == 2 &&  $DepartStepcount3 > 0) {
                Gatepass_Employee::where('out_request_no', $request->req_no)->update(['Out_Approve_Step' => 3, 'Out_Approve_status' => null]);
                $prod=false;
            }
        }
        if ($request->during_approval === 'REJECT') {
            $prod=Gatepass_Employee::find($request->approveID);
        }
        if ($request->during_approval === 'RECHECK') {
            $prod=Gatepass_Employee::find($request->approveID);
        }
        if ($request->during_approval === 'FORWARD') {
            Forwarded_Data_Gatepass::where(['DepartmentID' => 2, 'DataID' => $request->req_no])->update(['status' => 1]);
            Gatepass_Employee::where('id', $request->approveID)->update(['Out_Forward_Status' => 1]);

            $forward = new Forwarded_Data_Gatepass;
            $forward->userID = auth()->user()->id;
            $forward->Forward_To_id = $request->Forward_To;
            $forward->DepartmentID = 2;
            $forward->DataID = $request->req_no;
            $forward->remarks = $request->comment_text;
            $forward->status = 0;

            $forward->save();
        }

        $approve = new EmployeeGatePassApproval;
        $approve->userID = auth()->user()->id;
        if (auth()->user()->role == 0) {
            $approve->role = 'Admin';
        } elseif (isset($CUSTEXT[2]['Inputer'])) {
            $approve->role = 'Inputer';
        } elseif (isset($CUSTEXT[2]['approver'])) {
            $approve->role = 'Approver';
        } else {
            $approve->role = 'Viewer';
        }
        $approve->GatepassID = $request->req_no;
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
            Gatepass_Employee::where('id', $request->approveID)->update(['Out_Approve_status' => null]);
            return redirect('GatePass/List')->with('success', 'successfully.....');
        } elseif (($request->pre_post_approval == 'AUDIT' || $request->pre_post_approval == 'INTIMATION' || $request->pre_post_approval == 'QUERY') && $request->non_acting == 1) {
            return redirect('GatePass/List')->with('success', 'successfully.....');
        } else {
            return redirect('GatePass/Employee_Gatepass_Out_Approval')->with('success', 'Approved successfully.....');
        }
    }

    public function Out_Release_Hold(Request $request, $id)
    {
        $CUSTEXT = Session::get('CUSTEXT');
        $currentDate = now();

        $approvesss = EmployeeGatePassApproval::where('GatepassID', $id)->where('action', 'HOLD')->update(['days_for_holding' => $currentDate, 'status' => 0]);
        $emp_gatepass =  Gatepass_Employee::where('out_request_no', $id)->update(['Out_Approve_status' => null]);

        $approve = new EmployeeGatePassApproval;
        $approve->userID = auth()->user()->id;
        if (auth()->user()->role == 0) {
            $approve->role = 'Admin';
        } elseif (isset($CUSTEXT[2]['approver'])) {
            $approve->role = 'Approver';
        } elseif (isset($CUSTEXT[2]['inputer'])) {
            $approve->role = 'Inputer';
        } else {
            $approve->role = 'Viewer';
        }
        $approve->GatepassID = $id;
        $approve->status = 1;
        $approve->action = 'Hold Released';
        $approve->comment_text = $request->comment_text;
        $approve->ip_address = $request->getClientIp();
        $approve->device_name = $request->server('HTTP_USER_AGENT');
        $approve->save();

        return redirect('GatePass/List')->with('success', 'Hold Released successfully.....');
    }

}
