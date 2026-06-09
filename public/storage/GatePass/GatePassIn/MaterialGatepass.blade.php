@extends('layout.main')
@section('main-container')
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
<title>Gate Pass Material Form Details</title>
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
                <a href="{{url('GatePass/material-list')}}" class="btn btn-info"> <i class="fa fa-arrow-left"></i> BACK</a>
                <a href="{{url('GatePass/material-list')}}" class="btn btn-info" style="margin-left:10px"> <i class="fa fa-home"></i> Home</a>
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
                                            <h5>Material Gate Pass</h5>
                                        </div>
                                        <div class="col">
                                            <label for="">Inputer Name : {{auth()->user()->fullname}}</label>
                                        </div>
                                        <div class="col">
                                            <label for="">Date & Time : <span id="clock"></span></label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="tab1">
                                <form id="submitform" action="{{url('GatePass/material-store')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input class="form-control" type="hidden" name="edit" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}" id="form_id">
                                    <input readonly class="form-control form-control-sm" type="hidden" id="date" name="request_date" value="">
                                    <input readonly class="form-control form-control-sm" type="hidden" id="time" name="request_time" value="">
                                    <div class="row">
                                        <div class="col-sm-4 form-group">
                                            <label>Created By <span class="text-danger fw-bolder">*</span></label>
                                            <input readonly class="form-control form-control-sm" type="text" id="request_by" name="request_by" placeholder="Request By." value="{{auth()->user()->fullname}}">
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>Creation Date & Time <span class="text-danger fw-bolder">*</span></label>
                                            <input readonly class="form-control form-control-sm" type="datetime-local" id="date_time" name="request_time" value="">
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>Organization <span class="text-danger fw-bolder">*</span></label>
                                            <select class="form-select form-select-sm js-example-matcher-start" name="org_id" required>
                                                <option value="" selected disabled>Select Organization </option>
                                                @foreach($organisations as $organisation)
                                                <option value="{{$organisation->id}}">{{$organisation->organisation}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-3 form-group">
                                            <label>Vehicle No. <span class="text-danger fw-bolder">*</span></label>
                                            <input class="form-control form-control-sm" type="text" name="vehicle_no" placeholder="Vehicle No." value="{{isset($edit->vehicle_no) && $edit->vehicle_no!=''?$edit->vehicle_no:''}}" {{isset($edit->vehicle_no) && $edit->vehicle_no!=''?'readonly':''}}>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>Vehicle Weight <span class="text-danger fw-bolder">*</span></label>
                                            <select class="form-select form-select-sm js-example-matcher-start" onchange="checkWeight()" name="vehicle_weight" id="weight_type" required>
                                                <option value="" selected disabled>Select Weight Type </option>
                                                <option value="Empty">Empty </option>
                                                <option value="Loaded">Loaded </option>
                                            </select>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>{{isset($edit->id) && $edit->id!=''?'OUT':'IN'}} Weight Details(KG) <span class="text-danger fw-bolder">*</span></label>
                                            <input class="form-control form-control-sm" type="number" name="vehicle_weight_kg" placeholder="Vehicle Weight in KG" value="" required>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>Weight Attachment</label>
                                            <input class="form-control form-control-sm" type="file" name="weight_attachment" placeholder="Choose File" value="">
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>Insurance No <span class="text-danger fw-bolder">*</span></label>
                                            <input class="form-control form-control-sm" type="text" name="insurance_no" placeholder="Insurance No." value="{{isset($edit->insurance_no) && $edit->insurance_no!=''?$edit->insurance_no:''}}" required {{isset($edit->insurance_no) && $edit->insurance_no!=''?'readonly':''}}>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>Insurance Valid Upto<span class="text-danger fw-bolder">*</span></label>
                                            <input class="form-control form-control-sm" type="date" name="insurance_dt" placeholder="Insurance Valid Date" value="{{isset($edit->insurance_dt) && $edit->insurance_dt!=''?$edit->insurance_dt:''}}" required {{isset($edit->insurance_dt) && $edit->insurance_dt!=''?'readonly':''}}>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label> {{isset($edit->id) && $edit->id!=''?'Out':'In'}} Date & Time <span class="text-danger fw-bolder">*</span></label>
                                            <input class="form-control form-control-sm" id="intime" type="datetime-local" name="vehicle_in_time" placeholder="Vehicle {{isset($edit->id) && $edit->id!=''?'Out':'In'}} Time" value="" required>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>Driver Name <span class="text-danger fw-bolder">*</span></label>
                                            <input class="form-control form-control-sm" type="text" name="driver_name" placeholder="Driver Name" value="{{isset($edit->driver_name) && $edit->driver_name!=''?$edit->driver_name:''}}" required>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>Driver Mobile No <span class="text-danger fw-bolder">*</span></label>
                                            <input class="form-control form-control-sm" id="driverNumber" type="number" min="1000000000" max="9999999999" name="driver_number" placeholder="Driver Number" value="{{isset($edit->driver_number) && $edit->driver_number!=''?$edit->driver_number:''}}" onkeyup="this.value=this.value.replace(/[^\d.]+/g,'')" required>
                                            <small id="driverNumberError" style="color: red;"></small>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>DL Number <span class="text-danger fw-bolder">*</span></label>
                                            <input class="form-control form-control-sm" type="text" name="dl_no" placeholder="DL Number" value="{{isset($edit->dl_no) && $edit->dl_no!=''?$edit->dl_no:''}}" required>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>DL Expire Date <span class="text-danger fw-bolder">*</span></label>
                                            <input class="form-control form-control-sm" type="date" id="dl_expire" name="dl_expire" value="{{isset($edit->dl_expire) && $edit->dl_expire!=''?$edit->dl_expire:''}}" required>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>Invoice No</label> <span class="text-danger fw-bolder" id="inv_no_req">*</span></label>
                                            <input class="form-control form-control-sm" type="text" name="invoice_no" placeholder="Invoice No." value="" id="inv_no" required>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>E - Way Bill Number <span class="text-danger fw-bolder" id="bl_no_req">*</span></label>
                                            <input class="form-control form-control-sm" type="text" name="bill_no" placeholder="Bill No." value="" id="bl_no" required>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>Security Guard Name <span class="text-danger fw-bolder" id="sec_guard_req">*</span></label>
                                            <input class="form-control form-control-sm" type="text" name="sec_guard_name" placeholder="Security Guard Name" value="" id="sec_guard" required>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>From Address ( With Company name ) <span class="text-danger fw-bolder" id="frm_adrs_req">*</span></label>
                                            <textarea class="form-control" name="from_address" id="frm_adrs" placeholder="From address" cols="10" rows="2"></textarea>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>To Address ( With Company name ) <span class="text-danger fw-bolder" id="to_adrs_req">*</span></label>
                                            <textarea class="form-control" name="to_address" id="to_adrs" placeholder="To Address" cols="10" rows="2"></textarea>
                                        </div>
                                        @if(isset($edit))
                                            <div class="row align-items-center fin_good" style="display: none;">
                                                <label class="col-auto fw-bold">Finished Good
                                                    <span class="text-danger fw-bolder" id="f_good_req">*</span>
                                                </label>
                                                <div class="col-auto form-group">
                                                    <div class="form-check">
                                                        <input type="radio" class="form-check-input" name="f_good" id="fin_good_yes" onclick="toggleItemDesc(true)">
                                                        <label for="fin_good_yes" class="form-check-label">YES</label>
                                                    </div>
                                                </div>
                                                <div class="col-auto form-group">
                                                    <div class="form-check">
                                                        <input type="radio" class="form-check-input" name="f_good" id="fin_good_no" checked onclick="toggleItemDesc(false)">
                                                        <label for="fin_good_no" class="form-check-label">NO</label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12 col-md-12"></div>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="th-sm text-center">SL. No.</th>
                                                        <th class="th-sm">ITEM DESCRIPTION</th>
                                                        <th class="th-sm">UOM</th>
                                                        <th class="th-sm">ITEM QTY</th>
                                                        <th class="th-sm">REMARKS</th>
                                                        <th class="th-sm">ACTION</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="GatepassFields">
                                                    <tr id="itemRow1">
                                                        <td class="text-center">1</td>
                                                        <td class="item-desc-cell">
                                                            <input class="form-control form-control-sm" type="text" name="item_desc[]" placeholder="Enter Item Description" value="">
                                                        </td>
                                                        <td>
                                                            <select class="form-select form-select-sm js-example-matcher-start" name="uom_id[]">
                                                                <option value="0" selected disabled>Select UOM</option>
                                                                @foreach($uoms as $uom)
                                                                <option value="{{$uom->id}}">{{$uom->UOMs}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input class="form-control form-control-sm" type="number" name="item_qty[]" placeholder="Enter Item Quantity" value="">
                                                        </td>
                                                        <td>
                                                            <textarea class="form-control" name="item_remark[]" placeholder="Enter Remarks" cols="10" rows="1"></textarea>
                                                        </td>
                                                        <td>
                                                            <a href="javascript:;" id="gatepassAppend" onclick="gatepassAppend(1)" class="btn btn-success btn-sm mt-2"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 form-group">
                                            <textarea class="form-control" name="remarks" placeholder="Enter Remarks" cols="30" rows="3"></textarea>
                                        </div>
                                    </div>
                                    <div style="overflow:auto;">
                                        <div style="float:right;">
                                            <button type="submit" class="btn btn-bgclr float-right" style="margin: 5px;">Submit</button>
                                        </div>
                                    </div>
                                </form>
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
    activeclass(7, 3);
</script>
<script>
    function checkWeight() {
        var form_id = $("#form_id").val();
        var chk_wgt = $("#weight_type").val();
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
                $(".fin_good").hide();
            } else {
                $("#inv_no_req").show();
                $("#bl_no_req").show();
                $("#frm_adrs_req").show();
                $("#to_adrs_req").show();
                $("#inv_no").prop('required', true);
                $("#bl_no").prop('required', true);
                $("#frm_adrs").prop('required', true);
                $("#to_adrs").prop('required', true);
                $(".fin_good").show();
            }
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
    function AppendSelect2() {
        $('.js-example-matcher-start').select2();
    }

    function toggleItemDesc(isDropdown) {
        const rows = document.querySelectorAll('#GatepassFields .item-desc-cell');
        rows.forEach((cell, index) => {
            if (isDropdown) {
                const options = `@foreach($materials as $val)<option value="{{$val->RawMaterial->id}}" {{isset(request()->Raw_Material) && request()->Raw_Material==$val->RawMaterial->id?'selected':''}}>{{$val->RawMaterial->matname}}</option>@endforeach`;
                cell.innerHTML = `
                    <select class="form-select form-select-sm js-example-matcher-start" name="item_desc[]" required>
                        <option value="" selected disabled>Select Item Description</option>
                        ${options}
                    </select>`;
            } else {
                cell.innerHTML = `
                    <input class="form-control form-control-sm" type="text" name="item_desc[]" placeholder="Enter Item Description" value="">`;
            }
        });
        AppendSelect2();

    }

    function gatepassAppend(i) {
        i++;
        const finGoodYes = document.getElementById('fin_good_yes');
        const isDropdown = finGoodYes ? finGoodYes.checked : false;
        const options = `@foreach($uoms as $uom)<option value="{{$uom->id}}">{{$uom->UOMs}}</option>@endforeach`;
        const itemOptions = `@foreach($materials as $val)<option value="{{$val->RawMaterial->id}}" {{isset(request()->Raw_Material) && request()->Raw_Material==$val->RawMaterial->id?'selected':''}}>{{$val->RawMaterial->matname}}</option>@endforeach`;

        const itemDescField = isDropdown ?
            `<select class="form-select form-select-sm js-example-matcher-start" name="item_desc[]" required>
                    <option value="" selected disabled>Select Item Description</option>
                    ${itemOptions}
               </select>` :
            `<input class="form-control form-control-sm" type="text" name="item_desc[]" placeholder="Enter Item Description" value="">`;

        $('#GatepassFields').append(`
            <tr id="gatepassRemove${i}">
                <td class="text-center">${i}</td>
                <td class="item-desc-cell">${itemDescField}</td>
                <td>
                    <select class="form-select form-select-sm js-example-matcher-start" name="uom_id[]">
                        <option value="0" selected disabled>Select UOM</option>
                        ${options}
                    </select>
                </td>
                <td>
                    <input class="form-control form-control-sm" type="number" name="item_qty[]" placeholder="Enter Item Quantity" value="">
                </td>
                <td>
                    <textarea class="form-control" name="item_remark[]" placeholder="Enter Remarks" cols="10" rows="1"></textarea>
                </td>
                <td>
                    <a href="javascript:;" onclick="gatepassRemove(${i})" class="btn btn-danger btn-sm mt-2">X</a>
                </td>
            </tr>
        `);

        $("#gatepassAppend").attr("onclick", `gatepassAppend(${i})`);
        AppendSelect2();
    }

    function gatepassRemove(id) {
        $("#gatepassRemove" + id).remove();
    }
</script>

@endpush