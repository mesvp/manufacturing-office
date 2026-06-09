@extends('layout.main')
@section('main-container')

    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <title>IN Employee Gate Pass Approval List Details Page</title>
    <style>
        * {

            box-sizing: border-box;

        }

        body {

            background-color: #f1f1f1;

        }

        th,
        td {
            vertical-align: middle !important;
        }

        table#example {
            border: 1px solid #111;
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
            <section class="section p-2">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">IN Employee Gate Pass Approval List Page</li>
                </ol>
                <div class="row">
                    <div class="container">
                        <form action="{{ url('GatePass/filtered_ApproveEmp') }}" method="POST">
                            @csrf
                            <div class="row filter">
                                <div class="col-2 mb-3">
                                    <label for="" class="form-label">Date From</label>
                                    <input type="date" class="form-control form-control-sm" name="from_date"
                                        value="{{ isset($fromdate) && $fromdate != '' ? $fromdate : '' }}">
                                </div>
                                <div class="col-2 mb-3">
                                    <label for="" class="form-label">Date To</label>
                                    <input type="date" name="to_date" class="form-control form-control-sm"
                                        value="{{ isset($todate) && $todate != '' ? $todate : '' }}">
                                </div>
                                <div class="col-2 mb-3">
                                    <label for="Cost_Center" class="form-label">Cost Center</label>
                                    <select name="Cost_Center" class="form-select form-select-sm js-example-matcher-start" id="Cost_Center">
                                        <option value="" disabled>Select</option>
                                        <option value="all" {{ isset($CostCenter) && $CostCenter === 'all' ? 'selected' : '' }}>All</option>
                                        @foreach ($Projects as $project)
                                            <option value="{{ $project->id }}" {{ isset($CostCenter) && $CostCenter == $project->id ? 'selected' : '' }}>
                                                {{ $project->pname }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-2 mb-3">
                                    <label for="Sub_Cost_Center" class="form-label">Sub Cost Center</label>
                                    <select name="Sub_Cost_Center" class="form-select form-select-sm js-example-matcher-start" id="Sub_Cost_Center">
                                        <option value="" disabled>Select</option>
                                        <option value="all" {{ isset($SubCostCenter) && $SubCostCenter === 'all' ? 'selected' : '' }}>All</option>
                                        @foreach ($Sub_Projects as $subproject)
                                            <option value="{{ $subproject->id }}" {{ isset($SubCostCenter) && $SubCostCenter == $subproject->id ? 'selected' : '' }}>
                                                {{ $subproject->spname }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-2 mb-3">
                                    <label for="" class="form-label">Request No</label>
                                    <select name="Request_No" class="form-select form-select-sm js-example-matcher-start">
                                        <option value="" disabled selected>Select</option>
                                        <option value="all"
                                            {{ isset($RequestNos) && $RequestNos === 'all' ? 'selected' : '' }}>All</option>
                                        <?php $RepeatData = []; ?>
                                        @foreach ($DropdownData as $val)
                                            <?php
												$RequestNo = isset($val->request_no) && $val->request_no != '' ? $val->request_no : '';
												if (!empty($RequestNo) && !in_array($RequestNo, $RepeatData)) {
												$RepeatData[] = $RequestNo;
											?>
                                            <option value="{{ $RequestNo }}"
                                                {{ isset($RequestNos) && $RequestNos == $RequestNo ? 'selected' : '' }}>
                                                {{ $RequestNo }}</option>
                                            <?php } ?>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2 mb-3">
                                    <label for="" class="form-label">Gate Pass No</label>
                                    <select name="Gate_Pass_No" class="form-select form-select-sm js-example-matcher-start">
                                        <option value="" disabled selected>Select</option>
                                        <option value="all"
                                            {{ isset($GatePassNos) && $GatePassNos === 'all' ? 'selected' : '' }}>All
                                        </option>
                                        <?php $RepeatData = []; ?>
                                        @foreach ($DropdownData as $val)
                                            <?php
												$GatePassNo = isset($val->gate_pass_no) && $val->gate_pass_no != '' ? $val->gate_pass_no : '';
												if (!empty($GatePassNo) && !in_array($GatePassNo, $RepeatData)) {
												$RepeatData[] = $GatePassNo;
											?>
                                            <option value="{{ $GatePassNo }}"
                                                {{ isset($GatePassNos) && $GatePassNos == $GatePassNo ? 'selected' : '' }}>
                                                {{ $GatePassNo }}</option>
                                            <?php } ?>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2 mb-3">
                                    <label for="" class="form-label">Request Out Time</label>
                                    <input type="datetime-local" name="Request_Out_Time" class="form-control form-control-sm" value="{{ old('Request_Out_Time', $RequestOutTimes ?? '') }}">
                                </div>
                                <div class="col-2 mb-3">
                                    <label for="" class="form-label">Request In Time</label>
                                    <input type="datetime-local" name="Request_In_Time" class="form-control form-control-sm" value="{{ old('Request_In_Time', $RequestInTimes ?? '') }}">
                                </div>
                                <div class="col-2 mb-3">
                                    <label for="" class="form-label">Request By</label>
                                    <select name="Request_By" class="form-select form-select-sm js-example-matcher-start">
                                        <option value="" disabled selected>Select</option>
                                        <option value="all"
                                            {{ isset($RequestBys) && $RequestBys === 'all' ? 'selected' : '' }}>All
                                        </option>
                                        <?php $RepeatData = []; ?>
                                        @foreach ($DropdownData as $val)
                                            <?php
												$RequestBy = isset($val->request_by) && $val->request_by != '' ? $val->request_by : '';
												if (!empty($RequestBy) && !in_array($RequestBy, $RepeatData)) {
												$RepeatData[] = $RequestBy;
											?>
                                            <option value="{{ $RequestBy }}"
                                                {{ isset($RequestBys) && $RequestBys == $RequestBy ? 'selected' : '' }}>
                                                {{ $RequestBy }}</option>
                                            <?php } ?>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-2 mb-3">
                                    <label for="" class="form-label">Security Guard</label>
                                    <select name="Security_Guard" class="form-select form-select-sm js-example-matcher-start">
                                        <option value="" disabled selected>Select</option>
                                        <option value="all"
                                            {{ isset($SecurityGuards) && $SecurityGuards === 'all' ? 'selected' : '' }}>All</option>
                                        <?php $RepeatData = []; ?>
                                        @foreach ($DropdownData as $val)
                                            <?php
												$SecurityGuard = isset($val->sec_guard) && $val->sec_guard != '' ? $val->sec_guard : '';
												if (!empty($SecurityGuard) && !in_array($SecurityGuard, $RepeatData)) {
												$RepeatData[] = $SecurityGuard;
											?>
                                            <option value="{{ $SecurityGuard }}"
                                                {{ isset($SecurityGuards) && $SecurityGuards == $SecurityGuard ? 'selected' : '' }}>
                                                {{ $SecurityGuard }}</option>
                                            <?php } ?>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-2 mt-4">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                    <a href="{{ url('GatePass/Employee_Gatepass_Approval') }}"><button type="button"
                                            class="btn btn-secondary"><i class="fa fa-refresh"></i></button></a>
                                </div>
                                <div class="col-2 mt-4">
                                    <div class="FilterButtonnn">
                                        <div class="raone">
                                            <p class="raho" id="MyToggle">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-funnel-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z" />
                                                </svg>
                                            </p>
                                            <div class="ukom" id="myFilter">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="ToggleCheck"
                                                        onclick="toggleCheckboxes()">
                                                    <label class="form-check-label" for="ToggleCheck">All</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="NO"
                                                        value="SL. No." onclick="filterTable(this)">
                                                    <label class="form-check-label" for="NO">SL. No.</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="IN_Pass_No"
                                                        value="Request No" onclick="filterTable(this)">
                                                    <label class="form-check-label" for="IN_Pass_No">Request No</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="Gate_Pass_No"
                                                        value="Gate Pass No" onclick="filterTable(this)">
                                                    <label class="form-check-label" for="Gate_Pass_No">Gate Pass No</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="Cost_Center"
                                                        value="Cost Center" onclick="filterTable(this)">
                                                    <label class="form-check-label" for="Cost_Center">Cost Center</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="Sub_Cost_Center"
                                                        value="Sub Cost Center" onclick="filterTable(this)">
                                                    <label class="form-check-label" for="Sub_Cost_Center">Sub Cost Center</label>
                                                </div>

                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="Request_In_Time"
                                                        value="Request In Time" onclick="filterTable(this)">
                                                    <label class="form-check-label" for="Request_In_Time">Request In Time</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="Request_Out_Time"
                                                        value="Request Out Time" onclick="filterTable(this)">
                                                    <label class="form-check-label" for="Request_Out_Time">Request Out
                                                        Time</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="Person_With_Vehicle"
                                                        value="Person With Vehicle" onclick="filterTable(this)">
                                                    <label class="form-check-label" for="Person_With_Vehicle">Person With
                                                        Vehicle</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="Security_Guard"
                                                        value="Security Guard" onclick="filterTable(this)">
                                                    <label class="form-check-label" for="Security_Guard">Security Guard</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="Created_By"
                                                        value="Created By" onclick="filterTable(this)">
                                                    <label class="form-check-label" for="Created_By">Created By</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="Creation_Date_Time"
                                                        value="Creation Date & Time" onclick="filterTable(this)">
                                                    <label class="form-check-label" for="Creation_Date_Time">Creation Date &
                                                        Time</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="Status_IN"
                                                        value="Status" onclick="filterTable(this)">
                                                    <label class="form-check-label" for="Status_IN">Status</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="Pending_With_IN"
                                                        value="Pending With" onclick="filterTable(this)">
                                                    <label class="form-check-label" for="Pending_With_IN">Pending With</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="OPERATION" value="OPERATION" onclick="filterTable(this)">
                                                    <label class="form-check-label" for="OPERATION">OPERATION</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered w-00">
                                <thead style="background: #d0d9dc;">
                                    <tr class="th-sm text-center">
                                        <th class="th-sm">SL. No.</th>
                                        <th class="th-sm">Request No</th>
                                        <th class="th-sm">Gate Pass No</th>
                                        <th class="th-sm">Cost Center</th>
                                        <th class="th-sm">Sub Cost Center</th>
                                        <th class="th-sm">Request In Time</th>
                                        <th class="th-sm">Request Out Time</th>
                                        <th class="th-sm">Person With Vehicle</th>
                                        <th class="th-sm">Security Guard</th>
                                        <th class="th-sm">Created By</th>
                                        <th class="th-sm">Creation Date & Time</th>
                                        <th class="th-sm">Status</th>
                                        <th class="th-sm">Pending With</th>
                                        <th class="th-sm text-center">OPERATION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employeedata as $key => $val)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ isset($val->request_no) ? $val->request_no : 'N/A' }}</td>
                                            <td>{{ isset($val->gate_pass_no) ? $val->gate_pass_no : 'N/A' }}</td>
                                            <td>{{ isset($Manufacturing_unitdata[$val->project_id]) ? $Manufacturing_unitdata[$val->project_id] : 'N/A' }}</td>
                                            <td>{{ isset($plant_namedata[$val->subproject_id]) ? $plant_namedata[$val->subproject_id] : 'N/A' }}</td>
                                            <td>{{ isset($val->request_in_time) ? date('d-m-Y h:i A' , strtotime($val->request_in_time)) : 'N/A' }}</td>
                                            <td>{{ isset($val->request_out_time) ? date('d-m-Y h:i A' , strtotime($val->request_out_time)) : 'N/A' }}</td>
                                            <td>{{ isset($val->prsn_vehicle) ? ($val->prsn_vehicle == 1 ? 'Yes' : 'No') : 'N/A' }}</td>
                                            <td>{{ isset($val->sec_guard) ? $val->sec_guard : 'N/A' }}</td>
                                            <td>{{ isset($val->request_by) ? $val->request_by : 'N/A' }}</td>
                                            <td>{{ isset($val->created_at) ? date('d-m-Y h:i A', strtotime($val->created_at)) : 'N/A' }}</td>
                                            <td id="statuss{{ $val->id }}">
                                                @if ($val->Approve_status == 'APPROVE')
                                                    <span style="color: #1bb81b;">APPROVED</span>
                                                @elseif($val->Approve_status == 'REJECT')
                                                    <span style="color:red">REJECTED</span>
                                                @elseif($val->Approve_status == 'RECHECK')
                                                    <span style="color:#71a5ee">RECHECK</span>
                                                @elseif($val->Approve_status == 'OBJECT')
                                                    <span style="color:#da2aff">OBJECT</span>
                                                @elseif($val->Approve_status == 'HOLD')
                                                    <span style="color:#0cbad6">HOLD</span>
                                                @else
                                                    <span style="color: #FF9000;">Pending</span>
                                                @endif
                                            </td>
                                            <td class="PendingColor">
                                                @if (
                                                    ($val->Approve_status === 'FORWARD' && isset($val->status) && $val->status != 1) ||
                                                        ($val->Approve_status == '' && isset($val->status) && $val->status != 1))
                                                    Pending With
                                                    @foreach ($val->PendingWith as $name)
                                                        {{ isset($name->fullname) && $name->fullname != '' ? $name->fullname : '' }},
                                                    @endforeach
                                                @elseif($val->Approve_status == 'RECHECK' || $val->Approve_status == 'OBJECT')
                                                    {{ isset($val->user->fullname) && $val->user->fullname != '' ? 'Pending With ' . $val->user->fullname : '' }}
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ url('GatePass/employee_gatepass_approval_view/' . $val->id .'/'.'in') }}" class="btn btn-primary">VIEW</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <br> <br>
    </div>
@endsection
@push('custom-scripts')
    <script>
        activeclass(7, 2);
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
        var tableID = 7786;

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

            fetch("{{ url('StoreRequistion/getCheckBoxData') }}?ID=" + tableID, {
                    method: 'GET',
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.columns) {
                        try {
                            var existingData = data.columns;
                            if (JSON.stringify(existingData) !== JSON.stringify(columnNamesToShow)) {
                                fetch("{{ url('StoreRequistion/CheckBoxStore') }}", {
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

            fetch("{{ url('StoreRequistion/getCheckBoxData') }}?ID=" + tableID, {
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

@endpush