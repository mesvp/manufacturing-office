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
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <h6>MRN STOCK TRANSFER LIST</h6>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="text-end">
                                @php
                                    $exportUrl = url('StockTransfer/ExportData') . '?' . http_build_query([
                                        'from_date' => $fromdate ?? '',
                                        'to_date' => $todate ?? '',
                                        'Raw_Material' => $RawMaterialss ?? '',
                                    ]);
                                @endphp
                                <a href="{{ $exportUrl }}"><i class="fa-file-excel fas text-success"></i></a>
                                {{-- @if(isset($EXT[15]['inputer']))
                                <a href="{{url('StoreRequistion/AddStoreRequistion')}}"><button class="btn btn-info">Add Store Requistion</button></a>
                                @endif --}}
                            </div>
                        </div>
                    </div>

                    <form action="{{url('StockTransfer/filtered')}}" method="POST">
                        @csrf
                        <div class="row filter">
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                <label for="" class="form-label">Date From</label>
                                <input type="date" name="from_date" value="{{isset($fromdate) && $fromdate!=''?$fromdate:''}}" class="form-control form-control-sm">
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                <label for="" class="form-label">Date To</label>
                                <input type="date" name="to_date" value="{{isset($todate) && $todate!=''?$todate:''}}" class="form-control form-control-sm">
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12">
                                <label for="" class="form-label">Material</label>
                                <select name="Raw_Material" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all" {{isset($RawMaterialss) && $RawMaterialss === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach($DropdownData as $val)
                                        @if(isset($val->Raw_Material) && $val->Raw_Material !== null)
                                            <option value="{{ $val->Raw_Material->id ?? '' }}" 
                                                    {{ isset($RawMaterialss) && $RawMaterialss == $val->Raw_Material->id ? 'selected' : '' }}>
                                                {{ $val->Raw_Material->matname ?? 'N/A' }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 mt-4">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                <a href="{{url('StockTransfer/TransferRequestList')}}"><button type="button" class="btn btn-secondary"><i class="fa fa-refresh"></i></button></a>
                            </div>
                            <div class="col-xl-4 col-lg-6 col-md-8 col-sm-12 mt-4">
                                <div class="FilterButtonnn">
                                    <div class="raone">
                                        <p class="raho" id="MyToggle">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-funnel-fill" viewBox="0 0 16 16">
                                                <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z" />
                                            </svg>
                                        </p>
                                        <div class="ukom" id="myFilter">
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
                                                <input type="checkbox" class="form-check-input" id="Date_Time" value="Date & Time" onclick="filterTable(this)">
                                                <label class="form-check-label" for="Date_Time">Date & Time</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="Material" value="Material" onclick="filterTable(this)">
                                                <label class="form-check-label" for="Material">Material</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="UOM" value="UOM" onclick="filterTable(this)">
                                                <label class="form-check-label" for="UOM">UOM</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="Purchase_Date" value="Purchase Date" onclick="filterTable(this)">
                                                <label class="form-check-label" for="Purchase_Date">Purchase Date</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="Purchase_Qty" value="Purchase Qty" onclick="filterTable(this)">
                                                <label class="form-check-label" for="Purchase_Qty">Purchase Qty</label>
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
                                                <input type="checkbox" class="form-check-input" id="Transfer" value="Transfer" onclick="filterTable(this)">
                                                <label class="form-check-label" for="Transfer">Transfer</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="Transfer_Status" value="Transfer Status" onclick="filterTable(this)">
                                                <label class="form-check-label" for="Transfer_Status">Transfer Status</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="Operation" value="Operation" onclick="filterTable(this)">
                                                <label class="form-check-label" for="Operation">Operation</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mx-2">
                        <ul class="nav nav-pills" id="ex1" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link count active p-2" id="Alls" data-mdb-toggle="tab" href="#All" role="tab" aria-controls="All" aria-selected="true">All <span class="countss">{{count($store)}}</span></a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link count p-2" id="Approveds" data-mdb-toggle="tab" href="#Approved" role="tab" aria-controls="Approved" aria-selected="false">Approved <span class="countss">{{count($approved)}}</span></a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link count p-2" id="Pendings" data-mdb-toggle="tab" href="#Pending" role="tab" aria-controls="Pending" aria-selected="false">Pending <span class="countss">{{count($pending)}}</span></a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link count p-2" id="Holds" data-mdb-toggle="tab" href="#Hold" role="tab" aria-controls="Hold" aria-selected="false">Hold <span class="countss">{{count($HOLD)}}</span></a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link count p-2" id="Rechecks" data-mdb-toggle="tab" href="#Recheck" role="tab" aria-controls="Recheck" aria-selected="false">Recheck <span class="countss">{{count($RECHECK)}}</span></a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link count p-2" id="Objects" data-mdb-toggle="tab" href="#Object" role="tab" aria-controls="Object" aria-selected="false">Object <span class="countss">{{count($OBJECT)}}</span></a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link count p-2" id="Rejects" data-mdb-toggle="tab" href="#Reject" role="tab" aria-controls="Reject" aria-selected="false">Reject <span class="countss">{{count($REJECT)}}</span></a>
                            </li>
                        </ul>
                    </div>
                </div>
                    <div class="tab-content" id="ex1-content">
                        <div class="tab-pane fade show active ml-0 mt-1" id="All" role="tabpanel" aria-labelledby="Alls">
                            <div class="table-responsive">
                                <table id="" class="table table-sm table-striped table-bordered example w-100">
                                    <thead>
                                        <tr>
                                            <th class="th-sm">SL. No.</th>
                                            <th class="th-sm">Creater Name</th>
                                            <th class="th-sm">Date & Time</th>
                                            <th class="th-sm">Material</th>
                                            <th class="th-sm">UOM</th>
                                            <th class="th-sm">Purchase Date</th>
                                            <th class="th-sm">Purchase Qty</th>
                                            <th class="th-sm">Status</th>
                                            <th class="th-sm">Pending With</th>
                                            <th class="th-sm">Transfer</th>
                                            <th class="th-sm">Transfer Status</th>
                                            <th class="th-sm">Operation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $sesionarr=[];
                                        @endphp
                                        @foreach($store as $key => $val)
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
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{isset($val->username->fullname) && $val->username->fullname != '' ? $val->username->fullname : ''}}</td>
                                            <td>{{isset($val->created_at) && $val->created_at != '' ? date('d-m-Y H:i:s A', strtotime($val->created_at)) : ''}}</td>
                                            <td>{{isset($val->Material->matname) && $val->Material->matname != '' ? $val->Material->matname : ''}}</td>
                                            <td>{{isset($val->Material->UOM) && $val->Material->UOM != '' ? $val->Material->UOM : ''}}</td>
                                            <td>{{isset($val->Mrn_Date) && $val->Mrn_Date!=''?$val->Mrn_Date:''}}</td>
                                            {{-- <td>{{isset($val->user->fullname) && $val->user->fullname != '' ? $val->user->fullname : ''}}</td> --}}
                                            <td>{{isset($val->Quantity) && $val->Quantity != '' ? $val->Quantity : ''}}</td>
                                            {{-- <td>{{isset($val->UOM) && $val->UOM != '' ? $val->UOM : ''}}</td> --}}
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
                                                {{-- @php dd($val->Approve_status); @endphp --}}
                                                @if(($val->Approve_status==='FORWARD' && isset($val->status) && $val->status!=1) || ($val->Approve_status=='' && isset($val->status) && $val->status!=1))
                                                Pending With
                                                @foreach($val->PendingWith as $name)
                                                {{isset($name->fullname) && $name->fullname!=''?$name->fullname:''}},
                                                @endforeach
                                                @elseif($val->Approve_status=='RECHECK' || $val->Approve_status=='OBJECT')
                                                {{isset($val->user->fullname) && $val->user->fullname!=''?'Pending With '.$val->user->fullname:''}}
                                                @endif
                                            </td>
                                            <td>
                                                @if($val->trn_status == '0' && isset($val->showTransferButton) && $val->showTransferButton)
                                                <a href="{{url('StockTransfer/AddStoreTransfer/'.$val->id)}}" class="btn btn-warning">Transfer</a>
                                                @elseif($val->trn_status == '0')
                                                <span class="text-muted">Transfer Not Available</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($val->trn_status == '0' )
                                                {{"Not Transfer"}}
                                                @else 
                                                {{"Transfer"}}
                                                @endif
                                            </td>
                                            <td class="maindffd">
                                                @if(isset($val->status) && $val->status!=1)
                                                <a href="{{url('StockTransfer/StoreTransfer_View/'.$val->id.'/ALL')}}" class="btn btn-primary">View</a>
                                                @if($val->Approve_status == 'RECHECK' && isset($EXT[15]['inputer']))
                                                <a href="{{url('StockTransfer/AddStoreTransfer/'.$val->id)}}" class="btn btn-secondary">Edit</a>
                                                @elseif($val->HoldStatus > 0)
                                                <a href="{{url('StockTransfer/Release_Hold/'.$val->id)}}" class="btn btn-secondary">Release</a>
                                                @endif
                                                @else
                                                <a href="{{url('StockTransfer/StoreTransfer_View/'.$val->id.'/ALL')}}" class="btn btn-primary">View</a>
                                                {{-- <a href="{{url('StoreRequistion/StoreRequistion_View/'.$val->id.'/ALL')}}" class="btn btn-primary">View</a> --}}
                                                {{-- <a href="{{url('StoreRequistion/AddStoreRequistion/'.$val->id)}}" class="btn btn-warning">Draft</a> --}}
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
                        <div class="tab-pane fade ml-0 mt-1" id="Approved" role="tabpanel" aria-labelledby="Approveds">
                            <div class="table-responsive">
                                <table id="" class="table table-sm table-striped table-bordered example w-100">
                                    <thead>
                                        <tr>
                                            <th class="th-sm">SL. No.</th>
                                            <th class="th-sm">Creater Name</th>
                                            <th class="th-sm">Date & Time</th>
                                            <th class="th-sm">Material</th>
                                            <th class="th-sm">UOM</th>
                                            <th class="th-sm">Purchase Date</th>
                                            <th class="th-sm">Purchase Qty</th>
                                            <th class="th-sm">Status</th>
                                            <th class="th-sm">Pending With</th>
                                            <th class="th-sm">Transfer</th>
                                            <th class="th-sm">Transfer Status</th>
                                            <th class="th-sm">Operation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($approved as $key => $val)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{isset($val->username->fullname) && $val->username->fullname != '' ? $val->username->fullname : ''}}</td>
                                            <td>{{isset($val->created_at) && $val->created_at != '' ? date('d-m-Y H:i:s A', strtotime($val->created_at)) : ''}}</td>
                                            <td>{{isset($val->Material->matname) && $val->Material->matname != '' ? $val->Material->matname : ''}}</td>
                                            <td>{{isset($val->Material->UOM) && $val->Material->UOM != '' ? $val->Material->UOM : ''}}</td>
                                            <td>{{isset($val->Mrn_Date) && $val->Mrn_Date!=''?$val->Mrn_Date:''}}</td>
                                            {{-- <td>{{isset($val->user->fullname) && $val->user->fullname != '' ? $val->user->fullname : ''}}</td> --}}
                                            <td>{{isset($val->Quantity) && $val->Quantity != '' ? $val->Quantity : ''}}</td>
                                            {{-- <td>{{isset($val->UOM) && $val->UOM != '' ? $val->UOM : ''}}</td> --}}
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
                                                {{-- @php dd($val->Approve_status); @endphp --}}
                                                @if(($val->Approve_status==='FORWARD' && isset($val->status) && $val->status!=1) || ($val->Approve_status=='' && isset($val->status) && $val->status!=1))
                                                Pending With
                                                @foreach($val->PendingWith as $name)
                                                {{isset($name->fullname) && $name->fullname!=''?$name->fullname:''}},
                                                @endforeach
                                                @elseif($val->Approve_status=='RECHECK' || $val->Approve_status=='OBJECT')
                                                {{isset($val->user->fullname) && $val->user->fullname!=''?'Pending With '.$val->user->fullname:''}}
                                                @endif
                                            </td>
                                            <td>
                                                @if($val->trn_status == '0' && isset($val->showTransferButton) && $val->showTransferButton)
                                                <a href="{{url('StockTransfer/AddStoreTransfer/'.$val->id)}}" class="btn btn-warning">Transfer</a>
                                                @elseif($val->trn_status == '0')
                                                <span class="text-muted">Transfer Not Available</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($val->trn_status == '0' )
                                                {{"Not Transfer"}}
                                                @else
                                                {{"Transfer"}}
                                                @endif
                                            </td>
                                            <td class="maindffd">
                                                @if(isset($val->status) && $val->status!=1)
                                                <a href="{{url('StockTransfer/StoreTransfer_View/'.$val->id.'/ALL')}}" class="btn btn-primary">View</a>
                                                @if($val->Approve_status == 'RECHECK' && isset($EXT[15]['inputer']))
                                                <a href="{{url('StockTransfer/AddStoreTransfer/'.$val->id)}}" class="btn btn-secondary">Edit</a>
                                                @elseif($val->HoldStatus > 0)
                                                <a href="{{url('StockTransfer/Release_Hold/'.$val->id)}}" class="btn btn-secondary">Release</a>
                                                @endif
                                                @else
                                                <a href="{{url('StoreRequistion/StoreRequistion_View/'.$val->id.'/ALL')}}" class="btn btn-primary">View</a>
                                                {{-- <a href="{{url('StoreRequistion/AddStoreRequistion/'.$val->id)}}" class="btn btn-warning">Draft</a> --}}
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade ml-0 mt-1" id="Pending" role="tabpanel" aria-labelledby="Pendings">
                            <div class="table-responsive">
                                <table id="" class="table table-sm table-striped table-bordered example w-100">
                                    <thead>
                                        <tr>
                                            <th class="th-sm">SL. No.</th>
                                            <th class="th-sm">Creater Name</th>
                                            <th class="th-sm">Date & Time</th>
                                            <th class="th-sm">Material</th>
                                            <th class="th-sm">UOM</th>
                                            <th class="th-sm">Purchase Date</th>
                                            <th class="th-sm">Purchase Qty</th>
                                            <th class="th-sm">Status</th>
                                            <th class="th-sm">Pending With</th>
                                            <th class="th-sm">Transfer</th>
                                            <th class="th-sm">Transfer Status</th>
                                            <th class="th-sm">Operation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pending as $key => $val)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{isset($val->username->fullname) && $val->username->fullname != '' ? $val->username->fullname : ''}}</td>
                                            <td>{{isset($val->created_at) && $val->created_at != '' ? date('d-m-Y H:i:s A', strtotime($val->created_at)) : ''}}</td>
                                            <td>{{isset($val->Material->matname) && $val->Material->matname != '' ? $val->Material->matname : ''}}</td>
                                            <td>{{isset($val->Material->UOM) && $val->Material->UOM != '' ? $val->Material->UOM : ''}}</td>
                                            <td>{{isset($val->Mrn_Date) && $val->Mrn_Date!=''?$val->Mrn_Date:''}}</td>
                                            {{-- <td>{{isset($val->user->fullname) && $val->user->fullname != '' ? $val->user->fullname : ''}}</td> --}}
                                            <td>{{isset($val->Quantity) && $val->Quantity != '' ? $val->Quantity : ''}}</td>
                                            {{-- <td>{{isset($val->UOM) && $val->UOM != '' ? $val->UOM : ''}}</td> --}}
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
                                                {{-- @php dd($val->Approve_status); @endphp --}}
                                                @if(($val->Approve_status==='FORWARD' && isset($val->status) && $val->status!=1) || ($val->Approve_status=='' && isset($val->status) && $val->status!=1))
                                                Pending With
                                                @foreach($val->PendingWith as $name)
                                                {{isset($name->fullname) && $name->fullname!=''?$name->fullname:''}},
                                                @endforeach
                                                @elseif($val->Approve_status=='RECHECK' || $val->Approve_status=='OBJECT')
                                                {{isset($val->user->fullname) && $val->user->fullname!=''?'Pending With '.$val->user->fullname:''}}
                                                @endif
                                            </td>
                                            <td>
                                                @if($val->trn_status == '0' && isset($val->showTransferButton) && $val->showTransferButton)
                                                <a href="{{url('StockTransfer/AddStoreTransfer/'.$val->id)}}" class="btn btn-warning">Transfer</a>
                                                @elseif($val->trn_status == '0')
                                                <span class="text-muted">Transfer Not Available</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($val->trn_status == '0' )
                                                {{"Not Transfer"}}
                                                @else
                                                {{"Transfer"}}
                                                @endif
                                            </td>
                                            <td class="maindffd">
                                                @if(isset($val->status) && $val->status!=1)
                                                <a href="{{url('StockTransfer/StoreTransfer_View/'.$val->id.'/ALL')}}" class="btn btn-primary">View</a>
                                                @if($val->Approve_status == 'RECHECK' && isset($EXT[15]['inputer']))
                                                <a href="{{url('StockTransfer/AddStoreTransfer/'.$val->id)}}" class="btn btn-secondary">Edit</a>
                                                @elseif($val->HoldStatus > 0)
                                                <a href="{{url('StockTransfer/Release_Hold/'.$val->id)}}" class="btn btn-secondary">Release</a>
                                                @endif
                                                @else
                                                <a href="{{url('StoreRequistion/StoreRequistion_View/'.$val->id.'/ALL')}}" class="btn btn-primary">View</a>
                                                {{-- <a href="{{url('StoreRequistion/AddStoreRequistion/'.$val->id)}}" class="btn btn-warning">Draft</a> --}}
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade ml-0 mt-1" id="Hold" role="tabpanel" aria-labelledby="Holds">
                            <div class="table-responsive">
                                <table id="" class="table table-sm table-striped table-bordered example w-100">
                                    <thead>
                                        <tr>
                                            <th class="th-sm">SL. No.</th>
                                            <th class="th-sm">Creater Name</th>
                                            <th class="th-sm">Date & Time</th>
                                            <th class="th-sm">Material</th>
                                            <th class="th-sm">UOM</th>
                                            <th class="th-sm">Purchase Date</th>
                                            <th class="th-sm">Purchase Qty</th>
                                            <th class="th-sm">Status</th>
                                            <th class="th-sm">Pending With</th>
                                            <th class="th-sm">Transfer</th>
                                            <th class="th-sm">Transfer Status</th>
                                            <th class="th-sm">Operation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($HOLD as $key => $val)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{isset($val->username->fullname) && $val->username->fullname != '' ? $val->username->fullname : ''}}</td>
                                            <td>{{isset($val->created_at) && $val->created_at != '' ? date('d-m-Y H:i:s A', strtotime($val->created_at)) : ''}}</td>
                                            <td>{{isset($val->Material->matname) && $val->Material->matname != '' ? $val->Material->matname : ''}}</td>
                                            <td>{{isset($val->Material->UOM) && $val->Material->UOM != '' ? $val->Material->UOM : ''}}</td>
                                            <td>{{isset($val->Mrn_Date) && $val->Mrn_Date!=''?$val->Mrn_Date:''}}</td>
                                            {{-- <td>{{isset($val->user->fullname) && $val->user->fullname != '' ? $val->user->fullname : ''}}</td> --}}
                                            <td>{{isset($val->Quantity) && $val->Quantity != '' ? $val->Quantity : ''}}</td>
                                            {{-- <td>{{isset($val->UOM) && $val->UOM != '' ? $val->UOM : ''}}</td> --}}
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
                                                {{-- @php dd($val->Approve_status); @endphp --}}
                                                @if(($val->Approve_status==='FORWARD' && isset($val->status) && $val->status!=1) || ($val->Approve_status=='' && isset($val->status) && $val->status!=1))
                                                Pending With
                                                @foreach($val->PendingWith as $name)
                                                {{isset($name->fullname) && $name->fullname!=''?$name->fullname:''}},
                                                @endforeach
                                                @elseif($val->Approve_status=='RECHECK' || $val->Approve_status=='OBJECT')
                                                {{isset($val->user->fullname) && $val->user->fullname!=''?'Pending With '.$val->user->fullname:''}}
                                                @endif
                                            </td>
                                            <td>
                                                @if($val->trn_status == '0' && isset($val->showTransferButton) && $val->showTransferButton)
                                                <a href="{{url('StockTransfer/AddStoreTransfer/'.$val->id)}}" class="btn btn-warning">Transfer</a>
                                                @elseif($val->trn_status == '0')
                                                <span class="text-muted">Transfer Not Available</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($val->trn_status == '0' )
                                                {{"Not Transfer"}}
                                                @else
                                                {{"Transfer"}}
                                                @endif
                                            </td>
                                            <td class="maindffd">
                                                @if(isset($val->status) && $val->status!=1)
                                                <a href="{{url('StockTransfer/StoreTransfer_View/'.$val->id.'/ALL')}}" class="btn btn-primary">View</a>
                                                @if($val->Approve_status == 'RECHECK' && isset($EXT[15]['inputer']))
                                                <a href="{{url('StockTransfer/AddStoreTransfer/'.$val->id)}}" class="btn btn-secondary">Edit</a>
                                                @elseif($val->HoldStatus > 0)
                                                <a href="{{url('StockTransfer/Release_Hold/'.$val->id)}}" class="btn btn-secondary">Release</a>
                                                @endif
                                                @else
                                                <a href="{{url('StoreRequistion/StoreRequistion_View/'.$val->id.'/ALL')}}" class="btn btn-primary">View</a>
                                                {{-- <a href="{{url('StoreRequistion/AddStoreRequistion/'.$val->id)}}" class="btn btn-warning">Draft</a> --}}
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade ml-0 mt-1" id="Recheck" role="tabpanel" aria-labelledby="Rechecks">
                            <div class="table-responsive">
                                <table id="" class="table table-sm table-striped table-bordered example w-100">
                                    <thead>
                                        <tr>
                                            <th class="th-sm">SL. No.</th>
                                            <th class="th-sm">Creater Name</th>
                                            <th class="th-sm">Date & Time</th>
                                            <th class="th-sm">Material</th>
                                            <th class="th-sm">UOM</th>
                                            <th class="th-sm">Purchase Date</th>
                                            <th class="th-sm">Purchase Qty</th>
                                            <th class="th-sm">Status</th>
                                            <th class="th-sm">Pending With</th>
                                            <th class="th-sm">Transfer</th>
                                            <th class="th-sm">Transfer Status</th>
                                            <th class="th-sm">Operation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($RECHECK as $key => $val)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{isset($val->username->fullname) && $val->username->fullname != '' ? $val->username->fullname : ''}}</td>
                                            <td>{{isset($val->created_at) && $val->created_at != '' ? date('d-m-Y H:i:s A', strtotime($val->created_at)) : ''}}</td>
                                            <td>{{isset($val->Material->matname) && $val->Material->matname != '' ? $val->Material->matname : ''}}</td>
                                            <td>{{isset($val->Material->UOM) && $val->Material->UOM != '' ? $val->Material->UOM : ''}}</td>
                                            <td>{{isset($val->Mrn_Date) && $val->Mrn_Date!=''?$val->Mrn_Date:''}}</td>
                                            {{-- <td>{{isset($val->user->fullname) && $val->user->fullname != '' ? $val->user->fullname : ''}}</td> --}}
                                            <td>{{isset($val->Quantity) && $val->Quantity != '' ? $val->Quantity : ''}}</td>
                                            {{-- <td>{{isset($val->UOM) && $val->UOM != '' ? $val->UOM : ''}}</td> --}}
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
                                                {{-- @php dd($val->Approve_status); @endphp --}}
                                                @if(($val->Approve_status==='FORWARD' && isset($val->status) && $val->status!=1) || ($val->Approve_status=='' && isset($val->status) && $val->status!=1))
                                                Pending With
                                                @foreach($val->PendingWith as $name)
                                                {{isset($name->fullname) && $name->fullname!=''?$name->fullname:''}},
                                                @endforeach
                                                @elseif($val->Approve_status=='RECHECK' || $val->Approve_status=='OBJECT')
                                                {{isset($val->user->fullname) && $val->user->fullname!=''?'Pending With '.$val->user->fullname:''}}
                                                @endif
                                            </td>
                                            <td>
                                                @if($val->trn_status == '0' && isset($val->showTransferButton) && $val->showTransferButton)
                                                <a href="{{url('StockTransfer/AddStoreTransfer/'.$val->id)}}" class="btn btn-warning">Transfer</a>
                                                @elseif($val->trn_status == '0')
                                                <span class="text-muted">Transfer Not Available</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($val->trn_status == '0' )
                                                {{"Not Transfer"}}
                                                @else
                                                {{"Transfer"}}
                                                @endif
                                            </td>
                                            <td class="maindffd">
                                                @if(isset($val->status) && $val->status!=1)
                                                <a href="{{url('StockTransfer/StoreTransfer_View/'.$val->id.'/ALL')}}" class="btn btn-primary">View</a>
                                                @if($val->Approve_status == 'RECHECK' && isset($EXT[15]['inputer']))
                                                <a href="{{url('StockTransfer/AddStoreTransfer/'.$val->id)}}" class="btn btn-secondary">Edit</a>
                                                @elseif($val->HoldStatus > 0)
                                                <a href="{{url('StockTransfer/Release_Hold/'.$val->id)}}" class="btn btn-secondary">Release</a>
                                                @endif
                                                @else
                                                <a href="{{url('StoreRequistion/StoreRequistion_View/'.$val->id.'/ALL')}}" class="btn btn-primary">View</a>
                                                {{-- <a href="{{url('StoreRequistion/AddStoreRequistion/'.$val->id)}}" class="btn btn-warning">Draft</a> --}}
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade ml-0 mt-1" id="Object" role="tabpanel" aria-labelledby="Objects">
                            <div class="table-responsive">
                                <table id="" class="table table-sm table-striped table-bordered example w-100">
                                    <thead>
                                        <tr>
                                            <th class="th-sm">SL. No.</th>
                                            <th class="th-sm">Creater Name</th>
                                            <th class="th-sm">Date & Time</th>
                                            <th class="th-sm">Material</th>
                                            <th class="th-sm">UOM</th>
                                            <th class="th-sm">Purchase Date</th>
                                            <th class="th-sm">Purchase Qty</th>
                                            <th class="th-sm">Status</th>
                                            <th class="th-sm">Pending With</th>
                                            <th class="th-sm">Transfer</th>
                                            <th class="th-sm">Transfer Status</th>
                                            <th class="th-sm">Operation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($OBJECT as $key => $val)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{isset($val->username->fullname) && $val->username->fullname != '' ? $val->username->fullname : ''}}</td>
                                            <td>{{isset($val->created_at) && $val->created_at != '' ? date('d-m-Y H:i:s A', strtotime($val->created_at)) : ''}}</td>
                                            <td>{{isset($val->Material->matname) && $val->Material->matname != '' ? $val->Material->matname : ''}}</td>
                                            <td>{{isset($val->Material->UOM) && $val->Material->UOM != '' ? $val->Material->UOM : ''}}</td>
                                            <td>{{isset($val->Mrn_Date) && $val->Mrn_Date!=''?$val->Mrn_Date:''}}</td>
                                            {{-- <td>{{isset($val->user->fullname) && $val->user->fullname != '' ? $val->user->fullname : ''}}</td> --}}
                                            <td>{{isset($val->Quantity) && $val->Quantity != '' ? $val->Quantity : ''}}</td>
                                            {{-- <td>{{isset($val->UOM) && $val->UOM != '' ? $val->UOM : ''}}</td> --}}
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
                                                {{-- @php dd($val->Approve_status); @endphp --}}
                                                @if(($val->Approve_status==='FORWARD' && isset($val->status) && $val->status!=1) || ($val->Approve_status=='' && isset($val->status) && $val->status!=1))
                                                Pending With
                                                @foreach($val->PendingWith as $name)
                                                {{isset($name->fullname) && $name->fullname!=''?$name->fullname:''}},
                                                @endforeach
                                                @elseif($val->Approve_status=='RECHECK' || $val->Approve_status=='OBJECT')
                                                {{isset($val->user->fullname) && $val->user->fullname!=''?'Pending With '.$val->user->fullname:''}}
                                                @endif
                                            </td>
                                            <td>
                                                @if($val->trn_status == '0' && isset($val->showTransferButton) && $val->showTransferButton)
                                                <a href="{{url('StockTransfer/AddStoreTransfer/'.$val->id)}}" class="btn btn-warning">Transfer</a>
                                                @elseif($val->trn_status == '0')
                                                <span class="text-muted">Transfer Not Available</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($val->trn_status == '0' )
                                                {{"Not Transfer"}}
                                                @else
                                                {{"Transfer"}}
                                                @endif
                                            </td>
                                            <td class="maindffd">
                                                @if(isset($val->status) && $val->status!=1)
                                                <a href="{{url('StockTransfer/StoreTransfer_View/'.$val->id.'/ALL')}}" class="btn btn-primary">View</a>
                                                @if($val->Approve_status == 'RECHECK' && isset($EXT[15]['inputer']))
                                                <a href="{{url('StockTransfer/AddStoreTransfer/'.$val->id)}}" class="btn btn-secondary">Edit</a>
                                                @elseif($val->HoldStatus > 0)
                                                <a href="{{url('StockTransfer/Release_Hold/'.$val->id)}}" class="btn btn-secondary">Release</a>
                                                @endif
                                                @else
                                                <a href="{{url('StoreRequistion/StoreRequistion_View/'.$val->id.'/ALL')}}" class="btn btn-primary">View</a>
                                                {{-- <a href="{{url('StoreRequistion/AddStoreRequistion/'.$val->id)}}" class="btn btn-warning">Draft</a> --}}
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade ml-0 mt-1" id="Reject" role="tabpanel" aria-labelledby="Rejects">
                            <div class="table-responsive">
                                <table id="" class="table table-sm table-striped table-bordered example w-100">
                                    <thead>
                                        <tr>
                                            <th class="th-sm">SL. No.</th>
                                            <th class="th-sm">Creater Name</th>
                                            <th class="th-sm">Date & Time</th>
                                            <th class="th-sm">Material</th>
                                            <th class="th-sm">UOM</th>
                                            <th class="th-sm">Purchase Date</th>
                                            <th class="th-sm">Purchase Qty</th>
                                            <th class="th-sm">Status</th>
                                            <th class="th-sm">Pending With</th>
                                            <th class="th-sm">Transfer</th>
                                            <th class="th-sm">Transfer Status</th>
                                            <th class="th-sm">Operation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($REJECT as $key => $val)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{isset($val->username->fullname) && $val->username->fullname != '' ? $val->username->fullname : ''}}</td>
                                            <td>{{isset($val->created_at) && $val->created_at != '' ? date('d-m-Y H:i:s A', strtotime($val->created_at)) : ''}}</td>
                                            <td>{{isset($val->Material->matname) && $val->Material->matname != '' ? $val->Material->matname : ''}}</td>
                                            <td>{{isset($val->Material->UOM) && $val->Material->UOM != '' ? $val->Material->UOM : ''}}</td>
                                            <td>{{isset($val->Mrn_Date) && $val->Mrn_Date!=''?$val->Mrn_Date:''}}</td>
                                            {{-- <td>{{isset($val->user->fullname) && $val->user->fullname != '' ? $val->user->fullname : ''}}</td> --}}
                                            <td>{{isset($val->Quantity) && $val->Quantity != '' ? $val->Quantity : ''}}</td>
                                            {{-- <td>{{isset($val->UOM) && $val->UOM != '' ? $val->UOM : ''}}</td> --}}
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
                                                {{-- @php dd($val->Approve_status); @endphp --}}
                                                @if(($val->Approve_status==='FORWARD' && isset($val->status) && $val->status!=1) || ($val->Approve_status=='' && isset($val->status) && $val->status!=1))
                                                Pending With
                                                @foreach($val->PendingWith as $name)
                                                {{isset($name->fullname) && $name->fullname!=''?$name->fullname:''}},
                                                @endforeach
                                                @elseif($val->Approve_status=='RECHECK' || $val->Approve_status=='OBJECT')
                                                {{isset($val->user->fullname) && $val->user->fullname!=''?'Pending With '.$val->user->fullname:''}}
                                                @endif
                                            </td>
                                            <td>
                                                @if($val->trn_status == '0' && isset($val->showTransferButton) && $val->showTransferButton)
                                                <a href="{{url('StockTransfer/AddStoreTransfer/'.$val->id)}}" class="btn btn-warning">Transfer</a>
                                                @elseif($val->trn_status == '0')
                                                <span class="text-muted">Transfer Not Available</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($val->trn_status == '0' )
                                                {{"Not Transfer"}}
                                                @else
                                                {{"Transfer"}}
                                                @endif
                                            </td>
                                            <td class="maindffd">
                                                @if(isset($val->status) && $val->status!=1)
                                                <a href="{{url('StockTransfer/StoreTransfer_View/'.$val->id.'/ALL')}}" class="btn btn-primary">View</a>
                                                @if($val->Approve_status == 'RECHECK' && isset($EXT[15]['inputer']))
                                                <a href="{{url('StockTransfer/AddStoreTransfer/'.$val->id)}}" class="btn btn-secondary">Edit</a>
                                                @elseif($val->HoldStatus > 0)
                                                <a href="{{url('StockTransfer/Release_Hold/'.$val->id)}}" class="btn btn-secondary">Release</a>
                                                @endif
                                                @else
                                                <a href="{{url('StoreRequistion/StoreRequistion_View/'.$val->id.'/ALL')}}" class="btn btn-primary">View</a>
                                                {{-- <a href="{{url('StoreRequistion/AddStoreRequistion/'.$val->id)}}" class="btn btn-warning">Draft</a> --}}
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

        </section>
    </div>
