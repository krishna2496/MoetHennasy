<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "market_segments".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class MarketSegments extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'market_segments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['title','description'],'required','on' => ['create','update']],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title' => Yii::t("app", "market_segment_title"),
            'description' => Yii::t("app", "market_segment_description"), 
        ];
    }
}
