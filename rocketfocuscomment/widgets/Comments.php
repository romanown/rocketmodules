<?php
namespace humhub\modules\rocketfocuscomment\widgets;

use humhub\modules\comment\Module;
use humhub\modules\comment\widgets\Comments as BaseComments;
//use humhub\modules\comment\models\Comment as CommentModel;
use humhub\modules\comment\models\Comment as BaseCommentModel;
use humhub\modules\rocketfocuscomment\models\Comment as CommentModel;
use Yii;

class Comments extends BaseComments
{
    private $template = '@comment/widgets/views/comments';

    public function run()
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('comment');

        $objectModel = $this->getObjectModel($this->object);
        $objectId = $this->object->getPrimaryKey();

        // Count all Comments
        $commentCount = CommentModel::GetCommentCount($objectModel, $objectId);
        $comments = [];
        if ($commentCount !== 0) {
            $comments = CommentModel::GetCommentsLimited($objectModel, $objectId, $module->commentsPreviewMax);
        }

        foreach ($comments as $comment) {
            if ($comment->object_model === CommentModel::class) {
                $comment->object_model = BaseCommentModel::class;
            }
        }

        $isLimited = ($commentCount > $module->commentsPreviewMax);
        return $this->render($this->template, [
            'object' => $this->object,
            'comments' => $comments,
            'objectModel' => $objectModel,
            'objectId' => $objectId,
            'id' => $this->object->getUniqueId(),
            'isLimited' => $isLimited,
            'total' => $commentCount
        ]);
    }

    protected function getObjectModel($object)
    {
        $objectModel = get_class($object);
        if ($objectModel === CommentModel::class) {
            $objectModel = BaseCommentModel::class;
        }

        return $objectModel;
    }
}
