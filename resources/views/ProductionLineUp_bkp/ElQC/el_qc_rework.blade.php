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
                    </div>
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
                                        <td>RFID</td>
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
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->elqc_date)->format('d/m/Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->elqc_time)->format('h:i A') }}</td>
                                            <td>{{ $item->shiftdtl ?? '-' }}</td>
                                            <td>{{ $item->elqc_barcode ?? '-' }}</td>
                                            <td>{{ $item->elqc_rfid ?? '-' }}</td>
                                            <td>{{ $item->elqc_source ?? '-' }}</td>
                                            <td>{{ $item->wattage ?? '-' }}</td>
                                            <td>{{ $item->cell_efficiency ?? '-' }}</td>
                                            <td>{{ $item->bus_bar ?? '-' }}</td>
                                            <td>{{ $item->no_of_cell_damage ?? '-' }}</td>
                                            <td>{{ $item->elqc_operator ?? '-' }}</td>
                                            <td>{{ $item->elqc_incharge ?? '-' }}</td>
                                            <td>
                                                <a href="{{ route('el-qc-view', ['id' => $item->elqc_id]) }}"
                                                    class="btn btn-primary btn-xs text-capitalize waves-effect waves-light"
                                                    role="button"><i class="mdi mdi-eye"></i>View</a>
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
    </div>
@endsection
