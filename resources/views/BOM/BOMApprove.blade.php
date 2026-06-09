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
                <a href="{{url('BOM/BOMApproveList')}}" class="btn btn-info mr-1 btn-sm"> <i class="fa fa-arrow-left"></i></a>
                <a href="{{url('BOM/BOMApproveList')}}" class="btn btn-info btn-sm"> <i class="fa fa-home"></i></a>
            </div>
            <div class="row">
                <div class="container-fluid">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 border">
                        <form action="#" method="POST">
                            @csrf
                            <input disabled class="form-control" type="hidden" name="edit" value="{{isset($BOM->id) && $BOM->id!=''?$BOM->id:''}}">
                            <div class="row" id="adaaishhhh">
                                {{-- <div class="col-sm-3 form-group">
                                    <label>
                                        Organization*
                                    </label>
                                    <select disabled name="Organization" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Organization as $val)
                                        <option value="{{$val->id}}" {{isset($BOM->Organization) && $BOM->Organization==$val->id?'selected':''}}>{{$val->organization}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>
                                        Manufacturing Unit*
                                    </label>
                                    <select disabled name="Manufacturing_Unit" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Manufacturing_unit as $val)
                                        <option value="{{$val->id}}" {{isset($BOM->Manufacturing_Unit) && $BOM->Manufacturing_Unit==$val->id?'selected':''}}>{{$val->Manufacturing_unit}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>
                                        Plant Name*
                                    </label>
                                    <select disabled name="Plant_Name" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Plant_Name as $val)
                                        <option value="{{$val->id}}" {{isset($BOM->Plant_Name) && $BOM->Plant_Name==$val->id?'selected':''}}>{{$val->plant_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>
                                        Category*
                                    </label>
                                    <select disabled name="Category" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Category as $val)
                                        <option value="{{$val->id}}" {{isset($BOM->Category) && $BOM->Category==$val->id?'selected':''}}>{{$val->category}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>
                                        Product*
                                    </label>
                                    <select disabled name="Product" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Product as $val)
                                        <option value="{{$val->id}}" {{isset($BOM->Product) && $BOM->Product==$val->id?'selected':''}}>{{$val->product}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>
                                        Sub Product*
                                    </label>
                                    <select disabled name="Sub_Product" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Sub_Product as $val)
                                        <option value="{{$val->id}}" {{isset($BOM->Sub_Product) && $BOM->Sub_Product==$val->id?'selected':''}}>{{$val->sub_product}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>
                                        Sub Sub Product*
                                    </label>
                                    <select disabled name="Sub_Sub_Product" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Sub_Sub_Product as $val)
                                        <option value="{{$val->id}}" {{isset($BOM->Sub_Sub_Product) && $BOM->Sub_Sub_Product==$val->id?'selected':''}}>{{$val->sub_sub_product}}</option>
                                        @endforeach
                                    </select>
                                </div> --}}

                                <div class="col-xl-2 col-md-3 col-md-6 col-sm-12 form-group">
                                    <label>
                                        Finished Good(FG)*
                                        </lable>
                                        <select disabled name="Raw_Material_FG" class="form-select form-select-sm js-example-matcher-start" id="RawMaterial00" onclick="Material(0,0)" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($Raw_Material as $val)
                                            <option value="{{$val->RawMaterial->id}}" {{isset($BOM->Raw_Material_FG) && $BOM->Raw_Material_FG==$val->RawMaterial->id?'selected':''}}>{{$val->RawMaterial->material_name}}</option>
                                            @endforeach
                                        </select>
                                        <span class="error-message" style="color: red; display: none;"></span>
                                </div>
                                {{-- <div class="col-sm-3 form-group">
                                    <label>
                                        Manpower Skill*
                                    </label>
                                    <select disabled name="Color" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Color as $val)
                                        <option value="{{$val->id}}" {{isset($BOM->Color) && $BOM->Color==$val->id?'selected':''}}>{{$val->Color}}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                <div class="col-xl-2 col-md-3 col-md-6 col-sm-12 form-group">
                                    <label>HSN Code(FG)*</label>
                                    <div class="field-wrap">
                                        <input readonly class="form-control form-control-sm" type="number" id="HSNCode00" name="HSN_Code_FG" placeholder="HSN Code(FG)" value="{{isset($BOM->HSN_Code_FG) && $BOM->HSN_Code_FG!=''?$BOM->HSN_Code_FG:''}}" required>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-md-3 col-md-6 col-sm-12 form-group">
                                    <label>UOM(FG)</label>
                                    <div class="field-wrap">
                                        <input readonly class="form-control form-control-sm" type="text" id="uom00" name="UOMFG" placeholder="UOM(FG)" value="{{isset($BOM->UOMFG) && $BOM->UOMFG!=''?$BOM->UOMFG:''}}" required>
                                        {{-- <select disabled name="UOMFG" id="uom00" class="form-select form-select-sm js-example-matcher-start" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($UOM as $val)
                                            <option value="{{$val->id}}" {{isset($BOM->UOMFG) && $BOM->UOMFG==$val->id?'selected':''}}>{{$val->UOMFG}}</option>
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
                                <input disabled type="hidden" name="MaterialID[{{$i}}]" value="{{isset($MaterialVal->id) && $MaterialVal->id!=''?$MaterialVal->id:''}}">
                                <div class="col-xl-1 col-md-3 col-md-6 col-sm-12 form-group">
                                    <label>Raw Material*</lable>
                                        <select disabled id="Material{{$i}}" name="Material[{{$i}}]" class="form-select form-select-sm js-example-matcher-start" onclick="RawMaterial({{$i}})" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($Material as $val)
                                            <option value="{{$val['materialID']}}" {{isset($MaterialVal->Material) && $MaterialVal->Material==$val['materialID']?'selected':''}}>{{$val['materialName']}}</option>
                                            @endforeach
                                        </select>
                                        <span class="error-message" style="color: red; display: none;"></span>
                                </div>
                                <div class="col-xl-1 col-md-3 col-md-6 col-sm-12 form-group">
                                    <label>HSN Code*</label>
                                    <div class="field-wrap">
                                        <input readonly class="form-control form-control-sm" type="number" id="HSN_Code_Second{{$i}}" name="HSN_Code_Second[{{$i}}]" placeholder="HSN Code" value="{{isset($MaterialVal->HSN_Code_Second) && $MaterialVal->HSN_Code_Second!=''?$MaterialVal->HSN_Code_Second:''}}" required>
                                    </div>
                                </div>
                                <div class="col-xl-1 col-md-3 col-md-6 col-sm-12 form-group">
                                    <label>
                                        UOM*
                                    </label>
                                    <div class="field-wrap">
                                        <input readonly class="form-control form-control-sm" type="text"  name="UOM[{{$i}}]" placeholder="HSN Code" value="{{isset($MaterialVal->UOM) && $MaterialVal->UOM!=''?$MaterialVal->UOM:''}}" required>
                                    </div>
                                    {{-- <select disabled id="uoms{{$i}}" name="UOM[{{$i}}]" class="form-select form-select-sm js-example-matcher-start" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($UOM as $val)
                                        <option value="{{$val->id}}" {{isset($MaterialVal->UOM) && $MaterialVal->UOM==$val->id?'selected':''}}>{{$val->UOMs}}</option>
                                        @endforeach
                                    </select> --}}
                                </div>
                                {{-- <div class="col-sm-3 form-group">
                                    <label>
                                        UOM*
                                    </label>
                                    <select disabled name="UOM[{{$i}}]" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($UOM as $val)
                                        <option value="{{$val->id}}" {{isset($MaterialVal->UOM) && $MaterialVal->UOM==$val->id?'selected':''}}>{{$val->UOMs}}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                <div class="col-xl-1 col-md-3 col-md-6 col-sm-12 form-group">
                                    <label>Material QTY*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text" name="Material_QTY[{{$i}}]" placeholder="Material QTY" value="{{isset($MaterialVal->Material_QTY) && $MaterialVal->Material_QTY!=''?$MaterialVal->Material_QTY:''}}" required>
                                    </div>
                                </div>
                                <div class="col-xl-1 col-md-3 col-md-6 col-sm-12 form-group">
                                    <label>Scarp QTY.*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text" name="Scarp_QTY[{{$i}}]" placeholder="Scarp QTY" value="{{isset($MaterialVal->Scarp_QTY) && $MaterialVal->Scarp_QTY!=''?$MaterialVal->Scarp_QTY:''}}" required>
                                    </div>
                                </div>
                                <div class="col-xl-1 col-md-3 col-md-6 col-sm-12 form-group">
                                    <label>Total QTY.*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text" name="Total_QTY[{{$i}}]" placeholder="Total QTY" value="{{isset($MaterialVal->Total_QTY) && $MaterialVal->Total_QTY!=''?$MaterialVal->Total_QTY:''}}" required>
                                    </div>
                                </div>
                                <div class="col-xl-1 col-md-3 col-md-6 col-sm-12 form-group">
                                    <!-- <label>Basic Amount/unit*</label> -->
                                    <label>Basic Rate*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text" name="Basic_Amount_unit[{{$i}}]" placeholder="Basic Amount/unit" value="{{isset($MaterialVal->Basic_Amount_unit) && $MaterialVal->Basic_Amount_unit!=''?$MaterialVal->Basic_Amount_unit:''}}" required>
                                    </div>
                                </div>
                                <div class="col-xl-1 col-md-3 col-md-6 col-sm-12 form-group">
                                    <!-- <label>Total Basic Amount*</label> -->
                                    <label>Total Rate*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text" name="Total_Basic_Amount[{{$i}}]" placeholder="Total Basic Amount" value="{{isset($MaterialVal->Total_Basic_Amount) && $MaterialVal->Total_Basic_Amount!=''?$MaterialVal->Total_Basic_Amount:''}}" required>
                                    </div>
                                </div>
                                <div class="col-xl-1 col-md-3 col-md-6 col-sm-12 form-group">
                                    <label>GST%*</label>
                                    <select disabled name="GST_Percentage[{{$i}}]" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($GST_Percentage as $val)
                                        <option value="{{$val->GST_Percentage}}" {{isset($MaterialVal->GST_Percentage) && $MaterialVal->GST_Percentage==$val->GST_Percentage?'selected':''}}>{{$val->GST_Percentage}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-1 col-md-3 col-md-6 col-sm-12 form-group">
                                    <label>GST Value*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text" name="GST_Value[{{$i}}]" placeholder="GST Value" value="{{isset($MaterialVal->GST_Value) && $MaterialVal->GST_Value!=''?$MaterialVal->GST_Value:''}}" required>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-md-3 col-md-6 col-sm-12 form-group">
                                    <label>Total Amount*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text" name="Total_Amount[{{$i}}]" placeholder="Total Amount" value="{{isset($MaterialVal->Total_Amount) && $MaterialVal->Total_Amount!=''?$MaterialVal->Total_Amount:''}}" required>
                                    </div>
                                </div>
                            </div>
                            @php
                            $i++;
                            @endphp
                            @endforeach
                            @else
                            <div class="row">
                                <div class="col-sm-3 form-group">
                                    <label>
                                        Material.*
                                    </label>
                                    <select disabled name="Material[0]" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Material as $val)
                                        <option value="{{$val->id}}">{{$val->Material}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>
                                        Code*
                                    </label>
                                    <select disabled name="Code[0]" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Code as $val)
                                        <option value="{{$val->id}}">{{$val->Code}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>
                                        UOM*
                                    </label>
                                    <select disabled name="UOM[0]" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($UOM as $val)
                                        <option value="{{$val->id}}">{{$val->UOMs}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Material QTY*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text" name="Material_QTY[0]" placeholder="Material QTY" value="" required>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Scarp QTY.*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text" name="Scarp_QTY[0]" placeholder="Scarp QTY" value="" required>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Total QTY.*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text" name="Total_QTY[0]" placeholder="Total QTY" value="" required>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Basic Amount/unit*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text" name="Basic_Amount_unit[0]" placeholder="Basic Amount/unit" value="" required>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Total Basic Amount*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text" name="Total_Basic_Amount[0]" placeholder="Total Basic Amount" value="" required>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>GST%*</label>
                                    <select disabled name="GST_Percentage[0]" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($GST_Percentage as $val)
                                        <option value="{{$val->GST_Percentage}}">{{$val->GST_Percentage}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>GST Value*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text" name="GST_Value[0]" placeholder="GST Value" value="" required>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Total Amount*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text" name="Total_Amount[0]" placeholder="Total Amount" value="" required>
                                    </div>
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
                                <input disabled type="hidden" name="ManpowerID[{{$j}}]" value="{{isset($ManpowerVal->id) && $ManpowerVal->id!=''?$ManpowerVal->id:''}}">
                                <div class="col-xl-2 col-md-3 col-md-6 col-sm-12 form-group">
                                    <label>Manpower Skill*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text" name="Manpower_Skill[{{$j}}]" placeholder="Manpower Skill" value="{{isset($ManpowerVal->Color) && $ManpowerVal->Color!=''?$ManpowerVal->Color:''}}" required>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-md-3 col-md-6 col-sm-12 form-group">
                                    <label>Manpower Count*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text" name="Manpower_Count[{{$j}}]" placeholder="Manpower Count" value="{{isset($ManpowerVal->Manpower_Count) && $ManpowerVal->Manpower_Count!=''?$ManpowerVal->Manpower_Count:''}}" required>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-md-3 col-md-6 col-sm-12 form-group">
                                    <label>Average Salary*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text" name="Average_Salary[{{$j}}]" placeholder="Average Salary" value="{{isset($ManpowerVal->Average_Salary) && $ManpowerVal->Average_Salary!=''?$ManpowerVal->Average_Salary:''}}" required>
                                    </div>
                                </div>
                            </div>
                            @php
                            $j++;
                            @endphp
                            @endforeach
                            @else
                            <div class="row">
                                <div class="col-sm-3 form-group">
                                    <label>Manpower Skill*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text" name="Manpower_Skill[0]" placeholder="Manpower Skill" value="" required>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Manpower Count*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text" name="Manpower_Count[0]" placeholder="Manpower Count" value="" required>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Average Salary*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text" name="Average_Salary[0]" placeholder="Average Salary" value="" required>
                                    </div>
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
                                <input disabled type="hidden" name="MachineID[{{$k}}]" value="{{isset($MachineVal->id) && $MachineVal->id!=''?$MachineVal->id:''}}">
                                <div class="col-xl-2 col-md-3 col-md-6 col-sm-12 form-group">
                                    <label>Machine Name*</label>
                                    <select disabled name="Machine_Specification[{{$k}}]" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Machine_Specification as $val)
                                        <option value="{{$val->id}}" {{isset($MachineVal->Machine_Specification) && $MachineVal->Machine_Specification==$val->id?'selected':''}}>{{$val->Machine_Name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-2 col-md-3 col-md-6 col-sm-12 form-group">
                                    <label>Production Capacity Per Shift*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text" name="Production_Capacity_Per_Shift[{{$k}}]" placeholder="Production Capacity Per Shift" value="{{isset($MachineVal->Production_Capacity_Per_Shift) && $MachineVal->Production_Capacity_Per_Shift!=''?$MachineVal->Production_Capacity_Per_Shift:''}}" required>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-md-3 col-md-6 col-sm-12form-group">
                                    <label>
                                        UOM*
                                    </label>
                                    <select disabled name="UOM_Second[{{$k}}]" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($UOM as $val)
                                        <option value="{{$val->id}}" {{isset($MachineVal->UOM_Second) && $MachineVal->UOM_Second==$val->id?'selected':''}}>{{$val->UOMs}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @php
                            $k++;
                            @endphp
                            @endforeach
                            @else
                            <div class="row">
                                <div class="col-sm-3 form-group">
                                    <label>Machine Specification*</label>
                                    <select disabled name="Machine_Specification[0]" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Machine_Specification as $val)
                                        <option value="{{$val->id}}">{{$val->Machine_Specification}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Production Capacity Per Shift*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text" name="Production_Capacity_Per_Shift[0]" placeholder="Production Capacity Per Shift" value="" required>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>
                                        UOM*
                                    </label>
                                    <select disabled name="UOM_Second[0]" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($UOM as $val)
                                        <option value="{{$val->id}}">{{$val->UOMs}}</option>
                                        @endforeach
                                    </select>
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
                                <input disabled type="hidden" name="ServicesID[{{$l}}]" value="{{isset($ServicesVal->id) && $ServicesVal->id!=''?$ServicesVal->id:''}}">
                                <div class="col-sm-3 form-group">
                                    <label>Services*</label>
                                    <select disabled name="Services[{{$l}}]" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Services as $val)
                                        <option value="{{$val->id}}" {{isset($ServicesVal->Services) && $ServicesVal->Services==$val->id?'selected':''}}>{{$val->Services}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Amount.*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text" name="Services_Amount[{{$l}}]" placeholder="Amount" value="{{isset($ServicesVal->Services_Amount) && $ServicesVal->Services_Amount!=''?$ServicesVal->Services_Amount:''}}" required>
                                    </div>
                                </div>
                            </div>
                            @php
                            $l++;
                            @endphp
                            @endforeach
                            @else
                            <div class="row">
                                <div class="col-sm-3 form-group">
                                    <label>Services*</label>
                                    <select disabled name="Services[0]" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Services as $val)
                                        <option value="{{$val->id}}">{{$val->Services}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Amount.*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text" name="Services_Amount[0]" placeholder="Amount" value="" required>
                                    </div>
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
                                <input disabled type="hidden" name="ConsumblesID[{{$m}}]" value="{{isset($ConsumblesVal->id) && $ConsumblesVal->id!=''?$ConsumblesVal->id:''}}">
                                <div class="col-sm-3 form-group">
                                    <label>Consumbles*</label>
                                    <select disabled name="Consumbles[{{$m}}]" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Consumbles as $val)
                                        <option value="{{$val->id}}" {{isset($ConsumblesVal->Consumbles) && $ConsumblesVal->Consumbles==$val->id?'selected':''}}>{{$val->Consumbles}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Amount*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text" name="Consumbles_Amount[{{$m}}]" placeholder="Amount" value="{{isset($ConsumblesVal->Consumbles_Amount) && $ConsumblesVal->Consumbles_Amount!=''?$ConsumblesVal->Consumbles_Amount:''}}" required>
                                    </div>
                                </div>
                            </div>
                            @php
                            $m++;
                            @endphp
                            @endforeach
                            @else
                            <div class="row">
                                <div class="col-sm-3 form-group">
                                    <label>Consumbles*</label>
                                    <select disabled name="Consumbles[0]" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Consumbles as $val)
                                        <option value="{{$val->id}}">{{$val->Consumbles}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Amount*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text" name="Consumbles_Amount[0]" placeholder="Amount" value="" required>
                                    </div>
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
                                <input disabled type="hidden" name="ManagementID[{{$n}}]" value="{{isset($ManagementVal->id) && $ManagementVal->id!=''?$ManagementVal->id:''}}">
                                <div class="col-sm-3 form-group">
                                    <label>Management Expenses*</label>
                                    <select disabled name="Management_Expenses[{{$n}}]" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Management_Expenses as $val)
                                        <option value="{{$val->id}}" {{isset($ManagementVal->Management_Expenses) && $ManagementVal->Management_Expenses==$val->id?'selected':''}}>{{$val->Management_Expenses}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Amount*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text" name="Management_Expenses_Amount[{{$n}}]" placeholder="Amount" value="{{isset($ManagementVal->Management_Expenses_Amount) && $ManagementVal->Management_Expenses_Amount!=''?$ManagementVal->Management_Expenses_Amount:''}}" required>
                                    </div>
                                </div>
                            </div>
                            @php
                            $n++;
                            @endphp
                            @endforeach
                            @else
                            <div class="row">
                                <div class="col-sm-3 form-group">
                                    <label>Management Expenses*</label>
                                    <select disabled name="Management_Expenses[0]" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Management_Expenses as $val)
                                        <option value="{{$val->id}}">{{$val->Management_Expenses}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Amount*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text" name="Management_Expenses_Amount[0]" placeholder="Amount" value="" required>
                                    </div>
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
                                <input disabled type="hidden" name="ExpensesID[{{$o}}]" value="{{isset($ExpensesVal->id) && $ExpensesVal->id!=''?$ExpensesVal->id:''}}">
                                <div class="col-sm-3 form-group">
                                    <label>Other Expenses*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text" name="Other_Expenses[{{$o}}]" placeholder="Other Expenses" value="{{isset($ExpensesVal->Other_Expenses) && $ExpensesVal->Other_Expenses!=''?$ExpensesVal->Other_Expenses:''}}" required>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Amount*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text" name="Other_Expenses_Amount[{{$o}}]" placeholder="Amount" value="{{isset($ExpensesVal->Other_Expenses_Amount) && $ExpensesVal->Other_Expenses_Amount!=''?$ExpensesVal->Other_Expenses_Amount:''}}" required>
                                    </div>
                                </div>
                            </div>
                            @php
                            $o++;
                            @endphp
                            @endforeach
                            @else
                            <div class="row">
                                <div class="col-sm-3 form-group">
                                    <label>Other Expenses*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text" name="Other_Expenses[0]" placeholder="Other Expenses" value="" required>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Amount*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text" name="Other_Expenses_Amount[0]" placeholder="Amount" value="" required>
                                    </div>
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
                                <input disabled type="hidden" name="TransportID[{{$p}}]" value="{{isset($TransportVal->id) && $TransportVal->id!=''?$TransportVal->id:''}}">
                                <div class="col-sm-3 form-group">
                                    <label>Transport*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text" name="Transport[{{$p}}]" placeholder="Amount" value="{{isset($TransportVal->Transport) && $TransportVal->Transport!=''?$TransportVal->Transport:''}}" required>
                                    </div>
                                </div>
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
                                        <input disabled class="form-control form-control-sm" type="text" name="Transport[0]" placeholder="Amount" value="" required>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div id="TransportFields"></div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-6 form-group">
                                    <label>Total Amount*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text" name="All_Total_Amount" placeholder="Total Amount" value="{{isset($BOM->All_Total_Amount) && $BOM->All_Total_Amount!=''?$BOM->All_Total_Amount:''}}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label for="State">Remarks:</label>
                                    <input disabled type="text" name="remarks" cols="30" rows="5" class="form-control form-control-sm" placeholder="Remarks" value="{{isset($BOM->remarks) && $BOM->remarks!=''?$BOM->remarks:''}}">
                                </div>
                            </div>
                        </form>
                    </div>
                    @php
                    $STEP = Session::get('STEP');
                    $EXT = Session::get('EXT');
                    @endphp
                    <hr>
                    @if($BOM->Approve_status!='REJECT')
                    <form action="{{url('BOM/approve')}}" method="POST">
                        @csrf
                        <input type="hidden" name="approveID" value="{{isset($BOM->id) && $BOM->id!=''?$BOM->id:''}}">
                        <div class="tab-content" id="myTabContent">
                            @if($BOM->Approve_status!='APPROVE' && in_array(1, $STEP) || in_array(2, $STEP) || in_array(3, $STEP) || isset($EXT[11]['Forward']))

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
                                <div id="showfields" class="row" style="display: none">
                                    <div class="col-sm-4 form-group">
                                        <label>Days For Holding</label>
                                        <input type="date" style="border-radius: 12px;" min="{{date('Y-m-d')}}" name="days_for_holding" placeholder="Days For Holding" class="form-control form-control-sm requireddd" value="">
                                    </div>
                                </div>
                                <div id="Forwords" class="row" style="display: none;">
                                    <div class="col-sm-4 form-group">
                                        <label>Forward To</label>
                                        <select class="form-select form-select-sm requirrreddd" name="Forward_To">
                                            <option value="" selected disabled>Select</option>
                                            @foreach($employeeName as $val)
                                            <option value="{{isset($val->id) && $val->id!=''?$val->id:''}}">{{isset($val->fullname) && $val->fullname!=''?$val->fullname:''}}</option>
                                            @endforeach
                                        </select>
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
                        <a href="{{url('BOM/view-approve/'.$nextID)}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                        @else
                        <a href="{{url('BOM/BOMApproveList/')}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                        @endif
                    </form>
                    @endif
                </div>
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
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
            </div>
        </section>
    </div>
</div>

@endsection
@push('custom-scripts')
<script>
    $(document).ready(function() {
        activeclass(18, 2);
    });
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
