@extends('includes.layout')

@section('pageHeading')
    Layout Setup Report
@endsection

@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center bg-label-primary py-1">
                        <h5 class="mb-0">Layout Operator</h5>
                        <div class="text-end">
                            <a href="{{ route('bushing-setup-add') }}"
                                class="ms-2 btn  btn-primary btn-sm waves-effect waves-light"><span
                                    class="mdi mdi-playlist-plus me-1"></span> Add</a>
                            <a href="{{ url('production-lineup/bushing-setup/all-list-excel') . '?' . http_build_query(request()->all()) }}"
                                class="btn btn-primary buttons-excel buttons-html5">
                                <span><i class='fas fa-file-excel'></i> Excel</span>
                            </a>
                        </div>
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
                                    <a href="{{ url('production-lineup/bushing-setup') }}"><button type="button"
                                            class="btn btn-outline-success">Refresh</button></a>
                                </div>
                            </div>
                        </form>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <label for="per_page">Show </label>
                                <select id="per_page" onchange="changePerPage(this.value)" class="form-select d-inline-block w-auto">
                                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                    <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                                    <option value="200" {{ $perPage == 200 ? 'selected' : '' }}>200</option>
                                </select>
                                <label> records</label>
                            </div>
                        </div>
                        <table class="d-block dataTable no-footer table table-bordered table-responsive text-nowrap w-100"
                            id="">
                            <thead class="table table-secondary">
                                <tr>
                                    <td>SL No</td>
                                    <td>Date</td>
                                    <td>Time</td>
                                    <td>Shift</td>
                                    <td class="w-20">Bar Code</td>
                                    <!--<td class="w-20">RFID</td>-->
                                    <td>Wattage</td>
                                    <td>Cell Efficiency</td>
                                    <td>Batch No</td>
                                    <td>Added By</td>
                                    <td>Operator</td>
                                    <td>Incharge</td>
                                    <td>Operation</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($AllLists as $list)
                                    <tr>
                                        <td>{{ $loop->iteration + ($AllLists->currentPage() - 1) * $AllLists->perPage() }}</td>
                                        <td>{{ $list->bushing_date }}</td>
                                        <td>{{ \Carbon\Carbon::parse($list->bushing_time)->format('h:i A') }}</td>
                                        <td>{{ $list->shiftdtl }}</td>
                                        <td>{{ $list->bushing_barCode }}</td>
                                        <!--<td>{{ $list->bushing_rfid }}</td>-->
                                        <td>{{ $list->wattage }}</td>
                                        <td>{{ $list->cellSize }}</td>
                                        <td>{{ $list->bushing_batchNo }}</td>
                                        <td>{{ $list->createdBy_name }}</td>
                                        <td>{{ $list->bushing_operator_name??'' }}</td>
                                        <td>{{ $list->bushing_incherge_name }}</td>
                                        <td>
                                            <a class="btn btn-primary btn-xs text-capitalize waves-effect waves-light"
                                                href="{{ route('bushing-setup-view', ['id' => $list->bushing_id]) }}"
                                                role="button"><i class="mdi mdi-eye"></i>
                                                View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center">
                          {!! $AllLists->links() !!}
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    
<script>
function changePerPage(value) {
    // Get current URL details
    let url = new URL(window.location.href);
    
    // Set the new per_page value and reset page back to 1
    url.searchParams.set('per_page', value);
    url.searchParams.set('page', 1); 
    
    // Redirect to the updated URL
    window.location.href = url.href;
}
</script>
@endsection
