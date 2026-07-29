@extends('includes.layout')

@section('pageHeading')
    Stringer QC Request List Details
@endsection
<style>
    #example th:nth-child(12), #example td:nth-child(12), #example2 th:nth-child(12), #example2 td:nth-child(12), #example3 th:nth-child(12), #example3 td:nth-child(12), #example4 th:nth-child(12), #example4 td:nth-child(12), #example5 th:nth-child(12), #example5 td:nth-child(12), #example6 th:nth-child(12), #example6 td:nth-child(12) {
        min-width: 260px !important;
        white-space: normal !important;
    }
</style>
@section('content')

    <div class="container-fluid flex-grow-1 container-p-y">
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
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-pills flex-column flex-md-row mb-3 gap-2 gap-lg-0" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="javascript:void(0);" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-requestAll" aria-controls="navs-requestAll" aria-selected="true">
                            <i class="mdi mdi-account-outline mdi-20px me-1"></i>All
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-requestApproved" aria-controls="navs-requestApproved"
                            aria-selected="false">
                            <i class="mdi mdi-account-box-outline mdi-20px me-1"></i>Approved
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-requestReject" aria-controls="navs-requestReject" aria-selected="false">
                            <i class="mdi mdi-cash-multiple mdi-20px me-1"></i>Reject
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-requestRecheck" aria-controls="navs-requestRecheck" aria-selected="false">
                            <i class="mdi mdi-bookmark-outline mdi-20px me-1"></i>Recheck
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-requestPending" aria-controls="navs-requestPending" aria-selected="false">
                            <i class="mdi mdi-checkbox-multiple-marked mdi-20px me-1"></i>Pending
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-requestHold" aria-controls="navs-requestHold" aria-selected="false">
                            <i class="mdi mdi-pause-box-outline mdi-20px me-1"></i>Hold
                        </a>
                    </li>
                </ul>
                <div class="card">
                    <!-- <div class="card-header d-flex justify-content-between align-items-center bg-label-primary py-2">
                        <h5 class="mb-0">Stringer QC Request</h5>
                        <div class="text-end">
                            <a href="{{ route('stringer-qc-add') }}"
                                class="ms-2 btn btn-primary btn-sm waves-effect waves-light">
                                <span class="mdi mdi-playlist-plus me-1"></span> Add
                            </a>
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
                                                if (isset($_GET['createdBy']) && $_GET['createdBy'] == $creator->id) {
                                                    $selected = 'selected';
                                                } else {
                                                    $selected = '';
                                                }
                                            @endphp
                                            <option value="{{ $creator->id }}" {{ $selected }}>{{ $creator->fullname }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label for="email" class="form-label">Operator:</label>
                                    <select class="form-select select2" name="operator">
                                        <option value=''>Select Operator</option>
                                        @foreach ($userList as $operator)
                                            @php
                                                if (isset($_GET['operator']) && $_GET['operator'] == $operator->id) {
                                                    $selected = 'selected';
                                                } else {
                                                    $selected = '';
                                                }
                                            @endphp
                                            <option value="{{ $operator->id }}" {{ $selected }}>
                                                {{ $operator->fullname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label for="email" class="form-label">Checker:</label>
                                    <select class="form-select select2" name="checker">
                                        <option value=''>Select Checker</option>
                                        @foreach ($userList as $checker)
                                            @php
                                                if (isset($_GET['checker']) && $_GET['checker'] == $checker->id) {
                                                    $selected = 'selected';
                                                } else {
                                                    $selected = '';
                                                }
                                            @endphp
                                            <option value="{{ $checker->id }}" {{ $selected }}>
                                                {{ $checker->fullname }}</option>
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
                                    <label for="email" class="form-label">From Date:</label>
                                    <input type="text" name="fromDate"
                                        value="{{ isset($_GET['fromDate']) ? $_GET['fromDate'] : '' }}"
                                        placeholder="YYYY-MM-DD" class="form-control dob-picker">
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label for="email" class="form-label">To Date:</label>
                                    <input type="text" name="toDate"
                                        value="{{ isset($_GET['toDate']) ? $_GET['toDate'] : '' }}" placeholder="YYYY-MM-DD"
                                        class="form-control dob-picker">
                                </div>
                                <div class="col-md-6 mb-1 mt-4" style="text-align:right;">
                                    <button type="submit" class="btn btn-outline-primary">Search</button>
                                    <a href="{{ url('production-lineup/stringer-qc') }}"><button type="button"
                                            class="btn btn-outline-success">Refresh</button></a>
                                </div>
                            </div>
                        </form>

                        <div class="tab-content p-0">
                            {{-- All Tab --}}
                            <div class="tab-pane fade active show" id="navs-requestAll" role="tabpanel">
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
                                                <td>Stringer Size</td>
                                                <td>Total Production</td>
                                                <td>Total Reject</td>
                                                <td>Added by</td>
                                                <td>Operator</td>
                                                <td>Incharge</td>
                                                <td>Created On</td>
                                                <td>Pending With</td>
                                                <td>Status</td>
                                                <td>Operation</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($AllLists as $key => $allDataList)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ date('d-m-Y', strtotime($allDataList->date)) }}</td>
                                                    <td>{{ $allDataList->shiftdtl ?? '' }}</td>
                                                    <td>{{ $allDataList->wattage ?? '' }}</td>
                                                    <td>{{ $allDataList->Size ?? '' }}</td>
                                                    <td>{{ $allDataList->totalProductionQty ?? '' }}</td>
                                                    <td>{{ $allDataList->totalRejectQty ?? '' }}</td>
                                                    <td>{{ $allDataList->addByName ?? '' }}</td>
                                                    <td>{{ $allDataList->operatorName ?? '' }}</td>
                                                    <td>{{ $allDataList->checkerName ?? '' }}</td>
                                                    <td>{{ date('d-m-Y H:i:s', strtotime($allDataList->created_at)) }}</td>
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
                                                                echo 'Recheck By ' . $allDataList->actionByName;
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
                                                                echo 'Recheck';
                                                            } elseif ($allDataList->status == 3) {
                                                                echo 'Hold';
                                                            } elseif ($allDataList->status == 4) {
                                                                echo 'Rejected';
                                                            } elseif ($allDataList->status == 0) {
                                                                echo $allDataList->stage_title . ' Pending.';
                                                            }
                                                        @endphp
                                                    </td>
                                                    <td>
                                                        @if (
                                                            ($allDataList->appr_process == 0 && $allDataList->status == 0 && $allDataList->created_by == $empId) ||
                                                                ($allDataList->status == 2 && $allDataList->created_by == $empId))
                                                            <a class="btn btn-warning btn-xs text-capitalize waves-effect waves-light"
                                                                href="{{ route('stringer-qc-form-update', ['id' => $allDataList->id]) }}"
                                                                role="button"><i class="mdi mdi-pencil"></i> Edit</a>
                                                        @endif
                                                        <a class="btn btn-primary btn-xs text-capitalize waves-effect waves-light"
                                                            href="{{ route('stringer-qc-view', ['id' => $allDataList->id]) }}"
                                                            role="button">
                                                            <i class="mdi mdi-eye"></i> View
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{-- Approved Tab --}}
                            <div class="tab-pane fade" id="navs-requestApproved" role="tabpanel">
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
                                                <td>Stringer Size</td>
                                                <td>Total Production</td>
                                                <td>Total Reject</td>
                                                <td>Added by</td>
                                                <td>Operator</td>
                                                <td>Incharge</td>
                                                <td>Created On</td>
                                                <td>Pending With</td>
                                                <td>Status</td>
                                                <td>Operation</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $approvedCount = 1; @endphp
                                            @foreach ($AllLists as $key => $apprvDataList)
                                                @if ($apprvDataList->status == 1)
                                                    <tr>
                                                        <td>{{ $approvedCount++ }}</td>
                                                        <td>{{ date('d-m-Y', strtotime($apprvDataList->date)) }}</td>
                                                        <td>{{ $apprvDataList->shiftdtl ?? '' }}</td>
                                                        <td>{{ $apprvDataList->wattage ?? '' }}</td>
                                                        <td>{{ $apprvDataList->Size ?? '' }}</td>
                                                        <td>{{ $apprvDataList->totalProductionQty ?? '' }}</td>
                                                        <td>{{ $apprvDataList->totalRejectQty ?? '' }}</td>
                                                        <td>{{ $apprvDataList->addByName ?? '' }}</td>
                                                        <td>{{ $apprvDataList->operatorName ?? '' }}</td>
                                                        <td>{{ $apprvDataList->checkerName ?? '' }}</td>
                                                        <td>{{ date('d-m-Y H:i:s', strtotime($apprvDataList->created_at)) }}
                                                        </td>
                                                        <td>
                                                            @php
                                                                $approverList = [];
                                                                $person = '';
                                                                foreach ($approverDetails as $approver) {
                                                                    if ($apprvDataList->stage == $approver->stage_id) {
                                                                        $person = $person . $approver->Approver . ',';
                                                                        $approverList[] = $approver->person_id;
                                                                    }
                                                                }
                                                                if ($apprvDataList->status == 1) {
                                                                    echo 'Approved By ' . $apprvDataList->actionByName;
                                                                } elseif ($apprvDataList->status == 2) {
                                                                    echo 'Recheck By ' . $apprvDataList->actionByName;
                                                                } elseif ($apprvDataList->status == 3) {
                                                                    echo 'Hold By ' . $apprvDataList->actionByName;
                                                                } elseif ($apprvDataList->status == 4) {
                                                                    echo 'Rejected By ' . $apprvDataList->actionByName;
                                                                } else {
                                                                    echo 'Pending with - ' . rtrim($person, ',');
                                                                }
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            @php
                                                                if ($apprvDataList->status == 1) {
                                                                    echo 'Approved';
                                                                } elseif ($apprvDataList->status == 2) {
                                                                    echo 'Recheck';
                                                                } elseif ($apprvDataList->status == 3) {
                                                                    echo 'Hold';
                                                                } elseif ($apprvDataList->status == 4) {
                                                                    echo 'Rejected';
                                                                } elseif ($apprvDataList->status == 0) {
                                                                    echo $apprvDataList->stage_title . ' Pending.';
                                                                }
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-primary btn-xs text-capitalize waves-effect waves-light"
                                                                href="{{ route('stringer-qc-view', ['id' => $apprvDataList->id]) }}"
                                                                role="button"><i class="mdi mdi-eye"></i> View</a>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{-- Reject Tab --}}
                            <div class="tab-pane fade" id="navs-requestReject" role="tabpanel">
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
                                                <td>Stringer Size</td>
                                                <td>Total Production</td>
                                                <td>Total Reject</td>
                                                <td>Added by</td>
                                                <td>Operator</td>
                                                <td>Incharge</td>
                                                <td>Created On</td>
                                                <td>Pending With</td>
                                                <td>Status</td>
                                                <td>Operation</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $rejectCount = 1; @endphp
                                            @foreach ($AllLists as $key => $rejectDataList)
                                                @if ($rejectDataList->status == 4)
                                                    <tr>
                                                        <td>{{ $rejectCount++ }}</td>
                                                        <td>{{ date('d-m-Y', strtotime($rejectDataList->date)) }}
                                                        </td>
                                                        <td>{{ $rejectDataList->shiftdtl ?? '' }}</td>
                                                        <td>{{ $rejectDataList->wattage ?? '' }}</td>
                                                        <td>{{ $rejectDataList->Size ?? '' }}</td>
                                                        <td>{{ $rejectDataList->totalProductionQty ?? '' }}</td>
                                                        <td>{{ $rejectDataList->totalRejectQty ?? '' }}</td>
                                                        <td>{{ $rejectDataList->addByName ?? '' }}</td>
                                                        <td>{{ $rejectDataList->operatorName ?? '' }}</td>
                                                        <td>{{ $rejectDataList->checkerName ?? '' }}</td>
                                                        <td>{{ date('d-m-Y H:i:s', strtotime($rejectDataList->created_at)) }}
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
                                                                } elseif ($rejectDataList->status == 2) {
                                                                    echo 'Recheck By ' . $rejectDataList->actionByName;
                                                                } elseif ($rejectDataList->status == 3) {
                                                                    echo 'Hold By ' . $rejectDataList->actionByName;
                                                                } elseif ($rejectDataList->status == 4) {
                                                                    echo 'Rejected By ' . $rejectDataList->actionByName;
                                                                } else {
                                                                    echo 'Pending with - ' . rtrim($person, ',');
                                                                }
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            @php
                                                                if ($rejectDataList->status == 1) {
                                                                    echo 'Approved';
                                                                } elseif ($rejectDataList->status == 2) {
                                                                    echo 'Recheck';
                                                                } elseif ($rejectDataList->status == 3) {
                                                                    echo 'Hold';
                                                                } elseif ($rejectDataList->status == 4) {
                                                                    echo 'Rejected';
                                                                } elseif ($rejectDataList->status == 0) {
                                                                    echo $rejectDataList->stage_title . ' Pending.';
                                                                }
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-primary btn-xs text-capitalize waves-effect waves-light"
                                                                href="{{ route('stringer-qc-view', ['id' => $rejectDataList->id]) }}"
                                                                role="button"><i class="mdi mdi-eye"></i> View</a>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{-- Recheck Tab --}}
                            <div class="tab-pane fade" id="navs-requestRecheck" role="tabpanel">
                                <div class="">
                                    <table
                                        class="d-block dataTable no-footer table table-bordered table-responsive text-nowrap w-100"
                                        id="example4">
                                        <thead class="table-secondary">
                                            <tr>
                                                <td>SL No</td>
                                                <td>Date</td>
                                                <td>Shift</td>
                                                <td>Wattage</td>
                                                <td>Stringer Size</td>
                                                <td>Total Production</td>
                                                <td>Total Reject</td>
                                                <td>Added by</td>
                                                <td>Operator</td>
                                                <td>Incharge</td>
                                                <td>Created On</td>
                                                <td>Pending With</td>
                                                <td>Status</td>
                                                <td>Operation</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $recheckCount = 1; @endphp
                                            @foreach ($AllLists as $key => $recheckDataList)
                                                @if ($recheckDataList->status == 2)
                                                    <tr>
                                                        <td>{{ $recheckCount++ }}</td>
                                                        <td>{{ date('d-m-Y', strtotime($recheckDataList->date)) }}
                                                        </td>
                                                        <td>{{ $recheckDataList->shiftdtl ?? '' }}</td>
                                                        <td>{{ $recheckDataList->wattage ?? '' }}</td>
                                                        <td>{{ $recheckDataList->Size ?? '' }}</td>
                                                        <td>{{ $recheckDataList->totalProductionQty ?? '' }}</td>
                                                        <td>{{ $recheckDataList->totalRejectQty ?? '' }}</td>
                                                        <td>{{ $recheckDataList->addByName ?? '' }}</td>
                                                        <td>{{ $recheckDataList->operatorName ?? '' }}</td>
                                                        <td>{{ $recheckDataList->checkerName ?? '' }}</td>
                                                        <td>{{ date('d-m-Y H:i:s', strtotime($recheckDataList->created_at)) }}
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
                                                                } elseif ($recheckDataList->status == 2) {
                                                                    echo 'Recheck By ' . $recheckDataList->actionByName;
                                                                } elseif ($recheckDataList->status == 3) {
                                                                    echo 'Hold By ' . $recheckDataList->actionByName;
                                                                } elseif ($recheckDataList->status == 4) {
                                                                    echo 'Rejected By ' .
                                                                        $recheckDataList->actionByName;
                                                                } else {
                                                                    echo 'Pending with - ' . rtrim($person, ',');
                                                                }
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            @php
                                                                if ($recheckDataList->status == 1) {
                                                                    echo 'Approved';
                                                                } elseif ($recheckDataList->status == 2) {
                                                                    echo 'Recheck';
                                                                } elseif ($recheckDataList->status == 3) {
                                                                    echo 'Hold';
                                                                } elseif ($recheckDataList->status == 4) {
                                                                    echo 'Rejected';
                                                                } elseif ($recheckDataList->status == 0) {
                                                                    echo $recheckDataList->stage_title . ' Pending.';
                                                                }
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            @if ($recheckDataList->appr_process == 1 && $recheckDataList->created_by == $empId)
                                                                <a class="btn btn-warning btn-xs text-capitalize waves-effect waves-light"
                                                                    href="{{ route('stringer-qc-form-update', ['id' => $recheckDataList->id]) }}"
                                                                    role="button"><i class="mdi mdi-pencil"></i> Edit</a>
                                                            @endif
                                                            <a class="btn btn-primary btn-xs text-capitalize waves-effect waves-light"
                                                                href="{{ route('stringer-qc-view', ['id' => $recheckDataList->id]) }}"
                                                                role="button"><i class="mdi mdi-eye"></i> View</a>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{-- Pending Tab --}}
                            <div class="tab-pane fade" id="navs-requestPending" role="tabpanel">
                                <div class="">
                                    <table
                                        class="d-block dataTable no-footer table table-bordered table-responsive text-nowrap w-100"
                                        id="example5">
                                        <thead class="table-secondary">
                                            <tr>
                                                <td>SL No</td>
                                                <td>Date</td>
                                                <td>Shift</td>
                                                <td>Wattage</td>
                                                <td>Stringer Size</td>
                                                <td>Total Production</td>
                                                <td>Total Reject</td>
                                                <td>Added by</td>
                                                <td>Operator</td>
                                                <td>Incharge</td>
                                                <td>Created On</td>
                                                <td>Pending With</td>
                                                <td>Status</td>
                                                <td>Operation</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $pendingCount = 1; @endphp
                                            @foreach ($AllLists as $key => $pendDataList)
                                                @if ($pendDataList->status == 0)
                                                    <tr>
                                                        <td>{{ $pendingCount++ }}</td>
                                                        <td>{{ date('d-m-Y', strtotime($pendDataList->date)) }}</td>
                                                        <td>{{ $pendDataList->shiftdtl ?? '' }}</td>
                                                        <td>{{ $pendDataList->wattage ?? '' }}</td>
                                                        <td>{{ $pendDataList->Size ?? '' }}</td>
                                                        <td>{{ $pendDataList->totalProductionQty ?? '' }}</td>
                                                        <td>{{ $pendDataList->totalRejectQty ?? '' }}</td>
                                                        <td>{{ $pendDataList->addByName ?? '' }}</td>
                                                        <td>{{ $pendDataList->operatorName ?? '' }}</td>
                                                        <td>{{ $pendDataList->checkerName ?? '' }}</td>
                                                        <td>{{ date('d-m-Y H:i:s', strtotime($pendDataList->created_at)) }}
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
                                                                } elseif ($pendDataList->status == 2) {
                                                                    echo 'Recheck By ' . $pendDataList->actionByName;
                                                                } elseif ($pendDataList->status == 3) {
                                                                    echo 'Hold By ' . $pendDataList->actionByName;
                                                                } elseif ($pendDataList->status == 4) {
                                                                    echo 'Rejected By ' . $pendDataList->actionByName;
                                                                } else {
                                                                    echo 'Pending with - ' . rtrim($person, ',');
                                                                }
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            @php
                                                                if ($pendDataList->status == 1) {
                                                                    echo 'Approved';
                                                                } elseif ($pendDataList->status == 2) {
                                                                    echo 'Recheck';
                                                                } elseif ($pendDataList->status == 3) {
                                                                    echo 'Hold';
                                                                } elseif ($pendDataList->status == 4) {
                                                                    echo 'Rejected';
                                                                } elseif ($pendDataList->status == 0) {
                                                                    echo $pendDataList->stage_title . ' Pending.';
                                                                }
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            @if ($pendDataList->appr_process == 0 && $pendDataList->created_by == $empId)
                                                                <a class="btn btn-warning btn-xs text-capitalize waves-effect waves-light"
                                                                    href="{{ route('stringer-qc-form-update', ['id' => $pendDataList->id]) }}"
                                                                    role="button"><i class="mdi mdi-pencil"></i> Edit</a>
                                                            @endif
                                                            <a class="btn btn-primary btn-xs text-capitalize waves-effect waves-light"
                                                                href="{{ route('stringer-qc-view', ['id' => $pendDataList->id]) }}"
                                                                role="button"><i class="mdi mdi-eye"></i> View</a>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{-- Hold Tab --}}
                            <div class="tab-pane fade" id="navs-requestHold" role="tabpanel">
                                <div class="">
                                    <table
                                        class="d-block dataTable no-footer table table-bordered table-responsive text-nowrap w-100"
                                        id="example6">
                                        <thead class="table-secondary">
                                            <tr>
                                                <td>SL No</td>
                                                <td>Date</td>
                                                <td>Shift</td>
                                                <td>Wattage</td>
                                                <td>Stringer Size</td>
                                                <td>Total Production</td>
                                                <td>Total Reject</td>
                                                <td>Added by</td>
                                                <td>Operator</td>
                                                <td>Incharge</td>
                                                <td>Created On</td>
                                                <td>Pending With</td>
                                                <td>Status</td>
                                                <td>Operation</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $holdCount = 1; @endphp
                                            @foreach ($AllLists as $key => $holdDataList)
                                                @if ($holdDataList->status == 3)
                                                    <tr>
                                                        <td>{{ $holdCount++ }}</td>
                                                        <td>{{ date('d-m-Y', strtotime($holdDataList->date)) }}
                                                        </td>
                                                        <td>{{ $holdDataList->shiftdtl ?? '' }}</td>
                                                        <td>{{ $holdDataList->wattage ?? '' }}</td>
                                                        <td>{{ $holdDataList->Size ?? '' }}</td>
                                                        <td>{{ $holdDataList->totalProductionQty ?? '' }}</td>
                                                        <td>{{ $holdDataList->totalRejectQty ?? '' }}</td>
                                                        <td>{{ $holdDataList->addByName ?? '' }}</td>
                                                        <td>{{ $holdDataList->operatorName ?? '' }}</td>
                                                        <td>{{ $holdDataList->checkerName ?? '' }}</td>
                                                        <td>{{ date('d-m-Y H:i:s', strtotime($holdDataList->created_at)) }}
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
                                                                } elseif ($holdDataList->status == 2) {
                                                                    echo 'Recheck By ' . $holdDataList->actionByName;
                                                                } elseif ($holdDataList->status == 3) {
                                                                    echo 'Hold By ' . $holdDataList->actionByName;
                                                                } elseif ($holdDataList->status == 4) {
                                                                    echo 'Rejected By ' . $holdDataList->actionByName;
                                                                } else {
                                                                    echo 'Pending with - ' . rtrim($person, ',');
                                                                }
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            @php
                                                                if ($holdDataList->status == 1) {
                                                                    echo 'Approved';
                                                                } elseif ($holdDataList->status == 2) {
                                                                    echo 'Recheck';
                                                                } elseif ($holdDataList->status == 3) {
                                                                    echo 'Hold';
                                                                } elseif ($holdDataList->status == 4) {
                                                                    echo 'Rejected';
                                                                } elseif ($holdDataList->status == 0) {
                                                                    echo $holdDataList->stage_title . ' Pending.';
                                                                }
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-primary btn-xs text-capitalize waves-effect waves-light"
                                                                href="{{ route('stringer-qc-view', ['id' => $holdDataList->id]) }}"
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
                        {{-- End Tabs --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
