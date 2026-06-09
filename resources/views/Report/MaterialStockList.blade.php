@extends('layout.main')
@section('main-container')
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
<title>Finished Good Material Stock List</title>

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
        /*width: 99%;*/
        display: flex;
        align-items: center;
        justify-content: flex-end;
        margin-top: -45px;
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
        <section class="section">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Finished Good Material Stock Report</li>
            </ol>
            <div class="addbtn">
                <!--<a href="{{url('Report/ExportMaterialStockData')}}"><i class='fa-file-excel fas text-success'></i></a>-->
                <a id="exportBtn" href="#"><i class='fa-file-excel fas text-success'></i></a>
                
            </div>

            <div class="row">
                <div class="container">
                    <form action="{{url('Report/filtered_finishedgoodstock')}}" method="POST">
                        @csrf
                        <div class="row filter">
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">Date From</label>
                                <input type="date" name="from_date" value="{{ old('from_date', $fromdate ?? '') }}" class="form-control form-control-sm">
                            </div>
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">Date To</label>
                                <input type="date" name="to_date" value="{{ old('to_date', $todate ?? '') }}" class="form-control form-control-sm">
                            </div>
                            <div class="col-2 mb-3">
                                <label for="" class="form-label fw-bold">Project</label>
                                    <select name="Manufacturing_Unit" class="form-select form-select-sm" id="Manunit">
                                        <option value="" selected>Select</option>
                                        @foreach($Manufacturing_unit as $val)
                                        <option value="{{$val->id}}" {{isset(request()->Manufacturing_Unit) && request()->Manufacturing_Unit==$val->id?'selected':''}}>{{$val->pname}}</option>
                                        @endforeach
                                    </select>
                            </div>
                            <div class="col-2 mb-3">
                                <label for="" class="form-label fw-bold">Sub Project</label>
                                    <select name="Plant_Name" id="Plant_Name" class="form-select form-select-sm" >
                                        <option value="" selected >Select</option>
                                        @php
                                            $sub_project = DB::table('prj_subproject')->where('pid', request()->Manufacturing_Unit)->get();
                                        @endphp
                                        @if(isset(request()->Plant_Name))
                                            @foreach($sub_project as $val)
                                            <option value="{{$val->id}}" {{isset(request()->Plant_Name) && request()->Plant_Name==$val->id?'selected':''}}>{{$val->spname}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                            </div>
                            <div class="col-2 mb-3">
                                <label for="" class="form-label fw-bold">Organization</label>
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
                                <a href="{{url('Report/material-stock-report')}}"><button type="button" class="btn btn-secondary"><i class="fa fa-refresh"></i></button></a>
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
                                                <input type="checkbox" class="form-check-input" id="NO" value="SL. No." onclick="filterTable(this)">
                                                <label class="form-check-label" for="NO">SL. No.</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="mat_Name" value="MATERIAL NAME" onclick="filterTable(this)">
                                                <label class="form-check-label" for="mat_Name">MATERIAL NAME</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="Datehsn_Time" value="HSN" onclick="filterTable(this)">
                                                <label class="form-check-label" for="hsn">HSN</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="uom" value="UOM" onclick="filterTable(this)">
                                                <label class="form-check-label" for="uom">UOM</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="lpp" value="LPP" onclick="filterTable(this)">
                                                <label class="form-check-label" for="lpp">LPP</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="op_balance" value="OPENING BALANCE" onclick="filterTable(this)">
                                                <label class="form-check-label" for="op_balance">OPENING BALANCE</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="tot_rcv" value="TOTAL RECEIVED" onclick="filterTable(this)">
                                                <label class="form-check-label" for="tot_rcv">TOTAL RECEIVED</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="tot_issued" value="TOTAL ISSUED" onclick="filterTable(this)">
                                                <label class="form-check-label" for="tot_issued">TOTAL ISSUED</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="close_balance" value="CLOSING BALANCE" onclick="filterTable(this)">
                                                <label class="form-check-label" for="close_balance">CLOSING BALANCE</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           
                        </div>
                    </form>
                    <div class="row">
                        <div class="table-responsive">
                            <table id="example2" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="th-sm">SL. No.</th>
                                        <th class="th-sm">MATERIAL NAME</th>
                                        <th class="th-sm">HSN</th>
                                        <th class="th-sm">UOM</th>
                                        <th class="th-sm">LPP</th>
                                        <th class="th-sm">OPENING BALANCE</th>
                                        <th class="th-sm">TOTAL RECEIVED</th>
                                        <th class="th-sm">TOTAL ISSUED</th>
                                        <th class="th-sm">CLOSING BALANCE</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @php $i = 1; @endphp

                                    @foreach($Materials as $key => $val)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>
                                            <a href="{{ url('Report/MaterialStockDetail', $val['data']->materialID) }}">
                                                {{ $Material_Name[$val['data']->materialID] ?? 'N/A' }}
                                            </a>
                                        </td>
                                        <td>{{ $val['HSN_Code'] ?? 'N/A' }}</td>
                                        <td>{{ $val['UOM'] ?? 'N/A' }}</td>
                                        <td>{{ $lpp_arr[$val['data']->materialID] ?? 'N/A' }}</td>
                                        <td>{{ $val['opening_qty'] ?? 0 }}</td>
                                        <td>{{ $val['total_received_qty'] ?? 0 }}</td>
                                        <td>{{ $val['total_issue_qty'] ?? 0 }}</td>
                                        <td>{{ $val['closing_qty'] ?? 0 }}</td>
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
    <br><br>
</div>
@endsection
@push('custom-scripts')
<script>
    $(document).ready(function() {
        $('#Manunit').change(function() {
            $('#org_name').val('');
            var ManunitId = $(this).val();

            if (ManunitId) {
                $.ajax({
                    url: "{{url('PPFinishedGood/get-plantnamedetails')}}" + '/' + ManunitId,
                    type: 'GET',
                    data: {
                        ManunitId : ManunitId
                      },
                    success: function(response) {
                        $('#Plant_Name').empty();
                        $('#Plant_Name').append('<option value="" selected disabled>Select</option>');
                        $.each(response, function(index, plantdetails) {
                            var option = $('<option>');
                            option.val(plantdetails.id);
                            option.text(plantdetails.spname);
                            $('#Plant_Name').append(option);
                        });
                    }
                });
            }
        });
    });
    $(document).ready(function() {
        activeclass(20, 3);
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
    var tableID = 96;

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
document.getElementById('exportBtn').addEventListener('click', function (e) {
    e.preventDefault();
    const form = document.querySelector('form');
    const params = new URLSearchParams(new FormData(form)).toString();
    window.location.href = "{{ url('Report/ExportMaterialStockData') }}?" + params;
});
</script>

@endpush
