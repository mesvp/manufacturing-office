@extends('includes.layout')

@section('pageHeading')
    Add Glass Feeding Request
@endsection

@section('content')
    
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center bg-label-primary py-1">
                <h5 class="mb-0">Add Request Glass Feeding</h5>
                <div class="text-end">
                    <a href="javascript: history.go(-1)" class="ms-2 btn btn-primary btn-sm">
                        <span class="mdi mdi-keyboard-backspace"></span>
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="col-lg-12 mx-auto">
                    <form action="{{ url('production-lineup/glass-feeding-add/insert') }}" method="post">
                        @csrf
                        <div class="row g-2">
                            <div class="col-md-2 col-sm-6 col-12">
                                <label class="form-label">Select Batch No.</label>
                                <select name="batch_no" id="batch_no" class="select2 form-select"
                                    onchange="showHint(this.value)" required>
                                    <option value="">Batch No</option>
                                    @foreach ($batchList as $batch)
                                        <option value="{{ $batch->batchNo }}">{{ $batch->batchNo }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2 col-sm-6 col-12">
                                <label class="form-label">Date</label>
                                <input type="text" name="date" id="date" placeholder="YYYY-MM-DD" class="form-control dob-picker"
                                    required>
                            </div>

                            <div class="col-md-2 col-sm-6 col-12">
                                <label class="form-label">Select Shift</label>
                                <select name="shift" id="shift" class="select2 form-select" required>
                                    <option value="">Select Shift</option>
                                    @foreach ($ShiftMaster as $shift)
                                        <option value="{{ $shift->id }}">{{ $shift->shift }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2 col-sm-6 col-12">
                                <label class="form-label">Select Plant No.</label>
                                <select name="plant_no" id="plant_no" class="select2 form-select" required>
                                    <option value="">Select Plant</option>
                                    @foreach ($PlantMaster as $plant)
                                        <option value="{{ $plant->mstr_type_name }}">{{ $plant->mstr_type_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2 col-sm-6 col-12">
                                <label class="form-label">Wattage</label>
                                <input type="text" name="wattage" id="wattage" class="form-control" placeholder="Wattage" disabled>
                            </div>

                            <div class="col-md-2 col-sm-6 col-12">
                                <label class="form-label">Efficiency</label>
                                <input type="text" name="efficiency" id="efficiency" class="form-control" placeholder="Efficiency" disabled>
                            </div>

                            <div class="col-md-2 col-sm-6 col-12">
                                <label class="form-label">Finished Good</label>
                                <input type="text" name="finished_good" id="finished_good" class="form-control" placeholder="Finished Good" disabled>
                            </div>
                            
                            <div class="col-md-2 col-sm-6 col-12">
                                <label class="form-label">Cell Company Name</label>
                                <input type="text" name="cell_company" id="cell_company" class="form-control" placeholder="Cell company Name" disabled>
                            </div>

                            <div class="col-md-2 col-sm-6 col-12">
                                <label class="form-label">Glass Size</label>
                                <input type="text" name="glass_size" id="glass_size" class="form-control" placeholder="Glass Size" disabled>
                            </div>

                            <div class="col-md-3 col-sm-6 col-12">
                                <label class="form-label">Select Operator</label>
                                <select name="operator" class="select2 form-select" required>
                                    <option value="">Select Operator</option>
                                    @foreach ($userList as $operator)
                                        <option value="{{ $operator->id }}">{{ $operator->fullname }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 col-sm-6 col-12">
                                <label class="form-label">Select Checker</label>
                                <select name="checker" class="select2 form-select" required>
                                    <option value="">Select Checker</option>
                                    @foreach ($userList as $checker)
                                        <option value="{{ $checker->id }}">{{ $checker->fullname }}</option>
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
                                        <!-- Rows will be populated dynamically -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary mt-3">Submit</button>
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

function addMaterialRow(material = {}) {
    // Remove "no data" message if it exists
    if ($('#productionTable tr').length === 1 && $('#productionTable tr td').attr('colspan')) {
        $('#productionTable').empty();
    }

    const isFirstRow = $('#productionTable tr').length === 0;
    const actionButton = isFirstRow ?
        `<button type="button" class="btn btn-sm btn-primary" onclick="addMaterialRow()"><i class="mdi mdi-plus me-1"></i> Add</button>` :
        `<a class="btn border-start-0 removeRow" onclick="removeRow(this)">
            <i class="fa-duotone fa-solid fa-trash-can fa-xl" style="--fa-primary-color: #d94a0d; --fa-secondary-color: #e53446;"></i>
        </a>`;

    // Get size value from material parameter or main input field
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
                   placeholder="Time" required>
        </td>
        <td>
            <input type="number" min="0" name="materials[${materialIndex}][production_qty]" 
                   class="form-control w-px-150" 
                   placeholder="Production Qty" required>
        </td>
        <td>
            <input type="number" min="0" name="materials[${materialIndex}][rejection_qty]" 
                   class="form-control w-px-150" 
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
    
    // If no rows left, show "no data" message
    if ($('#productionTable tr').length === 0) {
        showNoDataMessage();
    } else {
        // Ensure first row has Add button
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

function showHint(batchNo) {
    if (!batchNo) {
        // Clear all fields
        $('#wattage, #efficiency, #cell_company, #glass_size').val('');
        showNoDataMessage();
        return;
    }

    $.ajax({
        url: "{{ url('production-lineup/glass-feeding-add/getGlassMaterial') }}",
        type: "GET",
        data: { q: batchNo },
        success: function(response) {
            console.log('AJAX Response:', response); // Debugging
            
            // Update main fields
            $('#wattage').val(response.wattage || '');
            $('#efficiency').val(response.efficiency || '');
            $('#finished_good').val(response.finishGood);
            $('#cell_company').val(response.cell_company || '');
            $('#glass_size').val(response.glass_size || '');

            // Clear and rebuild table
            $('#productionTable').empty();
            materialIndex = 0;

            if (response.materials && response.materials.length > 0) {
                response.materials.forEach(material => {
                    addMaterialRow({
                        id: 3, // Always Glass
                        size: material.size || response.glass_size || '',
                        stage: material.stage || '',
                        defect_category: material.defect_category || ''
                    });
                });
            } else {
                // Add default Glass row with size from response
                addMaterialRow({
                    id: 3,
                    size: response.glass_size || ''
                });
            }
            
            // Update all glass_size inputs (double ensure)
            $('.glass_size').val(response.glass_size || '');
        },
        error: function(xhr) {
            console.error("Error fetching batch data:", xhr.responseText);
            $('#productionTable').empty();
            addMaterialRow({id: 3});
        }
    });
}

// Initialize on page load
$(document).ready(function() {
    // Start with "no data" message
    showNoDataMessage();
    
    // Set today's date as default
    // $('#date').val(new Date().toISOString().split('T')[0]);
    
});
</script>
@endsection