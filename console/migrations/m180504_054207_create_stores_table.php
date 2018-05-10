<?php

use yii\db\Migration;

/**
 * Handles the creation of table `stores`.
 */
class m180504_054207_create_stores_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('stores', [
            'id' => $this->primaryKey(11)->unsigned(),
            'name' => $this->string(100)->notNull(),
            'photo' => $this->string(255)->defaultValue(null),
            'market_id' => $this->integer()->unsigned()->defaultValue(null),
            'market_segment_id' => $this->integer()->unsigned()->defaultValue(null),
            'address1' => $this->text()->defaultValue(null),
            'address2' => $this->text()->defaultValue(null),
            'country_id' => $this->integer()->unsigned()->defaultValue(null),
            'city_id' => $this->integer()->unsigned()->defaultValue(null),
            'latitude' => $this->double()->defaultValue(null),
            'longitude' => $this->double()->defaultValue(null),
            'comment' => $this->text()->defaultValue(null),
            'assign_to' => $this->integer()->unsigned()->defaultValue(null),
            'store_manager_first_name' => $this->string(255)->defaultValue(null),
            'store_manager_last_name' => $this->string(255)->defaultValue(null),
            'store_manager_email' => $this->string(255)->defaultValue(null),
            'store_manager_phone_code' => $this->string(10)->defaultValue(null),
            'store_manager_phone_number' => $this->string(30)->defaultValue(null),
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
