<?php

namespace backend\controllers;

use Yii;
use backend\models\Permission;
use backend\models\PermissionSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\helpers\CommonHelper;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

class PermissionsController extends BaseBackendController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                   'class' => \backend\components\AccessRule::className(),
                ],
                'rules' => [
                    [
                        'actions' => ['index','create','update','delete'],
                        'allow' => true,
                        'roles' => ['&'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $data = Yii::$app->request->queryParams;
        $permissionRepository = new PermissionRepository;
        $returnData = $permissionRepository->listing($data);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'userStatuses' => $userStatuses,
        ]);
    }

    protected function findModel($id)
    {
        //Type Casting
        $id = intval($id);
        
        if (($model = Permission::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'page_does_not_exist'));
        }
    }
}
