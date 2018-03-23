<?php

use yii\db\Migration;

/**
 * Handles the creation of table `permissions`.
 */
class m180320_114321_create_permissions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('permissions', [
            'id' => $this->primaryKey(11)->unsigned(),
            'permission_label' => $this->string(255)->defaultValue(null),
            'permission_title' => $this->string(255)->defaultValue(null),
            'parent_id' => $this->integer()->unsigned()->defaultValue(null),
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
        $this->dropTable('permissions');
    }
}
