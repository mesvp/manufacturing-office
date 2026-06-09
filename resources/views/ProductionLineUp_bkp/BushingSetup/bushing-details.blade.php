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
                            <a href="{{ url('production-lineup/bushing-setup/ExportBushMaterial2') }}" class="btn btn-primary buttons-excel buttons-html5" type="button"><span><i class='fas fa-file-excel'></i> Excel</span></a>

                            <!--<a href="{{ url('production-lineup/bushing-setup/ExportBushMaterial') }}"-->
                            <!--    class="btn btn-primary buttons-excel buttons-html5" type="button"><span><i-->
                            <!--            class='fas fa-file-excel'></i> Excel</span></a>-->
                            <a href="{{ url('production-lineup/bushing-setup/pdfBushMaterial') }}"
                                class="btn btn-primary buttons-pdf buttons-html5" type="button"><span><i
                                        class='fas fa-file-pdf'></i> PDF</span></a>
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
                                        value="{{ isset($_GET['toDate']) ? $_GET['toDate'] : '' }}"
                                        placeholder="YYYY-MM-DD" class="form-control dob-picker">
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
                                    <a href="{{ url('production-lineup/bushing-setup/bushing-details') }}"><button
                                            type="button" class="btn btn-outline-success">Refresh</button></a>
                                </div>
                            </div>
                        </form>
                        <div class="clearfix"></div>
                        <table class="d-block dataTable no-footer table table-bordered table-responsive text-nowrap w-100"
                            id="example8">
                            <thead class="table-secondary">
                                <tr>
                                    <td rowspan="2">SL No</td>
                                    <td colspan="9" class="text-center">Layout</td>
                                    <td>Module Watt</td>
                                    <td colspan="2" class="text-center">Cell Efficiency & Brand</td>

                                    {{-- Dynamic Material Names --}}
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
                                @foreach ($AllLists as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->bushing_batchNo }}</td>
                                        <td>{{ $item->bushing_date }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->bushing_time)->format('h:i A') }}</td>
                                        <td>{{ $item->shiftdtl }}</td>
                                        <td>{{ $item->bushing_barCode }}</td>
                                        <td>{{ $item->bushing_rfid }}</td>
                                        <td>{{ $item->createdBy }}</td>
                                        <td>{{ $item->bushing_operator }}</td>  
                                        <td>{{ $item->bushing_incherge }}</td>
                                        <td>{{ $item->wattage }}</td>
                                        <td>{{ $item->brand }}</td>
                                        <td>{{ $item->cellSize }}</td>
                                        @foreach ($Allmats as $mat)
                                            @php
                                                $batchMaterials = $AllMatLists[$item->bushing_batchNo] ?? collect();
                                                $material = $batchMaterials->firstWhere('matId', $mat->id);
                                            @endphp
                                            <td class="text-center">{{ $material->qty ?? '-' }}</td>
                                            <td class="text-center">{{ $material->size ?? '-' }}</td>
                                            <td class="text-center">{{ $material->brand ?? '-' }}</td>
                                        @endforeach

                                        <td class="text-center">{{ $item->bushing_logo ?? '-' }}</td>
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

@section('pageScript')
    
@endsection
