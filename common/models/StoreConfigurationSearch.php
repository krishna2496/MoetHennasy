<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\StoreConfiguration;
use common\repository\StoreConfigRepository;
use yii\data\ArrayDataProvider;
use common\helpers\CommonHelper;
use common\repository\MarketBrandsRepository;
use common\repository\CataloguesRepository;
use common\repository\QuestionsRepository;
use common\repository\MarketRulesRepository;
use common\repository\MarketRepository;
use common\models\User;
use common\models\MarketBrands;
use common\models\MarketBrandsVerietals;
use common\repository\BrandRepository;
use common\repository\ProductCategoryRepository;
use common\repository\ProductTypesRepository;

/**
 * StoreConfigurationSearch represents the model behind the search form of `common\models\StoreConfiguration`.
 */
class StoreConfigurationSearch extends StoreConfiguration
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'store_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['config_name', 'shelf_thumb', 'star_ratings', 'is_verified', 'is_autofill', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $userRepository = new StoreConfigRepository();
        $userList = array();
        $resultUserList = $userRepository->listing($params);
        
        if ($resultUserList['status']['success'] == 1) {
            if ($resultUserList['data']['stores_config']) {
                foreach ($resultUserList['data']['stores_config'] as $key => $value) {
                   
                    $temp = $value;
                   
                    $userList[] = $temp;
                    
                }
            }
        }
        if(!isset($params['limit'])){
            $params['limit'] = count($userList);
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $userList,
            'pagination' => [
                'pageSize' => $params['limit'],
            ],
            'sort' => [
                'attributes' =>
                    [
                    'sku',
                    'ean',
                    'short_name',
                    'productCategory',
                  
                    'marketName',
                    'brandName',
                    'price'
                ],
            ]
        ]);
       

        return $dataProvider;
    }
    
    public function brandProductList($marketId) {
       
        $filters = $filterForVarietal = $marketVarientalProduct = $variental = $params = $brands = $productCategories = $productTypes = array();
        $marketId = $marketId;
        
        $queryVarietal = \common\models\Catalogues::find()->andWhere(['top_shelf' => 0])->asArray()->all();
        if ($queryVarietal) {
            foreach ($queryVarietal as $k => $v) {
                $marketVarientalProduct[$v['product_category_id']][$v['brand_id']][$v['product_variental']][] = $v;
            }
        }
        //Fetch brand list
        $brandRepository = new BrandRepository();
        $brandRepository = $brandRepository->listing();
        if (!empty($brandRepository['data']['brand'])) {
            foreach ($brandRepository['data']['brand'] as $bk => $bv) {
                $brands[$bv['id']] = $bv;
                $brands[$bv['id']]['image'] = isset($bv['image']) ? CommonHelper::getPath('upload_url') . UPLOAD_PATH_BRANDS_IMAGES . $bv['image'] : '';
                $brands[$bv['id']]['color_code'] = isset($bv['color_code']) && ($bv['color_code'] != '') ? $bv['color_code'] : COLOR_CODE;
            }
        }

        //Fetch product category list
        $productCategoryRepository = new ProductCategoryRepository();
        $productCategoryRepository = $productCategoryRepository->listing();
        if (!empty($productCategoryRepository['data']['productCategories'])) {
            foreach ($productCategoryRepository['data']['productCategories'] as $pck => $pcv) {
                $productCategories[$pcv['id']] = $pcv;
            }
        }

        //Fetch product type list
        $productTypesRepository = new ProductTypesRepository();
        $productTypesRepository = $productTypesRepository->listing();
        if (!empty($productTypesRepository['data']['productTypes'])) {
            foreach ($productTypesRepository['data']['productTypes'] as $ptk => $ptv) {
                $productTypes[$ptv['id']] = $ptv;
            }
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
                    if (!empty($catalogueList)) {
                        foreach ($catalogueList as $cataKey => $cataVal) {
                            unset($catalogueList[$cataKey]['created_by']);
                            unset($catalogueList[$cataKey]['updated_by']);
                            unset($catalogueList[$cataKey]['deleted_by']);
                            unset($catalogueList[$cataKey]['created_at']);
                            unset($catalogueList[$cataKey]['updated_at']);
                            unset($catalogueList[$cataKey]['deleted_at']);
                        }
                    }
                    if (!empty($catalogueList)) {
                        foreach ($catalogueList as $cataKey => $cataVal) {
                            $returnDatas['market']['category'][$catKey]['top_shelf_product'][$cataKey] = $cataVal;
                            $returnDatas['market']['category'][$catKey]['top_shelf_product'][$cataKey]['image'] = isset($cataVal['image']) && ($cataVal['image'] != '') ? CommonHelper::getPath('upload_url') . UPLOAD_PATH_CATALOGUES_IMAGES . rawurlencode($cataVal['image']) : '';
                            $returnDatas['market']['category'][$catKey]['top_shelf_product'][$cataKey]['box_only'] = \yii::$app->params['catalogue_status'][$cataVal['box_only']];
                            $returnDatas['market']['category'][$catKey]['top_shelf_product'][$cataKey]['top_shelf'] = \yii::$app->params['catalogue_status'][$cataVal['top_shelf']];

                            $returnDatas['market']['category'][$catKey]['top_shelf_product'][$cataKey]['brand'] = isset($brands[$cataVal['brand_id']]) && !empty($brands[$cataVal['brand_id']]) ? $brands[$cataVal['brand_id']] : array();
                            $returnDatas['market']['category'][$catKey]['top_shelf_product'][$cataKey]['product_catgeory'] = isset($productCategories[$cataVal['product_category_id']]) && !empty($productCategories[$cataVal['product_category_id']]) ? $productCategories[$cataVal['product_category_id']] : array();
                            $returnDatas['market']['category'][$catKey]['top_shelf_product'][$cataKey]['product_type'] = isset($productTypes[$cataVal['product_type_id']]) && !empty($productTypes[$cataVal['product_type_id']]) ? $productTypes[$cataVal['product_type_id']] : array();
                        }
                    } else {
                        $returnDatas['market']['category'][$catKey]['top_shelf_product'] = array();
                    }

                    $filters['category_id'] = $catVal['id'];
                    $filters['market_id'] = $marketId;
                    $filters['limit'] = '';

                    //Brand list By category and market
                    $brandList = $searchModel->searchMarketBrand($filters);
                    if (!empty($brandList->allModels)) {
                        foreach ($brandList->allModels as $brandKey => $brandVal) {
                            unset($brandList->allModels[$brandKey]['created_by']);
                            unset($brandList->allModels[$brandKey]['updated_by']);
                            unset($brandList->allModels[$brandKey]['deleted_by']);
                            unset($brandList->allModels[$brandKey]['created_at']);
                            unset($brandList->allModels[$brandKey]['updated_at']);
                            unset($brandList->allModels[$brandKey]['deleted_at']);
                        }
                    }
                    if (!empty($brandList->allModels)) {
                        foreach ($brandList->allModels as $brandKey => $brandVal) {
                            $returnDatas['market']['category'][$catKey]['brand'][$brandKey] = $brandVal;
                            $returnDatas['market']['category'][$catKey]['brand'][$brandKey]['image'] = isset($brandVal['image']) ? CommonHelper::getPath('upload_url') . UPLOAD_PATH_BRANDS_IMAGES . $brandVal['image'] : '';
                            $returnDatas['market']['category'][$catKey]['brand'][$brandKey]['color_code'] = isset($brandVal['color_code']) && ($brandVal['color_code'] != '') ? $brandVal['color_code'] : COLOR_CODE;

                            //Brand shares value
                            $marketBrandShares = MarketBrands::find()->select(['shares'])->andWhere(['market_id' => $marketId, 'brand_id' => $brandVal['id'], 'category_id' => $catVal['id']])->orderBy(['reorder_id' => SORT_ASC])->asArray()->all();
                            if ($marketBrandShares) {
                                foreach ($marketBrandShares as $marketBrandSharesKey => $marketBrandSharesVal) {
                                    $returnDatas['market']['category'][$catKey]['brand'][$brandKey]['shares'] = $marketBrandSharesVal['shares'];
                                }
                            }

                            //Product varietal vata By market and brand filter
                            $filterForVarietal['market_id'] = $marketId;
                            $filterForVarietal['category_id'] = $catVal['id'];
                            $filterForVarietal['brand_id'] = $brandVal['id'];
                            $productVarietalSearchModel = new ProductVarietalSearch();
                            $productVarietalDataProvider = $productVarietalSearchModel->searchVariental($filterForVarietal);

                            if (!empty($productVarietalDataProvider->allModels)) {
                                foreach ($productVarietalDataProvider->allModels as $productVarietalKey => $productVarietalVal) {
                                    unset($productVarietalDataProvider->allModels[$productVarietalKey]['created_by']);
                                    unset($productVarietalDataProvider->allModels[$productVarietalKey]['updated_by']);
                                    unset($productVarietalDataProvider->allModels[$productVarietalKey]['deleted_by']);
                                    unset($productVarietalDataProvider->allModels[$productVarietalKey]['created_at']);
                                    unset($productVarietalDataProvider->allModels[$productVarietalKey]['updated_at']);
                                    unset($productVarietalDataProvider->allModels[$productVarietalKey]['deleted_at']);
                                }
                            }
                            if (!empty($productVarietalDataProvider->allModels)) {
                                foreach ($productVarietalDataProvider->allModels as $productVarietalKey => $productVarietalVal) {
                                    $returnDatas['market']['category'][$catKey]['brand'][$brandKey]['marketBrandsVerietals'][$productVarietalKey] = $productVarietalVal;
                                    $returnDatas['market']['category'][$catKey]['brand'][$brandKey]['marketBrandsVerietals'][$productVarietalKey]['brand_id'] = $brandVal['id'];
                                    //Brand shares value
                                    $marketBrandVarientalShares = MarketBrandsVerietals::find()->select(['shares'])->andWhere(['verietal_id' => $productVarietalVal['id'], 'market_id' => $marketId, 'brand_id' => $brandVal['id'], 'category_id' => $catVal['id']])->orderBy(['reorder_id' => SORT_ASC])->asArray()->all();
                                    if (!empty($marketBrandVarientalShares)) {
                                        foreach ($marketBrandVarientalShares as $marketBrandVarientalSharesKey => $marketBrandVarientalSharesVal) {
                                            $returnDatas['market']['category'][$catKey]['brand'][$brandKey]['marketBrandsVerietals'][$productVarietalKey]['shares'] = $marketBrandVarientalSharesVal['shares'];
                                        }
                                    }
                                    if (isset($returnDatas['market']['category'][$catKey]['brand'][$brandKey]['marketBrandsVerietals']) && !empty($returnDatas['market']['category'][$catKey]['brand'][$brandKey]['marketBrandsVerietals']))
                                        $variental = $returnDatas['market']['category'][$catKey]['brand'][$brandKey]['marketBrandsVerietals'];
                                    foreach ($variental as $vKey => $vVal) {
                                        if (isset($returnDatas['market']['category'][$catKey]['brand'][$brandKey]['marketBrandsVerietals'][$productVarietalKey])) {
                                            $returnDatas['market']['category'][$catKey]['brand'][$brandKey]['marketBrandsVerietals'][$productVarietalKey]['product'] = isset($marketVarientalProduct[$catVal['id']][$brandVal['id']][$productVarietalVal['id']]) ? $marketVarientalProduct[$catVal['id']][$brandVal['id']][$productVarietalVal['id']] : array();
                                            if (!empty($marketVarientalProduct[$catVal['id']][$brandVal['id']][$productVarietalVal['id']])) {
                                                foreach ($marketVarientalProduct[$catVal['id']][$brandVal['id']][$productVarietalVal['id']] as $marketVarientalProdDetailKey => $marketVarientalProdDetail) {
                                                    $returnDatas['market']['category'][$catKey]['brand'][$brandKey]['marketBrandsVerietals'][$productVarietalKey]['product'][$marketVarientalProdDetailKey]['image'] = isset($marketVarientalProdDetail['image']) && ($marketVarientalProdDetail['image'] != '') ? CommonHelper::getPath('upload_url') . UPLOAD_PATH_CATALOGUES_IMAGES . rawurlencode($marketVarientalProdDetail['image']) : '';
                                                    $returnDatas['market']['category'][$catKey]['brand'][$brandKey]['marketBrandsVerietals'][$productVarietalKey]['product'][$marketVarientalProdDetailKey]['box_only'] = \yii::$app->params['catalogue_status'][$marketVarientalProdDetail['box_only']];
                                                    $returnDatas['market']['category'][$catKey]['brand'][$brandKey]['marketBrandsVerietals'][$productVarietalKey]['product'][$marketVarientalProdDetailKey]['top_shelf'] = \yii::$app->params['catalogue_status'][$marketVarientalProdDetail['top_shelf']];
                                                    $returnDatas['market']['category'][$catKey]['brand'][$brandKey]['marketBrandsVerietals'][$productVarietalKey]['product'][$marketVarientalProdDetailKey]['brand'] = isset($brands[$marketVarientalProdDetail['brand_id']]) && !empty($brands[$marketVarientalProdDetail['brand_id']]) ? $brands[$marketVarientalProdDetail['brand_id']] : array();
                                                    $returnDatas['market']['category'][$catKey]['brand'][$brandKey]['marketBrandsVerietals'][$productVarietalKey]['product'][$marketVarientalProdDetailKey]['product_catgeory'] = isset($productCategories[$marketVarientalProdDetail['product_category_id']]) && !empty($productCategories[$marketVarientalProdDetail['product_category_id']]) ? $productCategories[$marketVarientalProdDetail['product_category_id']] : array();
                                                    $returnDatas['market']['category'][$catKey]['brand'][$brandKey]['marketBrandsVerietals'][$productVarietalKey]['product'][$marketVarientalProdDetailKey]['product_type'] = isset($productTypes[$marketVarientalProdDetail['product_type_id']]) && !empty($productTypes[$marketVarientalProdDetail['product_type_id']]) ? $productTypes[$marketVarientalProdDetail['product_type_id']] : array();
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                $returnDatas['market']['category'][$catKey]['brand'][$brandKey]['marketBrandsVerietals'] = array();
                            }
                        }
                    } else {
                        $returnDatas['market']['category'][$catKey]['brand'] = array();
                    }
                }
            } else {
                $returnDatas['market']['category'] = array();
            }
        }
        return $returnDatas;
    }
}
