<?php
	header("Content-Type: application/xls");  
	header("Content-Disposition: attachment; filename=death_report.xls");  
	header("Pragma: no-cache"); 
	header("Expires: 0");
?>
<table>
	<thead>
		<tr>
			<th colspan="2">Death Report</th>
		</tr>
	</thead>
	<tbody>
		@foreach($report as $game)
			<tr><th colspan="2"></th></tr>
			<tr><th colspan="2">{{ $game['game'] }}</th></tr>
			<tr><th colspan="2">Kills by means</th></tr>
			<tr>
				<th>Description</th>
				<th>Quantity</th>
			</tr>
			@foreach($game['kills_by_means'] as $description => $quantity)
				<tr>
					<td>{{ $description }}</td>
					<td>{{ $quantity }}</td>
				</tr>
			@endforeach
		@endforeach
	</tbody>
</table>
