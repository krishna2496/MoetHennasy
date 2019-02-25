<?php

namespace common\repository;

use Yii;
use common\helpers\CommonHelper;
use common\models\Catalogues;

class CataloguesRepository extends Repository {

    public function listing($data = array()) {
        $this->apiCode = 1;
        $query = Catalogues::find()->joinWith(['market.marketSegmentData.marketSegment.marketRules.rules', 'brand','productType','productCategory']);

        if (isset($data['serachText']) && ($data['serachText'] != '')) {
            $data['search'] = $data['serachText'];
        }

        if (isset($data['search']) && $data['search']) {
            $search = trim($data['search']);
            $query->andWhere([
                'or',
                    ['like', 'catalogues.ean', $search],
                    ['like', 'catalogues.sku', $search],
                    ['like', 'catalogues.short_name', $search],
                    ['like', 'catalogues.long_name', $search],
                    ['like', 'markets.title', $search],
                    ['like', 'brands.name', $search],
                    ['like', 'price', $search],
            ]);
        }

        if (isset($data['market_id']) && $data['market_id']) {

            $query->andWhere(['market_id' => $data['market_id']]);
        }
        if (isset($data['brand_id']) && ($data['brand_id'] != '')) {
            $query->andWhere(['brand_id' => $data['brand_id']]);
        }

        if (isset($data['product_id']) && ($data['product_id'] != '')) {
            $query->andWhere(['product_category_id' => $data['product_id']]);
        }
        
        if (isset($data['selection']) && (!empty($data['selection']))) {
           
            $query->andWhere(['catalogues.id' => explode(',',$data['selection'])]);
        }
        
        if (isset($data['products_id']) && ($data['products_id'] != '')) {
            $query->andWhere(['catalogues.id' => $data['products_id']]);
        }
        
      

        $result = $query->asArray();
        $data = array();
        $data['catalogues'] = $query->orderBy('reorder_id')->asArray()->all();
        
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
        //$model->product_sub_category_id = $data['product_sub_category_id'];
//        $model->market_id = $data['market_id'];
        $model->width = $data['width'];
        $model->height = $data['height'];
        $model->length = $data['length'];
        $model->scale = $data['scale'];
        $model->manufacturer = $data['manufacturer'];
        $model->box_only = $data['box_only'];
        //$model->market_share = $data['market_share'];
        $model->price = $data['price'];
        $model->top_shelf = $data['top_shelf'];
        $model->image = $data['image'];
        $model->product_type_id = $data['product_type_id'];
        $model->product_variental = $data['product_variental'];
        $model->special_format = $data['special_format'];
        
        if (isset($data['long_name'])) {
            $model->long_name = $data['long_name'];
        }
        if (isset($data['short_name'])) {
            $model->short_name = $data['short_name'];
        }

        if ($model->validate()) {
            if ($model->save(false)) {
                $cat_reorder_id= Catalogues::findOne([$model->id]);
                $cat_reorder_id->reorder_id = $model->id;
                $cat_reorder_id->save(false);
                    
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
        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        if (isset($data['sku'])) {
            $model->sku = $data['sku'];
        }
        if (isset($data['ean'])) {
            $model->ean = $data['ean'];
        }
        if (isset($data['brand_id'])) {
            $model->brand_id = $data['brand_id'];
        }
        if (isset($data['product_category_id'])) {
            $model->product_category_id = $data['product_category_id'];
        }
        /*if (isset($data['product_sub_category_id'])) {
            $model->product_sub_category_id = $data['product_sub_category_id'];
        }*/
        if (isset($data['market_id'])) {
//            $model->market_id = $data['market_id'];
        }
        if (isset($data['width'])) {
            $model->width = $data['width'];
        }
        if (isset($data['height'])) {
            $model->height = $data['height'];
        }
        if (isset($data['length'])) {
            $model->length = $data['length'];
        }
        if (isset($data['scale'])) {
            $model->scale = $data['scale'];
        }
        if (isset($data['manufacturer'])) {
            $model->manufacturer = $data['manufacturer'];
        }
        if (isset($data['box_only'])) {
            $model->box_only = $data['box_only'];
        }
        /*if (isset($data['market_share'])) {
            $model->market_share = $data['market_share'];
        }*/
        if (isset($data['price'])) {
            $model->price = $data['price'];
        }
        if (isset($data['top_shelf'])) {
            $model->top_shelf = $data['top_shelf'];
        }
        if (isset($data['image']) && ($data['image'] != '')) {
            $model->image = $data['image'];
        }
        if (isset($data['long_name'])) {
            $model->long_name = $data['long_name'];
        }
        if (isset($data['short_name'])) {
            $model->short_name = $data['short_name'];
        }
        if (isset($data['special_format'])) {
            $model->special_format = $data['special_format'];
        }
        if (isset($data['product_variental'])) {
            $model->product_variental = $data['product_variental'];
        }
        if (isset($data['product_type_id'])) {
             $model->product_type_id = $data['product_type_id'];
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
