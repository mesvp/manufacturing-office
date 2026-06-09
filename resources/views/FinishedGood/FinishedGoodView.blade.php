@extends('layout.main')
@section('main-container')
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
<title>Finished Good Gate Pass Material Details</title>
<style>
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

    .tab1 {
        padding: 20px;
        border: 1px solid #a8adb1;
    }

    tbody,
    td,
    tfoot,
    th,
    thead,
    tr {
        border: none !important;
    }


    table#dynamic_field {
        margin-top: -14px;
    }

    .downloadfile {
        display: flex;
    }

    .downloadfile div {
        margin: 0px 20px;
    }

    .downloadfile i.fa.fa-remove {
        color: red;
    }

    input.form-control.form-control-sm {
        margin-top: 10px;
    }

    hr {
        width: 99% !important;
    }

    div#adaais {
        margin-left: 10px;
        margin-bottom: 20px;

    }

    div#\a main_btn_uddhan {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        align-content: center;
    }

    table#ssef {
        border: 1px solid;
        width: 50%;
    }

    tr.jaafgg td {
        padding: 10px !important;
    }

    tr.jaafgg {
        border-bottom: 1px solid !important;
    }

    .rm_tabe {
        display: flex;
    }


    div#lkjhhdggdg {
        margin-top: 40px;
    }

    table#ssef td {
        padding-left: 10px;
        padding-top: 10px;
        padding-bottom: 10px;
    }


    input#logfgfau {
        height: 60px;
    }

    button#diraj-button {
        background: transparent;
        border: 1px solid;
    }

    table#ufkffguuyuffffu {
        margin-top: 30px;
        border: 1px solid #ddd;
    }

    table#ufkffguuyuffffu thead tr {
        padding: 10px !important;
    }

    table#ufkffguuyuffffu thead tr th.th-sm {
        padding: 10px;
        border: 1px solid #ddd !important;
    }

    table#ufkffguuyuffffu thead tr td.th-sm {
        padding: 10px;
        border: 1px solid #ddd !important;
    }

    div#himmatwalaa {
        display: flex;
        align-items: center;
        justify-content: center;
        align-content: center;
    }

    div#main_btn_uddhan {
        display: flex;
        justify-content: flex-end;
    }

    .selector {

        display: flex;

    }

    .selecotr-item {
        position: relative;

        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .selector-item_radio {
        appearance: none;
        display: none;
    }

    .selector .selecotr-item {
        margin: 4px;
    }

    .selector-item_label {
        position: relative;
        /* height: 63%; */
        /* width: 53%; */
        text-align: center;
        border-radius: 9999px;
        /* line-height: 400%; */
        font-weight: 600 !important;
        transition-duration: .5s;
        transition-property: transform, color, box-shadow;
        transform: none;
        padding: 7px 10px;
        border-radius: 5px !important;
        border: 1px solid #CED4DA;
        text-transform: capitalize;
    }

    .selector-item_radio:checked+.selector-item_label {
        background: #6741D5;
        color: white;
    }


    input[type="radio"] {

        display: none !important;
    }

    .textt {
        font-weight: 600;
    }

    div#DataTables_Table_0_filter {
        display: none;
    }

    div#Tabledata_length {
        display: none;
    }
</style>

