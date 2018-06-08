<?php

namespace common\models;

use Yii;

class MarketRules extends BaseModel
{
    
    public static function tableName()
    {
        return 'market_rules';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['market_id', 'rule_id', 'created_by', 'updated_by', 'deleted_by', 'created_at', 'updated_at', 'deleted_at'], 'required'],
            [['market_id', 'rule_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'market_id' => 'Market ID',
            'rule_id' => 'Rule ID',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }
}
