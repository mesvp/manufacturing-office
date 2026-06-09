<?php

namespace App\Http\Controllers\InventoryManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InventoryManagement\{Inventory_Management, Inventory_Management_Data, Inventory_Management_Product, Inventory_Management_Material, Inventory_Management_Godown,Inventory_Management_Approve};
use App\Models\Master\Plant\{Master_Manufacturing_unit, Master_BU};
use App\Models\Master\{Master_Plant_Machinery,Prj_Subproject,Prj_Project,Module_Bsns_Unit,Prj_Inventory,Pur_Address};
use App\Models\FactoryCreater\{Factory_Organisation, Factory_Uom, Factory_Product, Factory_Sub_Product, Factory_Sub_Sub_Product,Factory_Address_Detail,prj_organisation};
use App\Models\Master\RawMaterial\{Master_Rack_No, Master_Sub_Rack_No, Master_Bin_No, Master_Sub_Bin_No, Master_Raw_Material};
use App\Models\{CheckBox, Admin, Forwarded_Data};
use App\Models\BOM\{BOM, BOM_Material};
use App\Models\MaterialManagement\{MaterialManagement_Add_Material};
use App\Models\QCSampleTesting\{QCFinishedGoodResult, QCFinishedGood,QCFinishedGoodApprove};
use App\Models\Production\{ProductionData,ProductionBatch,Production,ProductionApprove};
use DB;
use Session;
use stdClass;

