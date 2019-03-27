<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use common\models\ProductCategories;
use common\repository\ProductVarietalRepository;

class ProductVarietalSearch extends ProductCategories {

    public function rules() {
        return [
                [['id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
                [['name', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params) {
//        echo '<pre>';
//        print_r($params);exit;
        $productVarietalRepository = new ProductVarietalRepository();
        $productVarietalRepository = $productVarietalRepository->listingVariental($params);

        $varietalList = array();
        if ($productVarietalRepository['status']['success'] == 1) {
            if ($productVarietalRepository['data']['productVarietal']) {
                foreach ($productVarietalRepository['data']['productVarietal'] as $key => $value) {

                    $temp = $value;
                    $temp['name'] = ucfirst($temp['name']);

                    $varietalList[] = $temp;
                }
            }
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $varietalList,
            'pagination' => [
                'pageSize' => $params['limit']
            ],
            'sort' => [
                'attributes' =>
                    [
                    'name',
                ],
            ]
        ]);

        return $dataProvider;
    }

    public function searchVariental($params) {
        $brandVarientalData = array();
        $productVarietalRepository = new ProductVarietalRepository();
        $productVarietalRepository = $productVarietalRepository->listingVariental($params);
        $removeDataId = array();
        $product = new ProductVarietalRepository();
        $productData =  $product->productVariental($params);
        if($productData['status']['success'] == 1){
            if($productData['data']['catalogue']){
                foreach ($productData['data']['catalogue'] as $k => $v){

                    $removeDataId[$v['product_variental']] =  $v['product_variental'];
                }
            }
        }
        
        $max = 1;
        if ($productVarietalRepository) {
            $productVarietalRepositoryNew = new ProductVarietalRepository();
            $marketBrandVriental = $productVarietalRepositoryNew->listingMarketBrandVriental($params);
            
            $max =  \common\helpers\CommonHelper::max_val($marketBrandVriental['data']['productVarietal'], 'reorder_id', SORT_DESC);
            if ($marketBrandVriental['status']['success'] == 1) {
                if ($marketBrandVriental['data']['productVarietal']) {
                    foreach ($marketBrandVriental['data']['productVarietal'] as $key => $value) {
                      
                        $brandVarientalData[$value['verietal_id']]['reorder_id'] = $value['reorder_id'];
                       
                    }
                }
            }
        }

        $varietalList = array();
        if ($productVarietalRepository['status']['success'] == 1) {
            if ($productVarietalRepository['data']['productVarietal']) {
                foreach ($productVarietalRepository['data']['productVarietal'] as $key => $value) {
                    if(in_array($value['id'], $removeDataId)){
                        $temp = $value;
                        $temp['name'] = ucfirst($temp['name']);
                        $temp['reorder_id'] = isset($brandVarientalData[$value['id']]) ? $brandVarientalData[$value['id']]['reorder_id']!= NULL ? $brandVarientalData[$value['id']]['reorder_id'] :$max++ : $max++;
                        $temp['shares'] = isset($brandVarientalData[$value['id']]['shares']) ? $brandVarientalData[$value['id']]['shares'] : 0;
                        $varietalList[] = $temp;
                    }else{
                        unset($productVarietalRepository['data']['productVarietal'][$key]);
                    }
                }
            }
        }
      
        
        \common\helpers\CommonHelper::sort_array_of_array($varietalList, 'reorder_id', SORT_ASC);
        
//        $this->sort_array_of_array($varietalList, 'reorder_id', SORT_DESC);
        $dataProvider = new ArrayDataProvider([
            'allModels' => $varietalList,
            
        ]);

        return $dataProvider;
    }
     
}
