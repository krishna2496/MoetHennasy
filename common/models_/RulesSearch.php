<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Rules;
use yii\data\ArrayDataProvider;
use common\repository\RulesRepository;

/**
 * RulesSearch represents the model behind the search form of `common\models\Rules`.
 */
class RulesSearch extends Rules
{
    
    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['type', 'product_fields', 'detail', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $rulesRepository = new RulesRepository();
        $rulesRepository = $rulesRepository->listing($params); 
        $rulesList = array();
        if($rulesRepository['status']['success'] == 1){
            if($rulesRepository['data']['rules']){
                foreach ($rulesRepository['data']['rules'] as $key => $value) {
                    $temp=$value;
                    $temp['type']=\yii\helpers\BaseInflector::camel2words(\yii\helpers\BaseInflector::camelize($temp['type']));
                    $temp['product_fields']=\yii\helpers\BaseInflector::camel2words(\yii\helpers\BaseInflector::camelize($temp['product_fields']));
                    $temp['detail']=\yii\helpers\BaseInflector::camel2words(\yii\helpers\BaseInflector::camelize($temp['detail']));
                    $rulesList []=$temp;
                }
            }
        }
        
       $dataProvider = new ArrayDataProvider([
            'allModels' => $rulesList,
            'pagination' => [
                'pageSize' => $params['limit']
            ],
            'sort' => [
                'attributes' =>
                [
                    'type','product_fields','detail'
                ],
                
            ]
        ]);

        return $dataProvider;
    }
}
