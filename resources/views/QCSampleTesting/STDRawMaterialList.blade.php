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
                <li class="breadcrumb-item">Sample Testing Details Raw Material View Page</li>
            </ol>
            <div class="addbtn">
                <a href="{{url('QCSampleTesting/STDRawMaterial')}}"><button class="btn btn-info">Add STD Raw Material</button></a>
            </div>
            <div class="row">
                <div class="container">
                    <form action="{{url('QCSampleTesting/filteredRawMaterial')}}" method="POST">
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
                                <a href="{{url('QCSampleTesting/STDRawMaterialList')}}"><button type="button" class="btn btn-warning">Clear </button></a>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="th-sm">SL. No.</th>
                                    <th class="th-sm">Invoice No.</th>
                                    <th class="th-sm">PO No.</th>
                                    <th class="th-sm">Material Code</th>
                                    <th class="th-sm">Material Name</th>
                                    <th class="th-sm">Material Type</th>
                                    <th class="th-sm">HSN Code</th>
                                    <th class="th-sm">Results</th>
                                    <th class="th-sm">Remarks</th>
                                    <th class="th-sm">Operation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($STD_data as $key=>$val)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{isset($val->Invoice_no) && $val->Invoice_no!=''?$val->Invoice_no:''}}</td>
                                    <td>{{isset($val->PO_NO) && $val->PO_NO!=''?$val->PO_NO:''}}</td>
                                    <td>{{isset($val->Material_Code) && $val->Material_Code!=''?$val->Material_Code:''}}</td>
                                    <td>{{isset($val->RawMaterial->Raw_Material) && $val->RawMaterial->Raw_Material!=''?$val->RawMaterial->Raw_Material:''}}</td>
                                    <td>{{isset($val->Material_Type) && $val->Material_Type!=''?$val->Material_Type:''}}</td>
                                    <td>{{isset($val->HNS_Code) && $val->HNS_Code!=''?$val->HNS_Code:''}}</td>
                                    <td>{{isset($val->result_one) && $val->result_one!=''?$val->result_one:''}}</td>
                                    <td>{{isset($val->remarks) && $val->remarks!=''?$val->remarks:''}}</td>
                                    <td>
                                        <a href="{{url('QCSampleTesting/STDRawMaterial/'.$val->id)}}"><button type="button" class="btn btn-warning">View/Edit</button></a>
                                        <a href="{{url('QCSampleTesting/STDRowdelete/'.$val->id)}}"><button type="button" class="btn btn-danger">Delete</button></a>
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
        activeclass(15, 2);
    });
</script>
@endpush