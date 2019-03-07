<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use common\helpers\CommonHelper;
use common\repository\MarketBrandsRepository;
use common\repository\CataloguesRepository;
use common\repository\QuestionsRepository;
use common\repository\MarketRulesRepository;
use common\repository\MarketRepository;
use yii\data\ArrayDataProvider;
use common\repository\StoreConfigRepository;
use common\models\User;
use common\models\MarketBrands;
use common\models\MarketBrandsVerietals;
use common\models\ProductCategories;
use common\models\Brands;
use common\models\Catalogues;
use common\models\MarketCategoryProduct;
use common\models\ProductVarietal;
use common\models\ProductVarietalSearch;
use common\models\BrandsSearch;

class StoresConfigController extends BaseApiController {

    public $modelClass = 'common\models\Brands';

//    public function behaviors()
//    {
//        $behaviors = parent::behaviors();
//        $behaviors['access'] = [
//            'class' => AccessControl::className(),
//            'ruleConfig' => [
//                'class' => \common\components\AccessRule::className(),
//            ],
//            'rules' => [
//                [
//                    'actions' => ['brand-list','question-list','market-rule-list','configuration','listing','rating'],
//                    'allow' => true,
//                    'roles' => ['&'],
//                ],
//            ],
//        ];
//        return $behaviors;
//    }

    public function actions() {
        $actions = parent::actions();

        unset($actions['brand-list']);
        unset($actions['question-list']);
        unset($actions['market-rule-list']);
        unset($actions['market-rule-list']);
        unset($actions['configuration']);
        unset($actions['listing']);
        unset($actions['rating']);
        unset($actions['brand-product-list']);
        unset($actions['new-brand-product-list']);
        return $actions;
    }

    public function actionBrandList() {

        $currentUser = CommonHelper::getUser();

        $marketId = '';
        if (isset($currentUser->market_id) && ($currentUser->market_id != '')) {
            $marketId = $currentUser->market_id;
        }

        $returnDatas = array();
        $repository = new MarketBrandsRepository();

        if ($marketId != '') {
            $data['market_id'] = $marketId;
            $returnData = $repository->listing($data);
            $brandId = array();
            if ($returnData['status']['success'] == 1) {
                if (!empty($returnData['data']['market_brands'])) {

                    foreach ($returnData['data']['market_brands'] as $key => $value) {

                        $image = $value['brand']['image'];

                        unset($value['brand']['created_by']);
                        unset($value['brand']['updated_by']);
                        unset($value['brand']['created_by']);
                        unset($value['brand']['deleted_by']);
                        unset($value['brand']['created_at']);
                        unset($value['brand']['updated_at']);
                        unset($value['brand']['deleted_at']);
                        $value['brand']['image'] = isset($value['brand']['image']) ? CommonHelper::getPath('upload_url') . UPLOAD_PATH_BRANDS_IMAGES . $image : '';
                        $value['brand']['color_code'] = isset($value['brand']['color_code']) && ($value['brand']['color_code'] != '') ? $value['brand']['color_code'] : COLOR_CODE;
                        $value['brand']['reorder_id'] = $value['reorder_id'];
                        $product = $value['brand']['product'];

                        foreach ($product as $key1 => $value1) {
                            $imageProduct = $value1['image'];
                            $box_only = $value1['box_only'];
                            $top_shelf = $value1['top_shelf'];
                            unset($value['brand']['product'][$key1]['image']);
                            unset($value['brand']['product'][$key1]['top_shelf']);
                            unset($value['brand']['product'][$key1]['box_only']);
                            $value['brand']['product'][$key1]['image'] = isset($imageProduct) && ($imageProduct != '') ? CommonHelper::getPath('upload_url') . UPLOAD_PATH_CATALOGUES_IMAGES . rawurlencode($imageProduct) : '';
                            $value['brand']['product'][$key1]['top_shelf'] = \yii::$app->params['catalogue_status'][$top_shelf];
                            $value['brand']['product'][$key1]['box_only'] = \yii::$app->params['catalogue_status'][$box_only];
                        }
                        $returnDatas['marketBrands'][$key] = $value['brand'];
                    }
                }
            }
        }

        return $returnDatas;
    }

    public function actionQuestionList() {
        $question = new QuestionsRepository();
        $list = $question->listing();
        return $list;
    }

    public function actionMarketRuleList() {
        $user = CommonHelper::getUser();
        $question = new MarketRepository();
        $filter = array();
        $filter['market_id'] = $user['market_id'];
        $list = $question->marketList($filter);
        $dataArry = array();
        $marketSegmentData = $list['data']['markets'][0]['marketSegmentData'];
        unset($list['data']['markets']);
        foreach ($marketSegmentData as $key => $value) {
            $marketRules = $value['marketSegment']['marketRules'];
            $dataArry['marketSegmentData'][$key]['id'] = $value['id'];
            $dataArry['marketSegmentData'][$key]['market_id'] = $value['market_id'];
            $dataArry['marketSegmentData'][$key]['market_segment_id'] = $value['market_segment_id'];
            $dataArry['marketSegmentData'][$key]['title'] = $value['marketSegment']['title'];
            $dataArry['marketSegmentData'][$key]['description'] = $value['marketSegment']['description'];
            $rulesArrry = array();
            $rules = $value['marketSegment']['marketRules'];
            foreach ($rules as $key1 => $value1) {
                if ($value1['market_id'] == $filter['market_id']) {
                    $rulesArrry[] = $value1['rules'];
                }
            }
            $dataArry['marketSegmentData'][$key]['marketRules'] = $rulesArrry;
        }
        return $dataArry;
    }

    public function actionConfiguration() {

        $data = Yii::$app->request->post('config');

        $configData = json_decode(stripcslashes(trim($data, '"')), true);

        $storeConfig = new StoreConfigRepository();
        if (isset($configData['config_id']) && ($configData['config_id'] != '')) {
            $returnData = $storeConfig->updateConfig($configData);
        } else {
            $returnData = $storeConfig->createConfig($configData);
        }
        return $returnData;
    }

