<?php

namespace App\Http\Controllers\Storeissue;

use App\Http\Controllers\Controller;
use App\Http\Controllers\OtherController;
use Illuminate\Http\Request;
use App\Models\Storeissue\{Store_issue};
use App\Models\{CheckBox, Admin,PlantStock};
use App\Models\StoreRequistion\{Store_Requistion, Store_Requistion_Material, Store_Requistion_approve};
use App\Models\Storeissue\{StoreIssueApprove,StoreIssueApprovedMaterial};
use App\Models\FactoryCreater\{Factory_Organisation, Factory_Uom,prj_organisation,unitname,Factory_Address_Detail};
use App\Models\Master\Plant\{Master_Manufacturing_unit,Master_Customer_Name, Master_BU};
use App\Models\Master\{Master_Plant_Machinery,Prj_Subproject,Prj_Project,Module_Bsns_Unit,Prj_Inventory,Pur_Address};
use App\Models\Master\RawMaterial\{Master_Godown_Name,Master_Raw_Material};
use App\Models\BOM\{BOM, BOM_Material};
use App\Models\MaterialManagement\{MaterialManagement_Add_Material};
use Illuminate\Support\Facades\Auth;
use Session;

class StoreissueApproverViewController extends Controller
{
    public function Input_Approve(Request $request)
    {
        $userid=Auth::guard('admin')->id();
        $check = Store_Requistion::find($request->approveID);
        $approve = new StoreIssueApprove;
        $approve->userID = $userid;
        $approve->role = 'Inputer';
        $approve->Store_Requistion_id = $request->approveID;
        $approve->status = 1;
        if ($request->during_approval != '') {
            $approve->action = $request->during_approval;
        } elseif ($request->pre_post_approval != '') {
            $approve->pre_post_approval = $request->pre_post_approval;
        } else {
            $approve->action = 'Replied';
        }
        $approve->comment_text = $request->comment_text;
        $approve->ip_address = $request->getClientIp();
        $approve->device_name = $request->server('HTTP_USER_AGENT');
        $approve->days_for_holding = $request->days_for_holding;
        $approve->Forward_To = $request->Forward_To;
        $sqry=$approve->save();
        if ($request->during_approval == '' && $request->pre_post_approval == '') {
            Store_Requistion::where('id', $request->approveID)->update(['Approve_statusForIssue' => null]);
            }
 
        return redirect('Storeissue/StoreissueList')->with('success', 'successfully.....');;
    }
    public function approve(Request $request)
    {
        //return $request->all();

        $materialid=explode(",",$request->materialid);
       // pre($materialid,true);
        $userid=Auth::guard('admin')->id();
        $check = Store_Requistion::find($request->approveID);
        $approve = new StoreIssueApprove;
        $approve->userID = $userid;
        $approve->role = 'Approver';
        $approve->Store_Requistion_id = $request->approveID;
        $approve->status = 1;
       
        if ($request->during_approval != '')
        {
            
            $approve->action = $request->during_approval;
        } 
        elseif ($request->pre_post_approval != '') 
        {
            $approve->pre_post_approval = $request->pre_post_approval;
        } 
        else 
        {
            $approve->action = 'Replied';
        }
        $approve->comment_text = $request->comment_text;
        $approve->ip_address = $request->getClientIp();
        $approve->device_name = $request->server('HTTP_USER_AGENT');
        $approve->days_for_holding = $request->days_for_holding;
        $approve->Forward_To = $request->Forward_To;
        $sqry=$approve->save();
        if ($request->during_approval === 'APPROVE') {
           
            $material=StoreIssueApprovedMaterial::where(['Store_Requistion_id'=>$request->approveID,'status'=>0])->whereIn('id',$materialid)->get();
            foreach($material as $materialdata)
            {
                // $materialqty=Master_Raw_Material::where(['Organization'=>$check->Organization_Name,'Godown_Name'=>$check->Godown_Name,'Material'=>$materialdata->Material_id]);
                // $qty=$materialqty->get()->first();
               $plantstock= PlantStock::where(['plantID'=>$check->Plant_Name,'materialID'=>$materialdata->Material_id,'type'=>0]);
               if($plantstock->count()>0)
               {
                    $getstock=$plantstock->get()->first();
                    $stock=$getstock->stock+$materialdata->issueQTY;
                    $plantstock->update(['stock'=>$stock]);
               }
               else{
                    $plantstock =new PlantStock;
                    $plantstock->plantID=$check->Plant_Name;
                    $plantstock->materialID=$materialdata->Material_id;
                    $plantstock->stock=$materialdata->issueQTY;
                    $plantstock->save();
               }
               //$materialqty->update(['Quantity'=>abs($qty->Quantity-$materialdata->issueQTY)]);
            }
            StoreIssueApprovedMaterial::where(['Store_Requistion_id'=>$request->approveID,'status'=>0])->whereIn('id',$materialid)->update(['status'=>1,'recived_ApproverID'=>$userid,'actionID'=>$approve->id,'action'=>$request->during_approval]);
            //Store_issue($request->approveID);
             //add custom start
                // Fetch store requisition
                $store_requstion = Store_Requistion::find($request->approveID);         
                
                // Fetch all requisition materials
                $Store_Requistion_materials = Store_Requistion_Material::where('Store_Requistion_id', $request->approveID)->get();
                
                $totsum = 0;
                
                // Check if there are materials
                if ($Store_Requistion_materials->isNotEmpty()) {
                    foreach ($Store_Requistion_materials as $material) {
                        if ($material->Store_issue_status != "2") {
                            $totsum += $material->QTY;  // Sum QTY for all rows where status is not "2"
                        }
                        if ($material->Store_issue_status == "2" && $material->StoreIssue_Approve_qty > 0) {
                            $totsum += $material->StoreIssue_Approve_qty;  // Sum Approved QTY where status is "2"
                        }
                    }
                }
                // Get approved issue quantity
                $Store_Requistion_material_approve = StoreIssueApprovedMaterial::where([
                    'Store_Requistion_id' => $request->approveID,
                    'action' => 'APPROVE'
                ])->sum('issueQTY');
                
                // Compare sums and update status
                // if ((float)$totsum === (float)$Store_Requistion_material_approve) {
                //     $store_requstion->Store_issue_status = 4;
                //     $store_requstion->save();
                // }
                $totsum = number_format($totsum, 2, '.', ''); 
                $Store_Requistion_material_approve = number_format($Store_Requistion_material_approve, 2, '.', '');
                
                if (bccomp($totsum, $Store_Requistion_material_approve, 2) === 0) {
                    $store_requstion->Store_issue_status = 4;
                    $store_requstion->save();
                }
                //add custom end
            
            
        }
        elseif ($request->during_approval === 'REJECT') {
            $material=StoreIssueApprovedMaterial::where(['Store_Requistion_id'=>$request->approveID,'status'=>0])->whereIn('id',$materialid)->get();
            foreach($material as $materialdata)
            {
                if(isset($materialdata->Organization_Used) && $materialdata->Organization_Used != ''){
                $materialqty=Master_Raw_Material::where(['Organization'=>$materialdata->Organization_Used,'Godown_Name'=>$check->Godown_Name,'Material'=>$materialdata->Material_id]);
                }else{
                $materialqty=Master_Raw_Material::where(['Organization'=>$check->Organization_Name,'Godown_Name'=>$check->Godown_Name,'Material'=>$materialdata->Material_id]);
                }
                
                $qty=$materialqty->get()->first();
                $materialqty->update(['Quantity'=>abs($qty->Quantity+$materialdata->issueQTY)]);
                $storeMaterial=Store_Requistion_Material::where('id',$materialdata->Store_Requistion_material_id);
                $storeqty=$storeMaterial->get()->first();
                $storeMaterial->update(['StoreIssue_Approve_qty'=>abs($storeqty->StoreIssue_Approve_qty-$materialdata->issueQTY)]);
            }
            StoreIssueApprovedMaterial::where(['Store_Requistion_id'=>$request->approveID,'status'=>0])->whereIn('id',$materialid)->update(['status'=>2,'recived_ApproverID'=>$userid,'actionID'=>$approve->id,'action'=>$request->during_approval]);
            Store_Requistion::where('id',$request->approveID)->update(['recived_by'=>0,'Approve_statusForIssue'=>($request->during_approval??'')]);
        }
        elseif ($request->during_approval === 'RECHECK') 
        {
            $material=StoreIssueApprovedMaterial::where(['Store_Requistion_id'=>$request->approveID,'status'=>0])->whereIn('id',$materialid)->get();
            foreach($material as $materialdata)
            {
                if(isset($materialdata->Organization_Used) && $materialdata->Organization_Used != ''){
                    $materialqty=Master_Raw_Material::where(['Organization'=>$materialdata->Organization_Used,'Godown_Name'=>$check->Godown_Name,'Material'=>$materialdata->Material_id]);
                }else{
                    $materialqty=Master_Raw_Material::where(['Organization'=>$check->Organization_Name,'Godown_Name'=>$check->Godown_Name,'Material'=>$materialdata->Material_id]);
                }
                
                $qty=$materialqty->get()->first();
                $materialqty->update(['Quantity'=>$qty->Quantity+$materialdata->issueQTY]);
                $storeMaterial=Store_Requistion_Material::where('id',$materialdata->Store_Requistion_material_id);
                $storeqty=$storeMaterial->get()->first();
                $storeMaterial->update(['StoreIssue_Approve_qty'=>$storeqty->StoreIssue_Approve_qty-$materialdata->issueQTY]);
            }
            StoreIssueApprovedMaterial::where(['Store_Requistion_id'=>$request->approveID,'status'=>0])->whereIn('id',$materialid)->update(['status'=>3,'recived_ApproverID'=>$userid,'actionID'=>$approve->id,'action'=>$request->during_approval]);
            Store_Requistion::where('id',$request->approveID)->update(['recived_by'=>0,'Approve_statusForIssue'=>$request->during_approval]);
        }
        elseif ($request->during_approval === 'HOLD') 
        {
            StoreIssueApprovedMaterial::where(['Store_Requistion_id'=>$request->approveID,'status'=>0])->whereIn('id',$materialid)->update(['status'=>4,'recived_ApproverID'=>$userid,'actionID'=>$approve->id,'action'=>$request->during_approval,'hold_date'=>$request->days_for_holding]);
            Store_Requistion::where('id',$request->approveID)->update(['Approve_statusForIssue'=>$request->during_approval]);
        }
        // elseif ($request->during_approval === 'OBJECT') 
        // {
        //     StoreIssueApprovedMaterial::where(['Store_Requistion_id'=>$request->approveID,'status'=>0])->update(['status'=>5,'recived_ApproverID'=>$userid,'actionID'=>$approve->id,'action'=>$request->during_approval]);
        //     Store_Requistion::where('id',$request->approveID)->update(['recived_by'=>0,'Approve_statusForIssue'=>$request->during_approval]);
        // }
        // elseif ($request->during_approval === 'FORWARD') 
        // {
        //     StoreIssueApprovedMaterial::where(['Store_Requistion_id'=>$request->approveID,'status'=>0])->update(['status'=>6,'recived_ApproverID'=>$userid,'actionID'=>$approve->id,'action'=>$request->during_approval]);
        //     Store_Requistion::where('id',$request->approveID)->update(['recived_by'=>0,'Approve_statusForIssue'=>$request->during_approval]);
        // }
        // elseif ($request->during_approval === 'AUDIT') 
        // {
        //     StoreIssueApprovedMaterial::where(['Store_Requistion_id'=>$request->approveID,'status'=>0])->update(['status'=>7,'recived_ApproverID'=>$userid,'actionID'=>$approve->id,'action'=>$request->during_approval]);
        //     Store_Requistion::where('id',$request->approveID)->update(['recived_by'=>0,'Approve_statusForIssue'=>$request->during_approval]);
        // }
        // elseif ($request->during_approval === 'INTIMATION') 
        // {
        //     StoreIssueApprovedMaterial::where(['Store_Requistion_id'=>$request->approveID,'status'=>0])->update(['status'=>8,'recived_ApproverID'=>$userid,'actionID'=>$approve->id,'action'=>$request->during_approval]);
        //     Store_Requistion::where('id',$request->approveID)->update(['recived_by'=>0,'Approve_statusForIssue'=>$request->during_approval]);
        // }
        // elseif ($request->during_approval === 'QUERY') 
        // {
        //     StoreIssueApprovedMaterial::where(['Store_Requistion_id'=>$request->approveID,'status'=>0])->update(['status'=>9,'recived_ApproverID'=>$userid,'actionID'=>$approve->id,'action'=>$request->during_approval]);
        //     Store_Requistion::where('id',$request->approveID)->update(['recived_by'=>0,'Approve_statusForIssue'=>$request->during_approval]);
        // }

        // if ($request->during_approval === 'REJECT') {
        //     MaterialManagement_Add_Material::where('id', $check->Raw_Material)->update(['Used_Status' => 0]);
        // }

        // if ($request->during_approval === 'FORWARD') {
        //     Forwarded_Data::where(['DepartmentID' => 15, 'DataID' => $request->approveID])->update(['status' => 1]);
        //     Store_Requistion::where('id', $request->approveID)->update(['Forward_Status' => 1]);

        //     $forward = new Forwarded_Data;
        //     $forward->userID = auth()->user()->id;
        //     $forward->Forward_To_id = $request->Forward_To;
        //     $forward->DepartmentID = 15;
        //     $forward->DataID = $request->approveID;
        //     $forward->status = 0;

        //     $forward->save();
        // }

        // $approve = new Store_Requistion_approve;
        // $approve->userID = auth()->user()->id;
        // if (auth()->user()->role == 0) {
        //     $approve->role = 'Admin';
        // } elseif (isset($EXT[15]['Inputer'])) {
        //     $approve->role = 'Inputer';
        // } elseif (isset($EXT[15]['approver'])) {
        //     $approve->role = 'Approver';
        // } else {
        //     $approve->role = 'Viewer';
        // }
        // $approve->Store_Requistion_id = $request->approveID;
        // $approve->status = 1;
        // if ($request->during_approval != '') {
        //     $approve->action = $request->during_approval;
        // } elseif ($request->pre_post_approval != '') {
        //     $approve->pre_post_approval = $request->pre_post_approval;
        // } else {
        //     $approve->action = 'Replied';
        // }
        // $approve->comment_text = $request->comment_text;
        // $approve->ip_address = $request->getClientIp();
        // $approve->device_name = $request->server('HTTP_USER_AGENT');
        // $approve->days_for_holding = $request->days_for_holding;
        // $approve->Forward_To = $request->Forward_To;

        // $approve->save();

        // if ($request->during_approval == '' && $request->pre_post_approval == '') {
        //     Store_Requistion::where('id', $request->approveID)->update(['Approve_status' => null]);
        //     return redirect('StoreRequistion/StoreRequistionList')->with('success', 'successfully.....');
        // } elseif (($request->pre_post_approval == 'AUDIT' || $request->pre_post_approval == 'INTIMATION' || $request->pre_post_approval == 'QUERY') && $request->non_acting == 1) {
        //     return redirect('StoreRequistion/StoreRequistionList')->with('success', 'successfull.....');
        // } else {
        //     return redirect('StoreRequistion/StoreRequistionApproveList')->with('success', 'Approved successfully.....');
        // }
        return redirect('Storeissue/StoreissueApproveList');
    }
    public function StoreissueList(Request $request)
    {
        $EXT = Session::get('EXT');
        $userid=Auth::guard('admin')->id();
        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));
        $query = Store_Requistion::where(['Approve_status'=>'APPROVE'])->orderBy('id', 'DESC');

        if ($fromdate && $todate) {
            $query->whereBetween('created_at', [$fromdate, $todate]);
        }

        $store = [];
        $nextid=[];
        foreach($query->get() as $val)
        {
            //pre($val);
            $val->Creater=Admin::find($val->userID);
            $val->Organization=prj_organisation::find($val->Organization_Name);
            $val->Manufacturing=prj_project::find($val->Manufacturing_Unit);
            $val->Plant_name=Prj_Subproject::find($val->Plant_Name);
            $val->Godown_Name = Prj_Inventory::find($val->Godown_Name);
            $cout=StoreIssueApprovedMaterial::where(['Store_Requistion_id'=>$val->id,'status'=>0,'recived_by'=>$userid])->count();
            if($cout>0)
            {
                $store[]=$val;
                $nextid[]=$val->id;
            }
           
            // $store[$val->Store_issue_status][]=$val;
        }
        Session::put('nextid',$nextid);
        // $store_arr = array();
        // foreach ($store as  $val) {
        //     $val->user = Admin::find($val->userID);

        //     array_push($store_arr, $val);
        // }

        return view('Storeissue/StoreissueApproveList', ['store' =>$store, 'fromdate' => $fromdate, 'todate' => $dateto]);
    }

    public function AddStoreissue($id = null)
    {
        // $appro = Store_Requistion_approve::where('Store_Requistion_id', $id)->get();
        // $approves = [];
        // foreach ($appro as $val) {
        //     $val->user = Admin::find($val->userID);
        //     array_push($approves, $val);
        // }

        // $Organization_Name = Factory_Organisation::all();
        // $Manufacturing_Unit = Master_Manufacturing_unit::all();
        // $Plant_Name = Master_Plant_Machinery::all();
        // $UOM = Factory_Uom::all();
        // $Godown_Name = Master_Godown_Name::all();
        // $employeeName = Admin::where('role', 1)->whereRaw('id IN (SELECT userID FROM Employee_Department where Departments="15")')->get();
        // $BOM_DATA = BOM::where('Approve_status', 'APPROVE')->get();
        // $Raw_Material = [];
        // foreach ($BOM_DATA as $Val) {
        //     if (isset($Val->Raw_Material_FG)) {
        //         $Val->RawMaterial = MaterialManagement_Add_Material::find($Val->Raw_Material_FG);
        //         $Raw_Material[$Val->Raw_Material_FG] = $Val;
        //     }
        // }
        // $Filtered_Array = array_values($Raw_Material);
        // $edit = Store_Requistion::find($id);
        // $Materials = array();
        // if (isset($edit->id) && $edit->id != '') {
           // $Materials = Store_Requistion_Material::where('Store_Requistion_id', $edit->id)->get();
        //}
        $val = Store_Requistion::find($id);
        //$val=$query->get()->first();
        $userid=Auth::guard('admin')->id();
        $val->Creater=Admin::find($val->userID);
        $val->Organization=Factory_Organisation::find($val->Organization_Name);
        $val->Manufacturing=Master_Manufacturing_unit::find($val->Manufacturing_Unit);
        $val->Plant_name=Master_Plant_Machinery::find($val->Plant_Name);
        $val->Godown_Name = Master_Godown_Name::find($val->Godown_Name);
        $UOM = Factory_Uom::all();
        $Materials = Store_Requistion_Material::where(['Store_Requistion_id', $val->id,'recived_by'=> $userid])->get();
        $master_material=Master_Raw_Material::where(['Organization'=>$val->Organization_Name,'Godown_Name'=>$val->Godown_Name->id])->get();
        $stock=[];
        foreach($master_material as $value)
        {
            $stock[$value->Material]=$value;
        }
       
        return view('Storeissue/Storeissue',compact('val','Materials','UOM','stock'));
       

        //return view('Storeissue/Storeissue', ['edit' => $edit, 'STORE_DATA' => $STORE_DATA]);
    }
    public function ViewStoreissue($id = null)
    {
        $userid=Auth::guard('admin')->id();
        $admindata=Admin::all_admin();
        $appro = StoreIssueApprove::where('Store_Requistion_id', $id)->get();
        $approves1 = StoreIssueApprovedMaterial::select('store_issue_approved_material.*','prj_organisation.organisation','prj_organisation.id as orgid')
                    ->leftJoin('prj_organisation','store_issue_approved_material.Organization_Used','=','prj_organisation.id')
                    ->where(['Store_Requistion_id'=> $id,'recived_by'=> $userid,'store_issue_approved_material.status'=>0])->get();

        
        $approves = [];
        foreach ($appro as $val) {
            $val->user = Admin::find($val->userID);
            //if($val->action!=null) array_push($approves, $val);
            array_push($approves, $val);
        }
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

        foreach($approves1 as $mat)
        {
            $Matxx= Store_Requistion_Material::where('id', $mat->Store_Requistion_material_id)->where('QTY','>',0)->whereIn('Store_issue_status',['0','3'])->get()->first();
            $Matxx->material_data=$mat;
            $Materials[]=$Matxx;
        }
        
        
        $uom_data=[];
        foreach($UOM->toArray() as $Valddd)
        {
            $uom_data[$Valddd['id']]=$Valddd;
        }
        // echo "<pre>";
        // print_r($edit);
        // echo "</pre>";
        
        // die;
        $master_material=Master_Raw_Material::where(['Organization'=>$edit->Organization_Name,'Godown_Name'=>$edit->Godown_Name])->get();
        $stock=[];
        foreach($master_material as $value)
        {
            $stock[$value->Material]=$value;
        }
        

        return view('Storeissue/StoreissueApproverData', ['edit' => $edit, 'Organization_Name' => $Organization_Name, 'Manufacturing_Unit' => $Manufacturing_Unit, 'Plant_Name' => $Plant_Name, 'Raw_Material' => $Filtered_Array, 'UOM' => $UOM, 'Godown_Name' => $Godown_Name, 'Materials' => $Materials,'stock'=>$stock,'uom_data'=>$uom_data,'admin'=>$admin,'approves'=>$approves,'approves1'=>$approves1,'admindata'=>$admindata]);
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
    public function forclose(Request $request)
    {
        if(empty($request->id))
        {
           return OtherController::fail('Id missing');
        }
        $update=Store_Requistion_Material::where('id',$request->id)->update(['Store_issue_status'=>2]);
        $value=Store_Requistion_Material::where('id',$request->id);
        $count=Store_Requistion_Material::where('Store_Requistion_id',$value->Store_Requistion_id)->whereIn('Store_issue_status',[0,3])->count();
        if($count<1)
        {
            Store_Requistion::where(['id'=>$value->Store_Requistion_id])->update(['Store_issue_status'=>2]);
        }
        if($update)
        {
           // return OtherController::pass('Request material for closed');
            return OtherController::pass($count);
        }
        else{
            return OtherController::fail('Somthing went wrong');
        }

    }
    public function issueqty(Request $request)
    {
        // echo "<pre>";
        // print_r($request->all());
        // echo "</pre>";
        $inputs=$request->all();
         $Store_Requistion = Store_Requistion::where('id',$request->edit)->update(['recived_by'=>$request->recived_by]);
         if(isset($request->Material_id)){
         foreach($request->Material_id as $key=> $val)
         {
            $fetch_material=Store_Requistion_Material::find($val);
            $qty=$inputs['issueQTY'][$key]+$fetch_material->issueQTY;
            Store_Requistion_Material::where('id',$val)->update(['StoreIssue_Approve_qty'=>$qty]);
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
}
