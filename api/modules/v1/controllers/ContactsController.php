<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\helpers\CommonHelper;
use common\repository\MarketContactRepository;


class ContactsController extends BaseApiController
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
       
        return $actions;
    }
    
    public function actionIndex(){
        
        $data = array();
        $data['market_segment_id']= Yii::$app->request->get('marketSegmentId');
        $data['market_id']= Yii::$app->request->get('marketId');
        $repository = new MarketContactRepository();
        $returnData = $repository->listing($data);
        return $returnData;
    }
}
