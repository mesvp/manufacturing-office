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
                                            <h5>Add Machine Name</h5>
                                        </div>
                                        <div class="col-6">
                                            @if(isset($edit->id) && $edit->id!='')
                                            <a href="{{url('Master/Machine_Name')}}"><button type="submit" class="btn btn1 float-right " style="margin: 5px;">Show Machine Name</button></a>
                                            @else
                                            <button type="submit" class="btn btn1 float-right " style="margin: 5px;">Show Machine Name</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="tab1">
                                <form id="machineForm" action="{{url('Master/Machine_Name_store')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="edit" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                                    <div class="row">                                      
                                        <div class="col-sm-12 form-group">
                                            <label>Machine Name*</label>
                                            <input class="form-control form-control-sm" type="text" id="Machine_Name" name="Machine_Name" placeholder="Machine Name" value="{{isset($edit->Machine_Name) && $edit->Machine_Name!=''?$edit->Machine_Name:''}}">
                                        </div>
                                        <div class="col-sm-12 form-group">
                                            <label>Supplier Name*</label>
                                            <input class="form-control form-control-sm" type="text" name="Supplier_Name" placeholder="Supplier Name" value="{{isset($edit->Supplier_Name) && $edit->Supplier_Name!=''?$edit->Supplier_Name:''}}">
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <label>Machine Date</label>
                                            <input class="form-control form-control-sm" type="date" name="Machine_Date" placeholder="Machine Date" value="{{isset($edit->Machine_Date) && $edit->Machine_Date!=''?$edit->Machine_Date:''}}">
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            
                                        </div>
                                        <div class="col-sm-12 form-group">
                                            <label>Machine Purpose*</label>
                                            <input class="form-control form-control-sm" type="text" id="Machine_Purpose" name="Machine_Purpose" placeholder="Machine Purpose" value="{{isset($edit->Machine_Purpose) && $edit->Machine_Purpose!=''?$edit->Machine_Purpose:''}}">
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
                                <h5>Manage Machine Name</h5>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn1 float-right " style="margin: 5px;">Add Machine Name</button>
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
                                                <th class="th-sm">Machine Name</th>                                               
                                                <th class="th-sm">Supplier Name</th>                                               
                                                <th class="th-sm">Machine Date</th>                                               
                                                <th class="th-sm">Machine Purpose</th>                                               
                                                <!-- <th class="th-sm">Operation</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($Machine_Name as $key=>$val)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{$val->Machine_Name}}</td>                                               
                                                <td>{{$val->Supplier_Name}}</td>                                               
                                                <td>{{$val->Machine_Date}}</td>                                               
                                                <td>{{$val->Machine_Purpose}}</td>                                               
                                                <!-- <td class="maindffd">
                                                    <a href="{{url('Master/Machine_Name/'.$val->id)}}" class="btn btn-warning">Edit</a>
                                                    <a href="{{url('Master/delete_Machine_Name/'.$val->id)}}" class="btn btn-danger">Delete</a>
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
    activeclass(3,8);

    // JavaScript validation for Machine Name and Machine Purpose
    document.getElementById('machineForm').addEventListener('submit', function(event) {
        var machineName = document.getElementById('Machine_Name').value.trim();
        var machinePurpose = document.getElementById('Machine_Purpose').value.trim();

        if (!machineName) {
            alert('Machine Name is required');
            event.preventDefault();
        } else if (!machinePurpose) {
            alert('Machine Purpose is required');
            event.preventDefault();
        }
    });
</script>
@endpush
