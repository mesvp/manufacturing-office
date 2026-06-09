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
        padding: 10px;
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
        /* margin-left: 10px; */
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
        /* margin-left: 10px; */
        margin-bottom: 20px;

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
                <!-- <div class="addbtn extra">
                    <a href="{{url('BOM/BOMList')}}" class="btn btn-info mr-1 btn-sm"> <i class="fa fa-arrow-left"></i></a>
                    <a href="{{url('BOM/BOMList')}}" class="btn btn-info btn-sm"> <i class="fa fa-home"></i></a>
                </div> -->

                <div class="container-fluid">


                    <div class="col-xl-12 col-md-12 col-sm-12 mb-2">
                        <div class="row">
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                <h5>BOM Details</h5>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                <label for="">Inputer Name : {{auth()->user()->name}}</label>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                <label for="">Date & Time : <span id="clock"></span></label>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                <div class="addbtn extra p-0">
                                    <a href="{{url('BOM/BOMList')}}" class="btn btn-info mr-1 btn-sm"> <i class="fa fa-arrow-left"></i></a>
                                    <a href="{{url('BOM/BOMList')}}" class="btn btn-info btn-sm"> <i class="fa fa-home"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="tab1">
                        <form action="{{url('BOM/AddBOM')}}" method="POST">
                            @csrf
                            <input class="form-control" type="hidden" name="edit" value="{{isset($BOM->id) && $BOM->id!=''?$BOM->id:''}}">
                            <div class="row" id="adaaishhhh">
                                
                                <div class="col-xl-2 col-md-4 col-lg-3 col-sm-12 form-group">
                                    <label>
                                        Finished Good(FG)*
                                        </lable>
                                        <select name="Raw_Material_FG" class="form-select form-select-sm js-example-matcher-start" id="RawMaterial00" onclick="Material(0,0)" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($Raw_Material as $val)
                                            <option value="{{$val->RawMaterial->id}}" {{isset($BOM->Raw_Material_FG) && $BOM->Raw_Material_FG==$val->RawMaterial->id?'selected':''}}>{{$val->RawMaterial->material_name}}</option>
                                            @endforeach
                                        </select>
                                        <span class="error-message" style="color: red; display: none;"></span>
                                </div>


                                <div class="col-xl-2 col-md-4 col-lg-3 col-sm-12 form-group">
                                    <label>HSN Code(FG)*</label>
                                    <div class="field-wrap">
                                        <input readonly class="form-control form-control-sm" type="number" id="HSNCode00" name="HSN_Code_FG" placeholder="HSN Code(FG)" value="{{isset($BOM->HSN_Code_FG) && $BOM->HSN_Code_FG!=''?$BOM->HSN_Code_FG:''}}" required>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-md-4 col-lg-3 col-sm-12 form-group">
                                    <label>UOM(FG)</label>
                                    <div class="field-wrap">
                                        <input readonly class="form-control form-control-sm" type="text" id="uom00" name="UOMFG" placeholder="UOM" value="{{isset($BOM->UOMFG) && $BOM->UOMFG!=''?$BOM->UOMFG:''}}" required>
                                        {{-- <select disabled name="UOMFG" id="uom00" class="form-select form-select-sm js-example-matcher-start" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($UOM as $val)
                                            <option value="{{$val->id}}" {{isset($BOM->UOMFG) && $BOM->UOMFG==$val->id?'selected':''}}>{{$val->UOMs}}</option>
                                            @endforeach
                                        </select> --}}
                                    </div>
                                </div>
                            </div>
                            <hr>
                            @if(count($Material_data)>0)
                            @php
                            $i=1;
                            @endphp
                            @foreach($Material_data as $MaterialVal)
                            <div class="row" id="MaterialRemove{{$i}}">
                                <input type="hidden" name="MaterialID[{{$i}}]" value="{{isset($MaterialVal->id) && $MaterialVal->id!=''?$MaterialVal->id:''}}">
                                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 form-group">
                                    <label>Raw Material*</lable>
                                        <select id="Material{{$i}}" name="Material[{{$i}}]" class="form-select form-select-sm js-example-matcher-start" onclick="RawMaterial({{$i}})" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($Material as $val)
                                            <option value="{{$val['materialID']}}" {{isset($MaterialVal->Material) && $MaterialVal->Material==$val['materialID']?'selected':''}}>{{$val['material_Name']}}</option>
                                            @endforeach
                                        </select>
                                        <span class="error-message" style="color: red; display: none;"></span>
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 form-group">
                                    <label>HSN Code*</label>
                                    <div class="field-wrap">
                                        <input readonly class="form-control form-control-sm" type="number" id="HSN_Code_Second{{$i}}" name="HSN_Code_Second[{{$i}}]" placeholder="HSN Code" value="{{isset($MaterialVal->HSN_Code_Second) && $MaterialVal->HSN_Code_Second!=''?$MaterialVal->HSN_Code_Second:''}}" required>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 form-group">
                                    <label>
                                        UOM*
                                    </label>
                                    <input readonly class="form-control form-control-sm" type="text" id="uoms{{$i}}" name="UOM[{{$i}}]" placeholder="UOM" value="{{isset($MaterialVal->UOM) && $MaterialVal->UOM!=''?$MaterialVal->UOM:''}}" required>
                                    {{-- <select disabled id="uoms{{$i}}" name="UOM[{{$i}}]" class="form-select form-select-sm js-example-matcher-start" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($UOM as $val)
                                        <option value="{{$val->id}}" {{isset($MaterialVal->UOM) && $MaterialVal->UOM==$val->id?'selected':''}}>{{$val->UOM}}</option>
                                        @endforeach
                                    </select> --}}
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 form-group">
                                    <label>Material QTY*</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="text" id="MaterialQTY{{$i}}" oninput="validateNumericInput(this);" onchange="TotalQTY({{$i}})" name="Material_QTY[{{$i}}]" placeholder="Material QTY" value="{{isset($MaterialVal->Material_QTY) && $MaterialVal->Material_QTY!=''?$MaterialVal->Material_QTY:''}}" required>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 form-group">
                                    <label>Scarp QTY.*</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="text" id="ScarpQTY{{$i}}" oninput="validateNumericInput(this);" onchange="TotalQTY({{$i}})" name="Scarp_QTY[{{$i}}]" placeholder="Scarp QTY" value="{{isset($MaterialVal->Scarp_QTY) && $MaterialVal->Scarp_QTY!=''?$MaterialVal->Scarp_QTY:''}}" required>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 form-group">
                                    <label>Total QTY.*</label>
                                    <div class="field-wrap">
                                        <input readonly class="form-control form-control-sm" type="text" id="TotalQTY{{$i}}" name="Total_QTY[{{$i}}]" placeholder="Total QTY" value="{{isset($MaterialVal->Total_QTY) && $MaterialVal->Total_QTY!=''?$MaterialVal->Total_QTY:''}}" required>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 form-group">
                                    <label>Basic Amount/unit*</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="text" id="BasicAmount{{$i}}" oninput="validateNumericInput(this);" onchange="TotalBasicAmount({{$i}})" name="Basic_Amount_unit[{{$i}}]" placeholder="Basic Amount/unit" value="{{isset($MaterialVal->Basic_Amount_unit) && $MaterialVal->Basic_Amount_unit!=''?$MaterialVal->Basic_Amount_unit:''}}" required>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 form-group">
                                    <label>Total Basic Amount*</label>
                                    <div class="field-wrap">
                                        <input readonly class="form-control form-control-sm" type="text" id="TotalBasicAmount{{$i}}" name="Total_Basic_Amount[{{$i}}]" placeholder="Total Basic Amount" value="{{isset($MaterialVal->Total_Basic_Amount) && $MaterialVal->Total_Basic_Amount!=''?$MaterialVal->Total_Basic_Amount:''}}" required>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 form-group">
                                    <label>GST%*</label>
                                    <select id="GSTPercentage{{$i}}" onchange="MaterialTotalAmount({{$i}})" name="GST_Percentage[{{$i}}]" class="form-select form-select-sm js-example-matcher-start" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($GST_Percentage as $val)
                                        <option value="{{$val->GST_Percentage}}" {{isset($MaterialVal->GST_Percentage) && $MaterialVal->GST_Percentage==$val->GST_Percentage?'selected':''}}>{{$val->GST_Percentage}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 form-group">
                                    <label>GST Value*</label>
                                    <div class="field-wrap">
                                        <input readonly class="form-control form-control-sm" type="text" id="GSTValue{{$i}}" name="GST_Value[{{$i}}]" placeholder="GST Value" value="{{isset($MaterialVal->GST_Value) && $MaterialVal->GST_Value!=''?$MaterialVal->GST_Value:''}}" required>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 form-group">
                                    <label>Total Amount*</label>
                                    <div class="field-wrap">
                                        <input readonly class="form-control form-control-sm" type="text" onchange="AllTotalAmount()" id="MaterialTotalAmount{{$i}}" name="Total_Amount[{{$i}}]" placeholder="Total Amount" value="{{isset($MaterialVal->Total_Amount) && $MaterialVal->Total_Amount!=''?$MaterialVal->Total_Amount:''}}" required>
                                    </div>
                                </div>
                                @if($i==1)
                                <div class="col-sm-1">
                                    <a href="javascript:;" id="MaterialAppend" onclick="MaterialAppend({{isset($Material_Count) && $Material_Count==0?1:$Material_Count+1}})" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                </div>
                                @else
                                <div class="col-sm-1">
                                    {{-- <a href="javascript:;" onclick="MaterialRemove({{$i}})" class="btn btn-danger btn-sm mt-4">X</a> --}}
                                </div>
                                @endif
                            </div>
                            @php
                            $i++;
                            @endphp
                            @endforeach
                            @else
                            <div class="row">
                                <div class="col-xl-1 col-lg-2 col-md-6 col-sm-12 form-group">
                                    <label>Raw Material*</lable>
                                        <select id="Material0" name="Material[0]" class="form-select form-select-sm js-example-matcher-start" onclick="RawMaterial(0)" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($Material as $val)
                                            <option value="{{$val['materialID']}}">{{$val['material_Name']}}</option>
                                            @endforeach
                                        </select>
                                        <span class="error-message" style="color: red; display: none;"></span>
                                </div>
                                <div class="col-xl-1 col-lg-2 col-md-6 col-sm-12 form-group">
                                    <label>HSN Code*</label>
                                    <div class="field-wrap">
                                        <input readonly class="form-control form-control-sm" type="number" id="HSN_Code_Second0" name="HSN_Code_Second[0]" placeholder="HSN Code" value="" required>
                                    </div>
                                </div>
                                <div class="col-xl-1 col-lg-2 col-md-6 col-sm-12 form-group">
                                    <label>
                                        UOM*
                                    </label>
                                    <input readonly class="form-control form-control-sm" type="text" id="uoms0" name="UOM[0]" value="" placeholder="UOM" required>
                                    {{-- <select disabled id="uoms0" name="UOM[0]" class="form-select form-select-sm js-example-matcher-start" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($UOM as $val)
                                        <option value="{{$val->id}}">{{$val->UOMs}}</option>
                                        @endforeach
                                    </select> --}}
                                </div>
                                <div class="col-xl-1 col-lg-2 col-md-6 col-sm-12 form-group">
                                    <label>Material QTY*</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="text"  id="MaterialQTY0" oninput="validateNumericInput(this);"  onchange="TotalQTY(0)" name="Material_QTY[0]" placeholder="Material QTY" value="" required>
                                    </div>
                                </div>
                                <div class="col-xl-1 col-lg-2 col-md-6 col-sm-12 form-group">
                                    <label>Scarp QTY.*</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="text" id="ScarpQTY0" oninput="validateNumericInput(this);" onchange="TotalQTY(0)" name="Scarp_QTY[0]" placeholder="Scarp QTY" value="" required>
                                    </div>
                                </div>
                                <div class="col-xl-1 col-lg-2 col-md-6 col-sm-12 form-group">
                                    <label>Total QTY.*</label>
                                    <div class="field-wrap">
                                        <input readonly class="form-control form-control-sm" type="text" id="TotalQTY0" name="Total_QTY[0]" placeholder="Total QTY" value="" required>
                                    </div>
                                </div>
                                <div class="col-xl-1 col-lg-2 col-md-6 col-sm-12 form-group">
                                    <label>Basic Rate*</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="text" id="BasicAmount0" oninput="validateNumericInput(this);" onchange="TotalBasicAmount(0)" name="Basic_Amount_unit[0]" placeholder="Basic Rate" value="" required>
                                    </div>
                                </div>
                                <div class="col-xl-1 col-lg-2 col-md-6 col-sm-12 form-group">
                                    <label>Total Rate*</label>
                                    <div class="field-wrap">
                                        <input readonly class="form-control form-control-sm" type="text" id="TotalBasicAmount0" name="Total_Basic_Amount[0]" placeholder="Total Rate" value="" required>
                                    </div>
                                </div>
                                <div class="col-xl-1 col-lg-2 col-md-6 col-sm-12 form-group">
                                    <label>GST%*</label>
                                    <select id="GSTPercentage0" onchange="MaterialTotalAmount(0)" name="GST_Percentage[0]" class="form-select form-select-sm js-example-matcher-start" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($GST_Percentage as $val)
                                        <option value="{{$val->GST_Percentage}}">{{$val->GST_Percentage}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-1 col-lg-2 col-md-6 col-sm-12 form-group">
                                    <label>GST Value*</label>
                                    <div class="field-wrap">
                                        <input readonly class="form-control form-control-sm" type="text" id="GSTValue0" name="GST_Value[0]" placeholder="GST Value" value="" required>
                                    </div>
                                </div>
                                <div class="col-xl-1 col-lg-2 col-md-6 col-sm-12 form-group">
                                    <label>Total Amount*</label>
                                    <div class="field-wrap">
                                        <input readonly class="form-control form-control-sm" type="text" onchange="AllTotalAmount()" id="MaterialTotalAmount0" name="Total_Amount[0]" placeholder="Total Amount" value="" required>
                                    </div>
                                </div>
                                <div class="col-xl-1 col-lg-2 col-md-6 col-sm-12">
                                    <a href="javascript:;" id="MaterialAppend" onclick="MaterialAppend(1)" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            @endif
                            <div id="MaterialFields"></div>
                            <hr>
                            @if(count($Manpower_data)>0)
                            @php
                            $j=1;
                            @endphp
                            @foreach($Manpower_data as $ManpowerVal)
                            <div class="row" id="ManpowerRemove{{$j}}">
                                <input type="hidden" name="ManpowerID[{{$j}}]" value="{{isset($ManpowerVal->id) && $ManpowerVal->id!=''?$ManpowerVal->id:''}}">
                                {{-- <div class="col-sm-3 form-group">
                                    <label>Manpower Skill*</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="text" name="Manpower_Skill[{{$j}}]" placeholder="Manpower Skill" value="{{isset($ManpowerVal->Manpower_Skill) && $ManpowerVal->Manpower_Skill!=''?$ManpowerVal->Manpower_Skill:''}}" required>
                                    </div>
                                </div> --}}
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 form-group">
                                    <label>
                                        Manpower Skill*
                                    </label>
                                    <select name="Manpower_Skill[{{$j}}]" class="form-select form-select-sm js-example-matcher-start" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Color as $val)
                                        <option value="{{$val->id}}" {{isset($ManpowerVal->Manpower_Skill) && $ManpowerVal->Manpower_Skill==$val->id?'selected':''}}>{{$val->Color}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 form-group">
                                    <label>Manpower Count*</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="number" name="Manpower_Count[{{$j}}]" placeholder="Manpower Count" value="{{isset($ManpowerVal->Manpower_Count) && $ManpowerVal->Manpower_Count!=''?$ManpowerVal->Manpower_Count:''}}" required>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 form-group">
                                    <label>Average Salary*</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="number" id="AverageSalary{{$j}}" onchange="AllTotalAmount()" name="Average_Salary[{{$j}}]" placeholder="Average Salary" value="{{isset($ManpowerVal->Average_Salary) && $ManpowerVal->Average_Salary!=''?$ManpowerVal->Average_Salary:''}}" required>
                                    </div>
                                </div>
                                @if($j==1)
                                <div class="col-sm-1">
                                    <a href="javascript:;" id="ManpowerAppend" onclick="ManpowerAppend({{isset($Manpower_Count) && $Manpower_Count==0?1:$Manpower_Count+1}})" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                </div>
                                @else
                                <div class="col-sm-1">
                                    {{-- <a href="javascript:;" onclick="ManpowerRemove({{$j}})" class="btn btn-danger btn-sm mt-4">X</a> --}}
                                </div>
                                @endif
                            </div>
                            @php
                            $j++;
                            @endphp
                            @endforeach
                            @else
                            <div class="row">
                                {{-- <div class="col-sm-3 form-group">
                                    <label>Manpower Skill*</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="text" name="Manpower_Skill[0]" placeholder="Manpower Skill" value="" required>
                                    </div>
                                </div> --}}
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 form-group">
                                    <label>
                                        Manpower Skill*
                                    </label>
                                    <select name="Manpower_Skill[0]" class="form-select form-select-sm js-example-matcher-start" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Color as $val)
                                        <option value="{{$val->id}}" {{isset($BOM->Color) && $BOM->Color==$val->id?'selected':''}}>{{$val->Color}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 form-group">
                                    <label>Manpower Count*</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="number" name="Manpower_Count[0]" placeholder="Manpower Count" value="" required>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 form-group">
                                    <label>Average Salary*</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="number" id="AverageSalary0" onchange="AllTotalAmount()" name="Average_Salary[0]" placeholder="Average Salary" value="" required>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <a href="javascript:;" id="ManpowerAppend" onclick="ManpowerAppend(1)" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            @endif
                            <div id="ManpowerFields"></div>
                            <hr>
                            @if(count($Machine_data)>0)
                            @php
                            $k=1;
                            @endphp
                            @foreach($Machine_data as $MachineVal)
                            <div class="row" id="MachineRemove{{$k}}">
                                <input type="hidden" name="MachineID[{{$k}}]" value="{{isset($MachineVal->id) && $MachineVal->id!=''?$MachineVal->id:''}}">
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 form-group">
                                    <label>Machine Name*</label>
                                    <select name="Machine_Specification[{{$k}}]" class="form-select form-select-sm js-example-matcher-start" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Machine_Specification as $val)
                                        <option value="{{$val->id}}" {{isset($MachineVal->Machine_Specification) && $MachineVal->Machine_Specification==$val->id?'selected':''}}>{{$val->Machine_Name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 form-group">
                                    <label>Production Capacity Per Shift*</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="number" name="Production_Capacity_Per_Shift[{{$k}}]" placeholder="Production Capacity Per Shift" value="{{isset($MachineVal->Production_Capacity_Per_Shift) && $MachineVal->Production_Capacity_Per_Shift!=''?$MachineVal->Production_Capacity_Per_Shift:''}}" required>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 form-group">
                                    <label>
                                        UOM*
                                    </label>
                                    <select name="UOM_Second[{{$k}}]" class="form-select form-select-sm js-example-matcher-start" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($UOM as $val)
                                        <option value="{{$val->id}}" {{isset($MachineVal->UOM_Second) && $MachineVal->UOM_Second==$val->id?'selected':''}}>{{$val->UOMs}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if($k==1)
                                <div class="col-sm-1">
                                    <a href="javascript:;" id="MachineAppend" onclick="MachineAppend({{isset($Machine_Count) && $Machine_Count==0?1:$Machine_Count+1}})" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                </div>
                                @else
                                <div class="col-sm-1">
                                    {{-- <a href="javascript:;" onclick="MachineRemove({{$k}})" class="btn btn-danger btn-sm mt-4">X</a> --}}
                                </div>
                                @endif
                            </div>
                            @php
                            $k++;
                            @endphp
                            @endforeach
                            @else
                            <div class="row">
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 form-group">
                                    <label>Machine Name*</label>
                                    <select name="Machine_Specification[0]" class="form-select form-select-sm js-example-matcher-start" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Machine_Specification as $val)
                                        <option value="{{$val->id}}">{{$val->Machine_Name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 form-group">
                                    <label>Production Capacity Per Shift*</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="number" name="Production_Capacity_Per_Shift[0]" placeholder="Production Capacity Per Shift" value="" required>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 form-group">
                                    <label>
                                        UOM*
                                    </label>
                                    <select name="UOM_Second[0]" class="form-select form-select-sm js-example-matcher-start" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($UOM as $val)
                                        <option value="{{$val->id}}">{{$val->UOMs}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-1">
                                    <a href="javascript:;" id="MachineAppend" onclick="MachineAppend(1)" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            @endif
                            <div id="MachineFields"></div>
                            <hr>
                            @if(count($Services_data)>0)
                            @php
                            $l=1;
                            @endphp
                            @foreach($Services_data as $ServicesVal)
                            <div class="row" id="ServicesRemove{{$l}}">
                                <input type="hidden" name="ServicesID[{{$l}}]" value="{{isset($ServicesVal->id) && $ServicesVal->id!=''?$ServicesVal->id:''}}">
                                <div class="col-sm-3 form-group">
                                    <label>Services*</label>
                                    <select name="Services[{{$l}}]" class="form-select form-select-sm js-example-matcher-start" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Services as $val)
                                        <option value="{{$val->id}}" {{isset($ServicesVal->Services) && $ServicesVal->Services==$val->id?'selected':''}}>{{$val->Services}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Amount.*</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="number" id="ServicesAmount{{$l}}" onchange="AllTotalAmount()" name="Services_Amount[{{$l}}]" placeholder="Amount" value="{{isset($ServicesVal->Services_Amount) && $ServicesVal->Services_Amount!=''?$ServicesVal->Services_Amount:''}}" required>
                                    </div>
                                </div>
                                @if($l==1)
                                <div class="col-sm-1">
                                    <a href="javascript:;" id="ServicesAppend" onclick="ServicesAppend({{isset($Services_Count) && $Services_Count==0?1:$Services_Count+1}})" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                </div>
                                @else
                                <div class="col-sm-1">
                                    {{-- <a href="javascript:;" onclick="ServicesRemove({{$l}})" class="btn btn-danger btn-sm mt-4">X</a> --}}
                                </div>
                                @endif
                            </div>
                            @php
                            $l++;
                            @endphp
                            @endforeach
                            @else
                            <div class="row">
                                <div class="col-xl-2 col-lg-3 co-md-6 col-sm-12 form-group">
                                    <label>Services*</label>
                                    <select name="Services[0]" class="form-select form-select-sm js-example-matcher-start" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Services as $val)
                                        <option value="{{$val->id}}">{{$val->Services}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-2 col-lg-3 co-md-6 col-sm-12 form-group">
                                    <label>Amount.*</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="number" id="ServicesAmount0" onchange="AllTotalAmount()" name="Services_Amount[0]" placeholder="Amount" value="" required>
                                    </div>
                                </div>
                                <div class="col-xl-1 col-lg-3 co-md-6 col-sm-12">
                                    <a href="javascript:;" id="ServicesAppend" onclick="ServicesAppend(1)" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            @endif
                            <div id="ServicesFields"></div>
                            <hr>
                            @if(count($Consumbles_data)>0)
                            @php
                            $m=1;
                            @endphp
                            @foreach($Consumbles_data as $ConsumblesVal)
                            <div class="row" id="ConsumblesRemove{{$m}}">
                                <input type="hidden" name="ConsumblesID[{{$m}}]" value="{{isset($ConsumblesVal->id) && $ConsumblesVal->id!=''?$ConsumblesVal->id:''}}">
                                <div class="col-sm-3 form-group">
                                    <label>Consumbles*</label>
                                    <select name="Consumbles[{{$m}}]" class="form-select form-select-sm js-example-matcher-start" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Consumbles as $val)
                                        <option value="{{$val->id}}" {{isset($ConsumblesVal->Consumbles) && $ConsumblesVal->Consumbles==$val->id?'selected':''}}>{{$val->Consumbles}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Amount*</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="number" id="ConsumblesAmount{{$m}}" onchange="AllTotalAmount()" name="Consumbles_Amount[{{$m}}]" placeholder="Amount" value="{{isset($ConsumblesVal->Consumbles_Amount) && $ConsumblesVal->Consumbles_Amount!=''?$ConsumblesVal->Consumbles_Amount:''}}" required>
                                    </div>
                                </div>
                                @if($m==1)
                                <div class="col-sm-1">
                                    <a href="javascript:;" id="ConsumblesAppend" onclick="ConsumblesAppend({{isset($Consumbles_Count) && $Consumbles_Count==0?1:$Consumbles_Count+1}})" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                </div>
                                @else
                                <div class="col-sm-1">
                                    {{-- <a href="javascript:;" onclick="ConsumblesRemove({{$m}})" class="btn btn-danger btn-sm mt-4">X</a> --}}
                                </div>
                                @endif
                            </div>
                            @php
                            $m++;
                            @endphp
                            @endforeach
                            @else
                            <div class="row">
                                <div class="col-xl-2 col-lg-3 co-md-6 col-sm-12 form-group">
                                    <label>Consumbles*</label>
                                    <select name="Consumbles[0]" class="form-select form-select-sm js-example-matcher-start" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Consumbles as $val)
                                        <option value="{{$val->id}}">{{$val->Consumbles}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-2 col-lg-3 co-md-6 col-sm-12 form-group">
                                    <label>Amount*</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="number" id="ConsumblesAmount0" onchange="AllTotalAmount()" name="Consumbles_Amount[0]" placeholder="Amount" value="" required>
                                    </div>
                                </div>
                                <div class="col-xl-1 col-lg-3 co-md-6 col-sm-12">
                                    <a href="javascript:;" id="ConsumblesAppend" onclick="ConsumblesAppend(1)" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            @endif
                            <div id="ConsumblesFields"></div>
                            <hr>
                            @if(count($Management_data)>0)
                            @php
                            $n = 1;
                            @endphp
                            @foreach($Management_data as $ManagementVal)
                            <div class="row" id="ManagementRemove{{$n}}">
                                <input type="hidden" name="ManagementID[{{$n}}]" value="{{isset($ManagementVal->id) && $ManagementVal->id!=''?$ManagementVal->id:''}}">
                                <div class="col-sm-3 form-group">
                                    <label>Management Expenses*</label>
                                    <select name="Management_Expenses[{{$n}}]" class="form-select form-select-sm js-example-matcher-start" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Management_Expenses as $val)
                                        <option value="{{$val->id}}" {{isset($ManagementVal->Management_Expenses) && $ManagementVal->Management_Expenses==$val->id?'selected':''}}>{{$val->Management_Expenses}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Amount*</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="number" id="ManagementExpensesAmount{{$n}}" onchange="AllTotalAmount()" name="Management_Expenses_Amount[{{$n}}]" placeholder="Amount" value="{{isset($ManagementVal->Management_Expenses_Amount) && $ManagementVal->Management_Expenses_Amount!=''?$ManagementVal->Management_Expenses_Amount:''}}" required>
                                    </div>
                                </div>
                                @if($n==1)
                                <div class="col-sm-1">
                                    <a href="javascript:;" id="ManagementAppend" onclick="ManagementAppend({{isset($Management_Count) && $Management_Count==0?1:$Management_Count+1}})" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                </div>
                                @else
                                <div class="col-sm-1">
                                    {{-- <a href="javascript:;" onclick="ManagementRemove({{$n}})" class="btn btn-danger btn-sm mt-4">X</a> --}}
                                </div>
                                @endif
                            </div>
                            @php
                            $n++;
                            @endphp
                            @endforeach
                            @else
                            <div class="row">
                                <div class="col-xl-2 col-lg-3 co-md-6 col-sm-12 form-group">
                                    <label>Management Expenses*</label>
                                    <select name="Management_Expenses[0]" class="form-select form-select-sm js-example-matcher-start" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Management_Expenses as $val)
                                        <option value="{{$val->id}}">{{$val->Management_Expenses}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-2 col-lg-3 co-md-6 col-sm-12 form-group">
                                    <label>Amount*</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="number" id="ManagementExpensesAmount0" onchange="AllTotalAmount()" name="Management_Expenses_Amount[0]" placeholder="Amount" value="" required>
                                    </div>
                                </div>
                                <div class="col-xl-1 col-lg-3 co-md-6 col-sm-12">
                                    <a href="javascript:;" id="ManagementAppend" onclick="ManagementAppend(1)" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            @endif
                            <div id="ManagementFields"></div>
                            <hr>
                            @if(count($Expenses_data)>0)
                            @php
                            $o = 1;
                            @endphp
                            @foreach($Expenses_data as $ExpensesVal)
                            <div class="row" id="ExpensesRemove{{$o}}">
                                <input type="hidden" name="ExpensesID[{{$o}}]" value="{{isset($ExpensesVal->id) && $ExpensesVal->id!=''?$ExpensesVal->id:''}}">
                                <div class="col-sm-3 form-group">
                                    <label>Other Expenses*</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="text" name="Other_Expenses[{{$o}}]" placeholder="Other Expenses" value="{{isset($ExpensesVal->Other_Expenses) && $ExpensesVal->Other_Expenses!=''?$ExpensesVal->Other_Expenses:''}}" required>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Amount*</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="number" id="OtherExpensesAmount{{$o}}" onchange="AllTotalAmount()" name="Other_Expenses_Amount[{{$o}}]" placeholder="Amount" value="{{isset($ExpensesVal->Other_Expenses_Amount) && $ExpensesVal->Other_Expenses_Amount!=''?$ExpensesVal->Other_Expenses_Amount:''}}" required>
                                    </div>
                                </div>
                                @if($o==1)
                                <div class="col-sm-1">
                                    <a href="javascript:;" id="ExpensesAppend" onclick="ExpensesAppend({{isset($Expenses_Count) && $Expenses_Count==0?1:$Expenses_Count+1}})" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                </div>
                                @else
                                <div class="col-sm-1">
                                    {{-- <a href="javascript:;" onclick="ExpensesRemove({{$o}})" class="btn btn-danger btn-sm mt-4">X</a> --}}
                                </div>
                                @endif
                            </div>
                            @php
                            $o++;
                            @endphp
                            @endforeach
                            @else
                            <div class="row">
                                <div class="col-xl-2 col-lg-3 co-md-6 col-sm-12 form-group">
                                    <label>Other Expenses*</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="text" name="Other_Expenses[0]" placeholder="Other Expenses" value="" required>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-3 co-md-6 col-sm-12 form-group">
                                    <label>Amount*</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="number" id="OtherExpensesAmount0" onchange="AllTotalAmount()" name="Other_Expenses_Amount[0]" placeholder="Amount" value="" required>
                                    </div>
                                </div>
                                <div class="col-xl-1 col-lg-3 co-md-6 col-sm-12">
                                    <a href="javascript:;" id="ExpensesAppend" onclick="ExpensesAppend(1)" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            @endif
                            <div id="ExpensesFields"></div>
                            <hr>
                            @if(count($Transport_data)>0)
                            @php
                            $p = 1;
                            @endphp
                            @foreach($Transport_data as $TransportVal)
                            <div class="row">
                                <input type="hidden" name="TransportID[{{$p}}]" value="{{isset($TransportVal->id) && $TransportVal->id!=''?$TransportVal->id:''}}">
                                <div class="col-sm-3 form-group">
                                    <label>Transport*</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="number" id="TransportAmount{{$p}}" onchange="AllTotalAmount()" name="Transport[{{$p}}]" placeholder="Amount" value="{{isset($TransportVal->Transport) && $TransportVal->Transport!=''?$TransportVal->Transport:''}}" required>
                                    </div>
                                </div>
                                @if($p==1)
                                <div class="col-sm-1">
                                    <a href="javascript:;" id="TransportAppend" onclick="TransportAppend({{isset($Transport_Count) && $Transport_Count==0?1:$Transport_Count+1}})" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                </div>
                                @else
                                <div class="col-sm-1">
                                    {{-- <a href="javascript:;" onclick="TransportRemove({{$p}})" class="btn btn-danger btn-sm mt-4">X</a> --}}
                                </div>
                                @endif
                            </div>
                            @php
                            $p++;
                            @endphp
                            @endforeach
                            @else
                            <div class="row">
                                <div class="col-sm-3 form-group">
                                    <label>Transport*</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="number" id="TransportAmount0" onchange="AllTotalAmount()" name="Transport[0]" placeholder="Amount" value="" required>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <a href="javascript:;" id="TransportAppend" onclick="TransportAppend(1)" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            @endif
                            <div id="TransportFields"></div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-6 form-group">
                                    <label>Total Amount*</label>
                                    <div class="field-wrap">
                                        <input readonly class="form-control form-control-sm" type="number" id="AllTotalAmounts" name="All_Total_Amount" placeholder="Total Amount" value="{{isset($BOM->All_Total_Amount) && $BOM->All_Total_Amount!=''?$BOM->All_Total_Amount:''}}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label for="State">Remarks:</label>
                                    <input type="text" name="remarks" cols="30" rows="5" class="form-control form-control-sm" placeholder="Remarks" value="{{isset($BOM->remarks) && $BOM->remarks!=''?$BOM->remarks:''}}">
                                </div>
                            </div>
                            <div style="overflow:auto;">
                                <div style="float:right;">
                                    <button type="button" id="draft" class="btn btn1 float-right" style="margin: 5px;">Draft & Save</button>
                                    <a href="" class="btn btn1 float-right" style="margin: 5px; display:{{isset($BOM->id) && $BOM->id != ''?'none':'block'}}">Clear All</a>
                                    <button type="submit" id="submitBtn" class="btn btn1 float-right" style="margin: 5px;">Submit</button>
                                </div>
                            </div>
                        </form>
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
        activeclass(18, 1);
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
    function MaterialAppend(i) {
        $('#MaterialFields').append('<div class="row" id="MaterialRemove' + i + '"> <div class="col-xl-1 col-lg-2 col-md-6 col-sm-12 form-group"> <label>Raw Material* </lable> <select id="Material' + i + '" name="Material[' + i + ']" class="form-select form-select-sm js-example-matcher-start" onclick="RawMaterial(' + i + ')" required> <option value="" selected disabled>Select</option> @foreach($Material as $val) <option value="{{$val["materialID"]}}">{{$val["material_Name"]}}</option> @endforeach </select><span class="error-message" style="color: red; display: none;"></span> </div><div class="col-xl-1 col-lg-2 col-md-6 col-sm-12 form-group"><label>HSN Code*</label><div class="field-wrap"> <input readonly class="form-control form-control-sm" type="text" id="HSN_Code_Second' + i + '" name="HSN_Code_Second[' + i + ']" placeholder="HSN Code" value="" required></div> </div><div class="col-xl-1 col-lg-2 col-md-6 col-sm-12 form-group"> <label> UOM* </label> <input readonly class="form-control form-control-sm" type="text" id="uoms' + i + '" name="UOM[' + i + ']" value="" placeholder="UOM"   required> </div><div class="col-xl-1 col-lg-2 col-md-6 col-sm-12 form-group"> <label>Material QTY*</label> <div class="field-wrap"> <input class="form-control form-control-sm" type="text" id="MaterialQTY' + i + '" oninput="validateNumericInput(this);" onchange="TotalQTY(' + i + ')" name="Material_QTY[' + i + ']" placeholder="Material QTY" value="" required> </div></div><div class="col-xl-1 col-lg-2 col-md-6 col-sm-12 form-group"> <label>Scarp QTY.*</label> <div class="field-wrap"> <input class="form-control form-control-sm" type="text" id="ScarpQTY' + i + '" oninput="validateNumericInput(this);" onchange="TotalQTY(' + i + ')" name="Scarp_QTY[' + i + ']" placeholder="Scarp QTY" value="" required> </div></div><div class="col-xl-1 col-lg-2 col-md-6 col-sm-12 form-group"> <label>Total QTY.*</label> <div class="field-wrap"> <input readonly class="form-control form-control-sm" type="text" id="TotalQTY' + i + '" name="Total_QTY[' + i + ']" placeholder="Total QTY" value="" required> </div></div><div class="col-xl-1 col-lg-2 col-md-6 col-sm-12 form-group"> <label>Basic Rate*</label> <div class="field-wrap"> <input class="form-control form-control-sm" type="text" id="BasicAmount' + i + '" oninput="validateNumericInput(this);" onchange="TotalBasicAmount(' + i + ')" name="Basic_Amount_unit[' + i + ']" placeholder="Basic Rate" value="" required> </div></div><div class="col-xl-1 col-lg-2 col-md-6 col-sm-12 form-group"> <label>Total Rate*</label> <div class="field-wrap"> <input readonly class="form-control form-control-sm" type="text" id="TotalBasicAmount' + i + '" name="Total_Basic_Amount[' + i + ']" placeholder="Total Rate" value="" required> </div></div><div class="col-xl-1 col-lg-2 col-md-6 col-sm-12 form-group"> <label>GST%*</label> <select id="GSTPercentage' + i + '" onchange="MaterialTotalAmount(' + i + ')" name="GST_Percentage[' + i + ']" class="form-select form-select-sm js-example-matcher-start" required><option value="" selected disabled>Select</option> @foreach($GST_Percentage as $val)<option value="{{$val->GST_Percentage}}">{{$val->GST_Percentage}}</option> @endforeach</select></div><div class="col-xl-1 col-lg-2 col-md-6 col-sm-12 form-group"> <label>GST Value*</label> <div class="field-wrap"> <input readonly class="form-control form-control-sm" type="text" id="GSTValue' + i + '" name="GST_Value[' + i + ']" placeholder="GST Value" value="" required> </div></div><div class="col-xl-1 col-lg-2 col-md-6 col-sm-12 form-group"> <label>Total Amount*</label> <div class="field-wrap"> <input readonly class="form-control form-control-sm" type="text" onchange="AllTotalAmount()" id="MaterialTotalAmount' + i + '" name="Total_Amount[' + i + ']" placeholder="Total Amount" value="" required> </div></div><div class="col-sm-1"> <a href="javascript:;"  onclick="MaterialRemove(' + i + ')" class="btn btn-danger btn-sm mt-4">X</a> </div></div>');
        i++;
        $("#MaterialAppend").attr("onclick", 'MaterialAppend(' + i + ')');
        AppendSelect2();
    }

    function MaterialRemove(id) {
        $("#MaterialRemove" + id).remove();
    }
</script>
<script>
    function ManpowerAppend(i) {
        $('#ManpowerFields').append('<div class="row" id="ManpowerRemove' + i + '"> <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12 form-group"> <label>Manpower Skill*</label> <div class="field-wrap"><select name="Manpower_Skill[' + i + ']" class="form-select form-select-sm js-example-matcher-start" required><option value="" selected disabled>Select</option>@foreach($Color as $val)<option value="{{$val->id}}" {{isset($BOM->Color) && $BOM->Color==$val->id?'selected':''}}>{{$val->Color}}</option>@endforeach</select></div></div><div class="col-xl-2 col-lg-2 col-md-3 col-sm-12 form-group"> <label>Manpower Count*</label> <div class="field-wrap"> <input class="form-control form-control-sm" type="number" name="Manpower_Count[' + i + ']" placeholder="Manpower Count" value="" required> </div></div><div class="col-xl-2 col-lg-2 col-md-3 col-sm-12 form-group"> <label>Average Salary*</label> <div class="field-wrap"> <input class="form-control form-control-sm" type="number" id="AverageSalary' + i + '" onchange="AllTotalAmount()" name="Average_Salary[' + i + ']" placeholder="Average Salary" value="" required> </div></div><div class="col-sm-1"> <a href="javascript:;"  onclick="ManpowerRemove(' + i + ')" class="btn btn-danger btn-sm mt-4">X</a> </div></div>');
        i++;
        $("#ManpowerAppend").attr("onclick", 'ManpowerAppend(' + i + ')');
        AppendSelect2();
    }

    function ManpowerRemove(id) {
        $("#ManpowerRemove" + id).remove();
    }
</script>
<script>
    function MachineAppend(i) {
        $('#MachineFields').append('<div class="row" id="MachineRemove' + i + '"> <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12 form-group"> <label>Machine Name*</label> <select name="Machine_Specification[' + i + ']" class="form-select form-select-sm js-example-matcher-start" required> <option value="" selected disabled>Select</option> @foreach($Machine_Specification as $val) <option value="{{$val->id}}">{{$val->Machine_Name}}</option> @endforeach </select> </div><div class="col-xl-2 col-lg-2 col-md-3 col-sm-12 form-group"> <label>Production Capacity Per Shift*</label> <div class="field-wrap"> <input class="form-control form-control-sm" type="number" name="Production_Capacity_Per_Shift[' + i + ']" placeholder="Production Capacity Per Shift" value="" required> </div></div><div class="col-xl-2 col-lg-2 col-md-3 col-sm-12 form-group"> <label> UOM* </label> <select name="UOM_Second[' + i + ']" class="form-select form-select-sm js-example-matcher-start" required> <option value="" selected disabled>Select</option> @foreach($UOM as $val) <option value="{{$val->id}}">{{$val->UOMs}}</option> @endforeach </select> </div><div class="col-sm-1"> <a href="javascript:;"  onclick="MachineRemove(' + i + ')" class="btn btn-danger btn-sm mt-4">X</a> </div></div>');
        i++;
        $("#MachineAppend").attr("onclick", 'MachineAppend(' + i + ')');
        AppendSelect2();
    }

    function MachineRemove(id) {
        $("#MachineRemove" + id).remove();
    }
</script>
<script>
    function ServicesAppend(i) {
        $('#ServicesFields').append('<div class="row" id="ServicesRemove' + i + '"> <div class="col-xl-2 col-lg-3 co-md-6 col-sm-12 form-group"> <label>Services*</label> <select name="Services[' + i + ']" class="form-select form-select-sm js-example-matcher-start" required> <option value="" selected disabled>Select</option> @foreach($Services as $val) <option value="{{$val->id}}">{{$val->Services}}</option> @endforeach </select> </div><div class="col-xl-2 col-lg-3 co-md-6 col-sm-12 form-group"> <label>Amount.*</label> <div class="field-wrap"> <input class="form-control form-control-sm" type="number" id="ServicesAmount' + i + '" onchange="AllTotalAmount()" name="Services_Amount[' + i + ']" placeholder="Amount" value="" required> </div></div><div class="col-sm-1"> <a href="javascript:;"  onclick="ServicesRemove(' + i + ')" class="btn btn-danger btn-sm mt-4">X</a> </div></div>');
        i++;
        $("#ServicesAppend").attr("onclick", 'ServicesAppend(' + i + ')');
        AppendSelect2();
    }

    function ServicesRemove(id) {
        $("#ServicesRemove" + id).remove();
    }
</script>
<script>
    function ConsumblesAppend(i) {
        $('#ConsumblesFields').append('<div class="row" id="ConsumblesRemove' + i + '"> <div class="col-xl-2 col-lg-3 co-md-6 col-sm-12 form-group"> <label>Consumbles*</label> <select name="Consumbles[' + i + ']" class="form-select form-select-sm js-example-matcher-start" required> <option value="" selected disabled>Select</option> @foreach($Consumbles as $val) <option value="{{$val->id}}">{{$val->Consumbles}}</option> @endforeach </select> </div><div class="col-xl-2 col-lg-3 co-md-6 col-sm-12 form-group"> <label>Amount*</label> <div class="field-wrap"> <input class="form-control form-control-sm" type="number" id="ConsumblesAmount' + i + '" onchange="AllTotalAmount()" name="Consumbles_Amount[' + i + ']" placeholder="Amount" value="" required> </div></div><div class="col-sm-1"> <a href="javascript:;"  onclick="ConsumblesRemove(' + i + ')" class="btn btn-danger btn-sm mt-4">X</a> </div></div>');
        i++;
        $("#ConsumblesAppend").attr("onclick", 'ConsumblesAppend(' + i + ')');
        AppendSelect2();
    }

    function ConsumblesRemove(id) {
        $("#ConsumblesRemove" + id).remove();
    }
</script>
<script>
    function ManagementAppend(i) {
        $('#ManagementFields').append('<div class="row" id="ManagementRemove' + i + '"> <div class="col-xl-2 col-lg-3 co-md-6 col-sm-12 form-group"> <label>Management Expenses*</label> <select name="Management_Expenses[' + i + ']" class="form-select form-select-sm js-example-matcher-start" required> <option value="" selected disabled>Select</option> @foreach($Management_Expenses as $val) <option value="{{$val->id}}">{{$val->Management_Expenses}}</option> @endforeach </select> </div><div class="col-xl-2 col-lg-3 co-md-6 col-sm-12 form-group"> <label>Amount*</label> <div class="field-wrap"> <input class="form-control form-control-sm" type="number" id="ManagementExpensesAmount' + i + '" onchange="AllTotalAmount()" name="Management_Expenses_Amount[' + i + ']" placeholder="Amount" value="" required> </div></div><div class="col-sm-1"> <a href="javascript:;"  onclick="ManagementRemove(' + i + ')" class="btn btn-danger btn-sm mt-4">X</a> </div></div>');
        i++;
        $("#ManagementAppend").attr("onclick", 'ManagementAppend(' + i + ')');
        AppendSelect2();
    }

    function ManagementRemove(id) {
        $("#ManagementRemove" + id).remove();
    }
</script>
<script>
    function ExpensesAppend(i) {
        $('#ExpensesFields').append('<div class="row" id="ExpensesRemove' + i + '"> <div class="col-xl-2 col-lg-3 co-md-6 col-sm-12 form-group"> <label>Other Expenses*</label> <div class="field-wrap"> <input class="form-control form-control-sm" type="text" name="Other_Expenses[' + i + ']" placeholder="Other Expenses" value="" required> </div></div><div class="col-xl-2 col-lg-3 co-md-6 col-sm-12 form-group"> <label>Amount*</label> <div class="field-wrap"> <input class="form-control form-control-sm" type="number" id="OtherExpensesAmount' + i + '" onchange="AllTotalAmount()" name="Other_Expenses_Amount[' + i + ']" placeholder="Amount" value="" required> </div></div><div class="col-sm-1"> <a href="javascript:;"  onclick="ExpensesRemove(' + i + ')" class="btn btn-danger btn-sm mt-4">X</a></div></div>');
        i++;
        $("#ExpensesAppend").attr("onclick", 'ExpensesAppend(' + i + ')');
        AppendSelect2();
    }

    function ExpensesRemove(id) {
        $("#ExpensesRemove" + id).remove();
    }
</script>
<script>
    function TransportAppend(i) {
        $('#TransportFields').append('<div class="row" id="TransportRemove' + i + '"> <div class="col-sm-3 form-group"> <label>Transport*</label> <div class="field-wrap"> <input class="form-control form-control-sm" type="number" id="TransportAmount' + i + '" onchange="AllTotalAmount()" name="Transport[' + i + ']" placeholder="Amount " value="" required> </div></div><div class="col-sm-1"> <a href="javascript:;" onclick="TransportRemove(' + i + ')" class="btn btn-danger btn-sm mt-4">X</a> </div></div>');
        i++;
        $("#TransportAppend").attr("onclick", 'TransportAppend(' + i + ')');
        AppendSelect2();
    }

    function TransportRemove(id) {
        $("#TransportRemove" + id).remove();
    }
</script>
<script>
    $('#product').change(function() {
        var productID = $(this).val();
        $('#subproduct').empty().prop('disabled', true);
        $('#subsubproduct').empty().prop('disabled', true);

        if (productID) {
            $.ajax({
                url: "{{url('BOM/get-subproduct')}}" + '/' + productID,
                type: 'GET',
                success: function(response) {
                    var options = '';
                    options += '<option value="" selected disabled>Select</option>';
                    $.each(response, function(index, subproduct) {
                        options += '<option value="' + subproduct.id + '">' + subproduct.sub_product + '</option>';
                    });
                    $('#subproduct').html(options).prop('disabled', false);
                }
            });
        }
    });

    $('#subproduct').change(function() {
        var subproductId = $(this).val();
        $('#subsubproduct').empty().prop('disabled', true);

        if (subproductId) {
            $.ajax({
                url: "{{url('BOM/get-subsubproduct')}}" + '/' + subproductId,
                type: 'GET',
                success: function(response) {
                    var options = '';
                    options += '<option value="" selected disabled>Select</option>';
                    $.each(response, function(index, subsubproduct) {
                        options += '<option value="' + subsubproduct.id + '">' + subsubproduct.sub_sub_product + '</option>';
                    });
                    $('#subsubproduct').html(options).prop('disabled', false);
                }
            });
        }
    });
</script>
<script>
    function TotalQTY(id) {
        var materialQTY = parseFloat($('#MaterialQTY' + id).val()) || 0;
        var scrapQTY = parseFloat($('#ScarpQTY' + id).val()) || 0;

        var totalQTY = materialQTY + scrapQTY;

        $('#TotalQTY' + id).val(totalQTY);
        TotalBasicAmount(id)
    }
</script>
<script>
    function TotalBasicAmount(id) {
        var TotalQTY = parseFloat($('#TotalQTY' + id).val()) || 0;
        var BasicAmount = parseFloat($('#BasicAmount' + id).val()) || 0;

        var totalBasicAmount = BasicAmount * TotalQTY;

        $('#TotalBasicAmount' + id).val(totalBasicAmount);
        MaterialTotalAmount(id)
    }
</script>
<script>
    function MaterialTotalAmount(id) {

        var TotalBasicAmount = parseFloat($('#TotalBasicAmount' + id).val()) || 0;
        var gSTPercentage = parseFloat($('#GSTPercentage' + id).val()) || 0;

        var Gstvalue = (TotalBasicAmount * gSTPercentage) / 100;

        var materialTotalAmount = TotalBasicAmount + Gstvalue;

        $('#GSTValue' + id).val(Gstvalue);

        $('#MaterialTotalAmount' + id).val(materialTotalAmount);
        AllTotalAmount()
    }
</script>
<script>
    function AllTotalAmount() {
        var allTotalAmount = 0;

        ['Total_Amount', 'Average_Salary', 'Services_Amount', 'Consumbles_Amount', 'Management_Expenses_Amount', 'Other_Expenses_Amount', 'Transport'].forEach(function(fieldName) {
            $('input[name^=' + fieldName + ']').each(function() {
                var value = parseFloat($(this).val()) || 0;
                allTotalAmount += value;
            });
        });

        $('#AllTotalAmounts').val(allTotalAmount);
    }

    ['Total_Amount', 'Average_Salary', 'Services_Amount', 'Consumbles_Amount', 'Management_Expenses_Amount', 'Other_Expenses_Amount', 'Transport'].forEach(function(fieldName) {
        $('input[name^=' + fieldName + ']').on('input', function() {
            AllTotalAmount();
        });
    });
</script>
<script>
    function RawMaterial(i) {
        $('#Material' + i).on('change', function() {
            var MaterialId = $(this).val();

            $.ajax({
                url: "{{url('RawMaterial/MaterialData')}}" + '/' + MaterialId,
                type: 'GET',
                data: {
                    MaterialId: MaterialId
                },
                success: function(data) {
                    $('#HSN_Code_Second' + i).val(data.data.HSN_Code);
                    $('#uoms' + i).val(data.data.UOM);
                }
            });
        });
    }
</script>
<script>
    $(document).ready(function() {
        $('#draft, #submitBtn').on('click', function() {

            $('select[name^="UOM"]').prop('disabled', false);

            var hasDuplicates = checkDuplicateMaterial();

            if (hasDuplicates) {
                return false;
            }

            if ($(this).attr('id') === 'submitBtn') {
                if (!checkRequiredFields()) {
                    alert('Please fill in all required fields.');
                    return false;
                }
            }

            $('#Form').submit();
        });
    });

    $(document).ready(function() {
    $('#Form').on('submit', function(e) {
            var isValid = this.checkValidity();

            if (isValid) {
                // Proceed with form submission or other actions
            } else {
                // Prevent form submission
                e.preventDefault();
                e.stopPropagation();
            }

            this.classList.add('was-validated');
        });
    });

    function checkDuplicateMaterial() {
        var selectedMaterials = [];
        var hasDuplicate = false;

        $('select[name^="Material"]').each(function() {
            var materialValue = $(this).val();

            if (selectedMaterials.includes(materialValue)) {
                $(this).siblings('.error-message').text('Material is Already In Use').show();
                hasDuplicate = true;
            } else {
                $(this).siblings('.error-message').text('').hide();
                selectedMaterials.push(materialValue);
            }
        });

        return hasDuplicate;
    }

    function checkRequiredFields() {

        var requiredFields = $('input[required], select[required], textarea[required]');

        for (var i = 0; i < requiredFields.length; i++) {
            if (!requiredFields[i].value.trim()) {
                return false;
            }
        }

        return true;
    }

    function validateNumericInput(input) {
    var val = input.value.trim();

        if (val === '') {
            input.setCustomValidity('Please enter a number.');
        } else if (isNaN(parseFloat(val))) {
            input.setCustomValidity('Please enter a valid number.');
        } else {
            input.setCustomValidity('');
        }
    }
</script>
@endpush
