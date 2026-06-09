<?php

namespace App\Http\Controllers\GatePass;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GatePass\{Gatepass_Employee, Gatepass_Visitor, Gatepass_Visitor_Name, InGatepassMaterials, InGatepassItemDetails, OutGatepassMaterials, OutGatepassItemDetails};



class GatePassController extends Controller
{
    public function Employee_Store(Request $request)
    {
        $request->all();
        $TimeZone = new \DateTimeZone('Asia/Kolkata');
        $currentTime = new \DateTime('now', $TimeZone);

        if ($request->edit != '') {
            $employee = Gatepass_Employee::find($request->edit);
        } else {
            $employee = new Gatepass_Employee;
            $employee->userID = auth()->user()->id;
        }
        $employee->request_time = $currentTime->format('H:i');
        $employee->request_by = $request->request_by;
        $employee->request_date = $request->request_date;
        $employee->employee_name = $request->employee_name;
        $employee->request_type = $request->request_type;
        $employee->request_out_time = $request->request_out_time;
        $employee->request_in_time = $request->request_in_time;
        $employee->reason = $request->reason;
        $employee->remarks = $request->remarks;
        if (!isset($request->draft)) {
            $employee->status = 0;
        } else {
            $employee->status = 1;
        }

        $employee->save();

        $employee->request_no = 'RN' . str_pad($employee->id, 4, '0', STR_PAD_LEFT);
        $employee->gate_pass_no = 'GPN' . str_pad($employee->id, 4, '0', STR_PAD_LEFT);

        $employee->save();

        return redirect('GatePass/List')->with('success', 'Added Successfully....');
    }

    public function delete_Employee($id)
    {
        Gatepass_Employee::find($id)->delete();
        return back()->with('success', 'Deleted Successfully....');
    }

