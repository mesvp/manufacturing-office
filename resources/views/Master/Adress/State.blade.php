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

    table#dynamic_field {
        margin-top: -14px;
    }

    .tab2 {
        padding: 20px;

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
                                    <h5>Add State</h5>
                                </div>
                                <div class="col-6">
                                    @if(isset($edit->id) && $edit->id!='')
                                    <a href="{{url('Master/state')}}"><button type="submit" class="btn btn1 float-right " style="margin: 5px;">Show State</button></a>
                                    @else
                                    <button type="submit" class="btn btn1 float-right " style="margin: 5px;">Show State</button>
                                    @endif
                                </div>
                            </div>
                            <br>
                            <br>
                            <div class="tab1">
                                <form action="{{url('Master/state_store')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="edit" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                                    <div class="row">
                                        <div class="col-sm-6 form-group">
                                            <label>Country*</lable>
                                                <select class="form-select form-select-sm" name="country_id" required>
                                                    <option value="null" selected disabled>Select Option </option>
                                                    @foreach($country as $val)
                                                    <option value="{{$val->id}}" {{isset($edit->country_id) && $edit->country_id==$val->id?'selected':''}}>{{$val->name}}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <label>State*</lable>
                                                <input class="form-control form-control-sm" type="text" name="name" value="{{isset($edit->name) && $edit->name!=''?$edit->name:''}}" placeholder="State" required>
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
                                <h5>Manage State</h5>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn1 float-right" style="margin: 5px;">Add State</button>
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
                                                <th class="th-sm">State</th>
                                                <th class="th-sm">Country</th>
                                                <!-- <th class="th-sm">Operation</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($state as $key=>$val)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{$val->name}}</td>
                                                <td>{{isset($val->country->name) && $val->country->name!=''?$val->country->name:''}}</td>
                                                <!-- <td class="maindffd">
                                                    <a href="{{url('Master/state/'.$val->id)}}" class="btn btn-warning">Edit</a>
                                                    <a href="{{url('Master/delete_state/'.$val->id)}}" class="btn btn-danger">Delete</a>
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
    @if(isset($edit->id))
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
    activeclass(1,2);   
</script>
@endpush