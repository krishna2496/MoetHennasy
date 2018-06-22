<?php

namespace common\repository;

use Yii;
use common\helpers\CommonHelper;
use common\models\StoreConfiguration;
use common\models\ShelfDisplayBrand;
use common\models\ShelfDisplay;
use common\models\ConfigFeedback;
use yii\web\NotFoundHttpException;

class StoreConfigRepository extends Repository {

    public function listing($data = array()) {

        $this->apiCode = 1;
        $query = StoreConfiguration::find()->joinWith(['shelfDisplay', 'configFeedBack','stores']);

        if (isset($data['config_id']) && $data['config_id']) {
            $query->andWhere(['store_configuration.id' => $data['config_id']]);
        }
        
        if (isset($data['store_id']) && $data['store_id']) {
            $query->andWhere(['stores.id' => $data['store_id']]);
        }
        
        if (isset($data['city_id']) && $data['city_id']) {
            $query->andWhere(['stores.city_id' => $data['city_id']]);
        }
        
        if (isset($data['province_id']) && $data['province_id']) {
            $query->andWhere(['stores.province_id' => $data['province_id']]);
        }
        
        if (isset($data['market_id']) && $data['market_id']) {
            $query->andWhere(['stores.market_id' => $data['market_id']]);
        }

        $data = array();
        $data['stores_config'] = $query->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }

