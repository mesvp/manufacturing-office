@extends('layout.main')
@section('main-container')
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
<style>
    * {
        box-sizing: border-box;
    }

    body {
        background-color: #f1f1f1;
    }

    #regForm {
        background-color: #ffffff;
        font-family: Raleway;
        width: 100%;
    }

    h1 {
        text-align: center;
    }

    input {
        padding: 10px;
        width: 100%;
        font-size: 17px;
        font-family: Raleway;
        border: 1px solid #aaaaaa;
    }

    /* Mark input boxes that gets an error on validation: */
    input.invalid {
        background-color: #ffdddd;
    }

    /* Hide all steps by default: */
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

    /* Make circles that indicate the steps of the form: */
    .step {
        height: 15px;
        width: 15px;
        margin: 0 2px;
        background-color: #bbbbbb;
        border: none;
        border-radius: 50%;
        display: inline-block;
        opacity: 0.5;
    }

    .step.active {
        opacity: 1;
    }

    /* Mark the steps that are finished and valid: */
    .step.finish {
        background-color: #04AA6D;
    }

    .tab {
        padding: 20px;
        background-color: white;
    }

    .tab1 {
        padding: 20px;
        border: 1px solid #a8adb1;
    }

    .col-sm-3 {
        width: 20% !important;
    }

    select.form-control {
        width: 200px;
    }

    tbody,
    td,
    tfoot,
    th,
    thead,
    tr {
        border: none !important;
    }

    .addbtn {
        display: flex;
        justify-content: flex-end;
        padding: 0px 12px;

    }

    .tabs {
        margin: 10px 0px !important;
        /* margin-bottom: 20px !important; */
    }

    select {
        background: white !important;
    }

    input {
        background: white;
    }

    input {
        background: white !important;
    }

    textarea {
        background: white !important;
    }

    input {
        display: block !important;
        width: 100% !important;
        padding: 0.375rem 0.75rem !important;
        font-size: 1rem !important;
        font-weight: 400 !important;
        line-height: 1.5 !important;
        color: #212529 !important;
        background-color: #fff !important;
        background-clip: padding-box !important;
        border: 1px solid #ced4da !important;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none !important;
        border-radius: 0.375rem !important;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out !important;
    }

    label.dp_homat {
        margin-top: 10px !important;
        display: block !important;
    }

    label.dp_homat p {
        margin-bottom: 0px !important;
        padding-bottom: 0px !important;
        display: block;
        /* margin-top: 10px; */
    }


    .form-control-sm {
        min-height: calc(1.5em + 0.5rem + 2px) !important;
        padding: 0.25rem 0.5rem !important;
        font-size: .875rem !important;
        border-radius: 0.25rem !important;
    }

    textarea#remarks {
        height: 30px !important;
    }

    textarea#remarks {
        margin-top: 5px !important;
    }

    div#gion {
        padding-top: 5px;
    }

    textarea#rathor {
        margin-top: 0px !important;
        height: 10px;
    }


    .form-select {

        background-image: none !important;

    }

    div#kim_id {
        width: 100% !important;
        display: block !important;
    }

    textarea {
        height: 30px !important;
    }

    ul#myTab {
        width: 100% !important;
    }

    ul#myTab .row {
        width: 100%;
    }

    ul#myTab .row .col-md-3 {
        text-align: center;
    }

    ul#myTab .row .col-md-3 li.nav-item {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        align-content: center;
    }

    ul#myTab .row .col-md-3 li.nav-item button#home-tab {
        width: 100%;
        border-radius: 5px !important;
    }

    ul#myTab {
        border: none !important;
    }

    li.nav-item button#profile-tab,
    button#contact-tab,
    button#home-tab {
        display: flex;
        align-items: center;
        justify-content: center;
        align-content: center;
        width: 100%;
        border: 1px solid #41719C;
        border-radius: 5px !important;
    }


    li.nav-item button#profile-tab,
    button#contact-tab,
    button#home-tab {
        display: flex;
        align-items: center;
        justify-content: center;
        align-content: center;
        width: 100%;
        border: 1px solid #41719C;
        border-radius: 5px !important;
        color: #232323;
        font-weight: 600 !important;
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

    div#DataTables_Table_0_filter {
        display: none;
    }
</style>

