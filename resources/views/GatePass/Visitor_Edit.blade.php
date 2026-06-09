@extends('layout.main')
@section('main-container')
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
<title>IN Visitor GatePass Update View Details Page</title>
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
                                        <div class="col">
                                            <h5>Visitor Gate Pass Details (<b class="text-primary">{{ $edit->request_no }}</b>)</h5>
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

                            <form id="submitform" action="{{ url('GatePass/updateVisitorGatepass/'. $edit->id.'/in') }}" method="POST">
                                @csrf
                                <div class="tab1">
                                    <input class="form-control" type="hidden" name="req_no" value="{{ isset($edit->request_no) && $edit->request_no != '' ? $edit->request_no : '' }}">
                                    <div class="row">
                                        <div class="col-sm-3 form-group">
                                            <label class="fw-bold">Created By</label>
                                            <input readonly class="form-control form-control-sm" type="text" id="requested_by" name="request_by" placeholder="Request By." value="{{ isset($edit->request_by) && $edit->request_by != '' ? $edit->request_by : auth()->user()->fullname }}" required>
                                        </div>
                                        @if (isset($edit->id) && $edit->id != '')
                                            <div class="col-sm-3 form-group">
                                                <label for="Manunit">Cost Center*</label>
                                                <select class="form-select form-select-sm js-example-matcher-start" name="cost_center" id="Manunit" required>
                                                    <option value="" disabled selected>Select Project</option>
                                                    @foreach ($Projects as $val)
                                                        <option value="{{ $val->id }}" {{ isset($edit->project_id) && $edit->project_id == $val->id ? 'selected' : '' }}>{{ $val->pname }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label for="Plant_Name">Sub Cost Center*</label>
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
                                                <label>Organisation Name*</label>
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
                                                        <th class="th-sm text-center">SL. NO.</th>
                                                        <th class="th-sm">VISITOR TYPE</th>
                                                        <th class="th-sm">VISITOR NAME</th>
                                                        <th class="th-sm">VISITOR MOBILE NO.</th>
                                                        <th class="th-sm">VISITOR ADDRESS</th>
                                                        <th class="th-sm">PURPOSE TO VISIT</th>
                                                        <th class="th-sm">WHOM TO MEET</th>
                                                        <th class="th-sm">ACTION</th>
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
                                                                <select class="form-select js-example-matcher-start visitorType" name="visitor_type[]" onchange="handlevisitorTypeChange(this)" required>
                                                                    <option value="" disabled>Select Visitor Type</option>
                                                                    <option value="0" {{ $val->visitor_type == 0 ? 'selected' : '' }}>OFFICE EMPLOYEE</option>
                                                                    <option value="1" {{ $val->visitor_type == 1 ? 'selected' : '' }}>VISITOR</option>
                                                                </select>
                                                            </td>
                                                            <td class="item-desc-cell">
                                                                <div class="visitorDropdown" style="{{ $val->visitor_type == 0 ? 'display: block;' : 'display: none;' }}">
                                                                    <select class="form-select js-example-matcher-start" name="visitor_name[]" onchange="getEmpDetails(this.value, this)">
                                                                        <option value="" disabled>Select Employee</option>
                                                                        @foreach ($empDtls as $value)
                                                                            <option value="{{ $value->id }}" {{ $val->visitor_name == $value->id ? 'selected' : '' }}>{{ $value->fullname }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <input type="text" class="form-control form-control-sm visitorInput" name="visitor_name[]" oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '');" placeholder="Enter Visitor Name" value="{{ $val->visitor_type == 1 ? $val->visitor_name : '' }}" style="{{ $val->visitor_type == 1 ? 'display: block;' : 'display: none;' }}">
                                                            </td>
                                                            <td>
                                                                <input class="form-control form-control-sm" type="number" maxlength="10" pattern="\d{10}" oninput="this.value=this.value.replace(/\D/g,'').slice(0,10)" name="visitor_phone[]" placeholder="Enter Phone No." value="{{ isset($val->visitor_phone) ? $val->visitor_phone : '' }}" {{ $val->visitor_type == 0 ? 'readonly' : '' }} required>
                                                            </td>
                                                            <td>
                                                                <textarea name="visitor_address[]" class="form-control form-control-sm" cols="30" rows="1" placeholder="Enter Your Address" required>{{ $val->visitor_address }}</textarea>
                                                            </td>
                                                            <td>
                                                                <textarea name="visitor_purpose[]" class="form-control form-control-sm" cols="30" rows="1" placeholder="Enter Purpose to Visit" required>{{ $val->visitor_purpose }}</textarea>
                                                            </td>
                                                            <td>
                                                                <input class="form-control form-control-sm" type="text" name="meet_prsn[]" placeholder="Enter meet person" value="{{ isset($val->visitor_meet_prsn) && $val->visitor_meet_prsn != '' ? $val->visitor_meet_prsn : '' }}" required>
                                                            </td>
                                                            <td>
                                                                @if ($key == 0)
                                                                    <a href="javascript:;" id="visitorgatepassAppend" onclick="visitorgatepassAppend({{ count($VisitorGatepassDetails) }})" class="btn btn-success btn-sm mt-2"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                                                @else
                                                                    <a href="javascript:;" onclick="visitorgatepassRemove({{ $key + 1 }})" class="btn btn-danger btn-sm mt-2">X</a>
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
                                            <label class="fw-bold">In Time*</label>
                                            <input class="form-control form-control-sm" id="intime" type="datetime-local" name="request_in_time" placeholder="Request In Time" value="{{ isset($edit->request_in_time) && !empty($edit->request_in_time) ? (DateTime::createFromFormat('d-m-Y h:i A', $edit->request_in_time) ? DateTime::createFromFormat('d-m-Y h:i A', $edit->request_in_time)->format('Y-m-d\TH:i') : $edit->request_in_time) : '' }}" required>
                                        </div>
                                        <div class="col-sm-2 form-group">
                                            <label class="fw-bold">Expected Out Time*</label>
                                            <input class="form-control form-control-sm" id="outtime" type="datetime-local" name="request_out_time" placeholder="Request Out Time" value="{{ isset($edit->request_out_time) && !empty($edit->request_out_time) ? (DateTime::createFromFormat('d-m-Y h:i A', $edit->request_out_time) ? DateTime::createFromFormat('d-m-Y h:i A', $edit->request_out_time)->format('Y-m-d\TH:i') : $edit->request_out_time) : '' }}" required>
                                        </div>
                                        <div class="col-sm-2 form-group">
                                            <label class="fw-bold">Person With Vehicle*</label>
                                            <select class="form-select form-select-sm js-example-matcher-start" name="prsn_vehicle" id="prsn_vehicle" required>
                                                <option value=""  selected>Select Option</option>
                                                <option value="1" {{ isset($edit->prsn_vehicle) && $edit->prsn_vehicle == 1 ? 'selected' : '' }}>Yes</option>
                                                <option value="0" {{ isset($edit->prsn_vehicle) && $edit->prsn_vehicle == 0 ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-3 form-group" id="vehicle_type_container">
                                            <label class="fw-bold">Vehicle Type*</label>
                                            <select class="form-select form-select-sm js-example-matcher-start" name="vehicle_type" id="vehicle_type" required>
                                                <option value=""  selected>Select Type</option>
                                                @foreach ($vehicle_types as $vehicle_type)
                                                    <option value="{{ $vehicle_type->id }}" {{ isset($edit->vehicle_type) && $edit->vehicle_type == $vehicle_type->id ? 'selected' : '' }}>{{ $vehicle_type->mstr_type_value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-3 form-group" id="vehicle_no_container">
                                            <label class="fw-bold">Vehicle No.*</label>
                                            <input class="form-control form-control-sm" type="text" name="vehicle_no" placeholder="Enter Vehicle No." id="vehicle_no" value="{{ isset($edit->vehicle_no) && $edit->vehicle_no != '' ? $edit->vehicle_no : '' }}" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-3 form-group">
                                            <label class="fw-bold">Security Guard Name*</label>
                                            <input class="form-control form-control-sm" type="text" name="sec_guard_name" placeholder="Enter Guard Name" value="{{ isset($edit->sec_guard) && $edit->sec_guard != '' ? $edit->sec_guard : '' }}" id="sec_guard" oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '');" required>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label class="fw-bold">Security Ph No*</label>
                                            <input class="form-control form-control-sm" type="text" name="sec_guard_phone" placeholder="Security Ph No" value="{{ isset($edit->sec_guard_no) && $edit->sec_guard_no != '' ? $edit->sec_guard_no : '' }}" id="sec_guard_phone"  maxlength="10" pattern="\d{10}" oninput="this.value=this.value.replace(/\D/g,'').slice(0,10)" required>
                                            <small id="sec_guard_phoneError" style="color: red;"></small>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label class="fw-bold">IN Visit Purpose *</label>
                                            <textarea class="form-control form-control-sm" name="visit_purpose" placeholder="Enter Visit Purpose" cols="30" rows="2" required>{{ isset($edit->visit_purpose) && $edit->visit_purpose != '' ? $edit->visit_purpose : '' }}</textarea>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label class="fw-bold">Whom To Meet *</label>
                                            <input class="form-control form-control-sm" type="text" name="visitor_meet_prsn" placeholder="Enter meet person" value="{{ isset($edit->meet_prsn) && $edit->meet_prsn != '' ? $edit->meet_prsn : '' }}" id="meet_prsn" required>
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

            // Add this new section for setting required properties on load
            $('.visitorType').each(function() {
                const row = $(this).closest('tr');
                const visitorDropdown = row.find('.visitorDropdown select');
                const visitorInput = row.find('.visitorInput');
                const visitorType = $(this).val();

                if (visitorType === "0") {
                    visitorDropdown.prop('required', true);
                    visitorInput.prop('required', false);
                } else if (visitorType === "1") {
                    visitorDropdown.prop('required', false);
                    visitorInput.prop('required', true);
                }
            });
        });

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

        function visitorgatepassAppend(i) {
            i++;

            $('#VisitorGatepassFields').append(`
                <tr id="visitorgatepassRemove${i}">
                    <td class="text-center">${i}</td>
                    <td>
                        <select class="form-select visitorType" name="visitor_type[]" onchange="handlevisitorTypeChange(this)" required>
                            <option value="" selected disabled>Select Visitor Type</option>
                            <option value="0">OFFICE EMPLOYEE</option>
                            <option value="1">VISITOR</option>
                        </select>
                    </td>
                    <td class="item-desc-cell">
                        <div class="visitorDropdown" style="display: none;">
                            <select class="form-select" name="visitor_name[]" onchange="getEmpDetails(this.value, this)">
                                <option value="" selected disabled>Select Employee</option>
                                @foreach ($empDtls as $value)
                                    <option value="{{ $value->id }}">{{ $value->fullname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="text" class="form-control form-control-sm visitorInput" name="visitor_name[]" oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '');" placeholder="Enter Visitor Name" style="display: none;">
                    </td>
                    <td>
                        <input class="form-control form-control-sm" type="number" name="visitor_phone[]" maxlength="10" pattern="\d{10}" oninput="this.value=this.value.replace(/\D/g,'').slice(0,10)" placeholder="Enter Phone No." value="" disabled required>
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

            $(`#visitorgatepassRemove${i} .visitorType`).trigger('change');
            $('tr').each(function() {
                const visitorSelect = $(this).find('select[name="visitor_name[]"]');
                const visitorInputCheck = $(this).find('input[name="visitor_name[]"]');

                if (visitorSelect.length > 0 && visitorSelect.is(':visible')) {
                    visitorInputCheck.prop('disabled', true);
                } else if(visitorInputCheck.length > 0 && visitorInputCheck.is(':visible')) {
                    visitorSelect.prop('disabled', true);
                }
            });
        }

        function visitorgatepassRemove(id) {
            const row = $("#visitorgatepassRemove" + id);
            const selectedEmpId = row.find('select[name="visitor_name[]"]').val();
            row.remove();
            updateSerialNumbers();
            updateEmployeeOptions();
        }

        $(document).ready(function() {
            $('.visitorType').each(function() {
                if ($(this).val() === "0") {
                    const row = $(this).closest('tr');
                    const visitorPhoneInput = row.find('input[name="visitor_phone[]"]')[0];
                    if (visitorPhoneInput) {
                        visitorPhoneInput.readOnly = true;
                    }
                }
            });

            $('tr').each(function() {
                const visitorSelect = $(this).find('select[name="visitor_name[]"]');
                const visitorInputCheck = $(this).find('input[name="visitor_name[]"]');

                if (visitorSelect.length > 0 && visitorSelect.is(':visible')) {
                    visitorInputCheck.prop('disabled', true);
                } else if(visitorInputCheck.length > 0 && visitorInputCheck.is(':visible')) {
                    visitorSelect.prop('disabled', true);
                }
            });

            let selectedEmployees = new Set();
            $('select[name="visitor_name[]"]').each(function() {
                const selectedId = $(this).val();
                if (selectedId) {
                    selectedEmployees.add(selectedId);
                }
            });

            $('select[name="visitor_name[]"]').each(function() {
                const currentValue = $(this).val();
                $(this).find('option').each(function() {
                    if (this.value && selectedEmployees.has(this.value) && this.value !== currentValue) {
                        $(this).prop('disabled', true);
                    }
                });
            });

            $(document).on('change', 'select[name="visitor_name[]"]', function() {
                updateEmployeeOptions();
            });
        });

        function handlevisitorTypeChange(selectElement) {
            const row = selectElement.closest('tr');
            const visitorDropdown = row.querySelector('.visitorDropdown');
            const visitorInput = row.querySelector('.visitorInput');
            const visitorInputCheck = $(row).find('input[name="visitor_name[]"]');
            const visitorNameSelect = $(row).find('select[name="visitor_name[]"]');
            const visitorPhoneInput = row.querySelector('input[name="visitor_phone[]"]');
            const visitorAddressInput = row.querySelector('textarea[name="visitor_address[]"]');
            const visitorPurposeInput = row.querySelector('textarea[name="visitor_purpose[]"]');
            const meetPersonInput = row.querySelector('input[name="meet_prsn[]"]');

            visitorNameSelect.val(null).trigger('change');
            visitorInput.value = "";
            visitorPhoneInput.value = "";

            if (selectElement.value === "0") {
                visitorDropdown.style.display = 'block';
                visitorInput.style.display = 'none';
                visitorInputCheck.prop('disabled', true);
                visitorInputCheck.prop('required', false);
                visitorNameSelect.prop('disabled', false);
                visitorNameSelect.prop('required', true);
                visitorPhoneInput.readOnly = true;
                $(visitorPhoneInput).attr('readonly', 'readonly');
            } else if (selectElement.value === "1") {
                visitorDropdown.style.display = 'none';
                visitorInput.style.display = 'block';
                visitorInputCheck.prop('disabled', false);
                visitorInputCheck.prop('required', true);
                visitorNameSelect.prop('disabled', true);
                visitorNameSelect.prop('required', false);
                visitorPhoneInput.readOnly = false;
                $(visitorPhoneInput).removeAttr('readonly');
            } else {
                visitorDropdown.style.display = 'block';
                visitorInput.style.display = 'none';
                visitorNameSelect.prop('disabled', true);
                visitorNameSelect.prop('required', false);
                visitorInputCheck.prop('required', false);
                visitorPhoneInput.readOnly = false;
                $(visitorPhoneInput).removeAttr('readonly');
            }

            if (visitorPhoneInput) {
                visitorPhoneInput.disabled = !selectElement.value;
            }
            if (visitorAddressInput) {
                visitorAddressInput.disabled = !selectElement.value;
            }
            if (visitorPurposeInput) {
                visitorPurposeInput.disabled = !selectElement.value;
            }
            if (meetPersonInput) {
                meetPersonInput.disabled = !selectElement.value;
            }
        }

        function updateEmployeeOptions() {
            let selectedEmployees = new Set();

            $('select[name="visitor_name[]"]').each(function() {
                const selectedId = $(this).val();
                if (selectedId) {
                    selectedEmployees.add(selectedId);
                }
            });

            $('select[name="visitor_name[]"]').each(function() {
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
                        $(visitorPhoneInput).attr('readonly', 'readonly');
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
    </script>
@endpush