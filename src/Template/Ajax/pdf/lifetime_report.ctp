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
	width: 50%;
}
</style>

<h1>Lifetime Metrics Summary</h1>
<hr>					
<table border="1" class="table-preferences">
	<tbody>
		<tr>
			<th>Total Number of Users: </th>
			<td><?= $numUsers ?></td>
		</tr>
		<tr>
			<th>Total Number of Reservations: </th>
			<td><?= $numReservations ?></td>
		</tr>
		<tr>
			<th>Average Reservations per User: </th>
			<td>
				<?php 
					if( $numUsers > 0) {
						echo $numReservations/$numUsers;
					} else {
						echo "0";
					}
				?>
			</td>
		</tr>
		<tr>
			<th>Total Pending Reservations: </th>
			<td><?= $pendingReservations ?></td>
		</tr>
		<tr>
			<th>Total Approved Reservations: </th>
			<td><?= $approvedReservations ?></td>
		</tr>
		<tr>
			<th>Total Denied Reservations: </th>
			<td><?= $deniedReservations ?></td>
		</tr>
 	</tbody>
</table>