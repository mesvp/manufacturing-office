@extends('includes.layout')

@section('pageHeading')
    EL & QC - View Details
@endsection

@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center bg-label-primary py-2">
                <h5 class="mb-0">EL & Quality Control — Details</h5>
                <div class="text-end">
                    <a href="javascript: history.go(-1)" class="ms-2 btn  btn-primary btn-sm waves-effect waves-light"
                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Back to list"><span
                            class="mdi mdi-keyboard-backspace"></span></a>
                </div>
            </div>

            <div class="card-body">
                @php $e = $elqcDetails; @endphp
                <form action="{{ url('production-lineup/el-qc/update/' . $e->elqc_id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="rwrk_pg" value="{{ request()->page }}">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Bushing No</label>
                            <input class="form-control" id="bushing_no" value="{{ $e->elqc_bushingNo ?? '-' }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Batch No</label>
                            <input class="form-control" id="batch_No" value="{{ $e->elqc_batchNo ?? '-' }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date</label>
                            <input class="form-control" id="date" value="{{ $e->elqc_date ?? '-' }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Time</label>
                            <input class="form-control" id="time" value="{{ $e->elqc_time ?? '-' }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Shift</label>
                            <input class="form-control" id="shift" value="{{ $e->shift_name ?? '-' }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Plant</label>
                            <input class="form-control" id="plant" value="{{ $e->elqc_plant ?? '-' }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Operator</label>
                            <input class="form-control" id="operator"
                                value="{{ $e->operator_name ?? ($e->elqc_operator ?? '-') }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Incharge</label>
                            <input class="form-control" id="incharge"
                                value="{{ $e->incharge_name ?? ($e->elqc_incharge ?? '-') }}" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class=" col-lg-6 col-md-6 col-12 mt-4">
                            <table class="d-block d-xxl-table table table-border table-responsive text-nowrap w-100">
                                <thead class="table-light">
                                    <tr>
                                        <th>Material</th>
                                        <th>Size</th>
                                        <th>Brand</th>
                                    </tr>
                                </thead>
                                <!-- First tbody: Barcode validation result -->
                                <tbody class="table-border-bottom-0 bushmaterial-logo"></tbody>
                                <tbody class="table-border-bottom-0 bushmaterial">

                                </tbody>
                            </table>
                        </div>

                        <div class="col-lg-6 col-md-6 col-12 mt-4" id="barcodeteble">
                            <table class="d-block d-xxl-table table table-border table-responsive text-nowrap w-100">
                                <thead class="table-light">
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th>Fetch No.</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    <!--<tr>-->
                                    <!--    <td>RFID</td>-->
                                    <!--    <td class="py-1">-->
                                    <!--    </td>-->
                                    <!--    <td><input type="text" class="form-control" name="rfid"-->
                                    <!--            value="{{ $e->elqc_rfid }}" placeholder="Fetch No." readonly>-->
                                    <!--    </td>-->
                                    <!--</tr>-->
                                    <tr>
                                        <td>Bar Code</td>
                                        <td class="py-1">
                                        </td>
                                        <td><input type="text" id="barcodeInput2" class="form-control" name="barCode"
                                                value="{{ $e->elqc_barcode }}" placeholder="Fetch No." autocomplete="off"
                                                readonly></td>
                                    </tr>
                                    <tr>
                                        <td>Wattage</td>
                                        <td class="py-1"> </td>
                                        <td id="wattage"></td>
                                    </tr>
                                    <tr>
                                        <td>NG</td>
                                        <td class="py-1"></td>
                                        <td>35</td>
                                    </tr>
                                    <tr>
                                        <td>Production</td>
                                        <td class="py-1"></td>
                                        <td>40</td>
                                    </tr>
                                    @if (request()->query('page') !== 'VIEW')
                                        <tr>
                                            <td>
                                                <select class="form-select select2 w-px-250" name="rwrk_status" id="el_type"
                                                    required>
                                                    <option value="1">Passed</option>
                                                    <option value="2">{{ request()->query('page') === 'RWRK' ? 'Damage' : 'Reject' }}</option>
                                                </select>
                                            </td>
                                            <td class="py-1"></td>
                                            <td>
                                                <button type="submit" class="btn btn-outline-primary waves-effect"
                                                    name="upRwrk">Update</button>
                                            </td>
                                        </tr>
                                    @endif

                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if (request()->query('page') !== 'VIEW')
                        <div class="row">
                            <div class="col-12 mt-4">
                                <div class="table-responsive text-nowrap">
                                    <table class="table table-bordered" id="defectTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>SL No</th>
                                                <th>Cell No</th>
                                                <th>Reject Cell Qty</th>
                                                <th>Defect Reason</th>
                                                <th>Defect Category</th>
                                                <th>Responsible Person</th>
                                                <th>Responsible Machine</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-border-bottom-0" id="productionTable">
                                            <tr>
                                                <td>1</td>
                                                <td>
                                                    <select name="cell_position[]" class="form-select w-px-150" required>
                                                        <option value="">Select Cell</option>
                                                        @if (!empty($bushingMaterial))
                                                            @php
                                                                $rows = (int) $bushingMaterial->cellRow;
                                                                $cols = (int) $bushingMaterial->celColumn;
    
                                                                function numToColLetter($num)
                                                                {
                                                                    $letters = '';
                                                                    while ($num > 0) {
                                                                        $remainder = ($num - 1) % 26;
                                                                        $letters = chr(65 + $remainder) . $letters;
                                                                        $num = intdiv($num - 1, 26);
                                                                    }
                                                                    return $letters;
                                                                }
                                                            @endphp
    
                                                            <!--@for ($col = 1; $col <= $cols; $col++)-->
                                                            <!--    @php $colLetter = numToColLetter($col); @endphp-->
                                                            <!--    @for ($row = 1; $row <= $rows; $row++)-->
                                                            <!--        <option value="{{ $row . $colLetter }}">-->
                                                            <!--            {{ $row . $colLetter }}</option>-->
                                                            <!--    @endfor-->
                                                            <!--@endfor-->
                                                            
                                                            @for ($row = 1; $row <= $rows; $row++)
                                                                @php $rowLetter = numToColLetter($row); @endphp
                                                                @for ($col = 1; $col <= $cols; $col++)
                                                                    <option value="{{ $rowLetter . $col }}">
                                                                        {{ $rowLetter . $col }}
                                                                    </option>
                                                                @endfor
                                                            @endfor
                                                        @else
                                                            <option disabled>No cell available</option>
                                                        @endif
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="cell_qty[]"
                                                        class="form-control invoice-item-price w-px-150" placeholder="0.5/1"
                                                        inputmode="decimal" pattern="^\d+(\.\d+)?$"
                                                        title="Enter a valid number (e.g., 1 or 0.5)"
                                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
    
                                                </td>
                                                <td>
                                                    <select name="dmgMat_reason[]" class="form-select" required>
                                                        <option value="" selected>Select Damage</option>
                                                        @foreach ($DmgRsn as $dmg)
                                                            <option value="{{ $dmg->mstr_type_name }}">
                                                                {{ $dmg->mstr_type_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="dmgMat_cat[]" class="form-select" required>
                                                        <option value="" selected>Select Category</option>
                                                        @foreach ($DmgCat as $dmg)
                                                            <option value="{{ $dmg->mstr_type_name }}">
                                                                {{ $dmg->mstr_type_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-select w-px-200" name="res_prsn[]" id="res_prsn"
                                                        required>
                                                        <option value="">Select Employee</option>
                                                        @foreach ($userList as $user)
                                                            <option value="{{ $user->id }}">
                                                                {{ $user->fullname }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select id="" class="form-select w-px-200" name="res_machine[]"
                                                        required>
                                                        <option value="">Select Machine</option>
                                                        @foreach ($DmgMachine as $dmg)
                                                            <option value="{{ $dmg->mstr_type_name }}">
                                                                {{ $dmg->mstr_type_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <button type="button" id="addProduct" class="btn btn-sm btn-primary">
                                                        <i class="mdi mdi-plus me-1"></i> Add
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!--<div class="col-12 text-end">-->
                            <!--    <button type="submit" class="btn btn-outline-primary waves-effect waves-light mt-3" name="upRwrk">Update</button>-->
                            <!--</div>-->
                        </div>
                    @endif
                </form>
                <hr>

                <h5>Defect Details : </h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>SL No</th>
                                <th>Cell No</th>
                                <th>Reject Qty</th>
                                <th>Defect Reason</th>
                                <th>Defect Category</th>
                                <th>Responsible Person</th>
                                <th>Responsible Machine</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($defectDetails) && $defectDetails->count() > 0)
                                @foreach ($defectDetails as $idx => $d)
                                    <tr>
                                        <td>{{ $idx + 1 }}</td>
                                        <td>{{ $d->cell_no ?? '-' }}</td>
                                        <td>{{ $d->cell_qty ?? '-' }}</td>
                                        <td>{{ $d->defectRsn ?? '-' }}</td>
                                        <td>{{ $d->defectCatgry ?? '-' }}</td>
                                        <td>{{ $d->responsible_person ?? '-' }}</td>
                                        <td>{{ $d->res_machine ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="text-center">No Defect Details Available.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <hr>
                <h5>EL QC Action Trail : </h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>SL No</th>
                                <th>Action</th>
                                <th>IP Address</th>
                                <th>Created By</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($elqcHistory) && $elqcHistory->count() > 0)
                                @foreach ($elqcHistory as $idx => $history)
                                    <tr>
                                        <td>{{ $idx + 1 }}</td>
                                        <td>{{ $history->action ?? '-' }}</td>
                                        <td>{{ $history->ip_address ?? '-' }}</td>
                                        <td>{{ $history->created_by ?? '-' }}</td>
                                        <td>{{ date('d-m-Y H:i A', strtotime($history->created_at)) ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center">No Data Available.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pageScript')
    <script>
        function validateBarcode(barCodeValue, bushNo,batchNo) {
                if (!barCodeValue || !bushNo || !batchNo) return;
                $.ajax({
                    url: "{{ url('production-lineup/el-qc/validate-barcode') }}",
                    type: 'GET',
                    data: {
                        barCode: barCodeValue,
                        id: bushNo,
                        batchNo: batchNo,
                        action: 'view'
                    },
                    success: function (response) {
                        if (response.status === 'error') {
                            alert(response.message);
                            $('input[name="barCode"]').val('');
                            return;
                        }
            
                        const logoContainer = $('.bushmaterial-logo');
                        logoContainer.empty();
            
                        if (response.bushing_logo) {
                            logoContainer.append(`
                                <tr>
                                    <td class="text-dark">Logo</td>
                                    <td></td>
                                    <td colspan="4" class="text-center">
                                        <input type="text"
                                               class="form-control w-px-150"
                                               value="${response.bushing_logo || 'N/A'}"
                                               disabled>
                                    </td>
                                </tr>
                            `);
                        }
                    },
                    error: function (xhr) {
                        alert('Error validating Bar Code: ' + xhr.responseText);
                    }
                });
            }
        
        function showHint(bushingNo) {
            if (!bushingNo) {
                $('.bushmaterial').html(
                    `<tr><td colspan="4" class="text-center text-danger">-- No Bushing No. is Selected --</td></tr>`
                );
                return;
            }

            $.ajax({
                url: "{{ url('production-lineup/el-qc/getBushingMaterial') }}",
                type: 'GET',
                data: {
                    q: bushingNo
                },
                success: function(response) {
                    console.log('AJAX Response:', response);
                    $('#wattage').text(response.wattage || 'N/A');
                    $('#batch_No').val(response.batchno || '');

                    const bushMaterialContainer = $('.bushmaterial');
                    bushMaterialContainer.empty();

                    const materials = response.materials || [];

                    if (materials.length > 0) {
                        materials.forEach((material) => {
                            let row = `
                        <tr>
                            <td class="text-dark">
                                ${material.matname || 'N/A'}
                                <input type="hidden" name="mat[]" value="${material.matid || ''}">
                            </td>
                            <td>
                                <input type="text" class="form-control w-px-150" 
                                    value="${material.msize || 'N/A'}" 
                                    placeholder="Size" disabled>
                            </td>
                            <td>
                                <input type="text" class="form-control w-px-150" 
                                    value="${material.mbrand || 'N/A'}" 
                                    placeholder="Brand" disabled>
                            </td>
                        </tr>
                    `;
                            bushMaterialContainer.append(row);
                        });

                        bushMaterialContainer.find('select.select2').select2({
                            width: '100%'
                        });
                    } else {
                        bushMaterialContainer.html(`
                    <tr>
                        <td colspan="4" class="text-center text-danger">
                            No Materials Found for the Selected Batch.
                        </td>
                    </tr>
                `);
                    }
                },
                error: function(xhr) {
                    console.error('Error fetching batch data:', xhr.responseText);
                    $('.bushmaterial').html(`
                <tr>
                    <td colspan="4" class="text-center text-danger">
                        Error Fetching Data. Please Try Again.
                    </td>
                </tr>
            `);
                },
            });
        }
        
        function callValidateIfReady(retry = 0) {
            const barCodeValue = $('input[name="barCode"]').val();
            const batchNo = $('#batch_No').val();
            const bushing_no = $('#bushing_no').val();
        
            console.log('Checking:', barCodeValue, batchNo); // debug
        
            if (barCodeValue && bushing_no) {
                validateBarcode(barCodeValue, bushing_no, batchNo);
                showHint(batchNo);
                getDefBatchId(batchNo);
                return;
            }
        
            if (retry < 10) {
                setTimeout(() => callValidateIfReady(retry + 1), 300);
            }
        }
        
        
        
        $(window).on('load', function () {
            callValidateIfReady();
        });
    </script>
    
    @php
        $cellOptions = '<option value="">Select Cell</option>';

        if (!empty($bushingMaterial)) {
            $rows = (int) $bushingMaterial->cellRow;
            $cols = (int) $bushingMaterial->celColumn;

            if (!function_exists('numToColLetter')) {
                function numToColLetter($num)
                {
                    $letters = '';
                    while ($num > 0) {
                        $remainder = ($num - 1) % 26;
                        $letters = chr(65 + $remainder) . $letters;
                        $num = intdiv($num - 1, 26);
                    }
                    return $letters;
                }
            }

            for ($row = 1; $row <= $rows; $row++) {
                $rowLetter = numToColLetter($row);   // Letter part
            
                for ($col = 1; $col <= $cols; $col++) {
                    $value = "{$rowLetter}{$col}";  // A1, A2, B1...
                    $cellOptions .= "<option value=\"{$value}\">{$value}</option>";
                }
            }
        } else {
            $cellOptions .= '<option disabled>No cell available</option>';
        }

        // Build options strings in PHP - simpler and more efficient
        $dmgReasonOptions = '<option value="" selected>Select Reason</option>';
        foreach ($DmgRsn ?? [] as $dmg) {
            $dmgReasonOptions .= "<option value=\"{$dmg->mstr_type_name}\">{$dmg->mstr_type_name}</option>";
        }

        $dmgCatOptions = '<option value="" selected>Select Category</option>';
        foreach ($DmgCat ?? [] as $dmg) {
            $dmgCatOptions .= "<option value=\"{$dmg->mstr_type_name}\">{$dmg->mstr_type_name}</option>";
        }

        $userOptions = '<option value="">Select Employee</option>';
        foreach ($userList ?? [] as $user) {
            $userOptions .= "<option value=\"{$user->id}\">{$user->fullname}</option>";
        }

        $machineOptions = '<option value="" selected>Select Machine</option>';
        foreach ($DmgMachine ?? [] as $dmg) {
            $machineOptions .= "<option value=\"{$dmg->mstr_type_name}\">{$dmg->mstr_type_name}</option>";
        }
    @endphp

    <script>
        // Simple approach - just use pre-built HTML strings from PHP
        window.cellOptionsHtml = {!! json_encode($cellOptions) !!};
        window.dmgReasonOptions = {!! json_encode($dmgReasonOptions) !!};
        window.dmgCatOptions = {!! json_encode($dmgCatOptions) !!};
        window.userOptions = {!! json_encode($userOptions) !!};
        window.machineOptions = {!! json_encode($machineOptions) !!};

        function numToColLetter(num) {
            let letters = '';
            while (num > 0) {
                const remainder = (num - 1) % 26;
                letters = String.fromCharCode(65 + remainder) + letters;
                num = Math.floor((num - 1) / 26);
            }
            return letters;
        }

        function buildCellOptions(rows, cols) {
            rows = parseInt(rows) || 0;
            cols = parseInt(cols) || 0;
            if (rows <= 0 || cols <= 0) {
                return '<option disabled>No cell available</option>';
            }
            let opts = '<option value="">Select Cell</option>';
            for (let r = 1; r <= rows; r++) {
                const rowLetter = numToColLetter(r); // A, B, C...
        
                for (let c = 1; c <= cols; c++) {
                    const v = `${rowLetter}${c}`; // A1, A2, B1...
                    opts += `<option value="${v}">${v}</option>`;
                }
            }
            return opts;
        }

        function getDefBatchId(batchNo) {
            if (!batchNo) return;
            console.log('getDefBatchId called with', batchNo);
            $.ajax({
                url: "{{ url('production-lineup/el-qc/getDefBatchId') }}",
                type: 'GET',
                data: {
                    q: batchNo
                },
                success: function(response) {
                    console.log('getDefBatchId response:', response);
                    const def = response.defBatchId || null;
                    const rows = def && def.cellRow ? parseInt(def.cellRow, 10) : 0;
                    const cols = def && def.celColumn ? parseInt(def.celColumn, 10) : 0;

                    if (rows > 0 && cols > 0) {
                        window.cellOptionsHtml = buildCellOptions(rows, cols);
                    } else {
                        window.cellOptionsHtml = '<option disabled>No cell available</option>';
                    }
                    $('#productionTable').find('select[name="cell_position[]"]').each(function() {
                        $(this).empty().html(window.cellOptionsHtml);
                    });
                },
                error: function(xhr) {
                    console.error('Error fetching def batch id:', xhr.responseText);
                }
            });
        }
        
        $(document).ready(function() {
            
            $(document).on("click", "#addProduct", function(e) {
                e.preventDefault();
                let rowCount = $("#productionTable tr").length + 1;

                let newRow = `
                <tr>
                    <td>${rowCount}</td>
                    <td>
                        <select name="cell_position[]" class="form-select w-px-150 select2-dynamic" required></select>
                    </td>
                    <td>
                        <input type="text" name="cell_qty[]" class="form-control invoice-item-price w-px-150" placeholder="0.5/1" inputmode="decimal" pattern="^\\d+(\\.\\d+)?$" title="Enter a valid number (e.g., 1 or 0.5)" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\\..*)\\./g, '$1');">
                    </td>
                    <td>
                        <select name="dmgMat_reason[]" class="form-select select2-dynamic" required></select>
                    </td>
                    <td>
                        <select name="dmgMat_cat[]" class="form-select select2-dynamic" required></select>
                    </td>
                    <td>
                        <select class="form-select w-px-200 select2-dynamic" name="res_prsn[]" required></select>
                    </td>
                    <td>
                        <select class="form-select w-px-200 select2-dynamic" name="res_machine[]" required></select>
                    </td>
                    <td>
                        <a class="btn border-start-0 removeRow">
                            <i class="fa-duotone fa-solid fa-trash-can fa-xl" style="--fa-primary-color: #d94a0d; --fa-secondary-color: #e53446;"></i>
                        </a>
                    </td>
                </tr>`;

                let $row = $(newRow);
                
                // Populate ALL dropdowns from pre-built options
                $row.find('select[name="cell_position[]"]').html(window.cellOptionsHtml || '<option value="">No cell available</option>');
                $row.find('select[name="dmgMat_reason[]"]').html(window.dmgReasonOptions || '<option value="">Select Reason</option>');
                $row.find('select[name="dmgMat_cat[]"]').html(window.dmgCatOptions || '<option value="">Select Category</option>');
                $row.find('select[name="res_prsn[]"]').html(window.userOptions || '<option value="">Select Employee</option>');
                $row.find('select[name="res_machine[]"]').html(window.machineOptions || '<option value="">Select Machine</option>');

                // Append to DOM
                $("#productionTable").append($row);
                // Initialize Select2 on newly added row
                $row.find('.select2-dynamic').select2({
                    dropdownParent: $('#productionTable').closest('.card-body'),
                    width: '100%'
                });
                
            });

            $(document).on("click", ".removeRow", function() {
                $(this).closest("tr").remove();

                $("#productionTable tr").each(function(index) {
                    $(this).find("td:first").text(index + 1);
                });
            });
        });

        $(document).ready(function() {
            $('#defectTable').find('select').not('.select2-hidden-accessible').select2({
                dropdownParent: $('#productionTable').closest('.card-body'),
                width: '100%'
            });
        });
    </script>
    
    @if(request()->query('page') === 'RWRK')
        <script>
            $(document).ready(function () {
                const $controls = $('#defectTable').find('select, input, textarea');
                function toggleDefectTable() {
                    let statusVal = $('#el_type').val();
            
                    if (statusVal == '2') {
                        $('#defectTable').show();
                        $controls.prop('disabled', false).prop('required', true);
                    } else {
                        $('#defectTable').hide();
                        $controls.prop('disabled', false).prop('required', false);
                    }
                }
            
                setTimeout(function(){
                    toggleDefectTable();
                }, 200);
            
                // change event
                $('#el_type').on('change', function () {
                    toggleDefectTable();
                });
            
            });
        </script>
    @else
        <script>
            $(document).ready(function () {
                const $controls = $('#defectTable').find('select, input, textarea');
                $controls.prop('disabled', false).prop('required', false);
            });
        </script>
    @endif
    


@endsection
