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
                        <h5 class="mb-0">Layout Detail View</h5>
                        <div class="text-end">
                            <a href="{{ url('production-lineup/bushing-setup/ExportBushMaterial2') . '?' . http_build_query(request()->all()) }}"
                               class="btn btn-primary buttons-excel buttons-html5">
                                <span><i class='fas fa-file-excel'></i> Excel</span>
                            </a>
                            <a href="{{ url('production-lineup/bushing-setup/pdfBushMaterial') . '?' . http_build_query(request()->all()) }}"
                               class="btn btn-primary buttons-pdf buttons-html5">
                                <span><i class='fas fa-file-pdf'></i> PDF</span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="col-md-12 row">
                                <div class="col-md-3 mb-1">
                                    <label class="form-label">Created By:</label>
                                    <select class="form-select select2" name="createdBy">
                                        <option value=''>Select an Employee</option>
                                        @foreach ($userList as $creator)
                                            <option value="{{ $creator->id }}" {{ request('createdBy') == $creator->id ? 'selected' : '' }}>
                                                {{ $creator->fullname }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label class="form-label">Operator:</label>
                                    <select class="form-select select2" name="operator">
                                        <option value=''>Select Operator</option>
                                        @foreach ($userList as $operator)
                                            <option value="{{ $operator->id }}" {{ request('operator') == $operator->id ? 'selected' : '' }}>
                                                {{ $operator->fullname }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label class="form-label">Incharge:</label>
                                    <select class="form-select select2" name="checker">
                                        <option value=''>Select Incharge</option>
                                        @foreach ($userList as $checker)
                                            <option value="{{ $checker->id }}" {{ request('checker') == $checker->id ? 'selected' : '' }}>
                                                {{ $checker->fullname }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label class="form-label">Shift:</label>
                                    <select class="form-select select2" name="shift">
                                        <option value=''>Select Shift</option>
                                        @foreach ($ShiftMaster as $shift)
                                            <option value="{{ $shift->id }}" {{ request('shift') == $shift->id ? 'selected' : '' }}>
                                                {{ $shift->shift }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label class="form-label">From Date:</label>
                                    <input type="text" name="fromDate"
                                        value="{{ request('fromDate') }}"
                                        placeholder="YYYY-MM-DD" class="form-control dob-picker">
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label class="form-label">To Date:</label>
                                    <input type="text" name="toDate"
                                        value="{{ request('toDate') }}"
                                        placeholder="YYYY-MM-DD" class="form-control dob-picker">
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label class="form-label">Batch No:</label>
                                    <select class="form-select select2" name="batchNo">
                                        <option value=''>Select Batch No</option>
                                        @foreach ($batchList as $batch)
                                            <option value="{{ $batch->bushing_batchNo }}" {{ request('batchNo') == $batch->bushing_batchNo ? 'selected' : '' }}>
                                                {{ $batch->bushing_batchNo }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-1 mt-4" style="text-align:right;">
                                    <button type="submit" class="btn btn-outline-primary">Search</button>
                                    <a href="{{ url('production-lineup/bushing-setup/bushing-details') }}">
                                        <button type="button" class="btn btn-outline-success">Refresh</button>
                                    </a>
                                </div>
                            </div>
                        </form>
                        <div class="clearfix"></div>
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
                            id="example85">
                            <thead class="table-secondary">
                                <tr>
                                    <td rowspan="2">SL No</td>
                                    <td colspan="9" class="text-center">Bushing</td>
                                    <td>Module Watt</td>
                                    <td colspan="2" class="text-center">Cell Efficiency & Brand</td>

                                    @foreach ($Allmats as $mat)
                                        <td colspan="3" class="text-center">{{ $mat->mname }}</td>
                                    @endforeach

                                    <td rowspan="2">Logo</td>
                                </tr>
                                <tr>
                                    <td>Batch No</td>
                                    <td>Date</td>
                                    <td>Time</td>
                                    <td>Shift</td>
                                    <td>Bar Code</td>
                                    <td>RFID</td>
                                    <td>Created By</td>
                                    <td>Operator</td>
                                    <td>Incharge</td>
                                    <td>Watt</td>
                                    <td>Brand</td>
                                    <td>Efficiency</td>
                                    @foreach ($Allmats as $mat)
                                        <td>Qty</td>
                                        <td>Size</td>
                                        <td>Brand</td>
                                    @endforeach
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($AllLists as $item)
                                    <tr>
                                        <td>{{ ($AllLists->currentPage() - 1) * $AllLists->perPage() + $loop->iteration }}</td>
                                        <td>{{ $item->bushing_batchNo }}</td>
                                        <td>{{ $item->bushing_date }}</td>
                                        <td>
                                            @if (!empty($item->bushing_time))
                                                {{ \Carbon\Carbon::parse($item->bushing_time)->format('h:i A') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $item->shiftdtl ?? '-' }}</td>
                                        <td>{{ $item->bushing_barCode ?? '-' }}</td>
                                        <td>{{ $item->bushing_rfid ?? '-' }}</td>
                                        <td>{{ $item->createdBy ?? '-' }}</td>
                                        <td>{{ $item->bushing_operator ?? '-' }}</td>
                                        <td>{{ $item->bushing_incherge ?? '-' }}</td>
                                        <td>{{ $item->wattage ?? '-' }}</td>
                                        <td>{{ $item->brand ?? '-' }}</td>
                                        <td>{{ $item->cellSize ?? '-' }}</td>
                                        @foreach ($Allmats as $mat)
                                            @php
                                                $batchMaterials = $AllMatLists[$item->bushing_batchNo] ?? collect();
                                                $material = $batchMaterials->firstWhere('matId', $mat->id);
                                            @endphp
                                            <td>{{ $material->qty ?? '-' }}</td>
                                            <td>{{ $material->size ?? '-' }}</td>
                                            <td>{{ $material->brand ?? '-' }}</td>
                                        @endforeach

                                        <td>{{ $item->bushing_logo ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center">No records found.</td>
                                    </tr>
                                @endforelse
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

@section('pageScript')
@endsection