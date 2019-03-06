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
       
        $returnDatas = $newProductArry = $marketVarientalProduct = $brandVariental = array();
        $repository = new MarketBrandsRepository();

        if ($marketId != '') {
            $data['market_id'] = $marketId;
            $returnData = $repository->listing($data);

            $productsData = $returnData['data']['market_product'];
            $brand_variental = $returnData['data']['brand_varietal'];
             
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
                    //print_r($marketBrandShares); exit;
                    $value['brand']['reorder_id'] = $value['reorder_id'];
                    $variental = $value['brand']['marketBrandsVerietals'];
//                                    echo '<pre>';
//                                    print_r($value['category']['id']);exit;
                     $brandVariental = MarketBrandsVerietals::find()->andWhere(['market_id' => $value['market_id'], 'brand_id' => $value['brand']['id'], 'category_id' => $value['category']['id']])->asArray()->all();

                    //$brandVariental = MarketBrandsVerietals::find()->select(['verietal_id'])->andWhere(['market_id' => $value['market_id'], 'brand_id' => $value['brand']['id'], 'category_id' => $value['category']['id']]);
                    //print_r($variental);exit;
                    if ($brandVariental) {
                        foreach ($brandVariental as $k => $v) {
                            $value['brand']['marketBrandsVerietals'][$value['brand']['id']][$k] = $v;
                        }
                    }
                   // print_r($value); exit;
                    foreach ($variental as $vKey => $vVal) {
                        if (isset($value['brand']['marketBrandsVerietals'][$vKey])) {
                            $value['brand']['marketBrandsVerietals'][$value['brand']['id']][$vKey]['product'] = isset($marketVarientalProduct[$value['category']['id']][$value['brand']['id']][$vVal['verietal_id']]) ? $marketVarientalProduct[$value['category']['id']][$value['brand']['id']][$vVal['verietal_id']] : '';
                        }
                    }
  
                    $product_data = '';



                    unset($value['brand']['product']);

                    if (!in_array($value['category']['id'], $collectCatId)) {
                        // $value['category']['brand'][] = $value['brand'];
                        $value['category']['top_shelf_product'] = isset($newProductFinalArry[$value['category']['id']]) ? $newProductFinalArry[$value['category']['id']] : '';
                        $returnDatas["market"]['category'][] = $value['category'];


                        $collectCatId[] = $value['category']['id'];
                    }


                    $key = array_search($value['category']['id'], array_column($returnDatas["market"]['category'], 'id'));
                    $returnDatas["market"]['category'][$key]['brand'][] = $value['brand'];
                }
            }
        }
        
        return $returnDatas;
    }
}
