<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use common\models\MarketSegments;
use common\repository\MarketSegmentsRepository;

/**
 * MarketSegmentsSearch represents the model behind the search form of `common\models\MarketSegments`.
 */
class MarketSegmentsSearch extends MarketSegments
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
      $marketSegmentsRepository = new MarketSegmentsRepository;
        $marketSegmentsList = array();
        $resultMarketSegmentsList = $marketSegmentsRepository->marketSegmentsList($params);
        if($resultMarketSegmentsList['status']['success'] == 1){
            if($resultMarketSegmentsList['data']['market_segments']){
                foreach ($resultMarketSegmentsList['data']['market_segments'] as $key => $value) {
                    $temp = $value;
                    $temp['title'] = $value['title'];
                    $temp['description'] = isset($value['description']) ? $value['description'] : '';
                    $temp['title']=\yii\helpers\BaseInflector::camel2words(\yii\helpers\BaseInflector::camelize($temp['title']));
                    $temp['description']=\yii\helpers\BaseInflector::camel2words(\yii\helpers\BaseInflector::camelize($temp['description']));
                    $marketSegmentsList[] = $temp;
                }
            }
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $marketSegmentsList,
            'pagination' => [
                'pageSize' => $params['limit']
            ],
            'sort' => [
                'attributes' =>
                [
                    'title',
                    'description',
                ],
                'defaultOrder' => [
                    'title' => SORT_ASC,
                ]
            ]
        ]);

        return $dataProvider;
    }
}
