<?php

namespace App\Http\Controllers\ProductCategories;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategories\{ProductCategories_Add_Product, ProductCategories_Add_Product_Other, ProductCategories_Approve};
use App\Models\FactoryCreater\{Factory_Product, Factory_Sub_Product, Factory_Sub_Sub_Product};
use App\Models\Master\Plant\{Master_BU, Master_Manufacturing_unit, Master_category};
use App\Models\FactoryCreater\{Factory_Organisation, Factory_Uom};
use App\Models\Master\Master_Plant_Machinery;
use App\Models\{Admin, Forwarded_Data, Department_Assign};
use App\Models\MaterialManagement\{MaterialManagement_Add_Material};
use Session;

class ProductCategoriesApproveController extends Controller
{
    public function ProductApprove(Request $request)
    {
        $EXT = Session::get('EXT');

        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));

        $query = new ProductCategories_Add_Product;
        if ($fromdate && $todate) {
            $query = $query->whereBetween('created_at', [$fromdate, $todate]);
        }

        if (isset($EXT[5]['Forward']) && isset($EXT[5]['approver'])) {
            $query = $query->where(function ($query) use ($EXT) {
                $query->where('Approve_status', null)->where('Forward_Status', 0)->whereRaw("Approve_Step IN (" . implode(",", $EXT[5]['approver']) . ")");
            })
                ->orWhere(function ($query) {
                    $query->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1');
                })
                ->orWhereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1')
                ->orderBy('id', 'DESC');
        } elseif (isset($EXT[5]['Forward'])) {
            $query = $query->where('Forward_Status', 1)->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD")')->orderBy('id', 'DESC');
        } elseif (isset($EXT[5]['approver'])) {
            $query = $query->where('Approve_status', null)->where(['Forward_Status' => 0, 'status' => 0])->WhereRaw("Approve_Step IN (" . implode(",", $EXT[5]['approver']) . ")")->orderBy('id', 'DESC');
        }

        $ProductList = $query->get();

        $ProductList_arr = [];

        foreach ($ProductList as $val) {
            if ($val->Forward_Status != 1) {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="5" AND step="' . $val->Approve_Step . '")')->get();
            } else {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data` WHERE DataID="' . $val->id . '" AND DepartmentID=5 AND status=0)')->get();
            }
            $val->user = Admin::find($val->userID);
            $val->productOther = ProductCategories_Add_Product_Other::where('Add_Product_ID', $val->id)->get();
            $val->product = Factory_Product::find($val->Product);
            $val->subproduct = Factory_Sub_Product::find($val->Sub_Product);
            $val->subsubproduct = Factory_Sub_Sub_Product::find($val->Sub_Sub_Product);
            $val->Organization_Name = Factory_Organisation::find($val->Organization_Name);
            $val->Manufacturing_Unit = Master_Manufacturing_unit::find($val->Manufacturing_Unit);
            $val->BU = Master_BU::find($val->BU);
            $val->Plant_Name = Master_Plant_Machinery::find($val->Plant_Name);
            $val->category = Master_category::find($val->Category);
            //$val->Raw_Material = MaterialManagement_Add_Material::find($val->Raw_Material);
            $val->Raw_Material = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                                    ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                                    ->where('materialmanagement_add_material.id',$val->Raw_Material)->first();
            $val->UOM = Factory_Uom::find($val->UOM);

            $ProductList_arr[] = $val;
        }

        return view('ProductCategories/ProductCategoryApproveList', ['ProductList' => $ProductList_arr, 'fromdate' => $fromdate, 'todate' => $dateto]);
    }

    public function view_approve($id, $type)
    {
        $appro = ProductCategories_Approve::where('Product_id', $id)->get();
        $approves = [];
        foreach ($appro as $val) {
            $val->user = Admin::find($val->userID);
            array_push($approves, $val);
        }

        $product = Factory_Product::all();
        $subproduct = Factory_Sub_Product::all();
        $subsubproduct = Factory_Sub_Sub_Product::all();
        $Organization_Name = Factory_Organisation::all();
        $Manufacturing_Unit = Master_Manufacturing_unit::all();
        $BU = Master_BU::all();
        $Plant_Name = Master_Plant_Machinery::all();
        $category = Master_category::all();
        $employeeName = Admin::where('role', 1)->whereRaw('id IN (SELECT userID FROM employee_department where Departments="5")')->get();
        //$Raw_Material = MaterialManagement_Add_Material::where('Approve_status', 'APPROVE')->get();
        $Raw_Material = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                     ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                     ->where('Approve_status', 'APPROVE')->get();
        $UOM = Factory_Uom::all();
        $edit = ProductCategories_Add_Product::find($id);
        $editother = array();
        $otherCount = 0;
        if (isset($edit->id) && $edit->id != '') {
            $editother = ProductCategories_Add_Product_Other::where('Add_Product_ID', $edit->id)->get();
            $otherCount += $editother->count();
        }

        $nextID = $this->next($id, $type);

        return view('ProductCategories/ProductCategoryApproveView', ['edit' => $edit, 'editother' => $editother, 'otherCount' => $otherCount, 'product' => $product, 'subproduct' => $subproduct, 'subsubproduct' => $subsubproduct, 'Organization_Name' => $Organization_Name, 'Manufacturing_Unit' => $Manufacturing_Unit, 'BU' => $BU, 'Plant_Name' => $Plant_Name, 'category' => $category, 'approves' => $approves, 'nextID' => $nextID, 'employeeName' => $employeeName, 'Raw_Material' => $Raw_Material, 'UOM' => $UOM]);
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
        //return $request->all();
        $EXT = Session::get('EXT');

        if (!empty($request->during_approval)) {
            ProductCategories_Add_Product::where('id', $request->approveID)->update(['Approve_status' => $request->during_approval]);
            ProductCategories_Approve::where('Product_id', $request->approveID)->where('status', 1)->update(['status' => 0]);
        }

        $check = ProductCategories_Add_Product::find($request->approveID);
        if ($request->during_approval === 'APPROVE') {
            $status = Forwarded_Data::where('DataID', $request->approveID)->update(['status' => 1]);
            ProductCategories_Add_Product::where('id', $request->approveID)->update(['Forward_Status' => 0]);

            $DepartStepcount2 = Department_Assign::where(['departments' => 5, 'step' => 2])->count();
            $DepartStepcount3 = Department_Assign::where(['departments' => 5, 'step' => 3])->count();

            if ($check->Approve_Step == 1 && $DepartStepcount2 > 0) {
                ProductCategories_Add_Product::where('id', $request->approveID)->update(['Approve_Step' => 2, 'Approve_status' => null]);
            }

            if ($check->Approve_Step == 2 &&  $DepartStepcount3 > 0) {
                ProductCategories_Add_Product::where('id', $request->approveID)->update(['Approve_Step' => 3, 'Approve_status' => null]);
            }
        }

        if ($request->during_approval === 'REJECT') {
            MaterialManagement_Add_Material::where('id', $check->Raw_Material)->update(['Used_Status' => 0]);
        }

        if ($request->during_approval === 'FORWARD') {
            Forwarded_Data::where(['DepartmentID' => 5, 'DataID' => $request->approveID])->update(['status' => 1]);
            ProductCategories_Add_Product::where('id', $request->approveID)->update(['Forward_Status' => 1]);

            $forward = new Forwarded_Data;
            $forward->userID = auth()->user()->id;
            $forward->Forward_To_id = $request->Forward_To;
            $forward->DepartmentID = 5;
            $forward->DataID = $request->approveID;
            $forward->status = 0;

            $forward->save();
        }

        $approve = new ProductCategories_Approve;
        $approve->userID = auth()->user()->id;
        if (auth()->user()->role == 0) {
            $approve->role = 'Admin';
        } elseif (isset($EXT[5]['Inputer'])) {
            $approve->role = 'Inputer';
        } elseif (isset($EXT[5]['approver'])) {
            $approve->role = 'Approver';
        } else {
            $approve->role = 'Viewer';
        }
        $approve->Product_id = $request->approveID;
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
            ProductCategories_Add_Product::where('id', $request->approveID)->update(['Approve_status' => null]);
            return redirect('ProductCategories/ProductList')->with('success', 'successfully.....');
        } elseif (($request->pre_post_approval == 'AUDIT' || $request->pre_post_approval == 'INTIMATION' || $request->pre_post_approval == 'QUERY') && $request->non_acting == 1) {
            return redirect('ProductCategories/ProductList')->with('success', 'successfull.....');
        } else {
            return redirect('ProductCategories/ProductApproveList')->with('success', 'Approved successfully.....');
        }
    }


    public function CheckHoldExpiry()
    {
        $Approve = ProductCategories_Approve::all();

        return response()->json($Approve);
    }

    public function UpdateStatus(Request $request)
    {
        $Product_id = $request->input('Product_id');
        $userID = $request->input('userID');

        $approves = ProductCategories_Approve::where('Product_id', $Product_id)->where('userID', $userID)->update(['status' => 0]);
        $factory =  ProductCategories_Add_Product::where('id', $Product_id)->update(['Approve_status' => null]);

        $approve = new ProductCategories_Approve;
        $approve->role = 'AUTO';
        $approve->Product_id = $Product_id;
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

        $approvesss = ProductCategories_Approve::where('Product_id', $id)->where('action', 'HOLD')->update(['days_for_holding' => $currentDate, 'status' => 0]);
        $factory =  ProductCategories_Add_Product::where('id', $id)->update(['Approve_status' => null]);

        $approve = new ProductCategories_Approve;
        $approve->userID = auth()->user()->id;
        if (auth()->user()->role == 0) {
            $approve->role = 'Admin';
        } elseif (isset($EXT[5]['approver'])) {
            $approve->role = 'Approver';
        } elseif (isset($EXT[5]['inputer'])) {
            $approve->role = 'Inputer';
        } else {
            $approve->role = 'Viewer';
        }
        $approve->Product_id = $id;
        $approve->status = 1;
        $approve->action = 'Hold Released';
        $approve->comment_text = $request->comment_text;
        $approve->ip_address = $request->getClientIp();
        $approve->device_name = $request->server('HTTP_USER_AGENT');
        $approve->save();


        return redirect('ProductCategories/ProductList')->with('success', 'Hold Released successfully.....');
    }
}
