<!-- File: src/Template/Users/metrics.ctp -->
<?php
	use Cake\Routing\Router;
    $this->Html->script('chart.min.js', array('block' => true));
    $this->Html->css('Users/metrics.css', array('block' => true));
?>


<div class="row">
    <div class="col-md-6">
        <h1>Usage Metrics</h1>
    </div>
</div>
<hr>



<?php
	$myTemplates = [
	    'message' => '<div class="alert alert-warning">{{content}}</div>'
	];
	
	if(!isset($startDate)) {
		$startDate = null;
	}
	if(!isset($endDate)) {
		$endDate = null;
	}

	if(!isset($userStartDate)) {
		$userStartDate = null;
	}
	if(!isset($userEndDate)) {
		$userEndDate = null;
	}

	$this->Form->templates($myTemplates);
	
?>
	<div class="row">
		<div class="col-md-4">
			<div class="row" style="margin-bottom: 5px;">
				<div class="col-md-12">
					<?php 
						echo $this->Form->button(__('Lifetime Metrics'),
			                array(
			                    'class' => 'btn btn-primary',
			                    'id' => 'system-lifetime'
			                ));
					?>
				</div>
			</div>
			<div class="row" style="margin-bottom: 5px;">
				<div class="col-md-12">
				<?= $this->Html->link(__('Generate Lifetime PDF'), ['controller' => 'ajax', 'action' => 'lifetime_report', '_ext' => 'pdf'], ['class' => 'btn btn-primary']); ?>
				</div>
			</div>
		</div>
		<div class="col-md-7 col-md-offset-1">
			<div class="row">
				<?php 
						echo '<div class="col-md-4">';
							echo $this->Form->input('start day', 
							                        array(
							                            'type' => 'text', 
							                            'title' => 'start day',
							                            'class' => 'form-control',
							                            'label' => ['class' => 'hidden'],
							                            'placeholder' => 'Start Date',
							                            'value' => $startDate
							                        ));
							echo $this->Form->input('start day formatted', 
							                        array(
							                            'class' => 'hidden',
							                            'label' => ['class' => 'hidden']
							                        ));
						echo '</div>
							<div class="col-md-4">';
							echo $this->Form->input('end day', 
						                        array(
						                            'type' => 'text', 
						                            'title' => 'end day',
						                            'class' => 'form-control',
						                            'label' => ['class' => 'hidden'],
						                            'placeholder' => 'End Date',
						                            'value' => $endDate
						                        ));
							echo $this->Form->input('end day formatted', 
							                        array(
							                            'class' => 'hidden',
							                            'label' => ['class' => 'hidden']
							                        ));
						echo '</div>
						<div class="col-md-4">';
							echo $this->Form->button(__('Apply Filter'),
	                            array(
	                                'class' => 'btn btn-primary',
	                                'id' => 'system-date-filter'
	                            ));
						echo '</div>';
					echo '</div>';
				?>
			</div>
		</div>
	</div>
</div>
<?php
	echo "<div class='row' id='results'>
			<div class='col-md-8'>";
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
			</div>
		</div>";
	echo "<hr>";
	echo "<div class='row'>
			<div class='col-md-6'>";
				echo "<h2>User Specific Metrics</h2>";
			echo '</div>';
			echo "<div class='col-md-4 col-md-offset-2'>";
				echo "<h2>";
			    echo $this->Form->input('user_id',
			    	array(
			            'templates' => [
			                'formGroup' => '{{input}}',
			                'error' => '<div class="error">{{content}}</div>'
			            ],
			            'type' => 'select',
			            'class' => 'form-control'
			        ),
			        ['options' => $users]);
			    echo "</h2>";
			echo "</div>
		</div>";
