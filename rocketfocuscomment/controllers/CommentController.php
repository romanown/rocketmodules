<?php
namespace humhub\modules\rocketfocuscomment\controllers;

use humhub\modules\comment\controllers\CommentController as BaseController;
use humhub\modules\comment\models\Comment as BaseComment;
use humhub\modules\comment\widgets\Comment as CommentWidget;
use humhub\modules\comment\widgets\ShowMore;
use humhub\modules\rocketfocuscomment\models\Comment;
use Yii;
use yii\data\Pagination;

class CommentController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function actionShow()
    {
        $focusedCommentId = Yii::$app->request->get($this->module->commentIdParam);
        if (!$focusedCommentId) {
            return parent::actionShow();
        }

        $commentModule = Yii::$app->getModule('comment');
        $parentCommentId = Comment::getParentCommentId($focusedCommentId);

        $query = Comment::find();
        if ($parentCommentId) {
            if ($this->isRequestForNestedComments() && $this->target->id === $parentCommentId) {
                $query->orderBy(sprintf('`comment`.`id` = "%d" DESC', $focusedCommentId));
            } else {
                $query->orderBy(sprintf('`comment`.`id` = "%d" DESC', $parentCommentId));
            }
        } else {
            if (!$this->isRequestForNestedComments()) {
                $query->orderBy(sprintf('`comment`.`id` = "%d" DESC', $focusedCommentId));
            }
        }

        $query->addOrderBy(['created_at' => SORT_DESC]);
//        $query->joinWith('user');
        $query->where(['object_model' => get_class($this->target), 'object_id' => $this->target->getPrimaryKey()]);

        $pagination = new Pagination([
            'totalCount' => Comment::GetCommentCount(get_class($this->target), $this->target->getPrimaryKey()),
            'pageSize' => $commentModule->commentsBlockLoadSize
        ]);

        $query->offset($pagination->offset)->limit($pagination->limit);
        $comments = array_reverse($query->all());

        Comment::$focusFetched = true;
        $output = ShowMore::widget(['pagination' => $pagination, 'object' => $this->target]);
        foreach ($comments as $comment) {
            $output .= CommentWidget::widget(['comment' => $comment]);
        }

        if (Yii::$app->request->get('mode') === 'popup') {
            return $this->renderAjax('showPopup', ['object' => $this->target, 'output' => $output, 'id' => $this->target->content->getUniqueId()]);
        } else {
            return $this->renderAjaxContent($output);
        }
    }

    /**
     * Checks whether the request is for root comments section or for nested block
     *
     * @return bool
     */
    private function isRequestForNestedComments()
    {
        return get_class($this->target) === BaseComment::class;
    }
}
