<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use common\models\Ratings;
use common\repository\RatingsRepository;

class RatingsSearch extends Ratings
{

    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['rating', 'type', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
         return Model::scenarios();
    }

    public function search($params)
    {
        $userRepository = new RatingsRepository;
        $userList = array();
        $resultUserList = $userRepository->listing($params);
        if($resultUserList['status']['success'] == 1){
            if($resultUserList['data']['ratings']){
                foreach ($resultUserList['data']['ratings'] as $key => $value) {
                    $temp = $value;
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
                    'rating',
                    'type',
                    
                ],
               
            ]
        ]);

        return $dataProvider;
    }
}
