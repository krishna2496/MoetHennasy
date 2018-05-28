<?php

namespace common\models;

use Yii;

class MarketContacts extends BaseModel
{
    public static function tableName()
    {
        return 'market_contacts';
    }

    public function rules()
    {
        return [
            [['market_segment_id', 'market_id', 'address', 'phone', 'email'], 'required'],
            [['market_segment_id', 'market_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['address'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['phone'], 'string', 'max' => 20],
            [['email'], 'string', 'max' => 50],
            [['email'], 'email'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'market_segment_id' => 'Market Segment',
            'market_id' => 'Market',
            'address' => 'Address',
            'phone' => 'Phone',
            'email' => 'Email',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }
    
    public function getMarket(){
        return $this->hasOne(Markets::className(), ['id' => 'market_id']);
    }
    
    public function getMarketSegment(){
        return $this->hasOne(MarketSegments::className(), ['id' => 'market_segment_id']);
    }
}
