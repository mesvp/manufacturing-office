@extends('includes.layout')

@section('pageHeading')
    Layout Damage Detailed Report
@endsection

@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center bg-label-primary py-1">
                        <h5 class="mb-0">Layout Damage Detailed Report :</h5>
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
                                    <a href="{{ url('production-lineup/bushing-setup/bushing-damage-report') }}"><button type="button"
                                            class="btn btn-outline-success">Refresh</button></a>
                                </div>
                            </div>
                        </form>
                        <table class="d-block dataTable no-footer table table-bordered table-responsive text-nowrap w-100"
                            id="example7">
                            <thead class="table-secondary">
                                <tr>
                                    <th>Sl.No.</th>
                                    <th>Layout No.</th>
                                    <th>Batch No.</th>
                                    <th>Bar Code No.</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Shift</th>
                                    <th>Operator</th>
                                    <th>Incharge</th>
                                    <th>Material Damage</th>
                                    <th>Qty</th>
                                    <th>UOM</th>
                                    <th>Rate</th>
                                    <th>Amount</th>
                                    <th>Reason</th>
                                    <th>Category</th>
                                    <th>Created By</th>
                                    <th>Operation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($AllLists as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ 'LAYOUT -'.$item->bushing_id }}</td>
                                        <td>{{ $item->bushing_batchNo }}</td>
                                        <td>{{ $item->bushing_barCode }}</td>
                                        <td>{{ $item->bushing_date }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->bushing_time)->format('h:i A') }}</td>
                                        <td>{{ $item->shiftdtl }}</td>
                                        <td>{{ $item->bushing_operator }}</td>
                                        <td>{{ $item->bushing_incherge }}</td>
                                        <td>{{ $item->matname }}</td>
                                        <td>{{ $item->dmgQty }}</td>
                                        <td>{{ $item->dmgUOM }}</td>
                                        <td>{{ $item->Basic_Amount_unit ?? '-' }}</td>
                                        <td>{{ $item->amount ?? '-' }}</td>
                                        <td>{{ $item->dmgReason }}</td>
                                        <td>{{ $item->dmgCategory }}</td>
                                        <td>{{ $item->createdBy }}</td>
                                        <td>
                                            <a href="{{ url('production-lineup/bushing-setup/view/' . $item->bushing_id) }}"
                                                class="btn btn-primary btn-xs text-capitalize waves-effect waves-light" role="button"><i class="mdi mdi-eye"></i>
                                                View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
