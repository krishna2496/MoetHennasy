<?php

namespace backend\controllers;

use Yii;
use common\models\ProductTypes;
use common\models\ProductTypesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\repository\ProductTypesRepository;

class ProductTypesController extends BaseBackendController
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
        $searchModel = new ProductTypesSearch();
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
        parent::userActivity('view_product_types',$description='');
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new ProductTypes();

        if(Yii::$app->request->post()) {          
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post('ProductTypes');
          
            $brandRepository = new ProductTypesRepository();
            $brandData = $brandRepository->createProductTypes($data); 
            if($brandData['status']['success'] == 1)
            {   parent::userActivity('create_product_types',$description='');
                Yii::$app->session->setFlash('success', $brandData['status']['message']);
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('danger', $brandData['status']['message']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

       if(Yii::$app->request->post()) {          
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post('ProductTypes');
            $brandRepository = new ProductTypesRepository();
            $data['id'] = $id;
            $brandData = $brandRepository->updateProductTypes($data); 
            if($brandData['status']['success'] == 1)
            {   parent::userActivity('update_product_types',$description='');
                Yii::$app->session->setFlash('success', $brandData['status']['message']);
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('danger', $brandData['status']['message']);
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
            parent::userActivity('delete_product_type',$description='');
            Yii::$app->session->setFlash('success', Yii::t('app', 'deleted_successfully', [Yii::t('app', 'product_type')]));
        }else{
            Yii::$app->session->setFlash('danger', $model['errors']['title'][0]);
        }
           return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = ProductTypes::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
