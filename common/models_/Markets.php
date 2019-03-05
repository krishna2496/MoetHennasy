<?php

namespace common\models;

use Yii;
use common\models\MarketSegmentData;
use common\models\User;
use common\models\Catalogues;
use common\models\Stores;

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
            [['title','market_segment_id'], 'required','on'=>['create','update']],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['title'], 'string', 'max' => 100],
            [['title'], 'unique'],
            [['title'], 'trim']
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
    
    
    
    public function canDelete()
    { 
        $count = User::find()->andWhere(['market_id' => $this->id])->count();
       
        if($count > 0){
            $this->addError('title', "{$this->title} is assign to user");
            return false;
        }
        
        $count = Catalogues::find()->andWhere(['market_id' => $this->id])->count();
       
        if($count > 0){
            $this->addError('title', "{$this->title} is used in catalogues");
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
