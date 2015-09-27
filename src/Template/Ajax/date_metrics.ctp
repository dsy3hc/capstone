<?php
	echo "<div class='col-md-8'>";
			echo "<table class='table table-hover'";
			echo "<tr>
					<th>
						Total Number of Users: 
					</th>
					<td>" . $numUsers . "</td>
				</tr>";
			echo "<tr>
					<th>
						Total Number of Reservations: 
					</th>
					<td>" . $numReservations . "</td>
				</tr>";
			echo "<tr>
					<th>
						Average Reservations per User:
					</th>
					<td>";
					if( $numUsers > 0) {
						echo $numReservations/$numUsers;
					} else {
						echo "0";
					}
			echo "</td>
				</tr>";
			echo "<tr>
					<th>
						Total Pending Reservations:
					</th>
					<td>" . $pendingReservations . "</td>
				</tr>";
			echo "<tr>
					<th>
						Total Approved Reservations:
					</th>
					<td>" . $approvedReservations . "</td>
				</tr>";
			echo "<tr>
					<th>
						Total Denied Reservations:
					</th>
					<td>" . $deniedReservations . "</td>
				</tr>";

	echo "		</table>
			</div>";