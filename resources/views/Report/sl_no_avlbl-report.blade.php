@extends('layout.main')
@section('main-container')
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
<title>Available Serial Numbers Report</title>

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

    input.invalid {
        background-color: #ffdddd;
    }

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

    table#example {
        border: 1px solid #111;
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
        z-index: 999;
        padding: 10px 15px;
        box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
        right: 0px;
        border-radius: 5px;
        border: 1px solid #ddd;
        top: 45px;
        width: 250px;
        max-height: 400px;
        overflow-y: auto;
    }

    .FilterButtonnn {
        position: relative;
        display: inline-block;
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

    /* Select2 Custom Styling */
    .select2-container--default .select2-selection--single {
        height: 31px !important;
        border: 1px solid #ced4da !important;
        border-radius: 0.375rem !important;
        font-size: 0.875rem !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 29px !important;
        padding-left: 8px !important;
        color: #495057 !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 29px !important;
        right: 8px !important;
    }

    .select2-dropdown {
        border: 1px solid #ced4da !important;
        border-radius: 0.375rem !important;
        font-size: 0.875rem !important;
    }

    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 1px solid #ced4da !important;
        border-radius: 0.375rem !important;
        padding: 6px 8px !important;
        font-size: 0.875rem !important;
    }
</style>

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
                <li class="breadcrumb-item">Available Serial Numbers Report</li>
            </ol>
            <div class="addbtn extra pt-3">
                <form action="{{url('Report/exportdata_serial')}}" method="GET" style="display:inline;">
                    <input type="hidden" name="from_date" value="{{ request('from_date', $fromdate ?? '') }}">
                    <input type="hidden" name="to_date" value="{{ request('to_date', $todate ?? '') }}">
                    <input type="hidden" name="material_name" value="{{ request('material_name') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="source" value="{{ request('source') }}">
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    <button type="submit" style="background:none; border:none; cursor:pointer; padding:0; margin:0;" title="Export to Excel">
                        <i class="fas fa-file-excel" style="font-size: 16px; color: green; margin-top: 8px; margin-right: 8px;"></i>
                    </button>
                </form>
                <a href="{{ url('Report/sl_no_avlbl-report') }}" class="btn btn-info mr-1 btn-sm"> <i
                        class="fa fa-arrow-left"></i></a>
                <a href="{{ url('Report/sl_no_avlbl-report') }}" class="btn btn-info btn-sm"> <i
                        class="fa fa-home"></i></a>
            </div>

            <div class="row">
                <div class="container">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    <form action="{{url('Report/sl_no_avlbl-report')}}" method="GET" id="reportForm">
                        <div class="row filter">
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">Date From</label>
                                <input type="date" name="from_date" id="from_date" value="{{ request('from_date', $fromdate ?? \Carbon\Carbon::now()->format('Y-m-d')) }}" class="form-control form-control-sm">
                            </div>
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">Date To</label>
                                <input type="date" name="to_date" id="to_date" value="{{ request('to_date', $todate ?? \Carbon\Carbon::now()->format('Y-m-d')) }}" class="form-control form-control-sm">
                            </div>
                            <div class="col-2 mb-3">
                                <label for="" class="form-label fw-bold">Material Name</label>
                                <select name="material_name" class="form-select form-select-sm" id="materialSelect">
                                    <option value="">Select Material</option>
                                    
                                    
                                    @foreach($materials as $val)
                                        <option value="{{ $val->material_name }}"
                                            {{ request('material_name') == $val->material_name ? 'selected' : '' }}>
                                            {{ $val->material_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2 mb-3">
                                <label for="" class="form-label fw-bold">Source</label>
                                <select name="source" class="form-select form-select-sm" id="sourceSelect">
                                    <option value="">Select Sources</option>
                                   
                                    
                                    @foreach($sources as $val)
    <option value="{{ $val->source }}"
        {{ request('source') == $val->source ? 'selected' : '' }}>
        {{ $val->source }}
    </option>
@endforeach
                                </select>
                            </div>
                            <div class="col-1 mb-3">
                                <label for="" class="form-label">Status</label>
                                    <select name="status" class="form-select form-select-sm" >
                                        <option value="" selected>All</option>
                                        <option value="APPROVE" {{isset(request()->status) && request()->status=='APPROVE'?'selected':''}}>APPROVE</option>
                                        <option value="PENDING" {{isset(request()->status) && request()->status=='PENDING'?'selected':''}}>PENDING</option>
                                        <option value="HOLD" {{isset(request()->status) && request()->status=='HOLD'?'selected':''}}>HOLD</option>
                                        <option value="RECHECK" {{isset(request()->status) && request()->status=='RECHECK'?'selected':''}}>RECHECK</option>
                                        <option value="REJECT" {{isset(request()->status) && request()->status=='REJECT'?'selected':''}}>REJECT</option>
                                        
                                        <!--<option value="">All</option>-->
                                        <!--<option value="APPROVE" {{ request('status', 'APPROVE') == 'APPROVE' ? 'selected' : '' }}>APPROVE</option>-->
                                        <!--<option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>PENDING</option>-->
                                        <!--<option value="HOLD" {{ request('status') == 'HOLD' ? 'selected' : '' }}>HOLD</option>-->
                                        <!--<option value="RECHECK" {{ request('status') == 'RECHECK' ? 'selected' : '' }}>RECHECK</option>-->
                                        <!--<option value="REJECT" {{ request('status') == 'REJECT' ? 'selected' : '' }}>REJECT</option>-->
                                    </select>
                            </div>
                            <div class="col-2 mb-3">
                                <label for="" class="form-label">Search</label>
                                <input type="text" 
                                       name="search" 
                                       class="form-control form-control-sm" 
                                       placeholder="Search..." 
                                       value="{{ $searchTerm }}">
                            </div>
                            <div class="col-3 mt-4" style="white-space: nowrap;">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                <a href="{{url('Report/sl_no_avlbl-report')}}"><button type="button" class="btn btn-secondary"><i class="fa fa-refresh"></i></button></a>
                                <button type="button" class="btn btn-success" id="MyToggle">
                                    <i class="fa fa-filter"></i>
                                </button>
                                <div class="FilterButtonnn">
                                    <div id="myFilter">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="ToggleCheck" onclick="toggleCheckboxes()">
                                            <label class="form-check-label fw-bold" for="ToggleCheck">Select All</label>
                                        </div>
                                        <hr>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="sl_no" value="SL No" onclick="filterTable(this)">
                                            <label class="form-check-label" for="sl_no">SL No</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="material_name" value="Material Name" onclick="filterTable(this)">
                                            <label class="form-check-label" for="material_name">Material Name</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="source" value="Source" onclick="filterTable(this)">
                                            <label class="form-check-label" for="source">Source</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="serial_no" value="Serial No" onclick="filterTable(this)">
                                            <label class="form-check-label" for="serial_no">Serial No</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="hsn" value="HSN" onclick="filterTable(this)">
                                            <label class="form-check-label" for="hsn">HSN</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="uom" value="UOM" onclick="filterTable(this)">
                                            <label class="form-check-label" for="uom">UOM</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="batch_no" value="Batch No" onclick="filterTable(this)">
                                            <label class="form-check-label" for="batch_no">Batch No</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="invoice_date" value="Invoice Date / Manufacturing Date" onclick="filterTable(this)">
                                            <label class="form-check-label" for="invoice_date">Invoice Date / Manufacturing Date</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="invoice_no" value="Invoice No" onclick="filterTable(this)">
                                            <label class="form-check-label" for="invoice_no">Invoice No</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="status" value="Status" onclick="filterTable(this)">
                                            <label class="form-check-label" for="status">Status</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Pagination Info -->  
                    <div class="row mb-3">
                        <div class="col-md-12 text-end">
                            <small class="text-muted">
                                Showing {{ $AvailableSerials->firstItem() ?? 0 }} to {{ $AvailableSerials->lastItem() ?? 0 }} of {{ $AvailableSerials->total() }} records
                                @if(!empty($searchTerm))
                                    (filtered from search: "{{ $searchTerm }}")
                                @endif
                            </small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered w-00">
                                <thead>
                                    <tr>
                                        <th class="th-sm">SL No</th>
                                        <th class="th-sm">Material Name</th>
                                        <th class="th-sm">Source</th>
                                        <th class="th-sm">Serial No</th>
                                        <th class="th-sm">HSN</th>
                                        <th class="th-sm">UOM</th>
                                        <th class="th-sm">Batch No</th>
                                        <th class="th-sm">Invoice Date / Manufacturing Date</th>
                                        <th class="th-sm">Invoice No</th>
                                        <th class="th-sm">Status</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @if($AvailableSerials->count() > 0)
                                        @foreach($AvailableSerials as $key => $val)
                                        <tr>
                                            <td>{{ ($AvailableSerials->currentPage() - 1) * $AvailableSerials->perPage() + $key + 1 }}</td>
                                            <td>{{ $val->matname ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-info text-dark">{{ $val->source ?? 'N/A' }}</span>
                                            </td>
                                            <td><strong style="color: blue;">{{ $val->serial_no }}</strong></td>
                                            <td>{{ $val->HSN_Code ?? 'N/A' }}</td>
                                            <td>{{ $val->UOM ?? 'N/A' }}</td>
                                            <td>{{ $val->batch_no ?? 'N/A' }}</td>
                                            <td>{{ $val->invoice_date ? \Carbon\Carbon::parse($val->invoice_date)->format('d-m-Y') : 'N/A' }}</td>
                                            <td>{{ $val->invoice_no ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge {{ $val->status == 'APPROVE' ? 'bg-success' : ($val->status == 'REJECT' ? 'bg-danger' : ($val->status == 'HOLD' ? 'bg-warning text-dark' : ($val->status == 'RECHECK' ? 'bg-info text-dark' : 'bg-secondary'))) }}">
                                                    {{ $val->status ?? 'PENDING' }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="10" class="text-center text-danger fw-bolder">!!! NO AVAILABLE SERIAL NUMBERS FOUND !!!</td>
                                        </tr>
                                    @endif

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Laravel Built-in Pagination -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <nav aria-label="Page navigation">
                                <div class="d-flex justify-content-center">
                                    {{ $AvailableSerials->links('pagination::bootstrap-4') }}
                                </div>
                            </nav>
                            
                            <!-- Page Info -->
                            <div class="text-center mt-2">
                                <small class="text-muted">
                                    Page {{ $AvailableSerials->currentPage() }} of {{ $AvailableSerials->lastPage() }} 
                                    ({{ $AvailableSerials->total() }} total records)
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
    <br><br>
</div>
@endsection

@push('custom-scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // DEBUG: Console logging for live server debugging
    console.log('========== SERIAL NUMBER REPORT DEBUG ==========');
    console.log('Page loaded successfully');
    console.log('Total Records: {{ $AvailableSerials->total() }}');
    console.log('Current Page: {{ $AvailableSerials->currentPage() }}');
    console.log('Per Page: {{ $AvailableSerials->perPage() }}');
    console.log('Has Records: {{ $AvailableSerials->count() > 0 ? "YES" : "NO" }}');
    console.log('From Date Filter: {{ request("from_date") ?? "not set" }}');
    console.log('To Date Filter: {{ request("to_date") ?? "not set" }}');
    console.log('Search Term: {{ $searchTerm ?? "empty" }}');
    console.log('========== END DEBUG ==========');
    
    // Simple table with custom pagination - no DataTables
    
    // Initialize Select2 for searchable dropdowns (optional)
    $('#materialSelect').select2({
        placeholder: "Search material",
        allowClear: true,
        width: '100%'
    });

    $('#sourceSelect').select2({
        placeholder: "Search source",
        allowClear: true,
        width: '100%'
    });

    // Initialize active class
    activeclass(20, 4);
});

// Export functions (can be implemented later if needed)
function exportTable(type) {
    console.log('Export function - ' + type + ' - to be implemented');
}

// Column Filter Toggle
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

function toggleCheckboxes() {
    var checkboxes = document.querySelectorAll('.form-check-input');
    var toggleCheckbox = document.getElementById('ToggleCheck');

    checkboxes.forEach(function(checkbox) {
        if (checkbox !== toggleCheckbox) {
            checkbox.checked = toggleCheckbox.checked;
        }
    });

    checkBoxess();
}

function filterTable(checkbox) {
    var checkboxes = document.querySelectorAll('.form-check-input');
    var toggleCheckbox = document.getElementById('ToggleCheck');

    var allChecked = true;

    checkboxes.forEach(function(cb) {
        if (cb !== toggleCheckbox && !cb.checked) {
            allChecked = false;
        }
    });

    toggleCheckbox.checked = allChecked;

    checkBoxess();
}

var tableID = 97;

function checkBoxess() {
    var checkedColumns = document.querySelectorAll('.form-check-input:checked');
    var columnNamesToShow = [];

    checkedColumns.forEach(function(checkbox) {
        if (checkbox.id !== 'ToggleCheck') {
            columnNamesToShow.push(checkbox.value);
        }
    });

    var tabledata = document.querySelectorAll('table');

    tabledata.forEach(function(table) {
        var rows = table.querySelectorAll('tr');

        if (checkedColumns.length === 0 || (checkedColumns.length === 1 && checkedColumns[0].id === 'ToggleCheck')) {
            // Show all columns
            for (var i = 0; i < rows.length; i++) {
                var cells = rows[i].querySelectorAll('td, th');
                for (var j = 0; j < cells.length; j++) {
                    cells[j].style.display = '';
                }
            }
        } else {
            // Show only selected columns
            for (var i = 0; i < rows.length; i++) {
                var cells = rows[i].querySelectorAll('td, th');
                for (var j = 0; j < cells.length; j++) {
                    var columnName = table.querySelector('thead th:nth-child(' + (j + 1) + ')').innerText;
                    if (columnNamesToShow.indexOf(columnName) !== -1) {
                        cells[j].style.display = '';
                    } else {
                        cells[j].style.display = 'none';
                    }
                }
            }
        }
    });

    // Save preferences
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

// Load saved column preferences
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
                    if (checkbox.id !== 'ToggleCheck' && columnNamesToShow.indexOf(checkbox.value) !== -1) {
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

    // Date range validation for maximum 1 month (30 days)
    // But allow search to bypass this restriction
    $('#reportForm').on('submit', function(e) {
        var fromDate = $('#from_date').val();
        var toDate = $('#to_date').val();
        var searchTerm = $('input[name="search"]').val();
        
        // Only validate if both dates are filled and no search term entered
        if (fromDate && toDate && !searchTerm) {
            var from = new Date(fromDate);
            var to = new Date(toDate);
            var diffTime = Math.abs(to - from);
            var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            if (diffDays > 30) {
                e.preventDefault();
                alert('Please select a date range of maximum 1 month (30 days) for better performance.\n\nNote: You can search beyond 1 month by using the search field to find specific serial numbers.');
                return false;
            }
        }
    });
    
    // Show helpful message when user changes dates
    $('#from_date, #to_date').on('change', function() {
        var fromDate = $('#from_date').val();
        var toDate = $('#to_date').val();
        var searchTerm = $('input[name="search"]').val();
        
        if (fromDate && toDate && !searchTerm) {
            var from = new Date(fromDate);
            var to = new Date(toDate);
            var diffTime = Math.abs(to - from);
            var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            if (diffDays > 30) {
                $(this).css('border-color', 'red');
            } else {
                $('#from_date, #to_date').css('border-color', '');
            }
        } else {
            $('#from_date, #to_date').css('border-color', '');
        }
    });

});
</script>

@endpush
