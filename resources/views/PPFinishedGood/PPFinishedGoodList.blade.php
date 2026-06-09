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

        /*margin: 100px auto;*/

        font-family: Raleway;

        /*padding: 40px;*/

        width: 100%;

        /*min-width: 300px;*/

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
                <li class="breadcrumb-item">PP Finished Good View Page</li>
            </ol>
            <div class="addbtn">
                <a href="{{url('PPFinishedGood/ExportData')}}"><i class='fas fa-file-excel'></i></a>
                @if(isset($EXT[7]['inputer']))
                <a href="{{url('PPFinishedGood/PPFinishedGood')}}"><button class="btn btn-info">Add PP Finished Good</button></a>
                @endif
            </div>
            <div class="row">
                <div class="container">
                    <form action="{{url('PPFinishedGood/filtered')}}" method="POST">
                        @csrf
                        <div class="row filter">
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">Date From</label>
                                <input type="date" name="from_date" value="{{isset($fromdate) && $fromdate!=''?$fromdate:''}}" class="form-control form-control-sm">
                            </div>
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">Date To</label>
                                <input type="date" name="to_date" value="{{isset($todate) && $todate!=''?$todate:''}}" class="form-control form-control-sm">
                            </div>
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">Organization</label>
                                <select name="Organization" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all" {{isset($Organizations) && $Organizations === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach($Organization as $val)
                                    <option value="{{ isset($val->id) && $val->id!=''?$val->id:'' }}" {{isset($Organizations) && $Organizations==$val->id?'selected':''}}>{{ isset($val->organisation) && $val->organisation!=''?$val->organisation:''}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">Manufacturing Unit</label>
                                <select name="Manufacturing_Unit" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all" {{isset($ManufacturingUnits) && $ManufacturingUnits === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach($Manufacturing_unit as $val)
                                    <option value="{{ isset($val->id) && $val->id!=''?$val->id:'' }}" {{isset($ManufacturingUnits) && $ManufacturingUnits==$val->id?'selected':''}}>{{ isset($val->pname) && $val->pname!=''?$val->pname:''}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">Plant Name</label>
                                <select name="Plant_Name" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all" {{isset($PlantNames) && $PlantNames === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach($Plant_Name as $val)
                                    <option value="{{ isset($val->id) && $val->id!=''?$val->id:'' }}" {{isset($PlantNames) && $PlantNames==$val->id?'selected':''}}>{{ isset($val->spname) && $val->spname!=''?$val->spname:''}}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- <div class="col-2 mb-3">
                                <label for="" class="form-label">Category</label>
                                <select name="Category" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all" {{isset($Categoryss) && $Categoryss === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach($Category as $val)
                                    <option value="{{ isset($val->id) && $val->id!=''?$val->id:'' }}" {{isset($Categoryss) && $Categoryss==$val->id?'selected':''}}>{{ isset($val->category) && $val->category!=''?$val->category:''}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">Product</label>
                                <select name="Product" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all" {{isset($Productss) && $Productss === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach($Product as $val)
                                    <option value="{{ isset($val->id) && $val->id!=''?$val->id:'' }}" {{isset($Productss) && $Productss==$val->id?'selected':''}}>{{ isset($val->product) && $val->product!=''?$val->product:''}}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">For Month</label>
                                <select name="For_Primary" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all" {{isset($ForPrimarys) && $ForPrimarys === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach($DropdownData as $val)
                                    <?php
                                    $ForPrimary = isset($val->data->For_Primary) && $val->data->For_Primary != '' ? $val->data->For_Primary : '';
                                    if (!empty($ForPrimary) && !in_array($ForPrimary, $RepeatData)) {
                                        $RepeatData[] = $ForPrimary;
                                    ?>
                                        <option value="{{ $ForPrimary }}" {{isset($ForPrimarys) && $ForPrimarys==$ForPrimary?'selected':''}}>{{ $ForPrimary }}</option>
                                    <?php } ?>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">QTY</label>
                                <select name="QTY" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all" {{isset($QTYs) && $QTYs === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach($DropdownData as $val)
                                    <?php
                                    $QTY = isset($val->data->QTY) && $val->data->QTY != '' ? $val->data->QTY : '';
                                    if (!empty($QTY) && !in_array($QTY, $RepeatData)) {
                                        $RepeatData[] = $QTY;
                                    ?>
                                        <option value="{{ $QTY }}" {{isset($QTYs) && $QTYs==$QTY?'selected':''}}>{{ $QTY }}</option>
                                    <?php } ?>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">Finished Good(FG)</label>
                                <select name="Raw_Material" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all" {{isset($RawMaterials) && $RawMaterials === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach($DropdownData as $val)
                                    <?php
                                    $RawMaterial = isset($val->RawMaterial->material_name) && $val->RawMaterial->material_name!=''?$val->RawMaterial->material_name:'';
                                    $RawMaterialid = isset($val->RawMaterial->id) && $val->RawMaterial->id!=''?$val->RawMaterial->id:'';
                                    if (!empty($RawMaterial) && !in_array($RawMaterial, $RepeatData)) {
                                        $RepeatData[] = $RawMaterial;
                                    ?>
                                        <option value="{{ $RawMaterialid }}" {{isset($RawMaterials) && $RawMaterials==$RawMaterialid?'selected':''}}>{{ $RawMaterial }}</option>
                                    <?php } ?>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">HSN Code(FG)</label>
                                <select name="HSN_Code" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all" {{isset($HSNCodes) && $HSNCodes === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach($DropdownData as $val)
                                    <?php
                                    $HSNCode = isset($val->data->HSN_Code) && $val->data->HSN_Code!=''?$val->data->HSN_Code:'';
                                    if (!empty($HSNCode) && !in_array($HSNCode, $RepeatData)) {
                                        $RepeatData[] = $HSNCode;
                                    ?>
                                        <option value="{{ $HSNCode }}" {{isset($HSNCodes) && $HSNCodes==$HSNCode?'selected':''}}>{{ $HSNCode }}</option>
                                    <?php } ?>
                                    @endforeach
                                </select>
                            </div>
                             <div class="col-2 mb-3">
                                <label for="" class="form-label">UOM</label>
                                <select name="UOM" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all" {{isset($UOMss) && $UOMss === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach($DropdownData as $val)
                                    <?php
                                    $UOMCode = isset($val->data->UOM) && $val->data->UOM != '' ? $val->data->UOM : '';
                                    if (!empty($UOMCode) && !in_array($UOMCode, $RepeatData)) {
                                        $RepeatData[] = $UOMCode;
                                    ?>
                                        <option value="{{ $UOMCode }}" {{isset($UOMss) && $UOMss==$UOMCode?'selected':''}}>{{ $UOMCode }}</option>
                                    <?php } ?>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">Per Day</label>
                                <select name="Per_Day" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all" {{isset($PerDays) && $PerDays === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach($DropdownData as $val)
                                    <?php
                                    $PerDay = isset($val->data->Per_Day) && $val->data->Per_Day != '' ? $val->data->Per_Day : '';
                                    if (!empty($PerDay) && !in_array($PerDay, $RepeatData)) {
                                        $RepeatData[] = $PerDay;
                                    ?>
                                        <option value="{{ $PerDay }}" {{isset($PerDays) && $PerDays==$PerDay?'selected':''}}>{{ $PerDay }}</option>
                                    <?php } ?>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">Per Shift</label>
                                <select name="Per_Shift" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all" {{isset($PerShifts) && $PerShifts === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach($DropdownData as $val)
                                    <?php
                                    $PerShift = isset($val->data->Per_Shift) && $val->data->Per_Shift != '' ? $val->data->Per_Shift : '';
                                    if (!empty($PerShift) && !in_array($PerShift, $RepeatData)) {
                                        $RepeatData[] = $PerShift;
                                    ?>
                                        <option value="{{ $PerShift }}" {{isset($PerShifts) && $PerShifts==$PerShift?'selected':''}}>{{ $PerShift }}</option>
                                    <?php } ?>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2 mt-4">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                <a href="{{url('PPFinishedGood/PPFinishedGoodList')}}"><button type="button" class="btn btn-secondary"><i class="fa fa-refresh"></i></button></a>
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
                                            <input type="checkbox" class="form-check-input" id="Planing_Batch_No" value="Planing Batch No" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Planing_Batch_No">Planing Batch No</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Organization" value="Organization" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Organization">Organization</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Manufacturing Unit" value="Manufacturing Unit" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Manufacturing Unit">Manufacturing Unit</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Plant_Name" value="Plant Name" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Plant_Name">Plant Name</label>
                                        </div>
                                        {{-- <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Category" value="Category" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Category">Category</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Product" value="Product" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Product">Product</label>
                                        </div> --}}
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="For_Month" value="For Month" onclick="filterTable(this)">
                                            <label class="form-check-label" for="For_Month">For Month</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="QTY" value="QTY" onclick="filterTable(this)">
                                            <label class="form-check-label" for="QTY">QTY</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Raw_Material" value="Raw Material(FG)" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Raw_Material">Raw Material(FG)</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="HSN_Code" value="HSN Code(FG)" onclick="filterTable(this)">
                                            <label class="form-check-label" for="HSN_Code">HSN Code(FG)</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="UOM" value="UOM" onclick="filterTable(this)">
                                            <label class="form-check-label" for="UOM">UOM</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Per_Day" value="Per Day" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Per_Day">Per Day</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Per_Shift" value="Per Shift" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Per_Shift">Per Shift</label>
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
                            <a class="nav-link count active" id="Alls" data-mdb-toggle="tab" href="#All" role="tab" aria-controls="All" aria-selected="true">All <span class="countss">{{count($PP_data)}}</span></a>
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
                                <table id="" class="table table-sm table-striped table-bordered example" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="th-sm">SL. No.</th>
                                            <th class="th-sm">Creater Name</th>
                                            <th class="th-sm">Date & Time</th>
                                            <th class="th-sm">Planing Batch No</th>
                                            <th class="th-sm">Organization</th>
                                            <th class="th-sm">Manufacturing Unit</th>
                                            <th class="th-sm">Plant Name</th>
                                            {{-- <th class="th-sm">Category</th>
                                            <th class="th-sm">Product</th> --}}
                                            <th class="th-sm">For Month</th>
                                            <th class="th-sm">QTY</th>
                                            <th class="th-sm">Raw Material(FG)</th>
                                            <th class="th-sm">HSN Code(FG)</th>
                                            <th class="th-sm">UOM</th>
                                            <th class="th-sm">Per Day</th>
                                            <th class="th-sm">Per Shift</th>
                                            <th class="th-sm">Status</th>
                                            <th class="th-sm">Pending With</th>
                                            <th class="th-sm">Operation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $sesionarr=[];
                                        @endphp
                                        @foreach($PP_data as $key => $val)
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
                                            <td>{{isset($val->Planing_Batch_No) && $val->Planing_Batch_No!=''?$val->Planing_Batch_No:''}}</td>
                                            <td>{{isset($val->data->organisation) && $val->data->organisation!=''?$val->data->organisation:''}}</td>
                                            <td>{{isset($val->data->pname) && $val->data->pname!=''?$val->data->pname:''}}</td>
                                            <td>{{isset($val->data->spname) && $val->data->spname!=''?$val->data->spname:''}}</td>
                                            {{-- <td>{{isset($val->category->category) && $val->category->category!=''?$val->category->category:''}}</td>
                                            <td>{{isset($val->Product->product) && $val->Product->product!=''?$val->Product->product:''}}</td> --}}
                                            <td>{{isset($val->data->For_Primary) && $val->data->For_Primary!=''?$val->data->For_Primary:''}}</td>
                                            <td>{{isset($val->data->QTY) && $val->data->QTY!=''?$val->data->QTY:''}}</td>
                                            <td>{{isset($val->Raw_Material->matname) && $val->Raw_Material->matname!=''?$val->Raw_Material->matname:''}}</td>
                                            <td>{{isset($val->data->HSN_Code) && $val->data->HSN_Code!=''?$val->data->HSN_Code:''}}</td>
                                            <td>{{isset($val->data->UOM) && $val->data->UOM!=''?$val->data->UOM:''}}</td>
                                            <td>{{isset($val->data->Per_Day) && $val->data->Per_Day!=''?$val->data->Per_Day:''}}</td>
                                            <td>{{isset($val->data->Per_Shift) && $val->data->Per_Shift!=''?$val->data->Per_Shift:''}}</td>
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
                                            <td>
                                                @if(isset($val->status) && $val->status!=1)
                                                <a href="{{url('PPFinishedGood/PPFinishedGood_view/'.$val->id.'/ALL')}}" class="btn btn-primary">View</a>
                                                @if($val->Approve_status == 'RECHECK' && isset($EXT[7]['inputer']))
                                                <a href="{{url('PPFinishedGood/PPFinishedGood/'.$val->id)}}"><button type="button" class="btn btn-secondary">Edit</button></a>
                                                @elseif($val->HoldStatus > 0)
                                                <a href="{{url('PPFinishedGood/Release_Hold/'.$val->id)}}" class="btn btn-secondary">Release</a>
                                                @endif
                                                @else
                                                <a href="{{url('PPFinishedGood/PPFinishedGood/'.$val->id)}}" class="btn btn-warning">Draft</a>
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
                                <table id="" class="table table-sm table-striped table-bordered example" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="th-sm">SL. No.</th>
                                            <th class="th-sm">Creater Name</th>
                                            <th class="th-sm">Date & Time</th>
                                            <th class="th-sm">Planing Batch No</th>
                                            <th class="th-sm">Organization</th>
                                            <th class="th-sm">Manufacturing Unit</th>
                                            <th class="th-sm">Plant Name</th>
                                            {{-- <th class="th-sm">Category</th>
                                            <th class="th-sm">Product</th> --}}
                                            <th class="th-sm">For Month</th>
                                            <th class="th-sm">QTY</th>
                                            <th class="th-sm">Raw Material(FG)</th>
                                            <th class="th-sm">HSN Code(FG)</th>
                                            <th class="th-sm">UOM</th>
                                            <th class="th-sm">Per Day</th>
                                            <th class="th-sm">Per Shift</th>
                                            <th class="th-sm">Status</th>
                                            <th class="th-sm">Pending With</th>
                                            <th class="th-sm">Operation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($approved as $key => $val)
                                        <td>{{$key+1}}</td>
                                        <td>{{isset($val->user->fullname) && $val->user->fullname!=''?$val->user->fullname:''}}</td>
                                        <td>{{isset($val->created_at) && $val->created_at!=''?date('d-m-Y H:i:s A',strtotime($val->created_at)):''}}</td>
                                        <td>{{isset($val->Planing_Batch_No) && $val->Planing_Batch_No!=''?$val->Planing_Batch_No:''}}</td>
                                        <td>{{isset($val->data->organisation) && $val->data->organisation!=''?$val->data->organisation:''}}</td>
                                        <td>{{isset($val->data->pname) && $val->data->pname!=''?$val->data->pname:''}}</td>
                                        <td>{{isset($val->data->spname) && $val->data->spname!=''?$val->data->spname:''}}</td>
                                        {{-- <td>{{isset($val->category->category) && $val->category->category!=''?$val->category->category:''}}</td>
                                        <td>{{isset($val->Product->product) && $val->Product->product!=''?$val->Product->product:''}}</td> --}}
                                        <td>{{isset($val->data->For_Primary) && $val->data->For_Primary!=''?$val->data->For_Primary:''}}</td>
                                        <td>{{isset($val->data->QTY) && $val->data->QTY!=''?$val->data->QTY:''}}</td>
                                        <td>{{isset($val->Raw_Material->matname) && $val->Raw_Material->matname!=''?$val->Raw_Material->matname:''}}</td>
                                        <td>{{isset($val->data->HSN_Code) && $val->data->HSN_Code!=''?$val->data->HSN_Code:''}}</td>
                                        <td>{{isset($val->data->UOM) && $val->data->UOM!=''?$val->data->UOM:''}}</td>
                                        <td>{{isset($val->data->Per_Day) && $val->data->Per_Day!=''?$val->data->Per_Day:''}}</td>
                                        <td>{{isset($val->data->Per_Shift) && $val->data->Per_Shift!=''?$val->data->Per_Shift:''}}</td>
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
                                        <td>
                                            @if(isset($val->status) && $val->status!=1)
                                            <a href="{{url('PPFinishedGood/PPFinishedGood_view/'.$val->id.'/APPROVE')}}" class="btn btn-primary">View</a>
                                            @if($val->Approve_status == 'RECHECK' && isset($EXT[7]['inputer']))
                                            <a href="{{url('PPFinishedGood/PPFinishedGood/'.$val->id)}}"><button type="button" class="btn btn-secondary">Edit</button></a>
                                            @elseif($val->HoldStatus > 0)
                                            <a href="{{url('PPFinishedGood/Release_Hold/'.$val->id)}}" class="btn btn-secondary">Release</a>
                                            @endif
                                            @else
                                            <a href="{{url('PPFinishedGood/PPFinishedGood/'.$val->id)}}" class="btn btn-warning">Draft</a>
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
                                <table id="" class="table table-sm table-striped table-bordered example" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="th-sm">SL. No.</th>
                                            <th class="th-sm">Creater Name</th>
                                            <th class="th-sm">Date & Time</th>
                                            <th class="th-sm">Planing Batch No</th>
                                            <th class="th-sm">Organization</th>
                                            <th class="th-sm">Manufacturing Unit</th>
                                            <th class="th-sm">Plant Name</th>
                                            {{-- <th class="th-sm">Category</th>
                                            <th class="th-sm">Product</th> --}}
                                            <th class="th-sm">For Month</th>
                                            <th class="th-sm">QTY</th>
                                            <th class="th-sm">Raw Material(FG)</th>
                                            <th class="th-sm">HSN Code(FG)</th>
                                            <th class="th-sm">UOM</th>
                                            <th class="th-sm">Per Day</th>
                                            <th class="th-sm">Per Shift</th>
                                            <th class="th-sm">Status</th>
                                            <th class="th-sm">Pending With</th>
                                            <th class="th-sm">Operation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pending as $key => $val)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{isset($val->user->fullname) && $val->user->fullname!=''?$val->user->fullname:''}}</td>
                                            <td>{{isset($val->created_at) && $val->created_at!=''?date('d-m-Y H:i:s A',strtotime($val->created_at)):''}}</td>
                                            <td>{{isset($val->Planing_Batch_No) && $val->Planing_Batch_No!=''?$val->Planing_Batch_No:''}}</td>
                                            <td>{{isset($val->data->organisation) && $val->data->organisation!=''?$val->data->organisation:''}}</td>
                                            <td>{{isset($val->data->pname) && $val->data->pname!=''?$val->data->pname:''}}</td>
                                            <td>{{isset($val->data->spname) && $val->data->spname!=''?$val->data->spname:''}}</td>
                                            {{-- <td>{{isset($val->category->category) && $val->category->category!=''?$val->category->category:''}}</td>
                                            <td>{{isset($val->Product->product) && $val->Product->product!=''?$val->Product->product:''}}</td> --}}
                                            <td>{{isset($val->data->For_Primary) && $val->data->For_Primary!=''?$val->data->For_Primary:''}}</td>
                                            <td>{{isset($val->data->QTY) && $val->data->QTY!=''?$val->data->QTY:''}}</td>
                                            <td>{{isset($val->Raw_Material->matname) && $val->Raw_Material->matname!=''?$val->Raw_Material->matname:''}}</td>
                                            <td>{{isset($val->data->HSN_Code) && $val->data->HSN_Code!=''?$val->data->HSN_Code:''}}</td>
                                            <td>{{isset($val->data->UOM) && $val->data->UOM!=''?$val->data->UOM:''}}</td>
                                            <td>{{isset($val->data->Per_Day) && $val->data->Per_Day!=''?$val->data->Per_Day:''}}</td>
                                            <td>{{isset($val->data->Per_Shift) && $val->data->Per_Shift!=''?$val->data->Per_Shift:''}}</td>
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
                                            <td>
                                                @if(isset($val->status) && $val->status!=1)
                                                <a href="{{url('PPFinishedGood/PPFinishedGood_view/'.$val->id.'/PENDING')}}" class="btn btn-primary">View</a>
                                                @if($val->Approve_status == 'RECHECK' && isset($EXT[7]['inputer']))
                                                <a href="{{url('PPFinishedGood/PPFinishedGood/'.$val->id)}}"><button type="button" class="btn btn-secondary">Edit</button></a>
                                                @elseif($val->HoldStatus > 0)
                                                <a href="{{url('PPFinishedGood/Release_Hold/'.$val->id)}}" class="btn btn-secondary">Release</a>
                                                @endif
                                                @else
                                                <a href="{{url('PPFinishedGood/PPFinishedGood/'.$val->id)}}" class="btn btn-warning">Draft</a>
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
                                <table id="" class="table table-sm table-striped table-bordered example" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="th-sm">SL. No.</th>
                                            <th class="th-sm">Creater Name</th>
                                            <th class="th-sm">Date & Time</th>
                                            <th class="th-sm">Planing Batch No</th>
                                            <th class="th-sm">Organization</th>
                                            <th class="th-sm">Manufacturing Unit</th>
                                            <th class="th-sm">Plant Name</th>
                                            {{-- <th class="th-sm">Category</th>
                                            <th class="th-sm">Product</th> --}}
                                            <th class="th-sm">For Month</th>
                                            <th class="th-sm">QTY</th>
                                            <th class="th-sm">Raw Material(FG)</th>
                                            <th class="th-sm">HSN Code(FG)</th>
                                            <th class="th-sm">UOM</th>
                                            <th class="th-sm">Per Day</th>
                                            <th class="th-sm">Per Shift</th>
                                            <th class="th-sm">Status</th>
                                            <th class="th-sm">Pending With</th>
                                            <th class="th-sm">Operation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($HOLD as $key => $val)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{isset($val->user->fullname) && $val->user->fullname!=''?$val->user->fullname:''}}</td>
                                            <td>{{isset($val->created_at) && $val->created_at!=''?date('d-m-Y H:i:s A',strtotime($val->created_at)):''}}</td>
                                            <td>{{isset($val->Planing_Batch_No) && $val->Planing_Batch_No!=''?$val->Planing_Batch_No:''}}</td>
                                            <td>{{isset($val->data->organisation) && $val->data->organisation!=''?$val->data->organisation:''}}</td>
                                            <td>{{isset($val->data->pname) && $val->data->pname!=''?$val->data->pname:''}}</td>
                                            <td>{{isset($val->data->spname) && $val->data->spname!=''?$val->data->spname:''}}</td>
                                            {{-- <td>{{isset($val->category->category) && $val->category->category!=''?$val->category->category:''}}</td>
                                            <td>{{isset($val->Product->product) && $val->Product->product!=''?$val->Product->product:''}}</td> --}}
                                            <td>{{isset($val->data->For_Primary) && $val->data->For_Primary!=''?$val->data->For_Primary:''}}</td>
                                            <td>{{isset($val->data->QTY) && $val->data->QTY!=''?$val->data->QTY:''}}</td>
                                            <td>{{isset($val->Raw_Material->matname) && $val->Raw_Material->matname!=''?$val->Raw_Material->matname:''}}</td>
                                            <td>{{isset($val->data->HSN_Code) && $val->data->HSN_Code!=''?$val->data->HSN_Code:''}}</td>
                                            <td>{{isset($val->data->UOM) && $val->data->UOM!=''?$val->data->UOM:''}}</td>
                                            <td>{{isset($val->data->Per_Day) && $val->data->Per_Day!=''?$val->data->Per_Day:''}}</td>
                                            <td>{{isset($val->data->Per_Shift) && $val->data->Per_Shift!=''?$val->data->Per_Shift:''}}</td>
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
                                            <td>
                                                @if(isset($val->status) && $val->status!=1)
                                                <a href="{{url('PPFinishedGood/PPFinishedGood_view/'.$val->id.'/HOLD')}}" class="btn btn-primary">View</a>
                                                @if($val->Approve_status == 'RECHECK' && isset($EXT[7]['inputer']))
                                                <a href="{{url('PPFinishedGood/PPFinishedGood/'.$val->id)}}"><button type="button" class="btn btn-secondary">Edit</button></a>
                                                @elseif($val->HoldStatus > 0)
                                                <a href="{{url('PPFinishedGood/Release_Hold/'.$val->id)}}" class="btn btn-secondary">Release</a>
                                                @endif
                                                @else
                                                <a href="{{url('PPFinishedGood/PPFinishedGood/'.$val->id)}}" class="btn btn-warning">Draft</a>
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
                                <table id="" class="table table-sm table-striped table-bordered example" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="th-sm">SL. No.</th>
                                            <th class="th-sm">Creater Name</th>
                                            <th class="th-sm">Date & Time</th>
                                            <th class="th-sm">Planing Batch No</th>
                                            <th class="th-sm">Organization</th>
                                            <th class="th-sm">Manufacturing Unit</th>
                                            <th class="th-sm">Plant Name</th>
                                            {{-- <th class="th-sm">Category</th>
                                            <th class="th-sm">Product</th> --}}
                                            <th class="th-sm">For Month</th>
                                            <th class="th-sm">QTY</th>
                                            <th class="th-sm">Raw Material(FG)</th>
                                            <th class="th-sm">HSN Code(FG)</th>
                                            <th class="th-sm">UOM</th>
                                            <th class="th-sm">Per Day</th>
                                            <th class="th-sm">Per Shift</th>
                                            <th class="th-sm">Status</th>
                                            <th class="th-sm">Pending With</th>
                                            <th class="th-sm">Operation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($RECHECK as $key => $val)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{isset($val->user->fullname) && $val->user->fullname!=''?$val->user->fullname:''}}</td>
                                            <td>{{isset($val->created_at) && $val->created_at!=''?date('d-m-Y H:i:s A',strtotime($val->created_at)):''}}</td>
                                            <td>{{isset($val->Planing_Batch_No) && $val->Planing_Batch_No!=''?$val->Planing_Batch_No:''}}</td>
                                            <td>{{isset($val->data->organisation) && $val->data->organisation!=''?$val->data->organisation:''}}</td>
                                            <td>{{isset($val->data->pname) && $val->data->pname!=''?$val->data->pname:''}}</td>
                                            <td>{{isset($val->data->spname) && $val->data->spname!=''?$val->data->spname:''}}</td>
                                            {{-- <td>{{isset($val->category->category) && $val->category->category!=''?$val->category->category:''}}</td>
                                            <td>{{isset($val->Product->product) && $val->Product->product!=''?$val->Product->product:''}}</td> --}}
                                            <td>{{isset($val->data->For_Primary) && $val->data->For_Primary!=''?$val->data->For_Primary:''}}</td>
                                            <td>{{isset($val->data->QTY) && $val->data->QTY!=''?$val->data->QTY:''}}</td>
                                            <td>{{isset($val->Raw_Material->matname) && $val->Raw_Material->matname!=''?$val->Raw_Material->matname:''}}</td>
                                            <td>{{isset($val->data->HSN_Code) && $val->data->HSN_Code!=''?$val->data->HSN_Code:''}}</td>
                                            <td>{{isset($val->data->UOM) && $val->data->UOM!=''?$val->data->UOM:''}}</td>
                                            <td>{{isset($val->data->Per_Day) && $val->data->Per_Day!=''?$val->data->Per_Day:''}}</td>
                                            <td>{{isset($val->data->Per_Shift) && $val->data->Per_Shift!=''?$val->data->Per_Shift:''}}</td>
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
                                            <td>
                                                @if(isset($val->status) && $val->status!=1)
                                                <a href="{{url('PPFinishedGood/PPFinishedGood_view/'.$val->id.'/RECHECK')}}" class="btn btn-primary">View</a>
                                                @if($val->Approve_status == 'RECHECK' && isset($EXT[7]['inputer']))
                                                <a href="{{url('PPFinishedGood/PPFinishedGood/'.$val->id)}}"><button type="button" class="btn btn-secondary">Edit</button></a>
                                                @elseif($val->HoldStatus > 0)
                                                <a href="{{url('PPFinishedGood/Release_Hold/'.$val->id)}}" class="btn btn-secondary">Release</a>
                                                @endif
                                                @else
                                                <a href="{{url('PPFinishedGood/PPFinishedGood/'.$val->id)}}" class="btn btn-warning">Draft</a>
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
                                <table id="" class="table table-sm table-striped table-bordered example" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="th-sm">SL. No.</th>
                                            <th class="th-sm">Creater Name</th>
                                            <th class="th-sm">Date & Time</th>
                                            <th class="th-sm">Planing Batch No</th>
                                            <th class="th-sm">Organization</th>
                                            <th class="th-sm">Manufacturing Unit</th>
                                            <th class="th-sm">Plant Name</th>
                                            {{-- <th class="th-sm">Category</th>
                                            <th class="th-sm">Product</th> --}}
                                            <th class="th-sm">For Month</th>
                                            <th class="th-sm">QTY</th>
                                            <th class="th-sm">Raw Material(FG)</th>
                                            <th class="th-sm">HSN Code(FG)</th>
                                            <th class="th-sm">UOM</th>
                                            <th class="th-sm">Per Day</th>
                                            <th class="th-sm">Per Shift</th>
                                            <th class="th-sm">Status</th>
                                            <th class="th-sm">Pending With</th>
                                            <th class="th-sm">Operation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($OBJECT as $key => $val)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{isset($val->user->fullname) && $val->user->fullname!=''?$val->user->fullname:''}}</td>
                                            <td>{{isset($val->created_at) && $val->created_at!=''?date('d-m-Y H:i:s A',strtotime($val->created_at)):''}}</td>
                                            <td>{{isset($val->Planing_Batch_No) && $val->Planing_Batch_No!=''?$val->Planing_Batch_No:''}}</td>
                                            <td>{{isset($val->data->organisation) && $val->data->organisation!=''?$val->data->organisation:''}}</td>
                                            <td>{{isset($val->data->pname) && $val->data->pname!=''?$val->data->pname:''}}</td>
                                            <td>{{isset($val->data->spname) && $val->data->spname!=''?$val->data->spname:''}}</td>
                                            {{-- <td>{{isset($val->category->category) && $val->category->category!=''?$val->category->category:''}}</td>
                                            <td>{{isset($val->Product->product) && $val->Product->product!=''?$val->Product->product:''}}</td> --}}
                                            <td>{{isset($val->data->For_Primary) && $val->data->For_Primary!=''?$val->data->For_Primary:''}}</td>
                                            <td>{{isset($val->data->QTY) && $val->data->QTY!=''?$val->data->QTY:''}}</td>
                                            <td>{{isset($val->Raw_Material->matname) && $val->Raw_Material->matname!=''?$val->Raw_Material->matname:''}}</td>
                                            <td>{{isset($val->data->HSN_Code) && $val->data->HSN_Code!=''?$val->data->HSN_Code:''}}</td>
                                            <td>{{isset($val->data->UOM) && $val->data->UOM!=''?$val->data->UOM:''}}</td>
                                            <td>{{isset($val->data->Per_Day) && $val->data->Per_Day!=''?$val->data->Per_Day:''}}</td>
                                            <td>{{isset($val->data->Per_Shift) && $val->data->Per_Shift!=''?$val->data->Per_Shift:''}}</td>
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
                                            <td>
                                                @if(isset($val->status) && $val->status!=1)
                                                <a href="{{url('PPFinishedGood/PPFinishedGood_view/'.$val->id.'/OBJECT')}}" class="btn btn-primary">View</a>
                                                @if($val->Approve_status == 'RECHECK' && isset($EXT[7]['inputer']))
                                                <a href="{{url('PPFinishedGood/PPFinishedGood/'.$val->id)}}"><button type="button" class="btn btn-secondary">Edit</button></a>
                                                @elseif($val->HoldStatus > 0)
                                                <a href="{{url('PPFinishedGood/Release_Hold/'.$val->id)}}" class="btn btn-secondary">Release</a>
                                                @endif
                                                @else
                                                <a href="{{url('PPFinishedGood/PPFinishedGood/'.$val->id)}}" class="btn btn-warning">Draft</a>
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
                                <table id="" class="table table-sm table-striped table-bordered example" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="th-sm">SL. No.</th>
                                            <th class="th-sm">Creater Name</th>
                                            <th class="th-sm">Date & Time</th>
                                            <th class="th-sm">Planing Batch No</th>
                                            <th class="th-sm">Organization</th>
                                            <th class="th-sm">Manufacturing Unit</th>
                                            <th class="th-sm">Plant Name</th>
                                            {{-- <th class="th-sm">Category</th>
                                            <th class="th-sm">Product</th> --}}
                                            <th class="th-sm">For Month</th>
                                            <th class="th-sm">QTY</th>
                                            <th class="th-sm">Raw Material(FG)</th>
                                            <th class="th-sm">HSN Code(FG)</th>
                                            <th class="th-sm">UOM</th>
                                            <th class="th-sm">Per Day</th>
                                            <th class="th-sm">Per Shift</th>
                                            <th class="th-sm">Status</th>
                                            <th class="th-sm">Pending With</th>
                                            <th class="th-sm">Operation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($REJECT as $key => $val)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{isset($val->user->fullname) && $val->user->fullname!=''?$val->user->fullname:''}}</td>
                                            <td>{{isset($val->created_at) && $val->created_at!=''?date('d-m-Y H:i:s A',strtotime($val->created_at)):''}}</td>
                                            <td>{{isset($val->Planing_Batch_No) && $val->Planing_Batch_No!=''?$val->Planing_Batch_No:''}}</td>
                                            <td>{{isset($val->data->organisation) && $val->data->organisation!=''?$val->data->organisation:''}}</td>
                                            <td>{{isset($val->data->pname) && $val->data->pname!=''?$val->data->pname:''}}</td>
                                            <td>{{isset($val->data->spname) && $val->data->spname!=''?$val->data->spname:''}}</td>
                                            {{-- <td>{{isset($val->category->category) && $val->category->category!=''?$val->category->category:''}}</td>
                                            <td>{{isset($val->Product->product) && $val->Product->product!=''?$val->Product->product:''}}</td> --}}
                                            <td>{{isset($val->data->For_Primary) && $val->data->For_Primary!=''?$val->data->For_Primary:''}}</td>
                                            <td>{{isset($val->data->QTY) && $val->data->QTY!=''?$val->data->QTY:''}}</td>
                                            <td>{{isset($val->Raw_Material->matname) && $val->Raw_Material->matname!=''?$val->Raw_Material->matname:''}}</td>
                                            <td>{{isset($val->data->HSN_Code) && $val->data->HSN_Code!=''?$val->data->HSN_Code:''}}</td>
                                            <td>{{isset($val->data->UOM) && $val->data->UOM!=''?$val->data->UOM:''}}</td>
                                            <td>{{isset($val->data->Per_Day) && $val->data->Per_Day!=''?$val->data->Per_Day:''}}</td>
                                            <td>{{isset($val->data->Per_Shift) && $val->data->Per_Shift!=''?$val->data->Per_Shift:''}}</td>
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
                                            <td>
                                                @if(isset($val->status) && $val->status!=1)
                                                <a href="{{url('PPFinishedGood/PPFinishedGood_view/'.$val->id.'/REJECT')}}" class="btn btn-primary">View</a>
                                                @if($val->Approve_status == 'RECHECK' && isset($EXT[7]['inputer']))
                                                <a href="{{url('PPFinishedGood/PPFinishedGood/'.$val->id)}}"><button type="button" class="btn btn-secondary">Edit</button></a>
                                                @elseif($val->HoldStatus > 0)
                                                <a href="{{url('PPFinishedGood/Release_Hold/'.$val->id)}}" class="btn btn-secondary">Release</a>
                                                @endif
                                                @else
                                                <a href="{{url('PPFinishedGood/PPFinishedGood/'.$val->id)}}" class="btn btn-warning">Draft</a>
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
        </section>
    </div>
