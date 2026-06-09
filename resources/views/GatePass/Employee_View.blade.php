@extends('layout.main')
@section('main-container')
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
<title>Employee Gate Pass View Details Page</title>
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
        <section class="section">
            <div class="addbtn extra">
                <a href="{{url('GatePass/List')}}" class="btn btn-info" style="margin-left:10px"> <i class="fa fa fa-arrow-left"></i> BACK</a>
                <a href="{{url('GatePass/List')}}" class="btn btn-info" style="margin-left:10px"> <i class="fa fa-home"></i> Home</a>
            </div>
            <div class="row">
                <div class="container">
                    <br>
                    <div>
                        <div class="tabs">
                            <div class="row">
                                <div class="col-4">
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col">
                                            <h5>Employee Gate Pass Details (<b class="text-primary">{{ $edit->request_no }}</b>)</h5>
                                        </div>
                                        <div class="col">
                                            <label class="fw-bold" for="">Inputer Name : {{ isset($edit->request_by) && $edit->request_by != '' ? $edit->request_by : auth()->user()->fullname }}</label>
                                        </div>
                                        <div class="col">
                                            <label class="fw-bold" for="">Date & Time : <span id="clock"></span></label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <br>
                        </div>
                            @php
                                $type = 'in';
                                $req_no = $edit->request_no;
                            @endphp
                            <div class="tab1">
                                <input class="form-control" type="hidden" name="edit" value="{{ isset($edit->id) && $edit->id != '' ? $edit->id : '' }}">
                                <div class="row">
                                    <div class="col-sm-3 form-group">
                                        <label class="fw-bold">Created By</label>
                                        <input readonly class="form-control form-control-sm" type="text" id="requested_by" name="request_by" placeholder="Request By." value="{{ isset($edit->request_by) && $edit->request_by != '' ? $edit->request_by : auth()->user()->fullname }}" required>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label class="fw-bold" for="Manunit">Cost Center</label>
                                        <input class="form-control form-control-sm" type="text" name="emp_code[]" placeholder="Enter Employee code" value="{{ isset($Manufacturing_unitdata[$edit->project_id]) ? $Manufacturing_unitdata[$edit->project_id] : 'N/A' }}" required disabled>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label class="fw-bold" for="Plant_Name">Sub Cost Center</label>
                                        <input class="form-control form-control-sm" type="text" name="emp_code[]" placeholder="Enter Employee code" value="{{ isset($plant_namedata[$edit->subproject_id]) ? $plant_namedata[$edit->subproject_id] : 'N/A' }}" required disabled>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label class="fw-bold">Organisation Name</label>
                                        <input class="form-control form-control-sm" type="text" name="emp_code[]" placeholder="Enter Employee code" value="{{ isset($orgdata[$edit->org_id]) ? $orgdata[$edit->org_id] : 'N/A' }}" required disabled>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12"></div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="th-sm text-center">SL. No.</th>
                                                    <th class="th-sm">Employee Shift</th>
                                                    <th class="th-sm">Employee Name</th>
                                                    <th class="th-sm">Employee Code</th>
                                                    <th class="th-sm">Department</th>
                                                    <th class="th-sm">Ph No</th>
                                                </tr>
                                            </thead>
                                            <tbody id="EmployeeGatepassFields">
                                                @php
                                                    $EmployeeGatepassDetails = DB::table('gatepass_employee_details')->where('request_no', $edit->request_no)->get();
                                                @endphp
                                                @foreach ($EmployeeGatepassDetails as $key => $val)
                                                    <tr id="itemRow{{ $key + 1 }}">
                                                        <td class="text-center">{{ $key + 1 }}</td>
                                                        <td>
                                                            <input class="form-control form-control-sm" type="text" name="emp_shift[]" placeholder="Enter Employee shift" value="{{ $val->emp_shift }}" required disabled>
                                                        </td>
                                                        <td class="item-desc-cell">
                                                            <input class="form-control form-control-sm" type="text" name="emp_name[]" placeholder="Enter Employee name" value="{{ $val->emp_name }}" required disabled>
                                                        </td>
                                                        <td>
                                                            <input class="form-control form-control-sm" type="text" name="emp_code[]" placeholder="Enter Employee code" value="{{ $val->emp_code }}" required disabled>
                                                        </td>
                                                        <td>
                                                            <input class="form-control form-control-sm" type="text" name="emp_dept[]" placeholder="Enter Department" value="{{ $val->emp_dept }}" required disabled>
                                                        </td>
                                                        <td>
                                                            <input class="form-control form-control-sm" type="number" name="emp_phone[]" placeholder="Enter Phone No." value="{{ $val->emp_phone }}" required disabled>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-2 form-group">
                                        <label class="fw-bold">In Time</label>
                                        <input class="form-control form-control-sm" id="intime" type="text" name="request_in_time" placeholder="Request In Time" value="{{ isset($edit->request_in_time) && $edit->request_in_time != '' ? date('d-m-Y h:i A' , strtotime($edit->request_in_time)) : '' }}" disabled>
                                    </div>
                                    <div class="col-sm-2 form-group">
                                        <label class="fw-bold">Expected Out Time</label>
                                        <input class="form-control form-control-sm" id="outtime" type="text" name="request_out_time" placeholder="Request Out Time" value="{{ isset($edit->request_out_time) && $edit->request_out_time != '' ? date('d-m-Y h:i A' , strtotime($edit->request_out_time)) : '' }}" disabled>
                                    </div>
                                    <div class="col-sm-2 form-group">
                                        <label class="fw-bold">Person With Vehicle</label>
                                        <select class="form-select form-select-sm js-example-matcher-start" name="prsn_vehicle" id="prsn_vehicle" disabled>
                                            <option value="" disabled selected>Select Option</option>
                                            <option value="1" {{ isset($edit->prsn_vehicle) && $edit->prsn_vehicle == 1 ? 'selected' : '' }}>Yes</option>
                                            <option value="0" {{ isset($edit->prsn_vehicle) && $edit->prsn_vehicle == 0 ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>
                                    @if (isset($edit->prsn_vehicle) && $edit->prsn_vehicle == 1)
                                        <div class="col-sm-3 form-group" id="vehicle_type_container">
                                            <label class="fw-bold">Vehicle Type</label>
                                            <select class="form-select form-select-sm js-example-matcher-start" name="vehicle_type" id="vehicle_type" disabled>
                                                <option value="" disabled selected>Select Type</option>
                                                @foreach ($vehicle_types as $vehicle_type)
                                                    <option value="{{ $vehicle_type->id }}" {{ isset($edit->vehicle_type) && $edit->vehicle_type == $vehicle_type->id ? 'selected' : '' }}>{{ $vehicle_type->mstr_type_value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-3 form-group" id="vehicle_no_container">
                                            <label class="fw-bold">Vehicle No.</label>
                                            <input class="form-control form-control-sm" type="text" name="vehicle_no" placeholder="Enter Vehicle No." id="vehicle_no" value="{{ isset($edit->vehicle_no) && $edit->vehicle_no != '' ? $edit->vehicle_no : '' }}" disabled>
                                        </div>
                                    @endif
                                </div>

                                <div class="row">
                                    <div class="col-sm-3 form-group">
                                        <label class="fw-bold">Security Guard Name <span class="text-danger fw-bolder" id="sec_guard_req"></span></label>
                                        <input class="form-control form-control-sm" type="text" name="sec_guard_name" placeholder="Enter Guard Name" value="{{ isset($edit->sec_guard) && $edit->sec_guard != '' ? $edit->sec_guard : '' }}" id="sec_guard" disabled>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label class="fw-bold">Security Ph No <span class="text-danger fw-bolder" id="sec_guard_req"></span></label>
                                        <input class="form-control form-control-sm" type="text" name="sec_guard_phone" placeholder="Security Ph No" value="{{ isset($edit->sec_guard_no) && $edit->sec_guard_no != '' ? $edit->sec_guard_no : '' }}" id="sec_guard_phone" disabled>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label class="fw-bold">IN Visit Purpose <span class="text-danger fw-bolder" id="sec_guard_req"></span></label>
                                        <textarea class="form-control form-control-sm" name="visit_purpose" placeholder="Enter Visit Purpose" cols="30" rows="2" disabled>{{ isset($edit->visit_purpose) && $edit->visit_purpose != '' ? $edit->visit_purpose : '' }}</textarea>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label class="fw-bold">Whom To Meet <span class="text-danger fw-bolder" id="meet_prsn"></span></label>
                                        <input class="form-control form-control-sm" type="text" name="meet_prsn" placeholder="Enter meet person" value="{{ isset($edit->meet_prsn) && $edit->meet_prsn != '' ? $edit->meet_prsn : '' }}" id="meet_prsn" disabled>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 form-group">
                                        <label class="fw-bold" for="State">Remarks:</label>
                                        <textarea class="form-control" name="remarks" placeholder="Enter Remarks" cols="30" rows="4" disabled>{{ isset($edit->remarks) && $edit->remarks != '' ? $edit->remarks : '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="container-fluid" id="action"> </div>
            <div class="container-fluid">
                <div class="table-responsive">
                    <table id="" class="table table-striped table-bordered mt-2 text-center" style="width:100%">
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
</section>
@endsection
@push('custom-scripts')
<script>
    activeclass(7, 1);
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
        id = '{{$edit->id}}';
        type = '{{$type}}';
        req_no = '{{$req_no}}';
        $.post("{{url('GatePass/employee_inputeraction')}}", {
            id: id,
            type: type,
            req_no: req_no
        }, function(data) {
            $("#action").html(data);
        });
        $.post("{{url('GatePass/employee_trail')}}", {
            id: id,
            type: type,
            req_no: req_no
        }, function(data) {
            $("#trail").html(data);
        });
    });
</script>
@endpush