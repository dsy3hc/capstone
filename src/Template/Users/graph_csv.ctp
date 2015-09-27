<?php
	$this->CSV->addRow("Month", "Reservations", "Registrations", "Timeoff Requests - Sick", "Timeoff Requests - Annual", "Timeoff Requests - Bonus");
	for($x = 0; $x < count($graph_labels); $x++) {
		$line[] = $graph_labels[$x];
		$line[] = $graph_reservation_points[$x];
		$line[] = $graph_registration_points[$x];
		$line[] = $graph_timeoff_points['Sick'][$x];
		$line[] = $graph_timeoff_points['Annual'][$x];
		$line[] = $graph_timeoff_points['Bonus'][$x];
		$this->CSV->addRow($line);
	}
	$filename='posts';
	echo  $this->CSV->render($filename);
?>

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
			
		</tr>
	<?php endfor; ?>

	</tbody>
</table> 