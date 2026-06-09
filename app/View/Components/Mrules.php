<?php

namespace App\View\Components;
use App\Models\Production\{Production_For_Sales, Production_For_Stock,ProductionData,ProductionBatch,Production,ProductionApprove};
use App\Models\QCSampleTesting\{QCFinishedGoodResult, QCFinishedGood,QCFinishedGoodApprove};
use Illuminate\View\Component;
use App\Models\InventoryManagement\{Inventory_Management};
use App\Models\Maintenance\{Maintenance,Maintenance_Assign};
use App\Models\SampleFreeGood\{SampleFreeGood, SampleFreeGood_data, SampleFreeGoodApprove};


class Mrules extends Component
{
    public $Type;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($type)
    {
        $this->Type=$type;
    }
    public function production()
    {
        // Use single query with CASE to count all statuses at once
        $counts = Production::selectRaw("
            COUNT(*) as all_count,
            COUNT(CASE WHEN Approve_status = 'APPROVE' THEN 1 END) as approved_count,
            COUNT(CASE WHEN Approve_status IS NULL OR Approve_status = 'FORWARD' THEN 1 END) as pending_count,
            COUNT(CASE WHEN Approve_status = 'HOLD' THEN 1 END) as hold_count,
            COUNT(CASE WHEN Approve_status = 'RECHECK' THEN 1 END) as recheck_count,
            COUNT(CASE WHEN Approve_status = 'OBJECT' THEN 1 END) as object_count,
            COUNT(CASE WHEN Approve_status = 'REJECT' THEN 1 END) as reject_count
        ")->first();
        
        $data['All'] = $counts->all_count;
        $data['Approved'] = $counts->approved_count;
        $data['Pending'] = $counts->pending_count;
        $data['Hold'] = $counts->hold_count;
        $data['Recheck'] = $counts->recheck_count;
        $data['Object'] = $counts->object_count;
        $data['Reject'] = $counts->reject_count;
        
        return $data;
    }
    public function qcfinishedGood()
    {
        $QCFinishedGood=new QCFinishedGood;
        $data['All']=$QCFinishedGood->count();
        $data['Approved']=$QCFinishedGood->where('Approve_status','APPROVE')->count();
        $data['Pending']=$QCFinishedGood->where('Approve_status',null)->orWhere('Approve_status', 'FORWARD')->count();
        $data['Hold']=$QCFinishedGood->where('Approve_status','HOLD')->count();
        $data['Recheck']=$QCFinishedGood->where('Approve_status','RECHECK')->count();
        $data['Object']=$QCFinishedGood->where('Approve_status','OBJECT')->count();
        $data['Reject']=$QCFinishedGood->where('Approve_status','REJECT')->count();
        return $data;
    }

    public function samplefreegood()
    {
        $QCFinishedGood=new SampleFreeGood;
        $data['All']=$QCFinishedGood->count();
        $data['Approved']=$QCFinishedGood->where('Approve_status','APPROVE')->count();
        $data['Pending']=$QCFinishedGood->where('Approve_status',null)->orWhere('Approve_status', 'FORWARD')->count();
        $data['Hold']=$QCFinishedGood->where('Approve_status','HOLD')->count();
        $data['Recheck']=$QCFinishedGood->where('Approve_status','RECHECK')->count();
        $data['Object']=$QCFinishedGood->where('Approve_status','OBJECT')->count();
        $data['Reject']=$QCFinishedGood->where('Approve_status','REJECT')->count();
        return $data;
    }
    public function inventorymanagment()
    {
        $QCFinishedGood=new Inventory_Management;
        $data['All']=$QCFinishedGood->count();
        $data['Approved']=$QCFinishedGood->where('Approve_status','APPROVE')->count();
        $data['Pending']=$QCFinishedGood->where('Approve_status',null)->orWhere('Approve_status', 'FORWARD')->count();
        $data['Hold']=$QCFinishedGood->where('Approve_status','HOLD')->count();
        $data['Recheck']=$QCFinishedGood->where('Approve_status','RECHECK')->count();
        $data['Object']=$QCFinishedGood->where('Approve_status','OBJECT')->count();
        $data['Reject']=$QCFinishedGood->where('Approve_status','REJECT')->count();
        return $data;
    }
    public function maintanceassign()
    {
        $QCFinishedGood=new Maintenance;
        $data['All']=$QCFinishedGood->count();
        $data['Approved']=$QCFinishedGood->where('Approve_status','APPROVE')->count();
        $data['Pending']=$QCFinishedGood->where('Approve_status',null)->orWhere('Approve_status', 'FORWARD')->count();
        $data['Hold']=$QCFinishedGood->where('Approve_status','HOLD')->count();
        $data['Recheck']=$QCFinishedGood->where('Approve_status','RECHECK')->count();
        $data['Object']=$QCFinishedGood->where('Approve_status','OBJECT')->count();
        $data['Reject']=$QCFinishedGood->where('Approve_status','REJECT')->count();
        return $data;
    }
    public function maintancecompeted()
    {
        $QCFinishedGood=new Maintenance_Assign;
        $data['All']=$QCFinishedGood->count();
        $data['Approved']=$QCFinishedGood->where('Approve_status','APPROVE')->count();
        $data['Pending']=$QCFinishedGood->where('Approve_status',null)->orWhere('Approve_status', 'FORWARD')->count();
        $data['Hold']=$QCFinishedGood->where('Approve_status','HOLD')->count();
        $data['Recheck']=$QCFinishedGood->where('Approve_status','RECHECK')->count();
        $data['Object']=$QCFinishedGood->where('Approve_status','OBJECT')->count();
        $data['Reject']=$QCFinishedGood->where('Approve_status','REJECT')->count();
        return $data;
    }
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $func=$this->Type;
        $data=$this->$func();
        return view('components.mrules',compact('data'));
    }
}
