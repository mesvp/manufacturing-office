@extends('layout.main')
@section('main-container')
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">

    <style>
        * {

            box-sizing: border-box;

        }

        body {

            background-color: #f1f1f1;

        }

        #regForm {
            background-color: #ffffff;
            font-family: Raleway;
            width: 100%;
        }

        h1 {

            text-align: center;

        }

        input {

            padding: 10px;

            width: 100%;

            font-size: 17px;

            font-family: Raleway;

            border: 1px solid #aaaaaa;

        }

        /* Mark input boxes that gets an error on validation: */

        input.invalid {

            background-color: #ffdddd;

        }

        /* Hide all steps by default: */

        .tab {

            display: none;

        }

        .btn1 {

            background-color: #95f3ff;

        }

        .btn1:hover {

            background-color: #e0f7fa;

        }

        button {

            background-color: #04AA6D;

            color: #ffffff;

            border: none;

            padding: 10px 20px;

            font-size: 17px;

            font-family: Raleway;

            cursor: pointer;

        }

        button:hover {

            opacity: 0.8;

        }

        #prevBtn {

            background-color: #bbbbbb;

        }

        /* Make circles that indicate the steps of the form: */

        .step {

            height: 15px;

            width: 15px;

            margin: 0 2px;

            background-color: #bbbbbb;

            border: none;

            border-radius: 50%;

            display: inline-block;

            opacity: 0.5;

        }

        .step.active {

            opacity: 1;

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

        select.custom-select.custom-select-sm.form-control.form-control-sm {
            margin-top: 3px;
        }

        .left-bar p {
            margin: 4% !important;
        }

        .activesle {
            background: #6741D5 !important;
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
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Store Stock Report</li>
                </ol>
                <?php //echo $matid;exit;
                ?>
                <div class="row">
                    <div class="container">
                        <div class="col-xl-12 col-md-12 col-sm-12 mb-2">
                            <div class="row">
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">

                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">

                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="addbtn extra p-0">
                                        <a href="{{ url('Report/storestockreport') }}" class="btn btn-info mr-1 btn-sm"> <i
                                                class="fa fa-arrow-left"></i></a>
                                        <a href="{{ url('Report/storestockreport') }}" class="btn btn-info btn-sm"> <i
                                                class="fa fa-home"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form action="{{ url('Report/filtered-details/' . $matid) }}" method="POST">
                            @csrf
                            <div class="row filter">
                                <?php
                                $fromdate = isset($fromdate) && $fromdate != '' ? $fromdate : date('Y-m-d', strtotime('-1 month'));
                                $todate = isset($todate) && $todate != '' ? $todate : date('Y-m-d');
                                ?>
                                <div class="col-2 mb-3">
                                    <label for="" class="form-label">Date From</label>
                                    <input type="date" name="from_date"
                                        value="{{ isset($fromdate) && $fromdate != '' ? $fromdate : '' }}"
                                        class="form-control form-control-sm">
                                </div>
                                <div class="col-2 mb-3">
                                    <label for="" class="form-label">Date To</label>
                                    <input type="date" name="to_date"
                                        value="{{ isset($todate) && $todate != '' ? $todate : '' }}"
                                        class="form-control form-control-sm">
                                </div>
                                <div class="col-2 mb-3">
                                    <label for="" class="form-label">Project</label>
                                    <select name="Manufacturing_Unit" class="form-select form-select-sm">
                                        <option value="" selected>Select</option>
                                        @foreach ($Manufacturing_unit as $val)
                                            <option value="{{ $val->id }}"
                                                {{ isset(request()->Manufacturing_Unit) && request()->Manufacturing_Unit == $val->id ? 'selected' : '' }}>
                                                {{ $val->pname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2 mb-3">
                                    <label for="" class="form-label">Sub Project</label>
                                    <select name="Plant_Name" id="Plant_Name" class="form-select form-select-sm">
                                        <option value="" selected>Select</option>
                                        @foreach ($plant_name as $val)
                                            <option value="{{ $val->id }}"
                                                {{ isset(request()->Plant_Name) && request()->Plant_Name == $val->id ? 'selected' : '' }}>
                                                {{ $val->spname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2 mb-3">
                                    <label for="" class="form-label">Material Name</label>
                                    <select name="Material_Name"
                                        class="form-select form-select-sm js-example-matcher-start">
                                        <option value="" disabled selected>Select</option>
                                        <option value="all"
                                            {{ isset($Materialss) && $Materialss === 'all' ? 'selected' : '' }}>All
                                        </option>
                                        <?php $RepeatData = []; ?>
                                        @php
                                            $matid = request()->route('id');
                                        @endphp

                                        @if (isset($matid))
                                            @foreach ($Material_Name as $val)
                                                <option value="{{ isset($val->id) && $val->id != '' ? $val->id : '' }}"
                                                    {{ isset($matid) && $matid == $val->id ? 'selected' : '' }}>
                                                    {{ isset($val->material_name) && $val->material_name != '' ? $val->material_name : '' }}
                                                </option>
                                            @endforeach
                                        @endif
                                        @if (isset($Materialss))
                                            @foreach ($Material_Name as $val)
                                                <option value="{{ isset($val->id) && $val->id != '' ? $val->id : '' }}"
                                                    {{ isset($Materialss) && $Materialss == $val->id ? 'selected' : '' }}>
                                                    {{ isset($val->material_name) && $val->material_name != '' ? $val->material_name : '' }}
                                                </option>
                                            @endforeach
                                        @endif

                                    </select>
                                </div>
                                <div class="col-2 mb-3">
                                    <label for="" class="form-label">Organization</label>
                                    <select name="Organization" class="form-select form-select-sm">
                                        <option value="" selected disabled>Select</option>
                                        @foreach ($Organization as $val)
                                            <option value="{{ $val->id }}"
                                                {{ isset(request()->Organization) && request()->Organization == $val->id ? 'selected' : '' }}>
                                                {{ isset($val->organisation) && $val->organisation != '' ? $val->organisation : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2 mb-3">
                                    <label for="" class="form-label">Godown</label>
                                    <select name="Godown_Name" id="Godown_Name"
                                        class="form-select form-select-sm js-example-matcher-start">
                                        <option value="" selected>Select</option>
                                        @foreach ($Godown_Name as $val)
                                            <option value="{{ $val->id }}"
                                                {{ isset(request()->Godown_Name) && request()->Godown_Name == $val->id ? 'selected' : '' }}>
                                                {{ isset($val->inventory_name) && $val->inventory_name != '' ? $val->inventory_name : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2 mt-4">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                    <a href="{{ url('Report/storestockreportdetails/' . $matid) }}"><button type="button"
                                            class="btn btn-secondary"><i class="fa fa-refresh"></i></button></a>
                                </div>

                            </div>
                        </form>


                        <div class="col-xl-12 col-lg-12 col-md-12 text-center">
                            <h6 class="text-center p-1" style="background-color: #e5e5e5;display: inline;">Store Stock
                                Report Details</h6>
                        </div>
                        <br>
                      <div class="tab-content" id="ex1-content">
    <div class="tab-pane fade show active" id="All" role="tabpanel" aria-labelledby="Alls">
        <div class="table-responsive">
            <table id="example2" class="table table-sm table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="th-sm">Sl No.</th>
                        <th class="th-sm">Material Name</th>
                        <th class="th-sm">UOM</th>
                        <th class="th-sm">LPP</th>
                        <th class="th-sm">From Project</th>
                        <th class="th-sm">From Sub Project</th>
                        <th class="th-sm">From Organization</th>
                        <th class="th-sm">From Godown</th>
                        <th class="th-sm">To Project</th>
                        <th class="th-sm">To Sub Project</th>
                        <th class="th-sm">To Organization</th>
                        <th class="th-sm">To Godown</th>
                        <th class="th-sm">Supplier Name</th>
                        <th class="th-sm">Mrn No.</th>
                        <th class="th-sm">Invoice No.</th>
                        <th class="th-sm">Mrn Date</th>
                        <th class="th-sm">Received Qty.</th>
                        <th class="th-sm">Receive Amt.</th>
                        <th class="th-sm">Issue Qty.</th>
                        <th class="th-sm">Issue Amt.</th>
                        <th class="th-sm">Transaction Date</th>
                        <th class="th-sm">Narration</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalQuantity = 0;
                        $totalAmount = 0;
                        $totalIssueQty = 0;
                        $totalIssuePrice = 0;
                        $sessionarr = [];

                        // Create a combined array with source tracking
                        $allMaterials = collect();

                        // Add received materials with source flag
                        foreach ($Receivedmaterials as $item) {
                            $item->source_type = 'received';
                            $allMaterials->push($item);
                        }

                        // Add issued materials with source flag
                        foreach ($Issuedmaterials as $item) {
                            $item->source_type = 'issued';
                            $allMaterials->push($item);
                        }
                    @endphp

                    @foreach ($allMaterials as $key => $val)
                    {{-- @php dd($allMaterials);@endphp --}}
                        @php
                            // Set defaults to avoid undefined variable errors
                            $fromProject = '';
                            $toProject = '';
                            $fromSubProject = '';
                            $toSubProject = '';
                            $orgFrom = '';
                            $orgTo = '';
                            $godownFrom = '';
                            $godownTo = '';
                            $receivedQty = '';
                            $receiveAmt = '';
                            $issueQty = '';
                            $issueAmt = '';
                            $transactionDate = '';
                            $narration = '';
                            $last_purchase_price = '';

                            // Check source type
                            $isReceived = $val->source_type === 'received';
                            $isIssued = $val->source_type === 'issued';
                        @endphp

                        @if ($isReceived)
                            @php
                                // Received material calculations
                                $totalQuantity +=
                                    isset($val->Quantity) && $val->Quantity != ''
                                        ? $val->Quantity
                                        : 0;
                                $totalAmount +=
                                    isset($val->Amount) && $val->Amount != ''
                                        ? $val->Amount
                                        : 0;

                                $lastPurchase = App\Models\Master\RawMaterial\Master_Raw_Material::select(
                                    'Rate',
                                )
                                    ->where('Material', $val->Material)
                                    ->orderBy('id', 'DESC')
                                    ->first();
                                $last_purchase_price = $lastPurchase
                                    ? $lastPurchase->Rate
                                    : null;

                                // For received materials - showing source (From)
                                $fromProject =
                                    isset($val->fromproject) && $val->fromproject != ''
                                        ? $val->fromproject
                                        : '0';
                                $fromSubProject =
                                    isset($val->fromsubproject) && $val->fromsubproject != ''
                                        ? $val->fromsubproject
                                        : '0';
                                $orgFrom =
                                    isset($val->from_org) && $val->from_org != ''
                                        ? $val->from_org
                                        : '0';
                                $godownFrom =
                                    isset($val->from_godown) && $val->from_godown != ''
                                        ? $val->from_godown
                                        : '0';
                                
                                // For received materials - To is typically store/inventory
                                $toProject = '0';
                                $toSubProject = '0';
                                $orgTo = 'N/A'; // Same organization
                                $godownTo = 'N/A'; // Same godown for storage
                                
                                $receivedQty =
                                    isset($val->Quantity) && $val->Quantity != ''
                                        ? $val->Quantity
                                        : '';
                                $receiveAmt =
                                    isset($val->Amount) && $val->Amount != ''
                                        ? $val->Amount
                                        : '';
                                $transactionDate =
                                    isset($val->Date) && $val->Date != '' ? $val->Date : '';
                                $narration = isset($val->through) && $val->through != ''
                                            ? $val->through
                                            : '';
                            @endphp
                        @endif

                        @if ($isIssued)
                            @php
                                // Issued material calculations
                                $totalIssueQty +=
                                    (isset($val->issueQTY) && $val->issueQTY != ''
                                        ? $val->issueQTY
                                        : 0) +
                                    (isset($val->purchase_qty) && $val->purchase_qty != ''
                                        ? $val->purchase_qty
                                        : 0);

                                $lastPurchase = App\Models\Master\RawMaterial\Master_Raw_Material::select(
                                    'Rate',
                                    'GST',
                                )
                                    ->where('Material', $val->Material_id)
                                    ->orderBy('id', 'DESC')
                                    ->first();
                                $last_purchase_price = $lastPurchase
                                    ? $lastPurchase->Rate
                                    : null;
                                $last_purchase_gst = $lastPurchase ? $lastPurchase->GST : null;

                                $qty =
                                    (isset($val->issueQTY) && $val->issueQTY != ''
                                        ? $val->issueQTY
                                        : 0) +
                                    (isset($val->purchase_qty) && $val->purchase_qty != ''
                                        ? $val->purchase_qty
                                        : 0);
                                $issueprice = $qty * ($last_purchase_price + ($last_purchase_price * $last_purchase_gst / 100));
                                $totalIssuePrice +=
                                    isset($issueprice) && $issueprice != '' ? $issueprice : 0;

                                // For issued materials - From is store/inventory
                                $fromProject =
                                    isset($val->fromproject) && $val->fromproject != ''
                                        ? $val->fromproject
                                        : 'N/A';
                                $fromSubProject =
                                    isset($val->fromsubproject) && $val->fromsubproject != ''
                                        ? $val->fromsubproject
                                        : 'N/A';
                                $orgFrom = isset($val->from_org) && $val->from_org != ''
                                    ? $val->from_org
                                    : 'N/A';
                                $godownFrom = isset($val->from_godown) && $val->from_godown != ''
                                    ? $val->from_godown
                                    : 'N/A';

                                // For issued materials - To is the destination
                                $toProject =
                                    isset($val->toproject) && $val->toproject != '' ? $val->toproject : 'N/A';
                                $toSubProject =
                                    isset($val->tosubproject) && $val->tosubproject != ''
                                        ? $val->tosubproject
                                        : 'N/A';
                                $orgTo =
                                    isset($val->to_org) && $val->to_org != ''
                                        ? $val->to_org
                                        : 'N/A';
                                $godownTo =
                                    isset($val->to_godown) && $val->to_godown != ''
                                        ? $val->to_godown
                                        : 'N/A';

                                $issueQty =
                                    isset($val->issueQTY) && $val->issueQTY != ''
                                        ? $val->issueQTY
                                        : (isset($val->purchase_qty) && $val->purchase_qty != ''
                                            ? $val->purchase_qty
                                            : 0);
                                $issueAmt =
                                    isset($issueprice) && $issueprice != '' ? $issueprice : '';
                                $transactionDate =
                                    isset($val->purchase_qty) && $val->purchase_qty != ''
                                        ? (isset($val->purchahedate) && $val->purchahedate != ''
                                            ? $val->purchahedate
                                            : '')
                                        : (isset($val->created_at) && $val->created_at != ''
                                            ? $val->created_at
                                            : '');
                                $narration =
                                    isset($val->purchase_qty) && $val->purchase_qty != ''
                                        ? 'Debit From Store Transfer'
                                        : 'Debit From Store Issue';
                            @endphp
                        @endif

                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ isset($val->matname) && $val->matname != '' ? $val->matname : '' }}</td>
                            <td>{{ isset($val->UOM) && $val->UOM != '' ? $val->UOM : '' }}</td>
                            <td>{{ isset($last_purchase_price) && $last_purchase_price != '' ? $last_purchase_price : '' }}</td>
                            <td>{{ !empty($fromProject) ? $fromProject : 'N/A' }}</td>
                            <td>{{ !empty($fromSubProject) ? $fromSubProject : 'N/A' }}</td>
                            <td>{{ !empty($orgFrom) ? $orgFrom : 'N/A' }}</td>
                            <td>{{ !empty($godownFrom) ? $godownFrom : 'N/A' }}</td>
                            <td>{{ !empty($toProject) ? $toProject : 'N/A' }}</td>
                            <td>{{ !empty($toSubProject) ? $toSubProject : 'N/A' }}</td>
                            <td>{{ !empty($orgTo) ? $orgTo : 'N/A' }}</td>
                            <td>{{ !empty($godownTo) ? $godownTo : 'N/A' }}</td>
                            <td>{{ isset($val->Supplier_Name) && $val->Supplier_Name != '' ? $val->Supplier_Name : 'N/A' }}</td>
                            <td>{{ isset($val->Mrn_No) && $val->Mrn_No != '' ? $val->Mrn_No : 'N/A' }}</td>
                            <td>{{ isset($val->Invoice_No) && $val->Invoice_No != '' ? $val->Invoice_No : 'N/A' }}</td>
                            <td>{{ isset($val->Mrn_Date) && $val->Mrn_Date != '' ? $val->Mrn_Date : 'N/A' }}</td>
                            <td>{{ !empty($receivedQty) ? $receivedQty : 0 }}</td>
                            <td>{{ !empty($receiveAmt) ? $receiveAmt : 0 }}</td>
                            <td>{{ !empty($issueQty) ? $issueQty : 0 }}</td>
                            <td>{{ !empty($issueAmt) ? $issueAmt : 0 }}</td>
                            <td>{{ $transactionDate }}</td>
                            <td>{{ $narration }}</td>
                        </tr>
                    @endforeach

                    @php
                        Session::put('nexdata', $sessionarr);
                    @endphp
                </tbody>
                
                <!-- Move totals row to tfoot for better DataTable compatibility -->
                <tfoot>
                    <tr class="table-info">
                        <td colspan="16"><strong>Total</strong></td>
                        <td><strong>{{ number_format($totalQuantity, 2) }}</strong></td>
                        <td><strong>{{ number_format($totalAmount, 2) }}</strong></td>
                        <td><strong>{{ number_format($totalIssueQty, 2) }}</strong></td>
                        <td><strong>{{ number_format($totalIssuePrice, 2) }}</strong></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            <br>
        </div>
    </div>
</div>
                    </div>
                </div>
            </section>
        </div>

    </div>


@endsection
@push('custom-scripts')
    <script>
        $(document).ready(function() {
            activeclass(20, 1);
        });
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
        var tableID = 15;

        function checkBoxess() {
            var checkedColumns = document.querySelectorAll('.form-check-input:checked');
            var columnNamesToShow = [];

            checkedColumns.forEach(function(checkbox) {
                columnNamesToShow.push(checkbox.value);
            });

            var tabledata = document.querySelectorAll('table');

            tabledata.forEach(function(table) {
                var rows = table.querySelectorAll('tr');

                if (checkedColumns.length === 0) {
                    for (var i = 0; i < rows.length; i++) {
                        var cells = rows[i].querySelectorAll('td');
                        for (var j = 0; j < cells.length; j++) {
                            cells[j].style.display = '';
                        }
                    }

                    var thead = table.querySelector('thead');
                    if (thead) {
                        var thElements = thead.querySelectorAll('th');
                        for (var k = 0; k < thElements.length; k++) {
                            thElements[k].style.display = '';
                        }
                    }
                } else {
                    for (var i = 0; i < rows.length; i++) {
                        var cells = rows[i].querySelectorAll('td');
                        for (var j = 0; j < cells.length; j++) {
                            var columnName = table.querySelector('thead th:nth-child(' + (j + 1) + ')').innerText;
                            if (columnNamesToShow.indexOf(columnName) !== -1) {
                                cells[j].style.display = '';
                            } else {
                                cells[j].style.display = 'none';
                            }
                        }
                    }

                    var thead = table.querySelector('thead');
                    if (thead) {
                        var thElements = thead.querySelectorAll('th');
                        for (var k = 0; k < thElements.length; k++) {
                            var columnName = thElements[k].innerText;
                            if (columnNamesToShow.indexOf(columnName) !== -1) {
                                thElements[k].style.display = '';
                            } else {
                                thElements[k].style.display = 'none';
                            }
                        }
                    }
                }
            });

            var CollumValue = columnNamesToShow.join(',');

            fetch("{{ url('ProductionProcess/getCheckBoxData') }}?ID=" + tableID, {
                    method: 'GET',
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.columns) {
                        try {
                            var existingData = data.columns;
                            if (JSON.stringify(existingData) !== JSON.stringify(columnNamesToShow)) {
                                fetch("{{ url('ProductionProcess/CheckBoxStore') }}", {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        },
                                        body: JSON.stringify({
                                            id: tableID,
                                            columns: CollumValue,
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

            fetch("{{ url('ProductionProcess/getCheckBoxData') }}?ID=" + tableID, {
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
    <script>
        $(document).ready(function() {
            $.ajax({
                url: "{{ url('ProductionProcess/CheckHoldExpiry') }}",
                method: 'GET',
                success: function(response) {
                    response.forEach(function(lead) {
                        if (lead.action === 'HOLD' && lead.status === 1) {
                            var currentDate = new Date();
                            var holdDate = new Date(lead.days_for_holding);

                            if (holdDate < currentDate) {
                                UpdateStatus(lead.Production_Process_id, lead.userID);
                            }
                        }
                    });
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        });

        function UpdateStatus(Production_Process_id, userID) {
            $.ajax({
                url: "{{ url('ProductionProcess/UpdateStatus') }}",
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                data: JSON.stringify({
                    Production_Process_id: Production_Process_id,
                    userID: userID
                }),
                success: function(response) {
                    $('#statuss' + Production_Process_id).html('<span style="color: #FF9000;">Pending</span>');
                }
            });
        }
    </script>
@endpush
