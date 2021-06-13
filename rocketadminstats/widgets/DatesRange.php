<?php
namespace humhub\modules\rocketadminstats\widgets;

use humhub\components\Widget;
use humhub\modules\rocketadminstats\models\traits\DatesFilter;

class DatesRange extends Widget
{
    public $model;

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (!$this->model) {
            return '';
        }

        return $this->render('datesRange', [
            'model' => $this->model,
        ]);
    }
}
