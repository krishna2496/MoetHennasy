<?php

namespace common\models;

use Yii;

class Role extends BaseModel
{
    public static function tableName()
    {
        return 'roles';
    }

    public function rules()
    {
        return [
            [['title'], 'required'],
            ['title', 'unique'],
            [['title'], 'string', 'max' => 255],
            [['title'], 'filter', 'filter' => 'trim', 'skipOnArray' => true],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Role Name',
        ];
    }
}
