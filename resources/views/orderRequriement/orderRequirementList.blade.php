@extends('layout.main')
@section('main-container')
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">

<style>
    * {

        box-sizing: border-box;

    }

    body {

        background-color: #f1f1f1;

    }

    #regForm {
        background-color: #ffffff;
        font-family: Raleway;
        width: 100%;
    }

    h1 {

        text-align: center;

    }

    input {

        padding: 10px;

        width: 100%;

        font-size: 17px;

        font-family: Raleway;

        border: 1px solid #aaaaaa;

    }

    /* Mark input boxes that gets an error on validation: */

    input.invalid {

        background-color: #ffdddd;

    }

    /* Hide all steps by default: */

    .tab {

        display: none;

    }

    .btn1 {

        background-color: #95f3ff;

    }

    .btn1:hover {

        background-color: #e0f7fa;

    }

    button {

        background-color: #04AA6D;

        color: #ffffff;

        border: none;

        padding: 10px 20px;

        font-size: 17px;

        font-family: Raleway;

        cursor: pointer;

    }

    button:hover {

        opacity: 0.8;

    }

    #prevBtn {

        background-color: #bbbbbb;

    }

    /* Make circles that indicate the steps of the form: */

    .step {

        height: 15px;

        width: 15px;

        margin: 0 2px;

        background-color: #bbbbbb;

        border: none;

        border-radius: 50%;

        display: inline-block;

        opacity: 0.5;

    }

    .step.active {

        opacity: 1;

    }

    .addbtn {
        display: flex;
        justify-content: flex-end;
        padding: 10px 12px;
        margin-top: -3%;
    }

    td.maindffd {
        display: flex;
        justify-content: space-evenly;
        width: 100%;
    }

    select.custom-select.custom-select-sm.form-control.form-control-sm {
        margin-top: 3px;
    }

    .left-bar p {
        margin: 4% !important;
    }

    .activesle {
        background: #6741D5 !important;
    }


    div#myFilter {
        position: absolute;
        background: white;
        z-index: 99;
        padding: 10px 15px;
        box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
        right: 10px;
    }


    .raone p.raho {
        background: green;
        display: flex;
        align-items: center;
        justify-content: center;
        align-content: center;
        padding: 10px 12px;
        color: white;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 5px;
    }

    .FilterButtonnn {
        width: 99%;
        display: flex;
        align-items: center;
        justify-content: flex-end;
    }

    #myFilter {
        display: none;
    }

    .show-div {
        display: block !important;
    }

    .addbtn i.fas.fa-file-excel {
        font-size: 20px;
        color: green;
        margin-top: 13px;
        margin-right: 10px;
    }
