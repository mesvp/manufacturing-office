@extends('includes.layout')

@section('pageHeading')
    Daily Production Report
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
                        <h5 class="mb-0">Dashboard /</span> Daily Production Report</h5> 
                        <div class="text-end">
                            
                        </div>
                    </div>
                    <div class="card-body">

                        <form>
                            <div class="col-md-12 row">

                              <div class="col-md-3 mb-1">
                                <label for="email" class="form-label">Date:</label>
                                <input type="text" name="date"
                                  value="{{ isset($_GET['date']) ? $_GET['date'] : date('Y-m-d') }}" placeholder="YYYY-MM-DD"
                                  class="form-control dob-picker">
                              </div>
                                <div class="col-md-3 mb-1">
                                    <label for="email" class="form-label">Material Name:</label>
                                    <select class="form-select select2" name="material">
                                        <option value=''>Select an Material</option>
                                        @foreach ($FinishedGood as $mat)
                                            @php
                                                if (isset($_GET['material']) && $_GET['material'] == $mat['matId']) {
                                                    $selected = 'selected';
                                                } else {
                                                    $selected = '';
                                                }
                                            @endphp
                                            <option value="{{ $mat['matId'] }}" {{ $selected }}>{{ $mat['matName'] }}
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
                                <div class="col-md-12" style="text-align:right;">
                                    <button type="submit" class="btn btn-outline-primary">Search</button>
                                    <a href="{{ url('production-lineup/dashboard/daily-production') }}"><button type="button"
                                            class="btn btn-outline-success">Refresh</button></a>
                                </div>
                            </div>
                        </form>
                        @php
                          $totalMW = 0;
                          foreach($AllLists as $dL)
                          {
                            $totalMW = $totalMW+(((int)$dL->wattage/1000000)*$dL->totalMatNo);
                          }
                        @endphp
                        <div class="d-flex align-items-center gap-2 col-3 mb-2">
                          <label for="pswd" class="form-label mb-0" style="white-space: nowrap;">Total M.W.:</label>
                          <input type="text" class="form-control" value="{{$totalMW}}" disabled>
                        </div>

                        <div class="tab-content p-0">
                            <div class="tab-pane fade active show" id="navs-pendingElrework" role="tabpanel">
                                <div class="">
                                    <table
                                      class="d-block dataTable no-footer table table-bordered table-responsive text-nowrap w-100"
                                      id="example" style="width:100%">
                                      <thead class="table-secondary">
                                        <tr>
                                          <td>SL No</td>
                                          <td>Date</td>
                                          <td>Shift</td>
                                          <td class="w-20">Material</td>
                                          <td>Watt</td>
                                          <td>M.W.</td>
                                          <td>Total Production</td>
                                          <td>Total M.W.</td>
                                          <td>Shift Incharge</td>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        @foreach($AllLists as $key=>$dataList)
                                          <tr>
                                            <td>{{++$key}}</td>
                                            <td>{{$dataList->fqc_date}}</td>
                                            <td>{{$dataList->shift}}</td>
                                            <td class="w-20">{{$dataList->finish_good_name}}</td>
                                            <td>{{$dataList->wattage}}</td>
                                            <td>{{(int)$dataList->wattage/1000000}}</td>
                                            <td>{{$dataList->totalMatNo}}</td>
                                            <td>{{((int)$dataList->wattage/1000000)*$dataList->totalMatNo}}</td>
                                            <td>{{$dataList->fullname}}</td>
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
