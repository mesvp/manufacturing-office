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

    button.ravind {
        background: transparent;
        color: black;
        border: 1px solid #ddd;
        padding: 5px;
        margin: 0;
        font-size: 12px;
    }

    div#jagdish {
        display: flex;
        justify-content: flex-start;
        align-content: center;
        align-items: center;
        flex-wrap: wrap;
    }

    div#jagdish .col-md-1 {
        width: fit-content !important;
    }

    button#omjhhggg {
        width: max-content;
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
                <li class="breadcrumb-item">PP Finished Good Approve List</li>
            </ol>

            <div class="row">
                <div class="container">
                    <form action="{{url('PPFinishedGood/filtered')}}" method="POST">
                        @csrf
                        <div class="row filter">
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">Date From</label>
                                <input type="date" name="from_date" value="{{isset($fromdate) && $fromdate!=''?$fromdate:''}}" class="form-control">
                            </div>
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">Date To</label>
                                <input type="date" name="to_date" value="{{isset($todate) && $todate!=''?$todate:''}}" class="form-control">
                            </div>
                            <div class="col-2 mt-4">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                <a href="{{url('PPFinishedGood/PPFinishedGoodList')}}"><button type="button" class="btn btn-secondary"><i class="fa fa-refresh"></i></button></a>
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
                                            <input type="checkbox" class="form-check-input" id="NO" value="SL. No." onclick="filterTable(this)">
                                            <label class="form-check-label" for="NO">SL. No.</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Creater_Name" value="Creater Name" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Creater_Name">Creater Name</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Date_Time" value="Date & Time" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Date_Time">Date & Time</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Organization" value="Organization" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Organization">Organization</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Manufacturing Unit" value="Manufacturing Unit" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Manufacturing Unit">Manufacturing Unit</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Plant_Name" value="Plant Name" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Plant_Name">Plant Name</label>
                                        </div>
                                        {{-- <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Category" value="Category" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Category">Category</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Product" value="Product" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Product">Product</label>
                                        </div> --}}
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="For_Month" value="For Month" onclick="filterTable(this)">
                                            <label class="form-check-label" for="For_Month">For Month</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="QTY" value="QTY" onclick="filterTable(this)">
                                            <label class="form-check-label" for="QTY">QTY</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Raw_Material" value="Raw Material(FG)" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Raw_Material">Raw Material(FG)</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="HSN_Code" value="HSN Code(FG)" onclick="filterTable(this)">
                                            <label class="form-check-label" for="HSN_Code">HSN Code(FG)</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="UOM" value="UOM" onclick="filterTable(this)">
                                            <label class="form-check-label" for="UOM">UOM</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Per_Day" value="Per Day" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Per_Day">Per Day</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Per_Shift" value="Per Shift" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Per_Shift">Per Shift</label>
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
                    </form>
                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="th-sm">SL. No.</th>
                                    <th class="th-sm">Creater Name</th>
                                    <th class="th-sm">Date & Time</th>
                                    <th class="th-sm">Organization</th>
                                    <th class="th-sm">Manufacturing Unit</th>
                                    <th class="th-sm">Plant Name</th>
                                    {{-- <th class="th-sm">Category</th>
                                    <th class="th-sm">Product</th> --}}
                                    <th class="th-sm">For Month</th>
                                    <th class="th-sm">QTY</th>
                                    <th class="th-sm">Raw Material(FG)</th>
                                    <th class="th-sm">HSN Code(FG)</th>
                                    <th class="th-sm">UOM</th>
                                    <th class="th-sm">Per Day</th>
                                    <th class="th-sm">Per Shift</th>
                                    <th class="th-sm">Status</th>
                                    <th class="th-sm">Pending With</th>
                                    <th class="th-sm">Operation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $sesionarr=[];
                                @endphp
                                @foreach($PP_data as $key=>$val)
                                @php
                                $sesionarr['ALL'][]=$val->id;
                                @endphp
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{isset($val->user->fullname) && $val->user->fullname!=''?$val->user->fullname:''}}</td>
                                    <td>{{isset($val->created_at) && $val->created_at!=''?date('d-m-Y H:i:s A',strtotime($val->created_at)):''}}</td>
                                    <td>{{isset($val->Organization->organisation) && $val->Organization->organisation!=''?$val->Organization->organisation:''}}</td>
                                    <td>{{isset($val->Manufacturing_Unit->pname) && $val->Manufacturing_Unit->pname!=''?$val->Manufacturing_Unit->pname:''}}</td>
                                    <td>{{isset($val->plant_name->spname) && $val->plant_name->spname!=''?$val->plant_name->spname:''}}</td>
                                    {{-- <td>{{isset($val->category->category) && $val->category->category!=''?$val->category->category:''}}</td>
                                    <td>{{isset($val->Product->product) && $val->Product->product!=''?$val->Product->product:''}}</td> --}}
                                    <td>{{isset($val->data->For_Primary) && $val->data->For_Primary!=''?$val->data->For_Primary:''}}</td>
                                    <td>{{isset($val->data->QTY) && $val->data->QTY!=''?$val->data->QTY:''}}</td>
                                    <td>{{isset($val->RawMaterial->matname) && $val->RawMaterial->matname!=''?$val->RawMaterial->matname:''}}</td>
                                    <td>{{isset($val->data->HSN_Code) && $val->data->HSN_Code!=''?$val->data->HSN_Code:''}}</td>
                                    <td>{{isset($val->data->UOM) && $val->data->UOM!=''?$val->data->UOM:''}}</td>
                                    <td>{{isset($val->data->Per_Day) && $val->data->Per_Day!=''?$val->data->Per_Day:''}}</td>
                                    <td>{{isset($val->data->Per_Shift) && $val->data->Per_Shift!=''?$val->data->Per_Shift:''}}</td>
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
                                        @if($val->Approve_status=='' || $val->Approve_status==='FORWARD')
                                        Pending With
                                        @foreach($val->PendingWith as $name)
                                        {{isset($name->fullname) && $name->fullname!=''?$name->fullname:''}},
                                        @endforeach
                                        @elseif($val->Approve_status=='RECHECK' || $val->Approve_status=='OBJECT')
                                        {{isset($val->user->fullname) && $val->user->fullname!=''?'Pending With '.$val->user->fullname:''}}
                                        @endif
                                    </td>
                                    <td><a href="{{url('PPFinishedGood/view-approve/'.$val->id.'/ALL')}}" class="btn btn-primary">View</a></td>
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
        </section>
    </div>
</div>
</div>
</div>
</section>
@endsection
@push('custom-scripts')
<script>
    $(document).ready(function() {
        activeclass(13, 2);
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

        var table = document.querySelector('table');
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

        var CollumValue = columnNamesToShow.join(',');

        fetch("{{ url('PPFinishedGood/getCheckBoxData') }}?ID=" + tableID, {
                method: 'GET',
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.columns) {
                    try {
                        var existingData = data.columns;
                        if (JSON.stringify(existingData) !== JSON.stringify(columnNamesToShow)) {
                            fetch("{{ url('PPFinishedGood/CheckBoxStore') }}", {
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

        fetch("{{ url('PPFinishedGood/getCheckBoxData') }}?ID=" + tableID, {
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