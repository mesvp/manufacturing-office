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
    .btn {
    background-color: #95f3ff;
}
</style>
<div class="card-form">
    <div class="app-content">
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
                                            <h5>Add Manual Stock Entry</h5>
                                        </div>
                                        <div class="col-6">
                                            @if(isset($edit->id) && $edit->id!='')
                                            <a href="{{url('Master/Raw_Material')}}"><button type="submit" class="btn btn1 float-right " style="margin: 5px;">Show Stock Entry</button></a>
                                            @else
                                            <button type="submit" class="btn btn1 float-right " style="margin: 5px;">Show Stock Entry</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="tab1">
                                <form action="{{url('Master/Raw_Material_store')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="edit" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                                    <div class="row">
                                        <div class="col-sm-3 form-group">
                                            <label>
                                                Organization Name*
                                            </label>
                                            <select name="Organization" class="form-select form-select-sm js-example-matcher-start" required>
                                                <option value="" selected disabled>Select</option>
                                                @foreach($Organization as $val)
                                                <option value="{{$val->id}}" {{isset($edit->Organization) && $edit->Organization==$val->id?'selected':''}}>{{$val->organisation}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>
                                                Godown Name*
                                            </label>
                                            <select name="Godown_Name" class="form-select form-select-sm js-example-matcher-start" required>
                                                <option value="" selected disabled>Select</option>
                                                @foreach($Godown_Name as $val)
                                                <option value="{{$val->id}}" {{isset($edit->Godown_Name) && $edit->Godown_Name==$val->id?'selected':''}}>{{$val->inventory_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>
                                                Material*
                                            </label>
                                            <select name="Material" class="form-select form-select-sm js-example-matcher-start" required>
                                                <option value="" selected disabled>Select</option>
                                                @foreach($Material as $val)
                                                <option value="{{$val->id}}" {{isset($edit->Material) && $edit->Material==$val->id?'selected':''}}>{{$val->matname}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>Date*</lable>
                                                <input class="form-control form-control-sm" type="Date" name="Date" placeholder="Date" value="{{isset($edit->Date) && $edit->Date!=''?$edit->Date:''}}" required>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>Quantity*</lable>
                                                <input class="form-control form-control-sm" type="Number" name="Quantity" placeholder="Quantity" id="Quantity" value="{{isset($edit->Quantity) && $edit->Quantity!=''?$edit->Quantity:''}}" required>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>Rate*</lable>
                                                <input class="form-control form-control-sm" type="number" name="Rate" placeholder="Rate" id="Rate" value="{{isset($edit->Rate) && $edit->Rate!=''?$edit->Rate:''}}" required>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>
                                                GST*
                                            </label>
                                            <select name="GST" class="form-select form-select-sm js-example-matcher-start" id="GST" required>
                                                <option value="" selected disabled>Select</option>
                                                @foreach($GST as $val)
                                                <option value="{{$val->GST_Percentage}}" {{isset($edit->GST) && $edit->GST==$val->GST_Percentage?'selected':''}}>{{$val->GST_Percentage}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>Amount*</lable>
                                                <input readonly class="form-control form-control-sm" type="number" name="Amount" placeholder="Amount" id="Amount" value="{{isset($edit->Amount) && $edit->Amount!=''?$edit->Amount:''}}" required>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>Reason</lable>
                                                <input class="form-control form-control-sm" type="text" name="reason" placeholder="reason" id="reason" value="{{isset($edit->reason) && $edit->reason!=''?$edit->reason:''}}" required>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div style="overflow:auto;">
                            <div style="float:right;">
                                <button type="submit" class="btn  float-right" style="margin: 5px;">Submit</button>
                            </div>
                        </div>
                        </form>
                    </div>
                    <div class="tab2" id="tabledata">
                        <div class="row">
                            <div class="col-6">
                                <h5>Manage Manual Stock Entry</h5>
                            </div>
                            <div class="col-6">
                                <button class="btn btn1 float-right " style="margin: 5px;">Add Stock Entry</button>
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="row">
                            <div class="container">
                                <div class="table-responsive">
                                    <table id="example2" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th class="th-sm">SL. No.</th>
                                                <th class="th-sm">Organization Name</th>
                                                <th class="th-sm">Godown Name</th>
                                                <th class="th-sm">Material</th>
                                                <th class="th-sm">Date</th>
                                                <th class="th-sm">Quantity</th>
                                                <th class="th-sm">Rate</th>
                                                <th class="th-sm">GST</th>
                                                <th class="th-sm">Amount</th>
                                                <th class="th-sm">Created By</th>
                                                <th class="th-sm">Created AT</th>
                                                <th class="th-sm">Details</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($Godown_Material as $key=>$val)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{isset($val->organization->organisation) && $val->organization->organisation!=''?$val->organization->organisation:''}}</td>
                                                <td>{{isset($val->Godown_Name->inventory_name) && $val->Godown_Name->inventory_name!=''?$val->Godown_Name->inventory_name:''}}</td>
                                                <td>{{isset($val->Material->matname) && $val->Material->matname!=''?$val->Material->matname:''}}</td>
                                                <td>{{isset($val->Date) && $val->Date!=''?$val->Date:''}}</td>
                                                <td>{{isset($val->Quantity) && $val->Quantity!=''?$val->Quantity:''}}</td>
                                                <td>{{isset($val->Rate) && $val->Rate!=''?$val->Rate:''}}</td>
                                                <td>{{isset($val->GST) && $val->GST!=''?$val->GST:''}}</td>
                                                <td>{{isset($val->Amount) && $val->Amount!=''?$val->Amount:''}}</td>
                                                <td>{{isset($val->fullname) && $val->fullname!=''?$val->fullname:''}}</td>
                                                <td>{{isset($val->created_at) && $val->created_at!=''?$val->created_at:''}}</td>
                                                <td><a href="{{url('Master/View_details_material/'.$val->id)}}" class="btn btn-primary">Details</a></td>
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
    activeclass(11, 2);
</script>
<script>
    $(document).ready(function() {
        $('#Quantity, #Rate, #GST').on('input', function() {
            var quantity = parseFloat($('#Quantity').val()) || 0;
            var rate = parseFloat($('#Rate').val()) || 0;
            var gst = parseFloat($('#GST').val()) || 0;

            var total = quantity * rate;
            var gstAmount = (total * gst) / 100;
            var amount = total + gstAmount;

            $('#Amount').val(amount.toFixed(2));
        });
    });
</script>
@endpush