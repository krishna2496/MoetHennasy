<?php

namespace common\models;

use Yii;
use common\models\Catalogues;

class MarketCategoryProduct extends BaseModel
{
    public static function tableName()
    {
        return 'market_category_product';
    }

    public function rules()
    {
        return [
          
            [['product_id', 'market_id', 'category_id', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
           
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'market_id' => 'Market Id',
            'shelf_thumb' => 'Shelf Thumb',
            'star_ratings' => 'Star Ratings',
            'is_verified' => 'Is Verified',
            'is_autofill' => 'Is Autofill',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }
    public function getProduct(){
        return $this->hasOne(Catalogues::className(), ['id' => 'product_id']);
    } 
    
}
