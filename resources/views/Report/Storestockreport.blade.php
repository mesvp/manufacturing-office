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
@php
$Department=Session::get('Department');
$EXT=Session::get('EXT');
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
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Store Stock Report</li>
            </ol>
            <div class="addbtn">
                <a href="{{url('Report/exportdata')}}">
                    <i class="fas fa-file-excel"></i>
                </a>
            </div>

            <div class="row">
                <div class="container">
                    <form action="{{url('Report/filtered')}}" method="POST">
                        @csrf
                        <div class="row filter">
                            <?php
                            // $fromdate = isset($fromdate) && $fromdate != '' ? $fromdate : date('Y-m-d', strtotime('-1 month'));
                            // $todate = isset($todate) && $todate != '' ? $todate : date('Y-m-d');
                            $fromdate = isset($fromdate) && $fromdate != '' ? $fromdate : date('Y-01-01');
                            $todate = isset($todate) && $todate != '' ? $todate : date('Y-m-d');
                            ?>
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">Date From</label>
                                <input type="date" name="from_date" value="<?php echo $fromdate; ?>" class="form-control form-control-sm">
                            </div>
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">Date To</label>
                                <input type="date" name="to_date" value="<?php echo $todate; ?>" class="form-control form-control-sm">
                            </div>
            
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">Material Name</label>
                                <select name="Material_Name" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all" {{isset($Materialss) && $Materialss === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach($Material_Name as $val)
                                    <option value="{{ isset($val->id) && $val->id!=''?$val->id:'' }}" {{isset($Materialss) && $Materialss==$val->id?'selected':''}}>{{ isset($val->material_name) && $val->material_name!=''?$val->material_name:''}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">Organization</label>
                                    <select name="Organization" class="form-select form-select-sm" >
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Organization as $val)
                                        <option value="{{$val->id}}" {{isset(request()->Organization) && request()->Organization==$val->id?'selected':''}}>{{isset($val->organisation) && $val->organisation!=''?$val->organisation:''}}</option>
                                        @endforeach
                                    </select>
                            </div>
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">Godown</label>
                                    <select name="Godown_Name" id="Godown_Name" class="form-select form-select-sm js-example-matcher-start" >
                                        <option value="" selected >Select</option>
                                        @foreach($Godown_Name as $val)
                                        <option value="{{$val->id}}" {{isset(request()->Godown_Name) && request()->Godown_Name==$val->id?'selected':''}}>{{isset($val->inventory_name) && $val->inventory_name!=''?$val->inventory_name:''}}</option>
                                        @endforeach
                                    </select>
                            </div>
                            <div class="col-2 mt-4">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                <a href="{{url('Report/storestockreport')}}"><button type="button" class="btn btn-secondary"><i class="fa fa-refresh"></i></button></a>
                            </div>
                            <div class="FilterButtonnn">
                                <div class="raone">
                                    <p class="raho" id="MyToggle">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-funnel-fill" viewBox="0 0 16 16">
                                            <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z" />
                                        </svg>
                                    </p>
                                    <div class="ukom" id="myFilter">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="ToggleCheck" onclick="toggleCheckboxes()">
                                            <label class="form-check-label" for="ToggleCheck">All</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="NO" value="Sl No." onclick="filterTable(this)">
                                            <label class="form-check-label" for="NO">Sl No.</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Creater_Name" value="Material Name" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Creater_Name">Material Name</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Date_Time" value="UOM" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Date_Time">UOM</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Raw_Material" value="LPP" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Raw_Material">LPP</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="HSN_Code" value="Total Opening Qty." onclick="filterTable(this)">
                                            <label class="form-check-label" for="HSN_Code">Total Opening Qty.</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="HSN_Code" value="Total Opening Bal." onclick="filterTable(this)">
                                            <label class="form-check-label" for="HSN_Code">Total Opening Bal.</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="HSN_Code" value="Total Receive Qty." onclick="filterTable(this)">
                                            <label class="form-check-label" for="HSN_Code">Total Receive Qty.</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="UOM" value="Total Receive Amt." onclick="filterTable(this)">
                                            <label class="form-check-label" for="Total Receive Amt.">Total Receive Amt.</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Status" value="Total Issue Qty." onclick="filterTable(this)">
                                            <label class="form-check-label" for="Status">Total Issue Qty.</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Pending_With" value="Total Issued Amt." onclick="filterTable(this)">
                                            <label class="form-check-label" for="Pending_With">Total Issued Amt.</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Operation" value="Closing Balance" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Operation">Closing Balance</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Operation" value="Closing Amt." onclick="filterTable(this)">
                                            <label class="form-check-label" for="Operation">Closing Amt.</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <ul class="nav nav-tabs mb-3" id="ex1" role="tablist">
                       
                    </ul>
                    <div class="tab-content" id="ex1-content">
                        <div class="tab-pane fade show active" id="All" role="tabpanel" aria-labelledby="Alls">
                            <div class="table-responsive">
                                <table id="example2" class="table table-sm table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="th-sm">Sl No.</th>
                                            <th class="th-sm">Material Name</th>
                                            <th class="th-sm">UOM</th>
                                            <th class="th-sm">LPP</th>
                                            <th class="th-sm">Total Opening Qty.</th>
                                            <th class="th-sm">Total Opening Bal.</th>
                                            <th class="th-sm">Total Receive Qty.</th>
                                            <th class="th-sm">Total Receive Amt.</th>
                                            <th class="th-sm">Total Issue Qty.</th>
                                            <th class="th-sm">Total Issued Amt.</th>
                                            <th class="th-sm">Closing Balance</th>
                                            <th class="th-sm">Closing Amt.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $sesionarr=[];
                                        @endphp
                                        @foreach($Materials as $key => $val)
                                        {{-- <tr>
                                            <td>{{$key+1}}</td>
                                             <td><a href="{{ url('Report/storestockreportdetails', $val->id) }}">{{isset($val->matname) && $val->matname!=''?$val->matname:''}}</a></td>
                                            <td>{{isset($val->UOM) && $val->UOM!=''?$val->UOM:''}}</td>
                                            <td>{{ $lpp_arr[$val->id] ?? 'N/A' }}</td>
                                            <td>{{ $opening_qty_arr[$val->id] ?? 'N/A' }}</td>
                                            <td>{{ $opening_amt_arr[$val->id] ?? 'N/A' }}</td>
                                            <td>{{ $total_qty_arr[$val->id] ?? 'N/A' }}</td>
                                            <td>{{ $total_mat_amt_arr[$val->id] ?? 'N/A' }}</td>
                                            <td>{{ $total_iss_qty_arr[$val->id] ?? 'N/A' }}</td>
                                            <td>{{ $val->issueprice ?? 'N/A' }}</td>
                                            <td>{{ $closing_qty_arr[$val->id] ?? 'N/A' }}</td>
                                            <td>{{ $closing_amt_arr[$val->id] ?? 'N/A' }}</td>

                                        </tr> --}}
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td><a href="{{ url('Report/storestockreportdetails', $val->id) }}">{{isset($val->matname) && $val->matname != '' ? $val->matname : ''}}</a></td>
                                            <td>{{isset($val->UOM) && $val->UOM != '' ? $val->UOM : ''}}</td>
                                            <td>{{ $lpp_arr[$val->id] ?? 'N/A' }}</td>
                                            <td>{{ $opening_qty_arr[$val->id] ?? 'N/A' }}</td>
                                            <td>{{ $opening_amt_arr[$val->id] ?? 'N/A' }}</td>
                                            <td>{{ $total_qty_arr[$val->id] ?? 'N/A' }}</td>
                                            <td>{{ $total_mat_amt_arr[$val->id] ?? 'N/A' }}</td>
                                            <td>{{ $total_iss_qty_arr[$val->id] ?? 'N/A' }}</td>
                                            <td>{{ $val->issueprice ?? 'N/A' }}</td>
                                            @php
                                                $closing_qty = $closing_qty_arr[$val->id] ?? 'N/A';
                                                $closing_amt = $closing_amt_arr[$val->id] ?? 'N/A';
                                                if ($closing_qty <= 0) {
                                                    $closing_qty = $opening_qty_arr[$val->id] ?? 'N/A';
                                                    $closing_amt = $opening_amt_arr[$val->id] ?? 'N/A';
                                                }
                                            @endphp
                                            <td>{{ $closing_qty }}</td>
                                            <td>{{ $closing_amt }}</td>
                                        </tr>
                                        
                                        @endforeach
                                        @php
                                        Session::put('nexdata',$sesionarr);
                                        @endphp
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        
                    </div>
                </div>
            </div>
    </div>
    <br> <br>
