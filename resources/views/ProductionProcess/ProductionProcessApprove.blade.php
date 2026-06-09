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
            <div class="addbtn extra">
                <a href="{{url('ProductionProcess/ProductionProcessApproveList')}}" class="btn btn-info"> <i class="fa fa-arrow-left"></i> BACK</a>
                <a href="{{url('ProductionProcess/ProductionProcessApproveList')}}" class="btn btn-info" style="margin-left:10px"> <i class="fa fa-home"></i> Home</a>
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
                        <div class="row">
                            {{-- <div class="col-sm-3 form-group">
                                <label>
                                    Product*
                                </label>
                                <div class="field-wrap">
                                    <select disabled name="Product" class="form-select form-select-sm" disabled required>
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
                                    <select disabled name="Sub_Product" class="form-select form-select-sm" disabled required>
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
                                    <select disabled name="Sub_Sub_Product" class="form-select form-select-sm" disabled required>
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
                                    <input disabled class="form-control form-control-sm" type="text" name="UOM" placeholder="UOM" value="{{isset($edit->UOM) && $edit->UOM!=''?$edit->UOM:''}}" required>
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
                            <input disabled type="hidden" name="Stage_Id[{{$i}}]" value="{{isset($DataVal->id) && $DataVal->id!=''?$DataVal->id:''}}">
                            <div class="tab1 col-sm-11">
                                <div class="row">
                                    <div class="col-sm-3 form-group">
                                        <label>
                                            Process Stage*
                                        </label>
                                        <div class="field-wrap">
                                            <input disabled class="form-control form-control-sm" type="text" name="Process_Stage[{{$i}}]" placeholder="Process Stage" value="{{isset($DataVal->Process_Stage) && $DataVal->Process_Stage!=''?$DataVal->Process_Stage:''}}" required>
                                        </div>
                                    </div>
                                </div>
                                @if(isset($DataVal->Stage_data) && count($DataVal->Stage_data)>0)
                                @php
                                $j=1;
                                @endphp
                                @foreach($DataVal->Stage_data as $StageVal)
                                <div class="row" id="StageData{{$i}}{{$j}}">
                                    <input disabled type='hidden' name='StageData_Id[{{$i}}][{{$j}}]' value="{{isset($StageVal->id) && $StageVal->id!=''?$StageVal->id:''}}">
                                    <div class="tab1 col-sm-11">
                                        <div class="row">
                                            <div class="col-sm-3 form-group">
                                                <label>
                                                    Process Name*
                                                </label>
                                                <div class="field-wrap">
                                                    <input disabled class="form-control form-control-sm" type="text" name="Process_Name[{{$i}}][{{$j}}]" placeholder="Process Name" value="{{isset($StageVal->Process_Name) && $StageVal->Process_Name!=''?$StageVal->Process_Name:''}}" required>
                                                </div>
                                            </div>
                                            <!--<div class="col-sm-3 form-group">-->
                                            <!--    <label>-->
                                            <!--        Material Use*-->
                                            <!--    </label>-->
                                            <!--    <div class="field-wrap">-->
                                            <!--        <input disabled class="form-control form-control-sm" type="text" name="Material_Use[{{$i}}][{{$j}}]" placeholder="Material Use" value="{{isset($StageVal->Material_Use) && $StageVal->Material_Use!=''?$StageVal->Material_Use:''}}" required>-->
                                            <!--    </div>-->
                                            <!--</div>-->
                                            <div class="col-sm-3 form-group">
                                                <label>
                                                    Output*
                                                </label>
                                                <div class="field-wrap">
                                                    <input disabled class="form-control form-control-sm" type="text" name="Output[{{$i}}][{{$j}}]" placeholder="Output" value="{{isset($StageVal->Output) && $StageVal->Output!=''?$StageVal->Output:''}}" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label>
                                                    Description*
                                                </label>
                                                <div class="field-wrap">
                                                    <input disabled class="form-control form-control-sm" type="text" name="Description_Second[{{$i}}][{{$j}}]" placeholder="Description" value="{{isset($StageVal->Description_Second) && $StageVal->Description_Second!=''?$StageVal->Description_Second:''}}" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                            </div>
                                            @foreach($StageVal->Machine as $vall)
                                            <div class="col-sm-3 form-group">
                                                <label>
                                                    Material Name*
                                                    </lable>
                                                    <select disabled name="Material_Name[{{$i}}][{{$j}}]" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" id="RawMaterial{{$i}}{{$j}}" onclick="RawMaterial({{$i}},{{$j}})" required>
                                                        <option value="" selected disabled>Select</option>
                                                        @foreach($Raw_Material as $val)
                                                        <option value="{{$val->RawMaterial->id}}" {{isset($vall->Material_Name) && $vall->Material_Name==$val->RawMaterial->id?'selected':''}}>{{$val->RawMaterial->material_name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error-message" style="color: red; display: none;"></span>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label>HSN Code*</label>
                                                <div class="field-wrap">
                                                    <input readonly class="form-control form-control-sm" type="number" id="HSNCode{{$i}}{{$j}}" name="HSN_Code[{{$i}}][{{$j}}]" placeholder="HSN Code" value="{{isset($vall->HSN_Code) && $vall->HSN_Code!=''?$vall->HSN_Code:''}}" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label>UOM</label>
                                                <div class="field-wrap">
                                                    <input readonly class="form-control form-control-sm" type="number" id="uom{{$i}}{{$j}}" name="UOM[{{$i}}][{{$j}}]" placeholder="UOM" value="{{isset($vall->UOM) && $vall->UOM!=''?$vall->UOM:''}}" required>
                                                    {{-- <select disabled id="uom{{$i}}{{$j}}" name="UOM[{{$i}}][{{$j}}]" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" required>
                                                        <option value="" selected disabled>Select</option>
                                                        @foreach($UOM as $val)
                                                        <option value="{{$val->id}}" {{isset($StageVal->UOM) && $StageVal->UOM==$val->id?'selected':''}}>{{$val->UOMs}}</option>
                                                        @endforeach
                                                    </select> --}}
                                                </div>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label>Material QTY*</label>
                                                <div class="field-wrap">
                                                    <input disabled class="form-control form-control-sm" type="number" name="Material_QTY[{{$i}}][{{$j}}]" placeholder="Material QTY" value="{{isset($vall->Material_QTY) && $vall->Material_QTY!=''?$vall->Material_QTY:''}}" required>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        <hr>
                                        @if(count($StageVal->Machine)>0)
                                        @php
                                        $k=1;
                                        @endphp
                                        @foreach($StageVal->Machine as $vall)
                                        <div class="row" id="Machine{{$i}}{{$j}}{{$k}}">
                                            <input disabled type="hidden" name="Machine_Id[{{$i}}][{{$j}}][{{$k}}]" value="{{isset($vall->id) && $vall->id!=''?$vall->id:''}}">
                                            <div class="row col-sm-11">
                                                {{-- <div class="col-sm-3 form-group">
                                                    <label>
                                                        Machine Name
                                                    </label>
                                                    <div class="field-wrap">
                                                        <select disabled name="Machine_Name[{{$i}}][{{$j}}][{{$k}}]" class="form-select form-select-sm js-example-matcher-start">
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
                                                        <select disabled name="Machine_Code[{{$i}}][{{$j}}][{{$k}}]" class="form-select form-select-sm js-example-matcher-start">
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
                                                        <select disabled name="Machine_Company[{{$i}}][{{$j}}][{{$k}}]" class="form-select form-select-sm js-example-matcher-start">
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
                                                        <select disabled name="Make_Model[{{$i}}][{{$j}}][{{$k}}]" class="form-select form-select-sm js-example-matcher-start">
                                                            <option value="" selected disabled>Select</option>
                                                            @foreach($Make_Model as $val)
                                                            <option value="{{$val->id}}" {{isset($vall->Make_Model) && $vall->Make_Model==$val->id?'selected':''}}>{{$val->Make_Model}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Date of Purchase*
                                                    </label>
                                                    <div class="field-wrap">
                                                        <input disabled class="form-control form-control-sm" type="date" name="Date_of_Purchase[{{$i}}][{{$j}}][{{$k}}]" placeholder="Date of Purchase" value="{{isset($vall->Date_of_Purchase) && $vall->Date_of_Purchase!=''?$vall->Date_of_Purchase:''}}" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Preventive Maintenance*
                                                    </label>
                                                    <div class="field-wrap">
                                                        <input disabled class="form-control form-control-sm" type="text" name="Preventive_Maintenance[{{$i}}][{{$j}}][{{$k}}]" placeholder="preventive Maintenance" value="{{isset($vall->Preventive_Maintenance) && $vall->Preventive_Maintenance!=''?$vall->Preventive_Maintenance:''}}" required>
                                                    </div>
                                                </div> --}}
                                            </div>
                                        </div>
                                        <br>
                                        @php
                                        $k++;
                                        @endphp
                                        @endforeach
                                        @endif
                                        <div id="MachineFiels00"></div>
                                    </div>
                                </div>
                                <br>
                                @php
                                $j++;
                                @endphp
                                @endforeach
                                @endif
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
                                                <input disabled class="form-control form-control-sm" type="text" name="Parameter[{{$i}}]" value="{{isset($DataVal->Parameter) && $DataVal->Parameter!=''?$DataVal->Parameter:''}}">
                                            </td>
                                            <td>
                                                <div class="field-wrap">
                                                    <select disabled name="UOM_Second[{{$i}}]" class="form-select form-select-sm js-example-matcher-start">
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
                        </div>
                        <br>
                        @php
                        $i++;
                        @endphp
                        @endforeach
                        @endif
                        <div id="Stage"></div>
                        <div class=" row">
                            <div class="col-sm-8 form-group"></div>
                            <div class="col-sm-4 form-group">
                                <br>
                                <br>
                                <label for="State">Remarks:</label>
                                <input disabled type="text" name="remarks" cols="30" rows="5" class="form-control form-control-sm" placeholder="Remarks" value="{{isset($edit->remarks) && $edit->remarks!=''?$edit->remarks:''}}" id="logfgfau">
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                @php
                $STEP = Session::get('STEP');
                $EXT = Session::get('EXT');
                @endphp
                @if($edit->Approve_status!='REJECT')
                <form action="{{url('ProductionProcess/approve')}}" method="POST">
                    @csrf
                    <input type="hidden" name="approveID" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                    <div class="tab-content" id="myTabContent">
                        @if($edit->Approve_status!='APPROVE' && in_array(1, $STEP) || in_array(2, $STEP) || in_array(3, $STEP) || isset($EXT[19]['Forward']))
                        <div class="button_div">
                            <div class="selector">
                                <div class="selecotr-item">
                                    <input type="radio" id="radio1" name="during_approval" class="selector-item_radio" value="APPROVE" required>
                                    <label for="radio1" class="selector-item_label">APPROVE</label>
                                </div>
                                <div class="selecotr-item">
                                    <input type="radio" id="radio2" name="during_approval" class="selector-item_radio" value="REJECT" required>
                                    <label for="radio2" class="selector-item_label">REJECT</label>
                                </div>
                                <div class="selecotr-item">
                                    <input type="radio" id="radio18" name="during_approval" class="selector-item_radio" value="RECHECK" required>
                                    <label for="radio18" class="selector-item_label">RECHECK</label>
                                </div>
                                <div class="selecotr-item">
                                    <input type="radio" id="radio4" name="during_approval" class="selector-item_radio" value="HOLD" required>
                                    <label for="radio4" class="selector-item_label">HOLD</label>
                                </div>
                                <div class="selecotr-item">
                                    <input type="radio" id="radio7" name="during_approval" class="selector-item_radio" value="OBJECT" required>
                                    <label for="radio7" class="selector-item_label">OBJECT</label>
                                </div>
                                <div class="selecotr-item">
                                    <input type="radio" id="radio5" name="during_approval" class="selector-item_radio" value="FORWARD" required>
                                    <label for="radio5" class="selector-item_label">FORWARD</label>
                                </div>
                                <div class="selecotr-item">
                                    <input type="radio" id="radio15" name="pre_post_approval" class="selector-item_radio" value="AUDIT">
                                    <label for="radio15" class="selector-item_label">AUDIT</label>
                                </div>
                                <div class="selecotr-item">
                                    <input type="radio" id="radio16" name="pre_post_approval" class="selector-item_radio" value="INTIMATION">
                                    <label for="radio16" class="selector-item_label">INTIMATION</label>
                                </div>
                                <div class="selecotr-item">
                                    <input type="radio" id="radio17" name="pre_post_approval" class="selector-item_radio" value="QUERY">
                                    <label for="radio17" class="selector-item_label">QUERY</label>
                                </div>
                            </div>
                            <div id="showfields" class="row" style="display: none;">
                                <div class="col-sm-4 form-group">
                                    <label>Days For Holding</lable>
                                        <input type="date" style="border-radius: 12px;" name="days_for_holding" placeholder="Days For Holding" min="{{date('Y-m-d')}}" class="form-control form-control-sm requireddd" value="">
                                </div>
                            </div>
                            <div id="Forwords" class="row" style="display: none;">
                                <div class="col-sm-4 form-group">
                                    <label>Forward To</lable>
                                        <select class="form-select form-select-sm requirrreddd" name="Forward_To">
                                            <option value="" selected disabled>Select</option>
                                            @foreach($employeeName as $val)
                                            <option value="{{isset($val->id) && $val->id!=''?$val->id:''}}">{{isset($val->fullname) && $val->fullname!=''?$val->fullname:''}}</option>
                                            @endforeach
                                        </select>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="button_div">
                            <div class="selector">
                                <div class="selecotr-item">
                                    <input type="radio" id="radio6" name="pre_post_approval" class="selector-item_radio" value="AUDIT">
                                    <label for="radio6" class="selector-item_label">AUDIT</label>
                                </div>
                                <div class="selecotr-item">
                                    <input type="radio" id="radio8" name="pre_post_approval" class="selector-item_radio" value="INTIMATION">
                                    <label for="radio8" class="selector-item_label">INTIMATION</label>
                                </div>
                                <div class="selecotr-item">
                                    <input type="radio" id="radio9" name="pre_post_approval" class="selector-item_radio" value="QUERY">
                                    <label for="radio9" class="selector-item_label">QUERY</label>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="form-group" id="u_rama">
                        <textarea class="form-control" name="comment_text" id="" rows="3" placeholder="Remarks" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    @if(isset($nextID) && !empty($nextID))
                    <a href="{{url('ProductionProcess/view-approve/'.$nextID)}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                    @else
                    <a href="{{url('ProductionProcess/ProductionProcessApproveList')}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
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
                                <td>{{isset($val->user->name) && $val->user->name!=''?$val->user->name:''}}</td>
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
            </div>
        </section>
    </div>
</div>
</section>
@endsection
@push('custom-scripts')
<script>
    $(document).ready(function() {
        activeclass(26, 2);
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
        $('input[type=radio][name=during_approval]').on('click', function() {
            if ($('#radio4').is(':checked')) {
                $('#showfields').show();
                $('.requireddd').prop('required', true);
            } else {
                $('#showfields').hide();
                $('.requireddd').prop('required', false);
            }
        });

        $('input[type=radio][name=pre_post_approval]').on('click', function() {
            $('#showfields').hide();
            $('.requireddd').prop('required', false);
        });
    });

    $(document).ready(function() {
        $('input[type=radio][name=during_approval]').on('click', function() {
            if ($('#radio5').is(':checked')) {
                $('#Forwords').show();
                $('.requirrreddd').prop('required', true);
            } else {
                $('#Forwords').hide();
                $('.requirrreddd').prop('required', false);
            }
        });

        $('input[type=radio][name=pre_post_approval]').on('click', function() {
            $('#Forwords').hide();
            $('.requirrreddd').prop('required', false);
        });
    });
</script>
<script>
    const prePostApprovalRadios = document.querySelectorAll('[name="pre_post_approval"]');
    const duringApprovalRadios = document.querySelectorAll('[name="during_approval"]');
    const duringApprovalFields = document.querySelector('.selector');

    prePostApprovalRadios.forEach(prePostRadio => {
        prePostRadio.addEventListener('change', () => {
            if (prePostRadio.checked) {
                duringApprovalRadios.forEach(duringRadio => {
                    duringRadio.checked = false;
                    duringRadio.removeAttribute('required');
                });

                duringApprovalFields.classList.add('disabled');
            }
        });
    });

    duringApprovalRadios.forEach(duringRadio => {
        duringRadio.addEventListener('change', () => {
            if (duringRadio.checked) {
                prePostApprovalRadios.forEach(prePostRadio => {
                    prePostRadio.checked = false;
                });

                duringApprovalFields.classList.remove('disabled');
            }
        });
    });
</script>
@endpush