@extends('includes.layout')

@section('pageHeading')
    EL & QC Rework Detailed Report
@endsection

@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center bg-label-primary py-1">
                        <h5 class="mb-0">EL & QC Rework Detailed Report :</h5>
                        <a href="{{ url('production-lineup/el_qc_rework-excel-export') . '?' . http_build_query(request()->all()) }}"
                    class="btn btn-primary buttons-excel buttons-html5">
                    <span><i class='fas fa-file-excel'></i> Excel</span>
                </a>
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
                                        value="{{ isset($_GET['fromDate']) ? $_GET['fromDate'] : '' }}"
                                        placeholder="YYYY-MM-DD" class="form-control dob-picker">
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label for="email" class="form-label">To Date:</label>
                                    <input type="text" name="toDate"
                                        value="{{ isset($_GET['toDate']) ? $_GET['toDate'] : '' }}" placeholder="YYYY-MM-DD"
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
                                    <a href="{{ url('production-lineup/el_qc_rework') }}"><button type="button"
                                            class="btn btn-outline-success">Refresh</button></a>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="dataTable no-footer table table-bordered table-responsive text-nowrap w-100"
                                id="">
                                <thead class="table-secondary">
                                    <tr>
                                        <td>SL No</td>
                                        <td>Date</td>
                                        <td>Time</td>
                                        <td>Shift</td>
                                        <td>Bar Code</td>
                                        <!--<td>RFID</td>-->
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
                                    @foreach ($AllELQCReworkLists as $item)
                                        <tr>
                                            <td>{{ $loop->iteration + ($AllELQCReworkLists->currentPage() - 1) * $AllELQCReworkLists->perPage() }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->elqc_date)->format('d/m/Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->elqc_time)->format('h:i A') }}</td>
                                            <td>{{ $item->shiftdtl ?? '-' }}</td>
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
                                                <a href="{{ route('el-qc-view', ['id' => $item->elqc_id]) }}"
                                                    class="btn btn-primary btn-xs text-capitalize waves-effect waves-light"
                                                    role="button"><i class="mdi mdi-eye"></i>View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center">
                              {!! $AllELQCReworkLists->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
