<?php

namespace common\repository;

use Yii;
use common\helpers\CommonHelper;
use common\models\MarketBrands;

class MarketBrandsRepository extends Repository {

    public function listing($data = array()) {

        $this->apiCode = 1;
        $query = MarketBrands::find()->joinWith('brand');
        if (isset($data['market_id']) && ($data['market_id'] != '')) {
            $query->andWhere(['market_id' => $data['market_id']]);
        }
        $data = array();
        $data['market_brands'] = $query->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }

    public function createRule($data = array()) {
        
        $this->apiCode = 0;
        $flag=1;
        $marketSegmentData = $data['brand_id'];
        $model = new MarketBrands;
        $model->deleteAll(['market_id' => $data['market_id']],true);
        foreach ($marketSegmentData as $value) {
            $modelSegment = new MarketBrands();
            $modelSegment->market_id = $data['market_id'];
            $modelSegment->brand_id = $value;
            if ($modelSegment->validate(false)) {
                $modelSegment->save(false);
            }else{
                $flag = 0;
            }
        }
        if($flag == 1){
              $this->apiCode = 1;
              $this->apiMessage = Yii::t('app','apply_brand');
        }else{
            $this->apiCode = 0;
            if (isset($model->errors) && $model->errors) {
                $this->apiMessage = $model->errors;
            } 
        }
  
        return $this->response();
    }
}
