<?php
namespace common\repository;

use Yii;
use common\helpers\CommonHelper;
use common\models\Catalogues;


class CataloguesRepository extends Repository
{
      public function listing($data = array()) {
        $this->apiCode = 1;
        $query = Catalogues::find()->joinWith(['market']);

        if(isset($data['serachText']) && ($data['serachText'] != '')){        
            $data['search']=$data['serachText'];
        }
        if(isset($data['search']) && $data['search']){
            $search = trim($data['search']);       
            $query->andWhere([
                'or',
                ['like', 'catalogues.ean', $search],
                ['like', 'catalogues.sku', $search],
                ['like', 'catalogues.short_name', $search],
                ['like', 'catalogues.long_name', $search],
                ['like', 'markets.title', $search],
                
            ]);
        }
        
         if(isset($data['market_id']) && $data['market_id']){
                 
            $query->andWhere(['market_id'=> $data['market_id']]);
        }

        $result=$query->asArray();
        $data = array();
        $data['catalogues'] = $query->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }

    public function createCatalogue($data = array()) {

        $this->apiCode = 0;
        $model = new Catalogues;

        $model->sku = $data['sku'];
        $model->ean = $data['ean'];
        $model->brand_id = $data['brand_id'];
        $model->product_category_id = $data['product_category_id'];
        $model->product_sub_category_id = $data['product_sub_category_id'];
        $model->market_id = $data['market_id'];
        $model->width = $data['width'];
        $model->height = $data['height'];
        $model->length = $data['length'];
        $model->scale = $data['scale'];
        $model->manufacturer = $data['manufacturer'];
        $model->box_only = $data['box_only'];
        $model->market_share = $data['market_share'];
        $model->price = $data['price'];
        $model->top_shelf = $data['top_shelf'];
        $model->image = $data['image'];
      
        if(isset($data['long_name'])){
            $model->long_name = $data['long_name'];
        }
        if(isset($data['short_name'])){
            $model->short_name = $data['short_name'];
        }

        if ($model->validate()) {
            if ($model->save(false)) {
                $returnData = array();
                $returnData['catalogue'] = $model;
//                $returnData['catalogue']['photo'] = $model->image ? CommonHelper::getPath('upload_url').UPLOAD_PATH_CATALOGUES_IMAGES.$model->image : '';               
                $this->apiData = $returnData;
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'created_successfully', [Yii::t('app', 'catalogues')]);
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

    public function updateCatalogue($data = array()) {

        $this->apiCode = 0;
        $model = Catalogues::findOne($data['id']);
        if(!$model){
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $model->sku = $data['sku'];
        $model->ean = $data['ean'];
        $model->brand_id = $data['brand_id'];
        $model->product_category_id = $data['product_category_id'];
        $model->product_sub_category_id = $data['product_sub_category_id'];
        $model->market_id = $data['market_id'];
        $model->width = $data['width'];
        $model->height = $data['height'];
        $model->length = $data['length'];
        $model->scale = $data['scale'];
        $model->manufacturer = $data['manufacturer'];
        $model->box_only = $data['box_only'];
        $model->market_share = $data['market_share'];
        $model->price = $data['price'];
        $model->top_shelf = $data['top_shelf'];
        if(isset($data['image']) && $data['image']){
            $model->image = $data['image'];
        }
        if(isset($data['long_name'])){
            $model->long_name = $data['long_name'];
        }
        if(isset($data['short_name'])){
            $model->short_name = $data['short_name'];
        }
        if ($model->validate()) {
            if ($model->save(false)) {
                $returnData = array();
                $returnData['catalogue'] = $model;
//                $returnData['catalogue']['photo'] = $model->image ? CommonHelper::getPath('upload_url').UPLOAD_PATH_CATALOGUES_IMAGES.$model->image : '';      
                $this->apiData = $returnData;
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'updated_successfully', [Yii::t('app', 'catalogues')]);
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