<div class="card">
    <div class="app-content">
        <section class="section">
            <div class="addbtn extra">
                <a href="{{url('FactoryCreater/factory-approve')}}" class="btn btn-info"> <i class="fa fa-arrow-left"></i> BACK</a>
                <a href="{{url('FactoryCreater/factory-approve')}}" class="btn btn-info" style="margin-left:10px"> <i class="fa fa-home"></i> Home</a>
            </div>
            <div class="row">
                <div class="container">
                    <br>
                    <div>
                        <div class="tabs">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col">
                                            <h5>Address Details</h5>
                                        </div>
                                        <div class="col">
                                            <label for="">Inputer Name : {{auth()->user()->name}}</label>

                                        </div>
                                        <div class="col">
                                            <label for="">Date & Time : <span id="clock"></span></label>
                                        </div>
                                    </div>
                                </div>
                            </div><br>
                            <div class="tab1">
                                <div class="row">
                                    <div class="col-sm-2 form-group">
                                        <label>Organization*</lable>
                                            <select disabled class="form-select form-select-sm" name="organization" required>
                                                <option value="null" selected disabled>Select Option </option>
                                                @foreach($Organization as $val)
                                                <option value="{{$val->id}}" {{isset($showdata->organization) && $showdata->organization==$val->id?'selected':''}}>{{$val->organisation}}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                    <div class="col-sm-2 form-group">
                                        <label>Name Of Unit*</lable>
                                            <select disabled class="form-select form-select-sm" name="name_of_unit" required>
                                                <option value="null" selected disabled>Select Option</option>
                                                @foreach($nameOfUnit as $val)
                                                <option value="{{$val->id}}" {{isset($showdata->name_of_unit) && $showdata->name_of_unit==$val->id?'selected':''}}>{{$val->pname}}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                    <div class="col-sm-2 form-group">
                                        <label>Country*</lable>
                                            <select disabled class="form-select form-select-sm" name="country" id="country" required>
                                                <option value="" selected disabled>Select Option</option>
                                                @foreach($country as $val)
                                                <option value="{{$val->id}}" {{isset($showdata->country) && $showdata->country==$val->id?'selected':''}}>{{$val->name}}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                    <div class="col-sm-2 form-group">
                                        <label>State*</lable>
                                            <select disabled class="form-select form-select-sm" name="state" id="state" required>
                                                <option value="null" selected disabled>Select Option</option>
                                                @foreach($state as $val)
                                                <option value="{{$val->id}}" {{isset($showdata->state) && $showdata->state==$val->id?'selected':''}}>{{$val->sname}}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                    <div class="col-sm-2 form-group">
                                        <label>District*</lable>
                                            <select disabled class="form-select form-select-sm" name="district" id="district" required>
                                                <option value="null" selected disabled>Select Option</option>
                                                @foreach($city as $val)
                                                <option value="{{$val->id}}" {{isset($showdata->district) && $showdata->district==$val->id?'selected':''}}>{{$val->distname}}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-2 form-group">
                                        <table class="table" id="dynamic_field">
                                            @php
                                            $i = 1;
                                            @endphp
                                            @if($address!='' && count($address)>0)
                                            @foreach($address as $val)
                                            <tr id="row{{$i}}">
                                                <input disabled class="form-control" type="hidden" name="addressID[]" value="{{isset($val->id) && $val->id!=''?$val->id:''}}">
                                                <div class="field-wrap">
                                                    <label style="display:flex;">
                                                        Address<span class="req">*</span>
                                                    </label>
                                                    <input disabled class="form-control form-control-sm" type="text" name="address[]" placeholder="Address" value="{{isset($val->address) && $val->address!=''?$val->address:''}}" required />
                                                </div>
                                            </tr>
                                            @php
                                            $i++;
                                            @endphp
                                            @endforeach
                                            @endif
                                        </table>
                                    </div>
                                    <div class="col-sm-2 form-group">
                                        <label>Pincode*</lable>
                                            <input disabled class="form-control form-control-sm" type="text" maxlength="6" onkeypress='return event.charCode >= 48 && event.charCode <= 57' name="pincode" placeholder="Pincode" value="{{isset($showdata->pincode) && $showdata->pincode!=''?$showdata->pincode:''}}" required>
                                    </div>
                                    <div class="col-sm-4 form-group">
                                        <label for="State">Remarks:</label>
                                        <input disabled type="text" name="remarks" id="" cols="30" rows="5" class="form-control form-control-sm" placeholder="Remarks" value="{{isset($showdata->remarks) && $showdata->remarks!=''?$showdata->remarks:''}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tabs">
                            <h5>Statutory Details</h5>
                            <div class="tab1">
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label>GST IN No.</lable>
                                            <div class="field-wrap">
                                                <select disabled name="gst_no" class="form-select form-select-sm" required>
                                                    <option value="" selected disabled>Select</option>
                                                    @foreach($gst as $val)
                                                    <option value="{{$val->id}}" {{isset($statury->gst_no) && $statury->gst_no==$val->id?'selected':''}}>{{$val->gst_no}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label>GST IN Certificate Attachement*</lable>
                                            <p><input disabled class="form-control form-control-sm " type="file" name="gst_in_certificate_attachement" value="{{isset($statury->gst_in_certificate_attachement) && $statury->gst_in_certificate_attachement!=''?$statury->gst_in_certificate_attachement:''}}" {{isset($statury->id)&&$statury->id!=''?'':'required'}}></p>
                                            @if(isset($statury->gst_in_certificate_attachement) && $statury->gst_in_certificate_attachement!='')
                                            <div class="downloadfile" id="gst_in_certificate_attachement">
                                                <div>{{substr(isset($statury->gst_in_certificate_attachement) && $statury->gst_in_certificate_attachement!=''?$statury->gst_in_certificate_attachement:'', 15)}}</div>
                                                <p><a href="{{url('storage/'.(isset($statury->gst_in_certificate_attachement) && $statury->gst_in_certificate_attachement!=''?$statury->gst_in_certificate_attachement:''))}}"  download><i class="fa fa-download" aria-hidden="true"></i></a></p>
                                                <div><a href="javascript:;" class="remove-file" onclick="removeFile('gst_in_certificate_attachement',{{ $statury->id}})"><i class="fa fa-remove"></i> </a></div>
                                            </div>
                                            @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label>Pan No.*</lable>
                                            <div class="field-wrap">
                                                <select disabled name="pan" class="form-select form-select-sm" required>
                                                    <option value="" selected disabled>Select</option>
                                                    @foreach($pan as $val)
                                                    <option value="{{$val->id}}" {{isset($statury->pan) && $statury->pan==$val->id?'selected':''}}>{{$val->pan_no}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label>PAN Attachement*</lable>
                                            <p><input disabled class="form-control form-control-sm" type="file" name="pan_attachement" {{isset($statury->id)&&$statury->id!=''?'':'required'}}></p>
                                            @if(isset($statury->pan_attachement) && $statury->pan_attachement!='')
                                            <div class="downloadfile" id="pan_attachement">
                                                <div>{{substr(isset($statury->pan_attachement) && $statury->pan_attachement!=''?$statury->pan_attachement:'', 15)}}</div>
                                                <p><a href="{{url('storage/'.(isset($statury->pan_attachement) && $statury->pan_attachement!=''?$statury->pan_attachement:''))}}" target="_blank" download><i class="fa fa-download" aria-hidden="true"></i></a></p>
                                                <div><a href="javascript:;" class="remove-file" onclick="removeFile('pan_attachement',{{ $statury->id}})"><i class="fa fa-remove"></i> </a></div>
                                            </div>
                                            @endif
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label>Factory License No.*</lable>
                                            <div class="field-wrap">
                                                <select disabled name="factory_license_no" class="form-select form-select-sm" required>
                                                    <option value="" selected disabled>Select</option>
                                                    @foreach($factoryLicense as $val)
                                                    <option value="{{$val->id}}" {{isset($statury->factory_license_no) && $statury->factory_license_no==$val->id?'selected':''}}>{{$val->factory_license_no}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label>Factory License Attachement*</lable>
                                            <p><input disabled class="form-control form-control-sm" type="file" name="factory_license_attachement" {{isset($statury->id)&&$statury->id!=''?'':'required'}}></p>
                                            @if(isset($statury->factory_license_attachement) && $statury->factory_license_attachement!='')
                                            <div class="downloadfile" id="factory_license_attachement">
                                                <div>{{substr(isset($statury->factory_license_attachement) && $statury->factory_license_attachement!=''?$statury->factory_license_attachement:'', 15)}}</div>
                                                <p><a href="{{url('storage/'.(isset($statury->factory_license_attachement) && $statury->factory_license_attachement!=''?$statury->factory_license_attachement:''))}}" target="_blank" download><i class="fa fa-download" aria-hidden="true"></i></a></p>
                                                <div><a href="javascript:;" class="remove-file" onclick="removeFile('factory_license_attachement',{{ $statury->id}})"><i class="fa fa-remove"></i> </a></div>
                                            </div>
                                            @endif
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label>Labour License No.*</lable>
                                            <div class="field-wrap">
                                                <select disabled name="labour_license_no" class="form-select form-select-sm" required>
                                                    <option value="" selected disabled>Select</option>
                                                    @foreach($labourLicense as $val)
                                                    <option value="{{$val->id}}" {{isset($statury->labour_license_no) && $statury->labour_license_no==$val->id?'selected':''}}>{{$val->labour_license_no}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label>Labour License Attachement*</lable>
                                            <p><input disabled class="form-control form-control-sm" type="file" name="labour_license_attachement" {{isset($statury->id)&&$statury->id!=''?'':'required'}}></p>
                                            @if(isset($statury->labour_license_attachement) && $statury->labour_license_attachement!='')
                                            <div class="downloadfile" id="labour_license_attachement">
                                                <div>{{substr(isset($statury->labour_license_attachement) && $statury->labour_license_attachement!=''?$statury->labour_license_attachement:'', 15)}}</div>
                                                <p><a href="{{url('storage/'.(isset($statury->labour_license_attachement) && $statury->labour_license_attachement!=''?$statury->labour_license_attachement:''))}}" target="_blank" download><i class="fa fa-download" aria-hidden="true"></i></a></p>
                                                <div><a href="javascript:;" class="remove-file" onclick="removeFile('labour_license_attachement',{{ $statury->id}})"><i class="fa fa-remove"></i> </a></div>
                                            </div>
                                            @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label>Pollution Certificate No.*</lable>
                                            <div class="field-wrap">
                                                <select disabled name="pollution_certificate_no" class="form-select form-select-sm" required>
                                                    <option value="" selected disabled>Select</option>
                                                    @foreach($polution as $val)
                                                    <option value="{{$val->id}}" {{isset($statury->pollution_certificate_no) && $statury->pollution_certificate_no==$val->id?'selected':''}}>{{$val->polution_certificate}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label>Pollution Certificate Attachement*</lable>
                                            <p><input disabled class="form-control form-control-sm" type="file" name="pollution_certificate_attachement" {{isset($statury->id)&&$statury->id!=''?'':'required'}}></p>
                                            @if(isset($statury->pollution_certificate_attachement) && $statury->pollution_certificate_attachement!='')
                                            <div class="downloadfile" id="pollution_certificate_attachement">
                                                <div>{{substr(isset($statury->pollution_certificate_attachement) && $statury->pollution_certificate_attachement!=''?$statury->pollution_certificate_attachement:'', 15)}}</div>
                                                <p><a href="{{url('storage/'.(isset($statury->pollution_certificate_attachement) && $statury->pollution_certificate_attachement!=''?$statury->pollution_certificate_attachement:''))}}" target="_blank" download><i class="fa fa-download" aria-hidden="true"></i></a></p>
                                                <div><a href="javascript:;" class="remove-file" onclick="removeFile('pollution_certificate_attachement',{{ $statury->id}})"><i class="fa fa-remove"></i> </a></div>
                                            </div>
                                            @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-8 form-group">
                                        <label>Others</lable>
                                            <table class="table table-bordered" id="dynamic_field1">
                                                @if(count($stuother)>0)
                                                @php
                                                $i = 1;
                                                @endphp
                                                @foreach($stuother as $val)
                                                <tr id="row{{$i}}">
                                                    <input disabled class="form-control form-control-sm" type="hidden" name="editother[]" value="{{isset($val->id) && $val->id!=''?$val->id:''}}">
                                                    <input disabled class="form-control form-control-sm" type="hidden" name="idd[]" value="{{$i}}">
                                                    <td>
                                                        <div class="field-wrap">
                                                            <label style="display:flex;" id="labell{{$i}}">Enter Field Manually</label>
                                                            <input disabled class="form-control form-control-sm" type="text" autocomplete="off" class="form-control form-control-sm" oninput="updateLabel(this,'{{$i}}')" placeholder="Enter Manually" name="add_field_manually[]" value="{{isset($val->add_field_manually) && $val->add_field_manually!=''?$val->add_field_manually:''}}">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="field-wrap">
                                                            <label style="display:flex;">Enter Document No</label>
                                                            <input disabled class="form-control form-control-sm" type="text" autocomplete="off" class="form-control form-control-sm" placeholder="Enter Document No" name="add_field_manually_second[]" value="{{isset($val->add_field_manually_second) && $val->add_field_manually_second!=''?$val->add_field_manually_second:''}}">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="field-wrap">
                                                            <label style="display:flex;">Add Field Attachement Manually</label>
                                                            <input disabled class="form-control form-control-sm" type="file" placeholder="Auto Fetch From Statutory" name="add_field_attachement_manually{{$i}}">
                                                            @if(isset($val->add_field_attachement_manually) && $val->add_field_attachement_manually!='')
                                                            <div class="downloadfile" id="add_field_attachement_manually">
                                                                <div>{{substr(isset($val->add_field_attachement_manually) && $val->add_field_attachement_manually!=''?$val->add_field_attachement_manually:'', 15)}}</div>
                                                                <p><a href="{{url('storage/'.(isset($val->add_field_attachement_manually) && $val->add_field_attachement_manually!=''?$val->add_field_attachement_manually:''))}}" target="_blank" download><i class="fa fa-download" aria-hidden="true"></i></a></p>
                                                                <div><a href="javascript:;" class="remove-file" onclick="removeFile('add_field_attachement_manually',{{ $val->id}})"><i class="fa fa-remove"></i> </a></div>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                                @php
                                                $i++;
                                                @endphp
                                                @endforeach
                                                @else
                                                <tr id="row">
                                                    <input disabled class="form-control form-control-sm" type="hidden" name="idd[]" value="0">
                                                    <td>
                                                        <div class="field-wrap">
                                                            <label style="display:flex;" id="labell0">Enter Field Manually</label>
                                                            <input disabled class="form-control form-control-sm" type="text" autocomplete="off" class="form-control form-control-sm" oninput="updateLabel(this,'0')" placeholder="Enter Manually" name="add_field_manually[0]">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="field-wrap">
                                                            <label style="display:flex;">Enter Document No</label>
                                                            <input disabled class="form-control form-control-sm" type="text" autocomplete="off" class="form-control form-control-sm" placeholder="Enter Document No" name="add_field_manually_second[0]">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="field-wrap">
                                                            <label style="display:flex;">Add Field Attachement Manually</label>
                                                            <input disabled class="form-control form-control-sm" type="file" placeholder="Auto Fetch From Statutory" name="add_field_attachement_manually0">
                                                        </div>
                                                    </td>
                                                    <td><a href="javascript:;" id="add121" class="btn btn-success btn btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
                                                </tr>
                                                @endif
                                            </table>
                                    </div>
                                    <div class="col-sm-4 form-group">
                                        <label for="State">Remarks:</label>
                                        <input disabled type="text" name="Remarks" class="form-control form-control-sm" value="{{isset($statury->Remarks) && $statury->Remarks!=''?$statury->Remarks:''}}" placeholder="Remarks">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tabs">
                            <h5>Land & Building:</h5>
                            <div class="tab1">
                                <div class="row">
                                    <div class="col-sm-3 form-group">
                                        <label>Land Type*</lable>
                                            <select disabled name="land_type" class="form-select form-select-sm" required>
                                                <option value="" selected disabled>Select</option>
                                                @foreach($masterland as $val)
                                                <option value="{{$val->id}}" {{isset($landbulding->land_type) && $landbulding->land_type==$val->id?'selected':''}}>{{$val->land_type}}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>Land Area(Sq. Ft.)*</lable>
                                            <p><input disabled type="number" class="form-control form-control-sm" id="landarea" name="land_area" placeholder="Land Area(Sq. Ft.)" value="{{isset($landbulding->land_area) && $landbulding->land_area!=''?$landbulding->land_area:''}}" required></p>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>Open Area(Sq. Ft.)*</lable>
                                            <p><input disabled type="number" class="form-control form-control-sm" id="openarea" name="open_area" placeholder="Open Area(Sq. Ft.)" value="{{isset($landbulding->open_area) && $landbulding->open_area!=''?$landbulding->open_area:''}}" required></p>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>Cover Area(Sq. Ft.)*</lable>
                                            <p><input disabled type="number" class="form-control form-control-sm" id="coverarea" name="cover_area" placeholder="Cover Area(Sq. Ft.)" value="{{isset($landbulding->cover_area) && $landbulding->cover_area!=''?$landbulding->cover_area:''}}" required></p>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>Building Area(Sq. Ft.)</lable>
                                            <p><input disabled class="form-control form-control-sm" type="number" id="buildingarea" name="building_area" placeholder="Building Area(Sq. Ft.)" value="{{isset($landbulding->building_area) && $landbulding->building_area!=''?$landbulding->building_area:''}}" required></p>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3 form-group">
                                        <label>Building Type*</lable>
                                            <p><input disabled class="form-control form-control-sm" name="building_type" placeholder="Building Type" value="{{isset($landbulding->building_type) && $landbulding->building_type!=''?$landbulding->building_type:''}}" required></p>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>Boundary Height(Sq. Ft.)*</lable>
                                            <p><input disabled class="form-control form-control-sm" name="boundary_height" placeholder="Boundary Height(Sq. Ft.)" value="{{isset($landbulding->boundary_height) && $landbulding->boundary_height!=''?$landbulding->boundary_height:''}}" required></p>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>Boundary Width(Sq. Ft.)*</lable>
                                            <p><input disabled class="form-control form-control-sm" name="boundary_width" placeholder="Boundary Width(Sq. Ft.)" value="{{isset($landbulding->boundary_width) && $landbulding->boundary_width!=''?$landbulding->boundary_width:''}}" required></p>
                                    </div>
                                    <div class="col-sm-4 form-group">
                                        <label>Boundary Type*</lable>
                                            <table class="table table-bordered" id="dynamic_field2">
                                                @php
                                                $i = 1;
                                                @endphp
                                                @if(count($landtype)>0)
                                                @foreach($landtype as $val)
                                                <tr id="rows{{$i}}">
                                                    <input disabled class="form-control form-control-sm" type="hidden" name="boundaryID[]" value="{{isset($val->id) && $val->id!=''?$val->id:''}}">
                                                    <td>
                                                        <div class="field-wrap">
                                                            <select disabled name="boundary_type[]" class="form-select form-select-sm selecttt" required>
                                                                <option value="" selected disabled>Select</option>
                                                                @foreach($boundarytype as $valss)
                                                                <option value="{{$valss->id}}" {{isset($val->boundary_type) && $val->boundary_type==$valss->id?'selected':''}}>{{$valss->Boundary_Type}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="field-wrap">
                                                            <input disabled class="form-control form-control-sm" type="hidden" name="idd[]" value="{{$i}}">
                                                            <input disabled class="form-control form-control-sm" type="file" autocomplete="off" class="form-control form-control-sm" name="attachement1" />
                                                            {{-- @foreach($landtype as $valattch)
                                                                        <div class="field-wrap form-select form-select-sm" id="kim_id">
                                                                            <a href="{{url('storage/'.$valattch->attachement)}}" target="_blank" download>Download</a>
                                                                        </div>
                                                            @endforeach --}}
                                                            @foreach($landtype as $valattch)
                                                                    <div>{{substr(isset($valattch->attachement) && $valattch->attachement!=''?$valattch->attachement:'', 13)}}</div>
                                                                    <p><a href="{{url('storage/'.(isset($valattch->attachement) && $valattch->attachement!=''?$valattch->attachement:''))}}" target="_blank" download><i class="fa fa-download" aria-hidden="true"></i></a></p>
                                                            @endforeach

                                                        </div>
                                                    </td>
                                                </tr>
                                                @php
                                                $i++;
                                                @endphp
                                                @endforeach
                                                @endif
                                            </table>
                                    </div>
                                    <div class="col-sm-3 form-group"></div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3 form-group">
                                        <label>Window*</lable>
                                            <p><input disabled class="form-control form-control-sm" name="window" placeholder="Window" value="{{isset($landbulding->window) && $landbulding->window!=''?$landbulding->window:''}}" required></p>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>Gate*</lable>
                                            <p><input disabled class="form-control form-control-sm" name="gate" placeholder="Gate" value="{{isset($landbulding->gate) && $landbulding->gate!=''?$landbulding->gate:''}}" required></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label>Other*</lable>
                                            <table class="table table-bordered" id="dynamic_field4">
                                                @php
                                                $i = 1;
                                                @endphp
                                                @foreach($landother as $val)
                                                <tr id="row{{$i}}">
                                                    <input disabled class="form-control form-control-sm" type="hidden" name="otherID[]" value="{{isset($val->id) && $val->id!=''?$val->id:''}}">
                                                    <td>
                                                        <div class="field-wrap"><label style="display:flex;">Add Field Name Manually</label>
                                                            <input disabled class="form-control form-control-sm" type="text" autocomplete="off" class="form-control form-control-sm" name="add_field_name_manually[]" placeholder="Add Field Name Manually" value="{{isset($val->add_field_name_manually) && $val->add_field_name_manually!=''?$val->add_field_name_manually:''}}" required />
                                                        </div>
                                                        <br>
                                                        <div class="field-wrap"><label style="display:flex;">Enter Manually Details</label>
                                                            <input disabled class="form-control form-control-sm" type="text" autocomplete="off" class="form-control form-control-sm" name="enter_manually_details[]" placeholder="Enter Manually Details" value="{{isset($val->enter_manually_details) && $val->enter_manually_details!=''?$val->enter_manually_details:''}}" required />
                                                        </div>
                                                    </td>
                                                </tr>
                                                @php
                                                $i++;
                                                @endphp
                                                @endforeach
                                            </table>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label for="State">Remark:</label>
                                        <input disabled type="text" name="remark" id="" value="{{isset($landbulding->remark) && $landbulding->remark!=''?$landbulding->remark:''}}" cols="30" rows="5" class="form-control form-control-sm" placeholder="Remarks">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tabs">
                            <h5>Plant & Machinery</h5>
                            <div class="maindd" id='mainddd'>
                                @php
                                $i = 1;
                                @endphp
                                @if(count($plantmach)>0)
                                @foreach($plantmach as $plantmach)
                                <div id="removemachinepage{{$i}}" class="row">
                                    <input disabled class="form-control form-control-sm" type="hidden" name="edit[{{$i}}]" value="{{isset($plantmach->id) && $plantmach->id!=''?$plantmach->id:''}}">
                                    <div class="col-sm-11 tab1">
                                        <div class="row">
                                            <div class="col-sm-3 form-group">
                                                <label>Plant Name*</lable>
                                                    <div class="field-wrap">
                                                        <select disabled name="Plant_Name[{{$i}}]" class="form-select form-select-sm" required>
                                                            <option value="" selected disabled>Select</option>
                                                            @foreach($masterplant as $val)
                                                            <option value="{{$val->id}}" {{isset($plantmach->Plant_Name) && $plantmach->Plant_Name==$val->id?'selected':''}}>{{$val->spname}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label>Production Capacity*</lable>
                                                    <p><input disabled type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57' class="form-control form-control-sm" name="Production_Capacity[{{$i}}]" value="{{isset($plantmach->Production_Capacity) && $plantmach->Production_Capacity!=''?$plantmach->Production_Capacity:''}}" required></p>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label>Uom*</lable>
                                                    <p><input disabled type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57' class="form-control form-control-sm" name="Production_Capacity[{{$i}}]" value="{{isset($plantmach->UOMs) && $plantmach->UOMs!=''?$plantmach->UOMs:''}}" required></p>
                                            </div>
                                            {{-- <div class="col-sm-3 form-group">
                                                <label>Product</lable>
                                                    <div class="field-wrap">
                                                        <select disabled name="Product[{{$i}}]" class="form-select form-select-sm" id="product{{$i}}" onclick="product({{$i}})" required>
                                                            <option value="" selected disabled>Select</option>
                                                            @foreach($product as $val)
                                                            <option value="{{$val->id}}" {{isset($plantmach->Product) && $plantmach->Product==$val->id?'selected':''}}>{{$val->product}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label>Sub product</lable>
                                                    <div class="field-wrap">
                                                        <select disabled name="Sub_product[{{$i}}]" class="form-select form-select-sm" id="subproduct{{$i}}" onclick="subproduct({{$i}})" required>
                                                            <option value="" selected disabled>Select</option>
                                                            @foreach($subproduct as $val)
                                                            <option value="{{$val->id}}" {{isset($plantmach->Sub_product) && $plantmach->Sub_product==$val->id?'selected':''}}>{{$val->sub_product}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label>Sub Sub product</lable>
                                                    <div class="field-wrap">
                                                        <select disabled name="Sub_Sub_product[{{$i}}]" class="form-select form-select-sm" id="subsubproduct{{$i}}" required>
                                                            <option value="" selected disabled>Select</option>
                                                            @foreach($subsubproduct as $val)
                                                            <option value="{{$val->id}}" {{isset($plantmach->Sub_Sub_product) && $plantmach->Sub_Sub_product==$val->id?'selected':''}}>{{$val->sub_sub_product}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                            </div> --}}
                                        </div>
                                        <div class="row">
                                            {{-- <div class="col-sm-3 form-group">
                                                <label>UOM*</lable>
                                                    <select disabled name="UOM[{{$i}}]" class="form-select form-select-sm" required>
                                                        <option value="" selected disabled>Select</option>
                                                        @foreach($uom as $val)
                                                        <option value="{{$val->id}}" {{isset($plantmach->UOM) && $plantmach->UOM==$val->id?'selected':''}}>{{$val->UOMs}}</option>
                                                        @endforeach
                                                    </select>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label>Duration*</lable>
                                                    <p><input disabled class="form-control form-control-sm" name="Duration[{{$i}}]" value="{{isset($plantmach->Duration) && $plantmach->Duration!=''?$plantmach->Duration:''}}" required></p>
                                            </div> --}}
                                        </div>
                                        <table class="table table-bordered" id="dynamic_fieldaddmachine{{$i}}">
                                            @php
                                            $j = 1;
                                            @endphp
                                            @foreach($plantmach->machinename as $machinename)
                                            <tr id="rowaddmachine{{$i}}{{$j}}">
                                                <input disabled class="form-control form-control-sm" type="hidden" name="machinenameID[{{$i}}][{{$j}}]" value="{{isset($machinename->id) && $machinename->id!=''?$machinename->id:''}}">
                                                <td>
                                                    <div class="row">
                                                        {{-- <div class="col-sm-3 form-group">
                                                            <label>Machine Name*</lable>
                                                                <select disabled name="Machine_Name[{{$i}}][{{$j}}]" class="form-select form-select-sm" required>
                                                                    <option value="" selected disabled>Select</option>
                                                                    @foreach($MachineName as $val)
                                                                    <option value="{{$val->id}}" {{isset($machinename->Machine_Name) && $machinename->Machine_Name==$val->id?'selected':''}}>{{$val->Machine_Name}}</option>
                                                                    @endforeach
                                                                </select>
                                                        </div>
                                                        <div class="col-sm-3 form-group">
                                                            <label>Attachement</lable>
                                                                <p><input disabled class="form-control form-control-sm" type="file" name="Attachement{{$i}}{{$j}}"></p>
                                                                @foreach($plantmach->machinename as $val)
                                                                <div>{{substr(isset($val->Attachement) && $val->Attachement!=''?$val->Attachement:'', 13)}}</div>
                                                                <p><a href="{{url('storage/'.(isset($val->Attachement) && $val->Attachement!=''?$val->Attachement:''))}}" target="_blank" download><i class="fa fa-download" aria-hidden="true"></i></a></p>
                                                                @endforeach
                                                        </div> --}}
                                                        <div class="col-sm-3 form-group">
                                                            <label>Machine Code*</lable>
                                                                <select disabled name="Machine_Code[{{$i}}][{{$j}}]" class="form-select form-select-sm" required>
                                                                    <option value="" selected disabled>Select</option>
                                                                    @foreach($Machine_Code as $val)
                                                                    <option value="{{$val->id}}" {{isset($machinename->Machine_Code) && $machinename->Machine_Code==$val->id?'selected':''}}>{{$val->Machine_Code}}</option>
                                                                    @endforeach
                                                                </select>
                                                        </div>
                                                        <div class="col-sm-3 form-group">
                                                            <label>Description*</lable>
                                                                <p><input disabled type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57' class="form-control form-control-sm" name="Production_Capacity[{{$i}}]" value="{{isset($machinename->Machine_description) && $machinename->Machine_description!=''?$machinename->Machine_description:''}}" required></p>
                                                        </div>
                                                        {{-- <div class="col-sm-3 form-group">
                                                            <label>Accessories*</lable>
                                                                <select disabled name="Accessories[{{$i}}][{{$j}}]" class="form-select form-select-sm" required>
                                                                    <option value="" selected disabled>Select</option>
                                                                    @foreach($Accessories as $val)
                                                                    <option value="{{$val->id}}" {{isset($machinename->Accessories) && $machinename->Accessories==$val->id?'selected':''}}>{{$val->Accessories}}</option>
                                                                    @endforeach
                                                                </select>
                                                        </div> --}}
                                                        {{-- <div class="col-sm-3 form-group">
                                                            <label>Attachement</lable>
                                                                <p><input disabled class="form-control form-control-sm" type="file" name="Attachements{{$i}}{{$j}}"></p>
                                                                @foreach($plantmach->machinename as $val)
                                                                    <div>{{substr(isset($val->Attachements) && $val->Attachements!=''?$val->Attachements:'', 13)}}</div>
                                                                    <p><a href="{{url('storage/'.(isset($val->Attachements) && $val->Attachements!=''?$val->Attachements:''))}}" target="_blank" download><i class="fa fa-download" aria-hidden="true"></i></a></p>
                                                                    </div>
                                                                @endforeach
                                                        </div> --}}
                                                        {{-- <div class="col-sm-3 form-group">
                                                            <label>Specification*</lable>
                                                                <select disabled name="Specification[{{$i}}][{{$j}}]" class="form-select form-select-sm" required>
                                                                    <option value="" selected disabled>Select</option>
                                                                    @foreach($Specification as $val)
                                                                    <option value="{{$val->id}}" {{isset($machinename->Specification) && $machinename->Specification==$val->id?'selected':''}}>{{$val->Specification}}</option>
                                                                    @endforeach
                                                                </select>
                                                        </div>
                                                        <div class="col-sm-3 form-group">
                                                            <label>Make & Model*</lable>
                                                                <div class="field-wrap">
                                                                    <select disabled name="Make_Model[{{$i}}][{{$j}}]" class="form-select form-select-sm" required>
                                                                        <option value="" selected disabled>Select</option>
                                                                        @foreach($Make_Model as $val)
                                                                        <option value="{{$val->id}}" {{isset($machinename->Make_Model) && $machinename->Make_Model==$val->id?'selected':''}}>{{$val->Make_Model}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                        </div> --}}
                                                    </div>
                                                </td>
                                            </tr>
                                            @php
                                            $j++;
                                            @endphp
                                            @endforeach
                                        </table>
                                        <table class="table table-bordered" id="dynamic_fieldaddwarnty{{$i}}">
                                            @php
                                            $k = 1;
                                            @endphp
                                            @foreach($plantmach->warrnty as $warrnty)
                                            <tr id="rowremovewarnty{{$i}}{{$k}}">
                                                <input disabled class="form-control form-control-sm" type="hidden" name="warrntyID[{{$i}}][{{$k}}]" value="{{isset($warrnty->id) && $warrnty->id!=''?$warrnty->id:''}}">
                                                <td>
                                                    <div class="row">
                                                        <div class="col-sm-3 form-group">
                                                            <label> Warranty*</lable>
                                                                <select disabled name="Warranty[{{$i}}][{{$k}}]" class="form-select form-select-sm" required>
                                                                    <option value="" selected disabled>Select</option>
                                                                    @foreach($Warranty as $val)
                                                                    <option value="{{$val->id}}" {{isset($warrnty->Warranty) && $warrnty->Warranty==$val->id?'selected':''}}>{{$val->Warranty}}</option>
                                                                    @endforeach
                                                                </select>
                                                        </div>
                                                        <div class="col-sm-3 form-group">
                                                            <label>Attachement</lable>
                                                                <p><input disabled class="form-control form-control-sm" type="file" name="Attachements{{$i}}{{$j}}"></p>
                                                                <div>{{substr(isset($warrnty->Attachement_warrenty) && $warrnty->Attachement_warrenty!=''?$warrnty->Attachement_warrenty:'', 12)}}</div>
                                                                <p><a href="{{url('storage/'.(isset($warrnty->Attachement_warrenty) && $warrnty->Attachement_warrenty!=''?$warrnty->Attachement_warrenty:''))}}" target="_blank" download><i class="fa fa-download" aria-hidden="true"></i></a></p>
                                                        </div>
                                                        {{-- <div class="col-sm-3 form-group">
                                                            <label>Production Capacity*</lable>
                                                                <p><input disabled type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57' class="form-control form-control-sm" name="Production_Capacitys[{{$i}}][{{$k}}]" value="{{isset($warrnty->Production_Capacitys) && $warrnty->Production_Capacitys!=''?$warrnty->Production_Capacitys:''}}" required></p>
                                                        </div> --}}
                                                        {{-- <div class="col-sm-3 form-group">
                                                            <label>UOM*</lable>
                                                                <div class="field-wrap">
                                                                    <select disabled name="UOMs[{{$i}}][{{$k}}]" class="form-select form-select-sm" required>
                                                                        <option value="" selected disabled>Select</option>
                                                                        @foreach($uom as $val)
                                                                        <option value="{{$val->id}}" {{isset($warrnty->UOMs) && $warrnty->UOMs==$val->id?'selected':''}}>{{$val->UOMs}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                        </div> --}}
                                                    </div>
                                                </td>
                                            </tr>
                                            @php
                                            $k++;
                                            @endphp
                                            @endforeach
                                        </table>
                                        <div class="row">
                                            <div class="col-sm-3 form-group">
                                                <label> Date Of Purchase*</lable>
                                                    <p><input disabled class="form-control form-control-sm" type="date" max="{{ now()->toDateString('Y-m-d') }}" name="Date_Of_Purchase[{{$i}}]" value="{{isset($plantmach->Date_Of_Purchase) && $plantmach->Date_Of_Purchase!=''?$plantmach->Date_Of_Purchase:''}}" required></p>
                                            </div>
                                            <div class="col-sm-4 form-group">
                                                <label>Machine Company Name*</lable>
                                                    <p><input disabled class="form-control form-control-sm" name="Machine_Company_Name[{{$i}}]" value="{{isset($plantmach->Machine_Company_Name) && $plantmach->Machine_Company_Name!=''?$plantmach->Machine_Company_Name:''}}" required></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 form-group">
                                                <label>Others</lable>
                                                    <table class="table table-bordered" id="dynamic_fieldaddother{{$i}}">
                                                        @php
                                                        $l = 1;
                                                        @endphp
                                                        @foreach($plantmach->other as $other)
                                                        <tr id="rowaddothers{{$i}}{{$l}}">
                                                            <input disabled class="form-control form-control-sm" type="hidden" name="otherID[{{$i}}][{{$l}}]" value="{{isset($other->id) && $other->id!=''?$other->id:''}}">
                                                            <td>
                                                                <div class="row">
                                                                    <div class="field-wrap col-sm-6">
                                                                        <label>Add Field Name Manually</label>
                                                                        <input disabled class="form-control form-control-sm" type="text" autocomplete="off" class="form-control form-control-sm" name="Add_Field_Name_Manually[{{$i}}][{{$l}}]" value="{{isset($other->Add_Field_Name_Manually) && $other->Add_Field_Name_Manually!=''?$other->Add_Field_Name_Manually:''}}" required />
                                                                    </div>
                                                                    <div class="field-wrap col-sm-6">
                                                                        <label>Enter Manually Details</label>
                                                                        <input disabled class="form-control form-control-sm" type="text" autocomplete="off" class="form-control form-control-sm" name="Enter_Manually_Details[{{$i}}][{{$l}}]" value="{{isset($other->Enter_Manually_Details) && $other->Enter_Manually_Details!=''?$other->Enter_Manually_Details:''}}" required />
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        @php
                                                        $l++;
                                                        @endphp
                                                        @endforeach
                                                    </table>
                                            </div>
                                            <div class="col-sm-6 form-group">
                                                <label for="State">Remarks:</label>
                                                <textarea disabled name="Remarks[{{$i}}]" id="" cols="30" rows="5" class="form-control form-control-sm">{{isset($plantmach->Remarks) && $plantmach->Remarks!=''?$plantmach->Remarks:''}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php
                                $i++;
                                @endphp
                                @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="tabs">
                            <h5>Amenities</h5> <br>
                            <div class="tab1">
                                <div class="row">
                                    <input disabled class="form-control" type="hidden" name="edit" value="{{isset($Amenitiess->id) && $Amenitiess->id!=''?$Amenitiess->id:''}}">
                                    <div class="col-sm-3 form-group">
                                        <label>Toilet Count</lable>
                                            <p>
                                                <input disabled class="form-control form-control-sm" id="totalCount" onkeypress="return ((event.charCode >= 48 && event.charCode <= 57) ||(event.charCode == 46))" placeholder="Toilet Count" name="Toilet_Count" value="{{isset($Amenitiess->Toilet_Count) && $Amenitiess->Toilet_Count!=''?$Amenitiess->Toilet_Count:''}}">
                                            </p>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>For Men</lable>
                                            <p><input disabled class="form-control form-control-sm" id="men" onkeypress="return ((event.charCode >= 48 && event.charCode <= 57) ||(event.charCode == 46))" placeholder="For Men" name="For_Men" value="{{isset($Amenitiess->For_Men) && $Amenitiess->For_Men!=''?$Amenitiess->For_Men:''}}"></p>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>For Women</lable>
                                            <p>
                                                <input disabled class="form-control form-control-sm" id="women" onkeypress="return ((event.charCode >= 48 && event.charCode <= 57) ||(event.charCode == 46))" placeholder="For Women" name="For_Women" value="{{isset($Amenitiess->For_Women) && $Amenitiess->For_Women!=''?$Amenitiess->For_Women:''}}">
                                            </p>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>WashBasin Count</lable>
                                            <p><input disabled class="form-control form-control-sm" onkeypress="return ((event.charCode >= 48 && event.charCode <= 57) ||(event.charCode == 46))" placeholder="WashBasin Count" name="WashBasin_Count" value="{{isset($Amenitiess->WashBasin_Count) && $Amenitiess->WashBasin_Count!=''?$Amenitiess->WashBasin_Count:''}}"></p>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>Urinals</lable>
                                            <p><input disabled class="form-control form-control-sm" onkeypress="return ((event.charCode >= 48 && event.charCode <= 57) ||(event.charCode == 46))" placeholder="Urinals" name="Urinals" value="{{isset($Amenitiess->Urinals) && $Amenitiess->Urinals!=''?$Amenitiess->Urinals:''}}"></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 form-group">
                                        <label>Others</lable>
                                            <table class="table table-bordered" id="dynamic_field8">
                                                @php
                                                $i = 1;
                                                @endphp
                                                @foreach($amentOther as $val)
                                                <tr id="row{{$i}}">
                                                    <input disabled type="hidden" name="other[]" value="{{isset($val->id) && $val->id!=''?$val->id:''}}">
                                                    <td>
                                                        <div class="field-wrap">
                                                            <label style="display:flex;">Add Field Name Manually</label>
                                                            <input disabled class="form-control form-control-sm" type="text" autocomplete="off" name="Add_Field_Name_Manually[]" value="{{isset($val->Add_Field_Name_Manually) && $val->Add_Field_Name_Manually!=''?$val->Add_Field_Name_Manually:''}}">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="field-wrap">
                                                            <label style="display:flex;">Add Count Manually</label>
                                                            <input disabled class="form-control form-control-sm" type="text" name="Add_Count_Manually[]" value="{{isset($val->Add_Count_Manually) && $val->Add_Count_Manually!=''?$val->Add_Count_Manually:''}}">
                                                        </div>
                                                    </td>
                                                </tr>
                                                @php
                                                $i++;
                                                @endphp
                                                @endforeach
                                            </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tabs">
                            <h5>Electricity</h5>
                            <div class="tab1">
                                <br>
                                <table class="table table-bordered" id="dynamic_field9">
                                    @php
                                    $i=1;
                                    @endphp
                                    @if(count($Electri)>0)
                                    @foreach($Electri as $Electricity)
                                    <tr id="remove{{$i}}">
                                        <input disabled type="hidden" name="electID[]" value="{{isset($Electricity->id) && $Electricity->id!=''?$Electricity->id:''}}">
                                        <td>
                                            <div class="row">
                                                <div class="col-sm-2 form-group">
                                                    <label>Total Capacity*</lable>
                                                        <p><input disabled class="form-control form-control-sm" id="totalcapacity{{$i}}" onchange="totalCapacity({{$i}})" placeholder="" name="Total_Capacity[]" value="{{isset($Electricity->Total_Capacity) && $Electricity->Total_Capacity!=''?$Electricity->Total_Capacity:''}}" required></p>
                                                </div>
                                                <div class="col-sm-2 form-group">
                                                    <label>Running Capacity*</lable>
                                                        <p><input disabled class="form-control form-control-sm" id="runningcapacity{{$i}}" onchange="totalCapacity({{$i}})" placeholder="" name="Running_Capacity[]" value="{{isset($Electricity->Running_Capacity) && $Electricity->Running_Capacity!=''?$Electricity->Running_Capacity:''}}" required></p>
                                                </div>
                                                <div class="col-sm-2 form-group">
                                                    <label>No. Of Meter*</lable>
                                                        <p><input disabled type="number" class="form-control form-control-sm" placeholder="" name="Meter[]" value="{{isset($Electricity->Meter) && $Electricity->Meter!=''?$Electricity->Meter:''}}" required></p>
                                                </div>
                                                <div class="col-sm-2 form-group">
                                                    <label>No. Of Sub Meter*</lable>
                                                        <p><input disabled type="number" class="form-control form-control-sm" placeholder="" name="Sub_Meter[]" value="{{isset($Electricity->Sub_Meter) && $Electricity->Sub_Meter!=''?$Electricity->Sub_Meter:''}}" required></p>
                                                </div>
                                                <div class="col-sm-2 form-group">
                                                    <label>Source Of Electricity*</lable>
                                                        <p><input disabled class="form-control form-control-sm" type="text" placeholder="" name="Source_Of_Electricity[]" value="{{isset($Electricity->Source_Of_Electricity) && $Electricity->Source_Of_Electricity!=''?$Electricity->Source_Of_Electricity:''}}" required />
                                                        </p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @php
                                    $i++;
                                    @endphp
                                    @endforeach
                                    @endif
                                </table>
                                <table class="table table-bordered" id="dynamic_field10">
                                    @php
                                    $j = 1;
                                    @endphp
                                    @if(count($Electrigenrate)>0)
                                    @foreach($Electrigenrate as $val)
                                    <tr id="removegen{{$j}}">
                                        <input disabled type="hidden" name="generatorID[]" value="{{isset($val->id) && $val->id!=''?$val->id:''}}">
                                        <td>
                                            <div class="row">
                                                <div class="col-sm-4 form-group">
                                                    <label>Generator*</lable>
                                                        <p><input disabled type="number" class="form-control form-control-sm" placeholder="" name="generator[]" value="{{isset($val->generator) && $val->generator!=''?$val->generator:''}}" required></p>
                                                </div>
                                                <div class="col-sm-4 form-group">
                                                    <label>Generator Capacity*</lable>
                                                        <p><input disabled class="form-control form-control-sm" placeholder="" name="Generator_Capacity[]" value="{{isset($val->Generator_Capacity) && $val->Generator_Capacity!=''?$val->Generator_Capacity:''}}" required></p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @php
                                    $j++;
                                    @endphp
                                    @endforeach
                                    @endif
                                </table>
                                <div class="row">
                                    <div class="col-sm-12 form-group">
                                        <label for="State">Remark:</label>
                                        <textarea disabled name="Electricity_remarks" id="" cols="30" rows="3" class="form-control form-control-sm">{{isset($showdata->Electricity_remarks) && $showdata->Electricity_remarks!=''?$showdata->Electricity_remarks:''}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tabs">
                            <h5>WareHouse & Room</h5>
                            <br>
                            <div class="tab1">
                                <div class="row">
                                    <div class="col-sm-10 form-group">
                                        <label>Total Warehouse*</lable>
                                            <p><input disabled class="form-control form-control-sm" placeholder="Total Warehouse" onkeypress="return ((event.charCode >= 48 && event.charCode <= 57) ||(event.charCode == 46))" name="Total_Warehouse" id="total_warehouse" value="{{isset($warehousetotal->Total_Warehouse) && $warehousetotal->Total_Warehouse!=''?$warehousetotal->Total_Warehouse:''}}" required></p>
                                    </div>
                                </div>
                                <div id="dynamic_field11">
                                    @php
                                    $i = 1;
                                    @endphp
                                    @if(count($warehouse)>0)
                                    @foreach($warehouse as $val)
                                    <input disabled class="form-control form-control-sm" type="hidden" name="warehouseID[]" value="{{isset($val->id) && $val->id!=''?$val->id:''}}">
                                    <div class="row" id="row{{$i}}">
                                        <div class="col-sm-3 form-group">
                                            <label>Warehouse Name*</lable>
                                                <p><input disabled class="form-control form-control-sm" placeholder="" name="Warehouse_Name[]" value="{{isset($val->Warehouse_Name) && $val->Warehouse_Name!=''?$val->Warehouse_Name:''}}" required></p>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>Count*</lable>
                                                <p><input disabled type="number" class="form-control form-control-sm" placeholder="" name="Count[]" value="{{isset($val->Count) && $val->Count!=''?$val->Count:''}}" required></p>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>Warehouse Type*</lable>
                                                <p><input disabled type="text" class="form-control form-control-sm" placeholder="" onkeypress="return ((event.charCode >= 65 && event.charCode <= 90) ||(event.charCode >= 97 && event.charCode <= 122) ||(event.charCode == 32 ))" name="Warehouse_Type[]" value="{{isset($val->Warehouse_Type) && $val->Warehouse_Type!=''?$val->Warehouse_Type:''}}" required></p>
                                        </div>
                                    </div>
                                    @php
                                    $i++;
                                    @endphp
                                    @endforeach
                                </div>
                                @endif
                                <br>
                                <div class="row">
                                    <div class="col-sm-10 form-group">
                                        <label>Total Room*</lable>
                                            <p><input disabled class="form-control form-control-sm" onkeypress="return ((event.charCode >= 48 && event.charCode <= 57) ||(event.charCode == 46))" placeholder="Total Room" name="Total_Room" id="total_room" value="{{isset($warehousetotal->Total_Room) && $warehousetotal->Total_Room!=''?$warehousetotal->Total_Room:''}}" required></p>
                                    </div>
                                </div>
                                <div id="dynamic_field12">
                                    @php
                                    $j = 1;
                                    @endphp
                                    @if(count($warehouseroom)>0)
                                    @foreach($warehouseroom as $val)
                                    <input disabled class="form-control form-control-sm" type="hidden" name="roomID[]" value="{{isset($val->id) && $val->id!=''?$val->id:''}}">
                                    <div class="row" id="rows{{$j}}">
                                        <div class="col-sm-4 form-group">
                                            <label>Room Name*</lable>
                                                <p><input disabled class="form-control form-control-sm" placeholder="" name="Room_Name[]" value="{{isset($val->Room_Name) && $val->Room_Name!=''?$val->Room_Name:''}}" required></p>
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>Room Count*</lable>
                                                <p><input disabled class="form-control form-control-sm" placeholder="" name="Room_Count[]" value="{{isset($val->Room_Count) && $val->Room_Count!=''?$val->Room_Count:''}}" required></p>
                                        </div>
                                    </div>
                                    @php
                                    $j++;
                                    @endphp
                                    @endforeach
                                    @else
                                    <div class="row">
                                        <div class="col-sm-4 form-group">
                                            <label>Room Name*</lable>
                                                <p><input disabled class="form-control form-control-sm" placeholder="" name="Room_Name[]" required></p>
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>Room Count*</lable>
                                                <p><input disabled class="form-control form-control-sm" placeholder="" name="Room_Count[]" required></p>
                                        </div>
                                        <div class="col-sm-2 form-group">
                                            <button type="button" name="add" id="add12" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="row">
                                    <div class="col-sm-12 form-group">
                                        <label for="State">Remark:</label>
                                        <textarea disabled name="Remark" id="" cols="30" rows="3" class="form-control form-control-sm">{{isset($warehousetotal->Remark) && $warehousetotal->Remark!=''?$warehousetotal->Remark:''}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tabs">
                            <h5>Office Asset</h5><br>
                            <div class="col-sm-2 form-group">
                                <label>Asset Category</lable>
                                    <p><input disabled class="form-control form-control-sm" name="Asset_Category" value="{{isset($officeasst->Asset_Category) && $officeasst->Asset_Category!=''?$officeasst->Asset_Category:''}}"></p>
                            </div>
                            <div class="tab1">
                                <br>
                                <div id="dynamic_field13">
                                    @php
                                    $i = 1;
                                    @endphp
                                    @if(count($assettypee)>0)
                                    @foreach($assettypee as $val)
                                    <div id="rows{{$i}}">
                                        <input disabled class="form-control form-control-sm" type="hidden" name="typeID[{{$i}}]" value="{{isset($val->id) && $val->id!=''?$val->id:''}}">
                                        <div class="row">
                                            <div class="col-sm-2 form-group">
                                                <label>Asset Type*</lable>
                                                    <p><input disabled class="form-control form-control-sm" placeholder="" name="Asset_Type[{{$i}}]" value="{{isset($val->Asset_Type) && $val->Asset_Type!=''?$val->Asset_Type:''}}" required></p>
                                            </div>
                                            <div class="col-sm-2 form-group">
                                                <label>Asset Name*</lable>
                                                    {{-- <p><input disabled class="form-control form-control-sm" placeholder="" name="Asset_Name[{{$i}}]" value="{{isset($val->Asset_Name) && $val->Asset_Name!=''?$val->Asset_Name:''}}" required></p> --}}
                                                    <select class="form-select form-select-sm" disabled id="assetname" name="Asset_Name[{{$i}}]" required>
                                                        <option value="null" selected disabled>Select Option </option>
                                                            @foreach($assetdeatils as $asset)
                                                            <option value="{{$asset->id}}" {{isset($val->Asset_Name) && $val->Asset_Name==$asset->id?'selected':''}}>{{$asset->description}}</option>
                                                            @endforeach
                                                    </select>
                                            </div>
                                            <div class="col-sm-2 form-group">
                                                <label>Asset SL No.*</lable>
                                                    <p><input disabled class="form-control form-control-sm" placeholder="" name="Asset_SL_No[{{$i}}]" value="{{isset($val->Asset_SL_No) && $val->Asset_SL_No!=''?$val->Asset_SL_No:''}}" required></p>
                                            </div>
                                            <div class="col-sm-2 form-group">
                                                <label>Date Of Purchase*</lable>
                                                    <p><input disabled class="form-control form-control-sm" type="date" placeholder="" name="Date_Of_Purchase[{{$i}}]" value="{{isset($val->Date_Of_Purchase) && $val->Date_Of_Purchase!=''?date('Y-m-d',strtotime($val->Date_Of_Purchase)):''}}" required></p>
                                            </div>
                                            <div class="col-sm-2 form-group">
                                                <label>Supplier Name*</lable>
                                                    <p><input disabled class="form-control form-control-sm" placeholder="" name="Supplier_Name[{{$i}}]" value="{{isset($val->Supplier_Name) && $val->Supplier_Name!=''?$val->Supplier_Name:''}}" required></p>
                                            </div>
                                            <div class="col-sm-2 form-group">
                                                <label>Invoice No.*</lable>
                                                    <p><input disabled class="form-control form-control-sm" placeholder="" name="invoice_No[{{$i}}]" value="{{isset($val->invoice_No) && $val->invoice_No!=''?$val->invoice_No:''}}" required></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-2 form-group">
                                                <label>QTY*</lable>
                                                    <p><input disabled type="number" class="form-control form-control-sm" placeholder="" name="QTY[{{$i}}]" value="{{isset($val->QTY) && $val->QTY!=''?$val->QTY:''}}" required></p>
                                            </div>
                                            <div class="col-sm-2 form-group">
                                                <label>Organization*</lable>
                                                    <p><input disabled class="form-control form-control-sm" placeholder="" name="Organization[{{$i}}]" value="{{isset($val->Organization) && $val->Organization!=''?$val->Organization:''}}" required></p>
                                            </div>
                                            <div class="col-sm-2 form-group">
                                                <label>Use By*</lable>
                                                    <p><input disabled class="form-control form-control-sm" placeholder="" name="Use_By[{{$i}}]" value="{{isset($val->Use_By) && $val->Use_By!=''?$val->Use_By:''}}" required></p>
                                            </div>
                                            <div class="col-sm-2 form-group">
                                                <label>Use In*</lable>
                                                    <p><input disabled class="form-control form-control-sm" placeholder="" name="Use_In[{{$i}}]" value="{{isset($val->Use_In) && $val->Use_In!=''?$val->Use_In:''}}" required></p>
                                            </div>
                                            <div class="col-sm-2 form-group">
                                                <label>Location*</lable>
                                                    <p><input disabled class="form-control form-control-sm" placeholder="" name="Location[{{$i}}]" value="{{isset($val->Location) && $val->Location!=''?$val->Location:''}}" required></p>
                                            </div>
                                            {{-- <div class="col-sm-2 form-group">
                                                <label>Furniture Type*</lable>
                                                    <p><input disabled class="form-control form-control-sm" placeholder="" name="Furniture_Type[{{$i}}]" value="{{isset($val->Furniture_Type) && $val->Furniture_Type!=''?$val->Furniture_Type:''}}" required></p>
                                            </div>
                                            <div class="col-sm-2 form-group">
                                                <label>Furniture Name*</lable>
                                                    <p><input disabled class="form-control form-control-sm" placeholder="" name="Furniture_Name[{{$i}}]" value="{{isset($val->Furniture_Name) && $val->Furniture_Name!=''?$val->Furniture_Name:''}}" required></p>
                                            </div>
                                            <div class="col-sm-2 form-group">
                                                <label>Furniture SL. No.*</lable>
                                                    <p><input disabled class="form-control form-control-sm" type="number" placeholder="" name="Furniture_SL_No[{{$i}}]" value="{{isset($val->Furniture_SL_No) && $val->Furniture_SL_No!=''?$val->Furniture_SL_No:''}}" required></p>
                                            </div>
                                            <div class="col-sm-2 form-group">
                                                <label>Date Of Purchase*</lable>
                                                    <p><input disabled class="form-control form-control-sm" type="date" placeholder="" name="Date_Of_Purchase_Second[{{$i}}]" value="{{isset($val->Date_Of_Purchase_Second) && $val->Date_Of_Purchase_Second!=''?$val->Date_Of_Purchase_Second:''}}" required></p>
                                            </div>
                                            <div class="col-sm-2 form-group">
                                                <label>Supplier Name*</lable>
                                                    <p><input disabled class="form-control form-control-sm" placeholder="" name="Supplier_Name_Second[{{$i}}]" value="{{isset($val->Supplier_Name_Second) && $val->Supplier_Name_Second!=''?$val->Supplier_Name_Second:''}}" required></p>
                                            </div>
                                            <div class="col-sm-2 form-group">
                                                <label>Invoice No*</lable>
                                                    <p><input disabled class="form-control form-control-sm" placeholder="" name="Invoice_No_Second[{{$i}}]" value="{{isset($val->Invoice_No_Second) && $val->Invoice_No_Second!=''?$val->Invoice_No_Second:''}}" required></p>
                                            </div>
                                            <div class="col-sm-2 form-group">
                                                <label>QTY*</lable>
                                                    <p><input disabled class="form-control form-control-sm" type="number" placeholder="" name="QTY_Second[{{$i}}]" value="{{isset($val->QTY_Second) && $val->QTY_Second!=''?$val->QTY_Second:''}}" required></p>
                                            </div>
                                            <div class="col-sm-2 form-group">
                                                <label>Organization*</lable>
                                                <select disabled class="form-select form-select-sm" name="Organization_Second[{{$i}}]" required>
                                                    <option value="null" selected disabled>Select Option </option>
                                                    @foreach($Organization as $org)
                                                    <option value="{{$org->id}}" {{isset($val->Organization_Second) && $val->Organization_Second==$org->id?'selected':''}}>{{$org->organisation}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-2 form-group">
                                                <label>Location*</lable>
                                                    <p><input disabled class="form-control form-control-sm" placeholder="" name="Location_Second[{{$i}}]" value="{{isset($val->Location_Second) && $val->Location_Second!=''?$val->Location_Second:''}}" required></p>
                                            </div>
                                            <div class="col-sm-2 form-group">
                                                <label>Use By*</lable>
                                                    <p><input disabled class="form-control form-control-sm" placeholder="" name="Use_By_Second[{{$i}}]" value="{{isset($val->Use_By_Second) && $val->Use_By_Second!=''?$val->Use_By_Second:''}}" required></p>
                                            </div>
                                            <div class="col-sm-2 form-group">
                                                <label>Used For*</lable>
                                                    <p><input disabled class="form-control form-control-sm" placeholder="" name="Used_For[{{$i}}]" value="{{isset($val->Used_For) && $val->Used_For!=''?$val->Used_For:''}}" required></p>
                                            </div>
                                            <div class="col-sm-2 form-group">
                                                <label>Other Item Details*</lable>
                                                    <p><input disabled class="form-control form-control-sm" placeholder="" name="Other_Item_Details[{{$i}}]" value="{{isset($val->Other_Item_Details) && $val->Other_Item_Details!=''?$val->Other_Item_Details:''}}" required></p>
                                            </div> --}}
                                        </div>
                                    </div>
                                    @php
                                    $i++;
                                    @endphp
                                    @endforeach
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 form-group">
                                        <label for="State">Remark:</label>
                                        <textarea disabled name="Remark" id="" cols="30" rows="3" class="form-control form-control-sm">{{isset($officeasst->Remark) && $officeasst->Remark!=''?$officeasst->Remark:''}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tabs">
                            <h5>Store</h5><br>
                            <div class="row">
                                <div class="col-sm-3 form-group">
                                    <label>Total Rack*</lable>
                                        <p><input disabled type="number" class="form-control form-control-sm" placeholder="" name="Total_Rack" value="{{isset($storee->Total_Rack) && $storee->Total_Rack!=''?$storee->Total_Rack:''}}" required></p>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label> Rack Capacity*</lable>
                                        <p><input disabled type="number" class="form-control form-control-sm" placeholder="" id="RackCapacityFirst" name="Rack_Capacity" value="{{isset($storee->Rack_Capacity) && $storee->Rack_Capacity!=''?$storee->Rack_Capacity:''}}" required></p>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Total Bin*</lable>
                                        <p><input disabled type="number" class="form-control form-control-sm" placeholder="" name="Total_Bin" value="{{isset($storee->Total_Bin) && $storee->Total_Bin!=''?$storee->Total_Bin:''}}" required></p>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Total Bin Capacity*</lable>
                                        <p><input disabled type="number" class="form-control form-control-sm" placeholder="" name="Total_Bin_Capacity" value="{{isset($storee->Total_Bin_Capacity) && $storee->Total_Bin_Capacity!=''?$storee->Total_Bin_Capacity:''}}" required></p>
                                </div>
                            </div>
                            <div class="tab1">
                                <div class="row">
                                    <div class="col-sm-3 form-group">
                                        <label> Rack No.*</lable>
                                            <p><input disabled type="number" class="form-control form-control-sm" placeholder="" name="Rack_No" value="{{isset($storee->Rack_No) && $storee->Rack_No!=''?$storee->Rack_No:''}}" required></p>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label> Rack Capacity*</lable>
                                            <p><input disabled type="number" class="form-control form-control-sm" style="margin-left:8px;" placeholder="" id="RackCapacitySecond" name="Rack_Capacities" value="{{isset($storee->Rack_Capacities) && $storee->Rack_Capacities!=''?$storee->Rack_Capacities:''}}" required></p>
                                    </div>
                                </div>
                                <h6>Details:</h6>
                                <hr>
                                <div id="dynamic_field16">
                                    @php
                                    $i=1;
                                    @endphp
                                    @if(count($storesubrack)>0)
                                    @foreach($storesubrack as $val)
                                    <div class="row" id="maindiv{{$i}}">
                                        <div class="col-sm-8">
                                            <div class="row">
                                                <div class="col-sm-5 form-group">
                                                    <label> Sub Rack No.*</lable>
                                                        <p><input disabled type="number" class="form-control form-control-sm" placeholder="" name="Sub_Rack_No[0]" value="{{isset($val->Sub_Rack_No) && $val->Sub_Rack_No!=''?$val->Sub_Rack_No:''}}" required></p>
                                                </div>
                                                <div class="col-sm-6 form-group">
                                                    <label> Sub Rack Capacity*</lable>
                                                        <p><input disabled type="number" class="form-control form-control-sm" placeholder="" name="Sub_Rack_Capacity[0]" value="{{isset($val->Sub_Rack_Capacity) && $val->Sub_Rack_Capacity!=''?$val->Sub_Rack_Capacity:''}}" required></p>
                                                </div>
                                            </div>
                                            <div id="dynamic_field{{$i}}">
                                                @php
                                                $j=1;
                                                @endphp
                                                @foreach($val->storebin as $storebin)
                                                <div class="sub-capacty" id="remsub-capacty{{$i}}{{$j}}">
                                                    <div class="row">
                                                        <div class="col-sm-5 form-group">
                                                            <label> Bin No.*</lable>
                                                                <p><input disabled type="number" class="form-control form-control-sm" placeholder="" name="Bin_No[0][0]" value="{{isset($storebin->Bin_No) && $storebin->Bin_No!=''?$storebin->Bin_No:''}}" required></p>
                                                        </div>
                                                        <div class="col-sm-5 form-group">
                                                            <label> Bin Capacity*</lable>
                                                                <p><input disabled type="number" class="form-control form-control-sm" placeholder="" name="Bin_Capacity[0][0]" value="{{isset($storebin->Bin_Capacity) && $storebin->Bin_Capacity!=''?$storebin->Bin_Capacity:''}}" required></p>
                                                        </div>
                                                    </div>
                                                    <div id="dynamic_field{{$i}}{{$j}}">
                                                        @php
                                                        $k=1;
                                                        @endphp
                                                        @foreach($storebin->storesubbin as $storesubbin)
                                                        <div class="row" id="remSub_Bin_No{{$j}}{{$k}}">
                                                            <div class="col-sm-5 form-group">
                                                                <label>Sub Bin No.*</lable>
                                                                    <p><input disabled type="number" class="form-control form-control-sm" placeholder="" name="Sub_Bin_No[0][0][0]" value="{{isset($storesubbin->Sub_Bin_No) && $storesubbin->Sub_Bin_No!=''?$storesubbin->Sub_Bin_No:''}}" required></p>
                                                            </div>
                                                            <div class="col-sm-5 form-group">
                                                                <label>Sub Bin Capacity*</lable>
                                                                    <p><input disabled type="number" class="form-control form-control-sm" placeholder="" name="Sub_Bin_Capacity[0][0][0]" value="{{isset($storesubbin->Sub_Bin_Capacity) && $storesubbin->Sub_Bin_Capacity!=''?$storesubbin->Sub_Bin_Capacity:''}}" required></p>
                                                            </div>
                                                        </div>
                                                        @php
                                                        $k++;
                                                        @endphp
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @php
                                                $j++;
                                                @endphp
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                    $i++;
                                    @endphp
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="tab1 remarkssss">
                            <div class="container">
                                <h5>Shelf Details</h5> <br>
                                <div class="main-sheilf" id="main-sheilf">
                                    <div id="outer-div-sherif">
                                        <div class="row">
                                            <div class="col-sm-5 form-group">
                                                <label>Total Shelf*</lable>
                                                    <p><input disabled type="number" class="form-control form-control-sm" placeholder="" name="Total_Shelf" value="{{isset($shelf->Total_Shelf) && $shelf->Total_Shelf!=''?$shelf->Total_Shelf:''}}" required></p>
                                            </div>
                                            <div class="col-sm-6 form-group">
                                                <label> Total Shelf Capacity*</lable>
                                                    <p><input disabled type="number" class="form-control form-control-sm" placeholder="" name="Total_Shelf_Capacity" value="{{isset($shelf->Total_Shelf_Capacity) && $shelf->Total_Shelf_Capacity!=''?$shelf->Total_Shelf_Capacity:''}}" required></p>
                                            </div>
                                        </div>
                                        <div id="dynamic_field_shelf" class="div-shelf">
                                            @php
                                            $i = 1;
                                            @endphp
                                            @if(count($shelfno)>0)
                                            @foreach($shelfno as $val)
                                            <div class="row" id="removesubshelf{{$i}}">
                                                <div class="col-sm-10  inner-div">
                                                    <div class="row">
                                                        <div class="col-sm-6 form-group">
                                                            <label>Shelf No.*</lable>
                                                                <p><input disabled type="number" class="form-control form-control-sm" placeholder="" name="Shelf_No[0]" value="{{isset($val->Shelf_No) && $val->Shelf_No!=''?$val->Shelf_No:''}}" required></p>
                                                        </div>
                                                        <div class="col-sm-6 form-group">
                                                            <label> Shelf Capacity*</lable>
                                                                <p><input disabled type="number" class="form-control form-control-sm" placeholder="" name="Shelf_Capacity[0]" value="{{isset($val->Shelf_Capacity) && $val->Shelf_Capacity!=''?$val->Shelf_Capacity:''}}" required></p>
                                                        </div>
                                                    </div>
                                                    <div id="dynamic_field_ravi{{$i}}">
                                                        @php
                                                        $j = 1;
                                                        @endphp
                                                        @foreach($val->subshelfsss as $subshelfsss)
                                                        <div class="row" id="removeshelf{{$i}}{{$j}}">
                                                            <div class="col-sm-6 form-group">
                                                                <label>Sub Shelf No.*</lable>
                                                                    <p><input disabled type="number" class="form-control form-control-sm" placeholder="" name="Sub_Shelf_No[0][0]" value="{{isset($subshelfsss->Sub_Shelf_No) && $subshelfsss->Sub_Shelf_No!=''?$subshelfsss->Sub_Shelf_No:''}}" required></p>
                                                            </div>
                                                            <div class="col-sm-4 form-group">
                                                                <label>Sub Shelf Capacity*</lable>
                                                                    <p><input disabled type="number" class="form-control form-control-sm" placeholder="" name="Sub_Shelf_Capacity[0][0]" value="{{isset($subshelfsss->Sub_Shelf_Capacity) && $subshelfsss->Sub_Shelf_Capacity!=''?$subshelfsss->Sub_Shelf_Capacity:''}}" required></p>
                                                            </div>
                                                        </div>
                                                        @php
                                                        $j++;
                                                        @endphp
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            @php
                                            $i++;
                                            @endphp
                                            @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <label for="State">Remark:</label>
                                <textarea disabled name="Remark" id="" cols="30" rows="3" class="form-control form-control-sm">{{isset($shelf->Remark) && $shelf->Remark!=''?$shelf->Remark:''}}</textarea>
                            </div>
                        </div>
                    </div>
                    <hr>
                    @php
                    $STEP = Session::get('STEP');
                    $EXT = Session::get('EXT');
                    @endphp
                    @if($showdata->Approve_status!='REJECT')
                    <form action="{{url('FactoryCreater/approve')}}" method="POST">
                        @csrf
                        <input type="hidden" name="approveID" value="{{isset($shelf->factory_id) && $shelf->factory_id!=''?$shelf->factory_id:''}}">
                        <div class="tab-content" id="myTabContent">
                            @if($showdata->Approve_status!='APPROVE' && in_array(1, $STEP) || in_array(2, $STEP) || in_array(3, $STEP) || isset($EXT[1]['Forward']))
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
                                        <input type="radio" id="radio4" name="during_approval" class="selector-item_radio" value="HOLD" {{isset($approvestatus->action) && $approvestatus->action=='HOLD'?'checked':''}} required>
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
                                <div id="showfields" class="row" style="display: {{isset($approvestatus->action) && $approvestatus->action=='HOLD'?'flex':'none'}};">
                                    <div class="col-sm-4 form-group">
                                        <label>Days For Holding</lable>
                                            <input type="date" style="border-radius: 12px;" name="days_for_holding" placeholder="Days For Holding" min="{{date('Y-m-d')}}" class="form-control form-control-sm requireddd" value="{{isset($approvestatus->days_for_holding) && $approvestatus->days_for_holding!=''?$approvestatus->days_for_holding:''}}">
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
                        <a href="{{url('FactoryCreater/view-approve/'.$nextID)}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                        @else
                        <a href="{{url('FactoryCreater/factory-approve')}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
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
    </div>
    </section>
</div>
</div>
</section>

@endsection
@push('custom-scripts')
<script>
    activeclass(5, 2);
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