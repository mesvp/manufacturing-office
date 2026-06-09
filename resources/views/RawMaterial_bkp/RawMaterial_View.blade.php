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

    /* Mark the steps that are finished and valid: */
    .step.finish {
        background-color: #04AA6D;
    }

    .tab {
        padding: 20px;
        background-color: white;
    }

    .tab1 {
        padding: 20px;
        border: 1px solid #a8adb1;
    }

    .col-sm-3 {
        width: 20% !important;
    }

    select.form-control {
        width: 200px;
    }

    tbody,
    td,
    tfoot,
    th,
    thead,
    tr {
        border: none !important;
    }

    .addbtn {
        display: flex;
        justify-content: flex-end;
        padding: 0px 12px;

    }

    .tabs {
        margin: 10px 0px !important;
        /* margin-bottom: 20px !important; */
    }

    select {
        background: white !important;
    }

    input {
        background: white;
    }

    input {
        background: white !important;
    }

    textarea {
        background: white !important;
    }

    input {
        display: block !important;
        width: 100% !important;
        padding: 0.375rem 0.75rem !important;
        font-size: 1rem !important;
        font-weight: 400 !important;
        line-height: 1.5 !important;
        color: #212529 !important;
        background-color: #fff !important;
        background-clip: padding-box !important;
        border: 1px solid #ced4da !important;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none !important;
        border-radius: 0.375rem !important;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out !important;
    }

    label.dp_homat {
        margin-top: 10px !important;
        display: block !important;
    }

    label.dp_homat p {
        margin-bottom: 0px !important;
        padding-bottom: 0px !important;
        display: block;
        /* margin-top: 10px; */
    }


    .form-control-sm {
        min-height: calc(1.5em + 0.5rem + 2px) !important;
        padding: 0.25rem 0.5rem !important;
        font-size: .875rem !important;
        border-radius: 0.25rem !important;
    }

    textarea#remarks {
        height: 30px !important;
    }

    textarea#remarks {
        margin-top: 5px !important;
    }

    div#gion {
        padding-top: 5px;
    }

    textarea#rathor {
        margin-top: 0px !important;
        height: 10px;
    }


    .form-select {

        background-image: none !important;

    }

    div#kim_id {
        width: 100% !important;
        display: block !important;
    }

    textarea {
        height: 30px !important;
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

    div#DataTables_Table_0_filter {
        display: none;
    }
</style>

