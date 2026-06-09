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

    div#laluKI {
        display: flex;
        align-items: flex-start;
        justify-content: flex-start;
        align-content: flex-start;
        margin-bottom: 20px;
    }

    div#jasbat {
        display: flex;
        align-items: center;
        justify-content: center;
        align-content: center;
    }

    a#addmainhhhhh {
        margin-left: 10px;
        margin-top: -20px;
    }


    table.gbaba {
        width: 500px;
        border: 1px solid #ddd;
        margin-right: 10px;
        padding: 10px;
    }

    table.gbaba th {
        border: 1px solid #ddd !important;
        padding: 10px;
    }

    table.gbaba td {
        border: 1px solid #ddd !important;
    }

    table.gbaba td input {
        height: 100% !important;
        margin: 0px !important;
        border-radius: 0px !important;
        width: 250px;
    }

    table.gbaba td select {

        width: 250px !important;
        margin: 0px !important;
        border-radius: 0px !important;
    }



    table.gbaba th {
        text-align: center;
    }

    .kshiugsdhiusdhginsdgn {
        display: flex;
        align-items: center;
        justify-content: center;
        align-content: center;
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
                <a href="{{url('ProductionProcess/ProductionProcess/'.(isset($edit->id) && $edit->id!=''?$edit->id:''))}}" class="btn btn-info"> <i class="fa fa-arrow-left"></i> BACK</a>
                <a href="{{url('ProductionProcess/ProductionProcessList')}}" class="btn btn-info" style="margin-left:10px"> <i class="fa fa-home"></i> Home</a>
            </div>
            <div class="row">
                <div class="container">
                    <div class="row">
                        <div class="col-4">
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col">
                                    <h5> Production Process</h5>
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
                        <form action="{{url('ProductionProcess/AddStages')}}" method="POST">
                            @csrf
                            <input class="form-control" type="hidden" name="edit" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                            <div class="row">
                                {{-- <div class="col-sm-3 form-group">
                                    <label>
                                        Product*
                                    </label>
                                    <div class="field-wrap">
                                        <select name="Product" class="form-select form-select-sm js-example-matcher-start" disabled required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($Product as $val)
                                            <option value="{{$val->id}}" {{isset($edit->Product) && $edit->Product==$val->id?'selected':''}}>{{$val->product}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Sub Product*</label>
                                    <div class="field-wrap">
                                        <select name="Sub_Product" class="form-select form-select-sm js-example-matcher-start" disabled required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($Sub_Product as $val)
                                            <option value="{{$val->id}}" {{isset($edit->Sub_Product) && $edit->Sub_Product==$val->id?'selected':''}}>{{$val->sub_product}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Sub Sub Product*</label>
                                    <div class="field-wrap">
                                        <select name="Sub_Sub_Product" class="form-select form-select-sm js-example-matcher-start" disabled required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($Sub_Sub_Product as $val)
                                            <option value="{{$val->id}}" {{isset($edit->Sub_Sub_Product) && $edit->Sub_Sub_Product==$val->id?'selected':''}}>{{$val->sub_sub_product}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}
                                <div class="col-sm-3 form-group">
                                    <label>
                                        Finished Good(FG)*
                                        </lable>
                                        <select disabled name="Raw_Material" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($Raw_Material as $val)
                                            <option value="{{$val->RawMaterial->id}}" {{isset($edit->Raw_Material) && $edit->Raw_Material==$val->RawMaterial->id?'selected':''}}>{{$val->RawMaterial->material_name}}</option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>HSN Code*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="number" name="HSN_Code" placeholder="HSN Code" value="{{isset($edit->HSN_Code) && $edit->HSN_Code!=''?$edit->HSN_Code:''}}" required>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>UOM</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text"  name="UOM" placeholder="HSN Code" value="{{isset($edit->UOM) && $edit->UOM!=''?$edit->UOM:''}}" required>
                                        {{-- <select disabled name="UOM" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($UOM as $val)
                                            <option value="{{$val->id}}" {{isset($edit->UOM) && $edit->UOM==$val->id?'selected':''}}>{{$val->UOMs}}</option>
                                            @endforeach
                                        </select> --}}
                                    </div>
                                </div>
                            </div>
                            @if(count($All_Data)>0)
                            @php
                            $i = 1;
                            @endphp
                            @foreach($All_Data as $DataVal)
                            <div class="row" id="Remove{{$i}}">
                                <input type="hidden" name="Stage_Id[{{$i}}]" value="{{isset($DataVal->id) && $DataVal->id!=''?$DataVal->id:''}}">
                                <div class="tab1 col-sm-11">
                                    <div class="row">
                                        <div class="col-sm-3 form-group">
                                            <label>
                                                Process Stage*
                                            </label>
                                            <div class="field-wrap">
                                                <input class="form-control form-control-sm" type="text" name="Process_Stage[{{$i}}]" placeholder="Process Stage" value="{{isset($DataVal->Process_Stage) && $DataVal->Process_Stage!=''?$DataVal->Process_Stage:''}}" required>
                                            </div>
                                        </div>
                                    </div>
                                    @if(isset($DataVal->Stage_data) && count($DataVal->Stage_data)>0)
                                    @php
                                    $j=1;
                                    @endphp
                                    @foreach($DataVal->Stage_data as $StageVal)
                                    <div class="row" id="StageData{{$i}}{{$j}}">
                                        <input type='hidden' name='StageData_Id[{{$i}}][{{$j}}]' value="{{isset($StageVal->id) && $StageVal->id!=''?$StageVal->id:''}}">
                                        <div class="tab1 col-sm-11">
                                            <div class="row">
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Process Name*
                                                    </label>
                                                    <div class="field-wrap">
                                                        <input class="form-control form-control-sm" type="text" name="Process_Name[{{$i}}][{{$j}}]" placeholder="Process Name" value="{{isset($StageVal->Process_Name) && $StageVal->Process_Name!=''?$StageVal->Process_Name:''}}" required>
                                                    </div>
                                                </div>
                                                {{-- <div class="col-sm-3 form-group">
                                                    <label>
                                                        Material Use*
                                                    </label>
                                                    <div class="field-wrap">
                                                        <input class="form-control form-control-sm" type="text" name="Material_Use[{{$i}}][{{$j}}]" placeholder="Material Use" value="{{isset($StageVal->Material_Use) && $StageVal->Material_Use!=''?$StageVal->Material_Use:''}}" required>
                                                    </div>
                                                </div> --}}
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Output*
                                                    </label>
                                                    <div class="field-wrap">
                                                        <input class="form-control form-control-sm" type="text" name="Output[{{$i}}][{{$j}}]" placeholder="Output" value="{{isset($StageVal->Output) && $StageVal->Output!=''?$StageVal->Output:''}}" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Description*
                                                    </label>
                                                    <div class="field-wrap">
                                                        <input class="form-control form-control-sm" type="text" name="Description_Second[{{$i}}][{{$j}}]" placeholder="Description" value="{{isset($StageVal->Description_Second) && $StageVal->Description_Second!=''?$StageVal->Description_Second:''}}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            @if(count($StageVal->Machine)>0)
                                            @php
                                            $k=1;
                                            @endphp
                                            @foreach($StageVal->Machine as $vall)
                                            <div class="row" id="Machine{{$i}}{{$j}}{{$k}}">
                                                <input type="hidden" name="Machine_Id[{{$i}}][{{$j}}][{{$k}}]" value="{{isset($vall->id) && $vall->id!=''?$vall->id:''}}">
                                                <div class="row col-sm-11">
                                                    <div class="col-sm-3 form-group">
                                                        <label>
                                                            Material Name*
                                                            </lable>
                                                            <select name="Material_Name[{{$i}}][{{$j}}][{{$k}}]" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" id="RawMaterial{{$i}}{{$j}}{{$k}}" onclick="RawMaterial({{$i}},{{$j}},{{$k}})" required>
                                                                <option value="" selected disabled>Select</option>
                                                                @foreach($Materials as $val)
                                                                <option value="{{$val->RawMaterial->id}}" {{isset($vall->Material_Name) && $vall->Material_Name==$val->RawMaterial->id?'selected':''}}>{{$val->RawMaterial->material_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <span class="error-message" style="color: red; display: none;"></span>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>HSN Code*</label>
                                                        <div class="field-wrap">
                                                            <input readonly class="form-control form-control-sm" type="number" id="HSNCode{{$i}}{{$j}}{{$k}}" name="HSN_Code[{{$i}}][{{$j}}][{{$k}}]" placeholder="HSN Code" value="{{isset($vall->HSN_Code) && $vall->HSN_Code!=''?$vall->HSN_Code:''}}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>UOM</label>
                                                        <div class="field-wrap">
                                                            <input readonly class="form-control form-control-sm" type="text" name="UOM[{{$i}}][{{$j}}][{{$k}}]" id="uom{{$i}}{{$j}}{{$k}}" placeholder="UOM" value="{{isset($vall->UOM) && $vall->UOM!=''?$vall->UOM:''}}" required>
                                                            {{-- <select disabled id="uom{{$i}}{{$j}}{{$k}}" name="UOM[{{$i}}][{{$j}}][{{$k}}]" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" required>
                                                                <option value="" selected disabled>Select</option>
                                                                @foreach($UOM as $val)
                                                                <option value="{{$val->id}}" {{isset($vall->UOM) && $vall->UOM==$val->id?'selected':''}}>{{$val->UOMs}}</option>
                                                                @endforeach
                                                            </select> --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>Material QTY*</label>
                                                        <div class="field-wrap">
                                                            <input class="form-control form-control-sm" type="number" name="Material_QTY[{{$i}}][{{$j}}][{{$k}}]" placeholder="Material QTY" value="{{isset($vall->Material_QTY) && $vall->Material_QTY!=''?$vall->Material_QTY:''}}" required>
                                                        </div>
                                                    </div>
                                                    {{-- <div class="col-sm-3 form-group">
                                                        <label>
                                                            Machine Name
                                                        </label>
                                                        <div class="field-wrap">
                                                            <select name="Machine_Name[{{$i}}][{{$j}}][{{$k}}]" class="form-select form-select-sm js-example-matcher-start">
                                                                <option value="" selected disabled>Select</option>
                                                                @foreach($Machine_Name as $val)
                                                                <option value="{{$val->id}}" {{isset($vall->Machine_Name) && $vall->Machine_Name==$val->id?'selected':''}}>{{$val->Machine_Name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>
                                                            Machine Code
                                                        </label>
                                                        <div class="field-wrap">
                                                            <select name="Machine_Code[{{$i}}][{{$j}}][{{$k}}]" class="form-select form-select-sm js-example-matcher-start">
                                                                <option value="" selected disabled>Select</option>
                                                                @foreach($Machine_Code as $val)
                                                                <option value="{{$val->id}}" {{isset($vall->Machine_Code) && $vall->Machine_Code==$val->id?'selected':''}}>{{$val->Machine_Code}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>
                                                            Machine Company
                                                        </label>
                                                        <div class="field-wrap">
                                                            <select name="Machine_Company[{{$i}}][{{$j}}][{{$k}}]" class="form-select form-select-sm js-example-matcher-start">
                                                                <option value="" selected disabled>Select</option>
                                                                @foreach($Machine_Company as $val)
                                                                <option value="{{$val->id}}" {{isset($vall->Machine_Company) && $vall->Machine_Company==$val->id?'selected':''}}>{{$val->Company_Name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>
                                                            Make & Model
                                                        </label>
                                                        <div class="field-wrap">
                                                            <select name="Make_Model[{{$i}}][{{$j}}][{{$k}}]" class="form-select form-select-sm js-example-matcher-start">
                                                                <option value="" selected disabled>Select</option>
                                                                @foreach($Make_Model as $val)
                                                                <option value="{{$val->id}}" {{isset($vall->Make_Model) && $vall->Make_Model==$val->id?'selected':''}}>{{$val->Make_Model}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>
                                                            Date of Purchase
                                                        </label>
                                                        <div class="field-wrap">
                                                            <input class="form-control form-control-sm" type="date" name="Date_of_Purchase[{{$i}}][{{$j}}][{{$k}}]" placeholder="Date of Purchase" value="{{isset($vall->Date_of_Purchase) && $vall->Date_of_Purchase!=''?$vall->Date_of_Purchase:''}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>
                                                            Preventive Maintenance
                                                        </label>
                                                        <div class="field-wrap">
                                                            <input class="form-control form-control-sm" type="text" name="Preventive_Maintenance[{{$i}}][{{$j}}][{{$k}}]" placeholder="preventive Maintenance" value="{{isset($vall->Preventive_Maintenance) && $vall->Preventive_Maintenance!=''?$vall->Preventive_Maintenance:''}}">
                                                        </div>
                                                    </div> --}}
                                                </div>
                                                @if($k==1)
                                                <div class="col-sm-1">
                                                    <a href="javascript:;" id="Machine00" onclick="Machine({{$i}},{{$j}},{{isset($Machines_count) && $Machines_count==0?1:$Machines_count+1}})" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                                </div>
                                                @else
                                                <div class="col-sm-1">
                                                    <a href="javascript:;" onclick="RemoveMachine({{$i}},{{$j}},{{$k}})" class="btn btn-danger btn-sm mt-4">X</a>
                                                </div>
                                                @endif
                                            </div>
                                            <br>
                                            @php
                                            $k++;
                                            @endphp
                                            @endforeach
                                            @else
                                            <div class="row">
                                                <div class="row col-sm-11">
                                                    <div class="col-sm-3 form-group">
                                                        <label>
                                                            Material Name*
                                                            </lable>
                                                            <select name="Material_Name[{{$i}}][{{$j}}][0]" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" id="RawMaterial{{$i}}{{$j}}0" onclick="RawMaterial({{$i}},{{$j}},0)" required>
                                                                <option value="" selected disabled>Select</option>
                                                                @foreach($Materials as $val)
                                                                <option value="{{$val->RawMaterial->id}}">{{$val->RawMaterial->material_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <span class="error-message" style="color: red; display: none;"></span>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>HSN Code*</label>
                                                        <div class="field-wrap">
                                                            <input readonly class="form-control form-control-sm" type="number" id="HSNCode{{$i}}{{$j}}0" name="HSN_Code[{{$i}}][{{$j}}][0]" placeholder="HSN Code" value="" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>UOM</label>
                                                        <div class="field-wrap">
                                                            <input readonly class="form-control form-control-sm" type="text" name="UOM[{{$i}}][{{$j}}][0]" id="uom{{$i}}{{$j}}0" placeholder="UOM" value="" required>
                                                            {{-- <select disabled id="uom{{$i}}{{$j}}0" name="UOM[{{$i}}][{{$j}}][0]" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" required>
                                                                <option value="" selected disabled>Select</option>
                                                                @foreach($UOM as $val)
                                                                <option value="{{$val->id}}">{{$val->UOMs}}</option>
                                                                @endforeach
                                                            </select> --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>Material QTY*</label>
                                                        <div class="field-wrap">
                                                            <input class="form-control form-control-sm" type="number" name="Material_QTY[{{$i}}][{{$j}}][0]" placeholder="Material QTY" value="" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>
                                                            Machine Name
                                                        </label>
                                                        <div class="field-wrap">
                                                            <select name="Machine_Name[{{$i}}][{{$j}}][0]" class="form-select form-select-sm js-example-matcher-start">
                                                                <option value="" selected disabled>Select</option>
                                                                @foreach($Machine_Name as $val)
                                                                <option value="{{$val->id}}" {{isset($StageVal->Machine_Name) && $StageVal->Machine_Name==$val->id?'selected':''}}>{{$val->Machine_Name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>
                                                            Machine Code
                                                        </label>
                                                        <div class="field-wrap">
                                                            <select name="Machine_Code[{{$i}}][{{$j}}][0]" class="form-select form-select-sm js-example-matcher-start">
                                                                <option value="" selected disabled>Select</option>
                                                                @foreach($Machine_Code as $val)
                                                                <option value="{{$val->id}}" {{isset($StageVal->Machine_Code) && $StageVal->Machine_Code==$val->id?'selected':''}}>{{$val->Machine_Code}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>
                                                            Machine Company
                                                        </label>
                                                        <div class="field-wrap">
                                                            <select name="Machine_Company[{{$i}}][{{$j}}][0]" class="form-select form-select-sm js-example-matcher-start">
                                                                <option value="" selected disabled>Select</option>
                                                                @foreach($Machine_Company as $val)
                                                                <option value="{{$val->id}}" {{isset($StageVal->Machine_Company) && $StageVal->Machine_Company==$val->id?'selected':''}}>{{$val->Company_Name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>
                                                            Make & Model
                                                        </label>
                                                        <div class="field-wrap">
                                                            <select name="Make_Model[{{$i}}][{{$j}}][0]" class="form-select form-select-sm js-example-matcher-start">
                                                                <option value="" selected disabled>Select</option>
                                                                @foreach($Make_Model as $val)
                                                                <option value="{{$val->id}}" {{isset($StageVal->Make_Model) && $StageVal->Make_Model==$val->id?'selected':''}}>{{$val->Make_Model}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>
                                                            Date of Purchase
                                                        </label>
                                                        <div class="field-wrap">
                                                            <input class="form-control form-control-sm" type="date" name="Date_of_Purchase[{{$i}}][{{$j}}][0]" placeholder="Date of Purchase" value="{{isset($StageVal->Date_of_Purchase) && $StageVal->Date_of_Purchase!=''?$StageVal->Date_of_Purchase:''}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>
                                                            Preventive Maintenance
                                                        </label>
                                                        <div class="field-wrap">
                                                            <input class="form-control form-control-sm" type="text" name="Preventive_Maintenance[{{$i}}][{{$j}}][0]" placeholder="preventive Maintenance" value="{{isset($StageVal->Preventive_Maintenance) && $StageVal->Preventive_Maintenance!=''?$StageVal->Preventive_Maintenance:''}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-1">
                                                    <a href="javascript:;" id="Machine{{$i}}{{$j}}" onclick="Machine({{$i}},{{$j}},1)" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                                </div>
                                            </div>
                                            @endif
                                            <div id="MachineFiels{{$i}}{{$j}}"></div>
                                        </div>
                                        @if($j==1)
                                        <div class="col-sm-1">
                                            <a href="javascript:;" id="StageData0" onclick="StageData({{$i}},{{isset($Stage_data_count) && $Stage_data_count==0?1:$Stage_data_count+1}})" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                        </div>
                                        @else
                                        <div class="col-sm-1">
                                            <a href="javascript:;" onclick="RemoveStageData({{$i}},{{$j}})" class="btn btn-danger btn-sm mt-4">X</a>
                                        </div>
                                        @endif
                                    </div>
                                    <br>
                                    @php
                                    $j++;
                                    @endphp
                                    @endforeach
                                    @else
                                    <div class="row">
                                        <div class="tab1 col-sm-11">
                                            <div class="row">
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Process Name*
                                                    </label>
                                                    <div class="field-wrap">
                                                        <input class="form-control form-control-sm" type="text" name="Process_Name[{{$i}}][0]" placeholder="Process Name" value="" required>
                                                    </div>
                                                </div>
                                                {{-- <div class="col-sm-3 form-group">
                                                    <label>
                                                        Material Use*
                                                    </label>
                                                    <div class="field-wrap">
                                                        <input class="form-control form-control-sm" type="text" name="Material_Use[{{$i}}][0]" placeholder="Material Use" value="" required>
                                                    </div>
                                                </div> --}}
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Output*
                                                    </label>
                                                    <div class="field-wrap">
                                                        <input class="form-control form-control-sm" type="text" name="Output[{{$i}}][0]" placeholder="Output" value="" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Description*
                                                    </label>
                                                    <div class="field-wrap">
                                                        <input class="form-control form-control-sm" type="text" name="Description_Second[{{$i}}][0]" placeholder="Description" value="" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="row col-sm-11">
                                                    <div class="col-sm-3 form-group">
                                                        <label>
                                                            Material Name*
                                                            </lable>
                                                            <select name="Material_Name[{{$i}}][0][0]" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" id="RawMaterial{{$i}}00" onclick="RawMaterial({{$i}},0,0)" required>
                                                                <option value="" selected disabled>Select</option>
                                                                @foreach($Materials as $val)
                                                                <option value="{{$val->RawMaterial->id}}">{{$val->RawMaterial->material_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <span class="error-message" style="color: red; display: none;"></span>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>HSN Code*</label>
                                                        <div class="field-wrap">
                                                            <input readonly class="form-control form-control-sm" type="number" id="HSNCode{{$i}}00" name="HSN_Code[{{$i}}][0][0]" placeholder="HSN Code" value="" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>UOM</label>
                                                        <div class="field-wrap">
                                                            <input readonly class="form-control form-control-sm" type="text" id="uom{{$i}}00" name="UOM[{{$i}}][0][0]" placeholder="UOM" value="" required>
                                                            {{-- <select disabled id="uom{{$i}}00" name="UOM[{{$i}}][0][0]" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" required>
                                                                <option value="" selected disabled>Select</option>
                                                                @foreach($UOM as $val)
                                                                <option value="{{$val->id}}">{{$val->UOMs}}</option>
                                                                @endforeach
                                                            </select> --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>Material QTY*</label>
                                                        <div class="field-wrap">
                                                            <input class="form-control form-control-sm" type="number" name="Material_QTY[{{$i}}][0][0]" placeholder="Material QTY" value="" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>
                                                            Machine Name
                                                        </label>
                                                        <div class="field-wrap">
                                                            <select name="Machine_Name[{{$i}}][0][0]" class="form-select form-select-sm js-example-matcher-start">
                                                                <option value="" selected disabled>Select</option>
                                                                @foreach($Machine_Name as $val)
                                                                <option value="{{$val->id}}">{{$val->Machine_Name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>
                                                            Machine Code
                                                        </label>
                                                        <div class="field-wrap">
                                                            <select name="Machine_Code[{{$i}}][0][0]" class="form-select form-select-sm js-example-matcher-start">
                                                                <option value="" selected disabled>Select</option>
                                                                @foreach($Machine_Code as $val)
                                                                <option value="{{$val->id}}">{{$val->Machine_Code}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>
                                                            Machine Company
                                                        </label>
                                                        <div class="field-wrap">
                                                            <select name="Machine_Company[{{$i}}][0][0]" class="form-select form-select-sm js-example-matcher-start">
                                                                <option value="" selected disabled>Select</option>
                                                                @foreach($Machine_Company as $val)
                                                                <option value="{{$val->id}}">{{$val->Company_Name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>
                                                            Make & Model
                                                        </label>
                                                        <div class="field-wrap">
                                                            <select name="Make_Model[{{$i}}][0][0]" class="form-select form-select-sm js-example-matcher-start">
                                                                <option value="" selected disabled>Select</option>
                                                                @foreach($Make_Model as $val)
                                                                <option value="{{$val->id}}">{{$val->Make_Model}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>
                                                            Date of Purchase
                                                        </label>
                                                        <div class="field-wrap">
                                                            <input class="form-control form-control-sm" type="date" name="Date_of_Purchase[{{$i}}][0][0]" placeholder="Date of Purchase" value="">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>
                                                            Preventive Maintenance
                                                        </label>
                                                        <div class="field-wrap">
                                                            <input class="form-control form-control-sm" type="text" name="Preventive_Maintenance[{{$i}}][0][0]" placeholder="preventive Maintenance" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-1">
                                                    <a href="javascript:;" id="Machine00" onclick="Machine({{$i}},0,1)" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                                </div>
                                            </div>
                                            <div id="MachineFiels00"></div>
                                        </div>
                                        <div class="col-sm-1">
                                            <a href="javascript:;" id="StageData{{$i}}" onclick="StageData({{$i}},1)" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                        </div>
                                    </div>
                                    @endif
                                    <div id="StageDataFields{{$i}}"></div>
                                    <br>
                                    {{-- <div class="kshiugsdhiusdhginsdgn">
                                        <table class="gbaba">
                                            <tr>
                                                <th>Parameter</th>
                                                <th>UMO</th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input class="form-control form-control-sm" type="text" name="Parameter[{{$i}}]" value="{{isset($DataVal->Parameter) && $DataVal->Parameter!=''?$DataVal->Parameter:''}}">
                                                </td>
                                                <td>
                                                    <div class="field-wrap">
                                                        <select name="UOM_Second[{{$i}}]" class="form-select form-select-sm js-example-matcher-start">
                                                            <option value="" selected disabled>Select</option>
                                                            @foreach($UOM as $val)
                                                            <option value="{{$val->id}}" {{isset($DataVal->UOM_Second) && $DataVal->UOM_Second==$val->id?'selected':''}}>{{$val->UOMs}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div> --}}
                                </div>
                                @if($i==1)
                                {{-- <div class="col-sm-1">
                                    <a href="javascript:;" id="Stages" onclick="Stages({{isset($Stage_count) && $Stage_count==0?1:$Stage_count+1}})" class="btn btn-success btn-sm "><i class="fa fa-plus" aria-hidden="true"></i></a>
                                </div> --}}
                                @else
                                <div class="col-sm-1">
                                    <a href="javascript:;" onclick="Remove({{$i}})" class="btn btn-danger btn-sm mt-4">X</a>
                                </div>
                                @endif
                            </div>
                            <br>
                            @php
                            $i++;
                            @endphp
                            @endforeach
                            @else
                            <div class="row">
                                <div class="tab1 col-sm-11">
                                    <div class="row">
                                        <div class="col-sm-3 form-group">
                                            <label>
                                                Process Stage*
                                            </label>
                                            <div class="field-wrap">
                                                <input class="form-control form-control-sm" type="text" name="Process_Stage[0]" placeholder="Process Stage" value="" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="tab1 col-sm-11">
                                            <div class="row">
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Process Name*
                                                    </label>
                                                    <div class="field-wrap">
                                                        <input class="form-control form-control-sm" type="text" name="Process_Name[0][0]" placeholder="Process Name" value="" required>
                                                    </div>
                                                </div>
                                                {{-- <div class="col-sm-3 form-group">
                                                    <label>
                                                        Material Use*
                                                    </label>
                                                    <div class="field-wrap">
                                                        <input class="form-control form-control-sm" type="text" name="Material_Use[0][0]" placeholder="Material Use" value="" required>
                                                    </div>
                                                </div> --}}
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Output*
                                                    </label>
                                                    <div class="field-wrap">
                                                        <input class="form-control form-control-sm" type="text" name="Output[0][0]" placeholder="Output" value="" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Description*
                                                    </label>
                                                    <div class="field-wrap">
                                                        <input class="form-control form-control-sm" type="text" name="Description_Second[0][0]" placeholder="Description" value="" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="row col-sm-11">
                                                    <div class="col-sm-3 form-group">
                                                        <label>
                                                            Material Name*
                                                            </lable>
                                                            <select name="Material_Name[0][0][0]" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" id="RawMaterial000" onclick="RawMaterial(0,0,0)" required>
                                                                <option value="" selected disabled>Select</option>
                                                                @foreach($Materials as $val)
                                                                <option value="{{$val->RawMaterial->id}}">{{$val->RawMaterial->material_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <span class="error-message" style="color: red; display: none;"></span>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>HSN Code*</label>
                                                        <div class="field-wrap">
                                                            <input readonly class="form-control form-control-sm" type="number" id="HSNCode000" name="HSN_Code[0][0][0]" placeholder="HSN Code" value="" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>UOM</label>
                                                        <div class="field-wrap">
                                                            <input readonly class="form-control form-control-sm" type="text" id="uom000" name="UOM[0][0][0]" placeholder="UOM" value="" required>
                                                            {{-- <select disabled id="uom000" name="UOM[0][0][0]" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" required>
                                                                <option value="" selected disabled>Select</option>
                                                                @foreach($UOM as $val)
                                                                <option value="{{$val->id}}">{{$val->UOMs}}</option>
                                                                @endforeach
                                                            </select> --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>Material QTY*</label>
                                                        <div class="field-wrap">
                                                            <input class="form-control form-control-sm" type="number" name="Material_QTY[0][0][0]" placeholder="Material QTY" value="" required>
                                                        </div>
                                                    </div>
                                                    {{-- <div class="col-sm-3 form-group">
                                                        <label>
                                                            Machine Name
                                                        </label>
                                                        <div class="field-wrap">
                                                            <select name="Machine_Name[0][0][0]" class="form-select form-select-sm js-example-matcher-start">
                                                                <option value="" selected disabled>Select</option>
                                                                @foreach($Machine_Name as $val)
                                                                <option value="{{$val->id}}">{{$val->Machine_Name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>
                                                            Machine Code
                                                        </label>
                                                        <div class="field-wrap">
                                                            <select name="Machine_Code[0][0][0]" class="form-select form-select-sm js-example-matcher-start">
                                                                <option value="" selected disabled>Select</option>
                                                                @foreach($Machine_Code as $val)
                                                                <option value="{{$val->id}}">{{$val->Machine_Code}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>
                                                            Machine Company
                                                        </label>
                                                        <div class="field-wrap">
                                                            <select name="Machine_Company[0][0][0]" class="form-select form-select-sm js-example-matcher-start">
                                                                <option value="" selected disabled>Select</option>
                                                                @foreach($Machine_Company as $val)
                                                                <option value="{{$val->id}}">{{$val->Company_Name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>
                                                            Make & Model
                                                        </label>
                                                        <div class="field-wrap">
                                                            <select name="Make_Model[0][0][0]" class="form-select form-select-sm js-example-matcher-start">
                                                                <option value="" selected disabled>Select</option>
                                                                @foreach($Make_Model as $val)
                                                                <option value="{{$val->id}}">{{$val->Make_Model}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>
                                                            Date of Purchase
                                                        </label>
                                                        <div class="field-wrap">
                                                            <input class="form-control form-control-sm" type="date" name="Date_of_Purchase[0][0][0]" placeholder="Date of Purchase" value="">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 form-group">
                                                        <label>
                                                            Preventive Maintenance
                                                        </label>
                                                        <div class="field-wrap">
                                                            <input class="form-control form-control-sm" type="text" name="Preventive_Maintenance[0][0][0]" placeholder="preventive Maintenance" value="">
                                                        </div>
                                                    </div> --}}
                                                </div>
                                                <div class="col-sm-1">
                                                    <a href="javascript:;" id="Machine00" onclick="Machine(0,0,1)" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                                </div>
                                            </div>
                                            <div id="MachineFiels00"></div>
                                        </div>
                                        <div class="col-sm-1">
                                            <a href="javascript:;" id="StageData0" onclick="StageData(0,1)" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                        </div>
                                    </div>
                                    <div id="StageDataFields0"></div>
                                    <br>
                                    {{-- <div class="kshiugsdhiusdhginsdgn">
                                        <table class="gbaba">
                                            <tr>
                                                <th>Parameter</th>
                                                <th>UMO</th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input class="form-control form-control-sm" type="text" name="Parameter[0]" value="">
                                                </td>
                                                <td>
                                                    <div class="field-wrap">
                                                        <select name="UOM_Second[0]" class="form-select form-select-sm js-example-matcher-start">
                                                            <option value="" selected disabled>Select</option>
                                                            @foreach($UOM as $val)
                                                            <option value="{{$val->id}}">{{$val->UOMs}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div> --}}
                                </div>
                                {{-- <div class="col-sm-1">
                                    <a href="javascript:;" id="Stages" onclick="Stages(1)" class="btn btn-success btn-sm "><i class="fa fa-plus" aria-hidden="true"></i></a>
                                </div> --}}
                            </div>
                            @endif
                            <div id="Stage"></div>
                            <div class=" row">
                                <div class="col-sm-8 form-group"></div>
                                <div class="col-sm-4 form-group">
                                    <br>
                                    <br>
                                    <label for="State">Remarks:</label>
                                    <input type="text" name="remarks" cols="30" rows="5" class="form-control form-control-sm" placeholder="Remarks" value="{{isset($edit->remarks) && $edit->remarks!=''?$edit->remarks:''}}" id="logfgfau">
                                </div>
                            </div>
                            <div style="overflow:auto;">
                                <div class="somras">
                                    <button type="button" id="draft" class="btn btn1 float-right" style="margin: 5px;">Draft & Save</button>
                                    <a href="" class="btn btn1 float-right" style="margin: 5px; display:{{isset($edit->id) && $edit->id != ''?'none':'block'}}">Clear All</a>
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
    $(document).ready(function() {
        activeclass(26, 1);
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
    function Stages(i) {
        $('#Stage').append('<br><div class="row" id="Remove' + i + '"> <div class="tab1 col-sm-11"> <div class="row">  <div class="tab1 col-sm-11"> <div class="row"> <div class="col-sm-3 form-group"> <label> Process Name* </label> <div class="field-wrap"> <input class="form-control form-control-sm" type="text" name="Process_Name[' + i + '][0]" placeholder="Process Name" value="" required> </div></div><div class="col-sm-3 form-group"> <label> Output* </label> <div class="field-wrap"> <input class="form-control form-control-sm" type="text" name="Output[' + i + '][0]" placeholder="Output" value="" required> </div></div><div class="col-sm-3 form-group"> <label> Description* </label> <div class="field-wrap"> <input class="form-control form-control-sm" type="text" name="Description_Second[' + i + '][0]" placeholder="Description" value="" required> </div></div></div><hr> <div class="row"> <div class="row col-sm-11"> <div class="col-sm-3 form-group"><label>Material Name*<select name="Material_Name[' + i + '][0][0]" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" id="RawMaterial' + i + '00" onclick="RawMaterial(' + i + ',0,0)" required><option value="" selected disabled>Select</option>@foreach($Materials as $val)<option value="{{$val->RawMaterial->id}}" >{{$val->RawMaterial->material_name}}</option>@endforeach</select><span class="error-message" style="color:red;display:none"></span></div><div class="col-sm-3 form-group"><label>HSN Code*</label><div class="field-wrap"><input readonly="readonly" class="form-control form-control-sm" type="number" id="HSNCode' + i + '00" name="HSN_Code[' + i + '][0][0]" placeholder="HSN Code" value="" required></div></div><div class="col-sm-3 form-group"><label>UOM</label><div class="field-wrap"><input readonly class="form-control form-control-sm" type="text" id="uom' + i + '00" name="UOM[' + i + '][0][0]" placeholder="UOM" value="" required></div></div><div class="col-sm-3 form-group"><label>Material QTY*</label><div class="field-wrap"><input class="form-control form-control-sm" type="number" name="Material_QTY[' + i + '][0][0]" placeholder="Material QTY" value="" required></div></div><div class="col-sm-3 form-group"><label>Machine Name</label><div class="field-wrap"><select name="Machine_Name[' + i + '][0][0]" class="form-select form-select-sm js-example-matcher-start"><option value="" selected disabled>Select</option>@foreach($Machine_Name as $val)<option value="{{$val->id}}">{{$val->Machine_Name}}</option>@endforeach</select></div></div><div class="col-sm-3 form-group"><label>Machine Code</label><div class="field-wrap"><select name="Machine_Code[' + i + '][0][0]" class="form-select form-select-sm js-example-matcher-start" ><option value="" selected disabled>Select</option>@foreach($Machine_Code as $val)<option value="{{$val->id}}">{{$val->Machine_Code}}</option>@endforeach</select></div></div><div class="col-sm-3 form-group"><label>Machine Company</label><div class="field-wrap"><select name="Machine_Company[' + i + '][0][0]" class="form-select form-select-sm js-example-matcher-start" ><option value="" selected disabled>Select</option>@foreach($Machine_Company as $val)<option value="{{$val->id}}">{{$val->Company_Name}}</option>@endforeach</select></div></div><div class="col-sm-3 form-group"><label>Make & Model</label><div class="field-wrap"><select name="Make_Model[' + i + '][0][0]" class="form-select form-select-sm js-example-matcher-start" ><option value="" selected disabled>Select</option>@foreach($Make_Model as $val)<option value="{{$val->id}}">{{$val->Make_Model}}</option>@endforeach</select></div></div><div class="col-sm-3 form-group"> <label> Date of Purchase </label> <div class="field-wrap"> <input class="form-control form-control-sm" type="date" name="Date_of_Purchase[' + i + '][0][0]" placeholder="Date of Purchase" value="" > </div></div><div class="col-sm-3 form-group"> <label> Preventive Maintenance </label> <div class="field-wrap"> <input class="form-control form-control-sm" type="text" name="Preventive_Maintenance[' + i + '][0][0]" placeholder="preventive Maintenance" value="" > </div></div></div><div class="col-sm-1"> <a href="javascript:;" id="Machine' + i + '0" onclick="Machine(' + i + ',0,1)" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a> </div></div><div id="MachineFiels' + i + '0"></div></div><div class="col-sm-1"> <a href="javascript:;" id="StageData' + i + '" onclick="StageData(' + i + ',1)" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a> </div></div><div id="StageDataFields' + i + '"></div><br><div class="kshiugsdhiusdhginsdgn"> <table class="gbaba"> <tr> <th>Parameter</th> <th>UMO</th> </tr><tr> <td><input class="form-control form-control-sm" type="text" name="Parameter[' + i + ']" value=""></td><td><div class="field-wrap"><input readonly class="form-control form-control-sm" type="text" name="UOM_Second[' + i + ']" placeholder="UOM" value="" required></div></td></tr></table> </div></div><div class="col-sm-1"><a href="javascript:;" onclick="Remove(' + i + ')" class="btn btn-danger btn-sm mt-4">X</a> </div></div>');
        i++;
        $("#Stages").attr("onclick", 'Stages(' + i + ')');
        AppendSelect2();
    }

    function Remove(i) {
        $("#Remove" + i).remove();
    }
</script>
<script>
    function StageData(i, j) {
        $('#StageDataFields' + i).append('<br><div class="row" id="StageData' + i + j + '">  <div class="tab1 col-sm-11"> <div class="row"> <div class="col-sm-3 form-group"> <label> Process Name* </label> <div class="field-wrap"> <input class="form-control form-control-sm" type="text" name="Process_Name[' + i + '][' + j + ']" placeholder="Process Name" value="" required> </div></div><div class="col-sm-3 form-group"> <label> Output* </label> <div class="field-wrap"> <input class="form-control form-control-sm" type="text" name="Output[' + i + '][' + j + ']" placeholder="Output" value="" required> </div></div><div class="col-sm-3 form-group"> <label> Description* </label> <div class="field-wrap"> <input class="form-control form-control-sm" type="text" name="Description_Second[' + i + '][' + j + ']" placeholder="Description" value="" required> </div></div></div><hr> <div class="row"> <div class="row col-sm-11"> <div class="col-sm-3 form-group"><label>Material Name*<select name="Material_Name[' + i + '][' + j + '][0]" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" id="RawMaterial' + i + j + '0" onclick="RawMaterial(' + i + ',' + j + ',0)" required><option value="" selected disabled>Select</option>@foreach($Materials as $val)<option value="{{$val->RawMaterial->id}}" >{{$val->RawMaterial->material_name}}</option>@endforeach</select><span class="error-message" style="color:red;display:none"></span></div><div class="col-sm-3 form-group"><label>HSN Code*</label><div class="field-wrap"><input readonly="readonly" class="form-control form-control-sm" type="number" id="HSNCode' + i + j + '0" name="HSN_Code[' + i + '][' + j + '][0]" placeholder="HSN Code" value="" required></div></div><div class="col-sm-3 form-group"><label>UOM</label><div class="field-wrap"><input readonly class="form-control form-control-sm" type="text" id="uom' + i + j + '0" name="UOM[' + i + '][' + j + '][0]" placeholder="UOM" value="" required></div></div><div class="col-sm-3 form-group"><label>Material QTY*</label><div class="field-wrap"><input class="form-control form-control-sm" type="number" name="Material_QTY[' + i + '][' + j + '][0]" placeholder="Material QTY" value="" required></div></div></div><div class="col-sm-1"> <a href="javascript:;" id="Machine' + i + j + '" onclick="Machine(' + i + ',' + j + ',1)" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a> </div></div><div id="MachineFiels' + i + j + '"></div></div><div class="col-sm-1"><a href="javascript:;" onclick="RemoveStageData(' + i + ',' + j + ')" class="btn btn-danger btn-sm mt-4">X</a> </div></div>');
        j++;
        $("#StageData" + i).attr("onclick", 'StageData(' + i + ',' + j + ')');
        AppendSelect2();
    }

    function RemoveStageData(i, j) {
        $("#StageData" + i + j).remove();
    }
</script>
<script>
    function Machine(i, j, k) {
        $('#MachineFiels' + i + j).append('<br><div class="row" id="Machine' + i + j + k + '"><div class="row col-sm-11"> <div class="col-sm-3 form-group"><label>Material Name*<select name="Material_Name[' + i + '][' + j + '][' + k + ']" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" id="RawMaterial' + i + j + k + '" onclick="RawMaterial(' + i + ',' + j + ',' + k + ')" required><option value="" selected disabled>Select</option>@foreach($Materials as $val)<option value="{{$val->RawMaterial->id}}" >{{$val->RawMaterial->material_name}}</option>@endforeach</select><span class="error-message" style="color:red;display:none"></span></div><div class="col-sm-3 form-group"><label>HSN Code*</label><div class="field-wrap"><input readonly="readonly" class="form-control form-control-sm" type="number" id="HSNCode' + i + j + k + '" name="HSN_Code[' + i + '][' + j + '][' + k + ']" placeholder="HSN Code" value="" required></div></div><div class="col-sm-3 form-group"><label>UOM</label><div class="field-wrap"><input readonly class="form-control form-control-sm" type="text" id="uom' + i + j + k + '" name="UOM[' + i + '][' + j + '][' + k + ']" placeholder="UOM" value="" required></div></div><div class="col-sm-3 form-group"><label>Material QTY*</label><div class="field-wrap"><input class="form-control form-control-sm" type="number" name="Material_QTY[' + i + '][' + j + '][' + k + ']" placeholder="Material QTY" value="" required></div></div></div><div class="col-sm-1"> <a href="javascript:;" onclick="RemoveMachine(' + i + ',' + j + ',' + k + ')" class="btn btn-danger btn-sm mt-4">X</a> </div></div>');
        k++;
        $("#Machine" + i + j).attr("onclick", 'Machine(' + i + ',' + j + ',' + k + ')');
        AppendSelect2();
    }

    function RemoveMachine(i, j, k) {
        $("#Machine" + i + j + k).remove();
    }
</script>
<script>
    function RawMaterial(i, j, k) {
        $('#RawMaterial' + i + j + k).on('change', function() {
            var MaterialId = $(this).val();

            $.ajax({
                url: "{{url('RawMaterial/MaterialData')}}" + '/' + MaterialId,
                type: 'GET',
                data: {
                    MaterialId: MaterialId
                },
                success: function(data) {
                    $('#HSNCode' + i + j + k).val(data.data.HSN_Code);
                    $('#uom' + i + j + k).val(data.data.UOM).change();
                }
            });
        });
    }
</script>
<script>
    function CheckMaterialQuantity() {
        var MaterialData = @json($Materials);
        var Value = [];
        var MaterialQuantities = {};
        var Check = false;
        var uniqueMaterials = new Set();

        $('select[name^="Material_Name"]').each(function() {
            var Material = $(this).val();
            var Quantity = $(this).closest('.row').find('input[name^="Material_QTY"]').val();

            if (MaterialQuantities[Material]) {
                uniqueMaterials.add(Material);
                MaterialQuantities[Material] += parseFloat(Quantity);
            } else {
                MaterialQuantities[Material] = parseFloat(Quantity);
            }
            if (!uniqueMaterials.has(Material)) {
                Value.push(Material);
            }
        });

        var sortedValue = Value.sort(function(a, b) {
            return MaterialData.findIndex(item => item.Material == a) - MaterialData.findIndex(item => item.Material == b);
        });

        for (var Material in MaterialQuantities) {
            var totalQuantity = MaterialQuantities[Material];

            var backendTotalQty = MaterialData.find(function(item) {
                return item.Material == Material;
            }).Total_QTY;

            if (parseFloat(totalQuantity) != parseFloat(backendTotalQty)) {
                alert('Quantity Mismatch Please Recheck.');
                Check = true;
            }
        }

        for (var i = 0; i < MaterialData.length; i++) {
            var material = MaterialData[i].Material;
            if (material != Value[i]) {
                alert('Material `' + MaterialData[i].RawMaterial.Material_Name + '` is not used. Please use all materials before submitting.');
                Check = true;
            }
        }

        return Check;
    }
</script>

@endpush