<?php
namespace rocket\humhub\modules\rocketadminstats\models;

use Yii;
use yii\base\Model;

class AdminStatsSettings extends Model
{
    /**
     * @var int Number of posts per page
     */
    public $usersPerPage = 10;

    /**
     * @var int Number of posts per page
     */
    public $postsPerPage = 10;

    /**
     * @var int Number of posts per page
     */
    public $commentsPerPage = 10;

    /**
     * @inerhitdoc
     */
    public function init()
    {
        $module = Yii::$app->getModule('rocketadminstats');
        $this->usersPerPage = (int)$module->settings->get('usersPerPage', $this->usersPerPage);
        $this->postsPerPage = (int)$module->settings->get('postsPerPage', $this->postsPerPage);
        $this->commentsPerPage = (int)$module->settings->get('commentsPerPage', $this->commentsPerPage);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['usersPerPage', 'required'],
            ['usersPerPage', 'integer', 'min' => 1, 'max' => 50],
            ['postsPerPage', 'required'],
            ['postsPerPage', 'integer', 'min' => 1, 'max' => 50],
            ['commentsPerPage', 'required'],
            ['commentsPerPage', 'integer', 'min' => 1, 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array(
            'usersPerPage' => Yii::t('RocketadminstatsModule.base', 'Number of active users per page'),
            'postsPerPage' => Yii::t('RocketadminstatsModule.base', 'Number of most commented posts per page'),
            'commentsPerPage' => Yii::t('RocketadminstatsModule.base', 'Number of most liked comments per page'),
        );
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $module = Yii::$app->getModule('rocketadminstats');
        $module->settings->set('usersPerPage', $this->usersPerPage);
        $module->settings->set('postsPerPage', $this->postsPerPage);
        $module->settings->set('commentsPerPage', $this->commentsPerPage);

        return true;
    }
}