</style>
@php
$Department=Session::get('Department');
$EXT=Session::get('EXT');
@endphp
<div class="card">
    <div class="app-content">
        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <section class="section">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Procurement Requriement Stock List</li>
            </ol>
            <div class="addbtn">
                @if(isset($EXT[18]['inputer']))
                <a href="{{url('orderRequirement/orderRequirement')}}"><button class="btn btn-info">Add Procurement Request</button></a>
                @endif
            </div>
            <div class="row">
                <div class="container">
                    <div class="row filter" id="allfilter">
                        
                    </div>
                    <div class="row filter">
                        <div class="FilterButtonnn sales_fields">
                            <div class="raone">
                                <p class="raho MyToggle" id="MyToggle">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-funnel-fill" viewBox="0 0 16 16">
                                        <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z" />
                                    </svg>
                                </p>
                                <div class="ukom myFilter" id="myFilter">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="ToggleCheck" onclick="toggleCheckboxes()">
                                        <label class="form-check-label" for="ToggleCheck">All</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="NO" value="SL. No." onclick="filterTable(this)">
                                        <label class="form-check-label" for="NO">SL. No.</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="Creater_Name" value="Creater Name" onclick="filterTable(this)">
                                        <label class="form-check-label" for="Creater_Name">Creater Name</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="Order_Type" value="Order Type" onclick="filterTable(this)">
                                        <label class="form-check-label" for="Order_Type">Order Type</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="Procurement_Type" value="Procurement Type" onclick="filterTable(this)">
                                        <label class="form-check-label" for="Order_Type">Procurement Type</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="Sales_Order_No" value="PR No" onclick="filterTable(this)">
                                        <label class="form-check-label" for="Sales_Order_No">PR No</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="Organization_Name" value="Organization Name" onclick="filterTable(this)">
                                        <label class="form-check-label" for="Organization_Name">Organization Name</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="BU_Name" value="BU Name" onclick="filterTable(this)">
                                        <label class="form-check-label" for="BU_Name">BU Name</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="Unit_Name" value="Unit Name" onclick="filterTable(this)">
                                        <label class="form-check-label" for="Unit_Name">Unit Name</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="Plant_Name" value="Plant Name" onclick="filterTable(this)">
                                        <label class="form-check-label" for="Plant_Name">Plant Name</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="Godowm_Name" value="Godowm Name" onclick="filterTable(this)">
                                        <label class="form-check-label" for="Godowm_Name">Godowm Name</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="Raw_Material(FG)" value="Finished Good(FG)" onclick="filterTable(this)">
                                        <label class="form-check-label" for="Raw_Material(FG)">Finished Good(FG)</label>
                                    </div>
                                    {{-- <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="Company_Name" value="Company Name" onclick="filterTable(this)">
                                        <label class="form-check-label" for="Company_Name">Company Name</label>
                                    </div> --}}
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="Expected_Date" value="Expected Date" onclick="filterTable(this)">
                                        <label class="form-check-label" for="Expected_Date">Expected Date</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="QTY" value="QTY" onclick="filterTable(this)">
                                        <label class="form-check-label" for="QTY">QTY</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="Date_Time" value="Date & Time" onclick="filterTable(this)">
                                        <label class="form-check-label" for="Date_Time">Date & Time</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="total_amount" value="Total Amount" onclick="filterTable(this)">
                                        <label class="form-check-label" for="total_amount">Total Amount</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="Status" value="Status" onclick="filterTable(this)">
                                        <label class="form-check-label" for="Status">Status</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="Pending_With" value="Pending With" onclick="filterTable(this)">
                                        <label class="form-check-label" for="Pending_With">Pending With</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="Operation" value="Operation" onclick="filterTable(this)">
                                        <label class="form-check-label" for="Operation">Operation</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="" id="main_btn_uddhan">
                    <a  href=""type="button" class="btn btn-default" id="stock">Stock List</a>
                    <button type="button" class="btn btn-success" id="sales" >Sales List</button>
                    {{-- <a href="{{url('orderRequirement/orderRequirementList')}}" type="button" class="btn btn-success" id="sales">Sales List</a> --}}
                       
                    </div>
                    <br>
                    <div class="sales_fields">
                        <ul class="nav nav-tabs mb-3" id="ex1" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link count active" id="SalesAlls" data-mdb-toggle="tab" href="#SalesAll" role="tab" aria-controls="All" aria-selected="true">All <span class="countss">{{count($Stock)}}</span></a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link count" id="SalesApproveds" data-mdb-toggle="tab" href="#SalesApproved" role="tab" aria-controls="Approved" aria-selected="false">Approved <span class="countss">{{count($approved)}}</span></a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link count" id="SalesPendings" data-mdb-toggle="tab" href="#SalesPending" role="tab" aria-controls="Pending" aria-selected="false">Pending <span class="countss">{{count($pending)}}</span></a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link count" id="SalesHolds" data-mdb-toggle="tab" href="#SalesHold" role="tab" aria-controls="Hold" aria-selected="false">Hold <span class="countss">{{count($HOLD)}}</span></a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link count" id="SalesRechecks" data-mdb-toggle="tab" href="#SalesRecheck" role="tab" aria-controls="Recheck" aria-selected="false">Recheck <span class="countss">{{count($RECHECK)}}</span></a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link count" id="SalesObjects" data-mdb-toggle="tab" href="#SalesObject" role="tab" aria-controls="Object" aria-selected="false">Object <span class="countss">{{count($OBJECT)}}</span></a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link count" id="SalesRejects" data-mdb-toggle="tab" href="#SalesReject" role="tab" aria-controls="Reject" aria-selected="false">Reject <span class="countss">{{count($REJECT)}}</span></a>
                            </li>
                        </ul>
                        <div class="tab-content" id="ex1-content">
                            <div class="tab-pane fade show active" id="SalesAll" role="tabpanel" aria-labelledby="SalesAlls">
                                <div class="table-responsive">
                                    <table id="example2" class="table table-sm table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th class="th-sm">SL. No.</th>
                                                <th class="th-sm">Creater Name</th>
                                                <th class="th-sm">Order Type</th>
                                                <th class="th-sm">Procurement Type</th>
                                                <th class="th-sm">PR No</th>
                                                <th class="th-sm">Organization Name</th>
                                                <th class="th-sm">BU Name</th>
                                                <th class="th-sm">Godowm Name</th>
                                                <th class="th-sm">Unit Name</th>
                                                <th class="th-sm">Plant Name</th>
                                                <th class="th-sm">Finished Good(FG)</th>
                                                <th class="th-sm">Expected Date</th>
                                                <th class="th-sm">QTY</th>
                                                {{-- <th class="th-sm">Company Name</th> --}}
                                                <th class="th-sm">Date & Time</th>
                                                <th class="th-sm">Total Amount</th>
                                                <th class="th-sm">Status</th>
                                                <th class="th-sm">Pending With</th>
                                                <th class="th-sm">Operation</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                            $sesionarr=[];
                                            @endphp
                                            @foreach($Stock as $key => $val)
                                            @php
                                            if(isset($val->status) && $val->status!=1)
                                            {
                                            $sesionarr['ALL'][]=$val->id;
                                            if($val->Approve_status=='APPROVE')
                                            {
                                            $sesionarr['APPROVE'][]=$val->id;
                                            }
                                            elseif($val->Approve_status=='REJECT')
                                            {
                                            $sesionarr['REJECT'][]=$val->id;
                                            }
                                            elseif($val->Approve_status=='RECHECK')
                                            {
                                            $sesionarr['RECHECK'][]=$val->id;
                                            }
                                            elseif($val->Approve_status=='OBJECT')
                                            {
                                            $sesionarr['OBJECT'][]=$val->id;
                                            }
                                            elseif($val->Approve_status=='HOLD')
                                            {
                                            $sesionarr['HOLD'][]=$val->id;
                                            }
                                            else
                                            {
                                            $sesionarr['PENDING'][]=$val->id;
                                            }
                                            }
                                            @endphp
                                             <tr @if($val->procurement_type == "Additional") 
                                                style="background-color: #ff6f005e;" 
                                            @elseif($val->procurement_type == "Loose") 
                                                style="background-color: #FAFAD2;" 
                                            @endif>
                                                <td>{{$key+1}}</td>
                                                <td>{{isset($val->user->fullname) && $val->user->fullname != ''?$val->user->fullname:''}}</td>

                                                <td>{{$val->Work_Order_Type}}</td>
                                                <td>{{$val->procurement_type}}</td>
                                                <td>{{$val->Stock_Order_No}}</td>
                                                <td>{{isset($val->organisation) && $val->organisation != ''?$val->organisation:''}}</td>
                                                <td>{{isset($val->unit_name) && $val->unit_name != ''?$val->unit_name:''}}</td>
                                                <td>{{isset($val->inventory_name) && $val->inventory_name != ''?$val->inventory_name:''}}</td>
                                                <td>{{isset($val->pname) && $val->pname != ''?$val->pname:''}}</td>
                                                <td>{{isset($val->spname) && $val->spname != ''?$val->spname:''}}</td>
                                                <td>{{isset($val->RawMaterial->matname) && $val->RawMaterial->matname != ''?$val->RawMaterial->matname:''}}</td>
                                                <td>{{$val->Expected_Date??''}}</td>
                                                <td>{{$val->QTY??''}}</td>
                                                {{-- <td>{{$Company_Name[$val->Company_Name]??''}}</td> --}}
                                                <td>{{isset($val->created_at) && $val->created_at!=''?date('d-m-Y H:i:s A',strtotime($val->created_at)):''}}</td>
                                                <td>{{isset($val->Total) && $val->Total != ''?$val->Total:''}}</td>
                                                <td id="statuss{{$val->id}}">
                                                    @if($val->Approve_status=='APPROVE')
                                                    <span style="color: #1bb81b;">APPROVED</span>
                                                    @elseif($val->Approve_status=='REJECT')
                                                    <span style="color:red">REJECTED</span>
                                                    @elseif($val->Approve_status=='RECHECK')
                                                    <span style="color:#71a5ee">RECHECK</span>
                                                    @elseif($val->Approve_status=='OBJECT')
                                                    <span style="color:#da2aff">OBJECT</span>
                                                    @elseif($val->Approve_status=='HOLD')
                                                    <span style="color:#0cbad6">HOLD</span>
                                                    @else
                                                    <span style="color: #FF9000;">Pending</span>
                                                    @endif
                                                </td>
                                                <td class="PendingColor">
                                                    @if(($val->Approve_status==='FORWARD' && isset($val->status) && $val->status!=1) || ($val->Approve_status=='' && isset($val->status) && $val->status!=1))
                                                    Pending With
                                                    @foreach($val->PendingWith as $name)
                                                    {{isset($name->fullname) && $name->fullname!=''?$name->fullname:''}},
                                                    @endforeach
                                                    @elseif($val->Approve_status=='RECHECK' || $val->Approve_status=='OBJECT')
                                                    {{isset($val->user->fullname) && $val->user->fullname!=''?'Pending With '.$val->user->fullname:''}}
                                                    @endif
                                                </td>
                                                <td class="maindffd">
                                                    @if(isset($val->status) && $val->status!=1)
                                                    <a href="{{url('orderRequirement/Stock_View/'.$val->id.'/ALL')}}" class="btn btn-primary">View</a>
                                                    @if($val->Approve_status == 'RECHECK' && isset($EXT[18]['inputer']))
                                                    <a href="{{url('orderRequirement/orderRequirement/'.$val->id)}}" class="btn btn-secondary">Edit</a>
                                                    @elseif($val->HoldStatus > 0)
                                                    <a href="{{url('orderRequirement/Stock_Release_Hold/'.$val->id)}}" class="btn btn-secondary">Release</a>
                                                    @endif
                                                    @else
                                                    <a href="{{url('orderRequirement/orderRequirement/'.$val->id)}}" class="btn btn-warning">Draft</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                            @php
                                            Session::put('nexdata',$sesionarr);
                                            @endphp
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="SalesApproved" role="tabpanel" aria-labelledby="SalesApproveds">
                                <div class="table-responsive">
                                    <table id="example2" class="table table-sm table-striped table-bordered example" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th class="th-sm">SL. No.</th>
                                                <th class="th-sm">Creater Name</th>
                                                <th class="th-sm">Order Type</th>
                                                <th class="th-sm">PR No</th>
                                                <th class="th-sm">Organization Name</th>
                                                <th class="th-sm">BU Name</th>
                                                <th class="th-sm">Godowm Name</th>
                                                <th class="th-sm">Unit Name</th>
                                                <th class="th-sm">Plant Name</th>
                                                <th class="th-sm">Finished Good(FG)</th>
                                                <th class="th-sm">Expected Date</th>
                                                <th class="th-sm">QTY</th>
                                                {{-- <th class="th-sm">Company Name</th> --}}
                                                <th class="th-sm">Date & Time</th>
                                                <th class="th-sm">Total Amount</th>
                                                <th class="th-sm">Total Amount</th>
                                                <th class="th-sm">Status</th>
                                                <th class="th-sm">Pending With</th>
                                                <th class="th-sm">Operation</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($approved as $key => $val)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{isset($val->user->fullname) && $val->user->fullname != ''?$val->user->fullname:''}}</td>

                                                <td>{{$val->Work_Order_Type}}</td>
                                                <td>{{$val->Stock_Order_No}}</td>
                                                <td>{{$Organisation[$val->Organization]??''}}</td>
                                                <td>{{$BU[$val->BU_Name]??''}}</td>
                                                <td>{{$all_godownname[$val->Factory_Godown_Name]??''}}</td>
                                                <td>{{$MU[$val->Unit_Name]??''}}</td>
                                                <td>{{$plant_name[$val->Plant_Name]??''}}</td>
                                                <td>{{$val->rawmaterial??''}}</td>
                                                <td>{{$val->Expected_Date??''}}</td>
                                                <td>{{$val->QTY??''}}</td>
                                                {{-- <td>{{$Company_Name[$val->Company_Name]??''}}</td> --}}
                                                <td>{{isset($val->created_at) && $val->created_at!=''?date('d-m-Y H:i:s A',strtotime($val->created_at)):''}}</td>
                                                <td>{{isset($val->Total) && $val->Total != ''?$val->Total:''}}</td>
                                                <td id="statuss{{$val->id}}">
                                                    @if($val->Approve_status=='APPROVE')
                                                    <span style="color: #1bb81b;">APPROVED</span>
                                                    @elseif($val->Approve_status=='REJECT')
                                                    <span style="color:red">REJECTED</span>
                                                    @elseif($val->Approve_status=='RECHECK')
                                                    <span style="color:#71a5ee">RECHECK</span>
                                                    @elseif($val->Approve_status=='OBJECT')
                                                    <span style="color:#da2aff">OBJECT</span>
                                                    @elseif($val->Approve_status=='HOLD')
                                                    <span style="color:#0cbad6">HOLD</span>
                                                    @else
                                                    <span style="color: #FF9000;">Pending</span>
                                                    @endif
                                                </td>
                                                <td class="PendingColor">
                                                    @if(($val->Approve_status==='FORWARD' && isset($val->status) && $val->status!=1) || ($val->Approve_status=='' && isset($val->status) && $val->status!=1))
                                                    Pending With
                                                    @foreach($val->PendingWith as $name)
                                                    {{isset($name->fullname) && $name->fullname!=''?$name->fullname:''}},
                                                    @endforeach
                                                    @elseif($val->Approve_status=='RECHECK' || $val->Approve_status=='OBJECT')
                                                    {{isset($val->user->fullname) && $val->user->fullname!=''?'Pending With '.$val->user->fullname:''}}
                                                    @endif
                                                </td>
                                                <td class="maindffd">
                                                    @if(isset($val->status) && $val->status!=1)
                                                    <a href="{{url('orderRequirement/Stock_View/'.$val->id.'/ALL')}}" class="btn btn-primary">View</a>
                                                    @if($val->Approve_status == 'RECHECK' && isset($EXT[18]['inputer']))
                                                    <a href="{{url('orderRequirement/orderRequirement/'.$val->id)}}" class="btn btn-secondary">Edit</a>
                                                    @elseif($val->HoldStatus > 0)
                                                    <a href="{{url('orderRequirement/Stock_Release_Hold/'.$val->id)}}" class="btn btn-secondary">Release</a>
                                                    @endif
                                                    @else
                                                    <a href="{{url('orderRequirement/orderRequirement/'.$val->id)}}" class="btn btn-warning">Draft</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="SalesPending" role="tabpanel" aria-labelledby="SalesPendings">
                                <div class="table-responsive">
                                    <table id="" class="table table-sm table-striped table-bordered example" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th class="th-sm">SL. No.</th>
                                                <th class="th-sm">Creater Name</th>
                                                <th class="th-sm">Order Type</th>
                                                <th class="th-sm">PR No</th>
                                                <th class="th-sm">Organization Name</th>
                                                <th class="th-sm">BU Name</th>
                                                <th class="th-sm">Godowm Name</th>
                                                <th class="th-sm">Unit Name</th>
                                                <th class="th-sm">Plant Name</th>
                                                <th class="th-sm">Finished Good(FG)</th>
                                                <th class="th-sm">Expected Date</th>
                                                <th class="th-sm">QTY</th>
                                                {{-- <th class="th-sm">Company Name</th> --}}
                                                <th class="th-sm">Date & Time</th>
                                                <th class="th-sm">Total Amount</th>
                                                <th class="th-sm">Status</th>
                                                <th class="th-sm">Pending With</th>
                                                <th class="th-sm">Operation</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pending as $key => $val)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{isset($val->user->fullname) && $val->user->fullname != ''?$val->user->fullname:''}}</td>

                                                <td>{{$val->Work_Order_Type}}</td>
                                                <td>{{$val->Stock_Order_No}}</td>
                                                <td>{{$Organisation[$val->Organization]??''}}</td>
                                                <td>{{$BU[$val->BU_Name]??''}}</td>
                                                <td>{{$all_godownname[$val->Factory_Godown_Name]??''}}</td>
                                                <td>{{$MU[$val->Unit_Name]??''}}</td>
                                                <td>{{$plant_name[$val->Plant_Name]??''}}</td>
                                                <td>{{$val->rawmaterial??''}}</td>
                                                <td>{{$val->Expected_Date??''}}</td>
                                                <td>{{$val->QTY??''}}</td>
                                                {{-- <td>{{$Company_Name[$val->Company_Name]??''}}</td> --}}
                                                <td>{{isset($val->created_at) && $val->created_at!=''?date('d-m-Y H:i:s A',strtotime($val->created_at)):''}}</td>
                                                <td>{{isset($val->Total) && $val->Total != ''?$val->Total:''}}</td>
                                                <td id="statuss{{$val->id}}">
                                                    @if($val->Approve_status=='APPROVE')
                                                    <span style="color: #1bb81b;">APPROVED</span>
                                                    @elseif($val->Approve_status=='REJECT')
                                                    <span style="color:red">REJECTED</span>
                                                    @elseif($val->Approve_status=='RECHECK')
                                                    <span style="color:#71a5ee">RECHECK</span>
                                                    @elseif($val->Approve_status=='OBJECT')
                                                    <span style="color:#da2aff">OBJECT</span>
                                                    @elseif($val->Approve_status=='HOLD')
                                                    <span style="color:#0cbad6">HOLD</span>
                                                    @else
                                                    <span style="color: #FF9000;">Pending</span>
                                                    @endif
                                                </td>
                                                <td class="PendingColor">
                                                    @if(($val->Approve_status==='FORWARD' && isset($val->status) && $val->status!=1) || ($val->Approve_status=='' && isset($val->status) && $val->status!=1))
                                                    Pending With
                                                    @foreach($val->PendingWith as $name)
                                                    {{isset($name->fullname) && $name->fullname!=''?$name->fullname:''}},
                                                    @endforeach
                                                    @elseif($val->Approve_status=='RECHECK' || $val->Approve_status=='OBJECT')
                                                    {{isset($val->user->fullname) && $val->user->fullname!=''?'Pending With '.$val->user->fullname:''}}
                                                    @endif
                                                </td>
                                                <td class="maindffd">
                                                    @if(isset($val->status) && $val->status!=1)
                                                    <a href="{{url('orderRequirement/Stock_View/'.$val->id.'/ALL')}}" class="btn btn-primary">View</a>
                                                    @if($val->Approve_status == 'RECHECK' && isset($EXT[18]['inputer']))
                                                    <a href="{{url('orderRequirement/orderRequirement/'.$val->id)}}" class="btn btn-secondary">Edit</a>
                                                    @elseif($val->HoldStatus > 0)
                                                    <a href="{{url('orderRequirement/Stock_Release_Hold/'.$val->id)}}" class="btn btn-secondary">Release</a>
                                                    @endif
                                                    @else
                                                    <a href="{{url('orderRequirement/orderRequirement/'.$val->id)}}" class="btn btn-warning">Draft</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="SalesHold" role="tabpanel" aria-labelledby="SalesHolds">
                                <div class="table-responsive">
                                    <table id="" class="table table-sm table-striped table-bordered example" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th class="th-sm">SL. No.</th>
                                                <th class="th-sm">Creater Name</th>
                                                <th class="th-sm">Order Type</th>
                                                <th class="th-sm">PR No</th>
                                                <th class="th-sm">Organization Name</th>
                                                <th class="th-sm">BU Name</th>
                                                <th class="th-sm">Godowm Name</th>
                                                <th class="th-sm">Unit Name</th>
                                                <th class="th-sm">Plant Name</th>
                                                <th class="th-sm">Finished Good(FG)</th>
                                                <th class="th-sm">Expected Date</th>
                                                <th class="th-sm">QTY</th>
                                                {{-- <th class="th-sm">Company Name</th> --}}
                                                <th class="th-sm">Date & Time</th>
                                                <th class="th-sm">Total Amount</th>
                                                <th class="th-sm">Status</th>
                                                <th class="th-sm">Pending With</th>
                                                <th class="th-sm">Operation</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($HOLD as $key => $val)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{isset($val->user->fullname) && $val->user->fullname != ''?$val->user->fullname:''}}</td>

                                                <td>{{$val->Work_Order_Type}}</td>
                                                <td>{{$val->Stock_Order_No}}</td>
                                                <td>{{$Organisation[$val->Organization]??''}}</td>
                                                <td>{{$BU[$val->BU_Name]??''}}</td>
                                                <td>{{$all_godownname[$val->Factory_Godown_Name]??''}}</td>
                                                <td>{{$MU[$val->Unit_Name]??''}}</td>
                                                <td>{{$plant_name[$val->Plant_Name]??''}}</td>
                                                <td>{{$val->rawmaterial??''}}</td>
                                                <td>{{$val->Expected_Date??''}}</td>
                                                <td>{{$val->QTY??''}}</td>
                                                {{-- <td>{{$Company_Name[$val->Company_Name]??''}}</td> --}}
                                                <td>{{isset($val->created_at) && $val->created_at!=''?date('d-m-Y H:i:s A',strtotime($val->created_at)):''}}</td>
                                                <td>{{isset($val->Total) && $val->Total != ''?$val->Total:''}}</td>
                                                <td id="statuss{{$val->id}}">
                                                    @if($val->Approve_status=='APPROVE')
                                                    <span style="color: #1bb81b;">APPROVED</span>
                                                    @elseif($val->Approve_status=='REJECT')
                                                    <span style="color:red">REJECTED</span>
                                                    @elseif($val->Approve_status=='RECHECK')
                                                    <span style="color:#71a5ee">RECHECK</span>
                                                    @elseif($val->Approve_status=='OBJECT')
                                                    <span style="color:#da2aff">OBJECT</span>
                                                    @elseif($val->Approve_status=='HOLD')
                                                    <span style="color:#0cbad6">HOLD</span>
                                                    @else
                                                    <span style="color: #FF9000;">Pending</span>
                                                    @endif
                                                </td>
                                                <td class="PendingColor">
                                                    @if(($val->Approve_status==='FORWARD' && isset($val->status) && $val->status!=1) || ($val->Approve_status=='' && isset($val->status) && $val->status!=1))
                                                    Pending With
                                                    @foreach($val->PendingWith as $name)
                                                    {{isset($name->fullname) && $name->fullname!=''?$name->fullname:''}},
                                                    @endforeach
                                                    @elseif($val->Approve_status=='RECHECK' || $val->Approve_status=='OBJECT')
                                                    {{isset($val->user->fullname) && $val->user->fullname!=''?'Pending With '.$val->user->fullname:''}}
                                                    @endif
                                                </td>
                                                <td class="maindffd">
                                                    @if(isset($val->status) && $val->status!=1)
                                                    <a href="{{url('orderRequirement/Stock_View/'.$val->id.'/ALL')}}" class="btn btn-primary">View</a>
                                                    @if($val->Approve_status == 'RECHECK' && isset($EXT[18]['inputer']))
                                                    <a href="{{url('orderRequirement/orderRequirement/'.$val->id)}}" class="btn btn-secondary">Edit</a>
                                                    @elseif($val->HoldStatus > 0)
                                                    <a href="{{url('orderRequirement/Stock_Release_Hold/'.$val->id)}}" class="btn btn-secondary">Release</a>
                                                    @endif
                                                    @else
                                                    <a href="{{url('orderRequirement/orderRequirement/'.$val->id)}}" class="btn btn-warning">Draft</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="SalesRecheck" role="tabpanel" aria-labelledby="SalesRechecks">
                                <div class="table-responsive">
                                    <table id="" class="table table-sm table-striped table-bordered example" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th class="th-sm">SL. No.</th>
                                                <th class="th-sm">Creater Name</th>
                                                <th class="th-sm">Order Type</th>
                                                <th class="th-sm">PR No</th>
                                                <th class="th-sm">Organization Name</th>
                                                <th class="th-sm">BU Name</th>
                                                <th class="th-sm">Godowm Name</th>
                                                <th class="th-sm">Unit Name</th>
                                                <th class="th-sm">Plant Name</th>
                                                <th class="th-sm">Finished Good(FG)</th>
                                                <th class="th-sm">Expected Date</th>
                                                <th class="th-sm">QTY</th>
                                                {{-- <th class="th-sm">Company Name</th> --}}
                                                <th class="th-sm">Date & Time</th>
                                                <th class="th-sm">Total Amount</th>
                                                <th class="th-sm">Status</th>
                                                <th class="th-sm">Pending With</th>
                                                <th class="th-sm">Operation</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($RECHECK as $key => $val)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{isset($val->user->fullname) && $val->user->fullname != ''?$val->user->fullname:''}}</td>

                                                <td>{{$val->Work_Order_Type}}</td>
                                                <td>{{$val->Stock_Order_No}}</td>
                                                <td>{{$Organisation[$val->Organization]??''}}</td>
                                                <td>{{$BU[$val->BU_Name]??''}}</td>
                                                <td>{{$all_godownname[$val->Factory_Godown_Name]??''}}</td>
                                                <td>{{$MU[$val->Unit_Name]??''}}</td>
                                                <td>{{$plant_name[$val->Plant_Name]??''}}</td>
                                                <td>{{$val->rawmaterial??''}}</td>
                                                <td>{{$val->Expected_Date??''}}</td>
                                                <td>{{$val->QTY??''}}</td>
                                                {{-- <td>{{$Company_Name[$val->Company_Name]??''}}</td> --}}
                                                <td>{{isset($val->created_at) && $val->created_at!=''?date('d-m-Y H:i:s A',strtotime($val->created_at)):''}}</td>
                                                <td>{{isset($val->Total) && $val->Total != ''?$val->Total:''}}</td>
                                                <td id="statuss{{$val->id}}">
                                                    @if($val->Approve_status=='APPROVE')
                                                    <span style="color: #1bb81b;">APPROVED</span>
                                                    @elseif($val->Approve_status=='REJECT')
                                                    <span style="color:red">REJECTED</span>
                                                    @elseif($val->Approve_status=='RECHECK')
                                                    <span style="color:#71a5ee">RECHECK</span>
                                                    @elseif($val->Approve_status=='OBJECT')
                                                    <span style="color:#da2aff">OBJECT</span>
                                                    @elseif($val->Approve_status=='HOLD')
                                                    <span style="color:#0cbad6">HOLD</span>
                                                    @else
                                                    <span style="color: #FF9000;">Pending</span>
                                                    @endif
                                                </td>
                                                <td class="PendingColor">
                                                    @if(($val->Approve_status==='FORWARD' && isset($val->status) && $val->status!=1) || ($val->Approve_status=='' && isset($val->status) && $val->status!=1))
                                                    Pending With
                                                    @foreach($val->PendingWith as $name)
                                                    {{isset($name->fullname) && $name->fullname!=''?$name->fullname:''}},
                                                    @endforeach
                                                    @elseif($val->Approve_status=='RECHECK' || $val->Approve_status=='OBJECT')
                                                    {{isset($val->user->fullname) && $val->user->fullname!=''?'Pending With '.$val->user->fullname:''}}
                                                    @endif
                                                </td>
                                                <td class="maindffd">
                                                    @if(isset($val->status) && $val->status!=1)
                                                    <a href="{{url('orderRequirement/Stock_View/'.$val->id.'/ALL')}}" class="btn btn-primary">View</a>
                                                    @if($val->Approve_status == 'RECHECK' && isset($EXT[18]['inputer']))
                                                    <a href="{{url('orderRequirement/orderRequirement/'.$val->id)}}" class="btn btn-secondary">Edit</a>
                                                    @elseif($val->HoldStatus > 0)
                                                    <a href="{{url('orderRequirement/Stock_Release_Hold/'.$val->id)}}" class="btn btn-secondary">Release</a>
                                                    @endif
                                                    @else
                                                    <a href="{{url('orderRequirement/orderRequirement/'.$val->id)}}" class="btn btn-warning">Draft</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="SalesObject" role="tabpanel" aria-labelledby="SalesObjects">
                                <div class="table-responsive">
                                    <table id="" class="table table-sm table-striped table-bordered example" style="width:100%">
                                        <thead>
                                            <tr>
                                            <th class="th-sm">SL. No.</th>
                                                <th class="th-sm">Creater Name</th>
                                                <th class="th-sm">Order Type</th>
                                                <th class="th-sm">PR No</th>
                                                <th class="th-sm">Organization Name</th>
                                                <th class="th-sm">BU Name</th>
                                                <th class="th-sm">Godowm Name</th>
                                                <th class="th-sm">Unit Name</th>
                                                <th class="th-sm">Plant Name</th>
                                                <th class="th-sm">Finished Good(FG)</th>
                                                <th class="th-sm">Expected Date</th>
                                                <th class="th-sm">QTY</th>
                                                {{-- <th class="th-sm">Company Name</th> --}}
                                                <th class="th-sm">Date & Time</th>
                                                <th class="th-sm">Total Amount</th>
                                                <th class="th-sm">Status</th>
                                                <th class="th-sm">Pending With</th>
                                                <th class="th-sm">Operation</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($OBJECT as $key => $val)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{isset($val->user->fullname) && $val->user->fullname != ''?$val->user->fullname:''}}</td>

                                                <td>{{$val->Work_Order_Type}}</td>
                                                <td>{{$val->Stock_Order_No}}</td>
                                                <td>{{$Organisation[$val->Organization]??''}}</td>
                                                <td>{{$BU[$val->BU_Name]??''}}</td>
                                                <td>{{$all_godownname[$val->Factory_Godown_Name]??''}}</td>
                                                <td>{{$MU[$val->Unit_Name]??''}}</td>
                                                <td>{{$plant_name[$val->Plant_Name]??''}}</td>
                                                <td>{{$val->rawmaterial??''}}</td>
                                                <td>{{$val->Expected_Date??''}}</td>
                                                <td>{{$val->QTY??''}}</td>
                                                {{-- <td>{{$Company_Name[$val->Company_Name]??''}}</td> --}}
                                                <td>{{isset($val->created_at) && $val->created_at!=''?date('d-m-Y H:i:s A',strtotime($val->created_at)):''}}</td>
                                                <td>{{isset($val->Total) && $val->Total != ''?$val->Total:''}}</td>
                                                <td id="statuss{{$val->id}}">
                                                    @if($val->Approve_status=='APPROVE')
                                                    <span style="color: #1bb81b;">APPROVED</span>
                                                    @elseif($val->Approve_status=='REJECT')
                                                    <span style="color:red">REJECTED</span>
                                                    @elseif($val->Approve_status=='RECHECK')
                                                    <span style="color:#71a5ee">RECHECK</span>
                                                    @elseif($val->Approve_status=='OBJECT')
                                                    <span style="color:#da2aff">OBJECT</span>
                                                    @elseif($val->Approve_status=='HOLD')
                                                    <span style="color:#0cbad6">HOLD</span>
                                                    @else
                                                    <span style="color: #FF9000;">Pending</span>
                                                    @endif
                                                </td>
                                                <td class="PendingColor">
                                                    @if(($val->Approve_status==='FORWARD' && isset($val->status) && $val->status!=1) || ($val->Approve_status=='' && isset($val->status) && $val->status!=1))
                                                    Pending With
                                                    @foreach($val->PendingWith as $name)
                                                    {{isset($name->fullname) && $name->fullname!=''?$name->fullname:''}},
                                                    @endforeach
                                                    @elseif($val->Approve_status=='RECHECK' || $val->Approve_status=='OBJECT')
                                                    {{isset($val->user->fullname) && $val->user->fullname!=''?'Pending With '.$val->user->fullname:''}}
                                                    @endif
                                                </td>
                                                <td class="maindffd">
                                                    @if(isset($val->status) && $val->status!=1)
                                                    <a href="{{url('orderRequirement/Stock_View/'.$val->id.'/ALL')}}" class="btn btn-primary">View</a>
                                                    @if($val->Approve_status == 'RECHECK' && isset($EXT[18]['inputer']))
                                                    <a href="{{url('orderRequirement/orderRequirement/'.$val->id)}}" class="btn btn-secondary">Edit</a>
                                                    @elseif($val->HoldStatus > 0)
                                                    <a href="{{url('orderRequirement/Stock_Release_Hold/'.$val->id)}}" class="btn btn-secondary">Release</a>
                                                    @endif
                                                    @else
                                                    <a href="{{url('orderRequirement/orderRequirement/'.$val->id)}}" class="btn btn-warning">Draft</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="SalesReject" role="tabpanel" aria-labelledby="SalesRejects">
                                <div class="table-responsive">
                                    <table id="" class="table table-sm table-striped table-bordered example" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th class="th-sm">SL. No.</th>
                                                <th class="th-sm">Creater Name</th>
                                                <th class="th-sm">Order Type</th>
                                                <th class="th-sm">PR No</th>
                                                <th class="th-sm">Organization Name</th>
                                                <th class="th-sm">BU Name</th>
                                                <th class="th-sm">Godowm Name</th>
                                                <th class="th-sm">Unit Name</th>
                                                <th class="th-sm">Plant Name</th>
                                                <th class="th-sm">Finished Good(FG)</th>
                                                <th class="th-sm">Expected Date</th>
                                                <th class="th-sm">QTY</th>
                                                {{-- <th class="th-sm">Company Name</th> --}}
                                                <th class="th-sm">Date & Time</th>
                                                <th class="th-sm">Total Amount</th>
                                                <th class="th-sm">Status</th>
                                                <th class="th-sm">Pending With</th>
                                                <th class="th-sm">Operation</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($REJECT as $key => $val)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{isset($val->user->fullname) && $val->user->fullname != ''?$val->user->fullname:''}}</td>

                                                <td>{{$val->Work_Order_Type}}</td>
                                                <td>{{$val->Stock_Order_No}}</td>
                                                <td>{{$Organisation[$val->Organization]??''}}</td>
                                                <td>{{$BU[$val->BU_Name]??''}}</td>
                                                <td>{{$all_godownname[$val->Factory_Godown_Name]??''}}</td>
                                                <td>{{$MU[$val->Unit_Name]??''}}</td>
                                                <td>{{$plant_name[$val->Plant_Name]??''}}</td>
                                                <td>{{$val->rawmaterial??''}}</td>
                                                <td>{{$val->Expected_Date??''}}</td>
                                                <td>{{$val->QTY??''}}</td>
                                                {{-- <td>{{$Company_Name[$val->Company_Name]??''}}</td> --}}
                                                <td>{{isset($val->created_at) && $val->created_at!=''?date('d-m-Y H:i:s A',strtotime($val->created_at)):''}}</td>
                                                <td>{{isset($val->Total) && $val->Total != ''?$val->Total:''}}</td>
                                                <td id="statuss{{$val->id}}">
                                                    @if($val->Approve_status=='APPROVE')
                                                    <span style="color: #1bb81b;">APPROVED</span>
                                                    @elseif($val->Approve_status=='REJECT')
                                                    <span style="color:red">REJECTED</span>
                                                    @elseif($val->Approve_status=='RECHECK')
                                                    <span style="color:#71a5ee">RECHECK</span>
                                                    @elseif($val->Approve_status=='OBJECT')
                                                    <span style="color:#da2aff">OBJECT</span>
                                                    @elseif($val->Approve_status=='HOLD')
                                                    <span style="color:#0cbad6">HOLD</span>
                                                    @else
                                                    <span style="color: #FF9000;">Pending</span>
                                                    @endif
                                                </td>
                                                <td class="PendingColor">
                                                    @if(($val->Approve_status==='FORWARD' && isset($val->status) && $val->status!=1) || ($val->Approve_status=='' && isset($val->status) && $val->status!=1))
                                                    Pending With
                                                    @foreach($val->PendingWith as $name)
                                                    {{isset($name->fullname) && $name->fullname!=''?$name->fullname:''}},
                                                    @endforeach
                                                    @elseif($val->Approve_status=='RECHECK' || $val->Approve_status=='OBJECT')
                                                    {{isset($val->user->fullname) && $val->user->fullname!=''?'Pending With '.$val->user->fullname:''}}
                                                    @endif
                                                </td>
                                                <td class="maindffd">
                                                    @if(isset($val->status) && $val->status!=1)
                                                    <a href="{{url('orderRequirement/Stock_View/'.$val->id.'/ALL')}}" class="btn btn-primary">View</a>
                                                    @if($val->Approve_status == 'RECHECK' && isset($EXT[18]['inputer']))
                                                    <a href="{{url('orderRequirement/orderRequirement/'.$val->id)}}" class="btn btn-secondary">Edit</a>
                                                    @elseif($val->HoldStatus > 0)
                                                    <a href="{{url('orderRequirement/Stock_Release_Hold/'.$val->id)}}" class="btn btn-secondary">Release</a>
                                                    @endif
                                                    @else
                                                    <a href="{{url('orderRequirement/orderRequirement/'.$val->id)}}" class="btn btn-warning">Draft</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <br> <br>