    public function Visitor_store(Request $request)
    {
        $TimeZone = new \DateTimeZone('Asia/Kolkata');
        $currentTime = new \DateTime('now', $TimeZone);

        if ($request->edit != '') {
            $visitor = Gatepass_Visitor::find($request->edit);
        } else {
            $visitor = new Gatepass_Visitor;
            $visitor->userID = auth()->user()->id;
        }
        $visitor->request_time = $currentTime->format('H:i');
        $visitor->request_by = $request->request_by;
        $visitor->request_date = $request->request_date;
        $visitor->remarks = $request->remarks;
        if (!isset($request->draft)) {
            $visitor->status = 0;
        } else {
            $visitor->status = 1;
        }

        $visitor->save();

        $visitor->request_no = 'RN' . str_pad($visitor->id, 4, '0', STR_PAD_LEFT);
        $visitor->gate_pass_no = 'GPN' . str_pad($visitor->id, 4, '0', STR_PAD_LEFT);
        $visitor->save();

        $res = $request->input();

        if (isset($res['visitor_name']) && $res['visitor_name'] != '') {
            foreach ($res['visitor_name'] as $key => $val) {
                $visitorEditId = isset($res['visitorEditId'][$key]) ? $res['visitorEditId'][$key] : '';
                if ($visitorEditId != '') {
                    $visitorsname = Gatepass_visitor_name::where('id', $visitorEditId)->update(['visitor_name' => $res['visitor_name'][$key] ?? '', 'person_to_meet' => $res['person_to_meet'][$key] ?? 0, 'department' => $res['department'][$key] ?? 0, 'request_through' => $res['request_through'][$key] ?? 0, 'reason_for_visit' => $res['reason_for_visit'][$key] ?? '', 'visitor_address' => $res['visitor_address'][$key] ?? '', 'visitor_in_time' => $res['visitor_in_time'][$key] ?? '', 'visitor_out_time' => $res['visitor_out_time'][$key] ?? '', 'vehicle' => $res['vehicle'][$key] ?? '', 'vehicle_reg_no' => $res['vehicle_reg_no'][$key] ?? '', 'make_model' => $res['make_model'][$key] ?? '']);
                } else {
                    $visitorsname = new Gatepass_Visitor_Name;
                    $visitorsname->visitorID = $visitor->id;
                    $visitorsname->visitor_name = $res['visitor_name'][$key] ?? '';
                    $visitorsname->person_to_meet = $res['person_to_meet'][$key] ?? 0;
                    $visitorsname->department = $res['department'][$key] ?? 0;
                    $visitorsname->request_through = $res['request_through'][$key] ?? 0;
                    $visitorsname->reason_for_visit = $res['reason_for_visit'][$key] ?? '';
                    $visitorsname->visitor_address = $res['visitor_address'][$key] ?? '';
                    $visitorsname->visitor_in_time = $res['visitor_in_time'][$key] ?? '';
                    $visitorsname->visitor_out_time = $res['visitor_out_time'][$key] ?? '';
                    $visitorsname->vehicle = $res['vehicle'][$key] ?? '';
                    $visitorsname->vehicle_reg_no = $res['vehicle_reg_no'][$key] ?? '';
                    $visitorsname->make_model = $res['make_model'][$key] ?? '';

                    $visitorsname->save();
                }
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
        // return $request->all();
        $TimeZone = new \DateTimeZone('Asia/Kolkata');
        $currentTime = new \DateTime('now', $TimeZone);

        if (isset($request->edit) && $request->edit != '') {
            $out_material = new OutGatepassMaterials;
            $out_material->userID = auth()->user()->id;
            $out_material->request_time = $currentTime->format('H:i');
            $out_material->request_by = $request->request_by;
            $out_material->request_date = $request->request_date;
            // $out_material->request_time = $request->request_time;
            // $out_material->request_time = $request->request_time;
            $out_material->org_id = $request->org_id;
            $out_material->vehicle_no = $request->vehicle_no;
            $out_material->vehicle_weight = $request->vehicle_weight;
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
            $out_material->invoice_no = $request->invoice_no;
            $out_material->bill_no = $request->bill_no;
            $out_material->sec_guard_name = $request->sec_guard_name;
            $out_material->from_address = $request->from_address;
            $out_material->to_address = $request->to_address;
            $out_material->remarks = $request->remarks;
            if (!isset($request->draft)) {
                $out_material->status = '1';
            } else {
                $out_material->status = '0';
            }
            $out_material->save();
            $out_material->request_no = 'OUT' . str_pad($request->edit, 4, '0', STR_PAD_LEFT);
            $out_material->gate_pass_no = 'GPN' . str_pad($request->edit, 4, '0', STR_PAD_LEFT);
            $out_material->save();
            if($out_material){
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
                    return redirect('GatePass/material-list')->with('success', 'Added Successfully....');
                }
            }
        } else {
            $in_material = new InGatepassMaterials;
            $in_material->userID = auth()->user()->id;
            $in_material->request_time = $currentTime->format('H:i');
            $in_material->request_by = $request->request_by;
            $in_material->request_date = $request->request_date;
            $in_material->org_id = $request->org_id;
            $in_material->vehicle_no = $request->vehicle_no;
            $in_material->vehicle_weight = $request->vehicle_weight;
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
            $in_material->invoice_no = $request->invoice_no;
            $in_material->bill_no = $request->bill_no;
            $in_material->sec_guard_name = $request->sec_guard_name;
            $in_material->from_address = $request->from_address;
            $in_material->to_address = $request->to_address;
            $in_material->remarks = $request->remarks;
            if (!isset($request->draft)) {
                $in_material->status = '1';
            } else {
                $in_material->status = '0';
            }
            $in_material->save();
            $in_material->request_no = 'IN' . str_pad($in_material->id, 4, '0', STR_PAD_LEFT);
            $in_material->gate_pass_no = 'GPN' . str_pad($in_material->id, 4, '0', STR_PAD_LEFT);
            $in_material->save();
            if($in_material){
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
                    return redirect('GatePass/material-list')->with('success', 'Added Successfully....');
                }
            }
        }
        return redirect('GatePass/material-list')->with('success', 'Added Successfully....');
    }

    public function delete_material($id)
    {
        InGatepassMaterials::find($id)->delete();
        return back()->with('success', 'Deleted Successfully....');
    }

    public function uploadCopy(Request $request, $id)
    {
        $up = InGatepassMaterials::find($id);
        if ($request->hasFile('in_copy'.$id)) {
            $name = $request->file('in_copy'.$id)->getClientOriginalName();
            $up->in_copy = $request->file('in_copy'.$id)->storeAs('GatePass/GatePassIn', $name);
            $up->save();

            return response()->json(['success' => 'Uploaded Successfully']);
        }

        return response()->json(['error' => 'File not found'], 404);
    }

    public function uploadoutCopy(Request $request, $id)
    {
        $up = OutGatepassMaterials::find($id);
        if ($request->hasFile('out_copy'.$id)) {
            $name = $request->file('out_copy'.$id)->getClientOriginalName();
            $up->out_copy = $request->file('out_copy'.$id)->storeAs('GatePass/GatePassOut', $name);
            $up->save();

            return response()->json(['success' => 'Uploaded Successfully']);
        }

        return response()->json(['error' => 'File not found'], 404);
    }

}
