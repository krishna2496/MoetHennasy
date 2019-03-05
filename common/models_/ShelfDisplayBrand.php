<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "shelf_display_brand".
 *
 * @property int $id
 * @property int $shelf_display_id
 * @property int $brand_id
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class ShelfDisplayBrand extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shelf_display_brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shelf_display_id', 'brand_id', 'created_by', 'updated_by', 'deleted_by', 'created_at', 'updated_at', 'deleted_at'], 'required'],
            [['shelf_display_id', 'brand_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shelf_display_id' => 'Shelf Display ID',
            'brand_id' => 'Brand ID',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }
}
