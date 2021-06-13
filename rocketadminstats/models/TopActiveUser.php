<?php
namespace rocket\humhub\modules\rocketadminstats\models;

use humhub\libs\DateHelper;
use humhub\modules\user\models\User;
use yii\data\ActiveDataProvider;
use yii\db\Query;

class TopActiveUser extends User
{
    public $startDate;
    public $endDate;
    public $commentsCount = 0;

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['startDate', 'endDate'], 'safe'],
        ]);
    }

    public static function find()
    {
        $query = parent::find();

        $query->addSelect(['user.*', 'COUNT(c.id) as commentsCount']);
        $query->leftJoin('comment c', 'c.created_by = user.id');
        $query->addGroupBy('user.id');

        return $query;
    }

    /**
     * @param string[] $params
     * @return ActiveDataProvider
     */
    public function search(array $params = [])
    {
        $settings = AdminStatsSettings::instance();

        $query = static::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => $settings->usersPerPage],
        ]);
        $dataProvider->setSort([
            'attributes' => ['commentsCount'],
        ]);
        $dataProvider->sort->defaultOrder = ['commentsCount' => SORT_DESC];
        $this->load($params);

        if (empty($this->startDate)) {
            $query->andWhere('c.created_at >= NOW() - INTERVAL 1 DAY');
        } else {
            $query->andWhere(
                'c.created_at >= :startDate',
                ['startDate' => DateHelper::parseDateTime($this->startDate)]
            );
        }
        if (!empty($this->endDate)) {
            $query->andWhere(
                'c.created_at <= :endDate',
                ['endDate' => DateHelper::parseDateTime($this->endDate)]
            );
        }

        return $dataProvider;
    }


    /**
     * Returns the number of active users (status = enabled)
     *
     * @return int
     */
    public function countEnabledUsers()
    {
        try {
            $query = new Query();
            $result = $query->select('COUNT(id)')
                ->from(User::tableName())
                ->where('status = :enabled', ['enabled' => User::STATUS_ENABLED])
                ->createCommand(\Yii::$app->db)
                ->queryScalar();
        } catch (\Exception $e) {
            $result = 0;
        }

        return (int)$result;
    }

    /**
     * Returns the number of users who has any activity for the specified period of time
     *
     * @return int
     */
    public function countUsersWithActivity()
    {
        try {
            $query = new Query();
            $result = $query->select('COUNT(DISTINCT u.id)')
                ->from('user u')
                ->leftJoin($this->joinActivity('`like`', 'l'))
                ->leftJoin($this->joinActivity('comment', 'c'))
                ->leftJoin($this->joinActivity('post', 'p'))
                ->where('c.created_at IS NOT NULL')
                ->orWhere('c.updated_at IS NOT NULL')
                ->orWhere('l.created_at IS NOT NULL')
                ->orWhere('l.updated_at IS NOT NULL')
                ->orWhere('p.created_at IS NOT NULL')
                ->orWhere('p.updated_at IS NOT NULL')
                ->createCommand(\Yii::$app->db)
                ->queryScalar();
        } catch (\Exception $e) {
            $result = 0;
        }

        return (int)$result;
    }

    private function joinActivity($table, $a)
    {
        return <<<JOIN
$table $a ON ($a.created_by = u.id AND {$this->datesCondition($a, 'created_at')})
    OR ($a.updated_by = u.id AND {$this->datesCondition($a, 'updated_at')})
JOIN;
    }

    private function datesCondition($table, $field)
    {
        if (empty($this->startDate) && empty($this->endDate)) {
            $condition = sprintf('%s.%s >= NOW() - INTERVAL 1 DAY', $table, $field);
        } else if (empty($this->startDate)) {
            $condition = sprintf(
                '%s.%s <= %s',
                $table,
                $field,
                DateHelper::parseDateTime($this->endDate)
            );
        } else if (empty($this->endDate)) {
            $condition = sprintf(
                '%s.%s >= %s',
                $table,
                $field,
                DateHelper::parseDateTime($this->startDate)
            );
        } else {
            $condition = sprintf(
                '%s.%s >= %s AND %s.%s <= %s',
                $table,
                $field,
                DateHelper::parseDateTime($this->startDate),
                $table,
                $field,
                DateHelper::parseDateTime($this->endDate)
            );
        }

        return $condition;
    }

    public function afterFind()
    {
        parent::afterFind();

        $this->commentsCount = (int)$this->commentsCount;
    }
}
