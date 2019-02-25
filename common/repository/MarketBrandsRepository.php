<?php

namespace common\repository;

use Yii;
use common\helpers\CommonHelper;
use common\models\MarketBrands;
use common\models\MarketBrandsVerietals;

class MarketBrandsRepository extends Repository {

    public function listing($data = array()) {

        $this->apiCode = 1;
        $query = MarketBrands::find()->joinWith('brand.product.productCategory')->joinWith('brand.product.productType');
        if (isset($data['market_id']) && ($data['market_id'] != '')) {
            $query->andWhere(['market_brands.market_id' => $data['market_id']]);
        }
        $data = array();
        $data['market_brands'] = $query->orderBy('reorder_id')->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }

    public function createRule($data = array()) {
        
        $this->apiCode = 0;
        $flag=1;
        $marketSegmentData = $data['brand_id'];
        $model = new MarketBrands;
        $model->deleteAll(['market_id' => $data['market_id']],true);
        
        foreach ($marketSegmentData as $value) {
            $modelSegment = new MarketBrands();
            $modelSegment->market_id = $data['market_id'];
            $modelSegment->brand_id = $value;
            if ($modelSegment->validate(false)) {
                if($modelSegment->save(false)){
//                $model = new MarketBrands();
                $model =  MarketBrands::findOne([$modelSegment->id]);
                $model->reorder_id = $modelSegment->id;
                $model->save(false);
                }
            }else{
                $flag = 0;
            }
        }
        if($flag == 1){
              $this->apiCode = 1;
              $this->apiMessage = Yii::t('app','apply_brand');
        }else{
            $this->apiCode = 0;
            if (isset($model->errors) && $model->errors) {
                $this->apiMessage = $model->errors;
            } 
        }
  
        return $this->response();
    }
    
      public function createBrand($data = array()) {
        
        $this->apiCode = 0;
        $flag=1;
        $marketSegmentData = $data['brand_id'];
        $marketShareSegmentData = $data['shares'];
        
        
        $previousData = MarketBrands::find()->select('brand_id')->andWhere(['market_id' => $data['market_id']])->asArray()->all();
        $previousDataId = MarketBrands::find()->select('id')->andWhere(['market_id' => $data['market_id']])->asArray()->all();
        
        $previous_array;
        
        foreach ($previousData as $key=>$value){
            $previous_array[$key] = $value['brand_id'];
        } 
//        echo '<pre>';
//        print_r($previous_array);
//        print_r($marketSegmentData);
////        exit;
         
        if(!empty($previous_array)){
            $deleted_array = array_diff($previous_array, $marketSegmentData);
//            echo '<pre>';
//            print_r($deleted_array);exit;
            if(!empty($deleted_array)){
            foreach ($deleted_array as $key=>$d_id){
            $model = new MarketBrands;
            $model->deleteAll(['market_id' =>  $data['market_id'],'brand_id'=>$d_id],true);
            }
            }
            
            $insert_array = array_diff($marketSegmentData,$previous_array);
            if(!empty($insert_array)){
            foreach ($insert_array as $key=>$i_id){
                $model = new MarketBrands;
                $model->brand_id = $i_id;
                $model->market_id = $data['market_id'];
                $model->shares = $marketShareSegmentData[$key];
                $model->reorder_id = 0;
                if($model->save(false)){
                    $modelNew =  MarketBrands::findOne([$model->id]);
                    $modelNew->reorder_id = $model->id;
                    $modelNew->save(false);
                }
            }
            }
            $update_array = array_diff($previous_array, $deleted_array);
            if(!empty($update_array)){
                foreach ($update_array as $key=>$i_id){
                    $model = MarketBrands::findOne($previousDataId[$key]);
                    $model->brand_id = $i_id;
                    $model->market_id = $data['market_id'];
                    $model->shares = $marketShareSegmentData[$key];
                    $model->save(false);
                }
            }
            
//           $update = array_intersect($previous_array, $marketSegmentData);
//           foreach ($update as $key=>$u_id){
//                $modelUpadte =  MarketBrands::findOne(['market_id' => $data['market_id'],'brand_id'=>$u_id]);
//                $modelUpadte->
//           }
           
        }else{
        foreach ($marketSegmentData as $marketSKey=>$value) {
            $modelSegment = new MarketBrands();
            $modelSegment->market_id = $data['market_id'];
            $modelSegment->brand_id = $value;
            $modelSegment->shares = $marketShareSegmentData[$marketSKey];
            $modelSegment->reorder_id = 0;
            if ($modelSegment->validate(false)) {
                if($modelSegment->save(false)){
//                $model = new MarketBrands();
                $model =  MarketBrands::findOne([$modelSegment->id]);
                $model->reorder_id = $modelSegment->id;
                $model->save(false);
                }
            }else{
                $flag = 0;
            }
            
            //Manage Market Verietal
            foreach ($data['brand_verietal'] as $brandVerietalKey=>$brandVerietalVal){
                
            }
        }
        }
        if($flag == 1){
              $this->apiCode = 1;
              $this->apiMessage = Yii::t('app','apply_brand');
        }else{
            $this->apiCode = 0;
            if (isset($model->errors) && $model->errors) {
                $this->apiMessage = $model->errors;
            } 
        }
  
        return $this->response();
    }
}
