<?php
namespace common\repository;

use Yii;
use common\helpers\CommonHelper;
use common\models\Ratings;


class RatingsRepository extends Repository
{
      public function listing($data = array()) {
        $this->apiCode = 1;
        $query = Ratings::find();

        if(isset($data['search']) && $data['search']){
        	$data['search'] = trim($data['search']);
        	$query->andWhere(['like','name',$data['search']]);
        }

        $data = array();
        $data['ratings'] = $query->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }

    public function createRatings($data = array()){
        $this->apiCode = 0;
        $model = new Ratings;
        if(isset($data['rating'])) {
        	$model->rating = $data['rating'];
        }
        if(isset($data['type'])) {
        	$model->type = $data['type'];
        }
        
        if($model->validate()) {
            if($model->save(false)) {
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'created_successfully', [Yii::t('app', 'ratings')]);
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

    public function upadateRatings($data = array()){
        $this->apiCode = 0;
        $model = Ratings::findOne($data['id']);
        if(isset($data['rating'])) {
        	$model->rating = $data['rating'];
        }
        if(isset($data['type'])) {
        	$model->type = $data['type'];
        }
        
        if($model->validate()) {
            if($model->save(false)) {
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'updated_successfully', [Yii::t('app', 'ratings')]);
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