@extends('layout.main')
@section('main-container')
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
<title>Visitor Gate Pass View Details Page</title>
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
        text-align: center;
        border-radius: 9999px;
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
                <a href="{{url('GatePass/visitor-list')}}" class="btn btn-info" style="margin-left:10px"> <i class="fa fa fa-arrow-left"></i> BACK</a>
                <a href="{{url('GatePass/visitor-list')}}" class="btn btn-info" style="margin-left:10px"> <i class="fa fa-home"></i> Home</a>
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
                                        @php
                                            $req_no = ($type == 'in') ? $edit->request_no : $edit->out_request_no;
                                        @endphp
                                        <div class="col">
                                            <h5>Visitor Gate Pass Details (<b class="text-primary">{{ $req_no }}</b>)</h5>
                                        </div>
                                        <div class="col">
                                            <label class="fw-bold" for="">Inputer Name : {{auth()->user()->fullname}}</label>
                                        </div>
                                        <div class="col">
                                            <label class="fw-bold" for="">Date & Time : <span id="clock"></span></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="tab1">
                                <input class="form-control" type="hidden" name="edit" value="{{ isset($edit->id) && $edit->id != '' ? $edit->id : '' }}">
                                <div class="row">
                                    <div class="col-sm-3 form-group">
                                        <label class="fw-bold">Created By</label>
                                        <input readonly class="form-control form-control-sm" type="text" id="requested_by" name="request_by" placeholder="Request By." value="{{ isset($edit->request_by) && $edit->request_by != '' ? $edit->request_by : auth()->user()->fullname }}">
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label class="fw-bold" for="Manunit">Cost Center</label>
                                        <input class="form-control form-control-sm" type="text" name="visitor_code[]" placeholder="Enter Visitor code" value="{{ isset($Manufacturing_unitdata[$edit->project_id]) ? $Manufacturing_unitdata[$edit->project_id] : 'N/A' }}" disabled>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label class="fw-bold" for="Plant_Name">Sub Cost Center</label>
                                        <input class="form-control form-control-sm" type="text" name="visitor_code[]" placeholder="Enter Visitor code" value="{{ isset($plant_namedata[$edit->subproject_id]) ? $plant_namedata[$edit->subproject_id] : 'N/A' }}" disabled>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label class="fw-bold">Organisation Name</label>
                                        <input class="form-control form-control-sm" type="text" name="visitor_code[]" placeholder="Enter Visitor code" value="{{ isset($orgdata[$edit->org_id]) ? $orgdata[$edit->org_id] : 'N/A' }}" disabled>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12"></div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="th-sm text-center">SL. NO.</th>
                                                    <th class="th-sm">VISITOR TYPE</th>
                                                    <th class="th-sm">VISITOR NAME</th>
                                                    <th class="th-sm">VISITOR MOBILE NO.</th>
                                                    <th class="th-sm">VISITOR ADDRESS</th>
                                                    <th class="th-sm">PURPOSE TO VISIT</th>
                                                    <th class="th-sm">WHOM TO MEET</th>
                                                </tr>
                                            </thead>
                                            <tbody id="VisitorGatepassFields">
                                                @php
                                                    $VisitorGatepassDetails = DB::table('gatepass_visitor_details')->where('request_no', $edit->request_no)->get();
                                                @endphp
                                                @foreach ($VisitorGatepassDetails as $key => $val)
                                                    <tr id="visitorgatepassRemove{{ $key + 1 }}">
                                                        <td class="text-center">{{ $key + 1 }}</td>
                                                        <td>
                                                            <select class="form-select js-example-matcher-start visitorType" name="visitor_type[]" disabled>
                                                                <option value="" disabled>Select Visitor Type</option>
                                                                <option value="0" {{ $val->visitor_type == 0 ? 'selected' : '' }}>OFFICE EMPLOYEE</option>
                                                                <option value="1" {{ $val->visitor_type == 1 ? 'selected' : '' }}>VISITOR</option>
                                                            </select>
                                                        </td>
                                                        <td class="item-desc-cell">
                                                            <div class="visitorDropdown" style="{{ $val->visitor_type == 0 ? 'display: block;' : 'display: none;' }}">
                                                                <select class="form-select js-example-matcher-start" name="visitor_name[]" disabled>
                                                                    <option value="" disabled>Select Employee</option>
                                                                    @foreach ($empDtls as $value)
                                                                        <option value="{{ $value->id }}" {{ $val->visitor_name == $value->id ? 'selected' : '' }}>{{ $value->fullname }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <input type="text" class="form-control form-control-sm visitorInput" name="visitor_name[]" placeholder="Enter Visitor Name" value="{{ $val->visitor_type == 1 ? $val->visitor_name : '' }}" style="{{ $val->visitor_type == 1 ? 'display: block;' : 'display: none;' }}" disabled>
                                                        </td>
                                                        <td>
                                                            <input class="form-control form-control-sm" type="number" name="visitor_phone[]" placeholder="Enter Phone No." value="{{ isset($val->visitor_phone) ? $val->visitor_phone : '' }}" disabled>
                                                        </td>
                                                        <td>
                                                            <textarea name="visitor_address[]" class="form-control form-control-sm" cols="30" rows="1" placeholder="Enter Your Address" disabled>{{ $val->visitor_address }}</textarea>
                                                        </td>
                                                        <td>
                                                            <textarea name="visitor_purpose[]" class="form-control form-control-sm" cols="30" rows="1" placeholder="Enter Purpose to Visit" disabled>{{ $val->visitor_purpose }}</textarea>
                                                        </td>
                                                        <td>
                                                            <input class="form-control form-control-sm" type="text" name="meet_prsn[]" placeholder="Enter meet person" value="{{ isset($val->visitor_meet_prsn) && $val->visitor_meet_prsn != '' ? $val->visitor_meet_prsn : '' }}" disabled>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                @if ($type == 'in')
                                    <div class="row">
                                        <div class="col-sm-2 form-group">
                                            <label class="fw-bold">In Time</label>
                                            <input class="form-control form-control-sm" id="intime" type="text" name="request_in_time" placeholder="Request In Time" value="{{ isset($edit->request_in_time) && $edit->request_in_time != '' ? $edit->request_in_time : '' }}" disabled>
                                        </div>
                                        <div class="col-sm-2 form-group">
                                            <label class="fw-bold">Expected Out Time</label>
                                            <input class="form-control form-control-sm" id="outtime" type="text" name="request_out_time" placeholder="Request Out Time" value="{{ isset($edit->request_out_time) && $edit->request_out_time != '' ? $edit->request_out_time : '' }}" disabled>
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
                                @else
                                    <div class="row">
                                        <div class="col-sm-4 form-group">
                                            <label class="fw-bold">In Time</label>
                                            <input class="form-control form-control-sm" id="intime" type="text" name="request_in_time" placeholder="Request In Time" value="{{ isset($edit->request_in_time) && $edit->request_in_time != '' ? date('d-m-Y h:i A', strtotime($edit->request_in_time)) : '' }}" disabled>
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label class="fw-bold">Expected Out Time</label>
                                            <input class="form-control form-control-sm" id="outtime" type="text" name="request_out_time" placeholder="Request Out Time" value="{{ isset($edit->request_out_time) && $edit->request_out_time != '' ? date('d-m-Y h:i A', strtotime($edit->request_out_time)) : '' }}" disabled>
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label class="fw-bold">Actual Out Time</label>
                                            <input class="form-control form-control-sm" id="outtime" type="text" name="request_out_time" placeholder="Request Out Time" value="{{ isset($edit->actual_out_time) && $edit->actual_out_time != '' ? date('d-m-Y h:i A', strtotime($edit->actual_out_time)) : '' }}" disabled>
                                        </div>
                                        <div class="col-sm-4 form-group">
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
                                        <div class="col-sm-2 form-group">
                                            <label class="fw-bold">Security Guard Name <span class="text-danger fw-bolder" id="sec_guard_req"></span></label>
                                            <input class="form-control form-control-sm" type="text" name="sec_guard_name" placeholder="Enter Guard Name" value="{{ isset($edit->out_sec_guard) && $edit->out_sec_guard != '' ? $edit->out_sec_guard : '' }}" id="sec_guard" disabled>
                                        </div>
                                        <div class="col-sm-2 form-group">
                                            <label class="fw-bold">Security Ph No <span class="text-danger fw-bolder" id="sec_guard_req"></span></label>
                                            <input class="form-control form-control-sm" type="text" name="sec_guard_phone" placeholder="Security Ph No" value="{{ isset($edit->out_sec_guard_no) && $edit->out_sec_guard_no != '' ? $edit->out_sec_guard_no : '' }}" id="sec_guard_phone" disabled>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label class="fw-bold">IN Visit Purpose <span class="text-danger fw-bolder" id="sec_guard_req"></span></label>
                                            <textarea class="form-control form-control-sm" name="visit_purpose" placeholder="Enter Visit Purpose" cols="30" rows="2" disabled>{{ isset($edit->visit_purpose) && $edit->visit_purpose != '' ? $edit->visit_purpose : '' }}</textarea>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label class="fw-bold">OUT Visit Purpose <span class="fw-bolder" id="out_visit_purpose"></span></label>
                                            <textarea class="form-control form-control-sm" name="out_visit_purpose" placeholder="Enter Visit Purpose" cols="30" rows="2" disabled>{{ isset($edit->out_visit_purpose) && $edit->out_visit_purpose != '' ? $edit->out_visit_purpose : '' }}</textarea>
                                        </div>
                                        <div class="col-sm-2 form-group">
                                            <label class="fw-bold">Whom To Meet <span class="text-danger fw-bolder" id="meet_prsn"></span></label>
                                            <input class="form-control form-control-sm" type="text" name="meet_prsn" placeholder="Enter meet person" value="{{ isset($edit->meet_prsn) && $edit->meet_prsn != '' ? $edit->meet_prsn : '' }}" id="meet_prsn" disabled>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 form-group">
                                            <label class="fw-bold" for="State">Remarks:</label>
                                            <textarea class="form-control" name="remarks" placeholder="Enter Remarks" cols="30" rows="4" disabled>{{ isset($edit->out_remarks) && $edit->out_remarks != '' ? $edit->out_remarks : '' }}</textarea>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
    function displayTime() {
        const now = new Date();
        const date = now.toLocaleDateString();
        const time = now.toLocaleTimeString();
        document.getElementById("clock").textContent = time + ', ' + date;
    }

    setInterval(displayTime, 1000);
    $(document).ready(function() {
        let id = '{{$edit->id}}';
        let type = '{{$type}}';
        let req_no = '{{$req_no}}';

        // Call activeclass based on the value of type
        if (type === 'in') {
            activeclass(7, 5);
        } else if (type === 'out') {
            activeclass(7, 6);
        }
        $.post("{{url('GatePass/visitor_action')}}", {
            id: id,
            type: type,
            req_no: req_no
        }, function(data) {
            $("#action").html(data);
        });
        $.post("{{url('GatePass/visitor_trail')}}", {
            id: id,
            type: type,
            req_no: req_no
        }, function(data) {
            $("#trail").html(data);
        });
    });
</script>
@endpush