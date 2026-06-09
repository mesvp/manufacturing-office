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

    div#example_filter {
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
                <a href="{{url('MaterialManagement/MaterialList')}}" class="btn btn-info"> <i class="fa fa-arrow-left"></i> BACK</a>
                <a href="{{url('MaterialManagement/MaterialList')}}" class="btn btn-info" style="margin-left:10px"> <i class="fa fa-home"></i> Home</a>
            </div>
            <div class="row">
                <div class="container">
                    <br>
                    <div>
                        <div class="tabs">
                            <div class="tab1">
                                <form action="#" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-3 form-group">
                                            <label>Material Name*</label>
                                            <input disabled class="form-control form-control-sm" type="text" name="Material_Name" placeholder="Material Name" value="{{isset($edit->material_name) && $edit->material_name!=''?$edit->material_name:''}}" required>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>
                                                Material Code*
                                            </label>
                                            <input disabled class="form-control form-control-sm" type="text" name="Material_Type" placeholder="Material Type" value="{{isset($edit->Material_Code) && $edit->Material_Code!=''?$edit->Material_Code:''}}" required>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>HSN Code*</label>
                                            <input disabled class="form-control form-control-sm" type="number" name="HSN_Code" placeholder="HSN Code" value="{{isset($edit->HSN_Code) && $edit->HSN_Code!=''?$edit->HSN_Code:''}}" required>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>UOM*</lable>
                                                <input class="form-control form-control-sm" disabled  type="text" id="uom" name="UOM" placeholder="Late Purchase Price" value="{{isset($edit->UOM) && $edit->UOM!=''?$edit->UOM:''}}" required>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>Last Purchase Price*</lable>
                                                <input class="form-control form-control-sm" disabled type="text" id="lastpurchaseprc" name="last_purchase_price" placeholder="Late Purchase Price" value="{{isset($edit->last_purchase_price) && $edit->last_purchase_price!=''?$edit->last_purchase_price:''}}" required>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>Last Purchase Date*</lable>
                                                <input class="form-control form-control-sm" disabled type="text" id="lastpurchasedte" name="last_purchase_date" placeholder="Last Purchase Date" value="{{isset($edit->last_purchase_date) && $edit->last_purchase_date!=''?$edit->last_purchase_date:''}}" required>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>Last Purchase Vendor Name*</lable>
                                                <input class="form-control form-control-sm" disabled type="text" id="lastpurchasevndrnm" name="last_purchase_vndr_name" placeholder="Last Purchase Vendor Name" value="{{isset($edit->last_purchase_vndr_name) && $edit->last_purchase_vndr_name!=''?$edit->last_purchase_vndr_name:''}}" required>
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
                                                <select disabled class="form-select form-select-sm" name="Alternate_UOM" required>
                                                    <option value="" selected disabled>Select Option</option>
                                                    @foreach($uom as $val)
                                                    <option value="{{isset($val->id) && $val->id!=''?$val->id:''}}" {{isset($edit->UOM) && $edit->UOM==$val->id?'selected':''}}>{{isset($val->UOMs) && $val->UOMs!=''?$val->UOMs:''}}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>
                                                Specification*
                                            </label>
                                            <input disabled class="form-control form-control-sm" type="text" name="Specification" placeholder="Specification" value="{{isset($edit->Specification) && $edit->Specification!=''?$edit->Specification:''}}" required>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>Quality Check Required Or Not*</lable>
                                                <select disabled class="form-select form-select-sm" name="Quality_Check" required>
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
                                            <input disabled class="form-control form-control-sm" type="text" name="Minium_Order_Level" placeholder="Minium Order Level" value="{{isset($edit->Minium_Order_Level) && $edit->Minium_Order_Level!=''?$edit->Minium_Order_Level:''}}" required>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>UOM*</lable>
                                                <select disabled class="form-select form-select-sm" name="UOM_one" required>
                                                    <option value="" selected disabled>Select Option</option>
                                                    @foreach($uom as $val)
                                                    <option value="{{isset($val->id) && $val->id!=''?$val->id:''}}" {{isset($edit->UOM) && $edit->UOM==$val->id?'selected':''}}>{{isset($val->UOMs) && $val->UOMs!=''?$val->UOMs:''}}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>
                                                Reorder Level*
                                            </label>
                                            <input disabled class="form-control form-control-sm" onkeypress="return ((event.charCode >= 48 && event.charCode <= 57) ||(event.charCode == 46))" type="text" name="Reorder_Level" placeholder="Reorder Level" value="{{isset($edit->Reorder_Level) && $edit->Reorder_Level!=''?$edit->Reorder_Level:''}}" required>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>UOM*</lable>
                                                <select disabled class="form-select form-select-sm" name="UOM_second" required>
                                                    <option value="" selected disabled>Select Option</option>
                                                    @foreach($uom as $val)
                                                    <option value="{{isset($val->id) && $val->id!=''?$val->id:''}}" {{isset($edit->UOM) && $edit->UOM==$val->id?'selected':''}}>{{isset($val->UOMs) && $val->UOMs!=''?$val->UOMs:''}}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>
                                                Gate Pass Required Or Not*
                                            </label>
                                            <select disabled class="form-select form-select-sm" name="Gate_Pass" required>
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
                                            <input disabled type="text" name="remarks" id="" cols="30" rows="5" class="form-control form-control-sm" placeholder="Remarks" value="{{isset($edit->remarks) && $edit->remarks!=''?$edit->remarks:''}}">
                                        </div>
                                    </div>
                                </form>
                                <hr>
                                @if($edit->Approve_status=='OBJECT')
                                <form action="{{url('MaterialManagement/approve')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="approveID" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                                    <div class="form-group" id="u_rama">
                                        <textarea class="form-control" name="comment_text" id="" rows="3" placeholder="Reply" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    @if(isset($nextID) && !empty($nextID))
                                    <a href="{{url('MaterialManagement/Material_view/'.$nextID)}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                                    @else
                                    <a href="{{url('MaterialManagement/MaterialList')}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                                    @endif
                                </form>
                                @else
                                <form action="{{url('MaterialManagement/approve')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="approveID" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                                    <input type="hidden" name="non_acting" value="1">
                                    <div class="tab-content" id="myTabContent">
                                        <div class="button_div">
                                            <div class="selector">
                                                <div class="selecotr-item">
                                                    <input type="radio" id="radio6" name="pre_post_approval" class="selector-item_radio" value="AUDIT">
                                                    <label for="radio6" class="selector-item_label">AUDIT</label>
                                                </div>
                                                <div class="selecotr-item">
                                                    <input type="radio" id="radio8" name="pre_post_approval" class="selector-item_radio" value="INTIMATION">
                                                    <label for="radio8" class="selector-item_label">INTIMATION</label>
                                                </div>
                                                <div class="selecotr-item">
                                                    <input type="radio" id="radio9" name="pre_post_approval" class="selector-item_radio" value="QUERY">
                                                    <label for="radio9" class="selector-item_label">QUERY</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" id="u_rama">
                                        <textarea class="form-control" name="comment_text" id="" rows="3" placeholder="Remarks" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    @if(isset($nextID) && !empty($nextID))
                                    <a href="{{url('MaterialManagement/Material_view/'.$nextID)}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                                    @else
                                    <a href="{{url('MaterialManagement/MaterialList')}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                                    @endif
                                </form>
                                @endif
                            </div>
                            <div class="table-responsive">
                                <table id="example" class="table table-striped table-bordered" style="width:100%">
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
    activeclass(9, 1);
</script>
@endpush