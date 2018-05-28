<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\HelpCategories;
use common\repository\HelpCategoriesRepository;
use yii\data\ArrayDataProvider;
/**
 * HelpCategoriesSearch represents the model behind the search form of `common\models\HelpCategories`.
 */
class HelpCategoriesSearch extends HelpCategories
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['title', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
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
        $userRepository = new HelpCategoriesRepository();
        $userList = array();
        $resultUserList = $userRepository->listing($params);
     
        if($resultUserList['status']['success'] == 1){
            if($resultUserList['data']['help_categories']){
                foreach ($resultUserList['data']['help_categories'] as $key => $value) {
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
                   'title'
                  
                ],
               
            ]
        ]);

        return $dataProvider;
    }
}
