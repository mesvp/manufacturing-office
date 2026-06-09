@extends('includes.layout')

@section('pageHeading')
    Update Glass Feeding Request
@endsection

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center bg-label-primary py-1">
            <h5 class="mb-0">
                Update Request Glass Feeding
            </h5>
            <div class="text-end">
                <a href="javascript: history.go(-1)" class="ms-2 btn btn-primary btn-sm">
                    <span class="mdi mdi-keyboard-backspace"></span>
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="col-lg-12 mx-auto">
                <form action="{{ route('glass-feeding-update', $glassFeedingDetails->id) }}" method="post">
                    @csrf
                   
                    <div class="row g-2">
                        <div class="col-md-2 col-sm-6 col-12">
                            <label class="form-label">Select Batch No.</label>
                            <input class="form-control" type="text" id="batchNo" name="batchNo" value="{{ $glassFeedingDetails->batchNo ?? '' }}" placeholder="" readonly>
                        </div>

                        <div class="col-md-2 col-sm-6 col-12">
                            <label class="form-label">Date</label>
                            <input type="text" name="date" id="date" class="form-control dob-picker" 
                                   value="{{ isset($glassFeedingDetails) ? $glassFeedingDetails->date : date('Y-m-d') }}" required />
                        </div>

                        <div class="col-md-2 col-sm-6 col-12">
                            <label class="form-label">Select Shift</label>
                            <select name="shift" id="shift" class="select2 form-select" required>
                                <option value="">Select Shift</option>
                                @foreach ($ShiftMaster as $shift)
                                    <option value="{{ $shift->id }}" {{ (isset($glassFeedingDetails) && $glassFeedingDetails->shift == $shift->id) ? 'selected' : '' }}>
                                        {{ $shift->shift }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 col-sm-6 col-12">
                            <label class="form-label">Select Plant No.</label>
                            <select name="plant_no" id="plant_no" class="select2 form-select" required>
                                <option value="">Select Plant</option>
                                @foreach ($PlantMaster as $plant)
                                    <option value="{{ $plant->mstr_type_name }}" {{ (isset($glassFeedingDetails) && $glassFeedingDetails->plant == $plant->mstr_type_name) ? 'selected' : '' }}>
                                        {{ $plant->mstr_type_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 col-sm-6 col-12">
                            <label class="form-label">Wattage</label>
                            <input type="text" name="wattage" id="wattage" class="form-control" 
                                   value="{{ isset($batchData) ? $batchData->wattage : '' }}" disabled>
                        </div>

                        <div class="col-md-2 col-sm-6 col-12">
                            <label class="form-label">Efficiency</label>
                            <input type="text" name="efficiency" id="efficiency" class="form-control" 
                                   value="{{ isset($batchData) ? $batchData->efficiency : '' }}" disabled>
                        </div>

                        <div class="col-md-2 col-sm-6 col-12">
                            <label for="defaultInput" class="form-label">Finished good</label>
                            <input class="form-control" type="text" id="finished_good" name="finished_good"
                                placeholder="Finished good" value="{{ $batchData->matname ?? '' }}" disabled>
                        </div>
                            
                        <div class="col-md-2 col-sm-6 col-12">
                            <label class="form-label">Cell Company Name</label>
                            <input type="text" name="cell_company" id="cell_company" class="form-control" 
                                   value="{{ isset($batchData) ? $batchData->brand : '' }}" disabled>
                        </div>

                        <div class="col-md-2 col-sm-6 col-12">
                            <label class="form-label">Glass Size</label>
                            <input type="text" name="glass_size" id="glass_size" class="form-control" 
                                   value="{{ isset($batchData) ? $batchData->efficiency : '' }}" disabled>
                        </div>

                        <div class="col-md-3 col-sm-6 col-12">
                            <label class="form-label">Select Operator</label>
                            <select name="operator" class="select2 form-select" required>
                                <option value="">Select</option>
                                @foreach ($userList as $operator)
                                    <option value="{{ $operator->id }}" {{ (isset($glassFeedingDetails) && $glassFeedingDetails->operator == $operator->id) ? 'selected' : '' }}>
                                        {{ $operator->fullname }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 col-sm-6 col-12">
                            <label class="form-label">Select Checker</label>
                            <select name="checker" class="select2 form-select" required>
                                <option value="">Select</option>
                                @foreach ($userList as $checker)
                                    <option value="{{ $checker->id }}" {{ (isset($glassFeedingDetails) && $glassFeedingDetails->checker == $checker->id) ? 'selected' : '' }}>
                                        {{ $checker->fullname }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>SL No</th>
                                        <th>Material</th>
                                        <th>Size</th>
                                        <th>Time</th>
                                        <th>Production Qty</th>
                                        <th>Rejection Qty</th>
                                        <th>Stage/Reason</th>
                                        <th>Defect Category</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0" id="productionTable">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary mt-3">
                            @if(isset($glassFeedingDetails))
                                Update
                            @else
                                Submit
                            @endif
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pageScript')
<script>
let materialIndex = 0;
const allMaterials = @json($materials ?? []);
const editMaterials = @json(isset($glassFeedingDetails) ? $glassFeedingDetails->materials : []);

function addMaterialRow(material = {}) {
    if ($('#productionTable tr').length === 1 && $('#productionTable tr td').attr('colspan')) {
        $('#productionTable').empty();
    }

    const isFirstRow = $('#productionTable tr').length === 0;
    const actionButton = isFirstRow ?
        `<button type="button" class="btn btn-sm btn-primary" onclick="addMaterialRow()"><i class="mdi mdi-plus me-1"></i> Add</button>` :
        `<a class="btn border-start-0 removeRow" onclick="removeRow(this)">
            <i class="fa-duotone fa-solid fa-trash-can fa-xl" style="--fa-primary-color: #d94a0d; --fa-secondary-color: #e53446;"></i>
        </a>`;
    const sizeValue = material.size || $('#glass_size').val() || '';

    let row = `
    <tr>
        <td>${$('#productionTable tr').length + 1}</td>
        <td>
            <select name="materials[${materialIndex}][name]" class="form-select w-px-100 material-select" required>
                <option value="3" selected>Glass</option>
            </select>
        </td>
        <td>
            <input type="text" name="materials[${materialIndex}][size]" 
                   class="form-control w-px-120 glass_size" 
                   value="${sizeValue}" 
                   placeholder="Size" readonly required>
        </td>
        <td>
            <input type="time" name="materials[${materialIndex}][time]" 
                   class="form-control w-px-12" 
                   value="${material.time || ''}"
                   placeholder="Time" required>
        </td>
        <td>
            <input type="number" min="0" name="materials[${materialIndex}][production_qty]" 
                   class="form-control w-px-150" 
                   value="${material.production_qty || ''}"
                   placeholder="Production Qty" required>
        </td>
        <td>
            <input type="number" min="0" name="materials[${materialIndex}][rejection_qty]" 
                   class="form-control w-px-150" 
                   value="${material.rejection_qty || ''}"
                   placeholder="Rejection Qty" required>
        </td>
        <td>
            <select class="form-select w-px-200" name="materials[${materialIndex}][stage]" required>
                <option value="">Select Stage</option>
                @foreach ($DmgRsn as $dmg)
                <option value="{{ $dmg->mstr_type_name }}" 
                        ${material.stage === '{{ $dmg->mstr_type_name }}' ? 'selected' : ''}>
                    {{ $dmg->mstr_type_name }}
                </option>
                @endforeach
            </select>
        </td>
        <td>
            <select class="form-select w-px-200" name="materials[${materialIndex}][defect_category]" required>
                <option value="">Select Defect Master</option>
                @foreach ($DefectCat as $cat)
                <option value="{{ $cat->mstr_type_name }}" 
                        ${material.defect_category === '{{ $cat->mstr_type_name }}' ? 'selected' : ''}>
                    {{ $cat->mstr_type_name }}
                </option>
                @endforeach
            </select>
        </td>
        <td class="text-center">${actionButton}</td>
    </tr>`;
    
    $('#productionTable').append(row);
    materialIndex++;
    updateSerialNumbers();
}

function removeRow(button) {
    $(button).closest('tr').remove();
    updateSerialNumbers();
    
    if ($('#productionTable tr').length === 0) {
        showNoDataMessage();
    } else {
        const firstRow = $('#productionTable tr:first');
        if (firstRow.find('.btn-primary').length === 0) {
            firstRow.find('td:last').html(
                `<button type="button" class="btn btn-sm btn-primary" onclick="addMaterialRow()"><i class="mdi mdi-plus me-1"></i> Add</button>`
            );
        }
    }
}

function updateSerialNumbers() {
    $('#productionTable tr').each(function(index) {
        if (!$(this).find('td').attr('colspan')) {
            $(this).find('td:first').text(index + 1);
        }
    });
}

function showNoDataMessage() {
    $('#productionTable').html(
        `<tr><td colspan="9" class="text-center text-danger">-- No Batch No. is Selected --</td></tr>`
    );
    materialIndex = 0;
}

$(document).ready(function() {
    @if (!empty($glassFeedingMaterials) && count($glassFeedingMaterials) > 0)
        @foreach($glassFeedingMaterials as $detail)
            addMaterialRow({
                id: '{{ $detail->id }}',
                mat_id: '{{ $detail->mat_id }}',
                time: '{{ $detail->time }}',
                production_qty: '{{ $detail->productionQty }}',
                rejection_qty: '{{ $detail->RejectQty }}',
                stage: '{{ $detail->reason }}',
                defect_category: '{{ $detail->defectCat }}'
            });
        @endforeach
    @else
        showNoDataMessage();
    @endif
});
</script>
@endsection