</div>
</div>
</div>
</section>
@endsection
@push('custom-scripts')
<script>
    $(document).ready(function() {
        activeclass(13, 1);
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
    var tableID = 13;

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

        fetch("{{ url('PPFinishedGood/getCheckBoxData') }}?ID=" + tableID, {
                method: 'GET',
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.columns) {
                    try {
                        var existingData = data.columns;
                        if (JSON.stringify(existingData) !== JSON.stringify(columnNamesToShow)) {
                            fetch("{{ url('PPFinishedGood/CheckBoxStore') }}", {
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

        fetch("{{ url('PPFinishedGood/getCheckBoxData') }}?ID=" + tableID, {
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
            url: "{{ url('PPFinishedGood/CheckHoldExpiry') }}",
            method: 'GET',
            success: function(response) {
                response.forEach(function(lead) {
                    if (lead.action === 'HOLD' && lead.status === 1) {
                        var currentDate = new Date();
                        var holdDate = new Date(lead.days_for_holding);

                        if (holdDate < currentDate) {
                            UpdateStatus(lead.PPFinishedGood_id, lead.userID);
                        }
                    }
                });
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    });

    function UpdateStatus(PPFinishedGood_id, userID) {
        $.ajax({
            url: "{{ url('PPFinishedGood/UpdateStatus') }}",
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            data: JSON.stringify({
                PPFinishedGood_id: PPFinishedGood_id,
                userID: userID
            }),
            success: function(response) {
                $('#statuss' + PPFinishedGood_id).html('<span style="color: #FF9000;">Pending</span>');
            }
        });
    }
</script>
@endpush