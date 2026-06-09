@extends('layout.main')
@section('main-container')
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
<title>Dispatch Serial Numbers Report</title>

<style>
    .addbtn {
        display: flex;
        justify-content: flex-end;
        padding: 10px 12px;
        margin-top: -3%;
    }

    .addbtn i.fas.fa-file-excel {
        font-size: 20px;
        color: green;
        margin-top: 13px;
        margin-right: 10px;
    }
</style>

<div class="card">
    <div class="app-content">
        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <section class="section">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Dispatch Serial Numbers Report</li>
            </ol>
            <div class="addbtn extra pt-3">
                <form action="{{url('Report/exportdispatchdata_serial')}}" method="GET" style="display:inline;">
                    <input type="hidden" name="from_date" value="{{ request('from_date', $fromdate ?? '') }}">
                    <input type="hidden" name="to_date" value="{{ request('to_date', $todate ?? '') }}">
                    <input type="hidden" name="search" value="{{ request('search', $searchTerm ?? '') }}">
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    <input type="hidden" name="material_id" value="{{ request('material_id', $materialIdFilter ?? '') }}">
                    <button type="submit" style="background:none; border:none; cursor:pointer; padding:0; margin:0;" title="Export to Excel">
                        <i class="fas fa-file-excel" style="font-size: 16px; color: green; margin-top: 8px; margin-right: 8px;"></i>
                    </button>
                </form>
                <a href="{{ url('Report/sl_no_avlbl-report') }}" class="btn btn-info mr-1 btn-sm"> <i
                        class="fa fa-arrow-left"></i></a>
                <a href="{{ url('Report/sl_no_avlbl-report') }}" class="btn btn-info btn-sm"> <i
                        class="fa fa-home"></i></a>
            </div>

            <div class="row">
                <div class="container">
                    <form action="{{url('Report/dis_sl_no_avlbl-report')}}" method="GET">
                        <div class="row filter">
                            <div class="col-2 mb-3">
                                <label class="form-label">Date From</label>
                                <input type="date" name="from_date" value="{{ request('from_date', $fromdate ?? '') }}" class="form-control form-control-sm">
                            </div>
                            <div class="col-2 mb-3">
                                <label class="form-label">Date To</label>
                                <input type="date" name="to_date" value="{{ request('to_date', $todate ?? '') }}" class="form-control form-control-sm">
                            </div>
                            <div class="col-2 mb-3">
                                <label class="form-label">Material Master</label>
                                <select name="material_id" class="form-select form-select-sm">
                                    <option value="" {{ request('material_id') == '' ? 'selected' : '' }}>All Materials</option>
                                    @if(!empty($materialOptions) && $materialOptions->count() > 0)
                                        @foreach($materialOptions as $material)
                                            <option value="{{ $material->id }}" {{ request('material_id', $materialIdFilter ?? '') == $material->id ? 'selected' : '' }}>
                                                {{ $material->model_name ?? 'Unknown' }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-2 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select form-select-sm">
                                    <option value="" {{ request('status') == '' ? 'selected' : '' }}>All</option>
                                    <option value="APPROVE" {{ request('status') == 'APPROVE' ? 'selected' : '' }}>APPROVE</option>
                                    <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>PENDING</option>
                                    <option value="HOLD" {{ request('status') == 'HOLD' ? 'selected' : '' }}>HOLD</option>
                                    <option value="RECHECK" {{ request('status') == 'RECHECK' ? 'selected' : '' }}>RECHECK</option>
                                    <option value="REJECT" {{ request('status') == 'REJECT' ? 'selected' : '' }}>REJECT</option>
                                </select>
                            </div>
                            <div class="col-4 mb-3">
                                <label class="form-label">Search</label>
                                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search all dispatch records" value="{{ request('search', $searchTerm ?? '') }}">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                <a href="{{url('Report/dis_sl_no_avlbl-report')}}" class="btn btn-secondary"><i class="fa fa-refresh"></i></a>
                            </div>
                        </div>
                    </form>

                    <div class="row mb-3">
                        <div class="col-md-12 text-end">
                            <small class="text-muted">
                                Showing {{ $AvailableSerials->firstItem() ?? 0 }} to {{ $AvailableSerials->lastItem() ?? 0 }} of {{ $AvailableSerials->total() }} records
                                @if(!empty($searchTerm))
                                    (filtered by "{{ $searchTerm }}")
                                @endif
                            </small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered w-00">
                                <thead>
                                    <tr>
                                        <th class="th-sm">SL No</th>
                                        <th class="th-sm">Material Name</th>
                                        <th class="th-sm">Serial No</th>
                                        <th class="th-sm">HSN</th>
                                        <th class="th-sm">UOM</th>
                                        <th class="th-sm">Dispatch Date</th>
                                        <th class="th-sm">Invoice No</th>
                                        <th class="th-sm">Invoice Date</th>
                                        <th class="th-sm">Gatepass No</th>
                                        <th class="th-sm">Party Name</th>
                                        <th class="th-sm">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($AvailableSerials->count() > 0)
                                        @foreach($AvailableSerials as $key => $val)
                                        <tr>
                                            <td>{{ ($AvailableSerials->currentPage() - 1) * $AvailableSerials->perPage() + $key + 1 }}</td>
                                            <td>{{ $val->matname ?? 'N/A' }}</td>
                                            <td><strong style="color: blue;">{{ $val->serial_no }}</strong></td>
                                            <td>{{ $val->HSN_Code ?? 'N/A' }}</td>
                                            <td>{{ $val->UOM ?? 'N/A' }}</td>
                                            <td>{{ $val->invoice_date ? \Carbon\Carbon::parse($val->invoice_date)->format('d-m-Y') : 'N/A' }}</td>
                                            <td>{{ $val->invoice_no ?? 'N/A' }}</td>
                                            <td>{{ isset($val->actual_invoice_date) && $val->actual_invoice_date ? \Carbon\Carbon::parse($val->actual_invoice_date)->format('d-m-Y') : 'N/A' }}</td>
                                            <td>{{ $val->gatepass_no ?? 'N/A' }}</td>
                                            <td>{{ $val->party_name ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge {{ $val->status == 'APPROVE' ? 'bg-success' : ($val->status == 'REJECT' ? 'bg-danger' : ($val->status == 'HOLD' ? 'bg-warning' : ($val->status == 'RECHECK' ? 'bg-info' : 'bg-secondary'))) }}">
                                                    {{ $val->status ?? 'PENDING' }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="11" class="text-center text-danger fw-bolder">!!! NO DISPATCHED SERIAL NUMBERS FOUND !!!</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <nav aria-label="Page navigation">
                                <div class="d-flex justify-content-center">
                                    {{ $AvailableSerials->links('pagination::bootstrap-4') }}
                                </div>
                            </nav>
                            <div class="text-center mt-2">
                                <small class="text-muted">
                                    Page {{ $AvailableSerials->currentPage() }} of {{ $AvailableSerials->lastPage() }}
                                    ({{ $AvailableSerials->total() }} total records)
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
    <br><br>
</div>
@endsection

@push('custom-scripts')
<script>
$(document).ready(function() {
    activeclass(20, 6);
});
</script>
@endpush
