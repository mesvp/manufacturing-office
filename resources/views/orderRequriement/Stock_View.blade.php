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
/*
    div#adaaishhhh {
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
                                {{-- <h5>Work Order Details</h5> --}}
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="">Inputer Name : {{auth()->user()->name}}</label>
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
                        <form action="#" method="POST" class="stock_fields">
                            @csrf
                            <input class="form-control" type="hidden" name="edit" value="{{isset($editStock->id) && $editStock->id!=''?$editStock->id:''}}">

                                <div class="row" id="adaaishhhh">
                                    <h6 class="border-bottom">Procurement Request Details</h6>

                                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                        <label>
                                            Organization Name*
                                        </label>
                                        <select disabled name="Organization" class="form-select form-select-sm" required>
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
                                        <select disabled name="Unit_Name" class="form-select form-select-sm" required>
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
                                        <select disabled name="Plant_Name" class="form-select form-select-sm" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($plant_name as $val)
                                            <option value="{{$val->id}}" {{isset($editStock->Plant_Name) && $editStock->Plant_Name==$val->id?'selected':''}}>{{$val->spname}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                        <label>
                                            BU Name*
                                        </label>
                                        <select disabled name="BU_Name" class="form-select form-select-sm" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($BU as $val)
                                            <option value="{{$val->id}}" {{isset($editStock->BU_Name) && $editStock->BU_Name==$val->id?'selected':''}}>{{isset($val->unit_name) && $val->unit_name!=''?$val->unit_name:''}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                        <label>
                                            Godown Name*
                                        </label>
                                        <select disabled name="Factory_Godown_Name" class="form-select form-select-sm" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($Godown_Name as $val)
                                            <option value="{{$val->id}}" {{isset($editStock->Factory_Godown_Name) && $editStock->Factory_Godown_Name==$val->id?'selected':''}}>{{$val->inventory_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                        <label> Expected Delivery Date*</label>
                                        <div class="field-wrap">
                                            <input disabled class="form-control form-control-sm" type="date" name="Expected_Date" placeholder="Order Date" value="{{isset($editStock->Expected_Date) && $editStock->Expected_Date!=''?$editStock->Expected_Date:''}}" required>
                                        </div>
                                    </div>
                                    {{-- <div class="col-sm-3 form-group">
                                        <label> Company Name*</label>
                                        <div class="field-wrap">
                                            <select disabled name="Company_Name" class="form-select form-select-sm" required>
                                                <option value="" selected disabled>Select</option>
                                                @foreach($Company_Name as $val)
                                                <option value="{{$val->id}}" {{isset($editStock->Company_Name) && $editStock->Company_Name==$val->id?'selected':''}}>{{$val->Company_Name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}
                                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                        <label>Procurement Type</label>
                                            <select name="procurement_type_display" id="procurement_type_display" class="form-select form-select-sm js-example-matcher-start" required disabled>
                                                <option value="" {{ !isset($editStock->procurement_type) ? 'selected' : '' }}>Select</option>
                                                <option value="Normal" {{ (isset($editStock->procurement_type) && $editStock->procurement_type == 'Normal') ? 'selected' : '' }}>Normal</option>
                                                <option value="Loose" {{ (isset($editStock->procurement_type) && $editStock->procurement_type == 'Loose') ? 'selected' : '' }}>Loose</option>
                                                <option value="Additional" {{ (isset($editStock->procurement_type) && $editStock->procurement_type == 'Additional') ? 'selected' : '' }}>Additional</option>
                                            </select>
                                    </div>
                                    @if($editStock->procurement_type != "Additional")
                                    <div class="col-sm-3 form-group">
                                        <label>
                                            Finished Good(FG)*
                                            </lable>
                                            <select disabled name="Raw_Material" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" id="RawMaterial" required>
                                                <option value="" selected disabled>Select</option>
                                                @foreach($Raw_Material as $val)
                                                <option value="{{$val->RawMaterial->id}}" {{isset($editStock->Raw_Material) && $editStock->Raw_Material==$val->RawMaterial->id?'selected':''}}>{{$val->RawMaterial->matname}}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>HSN Code*</label>
                                        <div class="field-wrap">
                                            <input readonly class="form-control form-control-sm" type="number" name="HSN_Code" id="HSNCode" placeholder="HSN Code" value="{{isset($editStock->HSN_Code) && $editStock->HSN_Code!=''?$editStock->HSN_Code:''}}" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>UOM</label>
                                        <input readonly class="form-control form-control-sm" type="text" name="UOM" id="uom" placeholder="UOM" value="{{isset($editStock->UOM) && $editStock->UOM!=''?$editStock->UOM:''}}" required>
                                        {{-- <div class="field-wrap">
                                            <select disabled name="UOM" id="uom" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" required>
                                                <option value="" selected disabled>Select</option>
                                                @foreach($UOM as $val)
                                                <option value="{{$val->id}}" {{isset($editStock->UOM) && $editStock->UOM==$val->id?'selected':''}}>{{$val->UOMs}}</option>
                                                @endforeach
                                            </select>
                                        </div> --}}
                                    </div>
                                    @if($editStock->procurement_type != "Loose")
                                    <div class="col-sm-3 form-group">
                                        <label>QTY*</label>
                                        <div class="field-wrap">
                                            <input disabled class="form-control form-control-sm" type="number" name="QTY" id="QTY" placeholder="QTY" value="{{isset($editStock->QTY) && $editStock->QTY!=''?$editStock->QTY:''}}" required>
                                        </div>
                                    </div>
                                    @endif
                                    @endif
                                    <div class="col-sm-0 form-group">
                                    </div>
                                    @if($editStock->procurement_type == "Loose")
                                    <div class="col-sm-3 form-group">
                                    </div>
                                    @endif

                                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                                        <label>Billing Address*</label>
                                        <div class="field-wrap">
                                            <input disabled class="form-control form-control-sm" type="text" name="billing_address" id="billing_address" placeholder="billing address" value="{{isset($billing_address->billing_address) && $billing_address->billing_address!=''?$billing_address->billing_address:''}}" required>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                                        <label>Billing Details*</label>
                                        <div class="field-wrap">
                                            <textarea class="form-control form-control-sm" readonly name="billing_details" id="billing_details">{{isset($editStock->billing_details) && $editStock->billing_details!=''?$editStock->billing_details:''}}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                                        <label>Shipping Address*</label>
                                        <div class="field-wrap">
                                            <input disabled class="form-control form-control-sm" type="text" name="QTY" id="QTY" placeholder="QTY" value="{{isset($shipping_address->shipping_address) && $shipping_address->shipping_address!=''?$shipping_address->shipping_address:''}}" required>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                                        <label>Shipping Details*</label>
                                        <div class="field-wrap">
                                            <textarea class="form-control form-control-sm" readonly name="billing_details" id="billing_details">{{isset($editStock->shiping_details) && $editStock->shiping_details!=''?$editStock->shiping_details:''}}</textarea>
                                        </div>
                                    </div>


                                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                                        <label>Delivery Address*</label>
                                        <div class="field-wrap">
                                            <textarea class="form-control form-control-sm" readonly name="billing_details" id="billing_details">{{isset($editStock->delivery_address) && $editStock->delivery_address!=''?$editStock->delivery_address:''}}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                                        <label>Contact Person*</label>
                                        <div class="field-wrap">
                                            <input disabled class="form-control form-control-sm" type="text" name="QTY" id="QTY" placeholder="QTY" value="{{isset($editStock->contprsn) && $editStock->contprsn!=''?$editStock->contprsn:''}}" required>
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                                        <label>Phone*</label>
                                        <div class="field-wrap">
                                            <input disabled class="form-control form-control-sm" type="text" name="QTY" id="QTY" placeholder="QTY" value="{{isset($editStock->phone) && $editStock->phone!=''?$editStock->phone:''}}" required>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-xl-12 col-md-12 col-sm-12">
                                    <h6>Product Details</h6>
                                </div>
                                <div class="table-responsive">
                                    <table id="Tabledata" class="table table-striped table-bordered dataTable no-footer example" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th class="th-sm">SL No.</th>
                                                <th class="th-sm">Material</th>
                                                <th class="th-sm">HSN Code</th>
                                                <th class="th-sm">UOM</th>
                                                <th class="th-sm">QTY</th>
                                                <th class="th-sm">Rate </th>
                                                <th class="th-sm">Amount </th>
                                                @if($editStock->procurement_type == "Additional")
                                                <th class="th-sm">GST PER </th>
                                                @endif
                                                <th class="th-sm">GST </th>

                                                <th class="th-sm">Sub Total </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($product as $key => $val)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>
                                                    <input readonly type="text" name="" class="form-control form-control-sm" value="{{isset($val->material_name->matname) && $val->material_name->matname!=''?$val->material_name->matname:''}}">
                                                </td>
                                                <td>
                                                    <input readonly type="text" name="" class="form-control form-control-sm" value="{{isset($val->HSN_Code_Second) && $val->HSN_Code_Second!=''?$val->HSN_Code_Second:''}}">
                                                </td>
                                                <td>
                                                    <input readonly type="text" name="" class="form-control form-control-sm" value="{{isset($val->UOM_Second) && $val->UOM_Second!=''?$val->UOM_Second:''}}">
                                                </td>
                                                {{-- <td>
                                                    <select disabled name="" class="form-select form-select-sm">
                                                        <option value="" selected disabled>Select</option>
                                                        @foreach($UOM as $values)
                                                        <option value="{{$values->id}}" {{isset($val->UOM_Second) && $val->UOM_Second==$values->id?'selected':''}}>{{$values->UOMs}}</option>
                                                        @endforeach
                                                    </select>
                                                </td> --}}
                                                <td>
                                                    <input readonly type="text" name="" class="form-control form-control-sm" value="{{isset($val->Total_QTY) && $val->Total_QTY!=''?$val->Total_QTY:''}}">
                                                </td>
                                                <td>
                                                    <input readonly type="text" name="" class="form-control form-control-sm" value="{{isset($val->Rate) && $val->Rate!=''?$val->Rate:''}}">
                                                </td>
                                                <td>
                                                    <input readonly type="text" name="" class="form-control form-control-sm" value="{{isset($val->Amount) && $val->Amount!=''?$val->Amount:''}}">
                                                </td>
                                                @if($editStock->procurement_type == "Additional")
                                                <td>
                                                    <input readonly type="text" name="" class="form-control form-control-sm" value="{{isset($val->GST_Per) && $val->GST_Per!=''?$val->GST_Per:''}}">
                                                </td>
                                                @endif
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

                            <div class="row">
                                <div class="col-sm-8 form-group"></div>
                                <div class="col-sm-4 form-group">
                                    <label for="State">Remarks:</label>
                                    <input disabled type="text" name="remarks" cols="30" rows="5" class="form-control form-control-sm" placeholder="Remarks" value="{{isset($editStock->remarks) && $editStock->remarks!=''?$editStock->remarks:''}}">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <hr>
                @if($editStock->Approve_status=='OBJECT' && $editStock->userID==auth()->user()->id)
                <form action="{{url('orderRequirement/Stock_approve')}}" method="POST">
                    @csrf
                    <input type="hidden" name="approveID" value="{{isset($editStock->id) && $editStock->id!=''?$editStock->id:''}}">
                    <div class="form-group" id="u_rama">
                        <textarea class="form-control" name="comment_text" id="" rows="5" placeholder="Reply" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    @if(isset($nextID) && !empty($nextID))
                    <a href="{{url('orderRequirement/ProductCategory_View/'.$nextID)}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                    @else
                    <a href="{{url('orderRequirement/ProductList')}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                    @endif
                </form>
                @else
                <form action="{{url('orderRequirement/Stock_approve')}}" method="POST">
                    @csrf
                    <input type="hidden" name="approveID" value="{{isset($editStock->id) && $editStock->id!=''?$editStock->id:''}}">
                    <input type="hidden" name="non_acting" value="1">
                    <div class="button_div">
                        <div class="selector">
                            <div class="selecotr-item">
                                <input type="radio" id="radio6" name="pre_post_approval" class="selector-item_radio" value="AUDIT" required>
                                <label for="radio6" class="selector-item_label">AUDIT</label>
                            </div>
                            <div class="selecotr-item">
                                <input type="radio" id="radio8" name="pre_post_approval" class="selector-item_radio" value="INTIMATION" required>
                                <label for="radio8" class="selector-item_label">INTIMATION</label>
                            </div>
                            <div class="selecotr-item">
                                <input type="radio" id="radio9" name="pre_post_approval" class="selector-item_radio" value="QUERY" required>
                                <label for="radio9" class="selector-item_label">QUERY</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="u_rama">
                        <textarea class="form-control mt-0" name="comment_text" id="" rows="5" placeholder="Remarks" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    @if(isset($nextID) && !empty($nextID))
                    <a href="{{url('orderRequirement/Stock_View/'.$nextID)}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                    @else
                    <a href="{{url('orderRequirement/orderRequirementList')}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                    @endif
                </form>
                @endif
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
                        <tbody>
                            @foreach($approves as $key=>$val)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>
                                    @if(!empty($val->action))
                                    {{isset($val->action) && $val->action!=''?$val->action:''}}
                                    @else
                                    {{isset($val->pre_post_approval) && $val->pre_post_approval!=''?$val->pre_post_approval:''}}
                                    @endif
                                </td>
                                <td>{{isset($val->user->fullname) && $val->user->fullname!=''?$val->user->fullname:''}}</td>
                                <td>{{isset($val->role) && $val->role!=''?$val->role:''}}</td>
                                <td>{{isset($val->created_at) && $val->created_at!=''?date('d-m-Y H:i:s A',strtotime($val->created_at)):''}}</td>
                                <td>{{isset($val->comment_text) && $val->comment_text!=''?$val->comment_text:''}}</td>
                                <td>{{isset($val->ip_address) && $val->ip_address!=''?$val->ip_address:''}}</td>
                                <td>{{isset($val->device_name) && $val->device_name!=''?$val->device_name:''}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
@endpush