    public function createConfig($data = array()) {
        $returnData = array();
        $this->apiCode = 0;
        $storeModel = new StoreConfiguration();
        $questionModel = new ConfigFeedback();
        $questionArray = $shelfDisplayArray = array();
        if (isset($data['store_id']) && $data['store_id']) {
            $storeModel->store_id = $data['store_id'];
        }
        if (isset($data['config_name']) && $data['config_name']) {
            $storeModel->config_name = $data['config_name'];
        }
        //catalogue image name
        if (isset($data['shelf_thumb']) && $data['shelf_thumb']) {
            $storeModel->shelf_thumb = $data['shelf_thumb'];
        }
        if (isset($data['configFeedBack']) && (!empty($data['configFeedBack']))) {
            $questionArray = $data['configFeedBack'];
        }
        if (isset($data['shelfDisplay']) && (!empty($data['shelfDisplay']))) {
            $shelfDisplayArray = $data['shelfDisplay'];
        }
        if (isset($data['star_ratings']) && (!empty($data['star_ratings']))) {
            $storeModel->star_ratings = $data['star_ratings'];
        }

        if (isset($data['is_verified']) && (!empty($data['is_verified']))) {
            $storeModel->is_verified = $data['is_verified'];
        }

        if (isset($data['is_autofill']) && (!empty($data['is_autofill']))) {
            $storeModel->is_autofill = $data['is_autofill'];
        }
        if ($storeModel->validate()) {

            if ($storeModel->save(false)) {
                $configId = $storeModel->id;
                //question and answer
                if(!empty($questionArray)){                   
                foreach ($questionArray as $key => $value) {

                    $questionModel = new ConfigFeedback();
                    $questionModel->config_id = $configId;
                    $questionModel->que_id = $value['que_id'];
                    $questionModel->answer = $value['answer'];
                    if (isset($value['reviewed_by']) && ($value['reviewed_by'])) {
                        $questionModel->reviewed_by = $value['reviewed_by'];
                    }

                    if (isset($value['reviewed_at']) && ($value['reviewed_at'])) {
                        $questionModel->reviewed_at = $value['reviewed_at'];
                    }
                    $questionModel->save(false);
                    }
                }
                //insert display   
                if(!empty($shelfDisplayArray)){
                foreach ($shelfDisplayArray as $key => $value) {

                    $displayModel = new ShelfDisplay();

                    $displayModel->config_id = $configId;
                    $displayModel->display_name = '';

                    if (isset($value['display_name']) && ($value['display_name'])) {
                        $displayModel->display_name = $value['display_name'];
                    }

                    $displayModel->no_of_shelves = $value['no_of_shelves'];
                    $displayModel->height_of_shelves = $value['height_of_shelves'];
                    $displayModel->width_of_shelves = $value['width_of_shelves'];
                    $displayModel->depth_of_shelves = $value['depth_of_shelves'];
                    $displayModel->shelf_config = json_encode($value['shelf_config']);
                    if (isset($value['brand_thumb_id']) && ($value['brand_thumb_id'] != '')) {
                        $displayModel->brand_thumb_id = $value['brand_thumb_id'];
                    }

                    $displayModel->save(false);
                }
                }
                $query = StoreConfiguration::find()->joinWith(['shelfDisplay', 'configFeedBack']);

                if (isset($configId) && ($configId != '')) {
                    $query->andWhere(['store_configuration.id' => $configId]);
                }
                $dataValue = $query->asArray()->all();
             
                $temp = array();
                $shelfDisplay = $dataValue[0]['shelfDisplay'];
                
                $shelf_thumb =  $dataValue[0]['shelf_thumb'];
                unset($dataValue[0]['shelf_thumb']);
                $dataValue[0]['shelf_thumb'] = CommonHelper::getImage(UPLOAD_PATH_STORE_CONFIG_IMAGES . $shelf_thumb);
                foreach ($shelfDisplay as $key => $value) {

                    $productIds = json_decode($value['shelf_config'], true);
                    foreach ($productIds as $key2 => $value2) {
                        $productId = explode(',', $value2['productIds']);
                        foreach ($productId as $productKey => $productValue) {
                            $catalogueRepository = new CataloguesRepository();
                            $productIdData['products_id'] = $productValue;
                            $productArray = array();
                            $product = $catalogueRepository->listing($productIdData);

                            if ($product['status']['success'] == 1) {
                                $productArray = $product['data']['catalogues'][0];
                                unset($productArray['market']);
                                unset($productArray['brand']);
                                unset($productArray['productType']);
                                unset($productArray['productCategory']);
                            }
                            $image = $productArray['image'];
                            unset($productArray['image']);
                            unset($dataValue[0]['shelfDisplay']);
                            $productArray['image'] = CommonHelper::getImage(UPLOAD_PATH_CATALOGUES_IMAGES . $image);
                            $temp['shelf_config'][$key2]['productIds'][$productKey] = $productArray;
                        }
                    }
                }
                
                $dataValue[0]['shelfDisplay']['display_name'] = $shelfDisplay[0]['display_name'];
                $dataValue[0]['shelfDisplay']['no_of_shelves'] = $shelfDisplay[0]['no_of_shelves'];
                $dataValue[0]['shelfDisplay']['height_of_shelves'] = $shelfDisplay[0]['height_of_shelves'];
                $dataValue[0]['shelfDisplay']['width_of_shelves'] = $shelfDisplay[0]['width_of_shelves'];
                $dataValue[0]['shelfDisplay']['depth_of_shelves'] = $shelfDisplay[0]['depth_of_shelves'];
                $dataValue[0]['shelfDisplay']['brand_thumb_id'] = $shelfDisplay[0]['brand_thumb_id'];
                $dataValue[0]['shelfDisplay']["shelf_config"] = $temp['shelf_config'];

                $this->apiData = $dataValue;
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'created_successfully', [Yii::t('app', 'Configuration')]);
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

    public function updateConfig($data = array()) {
        $returnData = array();
        $this->apiCode = 0;
        
        $storeModel = StoreConfiguration::findOne($data['config_id']);

        if (!$storeModel) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
       
        $questionModel = new ConfigFeedback();
        $questionArray = $shelfDisplayArray = array();
        if (isset($data['store_id']) && $data['store_id']) {
            $storeModel->store_id = $data['store_id'];
        }
        if (isset($data['config_name']) && $data['config_name']) {
            $storeModel->config_name = $data['config_name'];
        }
        //catalogue image name
        if (isset($data['shelf_thumb']) && $data['shelf_thumb']) {
            $storeModel->shelf_thumb = $data['shelf_thumb'];
        }
        if (isset($data['configFeedBack']) && (!empty($data['configFeedBack']))) {
            $questionArray = $data['configFeedBack'];
        }

        if (isset($data['shelfDisplay']) && (!empty($data['shelfDisplay']))) {
            $shelfDisplayArray = $data['shelfDisplay'];
        }

        if (isset($data['star_ratings']) && (!empty($data['star_ratings']))) {
            $storeModel->star_ratings = $data['star_ratings'];
        }

        if (isset($data['is_verified']) && (!empty($data['is_verified']))) {
            $storeModel->is_verified = $data['is_verified'];
        }

        if (isset($data['is_autofill']) && (!empty($data['is_autofill']))) {
            $storeModel->is_autofill = $data['is_autofill'];
        }
     



        if ($storeModel->validate(false)) {

            if ($storeModel->save(false)) {
                
                if(!empty($questionArray)){
                ConfigFeedback::deleteAll(['config_id' => $data['config_id']]);
                //question and answer
                foreach ($questionArray as $key => $value) {

                    $questionModel = new ConfigFeedback();
                    $questionModel->config_id = $data['config_id'];
                  
                    if (isset($value['que_id']) && ($value['que_id'])) {
                        $questionModel->que_id = $value['que_id'];
                    }
                    if (isset($value['answer']) && ($value['answer'])) {
                        $questionModel->answer = $value['answer'];
                    }

                    if (isset($value['reviewed_by']) && ($value['reviewed_by'])) {
                        $questionModel->reviewed_by = $value['reviewed_by'];
                    }

                    if (isset($value['reviewed_at']) && ($value['reviewed_at'])) {
                        $questionModel->reviewed_at = $value['reviewed_at'];
                    }
                    $questionModel->save(false);
                }
                }
                //insert display 
                if(!empty($shelfDisplayArray)){
                ShelfDisplay::deleteAll(['config_id' => $data['config_id']]);
                foreach ($shelfDisplayArray as $key => $value) {
                   
                    $displayModel = new ShelfDisplay();
                    $displayModel->config_id = $data['config_id'];
                    if (isset($value['display_name']) && ($value['display_name'])) {
                        $displayModel->display_name = $value['display_name'];
                    }
                   
                    if (isset($value['display_name']) && ($value['display_name'])) {
                        $displayModel->display_name = $value['display_name'];
                    }
                    if (isset($value['no_of_shelves']) && ($value['no_of_shelves'])) {
                        $displayModel->no_of_shelves = $value['no_of_shelves'];
                    }
                    if (isset($value['height_of_shelves']) && ($value['height_of_shelves'])) {
                        $displayModel->height_of_shelves = $value['height_of_shelves'];
                    }
                    if (isset($value['width_of_shelves']) && ($value['width_of_shelves'])) {
                        $displayModel->width_of_shelves = $value['width_of_shelves'];
                    }
                    if (isset($value['depth_of_shelves']) && ($value['depth_of_shelves'])) {
                        $displayModel->depth_of_shelves = $value['depth_of_shelves'];
                    }
                    if (isset($value['shelf_config']) && ($value['shelf_config'])) {
                        $displayModel->shelf_config = json_encode($value['shelf_config']);
                    }
                    if (isset($value['brand_thumb_id']) && ($value['brand_thumb_id'] != '')) {
                        $displayModel->brand_thumb_id = $value['brand_thumb_id'];
                    }

                    $displayModel->save(false);
                }
                }

                $query = StoreConfiguration::find()->joinWith(['shelfDisplay', 'configFeedBack']);

                if (isset($data['config_id']) && $data['config_id']) {
                    $query->andWhere(['store_configuration.id' => $data['config_id']]);
                }
                $dataValue = $query->asArray()->all();

                $temp = array();
                $shelfDisplay = $dataValue[0]['shelfDisplay'];
                $shelf_thumb =  $dataValue[0]['shelf_thumb'];
                unset($dataValue[0]['shelf_thumb']);
                $dataValue[0]['shelf_thumb'] = CommonHelper::getImage(UPLOAD_PATH_STORE_CONFIG_IMAGES . $shelf_thumb);
                foreach ($shelfDisplay as $key => $value) {

                    $productIds = json_decode($value['shelf_config'], true);

                    foreach ($productIds as $key2 => $value2) {
                        $productId = explode(',', $value2['productIds']);
                        foreach ($productId as $productKey => $productValue) {
                            $catalogueRepository = new CataloguesRepository();
                            $productIdData['products_id'] = $productValue;
                            $productArray = array();
                            $product = $catalogueRepository->listing($productIdData);

                            if ($product['status']['success'] == 1) {
                                $productArray = $product['data']['catalogues'][0];
                                unset($productArray['market']);
                                unset($productArray['brand']);
                                unset($productArray['productType']);
                                unset($productArray['productCategory']);
                            }
                            $image = $productArray['image'];
                            unset($productArray['image']);
                            unset($dataValue[0]['shelfDisplay']);
                            $productArray['image'] = CommonHelper::getImage(UPLOAD_PATH_CATALOGUES_IMAGES . $image);
                            $temp['shelf_config'][$key2]['productIds'][$productKey] = $productArray;
                        }
                    }
                }
                $dataValue[0]['shelfDisplay']['display_name'] = $shelfDisplay[0]['display_name'];
                $dataValue[0]['shelfDisplay']['no_of_shelves'] = $shelfDisplay[0]['no_of_shelves'];
                $dataValue[0]['shelfDisplay']['height_of_shelves'] = $shelfDisplay[0]['height_of_shelves'];
                $dataValue[0]['shelfDisplay']['width_of_shelves'] = $shelfDisplay[0]['width_of_shelves'];
                $dataValue[0]['shelfDisplay']['depth_of_shelves'] = $shelfDisplay[0]['depth_of_shelves'];
                $dataValue[0]['shelfDisplay']['brand_thumb_id'] = $shelfDisplay[0]['brand_thumb_id'];
                $dataValue[0]['shelfDisplay']["shelf_config"] = $temp['shelf_config'];

                $this->apiData = $dataValue;
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'updated_successfully', [Yii::t('app', 'Configuration')]);
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
    
    public function createRating($data = array()){
       $returnData = array();
       $this->apiCode = 0;
       
       $storeModel = StoreConfiguration::findOne($data['config_id']);

        if (!$storeModel) {
            throw new NotFoundHttpException('The requested page does not exist.');
        } 
      
       if(isset($data['star_ratings']) && ($data['star_ratings'])){
           $storeModel->star_ratings = $data['star_ratings'];
       }
       if ($storeModel->validate(false)) {

            if ($storeModel->save(false)) {
                $this->apiData = $returnData;
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'created_successfully', [Yii::t('app', 'Rating')]);
            }else {
                $this->apiCode = 0;
                $this->apiMessage = Yii::t('app', 'Something went wrong.');
            }
       
       }else{
            $this->apiCode = 0;
            if (isset($model->errors) && $model->errors) {
                $this->apiMessage = $model->errors;
            }
       }
       return $this->response();

}
}
