<?php
namespace  humhub\modules\rocketfocuscomment;

use humhub\libs\WidgetCreateEvent;
use humhub\modules\comment\models\Comment;
use humhub\modules\notification\models\Notification;
use humhub\modules\rocketfocuscomment\assets\FocusCommentAssets;
use humhub\modules\rocketfocuscomment\helpers\Url;
use humhub\modules\rocketfocuscomment\widgets\Comments;
use humhub\components\Response;
use Yii;
use yii\base\Action;
use yii\base\ActionEvent;

class Events
{
    const HEADER_LOCATION = 'Location';
    const HEADER_PJAX_REDIRECT = 'X-PJAX-REDIRECT-URL';
    const ROUTE_CUSTOM_SHOW_COMMENT = '/rocketfocuscomment/comment/show';

    public static function onViewRenderBegin()
    {
        Yii::$app->getView()->registerJsConfig('rocketfocuscomment.main', [
            'commentIdParam' => static::urlParam(),
        ]);
    }

    public static function onViewRenderEnd()
    {
        Yii::$app->getView()->registerAssetBundle(FocusCommentAssets::class);
    }

    public static function onSpaceIndexAction(ActionEvent $event)
    {
        Yii::$app->getView()->registerAssetBundle(FocusCommentAssets::class);
    }

    public static function onCommentsWidgetCreate(WidgetCreateEvent $event)
    {
        $event->config['class'] = Comments::class;
    }

    public static function onNotificationEntryRedirect(ActionEvent $event)
    {
        if ($event->action->id !== 'index') {
            return $event;
        }

        /** @var Response $response */
        $response = $event->result;
        if (!static::isRedirectResponse($response)) {
            return $event;
        }

        $notificationId = Yii::$app->request->get('id');
        if (!$notificationId) {
            return $event;
        }

        $notificationModel = Notification::findOne(['id' => $notificationId, 'user_id' => Yii::$app->user->id]);
        if (!$notificationModel || $notificationModel->source_class !== Comment::class) {
            return $event;
        }

        $commentId = $notificationModel->source_pk;
        if (!$commentId) {
            return $event;
        }

        $oldUrl = self::getRedirectLocation($response);
        $newUrl = Url::withGetParam($oldUrl, static::urlParam(), $commentId);

//        return $response->getHeaders()->set(static::HEADER_LOCATION, $newUrl);
        return static::redirect($event, $newUrl);
    }

    public static function onContentPermaLink(ActionEvent $event)
    {
        if ($event->action->id !== 'index') {
            return $event;
        }

        /** @var Response $response */
        $response = $event->result;
        if (!self::isRedirectResponse($response)) {
            return $event;
        }

        $commentId = Yii::$app->request->get(static::urlParam());
        if (empty($commentId)) {
            return $event;
        }

        $oldUrl = self::getRedirectLocation($response);
        $newUrl = Url::withGetParam($oldUrl, static::urlParam(), $commentId);

//        return $response->getHeaders()->set(static::HEADER_LOCATION, $newUrl);
        return static::redirect($event, $newUrl);
    }

    private static function isRedirectResponse($response)
    {
        return is_object($response)
            && get_class($response) === Response::class
            && $response->getIsRedirection();
    }

    private static function getRedirectLocation(Response $response)
    {
        return $response->headers->get(
            static::HEADER_PJAX_REDIRECT,
            $response->headers->get(static::HEADER_LOCATION, '')
        );
    }

    private static function redirect(ActionEvent $event, $url)
    {
        /** @var Response $response */
        $response = $event->result;
        if ($response->headers->has(static::HEADER_PJAX_REDIRECT)) {
            $response->headers->remove(static::HEADER_PJAX_REDIRECT);
        }
        $event->result = $event->action->controller->redirect($url);

        return $event;
    }

    public static function onCommentShowAction(ActionEvent $event)
    {
        if (!Yii::$app->request->get(static::urlParam())) {
            return $event;
        }

        if (!static::isCommentShowAction($event->action)) {
            return $event;
        }

        $event->isValid = false;
        $event->result = Yii::$app->runAction(static::ROUTE_CUSTOM_SHOW_COMMENT);
        Yii::$app->response->data = $event->result;
    }

    private static function isCommentShowAction(Action $action)
    {
        return $action->id === 'show'
            && get_class($action->controller) === \humhub\modules\comment\controllers\CommentController::class;
    }

    public static function urlParam()
    {
        return Yii::$app->getModule('rocketfocuscomment')->commentIdParam;
    }
}
