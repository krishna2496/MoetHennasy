<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Catalogues;
use common\repository\CataloguesRepository;
use yii\data\ArrayDataProvider;

class CataloguesSearch extends Catalogues
{
    
    public function rules()
    {
        return [
            [['id', 'brand_id', 'product_category_id', 'product_sub_category_id', 'product_type_id', 'market_id', 'market_share', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['sku', 'ean', 'image', 'short_name', 'long_name', 'short_description', 'manufacturer', 'box_only', 'top_shelf', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['width', 'height', 'length', 'scale', 'price'], 'number'],
        ];
    }

    public function scenarios()
    {
        
        return Model::scenarios();
    }

    public function search($params)
    {
        $userRepository = new CataloguesRepository;
        $userList = array();
        $resultUserList = $userRepository->listing($params);
        if($resultUserList['status']['success'] == 1){
            if($resultUserList['data']['catalogues']){
                foreach ($resultUserList['data']['catalogues'] as $key => $value) {
                   
                    $temp = $value;
                    $temp['marketName'] = $value['market']['title'];
                    $temp['brandName'] = $value['brand']['name'];
                    $userList[] = $temp;
                }
            }
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $userList,
            'pagination' => [
                'pageSize' => $params['limit']
            ],
            'sort' => [
                'attributes' =>
                [
                    'sku',
                    'ean',
                    'marketName',
                    'brandName',
                    'price'
                  
                ],
               
            ]
        ]);

        return $dataProvider;
    }
}