?>
<br>
<div class="row">
	<div class="col-md-3">
		<?php 
			echo $this->Form->button(__('Lifetime Metrics'),
                array(
                    'class' => 'btn btn-primary',
                    'id' => 'user-lifetime'
                ));
		?>
	</div>
	<div class="col-md-8 col-md-offset-1">
		<div class="row">
			<?php 
					echo '<div class="col-md-4">';
						echo $this->Form->input('user start day', 
						                        array(
						                            'type' => 'text', 
						                            'title' => 'user start day',
						                            'class' => 'form-control',
						                            'label' => ['class' => 'hidden'],
						                            'placeholder' => 'Start Date'
						                        ));
						echo $this->Form->input('user start day formatted', 
						                        array(
						                            'class' => 'hidden',
						                            'label' => ['class' => 'hidden']
						                        ));
					echo '</div>
						<div class="col-md-4">';
						echo $this->Form->input('user end day', 
					                        array(
					                            'type' => 'text', 
					                            'title' => 'user end day',
					                            'class' => 'form-control',
					                            'label' => ['class' => 'hidden'],
					                            'placeholder' => 'End Date'
					                        ));
						echo $this->Form->input('user end day formatted', 
						                        array(
						                            'class' => 'hidden',
						                            'label' => ['class' => 'hidden']
						                        ));
					echo '</div>
					<div class="col-md-4">';
						echo $this->Form->button(__('Apply Filter'),
                            array(
                                'class' => 'btn btn-primary',
	                            'id' => 'user-date-filter'
                            ));
					echo '</div>';
				echo '</div>';
		?>
	</div>
</div>
<div class="row" id="userResults">
	<div class='col-md-10'>
		<table class='table table-hover'>
			<?php
				echo "<tr>
					<th>
						Last Activity for Specified User: </th>
					<td>" . $userActivity . "</td>
				</tr>";
				echo "<tr>
					<th>
						Total Reservations for Specified User: </th>
					<td>" . $userReservations . "</td>
				</tr>";
			?>
		</table>
	</div>
</div>
<hr>
<div class="row">
    <div class="col-md-4 col-sm-12">
        <?= $this->Html->link(__('Generate Trends PDF'), ['action' => 'graph', '_ext' => 'pdf'], ['class' => 'btn btn-primary']); ?>
    </div>
    <div class="col-md-4 col-sm-12">
    	<?php $filename = "MetricsReport-" . $first_month . "-" . $last_month; ?>
        <?= $this->Html->link(__('Generate Trends CSV'), ['action' => "graph_csv/$filename", '_ext' => 'csv'], ['class' => 'btn btn-primary']); ?>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs" id="graph-tabs">
            <li data-tab="reservations-tab" class="active"><a>Reservations</a></li>
            <li data-tab="registrations-tab"><a>Registrations</a></li>
            <li data-tab="timeoff-tab"><a>Time Off</a></li>
        </ul>
    </div>
</div>
<div class="content-tab" id="reservations-tab" data-points='<?= $graph_reservation_points ?>'>
    <div class="row">
        <div class="col-md-12">
            <h3>Reservations Per Month</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10">
            <canvas id="reservation-graph"></canvas>
        </div>
        <div class="col-md-2">
            <div class="col-md-2 legend"></div>
        </div>
    </div>
</div>
<div class="content-tab" id="registrations-tab" data-points='<?= $graph_registration_points ?>' style="display: none">
    <div class="row">
        <div class="col-md-12">
            <h3>Registrations Per Month</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10">
            <canvas id="registration-graph"></canvas>
        </div>
        <div class="col-md-2">
            <div class="col-md-2 legend"></div>
        </div>
    </div>
</div>
<div class="content-tab" id="timeoff-tab" data-points='<?= $graph_timeoff_points ?>' style="display: none">
    <div class="row">
        <div class="col-md-12">
            <h3>Time Off Requests Per Month</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10">
            <canvas id="timeoff-graph"></canvas>
        </div>
        <div class="col-md-2">
            <div class="col-md-2 legend"></div>
        </div>
    </div>
