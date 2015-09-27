<!-- File: src/Template/Reservations/index.ctp -->
<?php
    use Cake\Routing\Router;
?>
<div class="row">
    <div class="col-md-6">
        <h1>Reservations</h1>
    </div>
    <div class="col-md-3 col-md-offset-3" style="text-align: right">
        <h2>
            <?= 
                $this->Html->link(
                    'Add Reservation',
                    ['controller' => 'Reservations', 'action' => 'reserve'],
                    array(
                        'class' => 'btn btn-success'
                    )
                ) 
            ?>
        </h2>
    </div>
</div>
<hr>

<div>
    <ul class="nav nav-tabs" id="myTab">
        <li id="pendingLi" <?php if ($title == "Pending") { echo ' class="active"'; } ?>><a onclick="tabClicked('pending');" id="pending">Pending</a></li>
        <li id="approvedLi" <?php if ($title == "Approved") { echo ' class="active"'; } ?>><a onclick="tabClicked('approved');">Approved</a></li>
        <li id="deniedLi" <?php if ($title == "Denied") { echo ' class="active"'; } ?>><a onclick="tabClicked('denied');">Denied</a></li>
    </ul>

    <div class="tab-content" id="tabs">
        <?php echo $this->element('reservation_tab'); ?>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
    function tabClicked(title) {
        window.location = "<?php echo Router::Url(array('controller' => 'Reservations', 'action' => '" + title + "')); ?>";
    }
</script>
<script type="text/javascript">
    $('[data-toggle="tooltip"]').tooltip();
</script>

