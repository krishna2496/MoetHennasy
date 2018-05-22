<?php

namespace common\models;

use Yii;

class StoresResponseType extends BaseModel
{
    
    public static function tableName()
    {
        return 'stores_response_type';
    }

   
    public function rules()
    {
        return [
            [['store_id', 'response_type_id'], 'required'],
            [['store_id', 'response_type_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'response_type_id' => 'Response Type ID',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }
}
