<?php
namespace common\repository;

use Yii;
use common\models\MarketSegments;
use common\helpers\CommonHelper;
use common\repository\PermissionRepository;
use common\models\StoresReview;

class StoreRatingsRepository extends Repository
{

    public function createStarRating($data = array()){
        $this->apiCode = 0;
        $model = new StoresReview();
        $model->reviews = $data['reviews'];
        $model->store_id = $data['store_id'];
        $model->config_id = $data['config_id'];
        
        if($model->validate()) {
         
            if($model->save(false)) {
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'market_segment_created_successfully');
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



    public function updateStarRating($data = array()){
        $this->apiCode = 0;
        $model = new StoresReview;
        $model = StoresReview::findOne($data['id']);
        if(!$model){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        if(isset($data['reviews'])) {
            $model->reviews = $data['reviews'];
        }
      
        if($model->validate()) {
            if($model->save(false)) {
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'market_segment_updated_successfully');
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