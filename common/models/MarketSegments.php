<?php

namespace common\models;

use Yii;

class MarketSegments extends BaseModel
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
