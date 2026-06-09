<?php 
use App\Models\Admin;
use App\Models\StoreRequistion\{Store_Requistion, Store_Requistion_Material, Store_Requistion_approve};
use App\Models\Storeissue\{StoreIssueApprove,StoreIssueApprovedMaterial};
use App\Models\Production\{Production_For_Sales, Production_For_Stock,ProductionData,ProductionBatch,Production,ProductionApprove};
use App\Models\Maintenance\{Maintenance_Assign,MaintenceSchedule, Maintenance_Assign_Data,Maintenance,MaintenanceAssignApprove};
use App\Models\Master\{MasterMachineChecklist};
use App\Models\BOM\{BOM, BOM_Material};
use App\Models\MaterialManagement\{MaterialManagement_Add_Material};
function Assign($id)
{
    $count1=0;
    $userid=auth()->user()->id;
    $count= Maintenance_Assign_Data::where(['Maintenance_Assign_id'=>$id,'Assign_To'=>$userid])->count();
    if($count>0)
    {
        $count1=MaintenceSchedule::where(['maintenance_id'=>$id,'userid'=>$userid,'status'=>0,'schedule_date_str'=>strtotime(date('d-m-Y'))])->count();
    }
    return $count1;
}
function Assign_To($id,$type=0)
{
    $for=Maintenance_Assign_Data::where('Maintenance_Assign_id',$id)->get();
    $admin=Admin::all_admin();
    
    if($type==0)
    {
        $str='';
        $str.='<ul>';
        foreach($for as $value)
        {
            $str.='<li>'.$admin[$value->Assign_To].'</li>';
        }
        $str.='</ul>';
        //echo $str;
        //die;
        return $str;
    }
    if($type==1)
    {
        $arr=[];
        foreach($for as $value)
        {
           $arr[]=$value->Assign_To;
        }
        return $arr;
    }
    if($type==2)
    {
        $arr=[];
        foreach($for as $value)
        {
           $arr[]=$admin[$value->Assign_To];
        }
        return $arr;
    }
    else{
        return $for;
    }
}
function status($action)
{
    if($action=='APPROVE')
    {
        ?> <span style="color: #1bb81b;">APPROVED</span><?php
    }
    elseif($action=='REJECT')
   {
    ?> <span style="color:red">REJECTED</span><?php 
   }
    elseif($action=='RECHECK')
    {
        ?><span style="color:#71a5ee">RECHECK</span><?php 
    }
    elseif($action=='OBJECT')
    {
        ?><span style="color:#da2aff">OBJECT</span><?php 
    }
    elseif($action=='HOLD')
    {
        ?> <span style="color:#0cbad6">HOLD</span><?php 
    }
    else
    {
        ?><span style="color: #FF9000;">Pending</span><?php 
    }
    
   
}
function pre($arr,$de=false,$a=false)
{
    echo "<pre>";
    if($a==true)
    {
        print_r($arr->toArray());
    }
    else{
        print_r($arr);
    }
    
    echo "</pre>";
    if($de==true)
    {
        die;
    }
}
function nextid($currentid,$arr=[])
{   $arr=Session::get('nextid');
    $key=array_search ($currentid, $arr);
    if(isset($arr[$key+1]))
    {
        return $arr[$key+1];
    }
    else{
        return 0;
    }
}
function getday($date)
{
    $now = strtotime('now');
    $your_date = $date;
    $datediff = $now - $your_date;
    return round($datediff / (60 * 60 * 24));
}
function checkmaintance($lastdate,$type,$check)
{
    
     $day=getday($lastdate);
     //pre($day);
    
    if($type=='Weekly')
    {
        if($check=='TODAY')
        {
            if($day==8)
            {
                return 1;
            }
            return 0;
        }
        else{
            if($day>8)
            {
                return 2;
            }
            return 0;
        }

    }
    elseif($type=='Monthly')
    {
        if($check=='TODAY')
        {
            if($day==30)
            {
                return 1;
            }
            return 0;
        }
        else{
            if($day>30)
            {
                return 2;
            }
            return 0;
        }

    }
    elseif($type=='Quaterly')
    {
        if($check=='TODAY')
        {
            if($day==90)
            {
                return 1;
            }
            return 0;
        }
        else{
            if($day>90)
            {
                return 2;
            }
            return 0;
        }

    }
    elseif($type=='Halfyearly')
    {
        
        if($check=='TODAY')
        {
            if($day==180)
            {
                return 1;
            }
            return 0;
        }
        else{
            if($day>180)
            {
                return 2;
            }
            return 0;
        }

    }
    elseif($type=='Yearly')
    {
        if($check=='TODAY')
        {
            if($day==365)
            {
                return 1;
            }
            return 0;
        }
        else{
            if($day>365)
            {
                return 2;
            }
            return 0;
        }

    }
    else{
        return 0;
    }
}
function Pending_With($departments,$val)
{
   // pre($departments);
    if ($val->Forward_Status != 1)
    {
       $data=Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="'.$departments.'" AND step="' . $val->Approve_Step . '")')->get();
       
   } else {
       $data=Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data` WHERE DataID="' . $val->id . '" AND DepartmentID='.$departments.' AND status=0)')->get();
   }
   $str='';
    if($val->Approve_status==='FORWARD' || ($val->Approve_status=='' && isset($val->status) && $val->status!=1))
    {
    
        foreach($data as $name)
        {
           $str.= (isset($name->fullname) && $name->fullname!=''?$name->fullname:'').' ,';
        }
    }
    elseif($val->Approve_status=='RECHECK' || $val->Approve_status=='OBJECT')
    {
        $str.= isset($val->user->fullname) && $val->user->fullname!=''?'Pending With '.$val->user->fullname:'';
    }
    if($str!='')
    {
        return 'Pending With '.$str;
    }
   return ;
}
function hold($val,$class,$key)
{
    return $val->HoldStatus = $class::where($key, $val->id)->where('action', 'HOLD')->where('status', 1)->count()??0;
}
function Store_issue($approveID)
{
    $store_requstion=Store_Requistion::where('id',$approveID)->first();
    $Store_Requistion_material=Store_Requistion_Material::where('Store_Requistion_id',$approveID)->sum('QTY');
    $Store_Requistion_material_approve=StoreIssueApprovedMaterial::where(['Store_Requistion_id'=>$approveID,'action'=>'APPROVE'])->sum('issueQTY');
   //echo $Store_Requistion_material.'=='.$Store_Requistion_material_approve;
    if($Store_Requistion_material==$Store_Requistion_material_approve)
    {
        $store_requstion->update(['Store_issue_status'=>4]);
    }
   // die;
}
function get_batch($id)
{
    $batch=ProductionBatch::where('productionID',$id)->get()->first();
    return $batch->batch_no??'';
}
function getchecklist($id,$type=0)
{
    $dta=MasterMachineChecklist::where('MachineID',$id)->get();
    if($type==0)
    {
        $str='';
        $str.='<ul>';
        foreach($dta as $value)
        {
            $str.='<li>'.$value->Checklist.'</li>';
        }
        $str.='</ul>';
        return $str;
    }
    else{
        return $dta;
    }
}