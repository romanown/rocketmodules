<?php
namespace humhub\modules\rocketadminstats\widgets;

use Yii;
use humhub\modules\admin\permissions\ManageGroups;
use humhub\modules\admin\permissions\ManageSettings;
use humhub\modules\admin\permissions\ManageUsers;
use humhub\modules\ui\menu\MenuLink;
use humhub\modules\ui\menu\widgets\TabMenu;
use yii\base\BaseObject;

class StatsTabMenu extends TabMenu
{
    public function init()
    {
        $this->addEntry(new MenuLink([
            'label' => Yii::t('RocketadminstatsModule.base', 'Top Talkers'),
            'url' => ['/rocketadminstats/admin/index'],
            'sortOrder' => 100,
            'isActive' => MenuLink::isActiveState('rocketadminstats', ['admin'], ['index', 'users']),
            'isVisible' => Yii::$app->user->can([
                ManageUsers::class,
                ManageGroups::class,
            ]),
        ]));

        $this->addEntry(new MenuLink([
            'label' => Yii::t('RocketadminstatsModule.base', 'Popular Posts'),
            'url' => ['/rocketadminstats/admin/posts'],
            'sortOrder' => 200,
            'isActive' => MenuLink::isActiveState('rocketadminstats', ['admin'], ['posts']),
            'isVisible' => Yii::$app->user->can(ManageSettings::class),
        ]));

        $this->addEntry(new MenuLink([
            'label' => Yii::t('RocketadminstatsModule.base', 'Popular Comments'),
            'url' => ['/rocketadminstats/admin/comments'],
            'sortOrder' => 300,
            'isActive' => MenuLink::isActiveState('rocketadminstats', ['admin'], ['comments']),
            'isVisible' => Yii::$app->user->can(ManageSettings::class),
        ]));

        parent::init();
    }
}
