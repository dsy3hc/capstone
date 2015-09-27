<?php
	if(isset($timeoffView) && $timeoffView == true) {
		$condition = in_array($this->request->action, ['index', 'view']);
	} else {
		$condition = $this->request->action == $action;
	}

?>
<li <?php if($this->request->controller == $controller && $condition) { echo 'class=\'active\''; }?> >
    <?=
	    $this->Html->link(
	        $linkText,
	        ['controller' => $controller, 'action' => $action]
	    )
    ?>
</li>