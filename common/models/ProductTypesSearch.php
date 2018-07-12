<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ProductTypes;
use common\repository\ProductTypesRepository;
use yii\data\ArrayDataProvider;

class ProductTypesSearch extends ProductTypes
{
    
    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['title', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
      return Model::scenarios();
    }

    public function search($params)
    {
        $brandRepository = new ProductTypesRepository;
        $brandRepository = $brandRepository->listing($params); 
        $brandList = array();
        if($brandRepository['status']['success'] == 1){
            if($brandRepository['data']['productTypes']){
                foreach ($brandRepository['data']['productTypes'] as $key => $value) {
                    $brandList[] = $value;
                }
            }
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $brandList,
            'pagination' => [
                'pageSize' => $params['limit']
            ],
            'sort' => [
                'attributes' =>
                [
                    'title',
                ],
                'defaultOrder' => [
                    'title' => SORT_ASC,
                ]
            ]
        ]);

        return $dataProvider;
    }
}
