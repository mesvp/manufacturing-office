<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Employee Gate Pass Details</title>
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
				box-shadow: inset 0 0 0 2px #8d4747;
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
			$req_no = ($type === 'in') ? $emp_data->request_no : $emp_data->out_request_no;
			$req_by = ($type === 'in') ? $emp_data->request_by : $emp_data->out_request_by;
			$created_at = ($type === 'in') ? date('d-m-Y h:i A', strtotime($emp_data->created_at)) : date('d-m-Y h:i A', strtotime($emp_data->out_created_at));
		@endphp

		<h2>Employee Gate Pass Details :: (<span style="color :blue;">{{ $req_no }}</span>)</h2><hr>
		<table class="test">
			<tbody>
				<tr>
					<td><p><strong>Gate Pass No : </strong> {{ $emp_data->gate_pass_no }}</p></td>
					<td><p><strong>Created By : </strong> {{ $req_by }}</p></td>
					<td><p><strong>Date & Time : </strong> {{ $created_at }}</p></td>
				</tr>
				<tr>
					<td><p><strong>Cost Center : </strong> {{ isset($Manufacturing_unitdata[$emp_data->project_id]) ? $Manufacturing_unitdata[$emp_data->project_id] : 'N/A' }}</p></td>
					<td><p><strong>Sub Cost Center : </strong> {{ isset($plant_namedata[$emp_data->subproject_id]) ? $plant_namedata[$emp_data->subproject_id] : 'N/A' }}</p></td>
					<td><p><strong>Organisation Name : </strong> {{ isset($orgdata[$emp_data->org_id]) ? $orgdata[$emp_data->org_id] : 'N/A' }}</p></td>
				</tr>
			</tbody>
		</table>

		<h3>Employee Information :: </h3><hr>
		<table>
			<tr>
				<th>SL. No.</th>
				<th>Employee Shift</th>
				<th>Employee Name</th>
				<th>Employee Code</th>
				<th>Department</th>
				<th>Phone No</th>
			</tr>
			@php $i = 0; @endphp
			@foreach($emp_details as $emp_detail)
				<tr>
					<td>{{ ++$i }}</td>
					<td>{{ $emp_detail->emp_shift }}</td>
					<td>{{ $emp_detail->emp_name }}</td>
					<td>{{ $emp_detail->emp_code }}</td>
					<td>{{ $emp_detail->emp_dept }}</td>
					<td>{{ $emp_detail->emp_phone }}</td>
				</tr>
			@endforeach
		</table>

		<h3>Visit Details :: </h3><hr>
		<table class="test">
			<tbody>
				<tr>
					<td><p><strong>In Time: </strong>{{ date('d-m-Y h:i A', strtotime($emp_data->request_in_time)) }}</p></td>
					<td><p><strong>Expected Out Time: </strong>{{ date('d-m-Y h:i A', strtotime($emp_data->request_out_time)) }}</p></td>
					<td>
						@if ($type === 'out')
							<p><strong>Actual Out Time: </strong>{{ date('d-m-Y h:i A', strtotime($emp_data->actual_out_time)) }}</p>
						@endif
					</td>
				</tr>
				<tr>
					<td><p><strong>Person With Vehicle: </strong>{{ ($emp_data->prsn_vehicle == 1) ? 'Yes' : 'No' }}</p></td>

					@if (isset($emp_data->prsn_vehicle) && $emp_data->prsn_vehicle == 1)
						@php
							$vehicle_type = DB::table('master_type_dtls')->select('mstr_type_value')->where('id', $emp_data->vehicle_type)->first();
						@endphp
						<td><p><strong>Vehicle Type: </strong>{{ $vehicle_type->mstr_type_value ?? '' }}</p></td>
						<td><p><strong>Vehicle No.: </strong>{{ $emp_data->vehicle_no ?? '' }}</p></td>
					@else
						<td></td>
						<td></td>
					@endif
				</tr>
				<tr>
					<td><p><strong>Visit Purpose: </strong>{{ ($type === 'out') ? $emp_data->out_visit_purpose : $emp_data->visit_purpose }}</p></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><p><strong>Security Guard Name: </strong>{{ ($type === 'out') ? $emp_data->out_sec_guard : $emp_data->sec_guard }}</p></td>
					<td><p><strong>Security Ph No: </strong>{{ ($type === 'out') ? $emp_data->out_sec_guard_no : $emp_data->sec_guard_no }}</p></td>
					<td><p><strong>Whom To Meet: </strong>{{ $emp_data->meet_prsn }}</p></td>
				</tr>
				<tr>
					<td><p><strong>Remarks: </strong>{{ ($type === 'out') ? $emp_data->out_remarks : $emp_data->remarks }}</p></td>
					<td></td>
					<td></td>
				</tr>
			</tbody>
		</table>
	</body>
</html>
