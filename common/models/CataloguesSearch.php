<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Catalogues;
use common\repository\CataloguesRepository;
use yii\data\ArrayDataProvider;

class CataloguesSearch extends Catalogues {

    public function rules() {
        return [
                [['id', 'brand_id', 'product_category_id', 'product_sub_category_id', 'product_type_id', 'market_id', 'market_share', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
                [['sku', 'ean', 'image', 'short_name', 'long_name', 'short_description', 'manufacturer', 'box_only', 'top_shelf', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
                [['width', 'height', 'length', 'scale', 'price'], 'number'],
        ];
    }

    public function scenarios() {

        return Model::scenarios();
    }

    public function search($params) {
      
        $userRepository = new CataloguesRepository;
        $userList = array();
        $resultUserList = $userRepository->listing($params);
        if ($resultUserList['status']['success'] == 1) {
            if ($resultUserList['data']['catalogues']) {
                foreach ($resultUserList['data']['catalogues'] as $key => $value) {
                    $temp = $value;
                    $temp['marketName'] = ucfirst($temp['market']['title']);
                    $temp['brandName'] = ucfirst($temp['brand']['name']);
                    $temp['productCategory'] = ucfirst($temp['productCategory']['name']);
                    $temp['sku']= ucfirst($temp['sku']);
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
                 
                'defaultOrder'=>['reorder_id'=>SORT_ASC],
                'attributes' =>
                    [
                    'reorder_id'  ,
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
       
        if (isset($params['selection']) && ($params['selection'] != '')) {
            $dataProvider->pagination->params = ['selection' => $params['selection']];
        }
        
        return $dataProvider;
    }

}
