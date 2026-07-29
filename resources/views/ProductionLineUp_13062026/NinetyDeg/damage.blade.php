@extends('includes.layout')

@section('pageHeading')
    90deg QC Damage Report
@endsection

@section('content')
    <!-- Content -->
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card-header d-flex justify-content-between align-items-center py-2">
            <h5 class="mb-0">90deg QC Damage Report :</h5>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="dataTable no-footer table table-bordered table-responsive text-nowrap w-100"
                        id="example">
                        <thead class="table-secondary">
                            <tr>
                                <td>SL No</td>
                                <td>Date</td>
                                <td>Time</td>
                                <td>Shift</td>
                                <td>Bar Code</td>
                                <!--<td>RFID</td>-->
                                <!--<td>Source</td>-->
                                <td>Watt</td>
                                <td>Cell Efficiency</td>
                                <td>Bus Bar</td>
                                <td>Cell No</td>
                                <td>Cell Quantity</td>
                                <td>Defect Reason</td>
                                <td>Defect Category</td>
                                <td>Resp. Machine</td>
                                <td>No of Cell Damage</td>
                                <td>Responsible Person</td>
                                <td>Operator</td>
                                <td>Incharge</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($AllDamageLists as $item)
                              @if ($item->status == '0')
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->ninetydeg_date)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->ninetydeg_time)->format('h:i A') }}</td>
                                    <td>{{ $item->shiftdtl ?? '-' }}</td>
                                    <td>{{ $item->ninetydeg_barcode ?? '-' }}</td>
                                    <!--<td>{{ $item->laminator_rfid ?? '-' }}</td>-->
                                    <!--<td>{{ $item->ninetydeg_source ?? '-' }}</td>-->
                                    <td>{{ $item->wattage ?? '-' }}</td>
                                    <td>{{ $item->cellSize ?? '-' }}</td>
                                    <td>{{ $item->bus_bar ?? '-' }}</td>
                                    <td>{{ $item->cell_no ?? '-' }}</td>
                                    <td>{{ $item->cell_qty ?? '-' }}</td>
                                    <td>{{ $item->defectRsn ?? '-' }}</td>
                                    <td>{{ $item->defectCatgry ?? '-' }}</td>
                                    <td>{{ $item->res_machine ?? '-' }}</td>
                                    <td>{{ $item->no_of_cell_damage ?? '-' }}</td>
                                    <td>{{ $item->rsponsible_person ?? '-' }}</td>
                                    <td>{{ $item->ninetydeg_operator ?? '-' }}</td>
                                    <td>{{ $item->ninetydeg_incharge ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('90deg-qc-view', ['id' => $item->ninetydeg_id]) }}?page=VIEW"
                                            class="btn btn-primary btn-xs text-capitalize waves-effect waves-light"
                                            role="button"><i class="mdi mdi-eye"></i>View</a>
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
@endsection

