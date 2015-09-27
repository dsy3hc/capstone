<!-- File: src/Template/Users/add.ctp -->
<?php echo $this->Form->create($user) ?>
<h1>Add User</h1>
<hr>
<div class="row">
    <div class="col-md-6">
        <?= $this->Form->input('first_name', ['class' => 'form-control']); ?>
    </div>
    <div class="col-md-6">
        <?= $this->Form->input('last_name', ['class' => 'form-control']); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?= $this->Form->input('email', ['class' => 'form-control', 'type' => 'email']); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
<?php
    echo $this->Form->input('role_id', [
            'options' => $roles,
            'type' => 'select',
            'class' => 'form-control',
            'empty' => true
        ]
    );
?>
    </div>
</div>
<div class="row">
	<div class ="col-md-4">
        <?php
        $languages= ['en_US'=>'English','es_ES'=>'Spanish','fr_FR'=>'French' ];
            echo $this->Form->input(__('language'), [
                    'options' => $languages,
                    'type' => 'select',
                    'class' => 'form-control',
                    'empty' => true
                ]
            );
        ?>
    </div>
</div>
<?= $this->element('Users/client_info', ['user' => $user]) ?>
<?= $this->Form->button(__('Save User'), ['class' => 'btn btn-success btn-lg']); ?>
<?= $this->Form->end(); ?>
