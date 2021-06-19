<?php
use humhub\libs\Html;
use humhub\modules\ui\filter\widgets\CheckboxFilterInput;
use humhub\modules\ui\form\widgets\ActiveForm;
use humhub\modules\rocketmailfilter\assets\Assets;

Assets::register($this);

/* @var $options array */
?>

<?= Html::beginTag('div', $options) ?>
    <?php $filterForm = ActiveForm::begin() ?>
    <?= CheckboxFilterInput::widget([
        'id' => 'unread-toggle',
        'category' => 'unread',
        'title' => Yii::t('RocketmailfilterModule.base', 'Unread'),
        'options' => [
            'name' => 'unread-toggle',
        ],
    ]) ?>
    <?= Html::hiddenInput('unread', '0', [
        'data-filter-id' => 'unread',
        'data-filter-category' => 'unread',
        'data-filter-type' => 'text',
    ]) ?>
    <?php ActiveForm::end() ?>
    <div class="form-check hidden">
        <label class="form-check-label rocketmailfilter-check-label col-form-label-sm">
            <?= Html::checkbox('unread-cosmetic', false, [
                'id' => 'rocketmailfilter-filter-checkbox',
            ]) ?>
            <?= Yii::t('RocketmailfilterModule.base', 'Show unread only') ?>
        </label>
    </div>
<?= Html::endTag('div') ?>
