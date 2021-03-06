<?php
namespace humhub\modules\rocketadminstats\models;

use humhub\libs\DateHelper;
use humhub\modules\post\models\Post;
use humhub\modules\rocketadminstats\helpers\DbDateParser;
use yii\data\ActiveDataProvider;

class MostCommentedPost extends Post
{
    public $startDate;
    public $endDate;
    public $commentsCount;

    /**
     * @inheritdoc
     */
    public static function getObjectModel()
    {
        return Post::class;
    }

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

        $query->addSelect(['post.*', 'COUNT(c.id) as commentsCount']);
        $query->joinWith('content');
        $query->leftJoin(
            'comment c',
            'c.object_model = :postModel AND c.object_id = post.id',
            ['postModel' => Post::class]
        );
        $query->addGroupBy('post.id');

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
            'pagination' => ['pageSize' => $settings->postsPerPage],
        ]);
        $dataProvider->setSort([
            'attributes' => ['commentsCount'],
        ]);
        $dataProvider->sort->defaultOrder = ['commentsCount' => SORT_DESC];
        $this->load($params);

        if ($this->startDate && $this->endDate) {
            $query->andWhere(
                'c.created_at >= :startDate',
                ['startDate' => DbDateParser::parse($this->startDate)]
            );
            $query->andWhere(
                'c.created_at <= :endDate',
                ['endDate' => DbDateParser::parse($this->endDate)]
            );
        } else if ($this->startDate) {
            $query->andWhere(
                'c.created_at >= :startDate',
                ['startDate' => DbDateParser::parse($this->startDate)]
            );
        } else if ($this->endDate) {
            $query->andWhere(
                'c.created_at <= :endDate',
                ['endDate' => DbDateParser::parse($this->endDate)]
            );
        } else {
            $query->andWhere('c.created_at >= NOW() - INTERVAL 1 DAY');
        }

        return $dataProvider;
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();

        $this->commentsCount = (int)$this->commentsCount;
    }
}
