<?php

namespace App\Http\Controllers\QCSampleTesting;

use App\Http\Controllers\Controller;
use App\Models\Production\{ProductionBatch,Production};
use Illuminate\Http\Request;
use App\Models\Master\Plant\{Master_Manufacturing_unit, Master_BU};
use App\Models\Master\RawMaterial\{Master_Raw_Material};
use App\Models\QCSampleTesting\{QCFinishedGoodResult, QCFinishedGood,QCFinishedGoodApprove};
use App\Models\FactoryCreater\{ Factory_Organisation,Factory_Uom,prj_organisation,Factory_Address_Detail};
use App\Models\BOM\{BOM, BOM_Material};
use App\Models\Master\{Master_Plant_Machinery,Prj_Subproject,Prj_Project,Module_Bsns_Unit,Prj_Inventory,Pur_Address};
use App\Models\MaterialManagement\{MaterialManagement_Add_Material};
use App\Models\{Admin,CheckBox};
use Session;
use DB;
use stdClass;

class QCSampleTestingViewController extends Controller
{
    public function STDFinishedGoodsList(Request $request,$export=0)
    {
        $Organization = prj_organisation::all();
        $BU = Module_Bsns_Unit::all();
        $Manufacturing_unit = prj_project::all();
        $plant_name = Prj_Subproject::all();
        $UOM = Factory_Uom::all();
        $admin=Admin::where('role',1)->get();
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
        $Filtered_Array = array_values($Raw_Material);
        $batch=DB::select("SELECT batch_no FROM `production_batch` WHERE productionID IN(SELECT id FROM `production` WHERE Approve_status='APPROVE') group by batch_no");
        //////////////////////////////////
        $uom_data=Factory_Uom::all_uom();
        $orgdata=prj_organisation::all_org();
        $BUdata = Module_Bsns_Unit::all_bu();
        $Manufacturing_unitdata = prj_project::all_mu();
        $plant_namedata = Prj_Subproject::all_pm();
        $admindata=Admin::all_admin();
        //$batch=ProductionBatch::where('productionID',$request->id)->get();
       
        //'edit'=>$edit,'batch'=>$batch
       
        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));
        $EXT = Session::get('EXT');
        if (isset($EXT[9]['inputer'])) {
            $query = QCFinishedGood::orderBy('id', 'DESC');
        } else {
            $query = QCFinishedGood::where('status', 0)->orderBy('id', 'DESC');
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
        if(isset($request->SampleCollectedBy) && $request->SampleCollectedBy!='')
        {
            $query= $query->where('SampleCollectedBy',$request->SampleCollectedBy);
        }
        if(isset($request->QCDate) && $request->QCDate!='')
        {
            $query= $query->where('QCDate',$request->QCDate);
        }
        if(isset($request->QCCode) && $request->QCCode!='')
        {
            $query= $query->where('QCCode',$request->QCCode);
        }
        if(isset($request->Raw_Material) && $request->Raw_Material!='')
        {
            $query= $query->where('Raw_Material',$request->Raw_Material);
        }
        $STD_arr = $query->get();
        if($export==1)
        {
            return compact('UOM','Organization','BU','plant_name','Manufacturing_unit','admin','Filtered_Array','batch','STD_arr','Raw_Materialdata','uom_data','orgdata','BUdata','Manufacturing_unitdata','plant_namedata','admindata');
        }
        elseif(isset($request->typeaction))
        {
        return view('QCSampleTesting/table', ['UOM'=>$UOM,'Organization' => $Organization, 'BU' => $BU,  'plant_name' => $plant_name,'Manufacturing_unit'=>$Manufacturing_unit,'admin'=>$admin,'batch'=>$batch,'Filtered_Array'=>$Filtered_Array ,'STD_data' => $STD_arr,'Raw_Materialdata'=>$Raw_Materialdata,'uom_data'=>$uom_data,'orgdata'=>$orgdata,'BUdata'=>$BUdata,'Manufacturing_unitdata'=>$Manufacturing_unitdata,'plant_namedata'=>$plant_namedata,'admindata'=>$admindata]);
        }
       else{
        return view('QCSampleTesting/STDFinishedGoodsList', ['UOM'=>$UOM,'Organization' => $Organization, 'BU' => $BU,  'plant_name' => $plant_name,'Manufacturing_unit'=>$Manufacturing_unit,'admin'=>$admin,'batch'=>$batch,'Filtered_Array'=>$Filtered_Array ,'STD_data' => $STD_arr,'Raw_Materialdata'=>$Raw_Materialdata,'uom_data'=>$uom_data,'orgdata'=>$orgdata,'BUdata'=>$BUdata,'Manufacturing_unitdata'=>$Manufacturing_unitdata,'plant_namedata'=>$plant_namedata,'admindata'=>$admindata]);
       }
    }

    public function AddSTDFinishedGoods($id = null)
    {
        $Organization = prj_organisation::all();
        $BU = Module_Bsns_Unit::all();
        $Manufacturing_unit = Factory_Address_Detail::select('prj_project.*')
        ->leftJoin('prj_project','factory_address_details.name_of_unit','=','prj_project.id')
        ->where('Approve_status','APPROVE')
        ->groupBy('prj_project.pname')
        ->get(); 
        $plant_name =Prj_Subproject::all();
        $UOM = Factory_Uom::all();
        $admin=Admin::where('role',1)->get();
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
        $batch=DB::select("SELECT batch_no FROM `production_batch` WHERE productionID IN(SELECT id FROM `production` WHERE Approve_status='APPROVE') group by batch_no");
       // $batch=ProductionBatch::where('productionID',$request->id)->get();
        $edit = QCFinishedGood::find($id);
        //'edit'=>$edit,'batch'=>$batch
       // dd($batch);
        return view('QCSampleTesting/STDFinishedGoods', ['UOM'=>$UOM,'Organization' => $Organization, 'BU' => $BU,  'plant_name' => $plant_name,'Manufacturing_unit'=>$Manufacturing_unit,'admin'=>$admin,'batch'=>$batch,'Raw_Material'=>$Filtered_Array,'edit'=>$edit]);
    }
    public function Release_Hold(Request $request, $id)
    {
        $EXT = Session::get('EXT');
        $currentDate = now();

        $approvesss = QCFinishedGoodApprove::where('QCFinishedGoodID', $id)->where('action', 'HOLD')->update(['days_for_holding' => $currentDate, 'status' => 0]);
        $factory =  QCFinishedGood::where('id', $id)->update(['Approve_status' => null]);

        $approve = new QCFinishedGoodApprove;
        $approve->userID = auth()->user()->id;
        if (auth()->user()->role == 0) {
            $approve->role = 'Admin';
        } elseif (isset($EXT[9]['approver'])) {
            $approve->role = 'Approver';
        } elseif (isset($EXT[9]['inputer'])) {
            $approve->role = 'Inputer';
        } else {
            $approve->role = 'Viewer';
        }
        $approve->QCFinishedGoodID = $id;
        $approve->status = 1;
        $approve->action = 'Hold Released';
        $approve->comment_text = $request->comment_text;
        $approve->ip_address = $request->getClientIp();
        $approve->device_name = $request->server('HTTP_USER_AGENT');
        $approve->save();


        return redirect('QCSampleTesting/STDFinishedGoodsList')->with('success', 'Hold Released successfully.....');
    }
    public function view($id)
    {
        $type=0;
        return view('QCSampleTesting/mainview',compact('id','type'));
    }
    public function action(Request $request,$type=0) {
        $edit=QCFinishedGood::find($request->id);
        if($type==1)
        {
            $employeeName=Admin::where('role',1)->get();
            return view('QCSampleTesting/approveraction',compact('edit','employeeName')); 
        }
        else{
            return view('QCSampleTesting/inputeraction',compact('edit'));
        }
        
    }
    public function trail(Request $request) {
        $edit=QCFinishedGood::find($request->id);
        $approves=QCFinishedGoodApprove::where('QCFinishedGoodID',$request->id)->get();
        $admin=Admin::all_admin();
        return view('QCSampleTesting/trail',compact('edit','approves','admin'));
    }
    public function formview(Request $request)
    {
      
        $Organization = prj_organisation::all();
        $BU = Module_Bsns_Unit::all();
        $Manufacturing_unit = prj_project::all();
        $plant_name = Prj_Subproject::all();
        $UOM = Factory_Uom::all();
        $admin=Admin::where('role',1)->get();
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
        $batch=DB::select("SELECT batch_no FROM `production_batch` WHERE productionID IN(SELECT id FROM `production` WHERE Approve_status='APPROVE') group by batch_no");
        $batch=QCFinishedGoodResult::where('QCFinishedGoodID',$request->id)->get();
        $edit = QCFinishedGood::find($request->id);
        //'edit'=>$edit,'batch'=>$batch
        return view('QCSampleTesting/view', ['UOM'=>$UOM,'Organization' => $Organization, 'BU' => $BU,  'plant_name' => $plant_name,'Manufacturing_unit'=>$Manufacturing_unit,'admin'=>$admin,'batch'=>$batch,'Raw_Material'=>$Filtered_Array,'edit'=>$edit]);
       // return view('Production/view', ['UOM'=>$UOM,'Raw_Material'=>$Filtered_Array,'Organization' => $Organization, 'BU' => $BU,  'plant_name' => $plant_name,'Manufacturing_unit'=>$Manufacturing_unit,'edit'=>$edit,'batch'=>$batch]);
    }
    public function FetchBatch(Request $request)
    {
        $subqry="";
        $data=new stdClass;
        if($request->id!='')
        {
            $subqry=" AND id!=".$request->id;
            $data=QCFinishedGood::find($request->id);
        }
        $qry="SELECT batch_no FROM `qcfinishedgood` WHERE (Approve_status <> 'REJECT' OR Approve_status IS NULL) AND Unit_Name='".$request->Unit_Name."' AND Plant_Name='".$request->Plant_Name."' $subqry";
         $sry="SELECT batch_no FROM `production_batch` WHERE productionID IN(SELECT id FROM `production` WHERE `Approve_status`='APPROVE' AND `Unit_Name`='".$request->Unit_Name."' AND `Plant_Name`='".$request->Plant_Name."' ) AND batch_no NOT IN($qry) group by batch_no";
        // $sry="SELECT * FROM `QCFinishedGood` WHERE Approve_status='APPROVE' AND `Unit_Name`='".$request->Unit_Name."' AND `Plant_Name`='".$request->Plant_Name."' ";
        $batch=DB::select($sry);
        $str='';
        $str.='<option value="">Select Batch</option>';
        foreach($batch as $val)
        {
            $str.='<option value="'.$val->batch_no.'"'.(isset($data->batch_no) && $data->batch_no==$val->batch_no?'selected':'').' >'.$val->batch_no.'</option>';
        }
        return $str;
    }
    public function FetchBatchData(Request $request)
    {
        $batch=ProductionBatch::where('batch_no',$request->batch_no)->get();
        $resultdata=[];
        if(isset($request->id))
        {
            $result=QCFinishedGoodResult::where('QCFinishedGoodID',$request->id)->get();
            foreach($result as $value)
            {
                $resultdata[$value->production_batchID]=$value;
            }
         }
        return view('QCSampleTesting/Batch',compact('batch','resultdata'));
    }
    public function FetchBatchDatafor(Request $request)
    {
        $batch=ProductionBatch::where('batch_no',$request->batch_no)->get()->first();
        $production=Production::find($batch->productionID);
        // $resultdata=[];
        // if(isset($request->id))
        // {
        //     $result=QCFinishedGoodResult::where('QCFinishedGoodID',$request->id)->get();
        //     foreach($result as $value)
        //     {
        //         $resultdata[$value->production_batchID]=$value;
        //     }
        //  }
        return response()->json($production);
    }
      

    public function delete($id)
    {
        STDFinishedGoods::find($id)->delete();

        STDFinishedGoods_data::where('STDFinishedGoods_id', $id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }



    public function STDRawMaterialList(Request $request)
    {
        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));

        $query = STDRawMaterial::orderBy('id', 'DESC');

        if ($fromdate && $todate) {
            $query->whereBetween('created_at', [$fromdate, $todate]);
        }

        $STD = $query->get();

        $STD_arr = array();
        foreach ($STD as $val) {
            $val->RawMaterial = Master_Raw_Material::find($val->Material_Name);

            array_push($STD_arr, $val);
        }

        return view('QCSampleTesting/STDRawMaterialList', ['STD_data' => $STD_arr, 'fromdate' => $fromdate, 'todate' => $dateto]);
    }

    public function AddSTDRawMaterial($id = null)
    {
        $RawMaterial = Master_Raw_Material::all();
        $QCStatus = Master_Quality_Check::all();

        $edit = STDRawMaterial::find($id);

        return view('QCSampleTesting/STDRawMaterial', ['edit' => $edit, 'RawMaterial' => $RawMaterial, 'QCStatus' => $QCStatus]);
    }

    public function STDRowdelete($id)
    {
        STDRawMaterial::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }
    public function ExportQCsample(Request $request)
    {
        ini_set('memory_limit', '-1');
        //$employeedata = Gatepass_Employee::orderBy('id', 'DESC')->get();
        $Checkbox = CheckBox::where('userID', auth()->user()->id)->where('tableID', 31511)->get();
        $Checkbox_Arr = [];
        foreach ($Checkbox as $val) {
            $valuee = $val->CheckBox;
            array_push($Checkbox_Arr, $valuee);
        }
        $d = [];
        $production=$this->STDFinishedGoodsList($request,1);
        //extract($production);
        //pre($production);
        //dd($production);
       // die;
         
        foreach ($production['STD_arr'] as $key => $value) {
            $rowData = [
                "SL. No." => $key + 1,
                "Creator Name" => $production['admindata'][$value->userID]??'',
                "Organization Name" =>  $production['orgdata'][$value->Organization_Name]??'',
                 "Manufacturing Unit" =>$production['Manufacturing_unitdata'][$value->Unit_Name]??'',
                 "Plant Name" => $production['plant_namedata'][$value->Plant_Name]??'',
                 "BU Name" => $production['BUdata'][$value->BU_Name]??'',
                 "Finished Good(FG)" =>$production['Raw_Materialdata'][$value->Raw_Material]??'',
                 "Sample Collected By" =>$production['admindata'][$value->SampleCollectedBy]??'',
                 "Batch No" =>$value->batch_no??'',
                 "QC Date" =>$value->QCDate??'',
                 "QC Code" => $value->QCCode??'',
                 //"Quantity" =>$value->Quantity??'',
                 "Date & Time" => $value->created_at??'',
                 "Status" => $value->Approve_status==null?'PENDING':$value->Approve_status,
                 "Pending With" => Pending_With(9,$value)??'',
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
        $file = "qc_sample_testing_data_".date("d-m-Y").".csv";
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
