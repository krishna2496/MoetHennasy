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
