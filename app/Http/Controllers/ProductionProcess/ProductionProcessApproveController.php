<?php

namespace App\Http\Controllers\ProductionProcess;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductionProcess\{Production_Process, Production_Process_Machine, Production_Process_Stage, Production_Process_Stage_Data, Production_Process_Approve};
use App\Models\FactoryCreater\{Factory_Product, Factory_Sub_Product, Factory_Sub_Sub_Product, Factory_Uom};
use App\Models\{Admin, Forwarded_Data, Department_Assign};
use App\Models\Master\Plant\{Master_Company_Name, Master_Machine_Code, Master_Machine_Name, Master_Make_Model};
use App\Models\MaterialManagement\{MaterialManagement_Add_Material};
use App\Models\BOM\{BOM};
use Session;

class ProductionProcessApproveController extends Controller
{
    public function ProductionProcessApprove(Request $request)
    {
        $EXT = Session::get('EXT');

        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));

        $query = new Production_Process;
        if ($fromdate && $todate) {
            $query = $query->whereBetween('created_at', [$fromdate, $todate]);
        }

        if (isset($EXT[19]['Forward']) && isset($EXT[19]['approver'])) {
            $query = $query->where(function ($query) use ($EXT) {
                $query->where('Approve_status', null)->where('Forward_Status', 0)->whereRaw("Approve_Step IN (" . implode(",", $EXT[19]['approver']) . ")");
            })
                ->orWhere(function ($query) {
                    $query->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1');
                })
                ->orWhereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1')
                ->orderBy('id', 'DESC');
        } elseif (isset($EXT[19]['Forward'])) {
            $query = $query->where('Forward_Status', 1)->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD")')->orderBy('id', 'DESC');
        } elseif (isset($EXT[19]['approver'])) {
            $query = $query->where('Approve_status', null)->where(['Forward_Status' => 0, 'status' => 0])->WhereRaw("Approve_Step IN (" . implode(",", $EXT[19]['approver']) . ")")->orderBy('id', 'DESC');
        }

        $ProductionProcess = $query->get();

        $Production_arr = [];
        foreach ($ProductionProcess as $val) {
            if ($val->Forward_Status != 1) {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="19" AND step="' . $val->Approve_Step . '")')->get();
            } else {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data` WHERE DataID="' . $val->id . '" AND DepartmentID=19 AND status=0)')->get();
            }
            $val->user = Admin::find($val->userID);
            $val->Product = Factory_Product::find($val->Product);
            $val->Sub_Product = Factory_Sub_Product::find($val->Sub_Product);
            $val->Sub_Sub_Product = Factory_Sub_Sub_Product::find($val->Sub_Sub_Product);
            //$val->Raw_Material = MaterialManagement_Add_Material::find($val->Raw_Material);
            $val->Raw_Material = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name')
            ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
            ->where('materialmanagement_add_material.id',$val->Raw_Material)
            ->first();
            //$val->UOM = Factory_Uom::find($val->UOM);

            $Production_arr[] = $val;
        }

        return view('ProductionProcess/ProductionProcessApproveList', ['ProductionProcess' => $Production_arr, 'fromdate' => $fromdate, 'todate' => $dateto]);
    }

    public function view_approve($id, $type)
    {
        $appro = Production_Process_Approve::where('Production_Process_id', $id)->get();
        $approves = [];
        foreach ($appro as $val) {
            $val->user = Admin::find($val->userID);
            array_push($approves, $val);
        }

        $Product = Factory_Product::all();
        $Sub_Product = Factory_Sub_Product::all();
        $Sub_Sub_Product = Factory_Sub_Sub_Product::all();
        $employeeName = Admin::where('role', 1)->whereRaw('id IN (SELECT userID FROM employee_department where Departments="19")')->get();
        $UOM = Factory_Uom::all();
        $Machine_Name = Master_Machine_Name::all();
        $Machine_Code = Master_Machine_Code::all();
        $Machine_Company = Master_Company_Name::all();
        $Make_Model = Master_Make_Model::all();
        $BOM_DATA = BOM::where('Approve_status', 'APPROVE')->get();
        $Raw_Material = [];
        foreach ($BOM_DATA as $Val) {
            if (isset($Val->Raw_Material_FG)) {
                //$Val->RawMaterial = MaterialManagement_Add_Material::find($Val->Raw_Material_FG);
                $Val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name')
                ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                ->where('materialmanagement_add_material.id',$Val->Raw_Material_FG)
                ->first();
                $Raw_Material[$Val->Raw_Material_FG] = $Val;
            }
        }
        $Filtered_Array = array_values($Raw_Material);
        $edit = Production_Process::find($id);

        $All_Data = [];
        $Stage_count = 0;
        $Stage_data_count = 0;
        $Machines_count = 0;

        if (isset($edit->id) && $edit->id != '') {
            $Stage = Production_Process_Stage::where('Production_Process_Id', $edit->id)->get();
            $Stage_count = $Stage->count();

            if ($Stage_count > 0) {
                foreach ($Stage as $val) {
                    $val->Stage_data = Production_Process_Stage_Data::where('Production_Process_Stage_Id', $val->id)->get();
                    $Stage_data_count += $val->Stage_data->count();

                    if ($val->Stage_data->count() > 0) {
                        foreach ($val->Stage_data as $val1) {
                            $val1->Machine = Production_Process_Machine::where('Production_Process_Stage_Data_Id', $val1->id)->get();
                            $Machines_count += $val1->Machine->count();
                        }
                    }
                    $All_Data[] = $val;
                }
            }
        }

        $nextID = $this->next($id, $type);

        return view('ProductionProcess/ProductionProcessApprove', ['edit' => $edit, 'All_Data' => $All_Data, 'Product' => $Product, 'Sub_Product' => $Sub_Product, 'Sub_Sub_Product' => $Sub_Sub_Product, 'approves' => $approves, 'nextID' => $nextID, 'employeeName' => $employeeName, 'UOM' => $UOM, 'Machine_Name' => $Machine_Name, 'Machine_Code' => $Machine_Code, 'Machine_Company' => $Machine_Company, 'Make_Model' => $Make_Model, 'Raw_Material' => $Filtered_Array]);
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
            Production_Process::where('id', $request->approveID)->update(['Approve_status' => $request->during_approval]);
            Production_Process_Approve::where('Production_Process_id', $request->approveID)->where('status', 1)->update(['status' => 0]);
        }

        $check = Production_Process::find($request->approveID);
        if ($request->during_approval === 'APPROVE') {
            $status = Forwarded_Data::where('DataID', $request->approveID)->update(['status' => 1]);
            Production_Process::where('id', $request->approveID)->update(['Forward_Status' => 0]);

            $DepartStepcount2 = Department_Assign::where(['departments' => 19, 'step' => 2])->count();
            $DepartStepcount3 = Department_Assign::where(['departments' => 19, 'step' => 3])->count();

            if ($check->Approve_Step == 1 && $DepartStepcount2 > 0) {
                Production_Process::where('id', $request->approveID)->update(['Approve_Step' => 2, 'Approve_status' => null]);
            }

            if ($check->Approve_Step == 2 &&  $DepartStepcount3 > 0) {
                Production_Process::where('id', $request->approveID)->update(['Approve_Step' => 3, 'Approve_status' => null]);
            }
        }

        if ($request->during_approval === 'FORWARD') {
            Forwarded_Data::where(['DepartmentID' => 19, 'DataID' => $request->approveID])->update(['status' => 1]);
            Production_Process::where('id', $request->approveID)->update(['Forward_Status' => 1]);

            $forward = new Forwarded_Data;
            $forward->userID = auth()->user()->id;
            $forward->Forward_To_id = $request->Forward_To;
            $forward->DepartmentID = 19;
            $forward->DataID = $request->approveID;
            $forward->status = 0;

            $forward->save();
        }

        $approve = new Production_Process_Approve;
        $approve->userID = auth()->user()->id;
        if (auth()->user()->role == 0) {
            $approve->role = 'Admin';
        } elseif (isset($EXT[19]['Inputer'])) {
            $approve->role = 'Inputer';
        } elseif (isset($EXT[19]['approver'])) {
            $approve->role = 'Approver';
        } else {
            $approve->role = 'Viewer';
        }
        $approve->Production_Process_id = $request->approveID;
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
            Production_Process::where('id', $request->approveID)->update(['Approve_status' => null]);
            return redirect('ProductionProcess/ProductionProcessList')->with('success', 'successfully.....');
        } elseif (($request->pre_post_approval == 'AUDIT' || $request->pre_post_approval == 'INTIMATION' || $request->pre_post_approval == 'QUERY') && $request->non_acting == 1) {
            return redirect('ProductionProcess/ProductionProcessList')->with('success', 'successfull.....');
        } else {
            return redirect('ProductionProcess/ProductionProcessApproveList')->with('success', 'Approved successfully.....');
        }
    }

    public function CheckHoldExpiry()
    {
        $Approve = Production_Process_Approve::all();

        return response()->json($Approve);
    }

    public function UpdateStatus(Request $request)
    {
        $Production_Process_id = $request->input('Production_Process_id');
        $userID = $request->input('userID');

        $approves = Production_Process_Approve::where('Production_Process_id', $Production_Process_id)->where('userID', $userID)->update(['status' => 0]);
        $factory =  Production_Process::where('id', $Production_Process_id)->update(['Approve_status' => null]);

        $approve = new Production_Process_Approve;
        $approve->role = 'AUTO';
        $approve->Production_Process_id = $Production_Process_id;
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

        $approvesss = Production_Process_Approve::where('Production_Process_id', $id)->where('action', 'HOLD')->update(['days_for_holding' => $currentDate, 'status' => 0]);
        $factory =  Production_Process::where('id', $id)->update(['Approve_status' => null]);

        $approve = new Production_Process_Approve;
        $approve->userID = auth()->user()->id;
        if (auth()->user()->role == 0) {
            $approve->role = 'Admin';
        } elseif (isset($EXT[19]['approver'])) {
            $approve->role = 'Approver';
        } elseif (isset($EXT[19]['inputer'])) {
            $approve->role = 'Inputer';
        } else {
            $approve->role = 'Viewer';
        }
        $approve->Production_Process_id = $id;
        $approve->status = 1;
        $approve->action = 'Hold Released';
        $approve->comment_text = $request->comment_text;
        $approve->ip_address = $request->getClientIp();
        $approve->device_name = $request->server('HTTP_USER_AGENT');
        $approve->save();

        return redirect('ProductionProcess/ProductionProcessList')->with('success', 'Hold Released successfully.....');
    }
}