</div>

@endsection
@push('custom-scripts')
<script>
    $(document).ready(function() {
        activeclass(29, 1);
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var MyToggle = document.getElementById("MyToggle");
        var myFilter = document.getElementById("myFilter");

        MyToggle.addEventListener("click", function() {
            myFilter.classList.toggle("show-div");
        });

        document.addEventListener("click", function(event) {
            if (!myFilter.contains(event.target) && !MyToggle.contains(event.target)) {
                myFilter.classList.remove("show-div");
            }
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
    var tableID = 2310;

    function checkBoxess() {
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

        fetch("{{ url('StoreRequistion/getCheckBoxData') }}?ID=" + tableID, {
                method: 'GET',
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.columns) {
                    try {
                        var existingData = data.columns;
                        if (JSON.stringify(existingData) !== JSON.stringify(columnNamesToShow)) {
                            fetch("{{ url('StoreRequistion/CheckBoxStore') }}", {
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

    document.addEventListener('DOMContentLoaded', function() {

        fetch("{{ url('StoreRequistion/getCheckBoxData') }}?ID=" + tableID, {
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
    });
</script>
<script>
    $(document).ready(function() {
        $.ajax({
            url: "{{ url('StockTransfer/CheckHoldExpiry') }}",
            method: 'GET',
            success: function(response) {
                response.forEach(function(lead) {
                    if (lead.action === 'HOLD' && lead.status === 1) {
                        var currentDate = new Date();
                        var holdDate = new Date(lead.days_for_holding);

                        if (holdDate < currentDate) {
                            UpdateStatus(lead.Store_Transfer_id, lead.userID);
                        }
                    }
                });
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    });

    function UpdateStatus(Store_Transfer_id, userID) {
        $.ajax({
            url: "{{ url('StockTransfer/UpdateStatus') }}",
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            data: JSON.stringify({
                Store_Transfer_id: Store_Transfer_id,
                userID: userID
            }),
            success: function(response) {
                $('#statuss' + Store_Transfer_id).html('<span style="color: #FF9000;">Pending</span>');
            }
        });
    }
</script>
@endpush
