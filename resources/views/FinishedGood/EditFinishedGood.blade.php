@extends('layout.main')
@section('main-container')
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <title>Finished Good Gate Pass Material Details</title>
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
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            <section class="section">
                <div class="container-fluid">
                    <div class="border-bottom pb-2 row">
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <h6>Finished Good Gatepass Details ({{ $edit->uniqID ?? '' }})</h6>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="">Inputer Name : {{ $uname }}</label>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="">Date & Time :
                                {{ isset($edit->created_at) && $edit->created_at != '' ? date('d-m-Y h:i A', strtotime($edit->created_at)) : '' }}</label>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="addbtn p-0"> <!-- class :- extra -->
                                <a href="{{ url('FinishedGood/ExportFinishedGoodViewData/' . $id) }}"><i
                                        class='fa-file-excel fas text-success'></i></a>
                                <a href="{{ url('FinishedGood/Finished_Good_List') }}" class="btn btn-info mr-1 btn-sm"> <i
                                        class="fa fa-arrow-left"></i></a>
                                <a href="{{ url('FinishedGood/Finished_Good_List') }}" class="btn btn-info btn-sm"> <i
                                        class="fa fa-home"></i></a>
                            </div>
                        </div>
                    </div>
                    <div id="row">
                        <!-- <h6>Production</h6> -->
                        <form action="{{ url('FinishedGood/updateFinishedGoodGatepass/' . $edit->id) }}" method="POST">
                            @csrf
                            <div class="my-2 row" id="adaaishhhh">
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>
                                        Unit Name
                                    </label>
                                    <select disabled name="Unit_Name" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach ($Manufacturing_unit as $val)
                                            <option value="{{ $val->id }}"
                                                {{ isset($edit->Unit_Name) && $edit->Unit_Name == $val->id ? 'selected' : '' }}>
                                                {{ $val->pname }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" value="{{ $edit->Unit_Name }}" name="Unit_Name">
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>
                                        Plant Name
                                    </label>
                                    <select disabled name="Plant_Name" id="Plant_Name" class="form-select form-select-sm"
                                        required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach ($plant_name as $val)
                                            <option value="{{ $val->id }}"
                                                {{ isset($edit->Plant_Name) && $edit->Plant_Name == $val->id ? 'selected' : '' }}>
                                                {{ $val->spname }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" value="{{ $edit->Plant_Name }}" name="Plant_Name">
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 form-group">
                                    <label>
                                        Organization Name
                                    </label>
                                    <select disabled name="Organization" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach ($Organization as $val)
                                            <option value="{{ $val->id }}"
                                                {{ isset($edit->Organization_Name) && $edit->Organization_Name == $val->id ? 'selected' : '' }}>
                                                {{ isset($val->organisation) && $val->organisation != '' ? $val->organisation : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" value="{{ $edit->Organization_Name }}" name="Organization">
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 form-group">
                                    <label>
                                        Transaction Date
                                    </label>
                                    <input type="date" value="{{ $edit->Transaction_Date ?? '' }}"
                                        placeholder="Production Date" name="Transaction_Date"
                                        class="form-control form-control-sm" required>
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 form-group">
                                    <label>
                                        From Godown
                                    </label>
                                    <select name="Godown_Name" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach ($Godown_Name as $val)
                                            <option value="{{ $val->id }}"
                                                {{ isset($edit->Godown_Name) && $edit->Godown_Name == $val->id ? 'selected' : '' }}>
                                                {{ $val->inventory_name }}</option>
                                        @endforeach
                                    </select>
                            </div>

                            <div class="border p-2 d-flex flex-wrap align-items-center">
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>Finished Good(FG)</lable>
                                        <select name="Raw_Material"
                                            class="form-select form-select-sm js-example-matcher-start js-example-matcher-start"
                                            id="RawMaterial" required>
                                            @foreach ($Raw_Material as $val)
                                                <option value="{{ $val->RawMaterial->id }}"
                                                    {{ isset($edit->Material_id) && $edit->Material_id == $val->RawMaterial->id ? 'selected' : '' }}>
                                                    {{ $val->RawMaterial->matname }}</option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="col-xl-1 col-lg-2 col-md-4 col-sm-12 form-group">
                                    <label>HSN Code </label>
                                    <div class="field-wrap">
                                        <input type="text" name="hsn" id="hsn"
                                            value="{{ $edit->HSN_Code ?? '' }}" placeholder="Rate"
                                            class="form-control form-control-sm" required>
                                    </div>
                                </div>
                                <div class="col-xl-1 col-lg-2 col-md-4 col-sm-12 form-group">
                                    <label>UOM </label>
                                    <div class="field-wrap">
                                        <input type="text" name="UOM" id="uom" value="{{ $edit->UOM ?? '' }}"
                                            placeholder="Rate" class="form-control form-control-sm" required>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12 form-group">
                                    <label>Rate</label>
                                    <div class="field-wrap">
                                        <input type="text" inputmode="decimal" pattern="^\d*\.?\d*$" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                            name="Rate" id="Rate" value="{{ $edit->Rate ?? '' }}"
                                            placeholder="Rate" class="form-control form-control-sm" required inputmode="decimal" pattern="^\d*\.?\d*$" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1')">
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12 form-group">
                                    <label>Quantity</label>
                                    <div class="field-wrap">
                                        <input type="text" readonly inputmode="decimal" pattern="-?\d+(\.\d+)?"
                                            oninput="this.value = this.value
																		.replace(/[^0-9.-]/g, '')              // Remove anything not 0-9, dot, or dash
																		.replace(/(?!^)-/g, '')                // Remove any dashes not at start
																		.replace(/(\..*)\./g, '$1');           // Allow only one decimal point
																"
                                            name="Quantity" onchange="materialdata()"
                                            value="{{ $edit->Quantity ?? '' }}" placeholder="Quantity" id="Quantity"
                                            class="form-control form-control-sm" required>
                                    </div>
                                </div>

                                <div class="col-xl-1 col-lg-1 col-md-3 col-sm-12 form-group">
                                    <label>GST*</label>
                                    <div class="field-wrap">
                                        <select name="gst" id="GST" class="form-select form-select-sm"
                                            required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach ($GST as $val)
                                                <option value="{{ $val->GST_Percentage }}"
                                                    {{ isset($edit->GST) && $edit->GST == $val->GST_Percentage ? 'selected' : '' }}>
                                                    {{ $val->GST_Percentage }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12 form-group">
                                    <label>Total Amount </label>
                                    <div class="field-wrap">
                                        <input type="text"
                                            onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                                            name="Total_amount" value="{{ $edit->Total_amount ?? '' }}"
                                            id="Total_amount" placeholder="Rate*Quantity"
                                            class="form-control form-control-sm" required>
                                    </div>
                                </div>
                            </div>

                            <br>
                    </div>
                    <br>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered dataTable no-footer" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="th-sm">SL No.</th>
                                    <th class="th-sm">Serial No.</th>
                                    <th class="th-sm">Supplier</th>
                                    <th class="th-sm">DOP</th>
                                    <th class="th-sm">Make</th>
                                    <th class="th-sm">Brand</th>
                                </tr>
                            </thead>
                            
                                <tbody>
                                  
                                   @foreach($finishedgooddetails as $key => $val)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        
                                        {{-- Serial No --}}
                                        <td>
                                            <input type="text" required name="serial_no[]" class="form-control form-control-sm" placeholder="Enter Serial No." value="{{ $val->serial_no }}">
                                        </td>
                                        
                                        {{-- Supplier --}}
                                        <td class="w-25">
                                            
                                            <select name="supplier_id[]" class="form-select form-select-sm js-example-matcher-start">
                                            <option value="">Select</option>
                                            @if(isset($SUPPLIER))
                                                @foreach($SUPPLIER as $supplier)
                                                <option value="{{ $supplier->id }}" {{ (isset($val->supplier_id) && $supplier->id == $val->supplier_id) ? 'selected' : '' }}>{{ $supplier->supplier_name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </td>
                                        
                                        {{-- DOP --}}
                                        <td>
                                            <input type="date" name="dop[]" class="form-control form-control-sm dop-dt" max="{{ date('Y-m-d') }}"
                                                value="{{ $val->dop ? date('Y-m-d', strtotime($val->dop)) : '' }}">
                                        </td>
                                        
                                        {{-- Make --}}
                                        <td>
                                            <input type="text" name="make[]" class="form-control form-control-sm" value="{{ $val->make }}">
                                        </td>
                                        
                                        {{-- Brand --}}
                                        <td>
                                            <input type="text" name="brand[]" class="form-control form-control-sm" value="{{ $val->brand }}">
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            
                            
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 form-group">
                            <label for="State">Remarks:</label>
							<textarea name="Remarks" id="Remarks" rows="2" class="form-control form-control-sm"
								placeholder="Remarks" required>{{ isset($edit->remarks) && $edit->remarks != '' ? $edit->remarks : '' }}</textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="text-end">
                                <button type="submit" class="btn btn1" style="">UPDATE</button>
                            </div>
                        </div>
                    </div>

                </div>
                </form>

            </section>
        </div>
    </div>

@endsection
@push('custom-scripts')
    <script>
        $(document).ready(function() {
            activeclass(28, 1);
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
        $('#RawMaterial').on('change', function() {
            materialdata()
        });

        function materialdata() {

            var MaterialId = $('#RawMaterial').val();
            var PlantID = $('#Plant_Name').val();
            var Quantity = parseInt($("#Quantity").val());
            if (PlantID == '' || PlantID == 0 || PlantID == 'null' || PlantID == null) {
                alert('Please Select Plant First')
                return false;
            }
            if (MaterialId == '' || MaterialId == 0 || MaterialId == 'null' || MaterialId == null) {
                return false;
            }
            if (Quantity < 1) {
                return false;
            }

            $.ajax({
                url: "{{ url('RawMaterial/MaterialData') }}" + '/' + MaterialId,
                type: 'GET',
                data: {
                    MaterialId: MaterialId,
                },
                success: function(data) {
                    $('#uom').val(data.data.UOM).change();
                    $('#hsn').val(data.data.HSN_Code).change();
                }
            });

        }

        $("#Quantity").blur(function() {
            ratecal()
        });
        $("#Rate").blur(function() {
            ratecal()
        });
        $("#GST").change(function() {
            ratecal()
        });

        function ratecal() {
            Quantity = parseInt($("#Quantity").val());
            Rate = (parseInt($("#Rate").val()));
            GST = (parseInt($("#GST").val()));
            var total = Quantity * Rate;
            var gstAmount = (total * GST) / 100;
            var amount = total + gstAmount;
            $("#Total_amount").val(amount);
        }
    </script>
    <script>
        $(document).ready(function() {
            // Set data-original for all serial_no inputs on page load
            $('input[name="serial_no[]"]').each(function() {
                $(this).attr('data-original', $(this).val().trim());
            });
        });

        $(document).on('blur', 'input[name="serial_no[]"]', function() {
            var input = $(this);
            var serialValue = input.val().trim();
            var originalValue = input.attr('data-original') || '';
            var row = input.closest('tr');
            var sideLabel = row.find('td:eq(0)').text().trim(); // SL No. column value
            var allInputs = $('input[name="serial_no[]"]');
            var count = 0;
            allInputs.each(function() {
                if ($(this).val().trim() === serialValue) {
                    count++;
                }
            });

            // If value is unchanged, do nothing
            if (serialValue === originalValue) {
                input.css('border-color', '#28a745');
                return;
            }

            // Local duplicate check (other row)
            if (serialValue !== "" && count > 1) {
                input.css('border-color', '#dc3545');
                var msg = $('<div></div>')
                    .text('Serial number [' + serialValue + '] for SL No. ' + sideLabel + ': Already used in another row.')
                    .css({
                        position: 'fixed',
                        top: '20px',
                        left: '50%',
                        transform: 'translateX(-50%)',
                        background: '#ffc107',
                        color: '#222',
                        padding: '10px 24px',
                        borderRadius: '6px',
                        zIndex: 9999,
                        fontWeight: 'bold',
                        fontSize: '18px',
                        boxShadow: '0 2px 8px rgba(0,0,0,0.15)'
                    });
                $('body').append(msg);
                setTimeout(function() { msg.fadeOut(400, function() { $(this).remove(); }); }, 3000);
                input.val(originalValue);
                input.css('border-color', '#28a745');
                return;
            }

            // AJAX check for global duplicate
            if (serialValue.length > 0) {
                $.ajax({
                    url: '/StockTransfer/CheckSerialNumber',
                    method: 'POST',
                    data: {
                        serial_no: serialValue,
                        current_id: '{{ $edit->id }}'
                    },
                    success: function(response) {
                        if (!response.valid) {
                            // Only show error if value is different from original
                            if (serialValue !== originalValue) {
                                var msg = $('<div></div>')
                                    .text('Serial number [' + serialValue + '] for SL No. ' + sideLabel + ': ' + (response.message || 'Already exists in another record.'))
                                    .css({
                                        position: 'fixed',
                                        top: '20px',
                                        left: '50%',
                                        transform: 'translateX(-50%)',
                                        background: '#ffc107',
                                        color: '#222',
                                        padding: '10px 24px',
                                        borderRadius: '6px',
                                        zIndex: 9999,
                                        fontWeight: 'bold',
                                        fontSize: '18px',
                                        boxShadow: '0 2px 8px rgba(0,0,0,0.15)'
                                    });
                                $('body').append(msg);
                                setTimeout(function() { msg.fadeOut(400, function() { $(this).remove(); }); }, 3000);
                                input.val(originalValue);
                                input.css('border-color', '#28a745');
                            } else {
                                input.css('border-color', '#28a745');
                            }
                        } else {
                            input.css('border-color', '#28a745');
                            input.attr('data-original', serialValue); // update original to new fresh value
                        }
                    },
                    error: function() {
                        var msg = $('<div></div>')
                            .text('Error checking serial number!')
                            .css({
                                position: 'fixed',
                                top: '20px',
                                left: '50%',
                                transform: 'translateX(-50%)',
                                background: '#dc3545',
                                color: '#fff',
                                padding: '10px 24px',
                                borderRadius: '6px',
                                zIndex: 9999,
                                fontWeight: 'bold',
                                fontSize: '18px',
                                boxShadow: '0 2px 8px rgba(0,0,0,0.15)'
                            });
                        $('body').append(msg);
                        setTimeout(function() { msg.fadeOut(400, function() { $(this).remove(); }); }, 3000);
                    }
                });
            } else {
                input.css('border-color', '');
            }
        });
    </script>
    <script>
        document.addEventListener('input', function (e) {
            if (e.target.classList.contains('dop-dt')) {
                const today = new Date().toISOString().split('T')[0];
                if (e.target.value > today) {
                    e.target.value = today;
                }
            }
        });
        
    </script>
@endpush
