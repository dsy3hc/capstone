<?php if ( !isset( $summary )  ) { $summary = 'before'; } ?>
    <div class="pull-right <?php echo ( !empty($class) ? $class : '');?>">
        <?php if ( $summary == 'before' ) {?>
            <div class="pagination-summary before" style="text-align:right">
                <?php
                    echo $this->Paginator->counter(array(
                            'format' => __('{{current}} of {{count}} ' . ucfirst( $this->request->params['controller'] ) . '<br>Page {{page}} of {{pages}}')
                    ));
                ?>
            </div>         
        <?php } ?>
 
        <ul class="pagination">
            <li><?php echo $this->Paginator->first('«', array('escape'=>false), null, array('escape'=>false, 'class' => 'prev disabled')); ?></li>
            <li><?php echo $this->Paginator->prev('‹', array('escape'=>false), null, array('escape'=>false, 'class' => 'prev disabled')); ?></li>
            <?php echo $this->Paginator->numbers(array( 'separator' => '</li><li>', 'before'=>'<li>', 'after'=>'</li>')); ?>
            <li><?php echo $this->Paginator->next('›', array('escape'=>false), null, array('escape'=>false,'class' => 'next disabled')); ?></li>
            <li><?php echo $this->Paginator->last('»', array('escape'=>false), null, array('escape'=>false,'class' => 'next disabled')); ?></li>
        </ul>
    </div><!-- /.pagination -->