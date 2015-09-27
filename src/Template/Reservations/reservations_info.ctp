<h3> Reservations Info </h3>
<br>
<table class="table table-striped">
    <thead>
        <th>Reservation Date</th>
        <th>Fare </th>

    </thead>


<?php foreach ($past_reservations as $reservation): ?>
    <tr>
        <td><?= date('F j, Y',strtotime($reservation->pick_up_day)); ?></td>
        <td>$____<td>
    </tr>
<?php endforeach; ?>
</table>


<table class="table table-striped">
    <thead>
        <th>Number of Rides</th>
        <th>Total Fare </th>
        <th>Avg. Fare </th>
    </thead>
    <tr>
        <td><?php echo $numReservations?></td>
        <td> $$$$$</td>
        <td> $$$$/<?php echo $numReservations?></td>
    </tr>
</table>
