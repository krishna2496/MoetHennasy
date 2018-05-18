<?php

use yii\db\Migration;

/**
 * Handles the creation of table `users`.
 */
class m180320_113841_rules_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('action_log', [
            'id' => $this->primaryKey(11)->unsigned(),
            'type' => $this->string(50)->notNull(),
            'product_fields' => $this->string(50)->notNull(),
            'detail' => $this->text()->notNull(),
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
