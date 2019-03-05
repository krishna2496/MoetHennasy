<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use common\repository\HelpsRepository;
use common\models\Helps;

class HelpsSearch extends Helps
{
    
    public function rules()
    {
        return [
            [['id', 'category_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['question', 'answer', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $userRepository = new HelpsRepository();
        $userList = array();
        $resultUserList = $userRepository->listing($params);
     
        if($resultUserList['status']['success'] == 1){
            if($resultUserList['data']['helps']){
                foreach ($resultUserList['data']['helps'] as $key => $value) {
                
                    $userList[] = $value;
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
                   'question'
                  
                ],
               
            ]
        ]);

        return $dataProvider;
    }
}
