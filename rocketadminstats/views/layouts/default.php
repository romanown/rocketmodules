<?php
use \rocket\humhub\modules\rocketadminstats\widgets\StatsTabMenu;
/**
 * @var $content stirng
 */
?>
<?php $this->beginContent('@admin/views/layouts/main.php') ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <?= Yii::t('RocketadminstatsModule.base', '(Rocket) <strong>Activity stats</strong> '); ?>
        </div>
        <?= StatsTabMenu::widget(); ?>
        <?= $content; ?>
    </div>
<?php $this->endContent(); ?>
