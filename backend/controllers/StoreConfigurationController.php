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
use yii\filters\AccessControl;
use common\models\Stores;

class StoreConfigurationController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => \common\components\AccessRule::className(),
                ],
                'rules' => [
                        [
                        'actions' => ['index', 'listing', 'create', 'update', 'view', 'delete', 'save-data', 'save-product-data'],
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

    public function actionListing($id) {

        $stores = Stores::findOne($id);
        if ($stores) {
            $filters = Yii::$app->request->queryParams;
            if (!isset($filters['limit'])) {
                $filters['limit'] = Yii::$app->params['pageSize'];
            }
            $filters['store_id'] = $id;
            $searchModel = new StoreConfigurationSearch();
            $dataProvider = $searchModel->search($filters);
            $dataProvider->pagination->pageSize = $filters['limit'];

            return $this->render('listing', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'filters' => $filters,
                    'id' => $id
            ]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionIndex($id) {

        $stores = Stores::find()->where(['id' => $id])->asArray()->one();

        if ($stores) {
            $marketFilter = array();

            $marketRules = new MarketRulesRepository();
            $marketFilter['market_id'] = $stores['market_id'];
            $marketFilter['market_segment_id'] = $stores['market_segment_id'];

            $marketRuleData = $marketRules->listing($marketFilter);

            $rulesArray = array();
            if ($marketRuleData['status']['success'] == 1) {
                if (!empty($marketRuleData['data']['market_rules'])) {
                    foreach ($marketRuleData['data']['market_rules'] as $key => $value) {

                        $rulesArray[$key]['ids'] = $value['rule_id'];
                        $rulesArray[$key]['type'] = $value['rules']['type'];
                        $rulesArray[$key]['product_fields'] = $value['rules']['product_fields'];
                        $rulesArray[$key]['detail'] = $value['rules']['detail'];
                    }

                    $_SESSION['config']['rules'] = $rulesArray;
                }
            }
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
                            $brandId[] = $value['brand']['id'];
                        }
                    }
                }
            }

            $filterProduct['brand_id'] = $brandId;
            if (!isset($filterProduct['limit'])) {
                $filterProduct['limit'] = Yii::$app->params['pageSize'];
            }

            if (isset($_SESSION['config']['brands']) && ($_SESSION['config']['brands'] != '')) {
                $filterProduct['brand_id'] = $_SESSION['config']['brands'];
            }

            $searchModel = new CataloguesSearch();
            $dataProvider = $searchModel->search($filterProduct);

            return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'brand' => $brand,
                    'store_id' => $id,
            ]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
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
        $productArry = array();

        if (!empty($post)) {
            $flag = 1;
            foreach ($post as $key => $value) {

                if ($value['sel'] == 'true') {

                    $searchModel = new CataloguesSearch();
                    $filters['products_id'] = $key;
                    $dataProvider = $searchModel->search($filters);

                    $data = $dataProvider->getModels();
                    $productsArray = $marketRule = $rulesArray = $rulesId = $racksProductArray = array();
                    $market = $data[0]['market'];


                    $marketRule['markt_title'] = $market['title'];
                    $rules = $market['marketSegmentData'][0]['marketSegment']['marketRules'];
                    foreach ($rules as $ruleKey => $ruleValue) {
                        $rulesArray[$ruleKey] = $ruleValue['rules'];
                    }


                    foreach ($rulesArray as $rulekey => $rulevalue) {

                        $rulesId[$rulekey]['id'] = $rulevalue['id'];
                        $rulesId[$rulekey]['product_fields'] = $rulevalue['product_fields'];
                        $rulesId[$rulekey]['detail'] = $rulevalue['detail'];
                    }
//                    $marketRule['rules'] = $rulesId;

                    unset($data[0]['market']);
                    $dataIds[$key] = $data[0];
                    $dataIds[$key]['is_top_shelf'] = $value['shelf'];
                    $dataIds[$key]['market'] = $marketRule;
                }
            }
        }

        $_SESSION['config']['products'] = $dataIds;
        
        foreach ($dataIds as $key=>$value){
           unset($dataIds[$key]['brand']);
           unset($dataIds[$key]['productType']);
           unset($dataIds[$key]['productCategory']);
           unset($dataIds[$key]['marketName']);
           unset($dataIds[$key]['brandName']);
           unset($dataIds[$key]['market']);
           unset($dataIds[$key]['created_by']);
           unset($dataIds[$key]['updated_by']);
           unset($dataIds[$key]['deleted_by']);
           unset($dataIds[$key]['created_at']);
           unset($dataIds[$key]['updated_at']);
           unset($dataIds[$key]['deleted_at']);
           unset($dataIds[$key]['product_category_id']);
           unset($dataIds[$key]['product_sub_category_id']);
           unset($dataIds[$key]['product_type_id']);
           unset($dataIds[$key]['market_id']);
           unset($dataIds[$key]['brand_id']);
           unset($dataIds[$key]['sku']);
           unset($dataIds[$key]['ean']);
           unset($dataIds[$key]['manufacturer']);
         }
        
        if ($this->ifRuleContain(\yii::$app->params['configArray']['top_shelf'])) {
            foreach ($dataIds as $dataKey => $dataValue) {
                if ($dataValue['is_top_shelf'] == 'true') {
                $this->ruleTopShelf($dataValue, $racksProductArray[0]);
                }
            }
        }
        echo '<pre>';
        print_r($racksProductArray);exit;
        if (count($racksProductArray[0]) > 0) {
            $this->applySortingRule($racksProductArray[0]);
        }
       
    }

    private function ruleTopShelf($dataValue, &$racksProductArray) {
        
            $racksProductArray[$dataValue['id']] = $dataValue;
     
    }

    private function applySortingRule(&$racksProductArray) {




        if ($this->ifRuleContain(\yii::$app->params['configArray']['market_share'])) {

            rsort($racksProductArray);
        }
        //price
        if ($this->ifRuleContain(\yii::$app->params['configArray']['price'])) {
             rsort($racksProductArray);
        }

        //height rule
        if ($this->ifRuleContain(\yii::$app->params['configArray']['size_height'])) {
             rsort($racksProductArray);
        }
        //gift box 
        if ($this->ifRuleContain(\yii::$app->params['configArray']['gift_box'])) {
             rsort($racksProductArray);
        }
    }

    private function ifRuleContain($ruleValue) {

        $rules = $_SESSION['config']['rules'];
        $rulesArray = array();
        foreach ($rules as $key => $value) {
            $rulesArray[] = $value['product_fields'];
        }

        if (in_array($ruleValue, $rulesArray)) {
            return true;
        } else {

            return false;
        }
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
