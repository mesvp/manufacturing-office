<?php

namespace App\Http\Controllers\BOM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\BOM\{Master_Code, Master_Color, Master_Consumbles, Master_GST_Percentage, Master_Management_Expenses, Master_Material, Master_Services, Master_Machine_Specification};
use App\Models\BOM\{BOM, BOM_Consumbles, BOM_Expenses, BOM_Machine, BOM_Management, BOM_Manpower, BOM_Material, BOM_Services, BOM_Transport, BOM_Approve};
use App\Models\Master\Plant\{Master_category, Master_Manufacturing_unit,Master_Machine_Name};
use App\Models\FactoryCreater\{Factory_Organisation, Factory_Product, Factory_Sub_Product, Factory_Sub_Sub_Product, Factory_Uom};
use App\Models\Master\{Master_Plant_Machinery};
use App\Models\{Admin, Department_Assign, Forwarded_Data};
use App\Models\MaterialManagement\{MaterialManagement_Add_Material};
use App\Models\RawMaterial\{RawMaterial_stock, RawMaterial, RawMaterial_data};
use App\Models\ProductCategories\{ProductCategories_Add_Product};
use Session;


class BOMApproveController extends Controller
{
    public function BOM_Approve(Request $request)
    {
        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));

        $EXT = Session::get('EXT');

        $query = new BOM;

        if ($fromdate && $todate) {
            $query = $query->whereBetween('created_at', [$fromdate, $todate]);
        }

        if (isset($EXT[11]['Forward']) && isset($EXT[11]['approver'])) {
            $query = $query->where(function ($query) use ($EXT) {
                $query->where('Approve_status', null)->where('Forward_Status', 0)->whereRaw("Approve_Step IN (" . implode(",", $EXT[11]['approver']) . ")");
            })
                ->orWhere(function ($query) {
                    $query->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1');
                })
                ->orWhereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1')
                ->orderBy('id', 'DESC');
        } elseif (isset($EXT[11]['Forward'])) {
            $query = $query->where('Forward_Status', 1)->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD")')->orderBy('id', 'DESC');
        } elseif (isset($EXT[11]['approver'])) {
            $query = $query->where('Approve_status', null)->where(['Forward_Status' => 0, 'status' => 0])->WhereRaw("Approve_Step IN (" . implode(",", $EXT[11]['approver']) . ")")->orderBy('id', 'DESC');
        }

       $BOM = $query->get();

        $BOM_arr = [];
        foreach ($BOM as $val) {
            if ($val->Forward_Status != 1) {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="11" AND step="' . $val->Approve_Step . '")')->get();
            } else {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data` WHERE DataID="' . $val->id . '" AND DepartmentID=11 AND status=0)')->get();
            }
            $val->user = Admin::find($val->userID);
            $val->Organization = Factory_Organisation::find($val->Organization);
            $val->Manufacturing_Unit = Master_Manufacturing_unit::find($val->Manufacturing_Unit);
            $val->plant_name = Master_Plant_Machinery::find($val->Plant_Name);
            $val->Category = Master_category::find($val->Category);
            $val->Product = Factory_Product::find($val->Product);
            //$val->RawMaterial = MaterialManagement_Add_Material::find($val->Raw_Material_FG);
            $val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name')
            ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
            ->where('materialmanagement_add_material.id',$val->Raw_Material_FG)->first();;
            $val->UOM = Factory_Uom::find($val->UOMFG);

            $BOM_arr[] = $val;
        }

        return view('BOM/BOMApproveList', ['BOM_Data' => $BOM_arr, 'fromdate' => $fromdate, 'todate' => $dateto]);
    }

    public function view_approve($id, $type)
    {
        $appro = BOM_Approve::where('BOM_id', $id)->get();
        $approves = [];
        foreach ($appro as $val) {
            $val->user = Admin::find($val->userID);
            array_push($approves, $val);
        }

        $Organization = Factory_Organisation::all();
        $Manufacturing_unit = Master_Manufacturing_unit::all();
        $Plant_Name = Master_Plant_Machinery::all();
        $Category = Master_category::all();
        $Product = Factory_Product::all();
        $Sub_Product = Factory_Sub_Product::all();
        $Sub_Sub_Product = Factory_Sub_Sub_Product::all();
        $UOM = Factory_Uom::all();
        $Code = Master_Code::all();
        $Color = Master_Color::all();
        $Consumbles = Master_Consumbles::all();
        $GST_Percentage = Master_GST_Percentage::all();
        $Management_Expenses = Master_Management_Expenses::all();
        $materials = RawMaterial_stock::where('Approve_status', 'APPROVE')->get();
        $materialss = [];

        foreach ($materials as $material) {
            $material->rawMaterials = RawMaterial::where('RawMaterial_stock_id', $material->id)->get();

            foreach ($material->rawMaterials as $rawMaterial) {
                $rawMaterial->materials = RawMaterial_data::where('RawMaterial_id', $rawMaterial->id)->get();

                foreach ($rawMaterial->materials as $materialData) {
                    //$materialData->Name = MaterialManagement_Add_Material::find($materialData->Raw_Material);
                    $materialData->Name = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name')
                    ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                    ->where('materialmanagement_add_material.id',$materialData->Raw_Material)->first();;
                    if (isset($materialData->Name)) {
                        $materialss[] = [
                            'materialName' => $materialData->Name->material_name,
                            'materialID' => $materialData->Name->id,
                        ];
                    }
                }
            }
        }
        $Services = Master_Services::all();
        $Machine_Specification = Master_Machine_Name::all();
        $employeeName = Admin::where('role', 1)->whereRaw('id IN (SELECT userID FROM employee_department  where Departments="11")')->get();
        $product_data = ProductCategories_Add_Product::where('Approve_status', 'APPROVE')->get();
        $Raw_Material = [];
        foreach ($product_data as $Val) {
            if (isset($Val->Raw_Material)) {
                //$Val->RawMaterial = MaterialManagement_Add_Material::find($Val->Raw_Material);
                $Val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name')
                ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                ->where('materialmanagement_add_material.id',$Val->Raw_Material)->first();
                $Raw_Material[] = $Val;
            }
        }

        $BOM = BOM::find($id);
        $Material_data = [];
        $Material_Count = 0;
        $Consumbles_data = [];
        $Consumbles_Count = 0;
        $Expenses_data = [];
        $Expenses_Count = 0;
        $Machine_data = [];
        $Machine_Count = 0;
        $Management_data = [];
        $Management_Count = 0;
        $Manpower_data = [];
        $Manpower_Count = 0;
        $Services_data = [];
        $Services_Count = 0;
        $Transport_data = [];
        $Transport_Count = 0;

        if (isset($BOM->id)) {
            $Material_data = BOM_Material::where('BOM_id', $BOM->id)->get();
            $Material_Count += $Material_data->count();
            $Consumbles_data = BOM_Consumbles::where('BOM_id', $BOM->id)->get();
            $Consumbles_Count += $Consumbles_data->count();
            $Expenses_data = BOM_Expenses::where('BOM_id', $BOM->id)->get();
            $Expenses_Count += $Expenses_data->count();
            $Machine_data = BOM_Machine::where('BOM_id', $BOM->id)->get();
            $Machine_Count += $Machine_data->count();
            $Management_data = BOM_Management::where('BOM_id', $BOM->id)->get();
            $Management_Count += $Management_data->count();
            $Manpower_data = BOM_Manpower::select('bom_manpower.*','master_color.Color')
                            ->leftJoin('master_color','bom_manpower.Manpower_Skill','=','master_color.id')
                            ->where('bom_manpower.BOM_ID', $BOM->id)->get();
            $Manpower_Count += $Manpower_data->count();
            $Services_data = BOM_Services::where('BOM_id', $BOM->id)->get();
            $Services_Count += $Services_data->count();
            $Transport_data = BOM_Transport::where('BOM_id', $BOM->id)->get();
            $Transport_Count += $Transport_data->count();
        }

        $nextID = $this->next($id, $type);

        return view('BOM/BOMApprove', ['Organization' => $Organization, 'Manufacturing_unit' => $Manufacturing_unit, 'Plant_Name' => $Plant_Name, 'Category' => $Category, 'Product' => $Product, 'Sub_Product' => $Sub_Product, 'Sub_Sub_Product' => $Sub_Sub_Product, 'UOM' => $UOM, 'Code' => $Code, 'Color' => $Color, 'Consumbles' => $Consumbles, 'GST_Percentage' => $GST_Percentage, 'Management_Expenses' => $Management_Expenses, 'Material' => $materialss, 'Services' => $Services, 'Machine_Specification' => $Machine_Specification, 'BOM' => $BOM, 'Material_data' => $Material_data, 'Consumbles_data' => $Consumbles_data, 'Expenses_data' => $Expenses_data, 'Machine_data' => $Machine_data, 'Management_data' => $Management_data, 'Manpower_data' => $Manpower_data, 'Services_data' => $Services_data, 'Transport_data' => $Transport_data, 'Material_Count' => $Material_Count, 'Consumbles_Count' => $Consumbles_Count, 'Expenses_Count' => $Expenses_Count, 'Machine_Count' => $Machine_Count, 'Management_Count' => $Management_Count, 'Manpower_Count' => $Manpower_Count, 'Services_Count' => $Services_Count, 'Transport_Count' => $Transport_Count, 'approves' => $approves, 'nextID' => $nextID, 'employeeName' => $employeeName, 'Raw_Material' => $Raw_Material]);
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
            BOM::where('id', $request->approveID)->update(['Approve_status' => $request->during_approval]);
            BOM_Approve::where('BOM_id', $request->approveID)->where('status', 1)->update(['status' => 0]);
        }

        $check = BOM::find($request->approveID);
        if ($request->during_approval === 'APPROVE') {
            $status = Forwarded_Data::where('DataID', $request->approveID)->update(['status' => 1]);
            BOM::where('id', $request->approveID)->update(['Forward_Status' => 0]);

            $DepartStepcount2 = Department_Assign::where(['departments' => 11, 'step' => 2])->count();
            $DepartStepcount3 = Department_Assign::where(['departments' => 11, 'step' => 3])->count();

            if ($check->Approve_Step == 1 && $DepartStepcount2 > 0) {
                BOM::where('id', $request->approveID)->update(['Approve_Step' => 2, 'Approve_status' => null]);
            }

            if ($check->Approve_Step == 2 &&  $DepartStepcount3 > 0) {
                BOM::where('id', $request->approveID)->update(['Approve_Step' => 3, 'Approve_status' => null]);
            }
        }

        if ($request->during_approval === 'FORWARD') {
            Forwarded_Data::where(['DepartmentID' => 11, 'DataID' => $request->approveID])->update(['status' => 1]);
            BOM::where('id', $request->approveID)->update(['Forward_Status' => 1]);

            $forward = new Forwarded_Data;
            $forward->userID = auth()->user()->id;
            $forward->Forward_To_id = $request->Forward_To;
            $forward->DepartmentID = 11;
            $forward->DataID = $request->approveID;
            $forward->status = 0;

            $forward->save();
        }

        $approve = new BOM_Approve;
        $approve->userID = auth()->user()->id;
        if (auth()->user()->role == 0) {
            $approve->role = 'Admin';
        } elseif ($check->Approve_status == 'OBJECT') {
            $approve->role = 'Inputer';
        } elseif (isset($EXT[11]['approver'])) {
            $approve->role = 'Approver';
        } else {
            $approve->role = 'Viewer';
        }
        $approve->BOM_id = $request->approveID;
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
            BOM::where('id', $request->approveID)->update(['Approve_status' => null]);
            return redirect('BOM/BOMList')->with('success', 'successfully.....');
        } elseif (($request->pre_post_approval == 'AUDIT' || $request->pre_post_approval == 'INTIMATION' || $request->pre_post_approval == 'QUERY') && $request->non_acting == 1) {
            return redirect('BOM/BOMList')->with('success', 'successfull.....');
        } else {
            return redirect('BOM/BOMApproveList')->with('success', 'Approved successfully.....');
        }
    }

    public function CheckHoldExpiry()
    {
        $Approve = BOM_Approve::all();

        return response()->json($Approve);
    }

    public function UpdateStatus(Request $request)
    {
        $BOM_id = $request->input('BOM_id');
        $userID = $request->input('userID');

        $approves = BOM_Approve::where('BOM_id', $BOM_id)->where('userID', $userID)->update(['status' => 0]);
        $factory =  BOM::where('id', $BOM_id)->update(['Approve_status' => null]);

        $approve = new BOM_Approve;
        $approve->role = 'AUTO';
        $approve->BOM_id = $BOM_id;
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

        $approves = BOM_Approve::where('BOM_id', $id)->where('action', 'HOLD')->update(['days_for_holding' => $currentDate, 'status' => 0]);
        $factory =  BOM::where('id', $id)->update(['Approve_status' => null]);

        $approve = new BOM_Approve;
        $approve->userID = auth()->user()->id;
        if (auth()->user()->role == 0) {
            $approve->role = 'Admin';
        } elseif (isset($EXT[11]['approver'])) {
            $approve->role = 'Approver';
        } elseif (isset($EXT[11]['inputer'])) {
            $approve->role = 'Inputer';
        } else {
            $approve->role = 'Viewer';
        }
        $approve->BOM_id = $id;
        $approve->status = 1;
        $approve->action = 'Hold Released';
        $approve->comment_text = $request->comment_text;
        $approve->ip_address = $request->getClientIp();
        $approve->device_name = $request->server('HTTP_USER_AGENT');
        $approve->save();

        return redirect('BOM/BOMList')->with('success', 'Hold Released successfully.....');
    }
}
