<?php

namespace App\Http\Controllers\Storeissue;

use App\Http\Controllers\Controller;
use App\Http\Controllers\OtherController;
use Illuminate\Http\Request;
use App\Models\Storeissue\{Store_issue,StoreIssueApprove,StoreIssueApprovedMaterial};
use App\Models\{CheckBox, Admin};
use App\Models\StoreRequistion\{Store_Requistion, Store_Requistion_Material, Store_Requistion_approve};
use App\Models\FactoryCreater\{Factory_Organisation, Factory_Uom,prj_organisation,unitname,Factory_Address_Detail};
use App\Models\Master\Plant\{Master_Manufacturing_unit,Master_Customer_Name, Master_BU};
use App\Models\Master\{Master_Plant_Machinery,Prj_Subproject,Prj_Project,Module_Bsns_Unit,Prj_Inventory,Pur_Address};
use App\Models\Master\RawMaterial\{Master_Godown_Name,Master_Raw_Material};
use App\Models\BOM\{BOM, BOM_Material};
use App\Models\MaterialManagement\{MaterialManagement_Add_Material};
use Session;
class StoreissueViewController extends Controller
{
    public function filter_view()
    {
        $Organization_Name = prj_organisation::all();
        $Manufacturing_Unit = prj_project::all();
        $Plant_Name = Prj_Subproject::all();
        $UOM = Factory_Uom::all();
        $Godown_Name = Prj_Inventory::all();
        $BOM_DATA = BOM::where('Approve_status', 'APPROVE')->get();
        $Raw_Material = [];
        foreach ($BOM_DATA as $Val) {
            if (isset($Val->Raw_Material_FG)) {
                $Val->RawMaterial = MaterialManagement_Add_Material::find($Val->Raw_Material_FG);
                $Raw_Material[$Val->Raw_Material_FG] = $Val;
            }
        }
        $Filtered_Array = array_values($Raw_Material);      

       

        return view('Storeissue/filter', [ 'Organization_Name' => $Organization_Name, 'Manufacturing_Unit' => $Manufacturing_Unit, 'Plant_Name' => $Plant_Name, 'Raw_Material' => $Filtered_Array, 'UOM' => $UOM, 'Godown_Name' => $Godown_Name,]);
    }
    public function StoreissueList($request)
    {
        $userID = auth()->user()->id;
        $EXT = Session::get('EXT');
        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));
       // $query = Store_Requistion::where(['Approve_status'=>'APPROVE'])->whereIn('Store_issue_status',[0,3])->orderBy('id', 'DESC');
        $query = Store_Requistion::where(['Approve_status'=>'APPROVE']);

        if ($fromdate && $todate) 
        {
            $query= $query->whereBetween('created_at', [$fromdate, $todate]);
        }
        if (isset($request->Organization_Name) && $request->Organization_Name!='') 
        {
            $query=  $query->where('Organization_Name', $request->Organization_Name);
        }
        if (isset($request->Manufacturing_Unit) && $request->Manufacturing_Unit!='') 
        {
            $query= $query->where('Manufacturing_Unit', $request->Manufacturing_Unit);
        }
        if (isset($request->Plant_Name) && $request->Plant_Name!='') 
        {
            $query= $query->where('Plant_Name', $request->Plant_Name);
        }
        if (isset($request->Godown_Name) && $request->Godown_Name!='') 
        {
            $query= $query->where('Godown_Name', $request->Godown_Name);
        }
        $query=$query->orderBy('id', 'DESC');
        $store = [];
        $closed=[];
        $all=[];
        $myall=[];
        
        $allcount=0;
        $mycount=0;
        foreach($query->get() as $val)
        {
            $val->Creater=Admin::find($val->userID);
            $val->Organization=prj_organisation::find($val->Organization_Name);
            $val->Manufacturing=prj_project::find($val->Manufacturing_Unit);
            $val->Plant_name=Prj_Subproject::find($val->Plant_Name);
            $val->Godown_Name = Prj_Inventory::find($val->Godown_Name);
            $val->u=0;
            if($val->Store_issue_status==0 || $val->Store_issue_status==3) 
            {
                $store[]=$val;
            }
            else{
                $closed[]=$val;
            }
            
            $approves1 = StoreIssueApprovedMaterial::select('store_issue_approved_material.*','prj_organisation.organisation','prj_organisation.id as orgid')
            ->leftJoin('prj_organisation','store_issue_approved_material.Organization_Used','=','prj_organisation.id')
            ->where('store_issue_approved_material.Store_Requistion_id',$val->id)->groupBy('store_issue_approved_material.issueNO','status')->orderBy('store_issue_approved_material.issueNO', 'DESC')->orderBy('store_issue_approved_material.status', 'DESC')->get();

            if(sizeof($approves1)>0)
            {
                $Materialss = array();
                $debuge=false;
                foreach($approves1 as $mat)
                {
                    $Matxx= Store_Requistion_Material::where('id', $mat->Store_Requistion_material_id)->get()->first();
                    $Matxx->material_data=$mat;
                    $Materialss[]=$Matxx;
                    $allcount++;
                    if($mat['userID']==$userID)
                    {
                        $mycount++;
                        $debuge=true;
                    }
                    
                }
                $val->stroeissue=$Materialss;
                $all[]=$val;
                if($debuge==true)
                {
                    $myall[]=$val;
                }
                
            }
        }
        $count=[];
        $count['total']=$allcount;
        $count['mytotal']=$mycount;
          

        $admindata=Admin::all_admin();

        return ['store' =>$store,'closed'=>$closed, 'fromdate' => $fromdate, 'todate' => $dateto,'all'=>$all,'admindata'=>$admindata,'myall'=>$myall,'count'=>$count];
    }
    public function StoreissueListpending(Request $request)
    {
        return view('Storeissue/StoreissueList',$this->StoreissueList($request) );
    }
    public function StoreissueListclose(Request $request)
    {
        return view('Storeissue/StoreissueListclosed',$this->StoreissueList($request) );
    }
    public function StoreissueListDetails(Request $request)
    {
        return view('Storeissue/StoreissueListDetails',$this->StoreissueList($request) );
    }
    public function Release_Hold($id,$idd)
    {
        $userID = auth()->user()->id;
        $query=StoreIssueApprovedMaterial::where(['Store_Requistion_id'=>$id,'issueNO'=>$idd,'action'=>'HOLD','recived_by'=>$userID]);
        $arr['action']=null;
        $arr['status']=0;
        $arr['hold_date']=null;
        $query->update($arr);
        return redirect('Storeissue/StoreissueListDetails');
    }
    public function AddStoreissue($id = null,$type='')
    {
        $userID = auth()->user()->id;
        $appro = StoreIssueApprove::where('Store_Requistion_id', $id)->get();
        $approves = [];
        foreach ($appro as $val) {
            $val->user = Admin::find($val->userID);
            array_push($approves, $val);
        }
        $edit=$val = Store_Requistion::find($id);
        $val->Creater=Admin::find($val->userID);
        $val->Organization=prj_organisation::find($val->Organization_Name);
        $val->Manufacturing=prj_project::find($val->Manufacturing_Unit);
        $val->Plant_name=Prj_Subproject::find($val->Plant_Name);
        $val->Godown_Name = Prj_Inventory::find($val->Godown_Name);
        $UOM = Factory_Uom::all();
        $Materials = Store_Requistion_Material::where('Store_Requistion_id', $val->id)->get();
        $master_material=Master_Raw_Material::where(['Organization'=>$val->Organization_Name,'Godown_Name'=>$val->Godown_Name->id])->get();
        $stock=[];
        foreach($master_material as $value)
        {
            $stock[$value->Material]=$value;
        }
        $Materialss = array();
        $approves1 = StoreIssueApprovedMaterial::select('store_issue_approved_material.*','prj_organisation.organisation','prj_organisation.id as orgid')
            ->leftJoin('prj_organisation','store_issue_approved_material.Organization_Used','=','prj_organisation.id')
            ->where('Store_Requistion_id',$val->id)->get();
        //dd($approves1);
        foreach($approves1 as $mat)
        {
            //pre($mat->toArray());
           // die;
            $Matxx= Store_Requistion_Material::where('id', $mat->Store_Requistion_material_id)->where('QTY','>',0)//->whereIn('Store_issue_status',['0','3','1'])
            ->get()->first();
            $Matxx->material_data=$mat;
            $Materialss[]=$Matxx;
        }
       // die;
        $uom_data=[];
        foreach($UOM->toArray() as $Valddd)
        {
            $uom_data[$Valddd['id']]=$Valddd;
        }
        $admindata=Admin::all_admin();
        $val->u=0;
        if(StoreIssueApprovedMaterial::where('Store_Requistion_id',$val->id)->where('userID',$userID)->count()>0)
        {
            $val->u=1;
        }
        return view('Storeissue/Storeissue',compact('val','Materials','UOM','stock','edit','approves','type','Materialss','uom_data','admindata'));
       

        //return view('Storeissue/Storeissue', ['edit' => $edit, 'STORE_DATA' => $STORE_DATA]);
    }
    public function ViewStoreissue($id = null,$type=null,$idd=null,$orgid=null)
    {
        $userID = auth()->user()->id;
        $admin=Admin::where('role',1)->get();
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
        $recived_by=0;
        if (isset($edit->id) && $edit->id != '') {
            if($type=='RECHECK')
            {
                $Materialsdat = StoreIssueApprovedMaterial::where(['Store_Requistion_id'=>$id,'userID'=>$userID,'action'=>'RECHECK','issueNO'=>$idd])->get();
                foreach($Materialsdat as $recheck)
                {
                    $valuess= Store_Requistion_Material::where(['Store_Requistion_id'=>$id,'id'=>$recheck->Store_Requistion_material_id])->get()->first();
                    $valuess->mat_data=StoreIssueApprovedMaterial::where(['id'=>$recheck->id])->get()->first();
                    $Materials[] =$valuess;
                    $recived_by=$recheck->recived_by;
                }
            }
            else
            {
                $Materials = Store_Requistion_Material::where('Store_Requistion_id', $edit->id)->where('QTY','>',0)->whereIn('Store_issue_status',['0','3'])->get();
            }
            
        }
        //return $Materials;
        $uom_data=[];
        foreach($UOM->toArray() as $Valddd)
        {
            $uom_data[$Valddd['id']]=$Valddd;
        }
        // echo "<pre>";
        // print_r($edit);
        // echo "</pre>";
        //pre($Materials,true);
        // die;
        if(isset($orgid) && $orgid != ''){
            $master_material=Master_Raw_Material::where(['Organization'=>$orgid,'Godown_Name'=>$edit->Godown_Name])->get();
        }else{
            $master_material=Master_Raw_Material::where(['Organization'=>$edit->Organization_Name,'Godown_Name'=>$edit->Godown_Name])->get();
        }
        
        $stock=[];
        foreach($master_material as $value)
        {
            $stock[$value->Material]=$value;
        }
       

        return view('Storeissue/StoreissueApprove', ['edit' => $edit, 'Organization_Name' => $Organization_Name, 'Manufacturing_Unit' => $Manufacturing_Unit, 'Plant_Name' => $Plant_Name, 'Raw_Material' => $Filtered_Array, 'UOM' => $UOM, 'Godown_Name' => $Godown_Name, 'Materials' => $Materials,'stock'=>$stock,'uom_data'=>$uom_data,'admin'=>$admin,'recived_by'=>$recived_by,'orgid'=>$orgid]);
    }

    public function delete($id)
    {
        Store_issue::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function getCheckBoxData(Request $request)
    {
        $userID = auth()->user()->id;
        $id = $request->input('ID');

        $data = CheckBox::where('userID', $userID)->where('tableID', $id)->get();

        return response()->json(['success' => true, 'columns' => $data->pluck('CheckBox')]);
    }
    // public function forclose(Request $request)
    // {
    //     $value=Store_Requistion_Material::find($request->id);
    //     if(empty($request->id))
    //     {
    //       return OtherController::fail('Id missing');
    //     }
    //     if(StoreIssueApprovedMaterial::where(['Store_Requistion_id'=>$value->Store_Requistion_id,'status'=>0])->count()>0)
    //     {
    //       return OtherController::fail('You Can Not For Close Due To Some Issued Material Are In Pending State From Approver ! ');
    //     }
    //     $update=Store_Requistion_Material::where('id',$request->id)->update(['Store_issue_status'=>2]);
        
    //     $count=Store_Requistion_Material::where('Store_Requistion_id',$value->Store_Requistion_id)->whereIn('Store_issue_status',[0,3])->count();
    //     if($count<1)
    //     {
    //         Store_Requistion::where(['id'=>$value->Store_Requistion_id])->update(['Store_issue_status'=>2]);
    //     }
    //     if($update)
    //     {
    //         return OtherController::pass('Request material for closed');
    //     }
    //     else{
    //         return OtherController::fail('Somthing went wrong');
    //     }

    // }
    public function forclose(Request $request)
    {
        if (empty($request->id)) {
            return OtherController::fail('Id missing');
        }

        $value = Store_Requistion_Material::find($request->id);
        if (!$value) {
            return OtherController::fail('Record not found');
        }

        if (StoreIssueApprovedMaterial::where([
            'Store_Requistion_id' => $value->Store_Requistion_id,
            'status' => 0
        ])->count() > 0) {
            return OtherController::fail('You Can Not For Close Due To Some Issued Material Are In Pending State From Approver ! ');
        }

        $update = Store_Requistion_Material::where('id', $request->id)
            ->update(['Store_issue_status' => 2]);

        if ($update) {
            $pendingMaterialCount = Store_Requistion_Material::where('Store_Requistion_id', $value->Store_Requistion_id)
                ->where('Store_issue_status', 0)
                ->count();

            if ($pendingMaterialCount < 1) {
                Store_Requistion::where('id', $value->Store_Requistion_id)
                    ->update(['Store_issue_status' => 4]);
            }

            return OtherController::pass('Request material for closed');
        }

        return OtherController::fail('Somthing went wrong');

    }
    public function issueqty(Request $request)
    {
        //return $request->all();
        $userID = auth()->user()->id;
        $inputs=$request->all();
        $check = Store_Requistion::find($request->edit);
        $issue=StoreIssueApprovedMaterial::max('issueNO');
        if(empty($issue))
        {
            $issueNO=1;
        }
        else
        {
            $issueNO=$issue+1;
        }
        //die;
         if(isset($request->Material_id) && sizeof($request->Material_id)>0)
         {
            
            foreach($request->Material_id as $key=> $val)
            {
                    $fetch_material=Store_Requistion_Material::find($val);
                    if(!empty($inputs['editmaterial'][$key]))
                    {
                        $checkdata=StoreIssueApprovedMaterial::find($inputs['editmaterial'][$key]);
                    }
                    
                    if(isset($checkdata->action) && $checkdata->action=='RECHECK')
                    {
                        $qty=$inputs['issueQTY'][$key]+$fetch_material->StoreIssue_Approve_qty;
                        $storeee=Store_Requistion_Material::where('id',$val);
                        //$stordata=$storeee->get()->first();
                        //$storeee->update(['StoreIssue_Approve_qty'=>($qty+$stordata->StoreIssue_Approve_qty),'Store_issue_status'=>3]);
                        $storeee->update(['StoreIssue_Approve_qty'=>($qty),'Store_issue_status'=>3]);

                        //Store_Requistion::where('id',$request->edit)->update(['Store_issue_status'=>3,'Approve_statusForIssue'=>null]);
                        $storeissue=StoreIssueApprovedMaterial::where(['id'=>$inputs['editmaterial'][$key],'action'=>'RECHECK']);
                        $st['issueQTY']=$inputs['issueQTY'][$key];
                        $st['status']=0;
                        $st['action']=null;
                        $st['recived_by']=$request->recived_by;
                        if (isset($request->Organization_Name) && $request->Organization_Name != '') {
                            $storeissue->Organization_Used = $request->Organization_Name;
                        } 
                        $storeissue->update($st);
                        $sss=true;
                        if (isset($request->Organization_Name) && $request->Organization_Name != '') {
                            $materialqty=Master_Raw_Material::where(['Organization'=>$request->Organization_Name,'Godown_Name'=>$check->Godown_Name,'Material'=>$fetch_material->Material_id]);
                        }else{
                            $materialqty=Master_Raw_Material::where(['Organization'=>$check->Organization_Name,'Godown_Name'=>$check->Godown_Name,'Material'=>$fetch_material->Material_id]);
                        }
                        
                        $qtyss=$materialqty->get()->first();
                        $materialqty->update(['Quantity'=>abs($qtyss->Quantity-$inputs['issueQTY'][$key])]);
                    }
                    else
                    {
                        if($inputs['issueQTY'][$key]>0)
                        {
                            $qty=$inputs['issueQTY'][$key]+$fetch_material->StoreIssue_Approve_qty;
                            $storeee=Store_Requistion_Material::where('id',$val);
                            //$stordata=$storeee->get()->first();
                            //$storeee->update(['StoreIssue_Approve_qty'=>($qty+$stordata->StoreIssue_Approve_qty),'Store_issue_status'=>3]);
                            $storeee->update(['StoreIssue_Approve_qty'=>($qty),'Store_issue_status'=>3]);
                           // Store_Requistion::where('id',$request->edit)->update(['Store_issue_status'=>3]);
                            
                            $storeissue= new StoreIssueApprovedMaterial;
                            $storeissue->Store_Requistion_id=$request->edit;
                            $storeissue->Store_Requistion_material_id=$val;
                            if (isset($request->Organization_Name) && $request->Organization_Name != '') {
                                $storeissue->Organization_Used = $request->Organization_Name;
                            } 
                            // else {
                            //     $storeissue->Organization_Used = $check->Organization_Name;
                            // }
                            $storeissue->Material_id=$fetch_material->Material_id;
                            $storeissue->userID=$userID;
                            $storeissue->issueQTY=$inputs['issueQTY'][$key];
                            $storeissue->issueNO=$issueNO;
                            $storeissue->recived_by=$request->recived_by;
                            $storeissue->save();
                            if (isset($request->Organization_Name) && $request->Organization_Name != '') {
                                $materialqty=Master_Raw_Material::where(['Organization'=>$request->Organization_Name,'Godown_Name'=>$check->Godown_Name,'Material'=>$fetch_material->Material_id]);
                            }else{
                                $materialqty=Master_Raw_Material::where(['Organization'=>$check->Organization_Name,'Godown_Name'=>$check->Godown_Name,'Material'=>$fetch_material->Material_id]);
                            }
                
                            $qtyss=$materialqty->get()->first();
                            $materialqty->update(['Quantity'=>abs($qtyss->Quantity-$inputs['issueQTY'][$key])]);
                        }
                    }
                }
         }
         
         //$Store_Requistion->update(['recived_by'=>$request->recived_by]);
        // $update=Store_Requistion_Material::where('id',$request->id)->update(['Store_issue_status'=>2]);
        // $master_material=Master_Raw_Material::where(['Organization'=>$Store_Requistion->Organization_Name,'Godown_Name'=>$Store_Requistion->Godown_Name])->get();
        return redirect('Storeissue/StoreissueList')->with('message','Issue Data Updated');
    }
    public function ExportEmployee(Request $request)
    {
        $employeedata = Gatepass_Employee::orderBy('id', 'DESC')->get();

        $employeedata_arr = array();
        foreach ($employeedata as $val) {
            $val->emp_name = Admin::find($val->employee_name);
            $val->request_type = Master_Request_Type::find($val->request_type);

            array_push($employeedata_arr, $val);
        }

        $Checkbox = CheckBox::where('userID', auth()->user()->id)->where('tableID', 17)->get();

        $Checkbox_Arr = [];
        foreach ($Checkbox as $val) {
            $valuee = $val->CheckBox;
            array_push($Checkbox_Arr, $valuee);
        }

        $d = [];
        foreach ($employeedata_arr as $key => $val) {
            $rowData = [
                "SL. No." => $key + 1,
                "Creator Name" => isset($val->user->name) && $val->user->name != '' ? $val->user->name : '',
                "Date & Time" => isset($val->created_at) && $val->created_at != '' ? date('d-m-Y H:i:s A', strtotime($val->created_at)) : '',
                "Request No" => isset($val->request_no) && $val->request_no != '' ? $val->request_no : '',
                "Request By" => isset($val->request_by) && $val->request_by != '' ? $val->request_by : '',
                "Gate Pass No" => isset($val->gate_pass_no) && $val->gate_pass_no != '' ? $val->gate_pass_no : '',
                "Request Date" => isset($val->request_date) && $val->request_date != '' ? $val->request_date : '',
                "Request Time" => isset($val->request_time) && $val->request_time != '' ? $val->request_time : '',
                "Employee Name" => isset($val->emp_name->employee_name) && $val->emp_name->employee_name != '' ? $val->emp_name->employee_name : '',
                "Request Type" => isset($val->request_type->request_type) && $val->request_type->request_type != '' ? $val->request_type->request_type : '',
                "Request Out Time" => isset($val->request_out_time) && $val->request_out_time != '' ? $val->request_out_time : '',
                "Request In Time" => isset($val->request_in_time) && $val->request_in_time != '' ? $val->request_in_time : '',
                "Reason" => isset($val->reason) && $val->reason != '' ? $val->reason : '',
                "Remarks" => isset($val->remarks) && $val->remarks != '' ? $val->remarks : '',
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

        $file = "Employee_Gatepass_data.csv";
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
    public function getstorestock(Request $request)
    {
        $OrgId = $request->OrgId;
        $Godwonid = $request->Godwonid;

        $budetails=Master_Raw_Material::select('master_raw_material.Quantity','master_raw_material.Material')
                 ->where('Organization',$OrgId)
                 ->where('Godown_Name', $Godwonid)
                 ->get();
        return response()->json($budetails);
    }
}
