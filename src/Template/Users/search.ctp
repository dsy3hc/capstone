<!-- File: src/Template/Users/index.ctp -->
<div class="row">
    <div class="col-md-6">
        <h1>Users</h1>
    </div>

    <div class="col-md-2 col-md-offset-4">
        <h2>
            <?= 
                $this->Html->link(
                    'Add User',
                    ['controller' => 'Users', 'action' => 'add'],
                    array(
                        'class' => 'btn btn-success'
                    )
                ) 
            ?>
        </h2>
    </div>
</div>
<hr>

<?php 
    echo $this->Form->create("User",array('action' => 'search'));
    echo $this->Form->input("q", array('label' => 'Search for'));
    echo $this->Form->end("Search");
?> 
<table class="table table-striped">
    <thead>
        <th>Email</th>
        <th>ClientID</th>
        <th>Role</th>
        <th>Email Confirm Date</th>
        <th></th>
    </thead>

    <!-- Here is where we iterate through our $users query object, printing out user info -->

<?php foreach ($users as $user): ?>
    <tr>
        <td><?= $user->email ?></td>
        <td><?= $user->clientID ?></td>
        <td><?= ucfirst($user_role) ?></td>
        <td><?= $user->email_confirm_date ?></td>
        <td class="text-center">
            <?= 
                $this->Form->postLink(
                    'Delete',
                    ['action' => 'delete', $user->id],
                    array(
                        'class' => 'btn btn-info'
                    ),
                    ['confirm' => 'Are you sure?']
                )
            ?>
            &nbsp;
            <?= 
                $this->Html->link(
                    'Edit', 
                    ['action' => 'edit', $user->id],
                    array(
                        'class' => 'btn btn-warning'
                    )
                ) 
            ?>
        </td>
    </tr>
<?php endforeach; ?>
</table>

