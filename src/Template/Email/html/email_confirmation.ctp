<?= __("Dear {0} {1},", $first_name, $last_name) ?>
<br><br>
<?= __("Welcome to Jaunt!") ?>
<br><br>
<?= __("Please click the link below to verify your email:") ?>
<br>
<?php
    echo $this->Html->link([
        '_full' => true,
        'controller' => 'users',
        'action' => 'confirm',
        $key
    ]);
?>
<br><br>
<?= __("Thank you!") ?>