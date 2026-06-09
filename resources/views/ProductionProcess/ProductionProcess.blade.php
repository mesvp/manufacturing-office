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

    .downloadfile {
        display: flex;
    }

    .downloadfile div {
        margin: 0px 20px;
    }

    .downloadfile i.fa.fa-remove {
        color: red;
    }

    div#adaaishhhh {
        margin-left: 10px;
        margin-bottom: 20px;


        width: 98.5%;
    }

    input.form-control.form-control-sm {
        margin-top: 10px;
    }

    hr {
        width: 99% !important;
    }

    div#adaais {
        margin-left: 10px;
        margin-bottom: 20px;

    }

    div#\a main_btn_uddhan {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        align-content: center;
    }

    table#ssef {
        border: 1px solid;
        width: 50%;
    }


    tr.jaafgg td {
        padding: 10px !important;
    }

    tr.jaafgg {
        border-bottom: 1px solid !important;
    }

    .rm_tabe {
        display: flex;
    }


    div#lkjhhdggdg {
        margin-top: 40px;
    }

    table#ssef td {
        padding-left: 10px;
        padding-top: 10px;
        padding-bottom: 10px;
    }


    input#logfgfau {
        height: 60px;
    }

    button#diraj-button {
        background: transparent;
        border: 1px solid;
    }

    table#ufkffguuyuffffu {
        margin-top: 30px;
        border: 1px solid #ddd;
    }




    table#ufkffguuyuffffu thead tr {
        padding: 10px !important;
    }

    table#ufkffguuyuffffu thead tr th.th-sm {
        padding: 10px;
        border: 1px solid #ddd !important;
    }

    table#ufkffguuyuffffu thead tr td.th-sm {
        padding: 10px;
        border: 1px solid #ddd !important;
    }

    div#himmatwalaa {
        display: flex;
        align-items: center;
        justify-content: center;
        align-content: center;
    }

    div#laluKI {
        display: flex;
        align-items: flex-start;
        justify-content: flex-start;
        align-content: flex-start;
        margin-bottom: 20px;
    }

    div#jasbat {
        display: flex;
        align-items: center;
        justify-content: center;
        align-content: center;
    }

    a#addmainhhhhh {
        margin-left: 10px;
        margin-top: -20px;
    }


    table.gbaba {
        width: 500px;
        border: 1px solid #ddd;
        margin-right: 10px;
        padding: 10px;
    }

    table.gbaba th {
        border: 1px solid #ddd !important;
        padding: 10px;
    }

    table.gbaba td {
        border: 1px solid #ddd !important;
    }

    table.gbaba td input {
        height: 100% !important;
        margin: 0px !important;
        border-radius: 0px !important;
        width: 250px;
    }

    table.gbaba td select {

        width: 250px !important;
        margin: 0px !important;
        border-radius: 0px !important;
    }



    table.gbaba th {
        text-align: center;
    }

    .kshiugsdhiusdhginsdgn {
        display: flex;
        align-items: center;
        justify-content: center;
        align-content: center;
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
                <a href="{{url('ProductionProcess/ProductionProcessList')}}" class="btn btn-info"> <i class="fa fa-arrow-left"></i> BACK</a>
                <a href="{{url('ProductionProcess/ProductionProcessList')}}" class="btn btn-info" style="margin-left:10px"> <i class="fa fa-home"></i> Home</a>
            </div>
            <div class="row">
                <div class="container">
                    <div class="row">
                        <div class="col-4">
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col">
                                    <h5> Production Process</h5>
                                </div>
                                <div class="col">
                                    <label for="">Inputer Name : {{auth()->user()->name}}</label>
                                </div>
                                <div class="col">
                                    <label for="">Date & Time : <span id="clock"></span></label>
                                </div>

                            </div>
                        </div>
                    </div>
                    <br>
                    <form action="{{url('ProductionProcess/AddProductionProcess')}}" method="POST">
                        @csrf
                        <input class="form-control" type="hidden" name="edit" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                        <div class="tab1">
                            <div class="row" id="row">
                                {{-- <div class="col-sm-3 form-group">
                                    <label>
                                        Product*
                                    </label>
                                    <div class="field-wrap">
                                        <select name="Product" class="form-select form-select-sm" id="product" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($Product as $val)
                                            <option value="{{$val->id}}" {{isset($edit->Product) && $edit->Product==$val->id?'selected':''}}>{{$val->product}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Sub Product*</label>
                                    <div class="field-wrap">
                                        <select name="Sub_Product" class="form-select form-select-sm" id="subproduct" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($Sub_Product as $val)
                                            <option value="{{$val->id}}" {{isset($edit->Sub_Product) && $edit->Sub_Product==$val->id?'selected':''}}>{{$val->sub_product}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Sub Sub Product*</label>
                                    <div class="field-wrap">
                                        <select name="Sub_Sub_Product" class="form-select form-select-sm" id="subsubproduct" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($Sub_Sub_Product as $val)
                                            <option value="{{$val->id}}" {{isset($edit->Sub_Sub_Product) && $edit->Sub_Sub_Product==$val->id?'selected':''}}>{{$val->sub_sub_product}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}
                                <div class="col-sm-3 form-group">
                                    <label>
                                        Finished Good(FG)*
                                        </lable>
                                        <select name="Raw_Material" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" id="RawMaterial" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($Raw_Material as $val)
                                            <option value="{{$val->RawMaterial->id}}" {{isset($edit->Raw_Material) && $edit->Raw_Material==$val->RawMaterial->id?'selected':''}}>{{$val->RawMaterial->material_name}}</option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>HSN Code*</label>
                                    <div class="field-wrap">
                                        <input readonly class="form-control form-control-sm" type="number" name="HSN_Code" id="HSNCode" placeholder="HSN Code" value="{{isset($edit->HSN_Code) && $edit->HSN_Code!=''?$edit->HSN_Code:''}}" required>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>UOM</label>
                                    <div class="field-wrap">
                                        <input readonly class="form-control form-control-sm" type="text" name="UOM" id="uom" placeholder="UOM" value="{{isset($edit->UOM) && $edit->UOM!=''?$edit->UOM:''}}" required>
                                        {{-- <input readonly class="form-control form-control-sm" type="text" name="UOM" id="uom" placeholder="UOM" value="{{isset($BOM->UOMFG) && $BOM->UOMFG!=''?$BOM->UOMFG:''}}" required> --}}
                                        {{-- <select disabled name="UOM" id="uom" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($UOM as $val)
                                            <option value="{{$val->id}}" {{isset($edit->UOM) && $edit->UOM==$val->id?'selected':''}}>{{$val->UOMs}}</option>
                                            @endforeach
                                        </select> --}}
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>
                                        Description*
                                    </label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="text" name="Description" placeholder="Description" value="{{isset($edit->Description) && $edit->Description!=''?$edit->Description:''}}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="somras">
                            <button type="button" id="draft" class="btn btn1 float-right" style="margin: 5px;">Draft & Save</button>
                            <a href="" class="btn btn1 float-right" style="margin: 5px; display:{{isset($edit->id) && $edit->id != ''?'none':'block'}}">Clear All</a>
                            <button type="submit" id="submitBtn" class="btn btn1 float-right" style="margin: 5px;">Save & Next</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>
</section>
@endsection
@push('custom-scripts')
<script>
    $(document).ready(function() {
        activeclass(26, 1);
    });
</script>
<script>
    function displayTime() {
        const now = new Date();
        const date = now.toLocaleDateString();
        const time = now.toLocaleTimeString();
        document.getElementById("clock").textContent = time + ', ' + date;
    }

    setInterval(displayTime, 1000);
</script>
<script>
    $('#product').change(function() {
        var productID = $(this).val();
        $('#subproduct').empty().prop('disabled', true);
        $('#subsubproduct').empty().prop('disabled', true);

        if (productID) {
            $.ajax({
                url: "{{url('BOM/get-subproduct')}}" + '/' + productID,
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
                url: "{{url('BOM/get-subsubproduct')}}" + '/' + subproductId,
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
</script>
<script>
    $('#RawMaterial').on('change', function() {
        var MaterialId = $(this).val();

        $.ajax({
            url: "{{url('RawMaterial/MaterialData')}}" + '/' + MaterialId,
            type: 'GET',
            data: {
                MaterialId: MaterialId
            },
            success: function(data) {
                $('#HSNCode').val(data.data.HSN_Code);
                $('#uom').val(data.data.UOM).change();
            }
        });
    });
</script>
@endpush