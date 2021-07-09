<?php
namespace humhub\modules\rocketfocuscomment;

use humhub\modules\rocketfocuscomment\assets\FocusCommentAssets;
use Yii;

class Module extends \humhub\components\Module
{
    public $commentIdParam = 'ctid';
    public $commentsBlockLoadSize = 10;

    public function init()
    {
        parent::init();

        $this->commentsBlockLoadSize = Yii::$app->getModule('comment')->commentsBlockLoadSize;
    }
}
