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

    input.invalid {

        background-color: #ffdddd;

    }


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
        {{-- @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif --}}
        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
        @endif
        <section class="section">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Material List View Page</li>
            </ol>
            <div class="addbtn">
                <a href="{{url('MaterialManagement/ExportFilteredData')}}"><i class='fas fa-file-excel'></i></a>
                @if(isset($EXT[4]['inputer']))
                <a href="{{url('MaterialManagement/AddMaterial')}}"><button class="btn btn-info">Add Material</button></a>
                @endif
            </div>
            <div class="row">
                <div class="container">
                    <form action="{{url('MaterialManagement/filterMaterial')}}" method="POST">
                        @csrf
                        <div class="row filter">
                            <div class="col-2 mb-3">
                                <label class="form-label">Date From</label>
                                <input type="date" name="from_date" value="{{isset($fromdate) && $fromdate!=''?$fromdate:''}}" class="form-control form-control-sm">
                            </div>
                            <div class="col-2 mb-3">
                                <label class="form-label">Date To</label>
                                <input type="date" name="to_date" value="{{isset($todate) && $todate!=''?$todate:''}}" class="form-control form-control-sm">
                            </div>
                            <div class="col-2 mb-3">
                                <label class="form-label">Material Code</label>
                                <select name="Material_Code" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all" {{isset($MaterialCodes) && $MaterialCodes === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach($DropdownData as $val)
                                    <?php
                                    $MaterialCode = $val->Material_Code;
                                    if (!empty($MaterialCode) && !in_array($MaterialCode, $RepeatData)) {
                                        $RepeatData[] = $MaterialCode;
                                    ?>
                                        <option value="{{ $MaterialCode }}" {{isset($MaterialCodes) && $MaterialCodes==$MaterialCode?'selected':''}}>{{ $MaterialCode }}</option>
                                    <?php } ?>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2 mb-3">
                                <label class="form-label">Material Name</label>
                                <select name="Material_Name" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all" {{isset($MaterialNames) && $MaterialNames === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach($DropdownData as $val)
                                    <?php
                                    $MaterialName = isset($val->Material_Name) && $val->Material_Name != '' ? $val->Material_Name : '';
                                    if (!empty($MaterialName) && !in_array($MaterialName, $RepeatData)) {
                                        $RepeatData[] = $MaterialName;
                                    ?>
                                        <option value="{{ $MaterialName }}" {{isset($MaterialNames) && $MaterialNames==$MaterialName?'selected':''}}>{{ $val->matname }}</option>
                                    <?php } ?>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2 mb-3">
                                <label class="form-label">HSN Code</label>
                                <select name="HSN_Code" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all" {{isset($HSNCodes) && $HSNCodes === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach($DropdownData as $val)
                                    <?php
                                    $HSNCODE = isset($val->HSN_Code) && $val->HSN_Code != '' ? $val->HSN_Code : '';
                                    if (!empty($HSNCODE) && !in_array($HSNCODE, $RepeatData)) {
                                        $RepeatData[] = $HSNCODE;
                                    ?>
                                        <option value="{{ $HSNCODE }}" {{isset($HSNCodes) && $HSNCodes==$HSNCODE?'selected':''}}>{{ $HSNCODE }}</option>
                                    <?php } ?>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2 mb-3">
                                <label class="form-label">UOM</label>
                                <select name="UOM" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all" {{isset($UOMss) && $UOMss === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach($DropdownData as $val)
                                    <option value="{{isset($val->UOM) && $val->UOM!=''?$val->UOM:''}}" {{isset($UOMss) && $UOMss==$val->UOM?'selected':''}}>{{ isset($val->UOM) && $val->UOM!=''?$val->UOM:'' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- <div class="col-2 mb-3">
                                <label class="form-label">Last Purchase Price</label>
                                <select name="QC_Required" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all" {{isset($QCRequireds) && $QCRequireds === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach($Quality_Check as $val)
                                    <option value="{{ isset($val->id) && $val->id!=''?$val->id:'' }}" {{isset($QCRequireds) && $QCRequireds==$val->id?'selected':''}}>{{ isset($val->quality_check) && $val->quality_check!=''?$val->quality_check:'' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2 mb-3">
                                <label class="form-label">Last Purchase Date</label>
                                <select name="Minium_Order_Level" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all" {{isset($MiniumOrderLevels) && $MiniumOrderLevels === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach($DropdownData as $val)
                                    <?php
                                    $MiniumOrderLevel = $val->Minium_Order_Level;
                                    if (!empty($MiniumOrderLevel) && !in_array($MiniumOrderLevel, $RepeatData)) {
                                        $RepeatData[] = $MiniumOrderLevel;
                                    ?>
                                        <option value="{{ $MiniumOrderLevel }}" {{isset($MiniumOrderLevels) && $MiniumOrderLevels==$MiniumOrderLevel?'selected':''}}>{{ $MiniumOrderLevel }}</option>
                                    <?php } ?>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2 mb-3">
                                <label class="form-label">Last Purchase Vendor Name</label>
                                <select name="Reorder_Level" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all" {{isset($ReorderLevels) && $ReorderLevels === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach($DropdownData as $val)
                                    <?php
                                    $ReorderLevel = $val->Reorder_Level;
                                    if (!empty($ReorderLevel) && !in_array($ReorderLevel, $RepeatData)) {
                                        $RepeatData[] = $ReorderLevel;
                                    ?>
                                        <option value="{{ $ReorderLevel }}" {{isset($ReorderLevels) && $ReorderLevels==$ReorderLevel?'selected':''}}>{{ $ReorderLevel }}</option>
                                    <?php } ?>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="col-2 mt-4">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                <a href="{{url('MaterialManagement/MaterialList')}}"><button type="button" class="btn btn-secondary"><i class="fa fa-refresh"></i></button></a>
                            </div>
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
                                            <input type="checkbox" class="form-check-input" id="Material_ID" value="Material ID" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Material_Code">Material ID</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Material_Name" value="Material Name" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Material_Name">Material Name</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="HSN_Code" value="HSN Code" onclick="filterTable(this)">
                                            <label class="form-check-label" for="HSN_Code">HSN Code</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="UOM" value="UOM" onclick="filterTable(this)">
                                            <label class="form-check-label" for="UOM">UOM</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="last_purchase_price" value="Last Purchase Price" onclick="filterTable(this)">
                                            <label class="form-check-label" for="last_purchase_price">Last Purchase Price</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="last_purchase_vndrname" value="Last Purchase Vendor Name" onclick="filterTable(this)">
                                            <label class="form-check-label" for="last_purchase_vndrname">Last Purchase Vendor Name</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="group" value="Group" onclick="filterTable(this)">
                                            <label class="form-check-label" for="group">Group</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="sub-group" value="Sub-Group" onclick="filterTable(this)">
                                            <label class="form-check-label" for="sub-group">Sub-Group</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="category" value="Category" onclick="filterTable(this)">
                                            <label class="form-check-label" for="category">Category</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="sub-category" value="Sub-Category" onclick="filterTable(this)">
                                            <label class="form-check-label" for="sub-category">Sub-Category</label>
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
                    </form>
                    <ul class="nav nav-tabs mb-3" id="ex1" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link count active" id="Alls" data-mdb-toggle="tab" href="#All" role="tab" aria-controls="All" aria-selected="true">All <span class="countss">{{count($materialManagment)}}</span></a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link count" id="Approveds" data-mdb-toggle="tab" href="#Approved" role="tab" aria-controls="Approved" aria-selected="false">Approved <span class="countss">{{count($approved)}}</span></a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link count" id="Pendings" data-mdb-toggle="tab" href="#Pending" role="tab" aria-controls="Pending" aria-selected="false">Pending <span class="countss">{{count($pending)}}</span></a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link count" id="Holds" data-mdb-toggle="tab" href="#Hold" role="tab" aria-controls="Hold" aria-selected="false">Hold <span class="countss">{{count($HOLD)}}</span></a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link count" id="Rechecks" data-mdb-toggle="tab" href="#Recheck" role="tab" aria-controls="Recheck" aria-selected="false">Recheck <span class="countss">{{count($RECHECK)}}</span></a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link count" id="Objects" data-mdb-toggle="tab" href="#Object" role="tab" aria-controls="Object" aria-selected="false">Object <span class="countss">{{count($OBJECT)}}</span></a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link count" id="Rejects" data-mdb-toggle="tab" href="#Reject" role="tab" aria-controls="Reject" aria-selected="false">Reject <span class="countss">{{count($REJECT)}}</span></a>
                        </li>
                    </ul>
                    <div class="tab-content" id="ex1-content">
                        <div class="tab-pane fade show active" id="All" role="tabpanel" aria-labelledby="Alls">
                            <div class="table-responsive">
                                <table id="" class="table table-striped table-bordered example" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="th-sm">SL. No.</th>
                                            <th class="th-sm">Creater Name</th>
                                            <th class="th-sm">Date & Time</th>
                                            <th class="th-sm">Material ID</th>
                                            <th class="th-sm">Material Name</th>
                                            <th class="th-sm">HSN Code</th>
                                            <th class="th-sm">UOM</th>
                                            <th class="th-sm">Last Purchase Price</th>
                                            <th class="th-sm">Last Purchase Date</th>
                                            <th class="th-sm">Last Purchase Vendor Name</th>
                                            <th class="th-sm">Group</th>
                                            <th class="th-sm">Sub-Group</th>
                                            <th class="th-sm">Category</th>
                                            <th class="th-sm">Sub-Category</th>
                                            <th class="th-sm">Status</th>
                                            <th class="th-sm">Pending With</th>
                                            <th class="th-sm">Operation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $sesionarr=[];
                                        @endphp
                                        @foreach($materialManagment as $key=>$val)
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
                                            <td>{{isset($val->user->fullname) && $val->user->fullname!=''?$val->user->fullname:''}}</td>
                                            <td>{{isset($val->created_at) && $val->created_at!=''?date('d-m-Y H:i:s A',strtotime($val->created_at)):''}}</td>
                                            <td>{{isset($val->Material_Name) && $val->Material_Name!=''?$val->Material_Name:''}}</td>
                                            <td>{{isset($val->mtaerialdetails->material_name) && $val->mtaerialdetails->material_name!=''?$val->mtaerialdetails->material_name:''}}</td>
                                            <td>{{isset($val->HSN_Code) && $val->HSN_Code!=''?$val->HSN_Code:''}}</td>
                                            <td>{{isset($val->UOM) && $val->UOM!=''?$val->UOM:''}}</td>
                                            <td>{{isset($val->last_purchase_price) && $val->last_purchase_price!=''?$val->last_purchase_price:''}}</td>
                                            <td>{{isset($val->last_purchase_date) && $val->last_purchase_date!=''?$val->last_purchase_date:''}}</td>
                                            <td>{{isset($val->last_purchase_vndr_name) && $val->last_purchase_vndr_name!=''?$val->last_purchase_vndr_name:''}}</td>
                                            <td>{{isset($val->grp_name) && $val->grp_name!=''?$val->grp_name:''}}</td>
                                            <td>{{isset($val->sub_grp_name) && $val->sub_grp_name!=''?$val->sub_grp_name:''}}</td>
                                            <td>{{isset($val->cat_name) && $val->cat_name!=''?$val->cat_name:''}}</td>
                                            <td>{{isset($val->sub_cat_name) && $val->sub_cat_name!=''?$val->sub_cat_name:''}}</td>

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
                                                @if($val->Approve_status==='FORWARD' || $val->Approve_status=='' && isset($val->status) && $val->status!=1)
                                                Pending With
                                                @foreach($val->PendingWith as $name)
                                                {{isset($name->fullname) && $name->fullname!=''?$name->fullname:''}},
                                                @endforeach
                                                @elseif($val->Approve_status=='RECHECK' || $val->Approve_status=='OBJECT')
                                                {{isset($val->user->fullname) && $val->user->fullname!=''?'Pending With '.$val->user->fullname:''}}
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($val->status) && $val->status!=1)
                                                <a href="{{url('MaterialManagement/Material_view/'.$val->id.'/ALL')}}" class="btn btn-primary">View</a>
                                                @if($val->Approve_status == 'RECHECK' && (isset($EXT[4]['inputer'])))
                                                <a href="{{url('MaterialManagement/AddMaterial/'.$val->id)}}"><button type="button" class="btn btn-secondary">Edit</button></a>
                                                @elseif($val->HoldStatus > 0)
                                                <a href="{{url('MaterialManagement/Release_Hold/'.$val->id)}}"><button type="button" class="btn btn-secondary">Release</button></a>
                                                @endif
                                                @else
                                                <a href="{{url('MaterialManagement/AddMaterial/'.$val->id)}}" class="btn btn-warning">Draft</a>
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
                        <div class="tab-pane fade" id="Approved" role="tabpanel" aria-labelledby="Approveds">
                            <div class="table-responsive">
                                <table id="" class="table table-striped table-bordered example" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="th-sm">SL. No.</th>
                                            <th class="th-sm">Creater Name</th>
                                            <th class="th-sm">Date & Time</th>
                                            <th class="th-sm">Material ID</th>
                                            <th class="th-sm">Material Name</th>
                                            <th class="th-sm">HSN Code</th>
                                            <th class="th-sm">UOM</th>
                                            <th class="th-sm">Last Purchase Price</th>
                                            <th class="th-sm">Last Purchase Date</th>
                                            <th class="th-sm">Last Purchase Vendor Name</th>
                                            <th class="th-sm">Group</th>
                                            <th class="th-sm">Sub-Group</th>
                                            <th class="th-sm">Category</th>
                                            <th class="th-sm">Sub-Category</th>
                                            <th class="th-sm">Status</th>
                                            <th class="th-sm">Pending With</th>
                                            <th class="th-sm">Operation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($approved as $key=>$val)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{isset($val->user->fullname) && $val->user->fullname!=''?$val->user->fullname:''}}</td>
                                            <td>{{isset($val->created_at) && $val->created_at!=''?date('d-m-Y H:i:s A',strtotime($val->created_at)):''}}</td>
                                            <td>{{isset($val->Material_Name) && $val->Material_Name!=''?$val->Material_Name:''}}</td>
                                            <td>{{isset($val->mtaerialdetails->material_name) && $val->mtaerialdetails->material_name!=''?$val->mtaerialdetails->material_name:''}}</td>
                                            <td>{{isset($val->HSN_Code) && $val->HSN_Code!=''?$val->HSN_Code:''}}</td>
                                            <td>{{isset($val->UOM) && $val->UOM!=''?$val->UOM:''}}</td>
                                            <td>{{isset($val->last_purchase_price) && $val->last_purchase_price!=''?$val->last_purchase_price:''}}</td>
                                            <td>{{isset($val->last_purchase_date) && $val->last_purchase_date!=''?$val->last_purchase_date:''}}</td>
                                            <td>{{isset($val->last_purchase_vndr_name) && $val->last_purchase_vndr_name!=''?$val->last_purchase_vndr_name:''}}</td>
                                            <td>{{isset($val->grp_name) && $val->grp_name!=''?$val->grp_name:''}}</td>
                                            <td>{{isset($val->sub_grp_name) && $val->sub_grp_name!=''?$val->sub_grp_name:''}}</td>
                                            <td>{{isset($val->cat_name) && $val->cat_name!=''?$val->cat_name:''}}</td>
                                            <td>{{isset($val->sub_cat_name) && $val->sub_cat_name!=''?$val->sub_cat_name:''}}</td>
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
                                                @if($val->Approve_status==='FORWARD' || $val->Approve_status=='' && isset($val->status) && $val->status!=1)
                                                Pending With
                                                @foreach($val->PendingWith as $name)
                                                {{isset($name->fullname) && $name->fullname!=''?$name->fullname:''}},
                                                @endforeach
                                                @elseif($val->Approve_status=='RECHECK' || $val->Approve_status=='OBJECT')
                                                {{isset($val->user->fullname) && $val->user->fullname!=''?'Pending With '.$val->user->fullname:''}}
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($val->status) && $val->status!=1)
                                                <a href="{{url('MaterialManagement/Material_view/'.$val->id.'/APPROVE')}}" class="btn btn-primary">View</a>
                                                @if($val->Approve_status == 'RECHECK' && (isset($EXT[4]['inputer'])))
                                                <a href="{{url('MaterialManagement/AddMaterial/'.$val->id)}}"><button type="button" class="btn btn-secondary">Edit</button></a>
                                                @elseif($val->HoldStatus > 0)
                                                <a href="{{url('MaterialManagement/Release_Hold/'.$val->id)}}"><button type="button" class="btn btn-secondary">Release</button></a>
                                                @endif
                                                @else
                                                <a href="{{url('MaterialManagement/AddMaterial/'.$val->id)}}" class="btn btn-warning">Draft</a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Pending" role="tabpanel" aria-labelledby="Pendings">
                            <div class="table-responsive">
                                <table id="" class="table table-striped table-bordered example" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="th-sm">SL. No.</th>
                                            <th class="th-sm">Creater Name</th>
                                            <th class="th-sm">Date & Time</th>
                                            <th class="th-sm">Material ID</th>
                                            <th class="th-sm">Material Name</th>
                                            <th class="th-sm">HSN Code</th>
                                            <th class="th-sm">UOM</th>
                                            <th class="th-sm">Last Purchase Price</th>
                                            <th class="th-sm">Last Purchase Date</th>
                                            <th class="th-sm">Last Purchase Vendor Name</th>
                                            <th class="th-sm">Group</th>
                                            <th class="th-sm">Sub-Group</th>
                                            <th class="th-sm">Category</th>
                                            <th class="th-sm">Sub-Category</th>
                                            <th class="th-sm">Status</th>
                                            <th class="th-sm">Pending With</th>
                                            <th class="th-sm">Operation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pending as $key=>$val)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{isset($val->user->fullname) && $val->user->fullname!=''?$val->user->fullname:''}}</td>
                                            <td>{{isset($val->created_at) && $val->created_at!=''?date('d-m-Y H:i:s A',strtotime($val->created_at)):''}}</td>
                                            <td>{{isset($val->Material_Name) && $val->Material_Name!=''?$val->Material_Name:''}}</td>
                                            <td>{{isset($val->mtaerialdetails->material_name) && $val->mtaerialdetails->material_name!=''?$val->mtaerialdetails->material_name:''}}</td>
                                            <td>{{isset($val->HSN_Code) && $val->HSN_Code!=''?$val->HSN_Code:''}}</td>
                                            <td>{{isset($val->UOM) && $val->UOM!=''?$val->UOM:''}}</td>
                                            <td>{{isset($val->last_purchase_price) && $val->last_purchase_price!=''?$val->last_purchase_price:''}}</td>
                                            <td>{{isset($val->last_purchase_date) && $val->last_purchase_date!=''?$val->last_purchase_date:''}}</td>
                                            <td>{{isset($val->last_purchase_vndr_name) && $val->last_purchase_vndr_name!=''?$val->last_purchase_vndr_name:''}}</td>
                                            <td>{{isset($val->grp_name) && $val->grp_name!=''?$val->grp_name:''}}</td>
                                            <td>{{isset($val->sub_grp_name) && $val->sub_grp_name!=''?$val->sub_grp_name:''}}</td>
                                            <td>{{isset($val->cat_name) && $val->cat_name!=''?$val->cat_name:''}}</td>
                                            <td>{{isset($val->sub_cat_name) && $val->sub_cat_name!=''?$val->sub_cat_name:''}}</td>
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
                                                @if($val->Approve_status==='FORWARD' || $val->Approve_status=='' && isset($val->status) && $val->status!=1)
                                                Pending With
                                                @foreach($val->PendingWith as $name)
                                                {{isset($name->fullname) && $name->fullname!=''?$name->fullname:''}},
                                                @endforeach
                                                @elseif($val->Approve_status=='RECHECK' || $val->Approve_status=='OBJECT')
                                                {{isset($val->user->fullname) && $val->user->fullname!=''?'Pending With '.$val->user->fullname:''}}
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($val->status) && $val->status!=1)
                                                <a href="{{url('MaterialManagement/Material_view/'.$val->id.'/PENDING')}}" class="btn btn-primary">View</a>
                                                @if($val->Approve_status == 'RECHECK' && (isset($EXT[4]['inputer'])))
                                                <a href="{{url('MaterialManagement/AddMaterial/'.$val->id)}}"><button type="button" class="btn btn-secondary">Edit</button></a>
                                                @elseif($val->HoldStatus > 0)
                                                <a href="{{url('MaterialManagement/Release_Hold/'.$val->id)}}"><button type="button" class="btn btn-secondary">Release</button></a>
                                                @endif
                                                @else
                                                <a href="{{url('MaterialManagement/AddMaterial/'.$val->id)}}" class="btn btn-warning">Draft</a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Hold" role="tabpanel" aria-labelledby="Holds">
                            <div class="table-responsive">
                                <table id="" class="table table-striped table-bordered example" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="th-sm">SL. No.</th>
                                            <th class="th-sm">Creater Name</th>
                                            <th class="th-sm">Date & Time</th>
                                            <th class="th-sm">Material ID</th>
                                            <th class="th-sm">Material Name</th>
                                            <th class="th-sm">HSN Code</th>
                                            <th class="th-sm">UOM</th>
                                            <th class="th-sm">Last Purchase Price</th>
                                            <th class="th-sm">Last Purchase Date</th>
                                            <th class="th-sm">Last Purchase Vendor Name</th>
                                            <th class="th-sm">Group</th>
                                            <th class="th-sm">Sub-Group</th>
                                            <th class="th-sm">Category</th>
                                            <th class="th-sm">Sub-Category</th>
                                            <th class="th-sm">Status</th>
                                            <th class="th-sm">Pending With</th>
                                            <th class="th-sm">Operation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($HOLD as $key=>$val)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{isset($val->user->fullname) && $val->user->fullname!=''?$val->user->fullname:''}}</td>
                                            <td>{{isset($val->created_at) && $val->created_at!=''?date('d-m-Y H:i:s A',strtotime($val->created_at)):''}}</td>
                                            <td>{{isset($val->Material_Name) && $val->Material_Name!=''?$val->Material_Name:''}}</td>
                                            <td>{{isset($val->mtaerialdetails->material_name) && $val->mtaerialdetails->material_name!=''?$val->mtaerialdetails->material_name:''}}</td>
                                            <td>{{isset($val->HSN_Code) && $val->HSN_Code!=''?$val->HSN_Code:''}}</td>
                                            <td>{{isset($val->UOM) && $val->UOM!=''?$val->UOM:''}}</td>
                                            <td>{{isset($val->last_purchase_price) && $val->last_purchase_price!=''?$val->last_purchase_price:''}}</td>
                                            <td>{{isset($val->last_purchase_date) && $val->last_purchase_date!=''?$val->last_purchase_date:''}}</td>
                                            <td>{{isset($val->last_purchase_vndr_name) && $val->last_purchase_vndr_name!=''?$val->last_purchase_vndr_name:''}}</td>
                                            <td>{{isset($val->grp_name) && $val->grp_name!=''?$val->grp_name:''}}</td>
                                            <td>{{isset($val->sub_grp_name) && $val->sub_grp_name!=''?$val->sub_grp_name:''}}</td>
                                            <td>{{isset($val->cat_name) && $val->cat_name!=''?$val->cat_name:''}}</td>
                                            <td>{{isset($val->sub_cat_name) && $val->sub_cat_name!=''?$val->sub_cat_name:''}}</td>
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
                                                @if($val->Approve_status==='FORWARD' || $val->Approve_status=='' && isset($val->status) && $val->status!=1)
                                                Pending With
                                                @foreach($val->PendingWith as $name)
                                                {{isset($name->fullname) && $name->fullname!=''?$name->fullname:''}},
                                                @endforeach
                                                @elseif($val->Approve_status=='RECHECK' || $val->Approve_status=='OBJECT')
                                                {{isset($val->user->fullname) && $val->user->fullname!=''?'Pending With '.$val->user->fullname:''}}
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($val->status) && $val->status!=1)
                                                <a href="{{url('MaterialManagement/Material_view/'.$val->id.'/HOLD')}}" class="btn btn-primary">View</a>
                                                @if($val->Approve_status == 'RECHECK' && (isset($EXT[4]['inputer'])))
                                                <a href="{{url('MaterialManagement/AddMaterial/'.$val->id)}}"><button type="button" class="btn btn-secondary">Edit</button></a>
                                                @elseif($val->HoldStatus > 0)
                                                <a href="{{url('MaterialManagement/Release_Hold/'.$val->id)}}"><button type="button" class="btn btn-secondary">Release</button></a>
                                                @endif
                                                @else
                                                <a href="{{url('MaterialManagement/AddMaterial/'.$val->id)}}" class="btn btn-warning">Draft</a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Recheck" role="tabpanel" aria-labelledby="Rechecks">
                            <div class="table-responsive">
                                <table id="" class="table table-striped table-bordered example" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="th-sm">SL. No.</th>
                                            <th class="th-sm">Creater Name</th>
                                            <th class="th-sm">Date & Time</th>
                                            <th class="th-sm">Material ID</th>
                                            <th class="th-sm">Material Name</th>
                                            <th class="th-sm">HSN Code</th>
                                            <th class="th-sm">UOM</th>
                                            <th class="th-sm">Last Purchase Price</th>
                                            <th class="th-sm">Last Purchase Date</th>
                                            <th class="th-sm">Last Purchase Vendor Name</th>
                                            <th class="th-sm">Group</th>
                                            <th class="th-sm">Sub-Group</th>
                                            <th class="th-sm">Category</th>
                                            <th class="th-sm">Sub-Category</th>
                                            <th class="th-sm">Status</th>
                                            <th class="th-sm">Pending With</th>
                                            <th class="th-sm">Operation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($RECHECK as $key=>$val)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{isset($val->user->fullname) && $val->user->fullname!=''?$val->user->fullname:''}}</td>
                                            <td>{{isset($val->created_at) && $val->created_at!=''?date('d-m-Y H:i:s A',strtotime($val->created_at)):''}}</td>
                                            <td>{{isset($val->Material_Name) && $val->Material_Name!=''?$val->Material_Name:''}}</td>
                                            <td>{{isset($val->mtaerialdetails->material_name) && $val->mtaerialdetails->material_name!=''?$val->mtaerialdetails->material_name:''}}</td>
                                            <td>{{isset($val->HSN_Code) && $val->HSN_Code!=''?$val->HSN_Code:''}}</td>
                                            <td>{{isset($val->UOM) && $val->UOM!=''?$val->UOM:''}}</td>
                                            <td>{{isset($val->last_purchase_price) && $val->last_purchase_price!=''?$val->last_purchase_price:''}}</td>
                                            <td>{{isset($val->last_purchase_date) && $val->last_purchase_date!=''?$val->last_purchase_date:''}}</td>
                                            <td>{{isset($val->last_purchase_vndr_name) && $val->last_purchase_vndr_name!=''?$val->last_purchase_vndr_name:''}}</td>
                                            <td>{{isset($val->grp_name) && $val->grp_name!=''?$val->grp_name:''}}</td>
                                            <td>{{isset($val->sub_grp_name) && $val->sub_grp_name!=''?$val->sub_grp_name:''}}</td>
                                            <td>{{isset($val->cat_name) && $val->cat_name!=''?$val->cat_name:''}}</td>
                                            <td>{{isset($val->sub_cat_name) && $val->sub_cat_name!=''?$val->sub_cat_name:''}}</td>
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
                                                @if($val->Approve_status==='FORWARD' || $val->Approve_status=='' && isset($val->status) && $val->status!=1)
                                                Pending With
                                                @foreach($val->PendingWith as $name)
                                                {{isset($name->fullname) && $name->fullname!=''?$name->fullname:''}},
                                                @endforeach
                                                @elseif($val->Approve_status=='RECHECK' || $val->Approve_status=='OBJECT')
                                                {{isset($val->user->fullname) && $val->user->fullname!=''?'Pending With '.$val->user->fullname:''}}
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($val->status) && $val->status!=1)
                                                <a href="{{url('MaterialManagement/Material_view/'.$val->id.'/RECHECK')}}" class="btn btn-primary">View</a>
                                                @if($val->Approve_status == 'RECHECK' && (isset($EXT[4]['inputer'])))
                                                <a href="{{url('MaterialManagement/AddMaterial/'.$val->id)}}"><button type="button" class="btn btn-secondary">Edit</button></a>
                                                @elseif($val->HoldStatus > 0)
                                                <a href="{{url('MaterialManagement/Release_Hold/'.$val->id)}}"><button type="button" class="btn btn-secondary">Release</button></a>
                                                @endif
                                                @else
                                                <a href="{{url('MaterialManagement/AddMaterial/'.$val->id)}}" class="btn btn-warning">Draft</a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Object" role="tabpanel" aria-labelledby="Objects">
                            <div class="table-responsive">
                                <table id="" class="table table-striped table-bordered example" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="th-sm">SL. No.</th>
                                            <th class="th-sm">Creater Name</th>
                                            <th class="th-sm">Date & Time</th>
                                            <th class="th-sm">Material ID</th>
                                            <th class="th-sm">Material Name</th>
                                            <th class="th-sm">HSN Code</th>
                                            <th class="th-sm">UOM</th>
                                            <th class="th-sm">Last Purchase Price</th>
                                            <th class="th-sm">Last Purchase Date</th>
                                            <th class="th-sm">Last Purchase Vendor Name</th>
                                            <th class="th-sm">Group</th>
                                            <th class="th-sm">Sub-Group</th>
                                            <th class="th-sm">Category</th>
                                            <th class="th-sm">Sub-Category</th>
                                            <th class="th-sm">Status</th>
                                            <th class="th-sm">Pending With</th>
                                            <th class="th-sm">Operation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($OBJECT as $key=>$val)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{isset($val->user->fullname) && $val->user->fullname!=''?$val->user->fullname:''}}</td>
                                            <td>{{isset($val->created_at) && $val->created_at!=''?date('d-m-Y H:i:s A',strtotime($val->created_at)):''}}</td>
                                            <td>{{isset($val->Material_Name) && $val->Material_Name!=''?$val->Material_Name:''}}</td>
                                            <td>{{isset($val->mtaerialdetails->material_name) && $val->mtaerialdetails->material_name!=''?$val->mtaerialdetails->material_name:''}}</td>
                                            <td>{{isset($val->HSN_Code) && $val->HSN_Code!=''?$val->HSN_Code:''}}</td>
                                            <td>{{isset($val->UOM) && $val->UOM!=''?$val->UOM:''}}</td>
                                            <td>{{isset($val->last_purchase_price) && $val->last_purchase_price!=''?$val->last_purchase_price:''}}</td>
                                            <td>{{isset($val->last_purchase_date) && $val->last_purchase_date!=''?$val->last_purchase_date:''}}</td>
                                            <td>{{isset($val->last_purchase_vndr_name) && $val->last_purchase_vndr_name!=''?$val->last_purchase_vndr_name:''}}</td>
                                            <td>{{isset($val->grp_name) && $val->grp_name!=''?$val->grp_name:''}}</td>
                                            <td>{{isset($val->sub_grp_name) && $val->sub_grp_name!=''?$val->sub_grp_name:''}}</td>
                                            <td>{{isset($val->cat_name) && $val->cat_name!=''?$val->cat_name:''}}</td>
                                            <td>{{isset($val->sub_cat_name) && $val->sub_cat_name!=''?$val->sub_cat_name:''}}</td>
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
                                                @if($val->Approve_status==='FORWARD' || $val->Approve_status=='' && isset($val->status) && $val->status!=1)
                                                Pending With
                                                @foreach($val->PendingWith as $name)
                                                {{isset($name->fullname) && $name->fullname!=''?$name->fullname:''}},
                                                @endforeach
                                                @elseif($val->Approve_status=='RECHECK' || $val->Approve_status=='OBJECT')
                                                {{isset($val->user->fullname) && $val->user->fullname!=''?'Pending With '.$val->user->fullname:''}}
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($val->status) && $val->status!=1)
                                                <a href="{{url('MaterialManagement/Material_view/'.$val->id.'/OBJECT')}}" class="btn btn-primary">View</a>
                                                @if($val->Approve_status == 'RECHECK' && (isset($EXT[4]['inputer'])))
                                                <a href="{{url('MaterialManagement/AddMaterial/'.$val->id)}}"><button type="button" class="btn btn-secondary">Edit</button></a>
                                                @elseif($val->HoldStatus > 0)
                                                <a href="{{url('MaterialManagement/Release_Hold/'.$val->id)}}"><button type="button" class="btn btn-secondary">Release</button></a>
                                                @endif
                                                @else
                                                <a href="{{url('MaterialManagement/AddMaterial/'.$val->id)}}" class="btn btn-warning">Draft</a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Reject" role="tabpanel" aria-labelledby="Rejects">
                            <div class="table-responsive">
                                <table id="" class="table table-striped table-bordered example" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="th-sm">SL. No.</th>
                                            <th class="th-sm">Creater Name</th>
                                            <th class="th-sm">Date & Time</th>
                                            <th class="th-sm">Material ID</th>
                                            <th class="th-sm">Material Name</th>
                                            <th class="th-sm">HSN Code</th>
                                            <th class="th-sm">UOM</th>
                                            <th class="th-sm">Last Purchase Price</th>
                                            <th class="th-sm">Last Purchase Date</th>
                                            <th class="th-sm">Last Purchase Vendor Name</th>
                                            <th class="th-sm">Group</th>
                                            <th class="th-sm">Sub-Group</th>
                                            <th class="th-sm">Category</th>
                                            <th class="th-sm">Sub-Category</th>
                                            <th class="th-sm">Status</th>
                                            <th class="th-sm">Pending With</th>
                                            <th class="th-sm">Operation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($REJECT as $key=>$val)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{isset($val->user->fullname) && $val->user->fullname!=''?$val->user->fullname:''}}</td>
                                            <td>{{isset($val->created_at) && $val->created_at!=''?date('d-m-Y H:i:s A',strtotime($val->created_at)):''}}</td>
                                            <td>{{isset($val->Material_Name) && $val->Material_Name!=''?$val->Material_Name:''}}</td>
                                            <td>{{isset($val->mtaerialdetails->material_name) && $val->mtaerialdetails->material_name!=''?$val->mtaerialdetails->material_name:''}}</td>
                                            <td>{{isset($val->HSN_Code) && $val->HSN_Code!=''?$val->HSN_Code:''}}</td>
                                            <td>{{isset($val->UOM) && $val->UOM!=''?$val->UOM:''}}</td>
                                            <td>{{isset($val->last_purchase_price) && $val->last_purchase_price!=''?$val->last_purchase_price:''}}</td>
                                            <td>{{isset($val->last_purchase_date) && $val->last_purchase_date!=''?$val->last_purchase_date:''}}</td>
                                            <td>{{isset($val->last_purchase_vndr_name) && $val->last_purchase_vndr_name!=''?$val->last_purchase_vndr_name:''}}</td>
                                            <td>{{isset($val->grp_name) && $val->grp_name!=''?$val->grp_name:''}}</td>
                                            <td>{{isset($val->sub_grp_name) && $val->sub_grp_name!=''?$val->sub_grp_name:''}}</td>
                                            <td>{{isset($val->cat_name) && $val->cat_name!=''?$val->cat_name:''}}</td>
                                            <td>{{isset($val->sub_cat_name) && $val->sub_cat_name!=''?$val->sub_cat_name:''}}</td>
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
                                                @if($val->Approve_status==='FORWARD' || $val->Approve_status=='' && isset($val->status) && $val->status!=1)
                                                Pending With
                                                @foreach($val->PendingWith as $name)
                                                {{isset($name->fullname) && $name->fullname!=''?$name->fullname:''}},
                                                @endforeach
                                                @elseif($val->Approve_status=='RECHECK' || $val->Approve_status=='OBJECT')
                                                {{isset($val->user->fullname) && $val->user->fullname!=''?'Pending With '.$val->user->fullname:''}}
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($val->status) && $val->status!=1)
                                                <a href="{{url('MaterialManagement/Material_view/'.$val->id.'/REJECT')}}" class="btn btn-primary">View</a>
                                                @if($val->Approve_status == 'RECHECK' && (isset($EXT[4]['inputer'])))
                                                <a href="{{url('MaterialManagement/AddMaterial/'.$val->id)}}"><button type="button" class="btn btn-secondary">Edit</button></a>
                                                @elseif($val->HoldStatus > 0)
                                                <a href="{{url('MaterialManagement/Release_Hold/'.$val->id)}}"><button type="button" class="btn btn-secondary">Release</button></a>
                                                @endif
                                                @else
                                                <a href="{{url('MaterialManagement/AddMaterial/'.$val->id)}}" class="btn btn-warning">Draft</a>
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

    <br> <br>

