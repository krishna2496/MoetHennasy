<?php

namespace common\models;

use Yii;
use yii\db\Query;
use common\repository\PermissionRepository;
class ParentRolePermission extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'parent_roles_permissions';
    }

    public function rules()
    {
        return [
            [['parent_role_id', 'child_role_id'], 'required'],
            [['parent_role_id', 'child_role_id'], 'integer'],
        ];
    }

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
			ParentRolePermission::deleteAll();

			$model = new ParentRolePermission;
            if($data){
    			foreach ($data as $value) {
    				$values = explode (",", $value);

    				$model = new ParentRolePermission;
    				$model->parent_role_id = isset($values[0]) ? $values[0] : 0;
    				$model->child_role_id = isset($values[1]) ? $values[1] : 0;
    				$model->save();
    			}
            }

			$transaction->commit();

		} catch(Exception $e) {
			$transaction->rollback();
		}

        return true;
    }

    public function getAllParentPermission(){
        $permissionRepository = new PermissionRepository;
        $rolePermissionData = $permissionRepository->selectParentRolePermission();
        $roles = array();
        if($rolePermissionData['status']['success'] == 1) {
            if($rolePermissionData['data']['permission']) {
                foreach ($rolePermissionData['data']['permission'] as $key => $value) {
                    if($value['roleName']){
                        $role[$value['parent_role_id']][$value['roleName']['id']] = $value['roleName']['title'];
                    }
                }
            }
        }
        return $role;
    }

    public function getParentRole(){
        $permissionRepository = new PermissionRepository;
        $rolePermissionData = $permissionRepository->selectParentRolePermission();
        $roles = array();
        if($rolePermissionData['status']['success'] == 1) {
            if($rolePermissionData['data']['permission']) {
                foreach ($rolePermissionData['data']['permission'] as $key => $value) {
                    if($value['roleName']){
                        $role[$value['child_role_id']] = $value['parent_role_id'];
                    }
                }
            }
        }
        return $role;
    }

    public function hasChild($parentID){
        $data = array();
        $data['parent_role_id'] = $parentID;
        $permissionRepository = new PermissionRepository;
        $rolePermissionData = $permissionRepository->selectParentRolePermission($data);
        $roles = array();
        if($rolePermissionData['status']['success'] == 1) {
            if($rolePermissionData['data']['permission']) {
                return true;
            }
        }
        return false;
    }

    public function getRoleName(){
        return $this->hasOne(Role::className(), ['id' => 'child_role_id']);
    }
}
