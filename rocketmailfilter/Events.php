<?php
namespace  humhub\modules\rocketmailfilter;

use Yii;
use humhub\modules\mail\controllers\InboxController as BaseInboxController;
use humhub\modules\rocketmailfilter\widgets\FilterUnread;
use yii\base\Action;
use yii\base\ActionEvent;
use yii\base\ViewEvent;

class Events
{
    const ACTION_FILTER = 'index';
    const ROUTE_CUSTOM_FILTER = '/rocketmailfilter/inbox/index';

    public static function onInboxAction(ActionEvent $event)
    {
        if (static::isInboxFilterAction($event->action)) {
            $event->isValid = false;
            $event->result = Yii::$app->runAction(static::ROUTE_CUSTOM_FILTER);
            ob_start();
            echo $event->result;
        }
    }

    public static function isInboxFilterAction(Action $action)
    {
        return $action->id === static::ACTION_FILTER
            && BaseInboxController::class === get_class($action->controller)
            && Yii::$app->request->isPost;
    }

    public static function onInboxRender(ViewEvent $event)
    {
        if (strpos($event->viewFile, '_conversation_sidebar.php') > -1) {
            $event->output .= FilterUnread::widget();
        }
    }
}
