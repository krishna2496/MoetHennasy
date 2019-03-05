<?php

namespace common\models;

use Yii;
//use common\models\Catalogues;

class RuleAdministration extends \yii\db\ActiveRecord
{
    public $market_id;
    public $market_cluster_id;
    public $brand_id;
    public $product_category_id;
    public $limit;


    public function rules()
    {
        return [
            [['market', 'marketCluster', 'brand','product_category_id'], 'integer'],
           
        ];
    }

    public function attributeLabels()
    {
        return [
            'market_id' => 'Market',
            'market_cluster_id' => 'Market Cluster',
            'brand_id' => 'Brand',
            'product_category_id' => 'Product Category',
           
        ];
    }
    
    
}
