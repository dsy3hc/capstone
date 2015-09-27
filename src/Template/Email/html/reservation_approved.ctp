<div style="font-family: Verdana, Geneva, sans-serif; font-size: 16px; line-height: 1.42857; color: #333;">
    <header>
        <?= $this->Html->image('email_logo.png', array(
            'style' => 'max-height: 100px; width: auto; padding-bottom: 5px',
            'url' => $this->Url->build('/', true),
            'fullBase' => true
        )) ?>
    </header>
    <div style="border:solid 1px #EEE; border-width:1px 0 0 0; height: 1px;"></div>
    <p>
        <?= __("Dear {0} {1},", $reservation->first_name, $reservation->last_name) ?>
    </p>
    <p>
        <?= __("This is a notification that your requested trip for {0} has been approved.", $pick_up_day) ?>
    </p>
    <div>
        <strong>Outbound Trip</strong>
    </div>
    <table style="width: 100%; background-color: #EEE; padding: 15px; margin-bottom: 20px;">
        <tr style="font-size: 20px; margin-bottom: 10px; color: #222;">
            <td><?= $pick_up_time ?><td>
        </tr>
        <tr>
            <td style="width: 33%;">
                <div><?= $reservation->pick_up_address . $reservation->pick_up_unit ?></div>
                <div><?= $reservation->pick_up_city . " VA, " . $reservation->pick_up_zip ?></div>
            </td>
            <td style="width: 33%; text-align: center;">to</td>
            <td style="width: 33%;">
                <div style="text-align: right" align="right">
                    <div><?= $reservation->drop_off_address . $reservation->drop_off_unit ?></div>
                    <div><?= $reservation->drop_off_city . " VA, " . $reservation->drop_off_zip ?></div>
                </div>
            </td>
        </tr>
    </table>
    <div>
        <strong>Return Trip</strong>
    </div>
    <table style="width: 100%; background-color: #EEE; padding: 15px; margin-bottom: 20px;">
        <tr style="font-size: 20px; margin-bottom: 10px; color: #222;">
            <td><?= $return_time ?><td>
        </tr>
        <tr>
            <td style="width: 33%;">
                <div><?= $reservation->drop_off_address . $reservation->drop_off_unit ?></div>
                <div><?= $reservation->drop_off_city . " VA, " . $reservation->drop_off_zip ?></div>
            </td>
            <td style="width: 33%; text-align: center;">to</td>
            <td style="width: 33%;">
                <div style="text-align: right" align="right">
                    <div><?= $reservation->pick_up_address . $reservation->pick_up_unit ?></div>
                    <div><?= $reservation->pick_up_city . " VA, " . $reservation->pick_up_zip ?></div>
                </div>
            </td>
        </tr>
    </table>
    <p>
        <?= __("If this is not the exact time you requested, please keep in mind that our staff has scheduled this trip based upon your request and other factors including driver and vehicle availability.  If you no longer need this trip, please call JAUNT Reservations as soon as possible at (434) 296-3184.") ?>
    </p>
    <p>
        <?= __("Thank you for using {0}!", $app_name) ?>
    </p>
</div>