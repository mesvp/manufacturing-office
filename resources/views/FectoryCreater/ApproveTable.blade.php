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

    select.custom-select.custom-select-sm.form-control.form-control-sm {
        margin-top: 3px;
    }

    .gowami input[type=checkbox]+label {
        border: 1px solid #ddd;
        padding: 5px;
        /* margin: 10px 5px; */
        margin-left: 0px;
        margin-right: 5px !important;
        margin-bottom: 5px !important;
        display: block !important;
        border-radius: 5px;
        text-transform: uppercase;
        font-size: 11px;
    }

    .gowami input[type=checkbox]:checked+label {
        border: 1px solid #ddd;
        padding: 5px;
        background: #FF9000;
        color: white;
        margin-left: 0px;
        margin-right: 5px !important;
        width: max-content !important;
        display: block !important;
    }

    .CheckFilter {
        display: none;
    }

    .okmila {
        cursor: pointer;
    }

    .gowami {
        margin: 20px 0px;
        display: flex;
        align-items: center;
        align-content: center;
        flex-wrap: wrap;
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
        <section class="section">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Factory Approve View Page </li>
            </ol>
            <div class="row">
                <div class="container">
                    <form action="{{url('FactoryCreater/Filter-approve')}}" method="POST">
                        @csrf
                        <div class="row filter">
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">Date From</label>
                                <input type="date" name="from_date" value="{{isset($fromdate) && $fromdate!=''?$fromdate:''}}" class="form-control form-control-sm">
                            </div>
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">Date To</label>
                                <input type="date" name="to_date" value="{{isset($todate) && $todate!=''?$todate:''}}" class="form-control form-control-sm">
                            </div>
                            <div class="col-7 mt-4">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                <a href="{{url('FactoryCreater/factory-approve')}}"><button type="button" class="btn btn-secondary"><i class="fa fa-refresh"></i></button></a>
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
                                            <input type="checkbox" class="form-check-input" id="Name_Of_The_Unit" value="Name Of The Unit" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Name_Of_The_Unit">Name Of The Unit</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="District_State" value="District/State" onclick="filterTable(this)">
                                            <label class="form-check-label" for="District_State">District/State</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Total_Plant" value="Total Plant" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Total_Plant">Total Plant</label>
                                        </div>
                                        {{-- <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Capacity" value="Capacity" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Capacity">Capacity</label>
                                        </div> --}}
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="GST_No" value="GST No." onclick="filterTable(this)">
                                            <label class="form-check-label" for="GST_No">GST No.</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Factory_License" value="Factory License" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Factory_License">Factory License</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Land_Type" value="Land Type" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Land_Type">Land Type</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Total_Godown" value="Total Godown" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Total_Godown">Total Godown</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Total_Rack" value="Total Rack" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Total_Rack">Total Rack</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Rack_Capacity" value="Rack Capacity" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Rack_Capacity">Rack Capacity</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Total_Bin" value="Total Bin" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Total_Bin">Total Bin</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Bin_Cap" value="Bin Cap." onclick="filterTable(this)">
                                            <label class="form-check-label" for="Bin_Cap">Bin Cap.</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Pending_With" value="Pending With" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Pending_With">Pending With</label>
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
                    </form>
                    <div class="table-responsive">
                        <table id="" class="table table-striped table-bordered example" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="th-sm">SL. No.</th>
                                    <th class="th-sm">Creater Name</th>
                                    <th class="th-sm">Date & Time</th>
                                    <th class="th-sm">Organization</th>
                                    <th class="th-sm">Name Of The Unit</th>
                                    <th class="th-sm">District/State</th>
                                    <th class="th-sm">Total Plant</th>
                                    <th class="th-sm">GST No.</th>
                                    <th class="th-sm">Factory License</th>
                                    <th class="th-sm">Land Type</th>
                                    <th class="th-sm">Total Godown</th>
                                    <th class="th-sm">Total Rack</th>
                                    <th class="th-sm">Rack Capacity</th>
                                    <th class="th-sm">Total Bin</th>
                                    <th class="th-sm">Bin Cap.</th>
                                    <th class="th-sm">Status</th>
                                    <th class="th-sm">Pending With</th>
                                    <th class="th-sm">Operation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $sesionarr=[];
                                @endphp
                                @foreach($addressDetails as $key => $val)
                                @php
                                $sesionarr['ALL'][]=$val->id;
                                @endphp
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{isset($val->user->fullname) && $val->user->fullname!=''?$val->user->fullname:''}}</td>
                                    <td>{{isset($val->created_at) && $val->created_at!=''?date('d-m-Y H:i:s A',strtotime($val->created_at)):''}}</td>
                                    <td>{{isset($val->org->organisation) && $val->org->organisation!=''?$val->org->organisation:''}}</td>
                                    <td>{{isset($val->unitname->pname) && $val->unitname->pname!=''?$val->unitname->pname:''}}</td>
                                    <td>{{isset($val->districtss->distname) && $val->districtss->distname!=''?$val->districtss->distname:''}}/{{isset($val->statess->sname) && $val->statess->sname!=''?$val->statess->sname:''}}</td>
                                    <td>{{$val->plantsCount}}</td>
                                    <td>{{isset($val->gst->gst_no) && $val->gst->gst_no!=''?$val->gst->gst_no:''}}</td>
                                    <td>{{isset($val->factoryLicense->factory_license_no) && $val->factoryLicense->factory_license_no!=''?$val->factoryLicense->factory_license_no:''}}</td>
                                    <td>{{isset($val->landtype->land_type) && $val->landtype->land_type!=''?$val->landtype->land_type:''}}</td>
                                    <td>{{isset($val->WareHouseRoom->Total_Warehouse) && $val->WareHouseRoom->Total_Warehouse!=''?$val->WareHouseRoom->Total_Warehouse:''}}</td>
                                    <td>{{isset($val->store->Total_Rack) && $val->store->Total_Rack!=''?$val->store->Total_Rack:''}}</td>
                                    <td>{{isset($val->store->Rack_Capacity) && $val->store->Rack_Capacity!=''?$val->store->Rack_Capacity:''}}</td>
                                    <td>{{isset($val->store->Total_Bin) && $val->store->Total_Bin!=''?$val->store->Total_Bin:''}}</td>
                                    <td>{{isset($val->store->Total_Bin_Capacity) && $val->store->Total_Bin_Capacity!=''?$val->store->Total_Bin_Capacity:''}}</td>
                                    <td id="statuss{{$val->id}}">
                                        @if($val->Approve_status=='APPROVE')
                                        <span style="color: #1bb81b;">APPROVED</span>
                                        @elseif($val->Approve_status=='REJECT')
                                        <span style="color:red">REJECTED</span>
                                        @elseif($val->Approve_status=='RECHECK')
                                        <span style="color:#71a5ee">RECHECK</span>
                                        @elseif($val->Approve_status=='OBJECT')
                                        <span style="color:#da2aff">OBJECT</span>
                                        @elseif($val->Approve_status=='QUERY')
                                        <span style="color:#da2aff">QUERY</span>
                                        @elseif($val->Approve_status=='HOLD')
                                        <span style="color:#0cbad6">HOLD</span>
                                        @else
                                        <span style="color: #FF9000;">Pending</span>
                                        @endif
                                    </td>
                                    <td class="PendingColor">
                                        @if(($val->Approve_status==='FORWARD' && isset($val->store->status) && $val->store->status!=1) || ($val->Approve_status=='' && isset($val->store->status) && $val->store->status!=1))
                                        Pending With
                                        @foreach($val->PendingWith as $name)
                                        {{isset($name->fullname) && $name->fullname!=''?$name->fullname:''}},
                                        @endforeach
                                        @elseif($val->Approve_status=='RECHECK' || $val->Approve_status=='OBJECT')
                                        {{isset($val->user->fullname) && $val->user->fullname!=''?'Pending With '.$val->user->fullname:''}}
                                        @endif
                                    </td>
                                    <td class="maindffd">
                                        <a href="{{url('FactoryCreater/view-approve/'.$val->id.'/ALL')}}" class="btn btn-primary">View</a>
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
    <br> <br>
</div>
</section>
</div>
</div>
</section>
@endsection
@push('custom-scripts')
<script>
    activeclass(5, 2);
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
    var tableID = 2;

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

        fetch("{{ url('FactoryCreater/getCheckBoxData') }}?ID=" + tableID, {
                method: 'GET',
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.columns) {
                    try {
                        var existingData = data.columns;
                        if (JSON.stringify(existingData) !== JSON.stringify(columnNamesToShow)) {
                            fetch("{{ url('FactoryCreater/CheckBoxStore') }}", {
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

        fetch("{{ url('FactoryCreater/getCheckBoxData') }}?ID=" + tableID, {
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