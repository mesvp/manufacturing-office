<?php

namespace App\Http\Controllers\MaterialManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaterialManagement\{MaterialManagement_Add_Material, Material_Management_approve};
use App\Models\Master\{Master_Material_data};
use App\Models\FactoryCreater\Factory_Uom;
use App\Models\Master\Plant\Master_Quality_Check;
use App\Models\Master\BOM\Master_Material;
use App\Models\Master\RawMaterial\{Master_Gate_Pass_Required, Master_HSN_Code};
use App\Models\{CheckBox, Admin, Forwarded_Data};
use Session;


class MaterialManagementViewController extends Controller
{
    public function MaterialList(Request $request)
    {
        try {
            // Increase memory limit for this operation
            ini_set('memory_limit', '512M');
            ini_set('max_execution_time', '300');
            
            $Department = Session::get('Department');
            $EXT = Session::get('EXT');
            $STEP = Session::get('STEP');

            $dateto = $request->input('to_date');
            $fromdate = $request->input('from_date');
            $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));

            if (isset($EXT[4]['inputer'])) {
                $query = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                           ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                           ->orderBy('materialmanagement_add_material.id', 'DESC');
            } else {
                $query = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                           ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                           ->where('materialmanagement_add_material.status', 0)
                           ->orderBy('materialmanagement_add_material.id', 'DESC');
            }

            if ($fromdate && $todate) {
                $query->whereBetween('materialmanagement_add_material.created_at', [$fromdate, $todate]);
            }

            $MaterialCode = '';
            if ($request->has('Material_Code') && $request->input('Material_Code') != '') {
                $MaterialCode = $request->input('Material_Code');
                if ($MaterialCode !== 'all') {
                    $query->where('materialmanagement_add_material.Material_Code', $MaterialCode);
                }
            }

            $MaterialName = '';
            if ($request->has('Material_Name') && $request->input('Material_Name') != '') {
                $MaterialName = $request->input('Material_Name');
                if ($MaterialName !== 'all') {
                    $query->where('materialmanagement_add_material.Material_Name', $MaterialName);
                }
            }

            $HSNCode = '';
            if ($request->has('HSN_Code') && $request->input('HSN_Code') != '') {
                $HSNCode = $request->input('HSN_Code');
                if ($HSNCode !== 'all') {
                    $query->where('materialmanagement_add_material.HSN_Code', $HSNCode);
                }
            }

            $UOMss = '';
            if ($request->has('UOM') && $request->input('UOM') != '') {
                $UOMss = $request->input('UOM');
                if ($UOMss !== 'all') {
                    $query->where('materialmanagement_add_material.UOM', $UOMss);
                }
            }

            $lpps = '';
            if ($request->has('last_purchase_price') && $request->input('last_purchase_price') != '') {
                $lpps = $request->input('last_purchase_price');
                if ($lpps !== 'all') {
                    $query->where('materialmanagement_add_material.last_purchase_price', $lpps);
                }
            }

            $MiniumOrderLevel = '';
            if ($request->has('Minium_Order_Level') && $request->input('Minium_Order_Level') != '') {
                $MiniumOrderLevel = $request->input('Minium_Order_Level');
                if ($MiniumOrderLevel !== 'all') {
                    $query->where('materialmanagement_add_material.Minium_Order_Level', $MiniumOrderLevel);
                }
            }

            $ReorderLevel = '';
            if ($request->has('Reorder_Level') && $request->input('Reorder_Level') != '') {
                $ReorderLevel = $request->input('Reorder_Level');
                if ($ReorderLevel !== 'all') {
                    $query->where('materialmanagement_add_material.Reorder_Level', $ReorderLevel);
                }
            }

            // Count total records for pagination
            $totalRecords = $query->count();
            
            // Use pagination showing ALL records on one page
            $materialManagment = $query->paginate($totalRecords);
            
            // Pre-load all related data to avoid N+1 query problem
            $userIds = $materialManagment->pluck('userID')->unique()->filter();
            $uomIds = $materialManagment->pluck('UOM')->unique()->filter();
            $materialIds = $materialManagment->pluck('Material_Name')->unique()->filter();
            $qualityCheckIds = $materialManagment->pluck('Quality_Check')->unique()->filter();
            $gatePassIds = $materialManagment->pluck('Gate_Pass')->unique()->filter();
            $materialMgmtIds = $materialManagment->pluck('id');
            
