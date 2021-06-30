<?php

use humhub\libs\Html;
use yii\grid\ActionColumn;

/* @var $models \humhub\modules\rocketmailforward\models\MailForward[] */
/* @var $dataProvider \yii\data\ActiveDataProvider */
?>

<div class="alert alert-info">
    <?= Yii::t('RocketmailforwardModule.base', 'Incoming messages of the following users are being forwarded.'); ?>
</div>
<?= Html::a('+ Create rule', ['create'], ['class' => 'btn btn-success mb-0']) ?>
<div class="table-responsive">
    <?= \humhub\widgets\GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => '',
        'columns' => [
            ['class' => \humhub\modules\user\grid\ImageColumn::class, 'userAttribute' => 'user'],
            ['class' => \humhub\modules\user\grid\DisplayNameColumn::class, 'userAttribute' => 'user'],
            [
                'attribute' => 'endpoint',
                'label' => Yii::t('RocketmailforwardModule.base', 'Endpoint'),
            ],
            [
                'class' => ActionColumn::class,
                'buttons' => [
                    'view' => function() { return false; },
                ]
            ]
        ],
    ]); ?>
</div>

<?= Html::style(<<<CSS
.mb-0 {
    margin-bottom: 0;
}
CSS
); ?>
