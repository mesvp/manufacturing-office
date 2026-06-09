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
                <li class="breadcrumb-item">Product Categories View Page</li>
            </ol>
            <div class="addbtn">
                <a href="{{url('ProductCategories/ExportData')}}"><i class='fas fa-file-excel'></i></a>
                @if(isset($EXT[5]['inputer']))
                <a href="{{url('ProductCategories/AddProduct')}}"><button class="btn btn-info">Add Product Categories</button></a>
                @endif
            </div>
            <div class="row">
                <div class="container">
                    <form action="{{url('ProductCategories/filtered')}}" method="POST">
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
                            {{-- <div class="col-2 mb-3">
                                <label for="" class="form-label">Organization Name</label>
                                <select name="Organization_Name" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all" {{isset($OrganizationName) && $OrganizationName === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach($Organization as $val)
                                    <option value="{{ isset($val->id) && $val->id!=''?$val->id:'' }}" {{isset($OrganizationName) && $OrganizationName==$val->id?'selected':''}}>{{ isset($val->organization) && $val->organization!=''?$val->organization:''}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">Manufacturing Unit</label>
                                <select name="Manufacturing_Unit" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all" {{isset($ManufacturingUnits) && $ManufacturingUnits === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach($Manufacturing_Unit as $val)
                                    <option value="{{ isset($val->id) && $val->id!=''?$val->id:'' }}" {{isset($ManufacturingUnits) && $ManufacturingUnits==$val->id?'selected':''}}>{{ isset($val->Manufacturing_unit) && $val->Manufacturing_unit!=''?$val->Manufacturing_unit:''}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">BU</label>
                                <select name="BU" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all" {{isset($BUs) && $BUs === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach($BU as $val)
                                    <option value="{{ isset($val->id) && $val->id!=''?$val->id:'' }}" {{isset($BUs) && $BUs==$val->id?'selected':''}}>{{ isset($val->BU) && $val->BU!=''?$val->BU:''}}</option>
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
                                    <option value="{{ isset($val->id) && $val->id!=''?$val->id:'' }}" {{isset($PlantNames) && $PlantNames==$val->id?'selected':''}}>{{ isset($val->plant_name) && $val->plant_name!=''?$val->plant_name:''}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">Product</label>
                                <select name="Product" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all" {{isset($Products) && $Products === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach($Product as $val)
                                    <option value="{{ isset($val->id) && $val->id!=''?$val->id:'' }}" {{isset($Products) && $Products==$val->id?'selected':''}}>{{ isset($val->product) && $val->product!=''?$val->product:''}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">Sub Product</label>
                                <select name="Sub_Product" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all" {{isset($SubProducts) && $SubProducts === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach($Sub_Product as $val)
                                    <option value="{{ isset($val->id) && $val->id!=''?$val->id:'' }}" {{isset($SubProducts) && $SubProducts==$val->id?'selected':''}}>{{ isset($val->sub_product) && $val->sub_product!=''?$val->sub_product:''}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">Sub Sub Product</label>
                                <select name="Sub_Sub_Product" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all" {{isset($SubSubProducts) && $SubSubProducts === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach($Sub_Sub_Product as $val)
                                    <option value="{{ isset($val->id) && $val->id!=''?$val->id:'' }}" {{isset($SubSubProducts) && $SubSubProducts==$val->id?'selected':''}}>{{ isset($val->sub_sub_product) && $val->sub_sub_product!=''?$val->sub_sub_product:''}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">Company Name</label>
                                <select name="Company_Name" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all" {{isset($CompanyNames) && $CompanyNames === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach($DropdownData as $val)
                                    <?php
                                    $CompanyName = isset($val->Company_Name) && $val->Company_Name != '' ? $val->Company_Name : '';
                                    if (!empty($CompanyName) && !in_array($CompanyName, $RepeatData)) {
                                        $RepeatData[] = $CompanyName;
                                    ?>
                                        <option value="{{ $CompanyName }}" {{isset($CompanyNames) && $CompanyNames==$CompanyName?'selected':''}}>{{ $CompanyName }}</option>
                                    <?php } ?>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">Color</label>
                                <select name="Color" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all" {{isset($Colors) && $Colors === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach($DropdownData as $val)
                                    <?php
                                    $Color = isset($val->Colour) && $val->Colour != '' ? $val->Colour : '';
                                    if (!empty($Color) && !in_array($Color, $RepeatData)) {
                                        $RepeatData[] = $Color;
                                    ?>
                                        <option value="{{ $Color }}" {{isset($Colors) && $Colors==$Color?'selected':''}}>{{ $Color }}</option>
                                    <?php } ?>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">Size</label>
                                <select name="Size" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all" {{isset($Sizes) && $Sizes === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach($DropdownData as $val)
                                    <?php
                                    $Size = isset($val->Size) && $val->Size != '' ? $val->Size : '';
                                    if (!empty($Size) && !in_array($Size, $RepeatData)) {
                                        $RepeatData[] = $Size;
                                    ?>
                                        <option value="{{ $Size }}" {{isset($Sizes) && $Sizes==$Size?'selected':''}}>{{ $Size }}</option>
                                    <?php } ?>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">Category</label>
                                <select name="Category" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all" {{isset($Categorys) && $Categorys === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach($category as $val)
                                    <option value="{{ isset($val->id) && $val->id!=''?$val->id:'' }}" {{isset($Categorys) && $Categorys==$val->id?'selected':''}}>{{ isset($val->category) && $val->category!=''?$val->category:''}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">Lable</label>
                                <select name="Lable" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all" {{isset($Lables) && $Lables === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach($DropdownData as $val)
                                    <?php
                                    $Lable = isset($val->Lable) && $val->Lable != '' ? $val->Lable : '';
                                    if (!empty($Lable) && !in_array($Lable, $RepeatData)) {
                                        $RepeatData[] = $Lable;
                                    ?>
                                        <option value="{{ $Lable }}" {{isset($Lables) && $Lables==$Lable?'selected':''}}>{{ $Lable }}</option>
                                    <?php } ?>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">Raw Material(FG)</label>
                                <select name="Raw_Material" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all" {{isset($RawMaterialss) && $RawMaterialss === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach($RawMaterial as $val)
                                    <option value="{{ isset($val->id) && $val->id!=''?$val->id:'' }}" {{isset($RawMaterialss) && $RawMaterialss==$val->id?'selected':''}}>{{ isset($val->matname) && $val->matname!=''?$val->matname:''}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">HSN Code</label>
                                <select name="HSN_Code" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all" {{isset($HSNCodes) && $HSNCodes === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach($DropdownData as $val)
                                    <?php
                                    $HSNCode = isset($val->HSN_Code) && $val->HSN_Code != '' ? $val->HSN_Code : '';
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
                                    @foreach($RawMaterial as $val)
                                    <?php
                                    $UOMCode = isset($val->UOM) && $val->UOM != '' ? $val->UOM : '';
                                    if (!empty($UOMCode) && !in_array($UOMCode, $RepeatData)) {
                                        $RepeatData[] = $UOMCode;
                                    ?>
                                        <option value="{{ $UOMCode }}" {{isset($UOMss) && $UOMss==$UOMCode?'selected':''}}>{{ $UOMCode }}</option>
                                    <?php } ?>
                                    @endforeach
                                </select>
                            </div>
                            {{-- <div class="col-2 mb-3">
                                <label for="" class="form-label">UOM</label>
                                <select name="UOM" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all" {{isset($UOMss) && $UOMss === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach($UOM as $val)
                                    <option value="{{ isset($val->id) && $val->id!=''?$val->id:'' }}" {{isset($UOMss) && $UOMss==$val->id?'selected':''}}>{{ isset($val->UOMs) && $val->UOMs!=''?$val->UOMs:''}}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="col-2 mt-4">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                <a href="{{url('ProductCategories/ProductList')}}"><button type="button" class="btn btn-secondary"><i class="fa fa-refresh"></i></button></a>
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
                                        {{-- <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Organization_Name" value="Organization Name" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Organization_Name">Organization Name</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Manufacturing_Unit" value="Manufacturing Unit" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Manufacturing_Unit">Manufacturing Unit</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="BU" value="BU" onclick="filterTable(this)">
                                            <label class="form-check-label" for="BU">BU</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Plant_Name" value="Plant Name" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Plant_Name">Plant Name</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Product" value="Product" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Product">Product</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Sub_Product" value="Sub Product" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Sub_Product">Sub Product</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Sub_Sub_Product" value="Sub Sub Product" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Sub_Sub_Product">Sub Sub Product</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Company_Name" value="Company Name" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Company_Name">Company Name</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Color" value="Color" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Color">Color</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Size" value="Size" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Size">Size</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Category" value="Category" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Category">Category</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Lable" value="Lable" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Lable">Lable</label>
                                        </div> --}}
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Raw_Material" value="Finished Good(FG)" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Raw_Material">Finished Good(FG)</label>
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
                            <a class="nav-link count active" id="Alls" data-mdb-toggle="tab" href="#All" role="tab" aria-controls="All" aria-selected="true">All <span class="countss">{{count($ProductList)}}</span></a>
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
                                            {{-- <th class="th-sm">Organization Name</th>
                                            <th class="th-sm">Manufacturing Unit</th>
                                            <th class="th-sm">BU</th>
                                            <th class="th-sm">Plant Name</th>
                                            <th class="th-sm">Product</th>
                                            <th class="th-sm">Sub Product</th>
                                            <th class="th-sm">Sub Sub Product</th>
                                            <th class="th-sm">Company Name</th>
                                            <th class="th-sm">Color</th>
                                            <th class="th-sm">Size</th>
                                            <th class="th-sm">Category</th>
                                            <th class="th-sm">Lable</th> --}}
                                            <th class="th-sm">Finished Good(FG)</th>
                                            <th class="th-sm">HSN Code</th>
                                            <th class="th-sm">UOM</th>
                                            <th class="th-sm">Status</th>
                                            <th class="th-sm">Pending With</th>
                                            <th class="th-sm">Operation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $sesionarr=[];
                                        @endphp
                                        @foreach($ProductList as $key => $val)
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
                                            {{-- <td>{{isset($val->Organization_Name->organization) && $val->Organization_Name->organization!=''?$val->Organization_Name->organization:''}}</td>
                                            <td>{{isset($val->Manufacturing_Unit->Manufacturing_unit) && $val->Manufacturing_Unit->Manufacturing_unit!=''?$val->Manufacturing_Unit->Manufacturing_unit:''}}</td>
                                            <td>{{isset($val->BU->BU) && $val->BU->BU!=''?$val->BU->BU:''}}</td>
                                            <td>{{isset($val->Plant_Name->plant_name) && $val->Plant_Name->plant_name!=''?$val->Plant_Name->plant_name:''}}</td>
                                            <td>{{isset($val->product->product) && $val->product->product!=''?$val->product->product:''}}</td>
                                            <td>{{isset($val->subproduct->sub_product) && $val->subproduct->sub_product!=''?$val->subproduct->sub_product:''}}</td>
                                            <td>{{isset($val->subsubproduct->sub_sub_product) && $val->subsubproduct->sub_sub_product!=''?$val->subsubproduct->sub_sub_product:''}}</td>
                                            <td>{{isset($val->Company_Name) && $val->Company_Name!=''?$val->Company_Name:''}}</td>
                                            <td>{{isset($val->Colour) && $val->Colour!=''?$val->Colour:''}}</td>
                                            <td>{{isset($val->Size) && $val->Size!=''?$val->Size:''}}</td>
                                            <td>{{isset($val->category->category) && $val->category->category!=''?$val->category->category:''}}</td>
                                            <td>{{isset($val->Lable) && $val->Lable!=''?$val->Lable:''}}</td> --}}
                                            <td>{{isset($val->Raw_Material->matname) && $val->Raw_Material->matname!=''?$val->Raw_Material->matname:''}}</td>
                                            <td>{{isset($val->HSN_Code) && $val->HSN_Code!=''?$val->HSN_Code:''}}</td>
                                            <td>{{isset($val->Raw_Material->UOM) && $val->Raw_Material->UOM!=''?$val->Raw_Material->UOM:''}}</td>
                                            {{-- <td>{{isset($val->UOM->UOMs) && $val->UOM->UOMs!=''?$val->UOM->UOMs:''}}</td> --}}
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
                                                <a href="{{url('ProductCategories/ProductCategory_View/'.$val->id.'/ALL')}}" class="btn btn-primary">View</a>
                                                @if($val->Approve_status == 'RECHECK' && isset($EXT[5]['inputer']))
                                                <a href="{{url('ProductCategories/AddProduct/'.$val->id)}}" class="btn btn-secondary">Edit</a>
                                                @elseif($val->HoldStatus > 0)
                                                <a href="{{url('ProductCategories/Release_Hold/'.$val->id)}}" class="btn btn-secondary">Release</a>
                                                @endif
                                                @else
                                                <a href="{{url('ProductCategories/AddProduct/'.$val->id)}}" class="btn btn-warning">Draft</a>
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
                                            {{-- <th class="th-sm">Organization Name</th>
                                            <th class="th-sm">Manufacturing Unit</th>
                                            <th class="th-sm">BU</th>
                                            <th class="th-sm">Plant Name</th>
                                            <th class="th-sm">Product</th>
                                            <th class="th-sm">Sub Product</th>
                                            <th class="th-sm">Sub Sub Product</th>
                                            <th class="th-sm">Company Name</th>
                                            <th class="th-sm">Color</th>
                                            <th class="th-sm">Size</th>
                                            <th class="th-sm">Category</th>
                                            <th class="th-sm">Lable</th> --}}
                                            <th class="th-sm">Raw Material(FG)</th>
                                            <th class="th-sm">HSN Code</th>
                                            <th class="th-sm">UOM</th>
                                            <th class="th-sm">Status</th>
                                            <th class="th-sm">Pending With</th>
                                            <th class="th-sm">Operation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($approved as $key => $val)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{isset($val->user->fullname) && $val->user->fullname!=''?$val->user->fullname:''}}</td>
                                            <td>{{isset($val->created_at) && $val->created_at!=''?date('d-m-Y H:i:s A',strtotime($val->created_at)):''}}</td>
                                            {{-- <td>{{isset($val->Organization_Name->organization) && $val->Organization_Name->organization!=''?$val->Organization_Name->organization:''}}</td>
                                            <td>{{isset($val->Manufacturing_Unit->Manufacturing_unit) && $val->Manufacturing_Unit->Manufacturing_unit!=''?$val->Manufacturing_Unit->Manufacturing_unit:''}}</td>
                                            <td>{{isset($val->BU->BU) && $val->BU->BU!=''?$val->BU->BU:''}}</td>
                                            <td>{{isset($val->Plant_Name->plant_name) && $val->Plant_Name->plant_name!=''?$val->Plant_Name->plant_name:''}}</td>
                                            <td>{{isset($val->product->product) && $val->product->product!=''?$val->product->product:''}}</td>
                                            <td>{{isset($val->subproduct->sub_product) && $val->subproduct->sub_product!=''?$val->subproduct->sub_product:''}}</td>
                                            <td>{{isset($val->subsubproduct->sub_sub_product) && $val->subsubproduct->sub_sub_product!=''?$val->subsubproduct->sub_sub_product:''}}</td>
                                            <td>{{isset($val->Company_Name) && $val->Company_Name!=''?$val->Company_Name:''}}</td>
                                            <td>{{isset($val->Colour) && $val->Colour!=''?$val->Colour:''}}</td>
                                            <td>{{isset($val->Size) && $val->Size!=''?$val->Size:''}}</td>
                                            <td>{{isset($val->category->category) && $val->category->category!=''?$val->category->category:''}}</td>
                                            <td>{{isset($val->Lable) && $val->Lable!=''?$val->Lable:''}}</td> --}}
                                            <td>{{isset($val->Raw_Material->matname) && $val->Raw_Material->matname!=''?$val->Raw_Material->matname:''}}</td>
                                            <td>{{isset($val->HSN_Code) && $val->HSN_Code!=''?$val->HSN_Code:''}}</td>
                                            <td>{{isset($val->Raw_Material->UOM) && $val->Raw_Material->UOM!=''?$val->Raw_Material->UOM:''}}</td>
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
                                                @if($val->Approve_status==='FORWARD' || ($val->Approve_status=='' && isset($val->status) && $val->status!=1))
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
                                                <a href="{{url('ProductCategories/ProductCategory_View/'.$val->id.'/APPROVE')}}" class="btn btn-primary">View</a>
                                                @if($val->Approve_status == 'RECHECK' && isset($EXT[5]['inputer']))
                                                <a href="{{url('ProductCategories/AddProduct/'.$val->id)}}" class="btn btn-secondary">Edit</a>
                                                @elseif($val->HoldStatus > 0)
                                                <a href="{{url('ProductCategories/Release_Hold/'.$val->id)}}" class="btn btn-secondary">Release</a>
                                                @endif
                                                @else
                                                <a href="{{url('ProductCategories/AddProduct/'.$val->id)}}" class="btn btn-warning">Draft</a>
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
                                            {{-- <th class="th-sm">Organization Name</th>
                                            <th class="th-sm">Manufacturing Unit</th>
                                            <th class="th-sm">BU</th>
                                            <th class="th-sm">Plant Name</th>
                                            <th class="th-sm">Product</th>
                                            <th class="th-sm">Sub Product</th>
                                            <th class="th-sm">Sub Sub Product</th>
                                            <th class="th-sm">Company Name</th>
                                            <th class="th-sm">Color</th>
                                            <th class="th-sm">Size</th>
                                            <th class="th-sm">Category</th>
                                            <th class="th-sm">Lable</th> --}}
                                            <th class="th-sm">Raw Material(FG)</th>
                                            <th class="th-sm">HSN Code</th>
                                            <th class="th-sm">UOM</th>
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
                                            {{-- <td>{{isset($val->Organization_Name->organization) && $val->Organization_Name->organization!=''?$val->Organization_Name->organization:''}}</td>
                                            <td>{{isset($val->Manufacturing_Unit->Manufacturing_unit) && $val->Manufacturing_Unit->Manufacturing_unit!=''?$val->Manufacturing_Unit->Manufacturing_unit:''}}</td>
                                            <td>{{isset($val->BU->BU) && $val->BU->BU!=''?$val->BU->BU:''}}</td>
                                            <td>{{isset($val->Plant_Name->plant_name) && $val->Plant_Name->plant_name!=''?$val->Plant_Name->plant_name:''}}</td>
                                            <td>{{isset($val->product->product) && $val->product->product!=''?$val->product->product:''}}</td>
                                            <td>{{isset($val->subproduct->sub_product) && $val->subproduct->sub_product!=''?$val->subproduct->sub_product:''}}</td>
                                            <td>{{isset($val->subsubproduct->sub_sub_product) && $val->subsubproduct->sub_sub_product!=''?$val->subsubproduct->sub_sub_product:''}}</td>
                                            <td>{{isset($val->Company_Name) && $val->Company_Name!=''?$val->Company_Name:''}}</td>
                                            <td>{{isset($val->Colour) && $val->Colour!=''?$val->Colour:''}}</td>
                                            <td>{{isset($val->Size) && $val->Size!=''?$val->Size:''}}</td>
                                            <td>{{isset($val->category->category) && $val->category->category!=''?$val->category->category:''}}</td>
                                            <td>{{isset($val->Lable) && $val->Lable!=''?$val->Lable:''}}</td> --}}
                                            <td>{{isset($val->Raw_Material->matname) && $val->Raw_Material->matname!=''?$val->Raw_Material->matname:''}}</td>
                                            <td>{{isset($val->HSN_Code) && $val->HSN_Code!=''?$val->HSN_Code:''}}</td>
                                            <td>{{isset($val->Raw_Material->UOM) && $val->Raw_Material->UOM!=''?$val->Raw_Material->UOM:''}}</td>
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
                                                @if($val->Approve_status==='FORWARD' || ($val->Approve_status=='' && isset($val->status) && $val->status!=1))
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
                                                <a href="{{url('ProductCategories/ProductCategory_View/'.$val->id.'/PENDING')}}" class="btn btn-primary">View</a>
                                                @if($val->Approve_status == 'RECHECK' && isset($EXT[5]['inputer']))
                                                <a href="{{url('ProductCategories/AddProduct/'.$val->id)}}" class="btn btn-secondary">Edit</a>
                                                @elseif($val->HoldStatus > 0)
                                                <a href="{{url('ProductCategories/Release_Hold/'.$val->id)}}" class="btn btn-secondary">Release</a>
                                                @endif
                                                @else
                                                <a href="{{url('ProductCategories/AddProduct/'.$val->id)}}" class="btn btn-warning">Draft</a>
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
                                            {{-- <th class="th-sm">Organization Name</th>
                                            <th class="th-sm">Manufacturing Unit</th>
                                            <th class="th-sm">BU</th>
                                            <th class="th-sm">Plant Name</th>
                                            <th class="th-sm">Product</th>
                                            <th class="th-sm">Sub Product</th>
                                            <th class="th-sm">Sub Sub Product</th>
                                            <th class="th-sm">Company Name</th>
                                            <th class="th-sm">Color</th>
                                            <th class="th-sm">Size</th>
                                            <th class="th-sm">Category</th>
                                            <th class="th-sm">Lable</th> --}}
                                            <th class="th-sm">Raw Material(FG)</th>
                                            <th class="th-sm">HSN Code</th>
                                            <th class="th-sm">UOM</th>
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
                                            {{-- <td>{{isset($val->Organization_Name->organization) && $val->Organization_Name->organization!=''?$val->Organization_Name->organization:''}}</td>
                                            <td>{{isset($val->Manufacturing_Unit->Manufacturing_unit) && $val->Manufacturing_Unit->Manufacturing_unit!=''?$val->Manufacturing_Unit->Manufacturing_unit:''}}</td>
                                            <td>{{isset($val->BU->BU) && $val->BU->BU!=''?$val->BU->BU:''}}</td>
                                            <td>{{isset($val->Plant_Name->plant_name) && $val->Plant_Name->plant_name!=''?$val->Plant_Name->plant_name:''}}</td>
                                            <td>{{isset($val->product->product) && $val->product->product!=''?$val->product->product:''}}</td>
                                            <td>{{isset($val->subproduct->sub_product) && $val->subproduct->sub_product!=''?$val->subproduct->sub_product:''}}</td>
                                            <td>{{isset($val->subsubproduct->sub_sub_product) && $val->subsubproduct->sub_sub_product!=''?$val->subsubproduct->sub_sub_product:''}}</td>
                                            <td>{{isset($val->Company_Name) && $val->Company_Name!=''?$val->Company_Name:''}}</td>
                                            <td>{{isset($val->Colour) && $val->Colour!=''?$val->Colour:''}}</td>
                                            <td>{{isset($val->Size) && $val->Size!=''?$val->Size:''}}</td>
                                            <td>{{isset($val->category->category) && $val->category->category!=''?$val->category->category:''}}</td>
                                            <td>{{isset($val->Lable) && $val->Lable!=''?$val->Lable:''}}</td> --}}
                                            <td>{{isset($val->Raw_Material->matname) && $val->Raw_Material->matname!=''?$val->Raw_Material->matname:''}}</td>
                                            <td>{{isset($val->HSN_Code) && $val->HSN_Code!=''?$val->HSN_Code:''}}</td>
                                            <td>{{isset($val->Raw_Material->UOM) && $val->Raw_Material->UOM!=''?$val->Raw_Material->UOM:''}}</td>
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
                                                @if($val->Approve_status==='FORWARD' || ($val->Approve_status=='' && isset($val->status) && $val->status!=1))
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
                                                <a href="{{url('ProductCategories/ProductCategory_View/'.$val->id.'/HOLD')}}" class="btn btn-primary">View</a>
                                                @if($val->Approve_status == 'RECHECK' && isset($EXT[5]['inputer']))
                                                <a href="{{url('ProductCategories/AddProduct/'.$val->id)}}" class="btn btn-secondary">Edit</a>
                                                @elseif($val->HoldStatus > 0)
                                                <a href="{{url('ProductCategories/Release_Hold/'.$val->id)}}" class="btn btn-secondary">Release</a>
                                                @endif
                                                @else
                                                <a href="{{url('ProductCategories/AddProduct/'.$val->id)}}" class="btn btn-warning">Draft</a>
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
                                            {{-- <th class="th-sm">Organization Name</th>
                                            <th class="th-sm">Manufacturing Unit</th>
                                            <th class="th-sm">BU</th>
                                            <th class="th-sm">Plant Name</th>
                                            <th class="th-sm">Product</th>
                                            <th class="th-sm">Sub Product</th>
                                            <th class="th-sm">Sub Sub Product</th>
                                            <th class="th-sm">Company Name</th>
                                            <th class="th-sm">Color</th>
                                            <th class="th-sm">Size</th>
                                            <th class="th-sm">Category</th>
                                            <th class="th-sm">Lable</th> --}}
                                            <th class="th-sm">Raw Material(FG)</th>
                                            <th class="th-sm">HSN Code</th>
                                            <th class="th-sm">UOM</th>
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
                                            {{-- <td>{{isset($val->Organization_Name->organization) && $val->Organization_Name->organization!=''?$val->Organization_Name->organization:''}}</td>
                                            <td>{{isset($val->Manufacturing_Unit->Manufacturing_unit) && $val->Manufacturing_Unit->Manufacturing_unit!=''?$val->Manufacturing_Unit->Manufacturing_unit:''}}</td>
                                            <td>{{isset($val->BU->BU) && $val->BU->BU!=''?$val->BU->BU:''}}</td>
                                            <td>{{isset($val->Plant_Name->plant_name) && $val->Plant_Name->plant_name!=''?$val->Plant_Name->plant_name:''}}</td>
                                            <td>{{isset($val->product->product) && $val->product->product!=''?$val->product->product:''}}</td>
                                            <td>{{isset($val->subproduct->sub_product) && $val->subproduct->sub_product!=''?$val->subproduct->sub_product:''}}</td>
                                            <td>{{isset($val->subsubproduct->sub_sub_product) && $val->subsubproduct->sub_sub_product!=''?$val->subsubproduct->sub_sub_product:''}}</td>
                                            <td>{{isset($val->Company_Name) && $val->Company_Name!=''?$val->Company_Name:''}}</td>
                                            <td>{{isset($val->Colour) && $val->Colour!=''?$val->Colour:''}}</td>
                                            <td>{{isset($val->Size) && $val->Size!=''?$val->Size:''}}</td>
                                            <td>{{isset($val->category->category) && $val->category->category!=''?$val->category->category:''}}</td>
                                            <td>{{isset($val->Lable) && $val->Lable!=''?$val->Lable:''}}</td> --}}
                                            <td>{{isset($val->Raw_Material->matname) && $val->Raw_Material->matname!=''?$val->Raw_Material->matname:''}}</td>
                                            <td>{{isset($val->HSN_Code) && $val->HSN_Code!=''?$val->HSN_Code:''}}</td>
                                            <td>{{isset($val->Raw_Material->UOM) && $val->Raw_Material->UOM!=''?$val->Raw_Material->UOM:''}}</td>
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
                                                @if($val->Approve_status==='FORWARD' || ($val->Approve_status=='' && isset($val->status) && $val->status!=1))
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
                                                <a href="{{url('ProductCategories/ProductCategory_View/'.$val->id.'/RECHECK')}}" class="btn btn-primary">View</a>
                                                @if($val->Approve_status == 'RECHECK' && isset($EXT[5]['inputer']))
                                                <a href="{{url('ProductCategories/AddProduct/'.$val->id)}}" class="btn btn-secondary">Edit</a>
                                                @elseif($val->HoldStatus > 0)
                                                <a href="{{url('ProductCategories/Release_Hold/'.$val->id)}}" class="btn btn-secondary">Release</a>
                                                @endif
                                                @else
                                                <a href="{{url('ProductCategories/AddProduct/'.$val->id)}}" class="btn btn-warning">Draft</a>
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
                                            {{-- <th class="th-sm">Organization Name</th>
                                            <th class="th-sm">Manufacturing Unit</th>
                                            <th class="th-sm">BU</th>
                                            <th class="th-sm">Plant Name</th>
                                            <th class="th-sm">Product</th>
                                            <th class="th-sm">Sub Product</th>
                                            <th class="th-sm">Sub Sub Product</th>
                                            <th class="th-sm">Company Name</th>
                                            <th class="th-sm">Color</th>
                                            <th class="th-sm">Size</th>
                                            <th class="th-sm">Category</th>
                                            <th class="th-sm">Lable</th> --}}
                                            <th class="th-sm">Raw Material(FG)</th>
                                            <th class="th-sm">HSN Code</th>
                                            <th class="th-sm">UOM</th>
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
                                            {{-- <td>{{isset($val->Organization_Name->organization) && $val->Organization_Name->organization!=''?$val->Organization_Name->organization:''}}</td>
                                            <td>{{isset($val->Manufacturing_Unit->Manufacturing_unit) && $val->Manufacturing_Unit->Manufacturing_unit!=''?$val->Manufacturing_Unit->Manufacturing_unit:''}}</td>
                                            <td>{{isset($val->BU->BU) && $val->BU->BU!=''?$val->BU->BU:''}}</td>
                                            <td>{{isset($val->Plant_Name->plant_name) && $val->Plant_Name->plant_name!=''?$val->Plant_Name->plant_name:''}}</td>
                                            <td>{{isset($val->product->product) && $val->product->product!=''?$val->product->product:''}}</td>
                                            <td>{{isset($val->subproduct->sub_product) && $val->subproduct->sub_product!=''?$val->subproduct->sub_product:''}}</td>
                                            <td>{{isset($val->subsubproduct->sub_sub_product) && $val->subsubproduct->sub_sub_product!=''?$val->subsubproduct->sub_sub_product:''}}</td>
                                            <td>{{isset($val->Company_Name) && $val->Company_Name!=''?$val->Company_Name:''}}</td>
                                            <td>{{isset($val->Colour) && $val->Colour!=''?$val->Colour:''}}</td>
                                            <td>{{isset($val->Size) && $val->Size!=''?$val->Size:''}}</td>
                                            <td>{{isset($val->category->category) && $val->category->category!=''?$val->category->category:''}}</td>
                                            <td>{{isset($val->Lable) && $val->Lable!=''?$val->Lable:''}}</td> --}}
                                            <td>{{isset($val->Raw_Material->matname) && $val->Raw_Material->matname!=''?$val->Raw_Material->matname:''}}</td>
                                            <td>{{isset($val->HSN_Code) && $val->HSN_Code!=''?$val->HSN_Code:''}}</td>
                                            <td>{{isset($val->Raw_Material->UOM) && $val->Raw_Material->UOM!=''?$val->Raw_Material->UOM:''}}</td>
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
                                                @if($val->Approve_status==='FORWARD' || ($val->Approve_status=='' && isset($val->status) && $val->status!=1))
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
                                                <a href="{{url('ProductCategories/ProductCategory_View/'.$val->id.'/OBJECT')}}" class="btn btn-primary">View</a>
                                                @if($val->Approve_status == 'RECHECK' && isset($EXT[5]['inputer']))
                                                <a href="{{url('ProductCategories/AddProduct/'.$val->id)}}" class="btn btn-secondary">Edit</a>
                                                @elseif($val->HoldStatus > 0)
                                                <a href="{{url('ProductCategories/Release_Hold/'.$val->id)}}" class="btn btn-secondary">Release</a>
                                                @endif
                                                @else
                                                <a href="{{url('ProductCategories/AddProduct/'.$val->id)}}" class="btn btn-warning">Draft</a>
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
                                            {{-- <th class="th-sm">Organization Name</th>
                                            <th class="th-sm">Manufacturing Unit</th>
                                            <th class="th-sm">BU</th>
                                            <th class="th-sm">Plant Name</th>
                                            <th class="th-sm">Product</th>
                                            <th class="th-sm">Sub Product</th>
                                            <th class="th-sm">Sub Sub Product</th>
                                            <th class="th-sm">Company Name</th>
                                            <th class="th-sm">Color</th>
                                            <th class="th-sm">Size</th>
                                            <th class="th-sm">Category</th>
                                            <th class="th-sm">Lable</th> --}}
                                            <th class="th-sm">Raw Material(FG)</th>
                                            <th class="th-sm">HSN Code</th>
                                            <th class="th-sm">UOM</th>
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
                                            {{-- <td>{{isset($val->Organization_Name->organization) && $val->Organization_Name->organization!=''?$val->Organization_Name->organization:''}}</td>
                                            <td>{{isset($val->Manufacturing_Unit->Manufacturing_unit) && $val->Manufacturing_Unit->Manufacturing_unit!=''?$val->Manufacturing_Unit->Manufacturing_unit:''}}</td>
                                            <td>{{isset($val->BU->BU) && $val->BU->BU!=''?$val->BU->BU:''}}</td>
                                            <td>{{isset($val->Plant_Name->plant_name) && $val->Plant_Name->plant_name!=''?$val->Plant_Name->plant_name:''}}</td>
                                            <td>{{isset($val->product->product) && $val->product->product!=''?$val->product->product:''}}</td>
                                            <td>{{isset($val->subproduct->sub_product) && $val->subproduct->sub_product!=''?$val->subproduct->sub_product:''}}</td>
                                            <td>{{isset($val->subsubproduct->sub_sub_product) && $val->subsubproduct->sub_sub_product!=''?$val->subsubproduct->sub_sub_product:''}}</td>
                                            <td>{{isset($val->Company_Name) && $val->Company_Name!=''?$val->Company_Name:''}}</td>
                                            <td>{{isset($val->Colour) && $val->Colour!=''?$val->Colour:''}}</td>
                                            <td>{{isset($val->Size) && $val->Size!=''?$val->Size:''}}</td>
                                            <td>{{isset($val->category->category) && $val->category->category!=''?$val->category->category:''}}</td>
                                            <td>{{isset($val->Lable) && $val->Lable!=''?$val->Lable:''}}</td> --}}
                                            <td>{{isset($val->Raw_Material->matname) && $val->Raw_Material->matname!=''?$val->Raw_Material->matname:''}}</td>
                                            <td>{{isset($val->HSN_Code) && $val->HSN_Code!=''?$val->HSN_Code:''}}</td>
                                            <td>{{isset($val->Raw_Material->UOM) && $val->Raw_Material->UOM!=''?$val->Raw_Material->UOM:''}}</td>
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
                                                @if($val->Approve_status==='FORWARD' || ($val->Approve_status=='' && isset($val->status) && $val->status!=1))
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
                                                <a href="{{url('ProductCategories/ProductCategory_View/'.$val->id.'/REJECT')}}" class="btn btn-primary">View</a>
                                                @if($val->Approve_status == 'RECHECK' && isset($EXT[5]['inputer']))
                                                <a href="{{url('ProductCategories/AddProduct/'.$val->id)}}" class="btn btn-secondary">Edit</a>
                                                @elseif($val->HoldStatus > 0)
                                                <a href="{{url('ProductCategories/Release_Hold/'.$val->id)}}" class="btn btn-secondary">Release</a>
                                                @endif
                                                @else
                                                <a href="{{url('ProductCategories/AddProduct/'.$val->id)}}" class="btn btn-warning">Draft</a>
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
    activeclass(8, 1);
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
    var tableID = 10;

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

        fetch("{{ url('ProductCategories/getCheckBoxData') }}?ID=" + tableID, {
                method: 'GET',
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.columns) {
                    try {
                        var existingData = data.columns;
                        if (JSON.stringify(existingData) !== JSON.stringify(columnNamesToShow)) {
                            fetch("{{ url('ProductCategories/CheckBoxStore') }}", {
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

        fetch("{{ url('ProductCategories/getCheckBoxData') }}?ID=" + tableID, {
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
            url: "{{ url('ProductCategories/CheckHoldExpiry') }}",
            method: 'GET',
            success: function(response) {
                response.forEach(function(lead) {
                    if (lead.action === 'HOLD' && lead.status === 1) {
                        var currentDate = new Date();
                        var holdDate = new Date(lead.days_for_holding);

                        if (holdDate < currentDate) {
                            UpdateStatus(lead.Product_id, lead.userID);
                        }
                    }
                });
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    });

    function UpdateStatus(Product_id, userID) {
        $.ajax({
            url: "{{ url('ProductCategories/UpdateStatus') }}",
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            data: JSON.stringify({
                Product_id: Product_id,
                userID: userID
            }),
            success: function(response) {
                $('#statuss' + Product_id).html('<span style="color: #FF9000;">Pending</span>');
            }
        });
    }
</script>
@endpush