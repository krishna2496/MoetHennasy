<?php

namespace api\modules\v1\controllers;
use Yii;
use common\repository\PermissionRepository;
use common\helpers\CommonHelper;
use yii\filters\AccessControl;

class PermissionController extends BaseApiController
{
    public $modelClass = 'common\models\Permission';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'ruleConfig' => [
                'class' => \common\components\AccessRule::className(),
            ],
            'rules' => [
                [
                    'actions' => ['index','create','update','delete-permission', 'matrix','matrix-listing'],
                    'allow' => true,
                    'roles' => ['&'],
                ],
            ],
        ];
        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);
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

    public function actionUpdate($id)
    {
        $data = array();
        $data['id'] = $id;
        $data['permission_label'] = Yii::$app->request->post('permission_label');
        $data['permission_title'] = Yii::$app->request->post('permission_title');
        $data['parent_id'] = Yii::$app->request->post('parent_id');
        $permissionRepository = new PermissionRepository;
        $returnData = $permissionRepository->update($data);
        return $returnData;
    }

    public function actionDeletePermission()
    {
        $data = array();
        $data['id'] = Yii::$app->request->post('id');
        $permissionRepository = new PermissionRepository;
        $returnData = $permissionRepository->delete($data);
        return $returnData;
    }

    public function actionMatrix()
    {
        $data = array();
        $data = Yii::$app->request->post('allowedPermissions');
        $permissionRepository = new PermissionRepository;
        $returnData = $permissionRepository->insertRole($data);
        return $returnData;
    }

    public function actionMatrixListing()
    {
        $permissionRepository = new PermissionRepository;
        $returnData = $permissionRepository->selectRolePermission();
        return $returnData;
    }
}


