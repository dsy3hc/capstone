<?php
$class = 'message alert alert-warning';
if (!empty($params['class'])) {
	$class .= ' ' . $params['class'];
}
?>
<div class="<?= h($class) ?>"><?= h($message) ?></div>
