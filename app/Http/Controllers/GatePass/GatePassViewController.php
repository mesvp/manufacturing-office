<?php

namespace App\Http\Controllers\GatePass;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{CheckBox, Admin, Hrdepartment, Eservicereg};
use App\Models\Master\{Master_Request_Type, Master_Department, Master_Person_To_Meet, Prj_Subproject, Prj_Project, Master_Request_Through, Master_Contact_Person, Master_Type, Master_Type_Details};
use App\Models\GatePass\{InGatepassMaterials, InGatepassItemDetails, InGatepassAttachment, OutGatepassMaterials, OutGatepassItemDetails, OutGatepassAttachment, Gatepass_Employee, EmployeeGatePassApproval, VisitorGatePassApproval, Gatepass_Employee_Details, Gatepass_Visitor, Gatepass_Visitor_Name, Gatepass_Visitor_Details, MaterialGatePassApproval, GatepassSlno};
use App\Models\FactoryCreater\{Factory_Uom, prj_organisation,Factory_Master_Shift,Factory_Address_Detail,Factory_Plant_Machinery};
use App\Models\ProductCategories\{ProductCategories_Add_Product, ProductCategories_Add_Product_Other, ProductCategories_Approve};
use Session;
use Illuminate\Support\Facades\Storage;
use \PDF;
use App\Models\BOM\BOM;
use App\Models\MaterialManagement\MaterialManagement_Add_Material;
use DB;

