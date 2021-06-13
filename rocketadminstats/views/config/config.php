<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var $model \rocket\humhub\modules\rocketadminstats\models\AdminStatsSettings
 */
?>
<div class="panel panel-default">
    <div class="panel-heading"><?php echo Yii::t('RocketadminstatsModule.base', 'Rocket Admin Stats Configuration'); ?></div>
    <div class="panel-body">
        <?php $form = ActiveForm::begin(); ?>
        <?php echo $form->errorSummary($model); ?>
        <?= $form->field($model, 'usersPerPage')->input('number', ['min' => 1, 'max' => 50]); ?>
        <?= $form->field($model, 'commentsPerPage')->input('number', ['min' => 1, 'max' => 50]); ?>
        <?= $form->field($model, 'commentsPerPage')->input('number', ['min' => 1, 'max' => 50]); ?>
        <hr>
        <?php echo Html::submitButton(Yii::t('RocketadminstatsModule.base', 'Save'), array('class' => 'btn btn-primary')); ?>
        <a class="btn btn-default" href="<?php echo Url::to(['/admin/module']); ?>"><?php echo Yii::t('RocketadminstatsModule.base', 'Back to modules'); ?></a>
        <?php $form::end(); ?>
    </div>
</div>
