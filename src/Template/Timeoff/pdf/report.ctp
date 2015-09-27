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
	margin-top: -30px;
	margin-bottom: 10px;
}

.table-preferences td, th {
	text-align: center;
}

.table-preferences th {
	background: #ccc;
}
</style>

<h1>Timeoff Report</h1>
<hr>

<h4> Employee </h4>
<p><?= $request['first_name'] . " " . $request['last_name'] ?></p>

<table>
	<tbody>
		<tr>
			<td><h4>Request Type</h4></td>
			<td><h4>Status</h4></td>
		</tr>
		<tr>
			<td><?= ucfirst($request->request_type) ?></td>
			<td><?= $request->text_status ?></td>
		</tr>
	</tbody>
</table>

<h4>Comments</h4>
<p><?= $request->comments ?></p>

<hr class="small-divider">
<?php if($request->status == 1):?>
	<p style="padding-bottom: 30px;"><strong>Please Note:</strong> since your request has been approved, only the approved time is shown in the table below.</p>
<?php endif; ?>
<table class="table-preferences" cellpadding="5" border="1">
    <tbody>
    <tr>
	    <th>Preference</th>
	    <th>Start</th>
	    <th>End</th>
    </tr>
    <?php $options = $request->getOptions(); ?>
    <?php foreach ($options as $option): ?>
        <?php if($request->status == 1 && $request->time_selected == $option['id']): ?>
            <tr>
	            <td><?= $option['id'] ?></td>
	            <td><?= date('m/d/y g:i a', strtotime($option['start'])); ?></td>
	            <td><?= date('m/d/y g:i a', strtotime($option['end'])); ?></td>
	        </tr>
        <?php elseif($request->status != 1): ?>
            <tr>
	            <td><?= $option['id'] ?></td>
	            <td><?= date('m/d/y g:i a', strtotime($option['start'])); ?></td>
	            <td><?= date('m/d/y g:i a', strtotime($option['end'])); ?></td>
	        </tr>
	    <?php endif; ?>
    <?php endforeach; ?>
    </tbody>
</table>