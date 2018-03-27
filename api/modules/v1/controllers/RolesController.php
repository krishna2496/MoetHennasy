<?php

namespace api\modules\v1\controllers;
use Yii;
use common\repository\RoleRepository;
use common\helpers\CommonHelper;
use yii\filters\AccessControl;

class RolesController extends BaseApiController
{
    public $modelClass = 'common\models\Roles';

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
                    'actions' => ['index'],
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
        return $actions;
    }

    public function actionIndex()
    {
        $permissionRepository = new RoleRepository;
        $returnData = $permissionRepository->listing();
        return $returnData;
    }
}


