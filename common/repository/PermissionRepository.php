<?php
namespace common\repository;
use common\models\PermissionSearch;
use common\models\Permission;
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
        if($data['permission_label']){
            $model->permission_label = $data['permission_label'];
        }
        if($data['permission_title']){
            $model->permission_title = $data['permission_title'];
        }
        if($data['parent_id']){
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
}