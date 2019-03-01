<?php

namespace common\models;

use Yii;
use common\models\ProductCategories;

class MarketBrands extends BaseModel
{
    public static function tableName()
    {
        return 'market_brands';
    }

    public function rules()
    {
        return [
            [['market_id', 'brand_id', 'created_by', 'updated_by', 'deleted_by', 'created_at', 'updated_at', 'deleted_at', 'shares'], 'required'],
            [['market_id', 'brand_id', 'created_by', 'updated_by', 'deleted_by', 'shares'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'market_id' => 'Market ID',
            'brand_id' => 'Brand ID',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'shares' => 'Share'
        ];
    }
     public function getBrand(){
        return $this->hasOne(Brands::className(), ['id' => 'brand_id']);
    } 
    public function getCategory(){
        return $this->hasOne(ProductCategories::className(), ['id' => 'category_id']);
    }
}
