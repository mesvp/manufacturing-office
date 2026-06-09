<?php

namespace App\Http\Controllers\GatePass;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Admin, Department_Assign, CheckBox};
use App\Models\GatePass\{InGatepassMaterials, MaterialGatePassApproval, Forwarded_Data_Gatepass};
use App\Models\Master\{Master_Type, Master_Type_Details, Master_Request_Type};
use Illuminate\Support\Facades\Session;

class MaterialGatePassApprovalController extends Controller
{
    public function MaterialGatepassApproveList(Request $request)
    {
        $CUSTEXT = Session::get('CUSTEXT');
        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));
        if (isset($CUSTEXT[2]['Forward']) || isset($CUSTEXT[2]['approver'])) {
            $query = InGatepassMaterials::query();
        } else {
            return view('GatePass.Material_Gatepass_Approval', ['materialdata' => [], 'DropdownData' => [], 'fromdate' => '', 'todate' => '', 'RequestNos' => [], 'RequestBys' => [], 'VehicleNos' => [], 'insuranceNos' => [], 'invoicechallannos' => [], 'drivernames' => [], 'DriverNumbers' => [], 'BillNos' => []]);
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
                // Handle multiple comma-separated invoice numbers
                if (strpos($invoicechallanno, ',') !== false) {
                    $invoiceArray = explode(',', $invoicechallanno);
                    $invoiceArray = array_map('trim', $invoiceArray);
                    $invoiceArray = array_filter($invoiceArray);

                    if (!empty($invoiceArray)) {
                        $query->where(function ($q) use ($invoiceArray) {
                            foreach ($invoiceArray as $invoice) {
                                $q->orWhereRaw("FIND_IN_SET(?, REPLACE(REPLACE(invoice_no, ' ', ''), ', ', ',')) > 0", [$invoice]);
                            }
                        });
                    }
                } else {
                    // Single invoice
                    $singleInvoice = trim($invoicechallanno);
                    $query->whereRaw("FIND_IN_SET(?, REPLACE(REPLACE(invoice_no, ' ', ''), ', ', ',')) > 0", [$singleInvoice]);
                }
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

        $materialdata = $query->get();
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

        $contactperssss = Admin::where('role', 1)->get();
        //return $CUSTEXT[2];
        if (isset($CUSTEXT[2]['Forward']) && isset($CUSTEXT[2]['approver'])) {
            $query = $query->where(function ($query) use ($CUSTEXT) {
                $query->where('Approve_status', null)->where('Forward_Status', 0)->whereRaw("Approve_Step IN (" . implode(",", $CUSTEXT[2]['approver']) . ")");
            })
                ->orWhere(function ($query) {
                    $query->whereRaw('request_no IN (SELECT DataID FROM forwarded_data_gatepass WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1');
                })
                ->orWhereRaw('request_no IN (SELECT DataID FROM forwarded_data_gatepass WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1')
                ->orderBy('id', 'DESC');
        } elseif (isset($CUSTEXT[2]['Forward'])) {
            $query = $query->where('Forward_Status', 1)->whereRaw('request_no IN (SELECT DataID FROM forwarded_data_gatepass WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD")')->orderBy('id', 'DESC');
        } elseif (isset($CUSTEXT[2]['approver'])) {
            $query = $query->where('Approve_status', null)->where(['Forward_Status' => 0, 'status' => 0])->WhereRaw("Approve_Step IN (" . implode(",", $CUSTEXT[2]['approver']) . ")")->orderBy('id', 'DESC');
        }

        $materialdata = $query->get();
        $ForDropdown = InGatepassMaterials::orderBy('id', 'DESC')->get();

        $ForDropdown_arr = array();
        foreach ($ForDropdown as $val) {
            $val->contact_person = Admin::find($val->contact_person);
            $val->request_type = Master_Request_Type::find($val->request_type);
            array_push($ForDropdown_arr, $val);
        }

        foreach ($materialdata as $val) {
            if ($val->Forward_Status != 1) {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="2" AND step="' . $val->Approve_Step . '")')->get();
            } else {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data_gatepass` WHERE DataID="' . $val->request_no . '" AND DepartmentID=2 AND `status`=0)')->get();
            }
        }
        return view('GatePass.Material_Gatepass_Approval', ['materialdata' => $materialdata, 'DropdownData' => $ForDropdown_arr, 'fromdate' => $fromdate, 'todate' => $dateto, 'RequestNos' => $RequestNo, 'RequestBys' => $RequestBy, 'VehicleNos' => $vehicleNo, 'insuranceNos' => $insuranceNo, 'invoicechallannos' => $invoicechallanno, 'drivernames' => $drivername, 'DriverNumbers' => $DriverNumber, 'BillNos' => $BillNo]);
    }

    public function approve(Request $request)
    {
        $CUSTEXT = Session::get('CUSTEXT');
        if (!empty($request->during_approval)) {
            InGatepassMaterials::where('request_no', $request->req_no)->update(['Approve_status' => $request->during_approval]);
            MaterialGatePassApproval::where('GatepassID', $request->req_no)->where('status', 1)->update(['status' => 0]);
        }
        $check = InGatepassMaterials::find($request->approveID);
        if ($request->during_approval === 'APPROVE') {
            $status = Forwarded_Data_Gatepass::where('DataID', $request->req_no)->update(['status' => 1]);
            InGatepassMaterials::where('request_no', $request->req_no)->update(['Forward_Status' => 0]);

            $DepartStepcount2 = Department_Assign::where(['departments' => 2, 'step' => 2])->count();
            $DepartStepcount3 = Department_Assign::where(['departments' => 2, 'step' => 3])->count();
            $prod = true;
            if ($check->Approve_Step == 1 && $DepartStepcount2 > 0) {
                InGatepassMaterials::where('request_no', $request->req_no)->update(['Approve_Step' => 2, 'Approve_status' => null]);
                $prod = false;
            }

            if ($check->Approve_Step == 2 &&  $DepartStepcount3 > 0) {
                InGatepassMaterials::where('request_no', $request->req_no)->update(['Approve_Step' => 3, 'Approve_status' => null]);
                $prod = false;
            }
        }
        if ($request->during_approval === 'REJECT') {
            $prod = InGatepassMaterials::find($request->approveID);
        }
        if ($request->during_approval === 'RECHECK') {
            $prod = InGatepassMaterials::find($request->approveID);
        }
        if ($request->during_approval === 'FORWARD') {
            Forwarded_Data_Gatepass::where(['DepartmentID' => 2, 'DataID' => $request->req_no])->update(['status' => 1]);
            InGatepassMaterials::where('id', $request->approveID)->update(['Forward_Status' => 1]);

            $forward = new Forwarded_Data_Gatepass;
            $forward->userID = auth()->user()->id;
            $forward->Forward_To_id = $request->Forward_To;
            $forward->DepartmentID = 2;
            $forward->DataID = $request->req_no;
            $forward->remarks = $request->comment_text;
            $forward->status = 0;

            $forward->save();
        }

        $approve = new MaterialGatePassApproval;
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
            InGatepassMaterials::where('id', $request->approveID)->update(['Approve_status' => null]);
            return redirect('GatePass/material-list')->with('success', 'successfully.....');
        } elseif (($request->pre_post_approval == 'AUDIT' || $request->pre_post_approval == 'INTIMATION' || $request->pre_post_approval == 'QUERY') && $request->non_acting == 1) {
            return redirect('GatePass/material-list')->with('success', 'successfull.....');
        } else {
            return redirect('GatePass/Material_Gatepass_Approval')->with('success', 'Approved successfully.....');
        }
    }

    public function Release_Hold(Request $request, $id)
    {
        $CUSTEXT = Session::get('CUSTEXT');
        $currentDate = now();

        $approvesss = MaterialGatePassApproval::where('GatepassID', $id)->where('action', 'HOLD')->update(['days_for_holding' => $currentDate, 'status' => 0]);
        $mtrl_gatepass =  InGatepassMaterials::where('request_no', $id)->update(['Approve_status' => null]);

        $approve = new MaterialGatePassApproval;
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
        return redirect('GatePass/material-list')->with('success', 'Hold Released successfully.....');
    }

    public function ExportMaterialApproval(Request $request)
    {
        $query = InGatepassMaterials::orderBy('id', 'DESC');

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
        $materialdata = $query->get();

        $materialdata_arr = array();
        foreach ($materialdata as $val) {
            $val->contact_person = Admin::find($val->contact_person);
            array_push($materialdata_arr, $val);
        }

        $Checkbox = CheckBox::where('userID', auth()->user()->id)->where('tableID', 2)->get();
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
                "Outgoing Pass No" => isset($val->outGatepassDatas->request_no) && $val->outGatepassDatas->request_no != '' ? $val->outGatepassDatas->request_no : '',
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
}
