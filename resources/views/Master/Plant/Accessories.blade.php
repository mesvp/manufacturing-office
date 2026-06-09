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
                                            <h5>Add Accessories</h5>
                                        </div>
                                        <div class="col-6">
                                            @if(isset($edit->id) && $edit->id!='')
                                            <a href="{{url('Master/Accessories')}}"><button type="submit" class="btn btn1 float-right " style="margin: 5px;">Show Accessories</button></a>
                                            @else
                                            <button type="submit" class="btn btn1 float-right " style="margin: 5px;">Show Accessories</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="tab1">
                                <form action="{{url('Master/Accessories_store')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="edit" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                                    <div class="row">
                                        <div class="col-sm-4 form-group">
                                            <label>Machine Name*</lable>
                                                <select class="form-select form-select-sm" name="Machine_Name_id" id="machinename" required>
                                                    <option value="null" selected disabled>Select Option</option>
                                                    @foreach($Machine_Name as $val)
                                                    <option value="{{$val->id}}" {{isset($editcode->Machine_Name_id) && $editcode->Machine_Name_id==$val->id?'selected':''}}>{{$val->Machine_Name}}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>Machine Code*</lable>
                                                <select class="form-select form-select-sm" name="Machine_Code_id" id="machinecode" required>
                                                    <option value="null" selected disabled>Select Option </option>
                                                    @foreach($MachineCode as $val)
                                                    <option value="{{$val->id}}" {{isset($edit->Machine_Code_id) && $edit->Machine_Code_id==$val->id?'selected':''}}>{{$val->Machine_Code}}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>Accessories*</lable>
                                                <input class="form-control form-control-sm" type="text" name="Accessories" placeholder="Accessories" value="{{isset($edit->Accessories) && $edit->Accessories!=''?$edit->Accessories:''}}" required>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div style="overflow:auto;">
                            <div style="float:right;">
                                <button type="submit" class="btn btn1 float-right" style="margin: 5px;">Submit</button>
                            </div>
                        </div>
                        </form>
                    </div>
                    <div class="tab2" id="tabledata">
                        <div class="row">
                            <div class="col-6">
                                <h5>Manage Accessories</h5>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn1 float-right " style="margin: 5px;">Add Accessories</button>
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
                                                <th class="th-sm">Accessories</th>
                                                <th class="th-sm">Machine Code</th>
                                                <th class="th-sm">Machine Name</th>
                                                <!-- <th class="th-sm">Operation</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($Accessories as $key=>$val)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{$val->Accessories}}</td>
                                                <td>{{isset($val->machinecode->Machine_Code) && $val->machinecode->Machine_Code!=''?$val->machinecode->Machine_Code:''}}</td>
                                                <td>{{isset($val->MachineName->Machine_Name) && $val->MachineName->Machine_Name!=''?$val->MachineName->Machine_Name:''}}</td>
                                                <!-- <td class="maindffd">
                                                    <a href="{{url('Master/Accessories/'.$val->id)}}" class="btn btn-warning">Edit</a>
                                                    <a href="{{url('Master/delete_Accessories/'.$val->id)}}" class="btn btn-danger">Delete</a>
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
    activeclass(3, 10);
</script>
<script>
    $('#machinename').change(function() {
        var MachineName = $(this).val();
        $('#machinecode').empty().prop('disabled', true);

        if (MachineName) {
            $.ajax({
                url: "{{url('FactoryCreater/get-machinecode')}}" + '/' + MachineName,
                type: 'GET',
                success: function(response) {
                    var options = '';
                    $.each(response, function(index, machinecode) {
                        options += '<option value="' + machinecode.id + '">' + machinecode.Machine_Code + '</option>';
                    });
                    $('#machinecode').html(options).prop('disabled', false);
                }
            });
        }
    });
</script>
@endpush