<?php
namespace humhub\modules\rocketmailforward\models;

use humhub\modules\rocketmailforward\Module;
use Yii;
use yii\base\Model;

class Config extends Model
{
    const DEFAULT_TIMEOUT_SEC = 5;

    public $httpRequestTimeout = self::DEFAULT_TIMEOUT_SEC;

    public function init()
    {
        parent::init();
        $module = $this->getModule();
        $this->httpRequestTimeout = (int)$module->settings->get('httpRequestTimeout', $this->httpRequestTimeout);
    }

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return [
            [['httpRequestTimeout'], 'integer', 'min' => 1, 'max' => 30],
            [['httpRequestTimeout'], 'required'],
        ];
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels()
    {
        return [
            'httpRequestTimeout' => Yii::t('RocketmailforwardModule.base', 'Maximum waiting time for sending http request with new message'),
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $module = static::getModule();
        $module->settings->set('httpRequestTimeout', $this->httpRequestTimeout);
        return true;
    }

    /**
     * @return Module
     */
    public static function getModule()
    {
        return Yii::$app->getModule('rocketmailforward');
    }
}
