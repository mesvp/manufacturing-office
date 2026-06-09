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
        justify-content: flex-start;
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

    button#diraj-button {
        background: transparent;
        border: 1px solid;
    }

    .tabtbs {
        display: flex;
    }

    .tabtbs input {
        margin-left: 10px;
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
                <a href="{{url('SampleFreeGood/SampleFreeGoodList')}}" class="btn btn-info"> <i class="fa fa-arrow-left"></i> BACK</a>
                <a href="{{url('SampleFreeGood/SampleFreeGoodList')}}" class="btn btn-info" style="margin-left:10px"> <i class="fa fa-home"></i> Home</a>
            </div>
            <div class="row">
                <div class="container">
                    <div class="row">
                        <div class="col-4">
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col">
                                    <h5>Free Or Sample Goods</h5>
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
                        <form action="{{url('SampleFreeGood/AddSampleFreeGood')}}" method="POST">
                            @csrf
                            <input class="form-control" type="hidden" name="edit" id="edit_id" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                            <div class="row">
                                
                                <div class="col-sm-3 form-group">
                                    <label>
                                        Manufacturing Unit*
                                    </label>
                                    <select name="Manufacturing_Unit" id="Manufacturing_Unit" class="form-select form-select-sm js-example-matcher-start" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Manufacturing_Unit as $val)
                                        <option value="{{$val->id}}" {{isset($edit->Manufacturing_Unit) && $edit->Manufacturing_Unit==$val->id?'selected':''}}>{{$val->pname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>
                                        Plant Name
                                    </label>
                                    <select name="Plant_Name" id="Plant_Name" class="form-select form-select-sm js-example-matcher-start" >
                                        <option value="" selected >Select</option>
                                        @foreach($Plant_Name as $val)
                                        <option value="{{$val->id}}" {{isset($edit->Plant_Name) && $edit->Plant_Name==$val->id?'selected':''}}>{{$val->spname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>
                                        Organization Name*
                                    </label>
                                    <select name="Organization_Name" id="Organization_Name" class="form-select form-select-sm js-example-matcher-start" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Organization_Name as $val)
                                        <option value="{{$val->id}}" {{isset($edit->Organization_Name) && $edit->Organization_Name==$val->id?'selected':''}}>{{$val->organisation}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>
                                        BU Name*
                                    </label>
                                    <select name="BU" id="BU_Name" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($BU as $val)
                                        <option value="{{$val->id}}" {{isset($edit->BU) && $edit->BU==$val->id?'selected':''}}>{{$val->unit_name??''}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>
                                        Godown Name
                                    </label>
                                    <select name="Godown_Name" id="Godown_Name" class="form-select form-select-sm js-example-matcher-start" >
                                        <option value="" selected >Select</option>
                                        @foreach($Godown_Name as $val)
                                        <option value="{{$val->id}}" {{isset($edit->Godown_Name) && $edit->Godown_Name==$val->id?'selected':''}}>{{$val->inventory_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>
                                        Finished Good(FG)*
                                        </lable>
                                        <select name="Raw_Material" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" id="RawMaterial" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($Raw_Material as $val)
                                            <option value="{{$val->RawMaterial->id}}" {{isset($edit->Raw_Material) && $edit->Raw_Material==$val->RawMaterial->id?'selected':''}}>{{$val->RawMaterial->matname}}</option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Stock Availabe</label>
                                    <div class="field-wrap">
                                        <input readonly class="form-control form-control-sm" type="number" name="StockAvailabe" id="StockAvailabe" placeholder="Stock Availabe" value="{{isset($edit->HSN_Code) && $edit->HSN_Code!=''?$edit->HSN_Code:''}}" required>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>UOM</label>
                                    <div class="field-wrap">
                                        <input readonly class="form-control form-control-sm" type="text" name="UOM" id="uom" placeholder="Stock Availabe" value="{{isset($edit->UOM) && $edit->UOM!=''?$edit->UOM:''}}" required>
                                        {{-- <select disabled name="UOM" id="uom" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" required readonly>
                                        <option value="" selected disabled>Select</option>
                                                @foreach($UOM as $val)
                                                <option value="{{$val->id}}" {{isset($edit->UOM) && $edit->UOM==$val->id?'selected':''}}>{{$val->UOMs}}</option>
                                                @endforeach
                                        </select> --}}
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Quantity</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="number" name="Quantity" id="Quantity" placeholder="Quantity" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" value="{{isset($edit->Quantity) && $edit->Quantity!=''?$edit->Quantity:''}}" required>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Date</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="date" name="date" id="date" placeholder="date" value="{{isset($edit->Date) && $edit->Date!=''?$edit->Date:''}}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <h6>Customer Details</h6>
                                {{-- <div class="col-sm-3 form-group">
                                    <label>Customer Name</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="text" name="Customer_Name" id="Customer_Name" placeholder="Customer Name" value="{{isset($edit->CustomerName) && $edit->CustomerName!=''?$edit->CustomerName:''}}" required>
                                    </div>
                                </div> --}}
                                <div class="col-sm-3 form-group">
                                    <label>Customer Name</label>
                                    <select name="Customer_Name" id="Customer_Name" class="form-select form-select-sm js-example-matcher-start" required>
                                    <option value="" selected disabled>Select</option>
                                        @foreach($Customer_Details as $val)
                                        <option value="{{$val->id}}" {{isset($edit->Organization_Name) && $edit->Organization_Name==$val->id?'selected':''}}>{{$val->prmcontnm}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- <div class="col-sm-3 form-group">
                                    <label>Customer Address</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="text" name="Customer_Address" id="Customer_Address" placeholder="Customer Address" value="{{isset($edit->CustomerAddress) && $edit->CustomerAddress!=''?$edit->CustomerAddress:''}}" readonly required>
                                    </div>
                                </div> --}}
                                
                                <div class="col-sm-3 form-group">
                                    <label>Customer Phone</label>
                                    <div class="field-wrap">
                                        <input onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" class="form-control form-control-sm" type="text" name="Customer_Phone" id="Customer_Phone" placeholder="Customer Phone" value="{{isset($edit->CustomerPhone) && $edit->CustomerPhone!=''?$edit->CustomerPhone:''}}" readonly required>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Company Name</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="text" name="Company_Name" id="Company_Name" placeholder="Company Name" value="{{isset($edit->CompanyName) && $edit->CompanyName!=''?$edit->CompanyName:''}}" readonly required>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Customer Address*</label>
                                    <div class="field-wrap">
                                        <textarea class="form-control form-control-sm" style="height: 110px;" readonly name="Customer_Address" id="Customer_Address">{{isset($edit->CustomerAddress) && $edit->CustomerAddress!=''?$edit->CustomerAddress:''}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <h6>Material Details :</h6>
                                <div class="col-sm-12" id="materialdata">
                                 
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <label>Reason For Free or sample Good : </label>
                                    <div class="field-wrap">
                                        <textarea name="reason" placeholder="Reason For Free or sample Good" class="form-control form-control-sm">{{$edit->Reason??''}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-8 form-group"></div>
                                <div class="col-sm-4 form-group">
                                    <label for="State">Remarks:</label>
                                    <input type="text" name="remarks" cols="30" rows="5" class="form-control form-control-sm" placeholder="Remarks" value="{{isset($edit->remarks) && $edit->remarks!=''?$edit->remarks:''}}">
                                </div>
                            </div>
                            <div style="overflow:auto;">
                                <div class="somras">
                                    <!-- <button type="button" id="draft" class="btn btn1 float-right" style="margin: 5px;">Draft & Save</button> -->
                                    <a href="" class="btn btn1 float-right" style="margin: 5px; display: {{isset($edit->id) && $edit->id != ''?'none':'block'}}">Clear All</a>
                                    <button type="submit" id="submitBtn" class="btn btn1 float-right" style="margin: 5px;">Submit</button>
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
        activeclass(16, 1);
    });
</script>
<script>
     $(document).ready(function() {
        $('#Manufacturing_Unit').change(function() {
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
    $(document).ready(function() {
        $('#Customer_Name').change(function() {
            var ManunitId = $(this).val();

            if (ManunitId) {
                $.ajax({
                    url: "{{url('SampleFreeGood/get-customerdetails')}}" + '/' + ManunitId,
                    type: 'GET',
                    data: {
                        ManunitId : ManunitId
                      },
                      success: function(response) {
                        $.each(response, function(index, customerdetails) {
                            //$("#Customer_Address").val(customerdetails.companynm);
                            $("#Customer_Address").val("Attention: " + customerdetails.bilattn + "\n" +
                                    "Country: " + customerdetails.bilcntry + ", " + "\n" +
                                    "Address: " + customerdetails.vendor_address + "\n"+
                                    "City: " + customerdetails.bilcity + "\n"+
                                    "State: " + customerdetails.billstate + "\n"+
                                    "Zip Code: " + customerdetails.pin + "\n"+
                                    "Phone: " + customerdetails.bilphone + "\n"+
                                    "Fax: " + customerdetails.bilfax);
                            $("#Customer_Phone").val(customerdetails.workph);
                            $("#Company_Name").val(customerdetails.companynm);
                        });
                       
                    }
                });
            }
        });
    });

    $("#Plant_Name").change(function(){
        $("#Godown_Name").val('');
        $("#select2-Godown_Name-container").text('Select')
        $("#uom").val('').change();
            $("#StockAvailabe").val('');
            $("#RawMaterial").val('').change()
            $("#Quantity").val(0)
    });
    $("#Godown_Name").change(function(){
        $("#Plant_Name").val('');
        $("#select2-Plant_Name-container").text('Select')
        $("#uom").val('').change();
            $("#StockAvailabe").val('');
            $("#RawMaterial").val('').change()
            $("#Quantity").val(0)
    });
    $("#RawMaterial,#Quantity").change(function(){
        StockAvailabe=parseInt($("#StockAvailabe").val())
        quantity=parseInt($("#Quantity").val())
        if(quantity>StockAvailabe)
        {
            alert("Quantity Can not be more then stock");
            $("#Quantity").val(0)
            return false;
        }
        $('#materialdata').children('div').remove();

        rawmaterial(1)

    });
 function rawmaterial(type=0,update=0)
 {
        quantity=$("#Quantity").val()
        edit=$("#edit_id").val();
        Plant_Name=$("#Plant_Name").val();
        Godown_Name= $("#Godown_Name").val();
        Organization= $("#Organization_Name").val();
        BU_Name= $("#BU_Name").val();
        Manufacturing_Unit=$("#Manufacturing_Unit").val();
        id= $("#RawMaterial").val();
        if(Organization=='')
        {
            alert("You Must Select Organization First !")
            return false
        }
        if(BU_Name=='')
        {
            alert("You Must Select Business Unit First !")
            return false
        }
        if(Manufacturing_Unit=='')
        {
            alert("You Must Select Manufacturing Unit First !")
            return false
        }
        if(Plant_Name=='' && Godown_Name=='')
        {
            alert("You Must Select Plant Or Godown First !")
            $("#RawMaterial").val('');
            $("#select2-RawMaterial-container").text('Select')
            return false
        }
        data={
            Plant_Name:Plant_Name,
            Godown_Name:Godown_Name,
            id:id,
            Organization:Organization,
            BU_Name:BU_Name,
            Manufacturing_Unit:Manufacturing_Unit,
            type:type,
            edit,
            update,
            quantity
        }
        $.post("{{url('SampleFreeGood/RawmaterialData')}}",data,(data)=>{
            //alert(data)
            $("#uom").val(data.data.UOM).change();
            $("#StockAvailabe").val(data.data.stock);
        });
        //alert(id)
        if(id==null)
        {
            return false;
        }
        if(quantity<1)
        {
            return false;
        }
        $.post("{{url('SampleFreeGood/RawmaterialgetData')}}",data,(data)=>{
            $("#materialdata").append(data)
            select()
        });
 }
 function removeele(id)
 {
    $("#remove"+id).remove()
 }
 function select()
 {
    $(".js-example-matcher-start").select2({});
 }
 function batch_no(value,id)
 {
    $.post("{{url('SampleFreeGood/Rawmaterialgetsl')}}",{value:value},(data)=>{
        $("#sl_no"+id).attr("name","sl_no["+value+"][]")
       // alert(data)
        //console.log(data)
        a='';
           for (x in data.data){
            a+='<option value="'+data.data[x]['sl_no']+'">'+data.data[x]['sl_no']+'</option>'
           }
           $("#sl_no"+id).html(a)
        });
 }
 $("#submitBtn").click(function(e){
   // e.preventDefault()
    StockAvailabe=parseInt($("#StockAvailabe").val())
        quantity=parseInt($("#Quantity").val())
        if(quantity>StockAvailabe)
        {
            alert("Quantity Can not be more then stock");
            $("#Quantity").val(0)
            return false;
        }
        if($("#Godown_Name").val()==''){
        var sl_no = [];
            $.each($(".alldata"), function(){
                sl_no.push($(this).val());
            });
            newsl_no=[]
            for (x in sl_no)
            {
                //alert(sl_no[x])
                spited=sl_no[x];
                for (i in spited)
                {
                    newsl_no.push(spited[i])
                }
            }
         if(newsl_no.length==parseInt(quantity))
        {

        }
        else{
            alert("Selected Sl no Can Not Be more then quantity or can not be less then quantity")
            return false
        }
    }
    else{
        
    }
 })
 $(document).ready(function(){
    @if(isset($edit->id))
    rawmaterial(0,1)
    @endif
 });
</script>
@endpush