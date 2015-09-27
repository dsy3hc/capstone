<?= $this->Form->create() ?>
<h2 class="text-center">
    <?= __("Reset Password") ?>
</h2>
<hr>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <?= $this->Form->input('email', ['class' => 'form-control', 'type' => 'email']); ?>
        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-success']); ?>
    </div>
</div>

<?= $this->Form->end(); ?>
