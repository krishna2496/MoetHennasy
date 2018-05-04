<?php
namespace common\repository;

use Yii;
use common\models\MarketSegments;
use common\helpers\CommonHelper;
use common\repository\PermissionRepository;

class MarketSegmentsRepository extends Repository
{

    public function marketSegmentsList($data = array()){
      
        $this->apiCode = 1;
        $query = MarketSegments::find();
       
        if(isset($data['search']) && $data['search']){
            $search =  $data['search'];       
            $query->andWhere([
                'or',
                    ['like', 'title', $search],
                    ['like', 'description', $search],
            ]);
        }
        $data = array();
        $data['market_segments'] = $query->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }

    public function createMarketSegment($data = array()){
       
        $this->apiCode = 0;
        $model = new MarketSegments;
        $model->scenario = 'create';
        $model->title = $data['title'];
        $model->description = isset($data['description']) ? $data['description'] : '';
       
      
        if($model->validate()) {
         
            if($model->save(false)) {
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'Market segment created successfully');
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

    public function updateMarketSegment($data = array()){
        $this->apiCode = 0;
        $model = new MarketSegments;
        $model = MarketSegments::findOne($data['id']);
        if(!$model){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        if(isset($data['title'])) {
            $model->title = $data['title'];
        }
        if(isset($data['description'])) {
            $model->description = $data['description'];
        }
       
        if($model->validate()) {
            if($model->save(false)) {
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'Market segment updated successfully');
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