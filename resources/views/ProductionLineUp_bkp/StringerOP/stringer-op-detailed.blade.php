@extends('includes.layout')

@section('pageHeading')
    Stringer OP Detailed Report
@endsection
<style>
    #example th:nth-child(22), #example td:nth-child(22) {
        min-width: 260px !important;
        white-space: normal !important;
    }
</style>
@section('content')
    <div class="content-wrapper">
        <div class="container-fluid flex-grow-1 container-p-y">
            <div class="row">
                <div class="col-md-12">

                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center bg-label-primary py-12">
                            <h5 class="mb-0">Stringer OP Detailed Report :</h5>
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
                                                    if (
                                                        isset($_GET['createdBy']) &&
                                                        $_GET['createdBy'] == $creator->id
                                                    ) {
                                                        $selected = 'selected';
                                                    } else {
                                                        $selected = '';
                                                    }
                                                @endphp
                                                <option value="{{ $creator->id }}" {{ $selected }}>
                                                    {{ $creator->fullname }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-1">
                                        <label for="email" class="form-label">Operator:</label>
                                        <select class="form-select select2" name="operator">
                                            <option value=''>Select Operator</option>
                                            @foreach ($userList as $operator)
                                                @php
                                                    if (
                                                        isset($_GET['operator']) &&
                                                        $_GET['operator'] == $operator->id
                                                    ) {
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
                                        <label for="email" class="form-label">Checker:</label>
                                        <select class="form-select select2" name="checker">
                                            <option value=''>Select Checker</option>
                                            @foreach ($userList as $checker)
                                                @php
                                                    if (isset($_GET['checker']) && $_GET['checker'] == $checker->id) {
                                                        $selected = 'selected';
                                                    } else {
                                                        $selected = '';
                                                    }
                                                @endphp
                                                <option value="{{ $checker->id }}" {{ $selected }}>
                                                    {{ $checker->fullname }}</option>
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
                                                <option value="{{ $shift->id }}" {{ $selected }}>
                                                    {{ $shift->shift }}</option>
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
                                            value="{{ isset($_GET['toDate']) ? $_GET['toDate'] : '' }}"
                                            placeholder="YYYY-MM-DD" class="form-control dob-picker">
                                    </div>
                                    <div class="col-md-6 mb-1 mt-4" style="text-align:right;">
                                        <button type="submit" class="btn btn-outline-primary">Search</button>
                                        <a href="{{ url('production-lineup/stringer-op-detailed') }}"><button type="button"
                                                class="btn btn-outline-success">Refresh</button></a>
                                    </div>
                                </div>
                            </form>

                            <div class="tab-content p-0">
                                <table
                                    class="d-block dataTable no-footer table table-bordered table-responsive text-nowrap w-100"
                                    id="example">
                                    <thead class="table-secondary">
                                        <tr>
                                            <td>SL No</td>
                                            <td>Batch No</td>
                                            <td>Date</td>
                                            <td>Shift</td>
                                            <td>Plant No</td>
                                            <td>Time</td>
                                            <td>Stringer No</td>
                                            <td>Wattage</td>
                                            <td>Finished Good</td>
                                            <td>Material</td>
                                            <td>Cell Efficieny</td>
                                            <td>Cell Company Name</td>
                                            <td>Production Qty</td>
                                            <td>Reject Qty</td>
                                            <td>Defect Stage/Reason</td>
                                            <td>Defect Category</td>
                                            <td>Added By</td>
                                            <td>Operator</td>
                                            <td>Checked By</td>
                                            <td>Created At</td>
                                            <td>Status</td>
                                            <td>Approved By</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($AllLists as $key => $apprvDataList)
                                            @if ($apprvDataList->status == 1)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $apprvDataList->batchNo ?? '' }}</td>
                                                    <td>{{ date('d-m-Y', strtotime($apprvDataList->date)) }}</td>
                                                    <td>{{ $apprvDataList->shiftdtl ?? '' }}</td>
                                                    <td>{{ $apprvDataList->plant ?? '' }}</td>
                                                    <td>{{ $apprvDataList->time ?? '' }}</td>
                                                    <td>{{ $apprvDataList->stringerNo ?? '' }}</td>
                                                    <td>{{ $apprvDataList->wattage ?? '' }}</td>
                                                    <td>{{ $apprvDataList->matname ?? '' }}</td>
                                                    <td>{{ 'String' }}</td>
                                                    <td>{{ $apprvDataList->size ?? '' }}</td>
                                                    <td>{{ $apprvDataList->brand ?? '' }}</td>
                                                    <td>{{ $apprvDataList->totalProductionQty ?? '' }}</td>
                                                    <td>{{ $apprvDataList->totalRejectQty ?? '' }}</td>
                                                    <td>{{ $apprvDataList->reason ?? '' }}</td>
                                                    <td>{{ $apprvDataList->defectCat ?? '' }}</td>
                                                    <td>{{ $apprvDataList->addByName ?? '' }}</td>
                                                    <td>{{ $apprvDataList->operatorName ?? '' }}</td>
                                                    <td>{{ $apprvDataList->checkerName ?? '' }}</td>
                                                    <td>{{ date('d-m-Y H:i:s', strtotime($apprvDataList->created_at)) }}
                                                    </td>
                                                    <td>
                                                        @if ($apprvDataList->status == 1)
                                                            <span class="badge bg-label-success">Approved</span>
                                                        @else
                                                            <span class="badge bg-label-danger">Pending</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ 'Approved By ' . $apprvDataList->actionByName }}
                                                    </td>
                                                </tr>
                                            @endif
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
