<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "shelf_display".
 *
 * @property int $id
 * @property int $config_id
 * @property string $display_name
 * @property int $no_of_shelves
 * @property int $height_of_shelves
 * @property int $width_of_shelves
 * @property int $depth_of_shelves
 * @property int $brand_thumb_id
 * @property string $shelf_config
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class ShelfDisplay extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shelf_display';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['config_id', 'display_name', 'no_of_shelves', 'height_of_shelves', 'width_of_shelves', 'depth_of_shelves', 'brand_thumb_id', 'shelf_config', 'created_by', 'updated_by', 'deleted_by', 'created_at', 'updated_at', 'deleted_at'], 'required'],
            [['config_id', 'height_of_shelves', 'width_of_shelves', 'depth_of_shelves', 'brand_thumb_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['shelf_config'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['display_name'], 'string', 'max' => 100],
            [['no_of_shelves'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'config_id' => 'Config ID',
            'display_name' => 'Display Name',
            'no_of_shelves' => 'No Of Shelves',
            'height_of_shelves' => 'Height Of Shelves',
            'width_of_shelves' => 'Width Of Shelves',
            'depth_of_shelves' => 'Depth Of Shelves',
            'brand_thumb_id' => 'Brand Thumb ID',
            'shelf_config' => 'Shelf Config',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }
}
