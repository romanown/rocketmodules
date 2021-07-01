<?php
namespace humhub\modules\rocketmailforward\controllers;

use humhub\modules\admin\components\Controller;
use humhub\modules\admin\permissions\ManageModules;
use humhub\modules\rocketmailforward\models\MailForward;
use humhub\modules\user\models\User;
use humhub\modules\user\models\UserPicker;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

class AdminController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

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

        $this->appendPageTitle(Yii::t('RocketmailforwardModule.base', 'Mail messages forward'));
        $this->subLayout = '@rocketmailforward/views/layouts/default';
    }

    /**
     * Configuration action for admins.
     */
    public function actionIndex()
    {
        $model = new MailForward();
        $dataProvider = $model->search();

        return $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Handles creation of the mail forward rule.
     */
    public function actionCreate()
    {
        $model = new MailForward();
        $data = Yii::$app->request->isPost ? $this->getSaveDataFromRequest() : [];

        if ($model->load($data) && $model->save()) {
            $this->view->saved();
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * * Handles updating of the mail forward rule.
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $data = Yii::$app->request->isPost ? $this->getSaveDataFromRequest() : [];

        if ($model->load($data) && $model->save()) {
            $this->view->saved();
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes mail forward rule.
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        $this->view->success(Yii::t('RocketmailforwardModule.base', 'Rule deleted'));

        return $this->redirect(['index']);
    }

    /**
     * Finds the existing mail forward rule from id specified
     * or from specific "prev_user_id" field (used in update flow) of the post request.
     */
    protected function findModel($id)
    {
        $post = Yii::$app->request->post();
        if (!empty($post['prev_user_id'])) {
            $id = $post['prev_user_id'];
        }

        if (($model = MailForward::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Replaces guid in the request with user id if any present.
     *
     * @return array|mixed
     */
    private function getSaveDataFromRequest()
    {
        $post = Yii::$app->request->post();
        if (!empty($post['MailForward']['user_id'])) {
            $userIdOrGuid = $post['MailForward']['user_id'][0];
            if (is_numeric($userIdOrGuid)) {
                $post['MailForward']['user_id'] = $userIdOrGuid;
                return $post;
            }
            $user = User::find()
                ->where('id = :id', ['id' => $userIdOrGuid])
                ->orWhere('guid = :guid', ['guid' => $userIdOrGuid])
                ->one();
            if ($user) {
                $post['MailForward']['user_id'] = $user->id;
                return $post;
            }
        }

        return $post;
    }

    /**
     * Autocomplete for users in create/update rule page.
     *
     * @param $keyword
     * @return \yii\web\Response
     * @throws \Throwable
     */
    public function actionSearchUser($keyword)
    {
        $result = UserPicker::filter([
            'query' => MailForward::getNotManagedUsersQuery(),
            'keyword' => $keyword,
            'fillUser' => true,
            'disableFillUser' => true,
            'disabledText' => Yii::t('RocketmailforwardModule.base','The specified user has forwarding rule already set.')
        ]);

        return $this->asJson($result);
    }
}
