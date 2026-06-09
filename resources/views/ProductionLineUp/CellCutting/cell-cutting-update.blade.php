@extends('includes.layout')

@section('pageHeading')
    Cell Cutting Update Form Details
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
            
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center bg-label-primary py-1">
                <h5 class="mb-0">Cell Cutting Update Request :</h5>
                <div class="text-end">
                    <a href="javascript: history.go(-1)" class="ms-2 btn btn-primary btn-sm waves-effect waves-light"
                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Back to list">
                        <span class="mdi mdi-keyboard-backspace"></span>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('cell-cutting.update', $cellCutting->id) }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="col-lg-12 mx-auto">
                        <div class="row g-2">
                            <div class="col-md-2 col-sm-6 col-12">
                                <label for="batch_no" class="form-label">Batch No.</label>
                                <input class="form-control" type="text" id="batchNo" name="batchNo"
                                    value="{{ $cellCutting->batchNo ?? '' }}" placeholder="" readonly>
                            </div>
                            <div class="col-md-2 col-sm-6 col-12">
                                <label for="date" class="form-label">Date</label>
                                <input type="text" name="date" placeholder="YYYY-MM-DD" class="form-control dob-picker"
                                    value="{{ $cellCutting->date ?? '' }}" required/>
                            </div>
                            <div class="col-md-2 col-sm-6 col-12">
                                <label for="shift" class="form-label">Select Shift</label>
                                <div class="form-floating-outline">
                                    <select id="shift" name="shift" class="select2 form-select" required>
                                        @foreach ($ShiftMaster as $shift)
                                            <option value="{{ $shift->id }}"
                                                {{ $cellCutting->shift == $shift->id ? 'selected' : '' }}>
                                                {{ $shift->shift }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-6 col-12">
                                <label for="plant_no" class="form-label">Plant No.</label>
                                <input class="form-control" type="text" name="plant_no" id="plant_no"
                                    value="{{ $batchData->plantNo }}" placeholder="Plant-1" disabled>
                            </div>
                            <div class="col-md-2 col-sm-6 col-12">
                                <label for="wattage" class="form-label">Wattage</label>
                                <input class="form-control" type="text" id="wattage" name="wattage"
                                    value="{{ $batchData->wattage ?? '' }}" placeholder="100W" disabled>
                            </div>
                            <div class="col-md-2 col-sm-6 col-12">
                                <label for="brand" class="form-label">Brand</label>
                                <input class="form-control" type="text" id="brand" name="brand"
                                    value="{{ $batchData->brand ?? '' }}" placeholder="Brand" disabled>
                            </div>
                            <div class="col-md-2 col-sm-6 col-12">
                                <label for="efficiency" class="form-label">Efficiency</label>
                                <input class="form-control" type="text" id="efficiency" name="efficiency"
                                    value="{{ $batchData->efficiency ?? '' }}" placeholder="Efficiency" disabled>
                            </div>
                            <div class="col-md-2 col-sm-6 col-12">
                                <label for="defaultInput" class="form-label">Finished good</label>
                                <input class="form-control" type="text" id="finished_good" name="finished_good"
                                    placeholder="Finished good" value="{{ $batchData->matname ?? '' }}" disabled>
                            </div>
                            <div class="col-md-2 col-sm-6 col-12">
                                <label for="defaultInput" class="form-label">Cell company Name</label>
                                <input class="form-control" type="text" id="cell_company" name="cell_company_name"
                                    placeholder="Iyrosolar" value="{{ $batchData->brand ?? '' }}" disabled>
                            </div>
                            <div class="col-md-3 col-sm-6 col-12">
                                <label for="operator" class="form-label">Select Operator</label>
                                <div class="form-floating-outline">
                                    <select id="operator" name="operator" class="select2 form-select" required>
                                        @foreach ($employees as $admin)
                                            <option value="{{ $admin->id }}"
                                                {{ $cellCutting->operator == $admin->id ? 'selected' : '' }}>
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
                                            <option value="{{ $admin->id }}"
                                                {{ $cellCutting->checker == $admin->id ? 'selected' : '' }}>
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

    function addMaterialRow(material = {}) {
        const materialId = material.mat_id || material.material || 1;
        
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
                <select class="form-select select2-dynamic w-px-100" name="materials[${materialIndex}][name]" required>
                    @foreach ($materialMaster as $mat)
                        <option value="{{ $mat->id }}" ${materialId == {{ $mat->id }} ? 'selected' : ''}>
                            {{ $mat->title }}
                        </option>
                    @endforeach
                </select>
                ${material.id ? `<input type="hidden" name="materials[${materialIndex}][id]" value="${material.id}">` : ''}
            </td>
            <td><input type="time" name="materials[${materialIndex}][time]" class="form-control w-px-12" value="${material.time || ''}" placeholder="Time" required></td>
            <td><input type="number" min="0" name="materials[${materialIndex}][production_qty]" class="form-control w-px-12" value="${material.productionQty || material.production_qty || ''}" placeholder="Production Qty" required></td>
            <td><input type="number" min="0" name="materials[${materialIndex}][rejection_qty]" class="form-control w-px-12" value="${material.RejectQty || material.rejection_qty || ''}" placeholder="Rejection Qty" required></td>
            <td>
                <select class="form-select select2-dynamic w-px-200" name="materials[${materialIndex}][stage]" required>
                    <option value="">Select Stage</option>
                    @foreach ($DmgRsn as $dmg)
                        <option value="{{ $dmg->mstr_type_name }}" ${(material.reason == '{{ $dmg->mstr_type_name }}' || material.stage == '{{ $dmg->mstr_type_name }}') ? 'selected' : ''}>
                            {{ $dmg->mstr_type_name }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td>
                <select class="form-select select2-dynamic w-px-200" name="materials[${materialIndex}][defect_category]" required>
                    <option value="">Select Defect Master</option>
                    @foreach ($DefectCat as $cat)
                        <option value="{{ $cat->mstr_type_name }}" ${(material.defectCat == '{{ $cat->mstr_type_name }}' || material.defect_category == '{{ $cat->mstr_type_name }}') ? 'selected' : ''}>
                            {{ $cat->mstr_type_name }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td class="text-center">
                ${materialIndex === 0 ? addButton : actionButton}
            </td>
        </tr>`;

        const $newRow = $(row);
        $('#productionTable').append(row);

        if($.fn.select2) {
            $('#productionTable tr:last select').select2();
        }
        // Initialize select2 on the newly added select elements
        $newRow.find('.select2-dynamic').select2({
            dropdownParent: $('#productionTable').closest('.card-body'),
            width: '100%'
        });

        materialIndex++;
        updateSerialNumbers();
    }

    function removeRow(button) {
        const tr = $(button).closest('tr');
        if($('#productionTable tr').length > 1) { // Don't remove if it's the last row
            tr.remove();
            updateSerialNumbers();
        }
    }

    function updateSerialNumbers() {
        $('#productionTable tr').each(function(index, row) {
            $(row).find('td:first').text(index + 1);
        });
    }

    $(document).ready(function() {
        $('#productionTable').empty();
        materialIndex = 0;

        @if (!empty($cellCuttingMaterials) && count($cellCuttingMaterials) > 0)
            @foreach ($cellCuttingMaterials as $detail)
                addMaterialRow({
                    id: '{{ $detail->id }}',
                    mat_id: '{{ $detail->mat_id }}',
                    time: '{{ $detail->time }}',
                    productionQty: '{{ $detail->productionQty }}',
                    RejectQty: '{{ $detail->RejectQty }}',
                    reason: '{{ $detail->reason }}',
                    defectCat: '{{ $detail->defectCat }}'
                });
            @endforeach
        @else
            addMaterialRow();
        @endif
    });
</script>
@endsection
