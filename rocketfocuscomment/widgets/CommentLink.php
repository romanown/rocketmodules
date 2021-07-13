<?php
namespace humhub\modules\rocketfocuscomment\widgets;

use humhub\components\Widget;
//use humhub\modules\comment\models\Comment as CommentModel;
use humhub\modules\comment\models\Comment as BaseCommentModel;
use humhub\modules\rocketfocuscomment\models\Comment as CommentModel;
use humhub\modules\comment\Module;
use humhub\modules\comment\widgets\CommentLink as BaseWidget;
use humhub\modules\content\components\ContentActiveRecord;
use Yii;

/**
 * This widget is used to show a comment link inside the wall entry controls.
 *
 * @since 0.5
 */
class CommentLink extends BaseWidget
{
    private $template = '@comment/widgets/views/link';

    /**
     * @inheritDoc
     */
    public function run()
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('comment');

        if (empty($this->mode)) {
            $this->mode = self::MODE_INLINE;
        }

        if ($module->canComment($this->object) || CommentModel::isSubComment($this->object) && $module->canComment($this->object->content->getPolymorphicRelation())){
            return $this->render($this->template, [
                'id' => $this->object->getUniqueId(),
                'mode' => $this->mode,
                'objectModel' => BaseCommentModel::class,
                'objectId' => $this->object->getPrimaryKey(),
                'commentCount' => CommentModel::GetCommentCountIncludingNested(get_class($this->object), $this->object->getPrimaryKey()),
                'isNestedComment' => ($this->object instanceof CommentModel),
                'comment' => $this->object,
                'module' => $module
            ]);
        }
    }
}
