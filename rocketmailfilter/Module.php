<?php

namespace humhub\modules\rocketmailfilter;

use Yii;
use yii\helpers\Url;

class Module extends \humhub\components\Module
{
    /**
    * @inheritdoc
    */
    public function getConfigUrl()
    {
        return "";
    }

    /**
    * @inheritdoc
    */
    public function disable()
    {
        parent::disable();
    }
}
