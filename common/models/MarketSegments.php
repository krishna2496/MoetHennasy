<?php

namespace common\models;

use Yii;
use common\models\MarketSegmentData;
use common\models\Stores;

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
            [['title'], 'string', 'max' => 100],
            [['title'], 'unique'],
            [['title','description'], 'trim']
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
    
    public function getMarketSegmentData(){
        return $this->hasMany(MarketSegmentData::className(), ['market_segment_id' => 'id']);
    }
    public function getMarketSegmentContacts(){
        return $this->hasOne(MarketContacts::className(), ['market_segment_id' => 'id']);
    }
    public function getMarketRules(){
        return $this->hasMany(MarketRules::className(), ['market_segment_id' => 'id']);
    }
    
    public function canDelete()
    { 
        $count = MarketSegmentData::find()->andWhere(['market_segment_id' => $this->id])->count();
       
        if($count > 0){
            $this->addError('title', "{$this->title} is used in market");
            return false;
        }
        
        $count = Stores::find()->andWhere(['market_segment_id' => $this->id])->count();
       
        if($count > 0){
            $this->addError('title', "{$this->title} is used in store");
            return false;
        }
        
        $count = Stores::find()->andWhere(['market_id' => $this->id])->count();
        if($count > 0){
            $this->addError('title', "{$this->title} is used in Stores");
            return false;
        }
        return true;
    }
}
