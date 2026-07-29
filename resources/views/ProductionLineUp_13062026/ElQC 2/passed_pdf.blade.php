<!DOCTYPE html>
<html>
<head>
    <title>Pending Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .header { text-align: center; margin-bottom: 20px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: right; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Pending Bushing Report</h1>
        <p>Generated on: {{ $date }}</p>
    </div>

    <table>
        <thead class="table-secondary">
            <tr>
                <td>SL No</td>
                <td>Date</td>
                <td>Time</td>
                <td>Shift</td>
                <td class="w-20">Bar Code</td>
                <!--<td class="w-20">RFID</td>-->
                <td>Source</td>
                <td>Watt</td>
                <td>Cell Efficiency</td>
                <td>Bus Bar</td>
                <td>No of Cell Damage</td>
                <td>Operator</td>
                <td>Incharge</td>
                <td>Action</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($lists as $item)
                @if ($item->status == '1' && $item->rwrk_status == '1')
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->elqc_date)->format('d/m/Y') }}
                        </td>
                        <td>{{ \Carbon\Carbon::parse($item->elqc_time)->format('h:i A') }}
                        </td>
                        <td>{{ $item->shiftdtl }}</td>
                        <td>{{ $item->elqc_barcode ?? '-' }}</td>
                        <!--<td>{{ $item->elqc_rfid ?? '-' }}</td>-->
                        <td>{{ $item->elqc_source ?? '-' }}</td>
                        <td>{{ $item->wattage ?? '-' }}</td>
                        <td>{{ $item->cellSize ?? '-' }}</td>
                        <td>{{ $item->bus_bar ?? '-' }}</td>
                        <td>{{ $item->no_of_cell_damage ?? '-' }}</td>
                        <td>{{ $item->elqc_operator_name ?? '-' }}</td>
                        <td>{{ $item->elqc_incharge_name ?? '-' }}</td>
                        <td>
                            <a class="btn btn-primary btn-xs text-capitalize waves-effect waves-light"
                                href="{{ route('el-qc-view', ['id' => $item->elqc_id]) }}?page=VIEW" role="button"><i class="mdi mdi-eye"></i>
                                View</a>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Page: <script type="text/php">echo $PAGE_NUM;</script>
    </div>
</body>
</html>