class InventoryManagementViewController extends Controller
{
    public function InventoryManagementList(Request $request,$export=0)
    {
        $Organization = prj_organisation::all();
        $BU = Module_Bsns_Unit::all();
        $Manufacturing_unit = prj_project::all();
        $plant_name = Prj_Subproject::all();
        //$admin=Admin::where('role',1)->get();
        $batch=DB::select("SELECT batch_no FROM `inventory_management` GROUP BY batch_no");
        $orgdata=prj_organisation::all_org();
        $BUdata = Module_Bsns_Unit::all_bu();
        $Manufacturing_unitdata = prj_project::all_mu();
        $plant_namedata = Prj_Subproject::all_pm();
        $admindata=Admin::all_admin();
        $BOM_DATA = BOM::where('Approve_status', 'APPROVE')->get();
        $Raw_Material = [];
        $Raw_Materialdata=[];
        foreach ($BOM_DATA as $Val) {
            if (isset($Val->Raw_Material_FG)) {
                //$Val->RawMaterial = MaterialManagement_Add_Material::find($Val->Raw_Material_FG);
                $Val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                    ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                    ->where('materialmanagement_add_material.id',$Val->Raw_Material_FG)
                    ->first();
                $Raw_Material[$Val->Raw_Material_FG] = $Val;
                $Raw_Materialdata[$Val->Raw_Material_FG]= $Val->RawMaterial->matname;
            }
        }
        /////////////////////////////////////
        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));
        $EXT = Session::get('EXT');
        if (isset($EXT[14]['inputer'])) {
            $query = Inventory_Management::orderBy('id', 'DESC');
        } else {
            $query = Inventory_Management::where('status', 0)->orderBy('id', 'DESC');
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
        if ($fromdate && $todate)
        {
            $query->whereBetween('created_at', [$fromdate, $todate]);
        }
        if(isset($request->Unit_Name) && $request->Unit_Name!='')
        {
            $query= $query->where('Unit_Name',$request->Unit_Name);
        }
        if(isset($request->Plant_Name) && $request->Plant_Name!='')
        {
            $query= $query->where('Plant_Name',$request->Plant_Name);
        }
        if(isset($request->Organization) && $request->Organization!='')
        {
            $query= $query->where('Organization_Name',$request->Organization);
        }
        if(isset($request->BU) && $request->BU!='')
        {
            $query= $query->where('BU_Name',$request->BU);
        }
        if(isset($request->batch_no) && $request->batch_no!='')
        {
            $query= $query->where('batch_no',$request->batch_no);
        }
        if(isset($request->QCCode) && $request->QCCode!='')
        {
            $query= $query->where('QCCode',$request->QCCode);
        }
        $InventoryManagement=$query->get();

        if($export==1)
        {
            return  compact('InventoryManagement','Organization','BU','Manufacturing_unit','plant_name','batch','orgdata','BUdata','Manufacturing_unitdata','plant_namedata','admindata','Raw_Materialdata');
        }
        if(isset($request->typeaction))
        {
            return view('InventoryManagement/table', compact('InventoryManagement','Organization','BU','Manufacturing_unit','plant_name','batch','orgdata','BUdata','Manufacturing_unitdata','plant_namedata','admindata','Raw_Materialdata'));
        }
        return view('InventoryManagement/InventoryManagementList', compact('InventoryManagement','Organization','BU','Manufacturing_unit','plant_name','batch','orgdata','BUdata','Manufacturing_unitdata','plant_namedata','admindata','Raw_Materialdata'));
    }

    public function AddInventoryManagement($id = null)
    {
        $Organization = prj_organisation::all();
        $BU = Module_Bsns_Unit::all();
        //$Manufacturing_unit = Master_Manufacturing_unit::all();
        $Manufacturing_unit = Factory_Address_Detail::select('prj_project.*')
        ->leftJoin('prj_project','factory_address_details.name_of_unit','=','prj_project.id')
        ->where('Approve_status','APPROVE')
        ->groupBy('prj_project.pname')
        ->get(); 
        $plant_name =Prj_Subproject::all();
        $UOM = Factory_Uom::all();
        $admin=Admin::where('role',1)->get();
        $batch=DB::select("SELECT batch_no FROM `qcfinishedgood` WHERE Approve_status='APPROVE'");
        //$batch=ProductionBatch::where('productionID',$request->id)->get();
        $edit = Inventory_Management::find($id);
        //'edit'=>$edit,'batch'=>$batch
        return view('InventoryManagement/InventoryManagement', ['UOM'=>$UOM,'Organization' => $Organization, 'BU' => $BU,  'plant_name' => $plant_name,'Manufacturing_unit'=>$Manufacturing_unit,'admin'=>$admin,'batch'=>$batch,'edit'=>$edit]);



    }
    public function Release_Hold(Request $request, $id)
    {
        $EXT = Session::get('EXT');
        $currentDate = now();

        $approvesss = Inventory_Management_Approve::where('Inventory_Management_id', $id)->where('action', 'HOLD')->update(['days_for_holding' => $currentDate, 'status' => 0]);
        $factory =  Inventory_Management::where('id', $id)->update(['Approve_status' => null]);

        $approve = new Inventory_Management_Approve;
        $approve->userID = auth()->user()->id;
        if (auth()->user()->role == 0) {
            $approve->role = 'Admin';
        } elseif (isset($EXT[14]['approver'])) {
            $approve->role = 'Approver';
        } elseif (isset($EXT[14]['inputer'])) {
            $approve->role = 'Inputer';
        } else {
            $approve->role = 'Viewer';
        }
        $approve->Inventory_Management_id = $id;
        $approve->status = 1;
        $approve->action = 'Hold Released';
        $approve->comment_text = $request->comment_text;
        $approve->ip_address = $request->getClientIp();
        $approve->device_name = $request->server('HTTP_USER_AGENT');
        $approve->save();


        return redirect('InventoryManagement/InventoryManagementList')->with('success', 'Hold Released successfully.....');
    }
    public function view($id)
    {
        $type=0;
        $edit = Inventory_Management::find($id);
        $admindata=Admin::all_admin()[$edit->userID];
        return view('InventoryManagement/mainview',compact('id','type','admindata'));
    }
    public function action(Request $request,$type=0) {
        $edit=Inventory_Management::find($request->id);
        if($type==1)
        {
            $employeeName=Admin::where('role',1)->get();
            return view('InventoryManagement/approveraction',compact('edit','employeeName'));
        }
        else{
            return view('InventoryManagement/inputeraction',compact('edit'));
        }

    }
    public function trail(Request $request) {
        $edit=Inventory_Management::find($request->id);
        $approves=Inventory_Management_Approve::where('Inventory_Management_id',$request->id)->get();
        $admin=Admin::all_admin();
        return view('InventoryManagement/trail',compact('edit','approves','admin'));
    }
    public function formview(Request $request)
    {

        $Organization = prj_organisation::all();
        $BU = Module_Bsns_Unit::all();
        $Manufacturing_unit = prj_project::all();
        $plant_name = Prj_Subproject::all();
        $UOM = Factory_Uom::all();
        $admin=Admin::where('role',1)->get();
        $admindata=Admin::all_admin();
        $batch=DB::select("SELECT batch_no FROM `qcfinishedgood` WHERE Approve_status='APPROVE'");
        //$batch=ProductionBatch::where('productionID',$request->id)->get();
        $edit = Inventory_Management::find($request->id);
        //'edit'=>$edit,'batch'=>$batch
        $data=[];

            $fetch=Inventory_Management_Material::where('Inventory_Management_Product_Id',$request->id)->get();
            foreach($fetch as $value)
            {
                $inv_prod=Inventory_Management_Product::where('Inventory_Management_Material_id',$value->id)->get();
                foreach($inv_prod as $val)
                {
                    $maindata=[];
                    $maindata['Rack_No']=(Master_Rack_No::find($value->Rack_No))->Rack_No;
                    $maindata['Sub_Rack_No']=(Master_Sub_Rack_No::find($value->Sub_Rack_No))->Sub_Rack_No;
                    $maindata['Bin_No']=(Master_Bin_No::find($value->Bin_No))->Bin_No;
                    $maindata['Sub_Bin_No']=(Master_Sub_Bin_No::find($value->Sub_Bin_No))->Sub_Bin_No;
                    $maindata['sl_no']=$val->material_sl_no;
                    $data[]=$maindata;
                }



            }
        return view('InventoryManagement/view', ['UOM'=>$UOM,'Organization' => $Organization, 'BU' => $BU,  'plant_name' => $plant_name,'Manufacturing_unit'=>$Manufacturing_unit,'admin'=>$admin,'batch'=>$batch,'edit'=>$edit,'admindata'=>$admindata,'data'=>$data]);
    }
    public function FetchBatchData(Request $request)
    {
        $BOM_DATA = BOM::where('Approve_status', 'APPROVE')->get();
        $Raw_Materialdata = [];
        foreach ($BOM_DATA as $Val) {
            if (isset($Val->Raw_Material_FG)) {
                //$Val->RawMaterial = MaterialManagement_Add_Material::find($Val->Raw_Material_FG);
                $Val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                    ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                    ->where('materialmanagement_add_material.id',$Val->Raw_Material_FG)
                    ->first();
                $Raw_Materialdata[$Val->Raw_Material_FG]= $Val->RawMaterial->matname;
            }
        }
        $admindata=Admin::all_admin();
        $qcdata=QCFinishedGood::where('batch_no',$request->batch_no)->get()->first();
        $production_batch=ProductionBatch::where('batch_no',$request->batch_no)->get()->first();
        $production=Production::find($production_batch->productionID);
        $arr=[];
        $arr['Finished_Good']=$Raw_Materialdata[$production->Raw_Material];
        $arr['fid']=$production->Raw_Material;
        $arr['Production_Date']=$production->Production_Date;
        $arr['Production_Shift']=$production->Shift;
        $arr['QCCode']=$qcdata->QCCode??'';
        $arr['SampleCollectedBy']=isset($qcdata->SampleCollectedBy)?$admindata[$qcdata->SampleCollectedBy]:''??'';
        $arr['result']=false;
        if(isset($qcdata->QCCode))
        {
            $arr['result']=true;
        }
        return json_encode($arr);
    }
    public function FetchBatch(Request $request)
    {
        //$qry="SELECT batch_no FROM `QCFinishedGood` WHERE (Approve_status <> 'REJECT' OR Approve_status IS NULL) AND Unit_Name='".$request->Unit_Name."' AND Plant_Name='".$request->Plant_Name."'";
      //   $sry="SELECT batch_no FROM `production_batch` WHERE productionID IN(SELECT id FROM `Production` WHERE `Approve_status`='APPROVE' AND `Unit_Name`='".$request->Unit_Name."' AND `Plant_Name`='".$request->Plant_Name."' ) AND batch_no NOT IN($qry) group by batch_no";
        $subqry="";
        $data=new stdClass;
        if($request->id!='')
        {
            $subqry=" AND id!=".$request->id;
            $data=Inventory_Management::find($request->id);
        }
       $sry="SELECT batch_no FROM `qcfinishedgood` WHERE batch_no NOT IN(SELECT batch_no FROM `inventory_management` WHERE ( Approve_status!='REJECT' OR Approve_status IS NULL ) $subqry )  AND Approve_status='APPROVE' AND Unit_Name='".$request->Unit_Name."' AND Plant_Name='".$request->Plant_Name."'";
        $batch=DB::select($sry);
        $str='';
        $str.='<option value="">Select Batch</option>';
        foreach($batch as $val)
        {
            $str.='<option value="'.$val->batch_no.'" '.(isset($data->batch_no) && $data->batch_no==$val->batch_no?'selected':'').'>'.$val->batch_no.'</option>';
        }
        return $str;
    }
    function ManageData(Request $request)
    {
        $rack_no=Master_Rack_No::all();
        $rack_sub_no=Master_Sub_Rack_No::all();
        $bin_no=Master_Bin_No::all();
        $sub_bin_no=Master_Sub_Bin_No::all();
        $batch=QCFinishedGoodResult::where('batch_no',$request->batch_no)->get();

        $data=[];
        if(isset($request->id) && $request->type==1)
        {
            $remove=[];
            $fetch=Inventory_Management_Material::where('Inventory_Management_Product_Id',$request->id)->get();
            foreach($fetch as $value)
            {
                $inv_sl=[];
                $inv_prod=Inventory_Management_Product::where('Inventory_Management_Material_id',$value->id)->get();
                foreach($inv_prod as $val)
                {
                    $inv_sl[$value->id][]=$val->material_sl_no;
                }
                $value->sl_no=$inv_sl;
                $data[]=$value;
                $remove[]=strtotime('now').rand(1,100000).rand(1,100000);
            }
        }
        else{
            $remove=strtotime('now').rand(1,100000);
        }
        //pre($data,true);
        return view('InventoryManagement/manage', compact('rack_no','rack_sub_no','bin_no','sub_bin_no','batch','remove','data'));
    }


    public function delete($id)
    {
        $inventory = Inventory_Management::find($id);

        if (!$inventory) {
            return back()->with('error', 'Inventory not found...');
        }

        $inventoryData = Inventory_Management_Data::where('Inventory_Management_id', $inventory->id)->get();

        foreach ($inventoryData as $data) {
            $products = Inventory_Management_Product::where('Inventory_Management_Data_id', $data->id)->get();

            foreach ($products as $product) {
                Inventory_Management_Material::where('Inventory_Management_Product_Id', $product->id)->delete();
                Inventory_Management_Godown::where('Inventory_Management_Product_Id', $product->id)->delete();
            }

            $data->delete();
        }

        $inventory->delete();

        return back()->with('success', 'Deleted Successfully...');
    }
    public function exportdata(Request $request)
    {
        ini_set('memory_limit', '-1');
        //$employeedata = Gatepass_Employee::orderBy('id', 'DESC')->get();
        $Checkbox = CheckBox::where('userID', auth()->user()->id)->where('tableID', 41511)->get();
        $Checkbox_Arr = [];
        foreach ($Checkbox as $val) {
            $valuee = $val->CheckBox;
            array_push($Checkbox_Arr, $valuee);
        }
        $d = [];
        $production=$this->InventoryManagementList($request,1);
        //extract($production);
        //pre($production);
        //dd($production);
       // die;

        foreach ($production['InventoryManagement'] as $key => $value) {
            $rowData = [
                "SL. No." => $key + 1,
                "Creator Name" => $production['admindata'][$value->userID]??'',
                "Organization Name" =>  $production['orgdata'][$value->Organization_Name]??'',
                 "Manufacturing Unit" =>$production['Manufacturing_unitdata'][$value->Unit_Name]??'',
                 "Plant Name" => $production['plant_namedata'][$value->Plant_Name]??'',
                 "BU Name" => $production['BUdata'][$value->BU_Name]??'',
                 //"Raw Material(FG)" =>$production['Raw_Materialdata'][$value->Raw_Material]??'',
                 //"Sample Collected By" =>$production['admindata'][$value->SampleCollectedBy]??'',
                 "Batch No" =>$value->batch_no??'',
                // "QC Date" =>$value->QCDate??'',
                 "QC Code" => $value->QCCode??'',
                 "Finished Good" =>$production['Raw_Materialdata'][$value->Raw_Material]??'',
                 "Date & Time" => $value->created_at??'',
                 "Status" => $value->Approve_status==null?'PENDING':$value->Approve_status,
                 "Pending With" => Pending_With(14,$value)??'',
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
        $file = "InventoryManagement_".date("d-m-Y").".csv";
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
