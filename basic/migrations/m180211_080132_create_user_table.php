<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m180211_080132_create_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username'=> $this->string(12)->notNull()->unique(),
            'password'=> $this->string(32)->notNull(),
            'referal'=>$this->integer(),
            'referelCode'=>$this->string(10)->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user');
    }
}
