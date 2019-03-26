<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use common\models\Brands;
use common\repository\BrandRepository;
use common\repository\MarketBrandsRepository;

class BrandsSearch extends Brands
{
    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['name', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $brandRepository = new BrandRepository();
        $brandRepository = $brandRepository->listing($params); 
       
        $brandList = array();
        if($brandRepository['status']['success'] == 1){
            if($brandRepository['data']['brand']){
                foreach ($brandRepository['data']['brand'] as $key => $value) {
                $temp=$value;
                $temp['name']= ucfirst($temp['name']);
                $brandList[] = $temp;
                }
            }
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $brandList,
            'pagination' => [
                'pageSize' => $params['limit']
            ],
            'sort' => [
            
                'attributes' =>
                [
                    'Title'=> [
                        'asc' => ['name' => SORT_ASC],
                        'desc' => ['name' => SORT_DESC],
                        'default' => SORT_ASC
                        ],
                  
                ],
               
            ]
        ]);

        return $dataProvider;
    }
    
     public function searchMarketBrand($params)
    {
     
        $brandRepository = new MarketBrandsRepository();
        $brandRepository = $brandRepository->listingBrand($params); 
        
        $brand = new MarketBrandsRepository();
        $brandData =  $brand->productVariental($params);
        $removeDataId = array();
        $brandList = $brandVarientalData = array();
        if($brandRepository['status']['success'] == 1){
            if($brandRepository['data']['brand_data']){
                
                if($brandData['data']['catalogue']){
                    foreach ($brandData['data']['catalogue'] as $k => $v){
                        $removeDataId[$v['brand_id']] =  $v['brand_id'];
                    }
                }
              
                $brandRepositoryNew = new MarketBrandsRepository();
                $brandRepositoryNewData = $brandRepositoryNew->listingMarketBrand($params); 
               
//                market_brands
                
                $max =  \common\helpers\CommonHelper::max_val($brandRepositoryNewData['data']['market_brands'], 'reorder_id', SORT_DESC);
                if ($brandRepositoryNewData['status']['success'] == 1) {
                    if ($brandRepositoryNewData['data']['market_brands']) {
                        foreach ($brandRepositoryNewData['data']['market_brands'] as $key => $value) {

                            $brandVarientalData[$value['brand_id']]['reorder_id'] = $value['reorder_id'];
                            $brandVarientalData[$value['brand_id']]['shares'] = $value['shares'];

                        }
                    }
                }
                
                foreach ($brandRepository['data']['brand_data'] as $key => $value) {
                  
                    if(in_array($value['id'], $removeDataId)){ 
                        $temp=$value;
                        $temp['name']= ucfirst($temp['name']);
                        
                        $temp['reorder_id'] = isset($brandVarientalData[$value['id']]) ? $brandVarientalData[$value['id']]['reorder_id']!= 0 ? $brandVarientalData[$value['id']]['reorder_id'] :$max++ : $max++;
                        $temp['shares'] = isset($brandVarientalData[$value['id']]['shares']) ? $brandVarientalData[$value['id']]['shares'] : 0;
                        $brandList[] = $temp;
                    }
                }
               
                
                
            }
        }
       \common\helpers\CommonHelper::sort_array_of_array($brandList, 'reorder_id', SORT_ASC);
        $dataProvider = new ArrayDataProvider([
            'allModels' => $brandList,
            'pagination' => [
                'pageSize' => $params['limit']
            ],
            
        ]);

        return $dataProvider;
    }
    
//      public function searchVariental($params) {
//        $brandVarientalData = array();
//        $productVarietalRepository = new ProductVarietalRepository();
//        $productVarietalRepository = $productVarietalRepository->listingVariental($params);
//        $max = 1;
//        if ($productVarietalRepository) {
//            $productVarietalRepositoryNew = new ProductVarietalRepository();
//            $marketBrandVriental = $productVarietalRepositoryNew->listingMarketBrandVriental($params);
//            
//            $max =  \common\helpers\CommonHelper::max_val($marketBrandVriental['data']['productVarietal'], 'reorder_id', SORT_DESC);
//            if ($marketBrandVriental['status']['success'] == 1) {
//                if ($marketBrandVriental['data']['productVarietal']) {
//                    foreach ($marketBrandVriental['data']['productVarietal'] as $key => $value) {
//
//                        $brandVarientalData[$value['verietal_id']]['reorder_id'] = $value['reorder_id'];
//                       
//                    }
//                }
//            }
//        }
//
//        $varietalList = array();
//        if ($productVarietalRepository['status']['success'] == 1) {
//            if ($productVarietalRepository['data']['productVarietal']) {
//                foreach ($productVarietalRepository['data']['productVarietal'] as $key => $value) {
//                    $temp = $value;
//                    $temp['name'] = ucfirst($temp['name']);
//                    $temp['reorder_id'] = isset($brandVarientalData[$value['id']]) ? $brandVarientalData[$value['id']]['reorder_id']!= NULL ? $brandVarientalData[$value['id']]['reorder_id'] :$max++ : $max++;
//                    $temp['shares'] = isset($brandVarientalData[$value['id']]['shares']) ? $brandVarientalData[$value['id']]['shares'] : 0;
//                    $varietalList[] = $temp;
//                }
//            }
//        }
//      
//        
//        \common\helpers\CommonHelper::sort_array_of_array($varietalList, 'reorder_id', SORT_ASC);
//        
////        $this->sort_array_of_array($varietalList, 'reorder_id', SORT_DESC);
//        $dataProvider = new ArrayDataProvider([
//            'allModels' => $varietalList,
//            
//        ]);
//
//        return $dataProvider;
//    }
}
