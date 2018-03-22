<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cms_categories`.
 */
class m180322_091543_create_cms_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('cms_categories', [
            'id' => $this->primaryKey(11)->unsigned(),
            'name' => $this->string(30)->defaultValue(null),
            'order' => $this->integer()->unsigned()->defaultValue(null),
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
        $this->dropTable('cms_categories');
    }
}
