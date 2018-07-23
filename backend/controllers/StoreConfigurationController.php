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
use Mpdf\Mpdf;
use common\components\Email;
use common\models\User;
use common\models\Questions;
use common\models\ConfigFeedback;
use common\models\Ratings;

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
                        'actions' => ['index', 'listing', 'update-config', 'delete'],
                        'allow' => true,
                        'roles' => ['&'],
                    ],
                        [
                        'actions' => ['send-mail', 'feedback', 'create', 'view', 'review-store', 'save-image', 'update', 'save-image', 'save-data', 'save-product-data', 'modal-content', 'get-products', 'edit-products', 'save-config-data'],
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
//create and update config data
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
            //send config mail
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
        $filter = array();
        $filter['config_id'] = $id;
        $feedBackResponse = array();
        $configData = $storeConfig->listing($filter);

        $questionsModel = new Questions();
        $feedback = new ConfigFeedback();

        $feedBackList = ConfigFeedback::find()->andWhere(['config_id' => $id])->asArray()->all();

        $rating = new Ratings();
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

//send feedback and give rating by superior  
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
//                  Yii::$app->session->setFlash('danger', $returnData['status']['message']);
                $flag = 1;
            }
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $flag;
    }
//save config thumb and original image
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
            if ($currentUser->role_id != Yii::$app->params['superAdminRole']) {
                $assign_to = !empty($stores['assign_to']) ? $stores['assign_to'] : '';

                $userObj = new User;
                $childUser = $userObj->getAllChilds(array($currentUser->id));
                $childUser[] = $currentUser->id;
                if (!empty($childUser) && in_array($assign_to, $childUser)) {
                     $reviewFlag = 1;
                }
                if (!empty($childUser) && !in_array($assign_to, $childUser)) {
                    throw new NotFoundHttpException('you are not allowed to access this page.');
                }
            }
       
            //CONGig Data
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
                        'store_id' => $storeId,
                        'is_update' => 1,
                        'brandThumbId' => $brandThumbId,
                        'configId' => $configId,
                        'display_name' => $display_name,
                        'reviewFlag' => $reviewFlag
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
          
            if ($currentUser->role_id != Yii::$app->params['superAdminRole']) {
                $assign_to = !empty($stores['assign_to']) ? $stores['assign_to'] : '';

                $userObj = new User;
                $childUser = $userObj->getAllChilds(array($currentUser->id));
                $childUser[] = $currentUser->id;

                if (!empty($childUser) && !in_array($assign_to, $childUser)) {
                    throw new NotFoundHttpException('you are not allowed to access this page.');
                }
            }
            if ($stores['assign_to'] == $currentUser->id) {
                $canCreateNewConfig = 1;
            }
            
            if ($currentUser->role_id == Yii::$app->params['superAdminRole']) {
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

    public function actionIndex($id) {
        $storeId = $id;
        $stores = Stores::find()->where(['id' => $id])->asArray()->one();
        $currentUser = CommonHelper::getUser();

        if ($stores) {
            if ($currentUser->role_id != Yii::$app->params['superAdminRole']) {
                $assign_to = !empty($stores['assign_to']) ? $stores['assign_to'] : '';

                $userObj = new User;
                $childUser = $userObj->getAllChilds(array($currentUser->id));
                $childUser[] = $currentUser->id;

                if (!empty($childUser) && !in_array($assign_to, $childUser)) {
                    throw new NotFoundHttpException('you are not allowed to access this page.');
                }
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

            $searchModel = new CataloguesSearch();
            $dataProvider = $searchModel->search($filterProduct);

            return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'brand' => $brand,
                    'store_id' => $id,
                    'is_update' => 0,
                    'brandThumbId' => 0,
                    'configId' => 0,
                    'reviewFlag' => 0,
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

        $_SESSION['config']['ratio'] = $post['ratio'];
        $_SESSION['config']['display_name'] = $post['display_name'];

        $searchModel = new BrandRepository();
        $filters['brand_id'] = $_SESSION['config']['brands'];
        $dataProvider = $searchModel->listing($filters);
        $brandsData = array();
        if ($dataProvider['status']['success'] == 1 && (!empty($dataProvider['data']['brand']))) {
            $brandsData = $dataProvider['data']['brand'];
        }
        $_SESSION['config']['brands_data'] = $brandsData;
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
//                    $market = $data[0]['market'];
//                    $marketRule['markt_title'] = $market['title'];
//                    $rules = $market['marketSegmentData'][0]['marketSegment']['marketRules'];
//                    foreach ($rules as $ruleKey => $ruleValue) {
//                        $rulesArray[$ruleKey] = $ruleValue['rules'];
//                    }
//                    foreach ($rulesArray as $rulekey => $rulevalue) {
//
//                        $rulesId[$rulekey]['id'] = $rulevalue['id'];
//                        $rulesId[$rulekey]['product_fields'] = $rulevalue['product_fields'];
//                        $rulesId[$rulekey]['detail'] = $rulevalue['detail'];
//                    }
//                    unset($data[0]['market']);
                    $dataIds[$key] = $data[0];
                    $dataIds[$key]['is_top_shelf'] = '';
                    $dataIds[$key]['market'] = $marketRule;
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
            unset($dataIds[$key]['product_category_id']);
            unset($dataIds[$key]['product_sub_category_id']);
            unset($dataIds[$key]['product_type_id']);
            unset($dataIds[$key]['market_id']);
            unset($dataIds[$key]['brand_id']);
            unset($dataIds[$key]['sku']);
            unset($dataIds[$key]['ean']);
            unset($dataIds[$key]['manufacturer']);
        }
        $selvesWidth = $_SESSION['config']['width_of_shelves'];
        $selvesHeight = $_SESSION['config']['height_of_shelves'];
        $selvesDepth =$_SESSION['config']['depth_of_shelves'];
        $selevesCount = $_SESSION['config']['no_of_shelves'];

        if ($this->ifRuleContain(\yii::$app->params['configArray']['top_shelf'])) {
            foreach ($dataIds as $dataKey => $dataValue) {
                if ($dataValue['top_shelf'] == '1') {
                    
                    $this->ruleTopShelf($dataValue, $racksProductArray[0], $selvesWidth);
                }
            }
        }

        if (isset($racksProductArray[0]) && (!empty($racksProductArray[0]))) {
            if (count($racksProductArray[0]) > 0) {
                $this->applySortingRule($racksProductArray[0]);
            }
        }

        $shelfIndex = (isset($racksProductArray[0]) && count($racksProductArray[0]) > 0 ) ? 1 : 0;
        foreach ($dataIds as $value) {

            if ($value['top_shelf'] == '1') {
                continue;
            }
            $sum = 0;
            if (!empty($racksProductArray[$shelfIndex])) {

                foreach ($racksProductArray[$shelfIndex] as $rackValue) {
                    $sum = $sum + $rackValue['width'];
                }
            }

            if (intval($selvesWidth) >= intval(intval($sum) + intval($value['width']))) {

                if ((intval(($selvesHeight) / ($selevesCount)) >= intval($value['height'])) && (intval($selvesDepth) >= intval($value['length']))) {
                   
                    if (empty($racksProductArray[$shelfIndex])) { 
                        $racksProductArray[$shelfIndex][] = $value;
                      
                    } else {
                        array_push($racksProductArray[$shelfIndex], $value);
                    }
                }
            } else {
                if (intval($shelfIndex) < ((intval($selevesCount)) - 1)) {
                    $shelfIndex = intval($shelfIndex) + 1;
                }
            }
        }
       
        foreach ($racksProductArray as $key =>$value){
 
        $this->applySortingRule($racksProductArray);
        }
     
        $this->fillUpEmptySpaceOfShelves($racksProductArray, $selvesWidth, $selevesCount);
        //all repeated products
     
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

    //edit and remove product's modal content
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
//action of edit and remove products
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

//                            foreach ($productsData as $key => $value){
//                                $ids = explode(',', $value['productIds']);
//                                $tmpProducts = '';
//                                foreach ($ids as $k =>$v){
//                                    $replacedData[$key]['productIds'][$k] = $v;
//                                    if(($shelvesNo == $key) && ($k == $productKey)){
//                                         $tmpProducts .= $id.",";
//                                    }else{
//                                         $tmpProducts .= $racksValue['id'].",";
//                                    }
//                                    $replacedData[$key]['productIds'] = rtrim($tmpProducts,",");
//                                }
//                            }

            $_SESSION['config']['shelvesProducts'] = json_encode($replacedData);
        }
        if ($data['edit'] == 'true') {           
            $height = $_SESSION['config']['rackProducts'][$shelvesNo][$productKey]['height'];
            $width = $_SESSION['config']['rackProducts'][$shelvesNo][$productKey]['width'];
            $id = $_SESSION['config']['rackProducts'][$shelvesNo][$productKey]['id'];

            $repository = new CataloguesRepository();
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
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $response;
    }

    private function fillUpEmptySpaceOfShelves(&$racksProductArray, $selvesWidth, $selevesCount) {
        $products = array();
        $arrayProducts = $racksProductArray;
        $selvesWidth = doubleval($selvesWidth);
        if ($this->ifRuleContain(\yii::$app->params['configArray']['market_share_count'])) {

            $productCount = count($racksProductArray);

            if ($productCount != 0) {
                for ($i = 0; $i < $selevesCount; $i++) {

                    $products = (isset($racksProductArray[$i]) && (!empty($racksProductArray[$i]))) ? $racksProductArray[$i] : '';

                    if ((empty($products)) && ($i > 0) && (isset($racksProductArray[$i - 1]))) {

                        $products = $racksProductArray[$i] = $racksProductArray[$i - 1];
                    }
                    $min = $sum = 0;
                    if (!empty($products)) {
                        $min = (min(array_column($products, 'width')) == 0) ? 1 : min(array_column($products, 'width'));
                        $sum = array_sum(array_column($products, 'width'));
                    }
                    $diff = intval($selvesWidth - $sum);
                    if (!empty($products)) {
                        if ($diff > $min) {
                            $sumOfMarketShare = array_sum(array_column($products, 'market_share'));
                            $sumOfMarketShare = ($sumOfMarketShare == 0) ? 1 : $sumOfMarketShare;
                            $noOfPlaces = intval(($selvesWidth) / ($min));

                            foreach ($products as $marketShareValue) {
                                $repeatCount = intval($marketShareValue['market_share'] * $noOfPlaces) / ($sumOfMarketShare);

                                for ($j = 0; $j < $repeatCount; $j++) {
                                    $tempSum = array_sum(array_column($racksProductArray[$i], 'width'));
                                    if ($selvesWidth >= ($tempSum + $marketShareValue['width'])) {
                                        array_push($racksProductArray[$i], $marketShareValue);
                                    }
                                }
                            }
                        }
                    }
                }
            }
         
            foreach ($racksProductArray as $key =>$value){
            $this->applySortingRule($racksProductArray[$key]);
            }
        }
    }

    private function ruleTopShelf($dataValue, &$racksProductArray, $selvesWidth) {
       $sum = 0;
        if (!empty($racksProductArray)) {
            $sum = array_sum(array_column($racksProductArray, 'width'));
        }
        if ($selvesWidth >= ($sum + $dataValue['width'])) {
            if (intval(($_SESSION['config']['height_of_shelves']) / ($_SESSION['config']['no_of_shelves'])) >= intval($dataValue['height'])) {
                if (intval($_SESSION['config']['depth_of_shelves']) >= intval($dataValue['length'])) {
                    $racksProductArray[$dataValue['id']] = $dataValue;
                }
            }
        }
    }

    private function applySortingRule(&$racksProductArray) {
        
        $this->sort_array_of_array($racksProductArray, 'id', SORT_DESC);
    
        if ($this->ifRuleContain(\yii::$app->params['configArray']['market_share'])) {            
            $sort = SORT_DESC;
            $this->sort_array_of_array($racksProductArray, 'market_share', $sort);
        }
        //price
        if ($this->ifRuleContain(\yii::$app->params['configArray']['price'])) {
            $sort = SORT_ASC;
            $this->sort_array_of_array($racksProductArray, 'price', $sort);
        }

        //height rule
        if ($this->ifRuleContain(\yii::$app->params['configArray']['size_height'])) {
            $sort = SORT_ASC;
            $this->sort_array_of_array($racksProductArray, 'height', $sort);
        }
        //gift box 
        if ($this->ifRuleContain(\yii::$app->params['configArray']['gift_box'])) {
            $giftProduct = $otherProduct = array();

            $skipBoxCheck = 0; //skip box check if shelf is already filled up with total market share
            foreach ($racksProductArray as $key => $value) {
                if(isset($value['box_only'])){
                if ($value['box_only'] == 1) {
                    array_push($giftProduct, $value);
                }
                if ($value['box_only'] == 0) {
                    array_push($otherProduct, $value);
                }
                }
                else
                {
                    $skipBoxCheck = 1;
                }
            }

            if(!$skipBoxCheck)
            {
                    $mergedArray = array_merge($giftProduct, $otherProduct);
                    $racksProductArray = $mergedArray;
            }
        }
    }

    public function sort_array_of_array(&$array, $subfield, $sort) {
        $sortarray = array();
        foreach ($array as $key => $row) {
            $sortarray[$key] = isset($row[$subfield]) ? $row[$subfield] : '';
        }

        array_multisort($sortarray, $sort, $array);
    }

    private function ifRuleContain($ruleValue) {
        $rulesArray = array();
        if (isset($_SESSION['config']['rules']) && !empty($_SESSION['config']['rules'])) {
            $rules = $_SESSION['config']['rules'];
            foreach ($rules as $key => $value) {
                $rulesArray[] = $value['product_fields'];
            }
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

    public function actionSendMail($thumb) 
    {
        $mpdf = new Mpdf();
        $thumImage = explode('/', $thumb);
        $thumb = end($thumImage);

        $user = CommonHelper::getUser();

        $parentEmail = '';
        $userRepository = new UserRepository();
        if ($user['parent_user_id'] != '') {
            $fiterUser = array();
            $fiterUser['id'] = $user['parent_user_id'];
            $parentUser = $userRepository->userList();
            if ($parentUser['status']['success'] == 1) {

                $parentEmail = $parentUser['data']['users'][0]['email'];
                $parentFirstName = $parentUser['data']['users'][0]['first_name'];
                $parentLastName = $parentUser['data']['users'][0]['last_name'];
            }
        }
       
        $userEmail = $user['email'];
        $firstName = !empty($user['first_name']) ? $user['first_name'] : '';
        $lastName = !empty($user['last_name']) ? $user['last_name'] : '';
        $userId = !empty($user['id']) ? $user['id'] : '';
        $shelfImage = CommonHelper::getPath('upload_path') . UPLOAD_PATH_STORE_CONFIG_ORIGINAL_IMAGES . $thumb;

        $pdfFileName = CommonHelper::getPath('upload_path') . UPLOAD_PATH_STORE_CONFIG_PDF . Yii::$app->formatter->asTimestamp(date('Y-d-m h:i:s')) . '' . $userId . '.pdf';

        $mpdf->WriteHTML($this->renderPartial('shelfPdf', ['image' => $shelfImage], true));
        $mpdf->Output($pdfFileName, 'F');
        
        $mail = new Email();
        $mail->email = $userEmail;
        $userString = array();
        $userString[] = $firstName;
        $userString[] = $lastName;
        $mail->body = $this->renderPartial('shelfMail');
        $mail->setFrom = Yii::$app->params['supportEmail'];
        $mail->subject = 'Store Shelf PDF';
        $mail->attachment = (Array($pdfFileName));
        $mail->set("NAME", implode(' ', $userString));
        $mail->send();

        //send mail to parent user if exist
        if ($parentEmail != '') {
            $mail = new Email();
            $mail->email = $parentEmail;
            $userString = array();
            $userString[] = $parentFirstName;
            $userString[] = $parentLastName;
            $mail->body = $this->renderPartial('shelfMail');
            $mail->setFrom = Yii::$app->params['supportEmail'];
            $mail->subject = 'Store Shelf PDF';
            $mail->attachment = (Array($pdfFileName));
            $mail->set("NAME", implode(' ', $userString));
            $mail->send();
        }

        if (file_exists($pdfFileName)) 
        {
            @unlink($pdfFileName);
        }
    }

}
