<?php
namespace common\repository;

use Yii;
use common\helpers\CommonHelper;
use common\models\Brands;


class BrandRepository extends Repository
{
      public function listing($data = array()) {
        $this->apiCode = 1;
        $query = Brands::find();
        $result=$query->asArray();
        $data = array();
        $data['brand'] = $query->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }

}