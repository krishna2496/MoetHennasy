<?php

namespace common\models;

use Yii;

class Markets extends BaseModel
{
    public static function tableName()
    {
        return 'markets';
    }

    public function rules()
    {
        return [
            [['market_segment_id', 'description', 'title'], 'required'],
            [['market_segment_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'market_segment_id' => 'Market Segment ID',
            'description' => 'Description',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }
}