            // Load all related records in bulk (1 query per type instead of N queries)
            $users = Admin::whereIn('id', $userIds)->get()->keyBy('id');
            $uoms = Factory_Uom::whereIn('id', $uomIds)->get()->keyBy('id');
            $materialDetails = Master_Material_data::whereIn('id', $materialIds)->get()->keyBy('id');
            $qualityChecks = Master_Quality_Check::whereIn('id', $qualityCheckIds)->get()->keyBy('id');
            $gatePasses = Master_Gate_Pass_Required::whereIn('id', $gatePassIds)->get()->keyBy('id');
            $holdStatuses = Material_Management_approve::whereIn('Material_Management_id', $materialMgmtIds)
                ->where('action', 'HOLD')
                ->where('status', 1)
                ->where('userID', auth()->user()->id)
                ->get()
                ->groupBy('Material_Management_id');
            
        $materialManagment_arr = [];
        $approved = [];
        $REJECT = [];
        $RECHECK = [];
        $OBJECT = [];
        $HOLD = [];
        $pending = [];

        foreach ($materialManagment as $val) {
            // Use pre-loaded data instead of running queries
            if ($val->Forward_Status != 1) {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="4" AND step="' . $val->Approve_Step . '")')->get();
            } else {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data` WHERE DataID="' . $val->id . '" AND DepartmentID=4 AND status=0)')->get();
            }
            
            $val->user = $users->get($val->userID);
            $val->uomss = $uoms->get($val->UOM);
            $val->mtaerialdetails = $materialDetails->get($val->Material_Name);
            $val->qualityCheck = $qualityChecks->get($val->Quality_Check);
            $val->GatePassRequired = $gatePasses->get($val->Gate_Pass);
            $val->HoldStatus = isset($holdStatuses[$val->id]) ? $holdStatuses[$val->id]->count() : 0;

            $materialManagment_arr[] = $val;

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

        $uom = Factory_Uom::all();
        $Quality_Check = Master_Quality_Check::all();
        $Gate_Pass_Required = Master_Gate_Pass_Required::all();
        $HSN_Code = Master_HSN_Code::all();
        $Material_Name = Master_Material::all();

        //$ForDropdown = MaterialManagement_Add_Material::orderBy('id', 'DESC')->get();
        // Optimize: Limit dropdown data to prevent memory issues
        $ForDropdown = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                       ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                       ->limit(500)
                       ->get();
        
        // Pre-load dropdown related data
        $dropdownUomIds = $ForDropdown->pluck('UOM')->unique()->filter();
        $dropdownQualityIds = $ForDropdown->pluck('Quality_Check')->unique()->filter();
        $dropdownGatePassIds = $ForDropdown->pluck('Gate_Pass')->unique()->filter();
        
        $dropdownUoms = Factory_Uom::whereIn('id', $dropdownUomIds)->get()->keyBy('id');
        $dropdownQualityChecks = Master_Quality_Check::whereIn('id', $dropdownQualityIds)->get()->keyBy('id');
        $dropdownGatePasses = Master_Gate_Pass_Required::whereIn('id', $dropdownGatePassIds)->get()->keyBy('id');
        
        $ForDropdown_arr = array();
        foreach ($ForDropdown as $val) {
            $val->uomss = $dropdownUoms->get($val->UOM);
            $val->qualityCheck = $dropdownQualityChecks->get($val->Quality_Check);
            $val->GatePassRequired = $dropdownGatePasses->get($val->Gate_Pass);

            array_push($ForDropdown_arr, $val);
        }

        return view('MaterialManagement/MaterialList', [
            'materialManagment' => $materialManagment_arr, 
            'approved' => $approved, 
            'REJECT' => $REJECT, 
            'RECHECK' => $RECHECK, 
            'OBJECT' => $OBJECT, 
            'HOLD' => $HOLD, 
            'pending' => $pending, 
            'DropdownData' => $ForDropdown_arr, 
            'fromdate' => $fromdate, 
            'todate' => $dateto, 
            'uom' => $uom, 
            'Quality_Check' => $Quality_Check, 
            'Gate_Pass_Required' => $Gate_Pass_Required, 
            'MaterialCodes' => $MaterialCode, 
            'MaterialNames' => $MaterialName, 
            'UOMss' => $UOMss, 
            'lpps' => $lpps, 
            'MiniumOrderLevels' => $MiniumOrderLevel, 
            'ReorderLevels' => $ReorderLevel, 
            'HSNCodes' => $HSNCode,
            'pagination' => $materialManagment // Pass pagination object for links
        ]);
        
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('MaterialList Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Return error view or redirect with error message
            return redirect()->back()->with('error', 'An error occurred while loading materials. Please check if the prj_material table exists and all database connections are correct. Error: ' . $e->getMessage());
        }
    }

    public function AddMaterial($id = null)
    {
        $edit = MaterialManagement_Add_Material::find($id);
        $uom = Factory_Uom::all();
        // return $materials = Master_Material_data::select('prj_material.*','materialmanagement_add_material.Approve_status')
        //             ->leftJoin('materialmanagement_add_material','prj_material.id','=','materialmanagement_add_material.Material_Name')
        //              ->where('materialmanagement_add_material.Approve_status',null)
        //             // ->orWhere('materialmanagement_add_material.Approve_status','=','REJECT')
        //             // ->orWhere('materialmanagement_add_material.Approve_status','!=','APPROVE')
        //             // ->orWhere('prj_material.status','1')
        //             ->get();
         $materials=Master_Material_data::select('prj_material.*')->where('prj_material.status','1')->get();
        // $materialdata=array();
        // foreach($materials as $material){
        //     $materialdata=$material;

        // }
        $Quality_Check = Master_Quality_Check::all();
        $Gate_Pass_Required = Master_Gate_Pass_Required::all();

        return view('MaterialManagement/AddMaterial', ['edit' => $edit, 'uom' => $uom, 'Quality_Check' => $Quality_Check, 'Gate_Pass_Required' => $Gate_Pass_Required,'materials'=>$materials]);
    }
    public function getmateraildeatsilsajax($id)
    {
        $materialdetails=Master_Material_data::select('prj_material.*','prj_category.name as catname','prj_subcategory.name as subcatname','prj_group.name as grpname','prj_subgroup.name as subgrpname')
                        ->leftJoin('prj_category','prj_material.category_id','=','prj_category.id')
                        ->leftJoin('prj_subcategory','prj_material.subcategory_id','=','prj_subcategory.id')
                        ->leftJoin('prj_group','prj_material.group_id','=','prj_group.id')
                        ->leftJoin('prj_subgroup','prj_material.subgroup_id','=','prj_subgroup.id')
                        ->where('prj_material.id',$id)->get();
        return response()->json($materialdetails);
    }

    public function Material_view($id, $type)
    {
        $appro = Material_Management_approve::where('Material_Management_id', $id)->get();
        $approves = [];
        foreach ($appro as $val) {
            $val->user = Admin::find($val->userID);
            array_push($approves, $val);
        }

        //$edit = MaterialManagement_Add_Material::find($id);
        $edit = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name')
              ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
              ->where('materialmanagement_add_material.id',$id)->first();
        $uom = Factory_Uom::all();
        $Quality_Check = Master_Quality_Check::all();
        $Gate_Pass_Required = Master_Gate_Pass_Required::all();

        $nextID = $this->next($id, $type);

        return view('MaterialManagement/Material_view', ['edit' => $edit, 'uom' => $uom, 'Quality_Check' => $Quality_Check, 'Gate_Pass_Required' => $Gate_Pass_Required, 'approves' => $approves, 'nextID' => $nextID]);
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

    public function DownloadFilteredData(Request $request)
    {
        //$Alldata = MaterialManagement_Add_Material::orderBy('id', 'DESC')->get();
        $Alldata = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                       ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                       ->get();
        $Alldata_arr = array();
        foreach ($Alldata as $val) {
            if ($val->Forward_Status != 1) {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="4" AND step="' . $val->Approve_Step . '")')->get();
            } else {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data` WHERE DataID="' . $val->id . '" AND DepartmentID=4 AND status=0)')->get();
            }
            $val->user = Admin::find($val->userID);
            $val->uomss = Factory_Uom::find($val->UOM);
            $val->qualityCheck = Master_Quality_Check::find($val->Quality_Check);
            $val->GatePassRequired = Master_Gate_Pass_Required::find($val->Gate_Pass);

            array_push($Alldata_arr, $val);
        }
        //return auth()->user()->id;
        $Checkbox = CheckBox::where('userID', auth()->user()->id)->where('tableID', 6)->get();

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
                "Material ID" => isset($val->Material_Name) ? $val->Material_Name : '',
                "Material Name" => isset($val->matname) ? $val->matname : '',
                "HSN Code" => isset($val->HSN_Code) ? $val->HSN_Code : '',
                "UOM" => isset($val->UOM) ? $val->UOM : '',
                "Last Purchase Price" => isset($val->last_purchase_price) ? $val->last_purchase_price : '',
                "Last Purchase Date" => isset($val->last_purchase_date) ? $val->last_purchase_date : '',
                "Group" => isset($val->grp_name) ? $val->grp_name : '',
                "Sub-Group" => isset($val->sub_grp_name) ? $val->sub_grp_name : '',
                "Category" => isset($val->cat_name) ? $val->cat_name : '',
                "Sub-Category" => isset($val->sub_cat_name) ? $val->sub_cat_name : '',
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

        $file = "Material_Managment_data.csv";
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
