@extends('layout.main')
@section('main-container')
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <title>Finished Good Gate Pass Material Approver List</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background-color: #f1f1f1;

        }

        #regForm {
            background-color: #ffffff;
            font-family: Raleway;
            width: 100%;
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

        .left-bar p {
            margin: 4% !important;
        }

        .activesle {
            background: #6741D5 !important;
        }
    </style>
    @php
        $Department = Session::get('Department');
        $EXT = Session::get('EXT');
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
                <ol class="breadcrumb border-bottom d-flex align-items-center">
                    <li class="breadcrumb-item">Finished Good Approval List</li>
                    <li class="ms-auto">
                        <a href="{{ url('FinishedGood/ExportFinishedGoodApproverData') }}">
                            <i class="fas fa-file-excel text-success p-3" style="font-size: 20px;"></i>
                        </a>
                    </li>
                </ol>
                <div class="row">
                    <div class="container-fluid">
                        <form method="POST">
                            @csrf
                            <div class="row filter">
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label for="" class="form-label">Date From</label>
                                    <input type="date" name="from_date" value="{{ request()->from_date ?? '' }}"
                                        class="form-control form-control-sm">
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label for="" class="form-label">Date To</label>
                                    <input type="date" name="to_date" value="{{ request()->to_date ?? '' }}"
                                        class="form-control form-control-sm">
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>Creator Name</lable>
                                        <select name="Request_By"
                                            class="form-select form-select-sm js-example-matcher-start">
                                            <option value="" disabled selected>Select</option>
                                            @foreach ($admindata as $key => $val)
                                                <option value="{{ $key }}"
                                                    {{ isset(request()->Request_By) && request()->Request_By == $key ? 'selected' : '' }}>
                                                    {{ $val }}</option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>Organization Name</label>
                                    <select name="Organization" class="form-select form-select-sm">
                                        <option value="" selected>Select</option>
                                        @foreach ($Orgs as $val)
                                            <option value="{{ $val->id }}"
                                                {{ isset(request()->Organization) && request()->Organization == $val->id ? 'selected' : '' }}>
                                                {{ isset($val->organisation) && $val->organisation != '' ? $val->organisation : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>Manufacturing Unit</lable>
                                        <select name="Cost_Center"
                                            class="form-select form-select-sm js-example-matcher-start">
                                            <option value="" selected>Select</option>
                                            @foreach ($Manufacturing_unit as $project)
                                                <option value="{{ $project->id }}"
                                                    {{ isset(request()->Cost_Center) && request()->Cost_Center == $project->id ? 'selected' : '' }}>
                                                    {{ $project->pname }}
                                                </option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>Plant Name</lable>
                                        <select name="Sub_Cost_Center"
                                            class="form-select form-select-sm js-example-matcher-start"
                                            id="Sub_Cost_Center">
                                            <option value="" selected>Select</option>
                                            @foreach ($plant_name as $subproject)
                                                <option value="{{ $subproject->id }}"
                                                    {{ isset(request()->Sub_Cost_Center) && request()->Sub_Cost_Center == $subproject->id ? 'selected' : '' }}>
                                                    {{ $subproject->spname }}
                                                </option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>Raw Material(FG)</lable>
                                        <select name="Raw_Material"
                                            class="form-select form-select-sm js-example-matcher-start" id="RawMaterial">
                                            <option value="" selected>Select</option>
                                            @foreach ($Filtered_Array as $val)
                                                <option value="{{ $val->RawMaterial->id }}"
                                                    {{ isset(request()->Raw_Material) && request()->Raw_Material == $val->RawMaterial->id ? 'selected' : '' }}>
                                                    {{ $val->RawMaterial->matname }}</option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-6 p-0 text-end mt-3">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                    <a href="{{ url('FinishedGood/Finished_Good_Approver_List') }}"><button type="button"
                                            class="btn btn-secondary"><i class="fa fa-refresh"></i></button></a>
                                </div>
                                <div class="col-xl-1 col-lg-1 col-md-1 col-sm-6 mt-4">
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
                                                    <input type="checkbox" class="form-check-input" id="Creater_Name"
                                                        value="Creator Name" onclick="filterTable(this)">
                                                    <label class="form-check-label" for="Creater_Name">Creator Name</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                        id="Organization_Name" value="Organization Name"
                                                        onclick="filterTable(this)">
                                                    <label class="form-check-label" for="Organization_Name">Organization
                                                        Name</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                        id="Manufacturing_Unit" value="Manufacturing Unit"
                                                        onclick="filterTable(this)">
                                                    <label class="form-check-label" for="Manufacturing_Unit">Manufacturing
                                                        Unit</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="Plant_Name"
                                                        value="Plant Name" onclick="filterTable(this)">
                                                    <label class="form-check-label" for="Plant_Name">Plant Name</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="Finished Good"
                                                        value="Finished Good(FG)" onclick="filterTable(this)">
                                                    <label class="form-check-label" for="Finished Good">Finished
                                                        Good(FG)</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="UOM"
                                                        value="UOM" onclick="filterTable(this)">
                                                    <label class="form-check-label" for="UOM">UOM</label>
                                                </div>

                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="Production_Date"
                                                        value="Production Date" onclick="filterTable(this)">
                                                    <label class="form-check-label" for="Production_Date">Production
                                                        Date</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="Rate"
                                                        value="Rate" onclick="filterTable(this)">
                                                    <label class="form-check-label" for="Rate">Rate</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="Quantity"
                                                        value="Quantity" onclick="filterTable(this)">
                                                    <label class="form-check-label" for="Quantity">Quantity</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="Date_Time"
                                                        value="Date & Time" onclick="filterTable(this)">
                                                    <label class="form-check-label" for="Date_Time">Date & Time</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="Status"
                                                        value="Status" onclick="filterTable(this)">
                                                    <label class="form-check-label" for="Status">Status</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="Pending_With"
                                                        value="Pending With" onclick="filterTable(this)">
                                                    <label class="form-check-label" for="Pending_With">Pending
                                                        With</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="Operation"
                                                        value="Operation" onclick="filterTable(this)">
                                                    <label class="form-check-label" for="Operation">Operation</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive" id="sales-fields">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="th-sm">SL. No.</th>
                                        <th class="th-sm">Creator Name</th>
                                        <th class="th-sm">Manufacturing Unit</th>
                                        <th class="th-sm">Plant Name</th>
                                        <th class="th-sm">Organization Name</th>
                                        <th class="th-sm">Production Date</th>
                                        <th class="th-sm">Finished Good(FG)</th>
                                        <th class="th-sm">UOM</th>
                                        <th class="th-sm">Rate</th>
                                        <th class="th-sm">Quantity</th>
                                        <th class="th-sm">Date & Time</th>
                                        <th class="th-sm">Status</th>
                                        <th class="th-sm">Pending With</th>
                                        <th class="th-sm">Operation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($i = 1)
                                    @foreach ($production as $value)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ isset($admindata[$value->userID]) ? $admindata[$value->userID] : '' }}
                                            </td>
                                            <td>{{ isset($Manufacturing_unitdata[$value->Unit_Name]) ? $Manufacturing_unitdata[$value->Unit_Name] : '' }}
                                            </td>
                                            <td>{{ isset($plant_namedata[$value->Plant_Name]) ? $plant_namedata[$value->Plant_Name] : '' }}
                                            </td>
                                            <td>{{ isset($orgdata[$value->Organization_Name]) ? $orgdata[$value->Organization_Name] : '' }}
                                            </td>
                                            <td>{{ date('d-m-Y', strtotime($value->Transaction_Date)) }}</td>
                                            <td>{{ isset($Raw_Materialdata[$value->Material_id]) ? $Raw_Materialdata[$value->Material_id] : '' }}
                                            </td>
                                            <td>{{ $value->UOM }}</td>
                                            <td>{{ $value->Rate }}</td>
                                            <td>{{ $value->Quantity }}</td>
                                            <td>{{ isset($value->created_at) && $value->created_at != '' ? date('d-m-Y h:i A', strtotime($value->created_at)) : '' }}
                                            </td>
                                            <td>{{ status($value->action) }}</td>
                                            <td class="PendingColor">{{ Pending_With(22, $value) }}</td>
                                            <td class="maindffd">
                                                <a href="{{ url('FinishedGood/ApproverView/' . $value->id) }}"><button
                                                        type="button" class="btn btn-primary">View</button></a>
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
    </div>


@endsection
@push('custom-scripts')
    <script>
        $(document).ready(function() {
            activeclass(28, 2);
        });
    </script>
    <script>
        $("#MyToggle").click(function() {
            $("#myFilter").toggle();
        });

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
        var tableID = 22;

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
