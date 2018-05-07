<?php
namespace common\repository;

use Yii;
use common\helpers\CommonHelper;
use common\models\Cities;
use common\models\Countries;
use common\models\Province;

class MasterDataRepository extends Repository
{
    public function countries($data = array()) {
        $this->apiCode = 1;
        $query = Countries::find();

        $data = array();
        $data['countries'] = $query->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }

    public function cities($data = array()) {
        $this->apiCode = 1;
        $query = Cities::find();
        if (isset($data['country_id']) && $data['country_id']) {
        	$query->andWhere(['country_id'=>$data['country_id']]);
        }
        $data = array();
        $data['cities'] = $query->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }

    public function provinces($data = array()) {
        $this->apiCode = 1;
        $query = Province::find();
        if (isset($data['city_id']) && $data['city_id']) {
        	$query->andWhere(['city_id'=>$data['city_id']]);
        }
        $data = array();
        $data['provinces'] = $query->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }
}