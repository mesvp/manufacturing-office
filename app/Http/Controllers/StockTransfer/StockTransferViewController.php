<?php

namespace App\Http\Controllers\StockTransfer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StoreRequistion\{Store_Requistion, Store_Requistion_Material, Store_Requistion_approve};
use App\Models\Master\Plant\{Master_Manufacturing_unit, Master_Customer_Name, Master_BU};
use App\Models\FactoryCreater\{Factory_Organisation, Factory_Uom,prj_organisation,unitname,Factory_Address_Detail};
use App\Models\Master\{Master_Plant_Machinery,Prj_Subproject,Prj_Project,Module_Bsns_Unit,Prj_Inventory,Pur_Address,Prj_Supllier,Crmwtp_Product_Detail};
use App\Models\{Admin, CheckBox};
use App\Models\MaterialManagement\{MaterialManagement_Add_Material};
use App\Models\Master\RawMaterial\{Master_Godown_Name, Master_Raw_Material,Master_Raw_Material_Detail, Master_OB, Master_Received_QTY, Master_Rack_No, Master_Sub_Rack_No, Master_Bin_No, Master_Sub_Bin_No, Master_Gate_Pass_Required, Master_HSN_Code, Master_Material_Name};
use App\Models\BOM\{BOM, BOM_Material};
use App\Models\StoreTransfer\{Mrn_Stock_Transfer,Mrn_Stock_Transfer_Detail,Mrn_Stock_Transfer_Approve};
use Session;

