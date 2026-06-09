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

    div#Tabledata_length {
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
                <div class="container-fluid">
                <div class="col-xl-12 col-md-12 col-sm-12 mb-2">
                        <div class="row">
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                               <h5>Mrn Stock Transfer Details</h5>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                               <label for="">Inputer Name : {{auth()->user()->name}}</label>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                <label for="">Date & Time : <span id="clock"></span></label>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                <div class="addbtn extra p-0">
                                    <a href="{{url('StockTransfer/ApprovalList')}}" class="btn btn-info mr-1 btn-sm"> <i class="fa fa-arrow-left"></i></a>
                                    <a href="{{url('StockTransfer/ApprovalList')}}" class="btn btn-info btn-sm"> <i class="fa fa-home"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>


                        <div class="col-xl-12 col-md-12 col-sm-12 border">
                            <form action="3" method="POST">
                                @csrf
                                <input class="form-control" type="hidden" name="edit" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                                <div class="row">
                                    <div class="col-sm-3 form-group" id="finishedgooddiv">
                            <label>
                                Material
                            </lable>
                                <input readonly class="form-control form-control-sm" type="text" name="Material" id="Material" placeholder="UOM" value="{{isset($edit->matname) && $edit->matname!=''?$edit->matname:''}}" required>
                                <input type="hidden" name="Material_id" value="{{isset($edit->Material) && $edit->Material!=''?$edit->Material:''}}" >
                                <input type="hidden" name="prj_Material_id" value="{{isset($edit->material_id) && $edit->material_id!=''?$edit->material_id:''}}" >
                            </div>
                            
                            <div class="col-sm-3 form-group" id="uomdiv">
                            <label>UOM</label>
                            <div class="field-wrap">
                                <input readonly class="form-control form-control-sm" type="text" name="UOM" id="uom" placeholder="UOM" value="{{isset($edit->uom) && $edit->uom!=''?$edit->uom:''}}" required>
                                
                            </div>
                            </div>
                            <div class="col-sm-3 form-group" id="hsncodediv">
                            <label>Purchase Date*</label>
                            <div class="field-wrap">
                                <input readonly class="form-control form-control-sm" type="text" name="purchahedate" id="purchahedate" placeholder="UOM" value="{{isset($edit->Mrn_Date) && $edit->Mrn_Date!=''?$edit->Mrn_Date:''}}" required>
                            </div>
                            </div>
                            <div class="col-sm-3 form-group" id="uomdiv">
                            <label>Purchase Qty</label>
                            <div class="field-wrap">
                                <input readonly class="form-control form-control-sm" type="text" name="purchase_qty" id="purchase_qty" placeholder="UOM" value="{{isset($edit->Quantity) && $edit->Quantity!=''?$edit->Quantity:''}}" required>
                            </div>
                            </div>
                            <div class="col-sm-3 form-group" id="uomdiv">
                            <label>To Organization*</label>
                            <div class="field-wrap">
                                <select name="Organization_Name" id="Organization_Name" class="form-control form-control-sm" disabled>
                                    <option value="">Select Organization</option>
                                    @foreach($Organization_Name as $org)
                                        <option value="{{ $org->id }}" {{ (isset($edit->Organization_Name) && $edit->Organization_Name == $org->id) ? 'selected' : '' }}>{{ $org->organisation }}</option>
                                    @endforeach
                                </select>
                            </div>
                            </div>
                            <div class="col-sm-3 form-group" id="uomdiv">
                            <label>To Godown*</label>
                            <div class="field-wrap">
                                <select name="Godown_Name" id="Godown_Name" class="form-control form-control-sm" disabled>
                                    <option value="">Select Godown</option>
                                    @foreach($Godown_Name as $godown)
                                        <option value="{{ $godown->id }}" {{ (isset($edit->Godown_Name) && $edit->Godown_Name == $godown->id) ? 'selected' : '' }}>{{ $godown->inventory_name ?? $godown->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            </div>
                        </div>
                        <br>
                            </div>

                            <div class="table-responsive">
                                <table id="Tabledata" class="table table-striped table-bordered dataTable no-footer example" style="width:100%">
                                    <thead>
                                         <tr>
                                            <th class="th-sm" id="slno">SL No.</th>
                                            <th class="th-sm">Serial No.</th>
                                            <th class="th-sm">Supplier</th>
                                            <th class="th-sm">DOP</th>
                                            <th class="th-sm">Make</th>
                                            <th class="th-sm">Brand</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $i=1;
                                        @endphp
                                        @foreach($Materials as $key=>$MaterialVal)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>
                                                <input readonly type="text" name="HSN_Code_Second[{{$i}}]" class="form-control form-control-sm" value="{{isset($MaterialVal->serial_no) && $MaterialVal->serial_no!=''?$MaterialVal->serial_no:''}}">
                                            </td>
                                            <td>
                                                <input readonly type="text" name="HSN_Code_Second[{{$i}}]" class="form-control form-control-sm" value="{{isset($MaterialVal->supplier) && $MaterialVal->supplier!=''?$MaterialVal->supplier:''}}">
                                            </td>
                                            <td>
                                                <div class="field-wrap">
                                                    <input readonly type="text" name="HSN_Code_Second[{{$i}}]" class="form-control form-control-sm" value="{{isset($MaterialVal->dop) && $MaterialVal->dop!=''?$MaterialVal->dop:''}}">
                                                </div>
                                            </td>
                                            <td>
                                                <input disabled type="text" name="QTY[{{$i}}]" class="form-control form-control-sm" value="{{isset($MaterialVal->make) && $MaterialVal->make!=''?$MaterialVal->make:''}}">
                                            </td>
                                            <td>
                                                <input disabled type="text" name="QTY[{{$i}}]" class="form-control form-control-sm" value="{{isset($MaterialVal->brand) && $MaterialVal->brand!=''?$MaterialVal->brand:''}}">
                                            </td>
                                        </tr>
                                        @php
                                        $i++;
                                        @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-8 form-group"></div>
                                <div class="col-sm-4 form-group">
                                    <label for="State">Remarks:</label>
                                    <input disabled type="text" name="remarks" cols="30" rows="5" class="form-control form-control-sm" placeholder="Remarks" value="{{isset($remarks->remarks) && $remarks->remarks!=''?$remarks->remarks:''}}">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <hr>
                @php
                $STEP = Session::get('STEP');
                $EXT = Session::get('EXT');
                @endphp
                @if($edit->Approve_status!='REJECT')
                <form action="{{url('StockTransfer/approve')}}" method="POST">
                    @csrf
                    <input type="hidden" name="approveID" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                    <div class="tab-content" id="myTabContent">
                        @if($edit->Approve_status!='APPROVE' && in_array(1, $STEP) || in_array(2, $STEP) || in_array(3, $STEP) || isset($EXT[15]['Forward']))
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
                            <div id="showfields" class="row" style="display: none;">
                                <div class="col-sm-4 form-group">
                                    <label>Days For Holding</lable>
                                        <input type="date" style="border-radius: 12px;" name="days_for_holding" placeholder="Days For Holding" min="{{date('Y-m-d')}}" class="form-control form-control-sm requireddd" value="">
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
                    <a href="{{url('StockTransfer/view-approve/'.$nextID)}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                    @else
                    <a href="{{url('StockTransfer/ApprovalList')}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                    @endif
                </form>
                @endif
                <div class="table-responsive">
                    <table id="" class="table table-striped table-bordered example w-100">
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

        </section>
    </div>
</div>

@endsection
@push('custom-scripts')
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
        activeclass(29, 2);
    });
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
