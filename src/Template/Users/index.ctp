<!-- File: src/Template/Users/index.ctp -->
<?php $this->Html->script('Users/index.js', array('block' => true)) ?>

<div class="row">
    <div class="col-md-2">
        <h1>Users</h1>
    </div>
    <div class="col-md-3 col-md-offset-4 user-header">
        <?= $this->Form->create($users, ['type' => 'get', 
                                        'class' => 'form-horizontal pull-right', 
                                        'id' => !isset($search) ? 'search-expand' : 'search',]); ?>
        <?= 
            $this->Form->input('search', [
                'templates' => [
                    'formGroup' => '<div class="form-group has-feedback">
                                        {{input}}
                                        <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
                                    </div>',
                    'error' => '<div class="error">{{content}}</div>'
                ],
                'class' => 'form-control',
                'label' => ['class' => 'hidden'],
                'placeholder' => 'Search',
                
                'value' => !isset($search) ? null : $search
            ]); 
        ?>
        <?= $this->Form->end(); ?>
    </div>

    <div class="col-md-1 text-center">
        <h3>
            <?= 
                $this->Html->link(
                    '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>', 
                    ['controller' => 'Users', 'action' => 'add'],
                    array(
                        'escape' => FALSE,
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'bottom',
                        'title' => 'Add'
                    )
                ) 
            ?>
        </h3>
    </div>
    <div class="col-md-2 user-header">
        <?php 
            foreach($filter_options as $key => $value) {
                $filter_options[$key] = ucfirst($value);
            }
        ?>
        <?= $this->Form->create(); ?>
            <?php
                $options = [
                    'options' => $filter_options,
                    'type' => 'select',
                    'class' => 'form-control has-feedback',
                    'empty' => 'No Filter',
                    'id' => 'filter-select',
                    'label' => false
                ];
                if (array_key_exists('filter', $query)) {
                    $options += ['default' => $query['filter']];
                }
                echo $this->Form->input('filter', $options);
            ?>
        <?= $this->Form->end(); ?>
    </div>
</div>

<?php if(isset($search) && !is_null($search)): ?>
    <div class="row">
        <div class="col-md-12">
            <h4>Search results for <strong><?php echo $search; ?></strong>:</h4>
        </div>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-12">
    <hr>
    </div>
</div>
<?php    
    $myTemplates = [
        'message' => '<div class="alert alert-warning">{{content}}</div>'
    ];
    $this->Form->templates($myTemplates);
?>
<?php 
    if(sizeof($users) > 0):?>

        <table class="table table-striped">
        <thead>
            <th>
                <?= $this->Paginator->sort('email', __('Email')) ?>
            </th>
            <th>
                <?= $this->Paginator->sort('clientID', __('Client ID')) ?>
            </th>
            <th>
                <?= $this->Paginator->sort('Roles.name', __('Role')) ?>
            </th>
            <th>
                <?= $this->Paginator->sort('expiration_date', __('CAT Expiration Date')) ?>
            </th>
            <th> <?= $this->Paginator->sort('activity', __('Activity')) ?> 
            </th>
            <th></th>
        </thead>
<?php endif; ?>

    <!-- Here is where we iterate through our $users query object, printing out user info -->


<?php 
if($pending!='true'):
// debug(sizeof($users)); exit();
    if(sizeof($users) > 0):
        foreach ($users as $user): 
            // debug($user); exit(); ?>
            <tr>
                <td><?= $user->email ?></td>
                <td><?= $user->clientID ?></td>
                <td><?= ucfirst($user->role->name) ?></td>
                <td><?= ($user->expiration_date == null) ? '' : $user->expiration_date->format('m/d/Y') ?></td>
                <td><?= $activities[$user->clientID] ?></td>
                <td class="text-center">
                    <?= 
                        $this->Html->link(
                            '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>', 
                            ['action' => 'edit', $user->id],
                            array('escape' => FALSE,
                                'data-toggle' => 'tooltip',
                                'data-placement' => 'bottom',
                                'title' => 'Edit'
                            )
                        ) 
                    ?>
                    &nbsp;
                    <?= 
                        $this->Form->postLink(
                            '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>',
                            ['action' => 'delete', $user->id],
                            array(
                                'escape' => FALSE,
                                'confirm' => 'Are you sure?',
                                'data-toggle' => 'tooltip',
                                'data-placement' => 'bottom',
                                'title' => 'Delete'
                            )                 
                        )
                    ?>
                </td>
            </tr>
        <?php endforeach;
    else:
        echo "<h4>Sorry, no users found.</h4>";
    endif;
endif;
?>

<?php 
    if($pending=='true'):
    	echo $this->Form->create(false,
                                array(
                                    'action' => 'saveAll'
                                ));

        $i=0;foreach ($users as $user): 
?>
            <tr>
                <td><?= $user->email ?>
                    <?php 
                        echo $this->Form->input('User.'.$i.'.id',
                                                array(
                                                    'label'=>false,
                                                    'value'=>$user->id,
                                                    'type'=>'hidden'
                                                ));
                    ?>
                </td>
                <td>
                    <?php 
                        echo $this->Form->input('User.'.$i.'.clientID',
                                                array(
                                                    'label' => false,
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Client ID'
                                                ));
                    ?>
                </td>
                <td>
                    <?= ucfirst($user->role->name) ?>
                </td>
                <td>
                    <?= $user->expiration_date ?>
                </td>
                <td class="text-center">
                    
<!--                         // $this->Html->link(
                        //     'Delete',
                        //     ['action' => 'delete', $user->id],
                        //     [
                        //         'class' => 'btn btn-info',
                        //         'confirm' => 'Are you sure?'
                        //     ]
                        // )

                    &nbsp;

                        // $this->Html->link(
                        //     'Edit', 
                        //     ['action' => 'edit', $user->id],
                        //     array(
                        //         'class' => 'btn btn-warning'
                        //     )
                        // )  -->

                </td> 
            </tr>

    <?php 
        $i++; endforeach;
        echo $this->Form->submit('Save All', array(
                                    'class' => 'btn btn-success btn-lg'
                                ));
        echo "<hr>";
        echo $this->Form->end('Save All');
        endif; 
    ?>
</table>

<?php echo $this->element('pagination'); ?>


<script type="text/javascript">
    $('[data-toggle="tooltip"]').tooltip();
</script>