<div class="card">
    <div class="app-content">
        <section class="section">
            <div class="addbtn extra">
                <a href="{{url('RawMaterial/RawMaterialList')}}" class="btn btn-info" style=""> <i class="fa fa-arrow-left"></i> BACK</a>
                <a href="{{url('RawMaterial/RawMaterialList')}}" class="btn btn-info" style="margin-left: 10px;"> <i class="fa fa-home"></i> Home</a>
            </div>
            <div class="row">
                <div class="container">
                    <br>
                    <div>
                        <form action="#" method="POST">
                            @csrf
                            <input disabled class="form-control" type="hidden" name="edit" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                            <div class="tab1">
                                @if(count($raw)>0)
                                @php
                                $i = 1;
                                @endphp
                                @foreach($raw as $rawVal)
                                <input disabled type="hidden" name="raw_id[{{$i}}]" value="{{isset($rawVal->id) && $rawVal->id!=''?$rawVal->id:''}}">
                                <div class="row" id="row{{$i}}">
                                    <div class="tab1 col-sm-11">
                                        <div class="row">
                                            <div class="col-sm-3 form-group Select2Design">
                                                <label>
                                                    Organization*
                                                </label>
                                                <select disabled name="Organization[{{$i}}]" class="form-select form-select-sm" required>
                                                    <option value="" selected disabled>Select</option>
                                                    @foreach($Organization as $val)
                                                    <option value="{{$val->id}}" {{isset($rawVal->Organization) && $rawVal->Organization==$val->id?'selected':''}}>{{$val->organization}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-3 form-group Select2Design">
                                                <label>
                                                    Manufacturing Unit*
                                                </label>
                                                <select disabled name="Manufacturing_Unit[{{$i}}]" class="form-select form-select-sm" required>
                                                    <option value="" selected disabled>Select</option>
                                                    @foreach($Manufacturing_Unit as $val)
                                                    <option value="{{$val->id}}" {{isset($rawVal->Manufacturing_Unit) && $rawVal->Manufacturing_Unit==$val->id?'selected':''}}>{{$val->Manufacturing_unit}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-3 form-group Select2Design">
                                                <label>
                                                    Godown Name*
                                                </label>
                                                <select disabled name="Godown_name[{{$i}}]" class="form-select form-select-sm" required>
                                                    <option value="" selected disabled>Select</option>
                                                    @foreach($Godown_Name as $val)
                                                    <option value="{{$val->id}}" {{isset($rawVal->Godown_name) && $rawVal->Godown_name==$val->id?'selected':''}}>{{$val->Godown_Name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label>
                                                    Date*
                                                </label>
                                                <input disabled disabled class="form-control form-control-sm date" type="text" name="date[{{$i}}]" placeholder="Current date" value="{{isset($rawVal->date) && $rawVal->date!=''?$rawVal->date:''}}" required>
                                            </div>
                                        </div>
                                        @if(count($rawVal->raw_data)>0)
                                        @php
                                        $j = 1;
                                        @endphp
                                        @foreach ($rawVal->raw_data as $dataVal)
                                        <input disabled type="hidden" name="raw_data_id[{{$i}}][{{$j}}]" value="{{isset($dataVal->id) && $dataVal->id!=''?$dataVal->id:''}}">
                                        <div class="row" id="rowss{{$i}}{{$j}}">
                                            <div class="tab1 col-sm-11 row">
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Raw Material*
                                                    </label>
                                                    <select disabled name="Raw_Material[{{$i}}][{{$j}}]" class="form-select form-select-sm" required>
                                                        <option value="" selected disabled>Select</option>
                                                        @foreach($Raw_Material as $val)
                                                        <option value="{{$val->id}}" {{isset($dataVal->Raw_Material) && $dataVal->Raw_Material==$val->id?'selected':''}}>{{$val->Material_Name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>HSN Code*</label>
                                                    <div class="field-wrap">
                                                        <input readonly class="form-control form-control-sm" type="number" id="HSNCode{{$i}}{{$j}}" name="HSN_Code[{{$i}}][{{$j}}]" placeholder="HSN Code" value="{{isset($dataVal->HSN_Code) && $dataVal->HSN_Code!=''?$dataVal->HSN_Code:''}}" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>UOM</label>
                                                    <div class="field-wrap">
                                                        <select disabled name="UOM[{{$i}}][{{$j}}]" class="form-select form-select-sm" required>
                                                            <option value="" selected disabled>Select</option>
                                                            @foreach($UOM as $val)
                                                            <option value="{{$val->id}}" {{isset($dataVal->UOM) && $dataVal->UOM==$val->id?'selected':''}}>{{$val->UOMs}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        OB*
                                                    </label>
                                                    <select disabled name="OB[{{$i}}][{{$j}}]" class="form-select form-select-sm" required>
                                                        <option value="" selected disabled>Select</option>
                                                        @foreach($OB as $val)
                                                        <option value="{{$val->id}}" {{isset($dataVal->OB) && $dataVal->OB==$val->id?'selected':''}}>{{$val->OB}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>Received QTY.*</label>
                                                    <div class="field-wrap">
                                                        <input disabled class="form-control form-control-sm" type="text" name="Received_QTY[{{$i}}][{{$j}}]" placeholder="Received QTY" value="{{isset($dataVal->Received_QTY) && $dataVal->Received_QTY!=''?$dataVal->Received_QTY:''}}" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>Balance Stock</label>
                                                    <div class="field-wrap">
                                                        <input disabled class="form-control form-control-sm" type="text" name="Balance_Stock[{{$i}}][{{$j}}]" placeholder="Balance Stock" value="{{isset($dataVal->Balance_Stock) && $dataVal->Balance_Stock!=''?$dataVal->Balance_Stock:''}}" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Store In Rack No.*
                                                    </label>
                                                    <select disabled name="rack_no[{{$i}}][{{$j}}]" class="form-select form-select-sm" required>
                                                        <option value="" selected disabled>Select</option>
                                                        @foreach($Rack_No as $val)
                                                        <option value="{{$val->id}}" {{isset($dataVal->rack_no) && $dataVal->rack_no==$val->id?'selected':''}}>{{$val->Rack_No}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Sub Rack No.*
                                                    </label>
                                                    <select disabled name="sub_rack_no[{{$i}}][{{$j}}]" class="form-select form-select-sm" required>
                                                        <option value="" selected disabled>Select</option>
                                                        @foreach($Sub_Rack_No as $val)
                                                        <option value="{{$val->id}}" {{isset($dataVal->sub_rack_no) && $dataVal->sub_rack_no==$val->id?'selected':''}}>{{$val->Sub_Rack_No}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Store In Bin No.*
                                                    </label>
                                                    <select disabled name="bin_no[{{$i}}][{{$j}}]" class="form-select form-select-sm" required>
                                                        <option value="" selected disabled>Select</option>
                                                        @foreach($Bin_No as $val)
                                                        <option value="{{$val->id}}" {{isset($dataVal->bin_no) && $dataVal->bin_no==$val->id?'selected':''}}>{{$val->Bin_No}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Sub Bin No.*
                                                    </label>
                                                    <select disabled name="sub_bin_no[{{$i}}][{{$j}}]" class="form-select form-select-sm" required>
                                                        <option value="" selected disabled>Select</option>
                                                        @foreach($Sub_Bin_No as $val)
                                                        <option value="{{$val->id}}" {{isset($dataVal->sub_bin_no) && $dataVal->sub_bin_no==$val->id?'selected':''}}>{{$val->Sub_Bin_No}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Rack OB.*
                                                    </label>
                                                    <input disabled class="form-control form-control-sm" type="text" name="rack_ob[{{$i}}][{{$j}}]" placeholder="Rack OB" value="{{isset($dataVal->rack_ob) && $dataVal->rack_ob!=''?$dataVal->rack_ob:''}}" required>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Rack CB.*
                                                    </label>
                                                    <input disabled class="form-control form-control-sm" type="text" name="rack_cb[{{$i}}][{{$j}}]" placeholder="Rack CB" value="{{isset($dataVal->rack_cb) && $dataVal->rack_cb!=''?$dataVal->rack_cb:''}}" required>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Bin OB.*
                                                    </label>
                                                    <input disabled class="form-control form-control-sm" type="text" name="bin_ob[{{$i}}][{{$j}}]" placeholder="Bin OB" value="{{isset($dataVal->bin_ob) && $dataVal->bin_ob!=''?$dataVal->bin_ob:''}}" required>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>
                                                        Bin CB.*
                                                    </label>
                                                    <input disabled class="form-control form-control-sm" type="text" name="bin_cb[{{$i}}][{{$j}}]" placeholder="Bin CB" value="{{isset($dataVal->bin_cb) && $dataVal->bin_cb!=''?$dataVal->bin_cb:''}}" required>
                                                </div>
                                            </div>
                                        </div>
                                        @php
                                        $j++;
                                        @endphp
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                                @php
                                $i++;
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
                        </form>
                    </div>
                </div>
                <hr>
                @if($edit->Approve_status=='OBJECT' && $edit->userID==auth()->user()->id)
                <form action="{{url('RawMaterial/approve')}}" method="POST">
                    @csrf
                    <input type="hidden" name="approveID" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                    <div class="form-group" id="u_rama">
                        <textarea class="form-control" name="comment_text" id="" rows="5" placeholder="Reply" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    @if(isset($nextID) && !empty($nextID))
                    <a href="{{url('RawMaterial/RawMaterial_View/'.$nextID)}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                    @else
                    <a href="{{url('RawMaterial/RawMaterialList/')}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                    @endif
                </form>
                @else
                <form action="{{url('RawMaterial/approve')}}" method="POST">
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
                    <a href="{{url('RawMaterial/RawMaterial_View/'.$nextID)}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                    @else
                    <a href="{{url('RawMaterial/RawMaterialList/')}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
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
    activeclass(12, 1);
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