</div>
</section>
</div>
</div>
</section>
@endsection
@push('custom-scripts')
<script>
    $(document).ready(function() {
        activeclass(25, 1);
        loadCheckBoxes()
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var MyToggles = document.querySelectorAll(".MyToggle");
        var myFilters = document.querySelectorAll(".myFilter");

        MyToggles.forEach(function(MyToggle, index) {
            MyToggle.addEventListener("click", function() {
                myFilters[index].classList.toggle("show-div");
            });
        });

        document.addEventListener("click", function(event) {
            MyToggles.forEach(function(MyToggle, index) {
                if (!myFilters[index].contains(event.target) && !MyToggle.contains(event.target)) {
                    myFilters[index].classList.remove("show-div");
                }
            });
        });
    });
</script>
<script>
    function toggleCheckboxes() {
        var checkboxes = document.querySelectorAll('.form-check-input');
        var toggleCheckbox = document.getElementById('ToggleCheck');

        checkboxes.forEach(function(checkbox) {
            checkbox.checked = toggleCheckbox.checked;
        });

        checkBoxess();
    }

    function filterTable() {
        var checkboxes = document.querySelectorAll('.form-check-input');
        var toggleCheckbox = document.getElementById('ToggleCheck');

        var allChecked = true;
        var toggleChecked = toggleCheckbox.checked;

        checkboxes.forEach(function(checkbox) {
            if (checkbox !== toggleCheckbox && !checkbox.checked) {
                allChecked = false;
            }
        });

        toggleCheckbox.checked = allChecked && toggleChecked;
        checkBoxess();
    }
