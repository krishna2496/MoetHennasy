<?php

use yii\db\Migration;

/**
 * Handles the creation of table `market_segments`.
 */
class m180322_092405_create_market_segments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('market_segments', [
            'id' => $this->primaryKey(11)->unsigned(),
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
        $this->dropTable('market_segments');
    }
}
