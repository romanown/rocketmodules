<?php
namespace humhub\modules\rocketmailfilter\models\forms;

use Yii;
use humhub\modules\mail\models\forms\InboxFilterForm as BaseInboxFilterForm;
use yii\db\ActiveQuery;

class InboxFilterForm extends BaseInboxFilterForm
{
    /**
     * @var bool
     */
    public $unread = 0;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['unread'], 'integer']
        ]);
    }

    /**
     * @inheritdoc
     */
    public function apply()
    {
        parent::apply();

        if ($this->unread) {
            $this->applyUnreadFilter($this->query);
        }
    }

    /**
     * Applies new messages condition to the query provided.
     *
     * @param ActiveQuery $query
     */
    public function applyUnreadFilter(ActiveQuery $query)
    {
        $query->andWhere("message.updated_at > user_message.last_viewed OR user_message.last_viewed IS NULL")
            ->andWhere(["<>", 'message.updated_by', Yii::$app->user->id]);
    }
}
