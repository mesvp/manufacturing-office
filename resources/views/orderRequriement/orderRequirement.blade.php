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

    /* div#adaaishhhh {
        margin-left: 10px;
        margin-bottom: 20px;


        width: 98.5%;
    } */

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
            <div class="container-fluid">
               <div class="col-xl-12 col-md-12 col-sm-12 mb-2">
                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <!-- <h5>Work Order Details</h5> -->
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                           <label for="">Inputer Name : {{auth()->user()->fullname}}</label>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                           <label for="">Date & Time : <span id="clock"></span></label>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="addbtn extra p-0">
                                <a href="{{url('orderRequirement/orderRequirementList')}}" class="btn btn-info mr-1 btn-sm"> <i class="fa fa-arrow-left"></i></a>
                                <a href="{{url('orderRequirement/orderRequirementList')}}" class="btn btn-info btn-sm"> <i class="fa fa-home"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12 col-md-12 col-sm-12">
                    <!-- <div id="main_btn_uddhan">
                        <button type="button" class="btn btn-success changeFields" id="stock" data-mode="stock">For Stock</button>
                        <button type="button" class="btn btn-primary" id="sales" data-mode="sales">For Sales</button>
                    </div> -->
                    <!--<form action="{{url('orderRequirement/AddSales')}}" id="sales_fieldsform" method="POST" class="sales_fields">-->
                    <!--    @csrf-->
                    <!--    <input class="form-control" type="hidden" name="edit" value="{{isset($editSales->id) && $editSales->id!=''?$editSales->id:''}}">-->
                    <!--     <div id="row">-->
                    <!--        <div class="row" id="adaaishhhh">-->
                    <!--            <h6 class="border-bottom">Sales Order Details</h6>-->


                    <!--           <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">-->
                    <!--                <label>-->
                    <!--                    Organization Name*-->
                    <!--                </label>-->
                    <!--                <select name="Organization" class="form-select form-select-sm" required>-->
                    <!--                    <option value="" selected disabled>Select</option>-->
                    <!--                    @foreach($Organization as $val)-->
                    <!--                    <option value="{{$val->id}}" {{isset($editSales->Organization) && $editSales->Organization==$val->id?'selected':''}}>{{isset($val->organisation) && $val->organisation!=''?$val->organisation:''}}</option>-->
                    <!--                    @endforeach-->
                    <!--                </select>-->
                    <!--            </div>-->
                    <!--            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">-->
                    <!--                <label>-->
                    <!--                    BU Name*-->
                    <!--                </label>-->
                    <!--                <select name="BU_Name" class="form-select form-select-sm" required>-->
                    <!--                    <option value="" selected disabled>Select</option>-->
                    <!--                    @foreach($BU as $val)-->
                    <!--                    <option value="{{$val->id}}" {{isset($editSales->BU_Name) && $editSales->BU_Name==$val->id?'selected':''}}>{{isset($val->BU) && $val->BU!=''?$val->BU:''}}</option>-->
                    <!--                    @endforeach-->
                    <!--                </select>-->
                    <!--            </div>-->
                    <!--            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">-->
                    <!--                <label>-->
                    <!--                    Unit Name*-->
                    <!--                </label>-->
                    <!--                <select name="Unit_Name" id="unitid" class="form-select form-select-sm" required>-->
                    <!--                    <option value="" selected disabled>Select</option>-->
                    <!--                    @foreach($Manufacturing_unit as $val)-->
                    <!--                    <option value="{{$val->id}}" {{isset($editSales->Unit_Name) && $editSales->Unit_Name==$val->id?'selected':''}}>{{$val->Manufacturing_unit}}</option>-->
                    <!--                    @endforeach-->
                    <!--                </select>-->
                    <!--            </div>-->
                    <!--            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">-->
                    <!--                <label>-->
                    <!--                    Plant Name*-->
                    <!--                </label>-->
                    <!--                <select name="Plant_Name" class="form-select form-select-sm" required>-->
                    <!--                    <option value="" selected disabled>Select</option>-->
                    <!--                    @foreach($plant_name as $val)-->
                    <!--                    <option value="{{$val->id}}" {{isset($editSales->Plant_Name) && $editSales->Plant_Name==$val->id?'selected':''}}>{{$val->plant_name}}</option>-->
                    <!--                    @endforeach-->
                    <!--                </select>-->
                    <!--            </div>-->
                    <!--            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">-->
                    <!--                <label> Order Date*</label>-->
                    <!--                <div class="field-wrap">-->
                    <!--                    <input class="form-control form-control-sm" type="date" name="Order_Date" placeholder="Order Date" value="{{isset($editSales->Order_Date) && $editSales->Order_Date!=''?$editSales->Order_Date:''}}" required>-->
                    <!--                </div>-->
                    <!--            </div>-->
                    <!--            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">-->
                    <!--                <label> Customer Name*</label>-->
                    <!--                <select name="Customer_Name" class="form-select form-select-sm" required>-->
                    <!--                    <option value="" selected disabled>Select</option>-->
                    <!--                    @foreach($Customer_Name as $val)-->
                    <!--                    <option value="{{$val->id}}" {{isset($editSales->Customer_Name) && $editSales->Customer_Name==$val->id?'selected':''}}>{{$val->Customer_Name}}</option>-->
                    <!--                    @endforeach-->
                    <!--                </select>-->
                    <!--            </div>-->
                    <!--            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">-->
                    <!--                <label> Company Name*</label>-->
                    <!--                <select name="Company_Name" class="form-select form-select-sm" required>-->
                    <!--                    <option value="" selected disabled>Select</option>-->
                    <!--                    @foreach($Company_Name as $val)-->
                    <!--                    <option value="{{$val->id}}" {{isset($editSales->Company_Name) && $editSales->Company_Name==$val->id?'selected':''}}>{{$val->Company_Name}}</option>-->
                    <!--                    @endforeach-->
                    <!--                </select>-->
                    <!--            </div>-->
                    <!--            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">-->
                    <!--                <label>Country*</lable>-->
                    <!--                    <select class="form-select form-select-sm" name="country" id="country" required>-->
                    <!--                        <option value="" selected disabled>Select Option</option>-->
                    <!--                        @foreach($country as $val)-->
                    <!--                        <option value="{{$val->id}}" {{isset($editSales->country) && $editSales->country==$val->id?'selected':''}}>{{$val->name}}</option>-->
                    <!--                        @endforeach-->
                    <!--                    </select>-->
                    <!--            </div>-->
                    <!--            <div class="col-sm-3 form-group">-->
                    <!--                <label>State*</lable>-->
                    <!--                    <select class="form-select form-select-sm" name="state" id="state" required>-->
                    <!--                        <option value="" selected disabled>Select Option</option>-->
                    <!--                        @foreach($state as $val)-->
                    <!--                        <option value="{{$val->id}}" {{isset($editSales->state) && $editSales->state==$val->id?'selected':''}}>{{$val->name}}</option>-->
                    <!--                        @endforeach-->
                    <!--                    </select>-->
                    <!--            </div>-->
                    <!--            <div class="col-sm-3 form-group">-->
                    <!--                <label>District*</lable>-->
                    <!--                    <select class="form-select form-select-sm" name="district" id="district" required>-->
                    <!--                        <option value="" selected disabled>Select Option</option>-->
                    <!--                        @foreach($city as $val)-->
                    <!--                        <option value="{{$val->id}}" {{isset($editSales->district) && $editSales->district==$val->id?'selected':''}}>{{$val->city}}</option>-->
                    <!--                        @endforeach-->
                    <!--                    </select>-->
                    <!--            </div>-->
                    <!--            <div class="col-sm-3 form-group">-->
                    <!--                <label>Zip Code*</label>-->
                    <!--                <div class="field-wrap">-->
                    <!--                    <input class="form-control form-control-sm" type="text" name="Zip_Code" placeholder="Zip Code" maxlength="6" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="{{isset($editSales->Zip_Code) && $editSales->Zip_Code!=''?$editSales->Zip_Code:''}}" required>-->
                    <!--                </div>-->
                    <!--            </div>-->
                    <!--            <div class="col-sm-3 form-group">-->
                    <!--                <label>Phone*</label>-->
                    <!--                <div class="field-wrap">-->
                    <!--                    <input class="form-control form-control-sm" id="Number" type="phone" min="1000000000" max="9999999999" name="Phone" placeholder="Driver Number" value="{{isset($editSales->Phone) && $editSales->Phone!=''?$editSales->Phone:''}}" required>-->
                    <!--                    <small id="NumberError" style="color: red;"></small>-->
                    <!--                </div>-->
                    <!--            </div>-->
                    <!--            <div class="col-sm-3 form-group">-->
                    <!--                <label>Address</label>-->
                    <!--                <div class="field-wrap">-->
                    <!--                    <input class="form-control form-control-sm" type="text" name="Address" placeholder="Address" value="{{isset($editSales->Address) && $editSales->Address!=''?$editSales->Address:''}}">-->
                    <!--                </div>-->
                    <!--            </div>-->
                    <!--            <div class="col-sm-3 form-group">-->
                    <!--                <label>Fax</label>-->
                    <!--                <div class="field-wrap">-->
                    <!--                    <input class="form-control form-control-sm" type="number" name="Fax" placeholder="Fax" value="{{isset($editSales->Fax) && $editSales->Fax!=''?$editSales->Fax:''}}">-->
                    <!--                </div>-->
                    <!--            </div>-->
                    <!--            <div class="col-sm-3 form-group">-->
                    <!--                <label>GST IN:*</label>-->
                    <!--                <div class="field-wrap">-->
                    <!--                    <input class="form-control form-control-sm" type="text" name="GST" placeholder="GST IN" value="{{isset($editSales->GST) && $editSales->GST!=''?$editSales->GST:''}}" required>-->
                    <!--                </div>-->
                    <!--            </div>-->
                    <!--            <div class="col-sm-3 form-group">-->
                    <!--                <label>Dispatch Date</label>-->
                    <!--                <div class="field-wrap">-->
                    <!--                    <input class="form-control form-control-sm" type="date" name="Dispatch_Date" placeholder="" value="{{isset($editSales->Dispatch_Date) && $editSales->Dispatch_Date!=''?$editSales->Dispatch_Date:''}}">-->
                    <!--                </div>-->
                    <!--            </div>-->
                    <!--            <div class="col-sm-3 form-group">-->
                    <!--                <label> Brand/Label</label>-->
                    <!--                <div class="field-wrap">-->
                    <!--                    <input class="form-control form-control-sm" type="text" name="Brand_Label" placeholder="" value="{{isset($editSales->Brand_Label) && $editSales->Brand_Label!=''?$editSales->Brand_Label:''}}">-->
                    <!--                </div>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--    <br>-->
                    <!--    <div class="row">-->
                    <!--        <div class="col-sm-8 form-group"></div>-->
                    <!--        <div class="col-sm-4 form-group">-->
                    <!--            <label for="State">Remarks:</label>-->
                    <!--            <input type="text" name="remarks" cols="30" rows="5" class="form-control form-control-sm" placeholder="Remarks" value="{{isset($editSales->remarks) && $editSales->remarks!=''?$editSales->remarks:''}}">-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--    <div style="overflow:auto;">-->
                    <!--        <div class="somras">-->
                    <!--            <button type="submit" class="btn btn1 float-right" style="margin: 5px;">Submit</button>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</form>-->

                    <form action="{{url('orderRequirement/AddStock')}}" method="POST" id="stock_fieldsform" class="stock_fields"">
                        @csrf
                        <input class="form-control" type="hidden" name="edit" value="{{isset($editStock->id) && $editStock->id!=''?$editStock->id:''}}">
                        <div id="row">
                            <div class="row" id="adaaishhhh">
                               <h6 class="border-bottom">Procurement Request Details</h6>

                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>
                                        Organization Name*
                                    </label>
                                    <select name="Organization" id="org_id" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Organization as $val)
                                        <option value="{{$val->id}}" {{isset($editStock->Organization) && $editStock->Organization==$val->id?'selected':''}}>{{isset($val->organisation) && $val->organisation!=''?$val->organisation:''}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>
                                        Unit Name*
                                    </label>
                                    <select name="Unit_Name" class="form-select form-select-sm"  id="Manunit" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Manufacturing_unit as $val)
                                        <option value="{{$val->id}}" {{isset($editStock->Unit_Name) && $editStock->Unit_Name==$val->id?'selected':''}}>{{$val->pname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>
                                        Plant Name*
                                    </label>
                                    <select name="Plant_Name" id="plan_uni_id" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @if(isset($editStock->Plant_Name))
                                            @foreach($plant_name as $val)
                                            <option value="{{$val->id}}" {{isset($editStock->Plant_Name) && $editStock->Plant_Name==$val->id?'selected':''}}>{{$val->spname}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                {{-- <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>
                                        BU Name*
                                    </label>
                                    <select name="BU_Name" id="bunameid" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @if(isset($editStock->BU_Name))
                                        @foreach($BU as $val)
                                        <option value="{{$val->id}}" {{isset($editStock->BU_Name) && $editStock->BU_Name==$val->id?'selected':''}}>{{isset($val->unit_name) && $val->unit_name!=''?$val->unit_name:''}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div> --}}
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>BU Name*</label>
                                    <select name="BU_Name" id="bunameid" class="form-select form-select-sm" required {{ isset($editStock->BU_Name) ? 'disabled' : '' }}>
                                        <option value="" selected disabled>Select</option>
                                        @if(isset($editStock->BU_Name))
                                        @foreach($BU as $val)
                                        <option value="{{$val->id}}" {{isset($editStock->BU_Name) && $editStock->BU_Name==$val->id?'selected':''}}>{{isset($val->unit_name) && $val->unit_name!=''?$val->unit_name:''}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    @if(isset($editStock->BU_Name))
                                    <input type="hidden" name="BU_Name" id="hidden_bunameid" value="{{ $editStock->BU_Name }}">
                                    @endif
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label style="font-size: 11px;">
                                        Godown Name*
                                    </label>
                                    <select name="Factory_Godown_Name" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Godown_Name as $val)
                                        <option value="{{$val->id}}" {{isset($editStock->Factory_Godown_Name) && $editStock->Factory_Godown_Name==$val->id?'selected':''}}>{{$val->inventory_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-1 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label style="font-size: 11px;">Exp. Delivery DT.*</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="date" name="Expected_Date" placeholder="Order Date" value="{{isset($editStock->Expected_Date) && $editStock->Expected_Date!=''?$editStock->Expected_Date:''}}" required>
                                    </div>
                                </div>
                                <div class="col-xl-1 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>PR Type</label>
                                    @if(isset($editStock->procurement_type))
                                        <select name="procurement_type_display" id="procurement_type_display" class="form-select form-select-sm js-example-matcher-start" required disabled>
                                            <option value="" {{ !isset($editStock->procurement_type) ? 'selected' : '' }}>Select</option>
                                            <option value="Normal" {{ (isset($editStock->procurement_type) && $editStock->procurement_type == 'Normal') ? 'selected' : '' }}>Normal</option>
                                            <option value="Loose" {{ (isset($editStock->procurement_type) && $editStock->procurement_type == 'Loose') ? 'selected' : '' }}>Loose</option>
                                            <option value="Additional" {{ (isset($editStock->procurement_type) && $editStock->procurement_type == 'Additional') ? 'selected' : '' }}>Additional</option>
                                        </select>
                                        <input type="hidden" name="procurement_type" id="procurement_type" value="{{ isset($editStock->procurement_type) ? $editStock->procurement_type : '' }}">
                                    @else
                                        <div class="field-wrap">
                                            <select  name="procurement_type" id="procurement_type" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" required>
                                                <option value="" selected>Select</option>
                                                <option value="Normal">Normal</option>
                                                <option value="Loose">Loose</option>
                                                <option value="Additional">Additional</option>
                                            </select>
                                        </div>
                                    @endif
                                </div>


                                @if(Request::is('orderRequirement/orderRequirement'))
                                <div class="col-sm-10 form-group" id="additionalhide">

                                </div>
                                @endif


                                <?php
                                $editprocurementid="";
                                if(isset($editStock)) {
                                    $editprocurementid = $editStock->procurement_type;

                                    if($editprocurementid != "Additional") {  ?>
                                    <div class="col-sm-0 form-group" id="additionalhide">

                                    </div>
                                    <?php
                                    }

                                    if($editprocurementid != "Additional") { ?>
                                        <div class="col-sm-3 form-group">
                                        <label>
                                            Finished Good(FG)*
                                            </lable>
                                            <select name="Raw_Material" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" id="RawMaterial" required>
                                                <option value="" selected>Select</option>
                                                @foreach($Raw_Material as $val)
                                                <option value="{{$val->RawMaterial->id}}" {{isset($editStock->Raw_Material) && $editStock->Raw_Material==$val->RawMaterial->id?'selected':''}}>{{str_replace('"', '&quot;', str_replace("'", '&#039;', $val->RawMaterial->matname))}}</option>
                                                @endforeach
                                            </select>
                                            {{-- <input class="form-control form-control-sm" type="hidden" name="Raw_Material" placeholder="" value="{{isset($raw_material_name->id) && $raw_material_name->id!=''?$raw_material_name->id:''}}" > --}}
                                    </div>
                                            {{-- @if(isset($raw_material_name->id))
                                            <div class="col-sm-3 form-group" id="finishedgddiv">
                                                <label>Finished Good(FG)*</label>
                                                <div class="field-wrap">
                                                    <input class="form-control form-control-sm" type="hidden" name="Raw_Material" placeholder="" value="{{isset($raw_material_name->id) && $raw_material_name->id!=''?$raw_material_name->id:''}}" >
                                                    <input class="form-control form-control-sm" readonly type="text"  placeholder="Finished Good" value="{{isset($raw_material_name->matname) && $raw_material_name->matname!=''? str_replace('"', '&quot;', str_replace("'", '&#039;', $raw_material_name->matname)) :''}}" >
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-sm-3 form-group" id="finishedgddiv">
                                                <label>Finished Good(FG)*</label>
                                                <select name="Raw_Material" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" id="RawMaterial">
                                                    <option value="" selected disabled>Select</option>
                                                    @foreach($Raw_Material as $val)
                                                        <option value="{{$val->RawMaterial->id}}" {{isset($editStock->Raw_Material) && $editStock->Raw_Material==$val->RawMaterial->id?'selected':''}}>{{str_replace('"', '&quot;', str_replace("'", '&#039;', $val->RawMaterial->matname))}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif --}}
                                        <div class="col-sm-3 form-group" id="hsncodediv">
                                            <label>HSN Code*</label>
                                            <div class="field-wrap">
                                                <input readonly class="form-control form-control-sm" type="number" name="HSN_Code" id="HSNCode" placeholder="HSN Code" value="{{isset($editStock->HSN_Code) && $editStock->HSN_Code!=''?$editStock->HSN_Code:''}}" >
                                            </div>
                                        </div>
                                        <div class="col-sm-3 form-group" id="uomdiv">
                                            <label>UOM</label>
                                            <div class="field-wrap">
                                                <input readonly class="form-control form-control-sm" type="text" name="UOM" id="uom" placeholder="UOM" value="{{isset($editStock->UOM) && $editStock->UOM!=''?$editStock->UOM:''}}" >
                                            </div>
                                        </div>

                                    <?php }
                                }
                            ?>
                            @if(Request::is('orderRequirement/orderRequirement'))
                                        @if(isset($raw_material_name->id))
                                        <div class="col-sm-3 form-group" id="finishedgddiv">
                                            <label>Finished Good(FG)*</label>
                                            <div class="field-wrap">
                                                <input class="form-control form-control-sm" type="hidden" name="Raw_Material" placeholder="" value="{{isset($raw_material_name->id) && $raw_material_name->id!=''?$raw_material_name->id:''}}" >
                                                <input class="form-control form-control-sm" readonly type="text"  placeholder="Finished Good" value="{{isset($raw_material_name->matname) && $raw_material_name->matname!=''? str_replace('"', '&quot;', str_replace("'", '&#039;', $raw_material_name->matname)) :''}}" >
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-sm-3 form-group" id="finishedgddiv">
                                            <label>Finished Good(FG)*</label>
                                            <select name="Raw_Material" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" id="RawMaterial">
                                                <option value="" selected disabled>Select</option>
                                                @foreach($Raw_Material as $val)
                                                    <option value="{{$val->RawMaterial->id}}" {{isset($editStock->Raw_Material) && $editStock->Raw_Material==$val->RawMaterial->id?'selected':''}}>{{str_replace('"', '&quot;', str_replace("'", '&#039;', $val->RawMaterial->matname))}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    <div class="col-sm-3 form-group" id="hsncodediv">
                                        <label>HSN Code*</label>
                                        <div class="field-wrap">
                                            <input readonly class="form-control form-control-sm" type="number" name="HSN_Code" id="HSNCode" placeholder="HSN Code" value="{{isset($editStock->HSN_Code) && $editStock->HSN_Code!=''?$editStock->HSN_Code:''}}" >
                                        </div>
                                    </div>
                                    <div class="col-sm-3 form-group" id="uomdiv">
                                        <label>UOM</label>
                                        <div class="field-wrap">
                                            <input readonly class="form-control form-control-sm" type="text" name="UOM" id="uom" placeholder="UOM" value="{{isset($editStock->UOM) && $editStock->UOM!=''?$editStock->UOM:''}}" >
                                        </div>
                                    </div>
                                   @endif


                                @if(isset($editStock->QTY) && $editStock->procurement_type == "Normal")
                                <div class="col-sm-3 form-group" id="qtydiv">
                                    <label>QTY*</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm"  type="number" name="QTY" id="QTY" placeholder="QTY" value="{{isset($editStock->QTY) && $editStock->QTY!=''?$editStock->QTY:''}}">
                                    </div>
                                </div>
                                @else
                                        @if(Request::is('orderRequirement/orderRequirement/*'))
                                    <div class="col-sm-0 form-group" >
                                    </div>
                                    @endif
                                @endif
                                @if(Request::is('orderRequirement/orderRequirement'))
                                    <div class="col-sm-3 form-group" id="qtydiv">
                                        <label>QTY*</label>
                                        <div class="field-wrap">
                                            <input class="form-control form-control-sm" type="number" name="QTY" id="QTY" placeholder="QTY" value="">
                                        </div>
                                    </div>
                                    <div class="col-sm-0 form-group" style="display: none" id="qtydiv2">
                                    </div>
                                @endif

                                {{-- <div class="col-sm-3 form-group" id="qtydiv">
                                    <label>QTY*</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="number" name="QTY" id="QTY" placeholder="QTY" value="">
                                    </div>
                                </div> --}}



                                {{-- <div class="col-sm-3 form-group" style="display: none" id="qtydiv2">
                                </div>
                                @endif --}}
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>Billing Address</label>
                                    <div class="field-wrap">
                                        <select name="billing_address" id="billing_address" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" id="RawMaterial" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($address_bill as $val)
                                            <option value="{{$val->id}}" {{isset($editStock->billing_address) &&               $editStock->billing_address==$val->id?'selected':''}}>{{$val->sname}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>Billing Details*</label>
                                    <div class="field-wrap">
                                        <textarea class="form-control form-control-sm" readonly name="billing_details" id="billing_details">{{isset($editStock->billing_details) && $editStock->billing_details!=''?$editStock->billing_details:''}}</textarea>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-2 col-sm-12 form-group">

                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-2 col-sm-12 form-group">

                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>Shipping Address</label>
                                    <div class="field-wrap">
                                        <select class="form-control form-control-sm" name="shipping_address" id="shipping_address" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" id="RawMaterial" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($address_ship as $val)
                                            <option value="{{$val->id}}" {{isset($editStock->Organization) &&               $editStock->shipping_address==$val->id?'selected':''}}>{{$val->sname}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>Shipping Details*</label>
                                    <div class="field-wrap">
                                        <textarea class="form-control form-control-sm" readonly name="shiping_details" id="shiping_details">{{isset($editStock->shiping_details) && $editStock->shiping_details!=''?$editStock->shiping_details:''}}</textarea>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-2 col-sm-12 form-group">

                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-2 col-sm-12 form-group">

                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>Delivery Address*</label>
                                    <div class="field-wrap">
                                        <textarea class="form-control form-control-sm" name="delivery_address" id="delivery_address">{{isset($editStock->delivery_address) && $editStock->delivery_address!=''?$editStock->delivery_address:''}}</textarea>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>Contact Person</label>
                                    <div class="field-wrap">
                                        <select name="contact_psrn" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($USER as $val)
                                            <option value="{{$val->id}}" {{isset($editStock->contact_psrn) && $editStock->contact_psrn==$val->id?'selected':''}}>{{$val->fullname}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>Phone*</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="number" name="phone" id="phone" placeholder="Phone" value="{{isset($editStock->phone) && $editStock->phone!=''?$editStock->phone:''}}" required>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="col-xl-12 col-md-12 col-md-12 ">
                                <h5>Product Details</h5>
                            </div>
                            <div class="table-responsive">
                                <table id="Tabledata" class="table table-striped table-bordered dataTable no-footer example p-2" style="width:100%">
                                    <thead>
                                        <tr>
                                            <?php if($editprocurementid == "Additional") { ?>
                                                <button type="button" id="removeadditional" class="btn float-right btn-danger" onclick="removeadditional();">Remove Material</button>
                                            <?php } ?>
                                            <th class="th-sm" id="sl_no" style="display: none">Sl No.</th>
                                            <th class="th-sm" id="select_all" ><input type="checkbox" name="select_all" class="form-check-input checkAll m-0" onclick="checkAll(this)" /></th>
                                            <th class="th-sm">Material</th>
                                            <th class="th-sm">HSN Code</th>
                                            <th class="th-sm">UOM</th>
                                            <th class="th-sm">QTY</th>
                                            <th class="th-sm">Rate </th>
                                            <th class="th-sm">Amount </th>
                                            <th class="th-sm" style="display: none" id="gstper">GST PER</th>
                                            <?php if($editprocurementid == "Additional") { ?>
                                            <th class="th-sm">GST PER</th>
                                            <?php } ?>
                                            <th class="th-sm">GST AMT</th>
                                            <th class="th-sm">Sub Total </th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($product as $key => $val)
                                        <tr>

                                            <td>{{$key+1}}
                                            <input type="hidden" name="loose" value="">
                                            <input type="hidden" name="Additional" value="">
                                            <input type="hidden" name="Normal" value="">
                                            </td>
                                            <td>
                                                <input readonly type="text" name="" class="form-control form-control-sm" value="{{isset($val->material_name->matname) && $val->material_name->matname!=''? str_replace('"', '&quot;', str_replace("'", '&#039;', $val->material_name->matname)) :''}}">
                                            </td>
                                            <td>
                                                <input readonly type="text" name="" class="form-control form-control-sm" value="{{isset($val->HSN_Code_Second) && $val->HSN_Code_Second!=''?$val->HSN_Code_Second:''}}">
                                            </td>
                                            <td>
                                                <input readonly type="text" name="" class="form-control form-control-sm" value="{{isset($val->UOM_Second) && $val->UOM_Second!=''?$val->UOM_Second:''}}">
                                                {{-- <select disabled name="" class="form-select form-select-sm">
                                                    <option value="" selected disabled>Select</option>
                                                    @foreach($UOM as $values)
                                                    <option value="{{$values->id}}" {{isset($val->UOM_Second) && $val->UOM_Second==$values->id?'selected':''}}>{{$values->UOMs}}</option>
                                                    @endforeach
                                                </select> --}}
                                            </td>
                                            <td>
                                                <input readonly type="text" name="" class="form-control form-control-sm" value="{{isset($val->Total_QTY) && $val->Total_QTY!=''?$val->Total_QTY:''}}">
                                            </td>
                                            <td>
                                                <input readonly type="text" name="" class="form-control form-control-sm" value="{{isset($val->Rate) && $val->Rate!=''?$val->Rate:''}}">
                                            </td>
                                            <td>
                                                <input readonly type="text" name="" class="form-control form-control-sm" value="{{isset($val->Amount) && $val->Amount!=''?$val->Amount:''}}">
                                            </td>
                                            <?php if($editprocurementid == "Additional") { ?>
                                            <td>
                                                <input readonly type="text" name="" class="form-control form-control-sm" value="{{isset($val->GST_Per) && $val->GST_Per!=''?$val->GST_Per:''}}">
                                            </td>
                                            <?php } ?>
                                            <td>
                                                <input readonly type="text" name="" class="form-control form-control-sm" value="{{isset($val->GST_Value) && $val->GST_Value!=''?$val->GST_Value:''}}">
                                            </td>
                                            <td>
                                                <input readonly type="text" name="Sub_Total" class="form-control form-control-sm" value="{{isset($val->Sub_Total) && $val->Sub_Total!=''?$val->Sub_Total:''}}">
                                            </td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="8" class="text-right font-weight-bold">Total</td>
                                            <td>
                                                <input readonly type="text" name="Total" class="form-control form-control-sm" value="{{isset($editStock->Total) && $editStock->Total!=''?$editStock->Total:''}}">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-8 form-group"></div>
                            <div class="col-sm-4 form-group">
                                <label for="State">Remarks:</label>
                                <input type="text" name="remarks" cols="30" rows="5" class="form-control form-control-sm" placeholder="Remarks" value="{{isset($editStock->remarks) && $editStock->remarks!=''?$editStock->remarks:''}}">
                            </div>
                        </div>
                        <div style="overflow:auto;">
                            <div class="somras">
                                <button type="button" id="draft" class="btn btn1 float-right" style="margin: 5px;">Draft & Save</button>
                                <a href="" class="btn btn1 float-right" style="margin: 5px; display: {{isset($editStock->id) && $editStock->id != ''?'none':'block'}}">Clear All</a>
                                <button type="submit" id="submitBtn" class="btn btn1 float-right" style="margin: 5px;">Submit</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </section>
    </div>
</div>

@endsection
@push('custom-scripts')
<script>
    $(document).ready(function() {
        activeclass(25, 1);
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
</script>
<script>
    $(document).ready(function() {
        const storedMode = localStorage.getItem('Orders');

        const urlParams = new URLSearchParams(window.location.search);
        const mode = urlParams.get('mode');

        if (mode === 'sales') {
            $(".stock_fields").hide();
            $(".sales_fields").show();
        } else if (mode === 'stock') {
            $(".sales_fields").hide();
            $(".stock_fields").show();
        } else if (storedMode === 'sales') {
            $(".stock_fields").hide();
            $(".stock_fields").show();
        } else if (storedMode === 'stock') {
            $(".sales_fields").hide();
            $(".stock_fields").show();
        }

        $(".changeFields").on("click", function() {
            const Orders = $(this).data('mode');
            localStorage.setItem('Orders', Orders);

            if (Orders === 'sales') {
                $(".stock_fields").hide();
                $(".sales_fields").show();
            } else if (Orders === 'stock') {
                $(".sales_fields").hide();
                $(".stock_fields").show();
            }
        });
    });
</script>
<script>
    // Function to escape HTML special characters
    function escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Materials data from server (safe JSON encoding)
    var materialsData = @json($Materials->pluck('matname', 'id'));

    // Function to create material select options
    function createMaterialSelect(index) {
        var select = $('<select>')
            .attr('name', 'Material_Name[]')
            .attr('onchange', 'Assetunit(' + index + ');')
            .addClass('form-select form-select-sm js-example-matcher-start')
            .attr('id', 'Material_' + index)
            .attr('required', true);
        
        // Add default option
        select.append('<option value="" selected disabled>Select</option>');
        
        // Add material options safely
        $.each(materialsData, function(id, name) {
            select.append($('<option>').val(id).text(name));
        });
        
        return select.prop('outerHTML');
    }

    $('#removeadditional').on('click', function() {
        var MaterialId = $('#RawMaterial').val();

        $.ajax({
            url: "{{url('RawMaterial/MaterialData')}}" + '/' + MaterialId,
            type: 'GET',
            data: {
                MaterialId: MaterialId
            },
            success: function(data) {
                $('#HSNCode').val(data.data.HSN_Code);
                $('#uom').val(data.data.UOM).change();
            }
        });

        var QTYValue = $('#QTY').val();
        var ptypeValue = $('#procurement_type').val();
        //alert(ptypeValue);

        $.ajax({
            url: "{{url('orderRequirement/MaterialData')}}" + '/' + MaterialId,
            type: 'GET',
            data: {
                MaterialId: MaterialId
            },
            success: function(data) {
                    $('#sl_no').hide();
                    $('#select_all').hide();
                    $('#qtydiv').hide();
                    $('#qtydiv2').show();
                    $('#hsncodediv').hide();
                    $('#uomdiv').hide();
                    $('#finishedgddiv').hide();
                    $('#additionalhide').hide();
                    $('#gstper').show();

                    var table = $('#Tabledata');
                    table.find('tbody').empty();
                    var Total = 0;

                    var newRow = '<tr>' +
                        '<td>' + createMaterialSelect(index) + '</td>' +
                        '<td><input readonly type="text" name="HSN_Code_Second[]" id="HSN_Code_Second_' + index + '" class="form-control form-control-sm" value=""></td>' +
                        '<td><input readonly type="text" name="UOM_Second[]" id="UOM_Second_' + index + '" class="form-control form-control-sm" value=""></td>' +
                        '<td><input type="text" id="Total_QTY_' + index + '" name="Total_QTY[]" class="form-control form-control-sm" value=""></td>' +
                        '<td><input type="text" name="Rate[]" id="Rate_' + index + '" class="form-control form-control-sm" value=""></td>' +
                        '<td><input readonly type="text" name="Amount[]" id="Amount_' + index + '" class="form-control form-control-sm" value=""></td>' +
                        '<td><input type="text" required name="GST_Per[]" id="GST_Per_' + index + '" class="form-control form-control-sm" value=""></td>' +
                        '<td><input readonly type="text" name="GST_Value[]" id="GST_Value_' + index + '" class="form-control form-control-sm" value=""></td>' +
                        '<td><input readonly type="text" name="Sub_Total[]" id="Sub_Total_' + index + '" class="form-control form-control-sm" value=""></td>' +
                        '<td><a href="javascript:;" class="btn btn-success btn-sm mt-4 productappend" data-index="' + index + '"><i class="fa fa-plus" aria-hidden="true"></i></a></td>' +
                        '</tr>';

                    table.find('tbody').append(newRow);
                    $('.js-example-matcher-start').select2();

                    // Append total row
                    var totalRow = '<tfoot><tr>' +
                        '<td colspan="8" class="text-right font-weight-bold">Total</td>' +
                        '<td><input readonly type="text" name="Total" class="form-control form-control-sm" value=""></td>' +
                        '</tr></tfoot>';
                    $('#Tabledata').append(totalRow);

                            var index = 0;
                        $(document).on('click', '.productappend', function () {
                        index++;
                        var currentIndex = index;
                        var newRow = '<tr>' +
                            '<td>' + createMaterialSelect(currentIndex) + '</td>' +
                            '<td><input readonly type="text" name="HSN_Code_Second[]" id="HSN_Code_Second_' + currentIndex + '" class="form-control form-control-sm" value=""></td>' +
                            '<td><input readonly type="text" name="UOM_Second[]" id="UOM_Second_' + currentIndex + '" class="form-control form-control-sm" value=""></td>' +
                            '<td><input type="text" id="Total_QTY_' + currentIndex + '" name="Total_QTY[]" class="form-control form-control-sm" value=""></td>' +
                            '<td><input type="text" name="Rate[]" id="Rate_' + currentIndex + '" class="form-control form-control-sm" value=""></td>' +
                            '<td><input readonly type="text" name="Amount[]" id="Amount_' + currentIndex + '" class="form-control form-control-sm" value=""></td>' +
                            '<td><input type="text" required name="GST_Per[]" id="GST_Per_' + currentIndex + '" class="form-control form-control-sm" value=""></td>' +
                            '<td><input readonly type="text" name="GST_Value[]" id="GST_Value_' + currentIndex + '" class="form-control form-control-sm" value=""></td>' +
                            '<td><input readonly type="text" name="Sub_Total[]" id="Sub_Total_' + currentIndex + '" class="form-control form-control-sm" value=""></td>' +
                            '<td><a href="javascript:;" class="btn btn-danger btn-sm delete-row" id="delete_' + currentIndex + '"><i class="fa fa-trash" aria-hidden="true"></i></a></td>' +
                            '</tr>';

                        $('#Tabledata tbody').append(newRow);
                        $('.js-example-matcher-start').select2();

                                    // Recalculate Total
                                    var total = calculatesubTotal();
                                    // Update Total row
                                    updateTotalRow(total);
                                });
                                    // Function to calculate total
                                    function calculatesubTotal() {
                                        var total = 0;
                                        $('#Tabledata tbody tr').each(function () {
                                            var rowTotal = parseFloat($(this).find('[name="Sub_Total[]"]').val());
                                            if (!isNaN(rowTotal)) {
                                                total += rowTotal;
                                            }
                                        });
                                        return total;
                                        updateTotalRow(total);
                                    }


                                    // Function to calculate Total
                                    function calculateTotal() {
                                        var total = 0;
                                        $('#Tabledata tbody tr').each(function () {
                                            var qty = parseFloat($(this).find('[name^="Total_QTY"]').val()) || 0;
                                            var rate = parseFloat($(this).find('[name^="Rate"]').val()) || 0;
                                            var amount = qty * rate;
                                            total += amount;
                                        });
                                        return total;
                                    }

                                    // Function to calculate GST Value and Subtotal
                                    function calculateGSTAndSubtotal() {
                                        var total = 0; // Define total variable
                                        $('#Tabledata tbody tr').each(function () {
                                            var qty = parseFloat($(this).find('[name^="Total_QTY"]').val()) || 0;
                                            var rate = parseFloat($(this).find('[name^="Rate"]').val()) || 0;
                                            var gstPer = parseFloat($(this).find('[name^="GST_Per"]').val()) || 0;

                                            var amount = qty * rate;
                                            var gstValue = (amount * gstPer) / 100;
                                            var subTotal = amount + gstValue;

                                            $(this).find('[name^="Amount"]').val(amount.toFixed(2));
                                            $(this).find('[name^="GST_Value"]').val(gstValue.toFixed(2));
                                            $(this).find('[name^="Sub_Total"]').val(subTotal.toFixed(2));

                                            total += subTotal; // Update total
                                        });

                                        updateTotalRow(total); // Update total row outside the loop
                                    }


                                         $(document).ready(function () {
                                            // Add event handler for Total_QTY field
                                            $(document).on('keyup', '[name^="Total_QTY"]', function () {
                                                var total = calculateTotal();
                                                updateTotalRow(total);
                                                calculateGSTAndSubtotal();
                                            });

                                            // Add event handler for GST_Per field
                                            $(document).on('keyup', '[name^="GST_Per"]', function () {
                                                calculateGSTAndSubtotal();
                                            });
                                            $(document).ready(function () {
                                                    $(document).on('keyup', '[name^="Total_QTY"], [name^="Rate"], [name^="GST_Per"]', function () {
                                                        calculateGSTAndSubtotal();
                                                    });
                                                });
                                        });
                                        // function calculation total end
                                        // Function to update Total row
                                        function updateTotalRow(total) {
                                            $('#Tabledata tfoot').remove(); // Remove existing total row
                                            var totalRow = '<tfoot><tr>' +
                                                '<td colspan="8" class="text-right font-weight-bold">Total</td>' +
                                                '<td><input type="hidden" name="Additional" value="1"><input readonly type="text" name="Total" class="form-control form-control-sm" value="' + total.toFixed(2) + '"></td>' +
                                                '</tr></tfoot>';
                                            $('#Tabledata').append(totalRow);
                                        }

                                        // Delete row
                                        $(document).on('click', '.delete-row', function () {
                                            $(this).closest('tr').remove();
                                            // Recalculate Total
                                            var total = calculatesubTotal();
                                            // Update Total row
                                            updateTotalRow(total);
                                        });



            }
        });

    });
    $('#RawMaterial,#QTY,#procurement_type').on('change', function() {
        var MaterialId = $('#RawMaterial').val();

        $.ajax({
            url: "{{url('RawMaterial/MaterialData')}}" + '/' + MaterialId,
            type: 'GET',
            data: {
                MaterialId: MaterialId
            },
            success: function(data) {
                $('#HSNCode').val(data.data.HSN_Code);
                $('#uom').val(data.data.UOM).change();
            }
        });

        var QTYValue = $('#QTY').val();
        var ptypeValue = $('#procurement_type').val();
        //alert(ptypeValue);

        $.ajax({
            url: "{{url('orderRequirement/MaterialData')}}" + '/' + MaterialId,
            type: 'GET',
            data: {
                MaterialId: MaterialId
            },
            success: function(data) {
                if(ptypeValue == "Normal"){
                $('#sl_no').show(); // Show Sl No. column
                $('#select_all').hide(); // Hide checkbox column
                $('#qtydiv').show();
                $('#qtydiv2').hide();
                $('#hsncodediv').show();
                $('#uomdiv').show();
                $('#finishedgddiv').show();
                $('#additionalhide').show();
                $('#gstper').hide();
                var table = $('#Tabledata');
                table.find('tbody').empty();

                var Total = 0;

                for (var i = 0; i < data.data.length; i++) {
                    var rowData = data.data[i];
                    var TotalQTY = (!isNaN(rowData.Total_QTY) && rowData.Total_QTY !== null) ? rowData.Total_QTY * QTYValue : 0;
                    var AmountValue = (!isNaN(TotalQTY) && TotalQTY !== null) ? TotalQTY * rowData.Basic_Amount_unit : 0;
                    var GSTValue = (!isNaN(AmountValue) && AmountValue !== null) ? (AmountValue * rowData.GST_Percentage) / 100 : 0;
                    var SubTotal = (!isNaN(GSTValue) && GSTValue !== null) ? AmountValue + GSTValue : AmountValue;
                    Total += SubTotal;

                    var uomSelect = '<select disabled name="UOM_Second[' + i + ']" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" required>' + '<option value="" selected disabled>Select</option>';

                    @foreach($UOM as $val)
                    uomSelect += '<option value="{{$val->id}}" ' + (rowData.UOM == "{{$val -> id}}" ? "selected" : "") + '>{{$val->UOMs}}</option>';
                    @endforeach

                    uomSelect += '</select>';

                    var newRow = '<tr>' +
                        '<td>' + (i + 1) + '</td>' +
                        '<td><input readonly type="text" name="Material_Name[' + i + ']" class="form-control form-control-sm" value="' + escapeHtml(rowData.RawMaterial.matname) + '"><input readonly type="hidden" name="Material_id[' + i + ']" class="form-control form-control-sm" value="' + rowData.RawMaterial.matid + '"></td>' +
                        '<td><input readonly type="text" name="HSN_Code_Second[' + i + ']" class="form-control form-control-sm" value="' + rowData.HSN_Code_Second + '"></td>' +
                        '<td><input readonly type="text" name="UOM_Second[' + i + ']" class="form-control form-control-sm" value="' + rowData.UOM + '"></td>' +
                        '<td><input readonly type="text" id="" name="Total_QTY[' + i + ']" class="form-control form-control-sm" value="' + TotalQTY + '"></td>' +
                        '<td><input readonly type="text" name="Rate[' + i + ']" class="form-control form-control-sm" value="' + rowData.Basic_Amount_unit + '"></td>' +
                        '<td><input readonly type="text" name="Amount[' + i + ']" class="form-control form-control-sm" value="' + AmountValue + '"></td>' +
                        '<td><input readonly type="text" name="GST_Value[' + i + ']" class="form-control form-control-sm" value="' + GSTValue + '"></td>' +
                        '<td><input readonly type="text" name="Sub_Total[' + i + ']" class="form-control form-control-sm" value="' + SubTotal + '"></td>' +
                        '</tr>';

                    table.find('tbody').append(newRow);
                }

                var totalRow = '<tr>' +
                    '<td colspan="8" class="text-right font-weight-bold">Total</td>' +
                    '<td><input type="hidden" name="Normal" value="1"><input readonly type="text" name="Total" class="form-control form-control-sm" value="' + Total + '"></td>' +
                    '</tr>';
                table.find('tbody').append(totalRow);
                }
                if(ptypeValue == "Loose"){
                    $('#sl_no').hide(); // Show Sl No. column
                    $('#select_all').show(); // Hide checkbox column
                    $('#Tabledata tbody').empty(); // Clear table body
                    $('#totqty, #totamt, #gstamt, #subtotamt').empty('');
                    $('#qtydiv').hide();
                    $('#qtydiv2').show();
                    $('#hsncodediv').show();
                    $('#uomdiv').show();
                    $('#finishedgddiv').show();
                    $('#additionalhide').show();
                    $('#gstper').hide();
                    var table = $('#Tabledata');
                    table.find('tbody').empty();


                    var Total = 0;

                    var additionalDiv = '<div class="col-sm-3 form-group"></div>';
                    $('#qtydiv').parent().append(additionalDiv);

                    for (var i = 0; i < data.data.length; i++) {
                        var rowData = data.data[i];
                        var TotalQTY = (!isNaN(rowData.Total_QTY) && rowData.Total_QTY !== null) ? rowData.Total_QTY * QTYValue : 0;
                        var AmountValue = (!isNaN(TotalQTY) && TotalQTY !== null) ? TotalQTY * rowData.Basic_Amount_unit : 0;
                        var GSTValue = (!isNaN(AmountValue) && AmountValue !== null) ? (AmountValue * rowData.GST_Percentage) / 100 : 0;
                        var SubTotal = (!isNaN(GSTValue) && GSTValue !== null) ? AmountValue + GSTValue : AmountValue;
                        Total += SubTotal;

                        var uomSelect = '<select disabled name="UOM_Second[' + i + ']" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" required>' + '<option value="" selected disabled>Select</option>';

                        @foreach($UOM as $val)
                        uomSelect += '<option value="{{$val->id}}" ' + (rowData.UOM == "{{$val -> id}}" ? "selected" : "") + '>{{$val->UOMs}}</option>';
                        @endforeach

                        uomSelect += '</select>';

                        var newRow = '<tr>' +
                            '<td> <input type="checkbox"  name="check[' + i + ']" class="form-check-input custom-checkbox m-1" onclick="onClickcalc()" id="check_' + i + '" value="' + i + '"> </td>' +
                            '<td><input readonly type="text" name="Material_Name[' + i + ']" class="form-control form-control-sm" value="' + escapeHtml(rowData.RawMaterial.matname) + '"><input readonly type="hidden" name="Material_id[' + i + ']" id="mat_id_' + i + '" class="form-control form-control-sm" value="' + rowData.RawMaterial.matid + '"></td>' +
                            '<td><input readonly type="text" name="HSN_Code_Second[' + i + ']" class="form-control form-control-sm" value="' + rowData.HSN_Code_Second + '"></td>' +
                            '<td><input readonly type="text" name="UOM_Second[' + i + ']" class="form-control form-control-sm" value="' + rowData.UOM + '"></td>' +
                            '<td><input type="text" name="Total_QTY[' + i + ']" id="totqty_' + i + '" onkeyup="return functioncal(' + i + ');" class="form-control form-control-sm" value=""></td>' +
                            '<td><input readonly type="text" name="Rate[' + i + ']" id="totrate_' + i + '" class="form-control form-control-sm" value="' + rowData.Basic_Amount_unit + '"></td>' +
                            '<td><input readonly type="text" name="Amount[' + i + ']" id="amt_' + i + '" class="form-control form-control-sm" value=""></td>' +
                            '<td><input readonly type="text" name="GST_Value[' + i + ']" id="gstamt_' + i + '" class="form-control form-control-sm" value=""></td>' +
                            '<td><input readonly type="text" name="Sub_Total[' + i + ']" id="totamt_' + i + '" class="form-control form-control-sm" value=""></td>' +
                            '</tr>';

                        table.find('tbody').append(newRow);
                    }

                    var totalRow = '<tr>' +
                        '<td colspan="8" class="text-right font-weight-bold">Total</td>' +
                        '<td><input type="hidden" name="loose" value="1"><input readonly type="text" required name="Total" id="subtotamt" class="form-control form-control-sm" value=""></td>' +
                        '</tr>';
                    table.find('tbody').append(totalRow);

                }

                if (ptypeValue == "Additional") {
                    $('#sl_no').hide();
                    $('#select_all').hide();
                    $('#qtydiv').hide();
                    $('#qtydiv2').show();
                    $('#hsncodediv').hide();
                    $('#uomdiv').hide();
                    $('#finishedgddiv').hide();
                    $('#additionalhide').hide();
                    $('#gstper').show();

                    var table = $('#Tabledata');
                    table.find('tbody').empty();
                    var Total = 0;

                    var newRow = '<tr>' +
                        '<td>' + createMaterialSelect(index) + '</td>' +
                        '<td><input readonly type="text" name="HSN_Code_Second[]" id="HSN_Code_Second_' + index + '" class="form-control form-control-sm" value=""></td>' +
                        '<td><input readonly type="text" name="UOM_Second[]" id="UOM_Second_' + index + '" class="form-control form-control-sm" value=""></td>' +
                        '<td><input type="text" id="Total_QTY_' + index + '" name="Total_QTY[]" class="form-control form-control-sm" value=""></td>' +
                        '<td><input type="text" name="Rate[]" id="Rate_' + index + '" class="form-control form-control-sm" value=""></td>' +
                        '<td><input readonly type="text" name="Amount[]" id="Amount_' + index + '" class="form-control form-control-sm" value=""></td>' +
                        '<td><input type="text" required name="GST_Per[]" id="GST_Per_' + index + '" class="form-control form-control-sm" value=""></td>' +
                        '<td><input readonly type="text" name="GST_Value[]" id="GST_Value_' + index + '" class="form-control form-control-sm" value=""></td>' +
                        '<td><input readonly type="text" name="Sub_Total[]" id="Sub_Total_' + index + '" class="form-control form-control-sm" value=""></td>' +
                        '<td><a href="javascript:;" class="btn btn-success btn-sm mt-4 productappend" data-index="' + index + '"><i class="fa fa-plus" aria-hidden="true"></i></a></td>' +
                        '</tr>';

                    table.find('tbody').append(newRow);
                    $('.js-example-matcher-start').select2();

                    // Append total row
                    var totalRow = '<tfoot><tr>' +
                        '<td colspan="8" class="text-right font-weight-bold">Total<input type="hidden" name="Additional" value="1"></td>' +
                        '<td><input readonly type="text" name="Total" class="form-control form-control-sm" value=""></td>' +
                        '</tr></tfoot>';
                    $('#Tabledata').append(totalRow);

                            var index = 0;
                        $(document).on('click', '.productappend', function () {
                        index++;
                        var currentIndex = index;
                        var newRow = '<tr>' +
                            '<td>' + createMaterialSelect(currentIndex) + '</td>' +
                            '<td><input readonly type="text" name="HSN_Code_Second[]" id="HSN_Code_Second_' + currentIndex + '" class="form-control form-control-sm" value=""></td>' +
                            '<td><input readonly type="text" name="UOM_Second[]" id="UOM_Second_' + currentIndex + '" class="form-control form-control-sm" value=""></td>' +
                            '<td><input type="text" id="Total_QTY_' + currentIndex + '" name="Total_QTY[]" class="form-control form-control-sm" value=""></td>' +
                            '<td><input type="text" name="Rate[]" id="Rate_' + currentIndex + '" class="form-control form-control-sm" value=""></td>' +
                            '<td><input readonly type="text" name="Amount[]" id="Amount_' + currentIndex + '" class="form-control form-control-sm" value=""></td>' +
                            '<td><input type="text" required name="GST_Per[]" id="GST_Per_' + currentIndex + '" class="form-control form-control-sm" value=""></td>' +
                            '<td><input readonly type="text" name="GST_Value[]" id="GST_Value_' + currentIndex + '" class="form-control form-control-sm" value=""></td>' +
                            '<td><input readonly type="text" name="Sub_Total[]" id="Sub_Total_' + currentIndex + '" class="form-control form-control-sm" value=""></td>' +
                            '<td><a href="javascript:;" class="btn btn-danger btn-sm delete-row" id="delete_' + currentIndex + '"><i class="fa fa-trash" aria-hidden="true"></i></a></td>' +
                            '</tr>';

                        $('#Tabledata tbody').append(newRow);
                        $('.js-example-matcher-start').select2();

                                    // Recalculate Total
                                    var total = calculatesubTotal();
                                    // Update Total row
                                    updateTotalRow(total);
                                });
                                    // Function to calculate total
                                    function calculatesubTotal() {
                                        var total = 0;
                                        $('#Tabledata tbody tr').each(function () {
                                            var rowTotal = parseFloat($(this).find('[name="Sub_Total[]"]').val());
                                            if (!isNaN(rowTotal)) {
                                                total += rowTotal;
                                            }
                                        });
                                        return total;
                                        updateTotalRow(total);
                                    }


                                    // Function to calculate Total
                                    function calculateTotal() {
                                        var total = 0;
                                        $('#Tabledata tbody tr').each(function () {
                                            var qty = parseFloat($(this).find('[name^="Total_QTY"]').val()) || 0;
                                            var rate = parseFloat($(this).find('[name^="Rate"]').val()) || 0;
                                            var amount = qty * rate;
                                            total += amount;
                                        });
                                        return total;
                                    }

                                    // Function to calculate GST Value and Subtotal
                                    function calculateGSTAndSubtotal() {
                                        var total = 0; // Define total variable
                                        $('#Tabledata tbody tr').each(function () {
                                            var qty = parseFloat($(this).find('[name^="Total_QTY"]').val()) || 0;
                                            var rate = parseFloat($(this).find('[name^="Rate"]').val()) || 0;
                                            var gstPer = parseFloat($(this).find('[name^="GST_Per"]').val()) || 0;

                                            var amount = qty * rate;
                                            var gstValue = (amount * gstPer) / 100;
                                            var subTotal = amount + gstValue;

                                            $(this).find('[name^="Amount"]').val(amount.toFixed(2));
                                            $(this).find('[name^="GST_Value"]').val(gstValue.toFixed(2));
                                            $(this).find('[name^="Sub_Total"]').val(subTotal.toFixed(2));

                                            total += subTotal; // Update total
                                        });

                                        updateTotalRow(total); // Update total row outside the loop
                                    }

                                        $(document).ready(function () {
                                            // Add event handler for Total_QTY field
                                            $(document).on('keyup', '[name^="Total_QTY"]', function () {
                                                var total = calculateTotal();
                                                updateTotalRow(total);
                                                calculateGSTAndSubtotal();
                                            });

                                            // Add event handler for GST_Per field
                                            $(document).on('keyup', '[name^="GST_Per"]', function () {
                                                calculateGSTAndSubtotal();
                                            });
                                            $(document).ready(function () {
                                                    $(document).on('keyup', '[name^="Total_QTY"], [name^="Rate"], [name^="GST_Per"]', function () {
                                                        calculateGSTAndSubtotal();
                                                    });
                                                });
                                        });
                                        // function calculation total end
                                        // Function to update Total row
                                        function updateTotalRow(total) {
                                            $('#Tabledata tfoot').remove(); // Remove existing total row
                                            var totalRow = '<tfoot><tr>' +
                                                '<td colspan="8" class="text-right font-weight-bold">Total</td>' +
                                                '<td><input type="hidden" name="Additional" value="1"><input readonly type="text" name="Total" class="form-control form-control-sm" value="' + total.toFixed(2) + '"></td>' +
                                                '</tr></tfoot>';
                                            $('#Tabledata').append(totalRow);
                                        }

                                        // Delete row
                                        $(document).on('click', '.delete-row', function () {
                                            $(this).closest('tr').remove();
                                            // Recalculate Total
                                            var total = calculatesubTotal();
                                            // Update Total row
                                            updateTotalRow(total);
                                        });

                }

            }
        });
    });
</script>
<script>


function Assetunit(i) {
    var id = "#Material_" + i;
    var AssetId = $(id).val();
        $.ajax({
                url: "{{url('RawMaterial/MaterialData')}}" + '/' + AssetId,
                type: 'GET',
                data: {
                    AssetId: AssetId
                },
                success: function(response) {
                    $.each(response, function(index, calculation) {
                        $('#HSN_Code_Second_' + i).val(calculation.HSN_Code);
                        $('#UOM_Second_' + i).val(calculation.UOM);
                        $('#Rate_' + i).val(calculation.last_purchase_price);
                    });
                }
        });

}

</script>
<script>
    function functioncal(key){
        //alert(key);
        var MaterialId = $('#RawMaterial').val();
        var mat_id = $('#mat_id_' + key).val();
        var totqty = $('#totqty_' + key).val();
        //var totrate = $('#totrate_' + key).val();
        //var gstamt = $('#gstamt_' + key).val();

        $.ajax({
            url: "{{url('orderRequirement/MaterialCalculation')}}" + '/' + mat_id,
            type: 'GET',
            data: {
                totqty: totqty,
                mat_id: mat_id
            },
            success: function(response) {
                $.each(response, function(index, calculation) {
                    $('#amt_' + key).val(calculation.calculated_amount);
                    $('#gstamt_' + key).val(calculation.gst_amount);
                    $('#totamt_' + key).val(calculation.total_amount);
                });
            }
        });

    }


    function checkAll(e) {
    if (e.checked) {
        $('input:checkbox').prop("checked", true);
    } else {
        $('input:checkbox').prop("checked", false);
    }
        onClickcalc();
    }

    // function onClickcalc() {
    //     var total_net_amt = 0;
    //     var checkedVals = $('.custom-checkbox:checkbox:checked').map(function () {
    //         var netamtid = `#totamt_${this.value}`;
    //         var amt = parseFloat($(netamtid).val());
    //         if (!isNaN(amt)) {
    //             total_net_amt += amt;
    //         }
    //         return this.value;
    //     }).get();
    //     if (checkedVals.length == 0) {
    //         alert("Please select at least one checkbox.");
    //         $('#subtotamt').val("");
    //     } else {
    //         $('#subtotamt').val(total_net_amt.toFixed(2));
    //     }
    // }
        function onClickcalc() {
            var total_net_amt = 0;
            var isAnyQtyFilled = false;
            var checkedVals = $('.custom-checkbox:checkbox:checked').map(function () {
                var qtyId = `#totqty_${this.value}`;
                var qty = $(qtyId).val();
                if (qty !== '') {
                    isAnyQtyFilled = true;
                }
                var netamtid = `#totamt_${this.value}`;
                var amt = parseFloat($(netamtid).val());
                if (!isNaN(amt)) {
                    total_net_amt += amt;
                }
                return this.value;
            }).get();

            if (!isAnyQtyFilled) {
                alert("Please fill in at least one Quantity field.");
                $('#subtotamt').val("");
                return;
            }

            if (checkedVals.length == 0) {
                alert("Please select at least one checkbox.");
                $('#subtotamt').val("");
            } else {
                $('#subtotamt').val(total_net_amt.toFixed(2));
            }
        }



</script>
<script>
    $(document).ready(function() {
        $('#Manunit').change(function() {

            var ManunitId = $(this).val();

            if (ManunitId) {
                $.ajax({
                    url: "{{url('PPFinishedGood/get-plantnamedetails')}}" + '/' + ManunitId,
                    type: 'GET',
                    data: {
                        ManunitId : ManunitId
                      },
                    success: function(response) {
                        $('#plan_uni_id').empty();
                        $('#plan_uni_id').append('<option value="" selected disabled>Select</option>');
                        $.each(response, function(index, plantdetails) {
                            var option = $('<option>');
                            option.val(plantdetails.id);
                            option.text(plantdetails.spname);
                            $('#plan_uni_id').append(option);
                        });
                    }
                });
            }
        });
    });
    $(document).ready(function() {
    $('#plan_uni_id').change(function() {
        var ManunitId = $(this).val();
        var prjid = $('#Manunit').val();
        var subprjid = $('#plan_uni_id').val();

            if (ManunitId) {
                $.ajax({
                    url: "{{url('PPFinishedGood/get-budetails')}}" + '/' + ManunitId,
                    type: 'GET',
                    data: {
                        ManunitId: ManunitId,
                        prjid: prjid,
                        subprjid: subprjid
                    },
                    success: function(response) {
                        $('#bunameid').empty();
                        $('#bunameid').append('<option value="" selected disabled>Select</option>');

                        if (response.length === 0) {
                            alert('This business unit is blank against your project and sub-project ID');
                        } else {
                            $.each(response, function(index, plantdetails) {
                                var option = $('<option>');
                                option.val(plantdetails.id);
                                option.text(plantdetails.unit_name);
                                $('#bunameid').append(option);
                            });
                        }
                    }
                });
            }
        });
    });
    $(document).ready(function() {
        $('#org_id').change(function() {
                var orgId = $(this).val();
                if (orgId) {
                    $.ajax({
                        url: "{{ url('orderRequirement/get-address') }}" + '/' + orgId,
                        type: 'GET',
                        dataType: 'json', // Add this line to specify the expected data type
                        success: function(response) {
                            $('#billing_address').empty();
                            $('#billing_address').append('<option value="" selected disabled>Select</option>');
                            $.each(response.billing_address, function(index, address) {
                                var option = $('<option>');
                                option.val(address.id);
                                option.text(address.sname);
                                $('#billing_address').append(option);
                            });
                            $('#shipping_address').empty();
                            $('#shipping_address').append('<option value="" selected disabled>Select</option>');
                            $.each(response.shipping_address, function(index, address) {
                                var option = $('<option>');
                                option.val(address.id);
                                option.text(address.sname);
                                $('#shipping_address').append(option);
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText); // Log any errors for debugging
                        }
                    });
                }
            });
        });
        $(document).ready(function() {
        $('#billing_address').change(function() {
                var BillId = $(this).val();
                if (BillId) {
                    $.ajax({
                        url: "{{ url('orderRequirement/get-address-details_bill') }}" + '/' + BillId,
                        type: 'GET',
                        dataType: 'json', // Add this line to specify the expected data type
                        success: function(response) {
                            $.each(response, function(index, billing_address_details) {
                                $("#billing_details").val("District Name: " + billing_address_details.distname + "\n" +
                                    "Address: " + billing_address_details.addrs_1 + ", " + billing_address_details.addrs_2 + ", " + billing_address_details.addrs_3 + "\n" +
                                    "GST No: " + billing_address_details.gst_no);
                            });
                        }
                    });
                }
            });
        });
        $(document).ready(function() {
        $('#shipping_address').change(function() {
                var ShipId = $(this).val();
                if (ShipId) {
                    $.ajax({
                        url: "{{ url('orderRequirement/get-address-details_ship') }}" + '/' + ShipId,
                        type: 'GET',
                        dataType: 'json', // Add this line to specify the expected data type
                        success: function(response) {
                            $.each(response, function(index, shipping_address_details) {
                                $("#shiping_details").val("District Name: " + shipping_address_details.distname + "\n" +
                                    "Address: " + shipping_address_details.addrs_1 + ", " + shipping_address_details.addrs_2 + ", " + shipping_address_details.addrs_3 + "\n" +
                                    "GST No: " + shipping_address_details.gst_no);
                            });
                        }
                    });
                }
            });
        });
    $(document).ready(function() {
        $('#country').change(function() {
            var countryId = $(this).val();
            $('#state').empty().prop('disabled', true);
            $('#district').empty().prop('disabled', true);

            if (countryId) {
                $.ajax({
                    url: "{{url('FactoryCreater/get-states')}}" + '/' + countryId,
                    type: 'GET',
                    success: function(response) {
                        var options = '';
                        options += '<option value="" selected disabled>Select Option</option>';
                        $.each(response, function(index, state) {
                            options += '<option value="' + state.id + '">' + state.name + '</option>';
                        });
                        $('#state').html(options).prop('disabled', false);
                    }
                });
            }
        });

        $('#state').change(function() {
            var stateId = $(this).val();
            $('#district').empty().prop('disabled', true);

            if (stateId) {
                $.ajax({
                    url: "{{url('FactoryCreater/get-cities')}}" + '/' + stateId,
                    type: 'GET',
                    success: function(response) {
                        var options = '';
                        options += '<option value="" selected disabled>Select Option</option>';
                        $.each(response, function(index, city) {
                            options += '<option value="' + city.id + '">' + city.city + '</option>';
                        });
                        $('#district').html(options).prop('disabled', false);
                    }
                });
            }
        });
    });
</script>
<script>
    var NumberInput = document.getElementById('Number');
    var NumberError = document.getElementById('NumberError');

    NumberInput.addEventListener('input', function(event) {
        var input = event.target.value;
        var isValid = /^\d{10}$/.test(input);

        if (isValid) {
            NumberError.textContent = '';
        } else {
            NumberError.textContent = 'Number Should Be 10 Digits.';
        }
    });
</script>
<script>
            $(document).ready(function() {
            // Initially disable the BU Name dropdown if it's an edit case
            @if(isset($editStock->BU_Name))
                $('#bunameid').prop('disabled', true);
            @endif

            // Enable BU Name dropdown on Plant Name selection
            $('#plan_uni_id').on('change', function() {
                // Enable the BU Name dropdown
                $('#bunameid').prop('disabled', false);

                // Remove the hidden field if it exists
                $('#hidden_bunameid').remove();

                // Optionally, you can load the BU names based on the selected Plant Name
                // Here you might want to use an AJAX call to fetch the BU names based on the selected plant
                var plantId = $(this).val();

                if (plantId) {
                    $.ajax({
                        url: '/get-bu-names/' + plantId, // Update with your actual endpoint
                        type: 'GET',
                        success: function(response) {
                            $('#bunameid').empty().append('<option value="" selected disabled>Select</option>');
                            $.each(response, function(index, bu) {
                                $('#bunameid').append('<option value="' + bu.id + '">' + bu.unit_name + '</option>');
                            });
                        },
                        error: function(xhr) {
                            console.error('Error fetching BU names:', xhr);
                        }
                    });
                }
            });

            // Handle form submission to ensure hidden field is set if BU Name is disabled
            $('form').on('submit', function() {
                if ($('#bunameid').is(':disabled')) {
                    var selectedBU = $('#bunameid').val();
                    $('<input>').attr({
                        type: 'hidden',
                        id: 'hidden_bunameid',
                        name: 'BU_Name',
                        value: selectedBU
                    }).appendTo('form');
                }
            });
        });
    </script>
@endpush
