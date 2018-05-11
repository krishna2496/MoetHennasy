<?php

namespace common\models;

use Yii;

class ProductCategories extends BaseModel
{
    public static function tableName()
    {
        return 'product_categories';
    }

    public function rules()
    {
        return [
            [['parent_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'required'],
            [['name'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'parent_id' => 'Parent ID',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    public function getParentCategory(){
        return $this->hasOne(ProductCategories::className(), ['id' => 'parent_id']);
    }
}
