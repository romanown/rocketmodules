<?php
/**
 * @var $content stirng
 */
?>
<?php $this->beginContent('@admin/views/layouts/main.php') ?>
<div class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-heading">
            <?= Yii::t('RocketmailforwardModule.base', '(Rocket) <strong>Mail forward</strong>'); ?>
        </div>
        <div class="panel-body">
            <?= $content; ?>
        </div>
    </div>
</div>
<?php $this->endContent(); ?>
