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

    div#main_btn_uddhan {
        display: flex;
        justify-content: flex-end;
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
                <a href="{{url('orderRequirement/orderRequirementApproveList')}}" class="btn btn-info"> <i class="fa fa-arrow-left"></i> BACK</a>
                <a href="{{url('orderRequirement/orderRequirementApproveList')}}" class="btn btn-info" style="margin-left:10px"> <i class="fa fa-home"></i> Home</a>
            </div>
            <div class="row">
                <div class="container">
                    <div class="row">
                        <div class="col-4">
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col">
                                    <h5>Work Order Details</h5>
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
                        <div class="row" id="row">
                            <div class="tab1 col-sm-12 row" id="adaaishhhh">
                                <h5>Sales Order Details</h5>
                                <hr>
                                <div class="col-sm-3 form-group">
                                    <label>
                                        Organization Name*
                                    </label>
                                    <select name="Organization" class="form-select form-select-sm" readonly disabled>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Organization as $val)
                                        <option value="{{$val->id}}" {{isset($editSales->Organization) && $editSales->Organization==$val->id?'selected':''}}>{{isset($val->organization) && $val->organization!=''?$val->organization:''}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>
                                        BU Name*
                                    </label>
                                    <select name="BU_Name" class="form-select form-select-sm" readonly disabled>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($BU as $val)
                                        <option value="{{$val->id}}" {{isset($editSales->BU_Name) && $editSales->BU_Name==$val->id?'selected':''}}>{{isset($val->BU) && $val->BU!=''?$val->BU:''}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>
                                        Unit Name*
                                    </label>
                                    <select name="Unit_Name" class="form-select form-select-sm" readonly disabled>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Manufacturing_unit as $val)
                                        <option value="{{$val->id}}" {{isset($editSales->Unit_Name) && $editSales->Unit_Name==$val->id?'selected':''}}>{{$val->Manufacturing_unit}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>
                                        Plant Name*
                                    </label>
                                    <select name="Plant_Name" class="form-select form-select-sm" readonly disabled>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($plant_name as $val)
                                        <option value="{{$val->id}}" {{isset($editSales->Plant_Name) && $editSales->Plant_Name==$val->id?'selected':''}}>{{$val->plant_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label> Order Date*</label>
                                    <div class="field-wrap">
                                        <input readonly disabled class="form-control form-control-sm" type="date" name="Order_Date" placeholder="Order Date" value="{{isset($editSales->Order_Date) && $editSales->Order_Date!=''?$editSales->Order_Date:''}}" required>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label> Customer Name*</label>
                                    <select name="Customer_Name" class="form-select form-select-sm" readonly disabled>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Customer_Name as $val)
                                        <option value="{{$val->id}}" {{isset($editSales->Customer_Name) && $editSales->Customer_Name==$val->id?'selected':''}}>{{$val->Customer_Name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label> Company Name*</label>
                                    <select name="Company_Name" class="form-select form-select-sm" readonly disabled>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Company_Name as $val)
                                        <option value="{{$val->id}}" {{isset($editSales->Company_Name) && $editSales->Company_Name==$val->id?'selected':''}}>{{$val->Company_Name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Country*</lable>
                                        <select class="form-select form-select-sm" name="country" id="country" readonly disabled>
                                            <option value="" selected disabled>Select Option</option>
                                            @foreach($country as $val)
                                            <option value="{{$val->id}}" {{isset($editSales->country) && $editSales->country==$val->id?'selected':''}}>{{$val->name}}</option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>State*</lable>
                                        <select class="form-select form-select-sm" name="state" id="state" readonly disabled>
                                            <option value="" selected disabled>Select Option</option>
                                            @foreach($state as $val)
                                            <option value="{{$val->id}}" {{isset($editSales->state) && $editSales->state==$val->id?'selected':''}}>{{$val->name}}</option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>District*</lable>
                                        <select class="form-select form-select-sm" name="district" id="district" readonly disabled>
                                            <option value="" selected disabled>Select Option</option>
                                            @foreach($city as $val)
                                            <option value="{{$val->id}}" {{isset($editSales->district) && $editSales->district==$val->id?'selected':''}}>{{$val->city}}</option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Zip Code*</label>
                                    <div class="field-wrap">
                                        <input readonly disabled class="form-control form-control-sm" type="text" name="Zip_Code" placeholder="Zip Code" maxlength="6" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="{{isset($editSales->Zip_Code) && $editSales->Zip_Code!=''?$editSales->Zip_Code:''}}" required>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Phone*</label>
                                    <div class="field-wrap">
                                        <input readonly disabled class="form-control form-control-sm" id="Number" type="number" min="1000000000" max="9999999999" name="Phone" placeholder="Driver Number" value="{{isset($editSales->Phone) && $editSales->Phone!=''?$editSales->Phone:''}}" required>
                                        <small id="NumberError" style="color: red;"></small>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Address</label>
                                    <div class="field-wrap">
                                        <input readonly disabled class="form-control form-control-sm" type="text" name="Address" placeholder="Address" value="{{isset($editSales->Address) && $editSales->Address!=''?$editSales->Address:''}}">
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Fax</label>
                                    <div class="field-wrap">
                                        <input readonly disabled class="form-control form-control-sm" type="number" name="Fax" placeholder="Fax" value="{{isset($editSales->Fax) && $editSales->Fax!=''?$editSales->Fax:''}}">
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>GST IN:*</label>
                                    <div class="field-wrap">
                                        <input readonly disabled class="form-control form-control-sm" type="text" name="GST" placeholder="GST IN" value="{{isset($editSales->GST) && $editSales->GST!=''?$editSales->GST:''}}" required>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Dispatch Date</label>
                                    <div class="field-wrap">
                                        <input readonly disabled class="form-control form-control-sm" type="date" name="Dispatch_Date" placeholder="" value="{{isset($editSales->Dispatch_Date) && $editSales->Dispatch_Date!=''?$editSales->Dispatch_Date:''}}">
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label> Brand/Label</label>
                                    <div class="field-wrap">
                                        <input readonly disabled class="form-control form-control-sm" type="text" name="Brand_Label" placeholder="" value="{{isset($editSales->Brand_Label) && $editSales->Brand_Label!=''?$editSales->Brand_Label:''}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-8 form-group"></div>
                            <div class="col-sm-4 form-group">
                                <label for="State">Remarks:</label>
                                <input type="text" readonly disabled name="remarks" cols="30" rows="5" class="form-control form-control-sm" placeholder="Remarks" value="{{isset($editSales->remarks) && $editSales->remarks!=''?$editSales->remarks:''}}">
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                @php
                $STEP = Session::get('STEP');
                $EXT = Session::get('EXT');
                @endphp
                @if($editSales->Approve_status!='REJECT')
                <form action="{{url('orderRequirement/Sales_approve')}}" method="POST">
                    @csrf
                    <input type="hidden" name="approveID" value="{{isset($editSales->id) && $editSales->id!=''?$editSales->id:''}}">
                    <div class="tab-content" id="myTabContent">
                        @if($editSales->Approve_status!='APPROVE' && in_array(1, $STEP) || in_array(2, $STEP) || in_array(3, $STEP) || isset($EXT[18]['Forward']))
                        <div class="button_div">
                            <div class="selector">
                                <div class="selecotr-item">
                                    <input type="radio" id="radio1" name="during_approval" class="selector-item_radio" value="APPROVE" required>
                                    <label for="radio1" class="selector-item_label">APPROVE</label>
                                </div>
                                <div class="selecotr-item">
                                    <input type="radio" id="radio2" name="during_approval" class="selector-item_radio" value="REJECT" required>
                                    <label for="radio2" class="selector-item_label">REJECT</label>
                                </div>
                                <div class="selecotr-item">
                                    <input type="radio" id="radio18" name="during_approval" class="selector-item_radio" value="RECHECK" required>
                                    <label for="radio18" class="selector-item_label">RECHECK</label>
                                </div>
                                <div class="selecotr-item">
                                    <input type="radio" id="radio4" name="during_approval" class="selector-item_radio" value="HOLD" {{isset($approvestatus->action) && $approvestatus->action=='HOLD'?'checked':''}} required>
                                    <label for="radio4" class="selector-item_label">HOLD</label>
                                </div>
                                <div class="selecotr-item">
                                    <input type="radio" id="radio7" name="during_approval" class="selector-item_radio" value="OBJECT" required>
                                    <label for="radio7" class="selector-item_label">OBJECT</label>
                                </div>
                                <div class="selecotr-item">
                                    <input type="radio" id="radio5" name="during_approval" class="selector-item_radio" value="FORWARD" required>
                                    <label for="radio5" class="selector-item_label">FORWARD</label>
                                </div>
                                <div class="selecotr-item">
                                    <input type="radio" id="radio15" name="pre_post_approval" class="selector-item_radio" value="AUDIT">
                                    <label for="radio15" class="selector-item_label">AUDIT</label>
                                </div>
                                <div class="selecotr-item">
                                    <input type="radio" id="radio16" name="pre_post_approval" class="selector-item_radio" value="INTIMATION">
                                    <label for="radio16" class="selector-item_label">INTIMATION</label>
                                </div>
                                <div class="selecotr-item">
                                    <input type="radio" id="radio17" name="pre_post_approval" class="selector-item_radio" value="QUERY">
                                    <label for="radio17" class="selector-item_label">QUERY</label>
                                </div>
                            </div>
                            <div id="showfields" class="row" style="display: {{isset($approvestatus->action) && $approvestatus->action=='HOLD'?'flex':'none'}};">
                                <div class="col-sm-4 form-group">
                                    <label>Days For Holding</lable>
                                        <input type="date" style="border-radius: 12px;" name="days_for_holding" placeholder="Days For Holding" min="{{date('Y-m-d')}}" class="form-control form-control-sm requireddd" value="{{isset($approvestatus->days_for_holding) && $approvestatus->days_for_holding!=''?$approvestatus->days_for_holding:''}}">
                                </div>
                            </div>
                            <div id="Forwords" class="row" style="display: none;">
                                <div class="col-sm-4 form-group">
                                    <label>Forward To</lable>
                                        <select class="form-select form-select-sm requirrreddd" name="Forward_To">
                                            <option value="" selected disabled>Select</option>
                                            @foreach($employeeName as $val)
                                            <option value="{{isset($val->id) && $val->id!=''?$val->id:''}}">{{isset($val->name) && $val->name!=''?$val->name:''}}</option>
                                            @endforeach
                                        </select>
                                </div>
                            </div>
                        </div>
                        @else
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
                        @endif
                    </div>
                    <div class="form-group" id="u_rama">
                        <textarea class="form-control" name="comment_text" id="" rows="3" placeholder="Remarks" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    @if(isset($nextID) && !empty($nextID))
                    <a href="{{url('orderRequirement/Stock-view-approve/'.$nextID)}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                    @else
                    <a href="{{url('orderRequirement/ProductApproveList')}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
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
        activeclass(25, 2);
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
    $(document).ready(function() {
        $('input[type=radio][name=during_approval]').on('click', function() {
            if ($('#radio4').is(':checked')) {
                $('#showfields').show();
                $('.requireddd').prop('required', true);
            } else {
                $('#showfields').hide();
                $('.requireddd').prop('required', false);
            }
        });

        $('input[type=radio][name=pre_post_approval]').on('click', function() {
            $('#showfields').hide();
            $('.requireddd').prop('required', false);
        });
    });

    $(document).ready(function() {
        $('input[type=radio][name=during_approval]').on('click', function() {
            if ($('#radio5').is(':checked')) {
                $('#Forwords').show();
                $('.requirrreddd').prop('required', true);
            } else {
                $('#Forwords').hide();
                $('.requirrreddd').prop('required', false);
            }
        });

        $('input[type=radio][name=pre_post_approval]').on('click', function() {
            $('#Forwords').hide();
            $('.requirrreddd').prop('required', false);
        });
    });
</script>
<script>
    const prePostApprovalRadios = document.querySelectorAll('[name="pre_post_approval"]');
    const duringApprovalRadios = document.querySelectorAll('[name="during_approval"]');
    const duringApprovalFields = document.querySelector('.selector');

    prePostApprovalRadios.forEach(prePostRadio => {
        prePostRadio.addEventListener('change', () => {
            if (prePostRadio.checked) {
                duringApprovalRadios.forEach(duringRadio => {
                    duringRadio.checked = false;
                    duringRadio.removeAttribute('required');
                });

                duringApprovalFields.classList.add('disabled');
            }
        });
    });

    duringApprovalRadios.forEach(duringRadio => {
        duringRadio.addEventListener('change', () => {
            if (duringRadio.checked) {
                prePostApprovalRadios.forEach(prePostRadio => {
                    prePostRadio.checked = false;
                });

                duringApprovalFields.classList.remove('disabled');
            }
        });
    });
</script>
@endpush