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

    .tab1 {
        padding: 20px;
        border: 1px solid #a8adb1;
    }

    .tab2 {
        padding: 20px;
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
            <div class="row">
                <div class="container">
                    <br>
                    <div class="tab2" id='formdata' style="display: none;">
                        <div class="tabs">
                            <div class="row">
                                <div class="col-6">
                                    <h5>Add District</h5>
                                </div>
                                <div class="col-6">
                                    @if(isset($edit->id) && $edit->id!='')
                                    <a href="{{url('Master/district')}}"><button type="submit" class="btn btn1 float-right " style="margin: 5px;">Show District</button></a>
                                    @else
                                    <button type="submit" class="btn btn1 float-right " style="margin: 5px;">Show District</button>
                                    @endif
                                </div>
                            </div>
                            <br>
                            <br>
                            <div class="tab1">
                                <form action="{{url('Master/district_store')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="edit" value="{{isset($edit->id)&&$edit->id!=''?$edit->id:''}}">
                                    <div class="row">
                                        <div class="col-sm-4 form-group">
                                            <label>Country*</lable>
                                                <select class="form-select form-select-sm" name="country_id" id="country" required>
                                                    <option value="" selected disabled>Select Option</option>
                                                    @foreach($country as $val)
                                                    <option value="{{$val->id}}" {{isset($contry->country_id)&&$contry->country_id==$val->id?'selected':''}}>{{$val->name}}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>State*</lable>
                                                <select class="form-select form-select-sm" name="state_id" id="state" required>
                                                    <option value="" selected disabled>Select Option </option>
                                                    @foreach($state as $val)
                                                    <option value="{{$val->id}}" {{isset($edit->state_id)&&$edit->state_id==$val->id?'selected':''}}>{{$val->name}}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>District*</lable>
                                                <input class="form-control form-control-sm" type="text" name="city" placeholder="District" value="{{isset($edit->city)&&$edit->city!=''?$edit->city:''}}" required>
                                        </div>
                                    </div>
                                    <div style="overflow:auto;">
                                        <div style="float:right;">
                                            <button type="submit" class="btn btn-success float-right" style="margin: 5px;">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="tab2" id="tabledata">
                        <div class="row">
                            <div class="col-6">
                                <h5>Manage District</h5>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn1 float-right" style="margin: 5px;">Add District</button>
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="row">
                            <div class="container">
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th class="th-sm">SL. No.</th>
                                                <th class="th-sm">District</th>
                                                <th class="th-sm">State</th>
                                                <th class="th-sm">Country</th>
                                                <!-- <th class="th-sm">Operation</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($district as $key=>$val)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{$val->city}}</td>
                                                <td>{{isset($val->state->name) && $val->state->name!=''?$val->state->name:''}}</td>
                                                <td>{{isset($val->country->name) && $val->country->name!=''?$val->country->name:''}}</td>
                                                <!-- <td class="maindffd">
                                                    <a href="{{url('Master/district/'.$val->id)}}" class="btn btn-warning">Edit</a>
                                                    <a href="{{url('Master/delete_district/'.$val->id)}}" class="btn btn-danger">Delete</a>
                                                </td> -->
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
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
    @if(isset($edit -> id))
    $(document).ready(function() {
        $("#tabledata").toggle();
        $("#formdata").toggle();
    });
    @else
    $(".btn1").click(function() {
        $("#tabledata").toggle();
        $("#formdata").toggle();
    });
    @endif
    activeclass(1, 3);
</script>
<script>
    $(document).ready(function() {
        $('#country').change(function() {
            var countryId = $(this).val();
            $('#state').empty().prop('disabled', true);

            if (countryId) {
                $.ajax({
                    url: "{{url('FactoryCreater/get-states')}}" + '/' + countryId,
                    type: 'GET',
                    success: function(response) {
                        var options = '';
                        $.each(response, function(index, state) {
                            options += '<option value="' + state.id + '">' + state.name + '</option>';
                        });
                        $('#state').html(options).prop('disabled', false);
                    }
                });
            }
        });
    });
</script>
@endpush