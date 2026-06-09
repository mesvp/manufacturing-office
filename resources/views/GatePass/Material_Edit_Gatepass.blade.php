@extends('layout.main')
@section('main-container')
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <title>Update Gate Pass Material Form Details</title>
    <style>
        :root {
            --bg-success-clr: #95f3ff;
            --borcolor: 1px solid #a8adb1;
        }

        .btn-bgclr {
            background-color: var(--bg-success-clr);
        }

        .bdr {
            border: var(--borcolor);
        }

        .tab1 {
            padding: 20px;
            border: 1px solid #a8adb1;
            border-radius: 10px;
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
                <div class="row">
                    <div class="container">
                        <br>
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col">
                                        <h5>Update Material Gate Pass <span class="text-primary fw-bolder">({{ $edit->request_no }})</span></h5>
                                    </div>
                                    <div class="col">
                                        <label for="">Inputer Name : {{ auth()->user()->fullname }}</label>
                                    </div>
                                    <div class="col">
                                        <label for="">Date & Time : <span id="clock"></span></label>
                                    </div>
                                    <div class="col addbtn extra">
                                        <a href="{{ url('GatePass/material-list') }}" class="btn btn-info"> <i
                                                class="fa fa-arrow-left"></i> BACK</a>
                                        <a href="{{ url('GatePass/material-list') }}" class="btn btn-info"
                                            style="margin-left:10px"> <i class="fa fa-home"></i> Home</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="tab1">
                            <form id="submitform" action="{{ url('GatePass/material-update', $edit->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <input class="form-control" type="hidden" name="edit"
                                    value="{{ isset($edit->id) && $edit->id != '' ? $edit->id : '' }}" id="form_id">
                                <input readonly class="form-control form-control-sm" type="hidden" id="date"
                                    name="request_date" value="">
                                <input readonly class="form-control form-control-sm" type="hidden" id="time"
                                    name="request_time" value="">
                                <input class="form-control form-control-sm" type="hidden" name="type"
                                    value="{{ isset($type) && $type != '' ? $type : '' }}">
                                <div class="row">
                                    <div class="col-sm-4 form-group">
                                        <label>Created By <span class="text-danger fw-bolder">*</span></label>
                                        <input readonly class="form-control form-control-sm" type="text" id="request_by"
                                            name="request_by" placeholder="Request By."
                                            value="{{ auth()->user()->fullname }}">
                                    </div>
                                    <div class="col-sm-4 form-group">
                                        <label>Creation Date & Time <span class="text-danger fw-bolder">*</span></label>
                                        <input readonly class="form-control form-control-sm" type="datetime-local"
                                            id="date_time" name="request_time" value="">
                                    </div>
                                    <div class="col-sm-4 form-group">
                                        <label>Organization <span class="text-danger fw-bolder">*</span></label>
                                        <select class="form-select form-select-sm js-example-matcher-start" name="org_id"
                                            required>
                                            <option value="" selected disabled>Select Organization </option>
                                            @foreach ($organisations as $organisation)
                                                <option value="{{ $organisation->id }}"
                                                    {{ isset($edit->org_id) && $edit->org_id == $organisation->id ? 'selected' : '' }}>
                                                    {{ $organisation->organisation }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3 form-group">
                                        <label>Vehicle No. <span class="text-danger fw-bolder">*</span></label>
                                        <input class="form-control form-control-sm" type="text" name="vehicle_no"
                                            placeholder="Vehicle No."
                                            value="{{ isset($edit->vehicle_no) && $edit->vehicle_no != '' ? $edit->vehicle_no : '' }}"
                                            {{ isset($edit->vehicle_no) && $edit->vehicle_no != $edit->vehicle_no ? '' : '' }}>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>Vehicle Weight <span class="text-danger fw-bolder">*</span></label>
                                        @if ($type == 'in')
                                            <select class="form-select form-select-sm js-example-matcher-start"
                                                name="vehicle_weight" onchange="inCheckWeight()" id="vehicle_weight_in"
                                                required>
                                                <option value="" selected disabled>Select Weight Type </option>
                                                <option value="Empty"
                                                    {{ isset($edit->vehicle_weight) && $edit->vehicle_weight == 'Empty' ? 'selected' : '' }}>
                                                    Empty </option>
                                                <option value="Loaded"
                                                    {{ isset($edit->vehicle_weight) && $edit->vehicle_weight == 'Loaded' ? 'selected' : '' }}>
                                                    Loaded</option>
                                            </select>
                                        @else
                                            <select class="form-select form-select-sm js-example-matcher-start"
                                                onchange="checkWeight()" name="vehicle_weight" id="vehicle_weight" required>
                                                <option value="" selected disabled>Select Weight Type </option>
                                                <option value="Empty"
                                                    {{ isset($edit->vehicle_weight) && $edit->vehicle_weight == 'Empty' ? 'selected' : '' }}>
                                                    Empty </option>
                                                <option value="Loaded"
                                                    {{ isset($edit->vehicle_weight) && $edit->vehicle_weight == 'Loaded' ? 'selected' : '' }}>
                                                    Loaded </option>
                                            </select>
                                        @endif
                                    </div>
                                    @if ($type == 'out')
                                        <div class="col-sm-3 form-group" id="weightCategoryDiv" style="display:none;">
                                            <label>Vehicle Type<span class="text-danger fw-bolder">*</span></label>
                                            <select class="form-select form-select-sm" name="weight_type" id="weight_type"
                                                onchange="getWeightType()">
                                                <option value="" selected>Select Vehicle Type</option>
                                                <option value="0"
                                                    {{ isset($edit->weight_type) && $edit->weight_type == '0' ? 'selected' : '' }}>
                                                    Other </option>
                                                <option value="1"
                                                    {{ isset($edit->weight_type) && $edit->weight_type == '1' ? 'selected' : '' }}>
                                                    Finished Goods </option>
                                            </select>
                                        </div>
                                    @else
                                        <div class="col-sm-3 form-group" id="weightCategoryDivIn" style="display:none;">
                                            <label>Vehicle Type<span class="text-danger fw-bolder">*</span></label>
                                            <select class="form-select form-select-sm" name="weight_type"
                                                id="weight_type_in" onchange="getWeightTypeIn()">
                                                <option value="" selected>Select Vehicle Type</option>
                                                <option value="0"
                                                    {{ isset($edit->weight_type) && $edit->weight_type == '0' ? 'selected' : '' }}>
                                                    Other </option>
                                                <option value="1"
                                                    {{ isset($edit->weight_type) && $edit->weight_type == '1' ? 'selected' : '' }}>
                                                    Finished Goods </option>
                                            </select>
                                        </div>
                                    @endif
                                    <div class="col-sm-3 form-group">
                                        <label>{{ $type == 'out' ? 'OUT' : 'IN' }} Weight
                                            Details(KG) <span class="text-danger fw-bolder">*</span></label>
                                        <input class="form-control form-control-sm" type="text"
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                            name="vehicle_weight_kg" placeholder="Vehicle Weight in KG"
                                            value="{{ isset($edit->vehicle_weight_kg) ? $edit->vehicle_weight_kg : '' }}"
                                            required>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>Weight Attachment</label>
                                        @if (isset($edit->weight_attachment) && $edit->weight_attachment)
                                            <a href="{{ url('GatePass/download-gatepass?path=' . $edit->weight_attachment) }}">
                                                Download Attachment <i class="fa-solid fa-download"></i>
                                            </a>
                                            <br>
                                            <small>Upload New File </small>
                                        @endif
                                        <input class="form-control form-control-sm" type="file"
                                            name="weight_attachment" placeholder="Choose File" value="">
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>Insurance No <span class="text-danger fw-bolder">*</span></label>
                                        <input class="form-control form-control-sm" type="text" name="insurance_no"
                                            placeholder="Insurance No."
                                            value="{{ isset($edit->insurance_no) && $edit->insurance_no != '' ? $edit->insurance_no : '' }}"
                                            required
                                            {{ isset($edit->insurance_no) && $edit->insurance_no != '' ? $edit->insurance_no : '' }}>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>Insurance Valid Upto<span class="text-danger fw-bolder">*</span></label>
                                        <input class="form-control form-control-sm" type="date" name="insurance_dt"
                                            placeholder="Insurance Valid Date"
                                            value="{{ isset($edit->insurance_dt) && $edit->insurance_dt != '' ? $edit->insurance_dt : '' }}"
                                            required
                                            {{ isset($edit->insurance_dt) && $edit->insurance_dt != '' ? $edit->insurance_dt : '' }}>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label> {{ $type == 'out' ? 'Out' : 'In' }} Date & Time
                                            <span class="text-danger fw-bolder">*</span></label>
                                        <input class="form-control form-control-sm" id="intime" type="datetime-local"
                                            name="vehicle_in_time"
                                            placeholder="Vehicle {{ $type == 'out' ? 'Out' : 'In' }} Time"
                                            value="{{ isset($edit->vehicle_in_time) ? $edit->vehicle_in_time : '' }}"
                                            required>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>Driver Name <span class="text-danger fw-bolder">*</span></label>
                                        <input class="form-control form-control-sm" type="text" name="driver_name"
                                            placeholder="Driver Name"
                                            value="{{ isset($edit->driver_name) && $edit->driver_name != '' ? $edit->driver_name : '' }}"
                                            required>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>Driver Mobile No <span class="text-danger fw-bolder">*</span></label>
                                        <input class="form-control form-control-sm" id="driverNumber" type="number"
                                            min="1000000000" max="9999999999" name="driver_number"
                                            placeholder="Driver Number"
                                            value="{{ isset($edit->driver_number) && $edit->driver_number != '' ? $edit->driver_number : '' }}"
                                            onkeyup="this.value=this.value.replace(/[^\d.]+/g,'')" required>
                                        <small id="driverNumberError" style="color: red;"></small>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>DL Number <span class="text-danger fw-bolder">*</span></label>
                                        <input class="form-control form-control-sm" type="text" name="dl_no"
                                            placeholder="DL Number"
                                            value="{{ isset($edit->dl_no) && $edit->dl_no != '' ? $edit->dl_no : '' }}"
                                            required>
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>DL Expire Date <span class="text-danger fw-bolder">*</span></label>
                                        <input class="form-control form-control-sm" type="date" id="dl_expire"
                                            name="dl_expire"
                                            value="{{ isset($edit->dl_expire) && $edit->dl_expire != '' ? $edit->dl_expire : '' }}"
                                            required>
                                    </div>
                                    @if ($type == 'out')
                                        <div class="col-sm-3 form-group" id="invnoContainer" style="display: none;">
                                            <label>Invoice No</label> <span class="text-danger fw-bolder"
                                                id="inv_no_req">*</span></label>
                                            <input class="form-control form-control-sm" type="text" name="invoice_no"
                                                placeholder="Invoice No."
                                                value="{{ isset($edit->invoice_no) ? $edit->invoice_no : '' }}"
                                                id="inv_no">
                                        </div>
                                    @else
                                        <div class="col-sm-3 form-group" id="invnoContainerIn" style="display: none;">
                                            <label>Invoice No</label> <span class="text-danger fw-bolder"
                                                id="inv_no_req">*</span></label>
                                            <input class="form-control form-control-sm" type="text" name="invoice_no"
                                                placeholder="Invoice No."
                                                value="{{ isset($edit->invoice_no) ? $edit->invoice_no : '' }}"
                                                id="inv_noIn">
                                        </div>
                                    @endif


                                    <div class="col-sm-3 form-group">
                                        <label>E - Way Bill Number <span class="text-danger fw-bolder"
                                                id="bl_no_req">*</span></label>
                                        <input class="form-control form-control-sm" type="text" name="bill_no"
                                            placeholder="Bill No."
                                            value="{{ isset($edit->bill_no) ? $edit->bill_no : '' }}" id="bl_no">
                                    </div>
                                    <div class="col-sm-3 form-group">
                                        <label>Security Guard Name <span class="text-danger fw-bolder"
                                                id="sec_guard_req">*</span></label>
                                        <input class="form-control form-control-sm" type="text" name="sec_guard_name"
                                            placeholder="Security Guard Name"
                                            value="{{ isset($edit->sec_guard_name) ? $edit->sec_guard_name : '' }}"
                                            id="sec_guard">
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6 form-group">
                                            <label>From Address ( With Company name ) <span class="text-danger fw-bolder"
                                                    id="frm_adrs_req">*</span></label>
                                            <textarea class="form-control" name="from_address" id="frm_adrs" placeholder="From address" cols="10"
                                                rows="2">{{ isset($edit->from_address) ? $edit->from_address : '' }}</textarea>
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <label>To Address ( With Company name ) <span class="text-danger fw-bolder"
                                                    id="to_adrs_req">*</span></label>
                                            <textarea class="form-control" name="to_address" id="to_adrs" placeholder="To Address" cols="10"
                                                rows="2">{{ isset($edit->to_address) ? $edit->to_address : '' }}</textarea>
                                        </div>
                                    </div>
                                    @if ($type == 'out')
                                        <div class="col-sm-3 form-group" id="invnosContainer" style="display: none;">
                                            <label>Select Invoice No</label> <span class="text-danger fw-bolder"
                                                id="inv_no_req">*</span></label>
                                            <select class="form-select form-select-sm js-example-matcher-start"
                                                name="invoice_nos[]" id="inv_nos" style="display: none;"
                                                onchange="getInvDtls()" multiple>
                                                @foreach ($invoices as $invoice)
                                                    <option value="{{ $invoice }}"
                                                        {{ in_array($invoice, $selectedInvoices) ? 'selected' : '' }}>
                                                        {{ $invoice }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @else
                                        <div class="col-sm-3 form-group" id="invnosContainerIn" style="display: none;">
                                            <label>Select Invoice No</label> <span class="text-danger fw-bolder"
                                                id="inv_no_req">*</span></label>
                                            <select class="form-select form-select-sm js-example-matcher-start"
                                                name="invoice_nos[]" id="inv_nosIn" style="display: none;"
                                                onchange="getInvDtlsIn()" multiple>
                                                @foreach ($in_invoices as $inv_no)
                                                    <option value="{{ $inv_no }}"
                                                        {{ in_array($inv_no, $selectedInvoices) ? 'selected' : '' }}>
                                                        {{ $inv_no }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                </div>

                                @if ($type == 'out')
                                    <div class="row tab1 outinvTbls" style="display: none;">
                                        <div class="col-lg-12 col-md-12">
                                            <h5 class="text-center text-bolder">:: INVOICE DETAILS ::</h5>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <thead class="bg-white">
                                                    <tr>
                                                        <th class="th-sm text-center">SL. No.</th>
                                                        <th class="th-sm">SUPPLIER NAME</th>
                                                        <th class="th-sm">MATERIAL</th>
                                                        <th class="th-sm">UOM</th>
                                                        <th class="th-sm">QTY</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="invTblBody">
                                                    <!-- Table will be populated by JavaScript -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @else
                                    <div class="row tab1 ininvTbls" style="display: none;">
                                        <div class="col-lg-12 col-md-12">
                                            <h5 class="text-center ">:: INVOICE DETAILS ::</h5>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <thead class="bg-white">
                                                    <tr>
                                                        <th class="th-sm text-center">SL. No.</th>
                                                        <th class="th-sm">SUPPLIER NAME</th>
                                                        <th class="th-sm">MATERIAL</th>
                                                        <th class="th-sm">UOM</th>
                                                        <th class="th-sm">QTY</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="ininvTblBody">
                                                    <!-- Table will be populated by JavaScript -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-sm-12 form-group">
                                        <textarea class="form-control" name="remarks" placeholder="Enter Remarks" cols="30" rows="3">{{ isset($edit->remarks) ? $edit->remarks : '' }}</textarea>
                                    </div>
                                </div>

                                <div style="overflow:auto;">
                                    <div style="float:right;">
                                        <button type="submit" class="btn btn-bgclr float-right"
                                            style="margin: 5px;">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
@push('custom-scripts')
    <script>
        activeclass(7, 7);
    </script>
    <script>
        function checkWeight() {
            var form_id = $("#form_id").val();
            var chk_wgt = $("#vehicle_weight").val();

            $("#inv_no").val("");
            $("#inv_nos").val(null).trigger('change');

            $("#invnoContainer").hide();
            $("#invnosContainer").hide();
            $(".outinvTbls").hide();

            if (form_id != '') {
                if (chk_wgt == 'Empty') {
                    $("#inv_no_req").hide();
                    $("#bl_no_req").hide();
                    $("#frm_adrs_req").hide();
                    $("#to_adrs_req").hide();

                    $("#inv_no").prop('required', false);
                    $("#bl_no").prop('required', false);
                    $("#frm_adrs").prop('required', false);
                    $("#to_adrs").prop('required', false);

                    $("#weightCategoryDiv").hide();
                    $("#invnoContainer").show();
                    $(".outinvTbls").hide();
                } else if (chk_wgt == 'Loaded') {
                    $("#inv_no_req").show();
                    $("#bl_no_req").show();
                    $("#frm_adrs_req").show();
                    $("#to_adrs_req").show();

                    $("#inv_no").prop('required', true);
                    $("#bl_no").prop('required', true);
                    $("#frm_adrs").prop('required', true);
                    $("#to_adrs").prop('required', true);

                    $("#weightCategoryDiv").show();
                }
            }
        }

        function inCheckWeight() {
            var chk_wgt = $("#vehicle_weight_in").val();

            $("#inv_noIn").val("");
            $("#inv_nosIn").val(null).trigger('change');

            $("#invnoContainerIn").hide();
            $("#invnosContainerIn").hide();
            $(".ininvTbls").hide();

            if (chk_wgt == 'Empty') {
                $("#inv_no_req").hide();
                $("#bl_no_req").hide();
                $("#frm_adrs_req").hide();
                $("#to_adrs_req").hide();

                $("#inv_noIn").prop('required', false);
                $("#bl_no").prop('required', false);
                $("#frm_adrs").prop('required', false);
                $("#to_adrs").prop('required', false);

                $("#weightCategoryDivIn").hide();
                $("#invnoContainerIn").show();
                $(".ininvTbls").hide();

            } else if (chk_wgt == 'Loaded') {
                $("#inv_no_req").show();
                $("#bl_no_req").show();
                $("#frm_adrs_req").show();
                $("#to_adrs_req").show();

                $("#inv_noIn").prop('required', true);
                $("#bl_no").prop('required', true);
                $("#frm_adrs").prop('required', true);
                $("#to_adrs").prop('required', true);

                $("#weightCategoryDivIn").show();
            }
        }

        function getWeightType() {
            var weight_type = $("#weight_type").val();

            $("#inv_no").val("");
            $("#inv_nos").val(null).trigger('change');

            $("#invnoContainer").hide();
            $("#invnosContainer").hide();
            $(".outinvTbls").hide();

            if (weight_type == "1") {
                $("#invnosContainer").show();
                $("#inv_nos").show();
            } else {
                $("#invnoContainer").show();
                $(".outinvTbls").hide();
                $("#inv_no").prop("required", true);
            }
        }

        function getWeightTypeIn() {
            var weight_type = $("#weight_type_in").val();

            $("#inv_noIn").val("");
            $("#inv_nosIn").val(null).trigger('change');

            $("#invnoContainerIn").hide();
            $("#invnosContainerIn").hide();
            $(".ininvTbls").hide();

            if (weight_type == "1") {
                $("#invnosContainerIn").show();
                $("#inv_nosIn").show();
            } else {
                $("#invnoContainerIn").show();
                $(".ininvTbls").hide();
                $("#inv_noIn").prop("required", true);
            }
        }
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
        var currentDate = new Date().toISOString().slice(0, 10);
        var currentDateTime = new Date();

        var dateField = document.getElementById('date');
        if (dateField.value === '') {
            dateField.value = currentDate;
        }

        var timeField = document.getElementById('time');
        if (timeField.value === '') {
            var hours = currentDateTime.getHours().toString().padStart(2, '0');
            var minutes = currentDateTime.getMinutes().toString().padStart(2, '0');
            timeField.value = hours + ':' + minutes;
        }
        var datetimeField = document.getElementById('date_time');
        if (datetimeField.value === '') {
            datetimeField.value = dateField.value + ' ' + timeField.value;
        }
    </script>
    <script>
        var driverNumberInput = document.getElementById('driverNumber');
        var driverNumberError = document.getElementById('driverNumberError');

        driverNumberInput.addEventListener('input', function(event) {
            var input = event.target.value;
            var isValid = /^\d{10}$/.test(input);

            if (isValid) {
                driverNumberError.textContent = '';
            } else {
                driverNumberError.textContent = 'Driver Number Should Be 10 Digits.';
            }
        });
    </script>
    <script>
        // Initialize select2 on page load
        $(document).ready(function() {
            $('.js-example-matcher-start').select2();

            // Show select elements
            $("#inv_nos").show();
            $("#inv_nosIn").show();

            // Function to load invoice details on page load
            function loadInvoiceDetailsOnPageLoad() {
                @if (isset($edit))
                    @if ($type == 'out')
                        // For OUT edit page
                        if ($("#vehicle_weight").val() === 'Loaded') {
                            $("#weightCategoryDiv").show();
                            if ($("#weight_type").val() === '1') {
                                $("#invnosContainer").show();
                                $("#inv_nos").show();
                                
                                // Load invoice details immediately
                                if ($("#inv_nos").val() && $("#inv_nos").val().length > 0) {
                                    $(".outinvTbls").show();
                                    getInvDtls();
                                }
                            } else if ($("#weight_type").val() === '0') {
                                $("#invnoContainer").show();
                            }
                        }
                    @elseif ($type == 'in')
                        // For IN edit page
                        if ($("#vehicle_weight_in").val() === 'Loaded') {
                            $("#weightCategoryDivIn").show();
                            if ($("#weight_type_in").val() === '1') {
                                $("#invnosContainerIn").show();
                                $("#inv_nosIn").show();
                                
                                // Load invoice details immediately
                                if ($("#inv_nosIn").val() && $("#inv_nosIn").val().length > 0) {
                                    $(".ininvTbls").show();
                                    getInvDtlsIn();
                                }
                            } else if ($("#weight_type_in").val() === '0') {
                                $("#invnoContainerIn").show();
                            }
                        }
                    @endif
                @endif
            }

            // Call the function after a short delay to ensure DOM is ready
            setTimeout(loadInvoiceDetailsOnPageLoad, 100);
        });

        function getInvDtls() {
            var selectedInvoices = $('#inv_nos').val();

            if (!selectedInvoices || selectedInvoices.length === 0) {
                $('#invTblBody').empty();
                $('.outinvTbls').hide();
                return;
            }

            $.ajax({
                url: "{{ url('GatePass/get-invoice-dtls') }}",
                type: "GET",
                data: {
                    _token: '{{ csrf_token() }}',
                    'invoices': selectedInvoices
                },

                beforeSend: function() {
                    $('.outinvTbls').show();
                    $("#invTblBody").html(`
                    <tr>
                        <td colspan="5" class="text-center text-muted">Loading...</td>
                    </tr>
                `);
                },

                success: function(response) {
                    let tbody = $('#invTblBody');
                    tbody.empty();
                    let sl = 1;

                    if (response.success && response.data && response.data.length > 0) {
                        response.data.forEach(function(inv) {
                            let matList = response.mat_details.filter(
                                m => m.dispatch_id == inv.id
                            );

                            matList.forEach(function(mat) {
                                // Get serial numbers for this material
                                let slnos = '';
                                if (response.slno_details) {
                                    slnos = response.slno_details
                                        .filter(sn => sn.material_id == mat.material_id)
                                        .map(sn => sn.serial_no)
                                        .join(',');
                                }

                                let row = `
                                <tr>
                                    <td class="text-center"><input type="hidden" name="slno_details[]" value="${slnos}">${sl}</td>
                                    <td>
                                        ${inv.customer_name || '-'}
                                        <input type="hidden" name="inv_no[]" value="${inv.invoice_no}">
                                        <input type="hidden" name="material_id[]" value="${mat.material_id}">
                                        <input type="hidden" name="customer_name[]" value="${inv.customer_name || ''}">
                                    </td>
                                    <td>
                                        ${mat.model_name || '-'}
                                        <input type="hidden" name="model_name[]" value="${mat.model_name || ''}">
                                    </td>
                                    <td>
                                        ${mat.uom || '-'}
                                        <input type="hidden" name="inv_uom[]" value="${mat.uom || ''}">
                                    </td>
                                    <td>
                                        ${mat.dispatch_qty || '-'}
                                        <input type="hidden" name="dispatch_qty[]" value="${mat.dispatch_qty || ''}">
                                        <input type="hidden" name="slno_details[]" value="${slnos}">
                                    </td>
                                </tr>`;

                                tbody.append(row);
                                sl++;
                            });
                        });
                    } else {
                        tbody.html(`
                        <tr>
                            <td colspan="5" class="text-center text-muted">No invoice details found</td>
                        </tr>
                    `);
                    }
                },

                error: function(xhr) {
                    console.log(xhr.responseText);
                    $('#invTblBody').html(`
                    <tr>
                        <td colspan="5" class="text-center text-danger">Error loading invoice details</td>
                    </tr>
                `);
                }
            });
        }

        function getInvDtlsIn() {
            var selectedInvoices = $('#inv_nosIn').val();

            if (!selectedInvoices || selectedInvoices.length === 0) {
                $('#ininvTblBody').empty();
                $('.ininvTbls').hide();
                return;
            }

            $.ajax({
                url: "{{ url('GatePass/get-invoice-dtls-in') }}",
                type: "GET",
                data: {
                    _token: '{{ csrf_token() }}',
                    'invoices': selectedInvoices
                },

                beforeSend: function() {
                    $('.ininvTbls').show();
                    $("#ininvTblBody").html(`
                    <tr>
                        <td colspan="5" class="text-center text-muted">Loading...</td>
                    </tr>
                `);
                },

                success: function(response) {
                    let tbody = $('#ininvTblBody');
                    tbody.empty();
                    let sl = 1;

                    if (response.success && response.data && response.data.length > 0) {
                        response.data.forEach(function(inv) {
                            let matList = response.mat_details.filter(m => m.unique_id == inv
                            .unique_id);

                            matList.forEach(function(mat) {
                                let row = `
                                <tr>
                                    <td><input type="hidden" name="inv_no[]" value="${inv.inv_no}">${sl}</td>
                                    <td>${inv.supplier_name || '-'}</td>
                                    <td>${mat.material_name || '-'}</td>
                                    <td>${mat.trnst_uom || '-'}</td>
                                    <td>${mat.trnst_dip_qty || '-'}</td>
                                    <input type="hidden" name="matid[]" value="${mat.trnst_matid}">
                                    <input type="hidden" name="supplier_name[]" value="${inv.supplier_name || '-'}">
                                    <input type="hidden" name="material_name[]" value="${mat.material_name || '-'}">
                                    <input type="hidden" name="trnst_uom[]" value="${mat.trnst_uom || '-'}">
                                    <input type="hidden" name="trnst_dip_qty[]" value="${mat.trnst_dip_qty || '-'}">
                                </tr>`;
                                tbody.append(row);
                                sl++;
                            });
                        });
                    } else {
                        tbody.html(`
                        <tr>
                            <td colspan="5" class="text-center text-muted">No invoice details found</td>
                        </tr>
                    `);
                    }
                },

                error: function(xhr) {
                    console.log(xhr.responseText);
                    $('#ininvTblBody').html(`
                    <tr>
                        <td colspan="5" class="text-center text-danger">Error loading invoice details</td>
                    </tr>
                `);
                }
            });
        }
    </script>
@endpush