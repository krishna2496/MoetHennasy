<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Glossary;
use yii\data\ArrayDataProvider;
use common\repository\GlossaryRepository;

class GlossarySearch extends Glossary
{
    public function rules()
    {
        return [
            [['id', 'title', 'description', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
       
        return Model::scenarios();
    }


    
    public function search($params)
    {
        $brandRepository = new GlossaryRepository();
        $brandRepository = $brandRepository->listing($params); 
        $brandList = array();
        if($brandRepository['status']['success'] == 1){
            if($brandRepository['data']['glossary']){
                foreach ($brandRepository['data']['glossary'] as $key => $value) {
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
                    'description'
                ],
                'defaultOrder' => [
                    'title' => SORT_ASC,
                ]
            ]
        ]);

        return $dataProvider;
    }
}
