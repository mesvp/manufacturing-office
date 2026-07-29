@extends('includes.layout')

@section('pageHeading')
    Add Laminator OP
@endsection


<style>
    .switch {
        position: relative;
        display: inline-flex;
        align-items: center;
        cursor: pointer;
        user-select: none;
    }

    .switch-input {
        display: none;
    }

    .switch-slider {
        position: relative;
        width: 70px;
        height: 30px;
        background-color: #ccc;
        border-radius: 15px;
        transition: background-color 0.3s;
    }

    .switch-slider::before {
        content: "";
        position: absolute;
        top: 3px;
        left: 3px;
        width: 24px;
        height: 24px;
        background-color: white;
        border-radius: 50%;
        transition: transform 0.3s;
    }

    /* When checked (ON) */
    .switch-input:checked+.switch-slider {
        background-color: #28a745;
    }

    .switch-input:checked+.switch-slider::before {
        transform: translateX(40px);
    }

    /* Show text only when checked */
    .switch-label-text::before {
        content: " ";
        font-weight: bold;
        color: #000;
        margin-left: 10px;
    }

    .switch-input:checked~.switch-label-text::before {
        content: "Locked";
        color: #28a745;
    }

    .switch-label-text::before {
        content: "Unlocked";
        font-weight: bold;
        color: #000;
        margin-left: 10px;
    }
</style>

