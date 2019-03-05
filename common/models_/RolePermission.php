<?php

namespace common\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "role_permission".
 *
 * @property integer $role_id
 * @property integer $permission_id
 */
class RolePermission extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'role_permissions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_id', 'permission_id'], 'required'],
            [['role_id', 'permission_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'role_id' => 'Role ID',
            'permission_id' => 'Permission ID',
        ];
    }
    
    public static function insertRolePermissions($data)
    {
		$connection = \Yii::$app->db;
		$transaction = $connection->beginTransaction();
              
		try
		{
			//All details DELETE FROM role_permission
			RolePermission::deleteAll();

			$model = new RolePermission;
            if($data){
    			foreach ($data as $value) {
    				$values = explode (",", $value);

    				$model = new RolePermission;
    				$model->role_id = isset($values[0]) ? $values[0] : 0;
    				$model->permission_id = isset($values[1]) ? $values[1] : 0;
    				$model->save();
    			}
            }

			$transaction->commit();

		} catch(Exception $e) {
			$transaction->rollback();
		}

        return true;
    }

    public function getPermission(){
        return $this->hasOne(Permission::className(), ['id' => 'permission_id']);
    }
}
