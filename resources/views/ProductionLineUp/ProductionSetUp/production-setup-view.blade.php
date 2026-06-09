@extends('includes.layout')

@section('pageHeading')
 Production Set Up View Details
@endsection


@section('content')

  <?php
    foreach($productSetDtls as $details);
  ?>

  <div class="container-fluid flex-grow-1 container-p-y">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center bg-label-primary py-2">
        <h5 class="mb-0">Production view :</h5>
        <div class="text-end">
          <a href="javascript: history.go(-1)" class="ms-2 btn  btn-primary btn-sm waves-effect waves-light" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Back to list"><span class="mdi mdi-keyboard-backspace"></span></a>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-4 col-12">
            <div class="mb-3">
              <label class="fw-medium text-black">Batch No : </label>
              <span>{{ $details->batchNo }}</span>
            </div>
          </div>
          <div class="col-md-4 col-12">
            <div class="mb-3">
              <label class="fw-medium text-black">Plant No :</label>
              <span>{{ $details->plantNo }}</span>
            </div>
          </div>
          <div class="col-md-4 col-12">
            <div class="mb-3">
              <label class="fw-medium text-black">Start From Date : </label>
              <span>{{ date('d/m/Y', strtotime($details->startDate)) }}</span>
            </div>
          </div>
          <div class="col-md-4 col-12">
            <div class="mb-3">
              <label class="fw-medium text-black">From Shift :</label>
              <span>{{ $details->ShiftName }}</span>
            </div>
          </div>
          <div class="col-md-4 col-12">
            <div class="mb-3">
              <label class="fw-medium text-black">Wattage :</label>
              <span>{{ $details->wattage }}</span>
            </div>
          </div>
          <div class="col-md-4 col-12">
            <div class="mb-3">
              <label class="fw-medium text-black">Finished Good :</label>
              <span>
                 @foreach ($FinishedGood as $product)
                    @if($details->finishGood == $product['matId'])
                        {{ $product['matName'] }}
                    @endif
                @endforeach
              </span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <div class="table-responsive border rounded-4 border-bottom-0">
              <table class="table m-0" id="">
                <thead class="bg-label-hover-dark">
                  <tr>
                    <td>SL No</td>
                    <td>Material</td>
                    <td>UOM</td>
                    <td>Size</td>
                    <td>UOM</td>
                    <td>Quantity</td>
                    <td>Brand</td>
                  </tr>
                </thead>
                <tbody>
                  @foreach($productSetMtrl as $key=>$prodMtrl)  
                  <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $prodMtrl->title }}</td>
                    <td>{{ $prodMtrl->mat_uom }}</td>
                    <td>{{ $prodMtrl->size }}</td>
                    <td>{{ $prodMtrl->uom }}</td>
                    <td>{{ $prodMtrl->qty }}</td>
                    <td>{{ $prodMtrl->brand }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div class="col-12 mt-3">
                        <div class="mb-6">
                            <h6 class="card-header p-2">CELL POSITION MODULE MATRIX</h6>
                            <div class="mb-3 row row-gap-1">
                                <div class="col-xl-5 col-md-7">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-12">
                                            <label class="form-label">Row No.</label>
                                            <input id="rowInput" class="form-control" type="text"
                                                value="{{ $details->cellRow }}" disabled>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-12">
                                            <label class="form-label">Column No</label>
                                            <input id="columnInput" class="form-control" type="text"
                                                value="{{ $details->celColumn }}" disabled>
                                        </div>
                                        <div class="col-12 col-md-12 col-sm-6 mt-2">
                                            <label class="fw-medium text-black">Comment : </label>
                                            <span>{{ $details->comment }} </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-7 col-md-5">
                                    <!-- <div class="text-center">
                                        <img src="assets/img/illustrations/solar.JPG" class="img-fluid" alt="Api Key Image" width="500">
                                    </div> -->
                                    <div class="col-md-2 col-sm-6 col-12">
                                        <h6 class="mb-2">Matrix</h6>
                                        <button type="button" id="viewpage_MatrixBtn" class="btn btn-primary">
                                            View
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
          <div class="col-12">
            <div class="table-responsive text-nowrap">
              <table class="table table-bordered">
                <thead class="bg-gradient-start-1">
                  <tr>
                    <th>Sl.NO</th>
                    <th>Stage Remark</th>
                    <th>Approval Action By</th>
                    <th>Created Date and Time</th>
                    <th>Message</th>
                    <th>IP Address</th>
                  </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                  @foreach($productSetTrail as $key=>$trail)
                  <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $trail->action }}</td>
                    <td>{{ $trail->fullname }}</td>
                    <td>{{ date('d-m-Y H:i:s', strtotime($trail->created_at)) }}</td>
                    <td>{{ $trail->remarks }}</td>
                    <td>{{ $trail->ip }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
<!-- Matrix Modal -->
                <div class="modal fade" id="matrixModal_view" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header py-2">
                                <h5 class="modal-title">Cell Position Module Matrix</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body" id="matrixContainer_view"></div>
                        </div>
                    </div>
                </div>
      </div>
    </div>
  </div>
@endsection
@section('pageScript')
    <script>
    
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
        $('#viewpage_MatrixBtn').on('click', function() {
            const rows = parseInt($('#rowInput').val() || $('#rowInput').attr('placeholder'));
            const cols = parseInt($('#columnInput').val() || $('#columnInput').attr('placeholder'));

            if (!rows || !cols || rows <= 0 || cols <= 0) {
                $('#matrixModal_view').modal('show');
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
                  matrixHtml += `<td class="p-1 header-cell text-light">${c + 1}</td>`;
                }
                matrixHtml += '</tr>';
            
                // Rows
                for (let i = 0; i < rows; i++) {
                  const rowLetter = getColumnLetter(i);
                  matrixHtml += `<tr><td class="p-1 header-cell text-light bg-facebook">${rowLetter}</td>`;
                  for (let j = 0; j < cols; j++) {
                    
                    matrixHtml += `<td class="p-1 matrix-cell" data-toggle="tooltip" title="Cell: ${rowLetter}${j + 1}"></td>`;
                  }
                  matrixHtml += '</tr>';
                }
                matrixHtml += `
                    </table>
                  </div>
                `;

            $('#matrixContainer_view').html(matrixHtml);
            $('#matrixModal_view').modal('show');

            $('.matrix-cell').tooltip({
                placement: 'top',
                trigger: 'hover'
            });
        });
    </script>
@endsection

