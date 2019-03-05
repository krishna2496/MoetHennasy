<?php

namespace common\models;

use Yii;

class Questions extends BaseModel
{
    public static function tableName()
    {
        return 'questions';
    }

    public function rules()
    {
        return [
            [['question', 'response_type'], 'required'],
            [['question', 'response_type'], 'string', 'max' => 80],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'question' => 'Question',
            'response_type' => 'Response Type',
        ];
    }
}
