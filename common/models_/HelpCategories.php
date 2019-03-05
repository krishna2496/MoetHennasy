<?php

namespace common\models;

use Yii;
use common\models\Helps;

class HelpCategories extends BaseModel
{
    public static function tableName()
    {
        return 'help_categories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['title'], 'string', 'max' => 100],
            [['title'], 'unique'],
            [['title'], 'trim']
        ];
    }

    /**
     * @inheritdoc
     */
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

    public function getQuestions(){
        return $this->hasMany(Helps::className(), ['category_id' => 'id']);
    }
    
    public function canDelete()
    { 
        $count = Helps::find()->andWhere(['category_id' => $this->id])->count();
        if($count > 0){
            $this->addError('title', "{$this->title} is used in help questions");
            return false;
        }
        return true;
    }
}
