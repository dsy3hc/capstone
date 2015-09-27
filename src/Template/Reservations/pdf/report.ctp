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
<h1>Reservation Summary</h1>
<hr>

<h4>Client</h4>
<p><?= $reservation['first_name'] . " " . $reservation['last_name']?></p>

<table style="margin-top: -20px;">
	<tbody>
		<tr>
			<td><h4>Booking Number</h4></td>
			<td><h4>PCA &nbsp;&nbsp;</h4></td>
			<td><h4>Children</h4></td>
		</tr>
		<tr>
			<td><?= $reservation->bookingNum ?></td>
			<td>
				<?php if(!is_null($reservation->children) && $reservation->children): ?>
					Yes
				<?php else: ?>
					No
				<?php endif; ?>
			</td>
			<td>			
				<?php if(!is_null($reservation->physicians) && $reservation->physicians): ?>
					Yes
				<?php else: ?>
					No
				<?php endif; ?>
			</td>
		</tr>
	</tbody>
</table>

<h4>Additional Comments</h4>
<p>
	<?php if(!is_null($reservation->physicians) && $reservation->physicians): ?>
		<?= $reservation->comments ?>
	<?php else: ?>
		None
	<?php endif; ?>
</p>

<h4>Status</h4>
<?php if($reservation->status == 1):?>
	<p>Approved </p>
<?php elseif($reservation->status == 2): ?>
	<p> Denied </p>
<?php else: ?>
	<p> Pending </p>
<?php endif; ?>

<hr class="small-divider">
<?php if($reservation->status == 1):?>
	<p>Your requested trip for <?= $reservation->pick_up_day->format('m/d/Y'); ?> has been approved.</p>
<?php endif; ?>
	
<div style="margin-bottom: 5px;">
    <strong>Outbound Trip</strong>
</div>
<table style="width: 100%; background-color: #EEE; padding: 15px; margin-bottom: 20px;">
    <tr style="font-size: 20px; margin-bottom: 10px; color: #222;">
        <td><?= $reservation->formatted_pickup_time ?><td>
    </tr>
    <tr>
        <td style="width: 33%;">
            <div><?= $reservation->pick_up_address ?></div>
            <?php if (strlen($reservation->pick_up_unit) > 0) :?>
            	APT <?= $reservation->pick_up_unit ?>
            <?php endif; ?>
            <div><?= $reservation->pick_up_city . " VA, " . $reservation->pick_up_zip ?></div>
        </td>
        <td style="width: 33%; text-align: center;">to</td>
        <td style="width: 33%;">
            <div style="text-align: right" align="right">
                <div><?= $reservation->drop_off_address ?></div>
                    <?php if (strlen($reservation->drop_off_unit) > 0) :?>
						APT <?= $reservation->drop_off_unit ?>
					<?php endif; ?>
                <div><?= $reservation->drop_off_city . " VA, " . $reservation->drop_off_zip ?></div>
            </div>
        </td>
    </tr>
</table>
<?php if ($reservation->one_way == 0) : ?>
    <div style="margin-bottom: 5px;">
        <strong>Return Trip</strong>
    </div>
    <table style="width: 100%; background-color: #EEE; padding: 15px; margin-bottom: 20px;">
   	 <tr style="font-size: 20px; margin-bottom: 10px; color: #222;">
            <td><?= $reservation->formatted_return_time ?><td>
    	</tr>
        <tr>
            <td style="width: 33%;">
            	<div><?= $reservation->drop_off_address?></div>
            	<?php if (strlen($reservation->drop_off_unit) > 0) :?>
            	     APT <?= $reservation->drop_off_unit ?>
            	<?php endif; ?>
                <div><?= $reservation->drop_off_city . " VA, " . $reservation->drop_off_zip ?></div>
            </td>
            <td style="width: 33%; text-align: center;">to</td>
            <td style="width: 33%;">
            	<div style="text-align: right" align="right">
                    <div><?= $reservation->pick_up_address?></div>
                	<?php if (strlen($reservation->pick_up_unit) > 0) :?>
                	    APT <?= $reservation->pick_up_unit ?>
                	<?php endif; ?>
                    <div><?= $reservation->pick_up_city . " VA, " . $reservation->pick_up_zip ?></div>
            	</div>
             </td>
    	</tr>
    </table>
<?php endif; ?>	
<hr class="small-divider">
<?php if($reservation->status == 1):?>
	<p>
	    If this is not the exact time you requested, please keep in mind that our staff has scheduled this trip based upon your request and other factors including driver and vehicle availability.  If you no longer need this trip, please call JAUNT Reservations as soon as possible at (434) 296-3184.
	</p>
<?php else: ?>
	<p>
	    Please keep in mind that our staff will schedule this trip based upon your request and other factors including driver and vehicle availability,
	    so your approved trip may not be scheduled at the exact time you requested.  If you no longer need this trip, please call JAUNT Reservations as soon as possible at (434) 296-3184.
	</p>
<?php endif; ?>
<p>
    Thank you for using <?= $app_name ?>!
</p>
