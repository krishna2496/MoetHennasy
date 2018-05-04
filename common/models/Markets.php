<?php

namespace common\models;

use Yii;
use common\models\MarketSegmentData;

class Markets extends BaseModel
{
    public $market_segment_id;
    public static function tableName()
    {
        return 'markets';
    }

    public function rules()
    {
        return [
            [['title'], 'required','on'=>['create','update']],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
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
    
    
    public function getMarketSegmentData(){
        return $this->hasMany(MarketSegmentData::className(), ['market_id' => 'id']);
    }
    
    public function getUser(){
        return $this->hasOne(User::className(), ['market_id' => 'id']);
    }
    
}
