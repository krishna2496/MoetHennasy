<?php

use yii\db\Migration;

/**
 * Handles the creation of table `catalogues`.
 */
class m180322_093859_create_catalogues_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('catalogues', [
            'id' => $this->primaryKey(11)->unsigned(),
            'sku' => $this->string(255)->defaultValue(null),
            'ean' => $this->string(255)->defaultValue(null),
            'image' => $this->string(255)->defaultValue(null),
            'short_name' => $this->string(255)->defaultValue(null),
            'long_name' => $this->string(255)->defaultValue(null),
            'short_description' => $this->text()->defaultValue(null),
            'brand_id' => $this->integer()->unsigned()->defaultValue(null)->comment('reference to brand table'),
            'product_category_id' => $this->integer()->unsigned()->defaultValue(null)->comment('reference to product category'),
            'product_sub_category_id' => $this->integer()->unsigned()->defaultValue(null)->comment('reference to product category'),
            'product_type_id' => $this->integer()->unsigned()->defaultValue(null)->comment('reference to product type'),
            'market_id' => $this->integer()->unsigned()->defaultValue(null)->comment('reference to market table'),
            'width' => $this->decimal(5,2)->defaultValue(null),
            'height' => $this->decimal(5,2)->defaultValue(null),
            'length' => $this->decimal(5,2)->defaultValue(null),
            'scale' => $this->decimal(5,2)->defaultValue(null),
            'manufacturer' => $this->string(255)->defaultValue(null),
            'box_only' => $this->tinyInteger(1)->defaultValue(null)->comment('0:no, 1:yes'),
            'market_share' => $this->integer()->unsigned()->defaultValue(null),
            'price' => $this->decimal(8,2)->defaultValue(null),
            'top_shelf' => $this->tinyInteger(1)->defaultValue(null)->comment('0:no, 1:yes'),
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
        $this->dropTable('catalogues');
    }
}
