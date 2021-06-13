<?php
namespace rocket\humhub\modules\rocketadminstats\grid;

use Yii;
use yii\bootstrap\Html;
use yii\grid\DataColumn;

/**
 * DisplayNameColumn
 *
 * @author Luke
 */
class DisplayMessageColumn extends DataColumn
{
    const MAX_LENGTH_DEFAULT = 255;

    public $length = self::MAX_LENGTH_DEFAULT;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->attribute === null) {
            $this->attribute = 'message';
        }

        if ($this->label === null) {
            $this->label = Yii::t('RocketadminstatsModule.base', 'Message');
        }
    }

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $content = (string)$model->{$this->attribute};
        if (strlen($content) > $this->length) {
            return Html::encode(substr($content, 0, $this->length)) . '<b>...</b>';
        }

        return Html::encode($content);
    }
}
