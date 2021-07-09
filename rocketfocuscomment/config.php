<?php

use humhub\modules\rocketfocuscomment\Events;
use humhub\modules\admin\widgets\AdminMenu;
use humhub\widgets\TopMenu;

return [
	'id' => 'rocketfocuscomment',
	'class' => 'humhub\modules\rocketfocuscomment\Module',
	'namespace' => 'humhub\modules\rocketfocuscomment',
	'events' => [
//        [
//            'class' => \humhub\modules\space\controllers\SpaceController::class,
//            'event' => \humhub\modules\space\controllers\SpaceController::EVENT_BEFORE_ACTION,
//            'callback' => [Events::class, 'onSpaceIndexAction'],
//        ],
        [
            'class' => \humhub\modules\ui\view\components\View::class,
            'event' => \humhub\modules\ui\view\components\View::EVENT_BEGIN_BODY,
            'callback' => [Events::class, 'onViewRenderBegin'],
        ],
        [
            'class' => \humhub\modules\ui\view\components\View::class,
            'event' => \humhub\modules\ui\view\components\View::EVENT_END_BODY,
            'callback' => [Events::class, 'onViewRenderEnd'],
        ],
        [
            'class' => '\humhub\modules\comment\widgets\Comments',
            'event' => \humhub\components\Widget::EVENT_CREATE,
            'callback' => [Events::class, 'onCommentsWidgetCreate'],
        ],
        [
            'class' => \humhub\modules\notification\controllers\EntryController::class,
            'event' => \humhub\modules\notification\controllers\EntryController::EVENT_AFTER_ACTION,
            'callback' => [Events::class, 'onNotificationEntryRedirect'],
        ],
        [
            'class' => \humhub\modules\content\controllers\PermaController::class,
            'event' => \humhub\modules\content\controllers\PermaController::EVENT_AFTER_ACTION,
            'callback' => [Events::class, 'onContentPermaLink'],
        ],
        [
            'class' => \humhub\modules\comment\controllers\CommentController::class,
            'event' => \humhub\components\Controller::EVENT_BEFORE_ACTION,
            'callback' => [Events::class, 'onCommentShowAction']
        ],
	],
];
