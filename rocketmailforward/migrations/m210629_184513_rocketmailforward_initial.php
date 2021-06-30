<?php

use yii\db\Migration;

/**
 * Class m210629_184513_rocketmailforward_initial
 */
class m210629_184513_rocketmailforward_initial extends Migration
{
    private $tableMailForward = 'rocket_mail_forward';

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable($this->tableMailForward, [
            'user_id' => $this->integer(11)->notNull(),
            'endpoint' => $this->string(512)->notNull(),
        ]);
        $this->addPrimaryKey('pk_rocket_mail_forward', $this->tableMailForward, 'user_id');

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable($this->tableMailForward);

        return true;
    }
}
