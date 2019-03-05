<?php

namespace common\models;

use Yii;

class Ratings extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ratings';
    }

    public function rules()
    {
        return [
            [[ 'type'], 'required'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['rating', 'type'], 'string', 'max' => 80],
            ['rating', 'unique']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rating' => 'Rating',
            'type' => 'Type',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }
}
