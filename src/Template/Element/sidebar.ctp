
  <!-- Everyone -->
  <?= $this->Element('sidebar-link', ['controller' => 'Users', 'action' => 'profile', 'linkText' => 'Profile']); ?>
  <?= $this->Element('sidebar-link', ['controller' => 'Users', 'action' => 'edit_profile', 'linkText' => 'Edit Profile']); ?>

  <?php if ($user_role == 1): ?>
      <!-- Admin -->
      <hr>
      <?= $this->Element('reservations-link'); ?>
      <?= $this->Element('users-link'); ?>
      <?= $this->Element('timeoff-index-link'); ?>
      <?= $this->Element('metrics-link'); ?>
      <?= $this->Element('settings-link');?>

  <?php endif; ?>

  <?php if ($user_role == 2): ?>
      <!-- Client -->
      <?= $this->Element('reserve-link'); ?>
      <!-- <hr> -->
      <?= $this->Element('reservation-info-link', ['resAction' => 'upcoming_reservations', 'resText' => 'Upcoming Reservations']); ?>
      <?= $this->Element('reservation-info-link', ['resAction' => 'past_reservations', 'resText' => 'Past Reservations']); ?>
      <hr>
      <li>
          <?=$this->Html->link('How to Ride Jaunt', 'http://www.ridejaunt.org/make-reservation.asp', array('target' => '_blank', 'escape' => false));?>
      </li>
      <li>
          <?=$this->Html->link('FAQ', 'http://www.ridejaunt.org/faq.asp', array('target' => '_blank', 'escape' => false));?>
      </li>
  <?php endif; ?>

  <?php if ($user_role == 3): ?>
      <!-- Hourly -->
      <?= $this->Element('timeoff-link'); ?>
      <hr>
      <?= $this->Element('reservations-link'); ?>
  <?php endif; ?>

  <?php if ($user_role == 5): ?>
      <!-- Scheduler -->
      <?= $this->Element('timeoff-link'); ?>
      <hr>
      <?= $this->Element('reservations-link'); ?>
      <?= $this->Element('timeoff-index-link'); ?>
  <?php endif; ?>

  <?php if ($user_role == 4): ?>
      <!-- Driver -->
      <?= $this->Element('timeoff-link'); ?>
  <?php endif; ?>