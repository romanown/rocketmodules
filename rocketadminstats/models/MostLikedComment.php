<?php
namespace rocket\humhub\modules\rocketadminstats\models;

use humhub\libs\DateHelper;
use humhub\modules\comment\models\Comment;
use yii\data\ActiveDataProvider;

class MostLikedComment extends Comment
{
    public $startDate;
    public $endDate;
    public $likesCount;

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['startDate', 'endDate'], 'safe'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        $query = parent::find();

        $query->addSelect(['comment.*', 'COUNT(l.id) as likesCount']);
        $query->leftJoin(
            'like l',
            'l.object_model = :commentModel AND l.object_id = comment.id',
            ['commentModel' => Comment::class]
        );
        $query->addGroupBy('comment.id');

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
            'pagination' => ['pageSize' => $settings->commentsPerPage],
        ]);
        $dataProvider->setSort([
            'attributes' => ['likesCount'],
        ]);
        $dataProvider->sort->defaultOrder = ['likesCount' => SORT_DESC];
        $this->load($params);

        if (empty($this->startDate)) {
            $query->andWhere('l.created_at >= NOW() - INTERVAL 1 DAY');
        } else {
            $query->andWhere(
                'l.created_at >= :startDate',
                ['startDate' => DateHelper::parseDateTime($this->startDate)]
            );
        }
        if (!empty($this->endDate)) {
            $query->andWhere(
                'l.created_at <= :endDate',
                ['endDate' => DateHelper::parseDateTime($this->endDate)]
            );
        }

        return $dataProvider;
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();

        $this->likesCount = (int)$this->likesCount;
    }
}
