<?php
namespace humhub\modules\rocketmailforward\controllers;

use humhub\modules\admin\components\Controller;
use humhub\modules\admin\permissions\ManageModules;
use humhub\modules\rocketmailforward\models\Config;
use Yii;

class ConfigController extends Controller
{
    /**
     * @inheritdoc
     */
    public function getAccessRules()
    {
        return [['permissions' => ManageModules::class]];
    }

    /**
     * Configuration action for admins.
     */
    public function actionIndex()
    {
        $model = new Config();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->view->saved();
        }

        return $this->render('index', array(
            'model' => $model,
        ));
    }
}
