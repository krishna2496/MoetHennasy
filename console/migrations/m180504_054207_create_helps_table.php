<?php

use yii\db\Migration;

/**
 * Handles the creation of table `stores`.
 */
class m180504_054207_create_helps_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('helps', [
            'id' => $this->primaryKey(11)->unsigned(),
            'category_id' => $this->integer()->unsigned()->defaultValue(null),
            'question' => $this->mediumText()->defaultValue(null),
            'answer' => $this->text()->defaultValue(null),
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
        $this->dropTable('stores');
    }
}
