<?php

namespace backend\controllers;

use Yii;
use common\models\ProductCategories;
use common\models\ProductCategoriesSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\repository\ProductCategoryRepository;
use common\helpers\CommonHelper;

class ProductCategoriesController extends BaseBackendController
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
        $searchModel = new ProductCategoriesSearch();
        $dataProvider = $searchModel->search($filters);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filters' => $filters,
        ]);
    }

    public function actionView($id)
    {   parent::userActivity('view_product_category',$description='');
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new ProductCategories();

        $productCategoryRepository = new ProductCategoryRepository();
        $productCategoryRepository = $productCategoryRepository->listing(); 

        $categoryList = array();
        if($productCategoryRepository['status']['success'] == 1){
            $categoryList = CommonHelper::getDropdown($productCategoryRepository['data']['productCategories'], ['id', 'name']);
        }


        if(Yii::$app->request->post()) {          
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post('ProductCategories');
            $categoryRepository = new ProductCategoryRepository();
            $categoryData = $categoryRepository->createProductCategory($data); 
            if($categoryData['status']['success'] == 1)
            {   parent::userActivity('create_product_category',$description='');
                Yii::$app->session->setFlash('success', $categoryData['status']['message']);
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('danger', $categoryData['status']['message']);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'categoryList' => $categoryList,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        $filters['except_id'] = $id;
//        $filters['parent_id'] = $model->parent_id;
        $productCategoryRepository = new ProductCategoryRepository();
        $productCategoryRepository = $productCategoryRepository->listing($filters); 

        $categoryList = array();
        if($productCategoryRepository['status']['success'] == 1){
            $categoryList = CommonHelper::getDropdown($productCategoryRepository['data']['productCategories'], ['id', 'name']);
        }


        if(Yii::$app->request->post()) {  
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post('ProductCategories');
            $data['id'] = $id;
            if(isset($data['parent_id'])){
                $parent_model = $this->findModel($data['parent_id']);
            }
            if(($data['parent_id'] == $parent_model->id) && ($parent_model->parent_id == $data['id'])){
                Yii::$app->session->setFlash('danger', 'Parent Loop Formation');
                return $this->redirect(['index']);
            }
            
            $categoryRepository = new ProductCategoryRepository();
            $categoryData = $categoryRepository->upadateProductCategory($data); 
            
            if($categoryData['status']['success'] == 1)
            {   parent::userActivity('update_product_category',$description='');
                Yii::$app->session->setFlash('success', $categoryData['status']['message']);
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('danger', $categoryData['status']['message']);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'categoryList' => $categoryList,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);        
        $modelall= ProductCategories::findAll(['parent_id' => $model->id]);
        
        
        if(isset($modelall) && !empty($modelall)){
            Yii::$app->session->setFlash('danger',Yii::t('app', 'Product already assigned as a reference.', [Yii::t('app', 'product_categories')]) );
        }
        else{       
            if($model->delete()){
                parent::userActivity('delete_product_category',$description='');
                Yii::$app->session->setFlash('success', Yii::t('app', 'deleted_successfully', [Yii::t('app', 'product_categories')]));
             }else{
                Yii::$app->session->setFlash('danger', $model['errors']['title'][0]);
            }
        }
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = ProductCategories::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
