<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\StoreConfiguration;
use common\repository\StoreConfigRepository;
use yii\data\ArrayDataProvider;

/**
 * StoreConfigurationSearch represents the model behind the search form of `common\models\StoreConfiguration`.
 */
class StoreConfigurationSearch extends StoreConfiguration
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'store_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['config_name', 'shelf_thumb', 'star_ratings', 'is_verified', 'is_autofill', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
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
        $userRepository = new StoreConfigRepository();
        $userList = array();
        $resultUserList = $userRepository->listing($params);
        
        if ($resultUserList['status']['success'] == 1) {
            if ($resultUserList['data']['stores_config']) {
                foreach ($resultUserList['data']['stores_config'] as $key => $value) {
                   
                    $temp = $value;
                   
                    $userList[] = $temp;
                    
                }
            }
        }
        if(!isset($params['limit'])){
            $params['limit'] = count($userList);
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $userList,
            'pagination' => [
                'pageSize' => $params['limit'],
            ],
            'sort' => [
                'attributes' =>
                    [
                    'sku',
                    'ean',
                    'short_name',
                    'productCategory',
                  
                    'marketName',
                    'brandName',
                    'price'
                ],
            ]
        ]);
       

        return $dataProvider;
    }
}
