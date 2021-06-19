<?php
namespace humhub\modules\rocketmailfilter\widgets;

use humhub\libs\Html;
use humhub\widgets\JsWidget;

class FilterUnread extends JsWidget
{
    public $init = true;

    public $jsWidget = 'rocketmailfilter.unread';

    public $id = 'rocketmailfilter-root';

    public function run()
    {
        return $this->render('filterUnread', [
            'options' => $this->getOptions()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getAttributes()
    {
        $attributes = parent::getAttributes();
        Html::addCssClass($attributes, [
            'rocketmailfilter-unread-form',
            'hidden',
        ]);

        return $attributes;
    }
}
