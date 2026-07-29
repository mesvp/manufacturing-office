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
                                        id="example">
                                        <thead class="table-secondary">
                                            <tr>
                                                <td>SL No</td>
                                                <td>Batch No</td>
                                                <td>Barcode No</td>
                                                <td>Status</td>
                                                <td>Material Name</td>
                                                <td>Size</td>
                                                <td>Brand</td>
                                                <td>Quantity</td>
                                                <td>UOM</td>
                                                <td>BOM Material</td>
                                                <td>BOM Quantity</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($AllLists as $key=>$item)
                                                <tr>
                                                    <td>{{ ++$key }}</td>
                                                    <td>{{ $item->batchNo }}</td>
                                                    <td>{{ $item->barCode }}</td>
                                                    <td>
                                                      @php
                                                       if($item->status == 1){
                                                        echo 'Passed';
                                                       }
                                                       else{
                                                          echo 'Damaged';
                                                       } 
                                                      @endphp 
                                                    </td>
                                                    <td>{{ $item->matName }}</td>
                                                    <td>{{ $item->size }}</td>
                                                    <td>{{ $item->brand }}</td>
                                                    <td>{{ $item->qty }}</td>
                                                    <td>{{ $item->uom }}</td>
                                                    <td>{{ $item->bomMatName }}</td>
                                                    <td>{{ $item->bomQty }}</td>
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
        </div>
    </div>
@endsection

@section('pageScript')
@endsection
