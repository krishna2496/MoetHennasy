<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use common\models\Questions;
use common\repository\QuestionsRepository;

class QuestionsSearch extends Questions
{
   
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['question', 'response_type'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $questionsRepository = new QuestionsRepository();
        $questionsListRepository = $questionsRepository->listing($params); 
        $questionsList = array();
        if($questionsListRepository['status']['success'] == 1){
            if($questionsListRepository['data']['questions']){
                foreach ($questionsListRepository['data']['questions'] as $key => $value) {
                    $questionsList[] = $value;
                }
            }
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $questionsList,
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
