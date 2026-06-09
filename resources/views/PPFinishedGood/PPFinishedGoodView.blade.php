@extends('layout.main')
@section('main-container')
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
<style>
    .tab {
        display: none;
    }

    .jaduji {
        display: none;
    }

    button.jhilmil svg.bi.bi-caret-down {
        margin-right: 5px;
    }

    .btn1 {
        background-color: #95f3ff;
    }

    .kakakakak {
        margin-top: 30px !important;
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
        justify-content: flex-start;
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


    button.jhilmil {
        background: #0DCAF0;
        border-radius: 5px;
    }

    div#saktiman {
        text-align: right;
    }

    div#adaaishhhhhhhhhh {
        margin-left: 0px;
        margin-bottom: 20px;
        /* width: 98.5%; */
        padding: 20px;
        border: 1px solid #a8adb1;
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
                                    <h5>Production Planning Details</h5>
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
                        <div class="row">
                            <div class="col-sm-3 form-group">
                                <label>
                                    Make To*
                                </label>
                                <select disabled name="Make_To" class="form-select form-select-sm" required>
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
                        <input disabled type="hidden" name="PP_Data_Id[{{$i}}]" value="{{isset($ppval->id) && $ppval->id!=''?$ppval->id:''}}">
                        <div class="row" id="row{{$i}}">
                            <div class="tab1 col-sm-11 row">
                                <div class="col-sm-3 form-group">
                                    <label>
                                        Organization*
                                    </label>
                                    <select disabled name="Organization[{{$i}}]" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Organization as $val)
                                        <option value="{{$val->id}}" {{isset($ppval->Organization) && $ppval->Organization==$val->id?'selected':''}}>{{$val->organisation}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>
                                        Manufacturing Unit*
                                    </label>
                                    <select disabled name="Manufacturing_Unit[{{$i}}]" class="form-select form-select-sm" required>
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
                                    <select disabled name="Plant_name[{{$i}}]" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Plant_Name as $val)
                                        <option value="{{$val->id}}" {{isset($ppval->Plant_name) && $ppval->Plant_name==$val->id?'selected':''}}>{{$val->spname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- <div class="col-sm-3 form-group">
                                    <label>
                                        Category*
                                    </label>
                                    <select disabled name="category[{{$i}}]" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($category as $val)
                                        <option value="{{$val->id}}" {{isset($ppval->category) && $ppval->category==$val->id?'selected':''}}>{{$val->category}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>
                                        Product*
                                    </label>
                                    <select disabled name="Product[{{$i}}]" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Product as $val)
                                        <option value="{{$val->id}}" {{isset($ppval->Product) && $ppval->Product==$val->id?'selected':''}}>{{$val->product}}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                {{-- <div class="col-sm-3 form-group">
                                    <label>
                                        Sub Product*
                                    </label>
                                    <select disabled name="Sub_Product[{{$i}}]" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Sub_Product as $val)
                                        <option value="{{$val->id}}" {{isset($ppval->Sub_Product) && $ppval->Sub_Product==$val->id?'selected':''}}>{{$val->sub_product}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>
                                        Sub Sub Product*
                                    </label>
                                    <select disabled name="Sub_Sub_Product[{{$i}}]" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Sub_Sub_Product as $val)
                                        <option value="{{$val->id}}" {{isset($ppval->Sub_Sub_Product) && $ppval->Sub_Sub_Product==$val->id?'selected':''}}>{{$val->sub_sub_product}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Color*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text" name="Color[{{$i}}]" placeholder="Color" value="{{isset($ppval->Color) && $ppval->Color!=''?$ppval->Color:''}}" required>
                                    </div>
                                </div> --}}
                                <div class="col-sm-3 form-group">
                                    <label>For Month*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="text" name="For_Primary[{{$i}}]" placeholder="For Primary" value="{{isset($ppval->For_Primary) && $ppval->For_Primary!=''?$ppval->For_Primary:''}}" required>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>
                                        Finished Good(FG)*
                                        </lable>
                                        <select disabled name="Raw_Material[{{$i}}]" class="form-select form-select-sm js-example-matcher-start" id="RawMaterial{{$i}}" onclick="RawMaterial({{$i}})" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($Raw_Material as $val)
                                            <option value="{{$val->RawMaterial->id}}" {{isset($ppval->Raw_Material) && $ppval->Raw_Material==$val->RawMaterial->id?'selected':''}}>{{$val->RawMaterial->matname}}</option>
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
                                        <input readonly class="form-control form-control-sm" type="text" name="UOM[{{$i}}]" placeholder="HSN Code" value="{{isset($ppval->UOM) && $ppval->UOM!=''?$ppval->UOM:''}}" required>
                                        {{-- <select disabled name="UOM[{{$i}}]" class="form-select form-select-sm" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($UOM as $val)
                                            <option value="{{$val->id}}" {{isset($ppval->UOM) && $ppval->UOM==$val->id?'selected':''}}>{{$val->UOMs}}</option>
                                            @endforeach
                                        </select> --}}
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>QTY*</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="number" name="QTY[{{$i}}]" placeholder="QTY" value="{{isset($ppval->QTY) && $ppval->QTY!=''?$ppval->QTY:''}}" required>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Per Day</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="number" name="Per_Day[{{$i}}]" placeholder="Per Day" value="{{isset($ppval->Per_Day) && $ppval->Per_Day!=''?$ppval->Per_Day:''}}" required>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Per Shift</label>
                                    <div class="field-wrap">
                                        <input disabled class="form-control form-control-sm" type="number" name="Per_Shift[{{$i}}]" placeholder="Per Shift" value="{{isset($ppval->Per_Shift) && $ppval->Per_Shift!=''?$ppval->Per_Shift:''}}" required>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <br>
                        @php
                        $i++
                        @endphp
                        @endforeach
                        @endif
                        <div id="addfields"></div>
                        <div class="row">
                            <div class="col-sm-8 form-group"></div>
                            <div class="col-sm-4 form-group">
                                <label for="State">Remarks:</label>
                                <input disabled type="text" name="remarks" cols="30" rows="5" class="form-control form-control-sm" placeholder="Remarks" value="{{isset($edit->remarks) && $edit->remarks!=''?$edit->remarks:''}}">
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                @if($edit->Approve_status=='OBJECT' && $edit->userID==auth()->user()->id)
                <form action="{{url('PPFinishedGood/approve')}}" method="POST">
                    @csrf
                    <input type="hidden" name="approveID" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                    <div class="form-group" id="u_rama">
                        <textarea class="form-control" name="comment_text" id="" rows="5" placeholder="Reply" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    @if(isset($nextID) && !empty($nextID))
                    <a href="{{url('PPFinishedGood/PPFinishedGood_view/'.$nextID)}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                    @else
                    <a href="{{url('PPFinishedGood/PPFinishedGoodtList')}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                    @endif
                </form>
                @else
                <form action="{{url('PPFinishedGood/approve')}}" method="POST">
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
                    <a href="{{url('PPFinishedGood/PPFinishedGood_view/'.$nextID)}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                    @else
                    <a href="{{url('PPFinishedGood/PPFinishedGoodList')}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
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
                                <td>{{isset($val->user->name) && $val->user->name!=''?$val->user->name:''}}</td>
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
    $(document).ready(function() {
        activeclass(13, 1);
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
@endpush