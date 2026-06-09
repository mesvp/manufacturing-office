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
        @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
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
                                    <h5>Employee Details</h5>
                                </div>
                                {{-- <div class="col-6">
                                    @if(isset($edit->id) && $edit->id!='')
                                    <a href="{{url('Master/Add_Employee')}}"><button type="submit" class="btn btn1 float-right " style="margin: 5px;">Show Employee</button></a>
                                    @else
                                    <button type="submit" class="btn btn1 float-right " style="margin: 5px;">Show Employee</button>
                                    @endif
                                </div> --}}
                            </div>
                            <br>
                            <br>

                            <div class="tab1">
                                <form action="{{url('Master/store_Employee')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="edit" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                                    @if(isset($edit->id))
                                    <div class="row">
                                        <div class="col-sm-3 form-group">
                                            <label>Employee Name*</lable>
                                                <input class="form-control form-control-sm" readonly type="text" name="name" placeholder="Employee Name" value="{{isset($edit->fullname) && $edit->fullname!=''?$edit->fullname:''}}" required>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>Employee Email*</lable>
                                                <input class="form-control form-control-sm" readonly type="email" name="email" placeholder="Employee Email" value="{{isset($edit->uemail) && $edit->uemail!=''?$edit->uemail:''}}" required>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>Department*</lable>
                                                <select class="select form-select form-select-sm js-example-basic-multiple-limit" name="Departments[]" required multiple>
                                                    @foreach($Departments as $val)
                                                    <option value="{{$val->id}}" {{in_array($val->id, $dep) ? 'selected' : ''}}>{{$val->Departments}}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                        {{-- <div class="col-sm-3 form-group">
                                            <label>Password*</lable>
                                                <input class="form-control form-control-sm" type="text" name="password" placeholder="Password">
                                        </div> --}}
                                    </div>
                                    @else
                                    <div class="col-sm-3 form-group">
                                        <label>Employee Name*</lable>
                                            <select class="select form-select form-select-sm js-example-basic-multiple-limit" name="employee_name[]" required multiple>
                                                <!--@foreach($empname as $val)-->
                                                <!--<option value="{{$val->id}}">{{$val->fullname}}</option>-->
                                                <!--@endforeach-->
                                                 @foreach ($empname as $val)
                                                    <option value="{{ $val->id }}"
                                                        class="{{ $val->status_class }}">
                                                        {{ $val->details }} {{ $val->status_text }}
                                                    </option>
                                                 @endforeach
                                            </select>
                                    </div>
                                    @endif
                                    <div style="overflow:auto;">
                                        <div style="float:left;">
                                            <button type="submit" class="btn btn-success float-left" style="margin: 5px;">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="tab2" id="tabledata">
                        <div class="row">
                            <div class="col-6">
                                <h5>Manage Employee</h5>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn1 float-right" style="margin: 5px;">Access Permission</button>
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
                                                <th class="th-sm">Employee Name</th>
                                                <th class="th-sm">Employee Email</th>
                                                <th class="th-sm">Uname</th>
                                                <th class="th-sm">Department</th>
                                                <th class="th-sm">Operation</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($Data as $key=>$val)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{isset($val->fullname) && $val->fullname!=''?$val->fullname:''}}</td>
                                                <td>{{isset($val->uemail) && $val->uemail!=''?$val->uemail:''}}</td>
                                                <td>{{isset($val->uname) && $val->uname!=''?$val->uname:''}}</td>
                                                <td>
                                                    <ul>
                                                        @foreach($val->Departmentss as $vals)
                                                        <li>
                                                            {{isset($vals->depart->Departments) && $vals->depart->Departments!=''?$vals->depart->Departments:''}}
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                                
                                                <td class="maindffd">
                                                    <a href="{{url('Master/Add_Employee/'.$val->id)}}" class="btn btn-warning">Edit</a>
                                                    <a href="{{url('Master/Remove_Employee/'.$val->id)}}" class="btn btn-danger" onclick="return confirm('Are you sure you want to remove this employee?')">X</a>
                                                </td>
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
    activeclass(27, 1);
</script>
<script>
    const department = $('#department').filterMultiSelect({
        placeholderText: "nothing selected",
        filterText: "Filter",
        selectAllText: "Select All",
        labelText: "",
        selectionLimit: 0,
        caseSensitive: false,
        allowEnablingAndDisabling: true,
    });

    function remove(id){
        alert(id);
    }
</script>
@endpush