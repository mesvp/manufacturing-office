<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Visitor Gate Pass Details</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: -20px;
                border: 1px solid;
                padding: 5px;
                font-size: 12px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            table, th, td {
                border: 1px solid #ddd;
                padding: 10px;
            }
            th {
                background-color: #d6d2d2;
            }
            .test table, .test th, .test td {
                border: none !important;
            }

        </style>
    </head>
    <body>
        @php
            $req_no = ($type === 'in') ? $visitor_data->request_no : $visitor_data->out_request_no;
            $req_by = ($type === 'in') ? $visitor_data->request_by : $visitor_data->out_request_by;
            $created_at = ($type === 'in') ? date('d-m-Y h:i A', strtotime($visitor_data->created_at)) : date('d-m-Y h:i A', strtotime($visitor_data->out_created_at));
        @endphp

        <h2>Visitor Gate Pass Details :: (<span style="color :blue;">{{ $req_no }}</span>)</h2><hr>
        <table class="test">
            <tbody>
                <tr>
                    <td><p><strong>Gate Pass No : </strong> {{ $visitor_data->gate_pass_no }}</p></td>
                    <td><p><strong>Created By : </strong> {{ $req_by }}</p></td>
                    <td><p><strong>Date & Time : </strong> {{ $created_at }}</p></td>
                </tr>
                <tr>
                    <td><p><strong>Cost Center : </strong> {{ isset($Manufacturing_unitdata[$visitor_data->project_id]) ? $Manufacturing_unitdata[$visitor_data->project_id] : 'N/A' }}</p></td>
                    <td><p><strong>Sub Cost Center : </strong> {{ isset($plant_namedata[$visitor_data->subproject_id]) ? $plant_namedata[$visitor_data->subproject_id] : 'N/A' }}</p></td>
                    <td><p><strong>Organisation Name : </strong> {{ isset($orgdata[$visitor_data->org_id]) ? $orgdata[$visitor_data->org_id] : 'N/A' }}</p></td>
                </tr>
            </tbody>
        </table>

        <h3>Visitor Information :: </h3><hr>
        <table>
            <tr>
                <th class="th-sm text-center">SL. NO.</th>
                <th class="th-sm">VISITOR TYPE</th>
                <th class="th-sm">VISITOR NAME</th>
                <th class="th-sm">VISITOR MOBILE NO.</th>
                <th class="th-sm">VISITOR ADDRESS</th>
                <th class="th-sm">PURPOSE TO VISIT</th>
                <th class="th-sm">WHOM TO MEET</th>
            </tr>
            @php $i = 0; @endphp
            @foreach($visitor_details as $visitor_detail)
            @php
                $empName = [];
                if ($visitor_detail->visitor_type == 0) {
                    $empName = DB::table('mstr_emp')->where('id', $visitor_detail->visitor_name)->pluck('fullname')->first();
                }
            @endphp
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ isset($visitor_detail->visitor_type) ? ($visitor_detail->visitor_type == 0 ? 'OFFICE EMPLOYEE' : 'VISITOR') : 'N/A' }}</td>
                    <td>{{ $visitor_detail->visitor_type == 0 ? ($empName ?? 'N/A') : $visitor_detail->visitor_name }}</td>
                    <td>{{ $visitor_detail->visitor_phone }}</td>
                    <td>{{ $visitor_detail->visitor_address }}</td>
                    <td>{{ $visitor_detail->visitor_purpose }}</td>
                    <td>{{ $visitor_detail->visitor_meet_prsn }}</td>
                </tr>
            @endforeach
        </table>

        <h3>Visit Details :: </h3><hr>
        <table class="test">
            <tbody>
                <tr>
                    <td><p><strong>In Time:</strong> {{ date('d-m-Y h:i A', strtotime($visitor_data->request_in_time)) }}</p></td>
                    <td><p><strong>Expected Out Time:</strong> {{ date('d-m-Y h:i A', strtotime($visitor_data->request_out_time)) }}</p></td>
                    <td>
                        @if ($type === 'out')
                            <p><strong>Actual Out Time:</strong> {{ date('d-m-Y h:i A', strtotime($visitor_data->actual_out_time)) }}</p>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><p><strong>Person With Vehicle:</strong> {{ ($visitor_data->prsn_vehicle == 1) ? 'Yes' : 'No' }}</p></td>
                    @if (isset($visitor_data->prsn_vehicle) && $visitor_data->prsn_vehicle == 1)
                    @php
                        $vehicle_type = DB::Table('master_type_dtls')->select('mstr_type_value')->where('id', $visitor_data->vehicle_type)->first();
                    @endphp
                        <td><p><strong>Vehicle Type:</strong> {{ $vehicle_type->mstr_type_value??'' }}</p></td>
                        <td><p><strong>Vehicle No.:</strong> {{ $visitor_data->vehicle_no??'' }}</p></td>
                    @else
                        <td></td>
                        <td></td>
                    @endif
                </tr>
                <tr>
                    <td><p><strong>Visit Purpose:</strong>{{ ($type === 'out') ? $visitor_data->out_visit_purpose : $visitor_data->visit_purpose }}</p></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td><p><strong>Security Guard Name : </strong>{{ ($type === 'out') ? $visitor_data->out_sec_guard : $visitor_data->sec_guard }}</p></td>
                    <td><p><strong>Security Ph No : </strong>{{ ($type === 'out') ? $visitor_data->out_sec_guard_no : $visitor_data->sec_guard_no }}</p></td>
                    <td>
                        <p><strong>Whom To Meet:</strong> {{ $visitor_data->meet_prsn }}</p>
                    </td>
                </tr>
                <tr>
                    <td><p><strong>Remarks:</strong> {{ ($type === 'out') ? $visitor_data->out_remarks : $visitor_data->remarks }}</p></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
