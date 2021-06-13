<?php
namespace rocket\humhub\modules\rocketadminstats\controllers;

use Yii;
use humhub\modules\admin\permissions\ManageModules;
use humhub\modules\admin\components\Controller;
use rocket\humhub\modules\rocketadminstats\models\MostCommentedPost;
use rocket\humhub\modules\rocketadminstats\models\MostLikedComment;
use rocket\humhub\modules\rocketadminstats\models\TopActiveUser;

class AdminController extends Controller
{
    /**
     * @inheritdoc
     */
    public function getAccessRules()
    {
        return [['permissions' => ManageModules::class]];
    }

    public function init()
    {
        parent::init();

        $this->appendPageTitle(Yii::t('RocketadminstatsModule.base', 'Users activity stats'));
        $this->subLayout = '@rocketadminstats/views/layouts/default';
    }

    public function actionIndex()
    {
        return $this->actionUsers();
    }

    public function actionUsers()
    {
        $searchModel = new TopActiveUser();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('users', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPosts()
    {
        $searchModel = new MostCommentedPost();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('posts', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionComments()
    {
        $searchModel = new MostLikedComment();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('comments', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
