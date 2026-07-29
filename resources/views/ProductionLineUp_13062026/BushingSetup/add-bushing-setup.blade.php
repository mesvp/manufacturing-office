@extends('includes.layout')

@section('pageHeading')
    Add Layout Setup
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
.switch-input:checked + .switch-slider {
  background-color: #28a745;
}

.switch-input:checked + .switch-slider::before {
  transform: translateX(40px);
}

/* Show text only when checked */
.switch-label-text::before {
  content: " ";
  font-weight: bold;
  color: #000;
  margin-left: 10px;
}

.switch-input:checked ~ .switch-label-text::before {
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
    <div class="container-fluid flex-grow-1 container-p-y">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
    
            <script>
                $(document).ready(function() {
                    setTimeout(function() {
                        $('#success-alert').fadeOut('slow', function() {
                            $(this).remove();
                        });
                    }, 10000);
                });
            </script>
        @endif
        
        @if (session('failed'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" id="danger-alert">
                {{ session('failed') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
    
            <script>
                $(document).ready(function() {
                    setTimeout(function() {
                        $('#danger-alert').fadeOut('slow', function() {
                            $(this).remove();
                        });
                    }, 10000);
                });
            </script>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center bg-label-primary py-2">
                        <h5 class="mb-0">Add Layout Operator</h5>
                        <div class="text-end">
                            <a href="{{url('production-lineup/bushing-setup')}}" class="ms-2 btn  btn-primary btn-sm waves-effect waves-light"
                                data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Back to list"><span
                                    class="mdi mdi-keyboard-backspace"></span></a>
                        </div>
                    </div>
                    <div class="card-body">


                        <form id="bushingForm" action="{{ url('production-lineup/bushing-setup/insert') }}" method="post">
                            @csrf

                            <div class="bg-body col-lg-12 mx-auto p-3 rounded-2">
                                <div class="g-2 row">
                                    <div class="col-md-2 col-sm-6 col-12">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" id="formatted-date" name="date"
                                                placeholder="YYYY-MM-DD" class="form-control" readonly
                                                required />
                                            <label for="formatted-date">Date</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-6 col-12">
                                        <div class="form-floating form-floating-outline">
                                            <input name="time" class="form-control" type="time" step="1" id="auto-time"
                                                readonly required />
                                            <label for="auto-time">Time</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-6 col-12">
                                        <div class="form-floating form-floating-outline">
                                            <select class="form-select select2" name="operator" id="opt" required>
                                                <option value="">--- Select Operator ---</option>
                                                @foreach ($userList as $user)
                                                    <option value="{{ $user->id }}"
                                                        {{ ((request('operator') == $user->id) && (request('operator') != '')) ? 'selected' : '' }}>
                                                        {{ $user->fullname }}</option>
                                                @endforeach
                                            </select>
                                            <label>Operator</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-6 col-12">
                                        <div class="form-floating form-floating-outline">
                                            <select class="form-select select2" id="batchno" name="batchNo" batchNo
                                                onchange="showHint(this.value)" required>
                                                <option value="">--- Select BatchNo ---</option>
                                                @foreach ($batchList as $batch)
                                                    <option value="{{ $batch->batchNo }}"
                                                        {{ request('batchno') == $batch->batchNo ? 'selected' : '' }}>
                                                        {{ $batch->batchNo }}</option>
                                                @endforeach
                                            </select>
                                            <label>Batch No</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-6 col-12">
                                        <div class="form-floating form-floating-outline">
                                            <select class="form-select select2" name="shift" id="Shift" required>
                                                <option value="">--- Select Shift ---</option>
                                                @foreach ($ShiftMaster as $shift)
                                                    <option value="{{ $shift->id }}"
                                                        {{ request('shift') == $shift->id ? 'selected' : '' }}>
                                                        {{ $shift->shift }}</option>
                                                @endforeach
                                            </select>
                                            <label>Shift</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-6 col-12">
                                        <div class="form-floating form-floating-outline">
                                            <select class="form-select select2" name="incherge" id="Incharge" required>
                                                <option value="">--- Select Incharge ---</option>
                                                @foreach ($userList as $user)
                                                    <option value="{{ $user->id }}"
                                                        {{ ((request('incharge') == $user->id) && (request('incharge') != '')) ? 'selected' : '' }}>
                                                        {{ $user->fullname }}</option>
                                                @endforeach
                                            </select>
                                            <label>Incharge</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-6 col-12">
                                        <div class="form-floating form-floating-outline">
                                            <select class="form-select select2" name="plant" id="Plant" required>
                                                <option value="">--- Select Plant ---</option>
                                                {{-- <option value="Yes" {{ request('plant') == 'Yes' ? 'selected' : '' }}>
                                                    Yes</option>
                                                <option value="No" {{ request('plant') == 'No' ? 'selected' : '' }}>No
                                                </option> --}}
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
                                        <div class="form-floating form-floating-outline">
                                            <input class="form-control" type="text" id="wattage" name="wattage"
                                                value="{{ $wattage ?? '' }}" readonly />
                                            <label for="wattage">Wattage</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-6 col-12">
                                        <div class="form-floating form-floating-outline">
                                            <input class="form-control" type="text" id="finished_good"
                                                value="{{ $matname ?? '' }}" name="finished_good" readonly />
                                            <label for="finished_good"> Finished Good </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-12 mt-2 p-2">
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
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class=" col-lg-8 col-md-6 col-12 mt-4">
                                    <table
                                        class="d-block d-xxl-table table table-border table-responsive text-nowrap w-100">
                                        <thead class="table-light">
                                            <tr>
                                                <th></th>
                                                <th>Size</th>
                                                <th>Brand</th>
                                                <th>Usages</th>
                                            </tr>
                                        </thead>
                                        <tbody class="">
                                            <tr>
                                                <td class="text-dark">Logo</td>
                                                <td>
                                                    <!-- <input type="text" class="form-control w-px-150" placeholder="150Px"
                                                            disabled> -->
                                                </td>
                                                <td>
                                                    <!-- <input type="text" class="form-control w-px-150" placeholder="groupsurya"
                                                            disabled> -->
                                                </td>
                                                <td>
                                                    <select class="form-select select2 w-px-150" name="logo" id="logo"
                                                        required>
                                                        <option value="No">No</option>
                                                        <option value="Yes">Yes</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tbody class="bushmaterial">
                                            @if (count($batchno) > 0)
                                                @foreach ($batchno as $material)
                                                    <tr>
                                                        <td class="text-dark">
                                                            {{ $material['mname'] ?? 'N/A' }}
                                                            <input type="hidden" name="mat[]"
                                                                value="{{ $material['mid'] ?? '' }}">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control w-px-150"
                                                                value="{{ $material['msize'] ?? 'N/A' }}"
                                                                placeholder="Size" disabled>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control w-px-150"
                                                                value="{{ $material['mbrand'] ?? 'N/A' }}"
                                                                placeholder="Brand" disabled>
                                                        </td>

                                                        @if ($material['mid'] > 3)
                                                            <td>
                                                                <select name="mat_stat[]" class="form-select">
                                                                    <!--<option value="No">No</option>-->
                                                                    <option value="Yes">Yes</option>
                                                                </select>
                                                            </td>
                                                        @else
                                                            <td>
                                                                <select name="mat_stat[]" class="form-control" readonly>
                                                                    <option value="Yes">Yes</option>
                                                                </select>
                                                            </td>
                                                        @endif
                                                    </tr>

                                                    @if ($material['mid'] == 2)
                                                        <tr>
                                                            <td class="text-dark">
                                                                {{ $material['mname'] ?? 'N/A' }} Quantity
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control w-px-150"
                                                                    value="{{ $material['mqty'] ?? 'N/A' }}"
                                                                    placeholder="Size" disabled>
                                                            </td>
                                                            <td colspan="2"></td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="4" class="text-center text-danger">
                                                        No Materials Found for the Selected Batch.
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>

                                        <tbody class="">
                                            <tr>
                                                <td class="text-dark">Damage Details</td>
                                                <td>
                                                    <select name="hasDamage" class="form-select select2 damage-select">
                                                        <option value="Yes">Yes</option>
                                                        <option value="No" selected>No</option>
                                                    </select>
                                                </td>
                                                <td colspan="2"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-lg-4 col-md-6 col-12 mt-4" id="barcodeteble">
                                    <table
                                        class="d-block d-xxl-table table table-border table-responsive text-nowrap w-100">
                                        <thead class="table-light">
                                            <tr>
                                                <th></th>
                                                <th>Scan</th>
                                                <th>Fetch No.</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-border-bottom-0">
                                            <!--<tr>-->
                                            <!--    <td>RFID</td>-->
                                            <!--    <td class="py-1"><button type="button" id="" class="btn btn-outline-primary waves-effect scn">Scan</button>-->
                                            <!--    </td>-->
                                            <!--    <td><input type="text" class="form-control" name="rfid" placeholder="Fetch No."></td>-->
                                            <!--</tr>-->
                                            <tr>
                                                <td>Bar Code</td>
                                                <td class="py-1">
                                                    <button type="button" id="" class="btn btn-outline-primary waves-effect scn">Scan</button>
                                                </td>
                                                <td><input type="text" id="barcode_Input"  class="form-control" name="barCode"
                                                        placeholder="Fetch No." autocomplete="off" required readonly></td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="clearfix"></div>
                            <div class="row">
                                <div class="col-12 mt-4">
                                    <div class="table-responsive text-nowrap">
                                        <table class="table table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>SL No</th>
                                                    <th>Material</th>
                                                    <th>Qty</th>
                                                    <th>UOM</th>
                                                    <th>Reason</th>
                                                    <th>Category</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-border-bottom-0" id="productionTable">
                                                <tr>
                                                    <td colspan="7" class="text-center text-danger">-- No Damage Data
                                                        Found --</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 text-end">
                                <button type="button" class="btn btn-primary ms-auto waves-effect waves-light mt-3"
                                     id="bushsubmit">Submit</button>
                            </div>

                            <!-- Small Modal -->
                            <!--<div class="modal-onboarding modal fade animate__animated" id="smallModal" tabindex="-1"-->
                            <!--    aria-hidden="true">-->
                            <!--    <div class="modal-dialog modal-dialog-centered" role="document">-->
                            <!--        <div class="modal-content text-center">-->
                            <!--            <div class="modal-header border-0">-->
                            <!--                <a class="text-muted close-label" href="javascript:void(0);"-->
                            <!--                    data-bs-dismiss="modal"></a>-->
                            <!--                <button type="button" class="bg-dark-subtle btn-close"-->
                            <!--                    data-bs-dismiss="modal" aria-label="Close"></button>-->
                            <!--            </div>-->
                            <!--            <div class="modal-body p-0">-->
                            <!--                <div class="onboarding-content mb-0">-->
                            <!--                    <h4 class="fs-5 text-heading">Do You Want to submit</h4>-->
                            <!--                </div>-->
                            <!--            </div>-->
                            <!--            <div class="border-0 justify-content-center modal-footer">-->
                            <!--                <button type="button" class="btn btn-outline-secondary"-->
                            <!--                    data-bs-dismiss="modal">Cancel</button>-->
                            <!--                <button type="submit" class="btn btn-primary">Submit</button>-->
                            <!--            </div>-->
                            <!--        </div>-->
                            <!--    </div>-->
                            <!--</div>-->


                        </form>




                    </div>




                </div>
            </div>
        </div>
    </div>
@endsection

@section('pageScript')
    <script>
        $(document).ready(function() {
            // $('.select2').change(function() {
            //     // Check if ANY select has value "Yes"
            //     const anyYesSelected = $('.select2').toArray().some(select => $(select).val() === 'Yes');

            //     const $buttons = $('#barcodeteble .table tbody button');

            //     if (anyYesSelected) {
            //         // Change all buttons to Scanner
            //         $buttons.each(function() {
            //             $(this)
            //                 .removeClass('btn-outline-primary')
            //                 .addClass('btn-info text-white scn')
            //                 .text('Scanner');
            //         });
            //     } else {
            //         // Reset all buttons to Scan
            //         $buttons.each(function() {
            //             $(this)
            //                 .removeClass('btn-info text-white scn')
            //                 .addClass('btn-outline-primary')
            //                 .text('Scan');
            //         });
            //     }
            // });
            
            // Listen for clicks on the "Scan" buttons
            //$(document).on('click', '.scn', function() {
                // Find the corresponding input field and focus on it
                //$(this).closest('td').next('td').find('input').focus();
            //});
            
            $(document).ready(function() {
                // When Scan button is clicked, temporarily enable input for scanning
                $(document).on('click', '#barcodeteble .scn', function() {
                    const barcodeInput = $('#barcode_Input');
                    
                    // Enable input for 30 seconds to allow scanning
                    barcodeInput.prop('readonly', false)
                               .val('')
                               .focus()
                               .attr('placeholder', 'Scan now...');
                    
                    // Auto-disable after 30 seconds or when value is entered
                    setTimeout(function() {
                        if (barcodeInput.val() === '') {
                            barcodeInput.prop('readonly', true)
                                       .attr('placeholder', 'Click Scan button first');
                        }
                    }, 30000);
                });
                
                // When barcode is scanned, make it readonly again
                $('#barcode_Input').on('change', function() {
                    if ($(this).val().trim()) {
                        $(this).prop('readonly', true);
                    }
                });
                
                // Reset when batch changes
                $('#batchno').on('change', function() {
                    $('#barcode_Input').val('')
                                      .prop('readonly', true)
                                      .attr('placeholder', 'Click Scan button first');
                });
            });

            // Validate RFID input
            $(document).on('change', 'input[name="rfid"]', function() {
                const rfidValue = $(this).val();
                if (rfidValue) {
                    $.ajax({
                        url: "{{ url('production-lineup/bushing-setup/validate-rfid') }}", // Create a new route for this
                        type: 'GET',
                        data: {
                            rfid: rfidValue
                        },
                        success: function(response) {
                            if (response.exists) {
                                alert(
                                    'This RFID is already in use. Please scan a different RFID.');
                                $('input[name="rfid"]').val(''); // Clear the input field
                            }
                        },
                        error: function(xhr) {
                            console.error('Error validating RFID:', xhr.responseText);
                        },
                    });
                }
            });

            // $(document).on('change', 'input[name="barCode"]', function () {
            //     const barCodeValue = $(this).val();
            //     if (barCodeValue) {
            //         $.ajax({
            //             url: "{{ url('production-lineup/bushing-setup/validate-barcode') }}",
            //             type: 'GET',
            //             data: { barCode: barCodeValue },
            //             success: function (response) {
            //                 if (response.status === 'error') {
            //                     alert(response.message);
            //                     $('input[name="barCode"]').val(''); // Clear field
            //                 } else {
            //                     console.log(response.message); // Success message if needed
            //                 }
            //             },
            //             error: function (xhr) {
            //                 console.error('Error validating Bar Code:', xhr.responseText);
            //             },
            //         });
            //     }
            // });
            
            // $(document).on('click', '#bushsubmit', function () {
            //     const barCodeValue = $('#barcode_Input').val();
            //     if (barCodeValue) {
            //         $.ajax({
            //             url: "{{ url('production-lineup/bushing-setup/validate-barcode') }}",
            //             type: 'GET',
            //             data: { barCode: barCodeValue },
            //             success: function (response) {
            //                 if (response.status === 'error') {
            //                     alert(response.message);
            //                     $('input[name="barCode"]').val(''); // Clear field
            //                 } else {
            //                     console.log(response.message); // Success message if needed
            //                 }
            //             },
            //             error: function (xhr) {
            //                 console.error('Error validating Bar Code:', xhr.responseText);
            //             },
            //         });
            //     }
            // });
            
            $(document).ready(function () {

                $('#bushingForm').on('submit', function (e) {
                    e.preventDefault();
            
                    const barCodeValue = $('#barcode_Input').val().trim();
                    const form = this;
            
                    if (!barCodeValue) {
                        alert('Please scan barcode');
                        return;
                    }
            
                    $.ajax({
                        url: "{{ url('production-lineup/bushing-setup/validate-barcode') }}",
                        type: 'GET',
                        data: { barCode: barCodeValue },
                        success: function (response) {
                            if (response.status === 'error') {
                                alert(response.message);
                                $('#barcode_Input').val('').focus();
                            } else {
                                form.submit();
                            }
                        },
                        error: function (xhr) {
                            console.error('Barcode validation error:', xhr.responseText);
                            alert('Barcode validation failed. Try again.');
                        }
                    });
                });
            });

            $('#bushsubmit').on('click', function () {
                const form = document.getElementById('bushingForm');
                
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
                const barcodeField = document.getElementById('barcode_Input');
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
        document.addEventListener('DOMContentLoaded', function() {
            const lockBtn = document.getElementById('lock-setup-btn');
            const inputs = document.querySelectorAll(
                '#formatted-date, #auto-time, #opt, #batchno, #Shift, #Incharge, #Plant');

            const fieldsToClear = document.querySelectorAll('#opt, #batchno, #Shift, #Incharge, #Plant');

            let isLocked = false;

            lockBtn.addEventListener('click', function() {
                if (!isLocked) {
                    // Lock setup
                    inputs.forEach(el => {
                        el.disabled = true;
                    });

                    lockBtn.textContent = 'Unlock Setup';
                    lockBtn.classList.remove('btn-outline-primary');
                    lockBtn.classList.add('btn-danger');

                    // Disable the button itself
                    lockBtn.disabled = true;

                    // Re-enable it after a short delay (for UX, remove if not needed)
                    setTimeout(() => {
                        lockBtn.disabled = false;
                    }, 500); // optional

                    isLocked = true;

                } else {
                    // Unlock setup and clear specific fields
                    inputs.forEach(el => {
                        el.disabled = false;
                    });

                    // Clear only selected fields
                    fieldsToClear.forEach(el => {
                        if (el.tagName === 'INPUT') {
                            el.value = '';
                        } else if (el.tagName === 'SELECT') {
                            el.selectedIndex = 0;
                            if ($(el).hasClass('select2')) {
                                $(el).val(null).trigger('change');
                            }
                        }
                    });

                    lockBtn.textContent = 'Lock Setup';
                    lockBtn.classList.remove('btn-danger');
                    lockBtn.classList.add('btn-outline-primary');

                    isLocked = false;
                }
            });
        });
    </script>

    <script>
        // window.addEventListener('DOMContentLoaded', () => {
        //     const formattedDateInput = document.getElementById('formatted-date');
        //     const timeInput = document.getElementById('auto-time');

        //     const now = new Date();

        //     // Correct format for <input type="date">
        //     const yyyy = now.getFullYear();
        //     const mm = String(now.getMonth() + 1).padStart(2, '0');
        //     const dd = String(now.getDate()).padStart(2, '0');
        //     const formattedDate = `${yyyy}-${mm}-${dd}`;
        //     formattedDateInput.value = formattedDate;

        //     // Format time to HH:MM
        //     const hours = String(now.getHours()).padStart(2, '0');
        //     const minutes = String(now.getMinutes()).padStart(2, '0');
        //     timeInput.value = `${hours}:${minutes}`;
        // });
    </script>
    
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const formattedDateInput = document.getElementById('formatted-date');
            const timeInput = document.getElementById('auto-time');
        
            // Mandatory date (YYYY-MM-DD)
            formattedDateInput.value = new Date().toISOString().split('T')[0];
        
            // Time with seconds
            const updateTime = () => {
                const now = new Date();
                const hh = String(now.getHours()).padStart(2, '0');
                const mm = String(now.getMinutes()).padStart(2, '0');
                const ss = String(now.getSeconds()).padStart(2, '0');
                timeInput.value = `${hh}:${mm}:${ss}`;
            };
        
            updateTime();
            setInterval(updateTime, 100);
        });
    </script>

    <script>
        let finishedGoodsData = []; // <-- Global variable to store the latest data
    
        function updateMaterialDropdowns() {
            const selectedMaterials = [];
            $('select[name="dmgMat[]"]').each(function () {
                const selectedValue = $(this).val();
                if (selectedValue && selectedValue !== 'Select') {
                    selectedMaterials.push(selectedValue);
                }
            });
    
            $('select[name="dmgMat[]"]').each(function () {
                const currentValue = $(this).val();
                $(this).find('option').each(function () {
                    const optionValue = $(this).val();
                    if (optionValue !== 'Select' && selectedMaterials.includes(optionValue) && optionValue !== currentValue) {
                        $(this).prop('disabled', true);
                    } else {
                        $(this).prop('disabled', false);
                    }
                });
            });
        }
    
        $(document).ready(function () {
            $('#productionTable').html(`<tr><td colspan="7" class="text-center text-danger">-- No Damage Data Found --</td></tr>`);
    
            // DAMAGE SELECTION CHANGE
            $(document).on('change', '.damage-select', function () {
                const selectedValue = $(this).val();
    
                if (selectedValue === 'No') {
                    $('#productionTable').html(`<tr><td colspan="7" class="text-center text-danger">-- No Damage Data Found --</td></tr>`);
                    return;
                }
    
                if (selectedValue === 'Yes') {
                    $.ajax({
                        url: "{{ url('production-lineup/bushing-setup/getFinishedGoodData') }}",
                        type: 'GET',
                        dataType: 'json',
                        success: function (response) {
                            console.log('AJAX Response:', response);
    
                            finishedGoodsData = response.FinishedGood || []; // store in global var
                            const productionTable = $('#productionTable');
                            productionTable.empty();
    
                            if (finishedGoodsData.length > 0) {
                                let options = `<option value=''>Select</option>`;
                                finishedGoodsData.forEach((item) => {
                                    options += `<option value="${item.Raw_Material}">${item.Raw_Material_Name}</option>`;
                                });
    
                                const row = `
                                    <tr>
                                        <td>1</td>
                                        <td>
                                            <select name="dmgMat[]" class="form-select w-px-250 material-dropdown" onchange="getUOM(this)" required>
                                                ${options}
                                            </select>
                                        </td>
                                        <td><input type="text" name="dmgMat_qty[]" class="form-control" placeholder="Qty" required></td>
                                        <td><input type="text" name="dmgMat_uom[]" class="form-control" placeholder="UOM" required></td>
                                        <td>
                                            <select name="dmgMat_reason[]" class="form-select w-px-250" required>
                                                <option value="" selected>Select</option>
                                                @foreach ($DmgRsn as $dmg)
                                                    <option value="{{ $dmg->mstr_type_name }}">{{ $dmg->mstr_type_name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="dmgMat_cat[]" class="form-select w-px-250" required>
                                                <option value="" selected>Select</option>
                                                @foreach ($DmgCat as $dmg)
                                                    <option value="{{ $dmg->mstr_type_name }}">{{ $dmg->mstr_type_name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary addProduct">
                                                <i class="mdi mdi-plus me-1"></i> Add
                                            </button>
                                        </td>
                                    </tr>`;
                                productionTable.append(row);
                                updateMaterialDropdowns();
                            } else {
                                productionTable.html(`<tr><td colspan="7" class="text-center text-danger">No Material Found.</td></tr>`);
                            }
                        },
                        error: function (xhr) {
                            console.error('Error fetching material data:', xhr.responseText);
                            $('#productionTable').html(`<tr><td colspan="7" class="text-center text-danger">Error Fetching Data. Please Try Again.</td></tr>`);
                        },
                    });
                }
            });
    
            // ADD NEW ROW (Use latest AJAX data)
            $(document).on('click', '.addProduct', function (e) {
                e.preventDefault();
    
                if (finishedGoodsData.length === 0) {
                    alert("No material data available. Please select Damage = Yes again.");
                    return;
                }
    
                let rowCount = $('#productionTable tr').length + 1;
    
                let options = `<option value=''>Select</option>`;
                finishedGoodsData.forEach((item) => {
                    options += `<option value="${item.Raw_Material}">${item.Raw_Material_Name}</option>`;
                });
    
                let newRow = `
                    <tr>
                        <td>${rowCount}</td>
                        <td>
                            <select name="dmgMat[]" class="form-select w-px-250 material-dropdown" onchange="getUOM(this)" required>
                                ${options}
                            </select>
                        </td>
                        <td><input type="text" name="dmgMat_qty[]" class="form-control" placeholder="Qty" required></td>
                        <td><input type="text" name="dmgMat_uom[]" class="form-control" placeholder="UOM" required></td>
                        <td>
                            <select name="dmgMat_reason[]" class="form-select w-px-250" required>
                                <option value="" selected>Select</option>
                                @foreach ($DmgRsn as $dmg)
                                    <option value="{{ $dmg->mstr_type_name }}">{{ $dmg->mstr_type_name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select name="dmgMat_cat[]" class="form-select w-px-250" required>
                                <option value="" selected>Select</option>
                                @foreach ($DmgCat as $dmg)
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
    
                $('#productionTable').append(newRow);
    
                // Update serial numbers
                $('#productionTable tr').each(function (index) {
                    $(this).find('td:first').text(index + 1);
                });
    
                updateMaterialDropdowns();
            });
    
            // REMOVE ROW
            $(document).on('click', '.removeRow', function () {
                $(this).closest('tr').remove();
    
                $('#productionTable tr').each(function (index) {
                    $(this).find('td:first').text(index + 1);
                });
    
                if ($('#productionTable tr').length === 0) {
                    $('#productionTable').html(`<tr><td colspan="7" class="text-center text-danger">-- No Damage Data Found --</td></tr>`);
                }
    
                updateMaterialDropdowns();
            });
        });
    </script>

    <script>
        function showHint(batchNo) {
            if (!batchNo) {
                $('.bushmaterial').html(
                    `<tr><td colspan="4" class="text-center text-danger">-- No Batch No. is Selected --</td></tr>`
                );
                return;
            }
            $('.damage-select').val('No').trigger('change');
            $.ajax({
                url: "{{ url('production-lineup/bushing-setup/getBushingMaterial') }}",
                type: 'GET',
                data: {
                    q: batchNo
                },
                success: function(response) {
                    console.log('AJAX Response:', response); // Debugging line

                    // Populate wattage and finished_good fields
                    $('#wattage').val(response.wattage || '');
                    $('#finished_good').val(response.matname || '');

                    const bushMaterialContainer = $('.bushmaterial');
                    bushMaterialContainer.empty(); // Clear existing rows

                    const materials = response.materials || [];

                    if (materials.length > 0) {
                        materials.forEach((material) => {
                            let var1 = `
                              <tr>
                                  <td class="text-dark">
                                      ${material.mname || 'N/A'}
                                      <input type="hidden" name="mat[]" value="${material.mid || ''}">
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
                          `;

                            let var2 = "";
                            if (material.mid > 3) {
                                var2 = `
                                  <td>
                                      <select name="mat_stat[]" class="form-select">
                                          <!-- <option value="No">No</option> -->
                                          <option value="Yes">Yes</option>
                                      </select>
                                  </td>
                              `;
                            } else {
                                var2 = `
                                  <td>
                                      <select name="mat_stat[]" class="form-control" readonly>
                                          <option value="Yes">Yes</option>
                                      </select>
                                  </td>
                              `;
                            }


                            let var3 = `</tr>`;

                            let var4 = '';
                            if (material.mid == 2) {
                                var4 = `
                                  <tr>
                                    <td class="text-dark">
                                      ${material.mname || 'N/A'} Quantity
                                      
                                    </td>
                                    <td>
                                      <input type="text" class="form-control w-px-150" 
                                          value="${material.mqty || 'N/A'}" 
                                          placeholder="Size" disabled>
                                    </td>
                                    <td colspan=2></td>
                                  </tr>
                              `;
                            }

                            const row = var1 + var2 + var3 + var4;
                            bushMaterialContainer.append(row);
                        });

                        // Reinitialize select2 for newly added dropdowns
                        bushMaterialContainer.find('select.select2').select2({
                            width: '100%',
                        });
                    } else {
                        bushMaterialContainer.html(
                            `<tr><td colspan="4" class="text-center text-danger">No Materials Found for the Selected Batch.</td></tr>`
                        );
                    }
                },
                error: function(xhr) {
                    console.error('Error fetching batch data:', xhr.responseText);
                    $('.bushmaterial').html(
                        `<tr><td colspan="4" class="text-center text-danger">Error Fetching Data. Please Try Again.</td></tr>`
                    );
                },
            });
        }
        
        
    </script>
    
    //off typing barcode
    <script>
        // const barcodeInput = document.getElementById('barcode_Input');
        
        // // Settings
        // const minBarcodeLength = 3;
        // const maxInterval = 50;
        // const suffixKeys = ['Enter', 'Tab', 'Return'];
        // const allowedCharacters = /^[a-zA-Z0-9\-_\.@]+$/; // Only allow these chars
        
        // let buffer = '';
        // let lastTime = 0;
        // let typingTimeout;
        // let isScanning = false;
        
        // barcodeInput.addEventListener('keydown', (e) => {
        //     const now = Date.now();
        //     const timeSinceLastKey = now - lastTime;
            
        //     // Completely ignore modifier keys
        //     if (['Shift', 'Control', 'Alt', 'Meta', 'CapsLock'].includes(e.key)) {
        //         e.preventDefault();
        //         return;
        //     }
            
        //     // Reset if too much time between keystrokes
        //     if (timeSinceLastKey > maxInterval) {
        //         if (buffer.length > 0) {
        //             console.log('🔄 Reset buffer - slow typing:', buffer);
        //         }
        //         buffer = '';
        //         isScanning = false;
        //     }
            
        //     // Handle suffix keys (barcode end)
        //     if (suffixKeys.includes(e.key)) {
        //         e.preventDefault();
        //         processBarcode();
        //         return;
        //     }
            
        //     // Only process single character keys that match our pattern
        //     if (e.key.length === 1 && allowedCharacters.test(e.key)) {
        //         buffer += e.key;
        //         lastTime = now;
        //         isScanning = true;
                
        //         clearTimeout(typingTimeout);
        //         typingTimeout = setTimeout(processBarcode, maxInterval + 10);
        //     } else if (e.key.length === 1) {
        //         console.log('⏩ Skipped invalid character:', e.key);
        //     }
            
        //     e.preventDefault();
        // });
        
        // function processBarcode() {
        //     clearTimeout(typingTimeout);
            
        //     // Clean the buffer - remove any unexpected characters
        //     const cleanBuffer = buffer.replace(/[^a-zA-Z0-9\-_\.@]/g, '');
            
        //     if (cleanBuffer.length >= minBarcodeLength && isScanning) {
        //         console.log('✅ Barcode detected:', cleanBuffer);
        //         barcodeInput.value = cleanBuffer;
                
        //         // Optional: Trigger events for other parts of your application
        //         const event = new CustomEvent('barcodeScanned', { 
        //             detail: { barcode: cleanBuffer } 
        //         });
        //         barcodeInput.dispatchEvent(event);
        //     } else if (isScanning && cleanBuffer.length > 0) {
        //         console.warn('🚫 Barcode too short:', cleanBuffer);
        //         barcodeInput.value = '';
        //     }
            
        //     buffer = '';
        //     isScanning = false;
        // }
        
        // // Event listeners for cleanup
        // barcodeInput.addEventListener('blur', () => {
        //     clearTimeout(typingTimeout);
        //     buffer = '';
        //     isScanning = false;
        // });
        
        // barcodeInput.addEventListener('paste', (e) => {
        //     e.preventDefault();
        //     console.warn('🚫 Pasting disabled');
        // });
    </script>

    
    <script>
        function getUOM(selectElement) {
            const materialId = $(selectElement).val();
            const row = $(selectElement).closest('tr');
            const uomInput = row.find('input[name="dmgMat_uom[]"]');

            if (!materialId) {
                uomInput.val('');
                return;
            }

            $.ajax({
                url: "{{ url('production-lineup/bushing-setup/getUOM') }}",
                type: 'GET',
                data: {
                    matId: materialId
                },
                success: function(response) {
                    // Set UOM value in the corresponding input field of the same row
                    uomInput.val(response.uom || '');
                },
                error: function(xhr) {
                    console.error('Error fetching UOM data:', xhr.responseText);
                    uomInput.val('');
                }
            });
            updateMaterialDropdowns();
        }
    </script>

@endsection
