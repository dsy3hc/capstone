<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
    <title><?= $this->fetch('title') ?></title>
</head>
<body style="font-family: Verdana, Geneva, sans-serif; font-size: 16px; line-height: 1.42857; color: #333;">
    <header>
        <?= $this->Html->image('email_logo.png', array(
            'style' => 'max-height: 100px; width: auto; padding-bottom: 5px',
            'url' => $this->Url->build('/', true),
            'fullBase' => true
        )) ?>
        <div style="border:solid 1px #EEE; border-width:1px 0 0 0; height: 1px;"></div>
    </header>
    <?= $this->fetch('content') ?>
</body>
</html>