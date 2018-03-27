<?php
namespace common\repository;
use common\models\PermissionSearch;
use common\models\Permission;
use common\models\RolePermission;
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
        $model->permission_label = $data['permission_label'];
        $model->permission_title = $data['permission_title'];
        $model->parent_id = $data['parent_id'];
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

    public function selectRolePermission()
    {
        $this->apiCode = 1;
        $rows = RolePermission::find()->asArray()->all();
        $data = array();
        $data['permission'] = $rows;
        $this->apiData = $data;
        return $this->response();
    }
}