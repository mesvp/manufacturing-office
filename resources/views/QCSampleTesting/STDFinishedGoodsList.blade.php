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

    .left-bar p {
        margin: 4% !important;
    }

    .activesle {
        background: #6741D5 !important;
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
                <li class="breadcrumb-item">Sample Testing Details Finished Goods View Page</li>
            </ol>
            <div class="addbtn">
            <a href="{{url('QCSampleTesting/exportdata')}}"><i class="fas fa-file-excel"></i></a>
            @if(isset($EXT[9]['inputer']) || auth()->user()->role==0)
                <a href="{{url('QCSampleTesting/STDFinishedGoods')}}"><button class="btn btn-info">Add STD Finished Goods</button></a>
            @endif
            </div>
            <div class="row">
                <div class="container">
                <form  method="POST">
                        @csrf
                        <div class="row filter">
                            <div class="col-sm-3 mb-3">
                                <label for="" class="form-label">Date From</label>
                                <input type="date" name="from_date" value="{{request()->from_date??''}}" class="form-control form-control-sm">
                            </div>
                            <div class="col-sm-3 mb-3">
                                <label for="" class="form-label">Date To</label>
                                <input type="date" name="to_date" value="{{request()->to_date??''}}" class="form-control form-control-sm">
                            </div>
                            <div class="col-sm-3 mb-3">
                                        <label>
                                        Manufacturing Unit*
                                        </label>
                                        <select name="Unit_Name" class="form-select form-select-sm" >
                                            <option value="" selected disabled>Select</option>
                                            @foreach($Manufacturing_unit as $val)
                                            <option value="{{$val->id}}" {{isset(request()->Unit_Name) &&
                                                request()->Unit_Name==$val->id?'selected':''}}>{{$val->pname}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3 mb-3">
                                        <label>
                                            Plant Name*
                                        </label>
                                        <select name="Plant_Name" id="Plant_Name" class="form-select form-select-sm" >
                                            <option value="" selected disabled>Select</option>
                                            @foreach($plant_name as $val)
                                            <option value="{{$val->id}}" {{isset(request()->Plant_Name) && request()->Plant_Name==$val->id?'selected':''}}>{{$val->spname}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3 mb-3">
                                        <label>
                                            Organization Name*
                                        </label>
                                        <select name="Organization" class="form-select form-select-sm" >
                                            <option value="" selected disabled>Select</option>
                                            @foreach($Organization as $val)
                                            <option value="{{$val->id}}" {{isset(request()->Organization) && request()->Organization==$val->id?'selected':''}}>{{isset($val->organisation) && $val->organisation!=''?$val->organisation:''}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3 mb-3">
                                        <label>
                                            BU Name*
                                        </label>
                                        <select name="BU" class="form-select form-select-sm" >
                                            <option value="" selected disabled>Select</option>
                                            @foreach($BU as $val)
                                            <option value="{{$val->id}}" {{isset(request()->BU) && request()->BU==$val->id?'selected':''}}>{{isset($val->unit_name) && $val->unit_name!=''?$val->unit_name:''}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3 mb-3">
                                        <label>
                                            Finished Good(FG)*
                                            </lable>
                                            <select name="Raw_Material" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" id="RawMaterial" >
                                                <option value="" selected disabled>Select</option>
                                                @foreach($Filtered_Array as $val)
                                                <option value="{{$val->RawMaterial->id}}" {{isset(request()->Raw_Material) && request()->Raw_Material==$val->RawMaterial->id?'selected':''}}>{{$val->RawMaterial->matname}}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                    <div class="col-sm-3 mb-3">
                                    <label>
                                        Batch No *
                                        </label>
                                        <select name="batch_no" id="batch_no" class="form-select form-select-sm" >
                                            <option value="" selected disabled>Select</option>
                                            @foreach($batch as $val)
                                            <option value="{{$val->batch_no}}" {{isset($edit->batch_no) && $edit->batch_no==$val->batch_no?'selected':''}}>{{$val->batch_no}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3 mb-3">
                                        <label>
                                        Sample Collected By *
                                        </label>
                                        <select name="SampleCollectedBy" class="form-select form-select-sm" >
                                            <option value="" selected disabled>Select</option>
                                            @foreach($admin as $val)
                                            <option value="{{$val->id}}" {{isset($edit->SampleCollectedBy) && $edit->SampleCollectedBy!=''?'selected':'' }} >{{$val->fullname}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3 mb-3">
                                        <label>
                                            QC Date *
                                        </label>
                                        <input type="date" name="QCDate" class="form-control form-control-sm" value="{{isset($edit->QCDate) && $edit->QCDate!=''?$edit->QCDate:''}}" >
                                    </div>
                                     <div class="col-sm-3 mb-3">
                                        <label>
                                            QC Code *
                                        </label>
                                        <input type="text" name="QCCode" placeholder="QC Code"   class="form-control form-control-sm" value="{{isset($edit->QCCode) && $edit->QCCode!=''?$edit->QCCode:''}}">
                                    </div> 
                                <div class="col-2 mt-4">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                <a href=""><button type="button" class="btn btn-secondary"><i class="fa fa-refresh"></i></button></a>
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
                                            <input type="checkbox" class="form-check-input" id="Creator_Name" value="Creator Name" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Creator_Name">Creator Name</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Organization_Name" value="Organization Name" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Organization_Name">Organization Name</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Manufacturing_Unit" value="Manufacturing Unit" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Manufacturing_Unit">Manufacturing Unit</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Plant_Name" value="Plant Name" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Plant_Name">Plant Name</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Raw_Material" value="Finished Good(FG)" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Raw_Material">Finished Good(FG)</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="SampleCollectedBy" value="Sample Collected By" onclick="filterTable(this)">
                                            <label class="form-check-label" for="SampleCollectedBy">Sample Collected By</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="BU_Name" value="BU Name" onclick="filterTable(this)">
                                            <label class="form-check-label" for="BU_Name">BU Name</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="QC_Code" value="QC Code" onclick="filterTable(this)">
                                            <label class="form-check-label" for="QC_Code">QC Code</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="QC_Date" value="QC Date" onclick="filterTable(this)">
                                            <label class="form-check-label" for="QC_Date">QC Date</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="BatchNo" value="Batch No" onclick="filterTable(this)">
                                            <label class="form-check-label" for="BatchNo">Batch No</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Date_Time" value="Date & Time" onclick="filterTable(this)">
                                            <label class="form-check-label" for="Date_Time">Date & Time</label>
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
                    <div id="mrulesdata">
                        <x-mrules type='qcfinishedGood'/>
                    </div>
                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="th-sm">SL. No.</th>
                                    <th class="th-sm">Creator Name</th>
                                    <th class="th-sm">Organization Name</th>
                                    <th class="th-sm">Manufacturing Unit</th>
                                    <th class="th-sm">Plant Name</th>
                                    <th class="th-sm">BU Name</th>
                                    <th class="th-sm">Finished Good(FG)</th>
                                    <th class="th-sm">Sample Collected By</th>
                                    <th class="th-sm">Batch No</th>
                                    <th class="th-sm">QC Date</th>
                                    <th class="th-sm">QC Code</th>
                                    <th class="th-sm">Date & Time</th>
                                    <th class="th-sm">Status</th>
                                    <th class="th-sm">Pending With</th>
                                    <th class="th-sm">Operation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($STD_data as $key=>$value)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$admindata[$value->userID]}}</td>
                                    <td>{{$orgdata[$value->Organization_Name]}}</td>
                                    <td>{{$Manufacturing_unitdata[$value->Unit_Name]}}</td>
                                    <td>{{$plant_namedata[$value->Plant_Name]}}</td>
                                    <td>{{$BUdata[$value->BU_Name]}}</td>
                                    <td>{{$Raw_Materialdata[$value->Raw_Material]}}</td>
                                    <td>{{$admindata[$value->SampleCollectedBy]}}</td>
                                    <td>{{$value->batch_no}}</td>                                                             
                                    <td>{{$value->QCDate}}</td>                                                             
                                    <td>{{$value->QCCode}}</td>                                                             
                                    <td>{{$value->created_at}}</td>                                                             
                                    <td>{{status($value->Approve_status)}}</td>
                                    <td  class="PendingColor">{{Pending_With(9,$value)}}</td>
                                    <td>
                                        @if(isset($value->status) && $value->status!=1)
                                        <a href="{{url('QCSampleTesting/InputerView/'.$value->id)}}" class="btn btn-primary">View</a>
                                        @if($value->Approve_status == 'RECHECK' && isset($EXT[9]['inputer']))
                                        <a href="{{url('QCSampleTesting/STDFinishedGoods/'.$value->id)}}" class="btn btn-secondary">Edit</a>
                                        @elseif(hold($value,'App\Models\QCSampleTesting\QCFinishedGoodApprove','QCFinishedGoodID') > 0)
                                        <a href="{{url('QCSampleTesting/Release_Hold/'.$value->id)}}" class="btn btn-secondary">Release</a>
                                        @endif
                                        @else
                                        <a href="{{url('QCSampleTesting/STDFinishedGoods/'.$value->id)}}" class="btn btn-warning">Draft</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
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
    $(document).ready(function() {
        activeclass(15, 1);
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
    var tableID = 31511;

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
    $(".tabingdata").click(function(){
        typeaction=$(this).attr('data-type');
        $(".tabingdata").removeClass('active')
        $(this).addClass('active')
        url='{{url(Route::current()->uri())}}'
        $.post(url,{typeaction},(data)=>{
            $("tbody").html(data)
            checkBoxess()
        });
    });
</script>
@endpush