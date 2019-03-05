<?php

namespace common\models;

use Yii;

class Configs extends BaseModel
{

    public static function tableName()
    {
        return 'configs';
    }

    public function rules()
    {
        return [
            [['store_id','value'], 'required'],
            [['store_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['key', 'value'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'key' => 'Key',
            'value' => 'Name',
            'store_id' => 'Store ID',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }
}
