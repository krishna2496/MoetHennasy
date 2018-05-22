<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\helpers\CommonHelper;
use common\repository\HelpCategoriesRepository;
use common\models\HelpsSearch;

class HelpsController extends BaseApiController
{
    public $modelClass = 'common\models\Helps';

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
        unset($actions['create']);
        unset($actions['list-stores']);
        return $actions;
    }
    
    public function actionIndex(){
        $data = array();
        $helpsRepository = new HelpCategoriesRepository();
        $returnData = $helpsRepository->listing($data);
        return $returnData;
    }
}
