<?php

use yii\db\Migration;

/**
 * Handles the creation of table `configs`.
 */
class m180320_114855_create_ratings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('ratings', [
            'id' => $this->primaryKey(11)->unsigned(),
            'rating' => $this->string(80)->defaultValue(null),
            'type' => $this->string(80)->defaultValue(null),
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
