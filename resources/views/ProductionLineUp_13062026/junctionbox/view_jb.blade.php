@extends('includes.layout')

@section('pageHeading')
    Junction Box  - View Details
@endsection

@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center bg-label-primary py-2">
                <h5 class="mb-0">Junction Box — Details</h5>
                <div class="text-end">
                    <a href="javascript: history.go(-1)" class="ms-2 btn  btn-primary btn-sm waves-effect waves-light"
                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Back to list"><span
                            class="mdi mdi-keyboard-backspace"></span></a>
                </div>
            </div>

            <div class="card-body">
                @php $e = $laminatorDetails; @endphp
                <form action="{{ url('production-lineup/laminator-op/update/' . $e->jb_id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="rwrk_pg" value="{{ request()->page }}">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Barcode No.</label>
                            <input class="form-control" id="bushing_no" value="{{ $e->jb_barcode ?? '-' }}"
                                readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Batch No</label>
                            <input class="form-control" id="batchno" value="{{ $e->jb_batchNo ?? '-' }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date</label>
                            <input class="form-control" id="date" value="{{ $e->jb_date ?? '-' }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Time</label>
                            <input class="form-control" id="time" value="{{ $e->jb_time ?? '-' }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Shift</label>
                            <input class="form-control" id="shift" value="{{ $e->shift_name ?? '-' }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Plant</label>
                            <input class="form-control" id="plant" value="{{ $e->jb_plant ?? '-' }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Operator</label>
                            <input class="form-control" id="operator"
                                value="{{ $e->operator_name ?? ($e->jb_operator ?? '-') }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Incharge</label>
                            <input class="form-control" id="incharge"
                                value="{{ $e->incharge_name ?? ($e->jb_incharge ?? '-') }}" readonly>
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
                                        <td>Bar Code</td>
                                        <td class="py-1">
                                        </td>
                                        <td><input type="text" id="barcodeInput2" class="form-control" name="barCode"
                                                value="{{ $e->jb_barcode }}" placeholder="Fetch No."
                                                autocomplete="off" readonly></td>
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
                                                <select class="form-select select2 w-px-250" name="rwrk_status"
                                                    id="el_type" required>
                                                    <option value="1">Passed</option>
                                                    <option value="2">Reject</option>
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
                </form>
                <hr>

                <h5>Defect Details : </h5>
                <div class="row">
                    <div class="col-12 mt-4">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>SL No</th>
                                        <th>Type</th>
                                        <th>Size</th>
                                        <th>Brand</th>
                                        <th>Quantity</th>
                                        <th>UOM</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!empty($defectDetails) && $defectDetails->count() > 0)
                                        @foreach ($defectDetails as $idx => $d)
                                            <tr>
                                                <td>{{ $idx + 1 }}</td>
                                                <td>{{ $d->type ?? '-' }}</td>
                                                <td>{{ $d->size ?? '-' }}</td>
                                                <td>{{ $d->brand ?? '-' }}</td>
                                                <td>{{ $d->qty ?? '-' }}</td>
                                                <td>{{ $d->uom ?? '-' }}</td>
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
                    </div>
                </div>
                <hr>
                <h5>Junction Box Trail : </h5>
                <div class="table-responsive text-nowrap">
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
                            @if (!empty($laminatorHistory) && $laminatorHistory->count() > 0)
                                @foreach ($laminatorHistory as $idx => $history)
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
        $(document).ready(function() {
            const batchno = $('#batchno').val();

            $.ajax({
                url: "{{ url('production-lineup/laminator-op/getBushingMaterial') }}",
                type: 'GET',
                data: {
                    q: batchno
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
    </script>
@endsection
