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
        return 'role_permission';
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
    
    public function selectRolePermission()
    {
        $rows = RolePermission::find()->all();
        
        if(!empty($rows))
        {
            foreach($rows as $row)
            {
				$roleId[] = $row->role_id;
				$permissionId[] = $row->permission_id;
            }
          
			$data['roleId']=$roleId;
			$data['permissionId']=$permissionId;
      
        	return $data;
        }
        else
        {
             return 0;
        }
    }
    
    public function insertRolePermissions($data)
    {
		$connection = \Yii::$app->db;
		$transaction = $connection->beginTransaction();
              
		try
		{
			//All details DELETE FROM role_permission
			RolePermission::deleteAll();

			$model = new RolePermission;

			foreach ($data as $value) {
				$values = explode (",", $value);

				$model = new RolePermission;
				$model->role_id = $values[0];
				$model->permission_id = $values[1];
				$model->save();
			} 

			$transaction->commit();

		} catch(Exception $e) {
			$transaction->rollback();
		}

        return true;
}
}
