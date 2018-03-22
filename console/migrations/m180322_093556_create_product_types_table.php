<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_types`.
 */
class m180322_093556_create_product_types_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('product_types', [
            'id' => $this->primaryKey(11)->unsigned(),
            'title' => $this->string(255)->defaultValue(null),
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
        $this->dropTable('product_types');
    }
}
