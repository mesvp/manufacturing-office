@extends('layout.main')
@section('main-container')
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
<title>Visitor Gate Pass Form Details</title>
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
        border-radius: 10px;
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
                                            <h5>Visitor Gate Pass Form Details </h5>
                                        </div>
                                        <div class="col">
                                            <label for="">Inputer Name : {{auth()->user()->fullname}}</label>
                                        </div>
                                        <div class="col">
                                            <label for="">Date & Time : <span id="clock"></span></label>
                                        </div>
                                        <div class="col addbtn extra">
                                            <a href="{{ url('GatePass/visitor-list') }}" class="btn btn-info"> <i class="fa fa-arrow-left"></i> BACK</a>
                                            <a href="{{ url('GatePass/visitor-list') }}" class="btn btn-info" style="margin-left:10px"> <i class="fa fa-home"></i> Home</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="tab1">
                                <form id="submitform" action="{{url('GatePass/visitor-store')}}" method="POST">
                                    @csrf
                                    <input class="form-control" type="hidden" name="edit" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                                    <div class="row">
                                        <div class="col-sm-3 form-group">
                                            <label>Created By <span class="text-danger fw-bolder">*</span></label>
                                            <input readonly class="form-control form-control-sm" type="text" id="requested_by" name="request_by" placeholder="Request By." value="{{ isset($edit->request_by) && $edit->request_by != '' ? $edit->request_by : auth()->user()->fullname }}" required>
                                        </div>
                                        @if (isset($edit->id) && $edit->id != '')
                                            <div class="col-sm-3 form-group">
                                                <label for="Manunit">Cost Center <span class="text-danger fw-bolder">*</span>
                                                <select class="form-select form-select-sm js-example-matcher-start" name="cost_center" id="Manunit" required disabled>
                                                    <option value="" disabled selected>Select Project</option>
                                                    @foreach ($Projects as $val)
                                                        <option value="{{ $val->id }}" {{ isset($edit->project_id) && $edit->project_id == $val->id ? 'selected' : '' }}>{{ $val->pname }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label for="Plant_Name">Sub Cost Center <span class="text-danger fw-bolder">*</span></label>
                                                <select class="form-select form-select-sm js-example-matcher-start" name="sub_cost_center" id="Plant_Name" required disabled>
                                                    <option value="" disabled selected>Select Sub Project</option>
                                                    @if (isset($edit->subproject_id))
                                                        @foreach ($Sub_Projects as $val)
                                                            <option value="{{ $val->id }}" {{ isset($edit->subproject_id) && $edit->subproject_id == $val->id ? 'selected' : '' }}>{{ $val->spname }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label>Organisation Name <span class="text-danger fw-bolder">*</span></label>
                                                <select class="form-select form-select-sm js-example-matcher-start" name="organisation_name" id="organisation_name" required disabled>
                                                    <option value="" disabled selected>Select Organisation</option>
                                                    @foreach ($Organisations as $val)
                                                        <option value="{{ $val->id }}" {{ isset($edit->org_id) && $edit->org_id == $val->id ? 'selected' : '' }}>{{ $val->organisation }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @else
                                            <div class="col-sm-3 form-group">
                                                <label for="Manunit">Cost Center <span class="text-danger fw-bolder">*</span></label>
                                                <select class="form-select form-select-sm js-example-matcher-start" name="cost_center" id="Manunit" required>
                                                    <option value="" disabled selected>Select Project</option>
                                                    @foreach ($Projects as $val)
                                                        <option value="{{ $val->id }}">{{ $val->pname }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label for="Plant_Name">Sub Cost Center <span class="text-danger fw-bolder">*</span>
                                                <select class="form-select form-select-sm js-example-matcher-start" name="sub_cost_center" id="Plant_Name" required>
                                                    <option value="" disabled selected>Select Sub Project</option>
                                                    @if (isset($edit->subproject_id))
                                                        @foreach ($Sub_Projects as $val)
                                                            <option value="{{ $val->id }}">{{ $val->spname }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label>Organisation Name <span class="text-danger fw-bolder">*</span>
                                                <select class="form-select form-select-sm js-example-matcher-start" name="organisation_name" id="organisation_name" required>
                                                    <option value="" disabled selected>Select Organisation</option>
                                                    @foreach ($Organisations as $val)
                                                        <option value="{{ $val->id }}">{{ $val->organisation }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="row">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="th-sm text-center">SL. NO. <span class="text-danger fw-bolder">*</span></th>
                                                        <th class="th-sm">VISITOR TYPE <span class="text-danger fw-bolder">*</span></th>
                                                        <th class="th-sm">VISITOR NAME <span class="text-danger fw-bolder">*</span></th>
                                                        <th class="th-sm">VISITOR MOBILE NO. <span class="text-danger fw-bolder">*</span></th>
                                                        <th class="th-sm">VISITOR ADDRESS <span class="text-danger fw-bolder">*</span></th>
                                                        <th class="th-sm">PURPOSE TO VISIT <span class="text-danger fw-bolder">*</span></th>
                                                        <th class="th-sm">WHOM TO MEET <span class="text-danger fw-bolder">*</span></th>
                                                        @if (!isset($edit->id))
                                                            <th class="th-sm">ACTION</th>
                                                        @endif
                                                    </tr>
                                                </thead>
                                                <tbody id="VisitorGatepassFields">
                                                    @if (isset($edit->id) && $edit->id != '')
                                                    @php
                                                        $VisitorGatepassDetails = DB::table('gatepass_visitor_details')->where('request_no', $edit->request_no)->get();
                                                    @endphp
                                                    @foreach ($VisitorGatepassDetails as $key => $val)
                                                        <tr id="{{ $key + 1 }}">
                                                            <td class="text-center">{{ $key + 1 }}</td>
                                                            <td>
                                                                <select class="form-select js-example-matcher-start" name="visitor_types[]" disabled>
                                                                    <option value="" disabled>Select Visitor Type</option>
                                                                    <option value="0" {{ $val->visitor_type == 0 ? 'selected' : '' }}>OFFICE EMPLOYEE</option>
                                                                    <option value="1" {{ $val->visitor_type == 1 ? 'selected' : '' }}>VISITOR</option>
                                                                </select>
                                                            </td>
                                                            <td class="item-desc-cell">
                                                                <div class="" style="{{ $val->visitor_type == 0 ? 'display: block;' : 'display: none;' }}">
                                                                    <select class="form-select js-example-matcher-start" name="visitor_name[]" disabled>
                                                                        <option value="" disabled>Select Employee</option>
                                                                        @foreach ($empDtls as $value)
                                                                            <option value="{{ $value->id }}" {{ $val->visitor_name == $value->id ? 'selected' : '' }}>{{ $value->fullname }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <input type="text" class="form-control form-control-sm visitorInput" name="visitor_name[]" placeholder="Enter Visitor Name" value="{{ $val->visitor_type == 1 ? $val->visitor_name : '' }}" style="{{ $val->visitor_type == 1 ? 'display: block;' : 'display: none;' }}"  disabled>
                                                            </td>
                                                            <td>
                                                                <input class="form-control form-control-sm" type="number" name="visitor_phone[]" placeholder="Enter Phone No." value="{{ isset($val->visitor_phone) ? $val->visitor_phone : '' }}" disabled readonly>
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
                                                    @else
                                                        <tr id="itemRow1">
                                                            <td class="text-center">1</td>
                                                            <td>
                                                                <select class="form-select js-example-matcher-start visitorType" name="visitor_type[]" required onchange="handlevisitorTypeChange(this)">
                                                                    <option value="" selected disabled>Select Visitor Type</option>
                                                                    <option value="0">OFFICE EMPLOYEE</option>
                                                                    <option value="1">VISITOR</option>
                                                                </select>
                                                            </td>
                                                            <td class="item-desc-cell">
                                                                <div class="visitorDropdown">
                                                                    <select class="form-select js-example-matcher-start employeeDropdown" name="visitor_name[]" onchange="getEmpDetails(this.value, this)">
                                                                        <option value="" selected disabled>Select Employee</option>
                                                                        @foreach ($empDtls as $value)
                                                                            <option value="{{ $value->id }}">{{ $value->fullname }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <input type="text" class="form-control form-control-sm visitorInput" name="visitor_name[]" placeholder="Enter Visitor Name" oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '');" style="display: none;">
                                                            </td>
                                                            <td>
                                                                <input class="form-control form-control-sm" type="number" name="visitor_phone[]" placeholder="Enter Phone No." value="" maxlength="10" pattern="\d{10}" oninput="this.value=this.value.replace(/\D/g,'').slice(0,10)" required readonly>
                                                            </td>
                                                            <td>
                                                                <textarea name="visitor_address[]" class="form-control form-control-sm" cols="30" rows="1" placeholder="Enter Your Address" required></textarea>
                                                            </td>
                                                            <td>
                                                                <textarea name="visitor_purpose[]" class="form-control form-control-sm" cols="30" rows="1" placeholder="Enter Purpose to Visit" required></textarea>
                                                            </td>
                                                            <td>
                                                                <input class="form-control form-control-sm" type="text" name="meet_prsn[]" placeholder="Enter Meet Person" value="" required>
                                                            </td>
                                                            <td>
                                                                <a href="javascript:;" id="visitorgatepassAppend" onclick="visitorgatepassAppend(1)" class="btn btn-success btn-sm mt-2"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    @if (isset($edit->id) && $edit->id != '')
                                        <div class="row">
                                            <div class="col-sm-4 form-group">
                                                <label for="intime">In Time <span class="text-danger fw-bolder">*</span></label>
                                                <input class="form-control form-control-sm" id="intime" type="text" name="request_in_time" placeholder="Request In Time" value="{{ isset($edit->request_in_time) && $edit->request_in_time != '' ? date('d-m-Y h:i A' , strtotime($edit->request_in_time)) : '' }}"
												data-intime="{{ isset($edit->request_in_time) && $edit->request_in_time != '' ? date('Y-m-d\TH:i', strtotime($edit->request_in_time)) : '' }}" disabled>
                                            </div>
                                            <div class="col-sm-4 form-group">
                                                <label for="outtime">Expected Out Time <span class="text-danger fw-bolder">*</span></label>
                                                <input class="form-control form-control-sm" id="outtime" type="text" name="request_out_time" placeholder="Request Out Time" value="{{ isset($edit->request_out_time) && $edit->request_out_time != '' ? date('d-m-Y h:i A' , strtotime($edit->request_out_time)) : '' }}" required disabled>
                                            </div>
                                            <div class="col-sm-4 form-group">
                                                <label for="actouttime">Actual Out Time <span class="text-danger fw-bolder">*</span></label>
                                                <input class="form-control form-control-sm" id="actouttime" type="datetime-local" name="request_out_time" placeholder="Request Out Time" value="" required>
                                            </div>
                                            <div class="col-sm-4 form-group">
                                                <label>Person With Vehicle <span class="text-danger fw-bolder">*</span></label>
                                                <select class="form-select form-select-sm js-example-matcher-start" name="prsn_vehicle" id="prsn_vehicle" required disabled>
                                                    <option value="" disabled selected>Select Option</option>
                                                    <option value="1" {{ isset($edit->prsn_vehicle) && $edit->prsn_vehicle == 1 ? 'selected' : '' }}>Yes</option>
                                                    <option value="0" {{ isset($edit->prsn_vehicle) && $edit->prsn_vehicle == 0 ? 'selected' : '' }}>No</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-4 form-group" id="vehicle_type_container" style="display: none;">
                                                <label for="vehicle_type">Vehicle Type <span class="text-danger fw-bolder">*</span></label>
                                                <select class="form-select form-select-sm js-example-matcher-start" name="vehicle_type" id="vehicle_type" disabled>
                                                    <option value="" disabled selected>Select Type</option>
                                                    @foreach ($vehicle_types as $vehicle_type)
                                                        <option value="{{ $vehicle_type->id }}" {{ isset($edit->vehicle_type) && $edit->vehicle_type == $vehicle_type->id ? 'selected' : '' }}>{{ $vehicle_type->mstr_type_value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-4 form-group" id="vehicle_no_container" style="display: none;">
                                                <label>Vehicle No. <span class="text-danger fw-bolder">*</span></label>
                                                <input class="form-control form-control-sm" type="text" name="vehicle_no" placeholder="Enter Vehicle No." id="vehicle_no" value="{{ isset($edit->vehicle_no) && $edit->vehicle_no != '' ? $edit->vehicle_no : '' }}" required disabled>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-2 form-group">
                                                <label>Security Guard Name <span class="text-danger fw-bolder" id="sec_guard_req">*</span></label>
                                                <input class="form-control form-control-sm" type="text" name="out_sec_guard_name" placeholder="Enter Guard Name" value="" id="sec_guard" oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '');" required>
                                            </div>
                                            <div class="col-sm-2 form-group">
                                                <label>Security Ph No <span class="text-danger fw-bolder" id="sec_guard_req">*</span></label>
                                                <input class="form-control form-control-sm" type="text" name="out_sec_guard_phone" placeholder="Security Ph No" value="" id="sec_guard_phone" maxlength="10" pattern="\d{10}" oninput="this.value=this.value.replace(/\D/g,'').slice(0,10)" required>
                                                <small id="driverNumberError" style="color: red;"></small>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label>IN Visit Purpose <span class="text-danger fw-bolder" id="sec_guard_req">*</span></label>
                                                <textarea class="form-control form-control-sm" name="visit_purpose" placeholder="Enter Visit Purpose" cols="30" rows="2" disabled>{{ isset($edit->visit_purpose) && $edit->visit_purpose != '' ? $edit->visit_purpose : '' }}</textarea>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label>OUT Visit Purpose <span class="text-danger fw-bolder" id="out_visit_purpose">*</span></label>
                                                <textarea class="form-control form-control-sm" name="out_visit_purpose" placeholder="Enter Visit Purpose" cols="30" rows="2" required></textarea>
                                            </div>
                                            <div class="col-sm-2 form-group">
                                                <label>Whom To Meet <span class="text-danger fw-bolder">*</span></label>
                                                <input class="form-control form-control-sm" type="text" name="visitor_meet_prsn" placeholder="Enter meet person" value="{{ isset($edit->meet_prsn) && $edit->meet_prsn != '' ? $edit->meet_prsn : '' }}" id="meet_prsn" required disabled>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row">
                                            <div class="col-sm-2 form-group">
                                                <label>In Time <span class="text-danger fw-bolder">*</span></label>
                                                <input class="form-control form-control-sm" id="intime" type="datetime-local" name="request_in_time" placeholder="Request In Time" value="{{ isset($edit->request_in_time) && $edit->request_in_time != '' ? $edit->request_in_time : '' }}" required>
                                            </div>
                                            <div class="col-sm-2 form-group">
                                                <label>Expected Out Time <span class="text-danger fw-bolder">*</span></label>
                                                <input class="form-control form-control-sm" id="outtime" type="datetime-local" name="request_out_time" placeholder="Request Out Time" value="{{ isset($edit->request_out_time) && $edit->request_out_time != '' ? $edit->request_out_time : '' }}" required>
                                            </div>
                                            <div class="col-sm-2 form-group">
                                                <label>Person With Vehicle <span class="text-danger fw-bolder">*</span></label>
                                                <select class="form-select form-select-sm js-example-matcher-start" name="prsn_vehicle" id="prsn_vehicle" required>
                                                    <option value="" disabled selected>Select Option</option>
                                                    <option value="1" {{ isset($edit->prsn_vehicle) && $edit->prsn_vehicle == 1 ? 'selected' : '' }}>Yes</option>
                                                    <option value="0" {{ isset($edit->prsn_vehicle) && $edit->prsn_vehicle == 0 ? 'selected' : '' }}>No</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-3 form-group" id="vehicle_type_container" style="display: none;">
                                                <label for="vehicle_type">Vehicle Type <span class="text-danger fw-bolder">*</span></label>
                                                <select class="form-select form-select-sm js-example-matcher-start" name="vehicle_type" id="vehicle_type" required>
                                                    <option value="" disabled selected>Select Type</option>
                                                    @foreach ($vehicle_types as $vehicle_type)
                                                        <option value="{{ $vehicle_type->id }}" {{ isset($edit->vehicle_type) && $edit->vehicle_type == $vehicle_type->id ? 'selected' : '' }}>{{ $vehicle_type->mstr_type_value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-3 form-group" id="vehicle_no_container" style="display: none;">
                                                <label>Vehicle No. <span class="text-danger fw-bolder">*</span></label>
                                                <input class="form-control form-control-sm" type="text" name="vehicle_no" placeholder="Enter Vehicle No." id="vehicle_no" value="{{ isset($edit->vehicle_no) && $edit->vehicle_no != '' ? $edit->vehicle_no : '' }}" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-3 form-group">
                                                <label>Security Guard Name <span class="text-danger fw-bolder" id="sec_guard_req">*</span></label>
                                                <input class="form-control form-control-sm" type="text" name="sec_guard_name" placeholder="Enter Guard Name" oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '');" value="" id="sec_guard" required>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label>Security Ph No <span class="text-danger fw-bolder" id="sec_guard_req">*</span></label>
                                                <input class="form-control form-control-sm" type="text" name="sec_guard_phone" placeholder="Security Ph No" value="" id="sec_guard_phone" maxlength="10" pattern="\d{10}" oninput="this.value=this.value.replace(/\D/g,'').slice(0,10)" required>
                                                <small id="sec_guard_phoneError" style="color: red;"></small>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label>IN Visit Purpose <span class="text-danger fw-bolder" id="sec_guard_req">*</span></label>
                                                <textarea class="form-control form-control-sm" name="visit_purpose" placeholder="Enter Visit Purpose" cols="30" rows="2" required></textarea>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label>Whom To Meet <span class="text-danger fw-bolder">*</span></label>
                                                <input class="form-control form-control-sm" type="text" name="visitor_meet_prsn" placeholder="Enter meet person" value="{{ isset($edit->meet_prsn) && $edit->meet_prsn != '' ? $edit->meet_prsn : '' }}" id="meet_prsn" required>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="row">
                                        <div class="col-sm-12 form-group">
                                            <label for="State">Remarks:</label>
                                            <textarea class="form-control" name="remarks" placeholder="Remarks" cols="30" rows="3" required>{{isset($edit->remarks) && $edit->remarks!=''?$edit->remarks:''}}</textarea>
                                        </div>
                                    </div>
                                    <div style="overflow:auto;" class="mt-3">
                                        <div style="float:right;">
                                            <a href="" class="btn btn1 float-right" style="margin: 5px; display:{{isset($edit->id) && $edit->id != ''?'none':'block'}}">Clear All</a>
                                            <button type="submit" class="btn btn1 float-right" style="margin: 5px;">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
</section>
@endsection
@push('custom-scripts')
    <script>
        activeclass(7, 4);
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
            $('#Manunit').change(function() {
                $('#org_name').val('');
                var ManunitId = $(this).val();
                if (ManunitId) {
                    $.ajax({
                        url: "{{ url('GatePass/get-plantnamedetails') }}" + '/' + ManunitId,
                        type: 'GET',
                        data: {
                            ManunitId: ManunitId
                        },
                        success: function(response) {
                            $('#Plant_Name').empty();

                            if (response.length > 0) {
                                $('#Plant_Name').append(
                                    '<option value="" selected disabled>Select Sub Project</option>');
                                $.each(response, function(index, plantdetails) {
                                    var option = $('<option>');
                                    option.val(plantdetails.id);
                                    option.text(plantdetails.spname);
                                    $('#Plant_Name').append(option);
                                });
                            } else {
                                $('#Plant_Name').append(
                                    '<option value="" selected disabled>No Sub Project Found</option>'
                                );
                            }
                        },
                        error: function() {
                            $('#Plant_Name').empty().append(
                                '<option value="" selected disabled>Error fetching data</option>'
                            );
                        }
                    });
                }
            });
        });

    </script>
    <script>
        const outTimeInput = document.getElementById("outtime");
        const inTimeInput = document.getElementById("intime");
        const actouttimeInput = document.getElementById("actouttime");

        outTimeInput.addEventListener("change", () => {
            const inTime = new Date(`${inTimeInput.value}`);
            const outTime = new Date(`${outTimeInput.value}`);
            if (inTime > outTime) {
                alert('Expected Out Time must be greater than In Time');
                outTimeInput.value = "";
            }
        });

        inTimeInput.addEventListener("change", () => {
            const inTime = new Date(`${inTimeInput.value}`);
            const outTime = new Date(`${outTimeInput.value}`);
            if (inTime > outTime) {
                alert('In Time must be smaller than Out Time');
                inTimeInput.value = "";
            }
        });

		actouttimeInput.addEventListener("change", () => {
			const inTime = new Date(inTimeInput.dataset.intime);
			const actouttime = new Date(actouttimeInput.value);
			if (inTime > actouttime) {
				alert('Actual Out Time must be greater than In Time');
				actouttimeInput.value = "";
			}
		});
    </script>
    <script>
        $(document).ready(function() {
            var prsn_vehicle = $('#prsn_vehicle').val();
            toggleVehicle(prsn_vehicle);

            $('#prsn_vehicle').change(function() {
                var prsn_vehicle = $(this).val();
                toggleVehicle(prsn_vehicle);
            });

            function toggleVehicle(prsn_vehicle) {
                if (prsn_vehicle == 0) {
                    $('#vehicle_type_container').hide();
                    $('#vehicle_no_container').hide();
                    $('#vehicle_type').prop('required', false);
                    $('#vehicle_no').prop('required', false);
                } else {
                    $('#vehicle_type_container').show();
                    $('#vehicle_no_container').show();
                    $('#vehicle_type').prop('required', true);
                    $('#vehicle_no').prop('required', true);
                }
            }
        });
    </script>
    <script>
        function AppendSelect2() {
            $('.form-select').select2();
        }

        function updateSerialNumbers() {
            $('#VisitorGatepassFields tr').each(function(index) {
                $(this).find('td:first').text(index + 1);
            });
        }

        function updateEmployeeOptions() {
            const selectedEmployees = new Set();

            $('.employeeDropdown').each(function() {
                const selectedId = $(this).val();
                if (selectedId) {
                    selectedEmployees.add(selectedId);
                }
            });

            $('.employeeDropdown').each(function() {
                const currentValue = $(this).val();
                $(this).find('option').each(function() {
                    if (this.value && this.value !== '') {
                        $(this).prop('disabled',
                            selectedEmployees.has(this.value) && this.value !== currentValue
                        );
                    }
                });
            });
        }

        $(document).ready(function() {
            $('.visitorType').each(function() {
                handlevisitorTypeChange(this);
            });
            updateEmployeeOptions();
            $(document).on('change', '.employeeDropdown', function() {
                updateEmployeeOptions();
            });

            $('.visitorType').each(function() {
                if ($(this).val() === "0") {
                    const row = $(this).closest('tr');
                    const visitorPhoneInput = row.find('input[name="visitor_phone[]"]')[0];
                    if (visitorPhoneInput) {
                        visitorPhoneInput.readOnly = true;
                    }
                }
            });
        });

        function handlevisitorTypeChange(selectElement) {
            const row = selectElement.closest('tr');
            const visitorDropdown = row.querySelector('.visitorDropdown');
            const visitorInput = row.querySelector('.visitorInput');
            const visitorPhoneInput = row.querySelector('input[name="visitor_phone[]"]');
            const visitorAddressInput = row.querySelector('textarea[name="visitor_address[]"]');
            const visitorPurposeInput = row.querySelector('textarea[name="visitor_purpose[]"]');
            const meetPersonInput = row.querySelector('input[name="meet_prsn[]"]');
            const employeeDropdown = row.querySelector('.employeeDropdown');

            if (selectElement.value === "0") {
                visitorDropdown.style.display = 'block';
                visitorInput.style.display = 'none';
                employeeDropdown.disabled = false;
                employeeDropdown.required = true;
                visitorInput.required = false;
                visitorPhoneInput.readOnly = true;
                visitorPhoneInput.value = '';
            } else if (selectElement.value === "1") {
                visitorDropdown.style.display = 'none';
                visitorInput.style.display = 'block';
                visitorInput.required = true;
                employeeDropdown.required = false;
                employeeDropdown.disabled = true;
                visitorPhoneInput.readOnly = false;
                visitorPhoneInput.value = '';
            } else {
                visitorDropdown.style.display = 'block';
                visitorInput.style.display = 'none';
                employeeDropdown.disabled = true;
                employeeDropdown.required = false;
                visitorInput.required = false;
            }

            const isDisabled = !selectElement.value;
            visitorPhoneInput.disabled = isDisabled;
            visitorAddressInput.disabled = isDisabled;
            visitorPurposeInput.disabled = isDisabled;
            meetPersonInput.disabled = isDisabled;
        }

        function visitorgatepassAppend(i) {
            i++;

            $('#VisitorGatepassFields').append(`
                <tr id="visitorgatepassRemove${i}">
                    <td class="text-center">${i}</td>
                    <td>
                        <select class="form-select visitorType" name="visitor_type[]" required onchange="handlevisitorTypeChange(this)">
                            <option value="" selected disabled>Select Visitor Type</option>
                            <option value="0">OFFICE EMPLOYEE</option>
                            <option value="1">VISITOR</option>
                        </select>
                    </td>
                    <td class="item-desc-cell">
                        <div class="visitorDropdown" style="display: none;">
                            <select class="form-select employeeDropdown" name="visitor_name[]" onchange="getEmpDetails(this.value, this)">
                                <option value="" selected disabled>Select Employee</option>
                                @foreach ($empDtls as $value)
                                    <option value="{{ $value->id }}">{{ $value->fullname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="text" class="form-control form-control-sm visitorInput" name="visitor_name[]" placeholder="Enter Visitor Name" oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '');" style="display: none;">
                    </td>
                    <td>
                        <input class="form-control form-control-sm" type="number" name="visitor_phone[]" maxlength="10" pattern="\d{10}" oninput="this.value=this.value.replace(/\D/g,'').slice(0,10)" placeholder="Enter Phone No." value="" required readonly>
                    </td>
                    <td>
                        <textarea name="visitor_address[]" class="form-control form-control-sm" cols="30" rows="1" placeholder="Enter Your Address" disabled required></textarea>
                    </td>
                    <td>
                        <textarea name="visitor_purpose[]" class="form-control form-control-sm" cols="30" rows="1" placeholder="Enter Purpose to Visit" disabled required></textarea>
                    </td>
                    <td>
                        <input class="form-control form-control-sm" type="text" name="meet_prsn[]" placeholder="Enter Meet Person" value="" disabled required>
                    </td>
                    <td>
                        <a href="javascript:;" onclick="visitorgatepassRemove(${i})" class="btn btn-danger btn-sm mt-2">X</a>
                    </td>
                </tr>
            `);

            $("#visitorgatepassAppend").attr("onclick", `visitorgatepassAppend(${i})`);
            AppendSelect2();
            updateSerialNumbers();
            updateEmployeeOptions();
            $('.employeeDropdown').each(function() {
                const selectedEmpId = $(this).val();
                if (selectedEmpId) {
                    disableSelectedEmployee(selectedEmpId);
                }
            });

            $(`#visitorgatepassRemove${i} .visitorType`).trigger('change');
        }

        function disableSelectedEmployee(empId) {
            $('.employeeDropdown option').each(function () {
                if ($(this).val() === empId) {
                    $(this).prop('disabled', true);
                }
            });
        }

        function enableSelectedEmployee(empId) {
            let isStillSelected = false;

            $('.employeeDropdown').each(function () {
                if ($(this).val() === empId) {
                    isStillSelected = true;
                }
            });

            if (!isStillSelected) {
                $('.employeeDropdown option').each(function () {
                    if ($(this).val() === empId) {
                        $(this).prop('disabled', false);
                    }
                });
            }
        }

        function getEmpDetails(empId, selectElement) {
            const row = selectElement.closest('tr');
            const visitorPhoneInput = row.querySelector('input[name="visitor_phone[]"]');
            const previousValue = $(selectElement).data('previous-value');
            $(selectElement).data('previous-value', empId);

            $.ajax({
                url: '{{ url("GatePass/get-employee-details") }}/' + empId,
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        visitorPhoneInput.value = response.data.phone;
                        visitorPhoneInput.readOnly = true;
                        updateEmployeeOptions();
                    }
                },
                error: function(xhr) {
                    console.error('Error fetching employee details:', xhr);
                    $(selectElement).val(previousValue).trigger('change');
                    updateEmployeeOptions();
                }
            });
        }

        function visitorgatepassRemove(id) {
            const row = $("#visitorgatepassRemove" + id);
            const selectedEmpId = row.find(".employeeDropdown").val();

            if (selectedEmpId) {
                enableSelectedEmployee(selectedEmpId);
            }

            row.remove();
            updateSerialNumbers();
            updateEmployeeOptions();
        }
    </script>
@endpush