@extends('includes.layout')

@section('pageHeading')
    EL & QC List Details
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
        <div class="card-header d-flex justify-content-between align-items-center py-1">
            <h5 class="mb-0">Production Set up /</span> EL & Layout Work</h5>
            <div class="text-end">
                <a href="{{ url('production-lineup/el_qc-rejected-excel-export') . '?' . http_build_query(request()->all()) }}"
                    class="btn btn-primary buttons-excel buttons-html5">
                    <span><i class='fas fa-file-excel'></i> Excel</span>
                </a>
                <!--<a href="{{ url('production-lineup/bushing-setup/ExportBushMaterial') }}"-->
                <!--    class="btn btn-primary buttons-excel buttons-html5" type="button"><span><i-->
                <!--            class='fas fa-file-excel'></i> Excel</span></a>-->
                <!--<a href="{{ url('production-lineup/el_qc-rejected-pdf-export') . '?' . http_build_query(request()->all()) }}"-->
                <!--    class="btn btn-primary buttons-pdf buttons-html5">-->
                <!--    <span><i class='fas fa-file-pdf'></i> PDF</span>-->
                <!--</a>-->
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-pills flex-column flex-md-row mb-3 gap-2 gap-lg-0" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link " href="{{ URL('production-lineup/el_qc-all') }}">
                            <i class="mdi mdi-account-outline mdi-20px me-1"></i>Pending
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="{{ URL('production-lineup/el_qc-all-passed') }}">
                            <i class="mdi mdi-account-box-outline mdi-20px me-1"></i>Passed
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ URL('production-lineup/el_qc-all-rejected') }}">
                            <i class="mdi mdi-cash-multiple mdi-20px me-1"></i>Reject
                        </a>
                    </li>
                </ul>
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center bg-label-primary py-2">
                        <h5 class="mb-0">EL & QC List Details</h5>
                        <!-- <div class="text-end">
                            <a href="{{ route('el-qc-add', ['page' => 'ALL']) }}" class="ms-2 btn  btn-primary btn-sm waves-effect waves-light"><span
                                    class="mdi mdi-playlist-plus me-1"></span> Add EL</a>
                        </div> -->
                    </div>
                    <div class="card-body">

                        <form>
                            <div class="col-md-12 row">
                                <div class="col-md-3 mb-1">
                                    <label for="email" class="form-label">Created By:</label>
                                    <select class="form-select select2" name="createdBy">
                                        <option value=''>Select an Employee</option>
                                        @foreach ($userList as $creator)
                                            @php
                                                if (isset($_GET['createdBy']) && $_GET['createdBy'] == $creator->id) {
                                                    $selected = 'selected';
                                                } else {
                                                    $selected = '';
                                                }
                                            @endphp
                                            <option value="{{ $creator->id }}" {{ $selected }}>{{ $creator->fullname }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label for="email" class="form-label">Operator:</label>
                                    <select class="form-select select2" name="operator">
                                        <option value=''>Select Operator</option>
                                        @foreach ($userList as $operator)
                                            @php
                                                if (isset($_GET['operator']) && $_GET['operator'] == $operator->id) {
                                                    $selected = 'selected';
                                                } else {
                                                    $selected = '';
                                                }
                                            @endphp
                                            <option value="{{ $operator->id }}" {{ $selected }}>
                                                {{ $operator->fullname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label for="email" class="form-label">Incharge:</label>
                                    <select class="form-select select2" name="checker">
                                        <option value=''>Select Incharge</option>
                                        @foreach ($userList as $checker)
                                            @php
                                                if (isset($_GET['checker']) && $_GET['checker'] == $checker->id) {
                                                    $selected = 'selected';
                                                } else {
                                                    $selected = '';
                                                }
                                            @endphp
                                            <option value="{{ $checker->id }}" {{ $selected }}>{{ $checker->fullname }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label for="email" class="form-label">Shift:</label>
                                    <select class="form-select select2" name="shift">
                                        <option value=''>Select Shift</option>
                                        @foreach ($ShiftMaster as $shift)
                                            @php
                                                if (isset($_GET['shift']) && $_GET['shift'] == $shift->id) {
                                                    $selected = 'selected';
                                                } else {
                                                    $selected = '';
                                                }
                                            @endphp
                                            <option value="{{ $shift->id }}" {{ $selected }}>{{ $shift->shift }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label for="email" class="form-label">From Date:</label>
                                    <input type="text" name="fromDate"
                                        value="{{ isset($_GET['fromDate']) ? $_GET['fromDate'] : date('Y-m-d', strtotime('-15 days')) }}"
                                        placeholder="YYYY-MM-DD" class="form-control dob-picker">
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label for="email" class="form-label">To Date:</label>
                                    <input type="text" name="toDate"
                                        value="{{ isset($_GET['toDate']) ? $_GET['toDate'] : date('Y-m-d') }}" placeholder="YYYY-MM-DD"
                                        class="form-control dob-picker">
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label for="email" class="form-label">Batch No:</label>
                                    <select class="form-select select2" name="batchNo">
                                        <option value=''>Select Batch No</option>
                                        @foreach ($batchList as $batch)
                                            @php
                                                if (
                                                    isset($_GET['batchNo']) &&
                                                    $_GET['batchNo'] == $batch->bushing_batchNo
                                                ) {
                                                    $selected = 'selected';
                                                } else {
                                                    $selected = '';
                                                }
                                            @endphp
                                            <option value="{{ $batch->bushing_batchNo }}" {{ $selected }}>
                                                {{ $batch->bushing_batchNo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-1 mt-4" style="text-align:right;">
                                    <button type="submit" class="btn btn-outline-primary">Search</button>
                                    <a href="{{ url('production-lineup/el_qc-all-rejected') }}"><button type="button"
                                            class="btn btn-outline-success">Refresh</button></a>
                                </div>
                            </div>
                        </form>

                        <p style="color:red;"><b>* Download Excel With 15 Days Records</b></p>

                        <div class="tab-content p-0">
                            <div class="tab-pane fade active show" id="navs-pendingElrework" role="tabpanel">
                                <div class="">
                                    <table
                                        class="d-block dataTable no-footer table table-bordered table-responsive text-nowrap w-100"
                                        id="">
                                        <thead class="table-secondary">
                                            <tr>
                                                <td>SL No</td>
                                                <td>Date</td>
                                                <td>Time</td>
                                                <td>Shift</td>
                                                <td class="w-20">Bar Code</td>
                                                <!--<td class="w-20">RFID</td>-->
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
                                            @foreach ($AllELQCLists as $item)
                                                
                                                    <tr>
                                                        <td>{{ $loop->iteration + ($AllELQCLists->currentPage() - 1) * $AllELQCLists->perPage() }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($item->elqc_date)->format('d/m/Y') }}
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($item->elqc_time)->format('h:i A') }}
                                                        </td>
                                                        <td>{{ $item->shiftdtl }}</td>
                                                        <td>{{ $item->elqc_barcode ?? '-' }}</td>
                                                        <!--<td>{{ $item->elqc_rfid ?? '-' }}</td>-->
                                                        <td>{{ $item->elqc_source ?? '-' }}</td>
                                                        <td>{{ $item->wattage ?? '-' }}</td>
                                                        <td>{{ $item->cellSize ?? '-' }}</td>
                                                        <td>{{ $item->bus_bar ?? '-' }}</td>
                                                        <td>{{ $item->no_of_cell_damage ?? '-' }}</td>
                                                        <td>{{ $item->elqc_operator_name ?? '-' }}</td>
                                                        <td>{{ $item->elqc_incharge_name ?? '-' }}</td>
                                                        <td>
                                                            <a class="btn btn-primary btn-xs text-capitalize waves-effect waves-light"
                                                                href="{{ route('el-qc-view', ['id' => $item->elqc_id]) }}?page=VIEW" role="button"><i class="mdi mdi-eye"></i>
                                                                View</a>
                                                        </td>
                                                    </tr>
                                                
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-center">
                                        {!! $AllELQCLists->links() !!}
                                    </div>
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
