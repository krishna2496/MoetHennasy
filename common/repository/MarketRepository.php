<?php

namespace common\repository;

use Yii;
use common\helpers\CommonHelper;
use common\models\Markets;
use common\models\MarketSegmentData;

class MarketRepository extends Repository {

    public function marketList($data = array()) {
        $this->apiCode = 1;
        $query = Markets::find()
            
             ->joinWith(['marketSegmentData.marketSegment','user']);
    
     
        
        if (isset($data['search']) && $data['search']) {
            $search = $data['search'];
            $query->andWhere([
                'or',
                    ['like', 'markets.title', $search],
                    ['like', 'market_segments.title', $search],
            ]);
        }

        if (isset($data['user_id']) && $data['user_id']) {
            $query->andWhere(['users.id' => $data['user_id']]);
        }

        $data = array();
        $data['markets'] = $query->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }

    public function createMarket($data = array()) {

        $this->apiCode = 0;
        $model = new Markets;

        $model->scenario = 'create';
        $model->title = $data['title'];

        if ($model->validate(false)) {
            $modelSegment = new MarketSegmentData;
            if ($model->save(false)) {
                $id = $model->id;
                $marketSegmentData = $data['market_segment_id'];
                foreach ($marketSegmentData as $value) {
                    $modelSegment = new MarketSegmentData;
                    if ($modelSegment->validate()) {
                        $modelSegment->market_id = $id;
                        $modelSegment->market_segment_id = $value;
                        $modelSegment->save(false);
                    }
                }
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'market_created_successfully');
            } else {
                $this->apiCode = 0;
                $this->apiMessage = Yii::t('app', 'Something went wrong.');
            }
        } else {
            $this->apiCode = 0;
            if (isset($model->errors) && $model->errors) {
                $this->apiMessage = $model->errors;
            }
        }

        return $this->response();
    }

    public function updateMarket($data = array()) {
        $this->apiCode = 0;
        $model = new Markets;
        $model = Markets::findOne($data['id']);
        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        if (isset($data['title'])) {
            $model->title = $data['title'];
        }
        $modelSegment = new MarketSegmentData;
        if ($model->validate()) {
            if ($model->save(false)) {
                $modelSegment::deleteAll(['market_id'=>$data['id']]);
                $marketSegmentData = $data['market_segment_id'];
                foreach ($marketSegmentData as $value) {
                    $modelSegment = new MarketSegmentData;
                    if ($modelSegment->validate()) {
                        $modelSegment->market_id = $data['id'];
                        $modelSegment->market_segment_id = $value;
                        $modelSegment->save(false);
                    }
                }

                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'market_updated_successfully');
            } else {
                $this->apiCode = 0;
                $this->apiMessage = Yii::t('app', 'Something went wrong.');
            }
        } else {
            $this->apiCode = 0;
            if (isset($model->errors) && $model->errors) {
                $this->apiMessage = $model->errors;
            }
        }

        return $this->response();
    }

}
