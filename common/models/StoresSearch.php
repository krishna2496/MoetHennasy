<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use common\repository\StoreRepository;

class StoresSearch extends Stores
{
    public function rules()
    {
        return [
            [['id', 'market_id', 'market_segment_id', 'country_id', 'city_id', 'assign_to', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['name', 'photo', 'address1', 'address2', 'latitude', 'longitude', 'comment', 'store_manager_first_name', 'store_manager_last_name', 'store_manager_email', 'store_manager_phone_code', 'store_manager_phone_number', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $storeRepository = new StoreRepository;
        $storeList = array();
        $resultStoreList = $storeRepository->storeList($params);
      
        if($resultStoreList['status']['success'] == 1){
            if($resultStoreList['data']['stores']){
                foreach ($resultStoreList['data']['stores'] as $key => $value) {
                    $temp = $value;
                    $temp['assignTo'] = isset($value['user']['first_name']) ? $value['user']['first_name'].' '.$value['user']['last_name'] : '';
                    $temp['market'] = isset($value['market']['title']) ? $value['market']['title'] : '';
                    $temp['marketSegment'] = isset($value['marketSegment']['title']) ? $value['marketSegment']['title'] : '';
                    $temp['assignTo']=\yii\helpers\BaseInflector::camel2words(\yii\helpers\BaseInflector::camelize($temp['assignTo']));
                    $temp['market']=\yii\helpers\BaseInflector::camel2words(\yii\helpers\BaseInflector::camelize($temp['market']));
                    $temp['marketSegment']=\yii\helpers\BaseInflector::camel2words(\yii\helpers\BaseInflector::camelize($temp['marketSegment']));
                    $temp['name']=\yii\helpers\BaseInflector::camel2words(\yii\helpers\BaseInflector::camelize($temp['name']));
                    $temp['address1']=\yii\helpers\BaseInflector::camel2words(\yii\helpers\BaseInflector::camelize($temp['address1']));
                    $storeList[] = $temp;
                }
            }
        }
//      pre($storeList);exit;
        $dataProvider = new ArrayDataProvider([
            'allModels' => $storeList,
            'pagination' => [
                'pageSize' => $params['limit']
            ],
            'sort' => [
                'attributes' =>
                ['id',
                    'name',
                    'market',
                    'marketSegment',
                    'address1',
                    'assignTo',
                ],
                'defaultOrder' => [
                    'name' => SORT_ASC,
                ]
            ]
        ]);
          $dataProvider->sort->attributes['city_id'] = [
          'asc'  => ['cities.name' => SORT_ASC ],
          'desc' => ['cities.name' => SORT_DESC],
        ];
//        echo '<pre>';
//        print_r($dataProvider);exit;
        return $dataProvider;
    }
}