</div>
</section>

</div>

</div>

</section>
@endsection
@push('custom-scripts')
<script>
    activeclass(9, 1);
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
    var tableID = 6;

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

        fetch("{{ url('MaterialManagement/getCheckBoxData') }}?ID=" + tableID, {
                method: 'GET',
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.columns) {
                    try {
                        var existingData = data.columns;
                        if (JSON.stringify(existingData) !== JSON.stringify(columnNamesToShow)) {
                            fetch("{{ url('MaterialManagement/CheckBoxStore') }}", {
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

        fetch("{{ url('MaterialManagement/getCheckBoxData') }}?ID=" + tableID, {
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
            url: "{{ url('MaterialManagement/CheckHoldExpiry') }}",
            method: 'GET',
            success: function(response) {
                response.forEach(function(lead) {
                    if (lead.action === 'HOLD' && lead.status === 1) {
                        var currentDate = new Date();
                        var holdDate = new Date(lead.days_for_holding);

                        if (holdDate < currentDate) {
                            UpdateStatus(lead.Material_Management_id, lead.userID);
                        }
                    }
                });
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    });

    function UpdateStatus(Material_Management_id, userID) {
        $.ajax({
            url: "{{ url('MaterialManagement/UpdateStatus') }}",
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            data: JSON.stringify({
                Material_Management_id: Material_Management_id,
                userID: userID
            }),
            success: function(response) {
                $('#statuss' + Material_Management_id).html('<span style="color: #FF9000;">Pending</span>');
            }
        });
    }
</script>
@endpush