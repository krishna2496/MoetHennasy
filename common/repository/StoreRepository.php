<?php

namespace common\repository;

use Yii;
use common\helpers\CommonHelper;
use common\models\Stores;

class StoreRepository extends Repository {

    public function storeList($data = array()) {
        $this->apiCode = 1;
        $query = Stores::find()->with(['market','marketSegment','user']);
        $data = array();
        $data['stores'] = $query->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }

    public function createStore($data = array()) {

        $this->apiCode = 0;
        $model = new Stores;

        $model->name = $data['name'];
        $model->storeImage = $data['storeImage'];
        $model->photo = $data['photo'];
        $model->market_id = $data['market_id'];
        $model->market_segment_id = $data['market_segment_id'];
        $model->address1 = $data['address1'];
        $model->address2 = $data['address2'];
        $model->country_id = $data['country_id'];
        $model->city_id = $data['city_id'];
        $model->province_id = $data['province_id'];
        $model->assign_to = $data['assign_to'];
        $model->store_manager_first_name = $data['store_manager_first_name'];
        $model->store_manager_last_name = $data['store_manager_last_name'];
        $model->store_manager_phone_code = $data['store_manager_phone_code'];
        $model->store_manager_phone_number = $data['store_manager_phone_number'];
        $model->store_manager_email = $data['store_manager_email'];
        if(isset($data['latitude'])){
            $model->latitude = $data['latitude'];
        }
        if(isset($data['longitude'])){
            $model->longitude = $data['longitude'];
        }
        if(isset($data['comment'])){
            $model->comment = $data['comment'];
        }

        if ($model->validate()) {
            if ($model->save(false)) {
                $returnData = array();
                $returnData['store'] = $model;
                $returnData['store']['photo'] = $model->photo ? CommonHelper::getPath('upload_url').UPLOAD_PATH_STORE_IMAGES.$model->photo : '';
                $returnData['market'] = $model->market;
                $returnData['marketSegment'] = $model->marketSegment;
                $returnData['assignTo'] = $model->user;
                $this->apiData = $returnData;
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'created_successfully', [Yii::t('app', 'store')]);
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

    public function updateStore($data = array()) {

        $this->apiCode = 0;
        $model = Stores::findOne($data['id']);
        if(!$model){
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if(isset($data['name'])){
            $model->name = $data['name'];
        }
        if(isset($data['storeImage'])){
            $model->storeImage = $data['storeImage'];
        }
        if(isset($data['photo']) && $data['photo']){
            $model->photo = $data['photo'];
        }
        if(isset($data['market_id'])){
            $model->market_id = $data['market_id'];
        }
        if(isset($data['market_segment_id'])){
            $model->market_segment_id = $data['market_segment_id'];
        }
        if(isset($data['address1'])){
            $model->address1 = $data['address1'];
        }
        if(isset($data['address2'])){
            $model->address2 = $data['address2'];
        }
        if(isset($data['country_id'])){
            $model->country_id = $data['country_id'];
        }
        if(isset($data['city_id'])){
            $model->city_id = $data['city_id'];
        }
        if(isset($data['province_id'])){
            $model->province_id = $data['province_id'];
        }
        if(isset($data['assign_to'])){
            $model->assign_to = $data['assign_to'];
        }
        if(isset($data['store_manager_first_name'])){
            $model->store_manager_first_name = $data['store_manager_first_name'];
        }
        if(isset($data['store_manager_last_name'])){
            $model->store_manager_last_name = $data['store_manager_last_name'];
        }
        if(isset($data['store_manager_phone_code'])){
            $model->store_manager_phone_code = $data['store_manager_phone_code'];
        }
        if(isset($data['store_manager_phone_number'])){
            $model->store_manager_phone_number = $data['store_manager_phone_number'];
        }
        if(isset($data['store_manager_email'])){
            $model->store_manager_email = $data['store_manager_email'];
        }
        if(isset($data['latitude'])){
            $model->latitude = $data['latitude'];
        }
        if(isset($data['longitude'])){
            $model->longitude = $data['longitude'];
        }
        if(isset($data['comment'])){
            $model->comment = $data['comment'];
        }

        if ($model->validate()) {
            if ($model->save(false)) {
                $returnData = array();
                $returnData['store'] = $model;
                $returnData['store']['photo'] = $model->photo ? CommonHelper::getPath('upload_url').UPLOAD_PATH_STORE_IMAGES.$model->photo : '';
                $returnData['market'] = $model->market;
                $returnData['marketSegment'] = $model->marketSegment;
                $returnData['assignTo'] = $model->user;
                $this->apiData = $returnData;
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'updated_successfully', [Yii::t('app', 'store')]);
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
