<!-- File: src/Template/Users/edit.ctp -->
<?php
    $this->Html->script('Users/add_edit.js', array('block' => true));
    echo $this->Form->create($user);
?>
<h1><?php echo __('Edit Profile') ?></h1>
<hr>
<div class="row">
	<div class="col-md-6">
        <?= $this->Form->input(__('email'), ['class' => 'form-control']) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?= $this->Form->input(__('password'), ['value' => '','class' => 'form-control']) ?>
    </div>
</div>
<div class="row">
	<div class ="col-md-6">
	   <?= $this->Form->input('cPassword',['value'=>'','type'=>'password','label' => __('Confirm password'), 'class'=>'form-control']) ?>
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
<?= $this->Form->button(__('Save Changes'), ['class' => 'btn btn-success btn-lg']); ?>
<?= $this->Form->end(); ?>


