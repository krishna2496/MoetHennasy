<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cms`.
 */
class m180322_091951_create_cms_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('cms', [
            'id' => $this->primaryKey(11)->unsigned(),
            'cms_category_id' => $this->integer()->unsigned()->defaultValue(null),
            'title' => $this->string(255)->defaultValue(null),
            'description' => $this->text()->defaultValue(null),
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
        $this->dropTable('cms');
    }
}
