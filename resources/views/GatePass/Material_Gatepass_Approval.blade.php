@extends('layout.main')
@section('main-container')

    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <title>IN Gate Pass Material Approval List Details</title>

    <style>
        #prevBtn {
            background-color: #bbbbbb;
        }

        .addbtn {
            display: flex;
            justify-content: flex-end;
            padding: 10px 12px;
            margin-top: -3%;
        }

        td.maindffd {
            display: flex;
            justify-content: space-evenly;
            width: 100%;
        }

        div#myFilter {
            position: absolute;
            background: white;
            z-index: 99;
            padding: 10px 15px;
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
            right: 10px;
        }

        .raone p.raho {
            background: green;
            display: flex;
            align-items: center;
            justify-content: center;
            align-content: center;
            padding: 10px 12px;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 5px;
        }

        .FilterButtonnn {
            width: 99%;
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        #myFilter {
            display: none;
        }

        .show-div {
            display: block !important;
        }

        .addbtn i.fas.fa-file-excel {
            font-size: 20px;
            color: green;
            margin-top: 13px;
            margin-right: 10px;
        }

        #page-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loader {
            border: 16px solid #f3f3f3;
            /* Light grey */
            border-top: 16px solid #3498db;
            /* Blue */
            border-radius: 50%;
            width: 120px;
            height: 120px;
            animation: spin 2s linear infinite;
        }

        table.example {
            border: 1px solid #111;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .doc-column {
            overflow-x: hidden;
            max-height: 100px;
        }

        th,
        td {
            vertical-align: middle !important;
        }
    </style>
    @php
        $Department = Session::get('Department');
        $EXT = Session::get('EXT');
    @endphp
    <div class="card">
        <div class="app-content">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <section class="section">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <h4>IN Gate Pass Material Approval List</h4>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">

                        </div>
                    </div>
                    <form action="{{ url('GatePass/filtered_ApproveMaterial') }}" method="POST">
                        @csrf
                        <div class="row filter">
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                <label for="" class="form-label">Creation Date From</label>
                                <input type="date" name="from_date"
                                    value="{{ isset($fromdate) && $fromdate != '' ? $fromdate : '' }}"
                                    class="form-control form-control-sm">
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                <label for="" class="form-label">Creation Date To</label>
                                <input type="date" name="to_date"
                                    value="{{ isset($todate) && $todate != '' ? $todate : '' }}"
                                    class="form-control form-control-sm">
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                <label for="" class="form-label">Request No</label>
                                <select name="Request_No" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all"
                                        {{ isset($RequestNos) && $RequestNos === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach ($DropdownData as $val)
                                        <?php
                                $RequestNo = isset($val->request_no) && $val->request_no != '' ? $val->request_no : '';
                                if (!empty($RequestNo) && !in_array($RequestNo, $RepeatData)) {
                                    $RepeatData[] = $RequestNo;
                                ?>
                                        <option value="{{ $RequestNo }}"
                                            {{ isset($RequestNos) && $RequestNos == $RequestNo ? 'selected' : '' }}>
                                            {{ $RequestNo }}</option>
                                        <?php } ?>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                <label for="" class="form-label">Request By</label>
                                <select name="Request_By" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all"
                                        {{ isset($RequestBys) && $RequestBys === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach ($DropdownData as $val)
                                        <?php
                                $RequestBy = isset($val->request_by) && $val->request_by != '' ? $val->request_by : '';
                                if (!empty($RequestBy) && !in_array($RequestBy, $RepeatData)) {
                                    $RepeatData[] = $RequestBy;
                                ?>
                                        <option value="{{ $RequestBy }}"
                                            {{ isset($RequestBys) && $RequestBys == $RequestBy ? 'selected' : '' }}>
                                            {{ $RequestBy }}</option>
                                        <?php } ?>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                <label for="" class="form-label">Vehicle No</label>
                                <select name="vehicle_no" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all"
                                        {{ isset($VehicleNos) && $VehicleNos === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach ($DropdownData as $val)
                                        <?php
                                $VehicleNo = isset($val->vehicle_no) && $val->vehicle_no != '' ? $val->vehicle_no : '';
                                if (!empty($VehicleNo) && !in_array($VehicleNo, $RepeatData)) {
                                    $RepeatData[] = $VehicleNo;
                                ?>
                                        <option value="{{ $VehicleNo }}"
                                            {{ isset($VehicleNos) && $VehicleNos == $VehicleNo ? 'selected' : '' }}>
                                            {{ $VehicleNo }}</option>
                                        <?php } ?>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                <label for="" class="form-label">Insurance No</label>
                                <select name="insurance_no" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all"
                                        {{ isset($insuranceNos) && $insuranceNos === 'all' ? 'selected' : '' }}>All
                                    </option>
                                    <?php $RepeatData = []; ?>
                                    @foreach ($DropdownData as $val)
                                        <?php
                                $insuranceNo = isset($val->insurance_no) && $val->insurance_no != '' ? $val->insurance_no : '';
                                if (!empty($insuranceNo) && !in_array($insuranceNo, $RepeatData)) {
                                    $RepeatData[] = $insuranceNo;
                                ?>
                                        <option value="{{ $insuranceNo }}"
                                            {{ isset($insuranceNos) && $insuranceNos == $insuranceNo ? 'selected' : '' }}>
                                            {{ $insuranceNo }}</option>
                                        <?php } ?>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                <label for="" class="form-label">Driver Name</label>
                                <select name="Driver_Name" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all"
                                        {{ isset($drivernames) && $drivernames === 'all' ? 'selected' : '' }}>All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach ($DropdownData as $val)
                                        <?php
                                $DriverName = isset($val->driver_name) && $val->driver_name != '' ? $val->driver_name : '';
                                if (!empty($DriverName) && !in_array($DriverName, $RepeatData)) {
                                    $RepeatData[] = $DriverName;
                                ?>
                                        <option value="{{ $DriverName }}"
                                            {{ isset($drivernames) && $drivernames == $DriverName ? 'selected' : '' }}>
                                            {{ $DriverName }}</option>
                                        <?php } ?>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                <label for="" class="form-label">Driver Number</label>
                                <select name="Driver_Number" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all"
                                        {{ isset($DriverNumbers) && $DriverNumbers === 'all' ? 'selected' : '' }}>All
                                    </option>
                                    <?php $RepeatData = []; ?>
                                    @foreach ($DropdownData as $val)
                                        <?php
                                $DriverNumber = isset($val->driver_number) && $val->driver_number != '' ? $val->driver_number : '';
                                if (!empty($DriverNumber) && !in_array($DriverNumber, $RepeatData)) {
                                    $RepeatData[] = $DriverNumber;
                                ?>
                                        <option value="{{ $DriverNumber }}"
                                            {{ isset($DriverNumbers) && $DriverNumbers == $DriverNumber ? 'selected' : '' }}>
                                            {{ $DriverNumber }}</option>
                                        <?php } ?>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                <label for="" class="form-label">IN Invoice No.</label>
                                <select name="Invoice_Challan_No"
                                    class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all"
                                        {{ isset($invoicechallannos) && $invoicechallannos === 'all' ? 'selected' : '' }}>
                                        All
                                    </option>

                                    <?php
                                    $RepeatData = [];
                                    $allInvoicesArray = [];
                                    ?>

                                    @foreach ($DropdownData as $val)
                                        <?php
                                        $InvoiceChallan = isset($val->invoice_no) && trim($val->invoice_no) != '' ? trim($val->invoice_no) : '';
                                        
                                        if (!empty($InvoiceChallan)) {
                                            // EXPLODE comma-separated values if they exist
                                            if (strpos($InvoiceChallan, ',') !== false) {
                                                $invoiceArray = explode(',', $InvoiceChallan);
                                                foreach ($invoiceArray as $singleInvoice) {
                                                    $singleInvoice = trim($singleInvoice);
                                                    if (!empty($singleInvoice) && !in_array($singleInvoice, $RepeatData)) {
                                                        $RepeatData[] = $singleInvoice;
                                                        $allInvoicesArray[] = $singleInvoice;
                                                    }
                                                }
                                            } else {
                                                // Single invoice number
                                                if (!in_array($InvoiceChallan, $RepeatData)) {
                                                    $RepeatData[] = $InvoiceChallan;
                                                    $allInvoicesArray[] = $InvoiceChallan;
                                                }
                                            }
                                        }
                                        ?>
                                    @endforeach

                                    {{-- Option with all invoice numbers as comma-separated --}}
                                    @if (!empty($allInvoicesArray))
                                        <?php
                                        $allInvoicesString = implode(',', $allInvoicesArray);
                                        ?>
                                        <option value="{{ $allInvoicesString }}"
                                            {{ isset($invoicechallannos) && $invoicechallannos == $allInvoicesString ? 'selected' : '' }}>
                                        </option>
                                    @endif

                                    {{-- Individual invoice options --}}
                                    @foreach ($RepeatData as $InvoiceChallan)
                                        <option value="{{ $InvoiceChallan }}"
                                            {{ isset($invoicechallannos) && $invoicechallannos == $InvoiceChallan ? 'selected' : '' }}>
                                            {{ $InvoiceChallan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                <label for="" class="form-label">IN E - way Bill Number</label>
                                <select name="bill_no" class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" disabled selected>Select</option>
                                    <option value="all" {{ isset($BillNos) && $BillNos === 'all' ? 'selected' : '' }}>
                                        All</option>
                                    <?php $RepeatData = []; ?>
                                    @foreach ($DropdownData as $val)
                                        <?php
                                $BillNo = isset($val->bill_no) && $val->bill_no != '' ? $val->bill_no : '';
                                if (!empty($BillNo) && !in_array($BillNo, $RepeatData)) {
                                    $RepeatData[] = $BillNo;
                                ?>
                                        <option value="{{ $BillNo }}"
                                            {{ isset($BillNos) && $BillNos == $BillNo ? 'selected' : '' }}>
                                            {{ $BillNo }}
                                        </option>
                                        <?php } ?>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-2 col-sm-6 mt-4">
                                <div class="">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                    <a href="{{ url('GatePass/Material_Gatepass_Approval') }}">
                                        <button type="button" class="btn btn-secondary"><i
                                                class="fa fa-refresh"></i></button></a>
                                    </a>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-2 col-sm-6 mt-4">
                                <div class="FilterButtonnn">
                                    <div class="raone">
                                        <p class="raho" id="MyToggle">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-funnel-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z" />
                                            </svg>
                                        </p>
                                        <div class="ukom" id="myFilter">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="ToggleCheck"
                                                    onclick="toggleCheckboxes()">
                                                <label class="form-check-label" for="ToggleCheck">All</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="NO"
                                                    value="SL. No." onclick="filterTable(this)">
                                                <label class="form-check-label" for="NO">SL. No.</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="in_Request_No"
                                                    value="InComing Pass No" onclick="filterTable(this)">
                                                <label class="form-check-label" for="in_Request_No">InComing Pass
                                                    No</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="creator"
                                                    value="Created By" onclick="filterTable(this)">
                                                <label class="form-check-label" for="creator">Created By</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="dt_time"
                                                    value="Creation Date & Time" onclick="filterTable(this)">
                                                <label class="form-check-label" for="dt_time">Creation Date &
                                                    Time</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="Vehicle_No"
                                                    value="Vehicle No" onclick="filterTable(this)">
                                                <label class="form-check-label" for="Vehicle_No">Vehicle No</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="ins_no"
                                                    value="Insurance No" onclick="filterTable(this)">
                                                <label class="form-check-label" for="ins_no">Insurance No</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="in_dt_time"
                                                    value="In Date & Time" onclick="filterTable(this)">
                                                <label class="form-check-label" for="in_dt_time">In Date & Time</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="Driver_Name"
                                                    value="Driver Name" onclick="filterTable(this)">
                                                <label class="form-check-label" for="Driver_Name">Driver Name</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="Driver_Number"
                                                    value="Driver Mobile No" onclick="filterTable(this)">
                                                <label class="form-check-label" for="Driver_Number">Driver Mobile
                                                    No</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="in_inv_no"
                                                    value="In Invoice No" onclick="filterTable(this)">
                                                <label class="form-check-label" for="in_inv_no">In Invoice No</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="in_bill_number"
                                                    value="In E - Way Bill Number" onclick="filterTable(this)">
                                                <label class="form-check-label" for="in_bill_number">In E - Way Bill
                                                    Number</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="Status_IN"
                                                    value="Status" onclick="filterTable(this)">
                                                <label class="form-check-label" for="Status_IN">Status</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="Pending_With_IN"
                                                    value="Pending With" onclick="filterTable(this)">
                                                <label class="form-check-label" for="Pending_With_IN">Pending With</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="OPERATION"
                                                    value="OPERATION" onclick="filterTable(this)">
                                                <label class="form-check-label" for="OPERATION">OPERATION</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered example w-100">
                            <thead style="background: #d0d9dc;">
                                <tr>
                                    <th class="th-sm">SL. No.</th>
                                    <th class="th-sm">InComing Pass No</th>
                                    <th class="th-sm">Created By</th>
                                    <th class="th-sm">Creation Date & Time</th>
                                    <th class="th-sm">Vehicle No</th>
                                    <th class="th-sm">Insurance No</th>
                                    <th class="th-sm">In Date & Time</th>
                                    <th class="th-sm">Driver Name</th>
                                    <th class="th-sm">Driver Mobile No</th>
                                    <th class="th-sm">In Invoice No</th>
                                    <th class="th-sm">In E - Way Bill Number</th>
                                    <th class="th-sm">Status</th>
                                    <th class="th-sm">Pending With</th>
                                    <th class="th-sm">OPERATION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($materialdata as $key => $val)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ isset($val->request_no) && $val->request_no != '' ? $val->request_no : '' }}
                                        </td>
                                        <td>{{ isset($val->request_by) && $val->request_by != '' ? $val->request_by : '' }}
                                        </td>
                                        <td>{{ isset($val->created_at) && $val->created_at != '' ? date('d-m-Y h:i A', strtotime($val->created_at)) : '' }}
                                        </td>
                                        <td>{{ isset($val->vehicle_no) && $val->vehicle_no != '' ? $val->vehicle_no : '' }}
                                        </td>
                                        <td>{{ isset($val->insurance_no) && $val->insurance_no != '' ? $val->insurance_no : '' }}
                                        </td>
                                        <td>{{ isset($val->vehicle_in_time) && $val->vehicle_in_time != '' ? date('d-m-Y h:i A', strtotime($val->vehicle_in_time)) : '' }}
                                        </td>
                                        <td>{{ isset($val->driver_name) && $val->driver_name != '' ? $val->driver_name : '' }}
                                        </td>
                                        <td>{{ isset($val->driver_number) && $val->driver_number != '' ? $val->driver_number : '' }}
                                        </td>
                                        <td>{{ isset($val->invoice_no) && $val->invoice_no != '' ? $val->invoice_no : '' }}
                                        </td>
                                        <td>{{ isset($val->bill_no) && $val->bill_no != '' ? $val->bill_no : '' }}</td>
                                        <td id="statuss{{ $val->id }}">
                                            @if ($val->Approve_status == 'APPROVE')
                                                <span style="color: #1bb81b;">APPROVED</span>
                                            @elseif($val->Approve_status == 'REJECT')
                                                <span style="color:red">REJECTED</span>
                                            @elseif($val->Approve_status == 'RECHECK')
                                                <span style="color:#71a5ee">RECHECK</span>
                                            @elseif($val->Approve_status == 'OBJECT')
                                                <span style="color:#da2aff">OBJECT</span>
                                            @elseif($val->Approve_status == 'HOLD')
                                                <span style="color:#0cbad6">HOLD</span>
                                            @else
                                                <span style="color: #FF9000;">Pending</span>
                                            @endif
                                        </td>
                                        <td class="PendingColor">
                                            @if (
                                                ($val->Approve_status === 'FORWARD' && isset($val->status) && $val->status != 1) ||
                                                    ($val->Approve_status == '' && isset($val->status) && $val->status != 1))
                                                Pending With
                                                @foreach ($val->PendingWith as $name)
                                                    {{ isset($name->fullname) && $name->fullname != '' ? $name->fullname : '' }},
                                                @endforeach
                                            @elseif($val->Approve_status == 'RECHECK' || $val->Approve_status == 'OBJECT')
                                                {{ isset($val->user->fullname) && $val->user->fullname != '' ? 'Pending With ' . $val->user->fullname : '' }}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ url('GatePass/Material_view/' . $val->id . '/approval') }}"
                                                class="btn btn-primary">VIEW</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
            </section>
        </div>
        <div id="page-loader" style="display: none;">
            <div class="loader"></div>
        </div>
    </div>


@endsection
@push('custom-scripts')
    <script>
        activeclass(7, 8);
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var MyToggle = document.getElementById("MyToggle");
            var myFilter = document.getElementById("myFilter");

            MyToggle.addEventListener("click", function() {
                myFilter.classList.toggle("show-div");
            });

            document.addEventListener("click", function(event) {
                if (!myFilter.contains(event.target) && !MyToggle.contains(event.target)) {
                    myFilter.classList.remove("show-div");
                }
            });
        });
    </script>
    <script>
        function toggleCheckboxes() {
            var checkboxes = document.querySelectorAll('.form-check-input');
            var toggleCheckbox = document.getElementById('ToggleCheck');

            checkboxes.forEach(function(checkbox) {
                checkbox.checked = toggleCheckbox.checked;
            });

            checkBoxess();
        }

        function filterTable() {
            var checkboxes = document.querySelectorAll('.form-check-input');
            var toggleCheckbox = document.getElementById('ToggleCheck');

            var allChecked = true;
            var toggleChecked = toggleCheckbox.checked;

            checkboxes.forEach(function(checkbox) {
                if (checkbox !== toggleCheckbox && !checkbox.checked) {
                    allChecked = false;
                }
            });

            toggleCheckbox.checked = allChecked && toggleChecked;

            checkBoxess();
        }
    </script>
    <script>
        var tableID = 7792;

        function checkBoxess() {
            var checkedColumns = document.querySelectorAll('.form-check-input:checked');
            var columnNamesToShow = [];

            checkedColumns.forEach(function(checkbox) {
                columnNamesToShow.push(checkbox.value);
            });

            var table = document.querySelector('table');
            if (!table) {
                console.error('Table element not found');
                return;
            }

            var rows = table.querySelectorAll('tr');

            if (checkedColumns.length === 0) {
                rows.forEach(function(row) {
                    var cells = row.querySelectorAll('td');
                    cells.forEach(function(cell) {
                        cell.style.display = '';
                    });
                });

                var thead = table.querySelector('thead');
                if (thead) {
                    var thElements = thead.querySelectorAll('th');
                    thElements.forEach(function(th) {
                        th.style.display = '';
                    });
                }
            } else {
                rows.forEach(function(row) {
                    var cells = row.querySelectorAll('td');
                    cells.forEach(function(cell, j) {
                        var columnName = table.querySelector('thead th:nth-child(' + (j + 1) + ')');
                        if (columnName) {
                            if (columnNamesToShow.indexOf(columnName.innerText) !== -1) {
                                cell.style.display = '';
                            } else {
                                cell.style.display = 'none';
                            }
                        }
                    });
                });

                var thead = table.querySelector('thead');
                if (thead) {
                    var thElements = thead.querySelectorAll('th');
                    thElements.forEach(function(th) {
                        var columnName = th.innerText;
                        if (columnNamesToShow.indexOf(columnName) !== -1) {
                            th.style.display = '';
                        } else {
                            th.style.display = 'none';
                        }
                    });
                }
            }

            var columnValue = columnNamesToShow.join(',');

            fetch("{{ url('FactoryCreater/getCheckBoxData') }}?ID=" + tableID, {
                    method: 'GET',
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.columns) {
                        try {
                            var existingData = data.columns;
                            if (JSON.stringify(existingData) !== JSON.stringify(columnNamesToShow)) {
                                fetch("{{ url('FactoryCreater/CheckBoxStore') }}", {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        },
                                        body: JSON.stringify({
                                            id: tableID,
                                            columns: columnValue,
                                        }),
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        console.log(data);
                                    })
                                    .catch(error => {
                                        console.error('Error sending data to the backend:', error);
                                    });
                            }
                        } catch (error) {
                            console.error('Error parsing JSON data:', error);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching checkbox data from the backend:', error);
                });
        }


        document.addEventListener('DOMContentLoaded', function() {

            fetch("{{ url('FactoryCreater/getCheckBoxData') }}?ID=" + tableID, {
                    method: 'GET',
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.columns) {
                        try {
                            var columnNamesToShow = data.columns;
                            var checkboxes = document.querySelectorAll('.form-check-input');

                            checkboxes.forEach(function(checkbox) {
                                if (columnNamesToShow.indexOf(checkbox.value) !== -1) {
                                    checkbox.checked = true;
                                }
                            });

                            filterTable();
                        } catch (error) {
                            console.error('Error parsing JSON data:', error);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching checkbox data from the backend:', error);
                });
        });
    </script>
@endpush
