<?php

namespace common\models;

use Yii;
use common\models\Catalogues;

class MarketBrandsVerietals extends BaseModel
{
    public static function tableName()
    {
        return 'market_brands_verietals';
    }

    public function rules()
    {
        return [
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['name'], 'required'],
            [['name'], 'unique'],
            [['name'], 'trim']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'market_id' => 'Market id',
            'brand_id' => 'Created By',
            'verietal_id' => 'Updated By',
            'category_id' => 'Deleted By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'shares'=>'Shares',
        ];
    }
//    public function getProductVeriatal(){
//        return $this->hasOne(ProductVarietal::className(), ['id' => 'verietal_id']);
//    }
    
    public function getProductVeriatal(){
        return $this->hasMany(Catalogues::className(), ['product_variental' => 'verietal_id']);
    }
    
    public function canDelete()
    { 
        $count = Catalogues::find()->andWhere(['id' => $this->id])->count();
       
        if($count > 0){
            $this->addError('title', "{$this->name} is used in Catalogues");
            return false;
        }
        return true;
    }
}
