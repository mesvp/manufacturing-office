<?php

namespace App\Http\Controllers\StoreRequistion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StoreRequistion\{Store_Requistion, Store_Requistion_Material, Store_Requistion_approve};
use App\Models\Master\Plant\{Master_Manufacturing_unit, Master_Customer_Name, Master_BU};
use App\Models\FactoryCreater\{Factory_Organisation, Factory_Uom,prj_organisation,unitname,Factory_Address_Detail};
use App\Models\Master\{Master_Plant_Machinery,Prj_Subproject,Prj_Project,Module_Bsns_Unit,Prj_Inventory,Pur_Address};
use App\Models\{Admin, CheckBox};
use App\Models\MaterialManagement\{MaterialManagement_Add_Material};
use App\Models\Master\RawMaterial\Master_Godown_Name;
use App\Models\BOM\{BOM, BOM_Material};
use Session;

class StoreRequistionViewController extends Controller
{
    public function StoreRequistionList(Request $request)
    {
        $EXT = Session::get('EXT');

        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));

        if (isset($EXT[15]['inputer'])) {
            $query = Store_Requistion::orderBy('id', 'DESC');
        } else {
            $query = Store_Requistion::where('status', 0)->orderBy('id', 'DESC');
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

        $Godown_Names = '';
        if ($request->has('Godown_Name') && $request->input('Godown_Name') != '') {
            $Godown_Names = $request->input('Godown_Name');
            if ($Godown_Names !== 'all') {
                $query->where('Godown_Name', $Godown_Names);
            }
        }

        $PlantNames = '';
        if ($request->has('Plant_Name') && $request->input('Plant_Name') != '') {
            $PlantNames = $request->input('Plant_Name');
            if ($PlantNames !== 'all') {
                $query->where('Plant_Name', $PlantNames);
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

        $store = $query->get();

        $store_arr = [];
        $approved = [];
        $REJECT = [];
        $RECHECK = [];
        $OBJECT = [];
        $HOLD = [];
        $pending = [];
        foreach ($store as  $val) {
            if ($val->Forward_Status != 1) {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="15" AND step="' . $val->Approve_Step . '")')->get();
            } else {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data` WHERE DataID="' . $val->id . '" AND DepartmentID=15 AND status=0)')->get();
            }
            $val->user = Admin::find($val->userID);
            $val->Organization_Name = prj_organisation::find($val->Organization_Name);
            $val->Manufacturing_Unit = prj_project::find($val->Manufacturing_Unit);
            $val->Plant_Name = Prj_Subproject::find($val->Plant_Name);
            $val->Godown_Name = Prj_Inventory::find($val->Godown_Name);
            $val->HoldStatus = Store_Requistion_approve::where('Store_Requistion_id', $val->id)->where('action', 'HOLD')->where('status', 1)->where('userID', auth()->user()->id)->count();
            //$val->Raw_Material = MaterialManagement_Add_Material::find($val->Raw_Material);
            $val->Raw_Material = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                    ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                    ->where('materialmanagement_add_material.id',$val->Raw_Material)
                    ->first();

            //$val->UOM = Factory_Uom::find($val->UOM);

            $store_arr[] = $val;

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
        $Manufacturing_Unit = prj_project::all();
        $Plant_Name = Prj_Subproject::all();
        $UOM = Factory_Uom::all();
        $Godown_Name = Prj_Inventory::all();
        $BOM_DATA = BOM::where('Approve_status', 'APPROVE')->get();
        $Raw_Material = [];
        foreach ($BOM_DATA as $Val) {
            if (isset($Val->Raw_Material_FG)) {
               // $Val->RawMaterial = MaterialManagement_Add_Material::find($Val->Raw_Material_FG);
                $Val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                    ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                    ->where('materialmanagement_add_material.id',$Val->Raw_Material_FG)
                    ->first();

                $Raw_Material[$Val->Raw_Material_FG] = $Val;
            }
        }
        $Filtered_Array = array_values($Raw_Material);

        $Dropdown = Store_Requistion::orderBy('id', 'DESC')->get();
        $Dropdown_arr = array();
        foreach ($Dropdown as $val) {
            $val->user = Admin::find($val->userID);
            $val->Organization_Name = Factory_Organisation::find($val->Organization_Name);
            $val->Manufacturing_Unit = Master_Manufacturing_unit::find($val->Manufacturing_Unit);
            $val->Plant_Name = Master_Plant_Machinery::find($val->Plant_Name);
            $val->Godown_Name = Master_Godown_Name::find($val->Godown_Name);
            $val->Raw_Material = MaterialManagement_Add_Material::find($val->Raw_Material);
            //$val->UOM = Factory_Uom::find($val->UOM);

            array_push($Dropdown_arr, $val);
        }

        return view('StoreRequistion/StoreRequistionList', ['store' => $store_arr, 'DropdownData' => $Dropdown_arr, 'approved' => $approved, 'REJECT' => $REJECT, 'RECHECK' => $RECHECK, 'OBJECT' => $OBJECT, 'HOLD' => $HOLD, 'pending' => $pending, 'fromdate' => $fromdate, 'todate' => $dateto, 'Organization' => $Organization, 'Manufacturing_Unit' => $Manufacturing_Unit, 'Plant_Name' => $Plant_Name, 'UOM' => $UOM, 'Godown_Name' => $Godown_Name, 'OrganizationName' => $OrganizationName, 'ManufacturingUnits' => $ManufacturingUnits, 'PlantNames' => $PlantNames, 'RawMaterial' => $Filtered_Array, 'Godown_Names' => $Godown_Names, 'RawMaterialss' => $RawMaterialss, 'HSNCodes' => $HSNCodes, 'UOMss' => $UOMss]);
    }

    public function AddStoreRequistion($id = null)
    {
        $Organization_Name = prj_organisation::all();
        //$Manufacturing_Unit = Master_Manufacturing_unit::all();
        $Manufacturing_Unit = Factory_Address_Detail::select('prj_project.*')
        ->leftJoin('prj_project','factory_address_details.name_of_unit','=','prj_project.id')
        ->where('Approve_status','APPROVE')
        ->groupBy('prj_project.pname')
        ->get();
        $Materialdetails=MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                        ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                        ->where('Approve_status','APPROVE')->get();
        $Plant_Name = Prj_Subproject::all();
        $UOM = Factory_Uom::all();
        $Godown_Name = Prj_Inventory::select('prj_inventory.*')->where('godown_type','69')->get();
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

        $edit = Store_Requistion::select('store_requistion.*','prj_organisation.organisation')
                ->leftJoin('prj_organisation','store_requistion.Organization_Name','=','prj_organisation.id')
                ->where('store_requistion.id',$id)
                ->first();
        //find($id);
        $Materials = array();
        if (isset($edit->id) && $edit->id != '') {
            $Materials = Store_Requistion_Material::where('Store_Requistion_id', $edit->id)->get();
        }
       

        return view('StoreRequistion/StoreRequistion', ['edit' => $edit, 'Organization_Name' => $Organization_Name, 'Manufacturing_Unit' => $Manufacturing_Unit, 'Plant_Name' => $Plant_Name, 'Raw_Material' => $Filtered_Array, 'UOM' => $UOM, 'Godown_Name' => $Godown_Name, 'Materials' => $Materials,'Materialdetails'=>$Materialdetails]);
    }

    public function StoreRequistion_View($id, $type)
    {
        $appro = Store_Requistion_approve::where('Store_Requistion_id', $id)->get();
        $approves = [];
        foreach ($appro as $val) {
            $val->user = Admin::find($val->userID);
            array_push($approves, $val);
        }

        $Organization_Name = prj_organisation::all();
        $Manufacturing_Unit = prj_project::all();
        $Plant_Name = Prj_Subproject::all();
        $UOM = Factory_Uom::all();
        $Godown_Name = Prj_Inventory::all();
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
        $edit = Store_Requistion::find($id);
        $Materials = array();
        if (isset($edit->id) && $edit->id != '') {
            $Materials = Store_Requistion_Material::where('Store_Requistion_id', $edit->id)->get();
        }
        //pre($Materials,true,true);
        $nextID = $this->next($id, $type);

        return view('StoreRequistion/StoreRequistion_View', ['edit' => $edit, 'Organization_Name' => $Organization_Name, 'Manufacturing_Unit' => $Manufacturing_Unit, 'Plant_Name' => $Plant_Name, 'Raw_Material' => $Filtered_Array, 'UOM' => $UOM, 'Godown_Name' => $Godown_Name, 'Materials' => $Materials, 'approves' => $approves, 'nextID' => $nextID]);
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
        Store_Requistion::find($id)->delete();

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
        $store = Store_Requistion::orderBy('id', 'DESC')->get();
        $store_arr = array();
        foreach ($store as  $val) {
            if ($val->Forward_Status != 1) {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="15" AND step="' . $val->Approve_Step . '")')->get();
            } else {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data` WHERE DataID="' . $val->id . '" AND DepartmentID=15 AND status=0)')->get();
            }
            $val->user = Admin::find($val->userID);
            $val->Organization_Name = prj_organisation::find($val->Organization_Name);
            $val->Manufacturing_Unit = prj_project::find($val->Manufacturing_Unit);
            $val->Plant_Name = Prj_Subproject::find($val->Plant_Name);
            $val->Godown_Name = Prj_Inventory::find($val->Godown_Name);
            $val->HoldStatus = Store_Requistion_approve::where('Store_Requistion_id', $val->id)->where('action', 'HOLD')->where('status', 1)->where('userID', auth()->user()->id)->count();
            //$val->Raw_Material = MaterialManagement_Add_Material::find($val->Raw_Material);
            $val->Raw_Material = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                    ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                    ->where('materialmanagement_add_material.id',$val->Raw_Material)
                    ->first();

            //$val->UOM = Factory_Uom::find($val->UOM);

            array_push($store_arr, $val);
        }

        $Checkbox = CheckBox::where('userID', auth()->user()->id)->where('tableID', 1010)->get();

        $Checkbox_Arr = [];
        foreach ($Checkbox as $val) {
            $valuee = $val->CheckBox;
            array_push($Checkbox_Arr, $valuee);
        }

        $d = [];
        foreach ($store_arr as $key => $val) {
            $rowData = [
                "SL. No." => $key + 1,
                "Creater Name" => isset($val->user->fullname) && $val->user->fullname != '' ? $val->user->fullname : '',
                "Date & Time" => isset($val->created_at) && $val->created_at != '' ? date('d-m-Y H:i:s A', strtotime($val->created_at)) : '',
                "Organization Name" => isset($val->Organization_Name->organisation) && $val->Organization_Name->organisation != '' ? $val->Organization_Name->organisation : '',
                "Manufacturing Unit" => isset($val->Manufacturing_Unit->pname) && $val->Manufacturing_Unit->pname != '' ? $val->Manufacturing_Unit->pname : '',
                "Plant Name" => isset($val->Plant_Name->spname) && $val->Plant_Name->spname != '' ? $val->Plant_Name->spname : '',
                "Godown Name" => isset($val->Godown_Name->inventory_name) && $val->Godown_Name->inventory_name != '' ? $val->Godown_Name->inventory_name : '',
                "Raw Material(FG)" => isset($val->Raw_Material->matname) && $val->Raw_Material->matname != '' ? $val->Raw_Material->matname : '',
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
        }

        $file = "StoreRequistion_data.csv";
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
