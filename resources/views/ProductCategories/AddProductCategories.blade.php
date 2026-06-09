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
        @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
        @endif
        <section class="section">
            <div class="addbtn extra">
                <a href="{{url('ProductCategories/ProductList')}}" class="btn btn-info"> <i class="fa fa-arrow-left"></i> BACK</a>
                <a href="{{url('ProductCategories/ProductList')}}" class="btn btn-info" style="margin-left:10px"> <i class="fa fa-home"></i> Home</a>
            </div>
            <div class="row">
                <div class="container">
                    <br>
                    <div>
                        <div class="tabs">
                            <div class="tab1">
                                <form action="{{url('ProductCategories/AddProduct')}}" method="POST">
                                    @csrf
                                    <input class="form-control" type="hidden" name="edit" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                                    <div class="row">
                                        
                                        <div class="col-sm-3 form-group">
                                            <label>
                                                Finished Good(FG)*
                                                </lable>
                                                @if(isset($edit->Raw_Material))
                                                <select name="Raw_Material" class="form-select form-select-sm js-example-matcher-start" id="RawMaterial00" onclick="Material(0,0)" disabled>
                                                    <option value="" selected disabled>Select</option>
                                                    @foreach($Raw_Material as $val)
                                                    <option value="{{$val->id}}" {{isset($edit->Raw_Material) && $edit->Raw_Material==$val->id?'selected':''}}>{{$val->material_name}}</option>
                                                    @endforeach
                                                </select>
                                                @else
                                                <select name="Raw_Material" class="form-select form-select-sm js-example-matcher-start" id="RawMaterial00" onclick="Material(0,0)" required>
                                                    <option value="" selected disabled>Select</option>
                                                    @foreach($Raw_Material as $val)
                                                    <option value="{{$val->id}}" {{isset($edit->Raw_Material) && $edit->Raw_Material==$val->id?'selected':''}}>{{$val->material_name}}</option>
                                                    @endforeach
                                                </select>
                                                @endif
                                                <span class="error-message" style="color: red; display: none;"></span>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>HSN Code*</label>
                                            <div class="field-wrap">
                                                <input readonly class="form-control form-control-sm" type="number" id="HSNCode00" name="HSN_Code" placeholder="HSN Code" value="{{isset($edit->HSN_Code) && $edit->HSN_Code!=''?$edit->HSN_Code:''}}" >
                                            </div>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>UOM</label>
                                            <div class="field-wrap">
                                                <input readonly class="form-control form-control-sm" type="text" name="UOM" id="uom00" placeholder="UOM" value="{{isset($dataVal->UOM) && $dataVal->UOM!=''?$dataVal->UOM:''}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6 form-group">
                                        </div>
                                        <div class="col-sm-2 form-group"></div>
                                        <div class="col-sm-4 form-group">
                                            <label for="State">Remarks:</label>
                                            <input type="text" name="remarks" cols="30" rows="5" class="form-control form-control-sm" placeholder="Remarks" value="{{isset($edit->remarks) && $edit->remarks!=''?$edit->remarks:''}}">
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div style="overflow:auto;">
                            <div style="float:right;">
                                <button type="button" id="draft" class="btn btn1 float-right" style="margin: 5px;">Draft & Save</button>
                                <a href="" class="btn btn1 float-right" style="margin: 5px; display:{{isset($edit->id) && $edit->id != ''?'none':'block'}}">Clear All</a>
                                <button type="submit" id="submitBtn" class="btn btn1 float-right" style="margin: 5px;">Submit</button>
                            </div>
                        </div>
                        </form>
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
    activeclass(8, 1);
</script>
<script>
    $(document).ready(function() {
        otherCount = parseInt('{{isset($otherCount)?$otherCount:1}}');
        var i = otherCount;
        $('#add121').click(function() {
            i++;
            $('#dynamic_field1').append('<tr id="row' + i + '"><td><div class="field-wrap"><label style="display:flex;">Enter Field Manually</label><input class="form-control form-control-sm" style="" type="text" autocomplete="off" class="form-control form-control-sm" placeholder="Enter Manually" name="manual_field[' + i + ']" required></div></td><td><a href="javascript:;" onclick="remove(' + i + ')" class="btn btn-danger btn btn-sm btn_remove mt-4">X</a></td></tr>');
        });
    });

    function remove(id) {
        $("#row" + id).remove();
    }
</script>
<script>
    $(document).ready(function() {
        $('#product').change(function() {
            var productID = $(this).val();
            $('#subproduct').empty().prop('disabled', true);
            $('#subsubproduct').empty().prop('disabled', true);

            if (productID) {
                $.ajax({
                    url: "{{url('ProductCategories/get-subproduct')}}/" + productID,
                    type: 'GET',
                    success: function(response) {
                        var options = '';
                        options += '<option value="" selected disabled>Select</option>';
                        $.each(response, function(index, subproduct) {
                            options += '<option value="' + subproduct.id + '">' + subproduct.sub_product + '</option>';
                        });
                        $('#subproduct').html(options).prop('disabled', false);
                    }
                });
            }
        });

        $('#subproduct').change(function() {
            var subproductId = $(this).val();
            $('#subsubproduct').empty().prop('disabled', true);

            if (subproductId) {
                $.ajax({
                    url: "{{url('ProductCategories/get-subsubproduct')}}/" + subproductId,
                    type: 'GET',
                    success: function(response) {
                        var options = '';
                        options += '<option value="" selected disabled>Select</option>';
                        $.each(response, function(index, subsubproduct) {
                            options += '<option value="' + subsubproduct.id + '">' + subsubproduct.sub_sub_product + '</option>';
                        });
                        $('#subsubproduct').html(options).prop('disabled', false);
                    }
                });
            }
        });
    });
</script>
@endpush