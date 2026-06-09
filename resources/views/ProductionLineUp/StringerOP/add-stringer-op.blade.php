@extends('includes.layout')

@section('pageHeading')
    Add Request page (Stringer OP)
@endsection

@section('content')

    <div class="container-fluid flex-grow-1 container-p-y">
        @if ($errors->any())
            <div class="alert alert-danger" id="errorMessage">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
    
            <script>
                setTimeout(function() {
                    const errorBox = document.getElementById('errorMessage');
                    if (errorBox) {
                        errorBox.style.transition = "opacity 0.5s ease";
                        errorBox.style.opacity = 0;
    
                        setTimeout(() => {
                            errorBox.remove();
                        }, 500);
                    }
                }, 5000);
            </script>
        @endif
    
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center bg-label-primary py-1">
                <h5 class="mb-0">Add Request page (Stringer OP) :</h5>
                <div class="text-end">
                    <a href="javascript: history.go(-1)" class="ms-2 btn btn-primary btn-sm waves-effect waves-light"
                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Back to list">
                        <span class="mdi mdi-keyboard-backspace"></span>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('stringer-op-store') }}" method="POST">
                    @csrf
                    <div class="col-lg-12 mx-auto">
                        <div class="row g-2">
                            <div class="col-md-3 col-sm-6 col-12">
                                <label for="batch_no" class="form-label">Batch No.</label>
                                <div class="form-floating-outline">
                                    <select id="batch_no" name="batch_no" class="form-select select2"
                                        onchange="showHint(this.value)" required>
                                        <option value="" selected>Select Batch No</option>
                                        @foreach ($batchList as $batch)
                                            <option value="{{ $batch->batchNo }}">{{ $batch->batchNo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-12">
                                <label for="date" class="form-label">Date</label>
                                <input type="text" id="date" name="date" placeholder="YYYY-MM-DD" class="form-control dob-picker" required/>
                            </div>
                            <div class="col-md-3 col-sm-6 col-12">
                                <label for="shift" class="form-label">Select Shift</label>
                                <div class="form-floating-outline">
                                    <select id="shift" name="shift" class="select2 form-select" required>
                                        <option value="" selected>Select Shift</option>
                                        @foreach ($ShiftMaster as $shift)
                                            <option value="{{ $shift->id }}">{{ $shift->shift }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-12">
                                <label for="plant_no" class="form-label">Plant No.</label>
                                <input class="form-control" type="text" name="plant_no" id="plant_no"
                                    placeholder="Plant-No" readonly>
                            </div>
                            <div class="col-md-3 col-sm-6 col-12">
                                <label for="defaultInput" class="form-label">Finished good</label>
                                <input class="form-control" type="text" id="finished_good" name="finished_good"
                                    placeholder="Finished good" disabled>
                            </div>
                            <div class="col-md-3 col-sm-6 col-12">
                                <label for="wattage" class="form-label">Wattage</label>
                                <input class="form-control" type="text" id="wattage" name="wattage" placeholder="Wattage"
                                    disabled>
                            </div>
                            <div class="col-md-3 col-sm-6 col-12">
                                <label for="brand" class="form-label">Brand</label>
                                <input class="form-control" type="text" id="brand" name="brand" placeholder="Brand"
                                    disabled>
                            </div>
                            <div class="col-md-3 col-sm-6 col-12">
                                <label for="efficiency" class="form-label">Cell Efficiency</label>
                                <input class="form-control" type="text" name="efficiency" id="efficiency"
                                    placeholder="Efficiency" disabled>
                            </div>
                            <div class="col-md-2 col-sm-6 col-12">
                                <label for="cell_company_name" class="form-label">Cell company Name</label>
                                <input class="form-control" type="text" name="cell_company_name" id="cell_company"
                                    placeholder="Cell company Name" disabled>
                            </div>
                            <div class="col-md-2 col-sm-6 col-12">
                                <label for="stringer_size" class="form-label">Stringer Size</label>
                                <input class="form-control" type="text" name="stringer_size" id="stringer_size"
                                    placeholder="Stringer Size" disabled>
                            </div>
                            <div class="col-md-2 col-sm-6 col-12">
                                <label for="strNo" class="form-label">Stringer No</label>
                                <div class="form-floating-outline">
                                    <select id="strNo" name="strNo" class="select2 form-select" required>
                                        <option value="">Select Stringer No</option>
                                        @foreach($StringerNo as $strno)
                                        <option value="{{ $strno->mstr_type_name }}">{{ $strno->mstr_type_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-12">
                                <label for="operator" class="form-label">Select Operator</label>
                                <div class="form-floating-outline">
                                    <select id="operator" name="operator" class="select2 form-select" required>
                                        <option value="" selected>Select Operator</option>
                                        @foreach ($employees as $admin)
                                            <option value="{{ $admin->id }}">{{ $admin->fullname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-12">
                                <label for="checker" class="form-label">Select Checker</label>
                                <div class="form-floating-outline">
                                    <select id="checker" name="checker" class="select2 form-select" required>
                                        <option value="" selected>Select Checker</option>
                                        @foreach ($employees as $admin)
                                            <option value="{{ $admin->id }}">{{ $admin->fullname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>SL No</th>
                                        <th>Material</th>
                                        <th>Stringer No</th>
                                        <th>Time</th>
                                        <th>Production Qty</th>
                                        <th>Rejection Qty</th>
                                        <th>Stage/Reason</th>
                                        <th>Defect Category</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0" id="productionTable">
                                    <tr>
                                        <td colspan="9" class="text-center text-danger"> -- No Batch No. is selected --</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary ms-auto waves-effect waves-light mt-3">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('pageScript')
    
    <script>
        let materialIndex = 0;
        let selectedStringerNo = '';
        window.materialList = [];

        // Event listener for the main Stringer No dropdown
        $(document).ready(function() {
            $('#productionTable').html(`
                <tr>
                    <td colspan="9" class="text-center text-danger"> -- No Batch No. is Selected --</td>
                </tr>
            `);
            
            // Add event listener for the main Stringer No dropdown
            $('#strNo').on('change', function() {
                selectedStringerNo = $(this).val();
                updateMaterialStringerSelections();
            });
        });

        // Function to update all material row stringer selections
        function updateMaterialStringerSelections() {
            $('#productionTable input[name*="[stringer_no]"]').each(function() {
                $(this).val(selectedStringerNo);
            });
        }

        function materialOptions(selectedId = '') {
            let options = '<option value="" selected>Select Material</option>';
            window.materialList.forEach(mat => {
                const selected = mat.id == selectedId ? 'selected' : '';
                options += `<option value="${mat.id}" ${selected}>${mat.name}</option>`;
            });
            return options;
        }

        function addMaterialRow(material = {}) {
            const isFirstRow = materialIndex === 0;
            const actionButton = isFirstRow ?
                `<button type="button" class="btn btn-sm btn-primary" onclick="addMaterialRow()"><i class="mdi mdi-plus me-1"></i> Add</button>` :
                `<a class="btn border-start-0 removeRow" onclick="removeRow(this)">
                    <i class="fa-duotone fa-solid fa-trash-can fa-xl" style="--fa-primary-color: #d94a0d; --fa-secondary-color: #e53446;"></i>
                </a>`;

            const row = `
                <tr>
                    <td>${materialIndex + 1}</td>
                    <td><select class="form-select w-px-100" name="materials[${materialIndex}][name]" required>${materialOptions(material.id || '')}</select></td>
                    <td>
                        <input type="text" name="materials[${materialIndex}][stringer_no]" class="form-control invoice-item-price w-px-150" placeholder="Stringer No" required readonly>
                    </td>
                    <td><input type="time" name="materials[${materialIndex}][time]" class="form-control invoice-item-price w-px-12" placeholder="Time" required></td>
                    <td><input type="number" min="0" name="materials[${materialIndex}][production_qty]" class="form-control invoice-item-price w-px-150" placeholder="Production Qty" required></td>
                    <td><input type="number" min="0" name="materials[${materialIndex}][rejection_qty]" class="form-control invoice-item-price w-px-150" placeholder="Rejection Qty" required></td>
                    <td><select class="form-select w-px-200" name="materials[${materialIndex}][stage]" required>
                        <option value="" selected>Select Stage</option>
                        @foreach ($DmgRsn as $dmg)
                            <option value="{{ $dmg->mstr_type_name }}">{{ $dmg->mstr_type_name }}</option>
                        @endforeach
                    </select></td>
                    <td><select class="form-select w-px-200" name="materials[${materialIndex}][defect_category]" required>
                        <option value="" selected>Select Defect Master</option>
                        @foreach ($DefectCat as $cat)
                            <option value="{{ $cat->mstr_type_name }}">{{ $cat->mstr_type_name }}</option>
                        @endforeach
                    </select></td>
                    <td class="text-center">${actionButton}</td>
                </tr>`;

            $('#productionTable').append(row);
            
            // If we have a selected stringer number, set it for this new row
            if (selectedStringerNo) {
                $(`input[name="materials[${materialIndex}][stringer_no]"]`).val(selectedStringerNo);
            }
            
            materialIndex++;
            updateSerialNumbers();

            // Ensure the first row always has Add button
            const firstRowActionCell = $('#productionTable tr:first td:last');
            if (firstRowActionCell.find('button.btn-primary').length === 0) {
                firstRowActionCell.html(
                    `<button type="button" class="btn btn-sm btn-primary" onclick="addMaterialRow()"><i class="mdi mdi-plus me-1"></i> Add</button>`
                );
            }
        }

        function removeRow(button) {
            $(button).closest('tr').remove();
            materialIndex--;
            updateSerialNumbers();

            // Ensure the first row always has Add button
            const firstRowActionCell = $('#productionTable tr:first td:last');
            if (firstRowActionCell.find('button.btn-primary').length === 0) {
                firstRowActionCell.html(
                    `<button type="button" class="btn btn-sm btn-primary" onclick="addMaterialRow()"><i class="mdi mdi-plus me-1"></i> Add</button>`
                );
            }
        }

        function updateSerialNumbers() {
            $('#productionTable tr').each(function(index, row) {
                $(row).find('td:first').text(index + 1);
            });
        }

        function showHint(batchNo) {
            if (!batchNo) {
                $('#productionTable').html(
                    `<tr><td colspan="9" class="text-center text-danger">-- No Batch No. is Selected --</td></tr>`);
                return;
            }

            $.ajax({
                url: "{{ url('production-lineup/stringer-op/getstringerOPMaterial') }}",
                type: "GET",
                data: {
                    q: batchNo
                },
                success: function(response) {
                    $('#wattage').val(response.wattage);
                    $('#efficiency').val(response.efficiency);
                    $('#cell_company').val(response.cell_company);
                    $('#stringer_size').val(response.StringSize);
                    $('#finished_good').val(response.finishGood);
                    $('#plant_no').val(response.plantNo);
                    $('#brand').val(response.brand);
                    $('#productionTable').empty();
                    materialIndex = 0;
                    window.materialList = response.materials || [];

                    if (window.materialList.length > 0) {
                        window.materialList.forEach(material => addMaterialRow(material));
                    } else {
                        addMaterialRow();
                    }
                },
                error: function(xhr) {
                    console.error("Error fetching batch data:", xhr.responseText);
                    $('#productionTable').html(
                        `<tr><td colspan="9" class="text-center text-danger">No Data Found. Please try again.</td></tr>`
                    );
                }
            });
        }
    </script>
@endsection