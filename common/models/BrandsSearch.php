<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use common\models\Brands;
use common\repository\BrandRepository;

class BrandsSearch extends Brands
{
    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['name', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $brandRepository = new BrandRepository();
        $brandRepository = $brandRepository->listing($params); 
        $brandList = array();
        if($brandRepository['status']['success'] == 1){
            if($brandRepository['data']['brand']){
                foreach ($brandRepository['data']['brand'] as $key => $value) {
                $temp=$value;
                $temp['name']=\yii\helpers\BaseInflector::camel2words(\yii\helpers\BaseInflector::camelize($temp['name']));
                $brandList[] = $temp;
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
                    'Title'=> [
                        'asc' => ['name' => SORT_ASC],
                        'desc' => ['name' => SORT_DESC],
                        'default' => SORT_ASC
                        ],
                ],
                'defaultOrder' => [
                    'Title' => SORT_ASC,
                ]
            ]
        ]);

        return $dataProvider;
    }
}
