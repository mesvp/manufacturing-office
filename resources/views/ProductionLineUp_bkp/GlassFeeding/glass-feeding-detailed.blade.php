@extends('includes.layout')

@section('pageHeading')
    Glass Feeding Details Report
@endsection

@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center bg-label-primary py-1">
                        <h5 class="mb-0">Glass Feeding Detailed Report</h5>
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
                                                if(isset($_GET['createdBy']) && $_GET['createdBy'] == $creator->id){$selected = 'selected';}else{$selected = '';}
                                            @endphp
                                            <option value="{{ $creator->id }}" {{ $selected }}>{{ $creator->fullname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label for="email" class="form-label">Operator:</label>
                                    <select class="form-select select2" name="operator">
                                        <option value=''>Select Operator</option>
                                        @foreach ($userList as $operator)
                                            @php
                                                if(isset($_GET['operator']) && $_GET['operator'] == $operator->id){$selected = 'selected';}else{$selected = '';}
                                            @endphp
                                            <option value="{{ $operator->id }}" {{ $selected }}>{{ $operator->fullname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label for="email" class="form-label">Checker:</label>
                                    <select class="form-select select2" name="checker">
                                        <option value=''>Select Checker</option>
                                        @foreach ($userList as $checker)
                                            @php
                                                if(isset($_GET['checker']) && $_GET['checker'] == $checker->id){$selected = 'selected';}else{$selected = '';}
                                            @endphp
                                            <option value="{{ $checker->id }}" {{ $selected }}>{{ $checker->fullname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label for="email" class="form-label">Shift:</label>
                                    <select class="form-select select2" name="shift">
                                        <option value=''>Select Shift</option>
                                        @foreach ($ShiftMaster as $shift)
                                            @php
                                                if(isset($_GET['shift']) && $_GET['shift'] == $shift->id){$selected = 'selected';}else{$selected = '';}
                                            @endphp
                                            <option value="{{ $shift->id }}" {{ $selected }}>{{ $shift->shift }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label for="email" class="form-label">From Date:</label>
                                    <input type="text" name="fromDate" value="{{ isset($_GET['fromDate'])?$_GET['fromDate']:''; }}" placeholder="YYYY-MM-DD" class="form-control dob-picker">
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label for="email" class="form-label">To Date:</label>
                                    <input type="text" name="toDate" value="{{ isset($_GET['toDate'])?$_GET['toDate']:''; }}" placeholder="YYYY-MM-DD" class="form-control dob-picker">
                                </div>
                                <div class="col-md-6 mb-1 mt-4" style="text-align:right;">
                                    <button type="submit" class="btn btn-outline-primary">Search</button>
                                    <a href="{{url('production-lineup/glass-feeding-detailed')}}"><button type="button" class="btn btn-outline-success">Refresh</button></a>
                                </div>
                            </div>
                        </form>


                      <div class="">
                          <table class="d-block dataTable no-footer table table-bordered table-responsive text-nowrap w-100" id="example">
                              <thead class="table-secondary">
                                  <tr>
                                      <td>SL No</td>
                                      <td>Batch No.</td>
                                      <td>Date</td>
                                      <td>Shift</td>
                                      <td>Wattage</td>
                                      <td>Material</td>
                                      <td>Finished Good</td>
                                      <td>Size</td>
                                      <td>UOM</td>
                                      <td>Brand</td>
                                      <td>Time</td>
                                      <td>Production Qty</td>
                                      <td>UOM</td>
                                      <td>Reject Quantity</td>
                                      <td>UOM</td>
                                      <td>Reason</td>
                                      <td>Defect Category</td>
                                      <td>Added by</td>
                                      <td>Operator</td>
                                      <td>Checker</td>
                                      <td>Cteated On</td>
                                      <td>Status</td>
                                      <td>Approved by</td>
                                  </tr>
                              </thead>
                              <tbody>
                                @php $allCount = 1; @endphp
                                @foreach ($allList as $key=>$allDataList)
                                    <tr>
                                        <td>{{ $allCount++ }}</td>
                                        <td>{{ $allDataList->batchNo }}</td>
                                        <td>{{ date('d-m-Y', strtotime($allDataList->date)) }}</td>
                                        <td>{{ $allDataList->shiftdtl }}</td>
                                        <td>{{ $allDataList->wattage }}</td>
                                        <td>{{ $allDataList->title }}</td>
                                        <td>{{ $allDataList->matname }}</td>
                                        <td>{{ $allDataList->glassSize }}</td>
                                        <td>{{ $allDataList->uom }}</td>
                                        <td>{{ $allDataList->brand }}</td>
                                        <td>{{ date('H:i', strtotime($allDataList->time)) }}</td>
                                        <td>{{ $allDataList->productionQty }}</td>
                                        <td>{{ $allDataList->matUOM }}</td>
                                        <td>{{ $allDataList->RejectQty }}</td>
                                        <td>{{ $allDataList->matUOM }}</td>
                                        <td>{{ $allDataList->reason }}</td>
                                        <td>{{ $allDataList->defectCat }}</td>
                                        <td>{{ $allDataList->addByName }}</td>
                                        <td>{{ $allDataList->operatorName }}</td>
                                        <td>{{ $allDataList->checkerName }}</td>
                                        <td>{{ date('d-m-Y H:i:s', strtotime($allDataList->created_at)) }}</td>
                                        <td>
                                            @if ($allDataList->status == 0)
                                                <span class="badge bg-label-warning">Pending</span>
                                            @elseif ($allDataList->status == 1)
                                                <span class="badge bg-label-success">Approved</span>
                                            @endif
                                        </td>
                                        <td>{{ 'Approved By ' . $allDataList->actionByName }}</td>
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
