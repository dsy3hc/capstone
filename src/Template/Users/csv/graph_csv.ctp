<?php
	$line = ["Month", "Reservations", "Registrations", "Timeoff Requests - Sick", "Timeoff Requests - Annual", "Timeoff Requests - Bonus"];
	echo implode(",",$line)."\n"; 
	for($x = 0; $x < count($graph_labels); $x++) {
		$line = [];
		$line[] = $graph_labels[$x];
		$line[] = $graph_reservation_points[$x];
		$line[] = $graph_registration_points[$x];
		$line[] = $graph_timeoff_points['Sick'][$x];
		$line[] = $graph_timeoff_points['Annual'][$x];
		$line[] = $graph_timeoff_points['Bonus'][$x];
		 // Loop through every value in a row 
        foreach ($line as &$value) 
        { 
            // Apply opening and closing text delimiters to every value 
            $value = "\"".$value."\""; 
        } 
        // Echo all values in a row comma separated 
        echo implode(",",$line)."\n"; 
	}
?>