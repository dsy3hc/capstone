<div class="row">
    <div class="col-md-3 col-sm-6">
        <?= $this->Html->link(__('Generate PDF'), ['action' => 'report', '_ext' => 'pdf', $request->id], ['class' => 'btn btn-primary']); ?>
    </div>
</div>
<hr>
<div class="request-label"><?= __("Employee") ?></div>
<p><?= $request->full_name ?></p>

<div class="request-label"><?= __("Request Type") ?></div>
<p><?= ucfirst($request->request_type) ?></p>

<div class="request-label"><?= __("Status") ?></div>
<p><?= $request->text_status ?></p>

<div class="request-label"><?= __("Comments") ?></div>
<p><?= $request->comments ?></p>

<hr>
<table class="table table-striped">
    <thead>
    <th>Preference</th>
    <th>Start</th>
    <th>End</th>
    <?php if($approve): ?>
    <th>Action</th>
    <?php endif; ?>
    </thead>
    <tbody>
    <?php $options = $request->getOptions(); ?>
    <?php foreach ($options as $option): ?>
        <?php if($request->time_selected == $option['id']): ?>
            <tr class="info">
        <?php else: ?>
            <tr>
        <?php endif; ?>
            <td><?= $option['id'] ?></td>
            <td><?= date('m/d/y g:i a', strtotime($option['start'])); ?></td>
            <td><?= date('m/d/y g:i a', strtotime($option['end'])); ?></td>
            <?php if($approve): ?>
                <td>
                    <?php
                    if($request->time_selected == $option['id']) {
                        echo $this->Form->postLink(
                            '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>',
                            ['action' => 'unapprove', $request->id],
                            [
                                'escape' => FALSE,
                                'data-toggle' => 'tooltip',
                                'data-placement' => 'bottom',
                                'title' => __('Undo Approval')
                            ]
                        );
                    }
                    else {
                        echo $this->Form->postLink(
                            '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>',
                            ['action' => 'approve', $request->id, $option['id']],
                            [
                                'escape' => FALSE,
                                'data-toggle' => 'tooltip',
                                'data-placement' => 'bottom',
                                'title' => __('Approve Option')
                            ]
                        );
                    }
                    ?>
                </td>
            <?php endif; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php
if($approve && $request->status != 2) {
    $btn_text = __("Deny Request");
    echo $this->Form->postLink(
        "<div class='btn btn-danger'>$btn_text</div>",
        ['action' => 'deny', $request->id],
        [
            'escape' => FALSE,
            'data-toggle' => 'tooltip',
            'data-placement' => 'bottom',
            'title' => __('Deny Request')
        ]
    );
}
?>
<hr>
<script>
function removeDuplicates(event) {
    var calendar = $('#calendar');
    var events = calendar.fullCalendar('clientEvents');

    for (var i = 0; i < events.length; i++) {
        var event2 = events[i];
        if (event.id == event2.id &&
            event.option == event2.option &&
            event.viewing != event2.viewing &&
            event.viewing == false) {
            return true;
        }
    }
}

var loading = function(isLoading, view) {
    <?php echo "var requestID = " . $request->id . ";\n" ?>
    var calendar = $('#calendar');
    calendar.fullCalendar('removeEvents', removeDuplicates);
    if (!isLoading && newLoad) {
        var events = calendar.fullCalendar('clientEvents');

        for (var i = 0; i < events.length; i++) {
            var event = events[i];
            if (event.id == requestID && event.option == 1) {
                calendar.fullCalendar('gotoDate', event.start);
                break;
            }
        }
        // prevent this function from running every time the calendar
        // tries to load new data (happens when changing months)
        newLoad = false;
    }
}
</script>
<?= $this->element('Calendar/base', [
    'sources' => ['approved' => 'all', 'view' => $request->id],
    'approve' => $approve,
    'modal' => 'Calendar/modal_approve'
]) ?>