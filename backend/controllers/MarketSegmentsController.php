<?php

namespace backend\controllers;

use Yii;
use common\models\MarketSegments;
use common\models\MarketSegmentsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\repository\MarketSegmentsRepository;

class MarketSegmentsController extends BaseBackendController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => \common\components\AccessRule::className(),
                ],
                'rules' => [
                    [
                        'actions' => ['index','create','update','view','delete'],
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
        $searchModel = new MarketSegmentsSearch();
        $filters = Yii::$app->request->queryParams;
        if(!isset($filters['limit'])){
            $filters['limit'] = Yii::$app->params['pageSize'];
        }
        
        $dataProvider = $searchModel->search($filters);
        $dataProvider->pagination->pageSize = $filters['limit'];
       
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filters' => $filters,
        ]);
    }

    public function actionView($id)
    {  parent::userActivity('view_market_segment',$description='');
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new MarketSegments();
        $model->scenario = 'create';
          if(Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
           
            $data = Yii::$app->request->post('MarketSegments');
       
            $marketSegmentsRepository = new MarketSegmentsRepository;
            $returnData = $marketSegmentsRepository->createMarketSegment($data);
            if($returnData['status']['success'] == 1)
            {   parent::userActivity('create_market_segment',$description='');
                Yii::$app->session->setFlash('success', $returnData['status']['message']);
                return $this->redirect(['index']);
            } else {
                 Yii::$app->session->setFlash('danger', $returnData['status']['message']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';
        if(Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post('MarketSegments');
            $data['id'] = $id;
            $marketSegmentsRepository = new MarketSegmentsRepository;
            $returnData = $marketSegmentsRepository->updateMarketSegment($data);
            if($returnData['status']['success'] == 1)
            {   parent::userActivity('update_market_segment',$description='');
                Yii::$app->session->setFlash('success', $returnData['status']['message']);
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('danger', $returnData['status']['message']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if($model->delete()){
            parent::userActivity('delete_market_segment',$description='');
            Yii::$app->session->setFlash('success', Yii::t('app', 'Market segment deleted successfully'));
        }else{
            Yii::$app->session->setFlash('danger', $model['errors']['title'][0]);
        }
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = MarketSegments::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
