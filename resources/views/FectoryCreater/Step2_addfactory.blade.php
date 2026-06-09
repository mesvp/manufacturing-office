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
        /*margin: 100px auto;*/
        font-family: Raleway;
        /*padding: 40px;*/
        width: 100%;
        /*min-width: 300px;*/
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

    /* Mark input class="form-control form-control-sm" boxes that gets an error on validation: */
    input .invalid {
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

    select.form-control form-control-sm {
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

    .downloadfile {
        display: flex;
    }

    .downloadfile div {
        margin: 0px 20px;
    }

    .downloadfile i.fa.fa-remove {
        color: red;
    }
</style>
<!--<br><br>-->
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
        <section class="section">
            <!-- <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="text-muted">Factory Creation</a></li>
                <li class="breadcrumb-item active text-" aria-current="page">Inputer List </li>
            </ol> -->
            <div class="addbtn extra">
                <a href="{{url('FactoryCreater/step2')}}" class="btn btn-info"> <i class="fa fa-arrow-left"></i> BACK</a>
                <a href="{{url('FactoryCreater/List')}}" class="btn btn-info" style="margin-left:10px"> <i class="fa fa-home"></i> Home</a>
            </div>
            <div class="tab-for-fac">
                <div class="line"></div>
                <div class="ul-div">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link {{$formdata['step1']}} anchor" aria-current="page" href="{{url('FactoryCreater/step1')}}">Address</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{$formdata['step2']}} active anchor" href="#">Statutory</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{$formdata['step3']}} anchor" href="{{url('FactoryCreater/step3')}}">Land & Building</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{$formdata['step4']}} anchor" href="{{url('FactoryCreater/step4')}}">Plant & Machinery</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{$formdata['step5']}} anchor" href="{{url('FactoryCreater/step5')}}">Amenities</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{$formdata['step6']}} anchor" href="{{url('FactoryCreater/step6')}}">Electricity</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{$formdata['step7']}} anchor" href="{{url('FactoryCreater/step7')}}">Warehouse & Room</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{$formdata['step8']}} anchor" href="{{url('FactoryCreater/step8')}}">Office Asset</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{$formdata['step9']}} anchor" href="{{url('FactoryCreater/step9')}}">Power House</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{$formdata['step10']}} anchor" href="{{url('FactoryCreater/step10')}}">Store</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="container">
                    <form action="{{url('FactoryCreater/statutory')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input class="form-control form-control-sm" type="hidden" name="edit" value="{{isset($statury->id) && $statury->id!=''?$statury->id:''}}">
                        <div>
                            <br>
                            <div class="tabs">
                                <h5>Statutory Details</h5>
                                <div class="tab1">
                                    <div class="row">
                                        <div class="col-sm-6 form-group">
                                            <label>GST IN No.</lable>
                                                <div class="field-wrap">
                                                    <select name="gst_no" class="form-select form-select-sm" >
                                                        <option value="" selected disabled>Select</option>
                                                        @foreach($gst as $val)
                                                        <option value="{{$val->id}}" {{isset($statury->gst_no) && $statury->gst_no==$val->id?'selected':''}}>{{$val->gst_no}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <label>GST IN Certificate Attachement*</lable>
                                                <p><input class="form-control form-control-sm " type="file" name="gst_in_certificate_attachement" value="{{isset($statury->gst_in_certificate_attachement) && $statury->gst_in_certificate_attachement!=''?$statury->gst_in_certificate_attachement:''}}" {{isset($statury->id)&&$statury->id!=''?'':''}}></p>
                                                @if(isset($statury->gst_in_certificate_attachement) && $statury->gst_in_certificate_attachement!='')
                                                <div class="downloadfile" id="gst_in_certificate_attachement">
                                                    <div>{{substr(isset($statury->gst_in_certificate_attachement) && $statury->gst_in_certificate_attachement!=''?$statury->gst_in_certificate_attachement:'', 15)}}</div>
                                                    <p><a href="{{url('storage/'.(isset($statury->gst_in_certificate_attachement) && $statury->gst_in_certificate_attachement!=''?$statury->gst_in_certificate_attachement:''))}}" target="_blank" download><i class="fa fa-download" aria-hidden="true"></i></a></p>
                                                    <div><a href="javascript:;" class="remove-file" onclick="removeFile('gst_in_certificate_attachement',{{ $statury->id}})"><i class="fa fa-remove"></i> </a></div>
                                                </div>
                                                @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6 form-group">
                                            <label>Pan No.*</lable>
                                                <div class="field-wrap">
                                                    <select name="pan" class="form-select form-select-sm" >
                                                        <option value="" selected disabled>Select</option>
                                                        @foreach($pan as $val)
                                                        <option value="{{$val->id}}" {{isset($statury->pan) && $statury->pan==$val->id?'selected':''}}>{{$val->pan_no}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <label>PAN Attachement*</lable>
                                                <p><input class="form-control form-control-sm" type="file" name="pan_attachement" {{isset($statury->id)&&$statury->id!=''?'':''}}></p>
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
                                                    <select name="factory_license_no" class="form-select form-select-sm" >
                                                        <option value="" selected disabled>Select</option>
                                                        @foreach($factoryLicense as $val)
                                                        <option value="{{$val->id}}" {{isset($statury->factory_license_no) && $statury->factory_license_no==$val->id?'selected':''}}>{{$val->factory_license_no}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <label>Factory License Attachement*</lable>
                                                <p><input class="form-control form-control-sm" type="file" name="factory_license_attachement" {{isset($statury->id)&&$statury->id!=''?'':''}}></p>
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
                                                    <select name="labour_license_no" class="form-select form-select-sm" >
                                                        <option value="" selected disabled>Select</option>
                                                        @foreach($labourLicense as $val)
                                                        <option value="{{$val->id}}" {{isset($statury->labour_license_no) && $statury->labour_license_no==$val->id?'selected':''}}>{{$val->labour_license_no}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <label>Labour License Attachement*</lable>
                                                <p><input class="form-control form-control-sm" type="file" name="labour_license_attachement" {{isset($statury->id)&&$statury->id!=''?'':''}}></p>
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
                                                    <select name="pollution_certificate_no" class="form-select form-select-sm" >
                                                        <option value="" selected disabled>Select</option>
                                                        @foreach($polution as $val)
                                                        <option value="{{$val->id}}" {{isset($statury->pollution_certificate_no) && $statury->pollution_certificate_no==$val->id?'selected':''}}>{{$val->polution_certificate}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <label>Pollution Certificate Attachement*</lable>
                                                <p><input class="form-control form-control-sm" type="file" name="pollution_certificate_attachement" {{isset($statury->id)&&$statury->id!=''?'':''}}></p>
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
                                                        <input class="form-control form-control-sm" type="hidden" name="editother[]" value="{{isset($val->id) && $val->id!=''?$val->id:''}}">
                                                        <input class="form-control form-control-sm" type="hidden" name="idd[]" value="{{$i}}">
                                                        <td>
                                                            <div class="field-wrap">
                                                                <label style="display:flex;" id="labell{{$i}}">Enter Field Manually</label>
                                                                <input class="form-control form-control-sm" type="text" autocomplete="off" class="form-control form-control-sm" oninput="updateLabel(this,'{{$i}}')" placeholder="Enter Manually" name="add_field_manually[]" value="{{isset($val->add_field_manually) && $val->add_field_manually!=''?$val->add_field_manually:''}}">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="field-wrap">
                                                                <label style="display:flex;">Enter Document No</label>
                                                                <input class="form-control form-control-sm" type="text" autocomplete="off" class="form-control form-control-sm" placeholder="Enter Document No" name="add_field_manually_second[]" value="{{isset($val->add_field_manually_second) && $val->add_field_manually_second!=''?$val->add_field_manually_second:''}}">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="field-wrap">
                                                                <label style="display:flex;">Add Field Attachement Manually</label>
                                                                <input class="form-control form-control-sm" type="file" placeholder="Auto Fetch From Statutory" name="add_field_attachement_manually{{$i}}">
                                                                @if(isset($val->add_field_attachement_manually) && $val->add_field_attachement_manually!='')
                                                                <div class="downloadfile" id="add_field_attachement_manually">
                                                                    <div>{{substr(isset($val->add_field_attachement_manually) && $val->add_field_attachement_manually!=''?$val->add_field_attachement_manually:'', 15)}}</div>
                                                                    <p><a href="{{url('storage/'.(isset($val->add_field_attachement_manually) && $val->add_field_attachement_manually!=''?$val->add_field_attachement_manually:''))}}" target="_blank" download><i class="fa fa-download" aria-hidden="true"></i></a></p>
                                                                    <div><a href="javascript:;" class="remove-file" onclick="removeFile('add_field_attachement_manually',{{ $val->id}})"><i class="fa fa-remove"></i> </a></div>
                                                                </div>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        @if($i==1)
                                                        <td><a href="javascript:;" id="add121" class="btn btn-success btn btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
                                                        @else
                                                        <td><a href="javascript:;" onclick="remove({{$i}})" class="btn btn-danger btn btn-sm btn_remove mt-4">X</a></td>
                                                        @endif
                                                    </tr>
                                                    @php
                                                    $i++;
                                                    @endphp
                                                    @endforeach
                                                    @else
                                                    <tr id="row">
                                                        <input class="form-control form-control-sm" type="hidden" name="idd[]" value="0">
                                                        <td>
                                                            <div class="field-wrap">
                                                                <label style="display:flex;" id="labell0">Enter Field Manually</label>
                                                                <input class="form-control form-control-sm" type="text" autocomplete="off" class="form-control form-control-sm" oninput="updateLabel(this,'0')" placeholder="Enter Manually" name="add_field_manually[0]">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="field-wrap">
                                                                <label style="display:flex;">Enter Document No</label>
                                                                <input class="form-control form-control-sm" type="text" autocomplete="off" class="form-control form-control-sm" placeholder="Enter Document No" name="add_field_manually_second[0]">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="field-wrap">
                                                                <label style="display:flex;">Add Field Attachement Manually</label>
                                                                <input class="form-control form-control-sm" type="file" placeholder="Auto Fetch From Statutory" name="add_field_attachement_manually0">
                                                            </div>
                                                        </td>
                                                        <td><a href="javascript:;" id="add121" class="btn btn-success btn btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
                                                    </tr>
                                                    @endif
                                                </table>
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label for="State">Remarks:</label>
                                            <input type="text" name="Remarks" class="form-control form-control-sm" value="{{isset($statury->Remarks) && $statury->Remarks!=''?$statury->Remarks:''}}" placeholder="Remarks">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="overflow:auto;">
                                <div style="float:right;">
                                    <button type="button" id="draft" class="btn btn1 float-right" style="margin: 5px;">Draft & Save</button>
                                    <a href="" class="btn btn1 float-right" style="margin: 5px; display:{{isset($statury->id) && $statury->id != ''?'none':'block'}}">Clear All</a>
                                    <!-- <button type="button" class="btn btn1 float-right">Previous</button> -->
                                    <button type="submit" class="btn btn1 float-right">Submit & Next</button>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
            <br> <br>
    </div>
</div>
</div>
</section>
@endsection
@push('custom-scripts')
<script>
    function updateLabel(input, index) {
        var label = document.getElementById('labell' + index);
        label.textContent = input.value;
    }
</script>
<script>
    function removeFile(name, fileId) {
        $.ajax({
            url: 'remove-file/' + fileId + '/' + name,
            method: 'GET',
            success: function(response) {
                $('#' + name).remove();
            }
        });
    }
</script>
</script>
<script>
    $(document).ready(function() {
        var stuothercount = parseInt('{{isset($stuothercount)?$stuothercount:1}}');
        var i = stuothercount;
        $('#add121').click(function() {
            i++;
            $('#dynamic_field1').append('<tr id="row' + i + '"><input class="form-control form-control-sm" type="hidden" name="idd[]" value="' + i + '"><td><div class="field-wrap"><label style="display:flex;" id="labell' + i + '">Enter Field Manually</label><input class="form-control form-control-sm" type="text" autocomplete="off" class="form-control form-control-sm" oninput="updateLabel(this,' + i + ')" placeholder="Enter Manually" name="add_field_manually[]"></div></td> <td><div class="field-wrap"><label style="display:flex;">Enter Document No</label><input class="form-control form-control-sm" type="text" autocomplete="off" class="form-control form-control-sm" placeholder="Enter Document No" name="add_field_manually_second[]" ></div></td><td><div class="field-wrap"><label style="display:flex;">Add Field Attachement Manually</label><input class="form-control form-control-sm"  type="file"  placeholder="Auto Fetch From Statutory" name="add_field_attachement_manually' + i + '"></div></td><td><a href="javascript:;" onclick="remove(' + i + ')" class="btn btn-danger btn btn-sm btn_remove mt-4">X</a></td></tr>');
        });
    });

    function remove(id) {
        $("#row" + id).remove();
    }
</script>
@endpush