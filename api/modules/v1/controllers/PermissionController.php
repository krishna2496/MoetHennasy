<?php

namespace api\modules\v1\controllers;
use Yii;
use common\repository\PermissionRepository;
use common\helpers\CommonHelper;

class PermissionController extends BaseApiController
{
    public $modelClass = 'common\models\Permission';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['create']);
        unset($actions['update']);
        return $actions;
    }

    public function actionIndex()
    {
        $data = Yii::$app->request->queryParams;
        $permissionRepository = new PermissionRepository;
        $returnData = $permissionRepository->listing($data);
        return $returnData;
    }

    public function actionCreate()
    {
        $data = array();
        $data['permission_label'] = Yii::$app->request->post('permission_label');
        $data['permission_title'] = Yii::$app->request->post('permission_title');
        $data['parent_id'] = Yii::$app->request->post('parent_id');
        $permissionRepository = new PermissionRepository;
        $returnData = $permissionRepository->create($data);
        return $returnData;
    }

    public function actionUpdatePermission()
    {
        $data = array();
        $data['id'] = Yii::$app->request->post('id');
        $data['permission_label'] = Yii::$app->request->post('permission_label');
        $data['permission_title'] = Yii::$app->request->post('permission_title');
        $data['parent_id'] = Yii::$app->request->post('parent_id');
        $permissionRepository = new PermissionRepository;
        $returnData = $permissionRepository->update($data);
        return $returnData;
    }
}


