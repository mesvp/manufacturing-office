 min="0"@extends('includes.layout')

@section('pageHeading')
    Stringer OP Form Details
@endsection

@section('content')
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

    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center bg-label-primary py-2">
                <h5 class="mb-0">Stringer OP Update Request :</h5>
                <div class="text-end">
                    <a href="javascript: history.go(-1)" class="ms-2 btn  btn-primary btn-sm waves-effect waves-light"
                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Back to list"><span
                            class="mdi mdi-keyboard-backspace"></span></a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('stringer-op-update', $stringerOpDetails->id) }}" method="POST">
                    @csrf
                    <div class="col-lg-12 mx-auto">
                        <div class="row g-2">
                            <div class="col-md-3 col-sm-6 col-12">
                                <label for="batch_no" class="form-label">Batch No.</label>
                                <input class="form-control" type="text" id="batchNo" name="batchNo" value="{{ $stringerOpDetails->batchNo ?? '' }}" placeholder="" readonly>
                            </div>
                            <div class="col-md-3 col-sm-6 col-12">
                                <label for="date" class="form-label">Date</label>
                                <input type="text" id="date" name="date" placeholder="YYYY-MM-DD" class="form-control dob-picker"
                                    value="{{ $stringerOpDetails->date }}" required/>
                            </div>
                            <div class="col-md-3 col-sm-6 col-12">
                                <label for="shift" class="form-label">Select Shift</label>
                                <div class="form-floating-outline">
                                    <select id="shift" name="shift" class="select2 form-select" required>
                                        @foreach ($ShiftMaster as $shift)
                                            <option value="{{ $shift->id }}" {{ $stringerOpDetails->shift == $shift->id ? 'selected' : '' }}>
                                                {{ $shift->shift }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-12">
                                <label for="plant_no" class="form-label">Plant No.</label>
                                <input class="form-control" type="text" name="plant_no" id="plant_no"
                                    value="{{ $batchData->plantNo ?? '' }}" readonly>
                            </div>
                            <div class="col-md-3 col-sm-6 col-12">
                                <label for="defaultInput" class="form-label">Finished good</label>
                                <input class="form-control" type="text" id="finished_good" value="{{ $batchData->matname ?? '' }}" name="finished_good"
                                    placeholder="Finished good" disabled>
                            </div>
                            <div class="col-md-3 col-sm-6 col-12">
                                <label for="wattage" class="form-label">Wattage</label>
                                <input class="form-control" type="text" id="wattage" name="wattage" 
                                    value="{{ $batchData->wattage ?? '' }}" disabled>
                            </div>
                            <div class="col-md-3 col-sm-6 col-12">
                                <label for="brand" class="form-label">Brand</label>
                                <input class="form-control" type="text" id="brand" name="brand" 
                                    value="{{ $batchData->brand ?? '' }}" disabled>
                            </div>
                            <div class="col-md-3 col-sm-6 col-12">
                                <label for="efficiency" class="form-label">Cell Efficiency</label>
                                <input class="form-control" type="text" name="efficiency" id="efficiency"
                                    value="{{ $batchData->efficiency ?? '' }}" disabled>
                            </div>
                            <div class="col-md-2 col-sm-6 col-12">
                                <label for="cell_company_name" class="form-label">Cell company Name</label>
                                <input class="form-control" type="text" name="cell_company_name" id="cell_company"
                                    value="{{ $batchData->cellBrand ?? '' }}" disabled>
                            </div>
                            <div class="col-md-2 col-sm-6 col-12">
                                <label for="stringer_size" class="form-label">Stringer Size</label>
                                <input class="form-control" type="text" name="stringer_size" id="stringer_size"
                                    value="{{ $batchData->StringSize ?? '' }}" disabled>
                            </div>
                            <div class="col-md-2 col-sm-6 col-12">
                                <label for="strNo" class="form-label">Stringer No</label>
                                <div class="form-floating-outline">
                                    <select id="strNo" name="strNo" class="select2 form-select" required>
                                        <option value="">Select Stringer No</option>
                                        @foreach($StringerNo as $strno)
                                        <option value="{{ $strno->mstr_type_name }}" {{ $stringerOpDetails->strNo == $strno->mstr_type_name ? 'selected' : '' }}>{{ $strno->mstr_type_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-12">
                                <label for="operator" class="form-label">Select Operator</label>
                                <div class="form-floating-outline">
                                    <select id="operator" name="operator" class="select2 form-select" required>
                                        @foreach ($employees as $admin)
                                            <option value="{{ $admin->id }}" {{ $stringerOpDetails->operator == $admin->id ? 'selected' : '' }}>
                                                {{ $admin->fullname }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-12">
                                <label for="checker" class="form-label">Select Checker</label>
                                <div class="form-floating-outline">
                                    <select id="checker" name="checker" class="select2 form-select" required>
                                        @foreach ($employees as $admin)
                                            <option value="{{ $admin->id }}" {{ $stringerOpDetails->checker == $admin->id ? 'selected' : '' }}>
                                                {{ $admin->fullname }}
                                            </option>
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
                                    <!-- JS will append rows here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary ms-auto waves-effect waves-light mt-3">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('pageScript')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let materialIndex = 0;
        let selectedStringerNo = '{{ $stringerOpDetails->strNo }}';

        // Event listener for the main Stringer No dropdown
        $(document).ready(function() {
            // Initialize with existing materials if any
            $('#productionTable').empty();
            materialIndex = 0;

            @if (!empty($stringerOpMaterials) && count($stringerOpMaterials) > 0)
                @foreach ($stringerOpMaterials as $detail)
                    addMaterialRow({
                        id: '{{ $detail->id }}',
                        mat_id: '{{ $detail->mat_id }}',
                        stringerNo: '{{ $detail->stringerNo }}',
                        time: '{{ $detail->time }}',
                        production_qty: '{{ $detail->productionQty }}',
                        rejection_qty: '{{ $detail->RejectQty }}',
                        stage: '{{ $detail->reason }}',
                        defect_category: '{{ $detail->defectCat }}'
                    });
                @endforeach
            @else
                addMaterialRow();
            @endif
            
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

        function addMaterialRow(material = {}) {
            const actionButton = `<a class="btn border-start-0 removeRow" onclick="removeRow(this)">
                <i class="fa-duotone fa-solid fa-trash-can fa-xl" style="--fa-primary-color: #d94a0d; --fa-secondary-color: #e53446;"></i>
            </a>`;

            const addButton = `<button type="button" class="btn btn-sm btn-primary ms-2" onclick="addMaterialRow()">
                <i class="mdi mdi-plus me-1"></i>Add
            </button>`;

            const row = `
                <tr>
                    <td>${materialIndex + 1}</td>
                    <td>
                        <select class="form-select w-px-100" name="materials[${materialIndex}][name]" required>
                            @foreach ($materialMaster as $mat)
                                <option value="{{ $mat->id }}" ${material.mat_id == {{ $mat->id }} ? 'selected' : ''}>
                                    {{ $mat->title }}
                                </option>
                            @endforeach
                        </select>
                        ${material.id ? `<input type="hidden" name="materials[${materialIndex}][id]" value="${material.id}">` : ''}
                    </td>
                    <td>
                        <input type="text" name="materials[${materialIndex}][stringer_no]" class="form-control invoice-item-price w-px-150" placeholder="Stringer No" required readonly>
                    </td>
                    <td><input type="time" name="materials[${materialIndex}][time]" class="form-control w-px-12" value="${material.time || ''}" placeholder="Time" required></td>
                    <td><input type="number" min="0" name="materials[${materialIndex}][production_qty]" class="form-control w-px-150" value="${material.production_qty || ''}" placeholder="Production Qty" required></td>
                    <td><input type="number" min="0" name="materials[${materialIndex}][rejection_qty]" class="form-control w-px-150" value="${material.rejection_qty || ''}" placeholder="Rejection Qty" required></td>
                    <td>
                        <select class="form-select w-px-200" name="materials[${materialIndex}][stage]" required>
                            <option value="">Select</option>
                            @foreach ($DmgRsn as $dmg)
                                <option value="{{ $dmg->mstr_type_name }}" ${material.stage == '{{ $dmg->mstr_type_name }}' ? 'selected' : ''}>
                                    {{ $dmg->mstr_type_name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="form-select w-px-200" name="materials[${materialIndex}][defect_category]" required>
                            <option value="">Select Defect Master</option>
                            @foreach ($DefectCat as $cat)
                                <option value="{{ $cat->mstr_type_name }}" ${material.defect_category == '{{ $cat->mstr_type_name }}' ? 'selected' : ''}>
                                    {{ $cat->mstr_type_name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="text-center">
                        ${materialIndex === 0 ? addButton : actionButton}
                    </td>
                </tr>`;

            $('#productionTable').append(row);

            // If we have a selected stringer number, set it for this new row
            if (selectedStringerNo) {
                $(`input[name="materials[${materialIndex}][stringer_no]"]`).val(selectedStringerNo);
            }
            
            if ($.fn.select2) {
                $('#productionTable tr:last select').select2();
            }

            materialIndex++;
            updateSerialNumbers();
        }

        function removeRow(button) {
            const tr = $(button).closest('tr');
            if ($('#productionTable tr').length > 1) {
                tr.remove();
                updateSerialNumbers();
            }
        }

        function updateSerialNumbers() {
            $('#productionTable tr').each(function(index, row) {
                $(row).find('td:first').text(index + 1);
            });
        }
    </script>
@endsection