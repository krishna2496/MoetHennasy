<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use common\models\ProductCategories;
use common\repository\ProductVarietalRepository;

class ProductVarietalSearch extends ProductCategories
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
        
        $productVarietalRepository = new ProductVarietalRepository();
        $productVarietalRepository = $productVarietalRepository->listing($params); 
       echo '<pre>';
        print_r($productVarietalRepository);exit;
        $varietalList = array();
        if($productVarietalRepository['status']['success'] == 1){
            if($productVarietalRepository['data']['productVarietal']){
                foreach ($productVarietalRepository['data']['productVarietal'] as $key => $value) {
                    if(isset($value['marketBrandsVerietals'])){
                        if($value['marketBrandsVerietals']['market_id'] != $params['market_id'] && $value['marketBrandsVerietals']['brand_id'] != $params['brand_id'] && $value['marketBrandsVerietals']['category_id'] != $params['category_id']){
                            unset($value['marketBrandsVerietals']);
                            $value['marketBrandsVerietals'] =[];
                        }
                    }
                    $temp = $value;
                    $temp['name']= ucfirst($temp['name']);
                    
                    $varietalList[]=$temp;
               }
            }
        }
 
        $dataProvider = new ArrayDataProvider([
            'allModels' => $varietalList,
            
            'sort' => [
                'attributes' =>
                [
                    'name',
                ],
                
            ]
        ]);

        return $dataProvider;
    }
}
