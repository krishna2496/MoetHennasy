<?php

namespace common\models;

use Yii;

class Rules extends BaseModel
{
    public static function tableName()
    {
        return 'rules';
    }

    public function rules()
    {
        return [
            [['type', 'product_fields', 'detail'], 'required'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['type', 'product_fields'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'product_fields' => 'Product Fields',
            'detail' => 'Detail',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }
}
