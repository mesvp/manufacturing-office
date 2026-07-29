@extends('includes.layout')

@section('pageHeading')
Barcode Scan Rport
@endsection

@section('content')
<!-- Content -->
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<script>
    $(document).ready(function() {
        setTimeout(function() {
            $('#success-alert').fadeOut('slow', function() {
                $(this).remove();
            });
        }, 10000);
    });
</script>
@endif
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card-header d-flex justify-content-between align-items-center py-1">
        <!-- <h5 class="mb-0">Dashboard /</span> Daily Production Report</h5> -->
        <div class="text-end">
            <!-- <a href="{{ url('production-lineup/el_qc-passed-excel-export') . '?' . http_build_query(request()->all()) }}"
                    class="btn btn-primary buttons-excel buttons-html5">
                    <span><i class='fas fa-file-excel'></i> Excel</span>
                </a> -->
            <!--<a href="{{ url('production-lineup/bushing-setup/ExportBushMaterial') }}"-->
            <!--    class="btn btn-primary buttons-excel buttons-html5" type="button"><span><i-->
            <!--            class='fas fa-file-excel'></i> Excel</span></a>-->
            <!--<a href="{{ url('production-lineup/el_qc-passed-pdf-export') . '?' . http_build_query(request()->all()) }}"-->
            <!--    class="btn btn-primary buttons-pdf buttons-html5">-->
            <!--    <span><i class='fas fa-file-pdf'></i> PDF</span>-->
            <!--</a>-->
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center bg-label-primary py-2">
                    <h5 class="mb-0">Reports /</span> Barcode Scan Report</h5>
                    <div class="text-end">
                        <a href="{{ url('production-lineup/report/barcode-scan-report-download') . '?' . http_build_query(request()->all()) }}"
                            class="btn btn-primary buttons-excel buttons-html5">
                            <span><i class='fas fa-file-excel'></i> Excel</span>
                        </a> 
                    </div>
                </div>
                <div class="card-body">

                    <form>
                        <div class="col-md-12 row">

                            <div class="col-md-3 mb-1">
                                <label for="email" class="form-label">From Date:</label>
                                <input type="date" name="fdate"
                                    value="{{ isset($_GET['fdate']) ? $_GET['fdate'] : date('Y-m-d', strtotime('-30 days')) }}" placeholder="YYYY-MM-DD"
                                    class="form-control">
                            </div>

                            <div class="col-md-3 mb-1">
                                <label for="email" class="form-label">To Date:</label>
                                <input type="date" name="tdate"
                                    value="{{ isset($_GET['tdate']) ? $_GET['tdate'] : date('Y-m-d') }}" placeholder="YYYY-MM-DD"
                                    class="form-control">
                            </div>
                            
                            <div class="col-md-3 mb-1">
                                <label for="email" class="form-label">Barcode:</label>
                                <input type="text" name="barcode"
                                    value="{{ isset($_GET['barcode']) ? $_GET['barcode'] : '' }}" placeholder="enter barcode"
                                    class="form-control">
                            </div>
                            
                            <div class="col-md-12" style="text-align:right;">
                                <button type="submit" class="btn btn-outline-primary">Search</button>
                                <a href="{{ url('production-lineup/report/barcode-scan-report') }}"><button type="button"
                                        class="btn btn-outline-success">Refresh</button></a>
                            </div>
                        </div>
                    </form>


                    <div class="tab-content p-0">
                        <div class="tab-pane fade active show" id="navs-pendingElrework" role="tabpanel">
                            <div class="">
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
                                <table
                                    class="d-block dataTable no-footer table table-bordered table-responsive text-nowrap w-100"
                                    id="" style="width:100%">
                                    <thead class="table-secondary">
                                        <tr>
                                            <td>SL No</td>
                                            <td>FQC Date</td>
                                            <td>Barcode</td>
                                            <td>Lay Up</td>
                                            <td>EL QC</td>
                                            <td>90 Degree QC</td>
                                            <td>Junction Box</td>
                                            <td>Final QC</td>
                                            <td>Total</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($AllLists as $key=>$dataList)
                                        <tr>
                                            <td>{{++$key}}</td>
                                            <td>{{$dataList->created_at}}</td>
                                            <td>{{$dataList->fqc_barcode}}</td>
                                            <td>{{ ($dataList->bushFlag==1)? 'YES' : 'NO' }}</td>
                                            <td>{{ ($dataList->elqcFlag==1)? 'YES' : 'NO' }}</td>
                                            <td>{{ ($dataList->ninetyFlag==1)? 'YES' : 'NO' }}</td>
                                            <td>{{ ($dataList->jbflag==1)? 'YES' : 'NO' }}</td>
                                            <td>{{ ($dataList->scan_flag==1)? 'YES' : 'NO' }}</td>
                                            <td>{{ $dataList->bushFlag + $dataList->elqcFlag + $dataList->ninetyFlag + $dataList->jbflag + $dataList->scan_flag }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="mt-4 d-flex justify-content-center">
                                    {{ $AllLists->appends(request()->query())->links() }}
                                </div>
                            </div>
                        </div>
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