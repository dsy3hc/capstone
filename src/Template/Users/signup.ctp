<?php
use Cake\Core\Configure;
$myTemplates = [
    'error' => '<div class="alert alert-danger">{{content}}</div>'
];
$this->Form->templates($myTemplates);

$this->Html->script('Users/signup.js', array('block' => true));
$this->Html->script('https://www.google.com/recaptcha/api.js', array('block' => true));
echo $this->Form->create($user);
$header = __( "{0}: Sign Up", [Configure::read('App.name')]);
echo "<h2 style='text-align: center'>$header</h2>";
echo "<hr>";

$this->Form->templates([
    'radioWrapper' => '<div class="radio">{{label}}</div>'
]);
echo $this->Html->div($display, $sorry_message.'<hr>', ['id' => 'sorry-message']);
echo __('Have you ridden JAUNT before?');
echo $this->Form->radio('ridden_before', ['true' => __('Yes'), 'false' => __('No')]);
echo "<hr>";
echo $this->Form->input('first_name', array(
            'div' => 'form-group',
            'label' => ['class' => 'hidden'],
            'placeholder' => __('First Name'),
            'class' => 'form-control'
        ));
echo $this->Form->input('last_name', array(
            'div' => 'form-group',
            'label' => ['class' => 'hidden'],
            'placeholder' => __('Last Name'),
            'class' => 'form-control'
        ));
echo $this->Form->input('email', array(
            'type' => 'email',
            'label' => ['class' => 'hidden'],
            'div' => 'form-group',
            'placeholder' => __('Email'),
            'class' => 'form-control'
        ));
echo $this->Form->input('password', array(
            'div' => 'form-group',
            'label' => ['class' => 'hidden'],
            'placeholder' => __('Password'),
            'class' => 'form-control'
        ));
echo $this->Form->input('confirm', array(
            'div' => 'form-group',
            'type' => 'password',
            'label' => ['class' => 'hidden'],
            'placeholder' => __('Confirm Password'),
            'class' => 'form-control'
        ));
        $languages= [''=>'Select Language', 'en_US'=>'English','es_ES'=>'Spanish','fr_FR'=>'French' ];

echo $this->Form->input('language', array(
            'div' => 'form-group',
            'options' => $languages,
            'label' => ['class' => 'hidden'],
            'placeholder' => __('Language'),
            'class' => 'form-control'
        ));
echo "<div class='g-recaptcha' data-sitekey='$site_key' style='margin-bottom: 10px'></div>";
echo $this->Form->button(__('Create Account'), array(
            'div' => 'form-group',
            'class' => 'btn btn-primary'
        ));
echo $this->Form->end();
?>
<hr>

<p style="text-align:center">
    <?php echo __('Already have an account?'); ?>
    <?=
        $this->Html->link(__('Login Here!'),
            ['controller' => 'Users', 'action' => 'login']
        )
    ?>
</p>
