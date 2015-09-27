<?php
    use Cake\Core\Configure;
?>

<?= __("Dear {0} {1},", $first_name, $last_name) ?>
<br><br>
<?= __("An account on JAUNT {0} has been created for you. Before you can use your account, you need
to set your account password. To do so, please click the link below:", Configure::read('App.name')) ?>
<br><br>
<?php
echo $this->Html->link([
    '_full' => true,
    'controller' => 'users',
    'action' => 'reset_password',
    $key,
]);
?>
<br><br>
<?= __("Thank you,") ?>
<br>
<?= __("JAUNT") ?>