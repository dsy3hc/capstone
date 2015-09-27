<?php
    use Cake\Core\Configure;
?>

<?= __("Dear {0} {1},", $first_name, $last_name) ?>
<br><br>
<?= __("There has been a request to reset the password on your JAUNT {0} account.
        To reset your password, please click the link below:", Configure::read('App.name')) ?>
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
<?= __("If you did not request a password reset, please ignore this message.") ?>
<br><br>
<?= __("Thank you,") ?>
<br>
<?= __("JAUNT") ?>