</div>
</section>
</div>
</div>
</section>
@endsection
@push('custom-scripts')
<script>
    $(document).ready(function() {
        activeclass(20, 1);
    });
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
    var tableID = 13;

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

        fetch("{{ url('ProductionProcess/getCheckBoxData') }}?ID=" + tableID, {
                method: 'GET',
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.columns) {
                    try {
                        var existingData = data.columns;
                        if (JSON.stringify(existingData) !== JSON.stringify(columnNamesToShow)) {
                            fetch("{{ url('ProductionProcess/CheckBoxStore') }}", {
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

        fetch("{{ url('ProductionProcess/getCheckBoxData') }}?ID=" + tableID, {
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
<script>
    $(document).ready(function() {
        $.ajax({
            url: "{{ url('ProductionProcess/CheckHoldExpiry') }}",
            method: 'GET',
            success: function(response) {
                response.forEach(function(lead) {
                    if (lead.action === 'HOLD' && lead.status === 1) {
                        var currentDate = new Date();
                        var holdDate = new Date(lead.days_for_holding);

                        if (holdDate < currentDate) {
                            UpdateStatus(lead.Production_Process_id, lead.userID);
                        }
                    }
                });
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    });

    function UpdateStatus(Production_Process_id, userID) {
        $.ajax({
            url: "{{ url('ProductionProcess/UpdateStatus') }}",
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            data: JSON.stringify({
                Production_Process_id: Production_Process_id,
                userID: userID
            }),
            success: function(response) {
                $('#statuss' + Production_Process_id).html('<span style="color: #FF9000;">Pending</span>');
            }
        });
    }
</script>
@endpush