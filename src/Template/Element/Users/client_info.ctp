<?php $this->Html->script('Users/client_info.js', array('block' => true)) ?>
<div id="clientInfo">
    <div class="row">
        <div class="col-md-6">
            <?= $this->Form->input('clientID', [
                'class' => 'form-control',
                'type' => 'number'
            ]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <label>CAT Disability Number</label>
            <?php
            echo $this->Form->input('cat disability num', [
                    'type' => 'number',
                    'class' => 'form-control',
                    'label' => ['class' => 'hidden'],
                    'value' => $user->cat_disability_num
                ]
            );
            ?>
        </div>
        <div class="col-md-6">
            <label>CAT Expiration Date</label>
            <?php
            $display_date = $this->Time->format($user->expiration_date, 'MM/dd/yyyy');
            $sql_date = $this->Time->format($user->expiration_date, 'yyyy-MM-dd');
            echo $this->Form->input('date', [
                    'type' => 'text',
                    'title' => 'date',
                    'class' => 'form-control',
                    'label' => ['class' => 'hidden'],
                    'empty' => true
                ]
            );
            echo $this->Form->input('expiration date', [
                    'type' => 'text',
                    'class' => 'hidden',
                    'label' => ['class' => 'hidden'],
                    'empty' => true
                ]
            );
            ?>
        </div>
    </div>
</div>