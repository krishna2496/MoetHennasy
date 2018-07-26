<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use common\models\ProductCategories;
use common\repository\ProductCategoryRepository;

class ProductCategoriesSearch extends ProductCategories
{    
    public function rules()
    {
        return [
            [['id', 'parent_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
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
        $productCategoryRepository = new ProductCategoryRepository();
        $productCategoryRepository = $productCategoryRepository->listing($params); 
        $categoryList = array();
        if($productCategoryRepository['status']['success'] == 1){
            if($productCategoryRepository['data']['productCategories']){
                foreach ($productCategoryRepository['data']['productCategories'] as $key => $value) {
                    $temp = $value;
                    $temp['name']=\yii\helpers\BaseInflector::camel2words(\yii\helpers\BaseInflector::camelize($temp['name']));
                    $temp ["parentCategory"]['name']=\yii\helpers\BaseInflector::camel2words(\yii\helpers\BaseInflector::camelize($temp["parentCategory"]['name']));
                    
                    $categoryList[]=$temp;
               }
            }
        }
//        echo "<pre>";
//        print_r($categoryList['name']);
//        print_r($categoryList['parentCategory']['name']);
//        exit;

        $dataProvider = new ArrayDataProvider([
            'allModels' => $categoryList,
            'pagination' => [
                'pageSize' => $params['limit']
            ],
            'sort' => [
                'attributes' =>
                [
                    'name',
                    'parentCategory.name',
                ],
                'defaultOrder' => [
                    'name' => SORT_ASC,
                ]
            ]
        ]);

        return $dataProvider;
    }
}
