<?php

namespace App\Http\Controllers\orderRequirement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\orderRequirement\{Order_Requirement_Sales, Order_Requirement_Stock, Order_Requirement_Product_Details, Order_Requirement_Stock_Approve, Order_Requirement_Sales_Approve};
use App\Models\FactoryCreater\{Factory_Organisation, Factory_Uom,prj_organisation,Factory_Address_Detail};
use App\Models\Master\Plant\{Master_Company_Name, Master_Customer_Name, Master_Manufacturing_unit, Master_BU};
use App\Models\Master\RawMaterial\{Master_Godown_Name};
use App\Models\Master\{Master_Plant_Machinery,Prj_Subproject,Prj_Project,Module_Bsns_Unit,Prj_Inventory,Pur_Address};
use App\Models\{Admin, City, Country, State, Department_Assign, Forwarded_Data};
use App\Models\MaterialManagement\{MaterialManagement_Add_Material};
use App\Models\BOM\{BOM, BOM_Material};
use Session;

class orderRequirementApproveController extends Controller
{
    public function orderRequirementApproveList(Request $request)
    {
        $EXT = Session::get('EXT');

        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));

        $query = new Order_Requirement_Sales;
        if ($fromdate && $todate) {
            $query = $query->whereBetween('created_at', [$fromdate, $todate]);
        }

        if (isset($EXT[18]['Forward']) && isset($EXT[18]['approver'])) {
            $query = $query->where(function ($query) use ($EXT) {
                $query->where('Approve_status', null)->where('Forward_Status', 0)->whereRaw("Approve_Step IN (" . implode(",", $EXT[18]['approver']) . ")");
            })
                ->orWhere(function ($query) {
                    $query->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1');
                })
                ->orWhereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1')
                ->orderBy('id', 'DESC');
        } elseif (isset($EXT[18]['Forward'])) {
            $query = $query->where('Forward_Status', 1)->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD")')->orderBy('id', 'DESC');
        } elseif (isset($EXT[18]['approver'])) {
            $query = $query->where('Approve_status', null)->where(['Forward_Status' => 0, 'status' => 0])->WhereRaw("Approve_Step IN (" . implode(",", $EXT[18]['approver']) . ")")->orderBy('id', 'DESC');
        }

        $Sales = $query->get();

        $userSales = [];
        foreach ($Sales as $sale) {
            if ($sale->Forward_Status != 1) {
                $sale->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="18" AND step="' . $sale->Approve_Step . '")')->get();
            } else {
                $sale->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data` WHERE DataID="' . $sale->id . '" AND DepartmentID=18 AND status=0)')->get();
            }
            $sale->user = Admin::find($sale->userID);

            $userSales[] = $sale;
        }

        $query = new Order_Requirement_Stock;
        if ($fromdate && $todate) {
            $query = $query->whereBetween('created_at', [$fromdate, $todate]);
        }

        if (isset($EXT[18]['Forward']) && isset($EXT[18]['approver'])) {
            $query = $query->where(function ($query) use ($EXT) {
                $query->where('Approve_status', null)->where('Forward_Status', 0)->whereRaw("Approve_Step IN (" . implode(",", $EXT[18]['approver']) . ")");
            })
                ->orWhere(function ($query) {
                    $query->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1');
                })
                ->orWhereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1')
                ->orderBy('id', 'DESC');
        } elseif (isset($EXT[18]['Forward'])) {
            $query = $query->where('Forward_Status', 1)->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD")')->orderBy('id', 'DESC');
        } elseif (isset($EXT[18]['approver'])) {
            $query = $query->where('Approve_status', null)->where(['Forward_Status' => 0, 'status' => 0])->WhereRaw("Approve_Step IN (" . implode(",", $EXT[18]['approver']) . ")")->orderBy('id', 'DESC');
        }

        $Stock = $query->get();

        $userStock = [];
        foreach ($Stock as $stockItem) {
            if ($stockItem->Forward_Status != 1) {
                $stockItem->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="18" AND step="' . $stockItem->Approve_Step . '")')->get();
            } else {
                $stockItem->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data` WHERE DataID="' . $stockItem->id . '" AND DepartmentID=18 AND status=0)')->get();
            }
            $stockItem->user = Admin::find($stockItem->userID);
            $stockItem->RawMaterial = MaterialManagement_Add_Material::select('prj_material.material_name as matname')
            ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
            ->where('materialmanagement_add_material.id',$stockItem->Raw_Material)
            ->first();
            $stockItem->organisation =prj_organisation::find($stockItem->Organization);
            $stockItem->buname =Module_Bsns_Unit::find($stockItem->BU_Name);
            $stockItem->projectname =Prj_Project::find($stockItem->Unit_Name);
            $stockItem->plantname =Prj_Subproject::find($stockItem->Plant_Name);
            $stockItem->inventoryname =Prj_Inventory::find($stockItem->Factory_Godown_Name);
            $userStock[] = $stockItem;
        }
        $Organisation=Factory_Organisation::all_org();
        $BU = Master_BU::all_bu();
        $MU = Master_Manufacturing_unit::all_mu();
        $plant_name = Master_Plant_Machinery::all_pm();

        $Customer_Name = Master_Customer_Name::all_cn();
        $Company_Name = Master_Company_Name::all_con();
        return view('orderRequriement/orderRequriementApproveList', ['Sales' => $userSales, 'Stock' => $userStock, 'fromdate' => $fromdate, 'todate' => $dateto,'Organisation'=>$Organisation,'BU'=>$BU,'MU'=>$MU,'plant_name'=>$plant_name,'Customer_Name'=>$Customer_Name,'Company_Name'=>$Company_Name]);
    }
    public function orderRequirementApproveStockList(Request $request)
    {
        $EXT = Session::get('EXT');

        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));

        $query = new Order_Requirement_Sales;
        if ($fromdate && $todate) {
            $query = $query->whereBetween('created_at', [$fromdate, $todate]);
        }

        if (isset($EXT[18]['Forward']) && isset($EXT[18]['approver'])) {
            $query = $query->where(function ($query) use ($EXT) {
                $query->where('Approve_status', null)->where('Forward_Status', 0)->whereRaw("Approve_Step IN (" . implode(",", $EXT[18]['approver']) . ")");
            })
                ->orWhere(function ($query) {
                    $query->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1');
                })
                ->orWhereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1')
                ->orderBy('id', 'DESC');
        } elseif (isset($EXT[18]['Forward'])) {
            $query = $query->where('Forward_Status', 1)->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD")')->orderBy('id', 'DESC');
        } elseif (isset($EXT[18]['approver'])) {
            $query = $query->where('Approve_status', null)->where(['Forward_Status' => 0, 'status' => 0])->WhereRaw("Approve_Step IN (" . implode(",", $EXT[18]['approver']) . ")")->orderBy('id', 'DESC');
        }

        $Sales = $query->get();

        $userSales = [];
        foreach ($Sales as $sale) {
            if ($sale->Forward_Status != 1) {
                $sale->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="18" AND step="' . $sale->Approve_Step . '")')->get();
            } else {
                $sale->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data` WHERE DataID="' . $sale->id . '" AND DepartmentID=18 AND status=0)')->get();
            }
            $sale->user = Admin::find($sale->userID);

            $userSales[] = $sale;
        }

        $query = new Order_Requirement_Stock;
        if ($fromdate && $todate) {
            $query = $query->whereBetween('created_at', [$fromdate, $todate]);
        }

        if (isset($EXT[18]['Forward']) && isset($EXT[18]['approver'])) {
            $query = $query->where(function ($query) use ($EXT) {
                $query->where('Approve_status', null)->where('Forward_Status', 0)->whereRaw("Approve_Step IN (" . implode(",", $EXT[18]['approver']) . ")");
            })
                ->orWhere(function ($query) {
                    $query->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1');
                })
                ->orWhereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1')
                ->orderBy('id', 'DESC');
        } elseif (isset($EXT[18]['Forward'])) {
            $query = $query->where('Forward_Status', 1)->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD")')->orderBy('id', 'DESC');
        } elseif (isset($EXT[18]['approver'])) {
            $query = $query->where('Approve_status', null)->where(['Forward_Status' => 0, 'status' => 0])->WhereRaw("Approve_Step IN (" . implode(",", $EXT[18]['approver']) . ")")->orderBy('id', 'DESC');
        }

        $Stock = $query->get();

        $userStock = [];
        foreach ($Stock as $stockItem) {
            if ($stockItem->Forward_Status != 1) {
                $stockItem->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="18" AND step="' . $stockItem->Approve_Step . '")')->get();
            } else {
                $stockItem->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data` WHERE DataID="' . $stockItem->id . '" AND DepartmentID=18 AND status=0)')->get();
            }
            //$rawss=MaterialManagement_Add_Material::find($stockItem->Raw_Material);
            $stockItem->RawMaterial = MaterialManagement_Add_Material::select('prj_material.material_name as matname')
            ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
            ->where('materialmanagement_add_material.id',$stockItem->Raw_Material)
            ->first();
            //$stockItem->rawmaterial=$rawss->Material_Name??'';
            $stockItem->user = Admin::find($stockItem->userID);

            $userStock[] = $stockItem;
        }
        $Organisation=prj_organisation::all_org();
        $BU = Module_Bsns_Unit::all_bu();
        $MU = Prj_Project::all_mu();
        $plant_name = Prj_Subproject::all_pm();
        $Customer_Name = Master_Customer_Name::all_cn();
        $Company_Name = Master_Company_Name::all_con();
        $all_godownname=Prj_Inventory::all_godownname();
        return view('orderRequriement/orderRequriementApproveStockList', ['Sales' => $userSales, 'Stock' => $userStock, 'fromdate' => $fromdate, 'todate' => $dateto,'Organisation'=>$Organisation,'BU'=>$BU,'MU'=>$MU,'plant_name'=>$plant_name,'Customer_Name'=>$Customer_Name,'Company_Name'=>$Company_Name,'all_godownname'=>$all_godownname]);
    }

    public function Sales_view_approve($id, $type)
    {
        $appro = Order_Requirement_Sales_Approve::where('Order_Requirement_Sales_id', $id)->get();
        $approves = [];
        foreach ($appro as $val) {
            $val->user = Admin::find($val->userID);
            array_push($approves, $val);
        }

        $country = Country::all();
        $state = State::all();
        $city = City::all();
        $BU = Master_BU::all();
        $Organization = Factory_Organisation::all();
        $Manufacturing_unit = Master_Manufacturing_unit::all();
        $plant_name = Master_Plant_Machinery::all();
        $Customer_Name = Master_Customer_Name::all();
        $Company_Name = Master_Company_Name::all();
        $Godown_Name = Master_Godown_Name::all();
        $UOM = Factory_Uom::all();
        $employeeName = Admin::where('role', 1)->whereRaw('id IN (SELECT userID FROM employee_department where Departments="18")')->get();
        $BOM_DATA = BOM::where('Approve_status', 'APPROVE')->get();
        $Raw_Material = [];
        foreach ($BOM_DATA as $Val) {
            if (isset($Val->Raw_Material_FG)) {
                $Val->RawMaterial = MaterialManagement_Add_Material::find($Val->Raw_Material_FG);
                $Raw_Material[$Val->Raw_Material_FG] = $Val;
            }
        }
        $Filtered_Array = array_values($Raw_Material);

        $editSales = Order_Requirement_Sales::find($id);

        $nextID = $this->next($id, $type);

        return view('orderRequriement/Sales_View_Approve', ['editSales' => $editSales, 'approves' => $approves, 'nextID' => $nextID, 'Raw_Material' => $Raw_Material, 'UOM' => $UOM, 'BU' => $BU, 'Organization' => $Organization, 'Manufacturing_unit' => $Manufacturing_unit, 'plant_name' => $plant_name, 'Customer_Name' => $Customer_Name, 'Company_Name' => $Company_Name, 'Godown_Name' => $Godown_Name, 'Raw_Material' => $Filtered_Array, 'city' => $city, 'country' => $country, 'state' => $state, 'employeeName' => $employeeName]);
    }

    public function Stock_view_approve($id, $type)
    {
        $approStock = Order_Requirement_Stock_Approve::where('Order_Requirement_Stock_id', $id)->get();
        $approvesStock = [];
        foreach ($approStock as $val) {
            $val->user = Admin::find($val->userID);
            array_push($approvesStock, $val);
        }

        $country = Country::all();
        $state = State::all();
        $city = City::all();
        $BU = Module_Bsns_Unit::all();
        $Organization = prj_organisation::all();
        $Manufacturing_unit =Factory_Address_Detail::select('prj_project.*')
        ->leftJoin('prj_project','factory_address_details.name_of_unit','=','prj_project.id')
        ->where('Approve_status','APPROVE')
        ->groupBy('prj_project.pname')
        ->get();
        $plant_name = Prj_Subproject::all();
        $Customer_Name = Master_Customer_Name::all();
        $Company_Name = Master_Company_Name::all();
        $Godown_Name = Prj_Inventory::all();
        $UOM = Factory_Uom::all();
        $employeeName = Admin::where('role', 1)->whereRaw('id IN (SELECT userID FROM employee_department where Departments="18")')->get();
        $BOM_DATA = BOM::where('Approve_status', 'APPROVE')->get();
        $Raw_Material = [];
        foreach ($BOM_DATA as $Val) {
            if (isset($Val->Raw_Material_FG)) {
                //$Val->RawMaterial = MaterialManagement_Add_Material::find($Val->Raw_Material_FG);
                $Val->RawMaterial =MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                ->where('materialmanagement_add_material.id',$Val->Raw_Material_FG)
                ->first();
                $Raw_Material[$Val->Raw_Material_FG] = $Val;
            }
        }
        $Filtered_Array = array_values($Raw_Material);

        $editStock = Order_Requirement_Stock::select('order_requirement_stock.*','mstr_emp.fullname as contprsn')
                    ->leftJoin('mstr_emp','order_requirement_stock.contact_psrn','=','mstr_emp.id')
                    ->where('order_requirement_stock.id',$id)
                    ->first();
        $billing_address=Order_Requirement_Stock::select('prj_state.sname as billing_address')
                        ->leftJoin('pur_address','order_requirement_stock.billing_address','=','pur_address.id')
                        ->leftJoin('prj_state','pur_address.sid','=','prj_state.id')
                        ->where('order_requirement_stock.id',$id)
                        ->first();
        $shipping_address=Order_Requirement_Stock::select('prj_state.sname as shipping_address')
                        ->leftJoin('pur_address','order_requirement_stock.shipping_address','=','pur_address.id')
                        ->leftJoin('prj_state','pur_address.sid','=','prj_state.id')
                        ->where('order_requirement_stock.id',$id)
                        ->first();
        $product = array();
        if (isset($editStock) && $editStock != '') {
            $product = Order_Requirement_Product_Details::where('Stock_id', $editStock->id)->get();
            foreach ($product as $Val) {
                $Val->material_name = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                ->where('materialmanagement_add_material.id',$Val->Material_Name)
                ->first();
            }
        }

        $nextID = $this->next($id, $type);

        return view('orderRequriement/Stock_View_Approve', ['editStock' => $editStock,'billing_address'=>$billing_address,'shipping_address'=>$shipping_address, 'product' => $product, 'approves' => $approvesStock, 'nextID' => $nextID, 'Raw_Material' => $Raw_Material, 'UOM' => $UOM, 'Organization' => $Organization, 'Manufacturing_unit' => $Manufacturing_unit, 'BU' => $BU, 'plant_name' => $plant_name, 'Customer_Name' => $Customer_Name, 'Company_Name' => $Company_Name, 'Godown_Name' => $Godown_Name, 'Raw_Material' => $Filtered_Array, 'city' => $city, 'country' => $country, 'state' => $state, 'employeeName' => $employeeName]);
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

    public function Sales_approve(Request $request)
    {
        $EXT = Session::get('EXT');

        if (!empty($request->during_approval)) {
            Order_Requirement_Sales::where('id', $request->approveID)->update(['Approve_status' => $request->during_approval]);
            Order_Requirement_Sales_Approve::where('Order_Requirement_Sales_id', $request->approveID)->where('status', 1)->update(['status' => 0]);
        }

        $check = Order_Requirement_Sales::find($request->approveID);
        if ($request->during_approval === 'APPROVE') {
            $status = Forwarded_Data::where('DataID', $request->approveID)->update(['status' => 1]);
            Order_Requirement_Sales::where('id', $request->approveID)->update(['Forward_Status' => 0]);

            $DepartStepcount2 = Department_Assign::where(['departments' => 18, 'step' => 2])->count();
            $DepartStepcount3 = Department_Assign::where(['departments' => 18, 'step' => 3])->count();

            if ($check->Approve_Step == 1 && $DepartStepcount2 > 0) {
                Order_Requirement_Sales::where('id', $request->approveID)->update(['Approve_Step' => 2, 'Approve_status' => null]);
            }

            if ($check->Approve_Step == 2 &&  $DepartStepcount3 > 0) {
                Order_Requirement_Sales::where('id', $request->approveID)->update(['Approve_Step' => 3, 'Approve_status' => null]);
            }
        }

        if ($request->during_approval === 'REJECT') {
            MaterialManagement_Add_Material::where('id', $check->Raw_Material)->update(['Used_Status' => 0]);
        }

        if ($request->during_approval === 'FORWARD') {
            Forwarded_Data::where(['DepartmentID' => 18, 'DataID' => $request->approveID])->update(['status' => 1]);
            Order_Requirement_Sales::where('id', $request->approveID)->update(['Forward_Status' => 1]);

            $forward = new Forwarded_Data;
            $forward->userID = auth()->user()->id;
            $forward->Forward_To_id = $request->Forward_To;
            $forward->DepartmentID = 18;
            $forward->DataID = $request->approveID;
            $forward->status = 0;

            $forward->save();
        }

        $approve = new Order_Requirement_Sales_Approve;
        $approve->userID = auth()->user()->id;
        if (auth()->user()->role == 0) {
            $approve->role = 'Admin';
        } elseif (isset($EXT[18]['Inputer'])) {
            $approve->role = 'Inputer';
        } elseif (isset($EXT[18]['approver'])) {
            $approve->role = 'Approver';
        } else {
            $approve->role = 'Viewer';
        }
        $approve->Order_Requirement_Sales_id = $request->approveID;
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
            Order_Requirement_Sales::where('id', $request->approveID)->update(['Approve_status' => null]);
            return redirect('orderRequirement/orderRequirementList')->with('success', 'successfully.....');
        } elseif (($request->pre_post_approval == 'AUDIT' || $request->pre_post_approval == 'INTIMATION' || $request->pre_post_approval == 'QUERY') && $request->non_acting == 1) {
            return redirect('orderRequirement/orderRequirementList')->with('success', 'successfull.....');
        } else {
            return redirect('orderRequirement/orderRequirementApproveList')->with('success', 'Approved successfully.....');
        }
    }

    public function Stock_approve(Request $request)
    {
        $EXT = Session::get('EXT');

        if (!empty($request->during_approval)) {
            Order_Requirement_Stock::where('id', $request->approveID)->update(['Approve_status' => $request->during_approval]);
            Order_Requirement_Stock_Approve::where('Order_Requirement_Stock_id', $request->approveID)->where('status', 1)->update(['status' => 0]);
        }

        $check = Order_Requirement_Stock::find($request->approveID);
        if ($request->during_approval === 'APPROVE') {
            $status = Forwarded_Data::where('DataID', $request->approveID)->update(['status' => 1]);
            Order_Requirement_Stock::where('id', $request->approveID)->update(['Forward_Status' => 0]);

            $DepartStepcount2 = Department_Assign::where(['departments' => 18, 'step' => 2])->count();
            $DepartStepcount3 = Department_Assign::where(['departments' => 18, 'step' => 3])->count();

            if ($check->Approve_Step == 1 && $DepartStepcount2 > 0) {
                Order_Requirement_Stock::where('id', $request->approveID)->update(['Approve_Step' => 2, 'Approve_status' => null]);
            }

            if ($check->Approve_Step == 2 &&  $DepartStepcount3 > 0) {
                Order_Requirement_Stock::where('id', $request->approveID)->update(['Approve_Step' => 3, 'Approve_status' => null]);
            }
        }

        if ($request->during_approval === 'REJECT') {
            MaterialManagement_Add_Material::where('id', $check->Raw_Material)->update(['Used_Status' => 0]);
        }

        if ($request->during_approval === 'FORWARD') {
            Forwarded_Data::where(['DepartmentID' => 18, 'DataID' => $request->approveID])->update(['status' => 1]);
            Order_Requirement_Stock::where('id', $request->approveID)->update(['Forward_Status' => 1]);

            $forward = new Forwarded_Data;
            $forward->userID = auth()->user()->id;
            $forward->Forward_To_id = $request->Forward_To;
            $forward->DepartmentID = 18;
            $forward->DataID = $request->approveID;
            $forward->status = 0;

            $forward->save();
        }

        $approve = new Order_Requirement_Stock_Approve;
        $approve->userID = auth()->user()->id;
        if (auth()->user()->role == 0) {
            $approve->role = 'Admin';
        } elseif (isset($EXT[18]['Inputer'])) {
            $approve->role = 'Inputer';
        } elseif (isset($EXT[18]['approver'])) {
            $approve->role = 'Approver';
        } else {
            $approve->role = 'Viewer';
        }
        $approve->Order_Requirement_Stock_id = $request->approveID;
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
            Order_Requirement_Stock::where('id', $request->approveID)->update(['Approve_status' => null]);
            return redirect('orderRequirement/orderRequirementList')->with('success', 'successfully.....');
        } elseif (($request->pre_post_approval == 'AUDIT' || $request->pre_post_approval == 'INTIMATION' || $request->pre_post_approval == 'QUERY') && $request->non_acting == 1) {
            return redirect('orderRequirement/orderRequirementList')->with('success', 'successfull.....');
        } else {
            return redirect('orderRequirement/orderRequirementApproveList')->with('success', 'Approved successfully.....');
        }
    }

    public function CheckHoldExpiry()
    {
        $Approve = orderRequriement_Approve::all();

        return response()->json($Approve);
    }

    public function UpdateStatus(Request $request)
    {
        $Product_id = $request->input('Product_id');
        $userID = $request->input('userID');

        $approves = orderRequriement_Approve::where('Product_id', $Product_id)->where('userID', $userID)->update(['status' => 0]);
        $factory =  Order_Requirement_Stock::where('id', $Product_id)->update(['Approve_status' => null]);

        $approve = new orderRequriement_Approve;
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


    public function Sales_Release_Hold(Request $request, $id)
    {
        $EXT = Session::get('EXT');
        $currentDate = now();

        $approvesss = Order_Requirement_Sales_Approve::where('Order_Requirement_Sales_id', $id)->where('action', 'HOLD')->update(['days_for_holding' => $currentDate, 'status' => 0]);
        $factory =  Order_Requirement_Sales::where('id', $id)->update(['Approve_status' => null]);

        $approve = new Order_Requirement_Sales_Approve;
        $approve->userID = auth()->user()->id;
        if (auth()->user()->role == 0) {
            $approve->role = 'Admin';
        } elseif (isset($EXT[18]['approver'])) {
            $approve->role = 'Approver';
        } elseif (isset($EXT[18]['inputer'])) {
            $approve->role = 'Inputer';
        } else {
            $approve->role = 'Viewer';
        }
        $approve->Order_Requirement_Sales_id = $id;
        $approve->status = 1;
        $approve->action = 'Hold Released';
        $approve->comment_text = $request->comment_text;
        $approve->ip_address = $request->getClientIp();
        $approve->device_name = $request->server('HTTP_USER_AGENT');
        $approve->save();


        return redirect('orderRequirement/orderRequirementList')->with('success', 'Hold Released successfully.....');
    }

    public function Stock_Release_Hold(Request $request, $id)
    {
        $EXT = Session::get('EXT');
        $currentDate = now();

        $approvesss = Order_Requirement_Stock_Approve::where('Order_Requirement_Stock_id', $id)->where('action', 'HOLD')->update(['days_for_holding' => $currentDate, 'status' => 0]);
        $factory =  Order_Requirement_Stock::where('id', $id)->update(['Approve_status' => null]);

        $approve = new Order_Requirement_Stock_Approve;
        $approve->userID = auth()->user()->id;
        if (auth()->user()->role == 0) {
            $approve->role = 'Admin';
        } elseif (isset($EXT[18]['approver'])) {
            $approve->role = 'Approver';
        } elseif (isset($EXT[18]['inputer'])) {
            $approve->role = 'Inputer';
        } else {
            $approve->role = 'Viewer';
        }
        $approve->Order_Requirement_Stock_id = $id;
        $approve->status = 1;
        $approve->action = 'Hold Released';
        $approve->comment_text = $request->comment_text;
        $approve->ip_address = $request->getClientIp();
        $approve->device_name = $request->server('HTTP_USER_AGENT');
        $approve->save();


        return redirect('orderRequirement/orderRequirementList')->with('success', 'Hold Released successfully.....');
    }
}
