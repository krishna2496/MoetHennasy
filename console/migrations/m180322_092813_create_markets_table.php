<?php

use yii\db\Migration;

/**
 * Handles the creation of table `markets`.
 */
class m180322_092813_create_markets_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('markets', [
            'id' => $this->primaryKey(11)->unsigned(),
            'title' => $this->string(255)->defaultValue(null),
            'market_segment_id' => $this->integer()->unsigned()->defaultValue(null),
            'market_administrator_id' => $this->integer()->unsigned()->defaultValue(null)->comment('Forignkey to user'),
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
        $this->dropTable('markets');
    }
}
