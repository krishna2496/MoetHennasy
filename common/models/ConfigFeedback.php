<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "config_feedback".
 *
 * @property int $id
 * @property int $config_id
 * @property int $que_id
 * @property int $answer
 * @property int $reviewed_by
 * @property int $reviewed_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class ConfigFeedback extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'config_feedback';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['config_id', 'que_id', 'answer', 'reviewed_by', 'reviewed_at', 'created_by', 'updated_by', 'deleted_by', 'created_at', 'updated_at', 'deleted_at'], 'required'],
            [['config_id', 'que_id', 'reviewed_by', 'reviewed_at', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['answer'], 'string', 'max' => 4],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'config_id' => 'Config ID',
            'que_id' => 'Que ID',
            'answer' => 'Answer',
            'reviewed_by' => 'Reviewed By',
            'reviewed_at' => 'Reviewed At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }
}
