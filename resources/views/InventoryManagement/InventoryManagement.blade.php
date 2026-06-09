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

    .ghhumggayas {
        display: flex;
        align-items: center;
        justify-content: center;
        align-content: center;
    }


    div#janis {
        padding-left: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    }



    div#janis .col-sm-1 {
        margin-left: 10px;
    }
    .col-sm-10.appending {
    border: 1px solid;
}
.main-row {
    margin-top: 20px;
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
                <a href="{{url('InventoryManagement/InventoryManagementList')}}" class="btn btn-info"> <i class="fa fa-arrow-left"></i> BACK</a>
                <a href="{{url('InventoryManagement/InventoryManagementList')}}" class="btn btn-info" style="margin-left:10px"> <i class="fa fa-home"></i> Home</a>
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
                        <form action="{{url('InventoryManagement/AddInventoryManagement')}}" method="POST">
                        @csrf
                            <input class="form-control" type="hidden" name="edit" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                            <div class="row" id="row">
                                <h6>QC FINISHED GOOD</h6>
                                <div class="col-sm-12 row" id="adaaishhhh">
                                    <div class="col-sm-3 form-group">
                                        <label>
                                            Unit Name*
                                        </label>
                                        <select name="Unit_Name" id="Unit_Name" class="form-select form-select-sm" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($Manufacturing_unit as $val)
                                            <option value="{{$val->id}}" {{isset($edit->Unit_Name) &&
                                                $edit->Unit_Name==$val->id?'selected':''}}>{{$val->pname}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>
                                            Plant Name*
                                        </label>
                                        <select name="Plant_Name" id="Plant_Name" class="form-select form-select-sm" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($plant_name as $val)
                                            <option value="{{$val->id}}" {{isset($edit->Plant_Name) && $edit->Plant_Name==$val->id?'selected':''}}>{{$val->spname}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>
                                        Batch No *
                                        </label>
                                        <select name="batch_no" id="batch_no" class="form-select form-select-sm" required>
                                            <option value="" selected >Select</option>
                                            
                                        </select>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                    <input type="hidden" name="Organization">
                                        <label>
                                            Organization Name*
                                        </label>
                                        <select  id="Organization_Name" class="form-select form-select-sm" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($Organization as $val)
                                            <option value="{{$val->id}}" {{isset($edit->Organization_Name) && $edit->Organization_Name==$val->id?'selected':''}}>{{isset($val->organisation) && $val->organisation!=''?$val->organisation:''}}</option>
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
                                            <option value="{{$val->id}}" {{isset($edit->BU_Name) && $edit->BU_Name==$val->id?'selected':''}}>{{isset($val->unit_name) && $val->unit_name!=''?$val->unit_name:''}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>
                                        Finished Good 
                                        </label>
                                        <input type="hidden" value="" id="FinishedGoodid" name="fid">
                                        <input type="text" disabled name="" id="FinishedGood" placeholder="Finished Good" class="form-control form-control-sm" value="" required>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>
                                        Production Date 
                                        </label>
                                        <input type="text" disabled name="" placeholder="Production Date" id="ProductionDate" class="form-control form-control-sm" value="" required>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>
                                        Production Shift
                                        </label>
                                        <input type="text" disabled placeholder="Production Shift" id="ProductionShift" class="form-control form-control-sm" value="" required>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>
                                            QC Code 
                                        </label>
                                        <input type="text" readonly name="QCCode" id="QCCode"   placeholder="QC Code" class="form-control form-control-sm" >
                                    </div> 
                                    <div class="col-sm-3 form-group">
                                        <label>
                                        QC Status 
                                        </label>
                                        <input type="text" placeholder="QC Status" disabled id="QCStatus" class="form-control form-control-sm" value="" required>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>
                                        Sample Collected By
                                        </label>
                                        <input type="text" disabled placeholder="Sample Collected By" id="SampleCollectedBy"    class="form-control form-control-sm" >
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
                                    <input type="text" name="remark" cols="30" rows="5" class="form-control form-control-sm" placeholder="Remarks" value="{{isset($edit->remarks) && $edit->remarks!=''?$edit->remarks:''}}">
                                </div>
                            </div>
                            <div style="overflow:auto;">
                                <div class="somras">
                                    <button type="submit" id="submitdata" class="btn btn1 float-right" style="margin: 5px;">Submit</button>
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
    function displayTime() {
        const now = new Date();
        const date = now.toLocaleDateString();
        const time = now.toLocaleTimeString();
        document.getElementById("clock").textContent = time + ', ' + date;
    }

    setInterval(displayTime, 1000);
</script>
<script>
    $(document).ready(function() {
        activeclass(21, 1);
    });
    // $(document).ready(function() {
    //     $('#Unit_Name').change(function() {
    //         $('#org_name').val('');
    //         var ManunitId = $(this).val();

    //         if (ManunitId) {
    //             $.ajax({
    //                 url: "{{url('PPFinishedGood/get-plantnamedetails')}}" + '/' + ManunitId,
    //                 type: 'GET',
    //                 data: {
    //                     ManunitId : ManunitId
    //                   },
    //                   success: function(response) {
    //                     console.log(response); // Check if the response is as expected
    //                     if (editPlantId) {
    //                     $('#Plant_Name').empty();
    //                     }
    //                     $('#Plant_Name').append('<option value="" selected disabled>Select</option>');
    //                     $.each(response, function(index, plantdetails) {
    //                         var option = $('<option>');
    //                         option.val(plantdetails.id);
    //                         option.text(plantdetails.spname);
    //                         $('#Plant_Name').append(option);
    //                     });
    //                     // Set selected option if available
    //                     // var editPlantId = "{{$edit->Plant_Name ?? ''}}";
    //                     // if (editPlantId) {
    //                     //     $('#Plant_Name').val(editPlantId);
    //                     //     //materialdata();
    //                     // }
    //                 }
    //             });
    //         }
    //     });
    // });
    $(document).ready(function() {
    var isFirstChange = true; // Flag variable to track the first change event

    $('#Unit_Name').change(function() {
        $('#org_name').val('');
        var ManunitId = $(this).val();
        var editPlantId = "{{$edit->Plant_Name ?? ''}}"; // Assuming this variable is defined elsewhere in your code

        if (ManunitId) {
            $.ajax({
                url: "{{url('PPFinishedGood/get-plantnamedetails')}}" + '/' + ManunitId,
                type: 'GET',
                data: {
                    ManunitId: ManunitId
                },
                success: function(response) {
                    console.log(response); // Check if the response is as expected
                    $('#Plant_Name').empty();
                    $('#Plant_Name').append('<option value="" selected>Select</option>');
                    $.each(response, function(index, plantdetails) {
                        var option = $('<option>');
                        option.val(plantdetails.id);
                        option.text(plantdetails.spname);
                        $('#Plant_Name').append(option);
                    });
                    if (isFirstChange && editPlantId) {
                        $('#Plant_Name').val(editPlantId);
                        isFirstChange = false; // Set the flag to false after the first change event
                    }
                }
            });
        }
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
               // alert(data)
                data=JSON.parse(data)
               // alert(data.result)
            if(data.result==true)
                {
                    //alert(data)
                    $("#SampleCollectedBy").val(data.SampleCollectedBy)
                    $("#ProductionShift").val(data.Production_Shift)
                    $("#QCCode").val(data.QCCode)
                    $("#QCStatus").val('APPROVE')
                    $("#FinishedGood").val(data.Finished_Good)
                    $("#FinishedGoodid").val(data.fid)
                    $("#ProductionDate").val(data.Production_Date)
                    $("#adaais455hhhh div").remove()
                    manage(1)
                }
            }
        });
    }
    function setdata()
    {
        
        $(".checkbox").change(function(){
        value=$(this).val()
        id=$(this).attr('target-id');
        lentt=$("."+value+":checked").length
        Rack_No=$("#Rack_No"+id).val()
        Sub_Rack_No=$("#Sub_Rack_No"+id).val()
        Bin_No=$("#Bin_No"+id).val()
        Sub_Bin_No=$("#Sub_Bin_No"+id).val()
        if(Rack_No=='')
        {
            alert('Please Select Rack No. First');
            $("#manage"+id+value).prop('checked',false);

        }
        else if(Sub_Rack_No=='')
        {
            alert('Please Select Sub Rack No. First');
            $("#manage"+id+value).prop('checked',false);

        }
        else if(Bin_No=='')
        {
            alert('Please Select Bin No. First');
            $("#manage"+id+value).prop('checked',false);

        }
        else if(Sub_Bin_No=='')
        {
            alert('Please Select Sub Bin No. First');
            $("#manage"+id+value).prop('checked',false);

        }
        else if(lentt>1)
        {
            alert("This Sl No. Material Already Selected For Another Rack No. Please Uncheck It Then Try To Select This Sl No.")
            $("#manage"+id+value).prop('checked',false);
        }
        // manage=[]
        // $.each($("input[name='manage[]']:checked"), function(){
        //         manage.push($(this).val());
        // });
       
        // function checkAge(id) 
        // {
        //     return id ==value;
        // }
       // neval=[]
        //neval.push(manage.find(checkAge))
        //alert(value)
        //alert(neval.length)
        // if(value== manage.find(checkAge))
        // {
        //     alert(value)
        // }
         });
    }
    function manage(type=0)
    {
        var batch_no = $("#batch_no").val();
        var id = '{{$edit->id??""}}';
            $.ajax({
            url: "{{url('InventoryManagement/manage')}}",
            type: 'POST',
            data: {
                batch_no,type,id
            },
            success: function(data) {
                $("#adaais455hhhh").append(data);
                setdata()
            }
        });
    }
    $("#batch_no").change(function(){
        materialdata()
        selectbatchdata()
    });
    function removedd(id)
    {
        $("#remss"+id).remove()
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
                    $('input[name="BU"]').val(data.BU_Name);
                    //$('input[name="Raw_Material"]').val(data.Raw_Material);
                    $('input[name="Organization"]').val(data.Organization_Name);
                    //$('#Raw_Material').val(data.Raw_Material).change();
                    
                }
            });
        }

    }
    function materialbatchdata() {
        id = "{{$edit->id??''}}"
var Unit_Name = $('#Unit_Name').val();
var Organization_Name = $('#Organization_Name').val();
var BU_Name = $("#BU_Name").val();
var Raw_Material = $("#Raw_Material").val();
var Plant_Name = $("#Plant_Name").val();
$.ajax({
    url: "{{url('InventoryManagement/fetchbatchoption')}}",
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
$("#submitdata").click(function(){
    totalslno=$("#totalslno").val()
    checkbox =$('.checkbox:checked').length;
    if(parseInt(totalslno)!=parseInt(checkbox))
    {
        alert("Please Select All SL. No.")
        return false;
    }
    
})
$("#Unit_Name  ,#Plant_Name").change(function () {
    materialbatchdata()
    });
    $("#BU_Name,#Organization_Name,#Raw_Material").select2({disabled:'readonly'});
    @if(isset($edit->id))
    $(document).ready(function(){
        $("#Unit_Name").change()
    });
    @endif
    
</script>
@endpush