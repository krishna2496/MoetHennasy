<?php

namespace common\models;

use Yii;
use common\models\RolePermission;

class Permission extends BaseModel
{
    public static function tableName()
    {
        return 'permissions';
    }

    public function rules()
    {
        return [
            [['permission_label', 'permission_title'], 'required'],
            [['permission_title'], 'unique'],
            [['parent_id'], 'integer'],
            [['permission_label', 'permission_title'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t("app", "id"),
            'permission_label' => Yii::t("app", "permission_label"),
            'permission_title' => Yii::t("app", "permission_title"),
            'parent_id' => Yii::t("app", "permission_parent_id"),
        ];
    }
    
    public function getParentTitle()
    {
        return $this->hasOne(Permission::className(),['id'=>'parent_id']);
    }
}
