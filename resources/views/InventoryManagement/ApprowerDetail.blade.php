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


    .button_div {
        margin-top: 40px;
        width: 100% !important;
    }

    .button_div a {
        color: #232356;
        font-weight: 500;
        border: 1px solid #41719C;
        padding: 5px 10px;
        text-decoration: none;
        border-radius: 5px;
        text-transform: capitalize;
    }

    div#home {
        width: 100% !important;
    }

    div#u_rama {
        margin-right: 30px;
        text-transform: capitalize !important;
    }

    div#u_rama textarea.form-control {
        border: 1px solid #41719C;
        height: 80px !important;
    }

    .betachao {
        padding: 10px 20px;
        background: #92D050;
        color: black;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: capitalize;
        border-radius: 5px;
        border: 1px solid #41719C;
    }

    div#profile {
        width: 100% !important;
    }

    div#contact {
        width: 100% !important;
    }

    .raja_table {
        margin-top: 30px;
    }

    .raja_table {
        border: 1px solid #41719C;
        padding: 30px;
        border-radius: 30px;
        margin-right: 30px;
    }

    .raja_table tr {
        border: none !important;
    }

    .raja_table tr th {
        border: none !important;
    }

    .raja_table th {
        color: #232356 !important;
        font-size: 16px;
        font-weight: 600 !important;
    }

    .raja_table th {
        text-align: center !important;
    }

    .dt-buttons {
        display: none;
    }

    div#example_filter {
        display: none;
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
                <a href="{{url('InventoryManagement/InventoryManagementApproveList')}}" class="btn btn-info"> <i class="fa fa-arrow-left"></i> BACK</a>
                <a href="{{url('InventoryManagement/InventoryManagementApproveList')}}" class="btn btn-info" style="margin-left:10px"> <i class="fa fa-home"></i> Home</a>
            </div>
            <div class="row">
                <div class="container">
                    <br>
                    <div class="tab1">
                        <form action="{{url('InventoryManagement/AddInventoryManagement')}}" method="POST">
                            @csrf
                            <input disabled class="form-control" type="hidden" name="edit" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                            @if(count($All_Data)>0)
                            @php
                            $i=1;
                            @endphp
                            @foreach($All_Data as $DataVal)
                            <div class="row" id="Remove{{$i}}">
                                <input disabled type="hidden" name="Data_Id[{{$i}}]" id="{{isset($DataVal->id) && $DataVal->id!=''?$DataVal->id:''}}">
                                <div class="tab1 col-sm-11">
                                    <div class="row" id="row">
                                        <div class="row" id="main_btn_uddhan">
                                            <div class="col-sm-3 form-group">
                                                <div class="field-wrap">
                                                    <label>
                                                        Organization*
                                                    </label>
                                                    <select disabled name="Organization[{{$i}}]" class="form-select form-select-sm" required>
                                                        <option value="" selected disabled>Select</option>
                                                        @foreach($Organization as $val)
                                                        <option value="{{$val->id}}" {{isset($DataVal->Organization) && $DataVal->Organization==$val->id?'selected':''}}>{{$val->organization}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <div class="field-wrap">
                                                    <label>
                                                        Manufacturing Unit*
                                                    </label>
                                                    <select disabled name="Manufacturing_Unit[{{$i}}]" class="form-select form-select-sm" required>
                                                        <option value="" selected disabled>Select</option>
                                                        @foreach($Manufacturing_Unit as $val)
                                                        <option value="{{$val->id}}" {{isset($DataVal->Manufacturing_Unit) && $DataVal->Manufacturing_Unit==$val->id?'selected':''}}>{{$val->Manufacturing_unit}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <div class="field-wrap">
                                                    <label>
                                                        Plant Name*
                                                    </label>
                                                    <select disabled name="Plant_Name[{{$i}}]" class="form-select form-select-sm" required>
                                                        <option value="" selected disabled>Select</option>
                                                        @foreach($Plant_Name as $val)
                                                        <option value="{{$val->id}}" {{isset($DataVal->Plant_Name) && $DataVal->Plant_Name==$val->id?'selected':''}}>{{$val->plant_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <div class="field-wrap">
                                                    <label>
                                                        Category*
                                                    </label>
                                                    <select disabled name="Category[{{$i}}]" class="form-select form-select-sm" required>
                                                        <option value="" selected disabled>Select</option>
                                                        <option value="Test" {{isset($DataVal->Category) && $DataVal->Category=='Test'?'selected':''}}>Test</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        @if(isset($DataVal->productss) && count($DataVal->productss)>0)
                                        @php
                                        $j=1;
                                        @endphp
                                        @foreach($DataVal->productss as $productVal)
                                        <div class="row" id="Product{{$i}}{{$j}}">
                                            <input disabled type="hidden" name="Product_Id[{{$i}}][{{$j}}]" value="{{isset($productVal->id) && $productVal->id!=''?$productVal->id:''}}">
                                            <div class="tab1 col-sm-11">
                                                <div class="row">
                                                    <div class="col-sm-3 form-group">
                                                        <label>
                                                            Product*
                                                        </label>
                                                        <select disabled name="Product[{{$i}}][{{$j}}]" class="form-select form-select-sm" required>
                                                            <option value="" selected disabled>Select</option>
                                                            @foreach($Product as $val)
                                                            <option value="{{$val->id}}" {{isset($productVal->Product) && $productVal->Product==$val->id?'selected':''}}>{{$val->product}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>
                                                            Sub Product*
                                                        </label>
                                                        <select disabled name="Sub_Product[{{$i}}][{{$j}}]" class="form-select form-select-sm" required>
                                                            <option value="" selected disabled>Select</option>
                                                            @foreach($Sub_Product as $val)
                                                            <option value="{{$val->id}}" {{isset($productVal->Sub_Product) && $productVal->Sub_Product==$val->id?'selected':''}}>{{$val->sub_product}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>
                                                            Sub Sub Product*
                                                        </label>
                                                        <select disabled name="Sub_Sub_Product[{{$i}}][{{$j}}]" class="form-select form-select-sm" required>
                                                            <option value="" selected disabled>Select</option>
                                                            @foreach($Sub_Sub_Product as $val)
                                                            <option value="{{$val->id}}" {{isset($productVal->Sub_Sub_Product) && $productVal->Sub_Sub_Product==$val->id?'selected':''}}>{{$val->sub_sub_product}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>
                                                            Date*
                                                        </label>
                                                        <div class="field-wrap">
                                                            <input disabled class="form-control form-control-sm" type="date" name="Date[{{$i}}][{{$j}}]" placeholder="Date" value="{{isset($productVal->Date) && $productVal->Date!=''?$productVal->Date:''}}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>UOM*</label>
                                                        <select disabled name="UOM[{{$i}}][{{$j}}]" class="form-select form-select-sm" required>
                                                            <option value="" selected disabled>Select</option>
                                                            @foreach($UOM as $val)
                                                            <option value="{{$val->id}}" {{isset($productVal->UOM) && $productVal->UOM==$val->id?'selected':''}}>{{$val->UOMs}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>
                                                            QTY*
                                                        </label>
                                                        <div class="field-wrap">
                                                            <input disabled class="form-control form-control-sm" type="text" name="QTY[{{$i}}][{{$j}}]" placeholder="QTY" value="{{isset($productVal->QTY) && $productVal->QTY!=''?$productVal->QTY:''}}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>
                                                            Batch No.*
                                                        </label>
                                                        <div class="field-wrap">
                                                            <input disabled class="form-control form-control-sm" type="text" name="Batch_No[{{$i}}][{{$j}}]" placeholder="Batch No" value="{{isset($productVal->Batch_No) && $productVal->Batch_No!=''?$productVal->Batch_No:''}}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>
                                                            QC Checking Done Or Not*
                                                        </label>
                                                        <select disabled name="QC_Checking[{{$i}}][{{$j}}]" class="form-select form-select-sm" required>
                                                            <option value="" selected disabled>Select</option>
                                                            @foreach($Quality_Check as $val)
                                                            <option value="{{$val->id}}" {{isset($productVal->QC_Checking) && $productVal->QC_Checking==$val->id?'selected':''}}>{{$val->quality_check}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>
                                                            SL. No. Start*
                                                        </label>
                                                        <div class="field-wrap">
                                                            <input disabled class="form-control form-control-sm" type="text" name="SL_No_Start[{{$i}}][{{$j}}]" placeholder=" SL. No. Start" value="{{isset($productVal->SL_No_Start) && $productVal->SL_No_Start!=''?$productVal->SL_No_Start:''}}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>
                                                            SL. No. End*
                                                        </label>
                                                        <div class="field-wrap">
                                                            <input disabled class="form-control form-control-sm" type="text" name="SL_No_End[{{$i}}][{{$j}}]" placeholder="SL. NO. End" value="{{isset($productVal->SL_No_End) && $productVal->SL_No_End!=''?$productVal->SL_No_End:''}}" required>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    @if(count($productVal->Materialss)>0)
                                                    @php
                                                    $k=1;
                                                    @endphp
                                                    @foreach($productVal->Materialss as $MaterialVal)
                                                    <div class="row" id="Material{{$i}}{{$j}}{{$k}}">
                                                        <input disabled type="hidden" name="Material_Id[{{$i}}][{{$j}}][{{$k}}]" value="{{isset($MaterialVal->id) && $MaterialVal->id!=''?$MaterialVal->id:''}}">
                                                        <div class="col-sm-3 form-group">
                                                            <label>
                                                                Material*
                                                            </label>
                                                            <select disabled name="Material[{{$i}}][{{$j}}][{{$k}}]" class="form-select form-select-sm" required>
                                                                <option value="" selected disabled>Select</option>
                                                                @foreach($Raw_Material as $val)
                                                                <option value="{{$val->id}}" {{isset($MaterialVal->Material) && $MaterialVal->Material==$val->id?'selected':''}}>{{$val->Raw_Material}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-3 form-group">
                                                            <label>
                                                                Rack No.*
                                                            </label>
                                                            <select disabled name="Rack_No[{{$i}}][{{$j}}][{{$k}}]" class="form-select form-select-sm" required>
                                                                <option value="" selected disabled>Select</option>
                                                                @foreach($Rack_No as $val)
                                                                <option value="{{$val->id}}" {{isset($MaterialVal->Rack_No) && $MaterialVal->Rack_No==$val->id?'selected':''}}>{{$val->Rack_No}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-3 form-group">
                                                            <label>
                                                                Sub Rack No*
                                                            </label>
                                                            <select disabled name="Sub_Rack_No[{{$i}}][{{$j}}][{{$k}}]" class="form-select form-select-sm" required>
                                                                <option value="" selected disabled>Select</option>
                                                                @foreach($Sub_Rack_No as $val)
                                                                <option value="{{$val->id}}" {{isset($MaterialVal->Sub_Rack_No) && $MaterialVal->Sub_Rack_No==$val->id?'selected':''}}>{{$val->Sub_Rack_No}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-3 form-group">
                                                            <label>
                                                                Bin No*
                                                            </label>
                                                            <select disabled name="Bin_No[{{$i}}][{{$j}}][{{$k}}]" class="form-select form-select-sm" required>
                                                                <option value="" selected disabled>Select</option>
                                                                @foreach($Bin_No as $val)
                                                                <option value="{{$val->id}}" {{isset($MaterialVal->Bin_No) && $MaterialVal->Bin_No==$val->id?'selected':''}}>{{$val->Bin_No}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-3 form-group">
                                                            <label>
                                                                Sub Bin No*
                                                            </label>
                                                            <select disabled name="Sub_Bin_No[{{$i}}][{{$j}}][{{$k}}]" class="form-select form-select-sm" required>
                                                                <option value="" selected disabled>Select</option>
                                                                @foreach($Sub_Bin_No as $val)
                                                                <option value="{{$val->id}}" {{isset($MaterialVal->Sub_Bin_No) && $MaterialVal->Sub_Bin_No==$val->id?'selected':''}}>{{$val->Sub_Bin_No}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>                                                      
                                                    </div>
                                                    <br>
                                                    @php
                                                    $k++;
                                                    @endphp
                                                    @endforeach
                                                    @endif
                                                    <hr>
                                                    @if(count($productVal->Godown)>0)
                                                    @php
                                                    $l=1;
                                                    @endphp
                                                    @foreach($productVal->Godown as $GodownVal)
                                                    <div class="row" id="GodownAdd{{$i}}{{$j}}{{$l}}">
                                                        <input disabled type="hidden" name="Godown_Id[{{$i}}][{{$j}}][{{$l}}]" value="{{isset($GodownVal->id) && $GodownVal->id!=''?$GodownVal->id:''}}">
                                                        <div class="col-sm-3 form-group">
                                                            <label>
                                                                Transfered To Godown Or Not*
                                                            </label>
                                                            <select disabled name="Transfered_To_Godown[{{$i}}][{{$j}}][{{$l}}]" class="form-select form-select-sm" required="">
                                                                <option value="" selected disabled="">Select</option>
                                                                <option value="Test" {{isset($GodownVal->Transfered_To_Godown) && $GodownVal->Transfered_To_Godown=='Test'?'selected':''}}>Test</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-3 form-group">
                                                            <label>
                                                                If yes Godown Name*
                                                            </label>
                                                            <select disabled name="Godown_Name[{{$i}}][{{$j}}][{{$l}}]" class="form-select form-select-sm" required="">
                                                                <option value="" selected disabled="">Select</option>
                                                                <option value="Test" {{isset($GodownVal->Godown_Name) && $GodownVal->Godown_Name=='Test'?'selected':''}}>Test</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-3 form-group">
                                                            <label>Shelf No.*</label>
                                                            <select disabled name="Shelf_No[{{$i}}][{{$j}}][{{$l}}]" class="form-select form-select-sm" required="">
                                                                <option value="" selected disabled="">Select</option>
                                                                <option value="Test" {{isset($GodownVal->Shelf_No) && $GodownVal->Shelf_No=='Test'?'selected':''}}>Test</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-3 form-group">
                                                            <label>
                                                                Sub Shelf No.*
                                                            </label>
                                                            <select disabled name="Sub_Shelf_No[{{$i}}][{{$j}}][{{$l}}]" class="form-select form-select-sm" required="">
                                                                <option value="" selected disabled="">Select</option>
                                                                <option value="Test" {{isset($GodownVal->Sub_Shelf_No) && $GodownVal->Sub_Shelf_No=='Test'?'selected':''}}>Test</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-3 form-group">
                                                            <label>
                                                                Shelf OB*
                                                            </label>
                                                            <div class="field-wrap">
                                                                <input disabled class="form-control form-control-sm" type="text" name="Shelf_OB[{{$i}}][{{$j}}][{{$l}}]" placeholder="Shelf OB" value="{{isset($GodownVal->Shelf_OB) && $GodownVal->Shelf_OB!=''?$GodownVal->Shelf_OB:''}}" required="">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3 form-group">
                                                            <label>
                                                                Shelf CB*
                                                            </label>
                                                            <div class="field-wrap">
                                                                <input disabled class="form-control form-control-sm" type="text" name="Shelf_CB[{{$i}}][{{$j}}][{{$l}}]" placeholder="Shelf CB" value="{{isset($GodownVal->Shelf_CB) && $GodownVal->Shelf_CB!=''?$GodownVal->Shelf_CB:''}}" required="">
                                                            </div>
                                                        </div>                                                      
                                                    </div>
                                                    <br>
                                                    @php
                                                    $l++;
                                                    @endphp
                                                    @endforeach
                                                    @endif                                                
                                                </div>
                                            </div>                                          
                                        </div>
                                        <br>
                                        @php
                                        $j++;
                                        @endphp
                                        @endforeach                                        
                                        @endif                                        
                                    </div>
                                </div>                            
                            </div>
                            <br>
                            @php
                            $i++;
                            @endphp
                            @endforeach                           
                            @endif
                            <br>
                            <div class="row">
                                <div class="col-sm-8 form-group"></div>
                                <div class="col-sm-4 form-group">
                                    <label for="State">Remarks:</label>
                                    <input disabled type="text" name="remarks" cols="30" rows="5" class="form-control form-control-sm" placeholder="Remarks" value="{{isset($edit->remarks) && $edit->remarks!=''?$edit->remarks:''}}">
                                </div>
                            </div>
                            <div style="overflow:auto;">
                                <div class="somras">
                                    <button type="submit" class="btn btn1 float-right" style="margin: 5px;">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <hr>
                    <form action="{{url('InventoryManagement/approve')}}" method="POST">
                        @csrf
                        <input type="hidden" name="approveID" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                        <div class="tab-content" id="myTabContent">
                            @if($edit->Approve_status=='' || $edit->Approve_status=='null')
                            <div class="button_div">
                                <div class="selector">
                                    <div class="selecotr-item">
                                        <input type="radio" id="radio1" name="during_approval" class="selector-item_radio" value="APPROVE" required {{isset($approvestatus->action) && $approvestatus->action=='APPROVE'?'checked':''}}>
                                        <label for="radio1" class="selector-item_label">APPROVE</label>
                                    </div>
                                    <div class="selecotr-item">
                                        <input type="radio" id="radio2" name="during_approval" class="selector-item_radio" value="REJECT" {{isset($approvestatus->action) && $approvestatus->action=='REJECT'?'checked':''}}>
                                        <label for="radio2" class="selector-item_label">REJECT</label>
                                    </div>
                                    <div class="selecotr-item">
                                        <input type="radio" id="radio3" name="during_approval" class="selector-item_radio" value="QUERY/RECHECK" {{isset($approvestatus->action) && $approvestatus->action=='QUERY/RECHECK'?'checked':''}}>
                                        <label for="radio3" class="selector-item_label">QUERY/RECHECK</label>
                                    </div>

                                    <div class="selecotr-item">
                                        <input type="radio" id="radio4" name="during_approval" class="selector-item_radio" value="HOLD" {{isset($approvestatus->action) && $approvestatus->action=='HOLD'?'checked':''}}>
                                        <label for="radio4" class="selector-item_label">HOLD</label>
                                    </div>
                                </div>
                                <div id="showfields" class="row" style="display: {{isset($approvestatus->action) && $approvestatus->action=='HOLD'?'flex':'none'}};">
                                    <div class="col-sm-4 form-group">
                                        <label>Hold</lable>
                                            <input type="text" style="border-radius: 12px;" name="hold" placeholder="Hold" class="form-control form-control-sm requireddd" value="{{isset($approvestatus->hold) && $approvestatus->hold!=''?$approvestatus->hold:''}}">
                                    </div>
                                    <div class="col-sm-4 form-group">
                                        <label>Reason For Hold</lable>
                                            <input type="text" style="border-radius: 12px;" name="reason_for_hold" placeholder="Reason For Hold" class="form-control form-control-sm requireddd" value="{{isset($approvestatus->reason_for_hold) && $approvestatus->reason_for_hold!=''?$approvestatus->reason_for_hold:''}}">
                                    </div>
                                    <div class="col-sm-4 form-group">
                                        <label>Days For Holding</lable>
                                            <input type="text" style="border-radius: 12px;" name="days_for_holding" placeholder="Days For Holding" class="form-control form-control-sm requireddd" value="{{isset($approvestatus->days_for_holding) && $approvestatus->days_for_holding!=''?$approvestatus->days_for_holding:''}}">
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="button_div">
                                <div class="selector">
                                    <div class="selecotr-item">
                                        <input type="radio" id="radio5" name="pre_post_approval" class="selector-item_radio" checked>
                                        <label for="radio5" class="selector-item_label">FORWARD</label>
                                    </div>
                                    <div class="selecotr-item">
                                        <input type="radio" id="radio6" name="pre_post_approval" class="selector-item_radio">
                                        <label for="radio6" class="selector-item_label">AUDIT</label>
                                    </div>
                                    <div class="selecotr-item">
                                        <input type="radio" id="radio7" name="pre_post_approval" class="selector-item_radio">
                                        <label for="radio7" class="selector-item_label">OBJECT</label>
                                    </div>
                                    <div class="selecotr-item">
                                        <input type="radio" id="radio8" name="pre_post_approval" class="selector-item_radio">
                                        <label for="radio8" class="selector-item_label">INTIMATION</label>
                                    </div>
                                    <div class="selecotr-item">
                                        <input type="radio" id="radio9" name="pre_post_approval" class="selector-item_radio">
                                        <label for="radio9" class="selector-item_label">QUERY</label>
                                    </div>
                                    <div class="selecotr-item">
                                        <input type="radio" id="radio10" name="pre_post_approval" class="selector-item_radio">
                                        <label for="radio10" class="selector-item_label">WITHDRAW</label>
                                    </div>
                                    <div class="selecotr-item">
                                        <input type="radio" id="radio11" name="pre_post_approval" class="selector-item_radio">
                                        <label for="radio11" class="selector-item_label">WARNING & ADVISORY</label>
                                    </div>
                                    <div class="selecotr-item">
                                        <input type="radio" id="radio12" name="pre_post_approval" class="selector-item_radio">
                                        <label for="radio12" class="selector-item_label">NEXT LANDING</label>
                                    </div>
                                </div>
                            </div>
                            <div class="button_div">
                                <div class="selector">
                                    <div class="selecotr-item">
                                        <input type="radio" id="radio120" name="post_approval" class="selector-item_radio" checked>
                                        <label for="radio120" class="selector-item_label">VIEW</label>
                                    </div>
                                    <div class="selecotr-item">
                                        <input type="radio" id="radio13" name="post_approval" class="selector-item_radio">
                                        <label for="radio13" class="selector-item_label">WITHDRAW</label>
                                    </div>
                                    <div class="selecotr-item">
                                        <input type="radio" id="radio14" name="post_approval" class="selector-item_radio">
                                        <label for="radio14" class="selector-item_label">ADVISORY</label>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="form-group" id="u_rama">
                            <textarea class="form-control" name="comment_text" id="" rows="3" placeholder="Remarks" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th class="th-sm">SL NO.</th>
                                <th class="th-sm">Status</th>
                                <th class="th-sm">Action</th>
                                <th class="th-sm">Action By</th>
                                <th class="th-sm">Role. (Reviewer,Approver,ETC)</th>
                                <th class="th-sm">Date & time</th>
                                <th class="th-sm">comment.Cat.</th>
                                <th class="th-sm">comment.Text.</th>
                                <th class="th-sm">Hold</th>
                                <th class="th-sm">Reason For Hold</th>
                                <th class="th-sm">Days For Holding</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($approves as $key=>$val)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td class="textt" style="color: {{isset($val->status) && $val->status=='1'?'Green':'Red'}}">{{isset($val->status) && $val->status=='1'?'Active':'Inactive'}}</td>
                                <td>{{isset($val->action) && $val->action!=''?$val->action:''}}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>{{isset($val->comment_text) && $val->comment_text!=''?$val->comment_text:''}}</td>
                                <td>{{isset($val->hold) && $val->hold!=''?$val->hold:''}}</td>
                                <td>{{isset($val->reason_for_hold) && $val->reason_for_hold!=''?$val->reason_for_hold:''}}</td>
                                <td>{{isset($val->days_for_holding) && $val->days_for_holding!=''?$val->days_for_holding:''}}</td>
                            </tr>
                            @endforeach
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
        activeclass(22, 1);
    });
</script>
@endpush