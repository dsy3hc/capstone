    <div class="col-lg-6 col-md-12 col-sm-12">
        <div class="well">
            <div class="row">
                <div class="col-md-8 col-sm-8">
                    <div class="row at-a-glance" style="text-align: center;">
                        <?php if(isset($title)): ?>
                            <div class="col-md-1 col-sm-1 pull-left buttons">
                                <?php if($title == "Pending" || $title == "Denied"): ?>
                                    <?= 
                                        $this->Html->link(
                                            '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>',
                                            ['action' => 'approve',$reservation->bookingNum],
                                            array(
                                                'escape' => FALSE,
                                                'data-toggle' => 'tooltip',
                                                'data-placement' => 'bottom',
                                                'title' => 'Approve'
                                            )
                                        )
                                    ?>
                                    <br>
                                <?php 
                                endif;
                                if($title == "Pending"): ?>
                                <?=
                                $this->Html->link(
                                    '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>',
                                    ['action' => 'edit', $reservation->bookingNum],
                                    array(
                                        'escape' => FALSE,
                                        'data-toggle' => 'tooltip',
                                        'data-placement' => 'bottom',
                                        'title' => 'Edit'
                                    )
                                );
                                echo "<br>";
                                endif;

                                if($title == "Pending" || $title == "Approved"): ?>
                                    <?= 
                                        $this->Html->link(
                                            '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>', 
                                            ['action' => 'deny', $reservation->bookingNum],
                                            array(
                                                'escape' => FALSE,
                                                'data-toggle' => 'tooltip',
                                                'data-placement' => 'bottom',
                                                'title' => 'Deny'
                                            )
                                        ) 
                                    ?>
                                <?php endif; ?>
                            </div>
                        
                        <div class="col-md-10 col-sm-10 buttons">
                        <?php else: ?>
                        <div class="col-md-12 col-sm-12 buttons">
                        <?php endif; ?>
                            <h4 class="reservation-name"><?= $reservation->first_name . " " . $reservation->last_name ?></h4>
                            <p  style="margin-bottom: 0;">Pick Up:</p>
                            <?php
                                $address = $reservation->pick_up_address . " ";
                                if(!is_null($reservation->pick_up_unit) && !empty($reservation->pick_up_unit)) {
                                    $address .= "Unit " . $reservation->pick_up_unit . "<br>";
                                }
                                $address .= $reservation->pick_up_city. ", VA ". $reservation->pick_up_zip;
                            ?>
                            <?= $address ?>
                        </div>
                    </div>
                    <div class="row at-a-glance" style="text-align: center;">
                        <div class="col-md-12 col-sm-12">
                            <?php if(strlen($reservation->drop_off_address) > 0): ?> 
                                <div>
                                    <p class="address">Drop Off: </p>
                                    <?php
                                        $address = $reservation->drop_off_address . " ";
                                        if(!is_null($reservation->drop_off_unit) && !empty($reservation->drop_off_unit)) {
                                            $address .= "Unit " . $reservation->drop_off_unit . "<br>";
                                        }
                                        $address .= $reservation->drop_off_city. ", VA ". $reservation->drop_off_zip;
                                    ?>
                                    <?= $address ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 at-a-glance extra-info" style="border-left: 1px solid #000;">
                    <div>
                        <p>Trip Time:</p>
                        <?= date('n/j/Y',strtotime($reservation->pick_up_day)); ?>
                        <?= date('g:i A',strtotime($reservation->pick_up_time)); ?>
                        <hr>
                        <?php if(strlen($reservation->drop_off_address) > 0): ?> 
                            <p>Return:&nbsp;</p>
                            <?php if($reservation->return_time == null): ?>
				<?php if($reservation->one_way == 0): ?>
                                    Will Call
				<?php else : ?>
				    One Way
				<?php endif; ?>
                            <?php else: ?>
                                <?= date('g:i A',strtotime($reservation->return_time)); ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div id="toggleText<?= $count ?>" class="toggleUp">See More</div>
                    <div id="hiddenText<?= $count ?>" style="display: none;" class="at-a-glance more-res-info">
                        <div>
                            <p style="display: inline;">Client ID: </p>
                            <?= $reservation->clientID ?>
                            <br>
                        
                            <?php if(is_null($reservation->comments) && is_null($reservation->comments) && is_null($reservation->comments)): ?>
                                No other information to display!
                            <?php else: ?>
                                <?php if(!is_null($reservation->comments) && !empty($reservation->comments)): ?>
                                    <p>Additional Comments: </p>
                                    <?= $reservation->comments ?>
                                    <br>
                                <?php endif; if(!is_null($reservation->physicians) && $reservation->physicians): ?>
                                    <p>Personal Care Attendant: </p>
                                    Yes
                                    <br>
                                <?php endif; if(!is_null($reservation->children) && $reservation->children): ?>
                                    <p>Children Under 6: </p>
                                    Yes
                                    <br>
                                <?php endif; ?>
                            <?php endif; ?>
                            <br>
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <?php $buttonText = $user_role == 2 ? "Print PDF" : "Generate PDF"; ?>
                                    <?= $this->Html->link(__($buttonText), ['controller' => 'Reservations', 'action' => 'report', '_ext' => 'pdf', $reservation->bookingNum], ['class' => 'btn btn-primary']); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
$(document).ready(function() {
    $('#toggleText<?= $count ?>').click(function() {
        $('#hiddenText<?= $count ?>').slideToggle();
        if($('#toggleText<?= $count ?>').html() == "See More") {
            $('#toggleText<?= $count ?>').html("See Less");
        } else {
            $('#toggleText<?= $count ?>').html("See More");
        }
    });
});
</script>
