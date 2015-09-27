<!-- File: src/Template/Timeoff/index.ctp -->
<div class="row">
    <div class="col-md-6">
        <h1>Time Off Requests</h1>
    </div>
    <div class="col-md-3 col-md-offset-3" style="text-align: right">
        <h2>
            <?= 
                $this->Html->link(
                    'Add Request',
                    ['controller' => 'Timeoff', 'action' => 'request'],
                    array(
                        'class' => 'btn btn-success'
                    )
                ) 
            ?>
        </h2>
    </div>
</div>
<hr>

<?php if ($user_role == 1 || $user_role == 5): ?>
    <h3> Pending Requests </h3>
    <?php if ($requests_pending->count() > 0): ?>
    <table class="table table-striped">
        <thead>
            <th>Name</th>
            <th>Date Submitted</th>
            <th>Comments</th>
            <th>Action</th>
        </thead>

        <!-- Here is where we iterate through our $reservations query object, printing out reservation info -->
    <?php foreach ($requests_pending as $request): ?>
        <tr>
            <td><?= $request->full_name ?></td>
            <td><?= $this->Time->format($request->created, 'MMMM dd, yyyy') ?></td>
            <td><?= $this->Text->truncate($request->comments, 32, ['ellipses' => '...', 'exact' => false]) ?></td>
            <td>
                <?=
                $this->Html->link(
                    '<span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>',
                    ['action' => 'view', $request->id],
                    [
                        'escape' => FALSE,
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'bottom',
                        'title' => 'View Request Details'
                    ]
                )
                ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>
    <?php else: ?>
        There are no pending requests
    <?php endif; ?>
    <div class="row">
        <div class="col-md-12">
            <?php echo $this->element('pagination'); ?>
        </div>
    </div>
    <hr>
<?php endif; ?>
<?= $this->element('Calendar/base', [
    'sources' => ['approved' => 'all'],
    'modal' => 'Calendar/modal_approve'
]) ?>
