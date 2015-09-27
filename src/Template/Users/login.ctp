<?php
	$myTemplates = [
			    'message' => '<div class="alert alert-warning">{{content}}</div>'
			];
			$this->Form->templates($myTemplates);
	echo $this->Form->create('login');

	echo $this->Form->input(__('email'), array(
				'type' => 'email',
				'label' => ['class' => 'hidden'],
				'div' => 'form-group',
				'placeholder' => 'Email',
				'class' => 'form-control'
			));
	echo $this->Form->input(__('password'), array(
				'div' => 'form-group',
				'label' => ['class' => 'hidden'],
				'placeholder' => 'Password',
				'class' => 'form-control'
			));
	echo $this->Form->button(__('Login'), array(
				'div' => 'form-group',
				'class' => 'btn btn-primary'
			));
	echo $this->Form->end();
	echo "<hr>";
?>
<p style="text-align:center">
    <?=
    $this->Html->link(__('Forgot your password?'),
        ['controller' => 'Users', 'action' => 'reset_password']
    )
    ?>
</p>
<p style="text-align:center">
<?php  echo __('No account?'); ?>
    <?=
        $this->Html->link(__('Sign Up Here!'),
            ['controller' => 'Users', 'action' => 'signup']
        )
    ?>
</p>

