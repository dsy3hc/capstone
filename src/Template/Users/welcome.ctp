<?php
    use Cake\Core\Configure;

    echo __('Thank you for your interest in {0}! Once a staff member has reviewed your registration and confirmed that
    you have a previous profile with JAUNT, a confirmation email will be sent to <b>{2}</b>. In the meantime,
    if you need to schedule an upcoming appointment or make changes to an existing appointment, please contact JAUNT
    staff at {1}. Thanks!', Configure::read('App.name'), Configure::read('JAUNT.phone_number'), $email);