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
        <section class="section">
            <div class="addbtn extra">
                <a href="{{url('FactoryCreater/List')}}" class="btn btn-info"> <i class="fa fa-arrow-left"></i> BACK</a>
                <a href="{{url('FactoryCreater/List')}}" class="btn btn-info" style="margin-left:10px"> <i class="fa fa-home"></i> Home</a>
            </div>
            <div class="tab-for-fac">
                <div class="line"></div>
                <div class="ul-div">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link {{$formdata['step1']}}  active anchor" aria-current="page" href="#">Address</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{$formdata['step2']}} anchor" href="{{url('FactoryCreater/step2')}}">Statutory</a>
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
                    <br>
                    <div>
                        <div class="tabs">
                            <div class="row">
                                <div class="col-4">
                                </div>
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
                                <form action="{{url('FactoryCreater/address')}}" method="POST">
                                    @csrf
                                    <input class="form-control" type="hidden" name="edit" value="{{isset($addresssdetails->id) && $addresssdetails->id!=''?$addresssdetails->id:''}}">
                                    <div class="row">
                                        <div class="col-sm-4 form-group">
                                            <label>Organization*</lable>
                                                <select class="form-select form-select-sm" name="organization" required>
                                                    <option value="" selected disabled>Select Option </option>
                                                    @foreach($Organization as $val)
                                                    <option value="{{$val->id}}" {{isset($addresssdetails->organization) && $addresssdetails->organization==$val->id?'selected':''}}>{{$val->organisation}}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>Name Of Unit*</lable>
                                                <select class="form-select form-select-sm" name="name_of_unit" required>
                                                    <option value="" selected disabled>Select Option</option>
                                                    @foreach($nameOfUnit as $val)
                                                            <option value="{{$val->id}}" {{isset($addresssdetails->name_of_unit) && $addresssdetails->name_of_unit==$val->id?'selected':''}}>{{$val->pname}}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>Country*</lable>
                                                <select class="form-select form-select-sm" name="country" id="country" required readonly>
                                                    @foreach($country as $val)
                                                    <option value="{{$val->id}}" {{isset($addresssdetails->country) && $addresssdetails->country==$val->id?'selected':''}}>{{$val->name}}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>State*</lable>
                                                <select class="form-select form-select-sm" name="state" id="state" required>
                                                    <option value="" selected disabled>Select Option</option>
                                                    @foreach($state as $val)
                                                    <option value="{{$val->id}}" {{isset($addresssdetails->state) && $addresssdetails->state==$val->id?'selected':''}}>{{$val->sname}}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>District*</lable>
                                                <select class="form-select form-select-sm" name="district" id="district" required>
                                                    <option value="" selected disabled>Select Option</option>
                                                    @foreach($city as $val)
                                                    <option value="{{$val->id}}" {{isset($addresssdetails->district) && $addresssdetails->district==$val->id?'selected':''}}>{{$val->distname}}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4 form-group">
                                            <table class="table" id="dynamic_field">
                                                @php
                                                $i = 1;
                                                @endphp
                                                @if(count($address)>0)
                                                @foreach($address as $val)
                                                <tr id="row{{$i}}">
                                                    <input class="form-control" type="hidden" name="addressID[]" value="{{isset($val->id) && $val->id!=''?$val->id:''}}">
                                                    <td>
                                                        <div class="field-wrap">
                                                            <label style="display:flex;">
                                                                Address<span class="req">*</span>
                                                            </label>
                                                            <input class="form-control" type="text" class="form-control" name="address[]" placeholder="Address" value="{{isset($val->address) && $val->address!=''?$val->address:''}}" required />
                                                        </div>
                                                    </td>
                                                    @if($i==1)
                                                    <td><button type="button" style="margin-top: 26px !important;" name="add" id="add" class="btn btn-success  btn-sm "><i class="fa fa-plus" aria-hidden="true"></i></button></td>
                                                    @else
                                                    <td><button style="margin-top: 26px !important;" name="remove" id="{{$i}}" class="btn btn-danger btn-sm btn_remove">X</button></td>
                                                    @endif

                                                </tr>
                                                @php
                                                $i++;
                                                @endphp
                                                @endforeach
                                                @else
                                                <tr>
                                                    <td>
                                                        <div class="field-wrap">
                                                            <label style="display:flex;">
                                                                Address<span class="req">*</span>
                                                            </label>
                                                            <input class="form-control form-control-sm" type="text" autocomplete="off" class="form-control" name="address[]" placeholder="Address" required />
                                                        </div>
                                                    </td>
                                                    <td><button type="button" style="margin-top: 26px !important;" name="add" id="add" class="btn btn-success  btn-sm"><i class="fa fa-plus" aria-hidden="true"></i></button></td>
                                                </tr>
                                                @endif
                                            </table>
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>Pincode*</lable>
                                                <input class="form-control form-control-sm" type="text" maxlength="6" onkeypress='return event.charCode >= 48 && event.charCode <= 57' name="pincode" placeholder="Pincode" value="{{isset($addresssdetails->pincode) && $addresssdetails->pincode!=''?$addresssdetails->pincode:''}}" required>
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label for="State">Remarks:</label>
                                            <input type="text" name="remarks" id="" cols="30" rows="5" class="form-control form-control-sm" placeholder="Remarks" value="{{isset($addresssdetails->remarks) && $addresssdetails->remarks!=''?$addresssdetails->remarks:''}}">
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div style="overflow:auto;">
                            <div style="float:right;">
                                <button type="button" id="draft" class="btn btn1 float-right" style="margin: 5px;">Draft & Save</button>
                                <a href="" class="btn btn1 float-right" style="margin: 5px; display:{{isset($addresssdetails->id) && $addresssdetails->id != ''?'none':'block'}}">Clear All</a>                            
                                <button type="submit" class="btn btn1 float-right" style="margin: 5px;">Submit & Next</button>
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
        var i = 1;
        $('#add').click(function() {
            i++;
            $('#dynamic_field').append('<tr id="row' + i + '"><td><div class="field-wrap"><div class="field-wrap"><label>Address</label><input class="form-control" type="text" autocomplete="off" class="form-control" name="address[]"/></div><td><button style="margin-top: 26px !important;" name="remove" id="' + i + '" class="btn btn-danger btn-sm btn_remove">X</button></td></tr>');
        });
        $(document).on('click', '.btn_remove', function() {
            var button_id = $(this).attr("id");
            $("#row" + button_id + "").remove();
        });
    });
</script>
<script>
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
                            options += '<option value="' + city.id + '">' + city.distname + '</option>';
                        });
                        $('#district').html(options).prop('disabled', false);
                    }
                });
            }
        });
    });
</script>
@endpush