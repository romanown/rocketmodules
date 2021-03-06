<?php

use humhub\modules\rocketadminstats\Events;
use humhub\modules\admin\widgets\AdminMenu;
use humhub\widgets\TopMenu;

return [
	'id' => 'rocketadminstats',
	'class' => 'humhub\modules\rocketadminstats\Module',
	'namespace' => 'humhub\modules\rocketadminstats',
	'events' => [
		[
			'class' => AdminMenu::class,
			'event' => AdminMenu::EVENT_INIT,
			'callback' => [Events::class, 'onAdminMenuInit']
		],
	],
];
