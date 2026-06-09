@extends('layout.main')
@section('main-container')
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
<title>IN Employee GatePass Update View Details Page</title>
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
                                            <h5>Employee Gate Pass Details (<b class="text-primary">{{ $edit->request_no }}</b>)</h5>
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

                            <form id="submitform" action="{{ url('GatePass/updateEmployeeGatepass/'. $edit->id.'/in') }}" method="POST">
                                @csrf
                                <div class="tab1">
                                    <input class="form-control" type="hidden" name="req_no" value="{{ isset($edit->request_no) && $edit->request_no != '' ? $edit->request_no : '' }}">
                                    <div class="row">
                                        <div class="col-sm-3 form-group">
                                            <label class="fw-bold">Created By *</label>
                                            <input readonly class="form-control form-control-sm" type="text" id="requested_by" name="request_by" placeholder="Request By." value="{{ isset($edit->request_by) && $edit->request_by != '' ? $edit->request_by : auth()->user()->fullname }}" required>
                                        </div>
                                        @if (isset($edit->id) && $edit->id != '')
                                            <div class="col-sm-3 form-group">
                                                <label for="Manunit">Cost Center *</label>
                                                <select class="form-select form-select-sm js-example-matcher-start" name="cost_center" id="Manunit" required>
                                                    <option value="" disabled selected>Select Project</option>
                                                    @foreach ($Projects as $val)
                                                        <option value="{{ $val->id }}" {{ isset($edit->project_id) && $edit->project_id == $val->id ? 'selected' : '' }}>{{ $val->pname }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label for="Plant_Name">Sub Cost Center *</label>
                                                <select class="form-select form-select-sm js-example-matcher-start" name="sub_cost_center" id="Plant_Name" required>
                                                    <option value="" disabled selected>Select Sub Project</option>
                                                    @if (isset($edit->subproject_id))
                                                        @foreach ($Sub_Projects as $val)
                                                            <option value="{{ $val->id }}" {{ isset($edit->subproject_id) && $edit->subproject_id == $val->id ? 'selected' : '' }}>{{ $val->spname }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label>Organisation Name *</label>
                                                <select class="form-select form-select-sm js-example-matcher-start" name="organisation_name" id="organisation_name" required>
                                                    <option value="" disabled selected>Select Organisation</option>
                                                    @foreach ($Organisations as $val)
                                                        <option value="{{ $val->id }}" {{ isset($edit->org_id) && $edit->org_id == $val->id ? 'selected' : '' }}>{{ $val->organisation }}</option>
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
                                                        <th class="th-sm">Employee Shift</th>
                                                        <th class="th-sm">Employee Name</th>
                                                        <th class="th-sm">Employee Code</th>
                                                        <th class="th-sm">Department</th>
                                                        <th class="th-sm">Ph No</th>
                                                        <th class="th-sm">Action</th>
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
                                                                <select class="form-select form-select-sm js-example-matcher-start" name="emp_shift[]" required>
                                                                    <option value="" selected disabled>Select Shift</option>
                                                                    @foreach($Shifts as $shift)
                                                                        <option value="{{$shift->shift}}" {{ ($shift->shift == $val->emp_shift) ? 'selected' : ''}}>{{$shift->shift}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td class="item-desc-cell">
                                                                <select class="form-select form-select-sm js-example-matcher-start" name="emp_id[]" onchange="getEmpDetails(this.value, this)" required>
                                                                    <option value="" selected disabled>Select Employee</option>
                                                                    @foreach($employees as $value)
                                                                        <option value="{{$value->id}}" {{isset($val->emp_name) && ($value->fullname == $val->emp_name) ?'selected':''}}>{{$value->fullname}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <input type="hidden" name="emp_name[]" value="{{ $val->emp_name }}">
                                                            </td>
                                                            <td>
                                                                <input class="form-control form-control-sm" type="text" name="emp_code[]" placeholder="Enter Employee code" value="{{ $val->emp_code }}" required readonly>
                                                            </td>
                                                            <td>
                                                                <input class="form-control form-control-sm" type="text" name="emp_dept[]" placeholder="Enter Department" value="{{ $val->emp_dept }}" required readonly>
                                                            </td>
                                                            <td>
                                                                <input class="form-control form-control-sm" type="number" name="emp_phone[]" placeholder="Enter Phone No." value="{{ $val->emp_phone }}" required readonly>
                                                            </td>
                                                            <td>
                                                                @if ($key == 0)
                                                                    <a href="javascript:;" id="empgatepassAppend" onclick="empgatepassAppend({{ count($EmployeeGatepassDetails) }})" class="btn btn-success btn-sm mt-2"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                                                @else
                                                                    <a href="javascript:;" onclick="empgatepassRemove({{ $key + 1 }})" class="btn btn-danger btn-sm mt-2">X</a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-2 form-group">
                                            <label class="fw-bold">In Time *</label>
                                            <input class="form-control form-control-sm" id="intime" type="datetime-local" name="request_in_time" placeholder="Request In Time" value="{{ isset($edit->request_in_time) && !empty($edit->request_in_time) ? (DateTime::createFromFormat('d-m-Y h:i A', $edit->request_in_time) ? DateTime::createFromFormat('d-m-Y h:i A', $edit->request_in_time)->format('Y-m-d\TH:i') : $edit->request_in_time) : '' }}" required>
                                        </div>
                                        <div class="col-sm-2 form-group">
                                            <label class="fw-bold">Expected Out Time *</label>
                                            <input class="form-control form-control-sm" id="outtime" type="datetime-local" name="request_out_time" placeholder="Request Out Time" value="{{ isset($edit->request_out_time) && !empty($edit->request_out_time) ? (DateTime::createFromFormat('d-m-Y h:i A', $edit->request_out_time) ? DateTime::createFromFormat('d-m-Y h:i A', $edit->request_out_time)->format('Y-m-d\TH:i') : $edit->request_out_time) : '' }}" required>
                                        </div>
                                        <div class="col-sm-2 form-group">
                                            <label class="fw-bold">Person With Vehicle *</label>
                                            <select class="form-select form-select-sm js-example-matcher-start" name="prsn_vehicle" id="prsn_vehicle" required>
                                                <option value=""  selected>Select Option</option>
                                                <option value="1" {{ isset($edit->prsn_vehicle) && $edit->prsn_vehicle == 1 ? 'selected' : '' }}>Yes</option>
                                                <option value="0" {{ isset($edit->prsn_vehicle) && $edit->prsn_vehicle == 0 ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-3 form-group" id="vehicle_type_container">
                                            <label class="fw-bold">Vehicle Type *</label>
                                            <select class="form-select form-select-sm js-example-matcher-start" name="vehicle_type" id="vehicle_type" required>
                                                <option value=""  selected>Select Type</option>
                                                @foreach ($vehicle_types as $vehicle_type)
                                                    <option value="{{ $vehicle_type->id }}" {{ isset($edit->vehicle_type) && $edit->vehicle_type == $vehicle_type->id ? 'selected' : '' }}>{{ $vehicle_type->mstr_type_value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-3 form-group" id="vehicle_no_container">
                                            <label class="fw-bold">Vehicle No. *</label>
                                            <input class="form-control form-control-sm" type="text" name="vehicle_no" placeholder="Enter Vehicle No." id="vehicle_no" value="{{ isset($edit->vehicle_no) && $edit->vehicle_no != '' ? $edit->vehicle_no : '' }}" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-3 form-group">
                                            <label class="fw-bold">Security Guard Name  *<span class="text-danger fw-bolder" id="sec_guard_req"></span></label>
                                            <input class="form-control form-control-sm" type="text" name="sec_guard_name" placeholder="Enter Guard Name" value="{{ isset($edit->sec_guard) && $edit->sec_guard != '' ? $edit->sec_guard : '' }}" id="sec_guard" oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '');" required>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label class="fw-bold">Security Ph No  *<span class="text-danger fw-bolder" id="sec_guard_req"></span></label>
                                            <input class="form-control form-control-sm" type="text" name="sec_guard_phone" placeholder="Security Ph No" value="{{ isset($edit->sec_guard_no) && $edit->sec_guard_no != '' ? $edit->sec_guard_no : '' }}" id="sec_guard_phone"  maxlength="10" pattern="\d{10}" oninput="this.value=this.value.replace(/\D/g,'').slice(0,10)" required>
                                            <small id="sec_guard_phoneError" style="color: red;"></small>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label class="fw-bold">IN Visit Purpose  *<span class="text-danger fw-bolder" id="sec_guard_req"></span></label>
                                            <textarea class="form-control form-control-sm" name="visit_purpose" placeholder="Enter Visit Purpose" cols="30" rows="2" required>{{ isset($edit->visit_purpose) && $edit->visit_purpose != '' ? $edit->visit_purpose : '' }}</textarea>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label class="fw-bold">Whom To Meet  *<span class="text-danger fw-bolder" id="meet_prsn"></span></label>
                                            <input class="form-control form-control-sm" type="text" name="meet_prsn" placeholder="Enter meet person" value="{{ isset($edit->meet_prsn) && $edit->meet_prsn != '' ? $edit->meet_prsn : '' }}" id="meet_prsn" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12 form-group">
                                            <label class="fw-bold" for="State">Remarks *:</label>
                                            <textarea class="form-control" name="remarks" placeholder="Enter Remarks" cols="30" rows="4" required>{{ isset($edit->remarks) && $edit->remarks != '' ? $edit->remarks : '' }}</textarea>
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
                                    '<option value="" selected>Select Sub Project</option>');
                                $.each(response, function(index, plantdetails) {
                                    var option = $('<option>');
                                    option.val(plantdetails.id);
                                    option.text(plantdetails.spname);
                                    $('#Plant_Name').append(option);
                                });
                            } else {
                                $('#Plant_Name').append(
                                    '<option value="" selected>No Sub Project Found</option>'
                                );
                            }
                        },
                        error: function() {
                            $('#Plant_Name').empty().append(
                                '<option value="" selected>Error fetching data</option>'
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
    </script>
    <script>
        function AppendSelect2() {
            $('.js-example-matcher-start').select2();
        }

        function empgatepassAppend(i) {
            i++;
            const options = `@foreach($Shifts as $val)
            <option value="{{$val->shift}}" {{isset($edit->shift)?'selected':''}}>{{$val->shift}}</option>
            @endforeach`;

            const selectedEmployees = [];
            $('select[name="emp_id[]"]').each(function() {
                const value = $(this).val();
                if (value) selectedEmployees.push(value);
            });

            let employeeOptions = '<option value="" selected disabled>Select Employee</option>';
            @foreach($employees as $value)
                employeeOptions += `<option value="{{$value->id}}" ${selectedEmployees.includes('{{$value->id}}') ? 'disabled' : ''}>{{$value->fullname}}</option>`;
            @endforeach

            $('#EmployeeGatepassFields').append(`
                <tr id="itemRow${i}">
                    <td class="text-center sl-no">${i}</td>
                    <td>
                        <select class="form-select form-select-sm js-example-matcher-start" name="emp_shift[]" required>
                            <option value="" selected disabled>Select Shift</option>
                            ${options}
                        </select>
                    </td>
                    <td class="item-desc-cell">
                        <select class="form-select form-select-sm js-example-matcher-start" name="emp_id[]" onchange="getEmpDetails(this.value, this)" required>
                            ${employeeOptions}
                        </select>
                        <input type="hidden" name="emp_name[]" value="">
                    </td>
                    <td>
                        <input class="form-control form-control-sm" type="text" name="emp_code[]" placeholder="Enter Employee code" value="" readonly>
                    </td>
                    <td>
                        <input class="form-control form-control-sm" type="text" name="emp_dept[]" placeholder="Enter Department" value="" readonly>
                    </td>
                    <td>
                        <input class="form-control form-control-sm" type="number" name="emp_phone[]" placeholder="Enter Phone No." value="" readonly>
                    </td>
                    <td>
                        <a href="javascript:;" onclick="empgatepassRemove(${i})" class="btn btn-danger btn-sm mt-2">X</a>
                    </td>
                </tr>
            `);

            $("#empgatepassAppend").attr("onclick", `empgatepassAppend(${i})`);
            AppendSelect2();
            updateSerialNumbers();
            updateEmployeeSelect();
        }

        function empgatepassRemove(id) {
            const row = $("#itemRow" + id);
            const selectedValue = row.find('select[name="emp_id[]"]').val();
            row.remove();
            updateSerialNumbers();
            updateEmployeeSelect();
        }

        function updateSerialNumbers() {
            $('#EmployeeGatepassFields tr').each(function(index, tr) {
                $(tr).find('.sl-no').text(index + 1);
            });
        }

        function updateEmployeeSelect() {
            const selectedValues = [];
            $('select[name="emp_id[]"]').each(function() {
                const value = $(this).val();
                if (value) selectedValues.push(value);
            });

            $('select[name="emp_id[]"]').each(function() {
                const currentSelect = $(this);
                const currentValue = currentSelect.val();
                currentSelect.find('option').prop('disabled', false);
                selectedValues.forEach(value => {
                    if (value !== currentValue) {
                        currentSelect.find(`option[value="${value}"]`).prop('disabled', true);
                    }
                });

                if (currentValue) {
                    currentSelect.find(`option[value="${currentValue}"]`).prop('disabled', false);
                }
            });

            $('.js-example-matcher-start').select2();
        }

        $(document).ready(function() {
            updateEmployeeSelect();
        });

        $(document).on('change', 'select[name="emp_id[]"]', function() {
            updateEmployeeSelect();
            getEmpDetails($(this).val(), this);
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
                },
                error: function(xhr) {
                    console.error('Error fetching employee details:', xhr);
                }
            });
        }
    </script>
@endpush