<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use common\models\Configs;
use common\repository\ConfigsRepository;

class ConfigsSearch extends Configs
{
   
    public function rules()
    {
        return [
            [['id', 'store_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['key', 'value', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $configRepository = new ConfigsRepository();
        $configList = array();
        $resultConfigList = $configRepository->listing($params);
     
        if($resultConfigList['status']['success'] == 1){
            if($resultConfigList['data']['configs']){
                foreach ($resultConfigList['data']['configs'] as $key => $value) {
                    $configList[] = $value;
                }
            }
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $configList,
            'pagination' => [
                'pageSize' => $params['limit']
            ],
            'sort' => [
                'attributes' =>
                [
                    'value',
                  
                ],
               
            ]
        ]);

        return $dataProvider;
    }
}
