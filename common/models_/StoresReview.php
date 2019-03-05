<?php

namespace common\models;

use Yii;

class StoresReview extends BaseModel
{
    
    public static function tableName()
    {
        return 'stores_review';
    }

    public function rules()
    {
        return [
            [['reviews'], 'required'],
            [['store_id', 'reviews', 'config_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'reviews' => 'Ratings',
            'config_id' => 'Config ID',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }
}
