@extends('layout.main')
@section('main-container')
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <title>Finished Good Material List</title>
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

        .btn1 {
            background-color: #95f3ff;
        }

        .btn1:hover {
            background-color: #e0f7fa;
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
        .tab1 {
			padding: 20px;
			border: 1px solid #a8adb1;
			border-radius: 10px;
		}
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    @php
        $Department = Session::get('Department');
        $EXT = Session::get('EXT');
    @endphp
    @if(session('error'))
    <script>
        alert("{{ session('error') }}");
    </script>
    @endif

    @if(session('active_tab') == 'formdata')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // hide tabledata and show formdata
                document.getElementById("tabledata").style.display = "none";
                document.getElementById("formdata").style.display = "block";
            });
        </script>
    @endif
    
    <div class="card-form">
        <div class="app-content">
            <section class="section">
                <div class="row">
                    <div class="container">
                        <br>
                        <div class="tab2" id="formdata" style="display: none;">
                            <div class="tabs">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-6">
                                                <h5>Add Finished Good</h5>
                                            </div>
                                            <div class="col-6">
                                                <button type="button" class="btn btn1 float-right" style="margin: 5px;">Show Finished Good</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>

                                <div class="tab1">
                                    <div class="row">
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 bdr p-4">
                                            <form action="{{ url('FinishedGood/AddFinishedGoodGatepass') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="edit" value="{{ old('edit', $edit->id ?? '') }}">

                                                <div class="row my-2">
                                                    {{-- Unit Name --}}
                                                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                                                        <label>Unit Name<span class="text-danger">*</span></label>
                                                        <select name="Unit_Name" id="Manunit" class="form-select form-select-sm" required>
                                                            <option value="" disabled {{ old('Unit_Name', $edit->Unit_Name ?? '') == '' ? 'selected' : '' }}>Select</option>
                                                            @foreach ($Manufacturing_unit as $val)
                                                                <option value="{{ $val->id }}" {{ old('Unit_Name', $edit->Unit_Name ?? '') == $val->id ? 'selected' : '' }}>
                                                                    {{ $val->pname }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('Unit_Name') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>

                                                    {{-- Plant Name --}}
                                                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                                                        <label>Plant Name<span class="text-danger">*</span></label>
                                                        <select name="Plant_Name" id="Plant_Name" class="form-select form-select-sm" required>
                                                            <option value="" disabled {{ old('Plant_Name', $edit->Plant_Name ?? '') == '' ? 'selected' : '' }}>Select</option>
                                                            @if (isset($plant_name))
                                                                @foreach ($plant_name as $val)
                                                                    <option value="{{ $val->id }}" {{ old('Plant_Name', $edit->Plant_Name ?? '') == $val->id ? 'selected' : '' }}>
                                                                        {{ $val->spname }}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        @error('Plant_Name') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>

                                                    {{-- Organization Name --}}
                                                    <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 form-group">
                                                        <label>Organization Name<span class="text-danger">*</span></label>
                                                        <select name="Organization_Name" class="form-select form-select-sm" required>
                                                            <option value="" disabled {{ old('Organization_Name', request()->Organization ?? '') == '' ? 'selected' : '' }}>Select</option>
                                                            @foreach ($Orgs as $val)
                                                                <option value="{{ $val->id }}" {{ old('Organization_Name', $edit->Organization_Name ?? '') == $val->id ? 'selected' : '' }}>
                                                                    {{ $val->organisation ?? '' }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('Organization_Name') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>

                                                    {{-- Transaction Date --}}
                                                    <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 form-group">
                                                        <label>Transaction Date<span class="text-danger">*</span></label>
                                                        <input type="date" id="Transaction_date" name="Transaction_Date"
                                                            class="form-control form-control-sm" required
                                                            value="{{ old('Transaction_Date', $edit->Transaction_Date ?? '') }}">
                                                        @error('Transaction_Date') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                    <!--<div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 form-group">-->
                                                    <!--    <label>From Godown<span class="text-danger">*</span></label>-->
                                                        
                                                    <!--    <select name="Godown_Name" class="form-select form-select-sm js-example-matcher-start" required>-->
                                                    <!--        <option value="" selected disabled>Select</option>-->
                                                    <!--        @foreach($Godown_Name as $val)-->
                                                    <!--        <option value="{{$val->id}}" {{isset($edit->Godown_Name) && $edit->Godown_Name==$val->id?'selected':''}}>{{$val->inventory_name}}</option>-->
                                                    <!--        @endforeach-->
                                                    <!--    </select>-->
                                                    <!--    @error('Godown_Name') <span class="text-danger">{{ $message }}</span> @enderror-->
                                                    <!--</div>-->
                                                    <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 form-group">
                                                        <label>From Godown<span class="text-danger">*</span></label>
                                                        
                                                        <select name="Godown_Name" class="form-select form-select-sm js-example-matcher-start" required>
                                                            <option value="" {{ old('Godown_Name', $edit->Godown_Name ?? '') == '' ? 'selected' : '' }} disabled>Select</option>
                                                            @foreach($Godown_Name as $val)
                                                            <option value="{{$val->id}}" {{ old('Godown_Name', $edit->Godown_Name ?? '') == $val->id ? 'selected' : '' }}>{{$val->inventory_name}}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('Godown_Name') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>

                                                {{-- Finished Good Section --}}
                                                <div class="border p-2 d-flex flex-wrap align-items-center">
                                                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                                                        <label>Finished Good(FG)<span class="text-danger">*</span></label>
                                                        <select name="Raw_Material" class="form-select form-select-sm" id="RawMaterials" required {{ isset($edit->Raw_Material) ? 'disabled' : '' }}>
                                                            <option value="" disabled {{ old('Raw_Material', $edit->Raw_Material ?? '') == '' ? 'selected' : '' }}>Select</option>
                                                            @foreach ($Raw_Material as $val)
                                                                <option value="{{ $val->RawMaterial->id }}" {{ old('Raw_Material', $edit->Raw_Material ?? '') == $val->RawMaterial->id ? 'selected' : '' }}>
                                                                    {{ $val->RawMaterial->matname }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('Raw_Material') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>

                                                    {{-- HSN Code --}}
                                                    <div class="col-xl-1 col-lg-2 col-md-4 col-sm-12 form-group">
                                                        <label>HSN Code</label>
                                                        <input readonly type="number" name="HSN_Code" id="HSNCode" placeholder="HSN"
                                                            value="{{ old('HSN_Code', $edit->HSN_Code ?? '') }}" class="form-control form-control-sm">
                                                    </div>

                                                    {{-- UOM --}}
                                                    <div class="col-xl-1 col-lg-2 col-md-4 col-sm-12 form-group">
                                                        <label>UOM</label>
                                                        <input readonly type="text" name="UOM" id="uom"
                                                            value="{{ old('UOM', $edit->UOM ?? '') }}" class="form-control form-control-sm">
                                                    </div>

                                                    {{-- Rate --}}
                                                    <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12 form-group">
                                                        <label>Rate<span class="text-danger">*</span></label>
                                                        <input 
                                                            type="text" name="Rate" id="Rate" value="{{ old('Rate', $edit->Rate ?? '') }}" class="form-control form-control-sm" required
                                                            inputmode="decimal" pattern="^\d*\.?\d*$" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1')"
                                                        >
                                                        @error('Rate') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                    
                                                    <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12 form-group">
                                                        <label>Quantity<span class="text-danger">*</span></label>
                                                        <input 
                                                            type="text" name="Quantity" id="Quantity" value="{{ old('Quantity', $edit->Quantity ?? '') }}" class="form-control form-control-sm" 
                                                            required onchange="checkAndGenerateRows()" {{ isset($edit->Quantity) ? 'readonly' : '' }} inputmode="decimal"
                                                            pattern="^\d*\.?\d*$"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1')"
                                                        >
                                                        @error('Quantity') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>

                                                    {{-- GST --}}
                                                    <div class="col-xl-1 col-lg-1 col-md-3 col-sm-12 form-group">
                                                        <label>GST<span class="text-danger">*</span></label>
                                                        <select name="gst" id="GST" class="form-select form-select-sm" required>
                                                            <option value="" disabled {{ old('gst', $edit->GST ?? '') == '' ? 'selected' : '' }}>Select</option>
                                                            @foreach ($GST as $val)
                                                                <option value="{{ $val->GST_Percentage }}" {{ old('gst', $edit->GST ?? '') == $val->GST_Percentage ? 'selected' : '' }}>
                                                                    {{ $val->GST_Percentage }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('gst') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>

                                                    {{-- Total amount --}}
                                                    <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12 form-group">
                                                        <label>Total amount</label>
                                                        <input readonly type="text" name="Total_amount" id="Total_amount"
                                                            value="{{ old('Total_amount', '') }}" class="form-control form-control-sm">
                                                    </div>
                                                </div><br>

                                                {{-- Serial Number Table --}}
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-bordered" style="width:100%">
                                                            <tr>
                                                                <th>SL. No.</th>
                                                                <th>Serial No</th>
                                                                <th>Supplier</th>
                                                                <th>DOP</th>
                                                                <th>Make</th>
                                                                <th>Brand</th>
                                                            </tr>
                                                        <tbody id="table_body">
                                                              {{-- Restore rows after validation error --}}
                                                           @php
    $oldSerials = old('serial_no', []);
    $oldSupplierIds = old('supplier_id', []);
    $oldDops = old('dop', []);
    $oldMakes = old('make', []);
    $oldBrands = old('brand', []);
    $SUPPLIER = $SUPPLIER ?? [];
    $edit = $edit ?? (object)[];
@endphp
@if (count($oldSerials))
    @foreach ($oldSerials as $idx => $sn)
        <tr>
            <td>{{ $idx + 1 }}</td>
            <td>
                <input type="text" name="serial_no[]" class="form-control form-control-sm"
                    required value="{{ $sn }}" placeholder="Enter Serial No.">
            </td>
            <td class="w-25">
                <select name="supplier_id[]" required class="form-select form-select-sm js-example-matcher-start">
                    <option value="">Select</option>
                    @foreach ($SUPPLIER as $val)
                        <option value="{{ $val->id }}" {{ ($oldSupplierIds[$idx] ?? '') == $val->id ? 'selected' : '' }}>
                            {{ $val->supplier_name }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="date" name="dop[]" required
                    value="{{ $oldDops[$idx] ?? ($edit->Mrn_Date ?? '') }}"
                    class="form-control form-control-sm dop-dt" max="{{ date('Y-m-d') }}">
            </td>
            <td>
                <input type="text" name="make[]" value="{{ $oldMakes[$idx] ?? '' }}" class="form-control form-control-sm">
            </td>
            <td>
                <input type="text" name="brand[]" value="{{ $oldBrands[$idx] ?? '' }}" class="form-control form-control-sm">
            </td>
        </tr>
    @endforeach
@endif
                                                        </tbody>
                                                    </table>
                                                </div>

                                                {{-- Add Serial Number Button --}}

                                                {{-- Remarks --}}
                                                <div class="row">
                                                    <div class="col-12">
                                                        <label>Remarks <span class="text-danger">*</span></label>
                                                        <textarea name="remarks" rows="5" class="form-control form-control-sm" required>{{ old('remarks', $edit->remarks ?? '') }}</textarea>
                                                        @error('remarks') <span class="text-danger">{{ $message }}</span> @enderror

                                                        <div class="d-flex float-end mt-3">
                                                            <button type="submit" id="submitBtn" class="btn btn-primary">Submit</button>
                                                        </div>
                                                    </div>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="tab2" id="tabledata">
                        <div class="row">
                            <div class="col-6">
                                <h5>Manage Finished Good</h5>
                            </div>
                            <div class="col-6">
                                <div class="text-end">
                                    <a href="{{ url('FinishedGood/ExportFinishedGoodData') }}"><i
                                            class='fa-file-excel fas text-success'></i></a>
                                    @if (isset($EXT[22]['inputer']))
                                        <button type="submit" class="btn btn1" style="margin: 5px;">Add Finished Good</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <form method="POST">
                            @csrf
                            <div class="row filter">
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label for="" class="form-label">Date From</label>
                                    <input type="date" name="from_date" value="{{ request()->from_date ?? '' }}"
                                        class="form-control form-control-sm">
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label for="" class="form-label">Date To</label>
                                    <input type="date" name="to_date" value="{{ request()->to_date ?? '' }}"
                                        class="form-control form-control-sm">
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>Creator Name</lable>
                                        <select name="Request_By"
                                            class="form-select form-select-sm js-example-matcher-start">
                                            <option value="" disabled selected>Select</option>
                                            @foreach ($admindata as $key => $val)
                                                <option value="{{ $key }}"
                                                    {{ isset(request()->Request_By) && request()->Request_By == $key ? 'selected' : '' }}>
                                                    {{ $val }}</option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>Organization Name</label>
                                    <select name="Organization" class="form-select form-select-sm">
                                        <option value="" selected>Select</option>
                                        @foreach ($Orgs as $val)
                                            <option value="{{ $val->id }}"
                                                {{ isset(request()->Organization) && request()->Organization == $val->id ? 'selected' : '' }}>
                                                {{ isset($val->organisation) && $val->organisation != '' ? $val->organisation : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>Manufacturing Unit</lable>
                                        <select name="Cost_Center"
                                            class="form-select form-select-sm js-example-matcher-start">
                                            <option value="" selected>Select</option>
                                            @foreach ($Manufacturing_unit as $project)
                                                <option value="{{ $project->id }}"
                                                    {{ isset(request()->Cost_Center) && request()->Cost_Center == $project->id ? 'selected' : '' }}>
                                                    {{ $project->pname }}
                                                </option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>Plant Name</lable>
                                        <select name="Sub_Cost_Center"
                                            class="form-select form-select-sm js-example-matcher-start"
                                            id="Sub_Cost_Center">
                                            <option value="" selected>Select</option>
                                            @foreach ($plant_name as $subproject)
                                                <option value="{{ $subproject->id }}"
                                                    {{ isset(request()->Sub_Cost_Center) && request()->Sub_Cost_Center == $subproject->id ? 'selected' : '' }}>
                                                    {{ $subproject->spname }}
                                                </option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>Raw Material(FG)</lable>
                                        <select name="Raw_Material"
                                            class="form-select form-select-sm js-example-matcher-start" id="RawMaterial">
                                            <option value="" selected>Select</option>
                                            @foreach ($Filtered_Array as $val)
                                                <option value="{{ $val->RawMaterial->id }}"
                                                    {{ isset(request()->Raw_Material) && request()->Raw_Material == $val->RawMaterial->id ? 'selected' : '' }}>
                                                    {{ $val->RawMaterial->matname }}</option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-6 p-0 text-end mt-3">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                    <a href="{{ url('FinishedGood/Finished_Good_List') }}"><button type="button"
                                            class="btn btn-secondary"><i class="fa fa-refresh"></i></button></a>
                                </div>
                                <div class="col-xl-1 col-lg-1 col-md-1 col-sm-6 mt-4">
                                    <div class="FilterButtonnn">
                                        <div class="raone">
                                            <p class="m-0 raho" id="MyToggle">
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
                                                    <input type="checkbox" class="form-check-input" id="Creater_Name"
                                                        value="Creator Name" onclick="filterTable(this)">
                                                    <label class="form-check-label" for="Creater_Name">Creator
                                                        Name</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                        id="Organization_Name" value="Organization Name"
                                                        onclick="filterTable(this)">
                                                    <label class="form-check-label" for="Organization_Name">Organization
                                                        Name</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="Production_Date"
                                                        value="Production Date" onclick="filterTable(this)">
                                                    <label class="form-check-label" for="Production_Date">Production
                                                        Date</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                        id="Manufacturing_Unit" value="Manufacturing Unit"
                                                        onclick="filterTable(this)">
                                                    <label class="form-check-label" for="Manufacturing_Unit">Manufacturing
                                                        Unit</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="Plant_Name"
                                                        value="Plant Name" onclick="filterTable(this)">
                                                    <label class="form-check-label" for="Plant_Name">Plant Name</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="Raw_Material"
                                                        value="Finished Good(FG)" onclick="filterTable(this)">
                                                    <label class="form-check-label" for="Raw_Material">Finished
                                                        Good(FG)</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="HSN"
                                                        value="HSN" onclick="filterTable(this)">
                                                    <label class="form-check-label" for="HSN">HSN</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="UOM"
                                                        value="UOM" onclick="filterTable(this)">
                                                    <label class="form-check-label" for="UOM">UOM</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="QTY"
                                                        value="QTY" onclick="filterTable(this)">
                                                    <label class="form-check-label" for="QTY">QTY</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="Status"
                                                        value="Status" onclick="filterTable(this)">
                                                    <label class="form-check-label" for="Status">Status</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="Pending_With"
                                                        value="Pending With" onclick="filterTable(this)">
                                                    <label class="form-check-label" for="Pending_With">Pending
                                                        With</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="date_time"
                                                        value="Creation Date & Time" onclick="filterTable(this)">
                                                    <label class="form-check-label" for="date_time">Creation Date &
                                                        Time</label>
                                                </div>

                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="Operation"
                                                        value="Operation" onclick="filterTable(this)">
                                                    <label class="form-check-label" for="Operation">Operation</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <br>
                        <div class="row">
                            <div class="container">
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th class="th-sm">SL. No.</th>
                                                <th class="th-sm">Creator Name</th>
                                                <th class="th-sm">Manufacturing Unit</th>
                                                <th class="th-sm">Plant Name</th>
                                                <th class="th-sm">Organization Name</th>
                                                <th class="th-sm">Production Date</th>
                                                <th class="th-sm">Finished Good(FG)</th>
                                                <th class="th-sm">HSN</th>
                                                <th class="th-sm">UOM</th>
                                                <th class="th-sm">QTY</th>
                                                <th class="th-sm">Status</th>
                                                <th class="th-sm">Pending With</th>
                                                <th class="th-sm">Creation Date & Time</th>
                                                <th class="th-sm">Operation</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($FinishedGood as $key => $val)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $admindata[$val->userID] ?? '' }}</td>
                                                    <td>{{ $Manufacturing_unitdata[$val->Unit_Name] ?? '' }}</td>
                                                    <td>{{ $plant_namedata[$val->Plant_Name] ?? '' }}</td>
                                                    <td>{{ $Organization[$val->Organization_Name] ?? '' }}</td>
                                                    <td>{{ date('d-m-Y', strtotime($val->Transaction_Date)) }}</td>
                                                    <td>{{ $Raw_Materialdata[$val->Material_id] ?? '' }}</td>
                                                    <td>{{ $val->HSN_Code }}</td>
                                                    <td>{{ $val->UOM }}</td>
                                                    <td>{{ $val->Quantity }}</td>
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
                                                    <td>{{ isset($val->created_at) && $val->created_at != '' ? date('d-m-Y h:i A', strtotime($val->created_at)) : '' }}
                                                    </td>
													@php
														$hold_count = DB::table('finished_good_gatepass_approves')->where('FinishedGoodID', $val->id)->where('action', 'HOLD')->where('status', 1)->count();
														$hold_data=DB::table('finished_good_gatepass_approves')->where('FinishedGoodID', $val->id)->where('action', 'HOLD')->where('status', 1)->orderBy('id','DESC')->first();
													@endphp
                                                    <td>
                                                        @if($val->Approve_status == 'RECHECK' && isset($val->userID) && $val->userID == Auth::user()->id)
                                                            <a href="{{ url('FinishedGood/EditFinishedGoodGatepass/' . $val->id) }}"
                                                                class="btn btn-secondary btn-sm">Edit</a>
                                                        @elseif($hold_count > 0 && $hold_data->userID == Auth::user()->id)
                                                            <a href="{{ url('FinishedGood/Release_Hold/' . $val->id) }}"
                                                                class="btn btn-secondary">Release</a>
                                                        @endif
                                                        <a href="{{ url('FinishedGood/FinishedGoodInputerView/' . $val->id) }}"
                                                            class="btn btn-primary">VIEW</a>
                                                    </td>
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
        </section>
    </div>
    </div>
    </section>
@endsection
@push('custom-scripts')
    <script>
            $(document).ready(function () {
            // Trigger check when FG changes
            $('#RawMaterials').on('change', function () {
                checkAndGenerateRows();
            });
        
            // Trigger check when Quantity changes
            $('#Quantity').on('change', function () {
                checkAndGenerateRows();
            });
        });
        $('#RawMaterials').on('change', function() {
            materialdata()
        });
        $('#Plant_Name').on('change', function() {
            materialdata()
        });

        function materialdata() {
            var MaterialId = $('#RawMaterials').val();
            var PlantID = $('#Plant_Name').val();
            var Quantity = parseInt($("#Quantity").val());
            if (PlantID == '' || PlantID == 0 || PlantID == 'null' || PlantID == null) {
                alert('Please Select Plant First')
                return false;
            }
            if (MaterialId == '' || MaterialId == 0 || MaterialId == 'null' || MaterialId == null) {
                return false;
            }
            if (Quantity < 1) {
                return false;
            }

            $.ajax({
                url: "{{ url('RawMaterial/MaterialData') }}" + '/' + MaterialId,
                type: 'GET',
                data: {
                    MaterialId: MaterialId,
                },
                success: function(data) {
                    $('#uom').val(data.data.UOM).change();
                    $('#HSNCode').val(data.data.HSN_Code).change();
                }
            });

            $.ajax({
                url: "{{ url('Production/MaterialData') }}",
                type: 'POST',
                data: {
                    productionID: '{{ $edit->id ?? '' }}',
                    MaterialId: MaterialId,
                    PlantID: PlantID,
                    Quantity: Quantity ?? 0,
                },
                success: function(data) {
                    console.log(data);

                    var table = $('#Tabledata');

                    if ($.fn.DataTable.isDataTable(table)) {
                        table.DataTable().destroy();
                    }

                    table.find('tbody').empty();

                    var Total = 0;
                    $("#Tabledata tbody").html(data)
                    table.DataTable({
                        "ordering": false
                    });
                }
            });
        }

        $(document).ready(function() {
            $('#Manunit').change(function() {
                $('#org_name').val('');
                var ManunitId = $(this).val();

                if (ManunitId) {
                    $.ajax({
                        url: "{{ url('PPFinishedGood/get-plantnamedetails') }}" + '/' + ManunitId,
                        type: 'GET',
                        data: {
                            ManunitId: ManunitId
                        },
                        success: function(response) {
                            $('#Plant_Name').empty();
                            $('#Plant_Name').append(
                                '<option value="" selected disabled>Select</option>');
                            $.each(response, function(index, plantdetails) {
                                var option = $('<option>');
                                option.val(plantdetails.id);
                                option.text(plantdetails.spname);
                                $('#Plant_Name').append(option);
                            });
                        }
                    });
                }
            });
        });
    </script>
    <script>
        $("#Quantity").blur(function() {
            ratecal()
        });
        $("#Rate").blur(function() {
            ratecal()
        });
        $("#GST").change(function() {
            ratecal()
        });

        function ratecal() {
			let Quantity = parseFloat($("#Quantity").val()) || 0;
			let Rate = parseFloat($("#Rate").val()) || 0;
			let GST = parseFloat($("#GST").val()) || 0;

			let total = Quantity * Rate;
			let gstAmount = (total * GST) / 100;
			let amount = total + gstAmount;

			// Round to 2 decimal places
			$("#Total_amount").val(amount.toFixed(2));
		}

    </script>
    <script>
        $(".btn1").click(function() {
            $("#tabledata").toggle();
            $("#formdata").toggle();
        });
        activeclass(28, 1);
    </script>
   <script>
function checkAndGenerateRows() {
    var Quantity = parseInt($("#Quantity").val());
    var MaterialId = $('#RawMaterials').val();
    var tbody = $('#table_body');

    if (!MaterialId || isNaN(Quantity) || Quantity <= 0) return;

    $.ajax({
        url: "{{ url('FinishedGood/check-serial-requirement') }}",
        type: "POST",
        data: {
            material_id: MaterialId,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            // Always regenerate rows when Quantity changes
            tbody.html(''); // Clear previous rows

            if (tbody.children("tr").length > 0) return;

            if (response.requires_serial) {
                var html = '';
                for (var i = 0; i < Quantity; i++) {
                    html += `
                        <tr>
                            <td>${i + 1}</td>
                            <td><input type="text" required name="serial_no[]" class="form-control form-control-sm"
                                placeholder="Enter Serial No."></td>
                            <td class="w-25">
                                <select name="supplier_id[]" required class="form-select form-select-sm js-example-matcher-start">
                                    <option value="" selected>Select</option>
                                    @foreach ($SUPPLIER as $val)
                                        <option value="{{ $val->id }}">{{ $val->supplier_name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="date" name="dop[]" required class="form-control form-control-sm dop-dt" max="{{ date('Y-m-d') }}" value="{{ $edit->Mrn_Date ?? '' }} "></td>
                            <td><input type="text" name="make[]" class="form-control form-control-sm"></td>
                            <td><input type="text" name="brand[]" class="form-control form-control-sm"></td>
                        </tr>
                    `;
                }
                tbody.html(html);

                // Initialize select2
                $('.js-example-matcher-start').select2({
                    matcher: function(params, data) {
                        if ($.trim(params.term) === '') return data;
                        if (typeof data.text === 'undefined') return null;
                        if (data.text.toLowerCase().startsWith(params.term.toLowerCase())) {
                            return $.extend({}, data, true);
                        }
                        return null;
                    }
                });
            } else {
                tbody.html(`
                    <tr>
                        <td colspan="6" class="text-center">
                            <span style="color: red; font-weight: bold;">Serial No. Not Set</span>
                        </td>
                    </tr>
                `);
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", error);
            alert("Something went wrong. Please check console.");
        }
    });
}

// If there are old rows, re-apply Select2 after page load
document.addEventListener('DOMContentLoaded', function () {
    if ($ && $.fn.select2) {
        $('.js-example-matcher-start').select2({
            matcher: function(params, data) {
                if ($.trim(params.term) === '') return data;
                if (typeof data.text === 'undefined') return null;
                if (data.text.toLowerCase().startsWith(params.term.toLowerCase())) {
                    return $.extend({}, data, true);
                }
                return null;
            }
        });
    }
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
        var tableID = 13;

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

        // Handle duplicate serial numbers from controller validation
        $(document).ready(function() {
            console.log('FinishedGood: Document ready, checking for duplicate serial numbers...');
            
            // Check for both validation errors and session errors
            var hasSerialErrors = false;
            var errorMessage = '';
            
            // Check validation errors first
            @if($errors->has('serial_no'))
                hasSerialErrors = true;
                errorMessage = @json($errors->first('serial_no'));
            @endif
            
            // Check session errors (like your FinishedGood controller uses)
            @if(session('error'))
                hasSerialErrors = true;
                if (errorMessage === '') {
                    errorMessage = @json(session('error'));
                }
            @endif
            
            console.log('Has serial errors:', hasSerialErrors);
            console.log('Error message:', errorMessage);
            
            if (hasSerialErrors && errorMessage !== '') {
                // Extract duplicate serial numbers from error message
                var duplicateSerials = [];
                
                // Check for different types of error messages
                if (errorMessage.includes('Duplicate serial numbers found') || errorMessage.includes('duplicate')) {
                    // Handle form submission duplicates - check all inputs for duplicates
                    setTimeout(function() {
                        var serialInputs = document.querySelectorAll('input[name="serial_no[]"]');
                        var values = [];
                        var duplicates = [];
                        
                        console.log('Found serial inputs:', serialInputs.length);
                        
                        serialInputs.forEach(function(input) {
                            var value = input.value.trim();
                            if (value !== '') {
                                if (values.includes(value)) {
                                    duplicates.push(value);
                                }
                                values.push(value);
                            }
                        });
                        
                        duplicateSerials = [...new Set(duplicates)]; // Remove duplicates from duplicates array
                        console.log('Form duplicates found:', duplicateSerials);
                        
                        highlightDuplicates(duplicateSerials);
                    }, 500);
                    
                } else if (errorMessage.includes('Serial number conflicts found') || errorMessage.includes('conflicts')) {
                    // Extract serial numbers from controller conflict message
                    var matches = errorMessage.match(/:\s*([0-9A-Za-z,\s]+)/g);
                    if (matches) {
                        matches.forEach(function(match) {
                            var serials = match.replace(':', '').trim().split(',');
                            serials.forEach(function(serial) {
                                var cleanSerial = serial.trim();
                                if (cleanSerial && !duplicateSerials.includes(cleanSerial)) {
                                    duplicateSerials.push(cleanSerial);
                                }
                            });
                        });
                    }
                    
                    console.log('Controller duplicates found:', duplicateSerials);
                    setTimeout(function() {
                        highlightDuplicates(duplicateSerials);
                    }, 500);
                }
            }
            
            // Function to highlight duplicate inputs
            function highlightDuplicates(duplicateSerials) {
                if (duplicateSerials.length > 0) {
                    var serialInputs = document.querySelectorAll('input[name="serial_no[]"]');
                    var firstDuplicateInput = null;
                    
                    console.log('Highlighting duplicates. Total inputs:', serialInputs.length);
                    console.log('Duplicates to highlight:', duplicateSerials);
                    
                    serialInputs.forEach(function(input, index) {
                        var value = input.value.trim();
                        console.log('Input', index, 'value:', value);
                        
                        if (duplicateSerials.includes(value)) {
                            console.log('Highlighting duplicate:', value);
                            // Apply red background
                            input.style.backgroundColor = '#ffcccc';
                            input.style.border = '2px solid #ff0000';
                            
                            // Store first duplicate for focusing
                            if (firstDuplicateInput === null) {
                                firstDuplicateInput = input;
                            }
                        }
                    });
                    
                    // Focus on first duplicate input
                    if (firstDuplicateInput) {
                        console.log('Focusing on first duplicate input');
                        setTimeout(function() {
                            firstDuplicateInput.focus();
                            firstDuplicateInput.select();
                        }, 200);
                    }
                }
            }
            
            // Add event listener to clear red styling when user starts typing
            $(document).on('input', 'input[name="serial_no[]"]', function() {
                console.log('Clearing red styling for input');
                $(this).css({
                    'backgroundColor': '',
                    'border': ''
                });
            });
        });
    </script>
    <script>
        $(document).on('blur', 'input[name="serial_no[]"]', function() {
            var input = $(this);
            var serialValue = input.val().trim();
            var row = input.closest('tr');
            var sideLabel = row.find('td:eq(0)').text().trim(); // SL No. column value
            var allInputs = $('input[name="serial_no[]"]');
            var count = 0;
            allInputs.each(function() {
                if ($(this).val().trim() === serialValue) {
                    count++;
                }
            });
            if (serialValue === "") {
        input.css('border-color', '');
        return;
    }
    if (count > 1) {
        input.css('border-color', '#dc3545');
        var msg = $('<div></div>')
            .text('Serial number [' + serialValue + '] for SL No. ' + sideLabel + ': Already used in another row.')
            .css({
                position: 'fixed',
                top: '20px',
                left: '50%',
                transform: 'translateX(-50%)',
                background: '#ffc107',
                color: '#222',
                padding: '10px 24px',
                borderRadius: '6px',
                zIndex: 9999,
                fontWeight: 'bold',
                fontSize: '18px',
                boxShadow: '0 2px 8px rgba(0,0,0,0.15)'
            });
        $('body').append(msg);
        setTimeout(function() { msg.fadeOut(400, function() { $(this).remove(); }); }, 3000);
        input.val('');
        return;
    }
    if (serialValue.length > 0) {
        $.ajax({
            url: '/StockTransfer/CheckSerialNumber',
            method: 'POST',
            data: {
                serial_no: serialValue
            },
            success: function(response) {
                if (!response.valid) {
                    var msg = $('<div></div>')
                        .text('Serial number [' + serialValue + '] for SL No. ' + sideLabel + ': ' + (response.message || 'Already exists in another record.'))
                        .css({
                            position: 'fixed',
                            top: '20px',
                            left: '50%',
                            transform: 'translateX(-50%)',
                            background: '#ffc107',
                            color: '#222',
                            padding: '10px 24px',
                            borderRadius: '6px',
                            zIndex: 9999,
                            fontWeight: 'bold',
                            fontSize: '18px',
                            boxShadow: '0 2px 8px rgba(0,0,0,0.15)'
                        });
                    $('body').append(msg);
                    setTimeout(function() { msg.fadeOut(400, function() { $(this).remove(); }); }, 3000);
                    input.css('border-color', '#dc3545');
                    input.val('');
                } else {
                    input.css('border-color', '#28a745');
                }
            },
            error: function() {
                var msg = $('<div></div>')
                    .text('Error checking serial number!')
                    .css({
                        position: 'fixed',
                        top: '20px',
                        left: '50%',
                        transform: 'translateX(-50%)',
                        background: '#dc3545',
                        color: '#fff',
                        padding: '10px 24px',
                        borderRadius: '6px',
                        zIndex: 9999,
                        fontWeight: 'bold',
                        fontSize: '18px',
                        boxShadow: '0 2px 8px rgba(0,0,0,0.15)'
                    });
                $('body').append(msg);
                setTimeout(function() { msg.fadeOut(400, function() { $(this).remove(); }); }, 3000);
            }
        });
    } else {
        input.css('border-color', '');
    }
});
    </script>
    <script>
        document.addEventListener('input', function (e) {
            if (e.target.classList.contains('dop-dt')) {
                const today = new Date().toISOString().split('T')[0];
                if (e.target.value > today) {
                    e.target.value = today;
                }
            }
        });
        
    </script>
@endpush