@section('content')
    <!-- Content -->
    <div class="container-fluid flex-grow-1 container-p-y">
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" id="error-alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <script>
                $(document).ready(function() {
                    setTimeout(function() {
                        $('#error-alert').fadeOut('slow', function() {
                            $(this).remove();
                        });
                    }, 10000);
                });
            </script>
        @endif
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center bg-label-primary py-2">
                <h5 class="mb-0">Add Laminator OP page : </h5>
                <div class="text-end">
                    <a href="javascript: history.go(-1)" class="ms-2 btn  btn-primary btn-sm waves-effect waves-light"
                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Back to list"><span
                            class="mdi mdi-keyboard-backspace"></span></a>
                </div>
            </div>
            <div class="card-body">
                <form id="laminaform" action="{{ url('production-lineup/laminator-op-store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="err" id="err" value="">
                    <div class="bg-body col-lg-12 mx-auto p-3 rounded-2">
                        <div class="g-2 row">
                            <div class="col-md-2 col-sm-6 col-12">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" id="formatted-date" name="date" placeholder="YYYY-MM-DD"
                                        class="form-control" readonly required />
                                    <label for="formatted-date">Date</label>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-6 col-12">
                                <div class="form-floating form-floating-outline">
                                    <input name="time" class="form-control" type="time" id="auto-time" readonly
                                        required />
                                    <label for="auto-time">Time</label>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-6 col-12">
                                <div class="form-floating form-floating-outline">
                                    @if (request()->get('page'))
                                        <select class="form-select select2" id="batch_No" name="batchNo"
                                            onchange="getBushId(this.value),getDefBatchId(this.value),showHint(this.value)"
                                            required>
                                            <option value="">--- Select Batch No ---</option>
                                            @foreach ($bushingNo as $busno)
                                                <option value="{{ $busno->elqc_batchNo }}"
                                                    {{ request('batchNo') == $busno->elqc_batchNo ? 'selected' : '' }}>
                                                    {{ $busno->elqc_batchNo }}</option>
                                            @endforeach
                                        </select>
                                        <label>Batch No</label>
                                    @else
                                        <input type="text" class="form-control" placeholder="Batch No" name="batchNo"
                                            id="batch_No" value="{{ request()->get('id') }}" readonly required>
                                        <label>Batch No</label>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-6 col-12">
                                <div class="form-floating form-floating-outline">
                                    @if (request()->get('page'))
                                        <select class="form-select select2" id="bushingNo" name="bushingNo" required>
                                            <option value="">--- Select ELQC No ---</option>
                                        </select>
                                        <label>ELQC No</label>
                                    @else
                                        <input type="text" class="form-control" placeholder="ELQC No" name="bushingNo"
                                            id="bushingNo" value="{{ request()->get('bid') }}" readonly
                                            required>
                                        <label>ELQC No</label>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-6 col-12">
                                <div class="form-floating form-floating-outline">
                                    <select class="form-select select2" name="operator" id="opt" required>
                                        <option value="">--- Select Operator ---</option>
                                        @foreach ($userList as $user)
                                            <option value="{{ $user->id }}"
                                                {{ request('operator') == $user->id && request('operator') != '' ? 'selected' : '' }}>
                                                {{ $user->fullname }}</option>
                                        @endforeach
                                    </select>
                                    <label>Operator</label>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-6 col-12">
                                <div class="form-floating form-floating-outline">
                                    <select class="form-select select2" name="incharge" id="Incharge" required>
                                        <option value="">--- Select Incharge ---</option>
                                        @foreach ($userList as $user)
                                            <option value="{{ $user->id }}"
                                                {{ request('incharge') == $user->id && request('incharge') != '' ? 'selected' : '' }}>
                                                {{ $user->fullname }}</option>
                                        @endforeach
                                    </select>
                                    <label>Incharge</label>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-6 col-12">
                                <div class="form-floating form-floating-outline">
                                    <select class="form-select select2" name="shift" id="Shift"
                                        onchange="changeCycleNo()">
                                        <option value="">--- Select Shift ---</option>
                                        @foreach ($ShiftMaster as $shift)
                                            <option value="{{ $shift->id }}"
                                                {{ request('shift') == $shift->id ? 'selected' : '' }}>{{ $shift->shift }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label>Shift</label>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-6 col-12">
                                <div class="form-floating form-floating-outline">
                                    <select class="form-select select2" name="plant" id="Plant" required>
                                        <option value="">--- Select Plant ---</option>
                                        @foreach ($PlantMaster as $plant)
                                            <option value="{{ $plant->mstr_type_name }}"
                                                {{ request('plant') == $plant->mstr_type_name ? 'selected' : '' }}>
                                                {{ $plant->mstr_type_name }}</option>
                                        @endforeach
                                    </select>
                                    <label>Plant</label>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-6 col-12">
                                <label class="switch">
                                    <input type="checkbox" name="lock" value="1" class="switch-input"
                                        @if (isset($_GET['lock'])) checked @endif
                                        onchange="this.nextElementSibling.nextElementSibling.textContent = this.checked ? 'Locked' : 'Lock Setup'">
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on"></span>
                                        <span class="switch-off"></span>
                                    </span>
                                    <span class="fw-bold switch-label text-black">
                                        @if (isset($_GET['lock']))
                                            Locked
                                        @else
                                            Lock Setup
                                        @endif
                                    </span>
                                </label>
                                <input type="hidden" name="page" value="page" readonly>
                            </div>
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
                                    <tr>
                                        <td>Cycle Sl. No.</td>
                                        <td colspan="2"><input type="number" class="form-control" name="cycleNo"
                                                onblur="checkSlno(this.value)" placeholder="Cycle Sl. No." max="200"
                                                required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>RFID</td>
                                        <td class="py-1"><button type="button" id=""
                                                class="btn btn-outline-primary waves-effect scn">Scan</button>
                                        </td>
                                        <td><input type="text" class="form-control" name="rfid" id="rfid2"
                                                placeholder="Fetch No."></td>
                                    </tr>
                                    <tr>
                                        <td>Bar Code</td>
                                        <td class="py-1">
                                            <button type="button" id=""
                                                class="btn btn-outline-primary waves-effect scn">Scan</button>
                                        </td>
                                        <td><input type="text" id="barcodeInput2" class="form-control"
                                                name="barCode" placeholder="Fetch No." autocomplete="off" required></td>
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
                                    <tr>
                                        <td>
                                            <select class="form-select w-px-150" name="el_type" id="el_type" required>
                                                <option value="1">Passed</option>
                                                <option value="0">Defect</option>
                                            </select>
                                        </td>
                                        <td class="py-1"></td>
                                        <td>
                                            {{-- <button class="btn btn-outline-primary waves-effect" data-bs-toggle="modal" data-bs-target="#Defectmodal">Defect</button> --}}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-12 mt-4">
                            <div class="table-responsive text-nowrap">
                                <table class="table table-bordered" id="defectTable" style="display: none;">
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

                                                        @for ($col = 1; $col <= $cols; $col++)
                                                            @php $colLetter = numToColLetter($col); @endphp
                                                            @for ($row = 1; $row <= $rows; $row++)
                                                                <option value="{{ $row . $colLetter }}">
                                                                    {{ $row . $colLetter }}</option>
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
                                                    <option selected>Select</option>
                                                    @foreach ($DmgRsn as $dmg)
                                                        <option value="{{ $dmg->mstr_type_name }}">
                                                            {{ $dmg->mstr_type_name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="dmgMat_cat[]" class="form-select" required>
                                                    <option selected>Select</option>
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
                    </div>

                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-primary ms-auto waves-effect waves-light mt-3"
                            id="opsubmit">Submit</button>
                    </div>

                    <!-- Small Modal -->
                    <!--<div class="modal-onboarding modal fade animate__animated" id="smallModal" tabindex="-1"-->
                    <!--    aria-hidden="true">-->
                    <!--    <div class="modal-dialog" role="document">-->
                    <!--        <div class="modal-content text-center">-->
                    <!--            <div class="modal-header border-0">-->
                    <!--                <a class="text-muted close-label" href="javascript:void(0);"-->
                    <!--                    data-bs-dismiss="modal"></a>-->
                    <!--                <button type="button" class="btn-close" data-bs-dismiss="modal"-->
                    <!--                    aria-label="Close"></button>-->
                    <!--            </div>-->
                    <!--            <div class="modal-body p-0">-->
                    <!--                <div class="onboarding-content mb-0">-->
                    <!--                    <h4 class="onboarding-title text-body">Do You Want to submit</h4>-->
                    <!--                </div>-->
                    <!--            </div>-->
                    <!--            <div class="modal-footer border-0">-->
                    <!--                <button type="submit" class="btn btn-primary">Submit</button>-->
                    <!--                <button type="button" class="btn btn-outline-secondary"-->
                    <!--                    data-bs-dismiss="modal">Cancel</button>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->
                </form>
            </div>
        </div>
    </div>
@endsection

@section('pageScript')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.scn', function() {
                $(this).closest('td').next('td').find('input').focus();
            });

            $(document).on('change', 'input[name="rfid"]', function() {
                const rfidValue = $(this).val();
                const batchNo = $('#batch_No').val();
                if (rfidValue) {
                    $.ajax({
                        url: "{{ url('production-lineup/laminator-op/validate-rfid') }}",
                        type: 'GET',
                        data: {
                            rfid: rfidValue,
                            id: batchNo
                        },
                        success: function(response) {
                            if (response.status === 'error') {
                                alert(response.message);
                                $('input[name="rfid"]').val('');
                            } else {
                                console.log(response.message);
                            }
                        },
                    });
                }
            });

            $(document).on('change', 'input[name="barCode"]', function() {
                const barCodeValue = $(this).val();
                const batchNo = $('#batch_No').val();
                if (barCodeValue) {
                    $.ajax({
                        url: "{{ url('production-lineup/laminator-op/validate-barcode') }}",
                        type: 'GET',
                        data: {
                            barCode: barCodeValue,
                            id: batchNo
                        },
                        success: function(response) {
                            if (response.status === 'error') {
                                alert(response.message);
                                $('input[name="barCode"]').val('');
                            } else {
                                console.log(response.message);
                            }
                        },
                        error: function(xhr) {
                            alert('Error validating Bar Code: ' + xhr.responseText);
                        },
                    });
                }
            });
            
            $('#opsubmit').on('click', function () {
                const form = document.getElementById('laminaform');
                
                // Create a temporary validation check
                let isValid = true;
                
                // Check all required fields except RFID (which isn't required)
                form.querySelectorAll('[required]').forEach(field => {
                    // Skip if field is disabled or hidden
                    if (field.disabled || field.offsetParent === null) {
                        return;
                    }
                    
                    if (!field.value.trim()) {
                        isValid = false;
                        field.reportValidity();
                        if (isValid === false) {
                            field.focus();
                        }
                    }
                });
                
                // Check barcode specifically (it has required attribute)
                const barcodeField = document.getElementById('barcodeInput2');
                if (!barcodeField.value.trim()) {
                    barcodeField.reportValidity();
                    barcodeField.focus();
                    isValid = false;
                }
                
                if (isValid) {
                    $(form).trigger('submit');
                }
            });
        });
    </script>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const formattedDateInput = document.getElementById('formatted-date');
            const timeInput = document.getElementById('auto-time');

            const now = new Date();

            const yyyy = now.getFullYear();
            const mm = String(now.getMonth() + 1).padStart(2, '0');
            const dd = String(now.getDate()).padStart(2, '0');
            const formattedDate = `${yyyy}-${mm}-${dd}`;
            formattedDateInput.value = formattedDate;

            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            timeInput.value = `${hours}:${minutes}`;
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

            for ($col = 1; $col <= $cols; $col++) {
                $colLetter = numToColLetter($col);
                for ($row = 1; $row <= $rows; $row++) {
                    $value = "{$row}{$colLetter}";
                    $cellOptions .= "<option value=\"{$value}\">{$value}</option>";
                }
            }
        } else {
            $cellOptions .= '<option disabled>No cell available</option>';
        }
    @endphp

    <script>
        window.cellOptionsHtml = {!! json_encode($cellOptions) !!};

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
            if (rows <= 0 || cols <= 0) return '<option disabled>No cell available</option>';
            let opts = '<option value="">Select Cell</option>';
            for (let c = 1; c <= cols; c++) {
                const colLetter = numToColLetter(c);
                for (let r = 1; r <= rows; r++) {
                    const v = `${r}${colLetter}`;
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
                        <select name="cell_position[]" class="form-select w-px-150" required></select>
                    </td>
                    <td>
                        <input type="text" name="cell_qty[]" class="form-control invoice-item-price w-px-150" placeholder="0.5/1" inputmode="decimal" pattern="^\\d+(\\.\\d+)?$" title="Enter a valid number (e.g., 1 or 0.5)" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\\..*)\\./g, '$1');">
                    </td>
                    <td>
                        <select name="dmgMat_reason[]" class="form-select" required>
                            <option selected>Select</option>
                            @foreach ($DmgRsn as $dmg)
                                <option value="{{ $dmg->mstr_type_name }}">{{ $dmg->mstr_type_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="dmgMat_cat[]" class="form-select" required>
                            <option selected>Select</option>
                            @foreach ($DmgCat as $dmg)
                                <option value="{{ $dmg->mstr_type_name }}">{{ $dmg->mstr_type_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="form-select w-px-200" name="res_prsn[]" id="res_prsn" required>
                            <option value="">Select Employee</option>
                            @foreach ($userList as $user)
                                <option value="{{ $user->id }}">{{ $user->fullname }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="form-select w-px-200" name="res_machine[]" id="res_machine" required>
                            <option value="" selected>Select Machine</option>
                            @foreach ($DmgMachine as $dmg)
                                <option value="{{ $dmg->mstr_type_name }}">{{ $dmg->mstr_type_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <a class="btn border-start-0 removeRow">
                            <i class="fa-duotone fa-solid fa-trash-can fa-xl" style="--fa-primary-color: #d94a0d; --fa-secondary-color: #e53446;"></i>
                        </a>
                    </td>
                </tr>`;

                let $row = $(newRow);
                $row.find('select[name="cell_position[]"]').html(window.cellOptionsHtml ||
                    '<option value=\"\">No cell available</option>');

                if ($('#defectTable').is(':visible')) {
                    $row.find('select, input, textarea').prop('required', true).prop('disabled', false);
                } else {
                    $row.find('select, input, textarea').prop('required', false).prop('disabled', true);
                }

                $("#productionTable").append($row);
            });

            $(document).on("click", ".removeRow", function() {
                $(this).closest("tr").remove();

                $("#productionTable tr").each(function(index) {
                    $(this).find("td:first").text(index + 1);
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            const batch_No = $('#batch_No').val();

            $.ajax({
                url: "{{ url('production-lineup/laminator-op/getBushingMaterial') }}",
                type: 'GET',
                data: {
                    q: batch_No                 
                },
                success: function(response) {
                    console.log('AJAX Response:', response);
                    $('#wattage').text(response.wattage || 'N/A');

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
        });

        function getBushId(batchNo) {
            $('#rfid2').val('');
            $('#barcodeInput2').val('');
            if (!batchNo) {
                $('#bushingNo').html(
                    `<option value="" class="text-center text-danger">-- No Batch No. is Selected --</option>`
                );
                return;
            }

            $.ajax({
                url: "{{ url('production-lineup/laminator-op/getelqcId') }}",
                type: 'GET',
                data: {
                    q: batchNo
                },
                success: function(response) {
                    console.log('AJAX Response for Bushing IDs:', response);
                    const bushingSelect = $('#bushingNo');
                    bushingSelect.empty();

                    if (response.elqc_ids && response.elqc_ids.length > 0) {
                        bushingSelect.append(
                            `<option value="">--- Select ELQC No ---</option>`
                        );
                        response.elqc_ids.forEach((elqc) => {
                            bushingSelect.append(
                                `<option value="${elqc.elqc_id}">${elqc.elqc_id}</option>`
                            );
                        });
                    } else {
                        bushingSelect.html(
                            `<option value="" class="text-center text-danger">-- No ELQC IDs Found --</option>`
                        );
                    }
                },
                error: function(xhr) {
                    console.error('Error fetching ELQC IDs:', xhr.responseText);
                },
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
                url: "{{ url('production-lineup/laminator-op/getBushingMaterial') }}",
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

        function setDefectControls(enable) {
            const $controls = $('#defectTable').find('select, input, textarea');
            if (enable) {
                $controls.prop('disabled', false).prop('required', true);
            } else {
                $controls.prop('required', false).prop('disabled', true);
            }
        }

        $(document).ready(function() {
            setDefectControls($('#el_type').val() === '0');
        });

        $(document).on('change', '#el_type', function() {
            const isDefect = $(this).val() === '0';
            $('#defectTable').toggle(isDefect);
            setDefectControls(isDefect);
        });

        function changeCycleNo() {
            const slno = $('input[name="cycleNo"]').val(1);
        }

        function checkSlno(slno) {

            const date = $('#formatted-date').val();
            const shift = $('#Shift').val();

            if (shift === '' || shift === undefined) {
                alert('Please select a shift.');
                $('input[name="cycleNo"]').val('');
                return;
            }

            if (!date) {
                alert('Please select a date.');
                $('input[name="cycleNo"]').val('');
                return;
            }

            if (slno && date && shift) {

                $.ajax({
                    url: "{{ url('production-lineup/laminator-op/checkCycleSlno') }}",
                    type: 'GET',
                    data: {
                        slno: slno,
                        date: date,
                        shift: shift
                    },

                    success: function(response) {
                        if (response.status === 'error') {
                            alert(response.message);
                            if (response.min_cycle !== undefined) {
                                $('input[name="cycleNo"]').val(response.min_cycle);
                                $('#err').val('');
                            } else {
                                $('input[name="cycleNo"]').val('');
                            }
                            $('#err').val('1');
                        } else {
                            $('#err').val('');
                            console.log(response.message);
                        }
                    }
                });
            }
        }

        $(document).on('click', '#opsubmit', function() {
            const slno = $('input[name="cycleNo"]').val();
            checkSlno(slno);
        });
    </script>
    <script>
        const hasPageParam = "{{ request()->get('page') ? '1' : '0' }}";
        $(document).ready(function () {
            if (hasPageParam === '1') {
                $('#bushingNo').on('change', function () {
                    fetchRFIDBar();
                });
            }

            if (hasPageParam === '0') {
                fetchRFIDBar();
            }
        });

        function fetchRFIDBar() {
            const bushingNo = $('#bushingNo').val();
            const batch_No  = $('#batch_No').val();
    
            if (!bushingNo || !batch_No) {
                console.warn('fetchRFIDBar skipped: empty values');
                return;
            }
    
            $.ajax({
                url: "{{ url('production-lineup/laminator-op/fetchRFIDBar') }}",
                type: 'GET',
                data: {
                    bushingNo: bushingNo,
                    batch_No: batch_No
                },
                success: function (response) {
                    console.log('fetchRFIDBar response:', response);
                    $('#rfid2').val(response.rfid);
                    $('#barcodeInput2').val(response.barcode);
                },
                error: function (xhr) {
                    console.error('fetchRFIDBar error:', xhr.responseText);
                }
            });
        }
    </script>
@endsection
