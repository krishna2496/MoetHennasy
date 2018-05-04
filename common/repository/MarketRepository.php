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
        $query = Markets::find()->joinWith(['marketSegment']);
       
        if(isset($data['search']) && $data['search']){
            $search =  $data['search'];       
            $query->andWhere([
                'or',
                    ['like', 'markets.title', $search],
                    ['like', 'market_segments.title', $search],
            ]);
        }
        $data = array();
        $data['markets'] = $query->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }
    
     public function createMarket($data = array()){
       
        $this->apiCode = 0;
        $model = new Markets;
        $model->scenario = 'create';
        $model->title = $data['title'];
        $model->market_segment_id = isset($data['market_segment_id']) ? $data['market_segment_id'] : '';
       
        if($model->validate()) {
         
            if($model->save(false)) {
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'market_created_successfully');
            } else {
                $this->apiCode = 0;
                $this->apiMessage = Yii::t('app', 'Something went wrong.');
            }
        } else {
            $this->apiCode = 0;
            if(isset($model->errors) && $model->errors){
                $this->apiMessage = $model->errors;
            }
        }

        return $this->response();
    }

    public function updateMarket($data = array()){
        $this->apiCode = 0;
        $model = new Markets;
        $model = Markets::findOne($data['id']);
        if(!$model){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        if(isset($data['title'])) {
            $model->title = $data['title'];
        }
        if(isset($data['market_segment_id'])) {
            $model->market_segment_id = $data['market_segment_id'];
        }
       
        if($model->validate()) {
            if($model->save(false)) {
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'market_updated_successfully');
            } else {
                $this->apiCode = 0;
                $this->apiMessage = Yii::t('app', 'Something went wrong.');
            }
        } else {
            $this->apiCode = 0;
            if(isset($model->errors) && $model->errors){
                $this->apiMessage = $model->errors;
            }
        }

        return $this->response();
    }
}