<?php 
	use Cake\Core\Configure; 
	// @codeCoverageIgnoreStart
    if (!Configure::read('PHPUNIT')) {
		require_once CORE_PATH . '../../../vendor/dompdf/dompdf/dompdf_config.inc.php';
		spl_autoload_register('DOMPDF_autoload'); 
		$dompdf = new DOMPDF(); 
		$dompdf->set_paper = 'A4';
		$dompdf->load_html(utf8_decode($this->fetch('content')));
		$dompdf->render();


		// echo $dompdf->output(); // uncomment this to view PDF without downloading
		if($this->request->controller == 'Timeoff') {
			$dompdf->stream("TimeoffReport-" . $request['first_name'] . $request['last_name'] . "-" . $request['id'] . ".pdf");
			// uncomment above line to download PDF
		} elseif($this->request->controller == 'Users') {
			if($this->request->action == 'graph') {
				$dompdf->stream("MetricsReport-" . $graph_labels[0] . "-" . $graph_labels[count($graph_labels) - 1] . ".pdf");
			}
		} elseif($this->request->controller == 'Ajax') {
			if($this->request->action == 'lifetime_report') {
				$dompdf->stream("LifetimeMetricsSummary." . date('n-j-Y', time()) . ".pdf");
			} else if($this->request->action == 'user_lifetime_report') {
				$dompdf->stream("LifetimeMetricsSummary." . $userName . "." . date('n-j-Y', time()) . ".pdf");
			}
			$dompdf->stream("LifetimeMetricsSummary." . $userName . "." . date('n-j-Y', time()) . ".pdf");
		} else {
			$dompdf->stream("ReservationSummary-" . $reservation['first_name'] . $reservation['last_name'] . "-" . $reservation['bookingNum'] . ".pdf");
		}
	}
?>
