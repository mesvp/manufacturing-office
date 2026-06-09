@extends('layout.main')
@section('main-container')
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
<title>OUT Employee GatePass Update View Details Page</title>
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
                                            <h5>Employee Gate Pass Details (<b class="text-primary">{{ $edit->out_request_no }}</b>)</h5>
                                        </div>
                                        <div class="col">
                                            <label class="fw-bold" for="">Inputer Name : {{ isset($edit->out_request_by) && $edit->out_request_by != '' ? $edit->out_request_by : auth()->user()->fullname }}</label>
                                        </div>
                                        <div class="col">
                                            <label class="fw-bold" for="">Date & Time : <span id="clock"></span></label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <br>
                            <form id="submitform" action="{{ url('GatePass/updateEmployeeGatepass/'. $edit->id.'/out') }}" method="POST">
                                @csrf
                                <div class="tab1">
                                    <input class="form-control" type="hidden" name="req_no" value="{{ isset($edit->out_request_no) && $edit->out_request_no != '' ? $edit->out_request_no : '' }}">
                                    <input class="form-control" type="hidden" name="edit" value="{{ isset($edit->id) && $edit->id != '' ? $edit->id : '' }}">
                                    <div class="row">
                                        <div class="col-sm-3 form-group">
                                            <label class="fw-bold">Created By</label>
                                            <input readonly class="form-control form-control-sm" type="text" id="requested_by" name="request_by" placeholder="Request By." value="{{ isset($edit->out_request_by) && $edit->out_request_by != '' ? $edit->out_request_by : auth()->user()->fullname }}" required>
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
                                        <div class="col-sm-4 form-group">
                                            <label class="fw-bold">In Time</label>
                                            <input class="form-control form-control-sm" id="intime" type="text" name="request_in_time" placeholder="Request In Time" value="{{ isset($edit->request_in_time) && !empty($edit->request_in_time) ? (DateTime::createFromFormat('d-m-Y h:i A', $edit->request_in_time) ? DateTime::createFromFormat('d-m-Y h:i A', $edit->request_in_time)->format('Y-m-d\TH:i') : $edit->request_in_time) : '' }}" disabled>
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label class="fw-bold">Expected Out Time</label>
                                            <input class="form-control form-control-sm" id="outtime" type="text" name="request_out_time" placeholder="Request Out Time" value="{{ isset($edit->request_out_time) && !empty($edit->request_out_time) ? (DateTime::createFromFormat('d-m-Y h:i A', $edit->request_out_time) ? DateTime::createFromFormat('d-m-Y h:i A', $edit->request_out_time)->format('Y-m-d\TH:i') : $edit->request_out_time) : '' }}" disabled>
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label class="fw-bold">Actual Out Time *</label>
                                            <input class="form-control form-control-sm" id="actouttime" type="datetime-local" name="actual_out_time" placeholder="Request Out Time" value="{{ isset($edit->actual_out_time) && !empty($edit->actual_out_time) ? (DateTime::createFromFormat('d-m-Y h:i A', $edit->actual_out_time) ? DateTime::createFromFormat('d-m-Y h:i A', $edit->actual_out_time)->format('Y-m-d\TH:i') : $edit->actual_out_time) : '' }}" required>
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label class="fw-bold">Person With Vehicle</label>
                                            <select class="form-select form-select-sm js-example-matcher-start" name="prsn_vehicle" id="prsn_vehicle" required disabled>
                                                <option value="" disabled selected>Select Option</option>
                                                <option value="1" {{ isset($edit->prsn_vehicle) && $edit->prsn_vehicle == 1 ? 'selected' : '' }}>Yes</option>
                                                <option value="0" {{ isset($edit->prsn_vehicle) && $edit->prsn_vehicle == 0 ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4 form-group" id="vehicle_type_container">
                                            <label class="fw-bold">Vehicle Type</label>
                                            <select class="form-select form-select-sm js-example-matcher-start" name="vehicle_type" id="vehicle_type" disabled>
                                                <option value="" disabled selected>Select Type</option>
                                                @foreach ($vehicle_types as $vehicle_type)
                                                    <option value="{{ $vehicle_type->id }}" {{ isset($edit->vehicle_type) && $edit->vehicle_type == $vehicle_type->id ? 'selected' : '' }}>{{ $vehicle_type->mstr_type_value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-4 form-group" id="vehicle_no_container">
                                            <label class="fw-bold">Vehicle No.</label>
                                            <input class="form-control form-control-sm" type="text" name="vehicle_no" placeholder="Enter Vehicle No." id="vehicle_no" value="{{ isset($edit->vehicle_no) && $edit->vehicle_no != '' ? $edit->vehicle_no : '' }}" disabled>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-2 form-group">
                                            <label class="fw-bold">Security Guard Name * <span class="text-danger fw-bolder" id="sec_guard_req"></span></label>
                                            <input class="form-control form-control-sm" type="text" name="sec_guard_name" placeholder="Enter Guard Name" value="{{ isset($edit->out_sec_guard) && $edit->out_sec_guard != '' ? $edit->out_sec_guard : '' }}" id="sec_guard" oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '');" required>
                                        </div>
                                        <div class="col-sm-2 form-group">
                                            <label class="fw-bold">Security Ph No * <span class="text-danger fw-bolder" id="sec_guard_req"></span></label>
                                            <input class="form-control form-control-sm" type="text" name="sec_guard_phone" placeholder="Security Ph No" maxlength="10" pattern="\d{10}" oninput="this.value=this.value.replace(/\D/g,'').slice(0,10)" value="{{ isset($edit->out_sec_guard_no) && $edit->out_sec_guard_no != '' ? $edit->out_sec_guard_no : '' }}" id="sec_guard_phone" required>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label class="fw-bold">IN Visit Purpose <span class="text-danger fw-bolder" id="sec_guard_req"></span></label>
                                            <textarea class="form-control form-control-sm" name="visit_purpose" placeholder="Enter Visit Purpose" cols="30" rows="2" disabled>{{ isset($edit->visit_purpose) && $edit->visit_purpose != '' ? $edit->visit_purpose : '' }}</textarea>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label class="fw-bold">OUT Visit Purpose * <span class="fw-bolder" id="out_visit_purpose"></span></label>
                                            <textarea class="form-control form-control-sm" name="out_visit_purpose" placeholder="Enter Visit Purpose" cols="30" rows="2" required>{{ isset($edit->out_visit_purpose) && $edit->out_visit_purpose != '' ? $edit->out_visit_purpose : '' }}</textarea>
                                        </div>
                                        <div class="col-sm-2 form-group">
                                            <label class="fw-bold">Whom To Meet <span class="text-danger fw-bolder" id="meet_prsn"></span></label>
                                            <input class="form-control form-control-sm" type="text" name="meet_prsn" placeholder="Enter meet person" value="{{ isset($edit->meet_prsn) && $edit->meet_prsn != '' ? $edit->meet_prsn : '' }}" id="meet_prsn" disabled>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12 form-group">
                                            <label class="fw-bold" for="State">Remarks *:</label>
                                            <textarea class="form-control" name="out_remarks" placeholder="Enter Remarks" cols="30" rows="4" required>{{ isset($edit->out_remarks) && $edit->out_remarks != '' ? $edit->out_remarks : '' }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="text-end">
                                                <button type="submit" class="btn btn1" style="">UPDATE</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
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
</script>
<script>
	const inTimeInput = document.getElementById("intime");
	const actouttimeInput = document.getElementById("actouttime");

	actouttimeInput.addEventListener("change", () => {
		const inTime = new Date(`${inTimeInput.value}`);
		const actouttime = new Date(`${actouttimeInput.value}`);
		if (inTime > actouttime) {
			alert('Actual Out Time must be greater than In Time');
			actouttimeInput.value = "";
		}
	});
</script>
@endpush