<?php

namespace App\Http\Controllers\GatePass;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{CheckBox, Admin};
use App\Models\Master\{Master_Request_Type, Master_Department, Master_Person_To_Meet, Master_Request_Through, Master_Contact_Person};
use App\Models\GatePass\{Gatepass_Employee, Gatepass_Visitor, Gatepass_Visitor_Name, InGatepassMaterials, InGatepassItemDetails, OutGatepassMaterials, OutGatepassItemDetails};
use App\Models\FactoryCreater\{Factory_Uom, prj_organisation};
use Session;
use Illuminate\Support\Facades\Storage;
use \PDF;
class GatePassViewController extends Controller
{
    public function Employee_Gatepass_Data(Request $request)
    {
        $EXT = Session::get('EXT');

        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));

        if (isset($EXT[2]['inputer']) || auth()->user()->role == 0) {
            $query = Gatepass_Employee::orderBy('id', 'DESC');
        } else {
            $query = Gatepass_Employee::where('status', 0)->orderBy('id', 'DESC');
        }

        if ($fromdate && $todate) {
            $query->whereBetween('created_at', [$fromdate, $todate]);
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

        $RequestTime = '';
        if ($request->has('Request_Time') && $request->input('Request_Time') != '') {
            $RequestTime = $request->input('Request_Time');
            if ($RequestTime !== 'all') {
                $query->where('request_time', $RequestTime);
            }
        }

        $EmployeeName = '';
        if ($request->has('Employee_Name') && $request->input('Employee_Name') != '') {
            $EmployeeName = $request->input('Employee_Name');
            if ($EmployeeName !== 'all') {
                $query->where('employee_name', $EmployeeName);
            }
        }

        $RequestType = '';
        if ($request->has('Request_Type') && $request->input('Request_Type') != '') {
            $RequestType = $request->input('Request_Type');
            if ($RequestType !== 'all') {
                $query->where('request_type', $RequestType);
            }
        }

        $RequestOutTime = '';
        if ($request->has('Request_Out_Time') && $request->input('Request_Out_Time') != '') {
            $RequestOutTime = $request->input('Request_Out_Time');
            if ($RequestOutTime !== 'all') {
                $query->where('request_out_time', $RequestOutTime);
            }
        }

        $RequestInTime = '';
        if ($request->has('Request_In_Time') && $request->input('Request_In_Time') != '') {
            $RequestInTime = $request->input('Request_In_Time');
            if ($RequestInTime !== 'all') {
                $query->where('request_in_time', $RequestInTime);
            }
        }

        $Reasons = '';
        if ($request->has('Reason') && $request->input('Reason') != '') {
            $Reasons = $request->input('Reason');
            if ($Reasons !== 'all') {
                $query->where('reason', $Reasons);
            }
        }

        $employeedata = $query->get();

        $employeedata_arr = array();
        foreach ($employeedata as $val) {
            $val->emp_name = Admin::find($val->employee_name);
            $val->request_type = Master_Request_Type::find($val->request_type);

            array_push($employeedata_arr, $val);
        }

        $ForDropdown = Gatepass_Employee::orderBy('id', 'DESC')->get();

        $ForDropdown_arr = array();
        foreach ($ForDropdown as $val) {
            $val->emp_name = Admin::find($val->employee_name);
            $val->request_type = Master_Request_Type::find($val->request_type);

            array_push($ForDropdown_arr, $val);
        }

        $empName = Admin::where('role', 1)->get();

        return view('GatePass.Employee_list_view', ['employeedata' => $employeedata_arr, 'DropdownData' => $ForDropdown_arr, 'empName' => $empName, 'fromdate' => $fromdate, 'todate' => $dateto, 'RequestNos' => $RequestNo, 'RequestBys' => $RequestBy, 'GatePassNos' => $GatePassNo, 'RequestTimes' => $RequestTime, 'EmployeeNames' => $EmployeeName, 'RequestTypes' => $RequestType, 'RequestOutTimes' => $RequestOutTime, 'RequestInTimes' => $RequestInTime, 'Reasonss' => $Reasons]);
    }

    public function Employee_Gatepass($id = null)
    {
        $empName = Admin::where('role', 1)->get();
        $requestType = Master_Request_Type::all();
        $edit = Gatepass_Employee::find($id);

        return view('GatePass.EmployeeGatepass', ['empName' => $empName, 'requestType' => $requestType, 'edit' => $edit]);
    }

    public function Visitor_Gatepass_Data(Request $request)
    {
        $EXT = Session::get('EXT');

        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));

        if (isset($EXT[2]['inputer']) || auth()->user()->role == 0) {
            $query = Gatepass_Visitor::orderBy('id', 'DESC');
        } else {
            $query = Gatepass_Visitor::where('status', 0)->orderBy('id', 'DESC');
        }

        if ($fromdate && $todate) {
            $query->whereBetween('created_at', [$fromdate, $todate]);
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

        $RequestTime = '';
        if ($request->has('Request_Time') && $request->input('Request_Time') != '') {
            $RequestTime = $request->input('Request_Time');
            if ($RequestTime !== 'all') {
                $query->where('request_time', $RequestTime);
            }
        }

        $visitor = $query->get();

        $ForDropdown = Gatepass_Visitor::orderBy('id', 'DESC')->get();

        return view('GatePass.Visitor_list_view', ['visitor' => $visitor, 'DropdownData' => $ForDropdown, 'fromdate' => $fromdate, 'todate' => $dateto, 'RequestNos' => $RequestNo, 'RequestBys' => $RequestBy, 'GatePassNos' => $GatePassNo, 'RequestTimes' => $RequestTime]);
    }

    public function Visitor_Gatepass($id = null)
    {
        $persontomeet = Master_Person_To_Meet::all();
        $empName = Admin::where('role', 1)->get();
        $department = Master_Department::all();
        $requestthrough = Master_Request_Through::all();
        $edit = Gatepass_Visitor::find($id);
        $editvisitors = '';
        $visitorscount = '';
        if (isset($edit->id)) {
            $editvisitors = Gatepass_Visitor_Name::where('visitorID', $edit->id)->get();
            $visitorscount = Gatepass_Visitor_Name::where('visitorID', $edit->id)->count();
        }
        return view('GatePass.VisitorGatepass', ['persontomeet' => $persontomeet, 'department' => $department, 'requestthrough' => $requestthrough, 'edit' => $edit, 'editvisitors' => $editvisitors, 'visitorscount' => $visitorscount,'empName'=>$empName]);
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

    public function Material_Gatepass_Data(Request $request)
    {
        $EXT = Session::get('EXT');

        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));

        if (isset($EXT[2]['inputer']) || auth()->user()->role == 0) {
            $query = InGatepassMaterials::orderBy('id', 'DESC');
        } else {
            $query = InGatepassMaterials::where('status', 0)->orderBy('id', 'DESC');
        }

        if ($fromdate && $todate) {
            $query->whereBetween('request_date', [$fromdate, $todate]);
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

        $vehicleNo = '';
        if ($request->has('vehicle_no') && $request->input('vehicle_no') != '') {
            $vehicleNo = $request->input('vehicle_no');
            if ($vehicleNo !== 'all') {
                $query->where('vehicle_no', $vehicleNo);
            }
        }

        $insuranceNo = '';
        if ($request->has('insurance_no') && $request->input('insurance_no') != '') {
            $insuranceNo = $request->input('insurance_no');
            if ($insuranceNo !== 'all') {
                $query->where('insurance_no', $insuranceNo);
            }
        }

        $invoicechallanno = '';
        if ($request->has('Invoice_Challan_No') && $request->input('Invoice_Challan_No') != '') {
            $invoicechallanno = $request->input('Invoice_Challan_No');
            if ($invoicechallanno !== 'all') {
                $query->where('invoice_no', $invoicechallanno);
            }
        }

        $drivername = '';
        if ($request->has('Driver_Name') && $request->input('Driver_Name') != '') {
            $drivername = $request->input('Driver_Name');
            if ($drivername !== 'all') {
                $query->where('driver_name', $drivername);
            }
        }

        $DriverNumber = '';
        if ($request->has('Driver_Number') && $request->input('Driver_Number') != '') {
            $DriverNumber = $request->input('Driver_Number');
            if ($DriverNumber !== 'all') {
                $query->where('driver_number', $DriverNumber);
            }
        }

        $BillNo = '';
        if ($request->has('bill_no') && $request->input('bill_no') != '') {
            $BillNo = $request->input('bill_no');
            if ($BillNo !== 'all') {
                $query->where('bill_no', $BillNo);
            }
        }

        $materialdata = $query->with('outGatepassDatas')->get();
        $materialdata_arr = array();
        foreach ($materialdata as $val) {
            $val->contact_person = Admin::find($val->contact_person);
            array_push($materialdata_arr, $val);
        }

        $ForDropdown = InGatepassMaterials::orderBy('id', 'DESC')->get();
        $ForDropdown_arr = array();
        foreach ($ForDropdown as $val) {
            $val->contact_person = Admin::find($val->contact_person);
            array_push($ForDropdown_arr, $val);
        }

        //$contactperssss = Admin::all();
        $contactperssss = Admin::where('role', 1)->get();

        return view('GatePass.Material_list_view', ['materialdata' => $materialdata_arr, 'DropdownData' => $ForDropdown_arr, 'fromdate' => $fromdate, 'todate' => $dateto, 'RequestNos' => $RequestNo, 'RequestBys' => $RequestBy, 'VehicleNos' => $vehicleNo, 'insuranceNos' => $insuranceNo, 'invoicechallannos' => $invoicechallanno, 'drivernames' => $drivername, 'DriverNumbers' => $DriverNumber, 'BillNos' => $BillNo]);
    }

    public function Material_Gatepass($id = null)
    {
        $uoms = Factory_Uom::all();
        $organisations = prj_organisation::all();
        $contactperson = Master_Contact_Person::all();
        $edit = InGatepassMaterials::find($id);
        $empName = Admin::where('role', 1)->get();

        return view('GatePass.MaterialGatepass', ['contactperson' => $contactperson, 'edit' => $edit,'empName'=>$empName,'uoms' => $uoms,'organisations' => $organisations]);
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

        $employeedata_arr = array();
        foreach ($employeedata as $val) {
            $val->emp_name = Admin::find($val->employee_name);
            $val->request_type = Master_Request_Type::find($val->request_type);

            array_push($employeedata_arr, $val);
        }

        $Checkbox = CheckBox::where('userID', auth()->user()->id)->where('tableID', 3)->get();

        $Checkbox_Arr = [];
        foreach ($Checkbox as $val) {
            $valuee = $val->CheckBox;
            array_push($Checkbox_Arr, $valuee);
        }

        $d = [];
        foreach ($employeedata_arr as $key => $val) {
            $rowData = [
                "SL. No." => $key + 1,
                "Request No" => isset($val->request_no) && $val->request_no != '' ? $val->request_no : '',
                "Request By" => isset($val->request_by) && $val->request_by != '' ? $val->request_by : '',
                "Gate Pass No" => isset($val->gate_pass_no) && $val->gate_pass_no != '' ? $val->gate_pass_no : '',
                "Request Date" => isset($val->request_date) && $val->request_date != '' ? $val->request_date : '',
                "Request Time" => isset($val->request_time) && $val->request_time != '' ? date('h:i A', strtotime($val->request_time)) : '',
                "Employee Name" => isset($val->emp_name->name) && $val->emp_name->name != '' ? $val->emp_name->name : '',
                "Request Type" => isset($val->request_type->request_type) && $val->request_type->request_type != '' ? $val->request_type->request_type : '',
                "Request Out Time" => isset($val->request_out_time) && $val->request_out_time != '' ? date('h:i A', strtotime($val->request_out_time)) : '',
                "Request In Time" => isset($val->request_in_time) && $val->request_in_time != '' ? date('h:i A', strtotime($val->request_in_time)) : '',
                "Reason" => isset($val->reason) && $val->reason != '' ? $val->reason : '',
                "Remarks" => isset($val->remarks) && $val->remarks != '' ? $val->remarks : '',
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

        $Checkbox = CheckBox::where('userID', auth()->user()->id)->where('tableID', 4)->get();

        $Checkbox_Arr = [];
        foreach ($Checkbox as $val) {
            $valuee = $val->CheckBox;
            array_push($Checkbox_Arr, $valuee);
        }

        $d = [];
        foreach ($gatepass as $key => $val) {
            $rowData = [
                "SL. No." => $key + 1,
                "Request No" => isset($val->request_no) && $val->request_no != '' ? $val->request_no : '',
                "Request By" => isset($val->request_by) && $val->request_by != '' ? $val->request_by : '',
                "Gate Pass No" => isset($val->gate_pass_no) && $val->gate_pass_no != '' ? $val->gate_pass_no : '',
                "Request Date" => isset($val->request_date) && $val->request_date != '' ? $val->request_date : '',
                "Request Time" => isset($val->request_time) && $val->request_time != '' ? date('h:i A', strtotime($val->request_time)) : '',
                "Remarks" => isset($val->remarks) && $val->remarks != '' ? $val->remarks : '',
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
        $materialdata = InGatepassMaterials::with('outGatepassDatas')->orderBy('id', 'DESC')->get();
        $materialdata_arr = array();
        foreach ($materialdata as $val) {
            $val->contact_person = Master_Contact_Person::find($val->contact_person);
            array_push($materialdata_arr, $val);
        }

        $Checkbox = CheckBox::where('userID', auth()->user()->id)->where('tableID', 5)->get();

        $Checkbox_Arr = [];
        foreach ($Checkbox as $val) {
            $valuee = $val->CheckBox;
            array_push($Checkbox_Arr, $valuee);
        }

        $d = [];
        foreach ($materialdata_arr as $key => $val) {
            $rowData = [
                "SL. No." => $key + 1,
                "InComing Pass No" => isset($val->request_no) && $val->request_no != '' ? $val->request_no : '',
                "Outgoing Pass No" => isset($val->outGatepassDatas->request_no) && $val->outGatepassDatas->request_no!= '' ? $val->outGatepassDatas->request_no : '',
                "Created By" => isset($val->request_by) && $val->request_by != '' ? $val->request_by : '',
                "Creation Date & Time" => isset($val->created_at) && $val->created_at != '' ? date('d-m-Y h:i A', strtotime($val->created_at)) : '',
                "Vehicle No" => isset($val->vehicle_no) && $val->vehicle_no != '' ? $val->vehicle_no : '',
                "Insurance No" => isset($val->insurance_no) && $val->insurance_no != '' ? $val->insurance_no : '',
                "In Date & Time" => isset($val->vehicle_in_time) && $val->vehicle_in_time != '' ? date('d-m-Y h:i A', strtotime($val->vehicle_in_time)) : '',
                "Out Date & Time" => isset($val->outGatepassDatas->vehicle_in_time) && $val->outGatepassDatas->vehicle_in_time != '' ? date('d-m-Y h:i A', strtotime($val->outGatepassDatas->vehicle_in_time)) : '',
                "Driver Name" =>  isset($val->driver_name) && $val->driver_name != '' ? $val->driver_name : '',
                "Driver Mobile No" => isset($val->driver_number) && $val->driver_number != '' ? $val->driver_number : '',
                "Invoice No" => isset($val->invoice_no) && $val->invoice_no != '' ? $val->invoice_no : '',
                "E - Way Bill Number" => isset($val->bill_no) && $val->bill_no != '' ? $val->bill_no : ''
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

    public function employee_view($id)
    {
        $empName = Admin::where('role', 1)->get();
        $requestType = Master_Request_Type::all();
        $edit = Gatepass_Employee::find($id);

        return view('GatePass.Employee_View', ['empName' => $empName, 'requestType' => $requestType, 'edit' => $edit]);
    }

    public function visitors_view($id)
    {
        $empName = Admin::where('role', 1)->get();
        $persontomeet = Master_Person_To_Meet::all();
        $department = Master_Department::all();
        $requestthrough = Master_Request_Through::all();
        $edit = Gatepass_Visitor::find($id);
        $editvisitors = '';
        $visitorscount = '';
        if (isset($edit->id)) {
            $editvisitors = Gatepass_Visitor_Name::where('visitorID', $edit->id)->get();
            $visitorscount = Gatepass_Visitor_Name::where('visitorID', $edit->id)->count();
        }
        return view('GatePass.Visitor_View', ['persontomeet' => $persontomeet, 'department' => $department, 'requestthrough' => $requestthrough, 'edit' => $edit, 'editvisitors' => $editvisitors, 'visitorscount' => $visitorscount,'empName'=>$empName]);
    }

    public function Material_view($id)
    {
        $edit = InGatepassMaterials::find($id);
        $in_items = InGatepassItemDetails::where('in_gatepass_id',$id)->get();
        $uoms = Factory_Uom::all();
        $organisations = prj_organisation::all();
        return view('GatePass.Material_View', ['edit' => $edit, 'in_items' => $in_items, 'uoms' => $uoms, 'organisations' => $organisations]);
    }
    public function Material_out_view($id)
    {
        $edit = OutGatepassMaterials::find($id);
        $in_items = OutGatepassItemDetails::where('in_gatepass_id',$id)->get();
        $uoms = Factory_Uom::all();
        $organisations = prj_organisation::all();
        return view('GatePass.Material_Out_View', ['edit' => $edit, 'in_items' => $in_items, 'uoms' => $uoms, 'organisations' => $organisations]);
    }
    public function downloadGatepass(Request $request){
        return Storage::download($request->path);
    }

    public function downloadPDF($id) {
        $in_data = InGatepassMaterials::with('organizationDatas')->find($id);
        $in_items = InGatepassItemDetails::where('in_gatepass_id',$id)->with('uomDatas')->get();
        $pdf = PDF::loadView('GatePass.pdf', ['in_data' => $in_data, 'in_items' => $in_items, 'type' => 'IN']);
        return $pdf->download($in_data->request_no.'_GatePass_Data.pdf');
    }

    public function downloadoutPDF($id) {
        $in_data = OutGatepassMaterials::with('organizationDatas')->find($id);
        $in_items = OutGatepassItemDetails::where('in_gatepass_id',$id)->with('uomDatas')->get();
        $pdf = PDF::loadView('GatePass.pdf', ['in_data' => $in_data, 'in_items' => $in_items, 'type' => 'OUT']);
        return $pdf->download($in_data->request_no.'_GatePass_Data.pdf');
    }

    public function downloadHardcopy(Request $request){
        return Storage::download($request->path);
    }
}
