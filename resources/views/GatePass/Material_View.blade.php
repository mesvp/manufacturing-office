@extends('layout.main')
@section('main-container')
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <title>Gate Pass Material IN View Details</title>
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
            border-radius: 10px;
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
    
        div#main_btn_uddhan {
            display: flex;
            justify-content: flex-end;
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
            <section class="section">
                <div class="addbtn extra">
                    <a href="{{ url('GatePass/material-list') }}" class="btn btn-info" style="margin-left:10px"> <i
                            class="fa fa-arrow-left"></i> BACK</a>
                    <a href="{{ url('GatePass/material-list') }}" class="btn btn-info" style="margin-left:10px"> <i
                            class="fa fa-home"></i> Home</a>
                </div>

                <div class="row">
                    <div class="container">
                        <br>
                        <div>
                            <div class="tabs">
                                <div class="row">
                                    <div class="col-4">
                                    </div>
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col">
                                                <h5>IN Material Gate Pass Details</h5>
                                            </div>
                                            <div class="col">
                                                <label for="">Gate Pass No. :
                                                    <b>{{ isset($edit->request_no) && $edit->request_no != '' ? $edit->request_no : '' }}</b></label>
                                            </div>
                                            <div class="col">
                                                <label for="">Inputer Name : {{ auth()->user()->fullname }}</label>
                                            </div>
                                            <div class="col">
                                                <label for="">Date & Time : <span id="clock"></span></label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="tab1">
                                    <form id="submitform" action="#" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-sm-4 form-group">
                                                <label>Created By<span class="text-danger fw-bolder">*</span></label>
                                                <input readonly class="form-control form-control-sm" type="text"
                                                    id="request_by" name="request_by" placeholder="Request By."
                                                    value="{{ isset($edit->request_by) && $edit->request_by != '' ? $edit->request_by : '' }}">
                                            </div>
                                            <div class="col-sm-4 form-group">
                                                <label>Creation Date & Time<span
                                                        class="text-danger fw-bolder">*</span></label>
                                                <input readonly class="form-control form-control-sm" type="datetime-local"
                                                    id="date_time" name="request_time"
                                                    value="{{ isset($edit->created_at) && $edit->created_at != '' ? $edit->created_at : '' }}">
                                            </div>
                                            <div class="col-sm-4 form-group">
                                                <label>Organization <span class="text-danger fw-bolder">*</span></label>
                                                <select class="form-select form-select-sm js-example-matcher-start"
                                                    name="org_id" disabled>
                                                    <option value="0" selected>Select Organization </option>
                                                    @foreach ($organisations as $organisation)
                                                        <option value="{{ $organisation->id }}"
                                                            {{ $edit->org_id == $organisation->id ? 'selected' : '' }}>
                                                            {{ $organisation->organisation }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-3 form-group">
                                                <label>Vehicle No.<span class="text-danger fw-bolder">*</span></label>
                                                <input class="form-control form-control-sm" type="text" name="vehicle_no"
                                                    placeholder="Vehicle No."
                                                    value="{{ isset($edit->vehicle_no) && $edit->vehicle_no != '' ? $edit->vehicle_no : '' }}"
                                                    readonly>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label>Vehicle Weight <span class="text-danger fw-bolder">*</span></label>
                                                <select class="form-select form-select-sm js-example-matcher-start"
                                                    name="vehicle_weight" disabled>
                                                    <option value="null" selected>Select Weight Type </option>
                                                    <option value="Empty"
                                                        {{ $edit->vehicle_weight == 'Empty' ? 'selected' : '' }}>Empty
                                                    </option>
                                                    <option value="Loaded"
                                                        {{ $edit->vehicle_weight == 'Loaded' ? 'selected' : '' }}>Loaded
                                                    </option>
                                                </select>
                                            </div>
                                            @if($edit->vehicle_weight == 'Loaded')
                                                <div class="col-sm-3 form-group">
                                                    <label>Vehicle Type<span class="text-danger fw-bolder">*</span></label>
                                                    <select class="form-select form-select-sm" name="weight_type" id="weight_type"
                                                       disabled>
                                                        <option value="" selected>Select Vehicle Type</option>
                                                        <option value="0" {{ $edit->weight_type == '0' ? 'selected' : '' }}>Other </option>
                                                        <option value="1" {{ $edit->weight_type == '1' ? 'selected' : '' }}>Finished Goods </option>
                                                    </select>
                                                </div>
                                            @endif
                                            <div class="col-sm-3 form-group">
                                                <label>IN Weight Details(KG)<span
                                                        class="text-danger fw-bolder">*</span></label>
                                                <input class="form-control form-control-sm" type="number"
                                                    name="vehicle_weight_kg" placeholder="Vehicle Weight in KG"
                                                    value="{{ isset($edit->vehicle_weight_kg) && $edit->vehicle_weight_kg != '' ? $edit->vehicle_weight_kg : '' }}"
                                                    readonly>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label>Weight Attachment</label><br>
                                                @if (isset($edit->weight_attachment))
                                                    <a
                                                        href="{{ url('GatePass/download-gatepass?path=' . (isset($edit->weight_attachment) && $edit->weight_attachment != '' ? $edit->weight_attachment : '')) }}">
                                                        {{ isset($edit->weight_attachment) && $edit->weight_attachment != '' ? $edit->weight_attachment : '' }}
                                                        <i class="fa-solid fa-download"></i>
                                                    </a>
                                                @else
                                                    <p class="text-primary">No Attachment Found !!</p>
                                                @endif
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label>Insurance No<span class="text-danger fw-bolder">*</span></label>
                                                <input class="form-control form-control-sm" type="text"
                                                    name="insurance_no" placeholder="Insurance No."
                                                    value="{{ isset($edit->insurance_no) && $edit->insurance_no != '' ? $edit->insurance_no : '' }}"
                                                    readonly>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label>Insurance Valid Date<span
                                                        class="text-danger fw-bolder">*</span></label>
                                                <input class="form-control form-control-sm" type="text"
                                                    name="insurance_dt" placeholder="Insurance Date."
                                                    value="{{ isset($edit->insurance_dt) && $edit->insurance_dt != '' ? date('d-m-Y', strtotime($edit->insurance_dt)) : '' }}"
                                                    readonly>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label>In Date & Time<span class="text-danger fw-bolder">*</span></label>
                                                <input class="form-control form-control-sm" id="intime"
                                                    type="datetime-local" name="vehicle_in_time"
                                                    placeholder="Vehicle In Time"
                                                    value="{{ isset($edit->vehicle_in_time) && $edit->vehicle_in_time != '' ? $edit->vehicle_in_time : '' }}"
                                                    readonly>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label>Driver Name<span class="text-danger fw-bolder">*</span></label>
                                                <input class="form-control form-control-sm" type="text"
                                                    name="driver_name" placeholder="Driver Name"
                                                    value="{{ isset($edit->driver_name) && $edit->driver_name != '' ? $edit->driver_name : '' }}"
                                                    readonly>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label>Driver Mobile No<span class="text-danger fw-bolder">*</span></label>
                                                <input class="form-control form-control-sm" id="driverNumber"
                                                    type="number" min="1000000000" max="9999999999"
                                                    name="driver_number" placeholder="Driver Number"
                                                    value="{{ isset($edit->driver_number) && $edit->driver_number != '' ? $edit->driver_number : '' }}"
                                                    readonly>
                                                <small id="driverNumberError" style="color: red;"></small>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label>DL Number<span class="text-danger fw-bolder">*</span></label>
                                                <input class="form-control form-control-sm" type="text" name="dl_no"
                                                    placeholder="DL Number"
                                                    value="{{ isset($edit->dl_no) && $edit->dl_no != '' ? $edit->dl_no : '' }}"
                                                    readonly>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label>DL Expire Date<span class="text-danger fw-bolder">*</span></label>
                                                <input class="form-control form-control-sm" type="date" id="dl_expire"
                                                    name="dl_expire"
                                                    value="{{ isset($edit->dl_expire) && $edit->dl_expire != '' ? $edit->dl_expire : '' }}"
                                                    readonly>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label>Invoice No</label>
                                                <textarea class="form-control" name="invoice_no" placeholder="Invoice No." cols="10" rows="2"
                                                    readonly>{{ isset($edit->invoice_no) && $edit->invoice_no != '' ? $edit->invoice_no : '' }}</textarea>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label>E - Way Bill Number<span
                                                        class="text-danger fw-bolder">*</span></label>
                                                <input class="form-control form-control-sm" type="text" name="bill_no"
                                                    placeholder="bill No."
                                                    value="{{ isset($edit->bill_no) && $edit->bill_no != '' ? $edit->bill_no : '' }}"
                                                    readonly>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label>Security Guard Name <span class="text-danger fw-bolder"
                                                        id="sec_guard_req">*</span></label>
                                                <input class="form-control form-control-sm" type="text"
                                                    name="sec_guard_name" placeholder="Security Guard Name"
                                                    value="{{ isset($edit->sec_guard_name) && $edit->sec_guard_name != '' ? $edit->sec_guard_name : '' }}"
                                                    id="sec_guard" readonly>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label>From Address ( With Company name)<span
                                                        class="text-danger fw-bolder">*</span></label>
                                                <textarea class="form-control" name="from_address" placeholder="From address" cols="10" rows="2"
                                                    readonly>{{ isset($edit->from_address) && $edit->from_address != '' ? $edit->from_address : '' }}</textarea>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label>To Address ( With Company name)<span
                                                        class="text-danger fw-bolder">*</span></label>
                                                <textarea class="form-control" name="to_address" placeholder="To Address" cols="10" rows="2" readonly>{{ isset($edit->to_address) && $edit->to_address != '' ? $edit->to_address : '' }}</textarea>

                                            </div>
                                        </div>
                                        
                                        @if ($in_items->count() > 0)
                                            <div class="row" style="border: 1px solid #bfbebe;">
                                                <div class="col-lg-12 col-md-12"></div>
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th class="th-sm text-center">SL. No.</th>
                                                                <th class="th-sm">ITEM DESCRIPTION</th>
                                                                <th class="th-sm">UOM</th>
                                                                <th class="th-sm">ITEM QTY</th>
                                                                <th class="th-sm" style="border-right: none;">REMARKS
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="GatepassFields">
                                                            @php $i = 0; @endphp
                                                            @foreach ($in_items as $in_item)
                                                                <tr>
                                                                    <td class="text-center">{{ ++$i }}</td>
                                                                    <td>
                                                                        <input class="form-control form-control-sm"
                                                                            type="text" name="item_desc[]"
                                                                            placeholder="Enter Item Description"
                                                                            value="{{ $in_item->item_desc }}" readonly>
                                                                    </td>
                                                                    <td>
                                                                        <select
                                                                            class="form-select form-select-sm js-example-matcher-start"
                                                                            name="uom_id[]" disabled>
                                                                            <option value="" selected>Select UOM
                                                                            </option>
                                                                            @foreach ($uoms as $uom)
                                                                                <option value="{{ $in_item->uom_id }}"
                                                                                    {{ $in_item->uom_id == $uom->id ? 'selected' : '' }}>
                                                                                    {{ $uom->UOMs }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input class="form-control form-control-sm"
                                                                            type="number" name="item_qty[]"
                                                                            placeholder="Enter Item Quantity"
                                                                            value="{{ $in_item->item_qty }}" readonly>
                                                                    </td>
                                                                    <td>
                                                                        <textarea class="form-control" name="item_remark[]" placeholder="Enter Remarks" cols="10" rows="1"
                                                                            readonly>{{ $in_item->item_remark }}</textarea>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @else
                                            <div class="row tab1 ininvTbls">
                                                <div class="col-lg-12 col-md-12">
                                                    <h5 class="text-center ">:: INVOICE DETAILS ::</h5>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-bordered">
                                                        <thead class="bg-white">
                                                            <tr>
                                                                <th class="th-sm text-center">SL. No.</th>
                                                                <th class="th-sm">SUPPLIER NAME</th>
                                                                <th class="th-sm">INVOICE NO.</th>
                                                                <th class="th-sm">MATERIAL</th>
                                                                <th class="th-sm">UOM</th>
                                                                <th class="th-sm">QTY</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="ininvTblBody">
                                                            @php $j = 0; @endphp
                                                            @foreach ($slno_details as $slno_detail)
                                                                <tr>
                                                                    <td class="text-center">{{ ++$j }}</td>
                                                                    <td>{{ $slno_detail->custName }}</td>
                                                                    <td>{{ $slno_detail->gp_invNo ?? '' }}</td>
                                                                    <td>{{ $slno_detail->matName }}</td>
                                                                    <td>{{ $slno_detail->invUom }}</td>
                                                                    <td>{{ $slno_detail->dispQty }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="row">
                                            <div class="col-sm-12 form-group">
                                                <textarea class="form-control" name="remarks" placeholder="Remarks" cols="30" rows="3" readonly>{{ isset($edit->remarks) && $edit->remarks != '' ? $edit->remarks : '' }}</textarea>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if ($type === 'approval')
                    <div class="container-fluid" id="appr_action"> </div>
                @else
                    <div class="container-fluid" id="action"> </div>
                @endif
                <div class="container-fluid">
                    <div class="table-responsive">
                        <table id="" class="table table-striped table-bordered mt-2 text-center"
                            style="width:100%">
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
                            <tbody id="trail"></tbody>
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
        activeclass(7, 7);
    </script>
    <script>
        function displayTime() {
            const now = new Date();
            const date = now.toLocaleDateString();
            const time = now.toLocaleTimeString();
            document.getElementById("clock").textContent = time + ', ' + date;
        }

        setInterval(displayTime, 1000);

        $(document).ready(function() {
            id = '{{ $edit->id }}';
            type = 'in';
            req_no = '{{ $edit->request_no }}';
            action = '{{ $type }}';

            // Load dynamic content
            if (action === 'approval') {
                $.post("{{ url('GatePass/material_action') }}", {
                    id: id,
                    type: type,
                    req_no: req_no
                }, function(data) {
                    $("#appr_action").html(data);
                });
            } else {
                $.post("{{ url('GatePass/material_inputeraction') }}", {
                    id: id,
                    type: type,
                    req_no: req_no
                }, function(data) {
                    $("#action").html(data);
                });
            }

            $.post("{{ url('GatePass/material_trail') }}", {
                id: id,
                type: type,
                req_no: req_no
            }, function(data) {
                $("#trail").html(data);
            });
        });
    </script>
@endpush