class GatePassViewController extends Controller
{
    public function Employee_Gatepass_Data(Request $request)
    {
        $CUSTEXT = Session::get('CUSTEXT');
        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));

        if (isset($CUSTEXT[2]['inputer']) || auth()->user()->role == 0) {
            $query = Gatepass_Employee::orderBy('id', 'DESC');
        } else {
            $query = Gatepass_Employee::where('status', 0)->orderBy('id', 'DESC');
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

        $employeedata = $query->get();

        $employeedata_arr = array();
        foreach ($employeedata as $val) {
            $val->emp_name = Admin::find($val->userID);
            $val->request_type = Master_Request_Type::find($val->request_type);

            if ($val->Approve_status == 'APPROVE') {
                if ($val->Out_Forward_Status != 1) {
                    $val->PendingWith = Admin::whereRaw('id IN (SELECT userID FROM department_assign WHERE departments="2" AND step=?)', [$val->Out_Approve_Step])->get();
                } else {
                    $val->PendingWith = Admin::whereRaw('id IN (SELECT Forward_To_id FROM forwarded_data_gatepass WHERE DataID=? AND DepartmentID=2 AND status=0)', [$val->out_request_no])->get();
                }
            } else {
                if ($val->Forward_Status != 1) {
                    $val->PendingWith = Admin::whereRaw('id IN (SELECT userID FROM department_assign WHERE departments="2" AND step=?)', [$val->Approve_Step])->get();
                } else {
                    $val->PendingWith = Admin::whereRaw('id IN (SELECT Forward_To_id FROM forwarded_data_gatepass WHERE DataID=? AND DepartmentID=2 AND status=0)', [$val->request_no])->get();
                }
            }

            array_push($employeedata_arr, $val);
        }

        $ForDropdown = Gatepass_Employee::orderBy('id', 'DESC')->get();
        $ForDropdown_arr = [];

        foreach ($ForDropdown as $val) {
            $val->emp_name = Admin::find($val->userID);
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

        return view('GatePass.Employee_list_view', ['employeedata' => $employeedata_arr, 'DropdownData' => $ForDropdown_arr, 'empName' => $empName, 'fromdate' => $fromdate, 'todate' => $dateto, 'RequestNos' => $RequestNo, 'RequestBys' => $RequestBy, 'GatePassNos' => $GatePassNo, 'RequestOutTimes' => $RequestOutTime, 'RequestInTimes' => $RequestInTime, 'Manufacturing_unitdata' => $Manufacturing_unitdata, 'plant_namedata' => $plant_namedata, 'Projects' => $Projects, 'Sub_Projects' => $Sub_Projects, 'CostCenter' => $CostCenter, 'SubCostCenter' => $sub_CostCenter, 'SecurityGuards' => $SecurityGuards, 'PersonWithVehicles' => $PersonWithVehicles]);
    }

    public function Employee_Gatepass($id = null)
    {
        $employees = Admin::get();
        $requestType = Master_Request_Type::all();
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
        $Organisations = prj_organisation::all();
        $Shifts = Factory_Master_Shift::all();
        $edit = Gatepass_Employee::find($id);
        $vehicle_types = Master_Type_Details::where('parent_name','master-vehicletype')->get();
        return view('GatePass.EmployeeGatepass', ['employees' => $employees, 'requestType' => $requestType, 'Projects' => $Projects, 'Sub_Projects' => $Sub_Projects, 'Organisations' => $Organisations, 'Shifts' => $Shifts, 'edit' => $edit, 'vehicle_types' => $vehicle_types]);
    }

	public function approverview($id, $type = null)
    {
        $empName = Admin::where('role', 1)->get();
        $Shifts = Factory_Master_Shift::all();
        $edit = Gatepass_Employee::find($id);
        $empName = Admin::where('role', 1)->get();
        $Manufacturing_unitdata = Prj_Project::all_mu();
        $orgdata=prj_organisation::all_org();
        $plant_namedata = Prj_Subproject::all_pm();
        $vehicle_types = Master_Type_Details::where('parent_name','master-vehicletype')->get();

        return view('GatePass.employee_approval_view', ['empName' => $empName,'Shifts' => $Shifts, 'edit' => $edit, 'Manufacturing_unitdata' => $Manufacturing_unitdata, 'plant_namedata' => $plant_namedata,'orgdata'=>$orgdata,'type' => $type, 'vehicle_types' => $vehicle_types]);
    }

    public function trail(Request $request) {
        $edit=Gatepass_Employee::find($request->id);
        $approves=EmployeeGatePassApproval::where('GatepassID',$request->req_no)->get();
        $admin=Admin::all_admin();

        return view('GatePass/employee_trail',compact('edit','approves','admin'));
    }

    public function action(Request $request) {
        $edit=Gatepass_Employee::find($request->id);
        $type = $request->type;
        $req_no = $request->req_no;
        $employeeName=Admin::where('role',1)->get();
        return view('GatePass/employee_approveraction',compact('edit','employeeName','type','req_no'));
    }

    public function inputeraction(Request $request) {
        $edit=Gatepass_Employee::find($request->id);
        $type = $request->type;
        $req_no = $request->req_no;
        $employeeName=Admin::where('role',1)->get();

        return view('GatePass/employee_inputeraction',compact('edit','employeeName','type','req_no'));
    }

    public function EditEmployeeGatepass($id ,$type = null)
    {
        $employees = Admin::get();
        $Manufacturing_unitdata = Prj_Project::all_mu();
        $orgdata=prj_organisation::all_org();
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
        $Organisations = prj_organisation::all();
        $Shifts = Factory_Master_Shift::all();
        $edit = Gatepass_Employee::find($id);
        $vehicle_types = Master_Type_Details::where('parent_name','master-vehicletype')->get();
        if (isset($type) && $type == 'out') {
            return view('GatePass.Employee_Out_Edit', ['employees' => $employees,  'edit' => $edit, 'Manufacturing_unitdata' => $Manufacturing_unitdata, 'plant_namedata' => $plant_namedata,'orgdata'=>$orgdata,'Projects' => $Projects, 'Sub_Projects' => $Sub_Projects, 'Organisations' => $Organisations, 'Shifts' => $Shifts, 'vehicle_types' => $vehicle_types]);
        } else {
            return view('GatePass.Employee_Edit', ['employees' => $employees,  'edit' => $edit, 'Manufacturing_unitdata' => $Manufacturing_unitdata, 'plant_namedata' => $plant_namedata,'orgdata'=>$orgdata,'Projects' => $Projects, 'Sub_Projects' => $Sub_Projects, 'Organisations' => $Organisations, 'Shifts' => $Shifts, 'vehicle_types' => $vehicle_types]);
        }
    }

    public function updateEmployeeGatepass(Request $request, $id, $type = null)
    {
        if ($type == 'in') {
            $employee = Gatepass_Employee::findOrFail($id);
            $employee->org_id = $request->organisation_name;
            $employee->request_out_time = date('d-m-Y h:i A', strtotime($request->request_out_time));
            $employee->request_in_time = date('d-m-Y h:i A', strtotime($request->request_in_time));
            $employee->prsn_vehicle = $request->prsn_vehicle;
            if ($request->prsn_vehicle == '1') {
                $employee->vehicle_type = $request->vehicle_type;
                $employee->vehicle_no = $request->vehicle_no;
            } else {
                $employee->vehicle_type = '';
                $employee->vehicle_no = '';
            }
            $employee->sec_guard = $request->sec_guard_name;
            $employee->sec_guard_no = $request->sec_guard_phone;
            $employee->visit_purpose = $request->visit_purpose;
            $employee->meet_prsn = $request->meet_prsn;
            $employee->remarks = $request->remarks;
            Gatepass_Employee::where('id', $id)->update(['Approve_status' => null]);
            EmployeeGatePassApproval::where('GatepassID', $request->req_no)->where('status', 1)->update(['status' => 0]);
            $employee->save();
            if ($employee) {
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
                $approve->status = 0;
                if ($request->during_approval != '') {
                    $approve->action = $request->during_approval;
                } elseif ($request->pre_post_approval != '') {
                    $approve->pre_post_approval = $request->pre_post_approval;
                } else {
                    $approve->action = 'Verified';
                }
                $approve->comment_text = $request->remarks;
                $approve->ip_address = $request->getClientIp();
                $approve->device_name = $request->server('HTTP_USER_AGENT');
                $approve->days_for_holding = $request->days_for_holding;
                $approve->Forward_To = $request->Forward_To;
                $approve->save();
            }
            Gatepass_Employee_Details::where('request_no', $request->req_no)->forceDelete();

            if ($employee) {
                if (isset($request['emp_shift']) && !empty($request['emp_shift'])) {
                    foreach ($request['emp_shift'] as $key => $val) {
                        $inGatepassEmpDetails = new Gatepass_Employee_Details;
                        $inGatepassEmpDetails->request_no = $request->req_no;
                        $inGatepassEmpDetails->emp_shift = $request['emp_shift'][$key] ?? '';
                        $inGatepassEmpDetails->emp_name = $request['emp_name'][$key] ?? '';
                        $inGatepassEmpDetails->emp_code = $request['emp_code'][$key] ?? '';
                        $inGatepassEmpDetails->emp_dept = $request['emp_dept'][$key] ?? '';
                        $inGatepassEmpDetails->emp_phone = $request['emp_phone'][$key] ?? '';
                        $inGatepassEmpDetails->save();
                    }
                } else {
                    return redirect('GatePass/List')->with('success', 'Updated Successfully....');
                }
            }
        } else {
            $out_employee = Gatepass_Employee::findOrFail($id);

            $out_employee->actual_out_time = date('d-m-Y h:i A', strtotime($request->actual_out_time));
            $out_employee->out_sec_guard = $request->sec_guard_name;
            $out_employee->out_sec_guard_no = $request->sec_guard_phone;
            $out_employee->out_visit_purpose = $request->out_visit_purpose;
            $out_employee->meet_prsn = $request->meet_prsn;
            $out_employee->out_remarks = $request->out_remarks;
            Gatepass_Employee::where('id', $id)->update(['Out_Approve_status' => null]);
            EmployeeGatePassApproval::where('GatepassID', $request->req_no)->where('status', 1)->update(['status' => 0]);
            $out_employee->save();
            if ($out_employee) {
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
                $approve->status = 0;
                if ($request->during_approval != '') {
                    $approve->action = $request->during_approval;
                } elseif ($request->pre_post_approval != '') {
                    $approve->pre_post_approval = $request->pre_post_approval;
                } else {
                    $approve->action = 'Verified';
                }
                $approve->comment_text = $request->out_remarks;
                $approve->ip_address = $request->getClientIp();
                $approve->device_name = $request->server('HTTP_USER_AGENT');
                $approve->days_for_holding = $request->days_for_holding;
                $approve->Forward_To = $request->Forward_To;
                $approve->save();
            }
            return redirect('GatePass/List')->with('success', 'Updated Successfully....');
        }

        return redirect('GatePass/List')->with('success', 'Updated Successfully....');
    }

    public function employee_view($id ,$type = null)
    {
        $empName = Admin::where('role', 1)->get();
        $Manufacturing_unitdata = Prj_Project::all_mu();
        $orgdata=prj_organisation::all_org();
        $plant_namedata = Prj_Subproject::all_pm();
        $edit = Gatepass_Employee::find($id);
        $vehicle_types = Master_Type_Details::where('parent_name','master-vehicletype')->get();

        if (isset($type) && $type == 'out') {
            return view('GatePass.Employee_Out_View', ['empName' => $empName,  'edit' => $edit, 'Manufacturing_unitdata' => $Manufacturing_unitdata, 'plant_namedata' => $plant_namedata,'orgdata'=>$orgdata, 'vehicle_types' => $vehicle_types]);
        } else {
            return view('GatePass.Employee_View', ['empName' => $empName,  'edit' => $edit, 'Manufacturing_unitdata' => $Manufacturing_unitdata, 'plant_namedata' => $plant_namedata,'orgdata'=>$orgdata, 'vehicle_types' => $vehicle_types]);
        }
    }

    public function Visitor_Gatepass_Data(Request $request)
    {
        $CUSTEXT = Session::get('CUSTEXT');

        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));

        if (isset($CUSTEXT[2]['inputer']) || auth()->user()->role == 0) {
            $query = Gatepass_Visitor::orderBy('id', 'DESC');
        } else {
            $query = Gatepass_Visitor::where('status', 0)->orderBy('id', 'DESC');
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

        $visitor = $query->get();
        $status = '';
        foreach ($visitor as $key => $value) {
            $value->emp_name = Admin::find($value->userID);
            $status = $value->Approve_status;
            if($status == 'APPROVE'){
                if ($value->Out_Forward_Status != 1) {
                    $value->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="2" AND step="' . $value->Out_Approve_Step . '")')->get();
                } else {
                    $value->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data_gatepass` WHERE DataID="' . $value->out_request_no . '" AND DepartmentID=2 AND `status`=0)')->get();
                }
            } else {
                if ($value->Forward_Status != 1) {
                    $value->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="2" AND step="' . $value->Approve_Step . '")')->get();
                } else {
                    $value->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data_gatepass` WHERE DataID="' . $value->request_no . '" AND DepartmentID=2 AND `status`=0)')->get();
                }
            }
        }

        $ForDropdown = Gatepass_Visitor::orderBy('id', 'DESC')->get();
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
        $Organisations = prj_organisation::all();

        return view('GatePass.Visitor_list_view', ['visitor' => $visitor, 'DropdownData' => $ForDropdown, 'fromdate' => $fromdate, 'todate' => $dateto, 'RequestNos' => $RequestNo, 'RequestBys' => $RequestBy, 'GatePassNos' => $GatePassNo, 'RequestOutTimes' => $RequestOutTime, 'RequestInTimes' => $RequestInTime, 'CostCenter' => $CostCenter, 'SubCostCenter' => $sub_CostCenter, 'SecurityGuards' => $SecurityGuards, 'PersonWithVehicles' => $PersonWithVehicles, 'Manufacturing_unitdata' => $Manufacturing_unitdata, 'plant_namedata' => $plant_namedata, 'Projects' => $Projects, 'Sub_Projects' => $Sub_Projects, 'Organisations' => $Organisations]);
    }

    public function EditVisitorGatepass($id ,$type = null)
    {
        $empDtls = Admin::get();
        $Manufacturing_unitdata = Prj_Project::all_mu();
        $orgdata=prj_organisation::all_org();
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
        $Organisations = prj_organisation::all();
        $edit = Gatepass_Visitor::find($id);
        $vehicle_types = Master_Type_Details::where('parent_name','master-vehicletype')->get();

        if (isset($type) && $type == 'out') {
            return view('GatePass.Visitor_Out_Edit', ['empDtls' => $empDtls,  'edit' => $edit, 'Manufacturing_unitdata' => $Manufacturing_unitdata, 'plant_namedata' => $plant_namedata,'orgdata'=>$orgdata,'Projects' => $Projects, 'Sub_Projects' => $Sub_Projects, 'Organisations' => $Organisations, 'vehicle_types' => $vehicle_types]);
        } else {
            return view('GatePass.Visitor_Edit', ['empDtls' => $empDtls,  'edit' => $edit, 'Manufacturing_unitdata' => $Manufacturing_unitdata, 'plant_namedata' => $plant_namedata,'orgdata'=>$orgdata,'Projects' => $Projects, 'Sub_Projects' => $Sub_Projects, 'Organisations' => $Organisations, 'vehicle_types' => $vehicle_types]);
        }
    }

    public function updateVisitorGatepass(Request $request, $id, $type = null)
    {
        if ($type == 'in') {
            $visitor = Gatepass_Visitor::findOrFail($id);
            $visitor->org_id = $request->organisation_name;
            $visitor->request_out_time = date('d-m-Y h:i A', strtotime($request->request_out_time));
            $visitor->request_in_time = date('d-m-Y h:i A', strtotime($request->request_in_time));
            $visitor->prsn_vehicle = $request->prsn_vehicle;
            if ($request->prsn_vehicle == '1') {
                $visitor->vehicle_type = $request->vehicle_type;
                $visitor->vehicle_no = $request->vehicle_no;
            } else {
                $visitor->vehicle_type = '';
                $visitor->vehicle_no = '';
            }
            $visitor->sec_guard = $request->sec_guard_name;
            $visitor->sec_guard_no = $request->sec_guard_phone;
            $visitor->visit_purpose = $request->visit_purpose;
            $visitor->meet_prsn = $request->visitor_meet_prsn;
            $visitor->remarks = $request->remarks;
            Gatepass_Visitor::where('id', $id)->update(['Approve_status' => null]);
            VisitorGatePassApproval::where('GatepassID', $request->req_no)->where('status', 1)->update(['status' => 0]);
            $visitor->save();
            if ($visitor) {
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
                $approve->status = 0;
                if ($request->during_approval != '') {
                    $approve->action = $request->during_approval;
                } elseif ($request->pre_post_approval != '') {
                    $approve->pre_post_approval = $request->pre_post_approval;
                } else {
                    $approve->action = 'Verified';
                }
                $approve->comment_text = $request->remarks;
                $approve->ip_address = $request->getClientIp();
                $approve->device_name = $request->server('HTTP_USER_AGENT');
                $approve->days_for_holding = $request->days_for_holding;
                $approve->Forward_To = $request->Forward_To;
                $approve->save();
            }
            Gatepass_Visitor_Details::where('request_no', $request->req_no)->forceDelete();

            if ($visitor) {
                if (isset($request['visitor_type']) && !empty($request['visitor_type'])) {
                    foreach ($request['visitor_type'] as $key => $val) {
                        $inGatepassVisitorDetails = new Gatepass_Visitor_Details;
                        $inGatepassVisitorDetails->request_no = $visitor->request_no;
                        $inGatepassVisitorDetails->visitor_type = $request['visitor_type'][$key] ?? '';
                        $inGatepassVisitorDetails->visitor_name = $request['visitor_name'][$key] ?? '';
                        $inGatepassVisitorDetails->visitor_phone = $request['visitor_phone'][$key] ?? '';
                        $inGatepassVisitorDetails->visitor_address = $request['visitor_address'][$key] ?? '';
                        $inGatepassVisitorDetails->visitor_purpose = $request['visitor_purpose'][$key] ?? '';
                        $inGatepassVisitorDetails->visitor_meet_prsn = $request['meet_prsn'][$key] ?? '';
                        $inGatepassVisitorDetails->save();

                    }
                } else {
                    return redirect('GatePass/visitor-list')->with('success', 'Visitor Details Not Added ....');
                }
            }
        } else {
            $out_Visitor = Gatepass_Visitor::findOrFail($id);

            $out_Visitor->actual_out_time = date('d-m-Y h:i A', strtotime($request->actual_out_time));
            $out_Visitor->out_sec_guard = $request->sec_guard_name;
            $out_Visitor->out_sec_guard_no = $request->sec_guard_phone;
            $out_Visitor->out_visit_purpose = $request->out_visit_purpose;
            $out_Visitor->meet_prsn = $request->meet_prsn;
            $out_Visitor->out_remarks = $request->out_remarks;
            Gatepass_Visitor::where('id', $id)->update(['Out_Approve_status' => null]);
            VisitorGatePassApproval::where('GatepassID', $request->req_no)->where('status', 1)->update(['status' => 0]);
            $out_Visitor->save();
            if ($out_Visitor) {
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
                $approve->status = 0;
                if ($request->during_approval != '') {
                    $approve->action = $request->during_approval;
                } elseif ($request->pre_post_approval != '') {
                    $approve->pre_post_approval = $request->pre_post_approval;
                } else {
                    $approve->action = 'Verified';
                }
                $approve->comment_text = $request->out_remarks;
                $approve->ip_address = $request->getClientIp();
                $approve->device_name = $request->server('HTTP_USER_AGENT');
                $approve->days_for_holding = $request->days_for_holding;
                $approve->Forward_To = $request->Forward_To;
                $approve->save();
            }
            return redirect('GatePass/visitor-list')->with('success', 'Updated Successfully....');
        }

        return redirect('GatePass/visitor-list')->with('success', 'Updated Successfully....');
    }

    public function Visitor_Gatepass($id = null)
    {
        $empDtls = Admin::get();
        $department = Master_Department::all();
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
        $Organisations = prj_organisation::all();
        $edit = Gatepass_Visitor::find($id);
        $vehicle_types = Master_Type_Details::where('parent_name','master-vehicletype')->get();

        $editvisitors = '';
        $visitorscount = '';
        if (isset($edit->id)) {
            $editvisitors = Gatepass_Visitor_Name::where('visitorID', $edit->id)->get();
            $visitorscount = Gatepass_Visitor_Name::where('visitorID', $edit->id)->count();
        }
        return view('GatePass.VisitorGatepass', ['department' => $department, 'edit' => $edit, 'editvisitors' => $editvisitors, 'visitorscount' => $visitorscount,'empDtls'=>$empDtls, 'Projects'=>$Projects,'Sub_Projects'=>$Sub_Projects,'Organisations'=>$Organisations,'vehicle_types'=>$vehicle_types]);
    }

    public function getEmployeeDetails($id = null)
    {
        if(!$id) {
            return response()->json([
                'success' => false,
                'message' => 'Employee ID is required',
            ]);
        } else {
            $employee = Admin::find($id);
            if ($employee->mstr_ref_id == 0) {
                $edtls = Hrdepartment::join('hr_employee_service_register as y', 'hr_department.id', '=', 'y.department_id')
                    ->join('mstr_emp as x', 'x.id', '=', 'y.emp_name')
                    ->where('x.id', $id)
                    ->select('hr_department.dept_name', 'hr_department.id', 'y.employee_id')
                    ->first();
            } else {
                $edtls = Hrdepartment::join('hr_employee_service_register as y', 'hr_department.id', '=', 'y.department_id', 'x.fullname')
                    ->join('mstr_emp as x', 'x.mstr_ref_id', '=', 'y.ref_id')
                    ->where('y.ref_id', $employee->mstr_ref_id)
                    ->select('hr_department.dept_name', 'hr_department.id', 'y.employee_id')
                    ->first();
            }

            if ($employee) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'ename' => $employee->fullname,
                        'phone' => $employee->contact,
                        'code' => $edtls->employee_id,
                        'dept' => $edtls->dept_name,
                    ],
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found',
                ]);
            }
        }

    }

    public function visitors_details($id)
    {
        $visitorsName = Gatepass_Visitor_Name::where('visitorID', $id)->get();
        $visitorsName_arr  = array();
        foreach ($visitorsName as $val) {
            $val->personToMeet  = Admin::find($val->person_to_meet);
            $val->department  = Master_Department::find($val->department);
            $val->requestthrough  = Admin::find($val->request_through);

            array_push($visitorsName_arr, $val);
        }

        return view('GatePass.VisitorsDetails', ['visitorsName' => $visitorsName_arr]);
    }

    // new visitor gate pass
    public function visitorapproverview($id, $type = null)
    {
        $empDtls = Admin::get();
        $edit = Gatepass_Visitor::find($id);
        $Manufacturing_unitdata = Prj_Project::all_mu();
        $orgdata=prj_organisation::all_org();
        $plant_namedata = Prj_Subproject::all_pm();
        $vehicle_types = Master_Type_Details::where('parent_name','master-vehicletype')->get();

        return view('GatePass.visitor_approval_view', ['edit' => $edit, 'Manufacturing_unitdata' => $Manufacturing_unitdata, 'plant_namedata' => $plant_namedata,'orgdata'=>$orgdata,'type' => $type,'empDtls'=>$empDtls, 'vehicle_types' => $vehicle_types]);
    }

    public function visitor_action(Request $request) {
        $edit=Gatepass_Visitor::find($request->id);
        $type = $request->type;
        $req_no = $request->req_no;
        $visitorName=Admin::where('role',1)->get();
        return view('GatePass/visitor_approveraction',compact('edit','visitorName','type','req_no'));
    }

    public function visitor_trail(Request $request) {
        $edit=Gatepass_Visitor::find($request->id);
        $approves=VisitorGatePassApproval::where('GatepassID',$request->req_no)->get();
        $admin=Admin::all_admin();
        return view('GatePass/visitor_trail',compact('edit','approves','admin'));
    }

    public function visitor_inputeraction(Request $request) {
        $edit=Gatepass_Visitor::find($request->id);
        $type = $request->type;
        $req_no = $request->req_no;
        $visitorName=Admin::where('role',1)->get();
        return view('GatePass/visitor_inputeraction',compact('edit','visitorName','type','req_no'));
    }

    public function Material_Gatepass_Data(Request $request, $export = null)
    {
    $CUSTEXT = Session::get('CUSTEXT');

    $dateto = $request->input('to_date');
    $fromdate = $request->input('from_date');
    $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));

    if (isset($CUSTEXT[2]['inputer']) || auth()->user()->role == 0) {
        $query = InGatepassMaterials::orderBy('id', 'DESC');
    } else {
        $query = InGatepassMaterials::where('status', 0)->orderBy('id', 'DESC');
    }

    // Date filter
    if ($fromdate && $todate) {
        $query->whereBetween('request_date', [$fromdate, $todate]);
    }

    // Filters
    if ($request->filled('Request_No') && $request->input('Request_No') !== 'all') {
        $query->where('request_no', $request->input('Request_No'));
    }

    if ($request->filled('Request_By') && $request->input('Request_By') !== 'all') {
        $query->where('request_by', $request->input('Request_By'));
    }

    if ($request->filled('vehicle_no') && $request->input('vehicle_no') !== 'all') {
        $query->where('vehicle_no', $request->input('vehicle_no'));
    }

    if ($request->filled('insurance_no') && $request->input('insurance_no') !== 'all') {
        $query->where('insurance_no', $request->input('insurance_no'));
    }

    // Invoice filter (IN)
    if ($request->filled('Invoice_Challan_No') && $request->input('Invoice_Challan_No') !== 'all') {
        $invoiceArray = array_filter(array_map('trim', explode(',', $request->input('Invoice_Challan_No'))));

        if (!empty($invoiceArray)) {
            $query->where(function ($q) use ($invoiceArray) {
                foreach ($invoiceArray as $invoice) {
                    $q->orWhereRaw(
                        "FIND_IN_SET(?, REPLACE(REPLACE(invoice_no,' ',''),', ',',') ) > 0",
                        [$invoice]
                    );
                }
            });
        }
    }

    // Out Invoice filter
    if ($request->filled('Out_Invoice_Challan_No') && $request->input('Out_Invoice_Challan_No') !== 'all') {
        $invoiceArray = array_filter(array_map('trim', explode(',', $request->input('Out_Invoice_Challan_No'))));

        if (!empty($invoiceArray)) {
            $query->whereHas('outGatepassDatas', function ($q) use ($invoiceArray) {
                $q->where(function ($subQ) use ($invoiceArray) {
                    foreach ($invoiceArray as $invoice) {
                        $subQ->orWhereRaw(
                            "FIND_IN_SET(?, REPLACE(REPLACE(invoice_no,' ',''),', ',',') ) > 0",
                            [$invoice]
                        );
                    }
                });
            });
        }
    }

    if ($request->filled('Driver_Name') && $request->input('Driver_Name') !== 'all') {
        $query->where('driver_name', $request->input('Driver_Name'));
    }

    if ($request->filled('Driver_Number') && $request->input('Driver_Number') !== 'all') {
        $query->where('driver_number', $request->input('Driver_Number'));
    }

    if ($request->filled('bill_no') && $request->input('bill_no') !== 'all') {
        $query->where('bill_no', $request->input('bill_no'));
    }

    // ✅ Pagination applied here (skipped for export)
        if ($export == 1) {
            $materialdata = $query
                ->with(['outGatepassDatas', 'inGatepassAttachs'])
                ->get();
        } else {
            $materialdata = $query
                ->with(['outGatepassDatas', 'inGatepassAttachs'])
                ->paginate(10)
                ->appends($request->all());
        }

    // ✅ Modify records WITHOUT breaking pagination
    foreach ($materialdata as $val) {

        $val->contact_person = Admin::find($val->contact_person);

        if ($val->outGatepassDatas) {
            if ($val->outGatepassDatas->Out_Forward_Status != 1) {
                $val->outPendingWith = Admin::whereRaw(
                    'id IN(SELECT userID FROM department_assign WHERE departments="2" AND step=?)',
                    [$val->outGatepassDatas->Out_Approve_Step]
                )->get();
            } else {
                $val->outPendingWith = Admin::whereRaw(
                    'id IN(SELECT Forward_To_id FROM forwarded_data_gatepass WHERE DataID=? AND DepartmentID=2 AND status=0)',
                    [$val->outGatepassDatas->request_no]
                )->get();
            }
        }

        if ($val->Forward_Status != 1) {
            $val->PendingWith = Admin::whereRaw(
                'id IN(SELECT userID FROM department_assign WHERE departments="2" AND step=?)',
                [$val->Approve_Step]
            )->get();
        } else {
            $val->PendingWith = Admin::whereRaw(
                'id IN(SELECT Forward_To_id FROM forwarded_data_gatepass WHERE DataID=? AND DepartmentID=2 AND status=0)',
                [$val->request_no]
            )->get();
        }
    }

    // ✅ Optimized dropdown (LIMITED)
    $ForDropdown = InGatepassMaterials::latest()->limit(200)->get();

    foreach ($ForDropdown as $val) {
        $val->contact_person = Admin::find($val->contact_person);
    }

    if ($export == 1) {
        return $materialdata;
    }

    return view('GatePass.Material_list_view', [
        'materialdata' => $materialdata, // ✅ IMPORTANT
        'DropdownData' => $ForDropdown,
        'fromdate' => $fromdate,
        'todate' => $dateto,
        'RequestNos' => $request->input('Request_No'),
        'RequestBys' => $request->input('Request_By'),
        'VehicleNos' => $request->input('vehicle_no'),
        'insuranceNos' => $request->input('insurance_no'),
        'invoicechallannos' => $request->input('Invoice_Challan_No'),
        'outinvoicechallannos' => $request->input('Out_Invoice_Challan_No'),
        'drivernames' => $request->input('Driver_Name'),
        'DriverNumbers' => $request->input('Driver_Number'),
        'BillNos' => $request->input('bill_no')
    ]);
}

    public function Material_Gatepass($id = null)
    {
        $uoms = Factory_Uom::all();
        $organisations = prj_organisation::all();
        $contactperson = Master_Contact_Person::all();
        $edit = InGatepassMaterials::find($id);
        $empName = Admin::where('role', 1)->get();
         $invoices = DB::table('dd_crmwtp_dispatch_details as d')
            ->where('d.dispatch_status', '1')
            ->where('d.stage', '11')
            ->where('d.invoice_no', '!=', '')
            ->whereNotExists(function ($q) {
                $q->from('gatepass_slno as gs')
                    ->join('out_gatepass_materials as ogm', 'ogm.request_no', '=', 'gs.gp_reqNo')
                    ->whereColumn('gs.gp_invNo', 'd.invoice_no')
                    ->where(function ($q2) {
                        $q2->where('ogm.Out_Approve_status', '!=', 'REJECT')
                            ->orWhereNull('ogm.Out_Approve_status');
                    });
            })
            ->groupBy('d.invoice_no')
            ->pluck('d.invoice_no');

        $in_invoices = DB::table('prj_dispatch as d1')
            ->where('d1.arrvl_flag', '1')
            ->where('d1.status', '1')
            ->where('d1.inv_no', '!=', '')
            ->whereNotExists(function ($q) {
                $q->from('gatepass_slno as gs')
                    ->join('in_gatepass_materials as igm', 'igm.request_no', '=', 'gs.gp_reqNo')
                    ->whereColumn('gs.gp_invNo', 'd1.inv_no')
                    ->where(function ($q2) {
                        $q2->where('igm.Approve_status', '!=', 'REJECT')
                            ->orWhereNull('igm.Approve_status');
                    });
            })
            ->groupBy('d1.inv_no')
            ->pluck('d1.inv_no');

        $MAT_DATA = ProductCategories_Add_Product::where('Approve_status', 'APPROVE')->get();
        $Raw_Material = [];
        $Raw_Materialdata = [];
        foreach ($MAT_DATA as $Val) {
            if (isset($Val->Raw_Material)) {
                //$Val->RawMaterial = MaterialManagement_Add_Material::find($Val->Raw_Material);
                $Val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*', 'prj_material.material_name as matname')
                    ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
                    ->where('materialmanagement_add_material.id', $Val->Raw_Material)
                    ->first();

                $Raw_Material[$Val->Raw_Material] = $Val;
                $Raw_Materialdata[$Val->Raw_Material] = $Val->RawMaterial->matname;
            }
        }
        $Filtered_Array = array_values($Raw_Material);
        return view('GatePass.MaterialGatepass', ['contactperson' => $contactperson, 'edit' => $edit, 'empName' => $empName, 'uoms' => $uoms, 'organisations' => $organisations, 'materials' => $Filtered_Array, 'invoices' => $invoices, 'in_invoices' => $in_invoices]);
    }

    public function CheckBoxStore(Request $request)
    {
        $userID = auth()->user()->id;
        $id = $request->input('id');
        $columns = $request->input('columns');

        $data = CheckBox::where('userID', $userID)->where('tableID', $id)->get();

        if ($data->count() > 0) {
            $data->each(function ($item) {
                $item->delete();
            });
        }

        if (isset($columns) && $columns != '') {
            foreach (explode(',', $columns) as $key => $value) {
                $insert = new CheckBox;
                $insert->userID = $userID;
                $insert->tableID = $id;
                $insert->CheckBox = $value;
                $insert->save();
            }
        }

        return response()->json(['success' => true, 'message' => 'Data Inserted']);
    }

    public function getCheckBoxData(Request $request)
    {
        $userID = auth()->user()->id;
        $id = $request->input('ID');

        $data = CheckBox::where('userID', $userID)->where('tableID', $id)->get();

        return response()->json(['success' => true, 'columns' => $data->pluck('CheckBox')]);
    }

    public function ExportEmployee(Request $request)
    {
        $employeedata = Gatepass_Employee::orderBy('id', 'DESC')->get();

        $Checkbox = CheckBox::where('tableID', 7785)->get();
        $Checkbox_Arr = [];
        foreach ($Checkbox as $val) {
            $valuee = $val->CheckBox;
            array_push($Checkbox_Arr, $valuee);
        }

        $d = [];
        foreach ($employeedata as $key => $val) {
            $rowData = [
                "SL. No." => $key + 1,
                "IN Request No" => isset($val->request_no) && $val->request_no != '' ? $val->request_no : '',
                "OUT Request No" => isset($val->out_request_no) && $val->out_request_no != '' ? $val->out_request_no : '',
                "IN Request By" => isset($val->request_by) && $val->request_by != '' ? $val->request_by : '',
                "OUT Request By" => isset($val->out_request_by) && $val->out_request_by != '' ? $val->out_request_by : '',
                "Gate Pass No" => isset($val->gate_pass_no) && $val->gate_pass_no != '' ? $val->gate_pass_no : '',
                "Cost Center" => isset($val->project_id) && $val->project_id != '' ? Prj_Project::find($val->project_id)->pname : '',
                "Sub Cost Center" => isset($val->subproject_id) && $val->subproject_id != '' ? Prj_Subproject::find($val->subproject_id)->spname : '',
                "Organisation" => isset($val->org_id) && $val->org_id != '' ? prj_organisation::find($val->org_id)->organisation : '',
                "Request Out Time" => isset($val->request_out_time) && $val->request_out_time != '' ? date('d-m-Y h:i A' , strtotime($val->request_out_time)) : '',
                "Request In Time" => isset($val->request_in_time) && $val->request_in_time != '' ? date('d-m-Y h:i A' , strtotime($val->request_in_time)) : '',
                "Actual Out Time" => isset($val->actual_out_time) && $val->actual_out_time != '' ? date('d-m-Y h:i A' , strtotime($val->actual_out_time)) : '',
                "Person With Vehicle" => $val->prsn_vehicle = $val->prsn_vehicle == 1 ? 'Yes' : 'No',
                "Vehicle Type" => optional(Master_Type_Details::find($val->vehicle_type))->mstr_type_value ?? '',
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
                "IN Creation Date & Time" => isset($val->created_at) ? date('d-m-Y h:i A', strtotime($val->created_at)) : '',
                "OUT Creation Date & Time" => isset($val->out_created_at) ? date('d-m-Y h:i A', strtotime($val->out_created_at)) : ''
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

        $file = "Employee_Gatepass_data.csv";
        $this->collectionExport($d, $file);
    }

    public function ExportVisitor(Request $request)
    {
        $gatepass = Gatepass_Visitor::orderBy('id', 'DESC')->get();
        $Checkbox = CheckBox::where('tableID', 7788)->get();

        $Checkbox_Arr = [];
        foreach ($Checkbox as $val) {
            $valuee = $val->CheckBox;
            array_push($Checkbox_Arr, $valuee);
        }

        $d = [];
        foreach ($gatepass as $key => $val) {
            $rowData = [
                "SL. No." => $key + 1,
                "IN Request No" => isset($val->request_no) && $val->request_no != '' ? $val->request_no : '',
                "OUT Request No" => isset($val->out_request_no) && $val->out_request_no != '' ? $val->out_request_no : '',
                "IN Request By" => isset($val->request_by) && $val->request_by != '' ? $val->request_by : '',
                "OUT Request By" => isset($val->out_request_by) && $val->out_request_by != '' ? $val->out_request_by : '',
                "Gate Pass No" => isset($val->gate_pass_no) && $val->gate_pass_no != '' ? $val->gate_pass_no : '',
                "Cost Center" => isset($val->project_id) && $val->project_id != '' ? Prj_Project::find($val->project_id)->pname : '',
                "Sub Cost Center" => isset($val->subproject_id) && $val->subproject_id != '' ? Prj_Subproject::find($val->subproject_id)->spname : '',
                "Organisation" => isset($val->org_id) && $val->org_id != '' ? prj_organisation::find($val->org_id)->organisation : '',
                "Request Out Time" => isset($val->request_out_time) && $val->request_out_time != '' ? date('d-m-Y h:i A' , strtotime($val->request_out_time)) : '',
                "Request In Time" => isset($val->request_in_time) && $val->request_in_time != '' ? date('d-m-Y h:i A' , strtotime($val->request_in_time)) : '',
                "Actual Out Time" => isset($val->actual_out_time) && $val->actual_out_time != '' ? date('d-m-Y h:i A' , strtotime($val->actual_out_time)) : '',
                "Person With Vehicle" => $val->prsn_vehicle = $val->prsn_vehicle == 1 ? 'Yes' : 'No',
                "Vehicle Type" => optional(Master_Type_Details::find($val->vehicle_type))->mstr_type_value ?? '',
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
                "IN Creation Date & Time" => isset($val->created_at) ? date('d-m-Y h:i A', strtotime($val->created_at)) : '',
                "OUT Creation Date & Time" => isset($val->out_created_at) ? date('d-m-Y h:i A', strtotime($val->out_created_at)) : ''
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

    public function ExportMaterial(Request $request)
    {
        $materialdata = $this->Material_Gatepass_Data($request, 1); // 1 = export mode
    
        $materialdata_arr = [];
        foreach ($materialdata as $val) {
            $val->contact_person = Master_Contact_Person::find($val->contact_person);
            $materialdata_arr[] = $val;
        }
    
        $Checkbox = CheckBox::where('userID', auth()->user()->id)
                    ->where('tableID', 5)->get();
    
        $Checkbox_Arr = [];
        foreach ($Checkbox as $val) {
            $Checkbox_Arr[] = $val->CheckBox;
        }
        
        $d = [];
        foreach ($materialdata as $key => $val) {
            $outPendingWith = '';

            if ($val->outGatepassDatas) {

                $outData = $val->outGatepassDatas;

                if (
                    (
                        $outData->Out_Approve_status === 'FORWARD' &&
                        isset($outData->status) &&
                        $outData->status != 1
                    )
                    ||
                    (
                        $outData->Out_Approve_status == '' &&
                        isset($outData->status) &&
                        $outData->status != 1
                    )
                ) {

                    $names = [];

                    if (!empty($val->outPendingWith)) {
                        foreach ($val->outPendingWith as $name) {
                            if (!empty($name->fullname)) {
                                $names[] = $name->fullname;
                            }
                        }
                    }

                    $outPendingWith = 'Pending With ' . implode(', ', $names);

                } elseif (
                    $outData->Out_Approve_status == 'RECHECK' ||
                    $outData->Out_Approve_status == 'OBJECT'
                ) {

                    if (!empty($val->user->fullname)) {
                        $outPendingWith = 'Pending With ' . $val->user->fullname;
                    }
                }
            }
            $rowData = [
                "SL. No." => $key + 1,
                "InComing Pass No" => $val->request_no ?? '',
                "Outgoing Pass No" => $val->outGatepassDatas->request_no ?? '',
                "Created By" => $val->request_by ?? '',
                "Creation Date & Time" => $val->created_at ? date('d-m-Y h:i A', strtotime($val->created_at)) : '',
                "Vehicle No" => $val->vehicle_no ?? '',
                "Insurance No" => $val->insurance_no ?? '',
                "In Date & Time" => $val->vehicle_in_time ? date('d-m-Y h:i A', strtotime($val->vehicle_in_time)) : '',
                "Out Date & Time" => isset($val->outGatepassDatas->vehicle_in_time) 
                                    ? date('d-m-Y h:i A', strtotime($val->outGatepassDatas->vehicle_in_time)) : '',
                "Driver Name" => $val->driver_name ?? '',
                "Driver Mobile No" => $val->driver_number ?? '',
                "Invoice No" => $val->invoice_no ?? '',
                "E - Way Bill Number" => $val->bill_no ?? '',
                "Out Invoice No" => isset($val->outGatepassDatas->invoice_no) ? $val->outGatepassDatas->invoice_no : '',
                "Out E - Way Bill Number" => isset($val->outGatepassDatas->bill_no) ? $val->outGatepassDatas->bill_no : '',
                "In Status" => $val->Approve_status,
                "In Pending With" => Pending_With(2, $val),
                "Out Status" => isset($val->outGatepassDatas->Out_Approve_status) ? $val->outGatepassDatas->Out_Approve_status : '',
                "Out Pending With" => $outPendingWith
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
    
        $file = "Material_Gatepass_data.csv";
        $this->collectionExport($d, $file);
    }


    public function ExportMaterialSlno($id)
    {
        $slno_details = GatepassSlno::where('gp_reqNo', $id)->get();

        $d = [];
        foreach ($slno_details as $key => $val) {
            $rowData = [
                "SL. No." => $key + 1,
                "Material Name" => $val->matName,
                "Supplier Name" => $val->custName,
                "Serial Number" => $val->slno_dtls,
                "UOM" => $val->invUom
            ];

            $d[] = $rowData;
        }

        $file = "Material_Gatepass_Serial_Numbers_data.csv";
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

    public function visitors_view($id, $type = null)
    {
        $empName = Admin::where('role', 1)->get();
        $Manufacturing_unitdata = Prj_Project::all_mu();
        $orgdata=prj_organisation::all_org();
        $plant_namedata = Prj_Subproject::all_pm();
        $empDtls = Admin::get();
        $edit = Gatepass_Visitor::find($id);
        $vehicle_types = Master_Type_Details::where('parent_name','master-vehicletype')->get();

        if (isset($type) && $type == 'out') {
            return view('GatePass.Visitor_Out_View', ['empName' => $empName, 'edit' => $edit, 'Manufacturing_unitdata' => $Manufacturing_unitdata, 'plant_namedata' => $plant_namedata,'orgdata'=>$orgdata,'empDtls'=>$empDtls, 'vehicle_types' => $vehicle_types]);
        } else {
            return view('GatePass.Visitor_View', ['empName' => $empName, 'edit' => $edit, 'Manufacturing_unitdata' => $Manufacturing_unitdata, 'plant_namedata' => $plant_namedata,'orgdata'=>$orgdata,'empDtls'=>$empDtls, 'vehicle_types' => $vehicle_types]);
        }
    }

    public function Material_view($id, $type = null)
    {
        $edit = InGatepassMaterials::find($id);
        $in_items = InGatepassItemDetails::where('in_gatepass_id', $id)->get();
        $uoms = Factory_Uom::all();
        $organisations = prj_organisation::all();
        $slno_details = GatepassSlno::where('gp_reqNo', $edit->request_no)->groupBy('matId')->get();

        return view('GatePass.Material_View', ['type' => $type, 'edit' => $edit, 'in_items' => $in_items, 'uoms' => $uoms, 'organisations' => $organisations, 'slno_details' => $slno_details]);
    }
    public function Material_out_view($id, $type = null)
    {
        $edit = OutGatepassMaterials::find($id);
        $in_items = OutGatepassItemDetails::where('in_gatepass_id', $id)->get();
        $uoms = Factory_Uom::all();
        $organisations = prj_organisation::all();
        $slno_details = GatepassSlno::where('gp_reqNo', $edit->request_no)->groupBy('matId')->get();
        $serials_by_mat = GatepassSlno::where('gp_reqNo', $edit->request_no)->get()->groupBy('matId');
        return view('GatePass.Material_Out_View', ['type' => $type, 'edit' => $edit, 'in_items' => $in_items, 'uoms' => $uoms, 'organisations' => $organisations, 'slno_details' => $slno_details, 'serials_by_mat' => $serials_by_mat]);
    }
    public function downloadGatepass(Request $request){
        return Storage::download($request->path);
    }

    public function downloadPDF($id)
    {
        $in_data = InGatepassMaterials::with('organizationDatas')->find($id);
        $in_items = InGatepassItemDetails::where('in_gatepass_id', $id)->with('uomDatas')->get();
        $approves = MaterialGatePassApproval::where('GatepassID', $in_data->request_no)
                    ->where('action', 'APPROVE')
                    ->leftJoin('mstr_emp', 'gatepass_material_approval.userID', '=', 'mstr_emp.id')
                    ->select('mstr_emp.fullname as approved_by')
                    ->first();
        $materials = DB::table('gatepass_slno')
            ->where('gp_reqNo', $in_data->request_no)
            ->where('type', 'IN')
            ->whereNotNull('matId')
            ->orderBy('matId')
            ->get()
            ->groupBy('matId');  
        $pdf = PDF::loadView('GatePass.pdf', ['in_data' => $in_data, 'in_items' => $in_items, 'approves'   => $approves, 'materials' => $materials, 'type' => 'IN']);
        return $pdf->download($in_data->request_no . '_GatePass_Data.pdf');
    }

    public function downloadoutPDF($id)
    {
        $out_data = OutGatepassMaterials::with('organizationDatas')->find($id);
        $out_items = OutGatepassItemDetails::where('in_gatepass_id', $id)->with('uomDatas')->get();
        $approves = MaterialGatePassApproval::where('GatepassID', $out_data->request_no)
                    ->where('action', 'APPROVE')
                    ->leftJoin('mstr_emp', 'gatepass_material_approval.userID', '=', 'mstr_emp.id')
                    ->select('mstr_emp.fullname as approved_by')
                    ->first();
        $materials = DB::table('gatepass_slno')
            ->where('gp_reqNo', $out_data->request_no)
            ->where('type', 'OUT')
            ->whereNotNull('matId')
            ->orderBy('matId')
            ->get()
            ->groupBy('matId');           
        $pdf = PDF::loadView('GatePass.pdf', ['in_data' => $out_data, 'in_items' => $out_items, 'approves'   => $approves, 'materials' => $materials, 'type' => 'OUT']);
        return $pdf->download($out_data->request_no . '_GatePass_Data.pdf');
    }

    public function downloadHardcopy(Request $request){
        return Storage::download($request->path);
    }

	// emp gatepass new
    public function downloadEmployeePDF($id , $type = null) {
        $Manufacturing_unitdata = Prj_Project::all_mu();
        $orgdata=prj_organisation::all_org();
        $plant_namedata = Prj_Subproject::all_pm();
        $emp_data = Gatepass_Employee::find($id);
        $emp_details = Gatepass_Employee_Details::where('request_no',$emp_data->request_no)->get();
        $pdf = PDF::loadView('GatePass.Employeepdf', ['type' => $type, 'emp_data' => $emp_data, 'emp_details' => $emp_details, 'Manufacturing_unitdata' => $Manufacturing_unitdata, 'plant_namedata' => $plant_namedata, 'orgdata' => $orgdata]);
        if ($type == 'in') {
            return $pdf->download($emp_data->request_no.'_Employee_GatePass_'.$type.'_Data.pdf');
        } else {
            return $pdf->download($emp_data->out_request_no.'_Employee_GatePass_'.$type.'_Data.pdf');
        }
    }

    public function downloadVisitorPDF($id , $type = null) {
        $Manufacturing_unitdata = Prj_Project::all_mu();
        $orgdata=prj_organisation::all_org();
        $plant_namedata = Prj_Subproject::all_pm();
        $visitor_data = Gatepass_Visitor::find($id);
        $empName = Admin::all_admin();
        $visitor_details = Gatepass_Visitor_Details::where('request_no',$visitor_data->request_no)->get();
        $pdf = PDF::loadView('GatePass.Visitorpdf', ['type' => $type, 'visitor_data' => $visitor_data, 'visitor_details' => $visitor_details, 'Manufacturing_unitdata' => $Manufacturing_unitdata, 'plant_namedata' => $plant_namedata, 'orgdata' => $orgdata, 'empName' => $empName]);
        if ($type == 'in') {
            return $pdf->download($visitor_data->request_no.'_Visitor_GatePass_'.$type.'_Data.pdf');
        } else {
            return $pdf->download($visitor_data->out_request_no.'_Visitor_GatePass_'.$type.'_Data.pdf');
        }
    }

    public function getplantnamedetails($id)
    {
        $plantdetails = Factory_Plant_Machinery::select('prj_subproject.*', 'prj_organisation.organisation', 'prj_organisation.id as orgid')
            ->leftJoin('prj_subproject', 'factory_plant_machineries.Plant_Name', '=', 'prj_subproject.id')
            ->leftJoin('factory_address_details', 'factory_plant_machineries.factory_id', '=', 'factory_address_details.id')
            ->leftJoin('prj_organisation', 'factory_address_details.organization', '=', 'prj_organisation.id')
            ->where('factory_address_details.name_of_unit', $id)
            ->where('factory_address_details.Approve_status', 'APPROVE')
            ->whereNotNull('prj_subproject.spname')
            ->get();
        return response()->json($plantdetails);
    }

    public function getInvoiceDtls(Request $request)
    {
        $invoices = $request->invoices;

        $invoice_details = DB::table('dd_crmwtp_dispatch_details as d1')
            ->leftjoin('fin_customers as c', 'd1.customer', '=', 'c.id')
            ->select('d1.id', 'd1.invoice_no', 'c.companynm as customer_name')
            ->whereIn('d1.invoice_no', $invoices)
            ->where('d1.dispatch_status', 1)
            ->where('d1.stage', 11)
            ->whereIn('d1.id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('dd_crmwtp_dispatch_details')
                    ->groupBy('invoice_no');
            })
            ->get();

        $mat_details = DB::table('dd_crmwtp_dispatch_items')
            ->leftJoin('crmwtp_product_details', 'dd_crmwtp_dispatch_items.material_id', '=', 'crmwtp_product_details.id')
            ->select('dd_crmwtp_dispatch_items.*', 'crmwtp_product_details.model_name', 'crmwtp_product_details.uom')
            ->whereIn('dispatch_id', $invoice_details->pluck('id'))
            ->get();

        $slno_details = DB::table('dd_crmwtp_dispatch_items_slno as sn')
            ->leftJoin('dd_crmwtp_dispatch_items', 'dd_crmwtp_dispatch_items.id', '=', 'sn.disp_item_id')
            ->select('sn.serial_no', 'sn.disp_item_id', 'sn.material_id')
            ->whereIn('sn.disp_item_id', $mat_details->pluck('id'))
            ->whereNotExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('gatepass_slno as gp')
                    ->whereColumn('gp.matId', 'sn.material_id')
                    ->whereColumn('gp.slno_dtls', 'sn.serial_no');
            })
            ->get();

        if ($invoice_details) {
            return response()->json([
                'success' => true,
                'data' => $invoice_details,
                'mat_details' => $mat_details,
                'slno_details' => $slno_details,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invoice details not found',
            ]);
        }
    }

    public function getInvoiceDtlsIn(Request $request)
    {
        $invoices = $request->invoices;

        $invoice_details = DB::table('prj_dispatch as d1')
            ->leftjoin('prj_supplier as p', 'd1.suplr_name_id', '=', 'p.id')
            ->select('d1.unique_id', 'p.supplier_name', 'd1.inv_no')
            ->whereIn('d1.inv_no', $invoices)
            ->where('d1.arrvl_flag', '1')
            ->where('d1.status', '1')
            ->where('d1.inv_no', '!=', '')
            ->groupBy('d1.inv_no')
            ->get();
        $mat_details = DB::table('prj_dispatch_material')
            ->join('prj_material', 'prj_dispatch_material.trnst_matid', '=', 'prj_material.id')
            ->select('prj_dispatch_material.*', 'prj_material.material_name')
            ->whereIn('prj_dispatch_material.unique_id', $invoice_details->pluck('unique_id'))
            ->get();

        if ($invoice_details) {
            return response()->json([
                'success' => true,
                'data' => $invoice_details,
                'mat_details' => $mat_details,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invoice details not found',
            ]);
        }
    }

    public function material_inputeraction(Request $request)
    {
        $edit = InGatepassMaterials::find($request->id);
        $type = $request->type;
        $req_no = $request->req_no;
        $contactperson = Master_Contact_Person::all();
        return view('GatePass/material_inputeraction', compact('edit', 'contactperson', 'type', 'req_no'));
    }

    public function material_trail(Request $request)
    {
        $edit = InGatepassMaterials::find($request->id);
        $approves = MaterialGatePassApproval::where('GatepassID', $request->req_no)->get();
        $admin = Admin::all_admin();
        return view('GatePass/material_trail', compact('edit', 'approves', 'admin'));
    }

    public function material_out_inputeraction(Request $request)
    {
        $edit = OutGatepassMaterials::find($request->id);
        $type = $request->type;
        $req_no = $request->req_no;
        $contactperson = Master_Contact_Person::all();
        return view('GatePass/material_inputeraction', compact('edit', 'contactperson', 'type', 'req_no'));
    }

    public function material_out_trail(Request $request)
    {
        $edit = OutGatepassMaterials::find($request->id);
        $approves = MaterialGatePassApproval::where('GatepassID', $request->req_no)->get();
        $admin = Admin::all_admin();
        return view('GatePass/material_trail', compact('edit', 'approves', 'admin'));
    }

    public function material_action(Request $request)
    {
        $edit = InGatepassMaterials::find($request->id);
        $type = $request->type;
        $req_no = $request->req_no;
        $empName = Admin::where('role', 1)->get();
        return view('GatePass/material_approveraction', compact('edit', 'empName', 'type', 'req_no'));
    }

    public function material_out_action(Request $request)
    {
        $edit = OutGatepassMaterials::find($request->id);
        $type = $request->type;
        $req_no = $request->req_no;
        $empName = Admin::where('role', 1)->get();
        return view('GatePass/material_approveraction', compact('edit', 'empName', 'type', 'req_no'));
    }

    public function EditMaterialGatepass($id = null, $type = null)
    {
        $uoms = Factory_Uom::all();
        $organisations = prj_organisation::all();
        $contactperson = Master_Contact_Person::all();
        $empName = Admin::where('role', 1)->get();

        if ($type == 'in') {
            $edit = InGatepassMaterials::find($id);
            $in_invoices = DB::table('prj_dispatch as d1')
            ->where('d1.arrvl_flag', '1')
            ->where('d1.status', '1')
            ->where('d1.inv_no', '!=', '')
            ->whereNotExists(function ($q) {
                $q->from('gatepass_slno as gs')
                    ->join('in_gatepass_materials as igm', 'igm.request_no', '=', 'gs.gp_reqNo')
                    ->whereColumn('gs.gp_invNo', 'd1.inv_no')
                    ->where(function ($q2) {
                        $q2->where('igm.Approve_status', '!=', 'REJECT')
                            ->orWhereNull('igm.Approve_status');
                    });
            })
            ->groupBy('d1.inv_no')
            ->pluck('d1.inv_no');
        } else {
            $edit = OutGatepassMaterials::find($id);
            $invoices = DB::table('dd_crmwtp_dispatch_details as d')
            ->where('d.dispatch_status', '1')
            ->where('d.stage', '11')
            ->where('d.invoice_no', '!=', '')
            ->whereNotExists(function ($q) {
                $q->from('gatepass_slno as gs')
                    ->join('out_gatepass_materials as ogm', 'ogm.request_no', '=', 'gs.gp_reqNo')
                    ->whereColumn('gs.gp_invNo', 'd.invoice_no')
                    ->where(function ($q2) {
                        $q2->where('ogm.Out_Approve_status', '!=', 'REJECT')
                            ->orWhereNull('ogm.Out_Approve_status');
                    });
            })
            ->groupBy('d.invoice_no')
            ->pluck('d.invoice_no');
        }
        // Get existing invoice selections for the current gatepass
        $selectedInvoices = [];
        if ($edit && $edit->invoice_no) {
            $selectedInvoices = is_string($edit->invoice_no) ?
                explode(',', $edit->invoice_no) :
                $edit->invoice_no;
        }

        $MAT_DATA = ProductCategories_Add_Product::where('Approve_status', 'APPROVE')->get();
        $Raw_Material = [];
        $Raw_Materialdata = [];

        foreach ($MAT_DATA as $Val) {
            if (isset($Val->Raw_Material)) {
                $Val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*', 'prj_material.material_name as matname')
                    ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
                    ->where('materialmanagement_add_material.id', $Val->Raw_Material)
                    ->first();

                if ($Val->RawMaterial) {
                    $Raw_Material[$Val->Raw_Material] = $Val;
                    $Raw_Materialdata[$Val->Raw_Material] = $Val->RawMaterial->matname;
                }
            }
        }

        $Filtered_Array = array_values($Raw_Material);
        return view('GatePass.Material_Edit_Gatepass', [
            'contactperson' => $contactperson,
            'edit' => $edit,
            'empName' => $empName,
            'uoms' => $uoms,
            'organisations' => $organisations,
            'materials' => $Filtered_Array,
            'invoices' => $invoices ?? null,
            'in_invoices' => $in_invoices ?? null,
            'selectedInvoices' => $selectedInvoices,
            'type' => $type
        ]);
    }
}
