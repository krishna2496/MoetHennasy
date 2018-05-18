<?php

use yii\db\Migration;

/**
 * Handles the creation of table `configs`.
 */
class m180320_114855_create_configs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('configs', [
            'id' => $this->primaryKey(11)->unsigned(),
            'key' => $this->string(255)->defaultValue(null),
            'value' => $this->string(255)->defaultValue(null),
            'store_id' => $this->integer()->unsigned()->defaultValue(null),
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
        $this->dropTable('configs');
    }
}
