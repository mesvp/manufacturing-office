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
                <a href="{{url('QCSampleTesting/STDFinishedGoodsList')}}" class="btn btn-info"> <i
                        class="fa fa-arrow-left"></i> BACK</a>
                <a href="{{url('QCSampleTesting/STDFinishedGoodsList')}}" class="btn btn-info" style="margin-left:10px">
                    <i class="fa fa-home"></i> Home</a>
            </div>
            <div class="row">
                <div class="container">
                    <div class="row">
                        <div class="col-4">
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col">
                                    <h5>Sample Testing Details Finished Goods</h5>
                                </div>
                                <div class="col">
                                    <label for="">Inputer Name : {{auth()->user()->name}}</label>
                                </div>
                                <div class="col">
                                    <label for="">Date & Time : <span id="clock"></span></label>
                                </div>

                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="tab1">
                        <form action="{{route('QCSampleTesting.store')}}" method="POST" id="sales-fields">
                            @csrf
                            <input class="form-control" type="hidden" name="edit"
                                value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                            <div class="row" id="row">
                                <h6>QC FINISHED GOOD</h6>
                                <div class="col-sm-12 row" id="adaaishhhh">
                                    <div class="col-sm-3 form-group">
                                        <label>
                                            Unit Name*
                                        </label>
                                        <select name="Unit_Name" id="Unit_Name" class="form-select form-select-sm"
                                            required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($Manufacturing_unit as $val)
                                            <option value="{{$val->id}}" {{isset($edit->Unit_Name) &&
                                                $edit->Unit_Name==$val->id?'selected':''}}>{{$val->pname}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>
                                            Plant Name*
                                        </label>
                                        <select name="Plant_Name" id="Plant_Name" class="form-select form-select-sm"
                                            required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($plant_name as $val)
                                            <option value="{{$val->id}}" {{isset($edit->Plant_Name) &&
                                                $edit->Plant_Name==$val->id?'selected':''}}>{{$val->spname}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>
                                            Batch No *
                                        </label>
                                        <select name="batch_no" id="batch_no" class="form-select form-select-sm"
                                            required>
                                            <option value="" selected disabled>Select Batch</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                    <input type="hidden" name="Organization">
                                        <label>
                                            Organization Name*
                                        </label>
                                        <select  id="Organization_Name"
                                            class="form-select form-select-sm" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($Organization as $val)
                                            <option value="{{$val->id}}" {{isset($edit->Organization_Name) &&
                                                $edit->Organization_Name==$val->id?'selected':''}}>{{isset($val->organisation)
                                                && $val->organisation!=''?$val->organisation:''}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                </div>
                                <div class="col-sm-12 row" id="adaaishhhh">
                                <div class="col-sm-3 form-group">
                                    <input type="hidden"name="BU">
                                        <label>
                                            BU Name*
                                        </label>
                                        <select  id="BU_Name" class="form-select form-select-sm" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($BU as $val)
                                            <option value="{{$val->id}}" {{isset($edit->BU_Name) &&
                                                $edit->BU_Name==$val->id?'selected':''}}>{{isset($val->unit_name) &&
                                                $val->unit_name!=''?$val->unit_name:''}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                    <input type="hidden"name="Raw_Material">
                                        <label>
                                            Finished Good(FG)*
                                            </lable>
                                            <select 
                                                class="form-select form-select-sm js-example-matcher-start js-example-matcher-start"
                                                id="Raw_Material" required>
                                                <option value="" selected disabled>Select</option>
                                                @foreach($Raw_Material as $val)
                                                <option value="{{$val->RawMaterial->id}}" {{isset($edit->Raw_Material)
                                                    &&
                                                    $edit->Raw_Material==$val->RawMaterial->id?'selected':''}}>{{$val->RawMaterial->matname}}
                                                </option>
                                                @endforeach
                                            </select>
                                    </div>
                                    
                                    <div class="col-sm-3 form-group">
                                        <label>
                                            Sample Collected By *
                                        </label>
                                        <select name="SampleCollectedBy" class="form-select form-select-sm" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($admin as $val)
                                            <option value="{{$val->id}}" {{isset($edit->SampleCollectedBy) &&
                                                $edit->SampleCollectedBy==$val->id?'selected':'' }} >{{$val->fullname}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-2 form-group">
                                        <label>
                                            QC Date *
                                        </label>
                                        <input type="date" name="QCDate" class="form-control form-control-sm"
                                            value="{{isset($edit->QCDate) && $edit->QCDate!=''?$edit->QCDate:''}}"
                                            required>
                                    </div>
                                    <div class="col-sm-1 form-group">
                                        <label>
                                            QC Code *
                                        </label>
                                        <input type="text" name="QCCode" placeholder="auto" readonly
                                            title="Automatically Genrate" class="form-control form-control-sm"
                                            value="{{isset($edit->QCCode) && $edit->QCCode!=''?$edit->QCCode:''}}">
                                    </div>
                                </div>
                                <div class="col-sm-12 row" id="adaais455hhhh">

                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-8 form-group"></div>
                                <div class="col-sm-4 form-group">
                                    <label for="State">Remarks:</label>
                                    <input type="text" name="remark" cols="30" rows="5"
                                        class="form-control form-control-sm" placeholder="Remarks"
                                        value="{{isset($edit->remarks) && $edit->remarks!=''?$edit->remarks:''}}">
                                </div>
                            </div>
                            <div style="overflow:auto;">
                                <div class="somras">
                                    <button type="submit" id="submitdata" class="btn btn1 float-right"
                                        style="margin: 5px;">Submit</button>
                                </div>
                            </div>
                        </form>
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
        activeclass(15, 1);
    });
</script>
<script>
    $(document).ready(function() {
        $('#Unit_Name').change(function() {
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
                        console.log(response); // Check if the response is as expected
                        $('#Plant_Name').empty();
                        $('#Plant_Name').append('<option value="" selected disabled>Select</option>');
                        $.each(response, function(index, plantdetails) {
                            var option = $('<option>');
                            option.val(plantdetails.id);
                            option.text(plantdetails.spname);
                            $('#Plant_Name').append(option);
                        });
                        // Set selected option if available
                        var editPlantId = "{{$edit->Plant_Name ?? ''}}";
                        if (editPlantId) {
                            $('#Plant_Name').val(editPlantId);
                            materialdata();
                        }
                    }
                });
            }
        });
    });
    function displayTime() {
        const now = new Date();
        const date = now.toLocaleDateString();
        const time = now.toLocaleTimeString();
        document.getElementById("clock").textContent = time + ', ' + date;
    }

    setInterval(displayTime, 1000);
    function materialdata() {
        id = "{{$edit->id??''}}"
        var Unit_Name = $('#Unit_Name').val();
        var Organization_Name = $('#Organization_Name').val();
        var BU_Name = $("#BU_Name").val();
        var Raw_Material = $("#Raw_Material").val();
        var Plant_Name = $("#Plant_Name").val();
        $.ajax({
            url: "{{url('QCSampleTesting/fetchbatch')}}",
            type: 'POST',
            data: {
                Unit_Name, Organization_Name, BU_Name, Raw_Material, Plant_Name,id
            },
            success: function (data) {
                $('#batch_no').html(data);
                @if(isset($edit->id))
                $("#batch_no").change()
                @endif
            }
        });
    }
    $("#Unit_Name  ,#Plant_Name").change(function () {
        materialdata()
    });
    function selectbatch()
    {
        batch_no = $("#batch_no").val()
        id = "{{$edit->id??''}}"
        if (batch_no != '') 
        {
            $.ajax({
                url: "{{url('QCSampleTesting/fetchbatchdata')}}",
                type: 'POST',
                data: {
                    batch_no, id
                },
                success: function (data) {
                    $('#adaais455hhhh').html(data);
                    selectbatchdata()
                }
            });
        }

    }
    function selectbatchdata()
    {
        batch_no = $("#batch_no").val()
        id = "{{$edit->id??''}}"
        if (batch_no != '') 
        {
            $.ajax({
                url: "{{url('QCSampleTesting/fetchbatchfordata')}}",
                type: 'POST',
                data: {
                    batch_no, id
                },
                success: function (data) {
                    $('#Organization_Name').val(data.Organization_Name).change();
                    $('#BU_Name').val(data.BU_Name).change();
                    $('#Raw_Material').val(data.Raw_Material).change();
                    $('input[name="BU"]').val(data.BU_Name);
                    $('input[name="Raw_Material"]').val(data.Raw_Material);
                    $('input[name="Organization"]').val(data.Organization_Name);
                    
                }
            });
        }

    }
    $("#batch_no").change(function () {
        selectbatch()
    });
    @if (isset($edit->id))
        $(document).ready(function () {
            //selectbatch()
            $("#Unit_Name").change()
        })
    @endif
    $("#BU_Name,#Organization_Name,#Raw_Material").select2({disabled:'readonly'});
</script>

@endpush