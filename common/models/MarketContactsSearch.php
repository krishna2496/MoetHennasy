<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use common\models\MarketContacts;
use common\repository\MarketContactRepository;

class MarketContactsSearch extends MarketContacts
{
    public function rules()
    {
        return [
            [['id', 'market_segment_id', 'market_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['address', 'phone', 'email', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
         return Model::scenarios();
    }

    public function search($params)
    {
        $repository = new MarketContactRepository();
        $list = array();
        $resultList = $repository->listing($params);
        if($resultList['status']['success'] == 1){
            if($resultList['data']['contacts']){
                foreach ($resultList['data']['contacts'] as $key => $value) {
                    $list[] = $value;
                }
            }
        }
     
        $dataProvider = new ArrayDataProvider([
            'allModels' => $list,
            'pagination' => [
                'pageSize' => $params['limit']
            ],
            'sort' => [
                'attributes' =>
                [
                   
                  
                ],
               
            ]
        ]);

        return $dataProvider;
    }
}
