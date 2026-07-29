@extends('includes.layout')

@section('pageHeading')
    Layout Setup View Details
@endsection

@section('content')

    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center bg-label-primary py-2">
                <h5 class="mb-0">Layout Operator view :</h5>
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
                            <label class="fw-medium text-black">Req No. : </label>
                            <span>{{ 'LAYOUT-'.$bushingData->bushing_id }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Date : </label>
                            <span>{{ $bushingData->bushing_date }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Time : </label>
                            <span>{{ \Carbon\Carbon::parse($bushingData->bushing_time)->format('h:i A') }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Operator :</label>
                            <span>{{ $bushingData->bushing_operator }}</span>
                        </div>
                    </div>

                    <div class="col-md-3 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Batch No :</label>
                            <span>{{ $bushingData->bushing_batchNo }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Shift :</label>
                            <span>{{ $bushingData->shiftdtl }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Incharge :</label>
                            <span>{{ $bushingData->bushing_incherge }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Plant :</label>
                            <span>{{ $bushingData->bushing_plant }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Wattage :</label>
                            <span>{{ $bushingData->wattage }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Finished Good :</label>
                            <span>{{ $bushingData->matname }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Created By :</label>
                            <span>{{ $bushingData->createdBy }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Logo :</label>
                            <span>{{ $bushingData->bushing_logo ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="table-responsive border rounded-4 border-bottom-0">
                            <table class="table m-0" id="">
                                <thead class="bg-label-hover-dark">
                                    <tr>
                                        <td>SL No</td>
                                        <td>Materials</td>
                                        <td>Size</td>
                                        <td>Brand</td>
                                        <td>Usages</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bushingMat as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->mname }}</td>
                                            <td>{{ $item->size }}</td>
                                            <td>{{ $item->brand }}</td>
                                            <td>{{ $item->status }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="table-responsive border rounded-4 border-bottom-0">
                            <table class="table m-0" id="">
                                <thead class="bg-label-hover-dark">
                                    <tr>
                                        <td></td>
                                        <td>Scan</td>
                                        <td>Fetch No</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!--<tr>-->
                                    <!--    <td>RFID</td>-->
                                    <!--    <td class="fw-bold text-info">Scanner</td>-->
                                    <!--    <td>{{ $bushingData->bushing_rfid }}</td>-->
                                    <!--</tr>-->
                                    <tr>
                                        <td>Bar Code</td>
                                        <td class="fw-bold text-info">Scanner</td>
                                        <td>{{ $bushingData->bushing_barCode }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-lg-12 mt-4">
                        <div class="table-responsive border rounded-4 border-bottom-0">
                            <table class="table m-0" id="">
                                <thead class="bg-label-hover-dark">
                                    <tr>
                                        <td>SL No</td>
                                        <td>Material</td>
                                        <td>Qty</td>
                                        <td>UOM</td>
                                        <td>Reason</td>
                                        <td>Category</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($bushingDamageMat->count() > 0)
                                        @foreach ($bushingDamageMat as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->mname }}</td>
                                                <td>{{ $item->dmgQty }}</td>
                                                <td>{{ $item->dmgUOM }}</td>
                                                <td>{{ $item->dmgReason }}</td>
                                                <td>{{ $item->dmgCategory }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center text-danger"> -- No Data Found -- </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
