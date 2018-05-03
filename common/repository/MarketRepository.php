<?php
namespace common\repository;

use Yii;
use common\helpers\CommonHelper;
use common\models\Markets;

class MarketRepository extends Repository
{
    public function marketList($data = array())
    {
        $this->apiCode = 1;
        $query = Markets::find();
        $returnData = array();
        $returnData['markets'] = $query->asArray()->all();
        $this->apiData = $returnData;
        return $this->response();
    }
}