    public function actionListing() {
        $storeConfig = new StoreConfigRepository();

        $data = Yii::$app->request->post();
        $user = CommonHelper::getUser();

        if (!isset($data['store_id']) || ($data['store_id'] == 0)) {
            $userObj = new User;

            $childUser = $userObj->getAllChilds(array($user->id));
            array_push($childUser, $user->id);

            $data['created_by'] = $childUser;
        }

        $returnData = $storeConfig->listing($data);

        $limit = Yii::$app->params['pageSize'];
        $data['per-page'] = Yii::$app->params['pageSize'];

        $data['page'] = 1;
        if (isset($data['pageNumber']) && ($data['pageNumber'] != '')) {
            $data['page'] = $data['pageNumber'];
            $_GET['page'] = $data['page'];
        }

        if (isset($data['sort']) && ($data['sort'] != '')) {

            if ($data['sort'] == 'StoreName A-Z') {
                $data['sort'] = 'storeName';
            }
            if ($data['sort'] == 'StoreName Z-A') {
                $data['sort'] = '-storeName';
            }

            if ($data['sort'] == 'configName A-Z') {
                $data['sort'] = 'config_name';
            }

            if ($data['sort'] == 'configName Z-A') {
                $data['sort'] = '-config_name';
            }

            if ($data['sort'] == 'CityName A-Z') {
                $data['sort'] = 'cityName';
            }
            if ($data['sort'] == 'CityName Z-A') {
                $data['sort'] = '-cityName';
            }
            if ($data['sort'] == 'market A-Z') {
                $data['sort'] = 'marketId';
            }
            if ($data['sort'] == 'market Z-A') {
                $data['sort'] = '-marketId';
            }
            if ($data['sort'] == 'Visit Old to new') {
                $data['sort'] = 'id';
            }
            if ($data['sort'] == 'Visit New to Old') {
                $data['sort'] = '-id';
            }

            $_GET['sort'] = $data['sort'];
        }



        $dataValue = $returnData['data']['stores_config'];

        foreach ($dataValue as $keyV => $valueV) {
            $temp = array();
            $shelfDisplay = $valueV['shelfDisplay'];

            $shelf_thumb = $valueV['shelf_thumb'];
            unset($dataValue[$keyV]['shelf_thumb']);
            $dataValue[$keyV]['shelf_thumb'] = CommonHelper::getImage(UPLOAD_PATH_STORE_CONFIG_IMAGES . $shelf_thumb);
            foreach ($shelfDisplay as $key => $value) {

                $productIds = json_decode($value['shelf_config'], true);
                foreach ($productIds as $key2 => $value2) {
                    $productId = explode(',', $value2['productIds']);
                    foreach ($productId as $productKey => $productValue) {
                        $catalogueRepository = new CataloguesRepository();
                        $productIdData['products_id'] = $productValue;
                        $productArray = array();
                        $product = $catalogueRepository->listing($productIdData);

                        if ($product['status']['success'] == 1) {
                            $productArray = $product['data']['catalogues'][0];
                            unset($productArray['market']);
                            unset($productArray['brand']);
                            unset($productArray['productType']);
                            unset($productArray['productCategory']);
                        }
                        $image = $productArray['image'];
                        unset($productArray['image']);
                        unset($dataValue[$keyV]['shelfDisplay']);
                        $productArray['image'] = CommonHelper::getImage(UPLOAD_PATH_CATALOGUES_IMAGES . $image);
                        $temp['shelf_config'][$key2]['productIds'][$productKey] = $productArray;
                    }
                }
            }
            $stores = $dataValue[$keyV]['stores'][0];
            unset($dataValue[$keyV]['stores'][0]);
            $dataValue[$keyV]['stores'] = $stores;
            $dataValue[$keyV]['storeName'] = $stores['name'];
            $dataValue[$keyV]['cityName'] = $stores['city_id'];
            $dataValue[$keyV]['marketId'] = $stores['market_id'];


            unset($dataValue[$keyV]['shelfDisplay']);
//                $dataValue[$keyV]['shelfDisplay']['display_name'] = $shelfDisplay[0]['display_name'];
//                $dataValue[$keyV]['shelfDisplay']['no_of_shelves'] = $shelfDisplay[0]['no_of_shelves'];
//                $dataValue[$keyV]['shelfDisplay']['height_of_shelves'] = $shelfDisplay[0]['height_of_shelves'];
//                $dataValue[$keyV]['shelfDisplay']['width_of_shelves'] = $shelfDisplay[0]['width_of_shelves'];
//                $dataValue[$keyV]['shelfDisplay']['depth_of_shelves'] = $shelfDisplay[0]['depth_of_shelves'];
//                $dataValue[$keyV]['shelfDisplay']['brand_thumb_id'] = $shelfDisplay[0]['brand_thumb_id'];
//                $dataValue[$keyV]['shelfDisplay']["shelf_config"] = $temp['shelf_config'];


            $tmpShelfDisplayArray = array();
            $tmpShelfDisplayArray['display_name'] = $shelfDisplay[0]['display_name'];
            $tmpShelfDisplayArray['no_of_shelves'] = $shelfDisplay[0]['no_of_shelves'];
            $tmpShelfDisplayArray['height_of_shelves'] = $shelfDisplay[0]['height_of_shelves'];
            $tmpShelfDisplayArray['width_of_shelves'] = $shelfDisplay[0]['width_of_shelves'];
            $tmpShelfDisplayArray['depth_of_shelves'] = $shelfDisplay[0]['depth_of_shelves'];
            $tmpShelfDisplayArray['brand_thumb_id'] = $shelfDisplay[0]['brand_thumb_id'];
            $tmpShelfDisplayArray["shelf_config"] = $temp['shelf_config'];
            $dataValue[$keyV]['shelfDisplay'][] = $tmpShelfDisplayArray;
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $dataValue,
            'pagination' => [
                'pageSize' => $limit
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
                'attributes' =>
                    [
                    'id',
                    'config_name',
                    'cityId',
                    'distance',
                    'name',
                    'market',
                    'marketSegment',
                    'address1',
                    'assignTo',
                ],
            ]
        ]);
        return $dataProvider;
    }

    public function actionRating() {
        $data = Yii::$app->request->post();

        $storeConfig = new StoreConfigRepository();

        $returnData = $storeConfig->createRating($data);
        return $returnData;
    }

