<?php

namespace common\models;

use Yii;

class Stores extends BaseModel
{
    public $storeImage;
    public static function tableName()
    {
        return 'stores';
    }

    public function rules()
    {
        return [
            [['name','market_id','grading', 'market_segment_id', 'country_id', 'city_id', 'province_id', 'assign_to','address1','store_manager_first_name', 'store_manager_last_name', 'store_manager_email','store_manager_phone_number','store_manager_phone_code'], 'required'],
            [['market_id', 'market_segment_id', 'country_id', 'city_id', 'assign_to', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['address1', 'address2', 'comment'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['name','store_manager_first_name', 'store_manager_last_name', 'store_manager_email'], 'string', 'max' => 100],
            [['photo'], 'string', 'max' => 255],
            [['store_manager_phone_number'], 'string','min' =>6,'max' =>15],
            [['latitude', 'longitude'], 'double'],
            [['store_manager_phone_code'], 'number','max'=>99],
            [['storeImage'], 'file','extensions'=>'jpg,png,jpeg'],
            [['store_manager_email'], 'email'],
            [['store_manager_email'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t("app", "store_name"),
            'photo' => Yii::t("app", "store_photo"),
            'market_id' => Yii::t("app", "store_market_id"),
            'market_segment_id' => Yii::t("app", "store_market_segment_id"),
            'address1' => Yii::t("app", "store_address1"),
            'address2' => Yii::t("app", "store_address2"),
            'country_id' => Yii::t("app", "store_country_id"),
            'city_id' => Yii::t("app", "store_city_id"),
            'province_id' => Yii::t("app", "store_province_id"),
            'latitude' => Yii::t("app", "store_latitude"),
            'longitude' => Yii::t("app", "store_longitude"),
            'comment' => Yii::t("app", "store_comment"),
            'assign_to' => Yii::t("app", "store_assign_to"),
            'store_manager_first_name' => Yii::t("app","store_manager_first_name"),
            'store_manager_last_name' => Yii::t("app","store_manager_last_name"),
            'store_manager_email' => Yii::t("app","store_manager_email"),
            'store_manager_phone_code' => Yii::t("app","store_manager_phone_code"),
            'store_manager_phone_number' => Yii::t("app","store_manager_phone_number"),
        ];
    }
    
    public function getMarket(){
        return $this->hasOne(Markets::className(), ['id' => 'market_id']);
    }

    public function getMarketSegment(){
        return $this->hasOne(MarketSegments::className(), ['id' => 'market_segment_id']);
    }

    public function getUser(){
        return $this->hasOne(User::className(), ['id' => 'assign_to']);
    }

    public function getCountry(){
        return $this->hasOne(Countries::className(), ['id' => 'country_id']);
    }

    public function getCity(){
        return $this->hasOne(Cities::className(), ['id' => 'city_id']);
    }

    public function getProvince(){
        return $this->hasOne(Province::className(), ['id' => 'province_id']);
    }
}
