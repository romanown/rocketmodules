<?php
namespace humhub\modules\rocketmailfilter\controllers;

use Yii;
use humhub\modules\mail\controllers\InboxController as BaseInboxController;
use humhub\modules\mail\widgets\ConversationInbox;
use humhub\modules\rocketmailfilter\models\forms\InboxFilterForm;

class InboxController extends BaseInboxController
{
    /**
     * Overrides inbox filter action to be able to get only unread conversations.
     * For BC reasons it should be called only in case the post request contains corresponding parameter.
     * Otherwise fallbacks to the original controller.
     *
     * @return string
     * @throws \Exception
     */
    public function actionIndex()
    {
        if (Yii::$app->request->post('unread', false)) {
            return ConversationInbox::widget([
                'filter' => new InboxFilterForm(),
            ]);
        }

        return parent::actionIndex();
    }
}
