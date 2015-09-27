<?php $this->Html->script('Timeoff/calendar_modal_approve', array('block' => true)); ?>
<div id="modal" class="popup">
    <span id="modal-close" class="glyphicon glyphicon-remove modal-close" aria-hidden="true"></span>
    <div class="row">
        <div class="col-md-12">
            <h2 id="modal-name"></h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="request-label"><?= __("Start") ?></div>
            <p id="modal-start"></p>
        </div>
        <div class="col-md-6">
            <div class="request-label"><?= __("End") ?></div>
            <p id="modal-end"></p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="request-label"><?= __("Comments") ?></div>
            <p id="modal-comments"></p>
        </div>
    </div>
    <hr>
    <div id="modal-buttons">
        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <?= $this->Html->link(
                    __('View'),
                    ['controller' => 'Timeoff', 'action' => 'view'],
                    ['class' => 'btn btn-primary', 'id' => 'modal-view-request']
                ) ?>
            </div>
        </div>
        <div id="modal-approve-row" class="row">
            <div class="col-md-offset-1 col-md-10">
                <?php
                $text = __("Approve");
                echo $this->Form->postLink(
                    "<div id='modal-approve' class='btn btn-success'>$text</div>",
                    ['controller' => 'Timeoff', 'action' => 'approve'],
                    ['escape' => false]
                )
                ?>
            </div>
        </div>
    </div>
</div>