<?php

namespace common\repository;

use Yii;
use common\helpers\CommonHelper;
use common\models\Stores;

class StoreRepository extends Repository {

    public function storeList($data = array()) {
       
        $this->apiCode = 1;
        $query = Stores::find()->joinWith(['market','marketSegment','user','city','country','province']);

        if(isset($data['serachText']) && ($data['serachText'] != '')){        
           $search = trim($data['serachText']);       
            $query->andWhere([
                'or',
                ['like', 'stores.name', $search],
                ['like', 'markets.title', $search],
                ['like', 'market_segments.title', $search],
                ['like', 'address1', $search],
                ['like', 'users.first_name', $search],
                ['like', 'users.last_name', $search],
            ]);
        }
        if(isset($data['search']) && $data['search']){
            $data['search'] = trim($data['search']);
            $nameArray = explode(' ', $data['search']);
            $firstName = $nameArray[0];
            $lastName = isset($nameArray[1]) ? $nameArray[1] : $nameArray[0];
            $query->andWhere([
                'or',
                    ['like', 'stores.store_manager_first_name', $firstName],
                    ['like', 'stores.store_manager_last_name', $lastName],
                    ['like', 'stores.store_manager_email', $data['search']],
                    ['like', 'stores.store_manager_phone_number', $data['search']],
                    ['like', 'stores.name', $data['search']],
            ]);
        }
        if(isset($data['city_id']) && $data['city_id']){
           $data['cityIdArray']=$data['city_id'];
        }
        if(isset($data['cityIdArray'])){        
            $query->andFilterWhere([
                'stores.city_id' => $data['cityIdArray']
            ]);
        }
        if(isset($data['provinceIdArray']) && ($data['provinceIdArray'] != '')){        
            $query->andFilterWhere([
                'stores.province_id' => $data['provinceIdArray']
            ]);
        }
        if(isset($data['marketIdArray']) && ($data['marketIdArray'] != '')){        
           $data['market_id']=$data['marketIdArray'];
        }
        if(isset($data['market_id']) && ($data['market_id'] != '')){     
            $query->andFilterWhere([
                'stores.market_id' => $data['market_id']
            ]);
        }
        if(isset($data['assign_to']) && $data['assign_to']){
            $data['assignTo']=$data['assign_to'];
        }
        if(isset($data['assignTo']) && ($data['assignTo'] != '')){
            $query->andFilterWhere([
                'stores.assign_to' => $data['assignTo']
            ]);
        }
        if(isset($data['market_segment_id']) && $data['market_segment_id']){
            $query->andWhere(['=','stores.market_segment_id',$data['market_segment_id']]);
        }
        if(isset($data['country_id']) && $data['country_id']){
            $query->andWhere(['=','stores.country_id',$data['country_id']]);
        }
       
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
