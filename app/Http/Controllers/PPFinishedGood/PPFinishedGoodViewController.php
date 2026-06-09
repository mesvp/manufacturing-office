<?php

namespace App\Http\Controllers\PPFinishedGood;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\Plant\{Master_Manufacturing_unit, Master_category};
use App\Models\Master\{Master_Plant_Machinery,Prj_Subproject,Prj_Project};
use App\Models\PPFinishedGood\{PPFinishedGood, PPFinishedGood_data, PPFinishedGood_Approve};
use App\Models\FactoryCreater\{Factory_Organisation,prj_organisation,unitname,gst, Factory_Uom, Factory_Product, Factory_Sub_Product, Factory_Sub_Sub_Product,Factory_Address_Detail,Factory_Plant_Machinery};
use App\Models\{CheckBox, Admin, Forwarded_Data};
use App\Models\MaterialManagement\{MaterialManagement_Add_Material};
use App\Models\ProductCategories\{ProductCategories_Add_Product};
use Session;

class PPFinishedGoodViewController extends Controller
{
    public function PPFinishedGoodList(Request $request)
    {
        $EXT = Session::get('EXT');

        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));

        if (isset($EXT[7]['inputer'])) {
            $query = PPFinishedGood::orderBy('id', 'DESC');
        } else {
            $query = PPFinishedGood::where('status', 0)->orderBy('id', 'DESC');
        }

        if ($fromdate && $todate) {
            $query->whereBetween('created_at', [$fromdate, $todate]);
        }

        $Organizations = '';
        if ($request->has('Organization') && $request->input('Organization') != '') {
            $Organizations = $request->input('Organization');
            if ($Organizations !== 'all') {
                $query->whereHas('Data', function ($subquery) use ($Organizations) {
                    $subquery->where('Organization', $Organizations);
                });
            }
        }

        $ManufacturingUnits = '';
        if ($request->has('Manufacturing_Unit') && $request->input('Manufacturing_Unit') != '') {
            $ManufacturingUnits = $request->input('Manufacturing_Unit');
            if ($ManufacturingUnits !== 'all') {
                $query->whereHas('Data', function ($subquery) use ($ManufacturingUnits) {
                    $subquery->where('Manufacturing_Unit', $ManufacturingUnits);
                });
            }
        }

        $PlantNames = '';
        if ($request->has('Plant_Name') && $request->input('Plant_Name') != '') {
            $PlantNames = $request->input('Plant_Name');
            if ($PlantNames !== 'all') {
                $query->whereHas('Data', function ($subquery) use ($PlantNames) {
                    $subquery->where('Plant_Name', $PlantNames);
                });
            }
        }

        $Categoryss = '';
        if ($request->has('Category') && $request->input('Category') != '') {
            $Categoryss = $request->input('Category');
            if ($Categoryss !== 'all') {
                $query->whereHas('Data', function ($subquery) use ($Categoryss) {
                    $subquery->where('Category', $Categoryss);
                });
            }
        }

        $Productss = '';
        if ($request->has('Product') && $request->input('Product') != '') {
            $Productss = $request->input('Product');
            if ($Productss !== 'all') {
                $query->whereHas('Data', function ($subquery) use ($Productss) {
                    $subquery->where('Product', $Productss);
                });
            }
        }

        $ForPrimarys = '';
        if ($request->has('For_Primary') && $request->input('For_Primary') != '') {
            $ForPrimarys = $request->input('For_Primary');
            if ($ForPrimarys !== 'all') {
                $query->whereHas('Data', function ($subquery) use ($ForPrimarys) {
                    $subquery->where('For_Primary', $ForPrimarys);
                });
            }
        }

        $QTYs = '';
        if ($request->has('QTY') && $request->input('QTY') != '') {
            $QTYs = $request->input('QTY');
            if ($QTYs !== 'all') {
                $query->whereHas('Data', function ($subquery) use ($QTYs) {
                    $subquery->where('QTY', $QTYs);
                });
            }
        }

        $RawMaterials = '';
        if ($request->has('Raw_Material') && $request->input('Raw_Material') != '') {
            $RawMaterials = $request->input('Raw_Material');
            if ($RawMaterials !== 'all') {
                $query->whereHas('Data', function ($subquery) use ($RawMaterials) {
                    $subquery->where('Raw_Material', $RawMaterials);
                });
            }
        }

        $HSNCodes = '';
        if ($request->has('HSN_Code') && $request->input('HSN_Code') != '') {
            $HSNCodes = $request->input('HSN_Code');
            if ($HSNCodes !== 'all') {
                $query->whereHas('Data', function ($subquery) use ($HSNCodes) {
                    $subquery->where('HSN_Code', $HSNCodes);
                });
            }
        }

        $UOMss = '';
        if ($request->has('UOM') && $request->input('UOM') != '') {
            $UOMss = $request->input('UOM');
            if ($UOMss !== 'all') {
                $query->whereHas('Data', function ($subquery) use ($UOMss) {
                    $subquery->where('UOM', $UOMss);
                });
            }
        }

        $PerDays = '';
        if ($request->has('Per_Day') && $request->input('Per_Day') != '') {
            $PerDays = $request->input('Per_Day');
            if ($PerDays !== 'all') {
                $query->whereHas('Data', function ($subquery) use ($PerDays) {
                    $subquery->where('Per_Day', $PerDays);
                });
            }
        }

        $PerShifts = '';
        if ($request->has('Per_Shift') && $request->input('Per_Shift') != '') {
            $PerShifts = $request->input('Per_Shift');
            if ($PerShifts !== 'all') {
                $query->whereHas('Data', function ($subquery) use ($PerShifts) {
                    $subquery->where('Per_Shift', $PerShifts);
                });
            }
        }

        $PP = $query->get();

        $PP_arr = [];
        $approved = [];
        $REJECT = [];
        $RECHECK = [];
        $OBJECT = [];
        $HOLD = [];
        $pending = [];
        foreach ($PP as $val) {
            //$val->data = PPFinishedGood_data::where('PPFinishedGood_id', $val->id)->first();
             $val->data = PPFinishedGood_data::select('ppfinishedgood_data.*','prj_project.pname','prj_subproject.spname','prj_organisation.organisation')
                    ->leftJoin('prj_organisation','ppfinishedgood_data.Organization','=','prj_organisation.id')
                    ->leftJoin('prj_project','ppfinishedgood_data.Manufacturing_Unit','=','prj_project.id')
                    ->leftJoin('prj_subproject','ppfinishedgood_data.Plant_name','=','prj_subproject.id')
                    ->where('PPFinishedGood_id', $val->id)
                    ->first();
            if (isset($val->data)) {
                if ($val->Forward_Status != 1) {
                    $val->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="7" AND step="' . $val->Approve_Step . '")')->get();
                } else {
                    $val->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data` WHERE DataID="' . $val->id . '" AND DepartmentID=7 AND status=0)')->get();
                }
                $val->user = Admin::find($val->userID);
                $val->Organization = Factory_Organisation::find($val->data->Organization);
                $val->Manufacturing_Unit = Master_Manufacturing_unit::find($val->data->Manufacturing_Unit);
                $val->plant_name = Master_Plant_Machinery::find($val->data->Plant_name);
                $val->category = Master_category::find($val->data->category);
                $val->Product = Factory_Product::find($val->data->Product);
                
                $val->Raw_Material = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                ->where('materialmanagement_add_material.id',$val->data->Raw_Material)->first();
                                //find($val->data->Raw_Material);
                $val->UOM = Factory_Uom::find($val->data->UOM);
                $val->HoldStatus = PPFinishedGood_Approve::where('PPFinishedGood_id', $val->id)->where('action', 'HOLD')->where('status', 1)->where('userID', auth()->user()->id)->count();
            }

            $PP_arr[] = $val;

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

        $Organization = prj_organisation::all();
        $Manufacturing_unit =  unitname::select('prj_project.*')
                            ->where('ptype','Corporate')
                            ->get();
        $Plant_Name = Prj_Subproject::all();
        $Category = Master_category::all();
        $Product = Factory_Product::all();
        $UOM = Factory_Uom::all();

        $ForDropdown = PPFinishedGood::orderBy('id', 'DESC')->get();
        $ForDropdown_arr = array();
        foreach ($ForDropdown as $val) {
            $val->data = PPFinishedGood_data::where('PPFinishedGood_id', $val->id)->first();
            if (isset($val->data)) {
                $val->user = Admin::find($val->userID);
                $val->Organization = Factory_Organisation::find($val->data->Organization);
                $val->Manufacturing_Unit = Master_Manufacturing_unit::find($val->data->Manufacturing_Unit);
                $val->plant_name = Master_Plant_Machinery::find($val->data->Plant_name);
                $val->category = Master_category::find($val->data->category);
                $val->Product = Factory_Product::find($val->data->Product);
                $val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name')
                                ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                                ->where('materialmanagement_add_material.id',$val->data->Raw_Material)->first();
                //find($val->data->Raw_Material);
            //    $val->Raw_Material = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
            //     ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
            //     ->where('materialmanagement_add_material.id',$val->data->Raw_Material)->first();
                //$val->UOM = Factory_Uom::find($val->data->UOM);
            }
            $ForDropdown_arr[] = $val;
        }

        return view('PPFinishedGood/PPFinishedGoodList', ['PP_data' => $PP_arr, 'approved' => $approved, 'REJECT' => $REJECT, 'RECHECK' => $RECHECK, 'OBJECT' => $OBJECT, 'HOLD' => $HOLD, 'pending' => $pending, 'DropdownData' => $ForDropdown_arr, 'fromdate' => $fromdate, 'todate' => $dateto, 'Organization' => $Organization, 'Manufacturing_unit' => $Manufacturing_unit, 'Plant_Name' => $Plant_Name, 'Category' => $Category, 'Product' => $Product, 'UOM' => $UOM, 'Organizations' => $Organizations, 'ManufacturingUnits' => $ManufacturingUnits, 'PlantNames' => $PlantNames, 'Categoryss' => $Categoryss, 'Productss' => $Productss, 'UOMss' => $UOMss, 'ForPrimarys' => $ForPrimarys, 'QTYs' => $QTYs, 'PerDays' => $PerDays, 'PerShifts' => $PerShifts, 'RawMaterials' => $RawMaterials, 'HSNCodes' => $HSNCodes]);
    }

    public function AddPPFinishedGood($id = null)
    {
        $Organization = prj_organisation::all();
        //$Manufacturing_Unit = Master_Manufacturing_unit::all();
        $Manufacturing_Unit = Factory_Address_Detail::select('prj_project.*')
        ->leftJoin('prj_project','factory_address_details.name_of_unit','=','prj_project.id')
        ->where('Approve_status','APPROVE')
        ->groupBy('prj_project.pname')
        ->get();
        $UOM = Factory_Uom::all();
        $Plant_Name = Prj_Subproject::all();
        $category = Master_category::all();
        $Product = Factory_Product::all();
        $Sub_Product = Factory_Sub_Product::all();
        $Sub_Sub_Product = Factory_Sub_Sub_Product::all();
        $product_data = ProductCategories_Add_Product::where('Approve_status', 'APPROVE')->get();
        $Raw_Material = [];
        foreach ($product_data as $Val) {
            if (isset($Val->Raw_Material)) {
                $Val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name')
                                ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                                ->where('materialmanagement_add_material.id',$Val->Raw_Material)
                                ->first();
                $Raw_Material[] = $Val;
            }
        }
        $edit = PPFinishedGood::find($id);
        $pp = array();
        $pp_count = 0;
        if (isset($id)) {
            $pp = PPFinishedGood_data::select('ppfinishedgood_data.*','prj_organisation.organisation')
                 ->leftJoin('prj_organisation','ppfinishedgood_data.Organization','=','prj_organisation.id')
                 ->where('PPFinishedGood_id', $id)
                 ->get();
            $pp_count += $pp->count();
        }

        return view('PPFinishedGood/PPFinishedGood', ['edit' => $edit, 'pp' => $pp, 'Manufacturing_Unit' => $Manufacturing_Unit, 'Organization' => $Organization, 'pp_count' => $pp_count, 'UOM' => $UOM, 'Plant_Name' => $Plant_Name, 'category' => $category, 'Product' => $Product, 'Sub_Product' => $Sub_Product, 'Sub_Sub_Product' => $Sub_Sub_Product, 'Raw_Material' => $Raw_Material]);
    }

    public function PPFinishedGood_View($id, $type)
    {
        $appro = PPFinishedGood_Approve::where('PPFinishedGood_id', $id)->get();
        $approves = [];
        foreach ($appro as $val) {
            $val->user = Admin::find($val->userID);
            array_push($approves, $val);
        }

        $Organization = prj_organisation::all();
        $Manufacturing_Unit = prj_project::all();
        $UOM = Factory_Uom::all();
        $Plant_Name = Prj_Subproject::all();
        $category = Master_category::all();
        $Product = Factory_Product::all();
        $Sub_Product = Factory_Sub_Product::all();
        $Sub_Sub_Product = Factory_Sub_Sub_Product::all();
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

        return view('PPFinishedGood/PPFinishedGoodView', ['edit' => $edit, 'pp' => $pp, 'Manufacturing_Unit' => $Manufacturing_Unit, 'Organization' => $Organization, 'pp_count' => $pp_count, 'UOM' => $UOM, 'Plant_Name' => $Plant_Name, 'category' => $category, 'Product' => $Product, 'Sub_Product' => $Sub_Product, 'Sub_Sub_Product' => $Sub_Sub_Product, 'nextID' => $nextID, 'approves' => $approves, 'Raw_Material' => $Raw_Material]);
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
        PPFinishedGood::find($id)->delete();

        PPFinishedGood_data::where('PPFinishedGood_id', $id)->delete();

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
        $Alldata = PPFinishedGood::orderBy('id', 'DESC')->get();
        $Alldata_arr = [];
        foreach ($Alldata as $val) {
            $val->data = PPFinishedGood_data::where('PPFinishedGood_id', $val->id)->first();
            if (isset($val->data)) {
                if ($val->Forward_Status != 1) {
                    $val->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="7" AND step="' . $val->Approve_Step . '")')->get();
                } else {
                    $val->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data` WHERE DataID="' . $val->id . '" AND DepartmentID=7 AND status=0)')->get();
                }
                $val->user = Admin::find($val->userID);
                $val->Organization = prj_organisation::find($val->data->Organization);
                $val->Manufacturing_Unit = prj_project::find($val->data->Manufacturing_Unit);
                $val->plant_name = Prj_Subproject::find($val->data->Plant_name);
                $val->category = Master_category::find($val->data->category);
                $val->Product = Factory_Product::find($val->data->Product);
                $val->Raw_Material = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                ->where('materialmanagement_add_material.id',$val->data->Raw_Material)->first();
                $val->UOM = Factory_Uom::find($val->data->UOM);
            }

            $Alldata_arr[] = $val;
        }

        $Checkbox = CheckBox::where('userID', auth()->user()->id)->where('tableID', 13)->get();

        $Checkbox_Arr = [];
        foreach ($Checkbox as $val) {
            $valuee = $val->CheckBox;
            array_push($Checkbox_Arr, $valuee);
        }

        $d = [];
        foreach ($Alldata_arr as $key => $val) {
            $val->data = PPFinishedGood_data::where('PPFinishedGood_id', $val->id)->first();
            if (isset($val->data)) {
                $rowData = [
                    "SL. No." => $key + 1,
                    "Creator Name" => isset($val->user->fullname) && $val->user->fullname != '' ? $val->user->fullname : '',
                    "Date & Time" => isset($val->created_at) && $val->created_at != '' ? date('d-m-Y H:i:s A', strtotime($val->created_at)) : '',
                    "Planing Batch No" => isset($val->Planing_Batch_No) && $val->Planing_Batch_No != '' ? $val->Planing_Batch_No : '',
                    "Organization" => isset($val->Organization->organisation) && $val->Organization->organisation != '' ? $val->Organization->organisation : '',
                    "Manufacturing Unit" => isset($val->Manufacturing_Unit->pname) && $val->Manufacturing_Unit->pname != '' ? $val->Manufacturing_Unit->pname : '',
                    "Plant Name" => isset($val->plant_name->spname) && $val->plant_name->spname != '' ? $val->plant_name->spname : '',
                    // "Category" => isset($val->category->category) && $val->category->category != '' ? $val->category->category : '',
                    // "Product" => isset($val->Product->product) && $val->Product->product != '' ? $val->Product->product : '',
                    "For Primary" => isset($val->data->For_Primary) && $val->data->For_Primary != '' ? $val->data->For_Primary : '',
                    "QTY" => isset($val->data->QTY) && $val->data->QTY != '' ? $val->data->QTY : '',
                    "Raw Material" => isset($val->Raw_Material->matname) && $val->Raw_Material->matname!= '' ? $val->Raw_Material->matname: '',
                    "Hsn Code" => isset($val->data->HSN_Code) && $val->data->HSN_Code != '' ? $val->data->HSN_Code : '',
                    "UOM" => isset($val->data->UOM) && $val->data->UOM != '' ? $val->data->UOM : '',
                    "Per Day" => isset($val->data->Per_Day) && $val->data->Per_Day != '' ? $val->data->Per_Day : '',
                    "Per Shift" => isset($val->data->Per_Shift) && $val->data->Per_Shift != '' ? $val->data->Per_Shift : '',
                    "Status" => ($val->Approve_status == 'APPROVE') ? 'APPROVED' : (($val->Approve_status == 'REJECT') ? 'REJECTED' : (($val->Approve_status == 'RECHECK') ? 'RECHECK' : (($val->Approve_status == 'OBJECT') ? 'OBJECT' : (($val->Approve_status == 'HOLD') ? 'HOLD' :
                        'Pending')))),
                    "Pending With" => (($val->Approve_status === 'FORWARD' && isset($val->status) && $val->status != 1) || ($val->Approve_status == '' && isset($val->status) && $val->status != 1)) ?
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
            }

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
        //return $d;

        $file = "PPFinishedGood_data.csv";
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
