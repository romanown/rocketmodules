<?php
use humhub\modules\ui\form\widgets\DatePicker;

/**
 * @var \yii\base\Model $model
 */
?>
<label>Start date:
    <?= DatePicker::widget(['model' => $model, 'attribute' => 'startDate', 'dateFormat' => 'yyyy-MM-dd']) ?>
</label>
<label>End date:
        <?= DatePicker::widget(['model' => $model, 'attribute' => 'endDate', 'dateFormat' => 'yyyy-MM-dd']) ?>
</label>
<div class="input-group">
<span class="input-group-btn">
    <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> Filter</button>
</span>
</div>
