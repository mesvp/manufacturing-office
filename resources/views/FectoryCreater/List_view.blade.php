@extends('layout.main')
@section('main-container')
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">

<body id="yahubaba">

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
                    <li class="breadcrumb-item">Factory Creation View Page</li>
                </ol>
                <div class="addbtn">
                    <a href="{{url('FactoryCreater/ExportFilteredData')}}"><i class='fas fa-file-excel'></i></a>
                    @if(isset($EXT[1]['inputer']))
                    <a href="{{url('FactoryCreater/unset')}}"><button class="btn btn-info">Add Factory</button></a>
                    @endif
                </div>
                <div class="row">
                    <div class="container">
                        <form action="{{url('FactoryCreater/filtered')}}" method="POST">
                            @csrf
                            <div class="row filter">
                                <div class="input-group-sm col-2 mb-3">
                                    <label for="" class="form-label">Date From</label>
                                    <input type="date" name="from_date" value="{{isset($fromdate) && $fromdate!=''?$fromdate:''}}" class="form-control form-control-sm">
                                </div>
                                <div class="input-group-sm col-2 mb-3">
                                    <label for="" class="form-label">Date To</label>
                                    <input type="date" name="to_date" value="{{isset($todate) && $todate!=''?$todate:''}}" class="form-control form-control-sm">
                                </div>
                                <div class="col-2 mb-3">
                                    <label for="" class="form-label">Organization</label>
                                    <select name="Organization" class="form-select form-select-sm js-example-matcher-start">
                                        <option value="" disabled selected>Select</option>
                                        <option value="all" {{isset($Organizations) && $Organizations === 'all' ? 'selected' : '' }}>All</option>
                                        <?php $RepeatData = []; ?>
                                        @foreach($OrganizationData as $val)
                                        <option value="{{ isset($val->id) && $val->id!=''?$val->id:'' }}" {{isset($Organizations) && $Organizations==$val->id?'selected':''}}>{{ isset($val->organisation) && $val->organisation!=''?$val->organisation:''}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2 mb-3">
                                    <label for="" class="form-label">Name Of The Unit</label>
                                    <select name="name_of_unit" class="form-select form-select-sm js-example-matcher-start">
                                        <option value="" disabled selected>Select</option>
                                        <option value="all" {{isset($nameOFUnit) && $nameOFUnit === 'all' ? 'selected' : '' }}>All</option>
                                        <?php $RepeatData = []; ?>
                                        @foreach($nameOfUnitData as $val)
                                        <option value="{{ isset($val->id) && $val->id!=''?$val->id:'' }}" {{isset($nameOFUnit) && $nameOFUnit==$val->id?'selected':''}}>{{ isset($val->pname) && $val->pname!=''?$val->pname:''}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2 mb-3">
                                    <label for="" class="form-label">District/State</label>
                                    <select name="District_State" class="form-select form-select-sm js-example-matcher-start">
                                        <option value="" disabled selected>Select</option>
                                        <option value="all" {{isset($district) && $district === 'all' ? 'selected' : '' }}>All</option>
                                        <?php $RepeatData = []; ?>
                                        @foreach($cityData as $val)
                                        <option value="{{ isset($val->id) && $val->id!=''?$val->id:'' }}" {{isset($district) && $district==$val->id?'selected':''}}>{{ isset($val->distname) && $val->distname!=''?$val->distname:'' }}/{{isset($val->stateData->sname) && $val->stateData->sname!=''?$val->stateData->sname:''}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2 mb-3">
                                    <label for="" class="form-label">Total Plant</label>
                                    <select name="Total_Plant" class="form-select form-select-sm js-example-matcher-start">
                                        <option value="" disabled selected>Select</option>
                                        <option value="all" {{isset($plantsCount) && $plantsCount === 'all' ? 'selected' : '' }}>All</option>
                                        <?php $RepeatData = []; ?>
                                        @foreach($DropdownData as $val)
                                        <?php
                                        $TotalPlant = $val->plantsCount;
                                        if (!empty($TotalPlant) && !in_array($TotalPlant, $RepeatData)) {
                                            $RepeatData[] = $TotalPlant;
                                        ?>
                                            <option value="{{ $TotalPlant }}" {{isset($plantsCount) && $plantsCount==$TotalPlant?'selected':''}}>{{ $TotalPlant }}</option>
                                        <?php } ?>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2 mb-3">
                                    <label for="" class="form-label">GST No.</label>
                                    <select name="GST_No" class="form-select form-select-sm js-example-matcher-start">
                                        <option value="" disabled selected>Select</option>
                                        <option value="all" {{isset($selectedGST) && $selectedGST === 'all' ? 'selected' : '' }}>All</option>
                                        <?php $RepeatData = []; ?>
                                        @foreach($gstData as $val)
                                        <option value="{{ isset($val->id) && $val->id!=''?$val->id:'' }}" {{isset($selectedGST) && $selectedGST==$val->id?'selected':''}}>{{ isset($val->gst_no) && $val->gst_no!=''?$val->gst_no:'' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2 mb-3">
                                    <label for="" class="form-label">Factory License</label>
                                    <select name="Factory_License" class="form-select form-select-sm js-example-matcher-start">
                                        <option value="" disabled selected>Select</option>
                                        <option value="all" {{isset($FactoryLicenses) && $FactoryLicenses === 'all' ? 'selected' : '' }}>All</option>
                                        <?php $RepeatData = []; ?>
                                        @foreach($factoryLicenseData as $val)
                                        <option value="{{ isset($val->id) && $val->id!=''?$val->id:'' }}" {{isset($FactoryLicenses) && $FactoryLicenses==$val->id?'selected':''}}>{{ isset($val->factory_license_no) && $val->factory_license_no!=''?$val->factory_license_no:'' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2 mb-3">
                                    <label for="" class="form-label">Land Type</label>
                                    <select name="Land_Type" class="form-select form-select-sm js-example-matcher-start">
                                        <option value="" disabled selected>Select</option>
                                        <option value="all" {{isset($LandTypes) && $LandTypes === 'all' ? 'selected' : '' }}>All</option>
                                        <?php $RepeatData = []; ?>
                                        @foreach($landTypeData as $val)
                                        <option value="{{ isset($val->id) && $val->id!=''?$val->id:'' }}" {{isset($LandTypes) && $LandTypes==$val->id?'selected':''}}>{{ isset($val->land_type) && $val->land_type!=''?$val->land_type:'' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2 mb-3">
                                    <label for="" class="form-label">Total Godown</label>
                                    <select name="Total_Godown" class="form-select form-select-sm js-example-matcher-start">
                                        <option value="" disabled selected>Select</option>
                                        <option value="all" {{isset($TotalGodowns) && $TotalGodowns === 'all' ? 'selected' : '' }}>All</option>
                                        <?php $RepeatData = []; ?>
                                        @foreach($DropdownData as $val)
                                        <?php
                                        $Godown = isset($val->WareHouseRoom->Total_Warehouse) && $val->WareHouseRoom->Total_Warehouse != '' ? $val->WareHouseRoom->Total_Warehouse : '';
                                        if (!empty($Godown) && !in_array($Godown, $RepeatData)) {
                                            $RepeatData[] = $Godown;
                                        ?>
                                            <option value="{{ $Godown }}" {{isset($TotalGodowns) && $TotalGodowns==$Godown?'selected':''}}>{{ $Godown }}</option>
                                        <?php } ?>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2 mb-3">
                                    <label for="" class="form-label">Total Rack</label>
                                    <select name="Total_Rack" class="form-select form-select-sm js-example-matcher-start">
                                        <option value="" disabled selected>Select</option>
                                        <option value="all" {{isset($TotalRacks) && $TotalRacks === 'all' ? 'selected' : '' }}>All</option>
                                        <?php $RepeatData = []; ?>
                                        @foreach($DropdownData as $val)
                                        <?php
                                        $totalRack = isset($val->store->Total_Rack) && $val->store->Total_Rack != '' ? $val->store->Total_Rack : '';
                                        if (!empty($totalRack) && !in_array($totalRack, $RepeatData)) {
                                            $RepeatData[] = $totalRack;
                                        ?>
                                            <option value="{{ $totalRack }}" {{isset($TotalRacks) && $TotalRacks==$totalRack?'selected':''}}>{{ $totalRack }}</option>
                                        <?php } ?>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2 mb-3">
                                    <label for="" class="form-label">Rack Capacity</label>
                                    <select name="Rack_Capacity" class="form-select form-select-sm js-example-matcher-start">
                                        <option value="" disabled selected>Select</option>
                                        <option value="all" {{isset($RackCapacitys) && $RackCapacitys === 'all' ? 'selected' : '' }}>All</option>
                                        <?php $RepeatData = []; ?>
                                        @foreach($DropdownData as $val)
                                        <?php
                                        $RackCapacity = isset($val->store->Rack_Capacity) && $val->store->Rack_Capacity != '' ? $val->store->Rack_Capacity : '';
                                        if (!empty($RackCapacity) && !in_array($RackCapacity, $RepeatData)) {
                                            $RepeatData[] = $RackCapacity;
                                        ?>
                                            <option value="{{ $RackCapacity }}" {{isset($RackCapacitys) && $RackCapacitys==$RackCapacity?'selected':''}}>{{ $RackCapacity }}</option>
                                        <?php } ?>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2 mb-3">
                                    <label for="" class="form-label">Total Bin</label>
                                    <select name="Total_Bin" class="form-select form-select-sm js-example-matcher-start">
                                        <option value="" disabled selected>Select</option>
                                        <option value="all" {{isset($TotalBins) && $TotalBins === 'all' ? 'selected' : '' }}>All</option>
                                        <?php $RepeatData = []; ?>
                                        @foreach($DropdownData as $val)
                                        <?php
                                        $TotalBin = isset($val->store->Total_Bin) && $val->store->Total_Bin != '' ? $val->store->Total_Bin : '';
                                        if (!empty($TotalBin) && !in_array($TotalBin, $RepeatData)) {
                                            $RepeatData[] = $TotalBin;
                                        ?>
                                            <option value="{{ $TotalBin }}" {{isset($TotalBins) && $TotalBins==$TotalBin?'selected':''}}>{{ $TotalBin }}</option>
                                        <?php } ?>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2 mb-3">
                                    <label for="" class="form-label">Bin Cap.</label>
                                    <select name="Bin_Cap" class="form-select form-select-sm js-example-matcher-start">
                                        <option value="" disabled selected>Select</option>
                                        <option value="all" {{isset($BinCaps) && $BinCaps === 'all' ? 'selected' : '' }}>All</option>
                                        <?php $RepeatData = []; ?>
                                        @foreach($DropdownData as $val)
                                        <?php
                                        $BinCap = isset($val->store->Total_Bin_Capacity) && $val->store->Total_Bin_Capacity != '' ? $val->store->Total_Bin_Capacity : '';
                                        if (!empty($BinCap) && !in_array($BinCap, $RepeatData)) {
                                            $RepeatData[] = $BinCap;
                                        ?>
                                            <option value="{{ $BinCap }}" {{isset($BinCaps) && $BinCaps==$BinCap?'selected':''}}>{{ $BinCap }}</option>
                                        <?php } ?>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2 mt-4">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                    <a href="{{url('FactoryCreater/List')}}"><button type="button" class="btn btn-secondary"><i class="fa fa-refresh"></i></button></a>
                                </div>
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
                                            <input type="checkbox" class="form-check-input" id="Organization" value="Organization" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Organization">Organization</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Name_Of_The_Unit" value="Name Of The Unit" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Name_Of_The_Unit">Name Of The Unit</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="District_State" value="District/State" onclick="filterTable(this)">
                                            <label class="form-check-label" for="District_State">District/State</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Total_Plant" value="Total Plant" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Total_Plant">Total Plant</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="GST_No" value="GST No." onclick="filterTable(this)">
                                            <label class="form-check-label" for="GST_No">GST No.</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Factory_License" value="Factory License" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Factory_License">Factory License</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Land_Type" value="Land Type" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Land_Type">Land Type</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Total_Godown" value="Total Godown" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Total_Godown">Total Godown</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Total_Rack" value="Total Rack" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Total_Rack">Total Rack</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Rack_Capacity" value="Rack Capacity" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Rack_Capacity">Rack Capacity</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Total_Bin" value="Total Bin" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Total_Bin">Total Bin</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Bin_Cap" value="Bin Cap." onclick="filterTable(this)">
                                            <label class="form-check-label" for="Bin_Cap">Bin Cap.</label>
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
                        </form>

                        <ul class="nav nav-tabs mb-3" id="ex1" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link count active" id="Alls" data-mdb-toggle="tab" href="#All" role="tab" aria-controls="All" aria-selected="true">All <span class="countss">{{count($addressDetails)}}</span></a>
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
                                                <th class="th-sm">Organization</th>
                                                <th class="th-sm">Name Of The Unit</th>
                                                <th class="th-sm">District/State</th>
                                                <th class="th-sm">Total Plant</th>
                                                <th class="th-sm">GST No.</th>
                                                <th class="th-sm">Factory License</th>
                                                <th class="th-sm">Land Type</th>
                                                <th class="th-sm">Total Godown</th>
                                                <th class="th-sm">Total Rack</th>
                                                <th class="th-sm">Rack Capacity</th>
                                                <th class="th-sm">Total Bin</th>
                                                <th class="th-sm">Bin Cap.</th>
                                                <th class="th-sm">Status</th>
                                                <th class="th-sm">Pending With</th>
                                                <th class="th-sm">Operation</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                            $sesionarr=[];
                                            @endphp
                                            @foreach($addressDetails as $key => $val)
                                            @php
                                            if(isset($val->store->status) && $val->store->status!=1)
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
                                                <td>{{isset($val->org->organisation) && $val->org->organisation!=''?$val->org->organisation:''}}</td>
                                                <td>{{isset($val->unitname->pname) && $val->unitname->pname!=''?$val->unitname->pname:''}}</td>
                                                <td>{{isset($val->districtss->distname) && $val->districtss->distname!=''?$val->districtss->distname:''}}/{{isset($val->statess->sname) && $val->statess->sname!=''?$val->statess->sname:''}}</td>
                                                <td>{{$val->plantsCount}}</td>
                                                <td>{{isset($val->gst->gst_no) && $val->gst->gst_no!=''?$val->gst->gst_no:''}}</td>
                                                <td>{{isset($val->factoryLicense->factory_license_no) && $val->factoryLicense->factory_license_no!=''?$val->factoryLicense->factory_license_no:''}}</td>
                                                <td>{{isset($val->landtype->land_type) && $val->landtype->land_type!=''?$val->landtype->land_type:''}}</td>
                                                <td>{{isset($val->WareHouseRoom->Total_Warehouse) && $val->WareHouseRoom->Total_Warehouse!=''?$val->WareHouseRoom->Total_Warehouse:''}}</td>
                                                <td>{{isset($val->store->Total_Rack) && $val->store->Total_Rack!=''?$val->store->Total_Rack:''}}</td>
                                                <td>{{isset($val->store->Rack_Capacity) && $val->store->Rack_Capacity!=''?$val->store->Rack_Capacity:''}}</td>
                                                <td>{{isset($val->store->Total_Bin) && $val->store->Total_Bin!=''?$val->store->Total_Bin:''}}</td>
                                                <td>{{isset($val->store->Total_Bin_Capacity) && $val->store->Total_Bin_Capacity!=''?$val->store->Total_Bin_Capacity:''}}</td>
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
                                                    @if(($val->Approve_status==='FORWARD' && isset($val->store->status) && $val->store->status!=1) || ($val->Approve_status=='' && isset($val->store->status) && $val->store->status!=1))
                                                    Pending With
                                                    @foreach($val->PendingWith as $name)
                                                    {{isset($name->fullname) && $name->fullname!=''?$name->fullname:''}},
                                                    @endforeach
                                                    @elseif($val->Approve_status=='RECHECK' || $val->Approve_status=='OBJECT')
                                                    {{isset($val->user->fullname) && $val->user->fullname!=''?'Pending With '.$val->user->fullname:''}}
                                                    @endif
                                                </td>
                                                <td class="maindffd">
                                                    @if(isset($val->store->status) && $val->store->status!=1)
                                                    <a href="{{ url('FactoryCreater/Fectory_view/' . $val->id.'/ALL') }}" class="btn btn-primary">View</a>
                                                    @if($val->Approve_status == 'RECHECK' && isset($EXT[1]['inputer']))
                                                    <a href="{{url('FactoryCreater/step1/'.$val->id)}}" class="btn btn-secondary">Edit</a>
                                                    @elseif($val->HoldStatus > 0)
                                                    <a href="{{url('FactoryCreater/Release_Hold/'.$val->id)}}" class="btn btn-secondary">Release</a>
                                                    @endif
                                                    @else
                                                    <a href="{{url('FactoryCreater/step1/'.$val->id)}}" class="btn btn-warning">Draft</a>
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
                                                <th class="th-sm">Organization</th>
                                                <th class="th-sm">Name Of The Unit</th>
                                                <th class="th-sm">District/State</th>
                                                <th class="th-sm">Total Plant</th>
                                                <th class="th-sm">GST No.</th>
                                                <th class="th-sm">Factory License</th>
                                                <th class="th-sm">Land Type</th>
                                                <th class="th-sm">Total Godown</th>
                                                <th class="th-sm">Total Rack</th>
                                                <th class="th-sm">Rack Capacity</th>
                                                <th class="th-sm">Total Bin</th>
                                                <th class="th-sm">Bin Cap.</th>
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
                                                <td>{{isset($val->created_at) && $val->created_at!=''?date('d-m-Y H:i:s',strtotime($val->created_at)):''}}</td>
                                                <td>{{isset($val->org->organisation) && $val->org->organisation!=''?$val->org->organisation:''}}</td>
                                                <td>{{isset($val->unitname->pname) && $val->unitname->pname!=''?$val->unitname->pname:''}}</td>
                                                <td>{{isset($val->districtss->distname) && $val->districtss->distname!=''?$val->districtss->distname:''}}/{{isset($val->statess->sname) && $val->statess->sname!=''?$val->statess->sname:''}}</td>
                                                <td>{{$val->plantsCount}}</td>
                                                <td>{{isset($val->gst->gst_no) && $val->gst->gst_no!=''?$val->gst->gst_no:''}}</td>
                                                <td>{{isset($val->factoryLicense->factory_license_no) && $val->factoryLicense->factory_license_no!=''?$val->factoryLicense->factory_license_no:''}}</td>
                                                <td>{{isset($val->landtype->land_type) && $val->landtype->land_type!=''?$val->landtype->land_type:''}}</td>
                                                <td>{{isset($val->WareHouseRoom->Total_Warehouse) && $val->WareHouseRoom->Total_Warehouse!=''?$val->WareHouseRoom->Total_Warehouse:''}}</td>
                                                <td>{{isset($val->store->Total_Rack) && $val->store->Total_Rack!=''?$val->store->Total_Rack:''}}</td>
                                                <td>{{isset($val->store->Rack_Capacity) && $val->store->Rack_Capacity!=''?$val->store->Rack_Capacity:''}}</td>
                                                <td>{{isset($val->store->Total_Bin) && $val->store->Total_Bin!=''?$val->store->Total_Bin:''}}</td>
                                                <td>{{isset($val->store->Total_Bin_Capacity) && $val->store->Total_Bin_Capacity!=''?$val->store->Total_Bin_Capacity:''}}</td>
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
                                                    @if(($val->Approve_status==='FORWARD' && isset($val->store->status) && $val->store->status!=1) || ($val->Approve_status=='' && isset($val->store->status) && $val->store->status!=1))
                                                    Pending With
                                                    @foreach($val->PendingWith as $name)
                                                    {{isset($name->fullname) && $name->fullname!=''?$name->fullname:''}},
                                                    @endforeach
                                                    @elseif($val->Approve_status=='RECHECK' || $val->Approve_status=='OBJECT')
                                                    {{isset($val->user->fullname) && $val->user->fullname!=''?'Pending With '.$val->user->fullname:''}}
                                                    @endif
                                                </td>
                                                <td class="maindffd">
                                                    @if(isset($val->store->status) && $val->store->status!=1)
                                                    <a href="{{url('FactoryCreater/Fectory_view/'.$val->id.'/APPROVE')}}" class="btn btn-primary">View</a>
                                                    @if($val->Approve_status == 'RECHECK' && isset($EXT[1]['inputer']))
                                                    <a href="{{url('FactoryCreater/step1/'.$val->id)}}" class="btn btn-secondary">Edit</a>
                                                    @endif
                                                    @else
                                                    <a href="{{url('FactoryCreater/step1/'.$val->id)}}" class="btn btn-warning">Draft</a>
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
                                                <th class="th-sm">Organization</th>
                                                <th class="th-sm">Name Of The Unit</th>
                                                <th class="th-sm">District/State</th>
                                                <th class="th-sm">Total Plant</th>
                                                <th class="th-sm">GST No.</th>
                                                <th class="th-sm">Factory License</th>
                                                <th class="th-sm">Land Type</th>
                                                <th class="th-sm">Total Godown</th>
                                                <th class="th-sm">Total Rack</th>
                                                <th class="th-sm">Rack Capacity</th>
                                                <th class="th-sm">Total Bin</th>
                                                <th class="th-sm">Bin Cap.</th>
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
                                                <td>{{isset($val->created_at) && $val->created_at!=''?date('d-m-Y H:i:s',strtotime($val->created_at)):''}}</td>
                                                <td>{{isset($val->org->organisation) && $val->org->organisation!=''?$val->org->organisation:''}}</td>
                                                <td>{{isset($val->unitname->pname) && $val->unitname->pname!=''?$val->unitname->pname:''}}</td>
                                                <td>{{isset($val->districtss->distname) && $val->districtss->distname!=''?$val->districtss->distname:''}}/{{isset($val->statess->sname) && $val->statess->sname!=''?$val->statess->sname:''}}</td>
                                                <td>{{$val->plantsCount}}</td>
                                                <td>{{isset($val->gst->gst_no) && $val->gst->gst_no!=''?$val->gst->gst_no:''}}</td>
                                                <td>{{isset($val->factoryLicense->factory_license_no) && $val->factoryLicense->factory_license_no!=''?$val->factoryLicense->factory_license_no:''}}</td>
                                                <td>{{isset($val->landtype->land_type) && $val->landtype->land_type!=''?$val->landtype->land_type:''}}</td>
                                                <td>{{isset($val->WareHouseRoom->Total_Warehouse) && $val->WareHouseRoom->Total_Warehouse!=''?$val->WareHouseRoom->Total_Warehouse:''}}</td>
                                                <td>{{isset($val->store->Total_Rack) && $val->store->Total_Rack!=''?$val->store->Total_Rack:''}}</td>
                                                <td>{{isset($val->store->Rack_Capacity) && $val->store->Rack_Capacity!=''?$val->store->Rack_Capacity:''}}</td>
                                                <td>{{isset($val->store->Total_Bin) && $val->store->Total_Bin!=''?$val->store->Total_Bin:''}}</td>
                                                <td>{{isset($val->store->Total_Bin_Capacity) && $val->store->Total_Bin_Capacity!=''?$val->store->Total_Bin_Capacity:''}}</td>
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
                                                    @if(($val->Approve_status==='FORWARD' && isset($val->store->status) && $val->store->status!=1) || ($val->Approve_status=='' && isset($val->store->status) && $val->store->status!=1))
                                                    Pending With
                                                    @foreach($val->PendingWith as $name)
                                                    {{isset($name->fullname) && $name->fullname!=''?$name->fullname:''}},
                                                    @endforeach
                                                    @elseif($val->Approve_status=='RECHECK' || $val->Approve_status=='OBJECT')
                                                    {{isset($val->user->fullname) && $val->user->fullname!=''?'Pending With '.$val->user->fullname:''}}
                                                    @endif
                                                </td>
                                                <td class="maindffd">
                                                    @if(isset($val->store->status) && $val->store->status!=1)
                                                    <a href="{{url('FactoryCreater/Fectory_view/'.$val->id.'/PENDING')}}" class="btn btn-primary">View</a>
                                                    @if($val->Approve_status == 'RECHECK' && isset($EXT[1]['inputer']))
                                                    <a href="{{url('FactoryCreater/step1/'.$val->id)}}" class="btn btn-secondary">Edit</a>
                                                    @endif
                                                    @else
                                                    <a href="{{url('FactoryCreater/step1/'.$val->id)}}" class="btn btn-warning">Draft</a>
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
                                                <th class="th-sm">Organization</th>
                                                <th class="th-sm">Name Of The Unit</th>
                                                <th class="th-sm">District/State</th>
                                                <th class="th-sm">Total Plant</th>
                                                <th class="th-sm">GST No.</th>
                                                <th class="th-sm">Factory License</th>
                                                <th class="th-sm">Land Type</th>
                                                <th class="th-sm">Total Godown</th>
                                                <th class="th-sm">Total Rack</th>
                                                <th class="th-sm">Rack Capacity</th>
                                                <th class="th-sm">Total Bin</th>
                                                <th class="th-sm">Bin Cap.</th>
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
                                                <td>{{isset($val->created_at) && $val->created_at!=''?date('d-m-Y H:i:s',strtotime($val->created_at)):''}}</td>
                                                <td>{{isset($val->org->organisation) && $val->org->organisation!=''?$val->org->organisation:''}}</td>
                                                <td>{{isset($val->unitname->pname) && $val->unitname->pname!=''?$val->unitname->pname:''}}</td>
                                                <td>{{isset($val->districtss->distname) && $val->districtss->distname!=''?$val->districtss->distname:''}}/{{isset($val->statess->sname) && $val->statess->sname!=''?$val->statess->sname:''}}</td>
                                                <td>{{$val->plantsCount}}</td>
                                                <td>{{isset($val->gst->gst_no) && $val->gst->gst_no!=''?$val->gst->gst_no:''}}</td>
                                                <td>{{isset($val->factoryLicense->factory_license_no) && $val->factoryLicense->factory_license_no!=''?$val->factoryLicense->factory_license_no:''}}</td>
                                                <td>{{isset($val->landtype->land_type) && $val->landtype->land_type!=''?$val->landtype->land_type:''}}</td>
                                                <td>{{isset($val->WareHouseRoom->Total_Warehouse) && $val->WareHouseRoom->Total_Warehouse!=''?$val->WareHouseRoom->Total_Warehouse:''}}</td>
                                                <td>{{isset($val->store->Total_Rack) && $val->store->Total_Rack!=''?$val->store->Total_Rack:''}}</td>
                                                <td>{{isset($val->store->Rack_Capacity) && $val->store->Rack_Capacity!=''?$val->store->Rack_Capacity:''}}</td>
                                                <td>{{isset($val->store->Total_Bin) && $val->store->Total_Bin!=''?$val->store->Total_Bin:''}}</td>
                                                <td>{{isset($val->store->Total_Bin_Capacity) && $val->store->Total_Bin_Capacity!=''?$val->store->Total_Bin_Capacity:''}}</td>
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
                                                    @if(($val->Approve_status==='FORWARD' && isset($val->store->status) && $val->store->status!=1) || ($val->Approve_status=='' && isset($val->store->status) && $val->store->status!=1))
                                                    Pending With
                                                    @foreach($val->PendingWith as $name)
                                                    {{isset($name->name) && $name->name!=''?$name->name:''}},
                                                    @endforeach
                                                    @elseif($val->Approve_status=='RECHECK' || $val->Approve_status=='OBJECT')
                                                    {{isset($val->user->name) && $val->user->name!=''?'Pending With '.$val->user->name:''}}
                                                    @endif
                                                </td>
                                                <td class="maindffd">
                                                    @if(isset($val->store->status) && $val->store->status!=1)
                                                    <a href="{{url('FactoryCreater/Fectory_view/'.$val->id.'/HOLD')}}" class="btn btn-primary">View</a>
                                                    @if($val->Approve_status == 'RECHECK' && isset($EXT[1]['inputer']))
                                                    <a href="{{url('FactoryCreater/step1/'.$val->id)}}" class="btn btn-secondary">Edit</a>
                                                    @elseif($val->HoldStatus > 0)
                                                    <a href="{{url('FactoryCreater/Release_Hold/'.$val->id)}}" class="btn btn-secondary">Release</a>
                                                    @endif
                                                    @else
                                                    <a href="{{url('FactoryCreater/step1/'.$val->id)}}" class="btn btn-warning">Draft</a>
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
                                                <th class="th-sm">Organization</th>
                                                <th class="th-sm">Name Of The Unit</th>
                                                <th class="th-sm">District/State</th>
                                                <th class="th-sm">Total Plant</th>
                                                <th class="th-sm">GST No.</th>
                                                <th class="th-sm">Factory License</th>
                                                <th class="th-sm">Land Type</th>
                                                <th class="th-sm">Total Godown</th>
                                                <th class="th-sm">Total Rack</th>
                                                <th class="th-sm">Rack Capacity</th>
                                                <th class="th-sm">Total Bin</th>
                                                <th class="th-sm">Bin Cap.</th>
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
                                                <td>{{isset($val->created_at) && $val->created_at!=''?date('d-m-Y H:i:s',strtotime($val->created_at)):''}}</td>
                                                <td>{{isset($val->org->organisation) && $val->org->organisation!=''?$val->org->organisation:''}}</td>
                                                <td>{{isset($val->unitname->pname) && $val->unitname->pname!=''?$val->unitname->pname:''}}</td>
                                                <td>{{isset($val->districtss->distname) && $val->districtss->distname!=''?$val->districtss->distname:''}}/{{isset($val->statess->sname) && $val->statess->sname!=''?$val->statess->sname:''}}</td>
                                                <td>{{$val->plantsCount}}</td>
                                                <td>{{isset($val->gst->gst_no) && $val->gst->gst_no!=''?$val->gst->gst_no:''}}</td>
                                                <td>{{isset($val->factoryLicense->factory_license_no) && $val->factoryLicense->factory_license_no!=''?$val->factoryLicense->factory_license_no:''}}</td>
                                                <td>{{isset($val->landtype->land_type) && $val->landtype->land_type!=''?$val->landtype->land_type:''}}</td>
                                                <td>{{isset($val->WareHouseRoom->Total_Warehouse) && $val->WareHouseRoom->Total_Warehouse!=''?$val->WareHouseRoom->Total_Warehouse:''}}</td>
                                                <td>{{isset($val->store->Total_Rack) && $val->store->Total_Rack!=''?$val->store->Total_Rack:''}}</td>
                                                <td>{{isset($val->store->Rack_Capacity) && $val->store->Rack_Capacity!=''?$val->store->Rack_Capacity:''}}</td>
                                                <td>{{isset($val->store->Total_Bin) && $val->store->Total_Bin!=''?$val->store->Total_Bin:''}}</td>
                                                <td>{{isset($val->store->Total_Bin_Capacity) && $val->store->Total_Bin_Capacity!=''?$val->store->Total_Bin_Capacity:''}}</td>
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
                                                    @if(($val->Approve_status==='FORWARD' && isset($val->store->status) && $val->store->status!=1) || ($val->Approve_status=='' && isset($val->store->status) && $val->store->status!=1))
                                                    Pending With
                                                    @foreach($val->PendingWith as $name)
                                                    {{isset($name->name) && $name->name!=''?$name->name:''}},
                                                    @endforeach
                                                    @elseif($val->Approve_status=='RECHECK' || $val->Approve_status=='OBJECT')
                                                    {{isset($val->user->name) && $val->user->name!=''?'Pending With '.$val->user->name:''}}
                                                    @endif
                                                </td>
                                                <td class="maindffd">
                                                    @if(isset($val->store->status) && $val->store->status!=1)
                                                    <a href="{{url('FactoryCreater/Fectory_view/'.$val->id.'/RECHECK')}}" class="btn btn-primary">View</a>
                                                    @if($val->Approve_status == 'RECHECK' && isset($EXT[1]['inputer']))
                                                    <a href="{{url('FactoryCreater/step1/'.$val->id)}}" class="btn btn-secondary">Edit</a>
                                                    @endif
                                                    @else
                                                    <a href="{{url('FactoryCreater/step1/'.$val->id)}}" class="btn btn-warning">Draft</a>
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
                                                <th class="th-sm">Organization</th>
                                                <th class="th-sm">Name Of The Unit</th>
                                                <th class="th-sm">District/State</th>
                                                <th class="th-sm">Total Plant</th>
                                                <th class="th-sm">GST No.</th>
                                                <th class="th-sm">Factory License</th>
                                                <th class="th-sm">Land Type</th>
                                                <th class="th-sm">Total Godown</th>
                                                <th class="th-sm">Total Rack</th>
                                                <th class="th-sm">Rack Capacity</th>
                                                <th class="th-sm">Total Bin</th>
                                                <th class="th-sm">Bin Cap.</th>
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
                                                <td>{{isset($val->created_at) && $val->created_at!=''?date('d-m-Y H:i:s',strtotime($val->created_at)):''}}</td>
                                                <td>{{isset($val->org->organisation) && $val->org->organisation!=''?$val->org->organisation:''}}</td>
                                                <td>{{isset($val->unitname->pname) && $val->unitname->pname!=''?$val->unitname->pname:''}}</td>
                                                <td>{{isset($val->districtss->distname) && $val->districtss->distname!=''?$val->districtss->distname:''}}/{{isset($val->statess->sname) && $val->statess->sname!=''?$val->statess->sname:''}}</td>
                                                <td>{{$val->plantsCount}}</td>
                                                <td>{{isset($val->gst->gst_no) && $val->gst->gst_no!=''?$val->gst->gst_no:''}}</td>
                                                <td>{{isset($val->factoryLicense->factory_license_no) && $val->factoryLicense->factory_license_no!=''?$val->factoryLicense->factory_license_no:''}}</td>
                                                <td>{{isset($val->landtype->land_type) && $val->landtype->land_type!=''?$val->landtype->land_type:''}}</td>
                                                <td>{{isset($val->WareHouseRoom->Total_Warehouse) && $val->WareHouseRoom->Total_Warehouse!=''?$val->WareHouseRoom->Total_Warehouse:''}}</td>
                                                <td>{{isset($val->store->Total_Rack) && $val->store->Total_Rack!=''?$val->store->Total_Rack:''}}</td>
                                                <td>{{isset($val->store->Rack_Capacity) && $val->store->Rack_Capacity!=''?$val->store->Rack_Capacity:''}}</td>
                                                <td>{{isset($val->store->Total_Bin) && $val->store->Total_Bin!=''?$val->store->Total_Bin:''}}</td>
                                                <td>{{isset($val->store->Total_Bin_Capacity) && $val->store->Total_Bin_Capacity!=''?$val->store->Total_Bin_Capacity:''}}</td>
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
                                                    @if(($val->Approve_status==='FORWARD' && isset($val->store->status) && $val->store->status!=1) || ($val->Approve_status=='' && isset($val->store->status) && $val->store->status!=1))
                                                    Pending With
                                                    @foreach($val->PendingWith as $name)
                                                    {{isset($name->name) && $name->name!=''?$name->name:''}},
                                                    @endforeach
                                                    @elseif($val->Approve_status=='RECHECK' || $val->Approve_status=='OBJECT')
                                                    {{isset($val->user->name) && $val->user->name!=''?'Pending With '.$val->user->name:''}}
                                                    @endif
                                                </td>
                                                <td class="maindffd">
                                                    @if(isset($val->store->status) && $val->store->status!=1)
                                                    <a href="{{url('FactoryCreater/Fectory_view/'.$val->id.'/OBJECT')}}" class="btn btn-primary">View</a>
                                                    @if($val->Approve_status == 'RECHECK' && isset($EXT[1]['inputer']))
                                                    <a href="{{url('FactoryCreater/step1/'.$val->id)}}" class="btn btn-secondary">Edit</a>
                                                    @endif
                                                    @else
                                                    <a href="{{url('FactoryCreater/step1/'.$val->id)}}" class="btn btn-warning">Draft</a>
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
                                                <th class="th-sm">Organization</th>
                                                <th class="th-sm">Name Of The Unit</th>
                                                <th class="th-sm">District/State</th>
                                                <th class="th-sm">Total Plant</th>
                                                <th class="th-sm">GST No.</th>
                                                <th class="th-sm">Factory License</th>
                                                <th class="th-sm">Land Type</th>
                                                <th class="th-sm">Total Godown</th>
                                                <th class="th-sm">Total Rack</th>
                                                <th class="th-sm">Rack Capacity</th>
                                                <th class="th-sm">Total Bin</th>
                                                <th class="th-sm">Bin Cap.</th>
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
                                                <td>{{isset($val->created_at) && $val->created_at!=''?date('d-m-Y H:i:s',strtotime($val->created_at)):''}}</td>
                                                <td>{{isset($val->org->organisation) && $val->org->organisation!=''?$val->org->organisation:''}}</td>
                                                <td>{{isset($val->unitname->pname) && $val->unitname->pname!=''?$val->unitname->pname:''}}</td>
                                                <td>{{isset($val->districtss->distname) && $val->districtss->distname!=''?$val->districtss->distname:''}}/{{isset($val->statess->sname) && $val->statess->sname!=''?$val->statess->sname:''}}</td>
                                                <td>{{$val->plantsCount}}</td>
                                                <td>{{isset($val->gst->gst_no) && $val->gst->gst_no!=''?$val->gst->gst_no:''}}</td>
                                                <td>{{isset($val->factoryLicense->factory_license_no) && $val->factoryLicense->factory_license_no!=''?$val->factoryLicense->factory_license_no:''}}</td>
                                                <td>{{isset($val->landtype->land_type) && $val->landtype->land_type!=''?$val->landtype->land_type:''}}</td>
                                                <td>{{isset($val->WareHouseRoom->Total_Warehouse) && $val->WareHouseRoom->Total_Warehouse!=''?$val->WareHouseRoom->Total_Warehouse:''}}</td>
                                                <td>{{isset($val->store->Total_Rack) && $val->store->Total_Rack!=''?$val->store->Total_Rack:''}}</td>
                                                <td>{{isset($val->store->Rack_Capacity) && $val->store->Rack_Capacity!=''?$val->store->Rack_Capacity:''}}</td>
                                                <td>{{isset($val->store->Total_Bin) && $val->store->Total_Bin!=''?$val->store->Total_Bin:''}}</td>
                                                <td>{{isset($val->store->Total_Bin_Capacity) && $val->store->Total_Bin_Capacity!=''?$val->store->Total_Bin_Capacity:''}}</td>
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
                                                    @if(($val->Approve_status==='FORWARD' && isset($val->store->status) && $val->store->status!=1) || ($val->Approve_status=='' && isset($val->store->status) && $val->store->status!=1))
                                                    Pending With
                                                    @foreach($val->PendingWith as $name)
                                                    {{isset($name->name) && $name->name!=''?$name->name:''}},
                                                    @endforeach
                                                    @elseif($val->Approve_status=='RECHECK' || $val->Approve_status=='OBJECT')
                                                    {{isset($val->user->name) && $val->user->name!=''?'Pending With '.$val->user->name:''}}
                                                    @endif
                                                </td>
                                                <td class="maindffd">
                                                    @if(isset($val->store->status) && $val->store->status!=1)
                                                    <a href="{{url('FactoryCreater/Fectory_view/'.$val->id.'/REJECT')}}" class="btn btn-primary">View</a>
                                                    @if($val->Approve_status == 'RECHECK' && isset($EXT[1]['inputer']))
                                                    <a href="{{url('FactoryCreater/step1/'.$val->id)}}" class="btn btn-secondary">Edit</a>
                                                    @endif
                                                    @else
                                                    <a href="{{url('FactoryCreater/step1/'.$val->id)}}" class="btn btn-warning">Draft</a>
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
        <br>
        <br>
    </div>
    </section>
    </div>
    </div>
    </section>
</body>
@endsection
@push('custom-scripts')
<script>
    activeclass(5, 1);
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
    var tableID = 1;

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

        fetch("{{ url('FactoryCreater/getCheckBoxData') }}?ID=" + tableID, {
                method: 'GET',
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.columns) {
                    try {
                        var existingData = data.columns;
                        if (JSON.stringify(existingData) !== JSON.stringify(columnNamesToShow)) {
                            fetch("{{ url('FactoryCreater/CheckBoxStore') }}", {
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

        fetch("{{ url('FactoryCreater/getCheckBoxData') }}?ID=" + tableID, {
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
            url: "{{ url('FactoryCreater/CheckHoldExpiry') }}",
            method: 'GET',
            success: function(response) {
                response.forEach(function(lead) {
                    if (lead.action === 'HOLD' && lead.status === 1) {
                        var currentDate = new Date();
                        var holdDate = new Date(lead.days_for_holding);

                        if (holdDate < currentDate) {
                            UpdateStatus(lead.factory_id, lead.userID);
                        }
                    }
                });
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    });

    function UpdateStatus(factory_id, userID) {
        $.ajax({
            url: "{{ url('FactoryCreater/UpdateStatus') }}",
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            data: JSON.stringify({
                factory_id: factory_id,
                userID: userID
            }),
            success: function(response) {
                $('#statuss' + factory_id).html('<span style="color: #FF9000;">Pending</span>');
            }
        });
    }
</script>
@endpush