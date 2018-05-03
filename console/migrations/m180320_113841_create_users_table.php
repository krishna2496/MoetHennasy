<?php

use yii\db\Migration;

/**
 * Handles the creation of table `users`.
 */
class m180320_113841_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(11)->unsigned(),
            'username' => $this->string(100)->notNull()->unique(),
            'email' => $this->string(255)->notNull()->unique(),
            'first_name' => $this->string(100)->defaultValue(null),
            'last_name' => $this->string(100)->defaultValue(null),
            'auth_key' => $this->string(255)->defaultValue(null),
            'password_hash' => $this->string(255)->defaultValue(null),
            'password_reset_token' => $this->string(255)->unique()->defaultValue(null),
            'role_id' => $this->integer()->unsigned()->defaultValue(null),
            'parent_user_id' => $this->integer()->unsigned()->defaultValue(null),
            'status' => $this->tinyInteger(1)->defaultValue(1)->comment('0:inactive, 1:active'),
            'profile_photo' => $this->string(255)->defaultValue(null),
            'phone' => $this->string(30)->defaultValue(null),
            'device_type' => $this->tinyInteger(1)->defaultValue(null)->comment('1:ios, 2:android, 3:Web'),
            'device_token' => $this->string(255)->defaultValue(null),
            'latitude' => $this->string(30)->defaultValue(null),
            'longitude' => $this->string(30)->defaultValue(null),
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
        $this->dropTable('users');
    }
}
