<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{{$type}} Gate Pass Material Details</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #000;
            margin: 0;
        }

        .container {
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .header-title {
            font-size: 11px;
            font-weight: bold;
            background: #e9ecef;
            padding: 6px;
        }

        .company-name {
            font-size: 11px;
            font-weight: bold;
            padding: 6px;
        }

        .label {
            font-weight: bold;
            width: 20%;
            background: #f4f4f4;
        }

        .value {
            width: 30%;
        }

        .section-title {
            background: #d6d8db;
            font-weight: bold;
            text-align: center;
        }

        .signature-box {
            height: 45px;
        }

        /* Item Table Styling */
        .item-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 9px;
            margin-top: 5px;
        }

        .item-table th,
        .item-table td {
            border: 1px solid #000;
            padding: 4px 5px;
        }

        .item-header {
            background: #d6d8db;
            font-weight: bold;
            text-align: center;
        }

        .material-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 9px;
            margin-top: 8px;
        }

        .material-table th,
        .material-table td {
            border: 1px solid #000;
            padding: 5px;
            word-wrap: break-word;
        }

        .main-header {
            background: #cfd3d7;
            font-weight: bold;
            text-align: center;
        }

        .serial-row {
            font-size: 8.5px;
        }
        
        .rqst {
            color: blue;
        }
    </style>
</head>

<body>

    <div class="container">
        <table>
            <tr>
                <th colspan="4" class="header-title text-center">
                    {{$type == 'IN' ? 'INCOMING' : 'OUTGOING'}} MATERIAL GATE PASS -
                    <span class="rqst">{{$in_data->request_no}}</span>
                </th>
            </tr>
            <tr>
                <td colspan="4" class="company-name text-center">
                    {{ isset($in_data->org_id) ? $in_data->organizationDatas->organisation : '' }}
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="label">{{$type}} DATE & TIME</td>
                <td class="value">{{date('d-m-Y h:i A', strtotime($in_data->vehicle_in_time))}}</td>
                <td class="label">VEHICLE NUMBER</td>
                <td class="value">{{$in_data->vehicle_no}}</td>
            </tr>

            <tr>
                <td class="label">VEHICLE WEIGHT</td>
                <td class="value">{{$in_data->vehicle_weight}} - {{$in_data->vehicle_weight_kg}} Kg</td>
                <td class="label">DRIVER NAME</td>
                <td class="value">{{$in_data->driver_name}}</td>
            </tr>

            <tr>
                <td class="label">DRIVER PHONE</td>
                <td class="value">{{$in_data->driver_number}}</td>
                <td class="label">INSURANCE NO</td>
                <td class="value">{{$in_data->insurance_no}}</td>
            </tr>

            <tr>
                <td class="label">INSURANCE VALID</td>
                <td class="value">{{date('d-m-Y', strtotime($in_data->insurance_dt))}}</td>
                <td class="label">DL NUMBER</td>
                <td class="value">{{$in_data->dl_no}}</td>
            </tr>

            <tr>
                <td class="label">DL EXPIRE</td>
                <td class="value">{{date('d-m-Y', strtotime($in_data->dl_expire))}}</td>
                <td class="label">E-WAY BILL</td>
                <td class="value">{{$in_data->bill_no}}</td>
            </tr>

            <tr>
                <td class="label">FROM COMPANY</td>
                <td class="value">{{$in_data->from_address}}</td>
                <td class="label">TO COMPANY</td>
                <td class="value">{{$in_data->to_address}}</td>
            </tr>

            <tr>
                <td class="label">SECURITY GUARD</td>
                <td class="value">{{$in_data->sec_guard_name}}</td>
                <td class="label">APPROVED BY</td>
                <td class="value">{{ $approves->approved_by ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">DESCRIPTION</td>
                <td class="value" colspan="3">{{$in_data->remarks ?? 'N/A'}}</td> 
            </tr>
            </table>
            @if($in_items->count() > 0)
                <table class="item-table">
                    
                    <tr style="text-align: center;">
                        <td><b>SN</b></td>
                        <td><b>ITEM DESCRIPTION</b></td>
                        <td><b>UOM</b></td>
                        <td><b>ITEM QTY</b></td>
                        <td><b>REMARKS</b></td>
                    </tr>
                @php $i = 0; @endphp
                @foreach($in_items as $in_item)
                    <tr style="text-align: center;">
                    <td>{{ ++$i }}</td>
                    @php
                        $mat_name = App\Models\MaterialManagement\MaterialManagement_Add_Material::select('prj_material.material_name as matname')
                        ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
                        ->where('materialmanagement_add_material.id', $in_item->item_desc)
                        ->first();
                    @endphp
                    <td>{{$mat_name ? $mat_name->matname : $in_item->item_desc}}</td>
                    <td>{{$in_item->uomDatas->UOMs}}</td>
                    <td>{{$in_item->item_qty}}</td>
                    <td>{{$in_item->item_remark}}</td>
                    </tr>
                @endforeach
            @endif
        </table>
        @if(isset($materials) && $materials->isNotEmpty())
        <table class="material-table">
            <thead>
                <tr class="main-header">
                    <th style="width:5%;">SL. No.</th>
                    <th style="width:20%;">{{ $type == 'IN' ? 'SUPPLIER' : 'CUSTOMER' }} NAME</th>
                    <th style="width:20%;">INVOICE NO</th>
                    <th style="width:20%;">MATERIAL</th>
                    <th style="width:15%;">UOM</th>
                    <th style="width:10%;">QTY</th>
                </tr>
            </thead>
            <tbody>
                @foreach($materials as $matId => $items)
                
                    @php
                        $first = $items->first();
                    @endphp
                
                    <tr class="item-row">
                        <td class="text-center">
                            {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                        </td>
                        <td>{{ $first->custName }}</td>
                        <td>{{ $first->gp_invNo }}</td>
                        <td>{{ $first->matName }}</td>
                        <td class="text-center">{{ $first->invUom }}</td>
                        <td class="text-center">{{ $first->dispQty }}</td>
                    </tr>
                
                    @if($items->whereNotNull('slno_dtls')->count())
                    <tr class="serial-row">
                        <td colspan="6">
                            @foreach($items as $serial)
                                @if($serial->slno_dtls)
                                    <span>{{ $serial->slno_dtls }}</span>&nbsp;&nbsp;
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    @endif
                
                @endforeach
            </tbody>
        </table>
        @endif
        <table class="item-table">
            <tr class="section-title">
                <td>STORE INCHARGE</td>
                <td>PLANT HEAD</td>
                <td>SECURITY</td>
                <td>DRIVER</td>
            </tr>
            <tr>
                <td class="signature-box"></td>
                <td class="signature-box"></td>
                <td class="signature-box"></td>
                <td class="signature-box"></td>
            </tr>
        </table>
        

    </div>

</body>

</html>
