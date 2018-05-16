<?php

namespace common\models;

use Yii;


class Helps extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'helps';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'question', 'answer'], 'required'],
            [['category_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['question', 'answer'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category ID',
            'question' => 'Question',
            'answer' => 'Answer',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }
}
