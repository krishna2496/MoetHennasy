<?php

namespace backend\controllers;

use Yii;
use common\models\StoreConfiguration;
use common\models\StoreConfigurationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\helpers\CommonHelper;
use common\repository\MarketBrandsRepository;
use common\repository\CataloguesRepository;
use common\repository\QuestionsRepository;
use common\repository\MarketRulesRepository;
use common\repository\MarketRepository;
use common\models\CataloguesSearch;

class StoreConfigurationController extends Controller {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    
    public function actionIndex() {
       
        $currentUser = CommonHelper::getUser();
        $marketId = '';
        if (isset($currentUser->market_id) && ($currentUser->market_id != '')) {
            $marketId = $currentUser->market_id;
        }

        $returnData = $brand = $brandId = array();
        $repository = new MarketBrandsRepository();
        if ($marketId != '') {
            $data['market_id'] = $marketId;
            $returnData = $repository->listing($data);

            $brandId = array();
            if ($returnData['status']['success'] == 1) {
                if (!empty($returnData['data']['market_brands'])) {

                    foreach ($returnData['data']['market_brands'] as $key => $value) {
                        $brand[$key]['id'] = $value['brand']['id'];
                        $brand[$key]['name'] = $value['brand']['name'];
                        $brand[$key]['image'] = $value['brand']['image'];
                        $brandId[]=$value['brand']['id'];
                    }
                }
            }
        }
       
        $filterProduct['brand_id'] = $brandId;
        if(!isset($filterProduct['limit'])){
            $filterProduct['limit'] = Yii::$app->params['pageSize'];
        }
        
        if(isset($_SESSION['config']['brands']) && ($_SESSION['config']['brands'] != '')){
            $filterProduct['brand_id'] = $_SESSION['config']['brands'];
        }
        
        $searchModel = new CataloguesSearch();
        $dataProvider = $searchModel->search($filterProduct);
        
        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'brand' => $brand,
               
        ]);
    }

    public function actionSaveData() {
        $post = Yii::$app->request->post();

        $_SESSION['config']['num_of_shelves'] = $post['num_of_shelves'];
        $_SESSION['config']['height_of_shelves'] = $post['height_of_shelves'];
        $_SESSION['config']['width_of_shelves'] = $post['width_of_shelves'];        
        $_SESSION['config']['depth_of_shelves'] = $post['depth_of_shelves'];
        $_SESSION['config']['brands'] = $post['brands'];
        $_SESSION['config']['display_name'] = $post['display_name'];
       
    }
    
    public function actionSaveProductData() {
        $post = Yii::$app->request->post('productObject');
       
        $flag = 0;
        $productArry= array();
        
        if(!empty($post)){
            $flag = 1;
        foreach ($post as $key => $value){
            
           if($value['sel'] == 'true'){
               
               $searchModel = new CataloguesSearch();
        $dataProvider = $searchModel->search($filters);
               
                $_SESSION['config']['products'][$key]['selcted'] = $value['sel'];
                $_SESSION['config']['products'][$key]['top_shelf'] = $value['shelf'];
           } 
        }
        }
   
        
        
      
        if(!empty($post['productArry'])){
       
        $product = $post['productArry'];
        $_SESSION['config']['products'] = implode(',', $product);
        }
        echo '<pre>';
        print_r($_SESSION);exit;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $flag;
    }

    public function actionView($id) {
        return $this->render('view', [
                'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate() {
        $model = new StoreConfiguration();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
                'model' => $model,
        ]);
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
                'model' => $model,
        ]);
    }

    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id) {
        if (($model = StoreConfiguration::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
