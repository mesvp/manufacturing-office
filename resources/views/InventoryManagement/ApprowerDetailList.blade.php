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
        font-family: Raleway;
        width: 100%;
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

    select.custom-select.custom-select-sm.form-control.form-control-sm {
        margin-top: 3px;
    }

    .left-bar p {
        margin: 4% !important;
    }

    .activesle {
        background: #6741D5 !important;
    }
</style>

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
                <li class="breadcrumb-item">Inventory Management View Page</li>
            </ol>
            <div class="row">
                <div class="container">
                    <form action="{{url('InventoryManagement/ApproveFilter')}}" method="POST">
                        @csrf
                        <div class="row filter">
                            <div class="col-5 mb-3">
                                <label for="" class="form-label">Date From</label>
                                <input type="date" name="from_date" value="{{isset($fromdate) && $fromdate!=''?$fromdate:''}}" class="form-control">
                            </div>
                            <div class="col-5 mb-3">
                                <label for="" class="form-label">Date To</label>
                                <input type="date" name="to_date" value="{{isset($todate) && $todate!=''?$todate:''}}" class="form-control">
                            </div>
                            <div class="col-2 mt-4">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{url('InventoryManagement/InventoryManagementApproveList')}}"><button type="button" class="btn btn-warning">Clear </button></a>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="th-sm">SL. No.</th>
                                    <th class="th-sm">Organizaion</th>
                                    <th class="th-sm">Manufacturing Unit</th>
                                    <th class="th-sm">Plant Name</th>
                                    <th class="th-sm">Category</th>
                                    <th class="th-sm">UMO</th>
                                    <th class="th-sm">QTY</th>
                                    <th class="th-sm">Date</th>
                                    <th class="th-sm">Batch No</th>
                                    <th class="th-sm">Operation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($InventoryManagement as $key=>$val)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{isset($val->Organization->organization) && $val->Organization->organization!=''?$val->Organization->organization:''}}</td>
                                    <td>{{isset($val->Manufacturing_Unit->Manufacturing_unit) && $val->Manufacturing_Unit->Manufacturing_unit!=''?$val->Manufacturing_Unit->Manufacturing_unit:''}}</td>
                                    <td>{{isset($val->plant_name->plant_name) && $val->plant_name->plant_name!=''?$val->plant_name->plant_name:''}}</td>
                                    <td>{{isset($val->data->Category) && $val->data->Category!=''?$val->data->Category:''}}</td>
                                    <td>{{isset($val->UOM->UOMs) && $val->UOM->UOMs!=''?$val->UOM->UOMs:''}}</td>
                                    <td>{{isset($val->products->QTY) && $val->products->QTY!=''?$val->products->QTY:''}}</td>
                                    <td>{{isset($val->products->Date) && $val->products->Date!=''?$val->products->Date:''}}</td>
                                    <td>{{isset($val->products->Batch_No) && $val->products->Batch_No!=''?$val->products->Batch_No:''}}</td>
                                    <td>
                                        @if($val->Approve_status=='APPROVE')
                                        <a href="{{url('InventoryManagement/view-approve/'.$val->id)}}" class="btn btn-success">Approved/View</a>
                                        @elseif($val->Approve_status=='REJECT')
                                        <a href="#" class="btn btn-danger">REJECTED</a>
                                        @elseif($val->Approve_status=='QUERY/RECHECK')
                                        <a href="{{url('InventoryManagement/view-approve/'.$val->id)}}" class="btn btn-info">QUERY & RECHECK/View</a>
                                        @elseif($val->Approve_status=='HOLD')
                                        <a href="{{url('InventoryManagement/view-approve/'.$val->id)}}" class="btn btn-warning">HOLD/View</a>
                                        @else
                                        <a href="{{url('InventoryManagement/view-approve/'.$val->id)}}" class="btn btn-primary">View/Approve</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>
    <br> <br>
</div>
</section>
</div>
</div>
</section>
@endsection
@push('custom-scripts')
<script>
    $(document).ready(function() {
        activeclass(22, 1);
    });
</script>
@endpush