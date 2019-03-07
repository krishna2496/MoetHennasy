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
        $marketId = '';
        if (isset($currentUser->market_id) && ($currentUser->market_id != '')) {
            $marketId = $currentUser->market_id;
        }

        $returnDatas = $newProductArry = $marketVarientalProduct = $brandVariental = $brandVarientalArr = array();
        $repository = new MarketBrandsRepository();

        if ($marketId != '') {
            $data['market_id'] = $marketId;
            $returnData = $repository->listing($data);

            $productsData = $returnData['data']['market_product'];

            if ($productsData) {
                foreach ($productsData as $key => $value) {
                    $newProductArry[$value['category_id']] = $value['category']['marketCategoryProduct'];
                }
            }
            $newProductFinalArry = array();
            foreach ($newProductArry as $k => $v) {
                foreach ($v as $key => $value) {
                    $category_id = $value['category_id'];
                    unset($value['id']);
                    unset($value['product_id']);
                    unset($value['category_id']);
                    unset($value['market_id']);
                    unset($value['created_by']);
                    unset($value['updated_by']);
                    unset($value['deleted_by']);
                    unset($value['created_at']);
                    unset($value['updated_at']);
                    unset($value['deleted_at']);
                    $newProductFinalArry[$category_id][] = $value['product'];
                }
            }

            $brandVariental = MarketBrandsVerietals::find()->andWhere(['market_id' => $marketId])->asArray()->all();
            foreach ($brandVariental as $brandVarientalKey => $brandVarientalVal) {
                if ($brandVarientalVal['shares'] != null) {
                    $brandVarientalArr[$brandVarientalVal['category_id']][$brandVarientalVal['brand_id']][] = $brandVarientalVal;
                } else {
                    $brandVarientalVal['shares'] = 0;
                    $brandVarientalArr[$brandVarientalVal['category_id']][$brandVarientalVal['brand_id']][] = $brandVarientalVal;
                }
            }

            $collectCatId = $collectMarketId = [];
            if (!empty($returnData['data']['market_brands'])) {

                if ($returnData['data']['market_varietal']) {
                    foreach ($returnData['data']['market_varietal'] as $k => $v) {
                        $marketVarientalProduct[$v['product_category_id']][$v['brand_id']][$v['product_variental']][] = $v;
                    }
                }

                foreach ($returnData['data']['market_brands'] as $key => $value) {

                    $tempValue = $value;
                    unset($tempValue['category']);
                    unset($tempValue['brand']);

                    if (!in_array($value['market_id'], $collectMarketId)) {
                        $returnDatas["market"] = $tempValue;
                        $collectMarketId[] = $value['market_id'];
                    }

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

                    $marketBrandShares = MarketBrands::find()->select(['shares'])->andWhere(['market_id' => $value['market_id'], 'brand_id' => $value['brand']['id'], 'category_id' => $value['category']['id']])->orderBy(['reorder_id' => SORT_ASC])->asArray()->all();
                    if ($marketBrandShares) {
                        foreach ($marketBrandShares as $k => $v) {
                            $value['brand']['shares'] = $v['shares'];
                        }
                    }
                    $value['brand']['reorder_id'] = $value['reorder_id'];

                    $value['brand']['marketBrandsVerietals'] = isset($brandVarientalArr[$value['category']['id']][$value['brand']['id']]) ? $brandVarientalArr[$value['category']['id']][$value['brand']['id']] : '';

                    $variental = $value['brand']['marketBrandsVerietals'];

                    if (!empty($variental)) {
                        foreach ($variental as $vKey => $vVal) {
                            if (isset($value['brand']['marketBrandsVerietals'][$vKey])) {
                                $value['brand']['marketBrandsVerietals'][$vKey]['product'] = isset($marketVarientalProduct[$value['category']['id']][$value['brand']['id']][$vVal['verietal_id']]) ? $marketVarientalProduct[$value['category']['id']][$value['brand']['id']][$vVal['verietal_id']] : '';
                            }
                        }
                    }

                    //$product_data = '';
                    unset($value['brand']['product']);

                    if (!in_array($value['category']['id'], $collectCatId)) {
                        // $value['category']['brand'][] = $value['brand'];
                        $value['category']['top_shelf_product'] = isset($newProductFinalArry[$value['category']['id']]) ? $newProductFinalArry[$value['category']['id']] : '';
                        $returnDatas["market"]['category'][] = $value['category'];
                        if (isset($value['category']['id'])) {
                            $collectCatId[] = $value['category']['id'];
                        }
                    }
                    if (isset($value['category']['id'])) {
                        $key = array_search($value['category']['id'], array_column($returnDatas["market"]['category'], 'id'));

                        $returnDatas["market"]['category'][$key]['brand'][] = $value['brand'];
                    }
                }
            }
        }
        //if (!empty($returnDatas)) {
        unset($returnDatas['market']['brand_id']);
        unset($returnDatas['market']['reorder_id']);
        unset($returnDatas['market']['category_id']);
        unset($returnDatas['market']['reorder_id']);
        unset($returnDatas['market']['created_by']);
        unset($returnDatas['market']['updated_by']);
        unset($returnDatas['market']['deleted_by']);
        unset($returnDatas['market']['created_at']);
        unset($returnDatas['market']['updated_at']);
        unset($returnDatas['market']['deleted_at']);
        unset($returnDatas['market']['shares']);

        if ($returnDatas) {
            foreach ($returnDatas['market']['category'] as $marketCatKey => $marketCatVal) {

                unset($returnDatas['market']['category'][$marketCatKey]['parent_id']);
                unset($returnDatas['market']['category'][$marketCatKey]['created_by']);
                unset($returnDatas['market']['category'][$marketCatKey]['updated_by']);
                unset($returnDatas['market']['category'][$marketCatKey]['deleted_by']);
                unset($returnDatas['market']['category'][$marketCatKey]['created_at']);
                unset($returnDatas['market']['category'][$marketCatKey]['updated_at']);
                unset($returnDatas['market']['category'][$marketCatKey]['deleted_at']);
            }
        }
        return $returnDatas;
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

            if (!empty($categoryList)) {
                foreach ($categoryList as $catKey => $catVal) {
                    $returnDatas['market']['category'][$catKey]['id'] = $catVal['id'];
                    $returnDatas['market']['category'][$catKey]['name'] = $catVal['name'];
            
                    //Catalogues list
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
                    $filters['limit'] = '';

                    //Brand list
                    $brandList = $searchModel->searchMarketBrand($filters);
                    foreach ($brandList->allModels as $brandKey => $brandVal) {
                        
                        $returnDatas['market']['category'][$catKey]['brand'][$brandKey] = $brandVal;
                        $returnDatas['market']['category'][$catKey]['brand'][$brandKey]['image'] = isset($brandVal['image']) ? CommonHelper::getPath('upload_url') . UPLOAD_PATH_BRANDS_IMAGES . $brandVal['image'] : '';
                        $returnDatas['market']['category'][$catKey]['brand'][$brandKey]['color_code'] = isset($brandVal['color_code']) && ($brandVal['color_code'] != '') ? $brandVal['color_code'] : COLOR_CODE;
                        
                        //Set brand shares value
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
                        foreach ($productVarietalDataProvider->allModels as $productVarietalKey => $productVarietalVal) {
                               $returnDatas['market']['category'][$catKey]['brand'][$brandKey]['marketBrandsVerietals'][$productVarietalKey] = $productVarietalVal;
                        }
                    }
                }
            }
        }
        return $returnDatas;
    }

}