//    public function actionBrandProductList() {
//
//        $currentUser = CommonHelper::getUser();
//        $marketId = '';
//        if (isset($currentUser->market_id) && ($currentUser->market_id != '')) {
//            $marketId = $currentUser->market_id;
//        }
//
//        $returnDatas = $newProductArry = $marketVarientalProduct = $brandVariental = array();
//        $repository = new MarketBrandsRepository();
//
//        if ($marketId != '') {
//            $data['market_id'] = $marketId;
//            $returnData = $repository->listing($data);
//
//            $productsData = $returnData['data']['market_product'];
//            $brand_variental = $returnData['data']['brand_varietal'];
//            // echo '<pre>';print_r($productsData); exit;
//            if ($productsData) {
//                foreach ($productsData as $key => $value) {
//                    $newProductArry[$value['category_id']] = $value['category']['marketCategoryProduct'];
//                }
//            }
//            $newProductFinalArry = array();
//            foreach ($newProductArry as $k => $v) {
//                foreach ($v as $key => $value) {
//                    $category_id = $value['category_id'];
//                    unset($value['id']);
//                    unset($value['product_id']);
//                    unset($value['category_id']);
//                    unset($value['market_id']);
//                    unset($value['created_by']);
//                    unset($value['updated_by']);
//                    unset($value['deleted_by']);
//                    unset($value['created_at']);
//                    unset($value['updated_at']);
//                    unset($value['deleted_at']);
//                    $newProductFinalArry[$category_id][] = $value['product'];
//                }
//            }
//
//
//            $collectCatId = $collectMarketId = [];
//            if (!empty($returnData['data']['market_brands'])) {
//
//                if ($returnData['data']['market_varietal']) {
//                    foreach ($returnData['data']['market_varietal'] as $k => $v) {
//                        $marketVarientalProduct[$v['product_category_id']][$v['brand_id']][$v['product_variental']][] = $v;
//                    }
//                }
////            echo '<pre>';
////            print_r($marketVarientalProduct);
////            print_r($brand_variental);
////            exit;
////                    
////                foreach ($brand_variental as $k=>$v){
////                    $brandVariental[]
////                }
//
//                foreach ($returnData['data']['market_brands'] as $key => $value) {
//
//                    $tempValue = $value;
//                    unset($tempValue['category']);
//                    unset($tempValue['brand']);
//
//                    if (!in_array($value['market_id'], $collectMarketId)) {
//                        $returnDatas["market"] = $tempValue;
//                        $collectMarketId[] = $value['market_id'];
//                    }
//
//
//                    $image = $value['brand']['image'];
//                    unset($value['brand']['created_by']);
//                    unset($value['brand']['updated_by']);
//                    unset($value['brand']['created_by']);
//                    unset($value['brand']['deleted_by']);
//                    unset($value['brand']['created_at']);
//                    unset($value['brand']['updated_at']);
//                    unset($value['brand']['deleted_at']);
//                    $value['brand']['image'] = isset($value['brand']['image']) ? CommonHelper::getPath('upload_url') . UPLOAD_PATH_BRANDS_IMAGES . $image : '';
//                    $value['brand']['color_code'] = isset($value['brand']['color_code']) && ($value['brand']['color_code'] != '') ? $value['brand']['color_code'] : COLOR_CODE;
//                    $value['brand']['reorder_id'] = $value['reorder_id'];
//                    $variental = $value['brand']['marketBrandsVerietals'];
////                                    echo '<pre>';
////                                    print_r($variental);exit;
//                        foreach ($variental as $vKey => $vVal) {
//                            if (isset($value['brand']['marketBrandsVerietals'][$vKey])) {
//                                $value['brand']['marketBrandsVerietals'][$vKey]['product'] = isset($marketVarientalProduct[$value['category']['id']][$value['brand']['id']][$vVal['verietal_id']]) ? $marketVarientalProduct[$value['category']['id']][$value['brand']['id']][$vVal['verietal_id']] : '';
//                            }
//                        }
//
//                    $product_data = '';
//
//
//
//                    unset($value['brand']['product']);
//
//                    if (!in_array($value['category']['id'], $collectCatId)) {
//                        // $value['category']['brand'][] = $value['brand'];
//                        $value['category']['top_shelf_product'] = isset($newProductFinalArry[$value['category']['id']]) ? $newProductFinalArry[$value['category']['id']] : '';
//                        $returnDatas["market"]['category'][] = $value['category'];
//
//
//                        $collectCatId[] = $value['category']['id'];
//                    }
//
//
//                    $key = array_search($value['category']['id'], array_column($returnDatas["market"]['category'], 'id'));
//                    //   echo '>>>>>'.$key;
//                    $returnDatas["market"]['category'][$key]['brand'][] = $value['brand'];
//                }
//            }
//        }
//        return $returnDatas;
//    }

    /*
     * action brand-product-list used get details of user such as different category,products,product varientals
     */
    public function actionBrandProductList() {

//        $currentUser = CommonHelper::getUser();
//        $marketId = '';
//        if (isset($currentUser->market_id) && ($currentUser->market_id != '')) {
//            $marketId = $currentUser->market_id;
//        }
//
//        $returnDatas = $newProductArry = $marketVarientalProduct = $brandVariental = $brandVarientalArr = array();
//        $repository = new MarketBrandsRepository();
//
//        if ($marketId != '') {
//            $data['market_id'] = $marketId;
//            $returnData = $repository->listing($data);
//            //echo '<pre>'; print_r($returnData); exit;
//            $productsData = $returnData['data']['market_product'];
////            echo '<pre>';
////            print_r($productsData);exit;
//            if ($productsData) {
//                foreach ($productsData as $key => $value) {
//                    $newProductArry[$value['category_id']] = $value['category']['marketCategoryProduct'];
//                }
//            }
//            $newProductFinalArry = array();
//            foreach ($newProductArry as $k => $v) {
//                foreach ($v as $key => $value) {
//                    $category_id = $value['category_id'];
//                    unset($value['id']);
//                    unset($value['product_id']);
//                    unset($value['category_id']);
//                    unset($value['market_id']);
//                    unset($value['created_by']);
//                    unset($value['updated_by']);
//                    unset($value['deleted_by']);
//                    unset($value['created_at']);
//                    unset($value['updated_at']);
//                    unset($value['deleted_at']);
//                    $newProductFinalArry[$category_id][] = $value['product'];
//                }
//            }
//
//            $brandVariental = MarketBrandsVerietals::find()->andWhere(['market_id' => $marketId])->asArray()->all();
//            foreach ($brandVariental as $brandVarientalKey => $brandVarientalVal) {
//                if ($brandVarientalVal['shares'] != null) {
//                    $brandVarientalArr[$brandVarientalVal['category_id']][$brandVarientalVal['brand_id']][] = $brandVarientalVal;
//                } else {
//                    $brandVarientalVal['shares'] = 0;
//                    $brandVarientalArr[$brandVarientalVal['category_id']][$brandVarientalVal['brand_id']][] = $brandVarientalVal;
//                }
//            }
//
//            $collectCatId = $collectMarketId = [];
//            if (!empty($returnData['data']['market_brands'])) {
//
//                if ($returnData['data']['market_varietal']) {
//                    foreach ($returnData['data']['market_varietal'] as $k => $v) {
//                        $marketVarientalProduct[$v['product_category_id']][$v['brand_id']][$v['product_variental']][] = $v;
//                    }
//                }
//
//                foreach ($returnData['data']['market_brands'] as $key => $value) {
//
//                    $tempValue = $value;
//                    unset($tempValue['category']);
//                    unset($tempValue['brand']);
//
//                    if (!in_array($value['market_id'], $collectMarketId)) {
//                        $returnDatas["market"] = $tempValue;
//                        $collectMarketId[] = $value['market_id'];
//                    }
//
//                    $image = $value['brand']['image'];
//                    unset($value['brand']['created_by']);
//                    unset($value['brand']['updated_by']);
//                    unset($value['brand']['created_by']);
//                    unset($value['brand']['deleted_by']);
//                    unset($value['brand']['created_at']);
//                    unset($value['brand']['updated_at']);
//                    unset($value['brand']['deleted_at']);
//                    $value['brand']['image'] = isset($value['brand']['image']) ? CommonHelper::getPath('upload_url') . UPLOAD_PATH_BRANDS_IMAGES . $image : '';
//                    $value['brand']['color_code'] = isset($value['brand']['color_code']) && ($value['brand']['color_code'] != '') ? $value['brand']['color_code'] : COLOR_CODE;
//
//                    $marketBrandShares = MarketBrands::find()->select(['shares'])->andWhere(['market_id' => $value['market_id'], 'brand_id' => $value['brand']['id'], 'category_id' => $value['category']['id']])->orderBy(['reorder_id' => SORT_ASC])->asArray()->all();
//                    if ($marketBrandShares) {
//                        foreach ($marketBrandShares as $k => $v) {
//                            $value['brand']['shares'] = $v['shares'];
//                        }
//                    }
//                    $value['brand']['reorder_id'] = $value['reorder_id'];
//
//                    $value['brand']['marketBrandsVerietals'] = isset($brandVarientalArr[$value['category']['id']][$value['brand']['id']]) ? $brandVarientalArr[$value['category']['id']][$value['brand']['id']] : '';
//
//                    $variental = $value['brand']['marketBrandsVerietals'];
//
//                    if (!empty($variental)) {
//                        foreach ($variental as $vKey => $vVal) {
//                            if (isset($value['brand']['marketBrandsVerietals'][$vKey])) {
//                                $value['brand']['marketBrandsVerietals'][$vKey]['product'] = isset($marketVarientalProduct[$value['category']['id']][$value['brand']['id']][$vVal['verietal_id']]) ? $marketVarientalProduct[$value['category']['id']][$value['brand']['id']][$vVal['verietal_id']] : '';
//                            }
//                        }
//                    }
//
//                    //$product_data = '';
//                    unset($value['brand']['product']);
//
//                    if (!in_array($value['category']['id'], $collectCatId)) {
//                        // $value['category']['brand'][] = $value['brand'];
//                        $value['category']['top_shelf_product'] = isset($newProductFinalArry[$value['category']['id']]) ? $newProductFinalArry[$value['category']['id']] : '';
//                        $returnDatas["market"]['category'][] = $value['category'];
//                        if (isset($value['category']['id'])) {
//                            $collectCatId[] = $value['category']['id'];
//                        }
//                    }
//
//                    if (isset($value['category']['id'])) {
//                        $key = array_search($value['category']['id'], array_column($returnDatas["market"]['category'], 'id'));
//
//                        $returnDatas["market"]['category'][$key]['brand'][] = $value['brand'];
//                    }
//                }
//            }
//        }
//        //if (!empty($returnDatas)) {
//        unset($returnDatas['market']['brand_id']);
//        unset($returnDatas['market']['reorder_id']);
//        unset($returnDatas['market']['category_id']);
//        unset($returnDatas['market']['reorder_id']);
//        unset($returnDatas['market']['created_by']);
//        unset($returnDatas['market']['updated_by']);
//        unset($returnDatas['market']['deleted_by']);
//        unset($returnDatas['market']['created_at']);
//        unset($returnDatas['market']['updated_at']);
//        unset($returnDatas['market']['deleted_at']);
//        unset($returnDatas['market']['shares']);
//
//        foreach ($returnDatas['market']['category'] as $marketCatKey => $marketCatVal) {
//
//            unset($returnDatas['market']['category'][$marketCatKey]['parent_id']);
//            unset($returnDatas['market']['category'][$marketCatKey]['created_by']);
//            unset($returnDatas['market']['category'][$marketCatKey]['updated_by']);
//            unset($returnDatas['market']['category'][$marketCatKey]['deleted_by']);
//            unset($returnDatas['market']['category'][$marketCatKey]['created_at']);
//            unset($returnDatas['market']['category'][$marketCatKey]['updated_at']);
//            unset($returnDatas['market']['category'][$marketCatKey]['deleted_at']);
//        }
//        return $returnDatas;
        header('Content-Type: application/json');
        echo '{"status":{"success":1,"message":""},"data":{"market":{"market_id":"2","category":[{"id":"1","name":"Spirits","top_shelf_product":[{"id":"34","sku":"1069351","ean":"256","image":"VCP VINT GB.png","short_name":"Veuve Clicquot Vintage giftbox 75cl","long_name":"Veuve Clicquot Vintage giftbox 75cl","short_description":null,"brand_id":"13","product_category_id":"2","product_sub_category_id":"48","product_type_id":"11","market_id":null,"width":"8.00","height":"32.00","length":"8.00","scale":null,"manufacturer":"","box_only":"1","market_share":"1","reorder_id":"117","product_variental":null,"special_format":"0","price":"1.00","top_shelf":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2018-11-01 12:06:06","updated_at":"2018-11-28 06:32:53","deleted_at":null,"is_inserted":1},{"id":"23","sku":"1076283","ean":"170","image":"MC GV NKD.png","short_name":"Mo?t & Chandon Grand Vintage 75cl","long_name":"Mo?t & Chandon Grand Vintage 75cl","short_description":null,"brand_id":"5","product_category_id":"2","product_sub_category_id":"51","product_type_id":"11","market_id":null,"width":"8.82","height":"31.90","length":"8.82","scale":null,"manufacturer":"","box_only":"0","market_share":"1","reorder_id":"93","product_variental":null,"special_format":"0","price":"1.00","top_shelf":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2018-11-01 12:06:06","updated_at":"2018-11-28 06:32:53","deleted_at":null,"is_inserted":1},{"id":"22","sku":"1077639","ean":"133","image":"5beadb99c46f0-6265.png","short_name":"Dom P?rignon P2 Blanc Giftbox 75cl","long_name":"Dom P?rignon P2 Blanc Giftbox 75cl","short_description":null,"brand_id":"8","product_category_id":"2","product_sub_category_id":"51","product_type_id":"11","market_id":null,"width":"16.00","height":"36.00","length":"12.10","scale":null,"manufacturer":"","box_only":"1","market_share":"1","reorder_id":"29","product_variental":null,"special_format":"0","price":"1.00","top_shelf":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2018-11-01 12:06:06","updated_at":"2018-11-28 06:32:53","deleted_at":null,"is_inserted":0},{"id":"28","sku":"1076738","ean":"183","image":"MC GV RO GB.png","short_name":"Mo?t & Chandon Grand Vintage Ros? Giftbox 75cl","long_name":"Mo?t & Chandon Grand Vintage Ros? Giftbox 75cl","short_description":null,"brand_id":"5","product_category_id":"2","product_sub_category_id":"48","product_type_id":"11","market_id":null,"width":"8.85","height":"31.90","length":"8.85","scale":null,"manufacturer":"MHCS","box_only":"0","market_share":"1","reorder_id":"80","product_variental":null,"special_format":"0","price":"1.00","top_shelf":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2018-11-01 12:06:06","updated_at":"2018-11-28 06:32:53","deleted_at":null,"is_inserted":0},{"id":"54","sku":"1054847","ean":"0","image":"Shoot","short_name":"Hennessy X.O Cognac 100cl","long_name":"Hennessy X.O Cognac 100cl with box","short_description":null,"brand_id":"4","product_category_id":"1","product_sub_category_id":"9","product_type_id":"10","market_id":null,"width":"10.60","height":"25.80","length":"178.00","scale":null,"manufacturer":"Jas Hennessy & Co","box_only":"1","market_share":"1","reorder_id":"61","product_variental":null,"special_format":"0","price":"1.00","top_shelf":"1","created_by":"1","updated_by":null,"deleted_by":null,"created_at":"2018-11-01 12:06:06","updated_at":null,"deleted_at":null,"is_inserted":0},{"id":"23","sku":"1076283","ean":"170","image":"MC GV NKD.png","short_name":"Mo?t & Chandon Grand Vintage 75cl","long_name":"Mo?t & Chandon Grand Vintage 75cl","short_description":null,"brand_id":"5","product_category_id":"2","product_sub_category_id":"51","product_type_id":"11","market_id":null,"width":"8.82","height":"31.90","length":"8.82","scale":null,"manufacturer":"","box_only":"0","market_share":"1","reorder_id":"93","product_variental":null,"special_format":"0","price":"1.00","top_shelf":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2018-11-01 12:06:06","updated_at":"2018-11-28 06:32:53","deleted_at":null,"is_inserted":1},{"id":"24","sku":"1076437","ean":"171","image":"MC GV GB.png","short_name":"Mo?t & Chandon Grand Vintage Giftbox 75cl","long_name":"Mo?t & Chandon Grand Vintage Giftbox 75cl","short_description":null,"brand_id":"5","product_category_id":"2","product_sub_category_id":"51","product_type_id":"11","market_id":null,"width":"8.85","height":"31.90","length":"8.85","scale":null,"manufacturer":"","box_only":"0","market_share":"1","reorder_id":"94","product_variental":null,"special_format":"0","price":"1.00","top_shelf":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2018-11-01 12:06:06","updated_at":"2018-11-28 06:32:53","deleted_at":null,"is_inserted":1},{"id":"27","sku":"1076700","ean":"182","image":"MC GV RO NKD.png","short_name":"Mo?t & Chandon Grand Vintage Ros? 75cl","long_name":"Mo?t & Chandon Grand Vintage Ros? 75cl","short_description":null,"brand_id":"5","product_category_id":"2","product_sub_category_id":"48","product_type_id":"11","market_id":null,"width":"8.82","height":"31.90","length":"8.82","scale":null,"manufacturer":"MHCS","box_only":"0","market_share":"1","reorder_id":"95","product_variental":null,"special_format":"0","price":"1.00","top_shelf":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2018-11-01 12:06:06","updated_at":"2018-11-28 06:32:53","deleted_at":null,"is_inserted":1},{"id":"27","sku":"1076700","ean":"182","image":"MC GV RO NKD.png","short_name":"Mo?t & Chandon Grand Vintage Ros? 75cl","long_name":"Mo?t & Chandon Grand Vintage Ros? 75cl","short_description":null,"brand_id":"5","product_category_id":"2","product_sub_category_id":"48","product_type_id":"11","market_id":null,"width":"8.82","height":"31.90","length":"8.82","scale":null,"manufacturer":"MHCS","box_only":"0","market_share":"1","reorder_id":"95","product_variental":null,"special_format":"0","price":"1.00","top_shelf":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2018-11-01 12:06:06","updated_at":"2018-11-28 06:32:53","deleted_at":null,"is_inserted":1},{"id":"22","sku":"1077639","ean":"133","image":"5beadb99c46f0-6265.png","short_name":"Dom P?rignon P2 Blanc Giftbox 75cl","long_name":"Dom P?rignon P2 Blanc Giftbox 75cl","short_description":null,"brand_id":"8","product_category_id":"2","product_sub_category_id":"51","product_type_id":"11","market_id":null,"width":"16.00","height":"36.00","length":"12.10","scale":null,"manufacturer":"","box_only":"1","market_share":"1","reorder_id":"29","product_variental":null,"special_format":"0","price":"1.00","top_shelf":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2018-11-01 12:06:06","updated_at":"2018-11-28 06:32:53","deleted_at":null,"is_inserted":1},{"id":"22","sku":"1077639","ean":"133","image":"5beadb99c46f0-6265.png","short_name":"Dom P?rignon P2 Blanc Giftbox 75cl","long_name":"Dom P?rignon P2 Blanc Giftbox 75cl","short_description":null,"brand_id":"8","product_category_id":"2","product_sub_category_id":"51","product_type_id":"11","market_id":null,"width":"16.00","height":"36.00","length":"12.10","scale":null,"manufacturer":"","box_only":"1","market_share":"1","reorder_id":"29","product_variental":null,"special_format":"0","price":"1.00","top_shelf":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2018-11-01 12:06:06","updated_at":"2018-11-28 06:32:53","deleted_at":null,"is_inserted":1},{"id":"24","sku":"1076437","ean":"171","image":"MC GV GB.png","short_name":"Mo?t & Chandon Grand Vintage Giftbox 75cl","long_name":"Mo?t & Chandon Grand Vintage Giftbox 75cl","short_description":null,"brand_id":"5","product_category_id":"2","product_sub_category_id":"51","product_type_id":"11","market_id":null,"width":"8.85","height":"31.90","length":"8.85","scale":null,"manufacturer":"","box_only":"0","market_share":"1","reorder_id":"94","product_variental":null,"special_format":"0","price":"1.00","top_shelf":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2018-11-01 12:06:06","updated_at":"2018-11-28 06:32:53","deleted_at":null,"is_inserted":1},{"id":"23","sku":"1076283","ean":"170","image":"MC GV NKD.png","short_name":"Mo?t & Chandon Grand Vintage 75cl","long_name":"Mo?t & Chandon Grand Vintage 75cl","short_description":null,"brand_id":"5","product_category_id":"2","product_sub_category_id":"51","product_type_id":"11","market_id":null,"width":"8.82","height":"31.90","length":"8.82","scale":null,"manufacturer":"","box_only":"0","market_share":"1","reorder_id":"93","product_variental":null,"special_format":"0","price":"1.00","top_shelf":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2018-11-01 12:06:06","updated_at":"2018-11-28 06:32:53","deleted_at":null,"is_inserted":0},{"id":"28","sku":"1076738","ean":"183","image":"MC GV RO GB.png","short_name":"Mo?t & Chandon Grand Vintage Ros? Giftbox 75cl","long_name":"Mo?t & Chandon Grand Vintage Ros? Giftbox 75cl","short_description":null,"brand_id":"5","product_category_id":"2","product_sub_category_id":"48","product_type_id":"11","market_id":null,"width":"8.85","height":"31.90","length":"8.85","scale":null,"manufacturer":"MHCS","box_only":"0","market_share":"1","reorder_id":"80","product_variental":null,"special_format":"0","price":"1.00","top_shelf":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2018-11-01 12:06:06","updated_at":"2018-11-28 06:32:53","deleted_at":null,"is_inserted":1},{"id":"52","sku":"1042790","ean":"0","image":"GN 18YO.png","short_name":"Glenmorangie 18 Years Old 75cl","long_name":"Glenmorangie 18 Years Old 75cl","short_description":null,"brand_id":"10","product_category_id":"1","product_sub_category_id":"49","product_type_id":"23","market_id":null,"width":"11.30","height":"34.50","length":"14.20","scale":null,"manufacturer":"","box_only":"0","market_share":"1","reorder_id":"41","product_variental":null,"special_format":"0","price":"1.00","top_shelf":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2018-11-01 12:06:06","updated_at":"2018-11-28 06:32:53","deleted_at":null,"is_inserted":1},{"id":"119","sku":"1073081","ean":"112","image":"5beadc797ba28-9175.png","short_name":"Dom P?rignon Blanc Vintage Giftbox 75cl","long_name":"Dom P?rignon Blanc Vintage 75cl","short_description":null,"brand_id":"8","product_category_id":"2","product_sub_category_id":"51","product_type_id":"11","market_id":null,"width":"13.00","height":"32.00","length":"33.30","scale":null,"manufacturer":"","box_only":"1","market_share":"1","reorder_id":"25","product_variental":null,"special_format":"0","price":"1.00","top_shelf":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2018-11-01 12:06:06","updated_at":"2018-11-28 06:32:53","deleted_at":null,"is_inserted":1},{"id":"122","sku":"1073819","ean":"131","image":"Shoot","short_name":"Dom P?rignon Rose 150cl","long_name":"RO05 P1 1.5L C3 CC TPIN1","short_description":null,"brand_id":"8","product_category_id":"2","product_sub_category_id":"48","product_type_id":"11","market_id":null,"width":"12.10","height":"36.60","length":"12.10","scale":null,"manufacturer":"","box_only":"0","market_share":"1","reorder_id":"28","product_variental":null,"special_format":"0","price":"1.00","top_shelf":"1","created_by":"1","updated_by":null,"deleted_by":null,"created_at":"2018-11-01 12:06:06","updated_at":null,"deleted_at":null,"is_inserted":1}],"brand":[{"id":"12","name":"Ruinart","image":"http://new.anasource.com/team11/moet_hennessy_app_new/uploads/brands/5b34867b77495-5603.png","color_code":"#363638","marketBrandsVerietals":[{"id":"5","market_id":"2","brand_id":"12","verietal_id":"92","reorder_id":"1","category_id":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2019-03-05 09:56:25","updated_at":"2019-03-05 11:34:00","deleted_at":null,"shares":"80","product":""},{"id":"6","market_id":"2","brand_id":"12","verietal_id":"91","reorder_id":"2","category_id":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2019-03-05 09:56:25","updated_at":"2019-03-05 11:34:00","deleted_at":null,"shares":"20","product":""}],"shares":"100","reorder_id":"1"},{"id":"4","name":"Hennessy","image":"http://new.anasource.com/team11/moet_hennessy_app_new/uploads/brands/5b34848ee9c30-5403.png","color_code":"#7f6000","marketBrandsVerietals":[{"id":"27","market_id":"2","brand_id":"4","verietal_id":"91","reorder_id":"2","category_id":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2019-03-06 10:31:06","updated_at":"2019-03-06 10:31:17","deleted_at":null,"shares":0,"product":""},{"id":"28","market_id":"2","brand_id":"4","verietal_id":"92","reorder_id":"1","category_id":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2019-03-06 10:31:06","updated_at":"2019-03-06 10:31:17","deleted_at":null,"shares":0,"product":""}],"shares":"0","reorder_id":"2"},{"id":"5","name":"Mo?t & Chandon","image":"http://new.anasource.com/team11/moet_hennessy_app_new/uploads/brands/5b34861178cbd-932.png","color_code":"#073763","marketBrandsVerietals":"","shares":"0","reorder_id":"3"},{"id":"9","name":"Estates & Wines","image":"http://new.anasource.com/team11/moet_hennessy_app_new/uploads/brands/5b34830ae45a9-5239.png","color_code":"#363638","marketBrandsVerietals":[{"id":"25","market_id":"2","brand_id":"9","verietal_id":"91","reorder_id":"1","category_id":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2019-03-06 10:21:25","updated_at":"2019-03-06 10:21:25","deleted_at":null,"shares":0,"product":""},{"id":"26","market_id":"2","brand_id":"9","verietal_id":"92","reorder_id":"2","category_id":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2019-03-06 10:21:25","updated_at":"2019-03-06 10:21:25","deleted_at":null,"shares":0,"product":""}],"shares":"0","reorder_id":"6"},{"id":"10","name":"Glenmorangie","image":"http://new.anasource.com/team11/moet_hennessy_app_new/uploads/brands/5b34839925243-3572.png","color_code":"#363638","marketBrandsVerietals":"","shares":"0","reorder_id":"7"},{"id":"15","name":"Krug","image":"http://new.anasource.com/team11/moet_hennessy_app_new/uploads/brands/5b34854b1cbf4-6516.png","color_code":"#363638","marketBrandsVerietals":"","shares":"0","reorder_id":"11"}]},{"id":"2","name":"Champagne","top_shelf_product":[{"id":"34","sku":"1069351","ean":"256","image":"VCP VINT GB.png","short_name":"Veuve Clicquot Vintage giftbox 75cl","long_name":"Veuve Clicquot Vintage giftbox 75cl","short_description":null,"brand_id":"13","product_category_id":"2","product_sub_category_id":"48","product_type_id":"11","market_id":null,"width":"8.00","height":"32.00","length":"8.00","scale":null,"manufacturer":"","box_only":"1","market_share":"1","reorder_id":"117","product_variental":null,"special_format":"0","price":"1.00","top_shelf":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2018-11-01 12:06:06","updated_at":"2018-11-28 06:32:53","deleted_at":null,"is_inserted":1},{"id":"23","sku":"1076283","ean":"170","image":"MC GV NKD.png","short_name":"Mo?t & Chandon Grand Vintage 75cl","long_name":"Mo?t & Chandon Grand Vintage 75cl","short_description":null,"brand_id":"5","product_category_id":"2","product_sub_category_id":"51","product_type_id":"11","market_id":null,"width":"8.82","height":"31.90","length":"8.82","scale":null,"manufacturer":"","box_only":"0","market_share":"1","reorder_id":"93","product_variental":null,"special_format":"0","price":"1.00","top_shelf":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2018-11-01 12:06:06","updated_at":"2018-11-28 06:32:53","deleted_at":null,"is_inserted":1},{"id":"22","sku":"1077639","ean":"133","image":"5beadb99c46f0-6265.png","short_name":"Dom P?rignon P2 Blanc Giftbox 75cl","long_name":"Dom P?rignon P2 Blanc Giftbox 75cl","short_description":null,"brand_id":"8","product_category_id":"2","product_sub_category_id":"51","product_type_id":"11","market_id":null,"width":"16.00","height":"36.00","length":"12.10","scale":null,"manufacturer":"","box_only":"1","market_share":"1","reorder_id":"29","product_variental":null,"special_format":"0","price":"1.00","top_shelf":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2018-11-01 12:06:06","updated_at":"2018-11-28 06:32:53","deleted_at":null,"is_inserted":1},{"id":"28","sku":"1076738","ean":"183","image":"MC GV RO GB.png","short_name":"Mo?t & Chandon Grand Vintage Ros? Giftbox 75cl","long_name":"Mo?t & Chandon Grand Vintage Ros? Giftbox 75cl","short_description":null,"brand_id":"5","product_category_id":"2","product_sub_category_id":"48","product_type_id":"11","market_id":null,"width":"8.85","height":"31.90","length":"8.85","scale":null,"manufacturer":"MHCS","box_only":"0","market_share":"1","reorder_id":"80","product_variental":null,"special_format":"0","price":"1.00","top_shelf":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2018-11-01 12:06:06","updated_at":"2018-11-28 06:32:53","deleted_at":null,"is_inserted":1},{"id":"54","sku":"1054847","ean":"0","image":"Shoot","short_name":"Hennessy X.O Cognac 100cl","long_name":"Hennessy X.O Cognac 100cl with box","short_description":null,"brand_id":"4","product_category_id":"1","product_sub_category_id":"9","product_type_id":"10","market_id":null,"width":"10.60","height":"25.80","length":"178.00","scale":null,"manufacturer":"Jas Hennessy & Co","box_only":"1","market_share":"1","reorder_id":"61","product_variental":null,"special_format":"0","price":"1.00","top_shelf":"1","created_by":"1","updated_by":null,"deleted_by":null,"created_at":"2018-11-01 12:06:06","updated_at":null,"deleted_at":null,"is_inserted":1},{"id":"23","sku":"1076283","ean":"170","image":"MC GV NKD.png","short_name":"Mo?t & Chandon Grand Vintage 75cl","long_name":"Mo?t & Chandon Grand Vintage 75cl","short_description":null,"brand_id":"5","product_category_id":"2","product_sub_category_id":"51","product_type_id":"11","market_id":null,"width":"8.82","height":"31.90","length":"8.82","scale":null,"manufacturer":"","box_only":"0","market_share":"1","reorder_id":"93","product_variental":null,"special_format":"0","price":"1.00","top_shelf":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2018-11-01 12:06:06","updated_at":"2018-11-28 06:32:53","deleted_at":null,"is_inserted":1},{"id":"24","sku":"1076437","ean":"171","image":"MC GV GB.png","short_name":"Mo?t & Chandon Grand Vintage Giftbox 75cl","long_name":"Mo?t & Chandon Grand Vintage Giftbox 75cl","short_description":null,"brand_id":"5","product_category_id":"2","product_sub_category_id":"51","product_type_id":"11","market_id":null,"width":"8.85","height":"31.90","length":"8.85","scale":null,"manufacturer":"","box_only":"0","market_share":"1","reorder_id":"94","product_variental":null,"special_format":"0","price":"1.00","top_shelf":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2018-11-01 12:06:06","updated_at":"2018-11-28 06:32:53","deleted_at":null,"is_inserted":1},{"id":"27","sku":"1076700","ean":"182","image":"MC GV RO NKD.png","short_name":"Mo?t & Chandon Grand Vintage Ros? 75cl","long_name":"Mo?t & Chandon Grand Vintage Ros? 75cl","short_description":null,"brand_id":"5","product_category_id":"2","product_sub_category_id":"48","product_type_id":"11","market_id":null,"width":"8.82","height":"31.90","length":"8.82","scale":null,"manufacturer":"MHCS","box_only":"0","market_share":"1","reorder_id":"95","product_variental":null,"special_format":"0","price":"1.00","top_shelf":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2018-11-01 12:06:06","updated_at":"2018-11-28 06:32:53","deleted_at":null,"is_inserted":1},{"id":"27","sku":"1076700","ean":"182","image":"MC GV RO NKD.png","short_name":"Mo?t & Chandon Grand Vintage Ros? 75cl","long_name":"Mo?t & Chandon Grand Vintage Ros? 75cl","short_description":null,"brand_id":"5","product_category_id":"2","product_sub_category_id":"48","product_type_id":"11","market_id":null,"width":"8.82","height":"31.90","length":"8.82","scale":null,"manufacturer":"MHCS","box_only":"0","market_share":"1","reorder_id":"95","product_variental":null,"special_format":"0","price":"1.00","top_shelf":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2018-11-01 12:06:06","updated_at":"2018-11-28 06:32:53","deleted_at":null,"is_inserted":1},{"id":"22","sku":"1077639","ean":"133","image":"5beadb99c46f0-6265.png","short_name":"Dom P?rignon P2 Blanc Giftbox 75cl","long_name":"Dom P?rignon P2 Blanc Giftbox 75cl","short_description":null,"brand_id":"8","product_category_id":"2","product_sub_category_id":"51","product_type_id":"11","market_id":null,"width":"16.00","height":"36.00","length":"12.10","scale":null,"manufacturer":"","box_only":"1","market_share":"1","reorder_id":"29","product_variental":null,"special_format":"0","price":"1.00","top_shelf":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2018-11-01 12:06:06","updated_at":"2018-11-28 06:32:53","deleted_at":null,"is_inserted":0},{"id":"22","sku":"1077639","ean":"133","image":"5beadb99c46f0-6265.png","short_name":"Dom P?rignon P2 Blanc Giftbox 75cl","long_name":"Dom P?rignon P2 Blanc Giftbox 75cl","short_description":null,"brand_id":"8","product_category_id":"2","product_sub_category_id":"51","product_type_id":"11","market_id":null,"width":"16.00","height":"36.00","length":"12.10","scale":null,"manufacturer":"","box_only":"1","market_share":"1","reorder_id":"29","product_variental":null,"special_format":"0","price":"1.00","top_shelf":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2018-11-01 12:06:06","updated_at":"2018-11-28 06:32:53","deleted_at":null,"is_inserted":0},{"id":"24","sku":"1076437","ean":"171","image":"MC GV GB.png","short_name":"Mo?t & Chandon Grand Vintage Giftbox 75cl","long_name":"Mo?t & Chandon Grand Vintage Giftbox 75cl","short_description":null,"brand_id":"5","product_category_id":"2","product_sub_category_id":"51","product_type_id":"11","market_id":null,"width":"8.85","height":"31.90","length":"8.85","scale":null,"manufacturer":"","box_only":"0","market_share":"1","reorder_id":"94","product_variental":null,"special_format":"0","price":"1.00","top_shelf":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2018-11-01 12:06:06","updated_at":"2018-11-28 06:32:53","deleted_at":null,"is_inserted":0},{"id":"23","sku":"1076283","ean":"170","image":"MC GV NKD.png","short_name":"Mo?t & Chandon Grand Vintage 75cl","long_name":"Mo?t & Chandon Grand Vintage 75cl","short_description":null,"brand_id":"5","product_category_id":"2","product_sub_category_id":"51","product_type_id":"11","market_id":null,"width":"8.82","height":"31.90","length":"8.82","scale":null,"manufacturer":"","box_only":"0","market_share":"1","reorder_id":"93","product_variental":null,"special_format":"0","price":"1.00","top_shelf":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2018-11-01 12:06:06","updated_at":"2018-11-28 06:32:53","deleted_at":null,"is_inserted":0},{"id":"28","sku":"1076738","ean":"183","image":"MC GV RO GB.png","short_name":"Mo?t & Chandon Grand Vintage Ros? Giftbox 75cl","long_name":"Mo?t & Chandon Grand Vintage Ros? Giftbox 75cl","short_description":null,"brand_id":"5","product_category_id":"2","product_sub_category_id":"48","product_type_id":"11","market_id":null,"width":"8.85","height":"31.90","length":"8.85","scale":null,"manufacturer":"MHCS","box_only":"0","market_share":"1","reorder_id":"80","product_variental":null,"special_format":"0","price":"1.00","top_shelf":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2018-11-01 12:06:06","updated_at":"2018-11-28 06:32:53","deleted_at":null,"is_inserted":1},{"id":"52","sku":"1042790","ean":"0","image":"GN 18YO.png","short_name":"Glenmorangie 18 Years Old 75cl","long_name":"Glenmorangie 18 Years Old 75cl","short_description":null,"brand_id":"10","product_category_id":"1","product_sub_category_id":"49","product_type_id":"23","market_id":null,"width":"11.30","height":"34.50","length":"14.20","scale":null,"manufacturer":"","box_only":"0","market_share":"1","reorder_id":"41","product_variental":null,"special_format":"0","price":"1.00","top_shelf":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2018-11-01 12:06:06","updated_at":"2018-11-28 06:32:53","deleted_at":null,"is_inserted":0},{"id":"119","sku":"1073081","ean":"112","image":"5beadc797ba28-9175.png","short_name":"Dom P?rignon Blanc Vintage Giftbox 75cl","long_name":"Dom P?rignon Blanc Vintage 75cl","short_description":null,"brand_id":"8","product_category_id":"2","product_sub_category_id":"51","product_type_id":"11","market_id":null,"width":"13.00","height":"32.00","length":"33.30","scale":null,"manufacturer":"","box_only":"1","market_share":"1","reorder_id":"25","product_variental":null,"special_format":"0","price":"1.00","top_shelf":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2018-11-01 12:06:06","updated_at":"2018-11-28 06:32:53","deleted_at":null,"is_inserted":1},{"id":"122","sku":"1073819","ean":"131","image":"Shoot","short_name":"Dom P?rignon Rose 150cl","long_name":"RO05 P1 1.5L C3 CC TPIN1","short_description":null,"brand_id":"8","product_category_id":"2","product_sub_category_id":"48","product_type_id":"11","market_id":null,"width":"12.10","height":"36.60","length":"12.10","scale":null,"manufacturer":"","box_only":"0","market_share":"1","reorder_id":"28","product_variental":null,"special_format":"0","price":"1.00","top_shelf":"1","created_by":"1","updated_by":null,"deleted_by":null,"created_at":"2018-11-01 12:06:06","updated_at":null,"deleted_at":null,"is_inserted":0}],"brand":[{"id":"12","name":"Ruinart","image":"http://new.anasource.com/team11/moet_hennessy_app_new/uploads/brands/5b34867b77495-5603.png","color_code":"#363638","marketBrandsVerietals":[{"id":"5","market_id":"2","brand_id":"12","verietal_id":"92","reorder_id":"1","category_id":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2019-03-05 09:56:25","updated_at":"2019-03-05 11:34:00","deleted_at":null,"shares":"80","product":""},{"id":"6","market_id":"2","brand_id":"12","verietal_id":"91","reorder_id":"2","category_id":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2019-03-05 09:56:25","updated_at":"2019-03-05 11:34:00","deleted_at":null,"shares":"20","product":""}],"shares":"100","reorder_id":"1"},{"id":"4","name":"Hennessy","image":"http://new.anasource.com/team11/moet_hennessy_app_new/uploads/brands/5b34848ee9c30-5403.png","color_code":"#7f6000","marketBrandsVerietals":[{"id":"27","market_id":"2","brand_id":"4","verietal_id":"91","reorder_id":"2","category_id":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2019-03-06 10:31:06","updated_at":"2019-03-06 10:31:17","deleted_at":null,"shares":0,"product":""},{"id":"28","market_id":"2","brand_id":"4","verietal_id":"92","reorder_id":"1","category_id":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2019-03-06 10:31:06","updated_at":"2019-03-06 10:31:17","deleted_at":null,"shares":0,"product":""}],"shares":"0","reorder_id":"2"},{"id":"5","name":"Mo?t & Chandon","image":"http://new.anasource.com/team11/moet_hennessy_app_new/uploads/brands/5b34861178cbd-932.png","color_code":"#073763","marketBrandsVerietals":"","shares":"0","reorder_id":"3"},{"id":"9","name":"Estates & Wines","image":"http://new.anasource.com/team11/moet_hennessy_app_new/uploads/brands/5b34830ae45a9-5239.png","color_code":"#363638","marketBrandsVerietals":[{"id":"25","market_id":"2","brand_id":"9","verietal_id":"91","reorder_id":"1","category_id":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2019-03-06 10:21:25","updated_at":"2019-03-06 10:21:25","deleted_at":null,"shares":0,"product":""},{"id":"26","market_id":"2","brand_id":"9","verietal_id":"92","reorder_id":"2","category_id":"1","created_by":"1","updated_by":"1","deleted_by":null,"created_at":"2019-03-06 10:21:25","updated_at":"2019-03-06 10:21:25","deleted_at":null,"shares":0,"product":""}],"shares":"0","reorder_id":"6"},{"id":"10","name":"Glenmorangie","image":"http://new.anasource.com/team11/moet_hennessy_app_new/uploads/brands/5b34839925243-3572.png","color_code":"#363638","marketBrandsVerietals":"","shares":"0","reorder_id":"7"},{"id":"15","name":"Krug","image":"http://new.anasource.com/team11/moet_hennessy_app_new/uploads/brands/5b34854b1cbf4-6516.png","color_code":"#363638","marketBrandsVerietals":"","shares":"0","reorder_id":"11"}]}]}}}';
        exit;
    }

    /*
     * action brand-product-list used get details of user such as different category,products,product varientals
     */

    public function actionNewBrandProductList() {

        $currentUser = CommonHelper::getUser();
        $filters = array();
        $marketId = '';
        if (isset($currentUser->market_id) && ($currentUser->market_id != '')) {
            $marketId = $currentUser->market_id;
        }

        $returnDatas = array();
        $returnDatas['market']['id'] = $marketId;
        if ($marketId != '') {
            //Master categories
            $categoryList = ProductCategories::find()->asArray()->all();
            //Master brands
            $searchModel = new BrandsSearch();

            //$brandList = Brands::find()->asArray()->all();
            //print_r($brandVariental); exit;
            if (!empty($categoryList)) {
                foreach ($categoryList as $catKey => $catVal) {
                    $returnDatas['market']['category'][$catKey]['id'] = $catVal['id'];
                    $returnDatas['market']['category'][$catKey]['name'] = $catVal['name'];
                    //Master catalogues
                    $catalogueList = Catalogues::find()->andWhere(['top_shelf' => 1, 'product_category_id' => $catVal['id']])->asArray()->all();

                    foreach ($catalogueList as $cataKey => $cataVal) {
                        $market_brand_repository = MarketCategoryProduct::find()->andWhere(['category_id' => $catVal['id'], 'market_id' => $marketId, 'product_id' => $cataVal['id'], 'is_inserted' => 1])->asArray()->all();
                        if (!empty($market_brand_repository[0])) {
                            $returnDatas['market']['category'][$catKey]['top_shelf_product'][$cataKey] = $cataVal;
                            $returnDatas['market']['category'][$catKey]['top_shelf_product'][$cataKey]['is_inserted'] = $market_brand_repository[0]['is_inserted'];
                        } else {
                            $returnDatas['market']['category'][$catKey]['top_shelf_product'][$cataKey] = $cataVal;
                            $returnDatas['market']['category'][$catKey]['top_shelf_product'][$cataKey]['is_inserted'] = 0;
                        }
                    }
                    $filters['category_id'] = $catVal['id'];
                    $filters['market_id'] = $marketId;
                    $filters['limit'] = Yii::$app->params['pageSize'];
                    $brandList = $searchModel->searchMarketBrand($filters);
                    //print_r($brandList->allModels);
                    foreach ($brandList->allModels as $brandKey => $brandVal) {
                        //$market_brand_repository = MarketCategoryProduct::findOne(['category_id' => $catVal['id'], 'market_id' => $marketId, 'product_id' => $cataVal['id'],'is_inserted' => 1]);
                        //print_r($market_brand_repository);exit;
                        $returnDatas['market']['category'][$catKey]['brand'][$brandKey] = $brandVal;
                        $returnDatas['market']['category'][$catKey]['brand'][$brandKey]['image'] = isset($brandVal['image']) ? CommonHelper::getPath('upload_url') . UPLOAD_PATH_BRANDS_IMAGES . $brandVal['image'] : '';
                        $returnDatas['market']['category'][$catKey]['brand'][$brandKey]['color_code'] = isset($brandVal['color_code']) && ($brandVal['color_code'] != '') ? $brandVal['color_code'] : COLOR_CODE;

                        $marketBrandShares = MarketBrands::find()->select(['shares'])->andWhere(['market_id' => $marketId, 'brand_id' => $brandVal['id'], 'category_id' => $catVal['id']])->orderBy(['reorder_id' => SORT_ASC])->asArray()->all();
                        if ($marketBrandShares) {
                            foreach ($marketBrandShares as $marketBrandSharesKey => $marketBrandSharesVal) {
                                $returnDatas['market']['category'][$catKey]['brand'][$brandKey]['shares'] = $marketBrandSharesVal['shares'];
                            }
                        }
                        $filters['market_id'] = $marketId;
                        $filters['category_id'] = $catVal['id'];
                        $filters['brand_id'] = $brandVal['id'];
                        $productVarietalSearchModel = new ProductVarietalSearch();
                        $productVarietalDataProvider = $productVarietalSearchModel->searchVariental($filters);
                        
                    }
                }
            }
        }
     //   exit;
//        print_r($returnDatas);
//        exit;

        return $returnDatas;
    }

}