</div>
<br>
<script>
	$(function() {
        $(document).ready(function() {
            $('#graph-tabs > li').click(function() {
                $('#graph-tabs > li').removeClass('active');
                $('.content-tab').hide();
                $(this).addClass('active');

                var tab = $('#' + $(this).attr('data-tab'));
                tab.show();
                graph(tab, months);
                $('html, body').scrollTop($("#graph-tabs").offset().top);
            });
        });


    	$( '[title="start day"]' ).datepicker({
			altFormat: "yy-mm-dd",
            altField: "[name='start day formatted']" 
    	});

    	$( '[title="end day"]' ).datepicker({
			altFormat: "yy-mm-dd",
            altField: "[name='end day formatted']" 
    	});
    	$( '[title="user start day"]' ).datepicker({
			altFormat: "yy-mm-dd",
            altField: "[name='user start day formatted']" 
    	});

    	$( '[title="user end day"]' ).datepicker({
			altFormat: "yy-mm-dd",
            altField: "[name='user end day formatted']" 
    	});
  	});

	$("#system-lifetime").click(function() {
        $.ajax({
            type:'POST',
            url: "<?php echo Router::Url(array('controller' => 'ajax', 'action' => 'lifetimeMetrics')); ?>",
            success: function(response) {
                $('#results').html(response);
                $("#start-day").val(null);
                $("#end-day").val(null);
            },
        });
        return false;
    });

  	$("#system-date-filter").click(function() {
  		var start_date_formatted = $("#start-day-formatted").val();
  		var end_date_formatted = $("#end-day-formatted").val();
        $.ajax({
            type:'POST',
            data: {
            	start_day_formatted: start_date_formatted,
            	end_day_formatted: end_date_formatted
            },
            url: "<?php echo Router::Url(array('controller' => 'ajax', 'action' => 'dateMetrics')); ?>",
            success: function(response) {
                $('#results').html(response);
            },
        });
        return false;
    });

    $("#user-lifetime").click(function() {
  		var user_id = $("#user-id").val();
        $.ajax({
            type:'POST',
            data: {
            	user_id: user_id
            },
            url: "<?php echo Router::Url(array('controller' => 'ajax', 'action' => 'userLifetimeMetrics')); ?>",
            success: function(response) {
                $('#userResults').html(response);
                $("#user-start-day").val(null);
                $("#user-end-day").val(null);
            },
        });
        return false;
    });

    $("#user-date-filter").click(function() {
  		var start_date_formatted = $("#user-start-day-formatted").val();
  		var end_date_formatted = $("#user-end-day-formatted").val();
  		var user_id = $("#user-id").val();
        $.ajax({
            type:'POST',
            data: {
            	user_start_day_formatted: start_date_formatted,
            	user_end_day_formatted: end_date_formatted,
            	user_id: user_id
            },
            url: "<?php echo Router::Url(array('controller' => 'ajax', 'action' => 'userDateMetrics')); ?>",
            success: function(response) {
                $('#userResults').html(response);
            },
        });
        return false;
    });

    <?php echo "var months = $graph_labels;\n" ?>

    graph($('#reservations-tab'), months);

    function graph(tab, labels) {
        var data = JSON.parse(tab.attr("data-points"));
        console.log(data);

        var colors = ['rgba(151, 187, 205, 1)', 'rgba(208, 135, 112, 1)', 'rgba(163, 190, 140, 1)'];

        var datasets = [];
        for (var name in data) {
            if (data.hasOwnProperty(name)) {
                datasets.push({
                    label: name,
                    strokeColor: colors[datasets.length],
                    pointColor: colors[datasets.length],
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: colors[datasets.length],
                    data: data[name]
                });
            }
        }

        var chart_data = {
            labels: labels,
            datasets: datasets
        };
        var canvas = tab.find('canvas').get()[0];
        var ctx = canvas.getContext("2d");
        var chart = new Chart(ctx).Line(chart_data, {
            responsive: true,
            bezierCurve: false,
            datasetFill: false,
            animation: false
        });
        var legend = chart.generateLegend();
        tab.find('.legend').html(legend);
    }

</script>