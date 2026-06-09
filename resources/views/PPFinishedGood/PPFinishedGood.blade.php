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
                <a href="{{url('PPFinishedGood/PPFinishedGoodList')}}" class="btn btn-info"> <i class="fa fa-arrow-left"></i> BACK</a>
                <a href="{{url('PPFinishedGood/PPFinishedGoodList')}}" class="btn btn-info" style="margin-left:10px"> <i class="fa fa-home"></i> Home</a>
            </div>
            <div class="row">
                <div class="container">
                    <div class="row">
                        <div class="col-4">
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col">
                                    <h5>Production Planning</h5>
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
                    <div class="tab1">
                        <form action="{{url('PPFinishedGood/AddPPFinishedGood')}}" method="POST">
                            @csrf
                            <input class="form-control" type="hidden" name="edit" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                            <div class="row">
                                <div class="col-sm-3 form-group">
                                    <label>
                                        Make To*
                                    </label>
                                    <select name="Make_To" class="form-select form-select-sm js-example-matcher-start" required>
                                        <option value="" selected disabled>Select</option>
                                        <option value="Order" {{isset($edit->Make_To) && $edit->Make_To=='Order'?'selected':''}}>Order</option>
                                        <option value="Stock" {{isset($edit->Make_To) && $edit->Make_To=='Stock'?'selected':''}}>Stock</option>
                                    </select>
                                </div>
                            </div>
                            @if(count($pp)>0)
                            @php
                            $i = 1;
                            @endphp
                            @foreach($pp as $ppval)
                            <input type="hidden" name="PP_Data_Id[{{$i}}]" value="{{isset($ppval->id) && $ppval->id!=''?$ppval->id:''}}">
                            <div class="row" id="row{{$i}}">
                                <div class="tab1 col-sm-11 row">
                                    
                                    <div class="col-sm-3 form-group">
                                        <label>
                                            Manufacturing Unit*
                                        </label>
                                        <select name="Manufacturing_Unit[{{$i}}]" id="Manunit{{$i}}" onchange="Manunit({{$i}})" class="form-select form-select-sm js-example-matcher-start" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($Manufacturing_Unit as $val)
                                                <option value="{{$val->id}}" {{isset($ppval->Manufacturing_Unit) && $ppval->Manufacturing_Unit==$val->id?'selected':''}}>{{$val->pname}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>
                                            Plant Name*
                                        </label>
                                        <select name="Plant_name[{{$i}}]" id="plan_uni_id{{$i}}" onchange="Planunit({{$i}})" class="form-select form-select-sm js-example-matcher-start" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($Plant_Name as $val)
                                            <option value="{{$val->id}}" {{isset($ppval->Plant_name) && $ppval->Plant_name==$val->id?'selected':''}}>{{$val->spname}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>
                                            Organization*
                                        </label>
                                        <input class="form-control form-control-sm" type="text" id="org_name{{$i}}" value="{{$ppval->organisation}}" name="Organization[{{$i}}]" readonly value="" required>
                                        <input class="form-control form-control-sm" type="hidden" id="org_id{{$i}}" value="{{$ppval->Organization}}" name="Organization_id[{{$i}}]" readonly value=""> 
                                    </div>
                                    
                                    <div class="col-sm-3 form-group">
                                        <label>For Month*</label>
                                        <div class="field-wrap">
                                            <input class="form-control form-control-sm" type="month" name="For_Primary[{{$i}}]" value="{{isset($ppval->For_Primary) && $ppval->For_Primary!=''?$ppval->For_Primary:''}}" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>
                                            Finished Good(FG)*
                                            </lable>
                                            <select name="Raw_Material[{{$i}}]" class="form-select form-select-sm js-example-matcher-start" id="RawMaterial{{$i}}" onclick="RawMaterial({{$i}})" required>
                                                <option value="" selected disabled>Select</option>
                                                @foreach($Raw_Material as $val)
                                                <option value="{{$val->RawMaterial->id}}" {{isset($ppval->Raw_Material) && $ppval->Raw_Material==$val->RawMaterial->id?'selected':''}}>{{$val->RawMaterial->material_name}}</option>
                                                @endforeach
                                            </select>
                                            <span class="error-message" style="color: red; display: none;"></span>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>HSN Code*</label>
                                        <div class="field-wrap">
                                            <input readonly class="form-control form-control-sm" type="number" id="HSNCode{{$i}}" name="HSN_Code[{{$i}}]" placeholder="HSN Code" value="{{isset($ppval->HSN_Code) && $ppval->HSN_Code!=''?$ppval->HSN_Code:''}}" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>UOM</label>
                                        <div class="field-wrap">
                                            <input readonly class="form-control form-control-sm" type="text" id="uom{{$i}}" name="UOM[{{$i}}]" placeholder="UOM" value="{{isset($ppval->UOM) && $ppval->UOM!=''?$ppval->UOM:''}}" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>QTY*</label>
                                        <div class="field-wrap">
                                            <input class="form-control form-control-sm" type="number" name="QTY[{{$i}}]" placeholder="QTY" value="{{isset($ppval->QTY) && $ppval->QTY!=''?$ppval->QTY:''}}" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>Per Day</label>
                                        <div class="field-wrap">
                                            <input class="form-control form-control-sm" type="number" name="Per_Day[{{$i}}]" placeholder="Per Day" value="{{isset($ppval->Per_Day) && $ppval->Per_Day!=''?$ppval->Per_Day:''}}" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>Per Shift</label>
                                        <div class="field-wrap">
                                            <input class="form-control form-control-sm" type="number" name="Per_Shift[{{$i}}]" placeholder="Per Shift" value="{{isset($ppval->Per_Shift) && $ppval->Per_Shift!=''?$ppval->Per_Shift:''}}" required>
                                        </div>
                                    </div>
                                    
                                </div>
                                @if($i==1)
                                <div class="col-sm-1">
                                    <a href="javascript:;" id="addmain" onclick="addmain({{isset($pp_count) && $pp_count==0?1:$pp_count+1}})" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                </div>
                                @else<div class="col-sm-1">
                                    <a href="javascript:;" onclick="remove({{$i}})" class="btn btn-danger btn-sm mt-4">X</a>
                                </div>
                                @endif
                            </div>
                            <br>
                            @php
                            $i++
                            @endphp
                            @endforeach
                            @else
                            <div class="row">
                                <div class="tab1 col-sm-11 row">
                                
                                    <div class="col-sm-3 form-group">
                                        <label>
                                            Manufacturing Unit*
                                        </label>
                                            <select name="Manufacturing_Unit[0]" class="form-select form-select-sm js-example-matcher-start" onchange="Manunit(0)" id="Manunit0"  required>
                                                <option value="" selected disabled>Select</option>
                                                @foreach($Manufacturing_Unit as $val)
                                                <option value="{{$val->id}}">{{$val->pname}}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>
                                            Plant Name*
                                        </label>
                                        <select name="Plant_name[0]" id="plan_uni_id0" onchange="Planunit(0)" class="form-select form-select-sm js-example-matcher-start" required>
                                            <option value="" selected disabled>Select</option>
                                            {{-- @foreach($Plant_Name as $val)
                                            <option value="{{$val->id}}">{{$val->plant_name}}</option>
                                            @endforeach --}}
                                        </select>
                                        <span class="error-message" style="color: red; display: none;"></span>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>
                                            Organization*
                                        </label>
                                        <div class="field-wrap">
                                            <input class="form-control form-control-sm" type="text" id="org_name0" name="Organization[0]" readonly value="" required>
                                            <input class="form-control form-control-sm" type="hidden" id="org_id0" name="Organization_id[0]" readonly value=""> 
                                        </div>
                                        {{-- <select name="Organization[0]" class="form-select form-select-sm js-example-matcher-start" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($Organization as $val)
                                            <option value="{{$val->id}}">{{$val->organization}}</option>
                                            @endforeach
                                        </select> --}}
                                    </div>
                                   {{-- <div class="col-sm-3 form-group">
                                        <label>
                                            Category*
                                        </label>
                                        <select name="category[0]" class="form-select form-select-sm js-example-matcher-start" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($category as $val)
                                            <option value="{{$val->id}}">{{$val->category}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>
                                            Product*
                                            </lable>
                                            <select name="Product[0]" class="form-select form-select-sm js-example-matcher-start" id="product0" onclick="product(0)" required>
                                                <option value="" selected disabled>Select</option>
                                                @foreach($Product as $val)
                                                <option value="{{$val->id}}">{{$val->product}}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>
                                            Sub Product*
                                            </lable>
                                            <select name="Sub_Product[0]" class="form-select form-select-sm js-example-matcher-start" id="subproduct0" onclick="subproduct(0)" required>
                                                <option value="" selected disabled>Select</option>
                                                @foreach($Sub_Product as $val)
                                                <option value="{{$val->id}}">{{$val->sub_product}}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>
                                            Sub Sub Product*
                                            </lable>
                                            <select name="Sub_Sub_Product[0]" class="form-select form-select-sm js-example-matcher-start" id="subsubproduct0" required>
                                                <option value="" selected disabled>Select</option>
                                                @foreach($Sub_Sub_Product as $val)
                                                <option value="{{$val->id}}">{{$val->sub_sub_product}}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>Color*</label>
                                        <div class="field-wrap">
                                            <input class="form-control form-control-sm" type="text" name="Color[0]" placeholder="Color" value="" required>
                                        </div>
                                    </div>  --}}
                                    <div class="col-sm-3 form-group">
                                        <label>For Month*</label>
                                        <div class="field-wrap">
                                            <input class="form-control form-control-sm" type="month" name="For_Primary[0]" value="" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>
                                            Finished Goods(FG)*
                                            </lable>
                                            <select name="Raw_Material[0]" class="form-select form-select-sm js-example-matcher-start" id="RawMaterial0" onclick="RawMaterial(0)" required>
                                                <option value="" selected disabled>Select</option>
                                                @foreach($Raw_Material as $val)
                                                <option value="{{$val->RawMaterial->id}}">{{$val->RawMaterial->material_name}}</option>
                                                @endforeach
                                            </select>
                                            {{-- <span class="error-message" style="color: red; display: none;"></span> --}}
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>HSN Code*</label>
                                        <div class="field-wrap">
                                            <input readonly class="form-control form-control-sm" type="number" id="HSNCode0" name="HSN_Code[0]" placeholder="HSN Code" value="" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>UOM</label>
                                        <div class="field-wrap">
                                            <input readonly class="form-control form-control-sm" type="text" id="uom0" name="UOM[0]" placeholder="UOM" value="" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>QTY*</label>
                                        <div class="field-wrap">
                                            <input class="form-control form-control-sm" type="number" name="QTY[0]" placeholder="QTY" value="" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>Per Day</label>
                                        <div class="field-wrap">
                                            <input class="form-control form-control-sm" type="number" name="Per_Day[0]" placeholder="Per Day" value="" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>Per Shift</label>
                                        <div class="field-wrap">
                                            <input class="form-control form-control-sm" type="number" name="Per_Shift[0]" placeholder="Per Shift" value="" required>
                                        </div>
                                    </div>
                                   
                                    
                                </div>
                                <div class="col-sm-1">
                                    <a href="javascript:;" id="addmain" onclick="addmain(1)" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            @endif
                            <div id="addfields"></div>
                            <div class="row">
                                <div class="col-sm-8 form-group"></div>
                                <div class="col-sm-4 form-group">
                                    <label for="State">Remarks:</label>
                                    <input type="text" name="remarks" cols="30" rows="5" class="form-control form-control-sm" placeholder="Remarks" value="{{isset($edit->remarks) && $edit->remarks!=''?$edit->remarks:''}}">
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
    $(document).ready(function() {
        activeclass(13, 1);
    });
</script>
<script>
    displayTime();
    function displayTime() {
        const now = new Date();
        const date = now.toLocaleDateString();
        const time = now.toLocaleTimeString();

        $("#clock").text(time + ', ' + date);
      
    }

    setInterval(displayTime, 1000);
</script>
<script>
    function addmain(i) {
        $('#addfields').append('<br> <div class="row" id="row' + i + '"> <div class="tab1 col-sm-11 row"><div class="col-sm-3 form-group"> <label> Manufacturing Unit* </label> <select name="Manufacturing_Unit[' + i + ']" id="Manunit' + i + '" onchange="Manunit(' + i + ')" class="form-select form-select-sm js-example-matcher-start" required> <option value="" selected disabled>Select</option> @foreach($Manufacturing_Unit as $val)<option value="{{$val->id}}">{{$val->pname}}</option>@endforeach </select> </div><div class="col-sm-3 form-group"> <label> Plant Name* </label> <select name="Plant_name[' + i + ']" id="plan_uni_id' + i + '" onchange="Planunit(' + i + ')" class="form-select form-select-sm js-example-matcher-start" required> <option value="" selected disabled>Select</option> </select> <span class="error-message" style="color: red; display: none;"></span></div> <div class="col-sm-3 form-group"> <label> Organization* </label> <input class="form-control form-control-sm" type="text" name="Organization[' + i + ']" id="org_name' + i + '" value="" readonly required> <input class="form-control form-control-sm" type="hidden" id="org_id' + i + '" name="Organization_id[0]" readonly value=""> </div> <div class="col-sm-3 form-group"> <label>For Month*</label> <div class="field-wrap"> <input class="form-control form-control-sm" type="month" name="For_Primary[' + i + ']" value="" required> </div></div><div class="col-sm-3 form-group"><label>Finished Goods(FG)*</lable><select name="Raw_Material[' + i + ']" class="form-select form-select-sm js-example-matcher-start" id="RawMaterial' + i + '" onclick="RawMaterial(' + i + ')" required><option value="" selected disabled>Select</option> @foreach($Raw_Material as $val)<option value="{{$val->RawMaterial->id}}">{{$val->RawMaterial->material_name}}</option> @endforeach </select> </div><div class="col-sm-3 form-group"> <label>HSN Code*</label><div class="field-wrap"><input readonly class="form-control form-control-sm" type="number" id="HSNCode' + i + '" name="HSN_Code[' + i + ']" placeholder="HSN Code" value="" required> </div></div><div class="col-sm-3 form-group"> <label>UOM</label> <div class="field-wrap"><input readonly class="form-control form-control-sm" type="text" id="uom' + i + '" name="UOM[' + i + ']" placeholder="UOM" value="" required> </div></div><div class="col-sm-3 form-group"> <label>QTY*</label> <div class="field-wrap"> <input class="form-control form-control-sm" type="number" name="QTY[' + i + ']" placeholder="QTY" value="" required> </div></div><div class="col-sm-3 form-group"> <label>Per Day</label> <div class="field-wrap"> <input class="form-control form-control-sm" type="number" name="Per_Day[' + i + ']" placeholder="Per Day" value="" required> </div></div><div class="col-sm-3 form-group"> <label>Per Shift</label> <div class="field-wrap"> <input class="form-control form-control-sm" type="number" name="Per_Shift[' + i + ']" placeholder="Per Shift" value="" required> </div></div></div><div class="col-sm-1"> <a href="javascript:;"  onclick="remove(' + i + ')" class="btn btn-danger btn-sm mt-4">X</a> </div></div>');
        i++;
        $("#addmain").attr("onclick", 'addmain(' + i + ')');
        AppendSelect2();
    }

    function remove(id) {
        $("#row" + id).remove();
    }
</script>
<script>
    function product(i) {
        $('#product' + i).change(function() {
            var productID = $(this).val();
            $('#subproduct' + i).empty().prop('disabled', true);
            $('#subsubproduct' + i).empty().prop('disabled', true);

            if (productID) {
                $.ajax({
                    url: "{{url('FactoryCreater/get-subproduct')}}" + '/' + productID,
                    type: 'GET',
                    success: function(response) {
                        var options = '';
                        options += '<option value="" selected disabled>Select</option>';
                        $.each(response, function(index, subproduct) {
                            options += '<option value="' + subproduct.id + '">' + subproduct.sub_product + '</option>';
                        });
                        $('#subproduct' + i).html(options).prop('disabled', false);
                    }
                });
            }
        });
    }

    function subproduct(i) {
        $('#subproduct' + i).change(function() {
            var subproductId = $(this).val();
            $('#subsubproduct' + i).empty().prop('disabled', true);

            if (subproductId) {
                $.ajax({
                    url: "{{url('FactoryCreater/get-subsubproduct')}}" + '/' + subproductId,
                    type: 'GET',
                    success: function(response) {
                        var options = '';
                        options += '<option value="" selected disabled>Select</option>';
                        $.each(response, function(index, subsubproduct) {
                            options += '<option value="' + subsubproduct.id + '">' + subsubproduct.sub_sub_product + '</option>';
                        });
                        $('#subsubproduct' + i).html(options).prop('disabled', false);
                    }
                });
            }
        });
    }
</script>
<script>
    function RawMaterial(i) {
        $('#RawMaterial' + i).on('change', function() {
            var MaterialId = $(this).val();

            $.ajax({
                url: "{{url('RawMaterial/MaterialData')}}" + '/' + MaterialId,
                type: 'GET',
                data: {
                    MaterialId: MaterialId
                },
                success: function(data) {
                    $('#HSNCode' + i).val(data.data.HSN_Code);
                    $('#uom' + i).val(data.data.uomval).change();
                }
            });
        });
    }
</script>

<script>
    function Manunit(i) {
        var id = "#Manunit" + i;
        var ManunitId = $(id).val();
        $("#plan_uni_id" + i).empty();
        $.ajax({
            //url: 'get-plantnamedetails/' + ManunitId,
            url: "{{url('PPFinishedGood/get-plantnamedetails')}}" + '/' + ManunitId,
            type: 'GET',
            data: {
                ManunitId: ManunitId
            },
            success: function(response) {
                $('#plan_uni_id' + i).empty();
                $('#plan_uni_id' + i).append('<option value="" selected disabled>Select</option>');
                $.each(response, function(index, plantdetails) {
                    var option = $('<option>');
                    option.val(plantdetails.id);
                    option.text(plantdetails.spname);
                    $('#plan_uni_id' + i).append(option);
                });
            }
        });
    }
    function Planunit(i){
        var id="#plan_uni_id"+i;
            var plantId = $(id).val();
           // $("#org_name").empty();
            $.ajax({
                //url: 'get-orgnames/' + plantId,
                url: "{{url('PPFinishedGood/get-orgnames')}}" + '/' + plantId,
                type: 'GET',
                data: {
                    plantId: plantId
                },
                success: function(response) {
                        $.each(response, function(index, plantdetails) {
                            $("#org_name" + i).val(plantdetails.organisation);
                            $("#org_id" + i).val(plantdetails.orgid);
                        });
                }
            });
    }

    $(document).ready(function() {
        $('#draft, #submitBtn').on('click', function() {

            var hasDuplicates = checkDuplicateMaterial();

            if (hasDuplicates) {
                return false;
            }

            if ($(this).attr('id') === 'submitBtn') {
                if (!checkRequiredFields()) {
                    alert('Please fill in all required fields.');
                    return false;
                }
            }
            $('#Form').submit();
        });
    });

    function checkDuplicateMaterial() {
        var selectedMaterials = [];
        var hasDuplicate = false;

        $('select[name^="Plant_name"]').each(function() {
            var materialValue = $(this).val();

            if (selectedMaterials.includes(materialValue)) {
                $(this).siblings('.error-message').text('Already Use Plant Name').show();
                hasDuplicate = true;
            } else {
                $(this).siblings('.error-message').text('').hide();
                selectedMaterials.push(materialValue);
            }
        });

        return hasDuplicate;
    }
    
    
</script>
@endpush