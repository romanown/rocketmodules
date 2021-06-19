<?php
use humhub\modules\rocketmailfilter\Events;;

return [
	'id' => 'rocketmailfilter',
	'class' => 'humhub\modules\rocketmailfilter\Module',
	'namespace' => 'humhub\modules\rocketmailfilter',
	'events' => [
        [
            'class' => 'humhub\modules\ui\view\components\View',
            'event' => \humhub\modules\ui\view\components\View::EVENT_AFTER_RENDER,
            'callback' => [Events::class, 'onInboxRender']
        ],
        [
            'class' => 'humhub\modules\mail\controllers\InboxController',
            'event' => \humhub\components\Controller::EVENT_BEFORE_ACTION,
            'callback' => [Events::class, 'onInboxAction']
        ],
	],
];
