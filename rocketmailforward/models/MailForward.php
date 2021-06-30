<?php
namespace humhub\modules\rocketmailforward\models;

use humhub\components\ActiveRecord;
use humhub\modules\mail\models\MessageEntry;
use humhub\modules\rocketadminstats\helpers\DbDateParser;
use humhub\modules\rocketadminstats\models\AdminStatsSettings;
use humhub\modules\user\models\User;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * Class MailForward
 * @package humhub\modules\rocketmailforward\models
 *
 * @property integer $user_id
 * @property string $endpoint
 */
class MailForward extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rocket_mail_forward';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'endpoint'], 'required'],
            [['endpoint'], 'string', 'max' => 512],
//            [['endpoint'], 'url'],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function findWithUsers()
    {
        $query = static::find();
        $query->joinWith(['user', 'user.profile']);

        return $query;
    }

    /**
     * @param string[] $params
     * @return ActiveDataProvider
     */
    public function search(array $params = [])
    {
        $query = static::findWithUsers();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 50],
        ]);

        $this->load($params);

        return $dataProvider;
    }

    /**
     * Returns query of not managed users for autocomplete.
     *
     * @return \humhub\modules\user\components\ActiveQueryUser
     */
    public static function getNotManagedUsersQuery()
    {
        $query = User::find();

        $ids = Yii::$app->db->createCommand(sprintf(
            'SELECT user_id FROM %s',
            static::tableName()
        ))->queryAll(\PDO::FETCH_COLUMN);
        $query->andWhere(['not in', 'id', $ids]);

        return $query;
    }

    /**
     * Returns the recipients list for whom the message has to be forwarded.
     *
     * @param MessageEntry $messageEntry
     * @return MailForward[]
     */
    public function findRecipients(MessageEntry $messageEntry)
    {
        $query = parent::find();
        return $query->select('rmf.*')
            ->from(sprintf('%s %s', static::tableName(), 'rmf'))
            ->innerJoin('user_message um', 'um.user_id = rmf.user_id')
            ->where('um.message_id = :messageId', ['messageId' => $messageEntry->message_id])
            ->andWhere('um.user_id <> :authorId', ['authorId' => $messageEntry->created_by])
            ->all();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('RocketmailforwardModule.base', 'Recipient'),
            'endpoint' => Yii::t('RocketmailforwardModule.base', 'Http endpoint'),
        ];
    }

    public function attributeHints()
    {
        return [
            'user_id' => Yii::t('RocketmailforwardModule.base', 'Pick the recipient for whom inbox messages will be forwarded'),
            'endpoint' => Yii::t('RocketmailforwardModule.base', 'Forwarding web address for post messages'),
        ];
    }
}
