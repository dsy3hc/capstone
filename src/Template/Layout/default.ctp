<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Controller\Component\AuthComponent;

$cakeDescription = 'JAUNT';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>
    <?= $this->Html->css('reservation_wells') ?>
    <?= $this->Html->css('bootstrap') ?>
    <?= $this->Html->css('sidebar') ?>
    <?= $this->Html->css('collapse') ?>
    <?= $this->Html->css('jasny-bootstrap') ?>

    <?=
        $this->Html->css(
            'http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css',
            array(
                'inline' => false
            )
        );
    ?>
    <?= $this->Html->script('jquery-1.11.1.min') ?>
    <?= $this->Html->script('jasny-bootstrap') ?>
    <?= $this->Html->script('http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js') ?>
    <?= $this->Html->script('http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js',
            array(
                'inline' => false
            )
        ) 
    ?>
    <?= $this->Html->script('bootstrap.min') ?>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <div id="container">
        <div id="content">
            <div class="container">
                <!-- Top navbar -->
                <header>
                    <div class="row">
                        <div class="col-md-3 col-sm-3" style="height: 75px">
                            <?php
                                echo $this->Html->image('logo.jpg', array(
                                        'style' => 'height: 100%',
                                        'alt' => 'JAUNT Logo',
                                        'url' => '/'
                                    ));
                            ?>
                        </div>

                        <div class="col-md-2 col-sm-2 col-md-offset-7 col-sm-offset-7">
                            <h1>
                                <div class="hidden-md hidden-lg">
                                    <button type="button" class="navbar-toggle" data-toggle="offcanvas" data-target=".navmenu">
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                    </button>
                                </div>
                                <?= 
                                    $this->Html->link(
                                        __('Logout'),
                                        ['controller' => 'Users', 'action' => 'logout'],
                                        array (
                                            'class' => 'btn btn-primary'
                                        )
                                    ) 
                                ?>
                            </h1>
                        </div>
                    </div>
                </header>
                <hr>
                <div class="row">
                    <div class="col-md-3 col-sm-3">
                        <ul class="nav nav-pills nav-stacked navmenu navmenu-default navmenu-fixed-left offcanvas-xs">
                            <?= $this->Element('sidebar'); ?>
                        </ul>
                    </div>
                    <div class="col-md-9 col-sm-9"  >
                        <!-- Flash -->
                        <div class="row">
                            <div class="col-sm-12">
                                <?= $this->Flash->render() ?>
                            </div>
                        </div>
                        <!-- Content -->
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                    $myTemplates = [
                                        'message' => '<div class="alert alert-warning">{{content}}</div>'
                                    ];
                                    $this->Form->templates($myTemplates);
                                ?>
                                <?= $this->fetch('content') ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="navbar-bottom">
                    <div class="footer">
                        <hr>
                        &copy; SLP Jaunt 2014
                        <br><br>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Menu Toggle Script -->
    <script>
        $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });
    </script>
</body>
</html>
