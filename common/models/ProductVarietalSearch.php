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
        $varietalList = array();
        if($productVarietalRepository['status']['success'] == 1){
            if($productVarietalRepository['data']['productVarietal']){
                foreach ($productVarietalRepository['data']['productVarietal'] as $key => $value) {
                    $temp = $value;
                    $temp['name']= ucfirst($temp['name']);
                    
                    $varietalList[]=$temp;
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
                'defaultOrder' => [
                    'name' => SORT_ASC,
                ]
            ]
        ]);

        return $dataProvider;
    }
}
