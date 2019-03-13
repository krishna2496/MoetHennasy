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
use common\repository\BrandRepository;
use common\repository\UploadRepository;
use common\repository\StoreConfigRepository;
use common\repository\UserRepository;
use mPDF;
use common\components\Email;
use common\models\User;
use common\models\Questions;
use common\models\ConfigFeedback;
use common\models\Ratings;
use common\models\ShelfDisplay;
use backend\controllers\ProductRuleController;

class StoreConfigurationController extends ProductRuleController {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => \common\components\AccessRule::className(),
                ],
                'rules' => [
                        [
                        'actions' => ['index', 'listing', 'update-config', 'delete'],
                        'allow' => true,
                        'roles' => ['&'],
                        ],
                        [
                        'actions' => ['send-mail', 'feedback', 'create', 'view', 'review-store', 'save-image', 'update', 'save-image', 'save-data', 'save-product-data', 'modal-content', 'get-products', 'edit-products', 'save-config-data','delete-all'],
                        'allow' => true,
                        'roles' => ['@'],
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
    
    public function actionSaveConfigData() {
        $post = yii::$app->request->post();
        $shelfThumb = isset($post['thumb_image']) ? $post['thumb_image'] : '';
        $brandId = isset($post['brand']) ? $post['brand'] : '';
        $configData = array();

        $storeConfig = new StoreConfigRepository();
        $storeId = $_SESSION['config']['storeId'];
        $configData['store_id'] = isset($_SESSION['config']['storeId']) ? $_SESSION['config']['storeId'] : '';
        $configData['shelf_thumb'] = $shelfThumb;
        $configData['config_name'] = isset($_SESSION['config']['display_name']) ? $_SESSION['config']['display_name'] : '';

        $configData['shelfDisplay'] = array(
            array(
                'width_of_shelves' => isset($_SESSION['config']['width_of_shelves']) ? $_SESSION['config']['width_of_shelves'] : '',
                'depth_of_shelves' => isset($_SESSION['config']['depth_of_shelves']) ? $_SESSION['config']['depth_of_shelves'] : '',
                'no_of_shelves' => isset($_SESSION['config']['no_of_shelves']) ? $_SESSION['config']['no_of_shelves'] : '',
                'shelf_config' => json_decode($_SESSION['config']['shelvesProducts'], true),
                'brand_thumb_id' => $brandId,
                'height_of_shelves' => isset($_SESSION['config']['height_of_shelves']) ? $_SESSION['config']['height_of_shelves'] : '',
            ),
        );

        if (isset($post['config_id']) && ($post['config_id'] != 0)) {
            $configData['config_id'] = $post['config_id'];
            $returnData = $storeConfig->updateConfig($configData);
        } else {
            $returnData = $storeConfig->createConfig($configData);
        }
        if ($returnData['status']['success'] == 1) {
            $this->actionSendMail($returnData['data']['shelf_thumb']);
            Yii::$app->session->setFlash('success', $returnData['status']['message']);
            unset($_SESSION['config']);
        } else {
            Yii::$app->session->setFlash('danger', $returnData['status']['message']);
        }
        return $this->redirect(['store-configuration/listing/' . $storeId]);
    }

    public function actionReviewStore($id) {
        $storeConfig = new StoreConfigRepository();
        $filter = $feedBackResponse = array();
        $filter['config_id'] = $id;
//        $configData = $storeConfig->listing($filter);
        $questionsModel = new Questions();
//        $feedback = new ConfigFeedback();
        $feedBackList = ConfigFeedback::find()->andWhere(['config_id' => $id])->asArray()->all();
//        $rating = new Ratings();
        $ratingData = StoreConfiguration::findOne($id);
        $storeRating = $ratingData['star_ratings'];

        foreach ($feedBackList as $key => $value) {
            $feedBackResponse[$value['que_id']] = $value['answer'];
        }

        $questions = $questionsModel::find()->indexBy('id')->asArray()->all();

        return $this->renderPartial('review-content', [
                'questions' => $questions,
                'feedBackResponse' => $feedBackResponse,
                'storeRating' => $storeRating
                ], true);
    }

    public function actionFeedback($id) {
        $config_id = $id;
        $flag = 0;
        $post = yii::$app->request->post();

        $user = CommonHelper::getUser();
        $questionArray = isset($post['data']) ? $post['data'] : array();
        $ansArray = array();
        $questionsModel = new Questions();
        $questions = $questionsModel::find()->indexBy('id')->asArray()->all();

        if ($post['action'] == 'feedback') {
            foreach ($questions as $key => $value) {
                $ansArray[$key]['question_id'] = $value['id'];
                if (in_array($value['id'], $questionArray)) {
                    $ansArray[$key]['ans'] = 1;
                } else {
                    $ansArray[$key]['ans'] = 0;
                }
            }

            $questionModel = new ConfigFeedback();
            ConfigFeedback::deleteAll(['config_id' => $config_id]);
            foreach ($ansArray as $key => $value) {
                $questionModel = new ConfigFeedback();
                $questionModel->config_id = $config_id;
                $questionModel->que_id = $value['question_id'];
                $questionModel->answer = $value['ans'];
                $questionModel->reviewed_by = $user['id'];
                $questionModel->save(false);
            }

            $flag = 1;
        }

        if ($post['action'] == 'rating') {
            $raingRepository = new StoreConfigRepository();
            $data['config_id'] = $config_id;
            $data['star_ratings'] = $post['data'];
            $returnData = $raingRepository->createRating($data);
            if ($returnData['status']['success'] == 1) {
                $flag = 1;
            }
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $flag;
    }
    
    public function actionSaveImage() {
        $returnData = array();
        $returnData['flag'] = 0;
        $post = yii::$app->request->post();
        $data = $post['imageData'];
        list($type, $data) = explode(';', $data);
        list(, $data) = explode(',', $data);
        $data = base64_decode($data);
        $randomName = rand() . '.png';
        $uploadUrl = CommonHelper::getPath('upload_path');
        $imagePath = $uploadUrl . UPLOAD_PATH_STORE_CONFIG_IMAGES . $randomName;
        $savefile = file_put_contents($imagePath, $data);
        if (CommonHelper::resizeImage(UPLOAD_PATH_STORE_CONFIG_IMAGES . $randomName, $randomName, 64, 64, UPLOAD_PATH_STORE_CONFIG_IMAGES)) {
            $returnData['name'] = $randomName;
            $returnData['flag'] = 1;
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return $returnData;
    }

    public function actionUpdateConfig($id, $storeId) {
        $configId = $id;
        $stores = Stores::find()->where(['id' => $storeId])->asArray()->one();
        $currentUser = CommonHelper::getUser();
         $reviewFlag = 0;
        if ($stores) {
            $this->checkUserAccess($currentUser, $stores);
            $storeFilter = array();
            $storeFilter['store_id'] = $storeId;
            $storeFilter['config_id'] = $id;
            $configRepository = new StoreConfigRepository();
            $cataloguesRepository = new CataloguesRepository();
            $configData = $configRepository->listing($storeFilter);

            $request = Yii::$app->request;
            $brandThumbId = $display_name = '';
            if ($currentUser->role_id == Yii::$app->params['superAdminRole']) {
                 $reviewFlag = 1;
            }
            
            if ($configData['status']['success'] == 1) {
                if (!$request->isPjax) {
                    $storeData = $configData['data']['stores_config'][0];
                   
                    $userData = new User();

                    $userDetail = User::findOne(['id' => $storeData['created_by']]);
                    $configCreatedByRole = $userDetail['role_id'];
                    $configCreatedByParent = ($userDetail['parent_user_id'] == '' ) ? 0 : $userDetail['parent_user_id'];

                    $reviewFlag = 1;
                    
                    $_SESSION['config']['storeId'] = $storeData['store_id'];

                    $display_name = $_SESSION['config']['display_name'] = $storeData['config_name'];
                    $brandThumbId = $storeData['shelfDisplay'][0]['brand_thumb_id'];

                    $_SESSION['config']['no_of_shelves'] = $storeData['shelfDisplay'][0]['no_of_shelves'];
                    $_SESSION['config']['height_of_shelves'] = $storeData['shelfDisplay'][0]['height_of_shelves'];
                    $_SESSION['config']['width_of_shelves'] = $storeData['shelfDisplay'][0]['width_of_shelves'];
                    $_SESSION['config']['depth_of_shelves'] = $storeData['shelfDisplay'][0]['depth_of_shelves'];
                    $ratioWidth = yii::$app->params['rackWidth'][0];
                    $_SESSION['config']['ratio'] = (($ratioWidth) / $storeData['shelfDisplay'][0]['width_of_shelves']);

                    $_SESSION['config']['shelvesProducts'] = $products = $storeData['shelfDisplay'][0]['shelf_config'];
                    $productsArray = json_decode($products, true);
                    $productsData = array();
                    $rackProducts = array();
                    $brandsArray = array();
                    foreach ($productsArray as $key => $v) {
                        $ids = explode(',', $v['productIds']);
                        $filterProduct = array();
                        foreach ($ids as $k => $value) {

                            $filterProduct['products_id'] = $value;
                            $listing = $cataloguesRepository->listing($filterProduct);
                            $filterListing = $listing['data']['catalogues'][0];
                            $marketTitle = $filterListing['market']['title'];
                            unset($filterListing['market']);
                            $filterListing['market'] = array('markt_title' => $marketTitle);
                            $category = $filterListing['productCategory']['name'];
                            unset($filterListing['productCategory']);
                            $filterListing['productCategory'] = $category;
                            $filterListing['marketName'] = $marketTitle;
                            $filterListing['brandName'] = $filterListing['brand']['name'];
                            $productsData[$value] = $filterListing;

                            $rackProducts[$key][$k]['id'] = $value;
                            $rackProducts[$key][$k]['image'] = CommonHelper::getImage(UPLOAD_PATH_CATALOGUES_IMAGES . $filterListing['image']);
                            $rackProducts[$key][$k]['height'] = $filterListing['height'];
                            $rackProducts[$key][$k]['width'] = $filterListing['width'];

                            $brandsArray[$filterListing['brand']['id']] = $filterListing['brand'];
                        }
                    }
                    $brandRepository = new BrandRepository();
                    $brandFilter = array();
                    $brandFilter['brand_id'] = $brandThumbId;
                    $brandData = $brandRepository->listing($brandFilter);
                    if ($brandData['status']['success'] == 1) {

                        $brandsArray[$brandData['data']['brand'][0]['id']] = $brandData['data']['brand'][0];
                    }
                    $_SESSION['config']['brands_data'] = $brandsArray;

                    $_SESSION['config']['products'] = $productsData;
                    $_SESSION['config']['rackProducts'] = $rackProducts;
                }
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
                $_SESSION['config']['storeId'] = $storeId;
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
                    $brandBackground = '';
                    $brandId = array();
                    if ($returnData['status']['success'] == 1) {
                        if (!empty($returnData['data']['market_brands'])) {

                            foreach ($returnData['data']['market_brands'] as $key => $value) {
                              
                                $brand[$key]['id'] = $value['brand']['id'];
                                $brand[$key]['name'] = $value['brand']['name'];
                                $brand[$key]['image'] = $value['brand']['image'];
                                $brandId[] = $value['brand']['id'];
                                if($brandThumbId != ''){
                                if($value['brand']['id'] == $brandThumbId){
                                    $brandBackground = $value['brand']['color_code'];
                                }
                                }
                                
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
                        'store_id' => $storeId,
                        'is_update' => 1,
                        'brandThumbId' => $brandThumbId,
                        'configId' => $configId,
                        'display_name' => $display_name,
                        'reviewFlag' => $reviewFlag,
                        'brandBackground' => $brandBackground
                ]);
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionListing($id) {
        $stores = Stores::findOne($id);
        $currentUser = CommonHelper::getUser();
        $canCreateNewConfig = 0;
        if ($stores) {
          
            $this->checkUserAccess($currentUser, $stores);
            if ($stores['assign_to'] == $currentUser->id || $currentUser->role_id == Yii::$app->params['superAdminRole']) {
                $canCreateNewConfig = 1;
            }
            
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
                    'id' => $id,
                    'canCreateNewConfig' => $canCreateNewConfig
            ]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionIndex($id , $categoryId = 0) {
      
        $storeId = $id;
        $stores = Stores::find()->where(['id' => $id])->asArray()->one();
        $currentUser = CommonHelper::getUser();

        if ($stores) {
            $this->checkUserAccess($currentUser, $stores);

            $marketFilter = array();

            $marketRules = new MarketRulesRepository();
            $marketFilter['market_id'] = $stores['market_id'];
            $marketFilter['market_segment_id'] = $stores['market_segment_id'];

            $marketRuleData = $marketRules->listing($marketFilter);

            $rulesArray = array();
            if ($marketRuleData['status']['success'] == 1 && isset($marketRuleData['data']['market_rules']) && !empty($marketRuleData['data']['market_rules'])) {
                
                foreach ($marketRuleData['data']['market_rules'] as $key => $value) {
                    $rulesArray[$key]['ids'] = $value['rule_id'];
                    $rulesArray[$key]['type'] = $value['rules']['type'];
                    $rulesArray[$key]['product_fields'] = $value['rules']['product_fields'];
                    $rulesArray[$key]['detail'] = $value['rules']['detail'];
                }

                $_SESSION['config']['rules'] = $rulesArray;
            }
            $_SESSION['config']['storeId'] = $storeId;
            $currentUser = CommonHelper::getUser();
            $marketId = '';

            if (isset($currentUser->market_id) && ($currentUser->market_id != '') && $currentUser->role_id == '1') { // check if role is super admin
                $marketId = $stores['market_id'];
            }
            else if (isset($currentUser->market_id) && ($currentUser->market_id != '')) {
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
        
            

            //category 
            $marketBrandModel = new StoreConfigurationSearch();
            $stores['market_id'] = 7;
            $marketBrand = $marketBrandModel->brandProductList($stores['market_id']);
            
            $wholeData = array();
            if(isset($marketBrand) && (!empty($marketBrand))){
                $wholeData =  $marketBrand['market']['category'];

                if($categoryId != 0){
                    $key = array_search($categoryId, array_column($wholeData, 'id'));
                    $newData =  $wholeData[$key];
                    $wholeData = array();
                    $wholeData[0] = $newData;
                   
                }
                
            }else{
                exit();
            }
            //variental product
            $brandFilter = [];
            if(isset($_SESSION['config']['brands'])){
                $brandFilter = $_SESSION['config']['brands'];
            }
            $filtetOtherProductData = array(
              'brands' => $brandFilter,
                
            );
            $searchModel = new CataloguesSearch();
            $dataProvider = $searchModel->searchProductData($wholeData,$filtetOtherProductData);
            //top shelf product
            $searchModel = new CataloguesSearch();
            $filterTopShelf['category_id'] = $categoryId;
            $topDataProvider = $searchModel->searchTopShelfProduct($wholeData,$filterTopShelf);

            return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'brand' => $brand,
                    'store_id' => $id,
                    'is_update' => 0,
                    'brandThumbId' => 0,
                    'configId' => 0,
                    'reviewFlag' => 0,
                    'brandBackground' => '',
                    'wholeData' => $wholeData,
                    'categoryId' => $categoryId,
                    'market_id' => $stores['market_id'],
                    'topDataProvider' => $topDataProvider
            ]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSaveData() {
        $post = Yii::$app->request->post();
        
        $_SESSION['config']['no_of_shelves'] = $post['no_of_shelves'];
        $_SESSION['config']['height_of_shelves'] = $post['height_of_shelves'];
        $_SESSION['config']['width_of_shelves'] = $post['width_of_shelves'];
        $_SESSION['config']['depth_of_shelves'] = $post['depth_of_shelves'];
        $_SESSION['config']['brands'] = $post['brands'];
        $_SESSION['config']['market_id'] = $post['market_id_hidden'];
        $_SESSION['config']['category_id'] = $post['category_id_hidden'];
        $_SESSION['config']['ratio'] = $post['ratio'];
        $_SESSION['config']['display_name'] = $post['display_name'];
     
        $filters['brand_id'] = $_SESSION['config']['brands'];
      
        $marketBrandModel = new StoreConfigurationSearch();
        $market_id = $post['market_id_hidden'];
        $marketBrand = $marketBrandModel->brandProductList($market_id);
        $brand = $marketBrand['market']['category'][0]['brand'];
        
        $newBrandData = array();
        foreach ($brand as $k => $v){
            if(in_array($v['id'], $filters['brand_id'])){
                $newBrandData[$v['id']] = $v;
            }
        }
        $_SESSION['config']['top_shelf'] = $marketBrand['market']['category'][0]['top_shelf_product'];
        $_SESSION['config']['brands_data'] = $newBrandData;
        
    }

    public function actionSaveProductData() {
        
        $post = Yii::$app->request->post('productObject');
        $flag = $marketId = $categoryId = 0;
        $productArry = $bottomProduct =  $uniqueBrandVarientalArry = $uniqueBrandSum = $sumOfBrandProduct = $sharesArry =array();
        $marketId = $_SESSION['config']['market_id'];
        $categoryId = $_SESSION['config']['category_id'];
        $orderArry = \common\models\MarketCategoryProduct::find()->andWhere(['category_id' =>$categoryId,'market_id'=>$marketId])->asArray()->all();
        $reOrderArry = CommonHelper::getDropdown($orderArry, ['product_id', 'top_reorder_id']);
     
        if (!empty($post)) {
            $flag = 1;
            foreach ($post as $key => $value) {
                if ($value['sel'] == 'true') {
                    $searchModel = new CataloguesSearch();
                    $filters['products_id'] = $key;
                    $dataProvider = $searchModel->search($filters);
                    $data = $dataProvider->getModels();
                    $productsArray = $marketRule = $rulesArray = $rulesId = $racksProductArray = $orderedArry = array();
                    if($value['shelf'] == 'true'){
                       $data[0]['order_id'] = isset($reOrderArry[$key]) ? $reOrderArry[$key] : 0;
                    }
                    $dataIds[$key] = $data[0];
                    $dataIds[$key]['is_top_shelf'] = '';
                    $dataIds[$key]['market'] = $marketRule;
                    if($value['shelf'] != 'true'){
                        $bottomProduct[]=$key;
                    }
                }
            }
        }
       
        $_SESSION['config']['products'] = $dataIds;
      
        foreach ($dataIds as $key => $value) {
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
           
            unset($dataIds[$key]['product_sub_category_id']);
            unset($dataIds[$key]['product_type_id']);
         
          
            unset($dataIds[$key]['sku']);
            unset($dataIds[$key]['ean']);
            unset($dataIds[$key]['manufacturer']);
        }
   
        $selvesWidth = $_SESSION['config']['width_of_shelves'];
        $selvesHeight = $_SESSION['config']['height_of_shelves'];
        $selvesDepth =$_SESSION['config']['depth_of_shelves'];
        $selevesCount = $_SESSION['config']['no_of_shelves'];
       
        //top shelf product
        $top_shelf_product = $_SESSION['config']['top_shelf'];
        //get top shelf order 
        if ($this->ifRuleContain(\yii::$app->params['configArray']['top_shelf'])) {
            foreach ($dataIds as $dataKey => $dataValue) {
                if ($dataValue['top_shelf'] == '1') {
                    $this->ruleTopShelf($dataValue, $racksProductArray[0], $selvesWidth);
                }
            }
        }
    
        if (isset($racksProductArray[0]) && (!empty($racksProductArray[0]))) {
            if (count($racksProductArray[0]) > 0) {
                $this->applySortingRule($racksProductArray[0],$selvesWidth);
            }
        }
        
        foreach ($dataIds as $value) {
            if ($value['top_shelf'] == '1') {
                continue;
            }
            //$uniqueBrandVarientalArry[$value['brand_id']] = array();
            if(isset($value['variental']['id'])){
                $uniqueBrandVarientalArry[$value['brand_id']][$value['variental']['id']][$value['id']] = $value['width'];
                $orderedArry[$value['brand_id']][] = $value['variental']['id'];
//                $uniqueBrandVarientalArry[$value['brand_id']]['variental_product_width'][] = 
            }
        }

        $sharesArry =array();
        $brandSum = 0;
        $productBrandData = $_SESSION['config']['brands_data'];
        
        foreach ($productBrandData as $bK=>$bV){
            $brandVarientalSum = 0;
            if(isset($uniqueBrandVarientalArry[$bK])){
                //brand share
                $sharesArry[$bK]['brand_shares'] = $bV['shares'];
                $brandSum = $brandSum + $bV['shares'];
                if(isset($bV['marketBrandsVerietals'])){
                    $brandVarietal = $bV['marketBrandsVerietals'];
                    foreach ($brandVarietal as $k=>$v){
                        if($v['shares'] != 0){
                            $sharesArry[$bK]['varietal'][$v['id']]= $v['shares'];
                            $brandVarientalSum = $brandVarientalSum + $v['shares'];
                        }
                    }
                    $sharesArry[$bK]['varietal_sum'] = $brandVarientalSum; 
                }
            }
        }
     
        $this->reIntializeBrandShareArry($sharesArry,$brandSum);
  
         
        if($sharesArry){
          if($brandSum == 100){
            foreach ($sharesArry as $k => $v){
                $brandWidth = ($selvesWidth * $v['brand_shares'])/(100);
                $sharesArry[$k]['brand_shares'] = $brandWidth;
                if($v['varietal']){
                    foreach ($v['varietal'] as $vK => $vV){
                         $newWidth = ($vV * $brandWidth)/(100);
                         $sharesArry[$k]['varietal'][$vK] = $newWidth;
                    }
                }
            }
          }
        }
     if($uniqueBrandVarientalArry){
        foreach ($uniqueBrandVarientalArry as $key => $varietal){
            foreach ($varietal as $k => $v){
                $uniqueBrandSum[$key][$k] = array_sum($v);
            }
        }
     }
   
     foreach ($sharesArry as $key => $val){
         if($val['varietal']){
             foreach ($val['varietal'] as $k => $v){
                if($v < $uniqueBrandSum[$key][$k]){
                    $response['flag'] = 0;
                    $response['msg'] = 'You have not selected any Products';
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return $response;
                }
             }
         }
         
     }
  //ordered array 
     $rulesDataArry = [];
     if($sharesArry){
        foreach ($sharesArry as $sK => $sV){
            foreach ($sV['varietal'] as $k => $v){
                $data = $uniqueBrandVarientalArry[$sK][$k];
                if(isset($data)){
                    $rulesDataArry[$sK.'_'.$k] = $data;
                }
            }
        }
     }
     
     $newArry = $applyRuleArry = array();
     foreach ($rulesDataArry as $k => $v){
         $keyData = explode('_',$k);
         $brand_id = $keyData[0];
         $varital_id = $keyData[1];
         $z= $sharesArry[$brand_id]['varietal'][$varital_id];
         $totalSum= $uniqueBrandSum[$brand_id][$varital_id];
         $x = ($z/$totalSum);
         $x = (int)$x;
         $y = $x*$totalSum;
         $w= $z - $y;
         
         foreach ($v as $key => $val){
           $repeatCount = $x;
           if($val < $w){
               $repeatCount++;
           }
           $newArry[$k][$key]=  $repeatCount;
         }
         
     }

     foreach ($newArry as $key => $value)
     {
         foreach ($value as $k => $v){
             for($i=0;$i<$v;$i++){
                $applyRuleArry[$key][]=$dataIds[$k];
             }
         }
     }
   
//        $shelfIndex = (isset($racksProductArray[0]) && count($racksProductArray[0]) > 0 ) ? 1 : 0;
//        if ($selevesCount > 1) {
//        
//        foreach ($dataIds as $value) {
//
//            if ($value['top_shelf'] == '1') {
//                continue;
//            }
//            $sum = 0;
//            if (!empty($racksProductArray[$shelfIndex])) {
//                foreach ($racksProductArray[$shelfIndex] as $rackValue) {
//                    $sum = $sum + $rackValue['width'];
//                }
//            }
//
//            if (intval($selvesWidth) >= intval(intval($sum) + intval($value['width']))) {
//
//                if ((intval(($selvesHeight) / ($selevesCount)) >= intval($value['height'])) && (intval($selvesDepth) >= intval($value['length']))) {
//                   
//                    if (empty($racksProductArray[$shelfIndex])) { 
//                        $racksProductArray[$shelfIndex][] = $value;
//                      
//                    } else {
//                        array_push($racksProductArray[$shelfIndex], $value);
//                    }
//                }
//            } else {
//                if (intval($shelfIndex) < ((intval($selevesCount)) - 1)) {
//                    $shelfIndex = intval($shelfIndex) + 1;
//                }
//            }
//        }
//        }
      
        foreach ($applyRuleArry as $key =>$value){
 
        $this->applySortingDataRule($applyRuleArry[$key]);
        }
        foreach ($applyRuleArry as $k => $v){
            foreach ($v as $key =>$val){
                if(isset($racksProductArray[0])){
                    $racksProductArray[1][]=$val;
                }else{
                    $racksProductArray[0][]=$val;
                }
            }
        }
        
        for($i=0;$i<$selevesCount;$i++){
            if(!isset($racksProductArray[$i])){
                $racksProductArray[$i] = $racksProductArray[$i-1];
            }
        }
       
//        $this->fillUpEmptySpaceOfShelves($racksProductArray, $selvesWidth, $selevesCount);
//        echo '<pre>';
//        print_r($racksProductArray);exit
        $finalProducuts = $finalProducutsRack = $productsId = array();

        foreach ($racksProductArray as $key => $value) {
            $tmpProducts = '';
            if (!empty($value)) {
                foreach ($value as $racksKey => $racksValue) {
                    $tmpProducts .= $racksValue['id'] . ",";
                    $finalProducuts[$key][$racksValue['id']] = $racksValue;
                    $finalProducutsRack[$key][$racksKey]['id'] = $racksValue['id'];
                    $finalProducutsRack[$key][$racksKey]['image'] = CommonHelper::getImage(UPLOAD_PATH_CATALOGUES_IMAGES . $racksValue['image']);
                    $finalProducutsRack[$key][$racksKey]['height'] = $racksValue['height'];
                    $finalProducutsRack[$key][$racksKey]['width'] = $racksValue['width'];
                }
            }
            $productsId[$key]['productIds'] = rtrim($tmpProducts, ",");
        }

        $_SESSION['config']['final_products'] = $finalProducuts;
        $_SESSION['config']['shelvesProducts'] = json_encode($productsId);
        $_SESSION['config']['rackProducts'] = $finalProducutsRack;
    }

    public function actionModalContent($id) {
        return $this->renderPartial('modal-content', [
                'id' => $id,
                ], true);
    }

    public function actionGetProducts() {
        $data = array();
        $data = Yii::$app->request->post();
        $data['brand_id'] = $data['id'];
        $repository = new CataloguesRepository();
        $returnData = $repository->listing($data);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $returnData;
    }  
    
    public function actionDeleteAll(){
        $data = array();
        $data = Yii::$app->request->post();
        $productsData = json_decode($_SESSION['config']['shelvesProducts'], true);
        $response=array();
        if(isset($data['value']) && !empty($data['value'])){
            $shelvesNo = $data['index'];
            $productKeyArr = $data['value'];

            $productKeyArry = array();
            foreach($productKeyArr as $keyP=>$valueP){
                $productKey = $valueP;
                $id = isset($_SESSION['config']['rackProducts'][$shelvesNo][$productKey]['id']) ? $_SESSION['config']['rackProducts'][$shelvesNo][$productKey]['id'] : '';
                unset($_SESSION['config']['rackProducts'][$shelvesNo][$productKey]);
            }
            $rackArrayProduct = array();
            foreach ($_SESSION['config']['rackProducts'][$shelvesNo] as $key => $value) {
                $rackArrayProduct[] = $value;
            }
            $_SESSION['config']['rackProducts'][$shelvesNo] = $rackArrayProduct;

                $replacedData = array();
                foreach ($productsData as $key => $value) {
                    $ids = explode(',', $value['productIds']);
                    $tmpProducts = '';
                    foreach ($ids as $k => $v) {
                        $replacedData[$key]['productIds'][$k] = $v;

                        if (($shelvesNo == $key) && (in_array($k, $productKeyArr))) {
                            unset($replacedData[$key]['productIds'][$k]);
                        } else {
                            $tmpProducts .= $v . ",";
                        }
                    }
                    $replacedData[$key]['productIds'] = rtrim($tmpProducts, ",");
                }
            $_SESSION['config']['shelvesProducts'] = json_encode($replacedData);
            $response['flag'] = 1;
            $response['msg'] = 'Product Removed Successfully';
            $response['action'] = 'remove';
            $response['replacedId'] = '';
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $response;
        }
        else {
           $response['flag'] = 0;
           $response['msg'] = 'You have not selected any Products';
           $response['action'] = 'remove';
           $response['replacedId'] = '';
           Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
           return $response;
        }
    }  

    public function actionEditProducts() {
        $response = array();
        $response['flag'] = 0;
        $response['msg'] = 'Plz try again later';
        $data = array();
        $data = Yii::$app->request->post();
        $shelvesNo = $data['dataShelves'];
        $productKey = $data['dataKey'];
        $replacedProductId = $data['product'];
        $productsData = json_decode($_SESSION['config']['shelvesProducts'], true);

        if ($data['remove'] == 'true') {
            $id = isset($_SESSION['config']['rackProducts'][$shelvesNo][$productKey]['id']) ? $_SESSION['config']['rackProducts'][$shelvesNo][$productKey]['id'] : '';
             unset($_SESSION['config']['rackProducts'][$shelvesNo][$productKey]);

            $rackArrayProduct = array();
            foreach ($_SESSION['config']['rackProducts'][$shelvesNo] as $key => $value) {
                $rackArrayProduct[] = $value;
            }
            $_SESSION['config']['rackProducts'][$shelvesNo] = $rackArrayProduct;

            $response['flag'] = 1;
            $response['msg'] = 'Product Removed Successfully';
            $response['action'] = 'remove';
            $response['replacedId'] = $replacedProductId;

            $replacedData = array();
            foreach ($productsData as $key => $value) {
                $ids = explode(',', $value['productIds']);
                $tmpProducts = '';
                foreach ($ids as $k => $v) {
                    $replacedData[$key]['productIds'][$k] = $v;

                    if (($shelvesNo == $key) && ($k == $productKey)) {
                        unset($replacedData[$key]['productIds'][$k]);
                    } else {
                        $tmpProducts .= $v . ",";
                    }
                }
                $replacedData[$key]['productIds'] = rtrim($tmpProducts, ",");
            }
            $_SESSION['config']['shelvesProducts'] = json_encode($replacedData);
        }
        
        
        if ($data['edit'] == 'true') {           
            $height = $_SESSION['config']['rackProducts'][$shelvesNo][$productKey]['height'];
            $width = $_SESSION['config']['rackProducts'][$shelvesNo][$productKey]['width'];
            $id = $_SESSION['config']['rackProducts'][$shelvesNo][$productKey]['id'];

            $repository = new CataloguesRepository();
            if(isset($replacedProductId) && !$replacedProductId == ""){
            $filterData['products_id'] = $replacedProductId;
            $returnData = $repository->listing($filterData);
            if ($returnData['status']['success'] == 1) { 
                $racksWidth = array_sum(array_column($_SESSION['config']['rackProducts'][$shelvesNo], 'width'));
                $countWidth = intval($racksWidth - $width);
                if (($_SESSION['config']['width_of_shelves'] >= ($countWidth + intval($returnData['data']['catalogues'][0]['width'])))) {
                if(($_SESSION['config']['height_of_shelves'])/($_SESSION['config']['no_of_shelves']) >= $returnData['data']['catalogues'][0]['height']){
                     if($_SESSION['config']['depth_of_shelves'] >= $returnData['data']['catalogues'][0]['length']){
                    $_SESSION['config']['rackProducts'][$shelvesNo][$productKey] = '';
                    $_SESSION['config']['rackProducts'][$shelvesNo][$productKey] = array(
                        'id' => $replacedProductId,
                        'image' => CommonHelper::getImage(UPLOAD_PATH_CATALOGUES_IMAGES . $returnData['data']['catalogues'][0]['image']),
                        'height' => $returnData['data']['catalogues'][0]['height'],
                        'width' => $returnData['data']['catalogues'][0]['width'],
                    );
                    unset($returnData['data']['catalogues'][0]['market']['marketSegmentData']);
                    unset($returnData['data']['catalogues'][0]['market']['productCategory']);
                    unset($returnData['data']['catalogues'][0]['market']['market']);
                    $returnData['data']['catalogues'][0]['productCategory'] = $returnData['data']['catalogues'][0]['productCategory']['name'];
                    $returnData['data']['catalogues'][0]['marketName'] = $returnData['data']['catalogues'][0]['market']['title'];
                    $returnData['data']['catalogues'][0]['brandName'] = $returnData['data']['catalogues'][0]['brand']['name'];
                    $returnData['data']['catalogues'][0]['market'] = array('markt_title' => $returnData['data']['catalogues'][0]['market']['title']);
                    $_SESSION['config']['products'][$replacedProductId] = $returnData['data']['catalogues'][0];
                    $response['flag'] = 1;
                    $response['msg'] = 'Product Edited Successfully';
                    $response['action'] = 'edit';
                    $response['replacedId'] = $replacedProductId;
                    $response['product'] = json_encode($_SESSION['config']['rackProducts'][$shelvesNo][$productKey]);
                    $replacedData = array();

                    foreach ($productsData as $key => $value) {
                        $ids = explode(',', $value['productIds']);
                        $tmpProducts = '';
                        foreach ($ids as $k => $v) {

                            $replacedData[$key]['productIds'][$k] = $v;
                            if (($shelvesNo == $key) && ($k == $productKey)) {

                                $tmpProducts .= $replacedProductId . ",";
                            } else {
                                $tmpProducts .= $v . ",";
                            }
                        }
                        $replacedData[$key]['productIds'] = rtrim($tmpProducts, ",");
                    }

                    $_SESSION['config']['shelvesProducts'] = json_encode($replacedData);
                }else{
                     $response['msg'] = 'Please Try Other product';
                }}else{
                      $response['msg'] = 'Please Try Other product';
                }}else{
                    $response['msg'] = 'Please Try Other product';
                }
            }
            }else{
                $response['msg'] = 'Please Select One product';
            }
            }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $response;
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

    public function actionDelete($id ='',$storeId = '') {  
        $currentUser  = CommonHelper::getUser();
        if(Stores::findOne($storeId)){
        $model = Stores::findOne($storeId);
        
        $this->checkUserAccess($currentUser, $model);
        
        if($this->findModel($id)->delete()){
          
        if(ConfigFeedback::findOne(['config_id' => $id])){
               ConfigFeedback::findOne(['config_id' => $id])->delete();
        }
        
        if(ShelfDisplay::findOne(['config_id' => $id])){
               ShelfDisplay::findOne(['config_id' => $id])->delete();
        }
  
        return $this->redirect(['store-configuration/listing/'.$storeId]);
        }
    }
    }

    protected function findModel($id) {
        if ($model = StoreConfiguration::findOne(['id'=>$id])) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    protected function checkUserAccess($currentUser,$stores){
        if ($currentUser->role_id != Yii::$app->params['superAdminRole']) {
                $assign_to = !empty($stores['assign_to']) ? $stores['assign_to'] : '';

                $userObj = new User;
                $childUser = $userObj->getAllChilds(array($currentUser->id));
                $childUser[] = $currentUser->id;

                if (!empty($childUser) && !in_array($assign_to, $childUser)) {
                    throw new NotFoundHttpException('you are not allowed to access this page.');
                }
        }
    }

}
