<?php
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use humhub\widgets\GridView;
use rocket\humhub\modules\rocketadminstats\models\MostLikedComment;
use rocket\humhub\modules\rocketadminstats\helpers\PeriodString;
use rocket\humhub\modules\rocketadminstats\widgets\DatesRange;
use rocket\humhub\modules\rocketadminstats\grid\DisplayMessageColumn;

/**
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $searchModel MostLikedComment
 */
?>
<div class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-body">
            <h4><?= Yii::t('RocketadminstatsModule.base', 'Most liked comments {period}', ['period' => PeriodString::fromModel($searchModel)]) ?></h4>
            <div class="help-block">
                <?= Yii::t('RocketadminstatsModule.base', 'Displays most liked comments for the given period of time (by default: for last 24h)'); ?>
                <br />
                <?= Yii::t('RocketadminstatsModule.base', 'If only <b>Start date</b> is set - shows the popular comments from the date chosen till now'); ?>
                <br />
                <?= Yii::t('RocketadminstatsModule.base', 'If only <b>End date</b> is set - shows the popular comments from the beginning till the date chosen'); ?>
                <br />
                <br />
            </div>
            <?php $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['/rocketadminstats/admin/comments']), 'options' => ['class' => 'form-inline']]); ?>
            <?= DatesRange::widget(['model' => $searchModel]) ?>
            <a href="<?= Url::to(['/rocketadminstats/admin/comments']) ?>" class="btn btn-default">Reset to 24h</a>
            <?php ActiveForm::end(); ?>
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'summary' => '',
                    'columns' => [
                        ['class' => DisplayMessageColumn::class, 'label' => 'Comment'],
                        [
                            'attribute' => 'likesCount',
                            'label' => Yii::t('RocketadminstatsModule.base', 'Likes'),
                            'options' => ['style' => 'width:140px;'],
                        ],
                        [
                            'class' => ActionColumn::class,
                            'buttons' => [
                                'view' => function($url, MostLikedComment $model) {
                                    return Html::a('View', Url::to(['/content/perma', 'id' => $model->content->id]), ['class' => 'btn btn-default btn-sm']);
                                },
                                'update' => function() { return false; },
                                'delete' => function() { return false; },
                            ]
                        ]
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
