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
use common\repository\BrandRepository;
use common\models\MarketSegments;
use common\models\MarketSegmentData;
use common\models\CataloguesSearch;
use common\repository\ProductCategoryRepository;
use common\models\RuleAdministration;
use common\models\Catalogues;
use yii\web\UploadedFile;
use common\repository\UploadRepository;
use common\repository\CataloguesRepository;
use common\repository\ProductTypesRepository;

class RuleAdministrationController extends BaseBackendController
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
                        'actions' => ['index','create','update','view','delete','product'],
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
        $currentUser = CommonHelper::getUser();
        $model =new RuleAdministration();
        $marketRepository = new MarketRepository;
        $marketFilter=array();
        
        if($currentUser->role_id != Yii::$app->params['superAdminRole']){
          $marketFilter['user_id'] = $currentUser->id;
        }
        $marketList = array();
        $resultMarketList = $marketRepository->marketList($marketFilter);
      
        if($resultMarketList['status']['success'] == 1){
            if($resultMarketList['data']['markets']){
                 $markets = CommonHelper::getDropdown($resultMarketList['data']['markets'], ['id', 'title']);
            }
        }
        
        $brandRepository = new BrandRepository();
        $brandFilter=array();
        $resultBrandList = $brandRepository->listing($brandFilter);
        if($resultBrandList['status']['success'] == 1){
            if($resultBrandList['data']['brand']){
                 $brands = CommonHelper::getDropdown($resultBrandList['data']['brand'], ['id', 'name']);
            }
        }
        
        $productCategoryRepository = new ProductCategoryRepository();
        $productCategoryFilter=array();
        $resultBrandList = $productCategoryRepository->listing($productCategoryFilter);
        if($resultBrandList['status']['success'] == 1){
            if($resultBrandList['data']['productCategories']){
                 $productCategory = CommonHelper::getDropdown($resultBrandList['data']['productCategories'], ['id', 'name']);
            }
        }
        $filters = Yii::$app->request->queryParams;
        if(Yii::$app->request->post()){
//            echo '<pre>';
//            print_r(Yii::$app->request->post());exit;
            $postData = Yii::$app->request->post('RuleAdministration');
           $filters['market_id'] =$postData['market_id'];
           $filters['brand_id'] = $postData['brand_id'];
           $filters['product_id']=$filters['product_category_id'] = $postData['product_category_id'];
           $filters['market_cluster_id'] = $postData['market_cluster_id'];
            $filters['selection[]'] = Yii::$app->request->post('selection');
        }
      
        
        if(!isset($filters['limit'])){
            $filters['limit'] = Yii::$app->params['pageSize'];
        }       
        $searchModel = new CataloguesSearch();
        $dataProvider = $searchModel->search($filters);
        $dataProvider->pagination->pageSize = $filters['limit'];

        return $this->render('listing', [
           'searchModel' => $searchModel,
           'dataProvider' => $dataProvider,
           'markets' => $markets,
           'filters' => $filters,
           'brands' => $brands,
           'productCategory' => $productCategory,
           'model' => $model
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
    
        public function actionProduct($id)
        {
           
        $model = Catalogues::findOne($id);
        if($model) {
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
        
        $productType = new ProductTypesRepository();
        $productType=$productType->listing();
        if($productType['status']['success'] == 1){
            $productTypeData = CommonHelper::getDropdown($productType['data']['productTypes'], ['id', 'title']);
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
                parent::userActivity('update_catalogue',$description='');
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
            'productSubCatData' => $productSubCatData,
            'productTypeData' => $productTypeData
        ]);
       }else{
           throw new NotFoundHttpException('The requested page does not exist.');
       }
    }
    
    protected function findModel($id)
    {
        if (($model = Markets::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
