<!-- File: src/Template/Reservations/reserve.ctp -->

<h1>Reserve a Ride</h1>
<p>
    Thank you for making your JAUNT reservation online.  Please fill out this form with all important information for your upcoming trip.  Please submit all trip requests at least <strong>48 hours in advance.</strong>
</p>
<p>
    If you need help filling out this trip request, call us at 434-296-3184 or send an email to <a href="mailto: trips@ridejaunt.org">trips@ridejaunt.org</a>.
</p>
<?php if($role != 2) : ?>
<p><em>
    You are filling this form out as a staff member for a client, so you will need to enter the Client ID of the specific client to complete this form.
</em></p>
<?php endif; ?>
<?php if(isset($error) and count($error) > 0): ?>
    <p>
        You have some errors in your form:
        <ul class="red">
            <?php foreach($error as $err): ?>
                <li><?= $err ?></li>
            <?php endforeach; ?>
        </ul>
    </p>
<?php endif; ?>
<hr>
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
    
    $this->Html->script('Reservations/restrictTime.js', array('block' => true));
    $this->Html->script('Reservations/willcall.js', array('block' => true));
    $this->Html->script('Reservations/oneway.js', array('block' => true));
    $this->Html->script('selectClass', array('block' => true));
    $this->Form->templates($myTemplates);
    echo $this->Form->create($reservation);

    if($role == 1) {
        // debug("here");
        $firstname = "";
        $lastname = "";
    }
