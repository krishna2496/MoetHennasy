<?php

use yii\db\Migration;

/**
 * Handles the creation of table `role_permissions`.
 */
class m180320_114709_create_role_permissions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('role_permissions', [
            'id' => $this->primaryKey(11)->unsigned(),
            'role_id' => $this->integer()->unsigned()->defaultValue(null),
            'permission_id' => $this->integer()->unsigned()->defaultValue(null),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('role_permissions');
    }
}
