<?php

namespace common\models;

use Yii;

class ProductTypes extends BaseModel
{

    public static function tableName()
    {
        return 'product_types';
    }

    public function rules()
    {
        return [
            [['title'],'required'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['title'], 'string', 'max' => 100],
            [['title'], 'unique'],
            [['title'], 'trim']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
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
        $count = Catalogues::find()->andWhere(['product_type_id' => $this->id])->count();

        if($count > 0){
            $this->addError('title', "{$this->title} is used in Catalogues");
            return false;
        }
        return true;
    }
}
