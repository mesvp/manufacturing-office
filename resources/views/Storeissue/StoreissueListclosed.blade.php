@extends('layout.main')
@section('main-container')
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">

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

            <!-- <div class="addbtn">
                <a href="{{url('Storeissue/AddStoreissue')}}"><button class="btn btn-info">Add Store issue</button></a>
            </div> -->

            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                        <h6>Store Issue View Page</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12" id="filtersid"></div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="">
                            <a href="{{url('Storeissue/StoreissueList')}}" class="btn btn-outline-default materialbtn btn-primary" id-data="MaterialIssuePending">Material Issue Pending</a>
                            <a href="#" class="btn  btn-outline-default materialbtn" id-data="MaterialIssueClosed">Material Issue Closed</a>
                            <a href="{{url('Storeissue/StoreissueListDetails')}}" class="btn btn-primary btn-outline-default materialbtn" id-data="MaterialIssuedetail">Material Issue detail</a>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="FilterButtonnn">
                            <div class="raone">
                                <p class="raho MyToggle" id="MyToggle">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-funnel-fill" viewBox="0 0 16 16">
                                        <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z"></path>
                                    </svg>
                                </p>
                                <div class="ukom myFilter" id="myFilter">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="ToggleCheck" onclick="toggleCheckboxes()">
                                        <label class="form-check-label" for="ToggleCheck">All</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="NO" value="SL. No." onclick="filterTable(this)">
                                        <label class="form-check-label" for="NO">SL. No.</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="Creater_Name" value="Creater Name" onclick="filterTable(this)">
                                        <label class="form-check-label" for="Creater_Name">Creater Name</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="Request_No" value="Request No" onclick="filterTable(this)">
                                        <label class="form-check-label" for="Request_No">Request No</label>
                                    </div>
                                    {{-- <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="WorkOrderNo" value="Work Order No" onclick="filterTable(this)">
                                        <label class="form-check-label" for="WorkOrderNo">Work Order No</label>
                                    </div> --}}
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="Organization_Name" value="Organization Name" onclick="filterTable(this)">
                                        <label class="form-check-label" for="Organization_Name">Organization
                                            Name</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="ManufacturingUnit" value="Manufacturing Unit" onclick="filterTable(this)">
                                        <label class="form-check-label" for="ManufacturingUnit">Manufacturing Unit</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="Plant_Name" value="Plant Name" onclick="filterTable(this)">
                                        <label class="form-check-label" for="Plant_Name">Plant Name</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="GodownName" value="Godown Name" onclick="filterTable(this)">
                                        <label class="form-check-label" for="GodownName">Godown Name</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="Date_Time" value="Date &amp; Time" onclick="filterTable(this)">
                                        <label class="form-check-label" for="Date_Time">Date &amp; Time</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="Status" value="Status" onclick="filterTable(this)">
                                        <label class="form-check-label" for="Status">Status</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="Operation" value="Operation" onclick="filterTable(this)">
                                        <label class="form-check-label" for="Operation">Operation</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-content" id="ex1-content">
                    <div class="tab-pane fade show active ml-0 mt-1" id="All" role="tabpanel" aria-labelledby="Alls">
                        <div class="table-responsive MaterialIssue" id="MaterialIssueClosed">
                            <table id="" class="table table-striped table-bordered example w-100">
                                <thead>
                                    <tr>
                                        <th class="th-sm">SL. No.</th>
                                        <th class="th-sm">Creater Name</th>
                                        <th class="th-sm">Request No</th>
                                        {{-- <th class="th-sm">Work Order No</th> --}}
                                        <th class="th-sm">Organization Name</th>
                                        <th class="th-sm">Manufacturing Unit</th>
                                        <th class="th-sm">Plant Name</th>
                                        <th class="th-sm">Godown Name</th>
                                        <th class="th-sm">Date & Time</th>
                                        <th class="th-sm">Status</th>
                                        <th class="th-sm">Operation</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($closed as $key=>$val)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$val->Creater->fullname??'' }}</td>
                                        <td>{{$val->Request_No??'' }}</td>
                                        {{-- <td>{{$val->Work_Order_No??'' }}</td> --}}
                                        <td>{{$val->Organization->organisation ?? '' }}</td>
                                        <td>{{$val->Manufacturing->pname ?? '' }}</td>
                                        <td>{{$val->Plant_name->spname ?? '' }}</td>
                                        <td>{{$val->Godown_Name->inventory_name ?? '' }}</td>
                                        <td>{{$val->created_at ?? '' }}</td>
                                        <td id="statuss{{$val->id}}">
                                            @if($val->Store_issue_status==2)
                                            <span style="color:red">Forclosed</span>
                                            @elseif($val->Store_issue_status==4)
                                            <span style="color: #1bb81b;">Completed</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{url('Storeissue/AddStoreissue/'.$val->id)}}"><button type="button" class="btn btn-primary">View</button></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
    $(document).ready(function() {
        activeclass(23, 1);
    });
</script>
<script>
    $("#MyToggle").click(function() {
        $("#myFilter").toggle();
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
    var tableID = 2013;

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

    /////////////////////////////

    $(document).ready(function() {
        data = {
            Work_Order_No: '{{request()->Work_Order_No}}',
            Organization_Name: '{{request()->Organization_Name}}',
            Manufacturing_Unit: '{{request()->Manufacturing_Unit}}',
            Plant_Name: '{{request()->Plant_Name}}',
            Godown_Name: '{{request()->Godown_Name}}',
        }
        $.post("{{url('Storeissue/Filter')}}", data, function(data) {
            $("#filtersid").html(data);
            AppendSelect2()
        });
    });
</script>
@endpush
