@extends('layout.main')
@section('main-container')
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <title>Employee Gate Pass Form Details</title>
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
                        <div class="tabs">
                            <div class="row">
                                <div class="col-4">
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col">
                                            <h5>Employee Gate Pass Form Details </h5>
                                        </div>
                                        <div class="col">
                                            <label for="">Inputer Name : {{ auth()->user()->fullname }}</label>
                                        </div>
                                        <div class="col">
                                            <label for="">Date & Time : <span id="clock"></span></label>
                                        </div>
                                        <div class="col addbtn extra">
                                            <a href="{{ url('GatePass/List') }}" class="btn btn-info"> <i class="fa fa-arrow-left"></i> BACK</a>
                                            <a href="{{ url('GatePass/List') }}" class="btn btn-info" style="margin-left:10px"> <i class="fa fa-home"></i> Home</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="tab1">
                                <form id="submitform" action="{{ url('GatePass/employee-store') }}" method="POST">
                                    @csrf
                                    <input class="form-control" type="hidden" name="edit" value="{{ isset($edit->id) && $edit->id != '' ? $edit->id : '' }}">

                                    <div class="row">
                                        <div class="col-sm-3 form-group">
                                            <label for="requested_by">Created By <span class="text-danger fw-bolder">*</span></label>
                                            <input readonly class="form-control form-control-sm" type="text" id="requested_by" name="request_by" placeholder="Request By." value="{{ auth()->user()->fullname }}" required>
                                        </div>
                                        @if (isset($edit->id) && $edit->id != '')
                                            <div class="col-sm-3 form-group">
                                                <label for="Manunit">Cost Center <span class="text-danger fw-bolder">*</span></label>
                                                <select class="form-select form-select-sm js-example-matcher-start" name="cost_center" id="Manunit" required disabled>
                                                    <option value="" disabled selected>Select Project</option>
                                                    @foreach ($Projects as $val)
                                                        <option value="{{ $val->id }}" {{ isset($edit->project_id) && $edit->project_id == $val->id ? 'selected' : '' }}>{{ $val->pname }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label for="Plant_Name">Sub Cost Center*</label>
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
                                                <label for="Plant_Name">Sub Cost Center <span class="text-danger fw-bolder">*</span></label>
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
                                                <label for="organisation_name" >Organisation Name <span class="text-danger fw-bolder">*</span></label>
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
                                        <div class="col-lg-12 col-md-12"></div>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="th-sm text-center">SL. No.</th>
                                                        <th class="th-sm">EMPLOYEE SHIFT <span class="text-danger fw-bolder">*</span></th>
                                                        <th class="th-sm">EMPLOYEE NAME <span class="text-danger fw-bolder">*</span></th>
                                                        <th class="th-sm">EMPLOYEE CODE <span class="text-danger fw-bolder">*</span></th>
                                                        <th class="th-sm">DEPARTMENT <span class="text-danger fw-bolder">*</span></th>
                                                        <th class="th-sm">PHONE NO <span class="text-danger fw-bolder">*</span></th>
                                                        @if (!isset($edit->id))
                                                            <th class="th-sm">ACTION</th>
                                                        @endif
                                                    </tr>
                                                </thead>
                                                <tbody id="EmployeeGatepassFields">
                                                    @if (isset($edit->id) && $edit->id != '')
                                                    @php
                                                        $EmployeeGatepassDetails = DB::table('gatepass_employee_details')->where('request_no', $edit->request_no)->get();
                                                    @endphp
                                                        @foreach ($EmployeeGatepassDetails as $key => $val)
                                                            <tr id="itemRow{{ $key + 1 }}">
                                                                <td class="text-center">{{ $key + 1 }}</td>
                                                                <td>
                                                                    <input class="form-control form-control-sm" type="text" name="emp_shift[]" placeholder="Enter Employee shift" value="{{ $val->emp_shift }}" disabled>
                                                                </td>
                                                                <td class="item-desc-cell">
                                                                    <input class="form-control form-control-sm" type="text" name="emp_name[]" placeholder="Enter Employee name" value="{{ $val->emp_name }}" disabled>
                                                                </td>
                                                                <td>
                                                                    <input class="form-control form-control-sm" type="text" name="emp_code[]" placeholder="Enter Employee code" value="{{ $val->emp_code }}" disabled>
                                                                </td>
                                                                <td>
                                                                    <input class="form-control form-control-sm" type="text" name="emp_dept[]" placeholder="Enter Department" value="{{ $val->emp_dept }}" disabled>
                                                                </td>
                                                                <td>
                                                                    <input class="form-control form-control-sm" type="number" name="emp_phone[]" placeholder="Enter Phone No." value="{{ $val->emp_phone }}" disabled>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                    <tr id="itemRow1">
                                                        <td class="text-center">1</td>
                                                        <td>
                                                            <select class="form-select form-select-sm js-example-matcher-start" name="emp_shift[]" required>
                                                                <option value="" selected disabled>Select Shift</option>
                                                                @foreach($Shifts as $val)
                                                                    <option value="{{$val->shift}}" {{isset($edit->shift)?'selected':''}}>{{$val->shift}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td class="item-desc-cell">
                                                            <select class="form-select form-select-sm js-example-matcher-start emp-select" name="emp_id[]" onchange="getEmpDetails(this.value, this)" required>
                                                                <option value="" selected disabled>Select Employee</option>
                                                                @foreach($employees as $val)
                                                                    <option value="{{$val->id}}" {{isset($edit->id)?'selected':''}}>{{$val->fullname}}</option>
                                                                @endforeach
                                                            </select>
                                                            <input type="hidden" name="emp_name[]" value="">
                                                        </td>
                                                        <td>
                                                            <input class="form-control form-control-sm" type="text" name="emp_code[]" placeholder="Enter Employee code" value="" required readonly>
                                                        </td>
                                                        <td>
                                                            <input class="form-control form-control-sm" type="text" name="emp_dept[]" placeholder="Enter Department" value="" required readonly>
                                                        </td>
                                                        <td>
                                                            <input class="form-control form-control-sm" type="number" name="emp_phone[]" placeholder="Enter Phone No." value="" required readonly>
                                                        </td>
                                                        <td>
                                                            <a href="javascript:;" id="empgatepassAppend" onclick="empgatepassAppend(1)" class="btn btn-success btn-sm mt-2"><i class="fa fa-plus" aria-hidden="true"></i></a>
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
                                                <input class="form-control form-control-sm" id="intime" type="text" name="request_in_time"	placeholder="Request In Time" value="{{ isset($edit->request_in_time) && $edit->request_in_time != '' ? date('d-m-Y h:i A' , strtotime($edit->request_in_time)) : '' }}"
												data-intime="{{ isset($edit->request_in_time) && $edit->request_in_time != '' ? date('Y-m-d\TH:i', strtotime($edit->request_in_time)) : '' }}"
												 disabled>
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
                                                <label for="prsn_vehicle">Person With Vehicle <span class="text-danger fw-bolder">*</span></label>
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
                                                <label for="vehicle_no">Vehicle No. <span class="text-danger fw-bolder">*</span></label>
                                                <input class="form-control form-control-sm" type="text" name="vehicle_no" placeholder="Enter Vehicle No." id="vehicle_no" value="{{ isset($edit->vehicle_no) && $edit->vehicle_no != '' ? $edit->vehicle_no : '' }}" required disabled>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-2 form-group">
                                                <label for="sec_guard">Security Guard Name <span class="text-danger fw-bolder">*</span></label>
                                                <input class="form-control form-control-sm" type="text" name="out_sec_guard_name" placeholder="Enter Guard Name" value="" id="sec_guard" oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '');" required>
                                            </div>
                                            <div class="col-sm-2 form-group">
                                                <label for="sec_guard_phone">Security Ph No <span class="text-danger fw-bolder">*</span></label>
                                                <input class="form-control form-control-sm" type="text" name="out_sec_guard_phone" placeholder="Security Ph No" value="" id="sec_guard_phone"  maxlength="10" pattern="\d{10}" oninput="this.value=this.value.replace(/\D/g,'').slice(0,10)" required>
                                                <small id="driverNumberError" style="color: red;"></small>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label for="visit_purpose">IN Visit Purpose <span class="text-danger fw-bolder">*</span></label>
                                                <textarea class="form-control form-control-sm" name="visit_purpose" placeholder="Enter Visit Purpose" id="visit_purpose" cols="30" rows="2" disabled>{{ isset($edit->visit_purpose) && $edit->visit_purpose != '' ? $edit->visit_purpose : '' }}</textarea>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label for="out_visit_purpose">OUT Visit Purpose <span class="text-danger fw-bolder">*</span></label>
                                                <textarea class="form-control form-control-sm" name="out_visit_purpose" placeholder="Enter Visit Purpose" cols="30" rows="2" required></textarea>
                                            </div>
                                            <div class="col-sm-2 form-group">
                                                <label for="meet_prsn">Whom To Meet <span class="text-danger fw-bolder">*</span></label>
                                                <input class="form-control form-control-sm" type="text" name="meet_prsn" placeholder="Enter meet person" value="{{ isset($edit->meet_prsn) && $edit->meet_prsn != '' ? $edit->meet_prsn : '' }}" id="meet_prsn" required disabled>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row">
                                            <div class="col-sm-2 form-group">
                                                <label for="intime">In Time <span class="text-danger fw-bolder">*</span></label>
                                                <input class="form-control form-control-sm" id="intime" type="datetime-local" name="request_in_time" placeholder="Request In Time" value="{{ isset($edit->request_in_time) && $edit->request_in_time != '' ? $edit->request_in_time : '' }}" required>
                                            </div>
                                            <div class="col-sm-2 form-group">
                                                <label for="outtime">Expected Out Time <span class="text-danger fw-bolder">*</span></label>
                                                <input class="form-control form-control-sm" id="outtime" type="datetime-local" name="request_out_time" placeholder="Request Out Time" value="{{ isset($edit->request_out_time) && $edit->request_out_time != '' ? $edit->request_out_time : '' }}" required>
                                            </div>
                                            <div class="col-sm-2 form-group">
                                                <label for="prsn_vehicle">Person With Vehicle <span class="text-danger fw-bolder">*</span></label>
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
                                                <label for="vehicle_no">Vehicle No. <span class="text-danger fw-bolder">*</span></label>
                                                <input class="form-control form-control-sm" type="text" name="vehicle_no" placeholder="Enter Vehicle No." id="vehicle_no" value="{{ isset($edit->vehicle_no) && $edit->vehicle_no != '' ? $edit->vehicle_no : '' }}" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-3 form-group">
                                                <label for="sec_guard">Security Guard Name <span class="text-danger fw-bolder">*</span></label>
                                                <input class="form-control form-control-sm" type="text" name="sec_guard_name" placeholder="Enter Guard Name" value="" id="sec_guard" oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '');" required>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label for="sec_guard_phone">Security Ph No <span class="text-danger fw-bolder">*</span></label>
                                                <input class="form-control form-control-sm" type="text" name="sec_guard_phone" placeholder="Security Ph No" value="" id="sec_guard_phone" maxlength="10" pattern="\d{10}" oninput="this.value=this.value.replace(/\D/g,'').slice(0,10)" required>
                                                <small id="sec_guard_phoneError" style="color: red;"></small>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label for="visit_purpose">IN Visit Purpose <span class="text-danger fw-bolder">*</span></label>
                                                <textarea class="form-control form-control-sm" name="visit_purpose" id="visit_purpose" placeholder="Enter Visit Purpose" cols="30" rows="2" required></textarea>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label for="meet_prsn">Whom To Meet <span class="text-danger fw-bolder">*</span></label>
                                                <input class="form-control form-control-sm" type="text" name="meet_prsn" placeholder="Enter meet person" value="{{ isset($edit->meet_prsn) && $edit->meet_prsn != '' ? $edit->meet_prsn : '' }}" id="meet_prsn" required>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="row">
                                        <div class="col-sm-12 form-group">
                                            <label for="remark">Remarks <span class="text-danger fw-bolder">*</span></label>
                                            <textarea class="form-control" name="remarks" placeholder="Enter Remarks" cols="30" rows="4" id="remark" required></textarea>
                                        </div>
                                    </div>
                                    <div style="overflow:auto;">
                                        <div style="float:right;">
                                            <a href="" class="btn btn1 float-right" style="margin: 5px; display:{{ isset($edit->id) && $edit->id != '' ? 'none' : 'block' }}">Clear All</a>
                                            <button type="submit" class="btn btn1 float-right" style="margin: 5px;">Submit</button>
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
@endsection
@push('custom-scripts')
    <script>
        activeclass(7, 1);
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
        function displayTime() {
            const now = new Date();
            const date = now.toLocaleDateString();
            const time = now.toLocaleTimeString();
            document.getElementById("clock").textContent = time + ', ' + date;
        }

        setInterval(displayTime, 1000);
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
        function AppendSelect2() {
            $('.js-example-matcher-start').select2();
        }

        function empgatepassAppend() {
			const rowCount = $('#EmployeeGatepassFields tr').length;
			const i = rowCount + 1;
			const options = `@foreach($Shifts as $val)
				<option value="{{$val->shift}}" {{isset($edit->shift)?'selected':''}}>{{$val->shift}}</option>
			@endforeach`;

			const employeeOptions = getEmployeeOptions();

			$('#EmployeeGatepassFields').append(`
				<tr id="empgatepassRemove${i}">
					<td class="text-center">${i}</td>
					<td>
						<select class="form-select form-select-sm js-example-matcher-start" name="emp_shift[]" required>
							<option value="" selected disabled>Select Shift</option>
							${options}
						</select>
					</td>
					<td class="item-desc-cell">
						<select class="form-select form-select-sm js-example-matcher-start emp-select" name="emp_id[]" onchange="getEmpDetails(this.value, this)" required>
							<option value="" selected disabled>Select Employee</option>
							${employeeOptions}
						</select>
						<input type="hidden" name="emp_name[]" value="">
					</td>
					<td>
						<input class="form-control form-control-sm" type="text" name="emp_code[]" placeholder="Enter Employee code" value="" required readonly>
					</td>
					<td>
						<input class="form-control form-control-sm" type="text" name="emp_dept[]" placeholder="Enter Department" value="" required readonly>
					</td>
					<td>
						<input class="form-control form-control-sm" type="number" name="emp_phone[]" placeholder="Enter Phone No." value="" required readonly>
					</td>
					<td>
						<a href="javascript:;" onclick="empgatepassRemove(${i})" class="btn btn-danger btn-sm mt-2">X</a>
					</td>
				</tr>
			`);

			AppendSelect2();
			updateEmployeeOptions();
		}

        function getEmployeeOptions() {
            let options = `
                @foreach($employees as $val)
                    <option value="{{$val->id}}" {{isset($edit->id)?'selected':''}}>{{$val->fullname}}</option>
                @endforeach
            `;
            return options;
        }

        function updateEmployeeOptions() {
            let selectedValues = Array.from(document.querySelectorAll('.emp-select')).map(select => select.value);
            document.querySelectorAll('.emp-select').forEach(select => {
                let currentValue = select.value;
                let options = select.querySelectorAll("option");

                options.forEach(option => {
                    if (option.value !== "" && selectedValues.includes(option.value) && option.value !== currentValue) {
                        option.disabled = true;
                    } else {
                        option.disabled = false;
                    }
                });

                select.value = currentValue;
            });
        }

        function empgatepassRemove(id) {
			const removedRow = $("#empgatepassRemove" + id);
			let removedEmpId = removedRow.find('select[name="emp_id[]"]').val();
			if (removedEmpId) {
				document.querySelectorAll('.emp-select option[value="' + removedEmpId + '"]').forEach(option => {
					option.disabled = false;
				});
			}
			removedRow.remove();
			updateEmployeeOptions();
			updateSerialNumbers();
		}
		function updateSerialNumbers() {
			$('#EmployeeGatepassFields tr').each(function(index, row) {
				$(row).find('td:first').text(index + 1);
			});
		}

        function getEmpDetails(empId, selectElement) {
            const row = selectElement.closest('tr');
            const empNameInput = row.querySelector('input[name="emp_name[]"]');
            const empPhoneInput = row.querySelector('input[name="emp_phone[]"]');
            const empCodeInput = row.querySelector('input[name="emp_code[]"]');
            const empDeptInput = row.querySelector('input[name="emp_dept[]"]');

            $.ajax({
                url: '{{ url("GatePass/get-employee-details") }}/' + empId,
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        empNameInput.value = response.data.ename;
                        empPhoneInput.value = response.data.phone;
                        empCodeInput.value = response.data.code;
                        empDeptInput.value = response.data.dept;
                    }
                    updateEmployeeOptions();
                },
                error: function(xhr) {
                    console.error('Error fetching employee details:', xhr);
                }
            });
        }
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
        var sec_guard_phoneInput = document.getElementById('sec_guard_phone');
        var sec_guard_phoneError = document.getElementById('sec_guard_phoneError');

        sec_guard_phoneInput.addEventListener('input', function(event) {
            var input = event.target.value;
            var isValid = /^\d{10}$/.test(input);
            if (isValid) {
                sec_guard_phoneError.textContent = '';
            } else {
                sec_guard_phoneError.textContent = 'Phone Number Should Be 10 Digits.';
            }
        });
    </script>
@endpush
