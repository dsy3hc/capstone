<div id="denied" class="tab-pane fade in active">
    <?php if(isset($title)): ?>
        <h3> <? echo $title; ?> Reservations </h3>
        <div class="row">
            <div class="col-md-6">
                <span><strong> Reservations Per Page: </strong></span>
            </div>
            <div class="col-md-6">
                <span><strong> Sort By: </strong></span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">    
                <?php
                    $page_params = $this->Paginator->params();
                    $limit = $page_params['limit'];
                    $options = array( 5 => '5', 10 => '10', 15 => '15', 20 => '20', 25 => '25', 30 => '30' );

                    echo $this->Form->create(null, array('type'=>'get'));
                    echo $this->Form->hidden('order', ['value' => $order]);
                    echo $this->Form->select('limit', $options, array(
                        'value'=>$limit, 
                        'default'=>15, 
                        'empty' => FALSE, 
                        'onChange'=>'this.form.submit();', 
                        'name'=>'limit',
                        'class' => 'form-control'
                        )
                    );
                    echo $this->Form->end();
                ?>  
            </div>
            <div class="col-md-4 col-md-offset-4">    
                <?php
                    $options = array(   'Reservations.last_name-asc' => 'Last Name: A to Z', 
                                        'Reservations.last_name-desc' => 'Last Name: Z to A', 
                                        'Reservations.created_time-asc' => 'Requested Date: Asc', 
                                        'Reservations.created_time-desc' => 'Requested Date: Desc', 
                                        'Reservations.pick_up_day-asc' => 'Trip Date: Asc', 
                                        'Reservations.pick_up_day-desc' => 'Trip Date: Desc');

                    echo $this->Form->create(null, array('type'=>'get'));
                    echo $this->Form->hidden('limit', ['value' => ($limit < 5) ? 15 : $limit]);
                    echo $this->Form->select('order', $options, array(
                        'value'=>$order, 
                        'default'=> 'Reservations.last_name-desc', 
                        'empty' => FALSE, 
                        'onChange'=>'this.form.submit();', 
                        'name'=>'order',
                        'class' => 'form-control'
                        )
                    );
                    echo $this->Form->end();
                ?>  
            </div>
        </div>
        <hr>
    <?php endif; ?>
    <div class="row">
    <?php 
    $count = 0;
    foreach ($reservations as $reservation): ?>
        <?= $this->element('reservation', [
            "reservation" => $reservation,
            "count" => $count
        ]); ?>
        <?php $count++; ?>
        <?php if($count % 2 == 0): ?>
            </div>
            <div class="row">
        <?php endif; ?>
    <?php endforeach; ?>
    </div>
    <?php echo $this->element('pagination'); ?>
</div>