?>

    <div class='row'>
        <div class='col-md-6'>
            <?= $this->Form->input('first name',
                                    array(
                                        'class' => 'form-control',
                                        'default' => isset($submitted) ? $old_data['first_name']: $firstname
                                    )); ?>
        </div>
        <div class='col-md-6'>
            <?= $this->Form->input('last name',
                                    array(
                                        'class' => 'form-control',
                                        'default' => isset($submitted) ? $old_data['last_name']: $lastname
                                    )); ?>
        </div>
    </div>
    <?php if($role != 2) : ?>
    <div class='row'>
        <div class='col-md-6'>
            <?= $this->Form->input('clientID',
                                    array(
                                        'type' => 'number',
                                        'class' => 'form-control',
                                        'label' => 'Client ID'
                                    )); ?>
        </div>
    </div>
    <?php endif; ?>
    <div class='row' style="margin-top: 5px; margin-bottom: 20px;">
        <div class='col-md-12'>
                <?= $this->Form->checkbox('doctors appointment', array('hiddenField' => false, 'id' => 'medical')); ?>
                <?= __("My destination is a medical appointment.") ?>
        </div>
    </div>
    <div class='row' style="margin-top: 5px; margin-bottom: 20px;">
        <div class='col-md-12'>
                <?= $this->Form->checkbox('one way', array('hiddenField' => false, 'id' => 'one_way')); ?>
                <?= __("My trip is one way.") ?>
        </div>
    </div>	
    <div class='row'>
        <div class='col-md-4'>
        <?php
            $pickupDay = $this->Time->format('+2 day', 'M/d/Y');
            $returnTime = $this->Time->format('+2 hour', 'H:m:s');

            echo $this->Form->input('trip day',
                                    array(
                                        'type' => 'text',
                                        'class' => 'form-control',
                                        'value' => isset($submitted) ? $old_data['pick_up_day']: $pickupDay
                                    ));
            echo $this->Form->input('pick up day',
                                    array(
                                        'class' => 'hidden',
                                        'label' => ['class' => 'hidden'],
                                        'value' => isset($submitted) ? $old_data['pick_up_day'] : null
                                    ));
        ?>
        </div>
        <div class='col-lg-6 col-md-6 col-sm-6'>
            <label>Trip Time</label>
                <?= $this->Form->input('pick up time',
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
                                            'end' => '16',
                                            'value' => isset($submitted) ? $old_data['pick_up_time'] : null
                                        )); ?>
        </div>
    </div>
    <div class='row'>
        <div class='col-md-4'>
            <label><?= __("Previous Addresses") ?></label>
            <?= $this->Form->input('pickup_address_list', [
                    'options' => $pickup_address,
                    'type' => 'select',
                    'class' => 'form-control',
                    'label' => ['class' => 'hidden'],
                    'empty' => true
                ]
            ); ?>
        </div>
        <div class='col-md-5'>
                <?= $this->Form->input('pick up address',
                                        array(
                                            'class' => 'form-control',
                                            'value' => isset($submitted) ? $old_data['pick_up_address'] : null
                                        )); ?>
        </div>
        <div class='col-md-3'>
                <?= $this->Form->input('pick up unit',
                                        array(
                                            'class' => 'form-control',
                                            'type'=>'text',
                                            'value' => isset($submitted) ? $old_data['pick_up_unit'] : null
                                        )); ?>
        </div>
    </div>
    <div class='row'>
        <div class='col-md-3 col-md-offset-4'>
            <?= $this->Form->input('pick up city',
                                    array(
                                        'class' => 'form-control',
                                        'value' => isset($submitted) ? $old_data['pick_up_city'] : null
                                    )); ?>
        </div>
        <div class= 'col-md-2'>
            <?= $this->Form->input('pick up zip',
                        array(
                            'class' => 'form-control',
                            'type'=> 'number',
                            'value' => isset($submitted) ? $old_data['pick_up_zip'] : null
                        )); ?>
        </div>
    </div>

    <hr>

    <div class="row" id="returnTime_label">
        <div class="col-md-3">
            <strong>Return Time</strong>
        </div>
    </div>
    <div class="hidden" id="willcall" style="margin-top: 5px; margin-bottom: 20px">
        <div class='row'>
            <div class="col-md-12">
                <?= $this->Form->checkbox('willcall', array('hiddenField' => false, 'id' => 'willcall-box')); ?>
                <?= __("I would like to have a will call return time.") ?>
                <span id="willcall-tooltip" class="glyphicon glyphicon-question-sign" style="font-size: 20px"
                      title="<?= __("Rather than specifying a return time, you will call JAUNT when you are ready to be picked up") ?>"></span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 hidden" id="willcall-msg" style="margin-top: 5px">
                <em><?= __("Please note that requests for will call are not accepted after 2pm or on weekends.") ?></em>
            </div>
        </div>
    </div>
    <div class='row' id="returnTime">
        <div class='col-lg-6 col-md-6 col-sm-6'>
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
                                            'interval' => 5,
                                            'value' => isset($submitted) ? $old_data['return_time'] : null
                                        )); ?>

        </div>
    </div>
    <div class='row'>
        <div class='col-md-4'>
            <label><?= __("Previous Addresses") ?></label>
            <?= $this->Form->input('dropoff_address_list', [
                    'options' => $dropoff_address,
                    'type' => 'select',
                    'class' => 'form-control',
                    'label' => ['class' => 'hidden'],
                    'empty' => true
                ]
            ); ?>
        </div>
        <div class='col-md-5'>
                <?= $this->Form->input('drop off address',
                                        array(
                                            'class' => 'form-control',
                                            'value' => isset($submitted) ? $old_data['drop_off_address'] : null
                                        )); ?>
        </div>
        <div class='col-md-3'>
                <?= $this->Form->input('drop off unit',
                                        array(
                                            'class' => 'form-control',
                                            'type'=>'text',
                                            'value' => isset($submitted) ? $old_data['drop_off_unit'] : null
                                        )); ?>
        </div>
    </div>
    <div class='row'>
        <div class='col-md-3 col-md-offset-4'>
            <?= $this->Form->input('drop off city',
                                    array(
                                        'class' => 'form-control',
                                        'value' => isset($submitted) ? $old_data['drop_off_city'] : null
                                    )); ?>
        </div>
        <div class= 'col-md-2'>
            <?= $this->Form->input('drop off zip',
                        array(
                            'class' => 'form-control',
                            'type'=> 'number',
                            'value' => isset($submitted) ? $old_data['drop_off_zip'] : null
                        )); ?>
        </div>
    </div>
    <div>
        <strong>Additional Travelers</strong>
        <div class='row'>
            <div class='col-md-12'>
                <?= $this->Form->checkbox('children', array('hiddenField' => false, 'id' => 'children')); ?>
                <?= __("I will be traveling with people under the age of 6") ?>
            </div>
        </div>
        <div class='row' style="margin-bottom: 20px">
            <div class='col-md-12'>
                <?= $this->Form->checkbox('physician', array('hiddenField' => false, 'id' => 'physician')); ?>
                <?= __("I will be traveling with a personal care attendant") ?>
            </div>
        </div>
    </div>
    <div>
        <strong>Comments</strong><br>
        <em><?= __("Will you be traveling with any guests? If so, please specify in the comments section below.") ?></em>
            <?= $this->Form->input('comments',
                        array(
                            'class'=> 'form-control',
                            'type' => 'textarea',
                            'rows'=>'6',
                            'label' => ['class' => 'hidden'],
                            'value' => isset($submitted) ? $old_data['comments'] : null
                        )); ?>
    </div>
    <hr>
    <?php echo $this->Form->button(__('Save Reservation'),
                            array(
                                'class' => 'btn btn-primary btn-lg'
                            ));
    echo $this->Form->end();
    ?>
<script type="text/javascript">
    $( document ).ready(function() {
        var tripDaySplit = $("#trip-day").val().split("/");
        $("#pick-up-day").val(tripDaySplit[2] + "-" + tripDaySplit[0] + "-" + tripDaySplit[1]);

        $( '#trip-day' ).datepicker({
            altFormat: "yy-mm-dd",
            altField: "#pick-up-day" 
        });
        var minDate = "<?php echo $request_date;?>";
        $( "#trip-day" ).datepicker('option','minDate', minDate); 

        $( '#pickup-address-list' ).change(function() {
            var pickupAddressSplit = $("#pickup-address-list").val().split(";");
            $('#pick-up-address').val(pickupAddressSplit[0]);
            $('#pick-up-unit').val(pickupAddressSplit[1]);
            $('#pick-up-city').val(pickupAddressSplit[2]);
            $('#pick-up-zip').val(pickupAddressSplit[3]);
        });

        $( '#dropoff-address-list' ).change(function() {
            var pickupAddressSplit = $("#dropoff-address-list").val().split(";");
            $('#drop-off-address').val(pickupAddressSplit[0]);
            $('#drop-off-unit').val(pickupAddressSplit[1]);
            $('#drop-off-city').val(pickupAddressSplit[2]);
            $('#drop-off-zip').val(pickupAddressSplit[3]);
        });
    });
</script>
