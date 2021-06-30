<?php
namespace humhub\modules\rocketadminstats;

use Yii;
use yii\helpers\Url;

class Events
{
    /**
     * Defines what to do if admin menu is initialized.
     *
     * @param $event
     */
    public static function onAdminMenuInit($event)
    {
        $event->sender->addItem([
            'label' => '(Rocket) Activity stats',
            'url' => Url::to(['/rocketadminstats/admin']),
            'group' => 'manage',
            'icon' => '<i class="fa fa-rocket"></i>',
            'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'rocketadminstats' && Yii::$app->controller->id == 'admin'),
            'sortOrder' => 88800,
        ]);
    }
}
