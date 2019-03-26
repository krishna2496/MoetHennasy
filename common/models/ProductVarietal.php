<?php

namespace common\models;

use Yii;
use common\models\Catalogues;

class ProductVarietal extends BaseModel
{
    public static function tableName()
    {
        return 'product_varietal';
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
            'name' => 'Name',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }
    
    public function canDelete()
    {
        $count = Catalogues::find()->andWhere(['product_variental' => $this->id])->count();
        
        
        if($count > 0){
            $this->addError('title', "{$this->name} is used in Catalogues");
            return false;
        }
        return true;
    }
    public function getMarketBrandsVerietals(){
        return $this->hasOne(MarketBrandsVerietals::className(), ['verietal_id' => 'id']);
    }
}
