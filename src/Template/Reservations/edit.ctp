<?php
$myTemplates = [
    'message' => '<div class="alert alert-warning">{{content}}</div>',
    'dateWidget' => '<div class="col-md-4">
                        {{hour}}
                    </div>
                    <div class="col-md-4">
                        {{minute}}
                    </div>
                    <div class="col-md-4">
                        {{second}}
                    </div>
                    <div class="col-md-4">
                        {{meridian}}
                    </div>'
];
$this->Html->script('selectClass', array('block' => true));
$this->Form->templates($myTemplates);
echo $this->Form->create($reservation);
?>

<h1>Edit Reservation</h1>
<p>
    <?= __("Please note that by editing the times for this reservation, you are thereby approving the reservation at those times.") ?>
</p>
<hr>

<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6">
        <?php
        echo $this->Form->input('trip day',
            array(
                'type' => 'text',
                'class' => 'form-control',
                'value' => $reservation->pick_up_day->format('m/d/Y')
            ));
        echo $this->Form->input('pick up day',
            array(
                'value' => $reservation->pick_up_day->format('Y-m-d'),
                'class' => 'hidden',
                'label' => ['class' => 'hidden']
            ));
        ?>
    </div>
    <div class='col-lg-6 col-md-6 col-sm-6'>
        <label>Pickup Time</label>
        <?= $this->Form->input('pick_up_time',
            array(
                'type'=> 'time',
                'templates' => [
                    'formGroup' => '<div class="row time">{{label}}{{input}}</div>',
                    'error' => '<div class="error">{{content}}</div>',
                ],
                'label' => ['class' => 'hidden'],
                'timeFormat' => '12',
                'interval' => 5,
                'start' => '8',
                'end' => '16'
            )); ?>
    </div>
</div>

<?php $hidden = $reservation->return_time == null ? 'display: none;' : '' ?>
<div class='row'>
    <div class='col-lg-6 col-md-6 col-sm-6'>
        <label>Return Time</label>
        <div class="checkbox" style="margin-top: 0; margin-bottom: 5px;">
            <label>
                <?= $this->Form->checkbox('will call', [
                    'id' => 'will-call-chkbx',
                    'checked' => $reservation->return_time == null,
                ]) ?>
                Will Call
            </label>
        </div>
        <div id="return-time-container" style="<?= $hidden ?>">
        <?= $this->Form->input('return_time',
            array(
                'templates' => [
                    'formGroup' => '{{label}}<div class="row time">{{input}}</div>',
                    'error' => '<div class="error">{{content}}</div>',
                    'disabled' => false
                ],
                'label' => ['class' => 'hidden'],
                'class' => 'form-control',
                'id' => 'return_time',
                'type' => 'time',
                'timeFormat' => '12',
                'interval' => 5
            )); ?>
        </div>
    </div>
</div>

<hr>

<?php echo $this->Form->button(__('Update and Approve'),
    array(
        'class' => 'btn btn-primary btn-lg'
    ));
echo $this->Form->end();
?>

<script type="text/javascript">
    $( document ).ready(function() {
        $('#trip-day').datepicker({
            altFormat: "yy-mm-dd",
            altField: "#pick-up-day"
        });

        $('#will-call-chkbx').change(function() {
            if ($(this).prop('checked') == true) {
                $('#return-time-container').hide();
            }
            else {
                $('#return-time-container').show();

            }
        })
    });
</script>
