<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Layout Material Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 10px;
        }

        /* ── Header ─────────────────────────────────────── */
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #333;
            margin: 0;
            font-size: 16px;
        }
        .header p {
            margin: 5px 0;
            font-size: 10px;
        }

        /* ── Filter bar ──────────────────────────────────── */
        .filters {
            margin-bottom: 10px;
            padding: 8px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            font-size: 9px;
        }
        .filters strong {
            color: #495057;
        }

        /* ── Table ───────────────────────────────────────── */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #333;
            padding: 4px 6px;
            text-align: left;
            font-size: 8px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .material-header {
            background-color: #e6e6e6;
            text-align: center;
            font-weight: bold;
        }
        .sub-header th {
            background-color: #d9d9d9;
        }
        .text-center { text-align: center; }

        /* ── Footer ──────────────────────────────────────── */
        .report-footer {
            margin-top: 20px;
            font-size: 8px;
            text-align: center;
        }
    </style>
</head>
<body>

    {{-- ── Page Header ────────────────────────────────────── --}}
    <div class="header">
        <h1>LAYOUT MATERIAL REPORT</h1>
        <p style="text-align:right;">Generated on: {{ $generatedAt }}</p>
    </div>

    {{-- ── Applied Filters ────────────────────────────────── --}}
    <div class="filters">
        @if(count($appliedFilters))
            {{ implode(' | ', $appliedFilters) }}
        @else
            Showing All Records
        @endif
    </div>

    {{-- ── Data Table ──────────────────────────────────────── --}}
    <table>
        <thead>
            {{-- Row 1: Section labels --}}
            <tr class="material-header">
                <th colspan="13" style="text-align:center;">LAYOUT DETAILS</th>
                <th colspan="{{ count($materialNames) * 3 }}" style="text-align:center;">MATERIALS</th>
                <th rowspan="3">Logo</th>
            </tr>

            {{-- Row 2: Fixed column names (rowspan 2) + material group names --}}
            <tr class="sub-header">
                @foreach (['SL No','Batch No','Date','Time','Shift','Bar Code','RFID','Created By','Operator','Incharge','Watt','Brand','Efficiency'] as $heading)
                    <th rowspan="2">{{ $heading }}</th>
                @endforeach

                @foreach ($materialNames as $material)
                    <th colspan="3">{{ $material->mname ?? 'Material' }}</th>
                @endforeach
            </tr>

            {{-- Row 3: Sub-headers for each material (Qty / Size / Brand) --}}
            <tr class="sub-header">
                @foreach ($materialNames as $material)
                    <th>Qty</th>
                    <th>Size</th>
                    <th>Brand</th>
                @endforeach
            </tr>
        </thead>

        <tbody>
            @foreach ($allLists as $key => $item)
           
                @php
                    $cellDetail = $cellDetailsByBatch[$item->bushing_batchNo] ?? null;
                    $materials  = $materialsByBatch[$item->bushing_batchNo]   ?? collect();
                @endphp

                <tr>
                    {{-- Fixed columns --}}
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td>{{ $item->bushing_batchNo ?? '-' }}</td>
                    <td>{{ $item->bushing_date    ?? '-' }}</td>
                    <td>
                        @if(!empty($item->bushing_time))
                            {{ \Carbon\Carbon::parse($item->bushing_time)->format('h:i A') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $item->shiftdtl         ?? '-' }}</td>
                    <td>{{ $item->bushing_barCode  ?? '-' }}</td>
                    <td>{{ $item->bushing_rfid     ?? '-' }}</td>
                    <td>{{ $item->createdBy        ?? '-' }}</td>
                    <td>{{ $item->bushing_operator ?? '-' }}</td>
                    <td>{{ $item->bushing_incherge ?? '-' }}</td>
                    <td>{{ $item->wattage          ?? '-' }}</td>
                    <td>{{ $cellDetail->brand      ?? '-' }}</td>
                    <td>{{ $cellDetail->cellSize   ?? '-' }}</td>

                    {{-- Dynamic material columns --}}
                    @foreach ($materialNames as $material)
                        @php
                            $materialData = $materials->firstWhere('matId', $material->id);
                        @endphp
                        <td>{{ $materialData->qty   ?? '-' }}</td>
                        <td>{{ $materialData->size  ?? '-' }}</td>
                        <td>{{ $materialData->brand ?? '-' }}</td>
                    @endforeach

                    {{-- Logo --}}
                    <td>{{ $item->bushing_logo ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ── Footer ─────────────────────────────────────────── --}}
    <div class="report-footer">
        Total Records: {{ count($allLists) }} | Total Materials: {{ count($materialNames) }}
    </div>

</body>
</html>
