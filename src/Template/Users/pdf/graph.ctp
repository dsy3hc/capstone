<style>
body {
	font-family: sans-serif;
}

p {
	margin-top: 0px;
}

h4 {
	margin-bottom: 0px;
}

.small-divider {
    width: 60%;
    border-top: 1px solid rgba(0, 0, 0, 0.1);
    border-bottom: 1px solid rgba(255, 255, 255, 0.3);
    margin-bottom: 50px;
}

table {
	width: 100%;
	margin-bottom: 10px;
}

.table-preferences td, th {
	text-align: center;
}

.table-preferences th {
	background: #ccc;
}
</style>

<h1>Metrics Summary</h1>
<hr>
<br>
<table border="1" class="table-preferences">
	<tbody>
		<tr>
			<th rowspan="2">Month</th>
			<th rowspan="2">Reservations</th>
			<th rowspan="2">Registrations</th>
			<th colspan="3">Timeoff Requests</th>
		</tr>
		<tr>
			<th>Sick</th>
			<th>Annual</th>
			<th>Bonus</th>
		</tr>
		 	<?php for($x = 0; $x < count($graph_labels); $x++) : ?>
		<tr>
			<th><?= $graph_labels[$x] ?></th>
			<td><?= $graph_reservation_points[$x] ?></td>
			<td><?= $graph_registration_points[$x] ?></td>
			<td><?= $graph_timeoff_points['Sick'][$x] ?></td>
			<td><?= $graph_timeoff_points['Annual'][$x] ?></td>
			<td><?= $graph_timeoff_points['Bonus'][$x] ?></td>
		</tr>
	<?php endfor; ?>

	</tbody>
</table> 