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


    .addbtn {
        display: flex;
        justify-content: flex-end;
        padding: 10px 12px;
        margin-top: -3%;
    }

    td.maindffd {
        display: flex;
        justify-content: space-evenly;
        width: 100%;
    }
</style>



<!--<br><br>-->

<div class="card">

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
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Visitor Details</li>
            </ol>
            <div class="addbtn extra">
                <a href="{{url('GatePass/visitor-list')}}" class="btn btn-info"> <i class="fa fa-arrow-left"></i> BACK</a>
            </div>
            <div class="row">
                <div class="container">
                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="th-sm">SL. No.</th>
                                    <th class="th-sm">Visitor Name</th>
                                    <th class="th-sm">Person To Meet</th>
                                    <th class="th-sm">Department</th>
                                    <th class="th-sm">Request Through</th>
                                    <th class="th-sm">Reason For Visit</th>
                                    <th class="th-sm">Visitor Address</th>
                                    <th class="th-sm">Visitor In Time</th>
                                    <th class="th-sm">Visitor Out Time</th>
                                    <th class="th-sm">Is Visitor Come With Vehicle.</th>
                                    <th class="th-sm">Vehicle Reg. No.</th>
                                    <th class="th-sm">Make & Model</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($visitorsName as $key=>$val)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{isset($val->visitor_name) && $val->visitor_name!=''?$val->visitor_name:''}}</td>
                                    <td>{{isset($val->personToMeet->fullname) && $val->personToMeet->fullname!=''?$val->personToMeet->fullname:''}}</td>
                                    <td>{{isset($val->department->department) && $val->department->department!=''?$val->department->department:''}}</td>
                                    <td>{{isset($val->requestthrough->fullname) && $val->requestthrough->fullname!=''?$val->requestthrough->fullname:''}}</td>
                                    <td>{{isset($val->reason_for_visit) && $val->reason_for_visit!=''?$val->reason_for_visit:''}}</td>
                                    <td>{{isset($val->visitor_address) && $val->visitor_address!=''?$val->visitor_address:''}}</td>
                                    <td>{{isset($val->visitor_in_time) && $val->visitor_in_time!=''?date('h:i A', strtotime($val->visitor_in_time)):''}}</td>
                                    <td>{{isset($val->visitor_out_time) && $val->visitor_out_time!=''?date('h:i A', strtotime($val->visitor_out_time)):''}}</td>
                                    <td>{{isset($val->vehicle) && $val->vehicle!=''?$val->vehicle:''}}</td>
                                    <td>{{isset($val->vehicle_reg_no) && $val->vehicle_reg_no!=''?$val->vehicle_reg_no:''}}</td>
                                    <td>{{isset($val->make_model) && $val->make_model!=''?$val->make_model:''}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>
    <br> 
    <br>
</div>
</section>
</div>
</div>
</section>
@endsection
@push('custom-scripts')
<script>
    activeclass(7, 2);
</script>
@endpush