<?php

namespace App\Http\Controllers\Gatepass;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{CheckBox, Admin,PlantStock, Department_Assign};
use App\Models\Master\{Master_Request_Type, Master_Department, Master_Person_To_Meet, Prj_Subproject, Prj_Project, Master_Request_Through, Master_Contact_Person};
use App\Models\GatePass\{Gatepass_Visitor, VisitorGatePassApproval,Forwarded_Data_Gatepass};
use App\Models\MaterialManagement\{MaterialManagement_Add_Material, MaterialManagement_Add_Material_Stock};
use App\Models\FactoryCreater\{Factory_Organisation, Factory_Uom,prj_organisation,Factory_Address_Detail,Factory_Plant_Machinery};

use Session;
use Illuminate\Support\Facades\Storage;
use \PDF;
use App\Models\BOM\BOM;

class VisitorGatePassApprovalController extends Controller
{
    public function VisitorGatepassApproveList(Request $request)
    {
        $CUSTEXT = Session::get('CUSTEXT');

        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));
        $query = Gatepass_Visitor::query();

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
                $query->where('sec_guard', $SecurityGuards);
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
                $query->where('request_no', $RequestNo);
            }
        }

        $RequestBy = '';
        if ($request->has('Request_By') && $request->input('Request_By') != '') {
            $RequestBy = $request->input('Request_By');
            if ($RequestBy !== 'all') {
                $query->where('request_by', $RequestBy);
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

        if (isset($CUSTEXT[2]['Forward']) && isset($CUSTEXT[2]['approver'])){
            $query = $query->where(function ($query) use ($CUSTEXT) {
                $query->where('Approve_status', null)->where('Forward_Status', 0)->whereRaw("Approve_Step IN (" . implode(",", $CUSTEXT[2]['approver']) . ")");
            })
            ->orWhere(function ($query) {
                $query->whereRaw('request_no IN (SELECT DataID FROM forwarded_data_gatepass WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1');
            })
            ->orWhereRaw('request_no IN (SELECT DataID FROM forwarded_data_gatepass WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1')
            ->orderBy('id', 'DESC');
        }
        elseif (isset($CUSTEXT[2]['Forward'])){
            $query = $query->where('Forward_Status', 1)->whereRaw('request_no IN (SELECT DataID FROM forwarded_data_gatepass WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD")')->orderBy('id', 'DESC');
        }
        elseif (isset($CUSTEXT[2]['approver'])){
            $query = $query->where('Approve_status', null)->where(['Forward_Status' => 0, 'status' => 0])->WhereRaw("Approve_Step IN (" . implode(",", $CUSTEXT[2]['approver']) . ")")->orderBy('id', 'DESC');
        }

        $visitordata = $query->get();
        $ForDropdown = Gatepass_Visitor::orderBy('id', 'DESC')->get();

        $ForDropdown_arr = array();
        foreach ($ForDropdown as $val) {
            $val->visitor_name = Admin::find($val->visitor_name);
            $val->request_type = Master_Request_Type::find($val->request_type);
            array_push($ForDropdown_arr, $val);
        }

        $visitorName = Admin::where('role', 1)->get();
        $Manufacturing_unitdata = Prj_Project::all_mu();
        $plant_namedata = Prj_Subproject::all_pm();
        $Projects = Factory_Address_Detail::select('prj_project.*')->leftJoin('prj_project','factory_address_details.name_of_unit','=','prj_project.id')->where('Approve_status','APPROVE')->groupBy('prj_project.pname')->get();
		$Sub_Projects = Factory_Plant_Machinery::select('prj_subproject.*','prj_organisation.organisation','prj_organisation.id as orgid')
		->leftJoin('prj_subproject', 'factory_plant_machineries.Plant_Name', '=', 'prj_subproject.id')
		->leftJoin('factory_address_details', 'factory_plant_machineries.factory_id', '=', 'factory_address_details.id')
		->leftJoin('prj_organisation', 'factory_address_details.organization', '=', 'prj_organisation.id')
		->whereIn('factory_address_details.name_of_unit',$Projects->pluck('id'))
		->where('factory_address_details.Approve_status', 'APPROVE')
		->whereNotNull('prj_subproject.spname')
		->get();
// dd($Sub_Projects);
        // $Sub_Projects = Prj_Subproject::whereIn('pid', $Projects->pluck('id'))->get();
        foreach ($visitordata as $val) {
            if ($val->Forward_Status != 1) {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="2" AND step="' . $val->Approve_Step . '")')->get();
            } else {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data_gatepass` WHERE DataID="' . $val->request_no . '" AND DepartmentID=2 AND `status`=0)')->get();
            }
        }
        return view('GatePass.Visitor_Gatepass_Approval', ['visitordata' => $visitordata, 'DropdownData' => $ForDropdown_arr, 'visitorName' => $visitorName, 'fromdate' => $fromdate, 'todate' => $dateto, 'RequestNos' => $RequestNo, 'RequestBys' => $RequestBy, 'GatePassNos' => $GatePassNo, 'RequestOutTimes' => $RequestOutTime, 'RequestInTimes' => $RequestInTime, 'Manufacturing_unitdata' => $Manufacturing_unitdata, 'plant_namedata' => $plant_namedata, 'CostCenter' => $CostCenter, 'sub_CostCenter' => $sub_CostCenter, 'SecurityGuards' => $SecurityGuards, 'PersonWithVehicles' => $PersonWithVehicles, 'Projects' => $Projects, 'Sub_Projects' => $Sub_Projects]);
    }

    public function approve(Request $request)
    {
        $CUSTEXT = Session::get('CUSTEXT');
        if (!empty($request->during_approval)) {
            Gatepass_Visitor::where('request_no', $request->req_no)->update(['Approve_status' => $request->during_approval]);
            VisitorGatePassApproval::where('GatepassID', $request->req_no)->where('status', 1)->update(['status' => 0]);
        }
        $check = Gatepass_Visitor::find($request->approveID);
        if ($request->during_approval === 'APPROVE') {
            $status = Forwarded_Data_Gatepass::where('DataID', $request->req_no)->update(['status' => 1]);
            Gatepass_Visitor::where('request_no', $request->req_no)->update(['Forward_Status' => 0]);

            $DepartStepcount2 = Department_Assign::where(['departments' => 2, 'step' => 2])->count();
            $DepartStepcount3 = Department_Assign::where(['departments' => 2, 'step' => 3])->count();
            $prod=true;
            if ($check->Approve_Step == 1 && $DepartStepcount2 > 0) {
                Gatepass_Visitor::where('request_no', $request->req_no)->update(['Approve_Step' => 2, 'Approve_status' => null]);
                $prod=false;
            }

            if ($check->Approve_Step == 2 &&  $DepartStepcount3 > 0) {
                Gatepass_Visitor::where('request_no', $request->req_no)->update(['Approve_Step' => 3, 'Approve_status' => null]);
                $prod=false;
            }
        }
        if ($request->during_approval === 'REJECT') {
            $prod=Gatepass_Visitor::find($request->approveID);
        }
        if ($request->during_approval === 'RECHECK') {
            $prod=Gatepass_Visitor::find($request->approveID);
        }
        if ($request->during_approval === 'FORWARD') {
            Forwarded_Data_Gatepass::where(['DepartmentID' => 2, 'DataID' => $request->req_no])->update(['status' => 1]);
            Gatepass_Visitor::where('id', $request->approveID)->update(['Forward_Status' => 1]);

            $forward = new Forwarded_Data_Gatepass;
            $forward->userID = auth()->user()->id;
            $forward->Forward_To_id = $request->Forward_To;
            $forward->DepartmentID = 2;
            $forward->DataID = $request->req_no;
            $forward->remarks = $request->comment_text;
            $forward->status = 0;

            $forward->save();
        }

        $approve = new VisitorGatePassApproval;
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
            Gatepass_Visitor::where('id', $request->approveID)->update(['Approve_status' => null]);
            return redirect('GatePass/visitor-list')->with('success', 'successfully.....');
        } elseif (($request->pre_post_approval == 'AUDIT' || $request->pre_post_approval == 'INTIMATION' || $request->pre_post_approval == 'QUERY') && $request->non_acting == 1) {
            return redirect('GatePass/visitor-list')->with('success', 'successfull.....');
        } else {
            return redirect('GatePass/Visitor_Gatepass_Approval')->with('success', 'Approved successfully.....');
        }
    }
    public function Release_Hold(Request $request, $id)
    {
        $CUSTEXT = Session::get('CUSTEXT');
        $currentDate = now();

        $approvesss = VisitorGatePassApproval::where('GatepassID', $id)->where('action', 'HOLD')->update(['days_for_holding' => $currentDate, 'status' => 0]);
        $visitor_gatepass =  Gatepass_Visitor::where('request_no', $id)->update(['Approve_status' => null]);

        $approve = new VisitorGatePassApproval;
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
        return redirect('GatePass/visitor-list')->with('success', 'Hold Released successfully.....');
    }

    public function ExportVisitorApproval(Request $request)
    {
        $query = Gatepass_Visitor::orderBy('id', 'DESC');

        if (isset($CUSTEXT[2]['Forward']) && isset($CUSTEXT[2]['approver'])) {
            $query->where(function ($query) use ($CUSTEXT) {
                $query->where('Approve_status', null)->where('Forward_Status', 0)
                    ->whereRaw("Approve_Step IN (" . implode(",", $CUSTEXT[2]['approver']) . ")");
            })->orWhereRaw('request_no IN (SELECT DataID FROM forwarded_data_gatepass WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1')
            ->orderBy('id', 'DESC');
        } elseif (isset($CUSTEXT[2]['Forward'])) {
            $query->where('Forward_Status', 1)->whereRaw('request_no IN (SELECT DataID FROM forwarded_data_gatepass WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD")')->orderBy('id', 'DESC');
        } elseif (isset($CUSTEXT[2]['approver'])) {
            $query->where('Approve_status', null)->where(['Forward_Status' => 0, 'status' => 0])
                ->whereRaw("Approve_Step IN (" . implode(",", $CUSTEXT[2]['approver']) . ")")
                ->orderBy('id', 'DESC');
        }
        $visitordata = $query->get();

        $visitordata_arr = array();
        foreach ($visitordata as $val) {
            $val->visitor_name = Admin::find($val->visitor_name);
            array_push($visitordata_arr, $val);
        }

        $Checkbox = CheckBox::where('userID', auth()->user()->id)->where('tableID', 2)->get();
        $Checkbox_Arr = [];
        foreach ($Checkbox as $val) {
            $valuee = $val->CheckBox;
            array_push($Checkbox_Arr, $valuee);
        }

        $d = [];
        foreach ($visitordata_arr as $key => $val) {
            $rowData = [
                "SL. No." => $key + 1,
                "IN Request No" => isset($val->request_no) && $val->request_no != '' ? $val->request_no : '',
                "OUT Request No" => isset($val->out_request_no) && $val->out_request_no != '' ? $val->out_request_no : '',
                "Request By" => isset($val->request_by) && $val->request_by != '' ? $val->request_by : '',
                "Gate Pass No" => isset($val->gate_pass_no) && $val->gate_pass_no != '' ? $val->gate_pass_no : '',
                "Cost Center" => isset($val->project_id) && $val->project_id != '' ? Prj_Project::find($val->project_id)->pname : '',
                "Sub Cost Center" => isset($val->subproject_id) && $val->subproject_id != '' ? Prj_Subproject::find($val->subproject_id)->spname : '',
                "Organisation" => isset($val->org_id) && $val->org_id != '' ? prj_organisation::find($val->org_id)->organisation : '',
                "Request Out Time" => isset($val->request_out_time) && $val->request_out_time != '' ? date('h:i A', strtotime($val->request_out_time)) : '',
                "Request In Time" => isset($val->request_in_time) && $val->request_in_time != '' ? date('h:i A', strtotime($val->request_in_time)) : '',
                "Actual Out Time" => isset($val->actual_out_time) && $val->actual_out_time != '' ? date('h:i A', strtotime($val->actual_out_time)) : '',
                "Person With Vehicle" => $val->prsn_vehicle = $val->prsn_vehicle == 1 ? 'Yes' : 'No',
                "Vehicle Type" => isset($val->vehicle_type) && $val->vehicle_type != '' ? $val->vehicle_type : '',
                "Vehicle No" => isset($val->vehicle_no) && $val->vehicle_no != '' ? $val->vehicle_no : '',
                "Security Guard" => isset($val->sec_guard) && $val->sec_guard != '' ? $val->sec_guard : '',
                "Security Guard No" => isset($val->sec_guard_no) && $val->sec_guard_no != '' ? $val->sec_guard_no : '',
                "OUT Security Guard" => isset($val->out_sec_guard) && $val->out_sec_guard != '' ? $val->out_sec_guard : '',
                "OUT Security Guard No" => isset($val->out_sec_guard_no) && $val->out_sec_guard_no != '' ? $val->out_sec_guard_no : '',
                "Visit Purpose" => isset($val->visit_purpose) && $val->visit_purpose != '' ? $val->visit_purpose : '',
                "Out Visit Purpose" => isset($val->out_visit_purpose) && $val->out_visit_purpose != '' ? $val->out_visit_purpose : '',
                "Meet Person" => isset($val->meet_prsn) && $val->meet_prsn != '' ? $val->meet_prsn : '',
                "Remarks" => isset($val->remarks) && $val->remarks != '' ? $val->remarks : '',
                "Out Remarks" => isset($val->out_remarks) && $val->out_remarks != '' ? $val->out_remarks : '',
                // "Status (IN)" => $val->Approve_status==null?'PENDING':$val->Approve_status,
                // "Pending With (IN)" => Pending_With(2,$val)??'',
                // "Status (OUT)" => $val->Out_Approve_status==null?'PENDING':$val->Out_Approve_status,
                // "Pending With (OUT)" => '',
                "Creation Date & Time" => isset($val->created_at) ? date('d-m-Y h:i A', strtotime($val->created_at)) : ''
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

        $file = "Visitor_Gatepass_data.csv";
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
