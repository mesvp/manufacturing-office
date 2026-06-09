<?php

namespace App\Http\Controllers\ProductCategories;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategories\{ProductCategories_Add_Product, ProductCategories_Add_Product_Other, ProductCategories_Approve};
use App\Models\FactoryCreater\{Factory_Product, Factory_Sub_Product, Factory_Sub_Sub_Product};
use App\Models\Master\Plant\{Master_BU, Master_Manufacturing_unit, Master_category};
use App\Models\FactoryCreater\{Factory_Organisation, Factory_Uom};
use App\Models\Master\Master_Plant_Machinery;
use App\Models\{Admin, CheckBox};
use App\Models\MaterialManagement\{MaterialManagement_Add_Material};
use Session;

class ProductCategoriesViewController extends Controller
{
    public function ProductList(Request $request)
    {
        $EXT = Session::get('EXT');

        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));

        if (isset($EXT[5]['inputer'])) {
            $query = ProductCategories_Add_Product::orderBy('id', 'DESC');
        } else {
            $query = ProductCategories_Add_Product::where('status', 0)->orderBy('id', 'DESC');
        }

        if ($fromdate && $todate) {
            $query->whereBetween('created_at', [$fromdate, $todate]);
        }

        $OrganizationName = '';
        if ($request->has('Organization_Name') && $request->input('Organization_Name') != '') {
            $OrganizationName = $request->input('Organization_Name');
            if ($OrganizationName !== 'all') {
                $query->where('Organization_Name', $OrganizationName);
            }
        }

        $ManufacturingUnits = '';
        if ($request->has('Manufacturing_Unit') && $request->input('Manufacturing_Unit') != '') {
            $ManufacturingUnits = $request->input('Manufacturing_Unit');
            if ($ManufacturingUnits !== 'all') {
                $query->where('Manufacturing_Unit', $ManufacturingUnits);
            }
        }

        $BUs = '';
        if ($request->has('BU') && $request->input('BU') != '') {
            $BUs = $request->input('BU');
            if ($BUs !== 'all') {
                $query->where('BU', $BUs);
            }
        }

        $PlantNames = '';
        if ($request->has('Plant_Name') && $request->input('Plant_Name') != '') {
            $PlantNames = $request->input('Plant_Name');
            if ($PlantNames !== 'all') {
                $query->where('Plant_Name', $PlantNames);
            }
        }

        $Products = '';
        if ($request->has('Product') && $request->input('Product') != '') {
            $Products = $request->input('Product');
            if ($Products !== 'all') {
                $query->where('Product', $Products);
            }
        }

        $SubProducts = '';
        if ($request->has('Sub_Product') && $request->input('Sub_Product') != '') {
            $SubProducts = $request->input('Sub_Product');
            if ($SubProducts !== 'all') {
                $query->where('Sub_Product', $SubProducts);
            }
        }

        $SubSubProducts = '';
        if ($request->has('Sub_Sub_Product') && $request->input('Sub_Sub_Product') != '') {
            $SubSubProducts = $request->input('Sub_Sub_Product');
            if ($SubSubProducts !== 'all') {
                $query->where('Sub_Sub_Product', $SubSubProducts);
            }
        }

        $CompanyNames = '';
        if ($request->has('Company_Name') && $request->input('Company_Name') != '') {
            $CompanyNames = $request->input('Company_Name');
            if ($CompanyNames !== 'all') {
                $query->where('Company_Name', $CompanyNames);
            }
        }

        $Colors = '';
        if ($request->has('Color') && $request->input('Color') != '') {
            $Colors = $request->input('Color');
            if ($Colors !== 'all') {
                $query->where('Colour', $Colors);
            }
        }

        $Sizes = '';
        if ($request->has('Size') && $request->input('Size') != '') {
            $Sizes = $request->input('Size');
            if ($Sizes !== 'all') {
                $query->where('Size', $Sizes);
            }
        }

        $Categorys = '';
        if ($request->has('Category') && $request->input('Category') != '') {
            $Categorys = $request->input('Category');
            if ($Categorys !== 'all') {
                $query->where('Category', $Categorys);
            }
        }

        $Lables = '';
        if ($request->has('Lable') && $request->input('Lable') != '') {
            $Lables = $request->input('Lable');
            if ($Lables !== 'all') {
                $query->where('Lable', $Lables);
            }
        }

        $RawMaterialss = '';
        if ($request->has('Raw_Material') && $request->input('Raw_Material') != '') {
            $RawMaterialss = $request->input('Raw_Material');
            if ($RawMaterialss !== 'all') {
                $query->where('Raw_Material', $RawMaterialss);
            }
        }

        $HSNCodes = '';
        if ($request->has('HSN_Code') && $request->input('HSN_Code') != '') {
            $HSNCodes = $request->input('HSN_Code');
            if ($HSNCodes !== 'all') {
                $query->where('HSN_Code', $HSNCodes);
            }
        }

        $UOMss = '';
        if ($request->has('UOM') && $request->input('UOM') != '') {
            $UOMss = $request->input('UOM');
            if ($UOMss !== 'all') {
                $query->where('UOM', $UOMss);
            }
        }


        $ProductList = $query->get();

        $ProductList_arr = [];
        $approved = [];
        $REJECT = [];
        $RECHECK = [];
        $OBJECT = [];
        $HOLD = [];
        $pending = [];
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
            $val->HoldStatus = ProductCategories_Approve::where('Product_id', $val->id)->where('action', 'HOLD')->where('status', 1)->where('userID', auth()->user()->id)->count();
            //$val->Raw_Material = MaterialManagement_Add_Material::find($val->Raw_Material);
            $val->Raw_Material = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                                    ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                                    ->where('materialmanagement_add_material.id',$val->Raw_Material)->first();
            $val->UOM = Factory_Uom::find($val->UOM);

            $ProductList_arr[] = $val;

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

        $Product = Factory_Product::all();
        $Sub_Product = Factory_Sub_Product::all();
        $Sub_Sub_Product = Factory_Sub_Sub_Product::all();
        $Organization = Factory_Organisation::all();
        $Manufacturing_Unit = Master_Manufacturing_unit::all();
        $BU = Master_BU::all();
        $Plant_Name = Master_Plant_Machinery::all();
        $category = Master_category::all();
        // $RawMaterial = MaterialManagement_Add_Material::where('Approve_status', 'APPROVE')->get();
        $RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                     ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                     ->get();
        $UOM = Factory_Uom::all();

        $Dropdown = ProductCategories_Add_Product::orderBy('id', 'DESC')->get();
        $Dropdown_arr = array();
        foreach ($Dropdown as $val) {
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

            array_push($Dropdown_arr, $val);
        }

        return view('ProductCategories/ProductList', ['ProductList' => $ProductList_arr, 'DropdownData' => $Dropdown_arr, 'approved' => $approved, 'REJECT' => $REJECT, 'RECHECK' => $RECHECK, 'OBJECT' => $OBJECT, 'HOLD' => $HOLD, 'pending' => $pending, 'fromdate' => $fromdate, 'todate' => $dateto, 'Product' => $Product, 'Sub_Product' => $Sub_Product, 'Sub_Sub_Product' => $Sub_Sub_Product, 'Organization' => $Organization, 'Manufacturing_Unit' => $Manufacturing_Unit, 'BU' => $BU, 'Plant_Name' => $Plant_Name, 'category' => $category, 'OrganizationName' => $OrganizationName, 'ManufacturingUnits' => $ManufacturingUnits, 'BUs' => $BUs, 'PlantNames' => $PlantNames, 'Products' => $Products, 'SubProducts' => $SubProducts, 'SubSubProducts' => $SubSubProducts, 'CompanyNames' => $CompanyNames, 'Colors' => $Colors, 'Sizes' => $Sizes, 'Categorys' => $Categorys, 'Lables' => $Lables, 'UOM' => $UOM, 'RawMaterial' => $RawMaterial, 'RawMaterialss' => $RawMaterialss, 'HSNCodes' => $HSNCodes, 'UOMss' => $UOMss]);
    }

    public function AddProduct($id = null)
    {
        $product = Factory_Product::all();
        $subproduct = Factory_Sub_Product::all();
        $subsubproduct = Factory_Sub_Sub_Product::all();
        $Organization_Name = Factory_Organisation::all();
        $Manufacturing_Unit = Master_Manufacturing_unit::all();
        $BU = Master_BU::all();
        $Plant_Name = Master_Plant_Machinery::all();
        $category = Master_category::all();
        $Raw_Material = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name')
        ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
        ->where(['Approve_status' => 'APPROVE'])->get();
        $Raw_Materialdata = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name')
        ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
        ->where(['Approve_status' => 'APPROVE'])->get();
        $UOM = Factory_Uom::all();
        $edit = ProductCategories_Add_Product::find($id);
        $editother = array();
        $otherCount = 0;
        if (isset($edit->id) && $edit->id != '') {
            $editother = ProductCategories_Add_Product_Other::where('Add_Product_ID', $edit->id)->get();
            $otherCount += $editother->count();
        }

        return view('ProductCategories/AddProductCategories', ['edit' => $edit, 'editother' => $editother, 'otherCount' => $otherCount, 'product' => $product, 'subproduct' => $subproduct, 'subsubproduct' => $subsubproduct, 'Organization_Name' => $Organization_Name, 'Manufacturing_Unit' => $Manufacturing_Unit, 'BU' => $BU, 'Plant_Name' => $Plant_Name, 'category' => $category, 'Raw_Material' => $Raw_Material,'Raw_Materialdata'=>$Raw_Materialdata, 'UOM' => $UOM]);
    }

    public function ProductCategory_View($id, $type)
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

        return view('ProductCategories/ProductCategoryVIew', ['edit' => $edit, 'editother' => $editother, 'otherCount' => $otherCount, 'product' => $product, 'subproduct' => $subproduct, 'subsubproduct' => $subsubproduct, 'Organization_Name' => $Organization_Name, 'Manufacturing_Unit' => $Manufacturing_Unit, 'BU' => $BU, 'Plant_Name' => $Plant_Name, 'category' => $category, 'approves' => $approves, 'nextID' => $nextID, 'Raw_Material' => $Raw_Material, 'UOM' => $UOM]);
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
        ProductCategories_Add_Product::find($id)->delete();
        ProductCategories_Add_Product_Other::where('Add_Product_ID', $id)->delete();

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
        //$ProductList = ProductCategories_Add_Product::orderBy('id', 'DESC')->get();
        $ProductList = ProductCategories_Add_Product::select('productcategories_add_product.*','prj_material.material_name as matname')
                        ->leftJoin('prj_material','productcategories_add_product.Raw_Material','=','prj_material.id')
                        ->get();
        $ProductList_arr = array();
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
            //$val->UOM = Factory_Uom::find($val->UOM);

            array_push($ProductList_arr, $val);
        }

        $Checkbox = CheckBox::where('userID', auth()->user()->id)->where('tableID', 10)->get();

        $Checkbox_Arr = [];
        foreach ($Checkbox as $val) {
            $valuee = $val->CheckBox;
            array_push($Checkbox_Arr, $valuee);
        }

        $d = [];
        foreach ($ProductList_arr as $key => $val) {
            $rowData = [
                "SL. No." => $key + 1,
                "Creater Name" => isset($val->user->fullname) && $val->user->fullname != '' ? $val->user->fullname : '',
                "Date & Time" => isset($val->created_at) && $val->created_at != '' ? date('d-m-Y H:i:s A', strtotime($val->created_at)) : '',
                // "Organization Name" => isset($val->Organization_Name->organization) && $val->Organization_Name->organization != '' ? $val->Organization_Name->organization : '',
                // "Manufacturing Unit" => isset($val->Manufacturing_Unit->Manufacturing_unit) && $val->Manufacturing_Unit->Manufacturing_unit != '' ? $val->Manufacturing_Unit->Manufacturing_unit : '',
                // "BU" => isset($val->BU->BU) && $val->BU->BU != '' ? $val->BU->BU : '',
                // "Plant Name" => isset($val->Plant_Name->plant_name) && $val->Plant_Name->plant_name != '' ? $val->Plant_Name->plant_name : '',
                // "Product" => isset($val->product->product) && $val->product->product != '' ? $val->product->product : '',
                // "Sub Product" => isset($val->subproduct->sub_product) && $val->subproduct->sub_product != '' ? $val->subproduct->sub_product : '',
                // "Sub Sub Product" => isset($val->subsubproduct->sub_sub_product) && $val->subsubproduct->sub_sub_product != '' ? $val->subsubproduct->sub_sub_product : '',
                // "Company Name" => isset($val->Company_Name) && $val->Company_Name != '' ? $val->Company_Name : '',
                // "Color" => isset($val->Colour) && $val->Colour != '' ? $val->Colour : '',
                // "Size" => isset($val->Size) && $val->Size != '' ? $val->Size : '',
                // "Category" => isset($val->category->category) && $val->category->category != '' ? $val->category->category : '',
                // "Lable" => isset($val->Lable) && $val->Lable != '' ? $val->Lable : '',
                "Finished Good(FG)" => isset($val->Raw_Material->matname) && $val->Raw_Material->matname != '' ? $val->Raw_Material->matname : '',
                "HSN Code" => isset($val->HSN_Code) && $val->HSN_Code != '' ? $val->HSN_Code : '',
                "UOM" => isset($val->UOM) && $val->UOM != '' ? $val->UOM : '',
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
            //return $d;
        }

        $file = "Finished_Good_data.csv";
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
