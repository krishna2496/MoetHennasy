<?php

namespace backend\controllers;

use Yii;
use common\models\Catalogues;
use common\models\CataloguesSearch;
use common\repository\MarketRepository;
use yii\web\Controller;
use yii\web\UploadedFile;
use common\repository\CataloguesRepository;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\repository\UploadRepository;
use common\repository\BrandRepository;
use common\helpers\CommonHelper;
use common\repository\ProductCategoryRepository;

class CataloguesController extends BaseBackendController
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
                    [
                        'actions' => ['product-sub-category'],
                        'allow' => true,
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
        $searchModel = new CataloguesSearch();
        $dataProvider = $searchModel->search($filters);
        $dataProvider->pagination->pageSize = $filters['limit'];
        
        $marketSearch =new MarketRepository();
        $market=$marketSearch->marketList();
        if($market['status']['success'] == 1){
            $market = CommonHelper::getDropdown($market['data']['markets'], ['id', 'title']);
        }
        
        $brand =new BrandRepository();
        $brand=$brand->listing(); 
        if($brand['status']['success'] == 1){
            $brand = CommonHelper::getDropdown($brand['data']['brand'], ['id', 'name']);
        }
        $product= new ProductCategoryRepository();
        $product=$product->listing();
         if($product['status']['success'] == 1){
            $productData = CommonHelper::getDropdown($product['data']['productCategories'], ['id', 'name']);
        }
        
        parent::userActivity('View Catalogues',$description='View Catalogues');
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'market' => $market,
            'filters'=>$filters,
            'brand' => $brand,
            'product' =>$productData
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
        $model = new Catalogues();
        $model->scenario = 'create';
        $marketSearch =new MarketRepository();
        $market=$marketSearch->marketList();
        if($market['status']['success'] == 1){
            $market = CommonHelper::getDropdown($market['data']['markets'], ['id', 'title']);
        }
        
        $brand =new BrandRepository();
        $brand=$brand->listing(); 
        if($brand['status']['success'] == 1){
            $brand = CommonHelper::getDropdown($brand['data']['brand'], ['id', 'name']);
        }
        
        $product= new ProductCategoryRepository();
        $product=$product->listing();
         if($product['status']['success'] == 1){
            $productData = CommonHelper::getDropdown($product['data']['productCategories'], ['id', 'name']);
        }
   
        
        $productSubCatData=array();
        if(Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post('Catalogues');  
            $data['image'] = '';
            if(UploadedFile::getInstance($model,'catalogueImage')) {
                $fileData = array();
                $fileData['files'][0] = UploadedFile::getInstance($model,'catalogueImage');
                $fileData['type'] = 'catalogues';
                $uploadUrl = CommonHelper::getPath('upload_url').$fileData['type'].'/';
                $uploadRepository = new UploadRepository;
                $uploadData = $uploadRepository->store($fileData);
                if($uploadData['status']['success'] == 1){
                    $data['catalogueImage'] = $data['image'] = str_replace($uploadUrl,"",$uploadData['data']['uploadedFile'][0]['name']);
                } else {
                    return $this->redirect(['index']);
                    Yii::$app->session->setFlash('danger', $uploadData['status']['message']);
                }
            }
            $userRepository = new CataloguesRepository;
            $returnData = $userRepository->createCatalogue($data);
            if($returnData['status']['success'] == 1)
            {
                parent::userActivity('Create Catalogues',$description='Create Catalogues');
                Yii::$app->session->setFlash('success', $returnData['status']['message']);
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('danger', $returnData['status']['message']);
            }
        }
        
        return $this->render('create', [
            'model' => $model,
            'market'=>$market,
            'brand' => $brand,
            'product'=>$productData,
            'productSubCatData'=>$productSubCatData
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $marketSearch =new MarketRepository();
        $market=$marketSearch->marketList();
      
        if($market['status']['success'] == 1){
            $market = CommonHelper::getDropdown($market['data']['markets'], ['id', 'title']);
        }
        
        $brand =new BrandRepository();
        $brand=$brand->listing(); 
        if($brand['status']['success'] == 1){
            $brand = CommonHelper::getDropdown($brand['data']['brand'], ['id', 'name']);
        }
       
        $product= new ProductCategoryRepository();
        $filterData['parent_id']=$model['product_category_id'];
        $productData=$product->listing();
        if($productData['status']['success'] == 1){
            $productData = CommonHelper::getDropdown($productData['data']['productCategories'], ['id', 'name']);
        }
        $product=$product->listing($filterData);
      
         if($product['status']['success'] == 1){
            $productSubCatData = CommonHelper::getDropdown($product['data']['productCategories'], ['id', 'name']);
        }
        if ($model->load(Yii::$app->request->post())) {
            $oldImagePath = CommonHelper::getPath('upload_path').UPLOAD_PATH_CATALOGUES_IMAGES.$model->image;
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post('Catalogues');
            $data['id'] = $id;
            $data['image'] = '';
            if(UploadedFile::getInstance($model,'catalogueImage')) {
                $fileData = array();
                $fileData['files'][0] = UploadedFile::getInstance($model,'catalogueImage');
                $fileData['type'] = 'catalogues';
                $uploadUrl = CommonHelper::getPath('upload_url').$fileData['type'].'/';
                $uploadRepository = new UploadRepository;
                $uploadData = $uploadRepository->store($fileData);
                if($uploadData['status']['success'] == 1){
                    parent::userActivity('Update Catalogues');
                    $data['image'] = str_replace($uploadUrl,"",$uploadData['data']['uploadedFile'][0]['name']);
                    if(file_exists($oldImagePath)){
                        @unlink($oldImagePath);
                    }
                } else {
                    return $this->redirect(['index']);
                    Yii::$app->session->setFlash('danger', $uploadData['status']['message']);
                }
            }

            $userRepository = new CataloguesRepository;
            $returnData = $userRepository->updateCatalogue($data);
            if($returnData['status']['success'] == 1)
            {
                parent::userActivity('Update Catalogues',$description='Update Catalogues');
                Yii::$app->session->setFlash('success', $returnData['status']['message']);
               
                return $this->redirect(['index']);
            } else {
               Yii::$app->session->setFlash('danger', $returnData['status']['message']);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'market'=>$market,
            'brand' => $brand,
            'product'=>$productData,
            'productSubCatData' => $productSubCatData
            
        ]);
    }

    public function actionProductSubCategory($data = array()){
      
        $isJson = 1;
        if($data) {
            $isJson = 0;
        } else {
            $data = Yii::$app->request->post();
        }
        $product= new ProductCategoryRepository();
        $filter['parent_id']=$data['product_id'];
        $product=$product->listing($filter);
        if($isJson){
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        }
        return $product;
        
    }
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', Yii::t('app', 'deleted_successfully', [Yii::t('app', 'catalogues')]));
        parent::userActivity('Delete Catalogues',$description='Catalogues Deleted');
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Catalogues::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
