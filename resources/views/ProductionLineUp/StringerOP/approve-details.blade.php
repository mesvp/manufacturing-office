@extends('includes.layout')

@section('pageHeading')
    Stringer OP Request View Details
@endsection

@section('content')
    <?php
    foreach ($productSetDtls as $details);
    ?>
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center bg-label-primary py-1">
                <h5 class="mb-0">Stringer OP Request View :</h5>
                <div class="text-end">
                    <a href="javascript: history.go(-1)" class="ms-2 btn btn-primary btn-sm waves-effect waves-light"
                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Back to list">
                        <span class="mdi mdi-keyboard-backspace"></span>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Batch No :</label>
                            <span>{{ $stringerOpDetails->batchNo ?? '' }}</span>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Date :</label>
                            <span>{{ isset($stringerOpDetails->date) ? date('d/m/Y', strtotime($stringerOpDetails->date)) : '' }}</span>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Shift :</label>
                            <span>{{ $stringerOpDetails->shiftdtl ?? '' }}</span>
                        </div>
                    </div>
                    @php $batch = $batchData->first(); @endphp
                    <div class="col-md-4 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Plant No. :</label>
                            <span>{{ $stringerOpDetails->plant ?? '' }}</span>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Wattage :</label>
                            <span>{{ $batch->wattage ?? '' }}</span>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Brand :</label>
                            <span>{{ $batch->StringBrand ?? '' }}</span>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Cell Efficiency :</label>
                            <span>{{ $batch->efficiency ?? '' }}</span>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Cell company Name :</label>
                            <span>{{ $batch->cellBrand ?? '' }}</span>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Stringer Size :</label>
                            <span>{{ $batch->StringSize ?? '' }}</span>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Stringer No :</label>
                            <span>{{ $stringerOpDetails->strNo ?? '' }}</span>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Operator :</label>
                            <span>{{ $stringerOpDetails->operatorName ?? '' }}</span>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Checker :</label>
                            <span>{{ $stringerOpDetails->checkerName ?? '' }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Finished Good :</label>
                            <span>{{ $batch->matname ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive border rounded-4 border-bottom-0">
                            <table class="table m-0">
                                <thead class="bg-label-hover-dark">
                                    <tr>
                                        <td>SL No</td>
                                        <td>Material</td>
                                        <td>Stringer No</td>
                                        <td>Time</td>
                                        <td>Production Qty</td>
                                        <td>Rejection Qty</td>
                                        <td>Stage/Reason</td>
                                        <td>Defect Category</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($stringerOpMaterials) > 0)
                                        @foreach ($stringerOpMaterials as $index => $material)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ 'String' }}</td>
                                                <td>{{ $material->stringerNo ?? '' }}</td>
                                                <td>{{ $material->time ?? '' }}</td>
                                                <td>{{ $material->productionQty ?? '' }}</td>
                                                <td>{{ $material->RejectQty ?? '' }}</td>
                                                <td>{{ $material->reason ?? '' }}</td>
                                                <td>{{ $material->defectCat ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="8" class="text-center text-danger text-uppercase">No materials
                                                found ...</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-12 mt-5">
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
                                    @foreach ($stringerOpHistory as $index => $history)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $history->action ?? 'N/A' }}</td>
                                            <td>{{ $history->actionBy ?? 'N/A' }}</td>
                                            <td>{{ date('d-m-Y h:i A', strtotime($history->created_at ?? '')) }}</td>
                                            <td>{{ $history->remarks ?? 'N/A' }}</td>
                                            <td>{{ $history->ip ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


                <hr>
                <div class="col-12 mt-2">
                    <h6>Approval Process</h6>
                    <form action="{{ url('production-lineup/stringer-op/approvalAction') }}" method="post">
                        @csrf
                        <textarea class="form-control" name="remark" required></textarea>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <select class="form-control" name="ApprStat">
                                    <option value="1">Approve</option>
                                    <option value="2">Recheck</option>
                                    <option value="3">Hold</option>
                                    <option value="4">Reject</option>
                                </select>
                            </div>
                            <div class="col-md-6" style="text-align:right;">
                                <button class="btn btn-primary" type="submit" name="submitData"
                                    value="{{ $details->id }}_{{ $details->stage }}_{{ $details->stage_title }}">Submit</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
