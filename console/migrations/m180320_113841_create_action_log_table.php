<?php

use yii\db\Migration;

/**
 * Handles the creation of table `users`.
 */
class m180320_113841_action_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('action_log', [
            'id' => $this->primaryKey(11)->unsigned(),
            'action_type' => $this->string(110)->notNull(),
            'date' => $this->date()->defaultValue(null),
            'time' => $this->time()->defaultValue(null),
            'last_name' => $this->string(100)->defaultValue(null),
            'description' => $this->text()->defaultValue(null),
            'user' => $this->integer()->unsigned()->defaultValue(null),
            'created_by' => $this->integer()->unsigned()->defaultValue(null),
            'updated_by' => $this->integer()->unsigned()->defaultValue(null),
            'deleted_by' => $this->integer()->unsigned()->defaultValue(null),
            'created_at' => $this->dateTime()->defaultValue(null),
            'updated_at' => $this->dateTime()->defaultValue(null),
            'deleted_at' => $this->dateTime()->defaultValue(null)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('action_log');
    }
}
