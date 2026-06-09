<?php

namespace App\Http\Controllers\orderRequirement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\orderRequirement\{Order_Requirement_Sales, Order_Requirement_Stock, Order_Requirement_Product_Details, Order_Requirement_Stock_Approve, Order_Requirement_Sales_Approve};
use App\Models\FactoryCreater\{Factory_Organisation, Factory_Uom,prj_organisation,unitname,Factory_Address_Detail};
use App\Models\Master\Plant\{Master_Company_Name, Master_Customer_Name, Master_Manufacturing_unit, Master_BU};
use App\Models\Master\RawMaterial\{Master_Godown_Name};
use App\Models\Master\{Master_Plant_Machinery,Prj_Subproject,Prj_Project,Module_Bsns_Unit,Prj_Inventory,Pur_Address};
use App\Models\{Admin, City, Country, State, CheckBox};
use App\Models\MaterialManagement\{MaterialManagement_Add_Material};
use App\Models\BOM\{BOM, BOM_Material};
use Session;

class orderRequirementViewController extends Controller
{
    public function orderRequirementList(Request $request)
    {
        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));

        $EXT = Session::get('EXT');
        if (isset($EXT[18]['inputer'])) {
            $sales = Order_Requirement_Sales::orderBy('id', 'DESC');
        } else {
            $sales = Order_Requirement_Sales::where('status', 0)->orderBy('id', 'DESC');
        }
            
        // if ($fromdate && $todate) {
        //     $sales->whereBetween('created_at', [$fromdate, $todate]);
        // }
        if(isset($request->Organization) && $request->Organization!='')
        {
            $sales=$sales->where('Organization',$request->Organization);
        }
        if(isset($request->BU_Name) && $request->BU_Name!='')
        {
            $sales=$sales->where('BU_Name',$request->BU_Name);
        }
        if(isset($request->Unit_Name) && $request->Unit_Name!='')
        {
            $sales=$sales->where('Unit_Name',$request->Unit_Name);
        }
        if(isset($request->Plant_Name) && $request->Plant_Name!='')
        {
            $sales=$sales->where('Plant_Name',$request->Plant_Name);
        }
        if(isset($request->Order_Date) && $request->Order_Date!='')
        {
            $sales=$sales->where('Order_Date',$request->Order_Date);
        }
        if(isset($request->Sales_Order_No) && $request->Sales_Order_No!='')
        {
            $sales=$sales->where('Sales_Order_No',$request->Sales_Order_No);
        }
        if(isset($request->Customer_Name) && $request->Customer_Name!='')
        {
            $sales=$sales->where('Customer_Name',$request->Customer_Name);
        }
        if(isset($request->Company_Name) && $request->Company_Name!='')
        {
            $sales=$sales->where('Company_Name',$request->Company_Name);
        }
        
        

        $Sales = $sales->get();

        $userSales = [];
        $SalesApproved = [];
        $SalesREJECT = [];
        $SalesRECHECK = [];
        $SalesOBJECT = [];
        $SalesHOLD = [];
        $SalesPending = [];
        foreach ($Sales as $sale) {
            if ($sale->Forward_Status != 1) {
                $sale->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="18" AND step="' . $sale->Approve_Step . '")')->get();
            } else {
                $sale->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data` WHERE DataID="' . $sale->id . '" AND DepartmentID=18 AND status=0)')->get();
            }
            $sale->user = Admin::find($sale->userID);
            $sale->HoldStatus = Order_Requirement_Sales_Approve::where('Order_Requirement_Sales_id', $sale->id)->where('action', 'HOLD')->where('status', 1)->where('userID', auth()->user()->id)->count();

            $userSales[] = $sale;

            if ($sale->Approve_status == 'APPROVE') {
                $SalesApproved[] = $sale;
            } elseif ($sale->Approve_status == 'REJECT') {
                $SalesREJECT[] = $sale;
            } elseif ($sale->Approve_status == 'RECHECK') {
                $SalesRECHECK[] = $sale;
            } elseif ($sale->Approve_status == 'OBJECT') {
                $SalesOBJECT[] = $sale;
            } elseif ($sale->Approve_status == 'HOLD') {
                $SalesHOLD[] = $sale;
            } else {
                $SalesPending[] = $sale;
            }
        }        

        if (isset($EXT[18]['inputer'])) {
            $stock = Order_Requirement_Stock::select('order_requirement_stock.*','prj_project.pname','prj_subproject.spname','prj_organisation.organisation','module_bsns_unit.unit_name','prj_inventory.inventory_name')
                    ->leftJoin('prj_organisation','order_requirement_stock.Organization','=','prj_organisation.id')
                    ->leftJoin('prj_project','order_requirement_stock.Unit_Name','=','prj_project.id')
                    ->leftJoin('prj_subproject','order_requirement_stock.Plant_Name','=','prj_subproject.id')
                    ->leftJoin('module_bsns_unit','order_requirement_stock.BU_Name','=','module_bsns_unit.id')
                    ->leftJoin('prj_inventory','order_requirement_stock.Factory_Godown_Name','=','prj_inventory.id')
                    ->orderBy('id', 'DESC');
        } else {
            $stock = Order_Requirement_Stock::select('order_requirement_stock.*','prj_project.pname','prj_subproject.spname','prj_organisation.organisation','module_bsns_unit.unit_name','prj_inventory.inventory_name')
                    ->leftJoin('prj_organisation','order_requirement_stock.Organization','=','prj_organisation.id')
                    ->leftJoin('prj_project','order_requirement_stock.Unit_Name','=','prj_project.id')
                    ->leftJoin('prj_subproject','order_requirement_stock.Plant_Name','=','prj_subproject.id')
                    ->leftJoin('module_bsns_unit','order_requirement_stock.BU_Name','=','module_bsns_unit.id')
                    ->leftJoin('prj_inventory','order_requirement_stock.Factory_Godown_Name','=','prj_inventory.id')
                    ->where('order_requirement_stock.status', 0)
                    ->orderBy('id', 'DESC');
        }

        if ($fromdate && $todate) {
            $stock->whereBetween('created_at', [$fromdate, $todate]);
        }

        $Stock = $stock->get();

        $userStock = [];
        $approved = [];
        $REJECT = [];
        $RECHECK = [];
        $OBJECT = [];
        $HOLD = [];
        $pending = [];
        foreach ($Stock as $stockItem) {
            if ($stockItem->Forward_Status != 1) {
                $stockItem->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="18" AND step="' . $stockItem->Approve_Step . '")')->get();
            } else {
                $stockItem->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data` WHERE DataID="' . $stockItem->id . '" AND DepartmentID=18 AND status=0)')->get();
            }
            $stockItem->user = Admin::find($stockItem->userID);
            $stockItem->HoldStatus = Order_Requirement_Stock_Approve::where('Order_Requirement_Stock_id', $stockItem->id)->where('action', 'HOLD')->where('status', 1)->where('userID', auth()->user()->id)->count();

            $userStock[] = $stockItem;

            if ($stockItem->Approve_status == 'APPROVE') {
                $approved[] = $stockItem;
            } elseif ($stockItem->Approve_status == 'REJECT') {
                $REJECT[] = $stockItem;
            } elseif ($stockItem->Approve_status == 'RECHECK') {
                $RECHECK[] = $stockItem;
            } elseif ($stockItem->Approve_status == 'OBJECT') {
                $OBJECT[] = $stockItem;
            } elseif ($stockItem->Approve_status == 'HOLD') {
                $HOLD[] = $stockItem;
            } else {
                $pending[] = $stockItem;
            }
            $stockItem->RawMaterial = MaterialManagement_Add_Material::select('prj_material.material_name as matname')
            ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
            ->where('materialmanagement_add_material.id',$stockItem->Raw_Material)
            ->first();
        }
        $Organisation=Factory_Organisation::all_org();
        $BU = Master_BU::all_bu();
        $MU = Master_Manufacturing_unit::all_mu();
        $plant_name = Master_Plant_Machinery::all_pm();
        $Customer_Name = Master_Customer_Name::all_cn();
        $Company_Name = Master_Company_Name::all_con();
        return view('orderRequriement/orderRequirementList', ['Sales' => $Sales, 'Stock' => $Stock, 'fromdate' => $fromdate, 'todate' => $dateto, 'userSales' => $userSales, 'userStock' => $userStock, 'SalesApproved' => $SalesApproved, 'SalesREJECT' => $SalesREJECT, 'SalesRECHECK' => $SalesRECHECK, 'SalesOBJECT' => $SalesOBJECT, 'SalesHOLD' => $SalesHOLD, 'SalesPending' => $SalesPending, 'approved' => $approved, 'REJECT' => $REJECT, 'RECHECK' => $RECHECK, 'OBJECT' => $OBJECT, 'HOLD' => $HOLD, 'pending' => $pending,'Organisation'=>$Organisation,'BU'=>$BU,'MU'=>$MU,'plant_name'=>$plant_name,'Customer_Name'=>$Customer_Name,'Company_Name'=>$Company_Name]);
    }
    public function orderRequirementStockList(Request $request)
    {
        
        $EXT = Session::get('EXT');
       
               

        if (isset($EXT[18]['inputer'])) {
            $stock = Order_Requirement_Stock::orderBy('id', 'DESC');
        } else {
            $stock = Order_Requirement_Stock::where('status', 0)->orderBy('id', 'DESC');
        }
        if(isset($request->Organization) && $request->Organization!='')
        {
            $stock=$stock->where('Organization',$request->Organization);
        }
        if(isset($request->BU_Name) && $request->BU_Name!='')
        {
            $stock=$stock->where('BU_Name',$request->BU_Name);
        }
        if(isset($request->Unit_Name) && $request->Unit_Name!='')
        {
            $stock=$stock->where('Unit_Name',$request->Unit_Name);
        }
        if(isset($request->Plant_Name) && $request->Plant_Name!='')
        {
            $stock=$stock->where('Plant_Name',$request->Plant_Name);
        }
        if(isset($request->Expected_Date) && $request->Expected_Date!='')
        {
            $stock=$stock->where('Expected_Date',$request->Expected_Date);
        }
        if(isset($request->Stock_Order_No) && $request->Stock_Order_No!='')
        {
            $stock=$stock->where('Stock_Order_No',$request->Stock_Order_No);
        }
        if(isset($request->Company_Name) && $request->Company_Name!='')
        {
            $stock=$stock->where('Company_Name',$request->Company_Name);
        }
        if(isset($request->Factory_Godown_Name) && $request->Factory_Godown_Name!='')
        {
            $stock=$stock->where('Factory_Godown_Name',$request->Factory_Godown_Name);
        }
        if(isset($request->Raw_Material) && $request->Raw_Material!='')
        {
            $stock=$stock->where('Raw_Material',$request->Raw_Material);
        }
        if(isset($request->QTY) && $request->QTY!='')
        {
            $stock=$stock->where('QTY',$request->QTY);
        }

        

        $Stock = $stock->get();

        $userStock = [];
        $approved = [];
        $REJECT = [];
        $RECHECK = [];
        $OBJECT = [];
        $HOLD = [];
        $pending = [];
        foreach ($Stock as $stockItem) {
            if ($stockItem->Forward_Status != 1) {
                $stockItem->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="18" AND step="' . $stockItem->Approve_Step . '")')->get();
            } else {
                $stockItem->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data` WHERE DataID="' . $stockItem->id . '" AND DepartmentID=18 AND status=0)')->get();
            }
            $stockItem->user = Admin::find($stockItem->userID);
            $stockItem->HoldStatus = Order_Requirement_Stock_Approve::where('Order_Requirement_Stock_id', $stockItem->id)->where('action', 'HOLD')->where('status', 1)->where('userID', auth()->user()->id)->count();
            // Raw_Material
            //$rawss=MaterialManagement_Add_Material::find($stockItem->Raw_Material);
            $rawss= MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
            ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
            ->where('materialmanagement_add_material.id',$stockItem->Raw_Material)
            ->first();

            $stockItem->rawmaterial=$rawss->matname??'';
            $userStock[] = $stockItem;

            if ($stockItem->Approve_status == 'APPROVE') {
                $approved[] = $stockItem;
            } elseif ($stockItem->Approve_status == 'REJECT') {
                $REJECT[] = $stockItem;
            } elseif ($stockItem->Approve_status == 'RECHECK') {
                $RECHECK[] = $stockItem;
            } elseif ($stockItem->Approve_status == 'OBJECT') {
                $OBJECT[] = $stockItem;
            } elseif ($stockItem->Approve_status == 'HOLD') {
                $HOLD[] = $stockItem;
            } else {
                $pending[] = $stockItem;
            }
        }
        $Organisation=prj_organisation::all_org();
        $BU = Module_Bsns_Unit::all_bu();
        $MU = prj_project::all_mu();
        $plant_name = Prj_Subproject::all_pm();
        $Customer_Name = Master_Customer_Name::all_cn();
        $Company_Name = Master_Company_Name::all_con();
        $all_godownname=Prj_Inventory::all_godownname();
        return view('orderRequriement/orderRequirementStockList', [ 'Stock' => $Stock,'userStock' => $userStock, 'approved' => $approved, 'REJECT' => $REJECT, 'RECHECK' => $RECHECK, 'OBJECT' => $OBJECT, 'HOLD' => $HOLD, 'pending' => $pending,'Organisation'=>$Organisation,'BU'=>$BU,'MU'=>$MU,'plant_name'=>$plant_name,'Customer_Name'=>$Customer_Name,'Company_Name'=>$Company_Name,'all_godownname'=>$all_godownname]);
    }
    public function SalesFilter(Request $req)
    {
        $country = Country::all();
        $state = State::all();
        $city = City::all();
        $Organization = Factory_Organisation::all();
        $BU = Master_BU::all();
        $Manufacturing_unit = Master_Manufacturing_unit::all();
        $plant_name = Master_Plant_Machinery::all();
        $Customer_Name = Master_Customer_Name::all();
        $Company_Name = Master_Company_Name::all();
        $Godown_Name = Master_Godown_Name::all();
        $UOM = Factory_Uom::all();
        return view('orderRequriement/sales_filter', [ 'Organization' => $Organization, 'BU' => $BU, 'Manufacturing_unit' => $Manufacturing_unit, 'plant_name' => $plant_name, 'Customer_Name' => $Customer_Name, 'Company_Name' => $Company_Name, 'Godown_Name' => $Godown_Name, 'UOM' => $UOM,'city' => $city, 'country' => $country, 'state' => $state]);
    }
    public function StockFilter(Request $req,$id='')
    {
        $country = Country::all();
        $state = State::all();
        $city = City::all();
        $Organization = prj_organisation::all();
        $BU = Module_Bsns_Unit::all();
        $Manufacturing_unit = prj_project::all();
        $plant_name = Prj_Subproject::all();
        $Customer_Name = Master_Customer_Name::all();
        $Company_Name = Master_Company_Name::all();
        $Godown_Name = Prj_Inventory::all();
        $UOM = Factory_Uom::all();
        $BOM_DATA = BOM::where('Approve_status', 'APPROVE')->get();
        $Raw_Material = [];
        foreach ($BOM_DATA as $Val) {
            if (isset($Val->Raw_Material_FG)) {
                //$Val->RawMaterial = MaterialManagement_Add_Material::find($Val->Raw_Material_FG);
                $Val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                    ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                    ->where('materialmanagement_add_material.id',$Val->Raw_Material_FG)
                    ->first();

                $Raw_Material[$Val->Raw_Material_FG] = $Val;
            }
        }
        $Filtered_Array = array_values($Raw_Material);

        $editSales = Order_Requirement_Sales::find($id);
        $editStock = Order_Requirement_Stock::find($id);
        $product = array();
        if (isset($editStock) && $editStock != '') {
            $product = Order_Requirement_Product_Details::where('Stock_id', $editStock->id)->get();
        }

        return view('orderRequriement/stock_filter', ['editSales' => $editSales, 'editStock' => $editStock, 'product' => $product, 'Organization' => $Organization, 'BU' => $BU, 'Manufacturing_unit' => $Manufacturing_unit, 'plant_name' => $plant_name, 'Customer_Name' => $Customer_Name, 'Company_Name' => $Company_Name, 'Godown_Name' => $Godown_Name, 'Raw_Material' => $Filtered_Array, 'UOM' => $UOM, 'city' => $city, 'country' => $country, 'state' => $state]);
    }

    public function AddorderRequirementPage($id = null)
    {
        $country = Country::all();
        $state = State::all();
        $city = City::all();
        $Organization = prj_organisation::all();
        //$Plant_Name = Prj_Subproject::all();
        $plant_name=Prj_Subproject::all();
        $USER=Admin::where('role',1)->get();
        $BU = Module_Bsns_Unit::all();
        //$Manufacturing_unit = Master_Manufacturing_unit::all();
        $Manufacturing_unit = Factory_Address_Detail::select('prj_project.*')
        ->leftJoin('prj_project','factory_address_details.name_of_unit','=','prj_project.id')
        ->where('Approve_status','APPROVE')
        ->groupBy('prj_project.pname')
        ->get();
        //$plant_name = Master_Plant_Machinery::all();
        $Customer_Name = Master_Customer_Name::all();
        $Company_Name = Master_Company_Name::all();
        $Godown_Name = Prj_Inventory::select('prj_inventory.*')->where('godown_type','69')->get();
        $UOM = Factory_Uom::all();
        $BOM_DATA = BOM::where('Approve_status', 'APPROVE')->get();
        $Materials=MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                        ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                        ->where('Approve_status','APPROVE')->get();

        $Raw_Material = [];
        foreach ($BOM_DATA as $Val) {
            if (isset($Val->Raw_Material_FG)) {
                  $Val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                    ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                    ->where('materialmanagement_add_material.id',$Val->Raw_Material_FG)
                    ->first();
                //find($Val->Raw_Material_FG);
                // $Val->Raw_Material = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                // ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                // ->where('materialmanagement_add_material.id',$Val->Raw_Material_FG)->first();
                $Raw_Material[$Val->Raw_Material_FG] = $Val;
            }
        }
        $Filtered_Array = array_values($Raw_Material);
        //return $Filtered_Array;

        $editSales = Order_Requirement_Sales::find($id);
        $editStock = Order_Requirement_Stock::find($id);
        if(isset($editStock)){
        $raw_material_name=MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
            ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
            ->where('materialmanagement_add_material.id',$editStock->Raw_Material)
            ->first();
        }else{
            $raw_material_name='';
        }
        if(isset($editStock)){
            $address_bill=Pur_Address::select('pur_address.*','prj_state.sname')
                ->leftJoin('prj_state', 'pur_address.sid', '=', 'prj_state.id')
                ->where('pur_address.status','1')
                ->where('pur_address.addrss_type','Billing Address')
                ->where('pur_address.org_id',$editStock->Organization)
                ->get();
            $address_ship = Pur_Address::select('pur_address.*','prj_state.sname')
                ->leftJoin('prj_state', 'pur_address.sid', '=', 'prj_state.id')
                ->where('pur_address.status','1')
                ->where('pur_address.org_id',$editStock->Organization)
                ->get();
        }else{
            $address_bill=[];
            $address_ship=[];
        }
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

        return view('orderRequriement/orderRequirement', ['editSales' => $editSales, 'editStock' => $editStock,'raw_material_name'=>$raw_material_name, 'product' => $product,'address_bill'=>$address_bill,'address_ship'=>$address_ship, 'Organization' => $Organization, 'BU' => $BU,'USER'=>$USER,'Manufacturing_unit' => $Manufacturing_unit, 'plant_name' => $plant_name, 'Customer_Name' => $Customer_Name, 'Company_Name' => $Company_Name, 'Godown_Name' => $Godown_Name, 'Raw_Material' => $Filtered_Array, 'UOM' => $UOM, 'city' => $city, 'country' => $country, 'state' => $state,'Materials'=>$Materials]);
    }
    public function getaddress($id)
        {
            $address=[];
            $billing_address=Pur_Address::select('pur_address.*','prj_state.sname')
                    ->leftJoin('prj_state', 'pur_address.sid', '=', 'prj_state.id')
                    ->where('pur_address.status','1')
                    ->where('pur_address.addrss_type','Billing Address')
                    ->where('pur_address.org_id',$id)
                    ->get();
            $shipping_address=Pur_Address::select('pur_address.*','prj_state.sname')
                    ->leftJoin('prj_state', 'pur_address.sid', '=', 'prj_state.id')
                    ->where('pur_address.status','1')
                    ->where('pur_address.org_id',$id)
                    ->get();

            $address['billing_address'] = $billing_address;
            $address['shipping_address'] = $shipping_address;

            return response()->json($address);
        }

    public function getaddressdetailsbill($id){
        $billing_address_details=Pur_Address::select('pur_address.*','prj_district.distname')
        ->leftJoin('prj_district', 'pur_address.did', '=', 'prj_district.id')
        ->where('pur_address.status','1')
        ->where('pur_address.id',$id)
        ->get();
        return response()->json($billing_address_details);
    }
    public function getaddressdetailsship($id){
        $shipping_address_details=Pur_Address::select('pur_address.*','prj_district.distname')
        ->leftJoin('prj_district', 'pur_address.did', '=', 'prj_district.id')
        ->where('pur_address.status','1')
        ->where('pur_address.id',$id)
        ->get();
        return response()->json($shipping_address_details);
    }

    public function delete_Sales($id)
    {
        Order_Requirement_Sales::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function delete_Stock($id)
    {
        Order_Requirement_Stock::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function Sales_View($id, $type)
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
        $Organization = Factory_Organisation::all();
        $BU = Master_BU::all();
        $Manufacturing_unit = Master_Manufacturing_unit::all();
        $plant_name = Master_Plant_Machinery::all();
        $Customer_Name = Master_Customer_Name::all();
        $Company_Name = Master_Company_Name::all();
        $Godown_Name = Master_Godown_Name::all();
        $UOM = Factory_Uom::all();
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

        return view('orderRequriement/Sales_View', ['editSales' => $editSales, 'UOM' => $UOM, 'Raw_Material' => $Filtered_Array, 'nextID' => $nextID, 'Organization' => $Organization, 'BU' => $BU, 'Manufacturing_unit' => $Manufacturing_unit, 'plant_name' => $plant_name, 'Customer_Name' => $Customer_Name, 'Company_Name' => $Company_Name, 'Godown_Name' => $Godown_Name, 'approves' => $approves, 'city' => $city, 'country' => $country, 'state' => $state]);
    }

    public function Stock_View($id, $type)
    {
        $approStock = Order_Requirement_Stock_Approve::where('Order_Requirement_Stock_id', $id)->get();
        $approvesStock = [];
        foreach ($approStock as $val) {
            $val->user = Admin::find($val->userID);
            array_push($approvesStock, $val);
        }

        $Organization = prj_organisation::all();
        $BU = Module_Bsns_Unit::all();
        $Manufacturing_unit = Factory_Address_Detail::select('prj_project.*')
        ->leftJoin('prj_project','factory_address_details.name_of_unit','=','prj_project.id')
        ->where('Approve_status','APPROVE')
        ->groupBy('prj_project.pname')
        ->get();
        $plant_name = Prj_Subproject::all();
        $Customer_Name = Master_Customer_Name::all();
        $Company_Name = Master_Company_Name::all();
        $Godown_Name = Prj_Inventory::all();
        $UOM = Factory_Uom::all();
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

        //$editStock = Order_Requirement_Stock::find($id);
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

        return view('orderRequriement/Stock_View', ['editStock' => $editStock,'billing_address'=>$billing_address,'shipping_address'=>$shipping_address,'product' => $product, 'Organization' => $Organization, 'BU' => $BU, 'Manufacturing_unit' => $Manufacturing_unit, 'plant_name' => $plant_name, 'Customer_Name' => $Customer_Name, 'Company_Name' => $Company_Name, 'Godown_Name' => $Godown_Name, 'Raw_Material' => $Filtered_Array, 'UOM' => $UOM, 'nextID' => $nextID, 'approves' => $approvesStock]);
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

    public function ExportDataSales(Request $request)
    {
        $SalesData = Order_Requirement_Sales::orderBy('id', 'DESC')->get();

        $Sales_arr = array();
        foreach ($SalesData as $sale) {
            if ($sale->Forward_Status != 1) {
                $sale->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="18" AND step="' . $sale->Approve_Step . '")')->get();
            } else {
                $sale->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data` WHERE DataID="' . $sale->id . '" AND DepartmentID=18 AND status=0)')->get();
            }
            $sale->user = Admin::find($sale->userID);

            $userSales[] = $sale;
            array_push($Sales_arr, $sale);
        }

        $Checkbox = CheckBox::where('userID', auth()->user()->id)->where('tableID', 222)->get();

        $Checkbox_Arr = [];
        foreach ($Checkbox as $val) {
            $valuee = $val->CheckBox;
            array_push($Checkbox_Arr, $valuee);
        }

        $d = [];
        foreach ($Sales_arr as $key => $val) {
            $rowData = [
                "SL. No." => $key + 1,
                "Creator Name" => isset($val->user->name) && $val->user->name != '' ? $val->user->name : '',
                "Date & Time" => isset($val->created_at) && $val->created_at != '' ? date('d-m-Y H:i:s A', strtotime($val->created_at)) : '',
                "Product" => isset($val->Product->product) && $val->Product->product != '' ? $val->Product->product : '',
                "Sub Product" => isset($val->Sub_Product->sub_product) && $val->Sub_Product->sub_product != '' ? $val->Sub_Product->sub_product : '',
                "Sub Sub Product" => isset($val->Sub_Sub_Product->sub_sub_product) && $val->Sub_Sub_Product->sub_sub_product != '' ? $val->Sub_Sub_Product->sub_sub_product : '',
                "Status" => ($val->Approve_status == 'APPROVE') ? 'APPROVED' : (($val->Approve_status == 'REJECT') ? 'REJECTED' : (($val->Approve_status == 'RECHECK') ? 'RECHECK' : (($val->Approve_status == 'OBJECT') ? 'OBJECT' : (($val->Approve_status == 'HOLD') ? 'HOLD' :
                    'Pending')))),
                "Raw Material(FG)" => isset($val->Raw_Material->Material_Name) && $val->Raw_Material->Material_Name != '' ? $val->Raw_Material->Material_Name : '',
                "HSN Code" => isset($val->HSN_Code) && $val->HSN_Code != '' ? $val->HSN_Code : '',
                "UOM" => isset($val->UOM->UOMs) && $val->UOM->UOMs != '' ? $val->UOM->UOMs : '',
                "Pending With" => ($val->Approve_status === 'FORWARD' || ($val->Approve_status == '' && isset($val->status) && $val->status != 1)) ?
                    'Pending With ' . (function () use ($val) {
                        $names = [];
                        if ($val->PendingWith != null) {
                            foreach ($val->PendingWith as $name) {
                                if (isset($name->name) && $name->name != '') {
                                    $names[] = $name->name;
                                }
                            }
                        }
                        return implode(', ', $names);
                    })() : (($val->Approve_status === 'RECHECK' || $val->Approve_status === 'OBJECT') ?
                        (isset($val->user->name) && $val->user->name != '' ? 'Pending With ' . $val->user->name : '') : ''),
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

        $file = "Sales_data.csv";
        $this->collectionExport($d, $file);
    }

    public function ExportDataStock(Request $request)
    {
        $StockData = Production_Process::orderBy('id', 'DESC')->get();

        $Stock_arr = array();
        foreach ($StockData as $stockItem) {
            if ($stockItem->Forward_Status != 1) {
                $stockItem->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="18" AND step="' . $stockItem->Approve_Step . '")')->get();
            } else {
                $stockItem->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data` WHERE DataID="' . $stockItem->id . '" AND DepartmentID=18 AND status=0)')->get();
            }
            $stockItem->user = Admin::find($stockItem->userID);

            array_push($Stock_arr, $stockItem);
        }

        $Checkbox = CheckBox::where('userID', auth()->user()->id)->where('tableID', 333)->get();

        $Checkbox_Arr = [];
        foreach ($Checkbox as $val) {
            $valuee = $val->CheckBox;
            array_push($Checkbox_Arr, $valuee);
        }

        $d = [];
        foreach ($Stock_arr as $key => $val) {
            $rowData = [
                "SL. No." => $key + 1,
                "Creator Name" => isset($val->user->name) && $val->user->name != '' ? $val->user->name : '',
                "Date & Time" => isset($val->created_at) && $val->created_at != '' ? date('d-m-Y H:i:s A', strtotime($val->created_at)) : '',
                "Product" => isset($val->Product->product) && $val->Product->product != '' ? $val->Product->product : '',
                "Sub Product" => isset($val->Sub_Product->sub_product) && $val->Sub_Product->sub_product != '' ? $val->Sub_Product->sub_product : '',
                "Sub Sub Product" => isset($val->Sub_Sub_Product->sub_sub_product) && $val->Sub_Sub_Product->sub_sub_product != '' ? $val->Sub_Sub_Product->sub_sub_product : '',
                "Status" => ($val->Approve_status == 'APPROVE') ? 'APPROVED' : (($val->Approve_status == 'REJECT') ? 'REJECTED' : (($val->Approve_status == 'RECHECK') ? 'RECHECK' : (($val->Approve_status == 'OBJECT') ? 'OBJECT' : (($val->Approve_status == 'HOLD') ? 'HOLD' :
                    'Pending')))),
                "Raw Material(FG)" => isset($val->Raw_Material->Material_Name) && $val->Raw_Material->Material_Name != '' ? $val->Raw_Material->Material_Name : '',
                "HSN Code" => isset($val->HSN_Code) && $val->HSN_Code != '' ? $val->HSN_Code : '',
                "UOM" => isset($val->UOM->UOMs) && $val->UOM->UOMs != '' ? $val->UOM->UOMs : '',
                "Pending With" => ($val->Approve_status === 'FORWARD' || ($val->Approve_status == '' && isset($val->status) && $val->status != 1)) ?
                    'Pending With ' . (function () use ($val) {
                        $names = [];
                        if ($val->PendingWith != null) {
                            foreach ($val->PendingWith as $name) {
                                if (isset($name->name) && $name->name != '') {
                                    $names[] = $name->name;
                                }
                            }
                        }
                        return implode(', ', $names);
                    })() : (($val->Approve_status === 'RECHECK' || $val->Approve_status === 'OBJECT') ?
                        (isset($val->user->name) && $val->user->name != '' ? 'Pending With ' . $val->user->name : '') : ''),
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

        $file = "Stock_data.csv";
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

    public function MaterialData($id)
    {
        $BOM = BOM::where(['Raw_Material_FG' => $id, 'Approve_status' => 'APPROVE'])->orderBy('id', 'DESC')->first();
        $Materials = [];
        if (isset($BOM) && $BOM != '') {
            $MaterialData = BOM_Material::where('BOM_ID', $BOM->id)->get();
            foreach ($MaterialData as $Val) {
                if (isset($Val->Material)) {
                    //$Val->RawMaterial = MaterialManagement_Add_Material::find($Val->Material);
                    $Val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname','materialmanagement_add_material.id as matid')
                    ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                    ->where('materialmanagement_add_material.id',$Val->Material)
                    ->first();
                    $Materials[] = $Val;
                }
            }
        }

        return response()->json(['success' => true, 'data' => $Materials]);
    }
    public function MaterialCalculation(Request $request, $id){
        //return $id;
        $mat_id = $request->mat_id; 
        $totqty = $request->totqty; 
        $calamt = 0;
        $gstamt = 0;
        $totalamt= 0;
        $calculationdata= [];
            $MaterialData = BOM_Material::where('Material', $mat_id)->first();
            $calamt=$MaterialData->Basic_Amount_unit * $totqty;
            $gstamt= ($calamt * $MaterialData->GST_Percentage) / 100 ;
            $totalamt=$calamt + $gstamt;
                $calculationdata = [
                    'calculated_amount' => $calamt,
                    'gst_amount' => $gstamt,
                    'total_amount' => $totalamt
                ];
        return response()->json(['success' => true, 'data' => $calculationdata]);
    }
}
