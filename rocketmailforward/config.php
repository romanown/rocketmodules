<?php

use humhub\modules\admin\widgets\AdminMenu;
use humhub\modules\rocketmailforward\Events;
use yii\db\BaseActiveRecord;

return [
	'id' => 'rocketmailforward',
	'class' => 'humhub\modules\rocketmailforward\Module',
	'namespace' => 'humhub\modules\rocketmailforward',
    'events' => [
        [
            'class' => 'humhub\modules\mail\models\MessageEntry',
            'event' => BaseActiveRecord::EVENT_AFTER_INSERT,
            'callback' => [Events::class, 'onMessageSent']
        ],
        [
            'class' => 'humhub\modules\mail\models\MessageEntry',
            'event' => BaseActiveRecord::EVENT_AFTER_UPDATE,
            'callback' => [Events::class, 'onMessageUpdated']
        ],
        [
            'class' => AdminMenu::class,
            'event' => AdminMenu::EVENT_INIT,
            'callback' => [Events::class, 'onAdminMenuInit']
        ],
    ],
];
