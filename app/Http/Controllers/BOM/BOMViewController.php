<?php

namespace App\Http\Controllers\BOM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\BOM\{Master_Code, Master_Color, Master_Consumbles, Master_GST_Percentage, Master_Management_Expenses, Master_Material, Master_Services, Master_Machine_Specification};
use App\Models\BOM\{BOM, BOM_Consumbles, BOM_Expenses, BOM_Machine, BOM_Management, BOM_Manpower, BOM_Material, BOM_Services, BOM_Transport, BOM_Approve};
use App\Models\Master\Plant\{Master_category, Master_Manufacturing_unit,Master_Machine_Name};
use App\Models\FactoryCreater\{Factory_Organisation, Factory_Product, Factory_Sub_Product, Factory_Sub_Sub_Product, Factory_Uom};
use App\Models\Master\{Master_Plant_Machinery};
use App\Models\{CheckBox, Admin, Forwarded_Data, Department_Assign};
use App\Models\MaterialManagement\{MaterialManagement_Add_Material};
use App\Models\RawMaterial\{RawMaterial_stock, RawMaterial, RawMaterial_data};
use App\Models\ProductCategories\{ProductCategories_Add_Product};
use Session;

class BOMViewController extends Controller
{
    public function BOMList(Request $request)
    {
        $EXT = Session::get('EXT');

        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));

        if (isset($EXT[11]['inputer'])) {
            $query = BOM::orderBy('id', 'DESC');
        } else {
            $query = BOM::where('status', 0)->orderBy('id', 'DESC');
        }

        if ($fromdate && $todate) {
            $query->whereBetween('created_at', [$fromdate, $todate]);
        }

        $BOMName = '';
        if ($request->has('BOM_Name') && $request->input('BOM_Name') != '') {
            $BOMName = $request->input('BOM_Name');
            if ($BOMName !== 'all') {
                $query->where('BOM_Name', $BOMName);
            }
        }

        $BOMCode = '';
        if ($request->has('BOM_Code') && $request->input('BOM_Code') != '') {
            $BOMCode = $request->input('BOM_Code');
            if ($BOMCode !== 'all') {
                $query->where('BOM_Code', $BOMCode);
            }
        }

        $Organizations = '';
        if ($request->has('Organization') && $request->input('Organization') != '') {
            $Organizations = $request->input('Organization');
            if ($Organizations !== 'all') {
                $query->where('Organization', $Organizations);
            }
        }

        $ManufacturingUnits = '';
        if ($request->has('Manufacturing_Unit') && $request->input('Manufacturing_Unit') != '') {
            $ManufacturingUnits = $request->input('Manufacturing_Unit');
            if ($ManufacturingUnits !== 'all') {
                $query->where('Manufacturing_Unit', $ManufacturingUnits);
            }
        }

        $PlantNames = '';
        if ($request->has('Plant_Name') && $request->input('Plant_Name') != '') {
            $PlantNames = $request->input('Plant_Name');
            if ($PlantNames !== 'all') {
                $query->where('Plant_Name', $PlantNames);
            }
        }

        $Categoryss = '';
        if ($request->has('Category') && $request->input('Category') != '') {
            $Categoryss = $request->input('Category');
            if ($Categoryss !== 'all') {
                $query->where('Category', $Categoryss);
            }
        }

        $Productss = '';
        if ($request->has('Product') && $request->input('Product') != '') {
            $Productss = $request->input('Product');
            if ($Productss !== 'all') {
                $query->where('Product', $Productss);
            }
        }

        $RawMaterials = '';
        if ($request->has('Raw_Material') && $request->input('Raw_Material') != '') {
            $RawMaterials = $request->input('Raw_Material');
            if ($RawMaterials !== 'all') {
                $query->where('Raw_Material_FG', $RawMaterials);
            }
        }

        $HSNCodes = '';
        if ($request->has('HSN_Code') && $request->input('HSN_Code') != '') {
            $HSNCodes = $request->input('HSN_Code');
            if ($HSNCodes !== 'all') {
                $query->where('HSN_Code_FG', $HSNCodes);
            }
        }

        $UOMss = '';
        if ($request->has('UOM') && $request->input('UOM') != '') {
            $UOMss = $request->input('UOM');
            if ($UOMss !== 'all') {
                $query->where('UOMFG', $UOMss);
            }
        }

        $BOM = $query->get();

        $BOM_arr = [];
        $approved = [];
        $REJECT = [];
        $RECHECK = [];
        $OBJECT = [];
        $HOLD = [];
        $pending = [];
        foreach ($BOM as $val) {
            if ($val->Forward_Status != 1) {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="11" AND step="' . $val->Approve_Step . '")')->get();
            } else {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data` WHERE DataID="' . $val->id . '" AND DepartmentID=11 AND `status`=0)')->get();
            }
            $val->user = Admin::find($val->userID);
            $val->Organizationss = Factory_Organisation::find($val->Organization);
            $val->Manufacturing_Unitss = Master_Manufacturing_unit::find($val->Manufacturing_Unit);
            $val->plant_namess = Master_Plant_Machinery::find($val->Plant_Name);
            $val->Categoryss = Master_category::find($val->Category);
            $val->Productss = Factory_Product::find($val->Product);
            $val->HoldStatus = BOM_Approve::where('BOM_id', $val->id)->where('action', 'HOLD')->where('status', 1)->where('userID', auth()->user()->id)->count();
            //$val->RawMaterial = MaterialManagement_Add_Material::find($val->Raw_Material_FG);
            $val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name')
            ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
            ->where('materialmanagement_add_material.id',$val->Raw_Material_FG)->first();
            $val->UOM = Factory_Uom::find($val->UOMFG);

            $BOM_arr[] = $val;

            if ($val->Approve_status == 'APPROVE') {
                $approved[] = $val;
            } elseif ($val->Approve_status == 'REJECT') {
                $REJECT[] = $val;
            } elseif ($val->Approve_status == 'RECHECK') {
                $RECHECK[] = $val;
            } elseif ($val->Approve_status == 'OBJECT') {
                $OBJECT[] = $val;
            } elseif ($val->Approve_status == 'HOLD') {
                $HOLD[] = $val;
            } else {
                $pending[] = $val;
            }
        }

        $Organization = Factory_Organisation::all();
        $Manufacturing_unit = Master_Manufacturing_unit::all();
        $Plant_Name = Master_Plant_Machinery::all();
        $Category = Master_category::all();
        $Product = Factory_Product::all();
        $UOM = Factory_Uom::all();

        $ForDropdown = BOM::orderBy('id', 'DESC')->get();
        $ForDropdown_arr = array();
        foreach ($ForDropdown as $val) {
            $val->Organization = Factory_Organisation::find($val->Organization);
            $val->Manufacturing_Unit = Master_Manufacturing_unit::find($val->Manufacturing_Unit);
            $val->plant_name = Master_Plant_Machinery::find($val->Plant_Name);
            $val->Category = Master_category::find($val->Category);
            $val->Product = Factory_Product::find($val->Product);
            //$val->RawMaterial = MaterialManagement_Add_Material::find($val->Raw_Material_FG);
            $val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name')
            ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
            ->where('materialmanagement_add_material.id',$val->Raw_Material_FG)->first();
            $val->UOM = Factory_Uom::find($val->UOMFG);

            array_push($ForDropdown_arr, $val);
        }

        return view('BOM/BOMList', ['BOM_Data' => $BOM_arr, 'approved' => $approved, 'REJECT' => $REJECT, 'RECHECK' => $RECHECK, 'OBJECT' => $OBJECT, 'HOLD' => $HOLD, 'pending' => $pending, 'DropdownData' => $ForDropdown_arr, 'fromdate' => $fromdate, 'todate' => $dateto, 'Organization' => $Organization, 'Manufacturing_unit' => $Manufacturing_unit, 'Plant_Name' => $Plant_Name, 'Category' => $Category, 'Product' => $Product, 'UOM' => $UOM, 'BOMNames' => $BOMName, 'BOMCodes' => $BOMCode, 'Organizations' => $Organizations, 'ManufacturingUnits' => $ManufacturingUnits, 'PlantNames' => $PlantNames, 'Categoryss' => $Categoryss, 'Productss' => $Productss, 'RawMaterials' => $RawMaterials, 'HSNCodes' => $HSNCodes, 'UOMss' => $UOMss]);
    }

    public function AddBOM($id = null)
    {
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
                    ->where('materialmanagement_add_material.id',$materialData->Raw_Material)->first();
                    if (isset($materialData->Name)) {
                        $materialss[] = [
                            'materialName' => $materialData->Name->Material_Name,
                            'material_Name' => $materialData->Name->material_name,
                            'materialID' => $materialData->Name->id,
                        ];
                    }
                }
            }
        }

        $Services = Master_Services::all();
        $Machine_Specification = Master_Machine_Name::all();
        $product_data = ProductCategories_Add_Product::where('Approve_status', 'APPROVE')->get();
        $Raw_Material = [];
        foreach ($product_data as $Val) {
            if (isset($Val->Raw_Material)) {
                //$Val->RawMaterial = MaterialManagement_Add_Material::find($Val->Raw_Material);
                $Val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name')
                            ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                            ->where('materialmanagement_add_material.id',$Val->Raw_Material)->first();
                //dd($Val->RawMaterial);
                //find($Val->Raw_Material);
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
            $Material_data = BOM_Material::where('BOM_ID', $BOM->id)->get();
            $Material_Count += $Material_data->count();
            $Consumbles_data = BOM_Consumbles::where('BOM_ID', $BOM->id)->get();
            $Consumbles_Count += $Consumbles_data->count();
            $Expenses_data = BOM_Expenses::where('BOM_ID', $BOM->id)->get();
            $Expenses_Count += $Expenses_data->count();
            $Machine_data = BOM_Machine::where('BOM_ID', $BOM->id)->get();
            $Machine_Count += $Machine_data->count();
            $Management_data = BOM_Management::where('BOM_ID', $BOM->id)->get();
            $Management_Count += $Management_data->count();
            $Manpower_data = BOM_Manpower::where('BOM_ID', $BOM->id)->get();
            $Manpower_Count += $Manpower_data->count();
            $Services_data = BOM_Services::where('BOM_ID', $BOM->id)->get();
            $Services_Count += $Services_data->count();
            $Transport_data = BOM_Transport::where('BOM_ID', $BOM->id)->get();
            $Transport_Count += $Transport_data->count();
        }

        return view('BOM/BOM', ['Organization' => $Organization, 'Manufacturing_unit' => $Manufacturing_unit, 'Plant_Name' => $Plant_Name, 'Category' => $Category, 'Product' => $Product, 'Sub_Product' => $Sub_Product, 'Sub_Sub_Product' => $Sub_Sub_Product, 'UOM' => $UOM, 'Code' => $Code, 'Color' => $Color, 'Consumbles' => $Consumbles, 'GST_Percentage' => $GST_Percentage, 'Management_Expenses' => $Management_Expenses, 'Material' => $materialss, 'Services' => $Services, 'Machine_Specification' => $Machine_Specification, 'BOM' => $BOM, 'Material_data' => $Material_data, 'Consumbles_data' => $Consumbles_data, 'Expenses_data' => $Expenses_data, 'Machine_data' => $Machine_data, 'Management_data' => $Management_data, 'Manpower_data' => $Manpower_data, 'Services_data' => $Services_data, 'Transport_data' => $Transport_data, 'Material_Count' => $Material_Count, 'Consumbles_Count' => $Consumbles_Count, 'Expenses_Count' => $Expenses_Count, 'Machine_Count' => $Machine_Count, 'Management_Count' => $Management_Count, 'Manpower_Count' => $Manpower_Count, 'Services_Count' => $Services_Count, 'Transport_Count' => $Transport_Count, 'Raw_Material' => $Raw_Material]);
    }

    public function BOM_View($id, $type)
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
        $Consumbles_data = [];
        $Expenses_data = [];
        $Machine_data = [];
        $Management_data = [];
        $Manpower_data = [];
        $Services_data = [];
        $Transport_data = [];

        if (isset($BOM->id)) {
            $Material_data = BOM_Material::where('BOM_ID', $BOM->id)->get();
            $Consumbles_data = BOM_Consumbles::where('BOM_ID', $BOM->id)->get();
            $Expenses_data = BOM_Expenses::where('BOM_ID', $BOM->id)->get();
            $Machine_data = BOM_Machine::where('BOM_ID', $BOM->id)->get();
            $Management_data = BOM_Management::where('BOM_ID', $BOM->id)->get();
            $Manpower_data = BOM_Manpower::select('bom_manpower.*','master_color.Color')
                            ->leftJoin('master_color','bom_manpower.Manpower_Skill','=','master_color.id')
                            ->where('bom_manpower.BOM_ID', $BOM->id)->get();
            $Services_data = BOM_Services::where('BOM_ID', $BOM->id)->get();
            $Transport_data = BOM_Transport::where('BOM_ID', $BOM->id)->get();
        }

        $nextID = $this->next($id, $type);

        return view('BOM/BOM_View', ['BOM' => $BOM, 'Material_data' => $Material_data, 'Consumbles_data' => $Consumbles_data, 'Expenses_data' => $Expenses_data, 'Machine_data' => $Machine_data, 'Management_data' => $Management_data, 'Manpower_data' => $Manpower_data, 'Services_data' => $Services_data, 'Transport_data' => $Transport_data, 'Organization' => $Organization, 'Manufacturing_unit' => $Manufacturing_unit, 'Plant_Name' => $Plant_Name, 'Category' => $Category, 'Product' => $Product, 'Sub_Product' => $Sub_Product, 'Sub_Sub_Product' => $Sub_Sub_Product, 'UOM' => $UOM, 'Code' => $Code, 'Color' => $Color, 'Consumbles' => $Consumbles, 'GST_Percentage' => $GST_Percentage, 'Management_Expenses' => $Management_Expenses, 'Material' => $materialss, 'Services' => $Services, 'Machine_Specification' => $Machine_Specification, 'nextID' => $nextID, 'approves' => $approves, 'Raw_Material' => $Raw_Material]);
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

    public function delete($id)
    {
        BOM::find($id)->delete();

        BOM_Material::where('BOM_ID', $id)->delete();
        BOM_Consumbles::where('BOM_ID', $id)->delete();
        BOM_Expenses::where('BOM_ID', $id)->delete();
        BOM_Machine::where('BOM_ID', $id)->delete();
        BOM_Management::where('BOM_ID', $id)->delete();
        BOM_Manpower::where('BOM_ID', $id)->delete();
        BOM_Services::where('BOM_ID', $id)->delete();
        BOM_Transport::where('BOM_ID', $id)->delete();

        return back()->with('success', 'Deleted Successfully...');
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

    public function ExportData(Request $request)
    {
        $Alldata = BOM::orderBy('id', 'DESC')->get();
        $Alldata_arr = [];
        foreach ($Alldata as $val) {
            if ($val->Forward_Status != 1) {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="11" AND step="' . $val->Approve_Step . '")')->get();
            } else {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data` WHERE DataID="' . $val->id . '" AND DepartmentID=11 AND `status`=0)')->get();
            }
            $val->user = Admin::find($val->userID);
            $val->Organizationss = Factory_Organisation::find($val->Organization);
            $val->Manufacturing_Unitss = Master_Manufacturing_unit::find($val->Manufacturing_Unit);
            $val->plant_namess = Master_Plant_Machinery::find($val->Plant_Name);
            $val->Categoryss = Master_category::find($val->Category);
            $val->Productss = Factory_Product::find($val->Product);
           // $val->RawMaterial = MaterialManagement_Add_Material::find($val->Raw_Material_FG);
            $val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name')
            ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
            ->where('materialmanagement_add_material.id',$val->Raw_Material_FG)->first();
            $val->UOM = Factory_Uom::find($val->UOMFG);

            $Alldata_arr[] = $val;
        }

        //$Checkbox = CheckBox::where('userID', auth()->user()->id)->where('tableID', 11)->get();
        $Checkbox = [];

        $Checkbox_Arr = [];
        foreach ($Checkbox as $val) {
            $valuee = $val->CheckBox;
            array_push($Checkbox_Arr, $valuee);
        }

        $d = [];
        foreach ($Alldata_arr as $key => $val) {
            $rowData = [
                "SL. No." => $key + 1,
                "Creater Name" => isset($val->user->fullname) && $val->user->fullname != '' ? $val->user->fullname : '',
                "Date & Time" => isset($val->created_at) && $val->created_at != '' ? date('d-m-Y H:i:s A', strtotime($val->created_at)) : '',
                "BOM Name" => isset($val->BOM_Name) && $val->BOM_Name != '' ? $val->BOM_Name : '',
                "BOM Code" => isset($val->BOM_Code) && $val->BOM_Code != '' ? $val->BOM_Code : '',
                // "Organization" => isset($val->Organizationss->organization) && $val->Organizationss->organization != '' ? $val->Organizationss->organization : '',
                // "Manufacturing Unit" => isset($val->Manufacturing_Unitss->Manufacturing_unit) && $val->Manufacturing_Unitss->Manufacturing_unit != '' ? $val->Manufacturing_Unitss->Manufacturing_unit : '',
                // "Plant Name" => isset($val->plant_namess->plant_name) && $val->plant_namess->plant_name != '' ? $val->plant_namess->plant_name : '',
                // "Category" => isset($val->Categoryss->category) && $val->Categoryss->category != '' ? $val->Categoryss->category : '',
                // "Product" => isset($val->Productss->product) && $val->Productss->product != '' ? $val->Productss->product : '',
                "Finished Good(FG)" => isset($val->RawMaterial->material_name) && $val->RawMaterial->material_name!=''?$val->RawMaterial->material_name:'',
                "HSN Code(FG)" => isset($val->HSN_Code_FG) && $val->HSN_Code_FG!=''?$val->HSN_Code_FG:'',
                "UOM(FG)" => isset($val->UOMFG) && $val->UOMFG!=''?$val->UOMFG:'',
                "Total Amount" => isset($val->All_Total_Amount) && $val->All_Total_Amount!=''?$val->All_Total_Amount:'',
                "Status" => ($val->Approve_status == 'APPROVE') ? 'APPROVED' : (($val->Approve_status == 'REJECT') ? 'REJECTED' : (($val->Approve_status == 'RECHECK') ? 'RECHECK' : (($val->Approve_status == 'OBJECT') ? 'OBJECT' : (($val->Approve_status == 'HOLD') ? 'HOLD' :
                    'Pending')))),
                "Pending With" => ($val->Approve_status === 'FORWARD' || ($val->Approve_status == '' && isset($val->status) && $val->status != 1)) ?
                    'Pending With ' . (function () use ($val) {
                        $names = [];
                        if ($val->PendingWith != null) {
                            foreach ($val->PendingWith as $name) {
                                if (isset($name->fullname) && $name->fullname != '') {
                                    $names[] = $name->fullname;
                                }
                            }
                        }
                        return implode(', ', $names);
                    })() : (($val->Approve_status === 'RECHECK' || $val->Approve_status === 'OBJECT') ?
                        (isset($val->user->fullname) && $val->user->fullname != '' ? 'Pending With ' . $val->user->fullname : '') : ''),
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

        $file = "BOM_data.csv";
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
