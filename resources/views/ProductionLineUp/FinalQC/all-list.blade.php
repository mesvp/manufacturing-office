@extends('includes.layout')

@section('pageHeading')
    Final QC Pending List
@endsection

@section('content')
    <!-- Content -->
                @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <script>
                    $(document).ready(function() {
                        setTimeout(function() {
                            $('#success-alert').fadeOut('slow', function() {
                                $(this).remove();
                            });
                        }, 10000);
                    });
                </script>
            @endif
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card-header d-flex justify-content-between align-items-center py-2">
            <h5><span class="text-muted fw-light"> Production Set up /</span> Final QC</h5>
            <div class="mb-2 text-end">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-pills flex-column flex-md-row mb-3 gap-2 gap-lg-0" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ request('tab', 'pending') == 'pending' ? 'active' : '' }}"
                           data-bs-toggle="tab"
                           href="#navs-pending">
                            Pending For Final QC
                        </a>
                    </li>
                     <li class="nav-item">
                        <a class="nav-link {{ request('tab') == 'passed' ? 'active' : '' }}"
                           data-bs-toggle="tab"
                           href="#navs-passed">
                            Passed
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('tab') == 'reject' ? 'active' : '' }}"
                           data-bs-toggle="tab"
                           href="#navs-reject">
                            Reject
                        </a>
                    </li>
                </ul>
                <div class="card">
                    <!--<div class="card-header d-flex justify-content-between align-items-center bg-label-primary py-2">-->
                    <!--    <h5 class="mb-0">Pending Final QC Lists</h5>-->
                    <!--    <div class="text-end">-->
                    <!--        <a href="{{ route('add-fqc', ['page' => 'ALL']) }}" class="ms-2 btn  btn-primary btn-sm waves-effect waves-light"><span-->
                    <!--                class="mdi mdi-playlist-plus me-1"></span> Add Final QC</a>-->
                    <!--    </div>-->
                    <!--</div>-->
                    <div class="card-body">
                        <div class="tab-content p-0">
                            <div class="tab-pane fade {{ request('tab', 'pending') == 'pending' ? 'show active' : '' }}"
                                 id="navs-pending">
                                <div class="">
                                    <table
                                        class="d-block no-footer table table-bordered table-responsive text-nowrap w-100"
                                        id="">
                                        <thead class="table-secondary">
                                            <tr>
                                                <td>SL No</td>
                                                <td>Date</td>
                                                <td>Time</td>
                                                <td>Shift</td>
                                                <td class="w-20">Bar Code</td>
                                                <!-- <td class="w-20">RFID</td> -->
                                                <td>Source</td>
                                                <td>Watt</td>
                                                <td>Cell Efficiency</td>
                                                <td>Bus Bar</td>
                                                <td>Operator</td>
                                                <td>Incharge</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($AllLists as $item)
                                                <tr>
                                                    <td>{{ ($AllLists->currentPage() - 1) * $AllLists->perPage() + $loop->iteration }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($item->jb_date)->format('d/m/Y') }}
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($item->jb_time)->format('h:i A') }}
                                                    </td>
                                                    <td>{{ $item->shiftdtl }}</td>
                                                    <td>{{ $item->jb_barcode ?? '-' }}</td>
                                                    <!-- <td>{{ $item->ninetydeg_rfid ?? '-' }}</td> -->
                                                    <td>{{ '90 Degree QC' }}</td>
                                                    <td>{{ $item->wattage ?? '-' }}</td>
                                                    <td>{{ $item->cellSize ?? '-' }}</td>
                                                    <td>{{ $item->bus_bar ?? '-' }}</td>
                                                    <td>{{ $item->jb_operator_name ?? '-' }}</td>
                                                    <td>{{ $item->jb_incharge_name ?? '-' }}</td>
                                                    
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-4 d-flex justify-content-center">
                                    {{ $AllLists->appends(['tab' => 'pending'])->links() }}
                                </div>
                            </div>
                            <div class="tab-pane fade {{ request('tab') == 'passed' ? 'show active' : '' }}" id="navs-passed">
                                <div class="">
                                    <table
                                        class="d-block no-footer table table-bordered table-responsive text-nowrap w-100"
                                        id="">
                                        <thead class="table-secondary">
                                            <tr>
                                                <td>SL No</td>
                                                <td>Date</td>
                                                <td>Time</td>
                                                <td>Shift</td>
                                                <td class="w-20">Bar Code</td>
                                                <td>Source</td>
                                                <td>Watt</td>
                                                <td>Bus Bar</td>
                                                <td>Status</td>
                                                <td>Operator</td>
                                                <td>Incharge</td>
                                                <td>Action</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($PassedLists as $item)
                                                    <tr>
                                                        <td>{{ ($PassedLists->currentPage() - 1) * $PassedLists->perPage() + $loop->iteration }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($item->fqc_date)->format('d/m/Y') }}
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($item->fqc_time)->format('h:i A') }}
                                                        </td>
                                                        <td>{{ $item->shiftdtl }}</td>
                                                        <td>{{ $item->fqc_barcode ?? '-' }}</td>
                                                        <td>{{ $item->fqc_source ?? '-' }}</td>
                                                        <td>{{ $item->wattage ?? '-' }}</td>
                                                        <td>{{ $item->bus_bar ?? '-' }}</td>
                                                        <td>{{ ($item->status == 1)?'Passed' : 'Passed With Damage' }}</td>
                                                        <td>{{ $item->fqc_operator_name ?? '-' }}</td>
                                                        <td>{{ $item->fqc_incharge_name ?? '-' }}</td>
                                                        <td>
                                                            <a class="btn btn-primary btn-xs text-capitalize waves-effect waves-light"
                                                                href="{{ route('fqc-view', ['id' => $item->fqc_id]) }}?page=VIEW" role="button"><i class="mdi mdi-eye"></i>
                                                                View</a>
                                                        </td>
                                                    </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-4 d-flex justify-content-center">
                                    {{ $PassedLists->appends(['tab' => 'passed'])->links() }}
                                </div>
                            </div>
                            <div class="tab-pane fade {{ request('tab') == 'reject' ? 'show active' : '' }}" id="navs-reject">
                                <div class="">
                                    <table
                                        class="d-block no-footer table table-bordered table-responsive text-nowrap w-100"
                                        id="">
                                        <thead class="table-secondary">
                                            <tr>
                                                <td>SL No</td>
                                                <td>Date</td>
                                                <td>Time</td>
                                                <td>Shift</td>
                                                <td class="w-20">Bar Code</td>
                                                <td>Source</td>
                                                <td>Watt</td>
                                                <td>Bus Bar</td>
                                                <td>Operator</td>
                                                <td>Incharge</td>
                                                <td>Action</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($RejectLists as $item)
                                                    <tr>
                                                        <td>{{ ($RejectLists->currentPage() - 1) * $RejectLists->perPage() + $loop->iteration }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($item->fqc_date)->format('d/m/Y') }}
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($item->fqc_time)->format('h:i A') }}
                                                        </td>
                                                        <td>{{ $item->shiftdtl }}</td>
                                                        <td>{{ $item->fqc_barcode ?? '-' }}</td>
                                                        <td>{{ $item->fqc_source ?? '-' }}</td>
                                                        <td>{{ $item->wattage ?? '-' }}</td>
                                                        <td>{{ $item->bus_bar ?? '-' }}</td>
                                                        <td>{{ $item->fqc_operator_name ?? '-' }}</td>
                                                        <td>{{ $item->fqc_incharge_name ?? '-' }}</td>
                                                        <td>
                                                            <a class="btn btn-primary btn-xs text-capitalize waves-effect waves-light"
                                                                href="{{ route('fqc-view', ['id' => $item->fqc_id]) }}?page=VIEW" role="button"><i class="mdi mdi-eye"></i>
                                                                View</a>
                                                        </td>
                                                    </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-4 d-flex justify-content-center">
                                    {{ $RejectLists->appends(['tab' => 'reject'])->links() }}
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
