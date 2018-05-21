<?php

namespace backend\controllers;

use Yii;
use common\models\Configs;
use common\models\ConfigsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Stores;
use common\models\StoresReview;
use yii\filters\AccessControl;
use common\repository\ConfigsRepository;
use common\repository\StoreRatingsRepository;
/**
 * ConfigsController implements the CRUD actions for Configs model.
 */
class ConfigsController extends BaseBackendController
{
    /**
     * @inheritdoc
     */
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
                        'actions' => ['index','create','update','view','delete','create-rating','review'],
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

    /**
     * Lists all Configs models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        $store=Stores::findOne($id);
        if($store){
        $ratingModel =new StoresReview;
        $filters = Yii::$app->request->queryParams;
        if(!isset($filters['limit'])){
            $filters['limit'] = Yii::$app->params['pageSize'];
        }       
        $filters['store_id']=$id;
        $searchModel = new ConfigsSearch();
        $dataProvider = $searchModel->search($filters);
        $dataProvider->pagination->pageSize = $filters['limit'];
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filters'=>$filters,
            'id' => $id,
            'ratingModel' => $ratingModel,
        ]);
        }else{
            throw new NotFoundHttpException('The requested page does not exist.'); 
        }   
    }

    public function actionView($id, $storeId = '')
    {
        parent::userActivity('view_configs',$description='');
        return $this->render('view', [
            'model' => $this->findModel($id),
            'storeId' => $storeId
          
        ]);
    }

    public function actionCreate($id)
    {
        $model = new Configs();
        if(Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post('Configs');
            $data['store_id']=Yii::$app->request->post('store_id');
            $repository = new ConfigsRepository;
            $returnData = $repository->createConfigs($data);
            if($returnData['status']['success'] == 1)
            {   parent::userActivity('create_configs',$description='');
                Yii::$app->session->setFlash('success', $returnData['status']['message']);
                return $this->redirect(['configs/index/'.$id]);
            } else {
                Yii::$app->session->setFlash('danger', $returnData['status']['message']);
            }
        }
        
        return $this->render('create', [
            'model' => $model,
            'id'=>$id
        ]);
    }

    public function actionUpdate($id, $storeId = '')
    {
        $model = $this->findModel($id);
        
        if(Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            
            $data = Yii::$app->request->post('Configs');
            $repository = new ConfigsRepository;
            $data['id'] = $id;
            $returnData = $repository->updateConfigs($data);
           
            if($returnData['status']['success'] == 1)
            {   
                parent::userActivity('update_configs',$description='');
                Yii::$app->session->setFlash('success', $returnData['status']['message']);
                return $this->redirect(['configs/index/'.$storeId]);
            } else {
                Yii::$app->session->setFlash('danger', $returnData['status']['message']);
            }
        }
      
        return $this->render('update', [
            'model' => $model,
            'id'=>$storeId
        ]);
    }
    public function actionReview($id, $storeId = '')
    {
        $model = $this->findReviewModel($id);
        if($model == 0){
           
             $model = new StoresReview(); 
        }
        return $this->render('review', [
            'model' => $model,
            'store_id'=>$storeId,
            'config_id' => $id,
        ]);
    }

    public function actionDelete($id,$storeId = '')
    {  
        $this->findModel($id)->delete();
        parent::userActivity('delete_configs',$description='');
        Yii::$app->session->setFlash('success', Yii::t('app', 'deleted_successfully', [Yii::t('app', 'configs')]));
        return $this->redirect(['configs/index/'.$storeId]);
    }
    
    public function actionCreateRating(){
        echo '<pre>';
        print_r(Yii::$app->request->post());exit;
        if(Yii::$app->request->post()){
        $data=Yii::$app->request->post();
        echo '<pre>';
        print_r($data);exit;
        $data['reviews']= $data['StoresReview']['reviews'];
        $data['store_id']= $data['store_id'];
        $data['config_id'] = $data['config_id'];
        $rating = new StoreRatingsRepository();
        $returnData = $rating->createStarRating($data);
         if($returnData['status']['success'] == 1)
            {   parent::userActivity('create_configs',$description='');
                Yii::$app->session->setFlash('success', $returnData['status']['message']);
                return $this->redirect(['configs/index/'.$id]);
            } else {
                Yii::$app->session->setFlash('danger', $returnData['status']['message']);
            }
        }
    }

    protected function findModel($id)
    {
        if (($model = Configs::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    protected function findReviewModel($id)
    {
        if (($model = StoresReview::findOne(['config_id' => $id])) !== null) {
            return $model;
        }else{
           return 0;
        }
    }
}
