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
            <div class="row">
                <div class="container">
                    <br>
                    <div class="tab2" id='formdata' style="display: none;">
                        <div class="tabs">
                            <div class="row">
                                <div class="col-4">
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-6">
                                            <h5>Add Factory License No</h5>
                                        </div>
                                        <div class="col-6">
                                            <button type="submit" class="btn btn1 float-right " style="margin: 5px;">Show Factory License No</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="tab1">
                                <form action="{{url('Master/factory_license_store')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="edit" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                                    <div class="row">
                                        <div class="col-sm-12 form-group">
                                            <label>Factory License No.*</lable>
                                                <input class="form-control form-control-sm" type="text" name="factory_license_no" placeholder="Factory License No." value="{{isset($edit->factory_license_no) && $edit->factory_license_no!=''?$edit->factory_license_no:''}}" required>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div style="overflow:auto;">
                            <div style="float:right;">
                                <button type="submit" class="btn btn-success float-right" style="margin: 5px;">Submit</button>
                            </div>
                        </div>
                        </form>
                    </div>
                    <div class="tab2" id="tabledata">
                        <div class="row">
                            <div class="col-6">
                                <h5>Manage Factory License No</h5>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn1 float-right " style="margin: 5px;">Add Factory License No</button>
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
                                                <th class="th-sm">Factory License No</th>
                                                <!-- <th class="th-sm">Operation</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($factorylicense as $key=>$val)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{$val->factory_license_no}}</td>
                                                <!-- <td class="maindffd">
                                                    <a href="{{url('Master/factory_license/'.$val->id)}}" class="btn btn-warning">Edit</a>
                                                    <a href="{{url('Master/delete_factory_license/'.$val->id)}}" class="btn btn-danger">Delete</a>
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
    activeclass(2,1);
</script>
@endpush