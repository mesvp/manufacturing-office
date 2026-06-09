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


    .button_div {
        margin-top: 40px;
        width: 100% !important;
    }

    .button_div a {
        color: #232356;
        font-weight: 500;
        border: 1px solid #41719C;
        padding: 5px 10px;
        text-decoration: none;
        border-radius: 5px;
        text-transform: capitalize;
    }

    div#home {
        width: 100% !important;
    }

    div#u_rama {
        margin-right: 30px;
        text-transform: capitalize !important;
    }

    div#u_rama textarea.form-control {
        border: 1px solid #41719C;
        height: 80px !important;
    }

    .betachao {
        padding: 10px 20px;
        background: #92D050;
        color: black;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: capitalize;
        border-radius: 5px;
        border: 1px solid #41719C;
    }

    div#profile {
        width: 100% !important;
    }

    div#contact {
        width: 100% !important;
    }

    .raja_table {
        margin-top: 30px;
    }

    .raja_table {
        border: 1px solid #41719C;
        padding: 30px;
        border-radius: 30px;
        margin-right: 30px;
    }

    .raja_table tr {
        border: none !important;
    }

    .raja_table tr th {
        border: none !important;
    }

    .raja_table th {
        color: #232356 !important;
        font-size: 16px;
        font-weight: 600 !important;
    }

    .raja_table th {
        text-align: center !important;
    }

    .dt-buttons {
        display: none;
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
                <a href="{{url('RawMaterial/RawMaterialApproveList')}}" class="btn btn-info"> <i class="fa fa-arrow-left"></i> BACK</a>
                <a href="{{url('RawMaterial/RawMaterialApproveList')}}" class="btn btn-info" style="margin-left:10px"> <i class="fa fa-home"></i> Home</a>
            </div>
            <div class="row">
                <div class="container">
                    <br>
                    <div>
                        <form action="#" method="POST">
                            @csrf
                            <div class="tab1">
                                @php
                                $i = 1;
                                @endphp
                                @foreach($raw as $rawVal)
                                <div class="row" id="row{{$i}}">
                                    <div class="tab1 col-sm-11">
                                        <div class="row">
                                            {{-- <div class="col-sm-3 form-group Select2Design">
                                                <label>
                                                    Organization*
                                                </label>
                                                <select disabled name="Organization[{{$i}}]" class="form-select form-select-sm" required>
                                                    <option value="" selected disabled>Select</option>
                                                    @foreach($Organization as $val)
                                                    <option value="{{$val->id}}" {{isset($rawVal->Organization) && $rawVal->Organization==$val->id?'selected':''}}>{{$val->organization}}</option>
                                                    @endforeach
                                                </select>
                                            </div> --}}
                                            {{-- <div class="col-sm-3 form-group Select2Design">
                                                <label>
                                                    Manufacturing Unit*
                                                </label>
                                                <select disabled name="Manufacturing_Unit[{{$i}}]" class="form-select form-select-sm" required>
                                                    <option value="" selected disabled>Select</option>
                                                    @foreach($Manufacturing_Unit as $val)
                                                    <option value="{{$val->id}}" {{isset($rawVal->Manufacturing_Unit) && $rawVal->Manufacturing_Unit==$val->id?'selected':''}}>{{$val->Manufacturing_unit}}</option>
                                                    @endforeach
                                                </select>
                                            </div> --}}
                                            {{-- <div class="col-sm-3 form-group Select2Design">
                                                <label>
                                                    Godown Name*
                                                </label>
                                                <select disabled name="Godown_name[{{$i}}]" class="form-select form-select-sm" required>
                                                    <option value="" selected disabled>Select</option>
                                                    @foreach($Godown_Name as $val)
                                                    <option value="{{$val->id}}" {{isset($rawVal->Godown_name) && $rawVal->Godown_name==$val->id?'selected':''}}>{{$val->Godown_Name}}</option>
                                                    @endforeach
                                                </select>
                                            </div> --}}
                                            <div class="col-sm-3 form-group">
                                                <label>
                                                    Date*
                                                </label>
                                                <input disabled disabled class="form-control form-control-sm date" type="text" name="date[{{$i}}]" placeholder="Current date" value="{{isset($rawVal->date) && $rawVal->date!=''?$rawVal->date:''}}" required>
                                            </div>
                                        </div>
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
                                                        <option value="{{$val->id}}" {{isset($dataVal->Raw_Material) && $dataVal->Raw_Material==$val->id?'selected':''}}>{{$val->matname}}</option>
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
                                                        <input readonly class="form-control form-control-sm" type="text" id="UOM{{$i}}{{$j}}" name="UOM[{{$i}}][{{$j}}]" placeholder="UOM" value="{{isset($dataVal->UOM) && $dataVal->UOM!=''?$dataVal->UOM:''}}" required>
                                                        
                                                    </div>
                                                </div>
                                                {{-- <div class="col-sm-3 form-group">
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
                                                </div> --}}
                                            </div>
                                        </div>
                                        @php
                                        $j++;
                                        @endphp
                                        @endforeach
                                    </div>
                                </div>
                                @php
                                $i++;
                                @endphp
                                @endforeach
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
                    @php
                    $STEP = Session::get('STEP');
                    $EXT = Session::get('EXT');
                    @endphp
                    <hr>
                    @if($edit->Approve_status!='REJECT')
                    <form action="{{url('RawMaterial/approve')}}" method="POST">
                        @csrf
                        <input type="hidden" name="approveID" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                        <div class="tab-content" id="myTabContent">
                            @if($edit->Approve_status!='APPROVE' && in_array(1, $STEP) || in_array(2, $STEP) || in_array(3, $STEP) || isset($EXT[6]['Forward']))
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
                                        <input type="radio" id="radio4" name="during_approval" class="selector-item_radio" value="HOLD" required>
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
                                <div id="showfields" class="row" style="display: none">
                                    <div class="col-sm-4 form-group">
                                        <label>Days For Holding</lable>
                                            <input type="date" style="border-radius: 12px;" min="{{date('Y-m-d')}}" name="days_for_holding" placeholder="Days For Holding" class="form-control form-control-sm requireddd" value="">
                                    </div>
                                </div>
                                <div id="Forwords" class="row" style="display: none;">
                                    <div class="col-sm-4 form-group">
                                        <label>Forward To</lable>
                                            <select class="form-select form-select-sm requirrreddd" name="Forward_To">
                                                <option value="" selected disabled>Select</option>
                                                @foreach($employeeName as $val)
                                                <option value="{{isset($val->id) && $val->id!=''?$val->id:''}}">{{isset($val->fullname) && $val->fullname!=''?$val->fullname:''}}</option>
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
                        <a href="{{url('RawMaterial/view-approve/'.$nextID)}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                        @else
                        <a href="{{url('RawMaterial/RawMaterialApproveList/')}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
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
                                <td>{{isset($val->created_at) && $val->created_at!=''?date('d-m-Y h:i:s A',strtotime($val->created_at)):''}}</td>
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
    </section>
</div>
</div>
</section>
@endsection
@push('custom-scripts')
<script>
    activeclass(12, 2);
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