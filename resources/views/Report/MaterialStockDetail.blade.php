@extends('layout.main')
@section('main-container')
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <title>Finished Good Material Stock Details</title>

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
                    <li class="breadcrumb-item">Finished Good Material Store Stock Report</li>
                </ol>
                <div class="addbtn extra pt-3">
                    <a href="{{ url('Report/material-stock-report') }}" class="btn btn-info mr-1 btn-sm"> <i
                            class="fa fa-arrow-left"></i></a>
                    <a href="{{ url('Report/material-stock-report') }}" class="btn btn-info btn-sm"> <i
                            class="fa fa-home"></i></a>
                </div>
                <div class="row">
                    <div class="container">
                        <form action="{{ url('Report/detailfiltered/' . $matid) }}" method="POST">
                            @csrf
                            <div class="row filter">
                                <div class="col-3 mb-3">
                                    <label for="" class="form-label">Date From</label>
                                    <input type="date" name="from_date" value="{{ old('from_date', $fromdate ?? '') }}"
                                        class="form-control form-control-sm">
                                </div>
                                <div class="col-3 mb-3">
                                    <label for="" class="form-label">Date To</label>
                                    <input type="date" name="to_date" value="{{ old('to_date', $todate ?? '') }}"
                                        class="form-control form-control-sm">
                                </div>
                                <div class="col-3 mt-4">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                    <a href="{{ url('Report/MaterialStockDetail/' . $matid) }}">
                                        <button type="button" class="btn btn-secondary"><i
                                                class="fa fa-refresh"></i></button>
                                    </a>
                                </div>
                            </div>
                        </form>

                        <div class="row">
                            <div class="table-responsive">
                                <table id="example2" class="table table-striped table-bordered w-00">
                                    <thead>
                                        <tr>
                                            <th class="th-sm">Sl No.</th>
                                            <th class="th-sm">MATERIAL NAME</th>
                                            <th class="th-sm">HSN</th>
                                            <th class="th-sm">UOM</th>
                                            <th class="th-sm">LPP</th>
                                            <th class="th-sm">FROM PROJECT</th>
                                            <th class="th-sm">FROM SUB PROJECT</th>
                                            <th class="th-sm">FROM ORGANIZATION</th>
                                            <th class="th-sm">FROM GODOWN</th>
                                            <th class="th-sm">FROM SUPPLIER</th>
                                            <th class="th-sm">TO PROJECT</th>
                                            <th class="th-sm">TO SUB PROJECT</th>
                                            <th class="th-sm">TO ORGANIZATION</th>
                                            <th class="th-sm">TO GODOWN</th>
                                            <th class="th-sm">TO CUSTOMER</th>
                                            
                                            <th class="th-sm">TRANSACTION DATE</th>
                                            <th class="th-sm">TOTAL RECEIVED</th>
                                            <th class="th-sm">TOTAL ISSUED</th>
                                            <th class="th-sm">CLOSING BALANCE</th>
                                            <th class="th-sm">TRANSACTION NARATION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalQuantity = 0;
                                            $totalIssueQty = 0;
                                            $counter = 1;
                                            $closingBalance = 0;
                                            $totalClosingBalance = 0;
                                        @endphp

                                        @if (count($Materials) > 0)
                                            @foreach ($Materials as $key => $val)
                                                @php
                                                    //$qty = $val->Quantity ?? 0;
                                                    $qty = ($val->Quantity ?? 0) + ($val->purchase_qty ?? 0);
                                                    $issueQty = $val->dispatch_qty ?? 0;

                                                    $totalQuantity += $qty;
                                                    $totalIssueQty += $issueQty;

                                                    $lastPurchase = App\Models\Master\RawMaterial\Master_Raw_Material::select(
                                                        'Rate',
                                                    )
                                                        ->where(
                                                            'Material',
                                                            $val->Material_id ?? ($val->Raw_Material ?? $val->matrl_id),
                                                        )
                                                        ->orderBy('id', 'DESC')
                                                        ->first();
                                                    $last_purchase_price = $lastPurchase->Rate ?? 0;
                                                @endphp

                                                <tr>
                                                    <td>{{ $counter++ }}</td>
                                                    <td>{{ $val->matname ?? '' }}</td>
                                                    <td>
                                                        @if ($val->type == 'Issued')
                                                            {{ $val->hsn ?? 'N/A' }}
                                                        @else
                                                            {{ $val->HSN_Code ?? 'N/A' }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($val->type == 'Issued')
                                                            {{ $val->uom ?? 'N/A' }}
                                                        @else
                                                            {{ $val->UOM ?? 'N/A' }}
                                                        @endif
                                                    </td>
                                                    <td>{{ $last_purchase_price }}</td>
                                                    <td>
                                                        @if (in_array($val->type, ['Finished Good', 'Received', 'MRN Transfer']))
                                                            {{ $val->pname ?? 'N/A' }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if (in_array($val->type, ['Finished Good', 'Received', 'MRN Transfer']))
                                                            {{ $val->spname ?? 'N/A' }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td>{{ $val->fromorg ?? 'N/A' }}</td>
                                                    <td>{{ $val->fromgodown ?? 'N/A' }}</td>
                                                    <td>N/A</td>
                                                    <td>{{ $val->project_name ?? 'N/A' }}</td>
                                                    <td>{{ $val->sub_project ?? 'N/A' }}</td>
                                                    <td>{{ $val->toorg ?? 'N/A' }}</td>
                                                    <td>N/A</td>
                                                    <td>{{ $val->customer_name ?? 'N/A' }}</td>

                                                    {{-- Date Field --}}
                                                    <td>
                                                        @if ($val->type == 'Received')
                                                            {{ $val->Production_Date ? \Carbon\Carbon::parse($val->Production_Date)->format('d-m-Y') : '' }}
                                                        @elseif($val->type == 'Issued')
                                                            {{ $val->transact_dt ? \Carbon\Carbon::parse($val->transact_dt)->format('d-m-Y') : '' }}
                                                        @elseif($val->type == 'Finished Good')
                                                            {{ $val->Transaction_Date ? \Carbon\Carbon::parse($val->Transaction_Date)->format('d-m-Y') : '' }}
                                                        @elseif($val->type == 'MRN Transfer')
                                                            {{ $val->purchahedate ? \Carbon\Carbon::parse($val->purchahedate)->format('d-m-Y') : '' }}
                                                        @else
                                                            0
                                                        @endif
                                                    </td>

                                                    {{-- Quantity --}}
                                                    <td>{{ $qty }}</td>

                                                    {{-- Issue Qty --}}
                                                    <td>{{ $issueQty }}</td>

                                                    {{-- Closing Balance --}}
                                                    <td>
                                                        @if (in_array($val->type, ['Received', 'Finished Good', 'MRN Transfer']))
                                                            @php $closingBalance += $qty; @endphp
                                                        @elseif($val->type == 'Issued')
                                                            @php $closingBalance -= $issueQty; @endphp
                                                        @endif
                                                        {{ $closingBalance }}
                                                        
                                                    </td>

                                                    {{-- Source Info --}}
                                                    <td>
                                                        @if ($val->type == 'Received')
                                                            Receive Through Production Entry
                                                        @elseif($val->type == 'Issued')
                                                            Issued Through Dispatch
                                                        @elseif($val->type == 'Finished Good')
                                                            Receive Through Manual FG Stock Entry
                                                        @elseif($val->type == 'MRN Transfer')
                                                            Receive Through MRN Stock Transfer
                                                        @else
                                                            0
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="18" class="text-center text-danger fw-bolder">!!! NO RECORD
                                                    FOUND !!!</td>
                                            </tr>
                                        @endif

                                        {{-- Total Footer --}}
                                       <tfoot>
                                            <tr style="background-color: #e5f9f9;">
                                                <td colspan="15"></td>
                                                <td><strong>Total Quantity</strong></td>
                                                <td><strong>{{ $totalQuantity }}</strong></td>
                                                <td><strong>{{ $totalIssueQty }}</strong></td>
                                                <!--<td><strong>{{ $totalClosingBalance }}</strong></td>-->
                                                <td><strong>{{ $totalQuantity- $totalIssueQty}}</strong></td>
                                                <td></td>
                                            </tr>
                                    </tfoot>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </section>
        </div>
    </div>
@endsection
