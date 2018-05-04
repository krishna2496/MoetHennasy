<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use common\models\Markets;
use common\repository\MarketRepository;

/**
 * MarketSegmentsSearch represents the model behind the search form of `common\models\MarketSegments`.
 */
class MarketSearch extends Markets
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['title', 'description', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
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
      $marketRepository = new MarketRepository;
        $marketList = array();
        $resultMarketList = $marketRepository->marketList($params);
       
        if($resultMarketList['status']['success'] == 1){
            if($resultMarketList['data']['markets']){
                foreach ($resultMarketList['data']['markets'] as $key => $value) {
                    $temp = $value;
                    $temp['title'] = $value['title'];
//                    $temp['market_segment_id'] = $value['market_segment_id'];
                    $marketList[] = $temp;
                }
            }
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $marketList,
            'pagination' => [
                'pageSize' => $params['limit']
            ],
            'sort' => [
                'attributes' =>
                [
                    'title',
//                    'market_segment_id',
                ],
                'defaultOrder' => [
                    'title' => SORT_ASC,
                ]
            ]
        ]);

        return $dataProvider;
    }
}
