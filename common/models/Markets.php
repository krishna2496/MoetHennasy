<?php

namespace common\models;

use Yii;
use common\models\MarketSegments;

class Markets extends BaseModel
{
    public static function tableName()
    {
        return 'markets';
    }

    public function rules()
    {
        return [
            [['market_segment_id', 'title'], 'required','on'=>['create','update']],
            [['market_segment_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t("app", "market_title"),
            'market_segment_id' => Yii::t("app", "market_segment_id"),
            
        ];
    }
    
    public function getMarketSegment(){
        return $this->hasOne(MarketSegments::className(), ['id' => 'market_segment_id']);
    }
    
    public function getUser(){
        return $this->hasOne(User::className(), ['market_id' => 'id']);
    }
    
}
