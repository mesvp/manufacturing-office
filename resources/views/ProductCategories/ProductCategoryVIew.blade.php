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

    div#example_filter {
        display: none;
    }

    .selector {

        display: flex;

    }

    .selecotr-item {
        position: relative;

        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .selector-item_radio {
        appearance: none;
        display: none;
    }

    .selector .selecotr-item {
        margin: 4px;
    }

    .selector-item_label {
        position: relative;
        /* height: 63%; */
        /* width: 53%; */
        text-align: center;
        border-radius: 9999px;
        /* line-height: 400%; */
        font-weight: 600 !important;
        transition-duration: .5s;
        transition-property: transform, color, box-shadow;
        transform: none;
        padding: 7px 10px;
        border-radius: 5px !important;
        border: 1px solid #CED4DA;
        text-transform: capitalize;
    }

    .selector-item_radio:checked+.selector-item_label {
        background: #6741D5;
        color: white;
    }


    input[type="radio"] {

        display: none !important;
    }

    .textt {
        font-weight: 600;
    }

    div#DataTables_Table_0_filter {
        display: none;
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
                                <form action="#" method="POST">
                                    @csrf
                                    <input disabled class="form-control" type="hidden" name="edit" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                                    <div class="row">
                                        {{-- <div class="col-sm-3 form-group">
                                            <label>
                                                Organization Name*
                                            </label>
                                            <select disabled name="Organization_Name" class="form-select form-select-sm js-example-matcher-start" required>
                                                <option value="" selected disabled>Select</option>
                                                @foreach($Organization_Name as $val)
                                                <option value="{{$val->id}}" {{isset($edit->Organization_Name) && $edit->Organization_Name==$val->id?'selected':''}}>{{$val->organization}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>
                                                Manufacturing Unit*
                                            </label>
                                            <select disabled name="Manufacturing_Unit" class="form-select form-select-sm js-example-matcher-start" required>
                                                <option value="" selected disabled>Select</option>
                                                @foreach($Manufacturing_Unit as $val)
                                                <option value="{{$val->id}}" {{isset($edit->Manufacturing_Unit) && $edit->Manufacturing_Unit==$val->id?'selected':''}}>{{$val->Manufacturing_unit}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>
                                                BU*
                                            </label>
                                            <select disabled name="BU" class="form-select form-select-sm js-example-matcher-start" required>
                                                <option value="" selected disabled>Select</option>
                                                @foreach($BU as $val)
                                                <option value="{{$val->id}}" {{isset($edit->BU) && $edit->BU==$val->id?'selected':''}}>{{$val->BU}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>
                                                Plant Name*
                                            </label>
                                            <select disabled name="Plant_Name" class="form-select form-select-sm js-example-matcher-start" required>
                                                <option value="" selected disabled>Select</option>
                                                @foreach($Plant_Name as $val)
                                                <option value="{{$val->id}}" {{isset($edit->Plant_Name) && $edit->Plant_Name==$val->id?'selected':''}}>{{$val->plant_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>
                                                Category*
                                            </label>
                                            <select disabled name="Category" class="form-select form-select-sm js-example-matcher-start" id="Category" required>
                                                <option value="" selected disabled>Select</option>
                                                @foreach($category as $val)
                                                <option value="{{$val->id}}" {{isset($edit->Category) && $edit->Category==$val->id?'selected':''}}>{{$val->category}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>Product</label>
                                            <div class="field-wrap">
                                                <select disabled name="Product" class="form-select form-select-sm js-example-matcher-start" id="product" required>
                                                    <option value="" selected disabled>Select</option>
                                                    @foreach($product as $val)
                                                    <option value="{{$val->id}}" {{isset($edit->Product) && $edit->Product==$val->id?'selected':''}}>{{$val->product}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>Sub product</label>
                                            <div class="field-wrap">
                                                <select disabled name="Sub_Product" class="form-select form-select-sm js-example-matcher-start" id="subproduct" required>
                                                    <option value="" selected disabled>Select</option>
                                                    @foreach($subproduct as $val)
                                                    <option value="{{$val->id}}" {{isset($edit->Sub_Product) && $edit->Sub_Product==$val->id?'selected':''}}>{{$val->sub_product}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>Sub Sub product</label>
                                            <div class="field-wrap">
                                                <select disabled name="Sub_Sub_Product" class="form-select form-select-sm js-example-matcher-start" id="subsubproduct" required>
                                                    <option value="" selected disabled>Select</option>
                                                    @foreach($subsubproduct as $val)
                                                    <option value="{{$val->id}}" {{isset($edit->Sub_Sub_Product) && $edit->Sub_Sub_Product==$val->id?'selected':''}}>{{$val->sub_sub_product}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>
                                                Company Name*
                                            </label>
                                            <input disabled class="form-control form-control-sm" type="text" name="Company_Name" placeholder="Company Name" value="{{isset($edit->Company_Name) && $edit->Company_Name!=''?$edit->Company_Name:''}}" required>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>
                                                Colour*
                                            </label>
                                            <input disabled class="form-control form-control-sm" type="text" name="Colour" placeholder="Colour" value="{{isset($edit->Colour) && $edit->Colour!=''?$edit->Colour:''}}" required>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>
                                                Size*
                                            </label>
                                            <input disabled class="form-control form-control-sm" type="text" name="Size" placeholder="Size" value="{{isset($edit->Size) && $edit->Size!=''?$edit->Size:''}}" required>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>
                                                Lable*
                                            </label>
                                            <input disabled class="form-control form-control-sm" type="text" name="Lable" placeholder="Lable" value="{{isset($edit->Lable) && $edit->Lable!=''?$edit->Lable:''}}" required>
                                        </div> --}}
                                        <div class="col-sm-3 form-group">
                                            <label>
                                                Raw Material(FG)*
                                                </lable>
                                                <select disabled name="Raw_Material" class="form-select form-select-sm js-example-matcher-start" id="RawMaterial00" onclick="Material(0,0)" required>
                                                    <option value="" selected disabled>Select</option>
                                                    @foreach($Raw_Material as $val)
                                                    <option value="{{$val->id}}" {{isset($edit->Raw_Material) && $edit->Raw_Material==$val->id?'selected':''}}>{{$val->matname}}</option>
                                                    @endforeach
                                                </select>
                                                <span class="error-message" style="color: red; display: none;"></span>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>HSN Code*</label>
                                            <div class="field-wrap">
                                                <input readonly class="form-control form-control-sm" type="number" id="HSNCode00" name="HSN_Code" placeholder="HSN Code" value="{{isset($edit->HSN_Code) && $edit->HSN_Code!=''?$edit->HSN_Code:''}}" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>UOM</label>
                                            <div class="field-wrap">
                                                {{-- <select disabled name="UOM" id="uom00" class="form-select form-select-sm js-example-matcher-start" required>
                                                    <option value="" selected disabled>Select</option>
                                                    @foreach($UOM as $val)
                                                    <option value="{{$val->id}}" {{isset($edit->UOM) && $edit->UOM==$val->id?'selected':''}}>{{$val->UOMs}}</option>
                                                    @endforeach
                                                </select> --}}
                                                <input readonly class="form-control form-control-sm" type="text" name="UOM" id="uom00" placeholder="UOM" value="{{isset($edit->UOM) && $edit->UOM!=''?$edit->UOM:''}}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6 form-group">
                                            {{-- <label>Others</lable>
                                                <table class="table table-bordered" id="dynamic_field1">
                                                    @if(count($editother)>0)
                                                    @php
                                                    $i = 1;
                                                    @endphp
                                                    @foreach($editother as $val)
                                                    <tr id="row{{$i}}">
                                                        <input disabled type="hidden" name="productID[]" value="{{isset($val->id) && $val->id!=''?$val->id:''}}">
                                                        <td>
                                                            <div class="field-wrap">
                                                                <label style="display:flex;">Enter Field Manually</label>
                                                                <input disabled class="form-control form-control-sm" type="text" autocomplete="off" class="form-control form-control-sm" placeholder="Enter Manually" name="manual_field[]" value="{{isset($val->manual_field) && $val->manual_field!=''?$val->manual_field:''}}">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @php
                                                    $i++;
                                                    @endphp
                                                    @endforeach
                                                    @endif
                                                </table> --}}
                                        </div>
                                        <div class="col-sm-2 form-group"></div>
                                        <div class="col-sm-4 form-group">
                                            <label for="State">Remarks:</label>
                                            <input disabled type="text" name="remarks" cols="30" rows="5" class="form-control form-control-sm" placeholder="Remarks" value="{{isset($edit->remarks) && $edit->remarks!=''?$edit->remarks:''}}">
                                        </div>
                                    </div>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
                <hr>
                @if($edit->Approve_status=='OBJECT' && $edit->userID==auth()->user()->id)
                <form action="{{url('ProductCategories/approve')}}" method="POST">
                    @csrf
                    <input type="hidden" name="approveID" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                    <div class="form-group" id="u_rama">
                        <textarea class="form-control" name="comment_text" id="" rows="5" placeholder="Reply" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    @if(isset($nextID) && !empty($nextID))
                    <a href="{{url('ProductCategories/ProductCategory_View/'.$nextID)}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                    @else
                    <a href="{{url('ProductCategories/ProductList')}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                    @endif
                </form>
                @else
                <form action="{{url('ProductCategories/approve')}}" method="POST">
                    @csrf
                    <input type="hidden" name="approveID" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                    <input type="hidden" name="non_acting" value="1">
                    <div class="button_div">
                        <div class="selector">
                            <div class="selecotr-item">
                                <input type="radio" id="radio6" name="pre_post_approval" class="selector-item_radio" value="AUDIT" required>
                                <label for="radio6" class="selector-item_label">AUDIT</label>
                            </div>
                            <div class="selecotr-item">
                                <input type="radio" id="radio8" name="pre_post_approval" class="selector-item_radio" value="INTIMATION" required>
                                <label for="radio8" class="selector-item_label">INTIMATION</label>
                            </div>
                            <div class="selecotr-item">
                                <input type="radio" id="radio9" name="pre_post_approval" class="selector-item_radio" value="QUERY" required>
                                <label for="radio9" class="selector-item_label">QUERY</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="u_rama">
                        <textarea class="form-control" name="comment_text" id="" rows="5" placeholder="Remarks" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    @if(isset($nextID) && !empty($nextID))
                    <a href="{{url('ProductCategories/ProductCategory_View/'.$nextID)}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                    @else
                    <a href="{{url('ProductCategories/ProductList')}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                    @endif
                </form>
                @endif
                <div class="table-responsive">
                    <table id="" class="table table-striped table-bordered example" style="width:100%">
                        <thead>
                            <tr>
                                <th class="th-sm">SL NO.</th>
                                <th class="th-sm">Action</th>
                                <th class="th-sm">Action By</th>
                                <th class="th-sm">Role. (Reviewer,Approver,ETC)</th>
                                <th class="th-sm">Date & time</th>
                                <th class="th-sm">comment</th>
                                <th class="th-sm">IP Address</th>
                                <th class="th-sm">Device ID</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($approves as $key=>$val)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>
                                    @if(!empty($val->action))
                                    {{isset($val->action) && $val->action!=''?$val->action:''}}
                                    @else
                                    {{isset($val->pre_post_approval) && $val->pre_post_approval!=''?$val->pre_post_approval:''}}
                                    @endif
                                </td>
                                <td>{{isset($val->user->fullname) && $val->user->fullname!=''?$val->user->fullname:''}}</td>
                                <td>{{isset($val->role) && $val->role!=''?$val->role:''}}</td>
                                <td>{{isset($val->created_at) && $val->created_at!=''?date('d-m-Y H:i:s A',strtotime($val->created_at)):''}}</td>
                                <td>{{isset($val->comment_text) && $val->comment_text!=''?$val->comment_text:''}}</td>
                                <td>{{isset($val->ip_address) && $val->ip_address!=''?$val->ip_address:''}}</td>
                                <td>{{isset($val->device_name) && $val->device_name!=''?$val->device_name:''}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
@endpush