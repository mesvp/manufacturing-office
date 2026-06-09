<?php

namespace App\Http\Controllers\SampleFreeGood;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\Plant\{Master_Manufacturing_unit, Master_BU};
use App\Models\Master\{Master_Plant_Machinery,Prj_Subproject,Prj_Project,Module_Bsns_Unit,Prj_Inventory,Pur_Address,Fin_Customer};
use App\Models\SampleFreeGood\{SampleFreeGood, SampleFreeGood_data, SampleFreeGoodApprove};
use App\Models\FactoryCreater\{Factory_Organisation, Factory_Product, Factory_Uom,prj_organisation,Factory_Address_Detail};
use App\Models\Master\RawMaterial\{Master_Godown_Name, Master_Raw_Material};
use App\Models\BOM\{BOM, BOM_Material};
use App\Models\Production\{Production, ProductionBatch};
use App\Models\MaterialManagement\{MaterialManagement_Add_Material};
use App\Models\{CheckBox, Admin, PlantStock};
use Session;

class SampleFreeGoodViewController extends Controller
{
    public function SampleFreeGoodList(Request $request,$export=0)
    {
        $admindata = Admin::all_admin();
        $Organization = prj_organisation::all();
        $orgdata = prj_organisation::all_org();
        $BUdata = Module_Bsns_Unit::all_bu();
        $BU = Module_Bsns_Unit::all();
        $Manufacturing_unit = prj_project::all();
        $Manufacturing_unitdata = prj_project::all_mu();
        $plant_name = Prj_Subproject::all();
        $plant_namedata = Prj_Subproject::all_pm();
        $BOM_DATA = BOM::where('Approve_status', 'APPROVE')->get();
        $Raw_Material = [];
        $Raw_Materialdata = [];
        foreach ($BOM_DATA as $Val) {
            if (isset($Val->Raw_Material_FG)) {
                //$Val->RawMaterial = MaterialManagement_Add_Material::find($Val->Raw_Material_FG);
                $Val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                    ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                    ->where('materialmanagement_add_material.id',$Val->Raw_Material_FG)
                    ->first();
                $Raw_Material[$Val->Raw_Material_FG] = $Val;
                $Raw_Materialdata[$Val->Raw_Material_FG] = $Val->RawMaterial->matname;
            }
        }
        $Filtered_Array = array_values($Raw_Material);
        $UOM = Factory_Uom::all();
        $uom_data = Factory_Uom::all_uom();
        $Godown_Name = Prj_Inventory::all();
        $Godown_data = Prj_Inventory::all_godownname();
        //////////////////////////////
        $EXT = Session::get('EXT');
        if (isset($EXT[10]['inputer'])) {
            $query = SampleFreeGood::orderBy('id', 'DESC');
        } else {
            $query = SampleFreeGood::where('status', 0)->orderBy('id', 'DESC');
        }
        if(isset($request->typeaction))
        {
            if($request->typeaction=='Pendings')
            {
                $query= $query->where('Approve_status',null)->orWhere('Approve_status', 'FORWARD');
            }
            elseif($request->typeaction=='ALL')
            {
                
            }
            else{
                $query= $query->where('Approve_status',$request->typeaction);
            }

        }
        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));
        if ($fromdate && $todate) {
            $query->whereBetween('created_at', [$fromdate, $todate]);
        }
        if (isset($request->Manufacturing_Unit) && $request->Manufacturing_Unit != '') {
            $query = $query->where('Manufacturing_Unit', $request->Manufacturing_Unit);
        }
        if (isset($request->Plant_Name) && $request->Plant_Name != '') {
            $query = $query->where('Plant_Name', $request->Plant_Name);
        }
        if (isset($request->Organization) && $request->Organization != '') {
            $query = $query->where('Organization_Name', $request->Organization);
        }
        if (isset($request->BU) && $request->BU != '') {
            $query = $query->where('BU', $request->BU);
        }
        if (isset($request->Raw_Material) && $request->Raw_Material != '') {
            $query = $query->where('Raw_Material', $request->Raw_Material);
        }
        if (isset($request->Godown_Name) && $request->Godown_Name != '') {
            $query = $query->where('Godown_Name', $request->Godown_Name);
        }
        $Sample_data = $query->get();
        if(isset($request->typeaction))
        {
            return view('SampleFreeGood/table', compact('Godown_data', 'Sample_data', 'Organization', 'BU', 'Manufacturing_unit', 'plant_name', 'UOM', 'Filtered_Array', 'admindata', 'uom_data', 'orgdata', 'BUdata', 'Manufacturing_unitdata', 'plant_namedata', 'Raw_Materialdata', 'Godown_Name'));
        }

        if($export==1)
        {
            return compact('Godown_data', 'Sample_data', 'Organization', 'BU', 'Manufacturing_unit', 'plant_name', 'UOM', 'Filtered_Array', 'admindata', 'uom_data', 'orgdata', 'BUdata', 'Manufacturing_unitdata', 'plant_namedata', 'Raw_Materialdata', 'Godown_Name');
        }

        return view('SampleFreeGood/SampleFreeGoodList', compact('Godown_data', 'Sample_data', 'Organization', 'BU', 'Manufacturing_unit', 'plant_name', 'UOM', 'Filtered_Array', 'admindata', 'uom_data', 'orgdata', 'BUdata', 'Manufacturing_unitdata', 'plant_namedata', 'Raw_Materialdata', 'Godown_Name'));
    }
    public function Release_Hold(Request $request, $id)
    {
        $EXT = Session::get('EXT');
        $currentDate = now();

        $approvesss = SampleFreeGoodApprove::where('SampleFreeGoodID', $id)->where('action', 'HOLD')->update(['days_for_holding' => $currentDate, 'status' => 0]);
        $factory = SampleFreeGood::where('id', $id)->update(['Approve_status' => null]);

        $approve = new SampleFreeGoodApprove;
        $approve->userID = auth()->user()->id;
        if (auth()->user()->role == 0) {
            $approve->role = 'Admin';
        } elseif (isset($EXT[10]['approver'])) {
            $approve->role = 'Approver';
        } elseif (isset($EXT[10]['inputer'])) {
            $approve->role = 'Inputer';
        } else {
            $approve->role = 'Viewer';
        }
        $approve->SampleFreeGoodID = $id;
        $approve->status = 1;
        $approve->action = 'Hold Released';
        $approve->comment_text = $request->comment_text;
        $approve->ip_address = $request->getClientIp();
        $approve->device_name = $request->server('HTTP_USER_AGENT');
        $approve->save();


        return redirect('SampleFreeGood/SampleFreeGoodList')->with('success', 'Hold Released successfully.....');
    }
    public function view($id)
    {
        $type = 0;
        return view('SampleFreeGood/mainview', compact('id', 'type'));
    }
    public function action(Request $request, $type = 0)
    {
        $edit = SampleFreeGood::find($request->id);
        if ($type == 1) {
            $employeeName = Admin::where('role', 1)->get();
            return view('SampleFreeGood/approveraction', compact('edit', 'employeeName'));
        } else {
            return view('SampleFreeGood/inputeraction', compact('edit'));
        }
    }
    public function trail(Request $request)
    {
        $edit = SampleFreeGood::find($request->id);
        $approves = SampleFreeGoodApprove::where('SampleFreeGoodID', $request->id)->get();
        $admin = Admin::all_admin();
        return view('SampleFreeGood/trail', compact('edit', 'approves', 'admin'));
    }
    public function formview(Request $request)
    {

        $Organization_Name = prj_organisation::all();
        $Manufacturing_Unit = prj_project::all();
        $Plant_Name = Prj_Subproject::all();
        $UOM = Factory_Uom::all();
        $Godown_Name = Prj_Inventory::all();
        $BU = Module_Bsns_Unit::all();
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
        $edit = [];
        // $edit = Store_Requistion::find($id);
        // $Materials = array();
        if (isset($request->id) && $request->id != '') {
            $edit = SampleFreeGood::where('id', $request->id)->get()->first();
        }


        return view('SampleFreeGood/view', ['edit' => $edit, 'BU' => $BU, 'Organization_Name' => $Organization_Name, 'Manufacturing_Unit' => $Manufacturing_Unit, 'Plant_Name' => $Plant_Name, 'Raw_Material' => $Filtered_Array, 'UOM' => $UOM, 'Godown_Name' => $Godown_Name]);
    }
    public function AddSampleFreeGood($id = null)
    {
        $Organization_Name = prj_organisation::all();
       // $Manufacturing_Unit = prj_project::all();
        $Manufacturing_Unit = Factory_Address_Detail::select('prj_project.*')
        ->leftJoin('prj_project','factory_address_details.name_of_unit','=','prj_project.id')
        ->where('Approve_status','APPROVE')
        ->groupBy('prj_project.pname')
        ->get(); 
        $Plant_Name = Prj_Subproject::all();
        $Customer_Details=Fin_Customer::select('fin_customers.*')->where('status','1')->get();
        $UOM = Factory_Uom::all();
        //$Godown_Name = Prj_Inventory::all();
        $Godown_Name = Prj_Inventory::select('prj_inventory.*')->where('godown_type','69')->get();
        $BU = Module_Bsns_Unit::all();
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
        $edit = [];
        // $edit = Store_Requistion::find($id);
        // $Materials = array();
        if (isset($id) && $id != '') {
            $edit = SampleFreeGood::where('id', $id)->get()->first();
        }


        return view('SampleFreeGood/SampleFreeGood', ['edit' => $edit, 'BU' => $BU, 'Organization_Name' => $Organization_Name, 'Manufacturing_Unit' => $Manufacturing_Unit, 'Plant_Name' => $Plant_Name, 'Raw_Material' => $Filtered_Array, 'UOM' => $UOM, 'Godown_Name' => $Godown_Name,'Customer_Details'=>$Customer_Details]);
    }
    public function getcustomerdetails($id)
    {
        $customerdetails=Fin_Customer::select('fin_customers.*','prj_state.sname as billstate')
                 ->leftJoin('prj_state','fin_customers.bilstate','=','prj_state.id')
                 ->where('fin_customers.id',$id)
                 ->get();
        return response()->json($customerdetails);
    }
    // public function AddSampleFreeGood($id = null)
    // {
    //     $Organization = Factory_Organisation::all();
    //     $Manufacturing_Unit = Master_Manufacturing_unit::all();
    //     $Plant_Name = Master_Plant_Machinery::all();
    //     $Product = Factory_Product::all();
    //     $UOM = Factory_Uom::all();

    //     $edit = SampleFreeGood::find($id);
    //     $Sample = array();
    //     $Sample_count = array();
    //     if (isset($id)) {
    //         $Sample = SampleFreeGood_data::where('SampleFreeGood_id', $id)->get();
    //         $Sample_count = SampleFreeGood_data::where('SampleFreeGood_id', $id)->count();
    //     }

    //     return view('SampleFreeGood/SampleFreeGood', ['edit' => $edit, 'Sample' => $Sample, 'Manufacturing_Unit' => $Manufacturing_Unit, 'Organization' => $Organization, 'Sample_count' => $Sample_count, 'Plant_Name' => $Plant_Name, 'Product' => $Product, 'UOM' => $UOM]);
    // }

    public function delete($id)
    {
        SampleFreeGood::find($id)->delete();

        SampleFreeGood_data::where('SampleFreeGood_id', $id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function DeleteAttachment($id)
    {
        $attachment = SampleFreeGood_data::where('id', $id)->update(['Attachment' => '']);

        return response()->json($attachment);
    }
    function RawmaterialData(Request $request)
    {
        //dd($request->all());
        if ($request->Plant_Name != '') {
            // echo $request->id;
            $cond['Plant_Name'] = $request->Plant_Name;
            //$cond['Unit_Name'] = $request->Manufacturing_Unit;
            //$cond['Organization_Name'] = $request->Organization;
            // $cond['BU_Name'] = $request->BU_Name;
            $cond['Raw_Material'] = $request->id;
            // $cond['Approve_status'] = 'APPROVE';

            //$stockdata = Production::where($cond)->sum('Quantity');
            //dd( $cond);
            $stockdata = PlantStock::get_stock_vendor($request->Plant_Name, $request->id, $request->Manufacturing_Unit, 1);
            //dd($stockdata);
            $stock = $stockdata ?? 0;
        } else {
            $stockdata = Master_Raw_Material::where(['Organization' => $request->Organization, 'Godown_Name' => $request->Godown_Name, 'Material' => $request->id])->get()->first();
            $stock = $stockdata->Quantity ?? 0;
        }
        $data = MaterialManagement_Add_Material::find($request->id);
        $UOM['UOM'] = $data->UOM??'';
        $UOM['stock'] = $stock;
        //dd($data);

        return response()->json(['success' => true, 'data' => $UOM]);
    }
    function RawmaterialgetData(Request $request)
    {
        $dy_id = strtotime('now') . rand(1, 100000);
        $type = $request->type;
        $update = $request->update;

        $cond['Plant_Name'] = $request->Plant_Name;
        $cond['Unit_Name'] = $request->Manufacturing_Unit;
        //$cond['Organization_Name'] = $request->Organization;
        //$cond['BU_Name'] = $request->BU_Name;
        $cond['Raw_Material'] = $request->id;
        $cond['Approve_status'] = 'APPROVE';
        $stockdata = Production::where($cond)->get();
        $batch = [];
        foreach ($stockdata as $value) {
            $batchno = ProductionBatch::where('productionID', $value->id)->groupBy('batch_no')->get()->first();
            $batch[] = $batchno->batch_no;
        }
        $batchno = [];
        $sl_no = [];
        $allsl = [];
        //dd($request);
        if ($request->update == 1)
         {
            if ($request->edit != '') 
            {
                $samplegood = SampleFreeGood::find($request->edit);
                //dd($samplegood);
                if ($samplegood->Plant_Name != '') 
                {
                    $data = SampleFreeGood_data::where('SampleFreeGood_id', $request->edit)->groupBy('Batch_No')->get();
                    foreach ($data as $valuedata) 
                    {
                        $batchno[] = $valuedata->$data;
                        $sldd = SampleFreeGood_data::where(['SampleFreeGood_id' => $request->edit, 'Batch_No' => $valuedata->Batch_No])->get();
                        foreach ($sldd as $sl_data) {
                            $sl_no[$valuedata->Batch_No][] = $sl_data->Sl_No;
                        }
                    }
                } else 
                {

                    $data = SampleFreeGood_data::where('SampleFreeGood_id', $request->edit)->get();
                    foreach ($data as $valuedata) 
                    {
                        $sl_no[$valuedata->Batch_No] = $valuedata->Sl_No;
                    }
                }
            }
            $allsl = ProductionBatch::whereIn('batch_no', $batch)->get();
        }
        //pre($sl_no);
        if ($request->Plant_Name != '') {
            return view('SampleFreeGood/PlantMaterial', compact('batch', 'dy_id', 'type', 'sl_no', 'batchno', 'update', 'allsl'));
        } else {
            //dd($sl_no);
            return view('SampleFreeGood/GoDownMaterial', compact('batch', 'dy_id', 'type', 'sl_no', 'batchno', 'update', 'allsl'));
        }
    }
    function Rawmaterialgetsl(Request $request)
    {
        $batch=[];
        $datadata = SampleFreeGood_data::where('batch_no', $request->value)->get();
        foreach($datadata as $value)
        {
            $samplegood = SampleFreeGood::find($value->SampleFreeGood_id);
            if($samplegood->Approve_status!='REJECT')
            {
                $batch[]=$value->Sl_No;
            }
        }
        $data = ProductionBatch::where('batch_no', $request->value)->whereNotIn('Sl_No',$batch)->get();
        //$data = ProductionBatch::where('batch_no', $request->value)->get();
        return response()->json(['success' => true, 'data' => $data]);
    }
    public function exportdata(Request $request)
    {
        ini_set('memory_limit', '-1');
        //$employeedata = Gatepass_Employee::orderBy('id', 'DESC')->get();
        $Checkbox = CheckBox::where('userID', auth()->user()->id)->where('tableID', 31011)->get();
        $Checkbox_Arr = [];
        foreach ($Checkbox as $val) {
            $valuee = $val->CheckBox;
            array_push($Checkbox_Arr, $valuee);
        }
        $d = [];
        $production=$this->SampleFreeGoodList($request,1);
        //extract($production);
        // pre($production['production']);
        // die;
         
        foreach ($production['Sample_data'] as $key => $value) {
            $rowData = [
                "SL. No." => $key + 1,
                "Creator Name" => $production['admindata'][$value->userID]??'',
                "Manufacturing Unit" => $production['Manufacturing_unitdata'][$value->Manufacturing_Unit]??'',
                "Plant Name" =>  $production['plant_namedata'][$value->Plant_Name]??'',
                "Godown Name" =>$production['Godown_data'][$value->Godown_Name]??'',
                 "Organization Name" => $production['orgdata'][$value->Organization_Name]??'',
                 "BU Name" => $production['BUdata'][$value->BU]??'',
                 "Finished Good(FG)" =>$production['Raw_Materialdata'][$value->Raw_Material]??'',
                 "UOM" =>  $value->UOM??'',
                 "Quantity" => $value->Quantity,
                 "Date" =>$value->Date??'',
                 "Customer Name" =>  $value->CustomerName??'',
                 "Customer Address" => $value->CustomerAddress??'',
                 "Customer Phone" => $value->CustomerPhone??'',
                 "Company Name" =>  $value->CompanyName??'',
                 "Date & Time" =>$value->created_at??'',
                 "Status" => $value->Approve_status==null?'PENDING':$value->Approve_status,
                 "Pending With" =>Pending_With(10,$value)??'',
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
        //pre($d,true);
        $file = "Sample_data".date("d-m-Y").".csv";
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
