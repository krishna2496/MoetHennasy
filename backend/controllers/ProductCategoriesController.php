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
    {
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
            {
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
            $categoryRepository = new ProductCategoryRepository();
            $categoryData = $categoryRepository->upadateProductCategory($data); 
            if($categoryData['status']['success'] == 1)
            {
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
        if($this->findModel($id)->delete()){
            Yii::$app->session->setFlash('success', Yii::t('app', 'deleted_successfully', [Yii::t('app', 'product_categories')]));
            return $this->redirect(['index']);
        }
    }

    protected function findModel($id)
    {
        if (($model = ProductCategories::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
