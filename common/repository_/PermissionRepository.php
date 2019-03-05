<?php
namespace common\repository;
use common\models\PermissionSearch;
use common\models\Permission;
use common\models\RolePermission;
use common\models\ParentRolePermission;
use yii\web\NotFoundHttpException;
use Yii;

class PermissionRepository extends Repository
{
    public function listing($data)
    {
        $searchModel = new PermissionSearch();
        $permissions = $searchModel->search($data);
        $this->apiCode = 1;
        $data = array();
        $data['permissions'] = $permissions;
        $this->apiData = $data;
        return $this->response();
    }

    public function create($data)
    {
        $this->apiCode = 0;
        $model = new Permission();
        $model->permission_label = $data['permission_label'];
        $model->permission_title = $data['permission_title'];
        $model->parent_id = $data['parent_id'];
        if($model->validate()){
            if($model->save(false)){
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'created_successfully', [Yii::t('app', 'permission')]);
            }
        } else {
           $this->apiMessage = $model->errors;
        }
        return $this->response();
    }

    public function update($data)
    {
        $this->apiCode = 0;
        $model = Permission::find()->andWhere(['id'=>$data['id']])->one();
        if(isset($data['permission_label'])) {
        $model->permission_label = $data['permission_label'];
        }
        if(isset($data['permission_title'])) {
        $model->permission_title = $data['permission_title'];
        }
        if(isset($data['parent_id'])) {
        $model->parent_id = $data['parent_id'];
        }
        if($model->validate()){
            if($model->save(false)){
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'updated_successfully', [Yii::t('app', 'permission')]);
            }
        } else {
           $this->apiMessage = $model->errors;
        }
        return $this->response();
    }

    public function delete($data)
    {
        $this->apiCode = 0;
        $model = Permission::find()->andWhere(['id'=>$data['id']])->one();
        if($model){
            if($model->delete()){
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'deleted_successfully', [Yii::t('app', 'permission')]);
            } else {
                $this->apiMessage = Yii::t('app', 'Something went wrong.');
            }
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
        return $this->response();
    }

    public function insertRole($data)
    {
        $this->apiCode = 0;
        $success = RolePermission::insertRolePermissions($data);
        if($success == true){
            $this->apiCode = 1;
            $this->apiMessage = Yii::t('app', 'updated_successfully', [Yii::t('app', 'role_permission')]);
        } else {
            $this->apiMessage = Yii::t('app', 'Something went wrong.');
        }
        return $this->response();
    }

    public function selectRolePermission($data = array())
    {
        $this->apiCode = 1;
        $query = RolePermission::find()->with(['permission']);
        if(isset($data['role_id']) && $data['role_id']){
            $query->andWhere(['role_id'=>$data['role_id']]);
        }
        $rows = $query->asArray()->all();
        $returnData = array();
        foreach ($rows as $key => $value) {
            $temp = $value;
            if($value['permission']){
                $temp['permission_label'] = $value['permission']['permission_label'];
                $temp['permission_title'] = $value['permission']['permission_title'];
            }
            unset($temp['permission']);
            $returnData[] = $temp;
        }
        $data = array();
        $data['permission'] = $returnData;
        $this->apiData = $data;
        return $this->response();
    }

    public function selectParentRolePermission($data = array())
    {
        $this->apiCode = 1;
        $query = ParentRolePermission::find()->with(['roleName']);
        if(isset($data['parent_role_id']) && $data['parent_role_id']){
            $query->andWhere(['parent_role_id'=>$data['parent_role_id']]);
        }
        $rows = $query->asArray()->all();
        $data = array();
        $data['permission'] = $rows;
        $this->apiData = $data;
        return $this->response();
    }

    public function insertParentRolePermission($data)
    {
        $this->apiCode = 0;
        $success = ParentRolePermission::insertRolePermissions($data);
        if($success == true){
            $this->apiCode = 1;
            $this->apiMessage = Yii::t('app', 'updated_successfully', [Yii::t('app', 'role_permission')]);
        } else {
            $this->apiMessage = Yii::t('app', 'Something went wrong.');
        }
        return $this->response();
    }

    public function checkLoginPermission($permission = '', $roleId)
    {
        if($permission){
            $row = RolePermission::find()->joinWith(['permission'])->andWhere(['permissions.permission_label'=>$permission])->andWhere(['role_permissions.role_id'=>$roleId])->asArray()->one();
            if($row){
                return true;    
            }
            return false;
        }
        return true;    
    }
}