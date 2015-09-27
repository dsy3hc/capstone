<?= $this->Form->create() ?>
<h1>
    <?= __("Change Password") ?>
</h1>
<hr>
<div class="row">
    <div class="col-md-12">
        <?= $this->Form->input('password', ['class' => 'form-control', 'type' => 'password']); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?= $this->Form->input('confirm_password', ['class' => 'form-control', 'type' => 'password']); ?>
    </div>
</div>
<?= $this->Form->button(__('Submit'), ['class' => 'btn btn-success btn-lg']); ?>
<?= $this->Form->end(); ?>
