<?php

use humhub\modules\rocketmailforward\helpers\Url;
use humhub\modules\user\widgets\UserPickerField;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model humhub\modules\rocketmailforward\models\MailForward */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mail-forward-form">
    <?php $form = ActiveForm::begin(); ?>
        <?= Html::hiddenInput('prev_user_id', $model->user_id); ?>
        <?= $form->field($model, 'user_id')->widget(UserPickerField::class,
            [
                'id' => sprintf('mailforward-user-id-%s', $model->user_id),
                'url' => Url::toSearchUniqueUser(),
                'itemKey' => 'id',
                'placeholder' => Yii::t('RocketmailforwardModule.base', 'Choose user'),
                'maxSelection' => 1,
            ]
        ) ?>
        <?= $form->field($model, 'endpoint')->textInput(['maxlength' => true]) ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-default']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
