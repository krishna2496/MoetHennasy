<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use common\helpers\CommonHelper;
use common\repository\GlossaryRepository;
use yii\data\ArrayDataProvider;
use common\models\GlossarySearch;
use common\models\Glossary;

class GlossaryController extends BaseApiController
{
    public $modelClass = 'common\models\Glossary';

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
  
    public function actionIndex(){
        $data = array();
        $rulesRepository = new GlossaryRepository();
        $returnData = $rulesRepository->listing($data);
        return $returnData;
        
    }
   
}
