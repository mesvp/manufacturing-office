@extends('includes.layout')

@section('pageHeading')
    De-Lamination List Details
@endsection

@section('content')
    <!-- Content -->
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card-header d-flex justify-content-between align-items-center py-2">
            <h5><span class="text-muted fw-light"> Production Set up /</span> De-Lamination</h5>
            <div class="mb-2 text-end">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-pills flex-column flex-md-row mb-3 gap-2 gap-lg-0" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="javascript:void(0);" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-pendingOp" aria-controls="navs-purchaseorder" aria-selected="true">
                            <i class="mdi mdi-account-outline mdi-20px me-1"></i>Pending For De-Lamination
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-passed" aria-controls="navs-sales-id" aria-selected="false">
                            <i class="mdi mdi-account-box-outline mdi-20px me-1"></i>Passed
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-reject" aria-controls="navs-profit-id" aria-selected="false">
                            <i class="mdi mdi-cash-multiple mdi-20px me-1"></i>Reject
                        </a>
                    </li>
                </ul>
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center bg-label-primary py-2">
                        <h5 class="mb-0">Pending De-Lamination Details</h5>
                        <div class="text-end">
                            <!--<a href="{{ route('add_qc', ['page' => 'ALL']) }}" class="ms-2 btn  btn-primary btn-sm waves-effect waves-light"><span-->
                                    <!--class="mdi mdi-playlist-plus me-1"></span> Add QC</a>-->
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content p-0">
                            <div class="tab-pane fade active show" id="navs-pendingOp" role="tabpanel">
                                <div class="">
                                    <table
                                        class="d-block dataTable no-footer table table-bordered table-responsive text-nowrap w-100"
                                        id="example2">
                                        <thead class="table-secondary">
                                            <tr>
                                                <td>SL No</td>
                                                <td>Date</td>
                                                <td>Time</td>
                                                <td>Shift</td>
                                                <td class="w-20">Bar Code</td>
                                                <td class="w-20">RFID</td>
                                                <td>Source</td>
                                                <td>Watt</td>
                                                <td>Cell Efficiency</td>
                                                <td>Bus Bar</td>
                                                <td>No of Cell Damage</td>
                                                <td>Operator</td>
                                                <td>Incharge</td>
                                                <td>Action</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($AllLaminatorLists as $item)
                                                @if ($item->status == '0')
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($item->ninetydeg_date)->format('d/m/Y') }}
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($item->ninetydeg_time)->format('h:i A') }}
                                                        </td>
                                                        <td>{{ $item->shiftdtl }}</td>
                                                        <td>{{ $item->ninetydeg_barcode ?? '-' }}</td>
                                                        <td>{{ $item->ninetydeg_rfid ?? '-' }}</td>
                                                        <td>{{ $item->ninetydeg_source ?? '-' }}</td>
                                                        <td>{{ $item->wattage ?? '-' }}</td>
                                                        <td>{{ $item->cell_efficiency ?? '-' }}</td>
                                                        <td>{{ $item->bus_bar ?? '-' }}</td>
                                                        <td>{{ $item->no_of_cell_damage ?? '-' }}</td>
                                                        <td>{{ $item->ninetydeg_operator ?? '-' }}</td>
                                                        <td>{{ $item->ninetydeg_incharge ?? '-' }}</td>
                                                        <td>
                                                            <a class="btn btn-primary btn-xs text-capitalize waves-effect waves-light"
                                                                href="{{ route('delamination-view', ['id' => $item->ninetydeg_id]) }}" role="button"><i class="mdi mdi-eye"></i>
                                                                View</a>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="navs-passed" role="tabpanel">
                                <div class="">
                                    <table
                                        class="d-block dataTable no-footer table table-bordered table-responsive text-nowrap w-100"
                                        id="example2">
                                        <thead class="table-secondary">
                                            <tr>
                                                <td>SL No</td>
                                                <td>Date</td>
                                                <td>Time</td>
                                                <td>Shift</td>
                                                <td class="w-20">Bar Code</td>
                                                <td class="w-20">RFID</td>
                                                <td>Source</td>
                                                <td>Watt</td>
                                                <td>Cell Efficiency</td>
                                                <td>Bus Bar</td>
                                                <td>No of Cell Damage</td>
                                                <td>Operator</td>
                                                <td>Incharge</td>
                                                <td>Action</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="navs-reject" role="tabpanel">
                                <div class="">
                                    <table
                                        class="d-block dataTable no-footer table table-bordered table-responsive text-nowrap w-100"
                                        id="example3">
                                        <thead class="table-secondary">
                                            <tr>
                                                <td>SL No</td>
                                                <td>Date</td>
                                                <td>Time</td>
                                                <td>Shift</td>
                                                <td class="w-20">Bar Code</td>
                                                <td class="w-20">RFID</td>
                                                <td>Source</td>
                                                <td>Watt</td>
                                                <td>Cell Efficiency</td>
                                                <td>Bus Bar</td>
                                                <td>No of Cell Damage</td>
                                                <td>Operator</td>
                                                <td>Incharge</td>
                                                <td>Action</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pageScript')
@endsection