</script>
<script>
    function checkBoxess() {
        let tableID = 222;
        var checkedColumns = document.querySelectorAll('.form-check-input:checked');
        var columnNamesToShow = [];

        checkedColumns.forEach(function(checkbox) {
            columnNamesToShow.push(checkbox.value);
        });

        var tabledata = document.querySelectorAll('table');

        tabledata.forEach(function(table) {
            var rows = table.querySelectorAll('tr');

            if (checkedColumns.length === 0) {
                for (var i = 0; i < rows.length; i++) {
                    var cells = rows[i].querySelectorAll('td');
                    for (var j = 0; j < cells.length; j++) {
                        cells[j].style.display = '';
                    }
                }

                var thead = table.querySelector('thead');
                if (thead) {
                    var thElements = thead.querySelectorAll('th');
                    for (var k = 0; k < thElements.length; k++) {
                        thElements[k].style.display = '';
                    }
                }
            } else {
                for (var i = 0; i < rows.length; i++) {
                    var cells = rows[i].querySelectorAll('td');
                    for (var j = 0; j < cells.length; j++) {
                        var columnName = table.querySelector('thead th:nth-child(' + (j + 1) + ')').innerText;
                        if (columnNamesToShow.indexOf(columnName) !== -1) {
                            cells[j].style.display = '';
                        } else {
                            cells[j].style.display = 'none';
                        }
                    }
                }

                var thead = table.querySelector('thead');
                if (thead) {
                    var thElements = thead.querySelectorAll('th');
                    for (var k = 0; k < thElements.length; k++) {
                        var columnName = thElements[k].innerText;
                        if (columnNamesToShow.indexOf(columnName) !== -1) {
                            thElements[k].style.display = '';
                        } else {
                            thElements[k].style.display = 'none';
                        }
                    }
                }
            }
        });

        var CollumValue = columnNamesToShow.join(',');
        fetch("{{ url('getCheckBoxData') }}?ID=" + tableID, {
                method: 'GET',
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.columns) {
                    try {
                        var existingData = data.columns;
                        if (JSON.stringify(existingData) !== JSON.stringify(columnNamesToShow)) {
                            fetch("{{ url('CheckBoxStore') }}", {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    },
                                    body: JSON.stringify({
                                        id: tableID,
                                        columns: CollumValue,
                                    }),
                                })
                                .then(response => response.json())
                                .then(data => {
                                    console.log(data);
                                })
                                .catch(error => {
                                    console.error('Error sending data to the backend:', error);
                                });
                        }
                    } catch (error) {
                        console.error('Error parsing JSON data:', error);
                    }
                }
            })
            .catch(error => {
                console.error('Error fetching checkbox data from the backend:', error);
            });

    }

    function loadCheckBoxes() {

        let tableID = 222;


        fetch("{{ url('getCheckBoxData') }}?ID=" + tableID, {
                method: 'GET',
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.columns) {
                    try {
                        var columnNamesToShow = data.columns;
                        var checkboxes = document.querySelectorAll('.form-check-input');

                        checkboxes.forEach(function(checkbox) {
                            if (columnNamesToShow.indexOf(checkbox.value) !== -1) {
                                checkbox.checked = true;
                            }
                        });

                        filterTable();
                    } catch (error) {
                        console.error('Error parsing JSON data:', error);
                    }
                }
            })
            .catch(error => {
                console.error('Error fetching checkbox data from the backend:', error);
            });
    }
</script>
<script>
    $(document).ready(function(){
        $.post("{{url('orderRequirement/StockFilter')}}",{},function(data){
            $("#allfilter").html(data);
            AppendSelect2()
            $("#stock_fieldsformfilter").attr('action',"{{url('orderRequirement/orderRequirementStockList')}}")
        });
    });
</script>
<script>
    document.getElementById('sales').addEventListener('click', function() {
        alert('This feature is currently under maintenance. We apologize for any inconvenience caused.');
    });
    </script>

@endpush