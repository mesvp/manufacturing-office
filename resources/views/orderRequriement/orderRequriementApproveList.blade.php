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
            <ol class="border-bottom breadcrumb">
                <li class="breadcrumb-item">Procurement Request Sales Approve Page</li>
            </ol>

            <div class="row">
                <div class="container-fluid">
                    <form action="{{url('orderRequirement/orderRequirementApproveStockList')}}" method="POST">
                        @csrf
                        <div class="row filter">
                            <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 form-group">
                                <label for="" class="form-label">Date From</label>
                                <input type="date" name="from_date" value="{{isset($fromdate) && $fromdate!=''?$fromdate:''}}" class="form-control form-control-sm">
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 form-group">
                                <label for="" class="form-label">Date To</label>
                                <input type="date" name="to_date" value="{{isset($todate) && $todate!=''?$todate:''}}" class="form-control form-control-sm">
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-6 col-sm-6 form-group mt-4">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                <a href=""><button type="button" class="btn btn-secondary"><i class="fa fa-refresh"></i></button></a>
                            </div>
                            <div class="col-xl-6 col-lg-3 col-md-6 col-sm-6 form-group mt-4">
                                <div class="FilterButtonnn sales_fields">
                                    <div class="raone">
                                        <p class="raho MyToggle" id="MyToggle">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-funnel-fill" viewBox="0 0 16 16">
                                                <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.224L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z" />
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
                                                <input type="checkbox" class="form-check-input" id="Order_Type" value="Order Type" onclick="filterTable(this)">
                                                <label class="form-check-label" for="Order_Type">Order Type</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="Procurement_Type" value="Procurement Type" onclick="filterTable(this)">
                                                <label class="form-check-label" for="Order_Type">Procurement Type</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="Sales_Order_No" value="PR No" onclick="filterTable(this)">
                                                <label class="form-check-label" for="Sales_Order_No">PR No</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="Organization_Name" value="Organization Name" onclick="filterTable(this)">
                                                <label class="form-check-label" for="Organization_Name">Organization Name</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="BU_Name" value="BU Name" onclick="filterTable(this)">
                                                <label class="form-check-label" for="BU_Name">BU Name</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="Unit_Name" value="Unit Name" onclick="filterTable(this)">
                                                <label class="form-check-label" for="Unit_Name">Unit Name</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="Plant_Name" value="Plant Name" onclick="filterTable(this)">
                                                <label class="form-check-label" for="Plant_Name">Plant Name</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="Godowm_Name" value="Godown Name" onclick="filterTable(this)">
                                                <label class="form-check-label" for="Godowm_Name">Godown Name</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="Raw_Material(FG)" value="Finished Good(FG)" onclick="filterTable(this)">
                                                <label class="form-check-label" for="Raw_Material(FG)">Finished Good(FG)</label>
                                            </div>
                                            {{-- <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="Company_Name" value="Company Name" onclick="filterTable(this)">
                                                <label class="form-check-label" for="Company_Name">Company Name</label>
                                            </div> --}}
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="Expected_Date" value="Expected Date" onclick="filterTable(this)">
                                                <label class="form-check-label" for="Expected_Date">Expected Date</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="QTY" value="QTY" onclick="filterTable(this)">
                                                <label class="form-check-label" for="QTY">QTY</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="Date_Time" value="Date & Time" onclick="filterTable(this)">
                                                <label class="form-check-label" for="Date_Time">Date & Time</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="total_amount" value="Total Amount" onclick="filterTable(this)">
                                                <label class="form-check-label" for="total_amount">Total Amount</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="Status" value="Status" onclick="filterTable(this)">
                                                <label class="form-check-label" for="Status">Status</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="Pending_With" value="Pending With" onclick="filterTable(this)">
                                                <label class="form-check-label" for="Pending_With">Pending With</label>
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
                    </form>
                    <!-- <div class="" id="main_btn_uddhan">
                        <a href="" type="button" class="btn btn-default" id="stock">Requisition List</a>
                        <button type="button" class="btn btn-success" id="sales" >Sales List</button>
                        {{-- <a href="{{url('orderRequirement/orderRequirementApproveList')}}" type="button" class="btn btn-success" id="sales">Sales List</a> --}}

                    </div> -->

                    <div class="sales_fields">
                        <div class="tab-content" id="ex1-content">
                            <div class="active fade ml-0 show tab-pane" id="SalesAll" role="tabpanel" aria-labelledby="SalesAlls">
                                <div class="table-responsive">
                                    <table id="" class="table table-sm table-bordered example">
                                        <thead>
                                            <tr>
                                                <th class="th-sm">SL. No.</th>
                                                <th class="th-sm">Creater Name</th>
                                                <th class="th-sm">Order Type</th>
                                                <th class="th-sm">Procurement Type</th>
                                                <th class="th-sm">PR No</th>
                                                <th class="th-sm">Organization Name</th>
                                                <th class="th-sm">BU Name</th>
                                                <th class="th-sm">Godown Name</th>
                                                <th class="th-sm">Unit Name</th>
                                                <th class="th-sm">Plant Name</th>
                                                <th class="th-sm">Finished Good(FG)</th>
                                                <th class="th-sm">Expected Date</th>
                                                <th class="th-sm">QTY</th>
                                                {{-- <th class="th-sm">Company Name</th> --}}
                                                <th class="th-sm">Date & Time</th>
                                                <th class="th-sm">Total Amount</th>
                                                <th class="th-sm">Status</th>
                                                <th class="th-sm">Pending With</th>
                                                <th class="th-sm">Operation</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                            $sesionarr=[];
                                            @endphp
                                            @foreach($Stock as $key => $val)
                                            @php
                                            if(isset($val->status) && $val->status!=1)
                                            {
                                            $sesionarr['ALL'][]=$val->id;
                                            }
                                            @endphp
                                            <tr @if($val->procurement_type == "Additional")
                                                style="background-color: #ff6f005e;"
                                                @elseif($val->procurement_type == "Loose")
                                                    style="background-color: #FAFAD2;"
                                                @endif>
                                                <td>{{$key+1}}</td>
                                                <td>{{isset($val->user->fullname) && $val->user->fullname != ''?$val->user->fullname:''}}</td>

                                                <td>{{$val->Work_Order_Type}}</td>
                                                <td>{{$val->procurement_type}}</td>
                                                <td>{{$val->Stock_Order_No}}</td>
                                                <td>{{isset($val->organisation->organisation) && $val->organisation->organisation != ''?$val->organisation->organisation:''}}</td>
                                                <td>{{isset($val->buname->unit_name) && $val->buname->unit_name != ''?$val->buname->unit_name:''}}</td>
                                                <td>{{isset($val->inventoryname->inventory_name) && $val->inventoryname->inventory_name != ''?$val->inventoryname->inventory_name:''}}</td>
                                                <td>{{isset($val->projectname->pname) && $val->projectname->pname != ''?$val->projectname->pname:''}}</td>
                                                <td>{{isset($val->plantname->spname) && $val->plantname->spname != ''?$val->plantname->spname:''}}</td>
                                                <td>{{isset($val->RawMaterial->matname) && $val->RawMaterial->matname != ''?$val->RawMaterial->matname:''}}</td>
                                                <td>{{$val->Expected_Date??''}}</td>
                                                <td>{{$val->QTY??''}}</td>
                                                {{-- <td>{{$Company_Name[$val->Company_Name]??''}}</td> --}}
                                                <td>{{isset($val->created_at) && $val->created_at!=''?date('d-m-Y H:i:s A',strtotime($val->created_at)):''}}</td>
                                                <td>{{isset($val->Total) && $val->Total != ''?$val->Total:''}}</td>
                                                <td id="statuss{{$val->id}}">
                                                    @if($val->Approve_status=='APPROVE')
                                                    <span style="color: #1bb81b;">APPROVED</span>
                                                    @elseif($val->Approve_status=='REJECT')
                                                    <span style="color:red">REJECTED</span>
                                                    @elseif($val->Approve_status=='RECHECK')
                                                    <span style="color:#71a5ee">RECHECK</span>
                                                    @elseif($val->Approve_status=='OBJECT')
                                                    <span style="color:#da2aff">OBJECT</span>
                                                    @elseif($val->Approve_status=='HOLD')
                                                    <span style="color:#0cbad6">HOLD</span>
                                                    @else
                                                    <span style="color: #FF9000;">Pending</span>
                                                    @endif
                                                </td>
                                                <td class="PendingColor">
                                                    @if(($val->Approve_status==='FORWARD' && isset($val->status) && $val->status!=1) || ($val->Approve_status=='' && isset($val->status) && $val->status!=1))
                                                    Pending With
                                                    @foreach($val->PendingWith as $name)
                                                    {{isset($name->fullname) && $name->fullname!=''?$name->fullname:''}},
                                                    @endforeach
                                                    @elseif($val->Approve_status=='RECHECK' || $val->Approve_status=='OBJECT')
                                                    {{isset($val->user->fullname) && $val->user->fullname!=''?'Pending With '.$val->user->fullname:''}}
                                                    @endif
                                                </td>
                                                <td class="maindffd">
                                                    <a href="{{url('orderRequirement/Stock-view-approve/'.$val->id.'/ALL')}}" class="btn btn-primary btn-sm"><i class="fa-solid fa-eye"></i> View</a>
                                                </td>
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
        </section>
    </div>
</div>
@endsection
@push('custom-scripts')
<script>
    $(document).ready(function() {
        activeclass(25, 2);
        loadCheckBoxes()
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var MyToggles = document.querySelectorAll(".MyToggle");
        var myFilters = document.querySelectorAll(".myFilter");

        MyToggles.forEach(function(MyToggle, index) {
            MyToggle.addEventListener("click", function() {
                myFilters[index].classList.toggle("show-div");
            });
        });

        document.addEventListener("click", function(event) {
            MyToggles.forEach(function(MyToggle, index) {
                if (!myFilters[index].contains(event.target) && !MyToggle.contains(event.target)) {
                    myFilters[index].classList.remove("show-div");
                }
            });
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
    function checkBoxess() {

        let tableID = 224;
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

    function loadCheckBoxes() {

        let tableID = 224;
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
    }
</script>
<script>
    document.getElementById('sales').addEventListener('click', function() {
        alert('This feature is currently under maintenance. We apologize for any inconvenience caused.');
    });
</script>
@endpush
