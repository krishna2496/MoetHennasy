<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "questions".
 *
 * @property int $id
 * @property string $question
 * @property string $response_type
 */
class Questions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'questions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question', 'response_type'], 'required'],
            [['question', 'response_type'], 'string', 'max' => 80],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'question' => 'Question',
            'response_type' => 'Response Type',
        ];
    }
}
