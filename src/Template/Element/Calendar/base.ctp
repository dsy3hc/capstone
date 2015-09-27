<?php
if (!isset($approve)) {
    $approve = false;
}

$event_sources = [];

if (isset($sources)) {
    if (array_key_exists('approved', $sources)) {
        if ($sources['approved'] == 'all') {
            $approved = $this->Url->build([
                'controller' => 'Timeoff',
                'action' => 'api',
                'approved',
                '_full' => true,
            ]);
            array_push($event_sources, ["url" => $approved]);
        }
    }

    // return the approved requests for the currently logged in user
    if (in_array('approved', $sources)) {
        $approved = $this->Url->build([
            'controller' => 'Timeoff',
            'action' => 'api',
            'approved',
            '_full' => true,
        ]);
        array_push($event_sources, ["url" => $approved]);
    }

    if (array_key_exists('view', $sources)) {
        if (is_int($sources['view'])) {
            $view = $this->Url->build([
                'controller' => 'Timeoff',
                'action' => 'api',
                'view',
                $sources['view'],
                '_full' => true,
            ]);
            array_push($event_sources, [
                "url" => $view,
                "color" => "#F0AD4E"
            ]);
        }
    }

    if (in_array('pending', $sources)) {
        $pending = $this->Url->build([
            'controller' => 'Timeoff',
            'action' => 'api',
            'pending',
            '_full' => true,
        ]);
        array_push($event_sources, [
            "url" => $pending,
            "color" => "#F0AD4E"
        ]);
    }
}
?>
<?php
$this->Html->script('moment.min', array('block' => true));
$this->Html->script('fullcalendar.min', array('block' => true));
$this->Html->css('fullcalendar.min', array('block' => true));
$this->Html->css('Timeoff/calendar', array('block' => true));
?>
<script>
    var newLoad = true;
</script>
<div class="row">
    <div class="col-md-12">
        <div class="calendar-container">
            <div id='calendar'></div>
            <?php
            if (isset($modal)) {
                echo $this->element($modal);
            }
            ?>
        </div>
    </div>
</div>
<div id="calendar-data"
     data-calendar-event-sources="<?= htmlspecialchars(json_encode($event_sources)) ?>"
     data-calendar-display-approve="<?= $approve ?>">
</div>
<?php $this->Html->script('Timeoff/calendar_base', array('block' => true)); ?>
