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
                <a href="{{url('MaterialManagement/MaterialList')}}" class="btn btn-info"> <i class="fa fa-arrow-left"></i> BACK</a>
                <a href="{{url('MaterialManagement/MaterialList')}}" class="btn btn-info" style="margin-left:10px"> <i class="fa fa-home"></i> Home</a>
            </div>
            <div class="row">
                <div class="container">
                    <br>
                    <div>
                        <div class="tabs">
                            <div class="tab1">
                                <form action="{{url('MaterialManagement/AddMaterial')}}" method="POST">
                                    @csrf
                                    <input class="form-control" type="hidden" name="edit" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                                    <div class="row">
                                        @if(isset($edit->Material_Name))
                                        <div class="col-sm-3 form-group">
                                            <label>Material Name*</label>
                                            {{-- <input class="form-control form-control-sm" type="hidden" name="Material_Name" placeholder="Material Name" value="{{isset($edit->Material_Name) && $edit->Material_Name!=''?$edit->Material_Name:''}}" > --}}

                                            <select name="Material_Name" id="materialname" class="form-select form-select-sm" disabled>
                                                <option value="" selected disabled>Select</option>
                                                @foreach($materials as $val)
                                                <option value="{{$val->id}}" {{isset($edit->Material_Name) && $edit->Material_Name==$val->id?'selected':''}}>{{$val->material_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @else 
                                        <div class="col-sm-3 form-group">
                                            <label>Material Name*</label>
                                            {{-- <input class="form-control form-control-sm" type="text" name="Material_Name" placeholder="Material Name" value="{{isset($edit->Material_Name) && $edit->Material_Name!=''?$edit->Material_Name:''}}" required> --}}
                                            <select name="Material_Name" id="materialname" class="form-select form-select-sm" required>
                                                <option value="" selected disabled>Select</option>
                                                @foreach($materials as $val)
                                                <option value="{{$val->id}}" {{isset($edit->Material_Name) && $edit->Material_Name==$val->id?'selected':''}}>{{$val->material_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @endif
                                       
                                        <div class="col-sm-3 form-group">
                                            <label>
                                                Material ID*
                                            </label>
                                            <input class="form-control form-control-sm" type="text" id="matid" name="Material_id" placeholder="Material Id" value="{{isset($edit->Material_id) && $edit->Material_id!=''?$edit->Material_id:''}}" readonly required>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>HSN Code*</label>
                                            <input class="form-control form-control-sm" type="number" id="hsncode" name="HSN_Code" placeholder="HSN Code" value="{{isset($edit->HSN_Code) && $edit->HSN_Code!=''?$edit->HSN_Code:''}}" readonly >
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>UOM*</lable>
                                                <input class="form-control form-control-sm" type="text" id="uom" name="UOM" placeholder="Late Purchase Price" value="{{isset($edit->UOM) && $edit->UOM!=''?$edit->UOM:''}}" readonly>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>Last Purchase Price*</lable>
                                                <input class="form-control form-control-sm" type="text" id="lastpurchaseprc" name="last_purchase_price" placeholder="Late Purchase Price" value="{{isset($edit->last_purchase_price) && $edit->last_purchase_price!=''?$edit->last_purchase_price:''}}" readonly>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>Last Purchase Date*</lable>
                                                <input class="form-control form-control-sm" type="text" id="lastpurchasedte" name="last_purchase_date" placeholder="Last Purchase Date" value="{{isset($edit->last_purchase_date) && $edit->last_purchase_date!=''?$edit->last_purchase_date:''}}" readonly>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>Last Purchase Vendor Name*</lable>
                                                <input class="form-control form-control-sm" type="text" id="lastpurchasevndrnm" name="last_purchase_vndr_name" placeholder="Last Purchase Vendor Name" value="{{isset($edit->last_purchase_vndr_name) && $edit->last_purchase_vndr_name!=''?$edit->last_purchase_vndr_name:''}}" readonly>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>Group*</lable>
                                                <input class="form-control form-control-sm" type="text" id="grp_name" name="grp_name" placeholder="Group Name" value="{{isset($edit->grp_name) && $edit->grp_name!=''?$edit->grp_name:''}}" readonly>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>Sub-Group*</lable>
                                                <input class="form-control form-control-sm" type="text" id="sub_grp_name" name="sub_grp_name" placeholder="Sub-Group Name" value="{{isset($edit->sub_grp_name) && $edit->sub_grp_name!=''?$edit->sub_grp_name:''}}" readonly>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>Category*</lable>
                                                <input class="form-control form-control-sm" type="text" id="cat_name" name="cat_name" placeholder="Category Name" value="{{isset($edit->cat_name) && $edit->cat_name!=''?$edit->cat_name:''}}" readonly>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>Sub-Category*</lable>
                                                <input class="form-control form-control-sm" type="text" id="sub_cat_name" name="sub_cat_name" placeholder="Sub-Category Name" value="{{isset($edit->sub_cat_name) && $edit->sub_cat_name!=''?$edit->sub_cat_name:''}}" readonly>
                                        </div>
                                        {{-- <div class="col-sm-3 form-group">
                                            <label>Alternate UOM*</lable>
                                                <select class="form-select form-select-sm" name="Alternate_UOM" required>
                                                    <option value="" selected disabled>Select Option</option>
                                                    @foreach($uom as $val)
                                                    <option value="{{isset($val->id) && $val->id!=''?$val->id:''}}" {{isset($edit->Alternate_UOM) && $edit->Alternate_UOM==$val->id?'selected':''}}>{{isset($val->UOMs) && $val->UOMs!=''?$val->UOMs:''}}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>
                                                Specification*
                                            </label>
                                            <input class="form-control form-control-sm" type="text" name="Specification" placeholder="Specification" value="{{isset($edit->Specification) && $edit->Specification!=''?$edit->Specification:''}}" required>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>Quality Check Required Or Not*</lable>
                                                <select class="form-select form-select-sm" name="Quality_Check" required>
                                                    <option value="" selected disabled>Select Option</option>
                                                    @foreach($Quality_Check as $val)
                                                    <option value="{{isset($val->id) && $val->id!=''?$val->id:''}}" {{isset($edit->Quality_Check) && $edit->Quality_Check==$val->id?'selected':''}}>{{$val->quality_check}}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>
                                                Minium Order Level*
                                            </label>
                                            <input class="form-control form-control-sm" type="text" name="Minium_Order_Level" placeholder="Minium Order Level" value="{{isset($edit->Minium_Order_Level) && $edit->Minium_Order_Level!=''?$edit->Minium_Order_Level:''}}" required>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>UOM*</lable>
                                                <select class="form-select form-select-sm" name="UOM_one" required>
                                                    <option value="" selected disabled>Select Option</option>
                                                    @foreach($uom as $val)
                                                    <option value="{{isset($val->id) && $val->id!=''?$val->id:''}}" {{isset($edit->UOM_one) && $edit->UOM_one==$val->id?'selected':''}}>{{isset($val->UOMs) && $val->UOMs!=''?$val->UOMs:''}}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>
                                                Reorder Level*
                                            </label>
                                            <input class="form-control form-control-sm" onkeypress="return ((event.charCode >= 48 && event.charCode <= 57) ||(event.charCode == 46))" type="text" name="Reorder_Level" placeholder="Reorder Level" value="{{isset($edit->Reorder_Level) && $edit->Reorder_Level!=''?$edit->Reorder_Level:''}}" required>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>UOM*</lable>
                                                <select class="form-select form-select-sm" name="UOM_second" required>
                                                    <option value="" selected disabled>Select Option</option>
                                                    @foreach($uom as $val)
                                                    <option value="{{isset($val->id) && $val->id!=''?$val->id:''}}" {{isset($edit->UOM_second) && $edit->UOM_second==$val->id?'selected':''}}>{{isset($val->UOMs) && $val->UOMs!=''?$val->UOMs:''}}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                        <div class="col-sm-3 form-group gatepassSelect">
                                            <label>
                                                Gate Pass Required Or Not*
                                            </label>
                                            <select class="form-select form-select-sm" name="Gate_Pass" required>
                                                <option value="null" selected disabled>Select Option</option>
                                                @foreach($Gate_Pass_Required as $val)
                                                <option value="{{isset($val->id) && $val->id!=''?$val->id:''}}" {{isset($edit->Gate_Pass) && $edit->Gate_Pass==$val->id?'selected':''}}>{{isset($val->Gate_Pass_Required) && $val->Gate_Pass_Required!=''?$val->Gate_Pass_Required:''}}</option>
                                                @endforeach
                                            </select>
                                        </div> --}}
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4 form-group"></div>
                                        <div class="col-sm-4 form-group"></div>
                                        <div class="col-sm-4 form-group">
                                            <label for="State">Remarks:</label>
                                            <input type="text" name="remarks" id="" cols="30" rows="5" class="form-control form-control-sm" placeholder="Remarks" value="{{isset($edit->remarks) && $edit->remarks!=''?$edit->remarks:''}}">
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div style="overflow:auto;">
                            <div style="float:right;">
                                @if(!isset($edit->id))
                                <button type="button" id="draft" class="btn btn1 float-right" style="margin: 5px;">Draft & Save</button>
                                @endif
                                <a href="" class="btn btn1 float-right" style="margin: 5px; display:{{isset($edit->id) && $edit->id != ''?'none':'block'}}">Clear All</a>
                                <button type="submit" class="btn btn1 float-right" style="margin: 5px;">Submit</button>
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
    activeclass(9, 1);
    $(document).ready(function() {
      $('#materialname').change(function() {
        var vid=$("#materialname").val();
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf_token"]').attr('content')
            }
        });
           $.ajax({
            url: 'get-materialdetailsajax/' + vid,
            type: 'GET',
               data: {
                     "_token": "{{ csrf_token() }}",
                     vid:vid,
                     },
               success:function(response) { 
                        $.each(response, function(index,materialdetails) {
                          //console.log(materialdetails.serial_no);
                          $("#matid").val(materialdetails.id);
                          $("#hsncode").val(materialdetails.hsn_code);
                          $("#uom").val(materialdetails.uom);
                          $("#lastpurchaseprc").val(materialdetails.lpp);
                          $("#lastpurchasedte").val(materialdetails.lpd);
                          $("#lastpurchasevndrnm").val(materialdetails.lpv);
                          if(materialdetails.group_id == "Primary"){
                            $("#grp_name").val("Primary");
                          }else{
                            $("#grp_name").val(materialdetails.grpname);
                          }
                          if(materialdetails.subgroup_id == "Primary"){
                            $("#sub_grp_name").val("Primary");
                          }else{
                            $("#sub_grp_name").val(materialdetails.subgrpname);
                          }
                          if(materialdetails.category_id == "Primary"){
                            $("#cat_name").val("Primary");
                          }else{
                            $("#cat_name").val(materialdetails.catname);
                          }
                          if(materialdetails.subcategory_id == "Primary"){
                            $("#sub_cat_name").val("Primary");
                          }else{
                            $("#sub_cat_name").val(materialdetails.subcatname);
                          }

                        });
                    }


                       });
      });
  });
</script>
@endpush