@extends('includes.layout')

@section('pageHeading')
Add Product
@endsection

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center bg-label-primary py-1">
      <h5 class="mb-0">Production Setup :</h5>
      <div class="text-end">
        <a href="javascript: history.go(-1)" class="ms-2 btn btn-primary btn-sm waves-effect waves-light"
          data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Back to list">
          <span class="mdi mdi-keyboard-backspace"></span>
        </a>
      </div>
    </div>
    <div class="card-body">

      <form action="{{ url('production-lineup/production-setup/add-production/insert') }}" method="POST" id="productionForm">
        @csrf

        <div class="col-lg-12 mx-auto">
          <div class="row g-2">
            <div class="col-md-3 col-sm-6 col-12">
              <label class="form-label">Batch No.</label>
              <input class="form-control" type="text" value="{{ time() }}" name="batchNo" readonly>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
              <label class="form-label">Plant No. *</label>
              <select class="select2 form-select" name="plant_no" required>
                <option value="">Select Plant</option>
                @foreach($PlantMaster as $plant)
                <option value="{{ $plant->mstr_type_name }}">{{ $plant->mstr_type_name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
              <label class="form-label">Start From Date *</label>
              <input type="date" class="form-control dob-picker" name="start_date" placeholder="YYYY-MM-DD" required/>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
              <label class="form-label">From Shift *</label>
              <select name="shift" id="shift" class="select2 form-select" required>
                <option value="">Select Shift</option>
                @foreach ($ShiftMaster as $shift)
                <option value="{{ $shift->id }}">{{ $shift->shift }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
              <label class="form-label">Wattage *</label>
              <input class="form-control" type="text" name="wattage" placeholder="Wattage" required>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
              <label class="form-label">Finished Good *</label>
              <select class="select2 form-select" name="finished_good" required>
                <option value="">Select Finished Good</option>
                @foreach ($FinishedGood as $product)
                <option value="{{ $product['matId'] }}">{{ $product['matName'] }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>

        <!-- Material Table -->
        <div class="mt-4 table-responsive text-nowrap">
          <table class="table table-bordered">
            <thead class="table-light">
              <tr>
                <th></th>
                <th>SL No *</th>
                <th>Material *</th>
                <th>UOM *</th>
                <th>Size *</th>
                <th>QTY *</th>
                <th>UOM *</th>
                <th>Brand *</th>
              </tr>
            </thead>
            <tbody id="productionTable">
              @foreach ($MaterialList as $key=>$material)
                <tr>
                    <td><input type="checkbox" class="row-check" name="row_check[]" checked <?php if($key<3) : ?> onclick="return false;" <?php endif; ?>></td>
                    <td>{{ $key+1 }}</td>
                    <td>
                        <select class="form-control w-px-200 material-select" name="material[]" required >
                            <option value="{{ $material['id'] }}">{{ $material['title'] }}</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control w-px-120" value="{{ $material['uom'] }}" readonly>
                    </td>
                    <td>
                        <input type="text" class="form-control w-px-120 size-field" name="size[]" inputmode="decimal" pattern="[0-9.]+"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1')" required >
                    </td>
                    <td>
                        <input type="text" class="form-control w-px-120 qty-field" name="qty[]" inputmode="decimal" pattern="[0-9.]+"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1')" required <>
                    </td>
                    <td>
                        <input type="text" class="form-control w-px-120 uom-field" name="uom[]" pattern="[A-Za-z]+" required >
                        <div class="invalid-feedback">Enter only letters</div>
                    </td>
                    <td>
                        <input type="text" class="form-control brand-field" name="brand[]" required >
                    </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <!-- Matrix Section -->
        <div class="col-12 d-flex justify-content-between mb-0 p-0 divider">
          <h4 class="divider-text fw-semibold mb-0 p-1 pt-2 text-primary">CELL POSITION MODULE MATRIX</h4>
        </div>
        <div class="row">
          <div class="col-md-2 col-sm-6 col-12">
            <label class="form-label">Row No. *</label>
            <input id="rowInput" class="form-control" type="number" placeholder="Row No" name="rowNo" required>
          </div>
          <div class="col-md-2 col-sm-6 col-12">
            <label class="form-label">Column No *</label>
            <input id="columnInput" class="form-control" type="number" placeholder="Column No" name="colNo" required>
          </div>
          <div class="col-md-2 col-sm-6 col-12">
            <h6 class="mb-2">Matrix</h6>
            <button type="button" id="viewMatrixBtn" class="btn btn-primary">
              View
            </button>
          </div>
          <div class="col-md-6 col-sm-6 col-12">
            <label class="form-label">Comment *</label>
            <textarea class="form-control" name="comment" rows="1" required></textarea>
          </div>
        </div>

        <div class="col-12 text-end">
          <button type="submit" class="btn btn-primary mt-3">Submit</button>
        </div>
      </form>

      <!-- Matrix Modal -->
      <div class="modal fade" id="matrixModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header py-2">
              <h5 class="modal-title">Cell Position Module Matrix</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="matrixContainer"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.invalid-feedback {
  display: none;
  color: #dc3545;
  font-size: 0.875em;
  margin-top: 0.25rem;
}
.is-invalid {
  border-color: #dc3545 !important;
}
.is-invalid + .invalid-feedback {
  display: block;
}
</style>
@endsection

@section('pageScript')
<script>
$(document).ready(function () {
      // Checkbox toggle for each row
      $('#productionTable').on('change', '.row-check', function () {
      let $row = $(this).closest('tr');
      let isChecked = this.checked;
      
      // Find all input/select except the checkbox & readonly fields
      $row.find('input:not([type=checkbox]), select').each(function() {
        $(this).prop('disabled', !isChecked);
        
        // Clear validation state and values when disabling
        if (!isChecked) {
          $(this).removeClass('is-invalid');
          
          // Clear field values when unchecked (except readonly fields)
          if (!$(this).is('[readonly]') && $(this).is('input')) {
            $(this).val('');
          }
          
          // Reset select fields to first option
          if ($(this).is('select')) {
            $(this).prop('selectedIndex', 0);
          }
        }
      });
    });

  // Validate UOM field on input
  $('#productionTable').on('input', '.uom-field', function() {
    validateUomField(this);
  });

  // Custom validation function for UOM field
  function validateUomField(field) {
    const value = $(field).val();
    const pattern = $(field).attr('pattern');
    
    if (value && pattern) {
      const regex = new RegExp('^' + pattern + '$');
      if (!regex.test(value)) {
        $(field).addClass('is-invalid');
        return false;
      } else {
        $(field).removeClass('is-invalid');
        return true;
      }
    }
    return true;
  }

  // Form submission handler
  $('#productionForm').on('submit', function(e) {
    let formValid = true;
    let firstInvalidField = null;
    
    // Check all enabled UOM fields
    $('.uom-field:not(:disabled)').each(function() {
      if (!validateUomField(this)) {
        formValid = false;
        if (!firstInvalidField) {
          firstInvalidField = this;
        }
      }
    });
    
    // Check if at least one row is selected
    const hasSelectedRow = $('.row-check:checked').length > 0;
    if (!hasSelectedRow) {
      alert('Please select at least one material row');
      formValid = false;
    }
    
    if (!formValid) {
      e.preventDefault();
      // Scroll to first error
      if (firstInvalidField) {
        $('html, body').animate({
          scrollTop: $(firstInvalidField).offset().top - 100
        }, 500);
        $(firstInvalidField).focus();
      }
      return false;
    }
  });

 // Function to convert column number (0-based) to Excel-style letters
  function getColumnLetter(colIndex) {
    let letters = "";
    while (colIndex >= 0) {
      letters = String.fromCharCode((colIndex % 26) + 65) + letters;
      colIndex = Math.floor(colIndex / 26) - 1;
    }
    return letters;
  }
  
  // Matrix view code
  $('#viewMatrixBtn').on('click', function () {
    const rows = parseInt($('#rowInput').val());
    const cols = parseInt($('#columnInput').val());

    if (!rows || !cols || rows <= 0 || cols <= 0) {
      alert("Please enter valid Row and Column numbers.");
      return;
    }

    let matrixHtml = `
      <div class="table-responsive">
        <table class="bg-black table table-bordered text-center">
          <tr class="bg-facebook">
            <td class="p-1 header-cell"></td>
    `;

    // Column headers
    for (let c = 0; c < cols; c++) {
      const columnLetter = getColumnLetter(c);
      matrixHtml += `<td class="p-1 header-cell text-light">${columnLetter}</td>`;
    }
    matrixHtml += '</tr>';

    // Rows
    for (let i = 0; i < rows; i++) {
      matrixHtml += `<tr><td class="p-1 header-cell text-light bg-facebook">${i + 1}</td>`;
      for (let j = 0; j < cols; j++) {
        const columnLetter = getColumnLetter(j);
        matrixHtml += `<td class="p-1 matrix-cell" data-toggle="tooltip" title="Cell: ${i + 1}${columnLetter}"></td>`;
      }
      matrixHtml += '</tr>';
    }
    matrixHtml += `
        </table>
      </div>
    `;

    $('#matrixContainer').html(matrixHtml);
    $('#matrixModal').modal('show');

    $('.matrix-cell').tooltip({
      placement: 'top',
      trigger: 'hover'
    });
  });
});
</script>
@endsection