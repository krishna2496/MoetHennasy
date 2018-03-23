<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Permission;

class PermissionSearch extends Permission
{
    public function rules()
    {
        return [
            [['id', 'parent_id'], 'integer'],
            [['permission_label', 'permission_title'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Permission::find();
        
        $this->load($params);

        $query->andFilterWhere([
            'id' => $this->id,
            'parent_id' => $this->parent_id,
        ]);

        $query->andFilterWhere(['like', 'permission_label', $this->permission_label])
            ->andFilterWhere(['like', 'permission_title', $this->permission_title]);
        return $query->asArray()->all();
    }
}
