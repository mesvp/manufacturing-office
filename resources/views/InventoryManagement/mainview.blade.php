@extends('layout.main')
@section('main-container')
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
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
    }

    tbody,
    td,
    tfoot,
    th,
    thead,
    tr {
        border: none !important;
    }


    table#dynamic_field {
        margin-top: -14px;
    }

    .downloadfile {
        display: flex;
    }

    .downloadfile div {
        margin: 0px 20px;
    }

    .downloadfile i.fa.fa-remove {
        color: red;
    }

    div#adaaishhhh {
        margin-left: 10px;
        margin-bottom: 20px;


        width: 98.5%;
    }

    input.form-control.form-control-sm {
        margin-top: 10px;
    }

    hr {
        width: 99% !important;
    }

    div#adaais {
        margin-left: 10px;
        margin-bottom: 20px;

    }

    div#\a main_btn_uddhan {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        align-content: center;
    }

    table#ssef {
        border: 1px solid;
        width: 50%;
    }


    tr.jaafgg td {
        padding: 10px !important;
    }

    tr.jaafgg {
        border-bottom: 1px solid !important;
    }

    .rm_tabe {
        display: flex;
    }


    div#lkjhhdggdg {
        margin-top: 40px;
    }

    table#ssef td {
        padding-left: 10px;
        padding-top: 10px;
        padding-bottom: 10px;
    }


    input#logfgfau {
        height: 60px;
    }

    button#diraj-button {
        background: transparent;
        border: 1px solid;
    }

    table#ufkffguuyuffffu {
        margin-top: 30px;
        border: 1px solid #ddd;
    }




    table#ufkffguuyuffffu thead tr {
        padding: 10px !important;
    }

    table#ufkffguuyuffffu thead tr th.th-sm {
        padding: 10px;
        border: 1px solid #ddd !important;
    }

    table#ufkffguuyuffffu thead tr td.th-sm {
        padding: 10px;
        border: 1px solid #ddd !important;
    }

    div#himmatwalaa {
        display: flex;
        align-items: center;
        justify-content: center;
        align-content: center;
    }

    div#main_btn_uddhan {
        display: flex;
        justify-content: flex-end;
    }

    .selector {

        display: flex;

    }

    .selecotr-item {
        position: relative;

        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .selector-item_radio {
        appearance: none;
        display: none;
    }

    .selector .selecotr-item {
        margin: 4px;
    }

    .selector-item_label {
        position: relative;
        /* height: 63%; */
        /* width: 53%; */
        text-align: center;
        border-radius: 9999px;
        /* line-height: 400%; */
        font-weight: 600 !important;
        transition-duration: .5s;
        transition-property: transform, color, box-shadow;
        transform: none;
        padding: 7px 10px;
        border-radius: 5px !important;
        border: 1px solid #CED4DA;
        text-transform: capitalize;
    }

    .selector-item_radio:checked+.selector-item_label {
        background: #6741D5;
        color: white;
    }


    input[type="radio"] {

        display: none !important;
    }

    .textt {
        font-weight: 600;
    }

    div#DataTables_Table_0_filter {
        display: none;
    }

    div#Tabledata_length {
        display: none;
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
        @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
        @endif
        <section class="section">
            <div class="addbtn extra">
                <a href="#" class="btn btn-info"> <i class="fa fa-arrow-left"></i> BACK</a>
                <a href="{{$type==1?url('InventoryManagement/InventoryManagementApproverList'):url('InventoryManagement/InventoryManagementList')}}" class="btn btn-info" style="margin-left:10px"> <i class="fa fa-home"></i> Home</a>
            </div>
            <div class="row">
                <div class="container">
                    <div class="row">
                        <div class="col-4">
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col">
                                    <h5>Inventory Management Details</h5>
                                </div>
                                <div class="col">
                                    <label for="">Inputer Name : {{$admindata}}</label>
                                </div>
                                <div class="col">
                                    <label for="">Date & Time : <span id="clock"></span></label>
                                </div>

                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="tab1" id="formview">


                    </div>
                </div>
                <br>
                <br>
                <div class="container" id="action">

                </div>
                <br>
                <br>
                <br>
                <br>
                <div class="container" >
                    <div class="table-responsive">
                        <table id="" class="table table-striped table-bordered example" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="th-sm">SL NO.</th>
                                    <th class="th-sm">Action</th>
                                    <th class="th-sm">Action By</th>
                                    <th class="th-sm">Role. (Reviewer,Approver,ETC)</th>
                                    <th class="th-sm">Date & time</th>
                                    <th class="th-sm">comment</th>
                                    <th class="th-sm">IP Address</th>
                                    <th class="th-sm">Device ID</th>
                                </tr>
                            </thead>
                            <tbody id="trail">

                            </tbody>
                        </table>
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
    $(document).ready(function() {
        activeclass(21, 1);
    });
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
        id = '{{$id}}'
        $.post("{{url('InventoryManagement/Formview')}}", {
            id: id
        }, function(data) {
            $("#formview").html(data);
            materialdata()
        });
        ////////////////////////
        $.post("{{url('InventoryManagement/action/')}}/{{$type??0}}", {
            id: id
        }, function(data) {
            $("#action").html(data);
        });
        /////////////////
        $.post("{{url('InventoryManagement/trail')}}", {
            id: id
        }, function(data) {
            $("#trail").html(data);
        });
    });

    function materialdata()
    {
        
        var Unit_Name = $('#Unit_Name').val();
        var Organization_Name = $('#Organization_Name').val();
        var BU_Name = $("#BU_Name").val();
        var Plant_Name = $("#Plant_Name").val();
        var batch_no = $("#batch_no").val();
            $.ajax({
            url: "{{url('InventoryManagement/fetchbatch')}}",
            type: 'POST',
            data: {
                Unit_Name,Organization_Name,BU_Name,batch_no,Plant_Name
            },
            success: function(data) {
                data=JSON.parse(data)
            if(data.result==true)
                {
                    $("#SampleCollectedBy").val(data.SampleCollectedBy)
                    $("#ProductionShift").val(data.Production_Shift)
                    $("#QCCode").val(data.QCCode)
                    $("#QCStatus").val('APPROVE')
                    $("#FinishedGood").val(data.Finished_Good)
                    $("#ProductionDate").val(data.Production_Date)
                    //manage(1)
                }
            }
        });
    }
    $(document).ready(function(){
        materialdata()
    });
</script>


@endpush