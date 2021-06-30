<?php
namespace humhub\modules\rocketmailforward;

use humhub\modules\rocketmailforward\helpers\HttpMailForwarder;
use Yii;
use yii\helpers\Url;

class Module extends \humhub\components\Module
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        Yii::$container->setSingleton(HttpMailForwarder::class);
    }

    /**
    * @inheritdoc
    */
    public function getConfigUrl()
    {
        return Url::to(['/rocketmailforward/config']);
    }

    /**
    * @inheritdoc
    */
    public function disable()
    {
        parent::disable();
    }
}
