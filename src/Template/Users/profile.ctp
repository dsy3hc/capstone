<!-- File: src/Template/Users/profile.ctp -->
<h1>
  <?= $user->first_name?> <?= $user->last_name?>
</h1>
<h4>
	<?= $user->email?><br><br>
</h4>
<h6>
	<strong><?php echo __('Last Activity:') ?></strong>
	<?php echo $Last_Activity;?> 
</h6>
<?php if (!is_null($user->expiration_date)): ?>
    <h6><strong><?php echo __('CAT Expiration Date:')?> </strong>
    <?= $this->Time->format(
        $user->expiration_date,
        'MMMM d, yyyy'
    ); ?>
    </h6>

    <?php if(strtotime($user->expiration_date) < strtotime('+2 months') and strtotime($user->expiration_date) > time()): ?>
    	<div class="alert alert-danger" role="alert">
            <strong><?php echo __('Uh-oh!')?></strong> <?php echo __('Looks like your CAT Certification will expire')?>
            <strong><?php echo __('less than 2 months from today')?></strong>.
        </div>
    <?php elseif(strtotime($user->expiration_date) < time()): ?>
        <div class="alert alert-danger" role="alert">
            <strong><?php echo __('Uh-oh!')?></strong> <?php echo __('Looks like your CAT Certification has')?>
            <strong><?php echo __('already expired.')?></strong>.
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php if($user_role == 2):?>
    <h6><strong><?php echo __('Average Reservations Per Month:')?> </strong><?= round($resPerMonth, 2) ?> </h6>
    <hr>
	<h3> <?php echo __('Pending Reservations')?> </h3>
	<?php echo $this->element('reservation_tab'); ?>
<?php endif; ?>	
<?php if($user_role == 3 or $user_role == 4 or $user_role == 5): ?>
    <hr>
	<h3> <?php echo __('Pending & Approved Time Off Requests')?> </h3>
    <?= $this->element('Calendar/base', [
        'sources' => ['approved', 'pending'],
        'modal' => 'Calendar/modal_approve'
    ]) ?>
<?php endif; ?>
