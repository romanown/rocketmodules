<?php
use humhub\modules\user\grid\DisplayNameColumn;
use humhub\modules\user\grid\ImageColumn;
use humhub\widgets\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use humhub\modules\rocketadminstats\widgets\DatesRange;
use humhub\modules\rocketadminstats\helpers\PeriodString;

/**
 * @var $searchModel \humhub\modules\rocketadminstats\models\TopActiveUser
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
?>
<div class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="alert alert-info">
                <?= Yii::t('RocketadminstatsModule.base', 'Total users enabled: <b>{count}</b>', ['count' => $searchModel->countEnabledUsers()]) ?>
                <br />
                <?= Yii::t('RocketadminstatsModule.base', 'Number of users with any activity (comment, post, like, etc.): <b>{count}</b> (filtered)', ['count' => $searchModel->countUsersWithActivity()]) ?>
            </div>
            <h4><?= Yii::t('RocketadminstatsModule.base', 'Top talkers {period}', ['period' => PeriodString::fromModel($searchModel)]) ?></h4>
            <div class="help-block">
                <?= Yii::t('RocketadminstatsModule.base', 'The table below contains a list of most talking users for the specified period of time (by default: for last 24h)'); ?>
                <br />
                <?= Yii::t('RocketadminstatsModule.base', 'If only <b>Start date</b> is set - shows the top talking users from the date chosen till now'); ?>
                <br />
                <?= Yii::t('RocketadminstatsModule.base', 'If only <b>End date</b> is set - shows the top talking users from the beginning till the date chosen'); ?>
                <br />
                <br />
            </div>
            <?php $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['/rocketadminstats/admin/users']), 'options' => ['class' => 'form-inline']]); ?>
            <?= DatesRange::widget(['model' => $searchModel]) ?>
            <a href="<?= Url::to(['/rocketadminstats/admin/users']) ?>" class="btn btn-default">Reset to 24h</a>
            <?php ActiveForm::end(); ?>
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'summary' => '',
                    'columns' => [
                        ['class' => ImageColumn::class],
                        ['class' => DisplayNameColumn::class],
                        [
                            'attribute' => 'commentsCount',
                            'label' => Yii::t('RocketadminstatsModule.base', 'Comments count'),
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
