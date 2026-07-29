@extends('includes.layout')

@section('pageHeading')
    Cell Cutting Request View Details
@endsection

@section('content')

    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center bg-label-primary py-1">
                <h5 class="mb-0">Cell Cutting Request View :</h5>
                <div class="text-end">
                    <a href="javascript: history.go(-1)" class="ms-2 btn  btn-primary btn-sm waves-effect waves-light"
                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Back to list"><span
                            class="mdi mdi-keyboard-backspace"></span></a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Batch No : </label>
                            <span>{{ $cellCuttingDetails->batchNo ?? '' }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Date : </label>
                            <span>{{ date('d/m/Y', strtotime($cellCuttingDetails->date ?? '')) }}</span>
                        </div>
                    </div>

                    <div class="col-md-3 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Shift :</label>
                            <span>{{ $cellCuttingDetails->shiftdtl ?? '' }}</span>
                        </div>
                    </div>
                    @php $batch = $batchData->first(); @endphp
                    <div class="col-md-3 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Plant No. :</label>
                            <span>{{ $batch->plantNo ?? '' }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Wattage :</label>
                            <span>{{ $batch->wattage ?? '' }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Efficiency :</label>
                            <span>{{ $batch->efficiency ?? '' }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Cell company Name :</label>
                            <span>{{ $batch->brand ?? '' }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Operator :</label>
                            <span>{{ $cellCuttingDetails->operatorName ?? '' }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Checker :</label>
                            <span>{{ $cellCuttingDetails->checkerName ?? '' }}</span>
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
                            <table class="table m-0" id="">
                                <thead class="bg-label-hover-dark">
                                    <tr>
                                        <td>SL No</td>
                                        <td>Material</td>
                                        <td>Time</td>
                                        <td>Production Qty</td>
                                        <td>Rejection Qty</td>
                                        <td>Stage/Reason</td>
                                        <td>Defect Category</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($cellCuttingMaterials) > 0)
                                        @foreach ($cellCuttingMaterials as $index => $material)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ 'Cell' }}</td>
                                                <td>{{ $material->time ?? '' }}</td>
                                                <td>{{ $material->productionQty ?? '' }}</td>
                                                <td>{{ $material->RejectQty ?? '' }}</td>
                                                <td>{{ $material->reason ?? '' }}</td>
                                                <td>{{ $material->defectCat ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center text-danger text-uppercase">No materials
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
                                    @foreach ($cellCuttingHistory as $index => $history)
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
            </div>
        </div>
    </div>
@endsection
