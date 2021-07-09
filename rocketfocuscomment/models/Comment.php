<?php
namespace humhub\modules\rocketfocuscomment\models;

use humhub\modules\comment\models\Comment as BaseComment;
use humhub\modules\comment\Module;
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

    public static function isRequestForNestedComments($model)
    {
        return $model === BaseComment::class;
    }

    public static function isForcedToUseCache()
    {
        return static::$focusFetched
            || Yii::$app->request->get('page', 0) > 1;
    }
}
