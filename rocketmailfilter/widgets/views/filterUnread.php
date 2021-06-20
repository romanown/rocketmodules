<?php
use humhub\libs\Html;
use humhub\modules\ui\filter\widgets\CheckboxFilterInput;
use humhub\modules\ui\form\widgets\ActiveForm;
use humhub\modules\rocketmailfilter\assets\Assets;

Assets::register($this);

/* @var $options array */
?>

<?= Html::beginTag('div', $options) ?>
    <div class="form-check">
        <label class="form-check-label rocketmailfilter-check-label col-form-label-sm">
            <?= Html::checkbox('unread', false, [
                'id' => 'rocketmailfilter-filter-checkbox',
                'data-filter-id' => 'unread',
                'data-filter-type' => 'text',
                'data-filter-term' => 'unread',
                'data-filter-category' => 'unread',
            ]) ?>
            <?= Yii::t('RocketmailfilterModule.base', 'Show unread only') ?>
        </label>
    </div>
<?= Html::endTag('div') ?>
