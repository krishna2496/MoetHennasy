<?php

namespace common\models;

use Yii;

class Cities extends BaseModel
{

    public static function tableName()
    {
        return 'cities';
    }

    public function rules()
    {
        return [
            [['country_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'country_id' => 'Country ID',
        ];
    }
}
