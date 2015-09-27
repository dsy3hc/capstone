<!-- File: src/Template/Reservations/request.ctp -->
<h1>Request Time Off</h1>
<p> Please fill out this form to request time off. You will receive a notification in your <strong>physical</strong> mailbox upon processing of the request. It is not neccesary to fill all three choices.
</p>
<hr>
<?php
    echo $this->Form->create($request);
    $this->Html->script('Timeoff/request.js', array('block' => true));
    $this->Html->css('Timeoff/request', array('block' => true));
    $radio_wrapper = '<div class="radio">{{label}}</div>';
    $templates = [
        'radioWrapper' => $radio_wrapper
    ];
    $this->Form->templates($templates);
?>

<div class='row'>
	<div class='col-md-6'>
        <?php
		echo $this->Form->input('first name',
				array(
					'class' => 'form-control',
					'default' => $firstname
				     ));
        ?>

	</div>
	<div class='col-md-6'>
        <?php
			echo $this->Form->input('last name',
					array(
						'class' => 'form-control',
						'default' => $lastname
					     ));
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <strong>Request Type</strong>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?= $this->Form->radio('request_type', [
            'sick' => 'Sick',
            'bonus' => 'Bonus',
            'annual' => 'Annual'
        ]) ?>
    </div>
</div>
<?php
echo  $this->Form->hidden('start_date_1');
echo  $this->Form->hidden('end_date_1');
echo  $this->Form->hidden('start_date_2');
echo  $this->Form->hidden('end_date_2');
echo  $this->Form->hidden('start_date_3');
echo  $this->Form->hidden('end_date_3');
?>
<div id="table-container" class="hidden">
    <div class="row">
        <div id="table-header" class="col-md-4 col-md-offset-4">
            <h3>Selected Options</h3>
        </div>
    </div>
    <table id="options-table" class="table">
        <thead>
        <tr>
            <th>Start</th>
            <th>End</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
<?= $this->element('Calendar/base', ['modal' => 'Calendar/modal_request']) ?>
<div id="comments-container" class="row">
    <div class="col-md-12">
        <?=
            $this->Form->input('comments',
            [
                'type'=> 'textarea',
                'templates' => [
                    'formGroup' => '{{label}}<br>{{input}}'
                ],
                'class' => 'form-control',
                'rows' => '6',
		'id' => 'comments',
            ]);
        ?>
    </div>
</div>
<?php
    echo $this->Form->button(__('Save Request'),
            array(
                'id' => 'submit-btn',
                'class' => 'btn btn-primary btn-lg'
                 ));
    echo $this->Form->end();
?>

<?= $this->Html->script('selectClass') ?>
