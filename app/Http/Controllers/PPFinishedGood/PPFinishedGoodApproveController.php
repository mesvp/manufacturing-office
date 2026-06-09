<?php

namespace App\Http\Controllers\PPFinishedGood;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\Plant\{Master_Manufacturing_unit, Master_category};
use App\Models\Master\{Master_Plant_Machinery,Prj_Subproject,Prj_Project};
use App\Models\PPFinishedGood\{PPFinishedGood, PPFinishedGood_data, PPFinishedGood_Approve};
use App\Models\FactoryCreater\{Factory_Organisation, Factory_Uom, Factory_Product, Factory_Sub_Product, Factory_Sub_Sub_Product,prj_organisation};
use App\Models\{CheckBox, Admin, Forwarded_Data, Department_Assign};
use App\Models\MaterialManagement\{MaterialManagement_Add_Material};
use App\Models\ProductCategories\{ProductCategories_Add_Product};
use Session;


class PPFinishedGoodApproveController extends Controller
{
    public function PPFinishedGood_Approve(Request $request)
    {
        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));

        $EXT = Session::get('EXT');

        $query = new PPFinishedGood;
        if ($fromdate && $todate) {
            $query = $query->whereBetween('created_at', [$fromdate, $todate]);
        }

        if (isset($EXT[7]['Forward']) && isset($EXT[7]['approver'])) {
            $query = $query->where(function ($query) use ($EXT) {
                $query->where('Approve_status', null)->where('Forward_Status', 0)->whereRaw("Approve_Step IN (" . implode(",", $EXT[7]['approver']) . ")");
            })
                ->orWhere(function ($query) {
                    $query->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1');
                })
                ->orWhereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1')
                ->orderBy('id', 'DESC');
        } elseif (isset($EXT[7]['Forward'])) {
            $query = $query->where('Forward_Status', 1)->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD")')->orderBy('id', 'DESC');
        } elseif (isset($EXT[7]['approver'])) {
            $query = $query->where('Approve_status', null)->where(['Forward_Status' => 0, 'status' => 0])->WhereRaw("Approve_Step IN (" . implode(",", $EXT[7]['approver']) . ")")->orderBy('id', 'DESC');
        }

        $PP = $query->get();

        $PP_arr = [];
        foreach ($PP as $val) {
            $val->data = PPFinishedGood_data::where('PPFinishedGood_id', $val->id)->first();
            if (isset($val->data)) {
                if ($val->Forward_Status != 1) {
                    $val->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="7" AND step="' . $val->Approve_Step . '")')->get();
                } else {
                    $val->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data` WHERE DataID="' . $val->id . '" AND DepartmentID=7 AND status=0)')->get();
                }
                $val->user = Admin::find($val->userID);
                $val->Organization = prj_organisation::find($val->data->Organization);
                $val->Manufacturing_Unit = Prj_Project::find($val->data->Manufacturing_Unit);
                $val->plant_name = Prj_Subproject::find($val->data->Plant_name);
                $val->category = Master_category::find($val->data->category);
                $val->Product = Factory_Product::find($val->data->Product);
                //$val->RawMaterial = MaterialManagement_Add_Material::find($val->data->Raw_Material);
                $val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                ->where('materialmanagement_add_material.id',$val->data->Raw_Material)->first();

                $val->UOM = Factory_Uom::find($val->data->UOM);
            }

            $PP_arr[] = $val;
        }

        return view('PPFinishedGood/PPFinishedGoodApproveList', ['PP_data' => $PP_arr, 'fromdate' => $fromdate, 'todate' => $dateto]);
    }

    public function view_approve($id, $type)
    {
        $appro = PPFinishedGood_Approve::where('PPFinishedGood_id', $id)->get();
        $approves = [];
        foreach ($appro as $val) {
            $val->user = Admin::find($val->userID);
            array_push($approves, $val);
        }

        $Organization = prj_organisation::all();
        $Manufacturing_Unit = Prj_Project::all();
        $UOM = Factory_Uom::all();
        $Plant_Name = Prj_Subproject::all();
        $category = Master_category::all();
        $Product = Factory_Product::all();
        $Sub_Product = Factory_Sub_Product::all();
        $Sub_Sub_Product = Factory_Sub_Sub_Product::all();
        $employeeName = Admin::where('role', 1)->whereRaw('id IN (SELECT userID FROM employee_department where Departments="7")')->get();
        $product_data = ProductCategories_Add_Product::where('Approve_status', 'APPROVE')->get();
        $Raw_Material = [];
        foreach ($product_data as $Val) {
            if (isset($Val->Raw_Material)) {
                //$Val->RawMaterial = MaterialManagement_Add_Material::find($Val->Raw_Material);
                $Val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                ->where('materialmanagement_add_material.id',$Val->Raw_Material)->first();
                $Raw_Material[] = $Val;
            }
        }

        $edit = PPFinishedGood::find($id);
        $pp = array();
        $pp_count = 0;
        if (isset($id)) {
            $pp = PPFinishedGood_data::where('PPFinishedGood_id', $id)->get();
            $pp_count += $pp->count();
        }

        $nextID = $this->next($id, $type);

        return view('PPFinishedGood/PPFinishedGoodApprove', ['edit' => $edit, 'pp' => $pp, 'Manufacturing_Unit' => $Manufacturing_Unit, 'Organization' => $Organization, 'pp_count' => $pp_count, 'UOM' => $UOM, 'Plant_Name' => $Plant_Name, 'category' => $category, 'Product' => $Product, 'Sub_Product' => $Sub_Product, 'Sub_Sub_Product' => $Sub_Sub_Product, 'nextID' => $nextID, 'employeeName' => $employeeName, 'approves' => $approves, 'Raw_Material' => $Raw_Material]);
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
            PPFinishedGood::where('id', $request->approveID)->update(['Approve_status' => $request->during_approval]);
            PPFinishedGood_Approve::where('PPFinishedGood_id', $request->approveID)->where('status', 1)->update(['status' => 0]);
        }

        $check = PPFinishedGood::find($request->approveID);
        if ($request->during_approval === 'APPROVE') {
            $status = Forwarded_Data::where('DataID', $request->approveID)->update(['status' => 1]);
            $DepartStepcount2 = Department_Assign::where(['departments' => 7, 'step' => 2])->count();
            $DepartStepcount3 = Department_Assign::where(['departments' => 7, 'step' => 3])->count();

            if ($check->Approve_Step == 1 && $DepartStepcount2 > 0) {
                PPFinishedGood::where('id', $request->approveID)->update(['Approve_Step' => 2, 'Approve_status' => null]);
            }

            if ($check->Approve_Step == 2 &&  $DepartStepcount3 > 0) {
                PPFinishedGood::where('id', $request->approveID)->update(['Approve_Step' => 3, 'Approve_status' => null]);
            }
        }

        if ($request->during_approval === 'FORWARD') {
            Forwarded_Data::where(['DepartmentID' => 7, 'DataID' => $request->approveID])->update(['status' => 1]);
            PPFinishedGood::where('id', $request->approveID)->update(['Forward_Status' => 1]);

            $forward = new Forwarded_Data;
            $forward->userID = auth()->user()->id;
            $forward->Forward_To_id = $request->Forward_To;
            $forward->DepartmentID = 7;
            $forward->DataID = $request->approveID;
            $forward->status = 0;

            $forward->save();
        }

        $approve = new PPFinishedGood_Approve;
        $approve->userID = auth()->user()->id;
        if (auth()->user()->role == 0) {
            $approve->role = 'Admin';
        } elseif (isset($EXT[7]['Inputer'])) {
            $approve->role = 'Inputer';
        } elseif (isset($EXT[7]['approver'])) {
            $approve->role = 'Approver';
        } else {
            $approve->role = 'Viewer';
        }
        $approve->PPFinishedGood_id = $request->approveID;
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
            PPFinishedGood::where('id', $request->approveID)->update(['Approve_status' => null]);
            return redirect('PPFinishedGood/PPFinishedGoodList')->with('success', 'successfully.....');
        } elseif (($request->pre_post_approval == 'AUDIT' || $request->pre_post_approval == 'INTIMATION' || $request->pre_post_approval == 'QUERY') && $request->non_acting == 1) {
            return redirect('PPFinishedGood/PPFinishedGoodList')->with('success', 'successfull.....');
        } else {
            return redirect('PPFinishedGood/PPFinishedGoodApproveList')->with('success', 'Approved successfully.....');
        }
    }

    public function CheckHoldExpiry()
    {
        $Approve = PPFinishedGood_Approve::all();

        return response()->json($Approve);
    }

    public function UpdateStatus(Request $request)
    {
        $PPFinishedGood_id = $request->input('PPFinishedGood_id');
        $userID = $request->input('userID');

        $approves = PPFinishedGood_Approve::where('PPFinishedGood_id', $PPFinishedGood_id)->where('userID', $userID)->update(['status' => 0]);
        $factory =  PPFinishedGood::where('id', $PPFinishedGood_id)->update(['Approve_status' => null]);

        $approve = new PPFinishedGood_Approve;
        $approve->role = 'AUTO';
        $approve->PPFinishedGood_id = $PPFinishedGood_id;
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

        $approvesss = PPFinishedGood_Approve::where('PPFinishedGood_id', $id)->where('action', 'HOLD')->update(['days_for_holding' => $currentDate, 'status' => 0]);
        $factory =  PPFinishedGood::where('id', $id)->update(['Approve_status' => null]);

        $approve = new PPFinishedGood_Approve;
        $approve->userID = auth()->user()->id;
        if (auth()->user()->role == 0) {
            $approve->role = 'Admin';
        } elseif (isset($EXT[7]['approver'])) {
            $approve->role = 'Approver';
        } elseif (isset($EXT[7]['inputer'])) {
            $approve->role = 'Inputer';
        } else {
            $approve->role = 'Viewer';
        }
        $approve->PPFinishedGood_id = $id;
        $approve->status = 1;
        $approve->action = 'Hold Released';
        $approve->comment_text = $request->comment_text;
        $approve->ip_address = $request->getClientIp();
        $approve->device_name = $request->server('HTTP_USER_AGENT');
        $approve->save();


        return redirect('PPFinishedGood/PPFinishedGoodList')->with('success', 'Hold Released successfully.....');
    }
}
