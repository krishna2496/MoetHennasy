<?php

namespace backend\controllers;

use Yii;
use common\models\Markets;
use common\models\MarketSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\repository\MarketRepository;
use common\repository\MarketSegmentsRepository;
use common\helpers\CommonHelper;
use common\models\MarketSegments;
use common\models\MarketSegmentData;

class MarketController extends BaseBackendController
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
        $searchModel = new MarketSearch();
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
    {
       $data=MarketSegmentData::find()->joinWith('marketSegment')->andWhere(['market_id'=>$id])->asArray()->all();
     
       $dataCount=count($data);
       $segment='';
       $i=0;
       foreach ($data as $key=>$value){
           $i++;
           if($i == $dataCount){
           $segment .=$value['marketSegment']['title'];
           }else{
           $segment .=$value['marketSegment']['title'].',';
           }
       }
       parent::userActivity('view_market',$description='');
        return $this->render('view', [
            'model' => $this->findModel($id),
            'segment'=>$segment,
        ]);
    }

    public function actionCreate()
    {
        $model = new Markets();
        $marketSegment = array();
        $marketSegmentRepository = new MarketSegmentsRepository();
        $marketsSegmentData = $marketSegmentRepository->marketSegmentsList();
        if($marketsSegmentData['status']['success'] == 1){
            $marketSegment = CommonHelper::getDropdown($marketsSegmentData['data']['market_segments'], ['id', 'title']);
        }
        $model->scenario = 'create';
          if(Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post('Markets');
         
            $marketRepository = new MarketRepository;
            $returnData = $marketRepository->createMarket($data);
            if($returnData['status']['success'] == 1)
            {  
                parent::userActivity('create_markets',$description='');
                Yii::$app->session->setFlash('success', $returnData['status']['message']);
                return $this->redirect(['index']);
            } else {
                 Yii::$app->session->setFlash('danger', $returnData['status']['message']);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'marketSegmentList' =>$marketSegment,
        ]);
    }
    
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
       
        $marketSegment = array();
        $marketSegmentRepository = new MarketSegmentsRepository();
        $marketsSegmentData = $marketSegmentRepository->marketSegmentsList();
        if($marketsSegmentData['status']['success'] == 1){
            $marketSegment = CommonHelper::getDropdown($marketsSegmentData['data']['market_segments'], ['id', 'title']);
        }
        
        $marketSegmentId=MarketSegmentData::find()->andWhere(['market_id'=>$id])->asArray()->all();
        $segmentIdArry = array();
        foreach ($marketSegmentId as $data){
            $segmentIdArry[]=$data['market_segment_id'];
        }

        $model->market_segment_id = $segmentIdArry;
        $model->scenario = 'update';
        if(Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post('Markets');
            $data['id'] = $id;
            $marketRepository = new MarketRepository;
           
            $returnData = $marketRepository->updateMarket($data);
            if($returnData['status']['success'] == 1)
            {
                parent::userActivity('update_markets',$description='');
                Yii::$app->session->setFlash('success', $returnData['status']['message']);
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('danger', $returnData['status']['message']);
            }
        }
        return $this->render('update', [
            'model' => $model,
            'marketSegmentList' =>$marketSegment,
        ]);
    }

    public function actionDelete($id)
    {  
        $model = $this->findModel($id);
        if($model->delete()){
            parent::userActivity('delete_markets',$description='');
            Yii::$app->session->setFlash('success', Yii::t('app', 'Market deleted successfully'));
        }else{
            Yii::$app->session->setFlash('danger', $model['errors']['title'][0]);
        }
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Markets::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
