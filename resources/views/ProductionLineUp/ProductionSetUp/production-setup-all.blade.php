@extends('includes.layout')

@section('pageHeading')
    Product List
@endsection
<style>
    #example th:nth-child(11), #example td:nth-child(11), #example2 th:nth-child(11), #example2 td:nth-child(11), #example3 th:nth-child(11), #example3 td:nth-child(11), #example4 th:nth-child(11), #example4 td:nth-child(11), #example5 th:nth-child(11), #example5 td:nth-child(11), #example6 th:nth-child(11), #example6 td:nth-child(11) {
        min-width: 260px !important;
        white-space: normal !important;
    }
</style>
@section('content')

    <div class="container-fluid flex-grow-1 container-p-y">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    {{ session('success') }}
                </div>
            @endif
            @if (session('failed'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    {{ session('failed') }}
                </div>
            @endif
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-pills flex-column flex-md-row mb-3 gap-2 gap-lg-0" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="javascript:void(0);" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-purchaseorder" aria-controls="navs-purchaseorder" aria-selected="true">
                            <i class="mdi mdi-account-outline mdi-20px me-1"></i>All
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-pendingforapv" aria-controls="navs-sales-id" aria-selected="false">
                            <i class="mdi mdi-account-box-outline mdi-20px me-1"></i>Pending
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-apv" aria-controls="navs-profit-id" aria-selected="false">
                            <i class="mdi mdi-cash-multiple mdi-20px me-1"></i>Recheck
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-pendingpo" aria-controls="navs-profit-id" aria-selected="false">
                            <i class="mdi mdi-bookmark-outline mdi-20px me-1"></i>Reject
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-completedpo" aria-controls="navs-profit-id" aria-selected="false">
                            <i class="mdi mdi-checkbox-multiple-marked mdi-20px me-1"></i>Approved
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-holdpo" aria-controls="navs-profit-id" aria-selected="false">
                            <i class="mdi mdi-pause-box-outline mdi-20px me-1"></i>Hold
                        </a>
                    </li>
                </ul>
                <div class="card">
                    <!-- <div class="card-header d-flex justify-content-between align-items-center bg-label-primary py-1">
                        <h5><span class="text-muted fw-light">Factory /</span> Production Setup</h5>
                        <div class="text-end">
                            <a href="{{ url('production-lineup/production-setup/add-production') }}"
                                class="ms-2 btn  btn-primary btn-sm waves-effect waves-light"><span
                                    class="mdi mdi-playlist-plus me-1"></span> Add Production</a>
                        </div>
                    </div> -->
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
                                    <label for="btch" class="form-label">Batch No:</label>
                                    <select class="form-select select2" name="batchno" id="btch">
                                        <option value=''>Select BatchNo</option>
                                        @foreach ($batchList as $batch)
                                            @php
                                                if(isset($_GET['batchno']) && $_GET['batchno'] == $batch->batchNo){$selected = 'selected';}else{$selected = '';}
                                            @endphp
                                            <option value="{{ $batch->batchNo }}" {{ $selected }}>{{ $batch->batchNo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label for="plnt" class="form-label">Plant No:</label>
                                    <select class="form-select select2" name="plantno" id="plnt">
                                        <option value=''>Select PlantNo</option>
                                        @foreach ($PlantMaster as $plant)
                                            @php
                                                if(isset($_GET['plantno']) && $_GET['plantno'] == $plant->mstr_type_name){$selected = 'selected';}else{$selected = '';}
                                            @endphp
                                            <option value="{{ $plant->mstr_type_name }}" {{ $selected }}>{{ $plant->mstr_type_name }}</option>
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
                                    <a href="{{url('production-lineup/production-setup')}}"><button type="button" class="btn btn-outline-success">Refresh</button></a>
                                </div>
                            </div>
                        </form>
                        
                        <div class="tab-content p-0">
                            <div class="tab-pane fade active show" id="navs-purchaseorder" role="tabpanel">
                                <div class="">
                                    <table
                                        class="d-block dataTable no-footer table table-bordered table-responsive text-nowrap w-100"
                                        id="example">
                                        <thead class="table-secondary"> <!-- All List -->
                                            <tr>
                                                <td>SL No</td>
                                                <td>Batch No.</td>
                                                <td>Plant No.</td>
                                                <td>Start Dt.</td>
                                                <td>Start Shift</td>
                                                <td>Wattage</td>
                                                <td>Cell Efficiency</td>
                                                <td>Cell Company</td>
                                                <td>Created By</td>
                                                <td>Status</td>
                                                <td>Status Pending At</td>
                                                <td>Created At</td>
                                                <td>Action</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($AllLists as $key => $allDataList)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $allDataList->batchNo }}</td>
                                                    <td>{{ $allDataList->plantNo }}</td>
                                                    <td>{{ date('d-m-Y', strtotime($allDataList->startDate)) }}</td>
                                                    <td>{{ $allDataList->ShiftName }}</td>
                                                    <td>{{ $allDataList->wattage }}</td>
                                                    <td>{{ $allDataList->efficiency }}</td>
                                                    <td>{{ $allDataList->brand }}</td>
                                                    <td>{{ $allDataList->createdByName }}</td>
                                                    <td>
                                                        @php
                                                            if ($allDataList->status == 1) {
                                                                echo 'Approved';
                                                            } elseif ($allDataList->status == 4) {
                                                                echo 'Rejected';
                                                            } elseif ($allDataList->status == 3) {
                                                                echo 'Hold';
                                                            } elseif ($allDataList->status == 2) {
                                                                echo 'Recheck';
                                                            } elseif ($allDataList->status == 0) {
                                                                echo $allDataList->stage_title . ' Pending.';
                                                            }
                                                        @endphp
                                                    </td>
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
                                                            } elseif ($allDataList->status == 4) {
                                                                echo 'Rejected By ' . $allDataList->actionByName;
                                                            } elseif ($allDataList->status == 3) {
                                                                echo 'Hold By ' . $allDataList->actionByName;
                                                            } elseif ($allDataList->status == 2) {
                                                                echo 'Recheck By ' . $allDataList->actionByName;
                                                            } else {
                                                                echo 'Pending with - ' . rtrim($person, ',');
                                                            }
                                                        @endphp
                                                    </td>
                                                    <td>{{ date('d-m-Y H:i:s', strtotime($allDataList->created_at)) }}</td>
                                                    <td>
                                                        @if (($allDataList->appr_process == 0 && $allDataList->status == 0 && $allDataList->created_by == $empId) || ($allDataList->status == 2 && $allDataList->created_by == $empId))
                                                            <a class="btn btn-warning btn-xs text-capitalize waves-effect waves-light"
                                                                href="{{ route('production-setup-form-update', ['batchNo' => $allDataList->batchNo]) }}"
                                                                role="button"><i class="mdi mdi-pencil"></i> Edit</a>
                                                        @endif
                                                        <a class="btn btn-primary btn-xs text-capitalize waves-effect waves-light"
                                                            href="{{ url('production-lineup/production-setup/view-details/' . $allDataList->batchNo) }}"
                                                            role="button"><i class="mdi mdi-eye"></i> View</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- PENDING -->
                            <div class="tab-pane fade" id="navs-pendingforapv" role="tabpanel">
                                <div class="">
                                    <table
                                        class="d-block dataTable no-footer table table-bordered table-responsive text-nowrap w-100"
                                        id="example2">
                                        <thead class="table-secondary"> <!-- Pending List -->
                                            <tr>
                                                <td>SL No</td>
                                                <td>Batch No.</td>
                                                <td>Plant No.</td>
                                                <td>Start Dt.</td>
                                                <td>Start Shift</td>
                                                <td>Wattage</td>
                                                <td>Cell Efficiency</td>
                                                <td>Cell Company</td>
                                                <td>Created By</td>
                                                <td>Status</td>
                                                <td>Status Pending At</td>
                                                <td>Created At</td>
                                                <td>Action</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $pendingCount = 1; @endphp
                                            @foreach ($AllLists as $key => $pendDataList)
                                                @if ($pendDataList->status == 0)
                                                    <tr>
                                                        <td>{{ $pendingCount++ }}</td>
                                                        <td>{{ $pendDataList->batchNo }}</td>
                                                        <td>{{ $pendDataList->plantNo }}</td>
                                                        <td>{{ date('d-m-Y', strtotime($pendDataList->startDate)) }}</td>
                                                        <td>{{ $pendDataList->ShiftName }}</td>
                                                        <td>{{ $pendDataList->wattage }}</td>
                                                        <td>{{ $pendDataList->efficiency }}</td>
                                                        <td>{{ $pendDataList->brand }}</td>
                                                        <td>{{ $allDataList->createdByName }}</td>
                                                        <td>
                                                            @php
                                                                if ($pendDataList->status == 1) {
                                                                    echo 'Approved';
                                                                } elseif ($pendDataList->status == 4) {
                                                                    echo 'Rejected';
                                                                } elseif ($pendDataList->status == 3) {
                                                                    echo 'Hold';
                                                                } elseif ($pendDataList->status == 2) {
                                                                    echo 'Recheck';
                                                                } elseif ($pendDataList->status == 0) {
                                                                    echo $pendDataList->stage_title . ' Pending.';
                                                                }
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            @php
                                                                $approverList = [];
                                                                $person = '';
                                                                foreach ($approverDetails as $approver) {
                                                                    if ($pendDataList->stage == $approver->stage_id) {
                                                                        $person = $person . $approver->Approver . ',';
                                                                        $approverList[] = $approver->person_id;
                                                                    }
                                                                }
                                                                if ($pendDataList->status == 1) {
                                                                    echo 'Approved By ' . $pendDataList->actionByName;
                                                                } elseif ($pendDataList->status == 4) {
                                                                    echo 'Rejected By ' . $pendDataList->actionByName;
                                                                } elseif ($pendDataList->status == 3) {
                                                                    echo 'Hold By ' . $pendDataList->actionByName;
                                                                } elseif ($pendDataList->status == 2) {
                                                                    echo 'Recheck By ' . $pendDataList->actionByName;
                                                                } else {
                                                                    echo 'Pending with - ' . rtrim($person, ',');
                                                                }
                                                            @endphp
                                                        </td>
                                                        <td>{{ date('d-m-Y H:i:s', strtotime($allDataList->created_at)) }}</td>
                                                        <td>
                                                            @if ($pendDataList->appr_process == 0 && $pendDataList->status == 0 && $pendDataList->created_by == $empId)
                                                                <a class="btn btn-warning btn-xs text-capitalize waves-effect waves-light"
                                                                    href="{{ route('production-setup-form-update', ['batchNo' => $pendDataList->batchNo]) }}"
                                                                    role="button"><i class="mdi mdi-pencil"></i> Edit</a>
                                                            @endif
                                                            <a href="{{ url('production-lineup/production-setup/view-details/' . $pendDataList->batchNo) }}"
                                                                class="btn btn-primary btn-xs text-capitalize waves-effect waves-light"
                                                                role="button"><i class="mdi mdi-eye"></i>
                                                                View</a>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- RECHECK -->
                            <div class="tab-pane fade" id="navs-apv" role="tabpanel">
                                <div class="">
                                    <table
                                        class="d-block dataTable no-footer table table-bordered table-responsive text-nowrap w-100"
                                        id="example3">
                                        <thead class="table-secondary"> <!-- Recheck List -->
                                            <tr>
                                                <td>SL No</td>
                                                <td>Batch No.</td>
                                                <td>Plant No.</td>
                                                <td>Start Dt.</td>
                                                <td>Start Shift</td>
                                                <td>Wattage</td>
                                                <td>Cell Efficiency</td>
                                                <td>Cell Company</td>
                                                <td>Created By</td>
                                                <td>Status</td>
                                                <td>Status Pending At</td>
                                                <td>Created At</td>
                                                <td>Action</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $recheckCount = 1; @endphp
                                            @foreach ($AllLists as $key => $recheckDataList)
                                                @if ($recheckDataList->status == 2)
                                                    <tr>
                                                        <td>{{ $recheckCount++ }}</td>
                                                        <td>{{ $recheckDataList->batchNo }}</td>
                                                        <td>{{ $recheckDataList->plantNo }}</td>
                                                        <td>{{ date('d-m-Y', strtotime($recheckDataList->startDate)) }}</td>
                                                        <td>{{ $recheckDataList->ShiftName }}</td>
                                                        <td>{{ $recheckDataList->wattage }}</td>
                                                        <td>{{ $recheckDataList->efficiency }}</td>
                                                        <td>{{ $recheckDataList->brand }}</td>
                                                        <td>{{ $allDataList->createdByName }}</td>
                                                        <td>
                                                            @php
                                                                if ($recheckDataList->status == 1) {
                                                                    echo 'Approved';
                                                                } elseif ($recheckDataList->status == 4) {
                                                                    echo 'Rejected';
                                                                } elseif ($recheckDataList->status == 3) {
                                                                    echo 'Hold';
                                                                } elseif ($recheckDataList->status == 2) {
                                                                    echo 'Recheck';
                                                                } elseif ($recheckDataList->status == 0) {
                                                                    echo $recheckDataList->stage_title . ' Pending.';
                                                                }
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            @php
                                                                $approverList = [];
                                                                $person = '';
                                                                foreach ($approverDetails as $approver) {
                                                                    if (
                                                                        $recheckDataList->stage == $approver->stage_id
                                                                    ) {
                                                                        $person = $person . $approver->Approver . ',';
                                                                        $approverList[] = $approver->person_id;
                                                                    }
                                                                }
                                                                if ($recheckDataList->status == 1) {
                                                                    echo 'Approved By ' .
                                                                        $recheckDataList->actionByName;
                                                                } elseif ($recheckDataList->status == 4) {
                                                                    echo 'Rejected By ' .
                                                                        $recheckDataList->actionByName;
                                                                } elseif ($recheckDataList->status == 3) {
                                                                    echo 'Hold By ' . $recheckDataList->actionByName;
                                                                } elseif ($recheckDataList->status == 2) {
                                                                    echo 'Recheck By ' . $recheckDataList->actionByName;
                                                                } else {
                                                                    echo 'Pending with - ' . rtrim($person, ',');
                                                                }
                                                            @endphp
                                                        </td>
                                                        <td>{{ date('d-m-Y H:i:s', strtotime($allDataList->created_at)) }}</td>
                                                        <td>
                                                            @if ($recheckDataList->appr_process == 1 && $recheckDataList->created_by == $empId)
                                                                <a class="btn btn-warning btn-xs text-capitalize waves-effect waves-light"
                                                                    href="{{ route('production-setup-form-update', ['batchNo' => $recheckDataList->batchNo]) }}"
                                                                    role="button"><i class="mdi mdi-pencil"></i> Edit</a>
                                                            @endif
                                                            <a class="btn btn-primary btn-xs text-capitalize waves-effect waves-light"
                                                                href="{{ url('production-lineup/production-setup/view-details/' . $recheckDataList->batchNo) }}"
                                                                role="button"><i class="mdi mdi-eye"></i> View</a>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="navs-pendingpo" role="tabpanel">
                                <div class="">
                                    <table
                                        class="d-block dataTable no-footer table table-bordered table-responsive text-nowrap w-100"
                                        id="example4">
                                        <thead class="table-secondary"> <!-- Reject List -->
                                            <tr>
                                                <td>SL No</td>
                                                <td>Batch No.</td>
                                                <td>Plant No.</td>
                                                <td>Start Dt.</td>
                                                <td>Start Shift</td>
                                                <td>Wattage</td>
                                                <td>Cell Efficiency</td>
                                                <td>Cell Company</td>
                                                <td>Created By</td>
                                                <td>Status</td>
                                                <td>Status Pending At</td>
                                                <td>Created At</td>
                                                <td>Action</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $rejectCount = 1; @endphp
                                            @foreach ($AllLists as $key => $rejectDataList)
                                                @if ($rejectDataList->status == 4)
                                                    <tr>
                                                        <td>{{ $rejectCount++ }}</td>
                                                        <td>{{ $rejectDataList->batchNo }}</td>
                                                        <td>{{ $rejectDataList->plantNo }}</td>
                                                        <td>{{ date('d-m-Y', strtotime($rejectDataList->startDate)) }}</td>
                                                        <td>{{ $rejectDataList->ShiftName }}</td>
                                                        <td>{{ $rejectDataList->wattage }}</td>
                                                        <td>{{ $rejectDataList->efficiency }}</td>
                                                        <td>{{ $rejectDataList->brand }}</td>
                                                        <td>{{ $allDataList->createdByName }}</td>
                                                        <td>
                                                            @php
                                                                if ($rejectDataList->status == 1) {
                                                                    echo 'Approved';
                                                                } elseif ($rejectDataList->status == 4) {
                                                                    echo 'Rejected';
                                                                } elseif ($rejectDataList->status == 3) {
                                                                    echo 'Hold';
                                                                } elseif ($rejectDataList->status == 2) {
                                                                    echo 'Recheck';
                                                                } elseif ($rejectDataList->status == 0) {
                                                                    echo $rejectDataList->stage_title . ' Pending.';
                                                                }
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            @php
                                                                $approverList = [];
                                                                $person = '';
                                                                foreach ($approverDetails as $approver) {
                                                                    if ($rejectDataList->stage == $approver->stage_id) {
                                                                        $person = $person . $approver->Approver . ',';
                                                                        $approverList[] = $approver->person_id;
                                                                    }
                                                                }
                                                                if ($rejectDataList->status == 1) {
                                                                    echo 'Approved By ' . $rejectDataList->actionByName;
                                                                } elseif ($rejectDataList->status == 4) {
                                                                    echo 'Rejected By ' . $rejectDataList->actionByName;
                                                                } elseif ($rejectDataList->status == 3) {
                                                                    echo 'Hold By ' . $rejectDataList->actionByName;
                                                                } elseif ($rejectDataList->status == 2) {
                                                                    echo 'Recheck By ' . $rejectDataList->actionByName;
                                                                } else {
                                                                    echo 'Pending with - ' . rtrim($person, ',');
                                                                }
                                                            @endphp
                                                        </td>
                                                        <td>{{ date('d-m-Y H:i:s', strtotime($allDataList->created_at)) }}</td>
                                                        <td>
                                                            <a class="btn btn-primary btn-xs text-capitalize waves-effect waves-light"
                                                                href="{{ url('production-lineup/production-setup/view-details/' . $rejectDataList->batchNo) }}"
                                                                role="button"><i class="mdi mdi-eye"></i> View</a>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="navs-completedpo" role="tabpanel">
                                <div class="">
                                    <table
                                        class="d-block dataTable no-footer table table-bordered table-responsive text-nowrap w-100"
                                        id="example5">
                                        <thead class="table-secondary"> <!-- Approved List -->
                                            <tr>
                                                <td>SL No</td>
                                                <td>Batch No.</td>
                                                <td>Plant No.</td>
                                                <td>Start Dt.</td>
                                                <td>Start Shift</td>
                                                <td>Wattage</td>
                                                <td>Cell Efficiency</td>
                                                <td>Cell Company</td>
                                                <td>Created By</td>
                                                <td>Status</td>
                                                <td>Status Pending At</td>
                                                <td>Created At</td>
                                                <td>Action</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $approvedCount = 1; @endphp
                                            @foreach ($AllLists as $key => $approvedDataList)
                                                @if ($approvedDataList->status == 1)
                                                    <tr>
                                                        <td>{{ $approvedCount++ }}</td>
                                                        <td>{{ $approvedDataList->batchNo }}</td>
                                                        <td>{{ $approvedDataList->plantNo }}</td>
                                                        <td>{{ date('d-m-Y', strtotime($approvedDataList->startDate)) }}</td>
                                                        <td>{{ $approvedDataList->ShiftName }}</td>
                                                        <td>{{ $approvedDataList->wattage }}</td>
                                                        <td>{{ $approvedDataList->efficiency }}</td>
                                                        <td>{{ $approvedDataList->brand }}</td>
                                                        <td>{{ $allDataList->createdByName }}</td>
                                                        <td>
                                                            @php
                                                                if ($approvedDataList->status == 1) {
                                                                    echo 'Approved';
                                                                } elseif ($approvedDataList->status == 4) {
                                                                    echo 'Rejected';
                                                                } elseif ($approvedDataList->status == 3) {
                                                                    echo 'Hold';
                                                                } elseif ($approvedDataList->status == 2) {
                                                                    echo 'Recheck';
                                                                } elseif ($approvedDataList->status == 0) {
                                                                    echo $approvedDataList->stage_title . ' Pending.';
                                                                }
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            @php
                                                                $approverList = [];
                                                                $person = '';
                                                                foreach ($approverDetails as $approver) {
                                                                    if (
                                                                        $approvedDataList->stage == $approver->stage_id
                                                                    ) {
                                                                        $person = $person . $approver->Approver . ',';
                                                                        $approverList[] = $approver->person_id;
                                                                    }
                                                                }
                                                                if ($approvedDataList->status == 1) {
                                                                    echo 'Approved By ' .
                                                                        $approvedDataList->actionByName;
                                                                } elseif ($approvedDataList->status == 4) {
                                                                    echo 'Rejected By ' .
                                                                        $approvedDataList->actionByName;
                                                                } elseif ($approvedDataList->status == 3) {
                                                                    echo 'Hold By ' . $approvedDataList->actionByName;
                                                                } elseif ($approvedDataList->status == 2) {
                                                                    echo 'Recheck By ' .
                                                                        $approvedDataList->actionByName;
                                                                } else {
                                                                    echo 'Pending with - ' . rtrim($person, ',');
                                                                }
                                                            @endphp
                                                        </td>
                                                        <td>{{ date('d-m-Y H:i:s', strtotime($allDataList->created_at)) }}</td>
                                                        <td>
                                                            <a class="btn btn-primary btn-xs text-capitalize waves-effect waves-light"
                                                                href="{{ url('production-lineup/production-setup/view-details/' . $approvedDataList->batchNo) }}"
                                                                role="button"><i class="mdi mdi-eye"></i> View</a>

                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="navs-holdpo" role="tabpanel">
                                <div class="">
                                    <table
                                        class="d-block dataTable no-footer table table-bordered table-responsive text-nowrap w-100"
                                        id="example6">
                                        <thead class="table-secondary"> <!-- Hold List -->
                                            <tr>
                                                <td>SL No</td>
                                                <td>Batch No.</td>
                                                <td>Plant No.</td>
                                                <td>Start Dt.</td>
                                                <td>Start Shift</td>
                                                <td>Wattage</td>
                                                <td>Cell Efficiency</td>
                                                <td>Cell Company</td>
                                                <td>Created By</td>
                                                <td>Status</td>
                                                <td>Status Pending At</td>
                                                <td>Created At</td>
                                                <td>Action</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $holdCount = 1; @endphp
                                            @foreach ($AllLists as $key => $holdDataList)
                                                @if ($holdDataList->status == 3)
                                                    <tr>
                                                        <td>{{ $holdCount++ }}</td>
                                                        <td>{{ $holdDataList->batchNo }}</td>
                                                        <td>{{ $holdDataList->plantNo }}</td>
                                                        <td>{{ date('d-m-Y', strtotime($holdDataList->startDate)) }}</td>
                                                        <td>{{ $holdDataList->ShiftName }}</td>
                                                        <td>{{ $holdDataList->wattage }}</td>
                                                        <td>{{ $holdDataList->efficiency }}</td>
                                                        <td>{{ $holdDataList->brand }}</td>
                                                        <td>{{ $allDataList->createdByName }}</td>
                                                        <td>
                                                            @php
                                                                if ($holdDataList->status == 1) {
                                                                    echo 'Approved';
                                                                } elseif ($holdDataList->status == 4) {
                                                                    echo 'Rejected';
                                                                } elseif ($holdDataList->status == 3) {
                                                                    echo 'Hold';
                                                                } elseif ($holdDataList->status == 2) {
                                                                    echo 'Recheck';
                                                                } elseif ($holdDataList->status == 0) {
                                                                    echo $holdDataList->stage_title . ' Pending.';
                                                                }
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            @php
                                                                $approverList = [];
                                                                $person = '';
                                                                foreach ($approverDetails as $approver) {
                                                                    if ($holdDataList->stage == $approver->stage_id) {
                                                                        $person = $person . $approver->Approver . ',';
                                                                        $approverList[] = $approver->person_id;
                                                                    }
                                                                }
                                                                if ($holdDataList->status == 1) {
                                                                    echo 'Approved By ' . $holdDataList->actionByName;
                                                                } elseif ($holdDataList->status == 4) {
                                                                    echo 'Rejected By ' . $holdDataList->actionByName;
                                                                } elseif ($holdDataList->status == 3) {
                                                                    echo 'Hold By ' . $holdDataList->actionByName;
                                                                } elseif ($holdDataList->status == 2) {
                                                                    echo 'Recheck By ' . $holdDataList->actionByName;
                                                                } else {
                                                                    echo 'Pending with - ' . rtrim($person, ',');
                                                                }
                                                            @endphp
                                                        </td>
                                                        <td>{{ date('d-m-Y H:i:s', strtotime($allDataList->created_at)) }}</td>
                                                        <td>
                                                            <a class="btn btn-primary btn-xs text-capitalize waves-effect waves-light"
                                                                href="{{ url('production-lineup/production-setup/view-details/' . $holdDataList->batchNo) }}"
                                                                role="button"><i class="mdi mdi-eye"></i> View</a>
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
        </div>

    </div>
@endsection
