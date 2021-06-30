<?php

use humhub\widgets\Button;
use yii\bootstrap\ActiveForm;

/* @var $model \humhub\modules\rocketmailforward\models\Config */
?>

<div class="panel panel-default">
    <div class="panel-heading"><?= Yii::t('RocketmailforwardModule.base', '<strong>Rocket Mail Forward</strong> module configuration'); ?></div>
    <div class="panel-body">
        <?php $form = ActiveForm::begin(['id' => 'configure-form']); ?>
        <?= $form->field($model, 'httpRequestTimeout')->textInput(['type' => 'number']); ?>
        <?= Button::save()->submit() ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>
