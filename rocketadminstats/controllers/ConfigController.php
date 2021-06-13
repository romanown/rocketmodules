<?php
namespace humhub\modules\rocketadminstats\controllers;

use Yii;
use humhub\modules\admin\permissions\ManageModules;
use humhub\modules\admin\components\Controller;
use humhub\modules\rocketadminstats\models\AdminStatsSettings;

/**
 * Defines the configure actions.
 */
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
        $model = new AdminStatsSettings();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->view->saved();
        }

        return $this->render('config', array(
            'model' => $model,
        ));
    }
}
