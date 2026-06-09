@extends('includes.layout')

@section('pageHeading')
    Glass Feeding My Pending for Approval
@endsection
<style>
    #example th:nth-child(11), #example td:nth-child(11), #example2 th:nth-child(11), #example2 td:nth-child(11), #example3 th:nth-child(11), #example3 td:nth-child(11), #example4 th:nth-child(11), #example4 td:nth-child(11), #example5 th:nth-child(11), #example5 td:nth-child(11), #example6 th:nth-child(11), #example6 td:nth-child(11) {
        min-width: 260px !important;
        white-space: normal !important;
    }
</style>
@section('content')
    <!-- Content -->
    <div class="container-fluid flex-grow-1 container-p-y">

        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-pills flex-column flex-md-row mb-3 gap-2 gap-lg-0" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="javascript:void(0);" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-requestPending" aria-controls="navs-purchaseorder" aria-selected="true">
                            <i class="mdi mdi-account-outline mdi-20px me-1"></i>Pending
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-requestRecheck" aria-controls="navs-sales-id" aria-selected="false">
                            <i class="mdi mdi-account-box-outline mdi-20px me-1"></i>Recheck
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" role="tab" data-bs-toggle="tab"
                            data-bs-target="#requestHold" aria-controls="navs-profit-id" aria-selected="false">
                            <i class="mdi mdi-pause-box-outline mdi-20px me-1"></i>Hold
                        </a>
                    </li>
                </ul>
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center bg-label-primary py-1">
                        <h5 class="mb-0">Glass Feeding Approval</h5>
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
                                    <a href="{{url('production-lineup/glass-feeding-approval-list')}}"><button type="button" class="btn btn-outline-success">Refresh</button></a>
                                </div>
                            </div>
                        </form>

                        <div class="tab-content p-0">
                            <div class="tab-pane fade active show" id="navs-requestPending" role="tabpanel">
                                <div class="">
                                    <table
                                        class="d-block dataTable no-footer table table-bordered table-responsive text-nowrap w-100"
                                        id="example">
                                        <thead class="table-secondary">
                                            <tr>
                                                <td>SL No</td>
                                                <td>Date</td>
                                                <td>Shift</td>
                                                <td>Wattage</td>
                                                <td>Size</td>
                                                <td>Production Qty</td>
                                                <td>Reject Quantity</td>
                                                <td>Added by</td>
                                                <td>Operator</td>
                                                <td>Checker</td>
                                                <td>Approved by</td>
                                                <td>Status</td>
                                                <td>Created On</td>
                                                <td>Operation</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $pendingCount = 1; @endphp
                                            @foreach ($allList as $key => $allDataList)
                                                @php
                                                    $approverListM = [];
                                                    foreach ($approverDetails as $approverM) {
                                                        if ($allDataList->stage == $approverM->stage_id) {
                                                            $approverListM[] = $approverM->person_id;
                                                        }
                                                    }
                                                @endphp

                                                @if (in_array($empId, $approverListM) && $allDataList->status == 0)
                                                    <tr>
                                                        <td>{{ $pendingCount++ }}</td>
                                                        <td>{{ date('d-m-Y', strtotime($allDataList->date)) }}</td>
                                                        <td>{{ $allDataList->shiftdtl }}</td>
                                                        <td>{{ $allDataList->wattage }}</td>
                                                        <td>{{ $allDataList->glassSize }}</td>
                                                        <td>{{ $allDataList->totalProductionQty }}</td>
                                                        <td>{{ $allDataList->totalRejectQty }}</td>
                                                        <td>{{ $allDataList->addByName }}</td>
                                                        <td>{{ $allDataList->operatorName }}</td>
                                                        <td>{{ $allDataList->checkerName }}</td>
                                                        <td>
                                                            @php
                                                                $approverList = [];
                                                                $person = '';
                                                                foreach ($approverDetails as $approver) {
                                                                    if ($allDataList->stage == $approver->stage_id) {
                                                                        $person = $person . $approver->Approver . ',';
                                                                        $approverList[] = $approver->person_id;
                                                                    }
                                                                }
                                                                if ($allDataList->status == 1) {
                                                                    echo 'Approved By ' . $allDataList->actionByName;
                                                                } elseif ($allDataList->status == 2) {
                                                                    echo 'Rechecked By ' . $allDataList->actionByName;
                                                                } elseif ($allDataList->status == 3) {
                                                                    echo 'Hold By ' . $allDataList->actionByName;
                                                                } elseif ($allDataList->status == 4) {
                                                                    echo 'Rejected By ' . $allDataList->actionByName;
                                                                } else {
                                                                    echo 'Pending with - ' . rtrim($person, ',');
                                                                }
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            @php
                                                                if ($allDataList->status == 1) {
                                                                    echo 'Approved';
                                                                } elseif ($allDataList->status == 2) {
                                                                    echo 'Rechecked';
                                                                } elseif ($allDataList->status == 3) {
                                                                    echo 'Hold';
                                                                } elseif ($allDataList->status == 4) {
                                                                    echo 'Rejected';
                                                                } elseif ($allDataList->status == 0) {
                                                                    echo $allDataList->stage_title . ' Pending.';
                                                                }
                                                            @endphp
                                                        </td>
                                                        <td>{{ date('d-m-Y H:i:s', strtotime($allDataList->created_at)) }}</td>
                                                        <td>
                                                            <div class="d-inline-block">
                                                                <a href="javascript:;"
                                                                    class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
                                                                    data-bs-toggle="dropdown">
                                                                    <i class="fa-solid fa-ellipsis-vertical fa-lg"></i>
                                                                </a>
                                                                <ul class="dropdown-menu dropdown-menu-end m-0">

                                                                    <li><a href="{{ url('production-lineup/glass-feeding-view/' . $allDataList->id . '?menu=glass-feeding-approval-list') }}"
                                                                            class="dropdown-item"><i
                                                                                class="mdi mdi-eye"></i> View</a></li>

                                                                    @if (in_array($empId, $approverList) && $allDataList->status == 0)
                                                                        <li><a href="{{ url('production-lineup/glass-feeding/approve/' . $allDataList->id) }}"
                                                                                class="dropdown-item"><i
                                                                                    class="mdi mdi-check"></i> Approval
                                                                                Action</a></li>
                                                                    @endif

                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="navs-requestRecheck" role="tabpanel">
                                <div class="">
                                    <table
                                        class="d-block dataTable no-footer table table-bordered table-responsive text-nowrap w-100"
                                        id="example2">
                                        <thead class="table-secondary">
                                            <tr>
                                                <td>SL No</td>
                                                <td>Date</td>
                                                <td>Shift</td>
                                                <td>Wattage</td>
                                                <td>Size</td>
                                                <td>Production Qty</td>
                                                <td>Reject Quantity</td>
                                                <td>Added by</td>
                                                <td>Operator</td>
                                                <td>Checker</td>
                                                <td>Approved by</td>
                                                <td>Status</td>
                                                <td>Created On</td>
                                                <td>Operation</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $recheckCount = 1; @endphp
                                            @foreach ($allList as $key => $allDataList)
                                                @if ($allDataList->status == 2 && $allDataList->actionBy == $empId)
                                                    <tr>
                                                        <td>{{ $recheckCount++ }}</td>
                                                        <td>{{ date('d-m-Y', strtotime($allDataList->date)) }}</td>
                                                        <td>{{ $allDataList->shiftdtl }}</td>
                                                        <td>{{ $allDataList->wattage }}</td>
                                                        <td>{{ $allDataList->glassSize }}</td>
                                                        <td>{{ $allDataList->totalProductionQty }}</td>
                                                        <td>{{ $allDataList->totalRejectQty }}</td>
                                                        <td>{{ $allDataList->addByName }}</td>
                                                        <td>{{ $allDataList->operatorName }}</td>
                                                        <td>{{ $allDataList->checkerName }}</td>
                                                        <td>
                                                            @php
                                                                $approverList = [];
                                                                $person = '';
                                                                foreach ($approverDetails as $approver) {
                                                                    if ($allDataList->stage == $approver->stage_id) {
                                                                        $person = $person . $approver->Approver . ',';
                                                                        $approverList[] = $approver->person_id;
                                                                    }
                                                                }
                                                                if ($allDataList->status == 1) {
                                                                    echo 'Approved By ' . $allDataList->actionByName;
                                                                } elseif ($allDataList->status == 2) {
                                                                    echo 'Rechecked By ' . $allDataList->actionByName;
                                                                } elseif ($allDataList->status == 3) {
                                                                    echo 'Hold By ' . $allDataList->actionByName;
                                                                } elseif ($allDataList->status == 4) {
                                                                    echo 'Rejected By ' . $allDataList->actionByName;
                                                                } else {
                                                                    echo 'Pending with - ' . rtrim($person, ',');
                                                                }
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            @php
                                                                if ($allDataList->status == 1) {
                                                                    echo 'Approved';
                                                                } elseif ($allDataList->status == 2) {
                                                                    echo 'Rechecked';
                                                                } elseif ($allDataList->status == 3) {
                                                                    echo 'Hold';
                                                                } elseif ($allDataList->status == 4) {
                                                                    echo 'Rejected';
                                                                } elseif ($allDataList->status == 0) {
                                                                    echo $allDataList->stage_title . ' Pending.';
                                                                }
                                                            @endphp
                                                        </td>
                                                        <td>{{ date('d-m-Y H:i:s', strtotime($allDataList->created_at)) }}</td>
                                                        <td>
                                                            <a class="btn btn-primary btn-xs text-capitalize waves-effect waves-light"
                                                                href="{{ url('production-lineup/glass-feeding-view/' . $allDataList->id . '?menu=glass-feeding-approval-list') }}"
                                                                role="button">
                                                                <i class="mdi mdi-eye"></i> View
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="requestHold" role="tabpanel">
                                <div class="">
                                    <table
                                        class="d-block dataTable no-footer table table-bordered table-responsive text-nowrap w-100"
                                        id="example3">
                                        <thead class="table-secondary">
                                            <tr>
                                                <td>SL No</td>
                                                <td>Date</td>
                                                <td>Shift</td>
                                                <td>Wattage</td>
                                                <td>Size</td>
                                                <td>Production Qty</td>
                                                <td>Reject Quantity</td>
                                                <td>Added by</td>
                                                <td>Operator</td>
                                                <td>Checker</td>
                                                <td>Approved by</td>
                                                <td>Status</td>
                                                <td>Created On</td>
                                                <td>Operation</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $holdCount = 1; @endphp
                                            @foreach ($allList as $key => $allDataList)
                                                @php
                                                    $approverListM = [];
                                                    foreach ($approverDetails as $approverM) {
                                                        if ($allDataList->stage == $approverM->stage_id) {
                                                            $approverListM[] = $approverM->person_id;
                                                        }
                                                    }
                                                @endphp

                                                @if (in_array($empId, $approverListM) && ($allDataList->actionBy == $empId && $allDataList->status == 3))
                                                    <tr>
                                                        <td>{{ $holdCount++ }}</td>
                                                        <td>{{ date('d-m-Y', strtotime($allDataList->date)) }}</td>
                                                        <td>{{ $allDataList->shiftdtl }}</td>
                                                        <td>{{ $allDataList->wattage }}</td>
                                                        <td>{{ $allDataList->glassSize }}</td>
                                                        <td>{{ $allDataList->totalProductionQty }}</td>
                                                        <td>{{ $allDataList->totalRejectQty }}</td>
                                                        <td>{{ $allDataList->addByName }}</td>
                                                        <td>{{ $allDataList->operatorName }}</td>
                                                        <td>{{ $allDataList->checkerName }}</td>
                                                        <td>
                                                            @php
                                                                $approverList = [];
                                                                $person = '';
                                                                foreach ($approverDetails as $approver) {
                                                                    if ($allDataList->stage == $approver->stage_id) {
                                                                        $person = $person . $approver->Approver . ',';
                                                                        $approverList[] = $approver->person_id;
                                                                    }
                                                                }
                                                                if ($allDataList->status == 1) {
                                                                    echo 'Approved By ' . $allDataList->actionByName;
                                                                } elseif ($allDataList->status == 2) {
                                                                    echo 'Rechecked By ' . $allDataList->actionByName;
                                                                } elseif ($allDataList->status == 3) {
                                                                    echo 'Hold By ' . $allDataList->actionByName;
                                                                } elseif ($allDataList->status == 4) {
                                                                    echo 'Rejected By ' . $allDataList->actionByName;
                                                                } else {
                                                                    echo 'Pending with - ' . rtrim($person, ',');
                                                                }
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            @php
                                                                if ($allDataList->status == 1) {
                                                                    echo 'Approved';
                                                                } elseif ($allDataList->status == 2) {
                                                                    echo 'Rechecked';
                                                                } elseif ($allDataList->status == 3) {
                                                                    echo 'Hold';
                                                                } elseif ($allDataList->status == 4) {
                                                                    echo 'Rejected';
                                                                } elseif ($allDataList->status == 0) {
                                                                    echo $allDataList->stage_title . ' Pending.';
                                                                }
                                                            @endphp
                                                        </td>
                                                        <td>{{ date('d-m-Y H:i:s', strtotime($allDataList->created_at)) }}</td>
                                                        <td>
                                                            <div class="d-inline-block">
                                                                <a href="javascript:;"
                                                                    class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
                                                                    data-bs-toggle="dropdown">
                                                                    <i class="fa-solid fa-ellipsis-vertical fa-lg"></i>
                                                                </a>
                                                                <ul class="dropdown-menu dropdown-menu-end m-0">

                                                                    <li><a href="{{ url('production-lineup/glass-feeding-view/' . $allDataList->id . '?menu=glass-feeding-approval-list') }}"
                                                                            class="dropdown-item"><i
                                                                                class="mdi mdi-eye"></i> View</a></li>

                                                                    @if ($allDataList->actionBy == $empId && $allDataList->status == 3)
                                                                        <li><a href="{{ url('production-lineup/glass-feeding/approve/' . $allDataList->id) }}"
                                                                                class="dropdown-item"><i
                                                                                    class="mdi mdi-check"></i> Approval
                                                                                Action</a></li>
                                                                    @endif
                                                                </ul>
                                                            </div>
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
                </div>
            </div>
        @endsection