class StockTransferViewController extends Controller
{
    public function TransferRequestList(Request $request)
    {
        $EXT = Session::get('EXT');

        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));

        if (isset($EXT[23]['inputer'])) {
            //$query = Master_Raw_Material_Detail::where('through','=','received through MRN')->orderBy('id', 'DESC');
             $query = Master_Raw_Material_Detail::select('master_raw_material_details.*','mrn_stock_transfer.userID','mrn_stock_transfer.Forward_Status','mrn_stock_transfer.Approve_status','mrn_stock_transfer.Approve_Step','mrn_stock_transfer.status','master_raw_material.created_by as creationID')
            ->leftJoin('mrn_stock_transfer','master_raw_material_details.id','=','mrn_stock_transfer.tr_id')
            ->leftJoin('master_raw_material','master_raw_material_details.raw_mat_id','=','master_raw_material.id')
            ->where('master_raw_material_details.entry', 1)
            ->whereIn('master_raw_material_details.Material', function ($q) {
                $q->select('materialmanagement_add_material.id')
                ->from('materialmanagement_add_material')
                ->join('crmwtp_product_details', 'materialmanagement_add_material.Material_Name', '=', 'crmwtp_product_details.matrl_id')
                ->where('materialmanagement_add_material.Approve_status', 'APPROVE') 
                ->groupBy('materialmanagement_add_material.id');
            })
            ->orderBy('id', 'DESC');

        } else {
            //$query = Master_Raw_Material_Detail::where('status', 0)->orderBy('id', 'DESC');
            //$query = Master_Raw_Material_Detail::where('through','=','received through MRN')->orderBy('id', 'DESC');
             $query = Master_Raw_Material_Detail::select('master_raw_material_details.*','mrn_stock_transfer.userID','mrn_stock_transfer.Forward_Status','mrn_stock_transfer.Approve_status','mrn_stock_transfer.Approve_Step','mrn_stock_transfer.status','master_raw_material.created_by as creationID')
            ->leftJoin('mrn_stock_transfer','master_raw_material_details.id','=','mrn_stock_transfer.tr_id')
            ->leftJoin('master_raw_material','master_raw_material_details.raw_mat_id','=','master_raw_material.id')
            ->where('master_raw_material_details.entry', 1)
            ->whereIn('master_raw_material_details.Material', function ($q) {
                $q->select('materialmanagement_add_material.id')
                ->from('materialmanagement_add_material')
                ->join('crmwtp_product_details', 'materialmanagement_add_material.Material_Name', '=', 'crmwtp_product_details.matrl_id')
                ->where('materialmanagement_add_material.Approve_status', 'APPROVE') 
                ->groupBy('materialmanagement_add_material.id');
            })
            ->orderBy('id', 'DESC');

        }

        if ($fromdate && $dateto) {
            $query->whereBetween('master_raw_material_details.Mrn_Date', [$fromdate, $dateto]);
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
                $query->where('master_raw_material_details.Material', $RawMaterialss);
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
        //dd($query->toSql(), $query->getBindings());
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
                $val->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="23" AND step="' . $val->Approve_Step . '")')->get();
            } else {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data` WHERE DataID="' . $val->id . '" AND DepartmentID=23 AND status=0)')->get();
            }
            $val->user = Admin::find($val->userID);
            $val->username = Admin::find($val->creationID);
            $val->Organization_Name = prj_organisation::find($val->Organization_Name);
            $val->Manufacturing_Unit = prj_project::find($val->Manufacturing_Unit);
            $val->Plant_Name = Prj_Subproject::find($val->Plant_Name);
            $val->Godown_Name = Prj_Inventory::find($val->Godown_Name);
            $val->HoldStatus = Mrn_Stock_Transfer_Approve::where('tr_id', $val->id)->where('action', 'HOLD')->where('status', 1)->where('userID', auth()->user()->id)->count();
            //$val->Raw_Material = MaterialManagement_Add_Material::find($val->Raw_Material);
            $val->Material = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                    ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                    ->where('materialmanagement_add_material.id',$val->Material)
                    ->groupBy('materialmanagement_add_material.id')
                    ->first();

            // Check transfer button visibility conditions
            $val->showTransferButton = true;
            
            // 1. Check own table if quantity is in -ve then do not show the transfer button
            if ($val->Quantity < 0) {
                $val->showTransferButton = false;
            }
            
            // 2 & 3. Check master table by fkey (raw_mat_id) for quantity conditions
            if ($val->showTransferButton && $val->raw_mat_id) {
                $masterRawMaterial = Master_Raw_Material::where('id', $val->raw_mat_id)
                    ->where('Organization', $val->Organization_Name->id ?? $val->Organization)
                    ->where('Godown_Name', $val->Godown_Name->id ?? $val->Godown_Name)
                    ->first();
                
                if ($masterRawMaterial) {
                    // 2. Check master table if the quantity is -ve then also hide the transfer button
                    if ($masterRawMaterial->Quantity < 0) {
                        $val->showTransferButton = false;
                    }
                    
                    // 3. Check master table quantity if smaller than master_raw_material_details quantity then also hide the button
                    if ($masterRawMaterial->Quantity < $val->Quantity) {
                        $val->showTransferButton = false;
                    }
                }
            }

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
   
        $Filtered_Array = array_values($Raw_Material);

        $Dropdown = Master_Raw_Material_Detail::orderBy('id', 'DESC')->get();
        $Dropdown_arr = array();
        foreach ($Dropdown as $val) {
            $val->user = Admin::find($val->userID);
            $val->Organization_Name = Factory_Organisation::find($val->Organization_Name);
            $val->Manufacturing_Unit = Master_Manufacturing_unit::find($val->Manufacturing_Unit);
            $val->Plant_Name = Master_Plant_Machinery::find($val->Plant_Name);
            $val->Godown_Name = Master_Godown_Name::find($val->Godown_Name);
            // return $val->Raw_Material = MaterialManagement_Add_Material::find($val->Material);
            $val->Raw_Material = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
            ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
            ->where('materialmanagement_add_material.id',$val->Material)
            ->first();
            //$val->UOM = Factory_Uom::find($val->UOM);

            array_push($Dropdown_arr, $val);
        }
        //return $Dropdown_arr;

        return view('StockTransfer/TransferRequestList', ['store' => $store_arr, 'DropdownData' => $Dropdown_arr, 'approved' => $approved, 'REJECT' => $REJECT, 'RECHECK' => $RECHECK, 'OBJECT' => $OBJECT, 'HOLD' => $HOLD, 'pending' => $pending, 'fromdate' => $fromdate, 'todate' => $dateto, 'Organization' => $Organization, 'Manufacturing_Unit' => $Manufacturing_Unit, 'Plant_Name' => $Plant_Name, 'UOM' => $UOM, 'Godown_Name' => $Godown_Name, 'OrganizationName' => $OrganizationName, 'ManufacturingUnits' => $ManufacturingUnits, 'PlantNames' => $PlantNames, 'RawMaterial' => $Filtered_Array, 'Godown_Names' => $Godown_Names, 'RawMaterialss' => $RawMaterialss, 'HSNCodes' => $HSNCodes, 'UOMss' => $UOMss]);
    }

    public function AddStoreTransfer($id = null)
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
        $SupplierData=Prj_Supllier::all();
        //$Godown_Name = Prj_Inventory::select('prj_inventory.*')->where('godown_type','209')->get();
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

        $edit=Master_Raw_Material_Detail::select('master_raw_material_details.*','prj_material.material_name as matname','prj_material.uom','prj_material.id as material_id')
                ->leftJoin('materialmanagement_add_material','master_raw_material_details.Material','=','materialmanagement_add_material.id')
                ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                ->where('master_raw_material_details.id','=',$id)->first();
        $slnocheck = Crmwtp_Product_Detail::where('matrl_id', $edit->material_id)
                ->where(function($q){
                    $q->where('sl_no_req', 'yes');
                })
                ->first();

        $stockdata=Mrn_Stock_Transfer::where('tr_id',$id)->first();
        $stockdetails=Mrn_Stock_Transfer_Detail::where('tr_id',$id)->get();
        
        // Prioritize values from mrn_stock_transfer table if they exist
        if ($stockdata) {
            $edit->Organization_Name = $stockdata->Organization_Name ?? $edit->Organization;
            $edit->Godown_Name = $stockdata->Godown_Name ?? $edit->Godown_Name;
        }

        
        $Materials = array();
        if (isset($edit->id) && $edit->id != '') {
            $Materials = Store_Requistion_Material::where('Store_Requistion_id', $edit->id)->get();
        }
       

        return view('StockTransfer/StoreTransfer', ['edit' => $edit, 'Organization_Name' => $Organization_Name, 'Manufacturing_Unit' => $Manufacturing_Unit, 'Plant_Name' => $Plant_Name, 'Raw_Material' => $Filtered_Array, 'UOM' => $UOM,'SupplierData'=>$SupplierData, 'Godown_Name' => $Godown_Name, 'Materials' => $Materials,'Materialdetails'=>$Materialdetails,'stockdata'=>$stockdata,'stockdetails'=>$stockdetails,'slnocheck'=>$slnocheck]);
    }

    public function StoreTransfer_View($id, $type)
    {
        $appro = Mrn_Stock_Transfer_Approve::where('tr_id', $id)->get();
        $approves = [];
        foreach ($appro as $val) {
            $val->user = Admin::find($val->userID);
            array_push($approves, $val);
        }

        $Organization_Name = prj_organisation::all();
        $Manufacturing_Unit = prj_project::all();
        $Plant_Name = Prj_Subproject::all();
        $UOM = Factory_Uom::all();
        //$Godown_Name = Prj_Inventory::select('prj_inventory.*')->where('godown_type','209')->get();
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
         $edit=Master_Raw_Material_Detail::select('master_raw_material_details.*','prj_material.material_name as matname','prj_material.uom','prj_material.id as material_id','mrn_stock_transfer.userID as userid','mrn_stock_transfer.Approve_status as aprvsts','mrn_stock_transfer.Organization_Name as transfer_org_name','mrn_stock_transfer.Godown_Name as transfer_godown_name')
                ->leftJoin('materialmanagement_add_material','master_raw_material_details.Material','=','materialmanagement_add_material.id')
                ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                ->leftJoin('mrn_stock_transfer','master_raw_material_details.id','=','mrn_stock_transfer.tr_id')
                ->where('master_raw_material_details.id','=',$id)->first();
        
        // Prioritize values from mrn_stock_transfer table
        if ($edit) {
            $edit->Organization_Name = $edit->transfer_org_name ?? $edit->Organization;
            $edit->Godown_Name = $edit->transfer_godown_name ?? $edit->Godown_Name;
        }
        // $edit=Mrn_Stock_Transfer::where('tr_id',$id)->first();
        $Materials = array();
        if (isset($edit->id) && $edit->id != '') {
            $Materials = Mrn_Stock_Transfer_Detail::where('tr_id', $edit->id)->get();
            $remarks=Mrn_Stock_Transfer::select('remarks')->where('tr_id',$edit->id)->first();
        }
        //pre($Materials,true,true);
        $nextID = $this->next($id, $type);

        return view('StockTransfer/StoreTransfer_View', ['edit' => $edit, 'Organization_Name' => $Organization_Name, 'Manufacturing_Unit' => $Manufacturing_Unit, 'Plant_Name' => $Plant_Name, 'Raw_Material' => $Filtered_Array, 'UOM' => $UOM, 'Godown_Name' => $Godown_Name, 'Materials' => $Materials, 'approves' => $approves, 'nextID' => $nextID,'remarks'=>$remarks]);
    }

    function next($id, $type)
    {
        $datra = Session::get('nexdata');
        if (isset($datra) && isset($datra[$type])) {
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
        $EXT = Session::get('EXT');

        // Build the main query
        $query = Master_Raw_Material_Detail::select(
            'master_raw_material_details.*',
            'mrn_stock_transfer.userID',
            'mrn_stock_transfer.Forward_Status',
            'mrn_stock_transfer.Approve_status',
            'mrn_stock_transfer.Approve_Step',
            'mrn_stock_transfer.status',
            'master_raw_material.created_by as creationID'

        )
        ->leftJoin('mrn_stock_transfer', 'master_raw_material_details.id', '=', 'mrn_stock_transfer.tr_id')
        ->leftJoin('master_raw_material','master_raw_material_details.raw_mat_id','=','master_raw_material.id')
        ->where('master_raw_material_details.entry', 1)
        ->whereIn('master_raw_material_details.Material', function ($q) {
            $q->select('materialmanagement_add_material.id')
            ->from('materialmanagement_add_material')
            ->join('crmwtp_product_details', 'materialmanagement_add_material.Material_Name', '=', 'crmwtp_product_details.matrl_id')
            ->groupBy('materialmanagement_add_material.id');
        })
        ->orderBy('id', 'DESC');

        // Apply date filters
        $fromdate = $request->input('from_date');
        $todate = $request->input('to_date');

        if (!empty($fromdate) && !empty($todate)) {
            $query->whereBetween('master_raw_material_details.Mrn_Date', [$fromdate, $todate]);
        }

        // Apply Material Filter
        $RawMaterialss = $request->input('Raw_Material', '');
        if (!empty($RawMaterialss) && $RawMaterialss !== 'all') {
            $query->where('master_raw_material_details.Material', $RawMaterialss);
        }

        $store = $query->get();

        // Get checkbox preferences
        $Checkbox = CheckBox::where('userID', auth()->user()->id)->where('tableID', 2310)->get();
        $Checkbox_Arr = $Checkbox->pluck('CheckBox')->toArray();

        // Define all possible columns with their labels
        $allColumns = [
            'SL. No.' => 'SL. No.',
            'Creater Name' => 'Creater Name',
            'Date & Time' => 'Date & Time',
            'Material' => 'Material',
            'UOM' => 'UOM',
            'Purchase Date' => 'Purchase Date',
            'Purchase Qty' => 'Purchase Qty',
            'Status' => 'Status',
            'Pending With' => 'Pending With',
            'Transfer Status' => 'Transfer Status',
        ];

        // Determine which columns to show
        $columnsToShow = empty($Checkbox_Arr) ? array_keys($allColumns) : $Checkbox_Arr;

        $exportData = [];
        
        foreach ($store as $key => $val) {
            // Set pending with based on status
            if ($val->Forward_Status != 1) {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="23" AND step="' . $val->Approve_Step . '")')->get();
            } else {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data` WHERE DataID="' . $val->id . '" AND DepartmentID=23 AND status=0)')->get();
            }

            // Load related data
            $val->user = Admin::find($val->userID);
            $val->username = Admin::find($val->creationID);
            $val->Organization_Name = prj_organisation::find($val->Organization_Name);
            $val->Manufacturing_Unit = prj_project::find($val->Manufacturing_Unit);
            $val->Plant_Name = Prj_Subproject::find($val->Plant_Name);
            $val->Godown_Name = Prj_Inventory::find($val->Godown_Name);
            $val->HoldStatus = Mrn_Stock_Transfer_Approve::where('tr_id', $val->id)
                ->where('action', 'HOLD')
                ->where('status', 1)
                ->where('userID', auth()->user()->id)
                ->count();

            // Get material details
            $val->Material = MaterialManagement_Add_Material::select(
                'materialmanagement_add_material.*',
                'prj_material.material_name as matname'
            )
            ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
            ->where('materialmanagement_add_material.id', $val->Material)
            ->groupBy('materialmanagement_add_material.id')
            ->first();

            // Determine transfer status
            $transfersts = ($val->trn_status == '0') ? "Not Transfer" : "Transfer";

            // Get pending with names
            $pendingWithNames = [];
            if ($val->PendingWith != null) {
                foreach ($val->PendingWith as $name) {
                    if (isset($name->fullname) && $name->fullname != '') {
                        $pendingWithNames[] = $name->fullname;
                    }
                }
            }

            // Determine pending with text
            $pendingWithText = '';
            if ($val->Approve_status === 'FORWARD' || ($val->Approve_status == '' && isset($val->status) && $val->status != 1)) {
                $pendingWithText = 'Pending With ' . implode(', ', $pendingWithNames);
            } elseif ($val->Approve_status === 'RECHECK' || $val->Approve_status === 'OBJECT') {
                $pendingWithText = (isset($val->user->fullname) && $val->user->fullname != '') ? 'Pending With ' . $val->user->fullname : '';
            }

            // Build row data with all possible columns
            $rowData = [
                "SL. No." => $key + 1,
                "Creater Name" => $val->username->fullname ?? '',
                "Date & Time" => $val->created_at ? date('d-m-Y H:i:s A', strtotime($val->created_at)) : '',
                "Material" => $val->Material->matname ?? '',
                "UOM" => $val->Material->UOM ?? '',
                "Purchase Date" => $val->Mrn_Date ?? '',
                "Purchase Qty" => $val->Quantity ?? '',
                "Status" => ($val->Approve_status == 'APPROVE') ? 'APPROVED' : (($val->Approve_status == 'REJECT') ? 'REJECTED' : (($val->Approve_status == 'RECHECK') ? 'RECHECK' : (($val->Approve_status == 'OBJECT') ? 'OBJECT' : (($val->Approve_status == 'HOLD') ? 'HOLD' : 'Pending')))),
                "Pending With" => $pendingWithText,
                "Transfer Status" => $transfersts,
            ];

            // Filter row data based on selected columns
            $filteredRow = [];
            foreach ($columnsToShow as $column) {
                if (array_key_exists($column, $rowData)) {
                    $filteredRow[$column] = $rowData[$column];
                }
            }

            $exportData[] = $filteredRow;
        }

        // Ensure we always have data to export, even if empty
        if (empty($exportData)) {
            // Create empty row with selected columns
            $emptyRow = [];
            foreach ($columnsToShow as $column) {
                $emptyRow[$column] = '';
            }
            $exportData[] = $emptyRow;
        }

        $file = "StoreTransfer_data.csv";
        $this->collectionExport($exportData, $file);
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
