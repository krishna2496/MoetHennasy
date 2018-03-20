<?php

use yii\db\Migration;

/**
 * Handles the creation of table `permissions`.
 */
class m180320_114322_create_permissions_table extends Migration
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
