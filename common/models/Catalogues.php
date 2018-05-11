<?php

namespace common\models;

use Yii;

class Catalogues extends BaseModel
{
    public $catalogueImage;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalogues';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sku','ean','brand_id','product_category_id','product_sub_category_id','market_id','width','height','length','scale','manufacturer','box_only','market_share','price','top_shelf'],'required'],
            [['short_description'], 'string'],
            [['brand_id', 'product_category_id', 'product_sub_category_id', 'product_type_id', 'market_id', 'market_share', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['width', 'height', 'length', 'scale', 'price'], 'number'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['sku', 'ean', 'short_name', 'long_name', 'manufacturer'], 'string', 'max' => 255],
            [['box_only', 'top_shelf'], 'string', 'max' => 1],
            [['catalogueImage'], 'file','extensions'=>'jpg,png,jpeg','on' => ['create','update']],
             [['catalogueImage'], 'required','on' => ['create']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sku' => 'Sku',
            'ean' => 'Ean',
            'image' => 'Image',
            'short_name' => 'Short Product Name',
            'long_name' => 'Long Product Name',
            'short_description' => 'Short Description',
            'brand_id' => 'Brand Name',
            'product_category_id' => 'Product Category',
            'product_sub_category_id' => 'Product Sub Category',
            'product_type_id' => 'Product Type',
            'market_id' => 'Market',
            'width' => 'Size Width',
            'height' => 'Size Height',
            'length' => 'Size Length',
            'scale' => 'Size Scale',
            'manufacturer' => 'Manufacturer',
            'box_only' => 'Gift Box',
            'market_share' => 'Market Share',
            'price' => 'Price',
            'top_shelf' => 'Top Shelf',
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
}
