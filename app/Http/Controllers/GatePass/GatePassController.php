<?php

namespace App\Http\Controllers\GatePass;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GatePass\{Gatepass_Employee, Gatepass_Employee_Details, Gatepass_Visitor, Gatepass_Visitor_Name, Gatepass_Visitor_Details, InGatepassMaterials, InGatepassItemDetails, InGatepassAttachment, OutGatepassMaterials, OutGatepassItemDetails, OutGatepassAttachment, EmployeeGatePassApproval, VisitorGatePassApproval, MaterialGatePassApproval, GatepassSlno};
use App\Models\Master\{Master_Type, Master_Type_Details};
use App\Models\Production\{Production_For_Sales, Production_For_Stock,ProductionData,ProductionBatch,Production,ProductionApprove};
use App\Models\FinishedGood\{FinishedGoodGatepass, FinishedGoodGatepassDetails, FinishedGoodGatepassApproval, FinishedGoodGatepassAttachment};
use Illuminate\Support\Facades\DB;

class GatePassController extends Controller
{
    public function Employee_Store(Request $request)
    {
        if ($request->edit != '') {
            $employee = Gatepass_Employee::find($request->edit);
            if ($employee) {
                $employee->outuserID = auth()->user()->id;
                $employee->Out_Approve_Step = 1;
                $employee->out_request_by = $request->request_by;
                $employee->actual_out_time = $request->request_out_time ?? $employee->actual_out_time;
                $employee->out_sec_guard = $request->out_sec_guard_name ?? $employee->out_sec_guard;
                $employee->out_sec_guard_no = $request->out_sec_guard_phone ?? $employee->out_sec_guard_no;
                $employee->out_visit_purpose = $request->out_visit_purpose ?? $employee->out_visit_purpose;
                $employee->out_remarks = $request->remarks ?? $employee->out_remarks;
                $employee->status = isset($request->draft) ? 1 : 0;
                $employee->out_request_no = 'EMPOUTRN' . str_pad($request->edit, 4, '0', STR_PAD_LEFT);
                $employee->out_created_at = date('Y-m-d H:i:s');
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
                    $approve->GatepassID = $employee->out_request_no;
                    $approve->status = 1;
                    if ($request->during_approval != '') {
                        $approve->action = $request->during_approval;
                    } elseif ($request->pre_post_approval != '') {
                        $approve->pre_post_approval = $request->pre_post_approval;
                    } else {
                        $approve->action = 'Request Raised';
                    }
                    $approve->comment_text = $employee->out_remarks;
                    $approve->ip_address = $request->getClientIp();
                    $approve->device_name = $request->server('HTTP_USER_AGENT');
                    $approve->days_for_holding = $request->days_for_holding;
                    $approve->Forward_To = $request->Forward_To;
                    $approve->save();
                }
            }
        } else {
            $employee = new Gatepass_Employee;
            $employee->userID = auth()->user()->id;
            $employee->request_by = $request->request_by;
            $employee->Approve_Step = 1;
            $employee->project_id = $request->cost_center;
            $employee->subproject_id = $request->sub_cost_center;
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
            $employee->status = isset($request->draft) ? 1 : 0;
            $employee->save();
            $employee->request_no = 'EMPINRN' . str_pad($employee->id, 4, '0', STR_PAD_LEFT);
            $employee->gate_pass_no = 'GPN' . str_pad($employee->id, 4, '0', STR_PAD_LEFT);
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
                $approve->GatepassID = $employee->request_no;
                $approve->status = 1;
                if ($request->during_approval != '') {
                    $approve->action = $request->during_approval;
                } elseif ($request->pre_post_approval != '') {
                    $approve->pre_post_approval = $request->pre_post_approval;
                } else {
                    $approve->action = 'Request Raised';
                }
                $approve->comment_text = $request->remarks;
                $approve->ip_address = $request->getClientIp();
                $approve->device_name = $request->server('HTTP_USER_AGENT');
                $approve->days_for_holding = $request->days_for_holding;
                $approve->Forward_To = $request->Forward_To;
                $approve->save();
            }
        }

        if ($employee) {
            if (isset($request['emp_shift']) && !empty($request['emp_shift'])) {
                foreach ($request['emp_shift'] as $key => $val) {
                    $inGatepassEmpDetails = new Gatepass_Employee_Details;
                    $inGatepassEmpDetails->request_no = $employee->request_no;
                    $inGatepassEmpDetails->emp_shift = $request['emp_shift'][$key] ?? '';
                    $inGatepassEmpDetails->emp_name = $request['emp_name'][$key] ?? '';
                    $inGatepassEmpDetails->emp_code = $request['emp_code'][$key] ?? '';
                    $inGatepassEmpDetails->emp_dept = $request['emp_dept'][$key] ?? '';
                    $inGatepassEmpDetails->emp_phone = $request['emp_phone'][$key] ?? '';
                    $inGatepassEmpDetails->save();
                }
            } else {
                return redirect('GatePass/List')->with('success', 'Added Successfully....');
            }
        }
        return redirect('GatePass/List')->with('success', 'Added Successfully....');
    }

    public function delete_Employee($id)
    {
        Gatepass_Employee::find($id)->delete();
        return back()->with('success', 'Deleted Successfully....');
    }

    public function Visitor_store(Request $request)
    {
        if ($request->edit != '') {
            $visitor = Gatepass_Visitor::find($request->edit);
            if ($visitor) {
                $visitor->outuserID = auth()->user()->id;
                $visitor->Out_Approve_Step = 1;
                $visitor->out_request_by = $request->request_by;
                $visitor->actual_out_time = $request->request_out_time ?? $visitor->actual_out_time;
                $visitor->out_sec_guard = $request->out_sec_guard_name ?? $visitor->out_sec_guard;
                $visitor->out_sec_guard_no = $request->out_sec_guard_phone ?? $visitor->out_sec_guard_no;
                $visitor->out_visit_purpose = $request->out_visit_purpose ?? $visitor->out_visit_purpose;
                $visitor->out_remarks = $request->remarks ?? $visitor->out_remarks;
                $visitor->status = isset($request->draft) ? 1 : 0;
                $visitor->out_request_no = 'VISOUTRN' . str_pad($request->edit, 4, '0', STR_PAD_LEFT);
                $visitor->out_created_at = date('Y-m-d H:i:s');
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
                    $approve->GatepassID = $visitor->out_request_no;
                    $approve->status = 1;
                    if ($request->during_approval != '') {
                        $approve->action = $request->during_approval;
                    } elseif ($request->pre_post_approval != '') {
                        $approve->pre_post_approval = $request->pre_post_approval;
                    } else {
                        $approve->action = 'Request Raised';
                    }
                    $approve->comment_text = $request->remarks;
                    $approve->ip_address = $request->getClientIp();
                    $approve->device_name = $request->server('HTTP_USER_AGENT');
                    $approve->days_for_holding = $request->days_for_holding;
                    $approve->Forward_To = $request->Forward_To;
                    $approve->save();
                }
            }
        } else {
            $visitor = new Gatepass_Visitor;
            $visitor->userID = auth()->user()->id;
            $visitor->request_by = $request->request_by;
            $visitor->Approve_Step = 1;
            $visitor->project_id = $request->cost_center;
            $visitor->subproject_id = $request->sub_cost_center;
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
            $visitor->status = isset($request->draft) ? 1 : 0;
            $visitor->save();
            $visitor->request_no = 'VISINRN' . str_pad($visitor->id, 4, '0', STR_PAD_LEFT);
            $visitor->gate_pass_no = 'GPN' . str_pad($visitor->id, 4, '0', STR_PAD_LEFT);
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
                $approve->GatepassID = $visitor->request_no;
                $approve->status = 1;
                if ($request->during_approval != '') {
                    $approve->action = $request->during_approval;
                } elseif ($request->pre_post_approval != '') {
                    $approve->pre_post_approval = $request->pre_post_approval;
                } else {
                    $approve->action = 'Request Raised';
                }
                $approve->comment_text = $request->remarks;
                $approve->ip_address = $request->getClientIp();
                $approve->device_name = $request->server('HTTP_USER_AGENT');
                $approve->days_for_holding = $request->days_for_holding;
                $approve->Forward_To = $request->Forward_To;
                $approve->save();
            }
        }

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

        return redirect('GatePass/visitor-list')->with('success', 'Added Successfully....');
    }

    public function delete_visitor($id)
    {
        Gatepass_Visitor::find($id)->delete();
        Gatepass_Visitor_Name::where('visitorID', $id)->delete();

        return back()->with('success', 'Deleted Successfully....');
    }

       public function Material_Store(Request $request)
    {
        DB::beginTransaction();

        try {

            $TimeZone = new \DateTimeZone('Asia/Kolkata');
            $currentTime = new \DateTime('now', $TimeZone);

            // =============================
            // COMMON INVOICE ARRAY
            // =============================
            $invoiceArray = [];

            if (!empty($request->inv_no)) {
                foreach ($request->inv_no as $inv) {
                    $inv = trim($inv);
                    if ($inv != '') {
                        $invoiceArray[] = $inv;
                    }
                }
            }

            if (isset($request->edit) && $request->edit != '') {

                // =============================
                // OUT REQUEST VALIDATIONS
                // =============================
                $requestNo = 'OUT' . str_pad($request->edit, 4, '0', STR_PAD_LEFT);

                // Request No duplicate check
                $reqExists = OutGatepassMaterials::where('request_no', $requestNo)->exists();

                if ($reqExists) {
                    return back()->withErrors([
                        'request_no' => "Request No {$requestNo} already exists."
                    ])->withInput();
                }

                // Invoice duplicate check
                if (!empty($invoiceArray)) {

                    $existingInvoices = GatepassSlno::whereIn('gp_invNo', $invoiceArray)
                        ->select('gp_invNo', 'gp_reqNo')
                        ->get();

                    if ($existingInvoices->count() > 0) {

                        $msg = '';
                        foreach ($existingInvoices as $inv) {
                            $msg .= "Invoice {$inv->gp_invNo} already used in Request No {$inv->gp_reqNo}. ";
                        }

                        return back()->withErrors(['invoice_no' => $msg])->withInput();
                    }
                }

                $out_material = new OutGatepassMaterials;
                $out_material->userID = auth()->user()->id;
                $out_material->Out_Approve_Step = 1;
                $out_material->request_time = $currentTime->format('H:i');
                $out_material->request_by = $request->request_by;
                $out_material->request_date = $request->request_date;
                $out_material->org_id = $request->org_id;
                $out_material->vehicle_no = $request->vehicle_no;
                $out_material->vehicle_weight = $request->vehicle_weight;
                $out_material->weight_type = $request->weight_type;
                $out_material->vehicle_weight_kg = $request->vehicle_weight_kg;
                if ($request->hasFile('weight_attachment')) {
                    $name = $request->file('weight_attachment')->getClientOriginalName();
                    $out_material->weight_attachment = $request->file('weight_attachment')->storeAs('GatePass', $name);
                }
                $out_material->insurance_no = $request->insurance_no;
                $out_material->insurance_dt = $request->insurance_dt;
                $out_material->vehicle_in_time = $request->vehicle_in_time;
                $out_material->driver_name = $request->driver_name;
                $out_material->driver_number = $request->driver_number;
                $out_material->dl_no = $request->dl_no;
                $out_material->dl_expire = $request->dl_expire;
                if ($request->has('invoice_nos')) {
                    $invoiceList = implode(',', $request->invoice_nos);
                    $out_material->invoice_no = $invoiceList;
                } else {
                    $out_material->invoice_no = $request->invoice_no;
                }
                $out_material->bill_no = $request->bill_no;
                $out_material->sec_guard_name = $request->sec_guard_name;
                $out_material->from_address = $request->from_address;
                $out_material->to_address = $request->to_address;
                $out_material->remarks = $request->remarks;
                $out_material->save();
                $out_material->request_no = 'OUT' . str_pad($request->edit, 4, '0', STR_PAD_LEFT);
                $out_material->gate_pass_no = 'GPN' . str_pad($request->edit, 4, '0', STR_PAD_LEFT);
                $out_material->save();
                if (!empty($request->material_id) && $request->vehicle_weight == 'Loaded') {
                    foreach ($request->material_id as $i => $matId) {

                        $serialInserted = false;

                        foreach ($request->slno_details ?? [] as $slnoString) {

                            if (trim($slnoString) === '') continue;

                            foreach (explode(',', $slnoString) as $item) {

                                $item = trim($item);
                                if ($item === '') continue;

                                [$sMatId, $slnoDtls] = explode(':', $item, 2);

                                if ($sMatId != $matId) continue;

                                $slno = new GatepassSlno();
                                $slno->type       = 'OUT';
                                $slno->gp_reqNo   = $out_material->request_no;
                                $slno->gp_invNo   = $request->inv_no[$i] ?? '';
                                $slno->matId      = $matId;
                                $slno->custName   = $request->customer_name[$i] ?? '';
                                $slno->matName    = $request->model_name[$i] ?? '';
                                $slno->invUom     = $request->inv_uom[$i] ?? '';
                                $slno->dispQty    = $request->dispatch_qty[$i] ?? '';
                                $slno->slno_dtls  = trim($slnoDtls);
                                $slno->save();

                                $serialInserted = true;
                            }
                        }

                        if (!$serialInserted) {

                            $slno = new GatepassSlno();
                            $slno->type     = 'OUT';
                            $slno->gp_reqNo = $out_material->request_no;
                            $slno->gp_invNo   = $request->inv_no[$i] ?? '';
                            $slno->matId    = $matId;
                            $slno->custName = $request->customer_name[$i] ?? '';
                            $slno->matName  = $request->model_name[$i] ?? '';
                            $slno->invUom   = $request->inv_uom[$i] ?? '';
                            $slno->dispQty  = $request->dispatch_qty[$i] ?? '';
                            $slno->save();
                        }
                    }
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
                $approve->GatepassID = $out_material->request_no;
                $approve->status = 1;
                if ($request->during_approval != '') {
                    $approve->action = $request->during_approval;
                } elseif ($request->pre_post_approval != '') {
                    $approve->pre_post_approval = $request->pre_post_approval;
                } else {
                    $approve->action = 'Request Raised';
                }
                $approve->comment_text = $out_material->remarks;
                $approve->ip_address = $request->getClientIp();
                $approve->device_name = $request->server('HTTP_USER_AGENT');
                $approve->days_for_holding = $request->days_for_holding;
                $approve->Forward_To = $request->Forward_To;
                $approve->save();
                if ($out_material) {
                    if (isset($request['item_desc']) && !empty($request['item_desc'])) {
                        foreach ($request['item_desc'] as $key => $val) {
                            if (isset($request['uom_id'][$key]) && $request['uom_id'][$key] != 0) {
                                $outGatepassItemDetails = new OutGatepassItemDetails;
                                $outGatepassItemDetails->in_gatepass_id = $out_material->id;
                                $outGatepassItemDetails->item_desc = isset($request['item_desc'][$key]) ? $request['item_desc'][$key] : '';
                                $outGatepassItemDetails->uom_id = isset($request['uom_id'][$key]) ? $request['uom_id'][$key] : '';
                                $outGatepassItemDetails->item_qty = isset($request['item_qty'][$key]) ? $request['item_qty'][$key] : '';
                                $outGatepassItemDetails->item_remark = isset($request['item_remark'][$key]) ? $request['item_remark'][$key] : '';
                                $outGatepassItemDetails->save();
                            }
                        }
                    } else {
                        DB::commit();
                        return redirect('GatePass/material-list')->with('success', 'Added Successfully....');
                    }
                }
            } else {

                if (!empty($invoiceArray)) {

                    $existingInvoices = GatepassSlno::whereIn('gp_invNo', $invoiceArray)
                        ->select('gp_invNo', 'gp_reqNo')
                        ->get();

                    if ($existingInvoices->count() > 0) {

                        $msg = '';
                        foreach ($existingInvoices as $inv) {
                            $msg .= "Invoice {$inv->gp_invNo} already used in Request No {$inv->gp_reqNo}. ";
                        }

                        return back()->withErrors(['invoice_no' => $msg])->withInput();
                    }
                }

                $in_material = new InGatepassMaterials;
                $in_material->userID = auth()->user()->id;
                $in_material->Approve_Step = 1;
                $in_material->request_time = $currentTime->format('H:i');
                $in_material->request_by = $request->request_by;
                $in_material->request_date = $request->request_date;
                $in_material->org_id = $request->org_id;
                $in_material->vehicle_no = $request->vehicle_no;
                $in_material->vehicle_weight = $request->vehicle_weight;
                $in_material->weight_type = $request->weight_type;
                $in_material->vehicle_weight_kg = $request->vehicle_weight_kg;
                if ($request->hasFile('weight_attachment')) {
                    $name = $request->file('weight_attachment')->getClientOriginalName();
                    $in_material->weight_attachment = $request->file('weight_attachment')->storeAs('GatePass', $name);
                }
                $in_material->insurance_no = $request->insurance_no;
                $in_material->insurance_dt = $request->insurance_dt;
                $in_material->vehicle_in_time = $request->vehicle_in_time;
                $in_material->driver_name = $request->driver_name;
                $in_material->driver_number = $request->driver_number;
                $in_material->dl_no = $request->dl_no;
                $in_material->dl_expire = $request->dl_expire;
                if ($request->has('invoice_nos')) {
                    $invoiceList = implode(',', $request->invoice_nos);
                    $in_material->invoice_no = $invoiceList;
                } else {
                    $in_material->invoice_no = $request->invoice_no;
                }
                $in_material->bill_no = $request->bill_no;
                $in_material->sec_guard_name = $request->sec_guard_name;
                $in_material->from_address = $request->from_address;
                $in_material->to_address = $request->to_address;
                $in_material->remarks = $request->remarks;
                $in_material->save();
                $in_material->request_no = 'IN' . str_pad($in_material->id, 4, '0', STR_PAD_LEFT);
                $in_material->gate_pass_no = 'GPN' . str_pad($in_material->id, 4, '0', STR_PAD_LEFT);
                $in_material->save();
                if (!empty($request->matid) && $request->vehicle_weight == 'Loaded') {
                    foreach ($request->matid as $i => $matId) {
                        $slno = new GatepassSlno();
                        $slno->type = 'IN';
                        $slno->gp_reqNo  = $in_material->request_no;
                        $slno->gp_invNo   = $request->inv_no[$i] ?? '';
                        $slno->matId     = $matId;
                        $slno->custName  = $request->supplier_name[$i] ?? '';
                        $slno->matName   = $request->material_name[$i] ?? '';
                        $slno->invUom    = $request->trnst_uom[$i] ?? '';
                        $slno->dispQty   = $request->trnst_dip_qty[$i] ?? '';
                        $slno->save();
                    }
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
                $approve->GatepassID = $in_material->request_no;
                $approve->status = 1;
                if ($request->during_approval != '') {
                    $approve->action = $request->during_approval;
                } elseif ($request->pre_post_approval != '') {
                    $approve->pre_post_approval = $request->pre_post_approval;
                } else {
                    $approve->action = 'Request Raised';
                }
                $approve->comment_text = $in_material->remarks;
                $approve->ip_address = $request->getClientIp();
                $approve->device_name = $request->server('HTTP_USER_AGENT');
                $approve->days_for_holding = $request->days_for_holding;
                $approve->Forward_To = $request->Forward_To;
                $approve->save();
                if ($in_material) {
                    if (isset($request['item_desc']) && !empty($request['item_desc'])) {
                        foreach ($request['item_desc'] as $key => $val) {
                            if (isset($request['uom_id'][$key]) && $request['uom_id'][$key] != 0) {
                                $inGatepassItemDetails = new InGatepassItemDetails;
                                $inGatepassItemDetails->in_gatepass_id = $in_material->id;
                                $inGatepassItemDetails->item_desc = isset($request['item_desc'][$key]) ? $request['item_desc'][$key] : '';
                                $inGatepassItemDetails->uom_id = isset($request['uom_id'][$key]) ? $request['uom_id'][$key] : '';
                                $inGatepassItemDetails->item_qty = isset($request['item_qty'][$key]) ? $request['item_qty'][$key] : '';
                                $inGatepassItemDetails->item_remark = isset($request['item_remark'][$key]) ? $request['item_remark'][$key] : '';
                                $inGatepassItemDetails->save();
                            }
                        }
                    } else {
                        DB::commit();
                        return redirect('GatePass/material-list')->with('success', 'Added Successfully....');
                    }
                }
            }

            DB::commit();
            return redirect('GatePass/material-list')->with('success', 'Added Successfully....');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->withErrors([
                'error' => $e->getMessage()
            ])->withInput();
        }
    }

    public function delete_material($id)
    {
        InGatepassMaterials::find($id)->delete();
        return back()->with('success', 'Deleted Successfully....');
    }

    public function uploadCopy(Request $request, $id)
    {
        if ($request->hasFile('in_attachment')) {
            foreach ($request->file('in_attachment') as $file) {
                $name = $file->getClientOriginalName();
                $path = $file->storeAs('GatePass/GatePassIn', $name);

                $in_attach = new InGatepassAttachment();
                $in_attach->in_gatepass_req_id = $id;
                $in_attach->in_attach_file = $path;
                $in_attach->save();
            }

            return response()->json(['success' => 'Files Uploaded Successfully']);
        }

        return response()->json(['error' => 'No files found'], 400);
    }

    public function uploadoutCopy(Request $request, $id)
    {
        if ($request->hasFile('out_attachment')) {
            foreach ($request->file('out_attachment') as $file) {
                $name = $file->getClientOriginalName();
                $path = $file->storeAs('GatePass/GatePassOut', $name);

                $out_attach = new OutGatepassAttachment();
                $out_attach->out_gatepass_req_id = $id;
                $out_attach->out_attach_file = $path;
                $out_attach->save();
            }

            return response()->json(['success' => 'Files Uploaded Successfully']);
        }

        return response()->json(['error' => 'File not found'], 404);
    }

	public function getMaterial(Request $request, $mid = null)
    {
        if (!$mid) {
            return response()->json(['error' => 'Material ID not found'], 400);
        }

        // Queries without date filtering
        $total_received_qty = Production::where('Raw_Material', $mid)
            ->where('Approve_status', 'APPROVE')
            ->sum('Quantity') +
            FinishedGoodGatepass::where('Material_id', $mid)
            ->where('Approve_status', 'APPROVE')
            ->sum('Quantity');

        $total_issue_qty = OutGatepassItemDetails::where('out_gatepass_item_details.item_desc', $mid)
            ->sum('item_qty');

        $closing_qty = $total_received_qty - $total_issue_qty;

        return response()->json(['closing_balance' => $closing_qty]);
    }

    public function material_update(Request $request, $id)
    {
        $TimeZone = new \DateTimeZone('Asia/Kolkata');
        $currentTime = new \DateTime('now', $TimeZone);

        $type = $request->type;

        if ($type == 'in') {
            $material = InGatepassMaterials::find($id);
            $gatepassType = 'IN';
            $materialModel = InGatepassMaterials::class;
            $itemDetailsModel = InGatepassItemDetails::class;
        } else {
            $material = OutGatepassMaterials::find($id);
            $gatepassType = 'OUT';
            $materialModel = OutGatepassMaterials::class;
            $itemDetailsModel = OutGatepassItemDetails::class;
        }

        if (!$material) {
            return response()->json(['error' => 'Material not found'], 404);
        }

        // Update main material record
        if ($type == 'in') {
            $material->Approve_status = null;
            $material->Approve_Step = 1;
        } else {
            $material->Out_Approve_status = null;
            $material->Out_Approve_Step = 1;
        }
        $material->request_time = $currentTime->format('H:i');
        $material->request_by = $request->request_by;
        $material->request_date = $request->request_date;
        $material->org_id = $request->org_id;
        $material->vehicle_no = $request->vehicle_no;
        $material->vehicle_weight = $request->vehicle_weight;
        $material->weight_type = $request->weight_type;
        $material->vehicle_weight_kg = $request->vehicle_weight_kg;

        if ($request->hasFile('weight_attachment')) {
            $name = $request->file('weight_attachment')->getClientOriginalName();
            $material->weight_attachment = $request->file('weight_attachment')->storeAs('GatePass', $name);
        }

        $material->insurance_no = $request->insurance_no;
        $material->insurance_dt = $request->insurance_dt;
        $material->vehicle_in_time = $request->vehicle_in_time;
        $material->driver_name = $request->driver_name;
        $material->driver_number = $request->driver_number;
        $material->dl_no = $request->dl_no;
        $material->dl_expire = $request->dl_expire;

        if ($request->has('invoice_nos')) {
            $invoiceList = implode(',', $request->invoice_nos);
            $material->invoice_no = $invoiceList;
        } else {
            $material->invoice_no = $request->invoice_no;
        }

        $material->bill_no = $request->bill_no;
        $material->sec_guard_name = $request->sec_guard_name;
        $material->from_address = $request->from_address;
        $material->to_address = $request->to_address;
        $material->remarks = $request->remarks;
        $material->save();

        // Update serial numbers based on type
        GatepassSlno::where('gp_reqNo', $material->request_no)->delete();

        if ($gatepassType == 'OUT') {
            $materialMap = [];

            $materialIds = $request->input('material_id', []);
            if (!is_array($materialIds)) {
                $materialIds = empty($materialIds) ? [] : [$materialIds];
            }

            foreach ($materialIds as $i => $matId) {
                $materialMap[$matId] = [
                    'invNo'    => $request->inv_no[$i] ?? '',
                    'custName' => $request->customer_name[$i] ?? '',
                    'matName'  => $request->model_name[$i] ?? '',
                    'invUom'   => $request->inv_uom[$i] ?? '',
                    'dispQty'  => $request->dispatch_qty[$i] ?? '',
                ];
            }

            $slnoDetails = $request->input('slno_details', []);
            if (!is_array($slnoDetails)) {
                $slnoDetails = empty($slnoDetails) ? [] : [$slnoDetails];
            }

            if (!empty($slnoDetails)) {
                foreach ($slnoDetails as $slnoString) {
                    foreach (explode(',', $slnoString) as $item) {
                        if (trim($item) === '') continue;

                        [$matId, $slnoDtls] = explode(':', $item, 2);

                        if (!isset($materialMap[$matId])) continue;

                        $mat = $materialMap[$matId];

                        $slno = new GatepassSlno();
                        $slno->type = 'OUT';
                        $slno->gp_reqNo  = $material->request_no;
                        $slno->gp_invNo  = $mat['invNo'] ?? '';
                        $slno->matId     = $matId;
                        $slno->custName  = $mat['custName'] ?? '';
                        $slno->matName   = $mat['matName'] ?? '';
                        $slno->invUom    = $mat['invUom'] ?? '';
                        $slno->dispQty   = $mat['dispQty'] ?? '';
                        $slno->slno_dtls = trim($slnoDtls);
                        $slno->save();
                    }
                }
            }
        } else {
            // IN Gatepass
            $matIds = $request->input('matid', []);
            if (!is_array($matIds)) {
                $matIds = empty($matIds) ? [] : [$matIds];
            }

            if (!empty($matIds)) {
                foreach ($matIds as $i => $matId) {
                    $slno = new GatepassSlno();
                    $slno->type = 'IN';
                    $slno->gp_reqNo  = $material->request_no;
                    $slno->gp_invNo  = $request->inv_no[$i] ?? '';
                    $slno->matId     = $matId;
                    $slno->custName  = $request->supplier_name[$i] ?? '';
                    $slno->matName   = $request->material_name[$i] ?? '';
                    $slno->invUom    = $request->trnst_uom[$i] ?? '';
                    $slno->dispQty   = $request->trnst_dip_qty[$i] ?? '';
                    $slno->save();
                }
            }
        }

        // Create approval record for the update
        $approve = new MaterialGatePassApproval;
        $approve->userID = auth()->user()->id;

        // Determine role (same logic as store function)
        if (auth()->user()->role == 0) {
            $approve->role = 'Admin';
        } elseif (isset($CUSTEXT[2]['Inputer'])) {
            $approve->role = 'Inputer';
        } elseif (isset($CUSTEXT[2]['approver'])) {
            $approve->role = 'Approver';
        } else {
            $approve->role = 'Viewer';
        }

        $approve->GatepassID = $material->request_no;
        $approve->status = 1;
        $approve->action = 'Record Updated';
        $approve->comment_text = $request->remarks ?: 'Record was updated';
        $approve->ip_address = $request->getClientIp();
        $approve->device_name = $request->server('HTTP_USER_AGENT');
        $approve->days_for_holding = $request->days_for_holding;
        $approve->Forward_To = $request->Forward_To;
        $approve->save();

        // Update item details
        $itemDetailsModel::where('in_gatepass_id', $material->id)->delete();

        if (isset($request['item_desc']) && !empty($request['item_desc'])) {
            foreach ($request['item_desc'] as $key => $val) {
                if (isset($request['uom_id'][$key]) && $request['uom_id'][$key] != 0) {
                    $itemDetail = new $itemDetailsModel();
                    $itemDetail->in_gatepass_id = $material->id;
                    $itemDetail->item_desc = $request['item_desc'][$key] ?? '';
                    $itemDetail->uom_id = $request['uom_id'][$key] ?? '';
                    $itemDetail->item_qty = $request['item_qty'][$key] ?? '';
                    $itemDetail->item_remark = $request['item_remark'][$key] ?? '';
                    $itemDetail->save();
                }
            }
        }
        return redirect('GatePass/material-list')->with('success', 'Updated Successfully....');
    }

}
