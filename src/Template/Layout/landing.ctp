<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'JAUNT';
?>
<!DOCTYPE html>
<html>
<head>
	<?= $this->Html->charset() ?>
	<title>
		<?= $cakeDescription ?>:
		<?= $this->fetch('title') ?>
	</title>
	<?= $this->Html->meta('icon') ?>

	<?= $this->Html->css('bootstrap') ?>
    <?= $this->Html->script('jquery-1.11.1.min') ?>
	<?= $this->fetch('meta') ?>
	<?= $this->fetch('css') ?>
	<?= $this->fetch('script') ?>
</head>
<body>
	<div id="container">
		<div id="content">
			<div class="container">
				<div class="row">
					<div class="col-md-4 col-md-offset-4 well well-lg">
						<?php
							echo $this->Html->image('logo.jpg', array(
									'style' => 'width: 100%'
								));
						?>
						<hr>
						<?= $this->Flash->render() ?>
						<?= $this->fetch('content') ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
