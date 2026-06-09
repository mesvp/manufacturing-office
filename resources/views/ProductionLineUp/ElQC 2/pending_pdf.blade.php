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
                <td>Operator</td>
                <td>Incharge</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($lists as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->bushing_date)->format('d/m/Y') }}
                    </td>
                    <td>{{ \Carbon\Carbon::parse($item->bushing_time)->format('h:i A') }}
                    </td>
                    <td>{{ $item->shiftdtl }}</td>
                    <td>{{ $item->bushing_barCode ?? '-' }}</td>
                    <!--<td>{{ $item->bushing_rfid ?? '-' }}</td>-->
                    <td>{{ $item->elqc_source ?? 'Layout' }}</td>
                    <td>{{ $item->wattage ?? '-' }}</td>
                    <td>{{ $item->cellSize ?? '-' }}</td>
                    <td>{{ $item->bus_bar ?? '-' }}</td>
                    <td>{{ $item->bushing_operator_name ?? '-' }}</td>
                    <td>{{ $item->bushing_incherge_name ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Page: <script type="text/php">echo $PAGE_NUM;</script>
    </div>
</body>
</html>