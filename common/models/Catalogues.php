<?php

namespace common\models;

use Yii;

class Catalogues extends BaseModel
{
    public $catalogueImage;
 
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
            [['brand_id','product_category_id','product_type_id','width','height','length','box_only', 'price','top_shelf', 'special_format', 'product_variental'],'required'],
            [['short_description'], 'string'],
            [['brand_id', 'product_category_id', 'product_sub_category_id', 'product_type_id', 'market_id' ,  'created_by', 'updated_by', 'deleted_by', 'product_variental', 'special_format'], 'integer'],
            [['width', 'height', 'length', 'scale'], 'number'],
            [['width', 'height', 'length'], 'number','min' => 5],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['sku', 'ean'], 'string', 'max' => 100],
            [['short_name', 'long_name', 'manufacturer'], 'string', 'max' => 255],
            [['box_only', 'top_shelf'], 'string', 'max' => 1],
            [['catalogueImage'], 'file','extensions'=>'jpg,png,jpeg','on' => ['create','update']],
            [['catalogueImage'], 'required','on' => ['create']],
            ['long_name', 'required', 'when' => function ($model) { return $model->short_name == ''; }, 'whenClient' => "function (attribute, value) { return $('#catalogues-short_name').val() == ''; }",'message'=>'Select either Long Product Name or Short Product Name.'],
            ['short_name', 'required', 'when' => function ($model) { return $model->long_name == ''; }, 'whenClient' => "function (attribute, value) { return $('#catalogues-long_name').val() == ''; }",'message'=>'Select either Long Product Name or Short Product Name.'],
            [['sku'], 'unique'],
            ['sku','match', 'pattern' => '/^[a-zA-Z0-9\-_]{0,50}$/', 'message' => 'Sku can only contain Alphabet and Numeric'],
            [['sku','ean','short_name','long_name','width','height','length','scale','manufacturer', 'price'],'trim'],
            [['market_share'],'integer','max' => 10,'min' => 1, 'message' => 'Market Share can only contain upto 11 digits'],
            [['price'],'number','max' => 999999, 'min' => 1,'message' => 'Price can only contain upto 6 digits'],
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
            'product_variental' => 'Product Varietal',
            'product_category_id' => 'Product Category',
            'product_sub_category_id' => 'Product Sub Category',
            'product_type_id' => 'Product Type',
            'market_id' => 'Market',
            'width' => 'Size Width',
            'height' => 'Size Height',
            'length' => 'Size Length (depth)',
            'scale' => 'Size Scale',
            'manufacturer' => 'Manufacturer',
            'box_only' => 'Gift Box',
            'market_share' => 'Market Share',
            'price' => 'Price',
            'top_shelf' => 'Top Shelf',
            'special_format' => 'Special Format',
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
     public function getBrand(){
        return $this->hasOne(Brands::className(), ['id' => 'brand_id']);
    }
    public function getProductCategory(){
        return $this->hasOne(ProductCategories::className(), ['id' => 'product_category_id']);
    }
    public function getProductSubCategory(){
        return $this->hasOne(ProductCategories::className(), ['id' => 'product_sub_category_id']);
    }
    
    public function getProductType(){
        return $this->hasOne(ProductTypes::className(), ['id' => 'product_type_id']);
    }
}
