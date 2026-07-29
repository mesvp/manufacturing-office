@extends('includes.layout')

@section('pageHeading')
    Laminator OP Rework Detailed Report
@endsection

@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center bg-label-primary py-1">
                        <h5 class="mb-0">Laminator OP Rework Detailed Report :</h5>
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
                                    @foreach ($AllOPReworkLists as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->laminator_date)->format('d/m/Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->laminator_time)->format('h:i A') }}</td>
                                            <td>{{ $item->shiftdtl ?? '-' }}</td>
                                            <td>{{ $item->laminator_barcode ?? '-' }}</td>
                                            <td>{{ $item->laminator_rfid ?? '-' }}</td>
                                            <td>{{ $item->laminator_source ?? '-' }}</td>
                                            <td>{{ $item->wattage ?? '-' }}</td>
                                            <td>{{ $item->cell_efficiency ?? '-' }}</td>
                                            <td>{{ $item->bus_bar ?? '-' }}</td>
                                            <td>{{ $item->no_of_cell_damage ?? '-' }}</td>
                                            <td>{{ $item->laminator_operator ?? '-' }}</td>
                                            <td>{{ $item->laminator_incharge ?? '-' }}</td>
                                            <td>
                                                <a href="{{ route('laminator-op-view', ['id' => $item->laminator_id]) }}"
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
