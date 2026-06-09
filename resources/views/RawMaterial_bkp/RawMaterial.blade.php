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
                <a href="{{url('RawMaterial/RawMaterialList')}}" class="btn btn-info"> <i class="fa fa-arrow-left"></i> BACK</a>
                <a href="{{url('RawMaterial/RawMaterialList')}}" class="btn btn-info" style="margin-left:10px"> <i class="fa fa-home"></i> Home</a>
            </div>
            <div class="row">
                <div class="container">
                    <br>
                    <div>
                        <form id="Form" action="{{url('RawMaterial/AddRawMaterial')}}" method="POST">
                            @csrf
                            <input class="form-control" type="hidden" name="edit" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                            <div class="tab1">
                                @if(count($raw)>0)
                                @php
                                $i = 1;
                                @endphp
                                @foreach($raw as $rawVal)
                                <input type="hidden" name="raw_id[{{$i}}]" value="{{isset($rawVal->id) && $rawVal->id!=''?$rawVal->id:''}}">
                                <div class="row" id="row{{$i}}">
                                    <div class="tab1 col-sm-11">
                                        <div class="row">
                                            <div class="col-sm-3 form-group Select2Design">
                                                <label>
                                                    Organization*
                                                </label>
                                                <select name="Organization[{{$i}}]" class="form-select form-select-sm js-example-matcher-start" required>
                                                    <option value="" selected disabled>Select</option>
                                                    @foreach($Organization as $val)
                                                    <option value="{{$val->id}}" {{isset($rawVal->Organization) && $rawVal->Organization==$val->id?'selected':''}}>{{$val->organization}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-3 form-group Select2Design">
                                                <label>
                                                    Manufacturing Unit*
                                                </label>
                                                <select name="Manufacturing_Unit[{{$i}}]" class="form-select form-select-sm js-example-matcher-start" required>
                                                    <option value="" selected disabled>Select</option>
                                                    @foreach($Manufacturing_Unit as $val)
                                                    <option value="{{$val->id}}" {{isset($rawVal->Manufacturing_Unit) && $rawVal->Manufacturing_Unit==$val->id?'selected':''}}>{{$val->Manufacturing_unit}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-3 form-group Select2Design">
                                                <label>
                                                    Godown Name*
                                                </label>
                                                <select name="Godown_name[{{$i}}]" class="form-select form-select-sm js-example-matcher-start" required>
                                                    <option value="" selected disabled>Select</option>
                                                    @foreach($Godown_Name as $val)
                                                    <option value="{{$val->id}}" {{isset($rawVal->Godown_name) && $rawVal->Godown_name==$val->id?'selected':''}}>{{$val->Godown_Name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label>
                                                    Date*
                                                </label>
                                                <input disabled class="form-control form-control-sm date" type="text" name="date[{{$i}}]" placeholder="Current date" value="{{isset($rawVal->date) && $rawVal->date!=''?$rawVal->date:''}}" required>
                                            </div>
                                        </div>
                                        @if(count($rawVal->raw_data)>0)
                                        @php
                                        $j = 1;
                                        @endphp
                                        @foreach ($rawVal->raw_data as $dataVal)
                                        <br>
                                        <input type="hidden" name="raw_data_id[{{$i}}][{{$j}}]" value="{{isset($dataVal->id) && $dataVal->id!=''?$dataVal->id:''}}">
                                        <div class="row" id="rowss{{$i}}{{$j}}">
                                            <div class="tab1 col-sm-11 row">
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Raw Material*
                                                        </lable>
                                                        <select name="Raw_Material[{{$i}}][{{$j}}]" class="form-select form-select-sm js-example-matcher-start" id="RawMaterial{{$i}}{{$j}}" onclick="Material({{$i}},{{$j}})" required>
                                                            <option value="" selected disabled>Select</option>
                                                            @foreach($Raw_Material as $val)
                                                            <option value="{{$val->id}}" {{isset($dataVal->Raw_Material) && $dataVal->Raw_Material==$val->id?'selected':''}}>{{$val->Material_Name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="error-message" style="color: red; display: none;"></span>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>HSN Code*</label>
                                                    <div class="field-wrap">
                                                        <input readonly class="form-control form-control-sm" type="number" id="HSNCode{{$i}}{{$j}}" name="HSN_Code[{{$i}}][{{$j}}]" placeholder="HSN Code" value="{{isset($dataVal->HSN_Code) && $dataVal->HSN_Code!=''?$dataVal->HSN_Code:''}}" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>UOM</label>
                                                    <div class="field-wrap">
                                                        <select disabled name="UOM[{{$i}}][{{$j}}]" id="uom{{$i}}{{$j}}" class="form-select form-select-sm js-example-matcher-start" required>
                                                            <option value="" selected disabled>Select</option>
                                                            @foreach($UOM as $val)
                                                            <option value="{{$val->id}}" {{isset($dataVal->UOM) && $dataVal->UOM==$val->id?'selected':''}}>{{$val->UOMs}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        OB*
                                                    </label>
                                                    <select name="OB[{{$i}}][{{$j}}]" class="form-select form-select-sm js-example-matcher-start" required>
                                                        <option value="" selected disabled>Select</option>
                                                        @foreach($OB as $val)
                                                        <option value="{{$val->id}}" {{isset($dataVal->OB) && $dataVal->OB==$val->id?'selected':''}}>{{$val->OB}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>Received QTY.*</label>
                                                    <div class="field-wrap">
                                                        <input class="form-control form-control-sm" type="number" name="Received_QTY[{{$i}}][{{$j}}]" placeholder="Received QTY" value="{{isset($dataVal->Received_QTY) && $dataVal->Received_QTY!=''?$dataVal->Received_QTY:''}}" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>Balance Stock</label>
                                                    <div class="field-wrap">
                                                        <input class="form-control form-control-sm" type="text" name="Balance_Stock[{{$i}}][{{$j}}]" placeholder="Balance Stock" value="{{isset($dataVal->Balance_Stock) && $dataVal->Balance_Stock!=''?$dataVal->Balance_Stock:''}}" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Store In Rack No.*
                                                    </label>
                                                    <select name="rack_no[{{$i}}][{{$j}}]" class="form-select form-select-sm js-example-matcher-start" required>
                                                        <option value="" selected disabled>Select</option>
                                                        @foreach($Rack_No as $val)
                                                        <option value="{{$val->id}}" {{isset($dataVal->rack_no) && $dataVal->rack_no==$val->id?'selected':''}}>{{$val->Rack_No}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Sub Rack No.*
                                                    </label>
                                                    <select name="sub_rack_no[{{$i}}][{{$j}}]" class="form-select form-select-sm js-example-matcher-start" required>
                                                        <option value="" selected disabled>Select</option>
                                                        @foreach($Sub_Rack_No as $val)
                                                        <option value="{{$val->id}}" {{isset($dataVal->sub_rack_no) && $dataVal->sub_rack_no==$val->id?'selected':''}}>{{$val->Sub_Rack_No}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Store In Bin No.*
                                                    </label>
                                                    <select name="bin_no[{{$i}}][{{$j}}]" class="form-select form-select-sm js-example-matcher-start" required>
                                                        <option value="" selected disabled>Select</option>
                                                        @foreach($Bin_No as $val)
                                                        <option value="{{$val->id}}" {{isset($dataVal->bin_no) && $dataVal->bin_no==$val->id?'selected':''}}>{{$val->Bin_No}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Sub Bin No.*
                                                    </label>
                                                    <select name="sub_bin_no[{{$i}}][{{$j}}]" class="form-select form-select-sm js-example-matcher-start" required>
                                                        <option value="" selected disabled>Select</option>
                                                        @foreach($Sub_Bin_No as $val)
                                                        <option value="{{$val->id}}" {{isset($dataVal->sub_bin_no) && $dataVal->sub_bin_no==$val->id?'selected':''}}>{{$val->Sub_Bin_No}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Rack OB.*
                                                    </label>
                                                    <input class="form-control form-control-sm" type="text" name="rack_ob[{{$i}}][{{$j}}]" placeholder="Rack OB" value="{{isset($dataVal->rack_ob) && $dataVal->rack_ob!=''?$dataVal->rack_ob:''}}" required>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Rack CB.*
                                                    </label>
                                                    <input class="form-control form-control-sm" type="text" name="rack_cb[{{$i}}][{{$j}}]" placeholder="Rack CB" value="{{isset($dataVal->rack_cb) && $dataVal->rack_cb!=''?$dataVal->rack_cb:''}}" required>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Bin OB.*
                                                    </label>
                                                    <input class="form-control form-control-sm" type="text" name="bin_ob[{{$i}}][{{$j}}]" placeholder="Bin OB" value="{{isset($dataVal->bin_ob) && $dataVal->bin_ob!=''?$dataVal->bin_ob:''}}" required>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Bin CB.*
                                                    </label>
                                                    <input class="form-control form-control-sm" type="text" name="bin_cb[{{$i}}][{{$j}}]" placeholder="Bin CB" value="{{isset($dataVal->bin_cb) && $dataVal->bin_cb!=''?$dataVal->bin_cb:''}}" required>
                                                </div>
                                            </div>
                                            @if($j==1)
                                            <div class="col-sm-1">
                                                <a href="javascript:;" id="addrow{{$i}}" onclick="addrow({{$i}},{{isset($raw_data_count) && $raw_data_count==0?1:$raw_data_count+1}})" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                            </div>
                                            @else
                                            <div class="col-sm-1">
                                                <a href="javascript:;" onclick="removeorow({{$i}},{{$j}})" class="btn btn-danger btn-sm mt-4 btn_remove mt-4">X</a>
                                            </div>
                                            @endif
                                        </div>
                                        @php
                                        $j++;
                                        @endphp
                                        @endforeach
                                        @else
                                        <div class="row">
                                            <div class="tab1 col-sm-11 row">
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Raw Material*
                                                        </lable>
                                                        <select name="Raw_Material[{{$i}}][0]" class="form-select form-select-sm js-example-matcher-start" id="RawMaterial{{$i}}0" onclick="Material({{$i}},0)" required>
                                                            <option value="" selected disabled>Select</option>
                                                            @foreach($Raw_Material as $val)
                                                            <option value="{{$val->id}}">{{$val->Material_Name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="error-message" style="color: red; display: none;"></span>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>HSN Code*</label>
                                                    <div class="field-wrap">
                                                        <input readonly class="form-control form-control-sm" type="number" id="HSNCode{{$i}}0" name="HSN_Code[{{$i}}][0]" placeholder="HSN Code" value="{{isset($dataVal->HSN_Code) && $dataVal->HSN_Code!=''?$dataVal->HSN_Code:''}}" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>UOM</label>
                                                    <div class="field-wrap">
                                                        <select disabled name="UOM[{{$i}}][0]" id="uom{{$i}}0" class="form-select form-select-sm js-example-matcher-start" required>
                                                            <option value="" selected disabled>Select</option>
                                                            @foreach($UOM as $val)
                                                            <option value="{{$val->id}}">{{$val->UOMs}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        OB*
                                                    </label>
                                                    <select name="OB[{{$i}}][0]" class="form-select form-select-sm js-example-matcher-start" required>
                                                        <option value="" selected disabled>Select</option>
                                                        @foreach($OB as $val)
                                                        <option value="{{$val->id}}">{{$val->OB}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>Received QTY.*</label>
                                                    <div class="field-wrap">
                                                        <input class="form-control form-control-sm" type="number" name="Received_QTY[{{$i}}][0]" placeholder="Received QTY" value="" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>Balance Stock</label>
                                                    <div class="field-wrap">
                                                        <input class="form-control form-control-sm" type="text" name="Balance_Stock[{{$i}}][0]" placeholder="Balance Stock" value="" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Store In Rack No.*
                                                    </label>
                                                    <select name="rack_no[{{$i}}][0]" class="form-select form-select-sm js-example-matcher-start" required>
                                                        <option value="" selected disabled>Select</option>
                                                        @foreach($Rack_No as $val)
                                                        <option value="{{$val->id}}">{{$val->Rack_No}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Sub Rack No.*
                                                    </label>
                                                    <select name="sub_rack_no[{{$i}}][0]" class="form-select form-select-sm js-example-matcher-start" required>
                                                        <option value="" selected disabled>Select</option>
                                                        @foreach($Sub_Rack_No as $val)
                                                        <option value="{{$val->id}}">{{$val->Sub_Rack_No}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Store In Bin No.*
                                                    </label>
                                                    <select name="bin_no[{{$i}}][0]" class="form-select form-select-sm js-example-matcher-start" required>
                                                        <option value="" selected disabled>Select</option>
                                                        @foreach($Bin_No as $val)
                                                        <option value="{{$val->id}}">{{$val->Bin_No}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Sub Bin No.*
                                                    </label>
                                                    <select name="sub_bin_no[{{$i}}][0]" class="form-select form-select-sm js-example-matcher-start" required>
                                                        <option value="" selected disabled>Select</option>
                                                        @foreach($Sub_Bin_No as $val)
                                                        <option value="{{$val->id}}">{{$val->Sub_Bin_No}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Rack OB.*
                                                    </label>
                                                    <input class="form-control form-control-sm" type="text" name="rack_ob[{{$i}}][0]" placeholder="Rack OB" value="" required>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Rack CB.*
                                                    </label>
                                                    <input class="form-control form-control-sm" type="text" name="rack_cb[{{$i}}][0]" placeholder="Rack CB" value="" required>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Bin OB.*
                                                    </label>
                                                    <input class="form-control form-control-sm" type="text" name="bin_ob[{{$i}}][0]" placeholder="Bin OB" value="" required>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Bin CB.*
                                                    </label>
                                                    <input class="form-control form-control-sm" type="text" name="bin_cb[{{$i}}][0]" placeholder="Bin CB" value="" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-1">
                                                <div class="col-sm-1">
                                                    <a href="javascript:;" id="addrow{{$i}}" onclick="addrow({{$i}},{{isset($raw_data_count) && $raw_data_count==0?1:$raw_data_count+1}})" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        <div id="addrowfields{{$i}}"></div>
                                    </div>
                                    @if($i==1)
                                    <div class="col-sm-1">
                                        <a href="javascript:;" id="addmain" onclick="addmain({{isset($raw_count) && $raw_count==0?1:$raw_count+1}})" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                    </div>
                                    @else
                                    <div class="col-sm-1">
                                        <a href="javascript:;" onclick="remove({{$i}})" class="btn btn-danger btn-sm mt-4">X</a>
                                    </div>
                                    @endif
                                </div>
                                @php
                                $i++;
                                @endphp
                                @endforeach
                                @else
                                <div class="row">
                                    <div class="tab1 col-sm-11">
                                        <div class="row">
                                            {{-- <div class="col-sm-3 form-group Select2Design">
                                                <label>
                                                    Organization*
                                                </label>
                                                <select name="Organization[0]" class="form-select form-select-sm js-example-matcher-start" required>
                                                    <option value="" selected disabled>Select</option>
                                                    @foreach($Organization as $val)
                                                    <option value="{{$val->id}}">{{$val->organization}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-3 form-group Select2Design">
                                                <label>
                                                    Manufacturing Unit*
                                                </label>
                                                <select name="Manufacturing_Unit[0]" class="form-select form-select-sm js-example-matcher-start" required>
                                                    <option value="" selected disabled>Select</option>
                                                    @foreach($Manufacturing_Unit as $val)
                                                    <option value="{{$val->id}}">{{$val->Manufacturing_unit}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-3 form-group Select2Design">
                                                <label>
                                                    Godown Name*
                                                </label>
                                                <select name="Godown_name[0]" class="form-select form-select-sm js-example-matcher-start" required>
                                                    <option value="" selected disabled>Select</option>
                                                    @foreach($Godown_Name as $val)
                                                    <option value="{{$val->id}}">{{$val->Godown_Name}}</option>
                                                    @endforeach
                                                </select>
                                            </div> --}}
                                            <div class="col-sm-3 form-group">
                                                <label>
                                                    Date*
                                                </label>
                                                <input disabled class="form-control form-control-sm date" type="text" name="date[0]" placeholder="Current date" value="" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="tab1 col-sm-11 row">
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Raw Material*
                                                        </lable>
                                                        <select name="Raw_Material[0][0]" class="form-select form-select-sm js-example-matcher-start matid" id="RawMaterial00" onclick="Material(0,0)" required>
                                                            <option value="" selected disabled>Select</option>
                                                            @foreach($Raw_Material as $val)
                                                            <option value="{{$val->id}}">{{$val->material_name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="error-message" style="color: red; display: none;"></span>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>HSN Code*</label>
                                                    <div class="field-wrap">
                                                        <input readonly class="form-control form-control-sm" type="number" id="HSNCode00" name="HSN_Code[0][0]" placeholder="HSN Code" value="{{isset($dataVal->HSN_Code) && $dataVal->HSN_Code!=''?$dataVal->HSN_Code:''}}" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>UOM</label>
                                                    <div class="field-wrap">
                                                        <input readonly class="form-control form-control-sm" type="text" id="uomm" name="UOM[0][0]" placeholder="UOM"  required>
                                                        {{-- <select disabled name="UOM[0][0]" id="uomm" class="form-select form-select-sm js-example-matcher-start" required>
                                                            <option value="" selected disabled>Select</option>
                                                            @foreach($UOM as $val)
                                                            <option value="{{$val->id}}">{{$val->UOMs}}</option>
                                                            @endforeach
                                                        </select> --}}
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        OB*
                                                    </label>
                                                    <select name="OB[0][0]" class="form-select form-select-sm js-example-matcher-start" required>
                                                        <option value="" selected disabled>Select</option>
                                                        @foreach($OB as $val)
                                                        <option value="{{$val->id}}">{{$val->OB}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>Received QTY.*</label>
                                                    <div class="field-wrap">
                                                        <input class="form-control form-control-sm" type="number" name="Received_QTY[0][0]" placeholder="Received QTY" value="" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>Balance Stock</label>
                                                    <div class="field-wrap">
                                                        <input class="form-control form-control-sm" type="text" name="Balance_Stock[0][0]" placeholder="Balance Stock" value="" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Store In Rack No.*
                                                    </label>
                                                    <select name="rack_no[0][0]" class="form-select form-select-sm js-example-matcher-start" required>
                                                        <option value="" selected disabled>Select</option>
                                                        @foreach($Rack_No as $val)
                                                        <option value="{{$val->id}}">{{$val->Rack_No}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Sub Rack No.*
                                                    </label>
                                                    <select name="sub_rack_no[0][0]" class="form-select form-select-sm js-example-matcher-start" required>
                                                        <option value="" selected disabled>Select</option>
                                                        @foreach($Sub_Rack_No as $val)
                                                        <option value="{{$val->id}}">{{$val->Sub_Rack_No}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Store In Bin No.*
                                                    </label>
                                                    <select name="bin_no[0][0]" class="form-select form-select-sm js-example-matcher-start" required>
                                                        <option value="" selected disabled>Select</option>
                                                        @foreach($Bin_No as $val)
                                                        <option value="{{$val->id}}">{{$val->Bin_No}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Sub Bin No.*
                                                    </label>
                                                    <select name="sub_bin_no[0][0]" class="form-select form-select-sm js-example-matcher-start" required>
                                                        <option value="" selected disabled>Select</option>
                                                        @foreach($Sub_Bin_No as $val)
                                                        <option value="{{$val->id}}">{{$val->Sub_Bin_No}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Rack OB.*
                                                    </label>
                                                    <input class="form-control form-control-sm" type="text" name="rack_ob[0][0]" placeholder="Rack OB" value="" required>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Rack CB.*
                                                    </label>
                                                    <input class="form-control form-control-sm" type="text" name="rack_cb[0][0]" placeholder="Rack CB" value="" required>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Bin OB.*
                                                    </label>
                                                    <input class="form-control form-control-sm" type="text" name="bin_ob[0][0]" placeholder="Bin OB" value="" required>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Bin CB.*
                                                    </label>
                                                    <input class="form-control form-control-sm" type="text" name="bin_cb[0][0]" placeholder="Bin CB" value="" required>
                                                </div>
                                            </div>
                                            {{-- <div class="col-sm-1">
                                                <a href="javascript:;" id="addrow0" onclick="addrow(0,1)" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                            </div> --}}
                                        </div>
                                        <div id="addrowfields0"></div>
                                    </div>
                                    {{-- <div class="col-sm-1">
                                        <a href="javascript:;" id="addmain" onclick="addmain(1)" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                    </div> --}}
                                </div>
                                @endif
                                <div id="addfields"></div>
                                <div class="row">
                                    <div class="col-sm-8 form-group"></div>
                                    <div class="col-sm-4 form-group">
                                        <label for="State">Remarks:</label>
                                        <input type="text" name="remarks" cols="30" rows="5" class="form-control form-control-sm" placeholder="Remarks" value="{{isset($edit->remarks) && $edit->remarks!=''?$edit->remarks:''}}">
                                    </div>
                                </div>
                                <div style="overflow:auto;">
                                    <div style="float:right;">
                                        <button type="button" id="draft" class="btn btn1 float-right" style="margin: 5px;">Draft & Save</button>
                                        <a href="" class="btn btn1 float-right" style="margin: 5px; display:{{isset($edit->id) && $edit->id != ''?'none':'block'}}">Clear All</a>
                                        <button type="submit" id="submitBtn" class="btn btn1 float-right" style="margin: 5px;">Submit</button>
                                    </div>
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
    activeclass(12, 1);
</script>
<script>
    class CurrentDateDisplay {
        constructor(selector) {
            this.elements = document.querySelectorAll(selector);
        }

        formatDate(date) {
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            return `${day}/${month}/${year}`;
        }

        displayCurrentDate() {
            const currentDate = new Date();
            const formattedDate = this.formatDate(currentDate);

            this.elements.forEach((element) => {
                element.value = formattedDate;
            });
        }
    }

    const currentDateDisplay = new CurrentDateDisplay('.date');

    window.addEventListener('load', () => {
        currentDateDisplay.displayCurrentDate();
    });
</script>
<script>
    function addmain(i) {
        $('#addfields').append('<br><div class="row" id="row' + i + '"> <div class="tab1 col-sm-11"> <div class="row"> <div class="col-sm-3 form-group"> <label> Organization* </label> <select name="Organization[' + i + ']" class="form-select form-select-sm js-example-matcher-start" required> <option value="" selected disabled>Select</option> @foreach($Organization as $val) <option value="{{$val->id}}">{{$val->organization}}</option> @endforeach </select> </div><div class="col-sm-3 form-group"> <label> Manufacturing Unit* </label> <select name="Manufacturing_Unit[' + i + ']" class="form-select form-select-sm js-example-matcher-start" required> <option value="" selected disabled>Select</option> @foreach($Manufacturing_Unit as $val) <option value="{{$val->id}}">{{$val->Manufacturing_unit}}</option> @endforeach </select> </div><div class="col-sm-3 form-group"> <label> Godown Name* </label> <select name="Godown_name[' + i + ']" class="form-select form-select-sm js-example-matcher-start" required> <option value="" selected disabled>Select</option>@foreach($Godown_Name as $val)<option value="{{$val->id}}" >{{$val->Godown_Name}}</option> @endforeach </select> </div><div class="col-sm-3 form-group"> <label> Date* </label> <input disabled class="form-control form-control-sm date" type="text" name="date[' + i + ']" placeholder="Current date" value="" required> </div></div><div class="row"> <div class="tab1 col-sm-11 row"> <div class="col-sm-3 form-group"> <label> Raw Material* </lable> <select name="Raw_Material[' + i + '][0]" class="form-select form-select-sm js-example-matcher-start" id="RawMaterial' + i + '0" onclick="Material(' + i + ',0)" required> <option value="" selected disabled>Select</option> @foreach($Raw_Material as $val)<option value="{{$val->id}}">{{$val->Material_Name}}</option> @endforeach</select><span class="error-message" style="color: red; display: none;"></span> </div><div class="col-sm-3 form-group"><label>HSN Code*</label><div class="field-wrap"><input readonly class="form-control form-control-sm" type="number" id="HSNCode' + i + '0" name="HSN_Code[' + i + '][0]" placeholder="HSN Code" value="" required></div></div><div class="col-sm-3 form-group"> <label>UOM</label> <div class="field-wrap"> <select disabled name="UOM[' + i + '][0]" id="uom' + i + '0" class="form-select form-select-sm js-example-matcher-start" required> <option value="" selected disabled>Select</option> @foreach($UOM as $val)<option value="{{$val->id}}">{{$val->UOMs}}</option> @endforeach </select> </div></div><div class="col-sm-3 form-group"> <label> OB* </label> <select name="OB[' + i + '][0]" class="form-select form-select-sm js-example-matcher-start" required> <option value="" selected disabled>Select</option> @foreach($OB as $val)<option value="{{$val->id}}">{{$val->OB}}</option>@endforeach</select> </div><div class="col-sm-3 form-group"> <label>Received QTY.*</label> <div class="field-wrap"> <input class="form-control form-control-sm" type="number" name="Received_QTY[' + i + '][0]" placeholder="Received QTY" value="" required> </div></div><div class="col-sm-3 form-group"> <label>Balance Stock</label> <div class="field-wrap"> <input class="form-control form-control-sm" type="text" name="Balance_Stock[' + i + '][0]" placeholder="Balance Stock" value="" required> </div></div><div class="col-sm-3 form-group"> <label> Store In Rack No.* </label> <select name="rack_no[' + i + '][0]" class="form-select form-select-sm js-example-matcher-start" required> <option value="" selected disabled>Select</option> @foreach($Rack_No as $val) <option value="{{$val->id}}">{{$val->Rack_No}}</option> @endforeach </select> </div><div class="col-sm-3 form-group"> <label> Sub Rack No.* </label> <select name="sub_rack_no[' + i + '][0]" class="form-select form-select-sm js-example-matcher-start" required> <option value="" selected disabled>Select</option> @foreach($Sub_Rack_No as $val) <option value="{{$val->id}}">{{$val->Sub_Rack_No}}</option> @endforeach </select> </div><div class="col-sm-3 form-group"> <label> Store In Bin No.* </label> <select name="bin_no[' + i + '][0]" class="form-select form-select-sm js-example-matcher-start" required> <option value="" selected disabled>Select</option> @foreach($Bin_No as $val)<option value="{{$val->id}}" >{{$val->Bin_No}}</option> @endforeach </select> </div><div class="col-sm-3 form-group"> <label> Sub Bin No.* </label> <select name="sub_bin_no[' + i + '][0]" class="form-select form-select-sm js-example-matcher-start" required> <option value="" selected disabled>Select</option> @foreach($Sub_Bin_No as $val) <option value="{{$val->id}}">{{$val->Sub_Bin_No}}</option> @endforeach </select> </div><div class="col-sm-3 form-group"> <label> Rack OB.* </label> <input class="form-control form-control-sm" type="text" name="rack_ob[' + i + '][0]" placeholder="Rack OB" value="" required> </div><div class="col-sm-3 form-group"> <label> Rack CB.* </label> <input class="form-control form-control-sm" type="text" name="rack_cb[' + i + '][0]" placeholder="Rack CB" value="" required> </div><div class="col-sm-3 form-group"> <label> Bin OB.* </label> <input class="form-control form-control-sm" type="text" name="bin_ob[' + i + '][0]" placeholder="Bin OB" value="" required> </div><div class="col-sm-3 form-group"> <label> Bin CB.* </label> <input class="form-control form-control-sm" type="text" name="bin_cb[' + i + '][0]" placeholder="Bin CB" value="" required> </div></div><div class="col-sm-1"> <a href="javascript:;" id="addrow' + i + '" onclick="addrow(' + i + ',1)" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a> </div></div><div id="addrowfields' + i + '"></div></div><div class="col-sm-1"> <a href="javascript:;"  onclick="remove(' + i + ')" class="btn btn-danger btn-sm mt-4">X</a> </div></div>');
        i++;
        $("#addmain").attr("onclick", 'addmain(' + i + ')');

        const newDateDisplay = new CurrentDateDisplay('.date');
        newDateDisplay.displayCurrentDate();
        AppendSelect2()
    }

    function remove(id) {
        $("#row" + id).remove();
    }
</script>

<script>
    function addrow(i, j) {
        $('#addrowfields' + i).append('<br><div class="row" id="rowss' + i + j + '"> <div class="tab1 col-sm-11 row"> <div class="col-sm-3 form-group"> <label> Raw Material* </lable> <select name="Raw_Material[' + i + '][' + j + ']" class="form-select form-select-sm js-example-matcher-start" id="RawMaterial' + i + j + '" onclick="Material(' + i + ',' + j + ')" required> <option value="" selected disabled>Select</option> @foreach($Raw_Material as $val)<option value="{{$val->id}}">{{$val->Material_Name}}</option>@endforeach</select><span class="error-message" style="color: red; display: none;"></span> </div><div class="col-sm-3 form-group"><label>HSN Code*</label><div class="field-wrap"><input readonly class="form-control form-control-sm" type="number" id="HSNCode' + i + j + '" name="HSN_Code[' + i + '][' + j + ']" placeholder="HSN Code" value="" required></div></div><div class="col-sm-3 form-group"> <label>UOM</label> <div class="field-wrap"> <select disabled name="UOM[' + i + '][' + j + ']" id="uom' + i + j + '" class="form-select form-select-sm js-example-matcher-start" required> <option value="" selected disabled>Select</option> @foreach($UOM as $val)<option value="{{$val->id}}">{{$val->UOMs}}</option> @endforeach </select> </div></div><div class="col-sm-3 form-group"> <label> OB* </label> <select name="OB[' + i + '][' + j + ']" class="form-select form-select-sm js-example-matcher-start" required> <option value="" selected disabled>Select</option> @foreach($OB as $val)<option value="{{$val->id}}">{{$val->OB}}</option> @endforeach </select> </div><div class="col-sm-3 form-group"> <label>Received QTY.*</label> <div class="field-wrap"> <input class="form-control form-control-sm" type="number" name="Received_QTY[' + i + '][' + j + ']" placeholder="Received QTY" value="" required> </div></div><div class="col-sm-3 form-group"> <label>Balance Stock</label> <div class="field-wrap"> <input class="form-control form-control-sm" type="text" name="Balance_Stock[' + i + '][' + j + ']" placeholder="Balance Stock" value="" required> </div></div><div class="col-sm-3 form-group"> <label> Store In Rack No.* </label> <select name="rack_no[' + i + '][' + j + ']" class="form-select form-select-sm js-example-matcher-start" required> <option value="" selected disabled>Select</option> @foreach($Rack_No as $val) <option value="{{$val->id}}">{{$val->Rack_No}}</option> @endforeach </select> </div><div class="col-sm-3 form-group"> <label> Sub Rack No.* </label> <select name="sub_rack_no[' + i + '][' + j + ']" class="form-select form-select-sm js-example-matcher-start" required> <option value="" selected disabled>Select</option> @foreach($Sub_Rack_No as $val)<option value="{{$val->id}}">{{$val->Sub_Rack_No}}</option>@endforeach </select> </div><div class="col-sm-3 form-group"> <label> Store In Bin No.* </label> <select name="bin_no[' + i + '][' + j + ']" class="form-select form-select-sm js-example-matcher-start" required> <option value="" selected disabled>Select</option> @foreach($Bin_No as $val) <option value="{{$val->id}}" >{{$val->Bin_No}}</option> @endforeach </select> </div><div class="col-sm-3 form-group"> <label> Sub Bin No.* </label> <select name="sub_bin_no[' + i + '][' + j + ']" class="form-select form-select-sm js-example-matcher-start" required> <option value="" selected disabled>Select</option> @foreach($Sub_Bin_No as $val)<option value="{{$val->id}}">{{$val->Sub_Bin_No}}</option>@endforeach </select> </div><div class="col-sm-3 form-group"> <label> Rack OB.* </label> <input class="form-control form-control-sm" type="text" name="rack_ob[' + i + '][' + j + ']" placeholder="Rack OB" value="" required> </div><div class="col-sm-3 form-group"> <label> Rack CB.* </label> <input class="form-control form-control-sm" type="text" name="rack_cb[' + i + '][' + j + ']" placeholder="Rack CB" value="" required> </div><div class="col-sm-3 form-group"> <label> Bin OB.* </label> <input class="form-control form-control-sm" type="text" name="bin_ob[' + i + '][' + j + ']" placeholder="Bin OB" value="" required> </div><div class="col-sm-3 form-group"> <label> Bin CB.* </label> <input class="form-control form-control-sm" type="text" name="bin_cb[' + i + '][' + j + ']" placeholder="Bin CB" value="" required> </div></div><div class="col-sm-1"> <a href="javascript:;" onclick="removeorow(' + i + ',' + j + ')" class="btn btn-danger btn-sm mt-4 btn_remove mt-4">X</a> </div></div>');
        j++;
        $("#addrow" + i).attr("onclick", 'addrow(' + i + ',' + j + ')');
        AppendSelect2()
    }

    function removeorow(i, j) {
        $("#rowss" + i + '' + j).remove();
    }
    $(document).ready(function() {
        $('.matid').on('change', function() {
            var mid = $(this).val();
            $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf_token"]').attr('content')
            }
            });
            $.ajax({
                url: 'get-matdetailsajax/' + mid,
                type: 'GET',
                data: {
                        "_token": "{{ csrf_token() }}",
                        mid:mid,
                        },
                success:function(response) { 
                            $.each(response, function(index,materialdetails) {
                            //console.log(materialdetails.uom);
                            $("#uomm").val(materialdetails.uom);
                            });
                        }


                        });
            });
    });
</script>
@endpush