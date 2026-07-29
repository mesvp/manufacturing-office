@extends('includes.layout')

@section('pageHeading')
    {{ $pageTitle }}
@endsection

@section('content')
    <!-- Content -->
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card-header d-flex justify-content-between align-items-center py-2">
            <h5><span class="text-muted fw-light"> Raw Material Report / </span> {{ $reportTitle }}</h5>
            <div class="mb-2 text-end">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center bg-label-primary py-2">
                        <h5 class="mb-0">{{ $pageTitle }}</h5>
                        
                    </div>
                    <div class="card-body">
                        <div class="tab-content p-0">
                            <div class="tab-pane fade active show" id="navs-pendingOp" role="tabpanel">
                                <div class="table-responsive">
                                    <table
                                        class=" dataTable no-footer table table-bordered table-responsive text-nowrap w-100"
                                        id="">
                                        <thead class="table-secondary">
                                            <tr>
                                                <td>SL No</td>
                                                <td>Batch No</td>
                                                <!-- <td>Barcode No</td> -->
                                                <td>Material Name</td>
                                                <td>Size</td>
                                                <td>Brand</td>
                                                <td>Quantity</td>
                                                <td>UOM</td>
                                                <td>BOM Material</td>
                                                <td>BOM Quantity</td>
                                                <td>Action</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($AllLists as $key=>$item)
                                                <tr>
                                                    <td>{{ ($AllLists->currentPage() - 1) * $AllLists->perPage() + $loop->iteration }}</td>
                                                    <td>{{ $item->batchNo }}</td>
                                                    <!-- <td>{{ $item->barCode }}</td> -->
                                                    <td>{{ $item->matName }}</td>
                                                    <td>{{ $item->size }}</td>
                                                    <td>{{ $item->brand }}</td>
                                                    <td>{{ $item->totQty }}</td>
                                                    <td>{{ $item->uom }}</td>
                                                    <td>{{ $item->bomMatName }}</td>
                                                    <td>{{ $item->bomQty }}</td>
                                                    <td>
                                                      <a href="{{ url($detailsLink.'?batchno='.$item->batchNo) }}">View Details</a>
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
            </div>
        </div>
    </div>
@endsection

@section('pageScript')
@endsection
