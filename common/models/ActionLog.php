<?php

namespace common\models;

use Yii;

class ActionLog extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'action_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['action_type', 'date', 'time', 'description', 'user'], 'required'],
            [['date', 'time', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['description'], 'string'],
            [['user', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['action_type'], 'string', 'max' => 110],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'action_type' => 'Action Type',
            'date' => 'Date',
            'time' => 'Time',
            'description' => 'Description',
            'user' => 'User',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }
}
