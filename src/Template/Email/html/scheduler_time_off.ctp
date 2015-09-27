<p>
    <?= $first_name ?> <?= $last_name ?> has submitted a new Time Off request.
</p>
<p>
    Click <?= $this->Html->link('here', [
        '_full' => true,
        'controller' => 'timeoff',
        'action' => 'view',
        $request->id
    ]) ?> to view the request.

</p>