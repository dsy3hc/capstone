<!-- File: src/Template/Users/edit.ctp -->
<?= $this->Form->create() ?>
<table class="table table-striped">

    <thead>
    <tr>
        <th>Setting</th>
        <th>Value</th>
    </tr>
    </thead>
    <?php foreach ($settings as $name => $setting): ?>
        <?php
        $error = '';
        if (array_key_exists('error', $setting)) {
            $error = 'danger';
        }
        ?>
    <tr class="<?= $error ?>">
        <td>
            <?= $setting['description'] ?>
        </td>
        <td class="<?= $error ?>">
        <?= $this->Form->select($name, $setting['options'], [
            'val' => $setting['value'],
            'class' => "form-control $error",
            'style' => 'margin-bottom: 0'
        ]) ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<div style="float:right">
    <?= $this->Form->button(__('Save'), ['class' => 'btn btn-success btn-lg']); ?>
</div>
<?= $this->Form->end() ?>