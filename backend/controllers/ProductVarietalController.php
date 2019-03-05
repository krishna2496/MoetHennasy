<?php

namespace backend\controllers;

use Yii;
use common\models\ProductVarietal;
use common\models\ProductVarietalSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\repository\ProductVarietalRepository;
use common\helpers\CommonHelper;

class ProductVarietalController extends BaseBackendController
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
        $filters = Yii::$app->request->queryParams;
        if(!isset($filters['limit'])){
            $filters['limit'] = Yii::$app->params['pageSize'];
        }
        $searchModel = new ProductVarietalSearch();
        $dataProvider = $searchModel->search($filters);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filters' => $filters,
        ]);
    }

    public function actionView($id)
    {   parent::userActivity('view_product_varietal',$description='');
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new ProductVarietal();

        $productVarietalRepository = new ProductVarietalRepository();
        $productVarietalRepository = $productVarietalRepository->listingVariental(); 

        $varietalList = array();
        if($productVarietalRepository['status']['success'] == 1){
            $varietalList = CommonHelper::getDropdown($productVarietalRepository['data']['productVarietal'], ['id', 'name']);
        }


        if(Yii::$app->request->post()) {          
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post('ProductVarietal');
            $varietalRepository = new ProductVarietalRepository();
            $varietalData = $varietalRepository->createProductVarietal($data); 
            if($varietalData['status']['success'] == 1)
            {   parent::userActivity('create_product_varietal',$description='');
                Yii::$app->session->setFlash('success', $varietalData['status']['message']);
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('danger', $varietalData['status']['message']);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'varietalList' => $varietalList,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        $filters['except_id'] = $id;
        $productVarietalRepository = new ProductVarietalRepository();
        $productVarietalRepository = $productVarietalRepository->listingVariental($filters); 

        $varietalList = array();
        if($productVarietalRepository['status']['success'] == 1){
            $varietalList = CommonHelper::getDropdown($productVarietalRepository['data']['productVarietal'], ['id', 'name']);
        }


        if(Yii::$app->request->post()) {  
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post('ProductVarietal');
            $data['id'] = $id;
            
            $varietalRepository = new ProductVarietalRepository();
            $varietalData = $varietalRepository->upadateProductVarietal($data); 
            
            if($varietalData['status']['success'] == 1)
            {   parent::userActivity('update_product_varietal',$description='');
                Yii::$app->session->setFlash('success', $varietalData['status']['message']);
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('danger', $varietalData['status']['message']);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'varietalList' => $varietalList,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);        
        $modelall= ProductVarietal::findAll([]);
        
        
        if(isset($modelall) && !empty($modelall)){
            Yii::$app->session->setFlash('danger',Yii::t('app', 'Product already assigned as a reference.', [Yii::t('app', 'product_varietal')]) );
        }
        else{       
            if($model->delete()){
                parent::userActivity('delete_product_varietal',$description='');
                Yii::$app->session->setFlash('success', Yii::t('app', 'deleted_successfully', [Yii::t('app', 'product_varietal')]));
             }else{
                Yii::$app->session->setFlash('danger', $model['errors']['title'][0]);
            }
        }
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = ProductVarietal::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
