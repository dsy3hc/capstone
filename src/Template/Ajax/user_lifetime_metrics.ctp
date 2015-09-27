<div class='col-md-10'>
	<table class='table table-hover'>
		<?php
			echo "<tr>
				<th>
					Last Activity for Specified User";
						if(strlen($userName) > 0) {
							echo " (<em>$userName</em>)";
						}
			echo ": </th>
				<td>";
				if(strlen($userActivity) > 0) {
					echo $userActivity;
				} else {
					echo "--";
				}
			echo "</td>
			</tr>";
			echo "<tr>
				<th>
					Total Reservations for Specified User";
						if(strlen($userName) > 0) {
							echo " (<em>$userName</em>)";
						}
			echo ": </th>
				<td style='text-align: center'>" . $userReservations . "</td>
			</tr>";
		?>
	</table>
</div>