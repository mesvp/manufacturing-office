<?php

namespace App\Http\Controllers\RawMaterial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\Plant\{Master_Manufacturing_unit};
use App\Models\Master\{Master_Material_data};
use App\Models\RawMaterial\{RawMaterial_stock, RawMaterial, RawMaterial_data, RawMaterial_approve};
use App\Models\FactoryCreater\{Factory_Organisation, Factory_Uom};
use App\Models\Master\RawMaterial\{Master_Godown_Name, Master_OB, Master_Rack_No, Master_Sub_Rack_No, Master_Bin_No, Master_Sub_Bin_No};
use App\Models\{CheckBox, Admin};
use App\Models\MaterialManagement\{MaterialManagement_Add_Material};
use Session;

class RawMaterialViewController extends Controller
{
    public function RawMaterialList(Request $request)
    {
        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));

        $EXT = Session::get('EXT');

        if (isset($EXT[6]['inputer'])) {
            $query = RawMaterial_stock::orderBy('id', 'DESC');
        } else {
            $query = RawMaterial_stock::where('status', 0)->orderBy('id', 'DESC');
        }

        if ($fromdate && $todate) {
            $query->whereBetween('created_at', [$fromdate, $todate]);
        }

        $Organizationss = '';
        if ($request->has('Organization') && $request->input('Organization') != '') {
            $Organizationss = $request->input('Organization');
            if ($Organizationss !== 'all') {
                $query->whereHas('RawMaterial', function ($subquery) use ($Organizationss) {
                    $subquery->where('Organization', $Organizationss);
                });
            }
        }

        $ManufacturingUnitss = '';
        if ($request->has('Manufacturing_Unit') && $request->input('Manufacturing_Unit') != '') {
            $ManufacturingUnitss = $request->input('Manufacturing_Unit');
            if ($ManufacturingUnitss !== 'all') {
                $query->whereHas('RawMaterial', function ($subquery) use ($ManufacturingUnitss) {
                    $subquery->where('Manufacturing_Unit', $ManufacturingUnitss);
                });
            }
        }

        $GodownNamess = '';
        if ($request->has('Godown_Name') && $request->input('Godown_Name') != '') {
            $GodownNamess = $request->input('Godown_Name');
            if ($GodownNamess !== 'all') {
                $query->whereHas('RawMaterial', function ($subquery) use ($GodownNamess) {
                    $subquery->where('Godown_Name', $GodownNamess);
                });
            }
        }

        $RawMaterialss = '';
        if ($request->has('Raw_Material') && $request->input('Raw_Material') != '') {
            $RawMaterialss = $request->input('Raw_Material');
            if ($RawMaterialss !== 'all') {
                $rawMaterialIds = RawMaterial_data::where('Raw_Material', $RawMaterialss)
                    ->pluck('RawMaterial_id');

                $rawMaterial = RawMaterial::whereIn('id', $rawMaterialIds)
                    ->pluck('RawMaterial_stock_id');

                $query->whereIn('id', $rawMaterial);
            }
        }

        $HSNCodes = '';
        if ($request->has('HSN_Code') && $request->input('HSN_Code') != '') {
            $HSNCodes = $request->input('HSN_Code');
            if ($HSNCodes !== 'all') {
                $rawMaterialIds = RawMaterial_data::where('HSN_Code', $HSNCodes)
                    ->pluck('RawMaterial_id');

                $rawMaterial = RawMaterial::whereIn('id', $rawMaterialIds)
                    ->pluck('RawMaterial_stock_id');

                $query->whereIn('id', $rawMaterial);
            }
        }

        $OBss = '';
        if ($request->has('OB') && $request->input('OB') != '') {
            $OBss = $request->input('OB');
            if ($OBss !== 'all') {
                $rawMaterialIds = RawMaterial_data::where('OB', $OBss)
                    ->pluck('RawMaterial_id');

                $rawMaterial = RawMaterial::whereIn('id', $rawMaterialIds)
                    ->pluck('RawMaterial_stock_id');

                $query->whereIn('id', $rawMaterial);
            }
        }

        $UOMss = '';
        if ($request->has('UOM') && $request->input('UOM') != '') {
            $UOMss = $request->input('UOM');
            if ($UOMss !== 'all') {
                $rawMaterialIds = RawMaterial_data::where('UOM', $UOMss)
                    ->pluck('RawMaterial_id');

                $rawMaterial = RawMaterial::whereIn('id', $rawMaterialIds)
                    ->pluck('RawMaterial_stock_id');

                $query->whereIn('id', $rawMaterial);
            }
        }

        $ReceivedQTYs = '';
        if ($request->has('Received_QTY') && $request->input('Received_QTY') != '') {
            $ReceivedQTYs = $request->input('Received_QTY');
            if ($ReceivedQTYs !== 'all') {
                $rawMaterialIds = RawMaterial_data::where('Received_QTY', $ReceivedQTYs)
                    ->pluck('RawMaterial_id');

                $rawMaterial = RawMaterial::whereIn('id', $rawMaterialIds)
                    ->pluck('RawMaterial_stock_id');

                $query->whereIn('id', $rawMaterial);
            }
        }

        $BalanceStocks = '';
        if ($request->has('Balance_Stock') && $request->input('Balance_Stock') != '') {
            $BalanceStocks = $request->input('Balance_Stock');
            if ($BalanceStocks !== 'all') {
                $rawMaterialIds = RawMaterial_data::where('Balance_Stock', $BalanceStocks)
                    ->pluck('RawMaterial_id');

                $rawMaterial = RawMaterial::whereIn('id', $rawMaterialIds)
                    ->pluck('RawMaterial_stock_id');

                $query->whereIn('id', $rawMaterial);
            }
        }

        $rowStock = $query->get();

        $rowStock_arr = [];
        $approved = [];
        $REJECT = [];
        $RECHECK = [];
        $OBJECT = [];
        $HOLD = [];
        $pending = [];
        foreach ($rowStock as $val) {
            $val->raw = RawMaterial::where('RawMaterial_stock_id', $val->id)->first();
            if (isset($val->raw)) {
                if ($val->Forward_Status != 1) {
                    $val->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="6" AND step="' . $val->Approve_Step . '")')->get();
                } else {
                    $val->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data` WHERE DataID="' . $val->id . '" AND DepartmentID=6 AND status=0)')->get();
                }
                $val->user = Admin::find($val->userID);
                $val->rawss = RawMaterial_data::where('RawMaterial_id', $val->raw->id)->first();
                $val->Organization = Factory_Organisation::find($val->raw->Organization);
                $val->Manufacturing_Unit = Master_Manufacturing_unit::find($val->raw->Manufacturing_Unit);
                $val->Godown_Name = Master_Godown_Name::find($val->raw->Godown_name);
                if ($val->rawss != '') {
                    //$val->Raw_Material = MaterialManagement_Add_Material::find($val->rawss->Raw_Material);
                     $val->Raw_Material = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                                    ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                                    ->where('materialmanagement_add_material.id',$val->rawss->Raw_Material)->first();
                    $val->OB = Master_OB::find($val->rawss->OB);
                    $val->UOM = Factory_Uom::find($val->rawss->UOM);
                }
            }
            $val->HoldStatus = RawMaterial_approve::where('RawMaterial_stock__id', $val->id)->where('action', 'HOLD')->where('status', 1)->where('userID', auth()->user()->id)->count();

            $rowStock_arr[] = $val;

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
        $ManufacturingUnit = Master_Manufacturing_unit::all();
        $GodownName = Master_Godown_Name::all();
        //$RawMaterial = MaterialManagement_Add_Material::where('Approve_status', 'APPROVE')->get();
        $RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                     ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                     ->get();
        $OB = Master_OB::all();
        $UOM = Factory_Uom::all();

        $dropdown = RawMaterial_stock::orderBy('id', 'DESC')->get();

        $dropdown_arr = array();
        foreach ($dropdown as $val) {
            $val->raw = RawMaterial::where('RawMaterial_stock_id', $val->id)->first();
            if (isset($val->raw)) {
                $val->rawss = RawMaterial_data::where('RawMaterial_id', $val->raw->id)->first();
                $val->Organization = Factory_Organisation::find($val->raw->Organization);
                $val->Manufacturing_Unit = Master_Manufacturing_unit::find($val->raw->Manufacturing_Unit);
                $val->Godown_Name = Master_Godown_Name::find($val->raw->Godown_name);
                if ($val->rawss != '') {
                    $val->OB = Master_OB::find($val->rawss->OB);
                    $val->UOM = Factory_Uom::find($val->rawss->UOM);
                }
            }

            array_push($dropdown_arr, $val);
        }

        return view('RawMaterial/RawMaterialList', ['rowStock' => $rowStock_arr, 'approved' => $approved, 'REJECT' => $REJECT, 'RECHECK' => $RECHECK, 'OBJECT' => $OBJECT, 'HOLD' => $HOLD, 'pending' => $pending, 'fromdate' => $fromdate, 'todate' => $dateto, 'Organization' => $Organization, 'ManufacturingUnit' => $ManufacturingUnit, 'GodownName' => $GodownName, 'RawMaterial' => $RawMaterial, 'OB' => $OB, 'UOM' => $UOM, 'DropdownData' => $dropdown_arr, 'Organizationss' => $Organizationss, 'ManufacturingUnits' => $ManufacturingUnitss, 'GodownNames' => $GodownNamess, 'RawMaterialss' => $RawMaterialss, 'HSNCodes' => $HSNCodes, 'OBss' => $OBss, 'UOMss' => $UOMss, 'ReceivedQTYs' => $ReceivedQTYs, 'BalanceStocks' => $BalanceStocks]);
    }
    public function getmateraildeatsilsajax($id)
    {
        $materialdetails=MaterialManagement_Add_Material::select('materialmanagement_add_material.UOM as uom')->where('id',$id)->get();
        return response()->json($materialdetails);
    }

    public function AddRawMaterial($id = null)
    {
        $Organization = Factory_Organisation::all();
        $Manufacturing_Unit = Master_Manufacturing_unit::all();
        $Godown_Name = Master_Godown_Name::all();
        // $matname=Master_Material_data::select('prj_material.material_name')
        // ->where('prj_material.id',$edit->Material_Name)->first();
        $Raw_Material = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name')
                        ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                        ->where(['Approve_status' => 'APPROVE', 'Used_Status_RM' => 0])->get();
        $Raw_Materialdata = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name')
        ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
        ->where(['Approve_status' => 'APPROVE'])->get();
        $OB = Master_OB::all();
        $Rack_No = Master_Rack_No::all();
        $Sub_Rack_No = Master_Sub_Rack_No::all();
        $Bin_No = Master_Bin_No::all();
        $Sub_Bin_No = Master_Sub_Bin_No::all();
        $UOM = Factory_Uom::all();
        $edit = RawMaterial_stock::find($id);
        $raw = array();
        $raw_arr = array();
        $raw_count = 0;
        $raw_data_count = 0;
        if (isset($edit->id) && $edit->id != '') {
            $raw = RawMaterial::where('RawMaterial_stock_id', $edit->id)->get();
            $raw_count += $raw->count();
            foreach ($raw as $val) {
                $val->raw_data = RawMaterial_data::where('RawMaterial_id', $val->id)->get();
                $raw_data_count += $val->raw_data->count();

                array_push($raw_arr, $val);
            }
        }

        return view('RawMaterial/RawMaterial', ['edit' => $edit, 'raw' => $raw_arr, 'Manufacturing_Unit' => $Manufacturing_Unit, 'Organization' => $Organization, 'raw_count' => $raw_count, 'raw_data_count' => $raw_data_count, 'Godown_Name' => $Godown_Name, 'Raw_Material' => $Raw_Material,'Raw_Materialdata'=>$Raw_Materialdata, 'OB' => $OB, 'Rack_No' => $Rack_No, 'Sub_Rack_No' => $Sub_Rack_No, 'Bin_No' => $Bin_No, 'Sub_Bin_No' => $Sub_Bin_No, 'UOM' => $UOM]);
    }

    public function RawMaterial_View($id, $type)
    {
        $appro = RawMaterial_approve::where('RawMaterial_stock__id', $id)->get();
        $approves = [];
        foreach ($appro as $val) {
            $val->user = Admin::find($val->userID);
            array_push($approves, $val);
        }

        $Organization = Factory_Organisation::all();
        $Manufacturing_Unit = Master_Manufacturing_unit::all();
        $Godown_Name = Master_Godown_Name::all();
        $Raw_Material = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                     ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                     ->where('Approve_status', 'APPROVE')->get();
        $OB = Master_OB::all();
        $Rack_No = Master_Rack_No::all();
        $Sub_Rack_No = Master_Sub_Rack_No::all();
        $Bin_No = Master_Bin_No::all();
        $Sub_Bin_No = Master_Sub_Bin_No::all();
        $UOM = Factory_Uom::all();
        $edit = RawMaterial_stock::find($id);
        $raw = array();
        $raw_arr = array();
        if (isset($edit->id) && $edit->id != '') {
            $raw = RawMaterial::where('RawMaterial_stock_id', $edit->id)->get();
            foreach ($raw as $val) {
                $val->raw_data = RawMaterial_data::where('RawMaterial_id', $val->id)->get();

                array_push($raw_arr, $val);
            }
        }

        $nextID = $this->next($id, $type);

        return view('RawMaterial/RawMaterial_View', ['edit' => $edit, 'raw' => $raw_arr, 'Manufacturing_Unit' => $Manufacturing_Unit, 'Organization' => $Organization, 'Godown_Name' => $Godown_Name, 'Raw_Material' => $Raw_Material, 'OB' => $OB, 'Rack_No' => $Rack_No, 'Sub_Rack_No' => $Sub_Rack_No, 'Bin_No' => $Bin_No, 'Sub_Bin_No' => $Sub_Bin_No, 'UOM' => $UOM, 'approves' => $approves, 'nextID' => $nextID]);
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
        $raw = RawMaterial_stock::find($id);

        $rawdata = RawMaterial::where('RawMaterial_stock_id', $raw->id)->get();

        foreach ($rawdata as $val) {
            RawMaterial_data::where('RawMaterial_id', $val->id)->delete();
        }

        RawMaterial::where('RawMaterial_stock_id', $raw->id)->delete();

        $raw->delete();

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

    public function DownloadFilteredData(Request $request)
    {
        //$Alldata = RawMaterial_stock::orderBy('id', 'DESC')->get();
        $Alldata = RawMaterial_stock::select('rawmaterial_stock.*','prj_material.material_name as matname','rawmaterial_data.*')
                       ->leftJoin('rawmaterial_data','rawmaterial_stock.id','=','rawmaterial_data.RawMaterial_id')
                       ->leftJoin('prj_material','rawmaterial_data.Raw_Material','=','prj_material.id')
                       ->get();
        $Alldata_arr = array();
        foreach ($Alldata as $val) {
            $val->raw = RawMaterial::where('RawMaterial_stock_id', $val->id)->first();
            if (isset($val->raw)) {
                if ($val->Forward_Status != 1) {
                    $val->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="6" AND step="' . $val->Approve_Step . '")')->get();
                } else {
                    $val->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data` WHERE DataID="' . $val->id . '" AND DepartmentID=6 AND status=0)')->get();
                }
                $val->user = Admin::find($val->userID);
                $val->rawss = RawMaterial_data::where('RawMaterial_id', $val->raw->id)->first();
                $val->Organization = Factory_Organisation::find($val->raw->Organization);
                $val->Manufacturing_Unit = Master_Manufacturing_unit::find($val->raw->Manufacturing_Unit);
                $val->Godown_Name = Master_Godown_Name::find($val->raw->Godown_name);
                if ($val->rawss != '') {
                    //$val->Raw_Material = MaterialManagement_Add_Material::find($val->rawss->Raw_Material);
                    $val->Raw_Material = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                    ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                    ->where('materialmanagement_add_material.id',$val->rawss->Raw_Material)->first();

                    $val->OB = Master_OB::find($val->rawss->OB);
                    $val->UOM = Factory_Uom::find($val->rawss->UOM);
                }
            }

            array_push($Alldata_arr, $val);
        }
        $Checkbox = CheckBox::where('userID', auth()->user()->id)->where('tableID', 8)->get();
        //$Checkbox = [];

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
                "Date" => isset($val->raw->date) && $val->raw->date != '' ? $val->raw->date : '',
                "Raw Material" => isset($val->Raw_Material->matname) && $val->Raw_Material->matname != '' ? $val->Raw_Material->matname : '',
                "HSN Code" => isset($val->rawss->HSN_Code) && $val->rawss->HSN_Code != '' ? $val->rawss->HSN_Code : '',
                "UOM" => isset($val->rawss->UOM) && $val->rawss->UOM != '' ? $val->rawss->UOM : '',
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

        $file = "Raw_Material_data.csv";
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

    function MaterialData($id)
    {
        $data = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','materialmanagement_add_material.UOM as uomval')
            ->where('id',$id)
            ->first();
            //find($id);

        return response()->json(['success' => true, 'data' => $data]);
    }
}
