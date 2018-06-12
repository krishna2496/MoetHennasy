<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "store_configuration".
 *
 * @property int $id
 * @property int $store_id
 * @property string $config_name
 * @property string $shelf_thumb
 * @property int $star_ratings
 * @property int $is_verified
 * @property int $is_autofill
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class StoreConfiguration extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'store_configuration';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'config_name', 'shelf_thumb', 'star_ratings', 'is_verified', 'is_autofill', 'created_by', 'updated_by', 'deleted_by', 'created_at', 'updated_at', 'deleted_at'], 'required'],
            [['store_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['config_name'], 'string', 'max' => 100],
            [['shelf_thumb'], 'string', 'max' => 255],
            [['star_ratings', 'is_verified', 'is_autofill'], 'string', 'max' => 4],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'config_name' => 'Config Name',
            'shelf_thumb' => 'Shelf Thumb',
            'star_ratings' => 'Star Ratings',
            'is_verified' => 'Is Verified',
            'is_autofill' => 'Is Autofill',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }
}