<div class="card-form">
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
        @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
        @endif
        <section class="section">
            <div class="container-fluid">
                <div class="border-bottom pb-2 row">
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                        <h6>Finished Good Gatepass Details ({{$edit->uniqID??''}})</h6>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                        <label for="">Inputer Name : {{$uname}}</label>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                        <label for="">Date & Time : {{isset($edit->created_at) && $edit->created_at!=''?date('d-m-Y h:i A', strtotime($edit->created_at)):''}}</label>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                        <div class="addbtn p-0"> <!-- class :- extra -->
                            <a href="{{ url('FinishedGood/ExportFinishedGoodViewData/'.$id) }}"><i class='fa-file-excel fas text-success'></i></a>
                            <a href="{{url('FinishedGood/Finished_Good_List')}}" class="btn btn-info mr-1 btn-sm"> <i class="fa fa-arrow-left"></i></a>
                            <a href="{{url('FinishedGood/Finished_Good_List')}}" class="btn btn-info btn-sm"> <i class="fa fa-home"></i></a>
                        </div>
                    </div>
                </div>
                <div id="row">
                    <!-- <h6>Production</h6> -->

                    <div class="my-2 row" id="adaaishhhh">
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                            <label>
                                Unit Name
                            </label>
                            <select disabled name="Unit_Name" class="form-select form-select-sm" required>
                                <option value="" selected disabled>Select</option>
                                @foreach($Manufacturing_unit as $val)
                                <option value="{{$val->id}}" {{isset($edit->Unit_Name) && $edit->Unit_Name==$val->id?'selected':''}}>{{$val->pname}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                            <label>
                                Plant Name
                            </label>
                            <select disabled name="Plant_Name" id="Plant_Name" class="form-select form-select-sm" required>
                                <option value="" selected disabled>Select</option>
                                @foreach($plant_name as $val)
                                <option value="{{$val->id}}" {{isset($edit->Plant_Name) && $edit->Plant_Name==$val->id?'selected':''}}>{{$val->spname}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 form-group">
                            <label>
                                Organization Name
                            </label>
                            <select name="Organization_Name" disabled class="form-select form-select-sm" required>
                                    <option value="" disabled {{ old('Organization_Name', request()->Organization ?? '') == '' ? 'selected' : '' }}>Select</option>
                                    @foreach ($Organization as $val)
                                        <option value="{{ $val->id }}" {{ old('Organization_Name', $edit->Organization_Name ?? '') == $val->id ? 'selected' : '' }}>
                                            {{ $val->organisation ?? '' }}
                                        </option>
                                    @endforeach
                                </select>
                            @error('Organization_Name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 form-group">
                            <label>
                                Transaction Date
                            </label>
                            <input disabled type="date" value="{{$edit->Transaction_Date??''}}" placeholder="Production Date" name="Transaction_Date" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 form-group">
                            <label>
                                From Godown
                            </label>
                            <select disabled name="Godown_Name" class="form-select form-select-sm" required>
                                <option value="" selected disabled>Select</option>
                                @foreach($Godown_Name as $val)
                                <option value="{{$val->id}}" {{isset($edit->Godown_Name) && $edit->Godown_Name==$val->id?'selected':''}}>{{$val->inventory_name}}</option>
                                @endforeach
                            </select>
                    </div>
                    <div class="border row p-2" id="adaaishhhh">
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 form-group">
                            <label>Finished Good(FG)</lable>
                                <select disabled name="Raw_Material" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" id="RawMaterial" required>
                                    <option value="" selected disabled>Select</option>
                                    @foreach($Raw_Material as $val)
                                    <option value="{{$val->RawMaterial->id}}" {{isset($edit->Material_id) && $edit->Material_id==$val->RawMaterial->id?'selected':''}}>{{$val->RawMaterial->matname}}</option>
                                    @endforeach
                                </select>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 form-group">
                            <label>HSN Code </label>
                            <div class="field-wrap">
                                <input disabled type="text" name="hsn" id="hsn" value="{{$edit->HSN_Code??''}}" placeholder="Rate" class="form-control form-control-sm" required>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 form-group">
                            <label>UOM </label>
                            <div class="field-wrap">
                                <input disabled type="text" name="UOM" id="uom" value="{{$edit->UOM??''}}" placeholder="Rate" class="form-control form-control-sm" required>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                            <label>Rate</label>
                            <div class="field-wrap">
                                <input disabled type="text" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" name="Rate" id="Rate" value="{{$edit->Rate??''}}" placeholder="Rate" class="form-control form-control-sm" required>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                            <label>Quantity</label>
                            <div class="field-wrap">
                                <input disabled type="text" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" name="Quantity" onchange="materialdata()" value="{{$edit->Quantity??''}}" {{isset($edit->Quantity)?'readonly':''}} placeholder="Quantity" id="Quantity" class="form-control form-control-sm" required>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                            <label>GST</label>
                            <div class="field-wrap">
                                <input disabled type="text" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" name="GST" onchange="materialdata()" value="{{$edit->GST??''}}" {{isset($edit->GST)?'readonly':''}} placeholder="Quantity" id="Quantity" class="form-control form-control-sm" required>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                            <label>Total Amount </label>
                            <div class="field-wrap">
                                <input disabled type="text" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" name="Total_amount" value="{{$edit->Total_amount??''}}" id="Total_amount" placeholder="Rate*Quantity" class="form-control form-control-sm" required>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
                <br>
                
                 <div class="table-responsive">
                        <table class="table table-striped table-bordered dataTable no-footer" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="th-sm">SL No.</th>
                                    <th class="th-sm">Serial No.</th>
                                    <th class="th-sm">Supplier</th>
                                    <th class="th-sm">DOP</th>
                                    <th class="th-sm">Make</th>
                                    <th class="th-sm">Brand</th>
                                </tr>
                            </thead>
                            @foreach($finishedgooddetails as $key => $val)
                                <tbody>
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$val->serial_no}}</td>
                                        <td>{{$val->Supplier}}</td>
                                        <td>{{isset($val->dop) && $val->dop!='' ? date('d-m-Y', strtotime($val->dop)) : ''}}</td>
                                        <td>{{$val->make}}</td>
                                        <td>{{$val->brand}}</td>
                                    </tr>

                                </tbody>
                            @endforeach
                            
                        </table>
                    </div>
                </br>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 form-group">
                        <label for="State">Remarks:</label>
                        <input disabled type="text" name="remarks" cols="30" rows="5" class="form-control form-control-sm" placeholder="Remarks" value="{{isset($edit->remarks) && $edit->remarks!=''?$edit->remarks:''}}">
                    </div>
                </div>
            </div>
            <div class="container-fluid" id="action"> </div>
            <div class="container-fluid">
                <div class="table-responsive">
                    <table id="" class="table table-striped table-bordered example" style="width:100%">
                        <thead>
                            <tr>
                                <th class="th-sm">SL NO.</th>
                                <th class="th-sm">Action</th>
                                <th class="th-sm">Action By</th>
                                <th class="th-sm">Role. (Reviewer,Approver,ETC)</th>
                                <th class="th-sm">Date & time</th>
                                <th class="th-sm">comment</th>
                                <th class="th-sm">IP Address</th>
                                <th class="th-sm">Device ID</th>
                            </tr>
                        </thead>
                        <tbody id="trail"></tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>

@endsection
@push('custom-scripts')
<script>
    $(document).ready(function() {
        activeclass(28, 1);
    });
</script>
<script>
    function displayTime() {
        const now = new Date();
        const date = now.toLocaleDateString();
        const time = now.toLocaleTimeString();
        document.getElementById("clock").textContent = time + ', ' + date;
    }
    setInterval(displayTime, 1000);

    $(document).ready(function() {
        id = '{{$id}}';
        $.post("{{url('FinishedGood/inputeraction')}}", {
            id: id
        }, function(data) {
            $("#action").html(data);
        });
        // Directly run the code for fetching and displaying trail data
        $.post("{{url('FinishedGood/trail')}}", {
            id: id
        }, function(data) {
            $("#trail").html(data);
        });
    });
</script>


@endpush