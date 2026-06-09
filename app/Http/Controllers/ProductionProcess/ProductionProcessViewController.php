<?php

namespace App\Http\Controllers\ProductionProcess;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductionProcess\{Production_Process, Production_Process_Machine, Production_Process_Stage, Production_Process_Stage_Data, Production_Process_Approve};
use App\Models\FactoryCreater\{Factory_Product, Factory_Sub_Product, Factory_Sub_Sub_Product, Factory_Uom};
use App\Models\{CheckBox, Admin};
use App\Models\Master\Plant\{Master_Company_Name, Master_Machine_Code, Master_Machine_Name, Master_Make_Model};
use App\Models\MaterialManagement\{MaterialManagement_Add_Material};
use App\Models\BOM\{BOM, BOM_Material};
use Session;

class ProductionProcessViewController extends Controller
{
    public function ProductionProcessList(Request $request)
    {
        $EXT = Session::get('EXT');

        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));

        if (isset($EXT[19]['inputer'])) {
            $query = Production_Process::orderBy('id', 'DESC');
        } else {
            $query = Production_Process::where('status', 0)->orderBy('id', 'DESC');
        }

        if ($fromdate && $todate) {
            $query->whereBetween('created_at', [$fromdate, $todate]);
        }

        $Productss = '';
        if ($request->has('Product') && $request->input('Product') != '') {
            $Productss = $request->input('Product');
            if ($Productss !== 'all') {
                $query->where('Product', $Productss);
            }
        }

        $SubProductss = '';
        if ($request->has('Sub_Product') && $request->input('Sub_Product') != '') {
            $SubProductss = $request->input('Sub_Product');
            if ($SubProductss !== 'all') {
                $query->where('Sub_Product', $SubProductss);
            }
        }

        $SubSubProductss = '';
        if ($request->has('Sub_Sub_Product') && $request->input('Sub_Sub_Product') != '') {
            $SubSubProductss = $request->input('Sub_Sub_Product');
            if ($SubSubProductss !== 'all') {
                $query->where('Sub_Sub_Product', $SubSubProductss);
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

        $UOMSS = '';
        if ($request->has('UOM') && $request->input('UOM') != '') {
            $UOMSS = $request->input('UOM');
            if ($UOMSS !== 'all') {
                $query->where('UOM', $UOMSS);
            }
        }

        $ProductionProcess = $query->get();

        $Production_arr = [];
        $approved = [];
        $REJECT = [];
        $RECHECK = [];
        $OBJECT = [];
        $HOLD = [];
        $pending = [];
        foreach ($ProductionProcess as $val) {
            if ($val->Forward_Status != 1) {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="19" AND step="' . $val->Approve_Step . '")')->get();
            } else {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `Forwarded_Data` WHERE DataID="' . $val->id . '" AND DepartmentID=19 AND status=0)')->get();
            }
            $val->user = Admin::find($val->userID);
            $val->Product = Factory_Product::find($val->Product);
            $val->Sub_Product = Factory_Sub_Product::find($val->Sub_Product);
            $val->Sub_Sub_Product = Factory_Sub_Sub_Product::find($val->Sub_Sub_Product);
            //$val->Raw_Material = MaterialManagement_Add_Material::find($val->Raw_Material);
            $val->Raw_Material = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name')
            ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
            ->where('materialmanagement_add_material.id',$val->Raw_Material)
            ->first();
            //$val->UOM = Factory_Uom::find($val->UOM);
            $val->HoldStatus = Production_Process_Approve::where('Production_Process_id', $val->id)->where('action', 'HOLD')->where('status', 1)->where('userID', auth()->user()->id)->count();

            $Production_arr[] = $val;

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
        $UOM = Factory_Uom::all();
        $BOM_DATA = BOM::where('Approve_status', 'APPROVE')->get();
        $Raw_Material = [];
        foreach ($BOM_DATA as $Val) {
            if (isset($Val->Raw_Material_FG)) {
                //$Val->RawMaterial = MaterialManagement_Add_Material::find($Val->Raw_Material_FG);
                $Val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name')
                ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                ->where('materialmanagement_add_material.id',$Val->Raw_Material_FG)
                ->first();
                $Raw_Material[$Val->Raw_Material_FG] = $Val;
            }
        }
        $Filtered_Array = array_values($Raw_Material);

        $Dropdown = Production_Process::orderBy('id', 'DESC')->get();

        return view('ProductionProcess/ProductionProcessList', ['ProductionProcess' => $Production_arr, 'approved' => $approved, 'REJECT' => $REJECT, 'RECHECK' => $RECHECK, 'OBJECT' => $OBJECT, 'HOLD' => $HOLD, 'pending' => $pending, 'fromdate' => $fromdate, 'todate' => $dateto, 'Dropdown' => $Dropdown, 'Productss' => $Productss, 'SubProductss' => $SubProductss, 'SubSubProductss' => $SubSubProductss, 'UOMSS' => $UOMSS, 'Product' => $Product, 'Sub_Product' => $Sub_Product, 'Sub_Sub_Product' => $Sub_Sub_Product, 'UOM' => $UOM, 'Raw_Material' => $Filtered_Array, 'RawMaterialss' => $RawMaterialss, 'HSNCodes' => $HSNCodes]);
    }

    public function AddProductionProcess($id = null)
    {
        $Product = Factory_Product::all();
        $Sub_Product = Factory_Sub_Product::all();
        $Sub_Sub_Product = Factory_Sub_Sub_Product::all();
        //$UOM = Factory_Uom::all();
        $BOM_DATA = BOM::where('Approve_status', 'APPROVE')->get();
        $Raw_Material = [];
        foreach ($BOM_DATA as $Val) {
            if (isset($Val->Raw_Material_FG)) {
                //$Val->RawMaterial = MaterialManagement_Add_Material::find($Val->Raw_Material_FG);
                $Val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name')
                                ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                                ->where('materialmanagement_add_material.id',$Val->Raw_Material_FG)
                                ->first();
                $Raw_Material[$Val->Raw_Material_FG] = $Val;
            }
        }
        $Filtered_Array = array_values($Raw_Material);

        $edit = Production_Process::find($id);

        return view('ProductionProcess/ProductionProcess', ['edit' => $edit, 'Product' => $Product, 'Sub_Product' => $Sub_Product, 'Sub_Sub_Product' => $Sub_Sub_Product, 'Raw_Material' => $Filtered_Array]);
    }

    public function ProductStage($id)
    {
        $Product = Factory_Product::all();
        $Sub_Product = Factory_Sub_Product::all();
        $Sub_Sub_Product = Factory_Sub_Sub_Product::all();
        $UOM = Factory_Uom::all();
        $Machine_Name = Master_Machine_Name::all();
        $Machine_Code = Master_Machine_Code::all();
        $Machine_Company = Master_Company_Name::all();
        $Make_Model = Master_Make_Model::all();
        $BOM_DATA = BOM::where('Approve_status', 'APPROVE')->get();
        $Raw_Material = [];
        foreach ($BOM_DATA as $Val) {
            if (isset($Val->Raw_Material_FG)) {
                //$Val->RawMaterial = MaterialManagement_Add_Material::find($Val->Raw_Material_FG);
                $Val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name')
                                ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                                ->where('materialmanagement_add_material.id',$Val->Raw_Material_FG)
                                ->first();
                $Raw_Material[] = $Val;
            }
        }

        $edit = Production_Process::find($id);

        $BOM = BOM::where(['Raw_Material_FG' => $edit->Raw_Material, 'Approve_status' => 'APPROVE'])->orderBy('id', 'DESC')->first();
        $Materials = [];
        if (isset($BOM) && $BOM != '') {
            $MaterialData = BOM_Material::where('BOM_ID', $BOM->id)->get();
            foreach ($MaterialData as $Val) {
                if (isset($Val->Material)) {
                    //$Val->RawMaterial = MaterialManagement_Add_Material::find($Val->Material);
                    $Val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name')
                            ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                            ->where('materialmanagement_add_material.id',$Val->Material)->first();
                    $Materials[] = $Val;
                }
            }
        }

        $All_Data = [];
        $Stage_count = 0;
        $Stage_data_count = 0;
        $Machines_count = 0;

        if (isset($edit->id) && $edit->id != '') {
            $Stage = Production_Process_Stage::where('Production_Process_Id', $edit->id)->get();
            $Stage_count = $Stage->count();

            if ($Stage_count > 0) {
                foreach ($Stage as $val) {
                    $val->Stage_data = Production_Process_Stage_Data::where('Production_Process_Stage_Id', $val->id)->get();
                    $Stage_data_count += $val->Stage_data->count();

                    if ($val->Stage_data->count() > 0) {
                        foreach ($val->Stage_data as $val1) {
                            $val1->Machine = Production_Process_Machine::where('Production_Process_Stage_Data_Id', $val1->id)->get();
                            $Machines_count += $val1->Machine->count();
                        }
                    }

                    $All_Data[] = $val;
                }
            }
        }

        return view('ProductionProcess/ProductionStages', ['edit' => $edit, 'All_Data' => $All_Data, 'Product' => $Product, 'Sub_Product' => $Sub_Product, 'Sub_Sub_Product' => $Sub_Sub_Product, 'Stage_count' => $Stage_count, 'Stage_data_count' => $Stage_data_count, 'Machines_count' => $Machines_count, 'Raw_Material' => $Raw_Material, 'Materials' => $Materials, 'UOM' => $UOM, 'Machine_Name' => $Machine_Name, 'Machine_Code' => $Machine_Code, 'Machine_Company' => $Machine_Company, 'Make_Model' => $Make_Model]);
    }

    public function ProductionProcess_View($id, $type)
    {
        $appro = Production_Process_Approve::where('Production_Process_id', $id)->get();
        $approves = [];
        foreach ($appro as $val) {
            $val->user = Admin::find($val->userID);
            array_push($approves, $val);
        }

        $Product = Factory_Product::all();
        $Sub_Product = Factory_Sub_Product::all();
        $Sub_Sub_Product = Factory_Sub_Sub_Product::all();
        $UOM = Factory_Uom::all();
        $Machine_Name = Master_Machine_Name::all();
        $Machine_Code = Master_Machine_Code::all();
        $Machine_Company = Master_Company_Name::all();
        $Make_Model = Master_Make_Model::all();
        $BOM_DATA = BOM::where('Approve_status', 'APPROVE')->get();
        $Raw_Material = [];
        foreach ($BOM_DATA as $Val) {
            if (isset($Val->Raw_Material_FG)) {
                //$Val->RawMaterial = MaterialManagement_Add_Material::find($Val->Raw_Material_FG);
                $Val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name')
                ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                ->where('materialmanagement_add_material.id',$Val->Raw_Material_FG)
                ->first();
                $Raw_Material[] = $Val;
            }
        }

        $edit = Production_Process::find($id);

        $BOM = BOM::where(['Raw_Material_FG' => $edit->Raw_Material, 'Approve_status' => 'APPROVE'])->orderBy('id', 'DESC')->first();
        $Materials = [];
        if (isset($BOM) && $BOM != '') {
            $MaterialData = BOM_Material::where('BOM_ID', $BOM->id)->get();
            foreach ($MaterialData as $Val) {
                if (isset($Val->Material)) {
                    //$Val->RawMaterial = MaterialManagement_Add_Material::find($Val->Material);
                    $Val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name')
                        ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                        ->where('materialmanagement_add_material.id',$Val->Material)
                        ->first();
                    $Materials[] = $Val;
                }
            }
        }

        $All_Data = [];
        $Stage_count = 0;
        $Stage_data_count = 0;
        $Machines_count = 0;

        if (isset($edit->id) && $edit->id != '') {
            $Stage = Production_Process_Stage::where('Production_Process_Id', $edit->id)->get();
            $Stage_count = $Stage->count();

            if ($Stage_count > 0) {
                foreach ($Stage as $val) {
                    $val->Stage_data = Production_Process_Stage_Data::where('Production_Process_Stage_Id', $val->id)->get();
                    $Stage_data_count += $val->Stage_data->count();

                    if ($val->Stage_data->count() > 0) {
                        foreach ($val->Stage_data as $val1) {
                            $val1->Machine = Production_Process_Machine::where('Production_Process_Stage_Data_Id', $val1->id)->get();
                            $Machines_count += $val1->Machine->count();
                        }
                        //dd($val1->Machine);
                    }

                    $All_Data[] = $val;
                }
            }
        }

        $nextID = $this->next($id, $type);

        return view('ProductionProcess/ProductionProcess_View', ['edit' => $edit, 'All_Data' => $All_Data, 'Product' => $Product, 'Sub_Product' => $Sub_Product, 'Sub_Sub_Product' => $Sub_Sub_Product, 'UOM' => $UOM, 'Raw_Material' => $Raw_Material, 'Materials' => $Materials, 'approves' => $approves, 'nextID' => $nextID, 'Machine_Name' => $Machine_Name, 'Machine_Code' => $Machine_Code, 'Machine_Company' => $Machine_Company, 'Make_Model' => $Make_Model]);
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
        $data = Production_Process::find($id);

        if (!$data) {
            return back()->with('error', 'Record not found');
        }

        $stages = Production_Process_Stage::where('Production_Process_Id', $data->id)->get();

        foreach ($stages as $stage) {
            $stageData = Production_Process_Stage_Data::where('Production_Process_Stage_Id', $stage->id)->get();

            foreach ($stageData as $dataItem) {
                Production_Process_Machine::where('Production_Process_Stage_Data_Id', $dataItem->id)->delete();
            }

            $stage->delete();
        }

        $data->delete();

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
        $Production = Production_Process::orderBy('id', 'DESC')->get();

        $Production_arr = array();
        foreach ($Production as $val) {
            if ($val->Forward_Status != 1) {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="19" AND step="' . $val->Approve_Step . '")')->get();
            } else {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `Forwarded_Data` WHERE DataID="' . $val->id . '" AND DepartmentID=19 AND status=0)')->get();
            }
            $val->user = Admin::find($val->userID);
            $val->Product = Factory_Product::find($val->Product);
            $val->Sub_Product = Factory_Sub_Product::find($val->Sub_Product);
            $val->Sub_Sub_Product = Factory_Sub_Sub_Product::find($val->Sub_Sub_Product);
            //$val->Raw_Material = MaterialManagement_Add_Material::find($val->Raw_Material);
            $val->Raw_Material = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name')
            ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
            ->where('materialmanagement_add_material.id',$val->Raw_Material)
            ->first();
            //$val->UOM = Factory_Uom::find($val->UOM);
            $val->HoldStatus = Production_Process_Approve::where('Production_Process_id', $val->id)->where('action', 'HOLD')->where('status', 1)->where('userID', auth()->user()->id)->count();

            array_push($Production_arr, $val);
        }

        $Checkbox = CheckBox::where('userID', auth()->user()->id)->where('tableID', 15)->get();

        $Checkbox_Arr = [];
        foreach ($Checkbox as $val) {
            $valuee = $val->CheckBox;
            array_push($Checkbox_Arr, $valuee);
        }

        $d = [];
        foreach ($Production_arr as $key => $val) {
            $rowData = [
                "SL. No." => $key + 1,
                "Creator Name" => isset($val->user->fullname) && $val->user->fullname != '' ? $val->user->fullname : '',
                "Date & Time" => isset($val->created_at) && $val->created_at != '' ? date('d-m-Y H:i:s A', strtotime($val->created_at)) : '',
                // "Product" => isset($val->Product->product) && $val->Product->product != '' ? $val->Product->product : '',
                // "Sub Product" => isset($val->Sub_Product->sub_product) && $val->Sub_Product->sub_product != '' ? $val->Sub_Product->sub_product : '',
                // "Sub Sub Product" => isset($val->Sub_Sub_Product->sub_sub_product) && $val->Sub_Sub_Product->sub_sub_product != '' ? $val->Sub_Sub_Product->sub_sub_product : '',
                "Status" => ($val->Approve_status == 'APPROVE') ? 'APPROVED' : (($val->Approve_status == 'REJECT') ? 'REJECTED' : (($val->Approve_status == 'RECHECK') ? 'RECHECK' : (($val->Approve_status == 'OBJECT') ? 'OBJECT' : (($val->Approve_status == 'HOLD') ? 'HOLD' :
                    'Pending')))),
                "Raw Material(FG)" => isset($val->Raw_Material->material_name) && $val->Raw_Material->material_name != '' ? $val->Raw_Material->material_name : '',
                "HSN Code" => isset($val->HSN_Code) && $val->HSN_Code != '' ? $val->HSN_Code : '',
                "UOM" => isset($val->UOM) && $val->UOM != '' ? $val->UOM : '',
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

        $file = "Production_Process_data.csv";
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
