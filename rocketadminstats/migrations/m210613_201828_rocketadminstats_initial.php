<?php

use yii\db\Migration;

/**
 * Class m210613_201828_rocketadminstats_initial
 */
class m210613_201828_rocketadminstats_initial extends Migration
{
    private $userStatusIndex = 'idx_user_status';
    private $likeCreatedAtIndex = 'idx_like_created_at';
    private $postCreatedAtIndex = 'idx_post_created_at';
    private $commentCreatedAtIndex = 'idx_comment_created_at';

    public function up()
    {
        $this->createIndex($this->userStatusIndex, 'user', ['status']);
        $this->createIndex($this->likeCreatedAtIndex, 'like', ['created_at']);
        $this->createIndex($this->postCreatedAtIndex, 'post', ['created_at']);
        $this->createIndex($this->commentCreatedAtIndex, 'comment', ['created_at']);
    }

    public function down()
    {
        $this->dropIndex($this->userStatusIndex, 'user');
        $this->dropIndex($this->likeCreatedAtIndex, 'like');
        $this->dropIndex($this->postCreatedAtIndex, 'post');
        $this->dropIndex($this->commentCreatedAtIndex, 'comment');
    }
}
