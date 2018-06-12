<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\StoreConfiguration;

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
        $query = StoreConfiguration::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'store_id' => $this->store_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'config_name', $this->config_name])
            ->andFilterWhere(['like', 'shelf_thumb', $this->shelf_thumb])
            ->andFilterWhere(['like', 'star_ratings', $this->star_ratings])
            ->andFilterWhere(['like', 'is_verified', $this->is_verified])
            ->andFilterWhere(['like', 'is_autofill', $this->is_autofill]);

        return $dataProvider;
    }
}
