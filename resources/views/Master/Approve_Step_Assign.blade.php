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

    li {
        list-style: none;
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
                                            <h5>Assign</h5>
                                        </div>
                                        <div class="col-6">
                                            @if(isset($edit->id) && $edit->id!='')
                                            <a href="{{url('Master/contact_person')}}"><button type="submit" class="btn btn1 ShowHide float-right " style="margin: 5px;">Show Assigned List</button></a>
                                            @else
                                            <button type="submit" class="btn btn1 ShowHide float-right " style="margin: 5px;">Show Assigned List</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="tab1">
                                <form id="myForm" action="{{url('Master/store_Assign_Step')}}" method="POST">
                                    @csrf
                                    @foreach($Departments as $key=> $depval)
                                    <b>{{$depval->Departments}}</b>
                                    <ul>
                                        <li>Inputer</li>
                                        <ul>
                                            <li>
                                                <div class="col-sm-4 form-group">
                                                    <label>Employee Name*</lable>
                                                        <select name="inputer[{{$depval->id}}][]" class="form-select form-select-sm js-example-basic-multiple-limit" multiple>
                                                            @foreach($employeeName as $val)
                                                            <option value="{{$val->id}}" {{in_array($val->id,$depview[$depval->id]['inputer'])?'selected':''}}>{{$val->fullname}}</option>
                                                            @endforeach
                                                        </select>
                                                </div>
                                            </li>
                                        </ul>
                                        <li>Approver</li>
                                        <ul>
                                            <li>Step 1</li>
                                            <ul>
                                                <li>
                                                    <div class="col-sm-4 form-group">
                                                        <label>Employee Name*</lable>
                                                            <select name="Approve_step1[{{$depval->id}}][]" class="form-select form-select-sm js-example-basic-multiple-limit" multiple>
                                                                @foreach($employeeName as $val)
                                                                <option value="{{$val->id}}" {{in_array($val->id,$depview[$depval->id]['Approve_step1'])?'selected':''}}>{{$val->fullname}}</option>
                                                                @endforeach
                                                            </select>
                                                    </div>
                                                </li>
                                            </ul>
                                            <li>Step 2</li>
                                            <ul>
                                                <li>
                                                    <div class="col-sm-4 form-group">
                                                        <label>Employee Name*</lable>
                                                            <select name="Approve_step2[{{$depval->id}}][]" class="form-select form-select-sm js-example-basic-multiple-limit" multiple>
                                                                @foreach($employeeName as $val)
                                                                <option value="{{$val->id}}" {{in_array($val->id,$depview[$depval->id]['Approve_step2'])?'selected':''}}>{{$val->fullname}}</option>
                                                                @endforeach
                                                            </select>
                                                    </div>
                                                </li>
                                            </ul>
                                            <li>Step 3</li>
                                            <ul>
                                                <li>
                                                    <div class="col-sm-4 form-group">
                                                        <label>Employee Name*</lable>
                                                            <select name="Approve_step3[{{$depval->id}}][]" class="form-select form-select-sm js-example-basic-multiple-limit" multiple>
                                                                @foreach($employeeName as $val)
                                                                <option value="{{$val->id}}" {{in_array($val->id,$depview[$depval->id]['Approve_step3'])?'selected':''}}>{{$val->fullname}}</option>
                                                                @endforeach
                                                            </select>
                                                    </div>
                                                </li>
                                            </ul>
                                        </ul>
                                    </ul>
                                    <hr>
                                    @endforeach
                                    <div style="overflow:auto;">
                                        <div style="float:right;">
                                            <button type="submit" id="submitButton" class="btn btn1 float-right" style="margin: 5px;">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="tab2" id="tabledata">
                        <div class="row">
                            <div class="col-6">
                                <h5>Assigned List</h5>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn1 ShowHide float-right" style="margin: 5px;">Assign</button>
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
                                                <th class="th-sm">Page Name</th>
                                                <th class="th-sm">Authority</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($Departments as $key=> $depval)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{$depval->Departments}}</td>
                                                <td>
                                                    <ol>
                                                        <li>Inputer</li>
                                                        <ul>
                                                            @foreach($employeeName as $val)
                                                            @if(in_array($val->id,$depview[$depval->id]['inputer']))
                                                            <li>{{in_array($val->id,$depview[$depval->id]['inputer'])?$val->fullname:''}}({{in_array($val->id,$depview[$depval->id]['inputer'])?$val->uemail:''}})</li>
                                                            @endif
                                                            @endforeach
                                                        </ul>
                                                        <li>Approve</li>

                                                        <ul>
                                                            <li>Step 1</li>
                                                            <ul>
                                                                @foreach($employeeName as $val)
                                                                @if(in_array($val->id,$depview[$depval->id]['Approve_step1']))
                                                                <li>{{in_array($val->id,$depview[$depval->id]['Approve_step1'])?$val->fullname:''}}({{in_array($val->id,$depview[$depval->id]['Approve_step1'])?$val->uemail:''}})</li>
                                                                @endif
                                                                @endforeach
                                                            </ul>
                                                            <li>Step 2</li>
                                                            <ul>
                                                                @foreach($employeeName as $val)
                                                                @if(in_array($val->id,$depview[$depval->id]['Approve_step2']))
                                                                <li>{{in_array($val->id,$depview[$depval->id]['Approve_step2'])?$val->fullname:''}}({{in_array($val->id,$depview[$depval->id]['Approve_step2'])?$val->uemail:''}})</li>
                                                                @endif
                                                                @endforeach
                                                            </ul>
                                                            <li>Step 3</li>
                                                            <ul>
                                                                @foreach($employeeName as $val)
                                                                @if(in_array($val->id,$depview[$depval->id]['Approve_step3']))
                                                                <li>{{in_array($val->id,$depview[$depval->id]['Approve_step3'])?$val->fullname:''}}({{in_array($val->id,$depview[$depval->id]['Approve_step3'])?$val->uemail:''}})</li>
                                                                @endif
                                                                @endforeach
                                                            </ul>
                                                        </ul>
                                                    </ol>
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
    $(".ShowHide").click(function() {
        $("#tabledata").toggle();
        $("#formdata").toggle();
    });
    @endif
    activeclass(27, 2);
</script>
<script>
    $("#submitButton").click(function(e) {
        e.preventDefault();
        var department = @json($Departments);
        var check = true
        for (x in department) {
            var Approve_step3 = $("[name='Approve_step3[" + department[x].id + "][]']").val();
            var Approve_step2 = $("[name='Approve_step2[" + department[x].id + "][]']").val();
            var Approve_step1 = $("[name='Approve_step1[" + department[x].id + "][]']").val();
            if (Approve_step3.length > 0) {
                if (Approve_step2.length > 0) {
                    if (Approve_step1.length < 1) {
                        alert("Step 1 Approver Can not be empty in " + department[x].Departments)
                        check = false
                        break;
                    }
                } else {
                    alert("Step 2 Approver Can not be empty in " + department[x].Departments)
                    check = false
                    break;
                }
            }
            if (Approve_step2.length > 0) {
                if (Approve_step1.length < 1) {
                    alert("Step 1 Approver Can not be empty in " + department[x].Departments)
                    check = false
                    break;
                }
            }

        }
        if (check == true) {
            $('#myForm').submit();
        } else {
            $("#tabledata").toggle();
            $("#formdata").toggle();
        }
    });
</script>
@endpush