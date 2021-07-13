<?php
namespace humhub\modules\rocketfocuscomment\models;

use humhub\modules\comment\models\Comment as BaseComment;
use humhub\modules\comment\Module;
use humhub\modules\post\models\Post;
use Yii;

class Comment extends BaseComment
{
    public static $focusFetched = false;

    public static function GetCommentsLimited($model, $id, $limit = null)
    {
        $focusedCommentId = static::getFocusedCommentId();
        if (!$focusedCommentId || static::isForcedToUseCache()) {
            return parent::GetCommentsLimited($model, $id, $limit);
        }

        if ($limit === null) {
            /** @var Module $module */
            $module = Yii::$app->getModule('comment');
            $limit = $module->commentsPreviewMax;
        }

        $commentCount = self::GetCommentCount($model, $id);
        $query = BaseComment::find();

        $parentCommentId = static::getParentCommentId($focusedCommentId);
        if ($parentCommentId) {
            if (static::isRequestForNestedComments($model) && $id === $parentCommentId) {
                $query->orderBy(sprintf('`comment`.`id` = "%d" ASC', $focusedCommentId));
            } else {
                $query->orderBy(sprintf('`comment`.`id` = "%d" ASC', $parentCommentId));
            }
        } else {
            if (!static::isRequestForNestedComments($model)) {
                $query->orderBy(sprintf('`comment`.`id` = "%d" ASC', $focusedCommentId));
            }
        }

        $query->offset($commentCount - $limit);
        $query->addOrderBy(['created_at' => SORT_ASC]);
        $query->limit($limit);
        $query->where(['object_model' => $model, 'object_id' => $id]);
        $query->joinWith('user');

        // We do this in order to reuse parent's cache of GetCommentsLimited
        $comments = $query->all();
        foreach ($comments as $comment) {
            if ($comment->id === $focusedCommentId) {
                static::$focusFetched = true;
                break;
            }
        }

        return $comments;
    }

    public static function getFocusedCommentId()
    {
        $param = Yii::$app->getModule('rocketfocuscomment')->commentIdParam;

        return (int)Yii::$app->request->get($param);
    }

    public static function getParentCommentId($commentId)
    {
        $parentCommentId = null;
        $query = static::find();
        $query->select(['object_model', 'object_id']);
        $query->where(['id' => $commentId]);
        $query->limit(1);
        $commentModel = $query->one();

        if ($commentModel->object_model === BaseComment::class) {
            $parentCommentId = $commentModel->object_id;
        }

        return $parentCommentId;
    }

    public static function getRelatedPostId($commentId)
    {
        $postId = null;
        $query = Yii::$app->db->createCommand(
            "SELECT object_id FROM comment WHERE id = :id AND object_model = :objectModel",
            ['id' => $commentId, 'objectModel' => Post::class]
        );
        $postId = $query->queryScalar();

        return $postId ? $postId : null;
    }

    public static function isRequestForNestedComments($model)
    {
        return $model === BaseComment::class;
    }

    public static function isForcedToUseCache()
    {
        return static::$focusFetched
            || Yii::$app->request->get('page', 0) > 1;
    }

    public static function GetCommentCountIncludingNested($model, $id)
    {
        if ($model === Post::class) {
            $cacheID = sprintf("commentCountWithNested_%s_%s", $model, $id);
            $commentCount = Yii::$app->cache->get($cacheID);
            if ($commentCount === false) {
                $directCommentsIds = static::getDirectCommentsIds($model, $id);
                $nestedCommentsCount = static::getNestedCommentsCount($directCommentsIds);
                $commentCount = count($directCommentsIds) + $nestedCommentsCount;
                Yii::$app->cache->set($cacheID, $commentCount, Yii::$app->settings->get('cache.expireTime'));
            }
            return $commentCount;
        } else {
            return parent::GetCommentCount($model, $id);
        }
    }

    private static function getDirectCommentsIds($model, $id)
    {
        $commentTable = static::tableName();
        $command = Yii::$app->db->createCommand(
        "SELECT `id` FROM $commentTable WHERE `object_model` = :objectModel AND `object_id` = :objectId",
            ['objectModel' => $model, 'objectId' => $id]
        );

        return $command->queryColumn();
    }

    private static function getNestedCommentsCount(array $parentIds)
    {
        if (empty($parentIds)) {
            return 0;
        }

        $params = [];
        $inCondition = Yii::$app->db->getQueryBuilder()->buildCondition(['IN', 'object_id', $parentIds], $params);
        $params['objectModel'] = BaseComment::class;
        $commentTable = static::tableName();
        $command = Yii::$app->db->createCommand(
        "SELECT COUNT(`id`) FROM $commentTable WHERE `object_model` = :objectModel AND $inCondition",
            $params
        );
        $count = $command->queryScalar();

        return $count ?: 0;
    }

    public static function flushCommentCacheFull(BaseComment $comment)
    {
        $objectModel = $comment->object_model;
        $objectId = $comment->object_id;

        if ($objectModel === BaseComment::class && $postId = static::getRelatedPostId($objectId)) {
            Yii::$app->cache->delete('commentCountWithNested_' . Post::class . '_' . $postId);
        }

        static::flushCommentCache($objectModel, $objectId);
    }

    public static function flushCommentCache($model, $id)
    {
        Yii::$app->cache->delete('commentCount_' . $model . '_' . $id);
        Yii::$app->cache->delete('commentCountWithNested_' . $model . '_' . $id);
        Yii::$app->cache->delete('commentsLimited_' . $model . '_' . $id);
    }
}
