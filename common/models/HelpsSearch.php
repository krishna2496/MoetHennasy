<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use common\repository\HelpsRepository;
use common\models\Helps;

/**
 * HelpsSearch represents the model behind the search form of `common\models\Helps`.
 */
class HelpsSearch extends Helps
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['question', 'answer', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
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
                    'sku',
                    'ean',
                    'marketName',
                  
                ],
               
            ]
        ]);

        return $dataProvider;
    }
}
