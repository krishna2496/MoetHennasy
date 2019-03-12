<?php

namespace common\repository;

use Yii;
use common\helpers\CommonHelper;
use common\models\MarketBrands;
use common\models\MarketBrandsVerietals;
use common\models\MarketCategoryProduct;
use common\models\Brands;
use common\models\CataloguesSearch;
use common\models\Catalogues;

class MarketBrandsRepository extends Repository {

    public function listing($data = array()) {
        $this->apiCode = 1;
        $query = MarketBrands::find()->joinWith('category', 'brand.product.productCategory')->joinWith('brand.product.productType')
                ->joinWith(
                ['brand.marketBrandsVerietals' => function (\yii\db\ActiveQuery $query) use($data) {
                        return $query
                                ->andWhere(['=', 'market_brands_verietals.market_id', $data['market_id']])->orderBy('reorder_id');
                    }]
        );
        if (isset($data['market_id']) && ($data['market_id'] != '')) {
            $query->andWhere(['market_brands.market_id' => $data['market_id']]);
        }

        $queryProduct = MarketBrands::find()->joinWith(['category.marketCategoryProduct' => function (\yii\db\ActiveQuery $queryProduct) use($data) {
                return $queryProduct
                                ->andWhere(['=', 'market_category_product.is_inserted', 1])->joinWith('product')->orderBy('top_reorder_id');
            }]);

        $queryVarietal = \common\models\Catalogues::find();

        $brandVariental = MarketBrandsVerietals::find()->andWhere(['=', 'market_brands_verietals.market_id', $data['market_id']]);

        $data = array();
        $data['market_brands'] = $query->orderBy('reorder_id')->asArray()->all();
        $data['market_product'] = $queryProduct->asArray()->all();
        $data['market_varietal'] = $queryVarietal->asArray()->all();
        $data['brand_varietal'] = $brandVariental->asArray()->all();

        $this->apiData = $data;
        return $this->response();
    }

    public function listingBrand($data = array()) {
        $this->apiCode = 1;
        $query = Brands::find();


        $data = array();
        $data['brand_data'] = $query->asArray()->all();

        $this->apiData = $data;
        return $this->response();
    }
    
    public function productVariental($data = array()) {
        $this->apiCode = 1;
        $query = \common\models\Catalogues::find()->andWhere(['product_category_id' =>  $data['category_id'],'top_shelf' => 0])->andWhere(['not', ['product_variental' => null]]);
        $data = array();
        $data['catalogue'] = $query->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }

    public function listingMarketBrand($data = array()) {
        $this->apiCode = 1;
        $query = MarketBrands::find()->andWhere(['market_id' => $data['market_id'], 'category_id' => $data['category_id']]);


        $data = array();
        $data['market_brands'] = $query->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }

    public function createRule($data = array()) {

        $this->apiCode = 0;
        $flag = 1;
        $marketSegmentData = $data['brand_id'];
        $model = new MarketBrands;
        $model->deleteAll(['market_id' => $data['market_id']], true);

        foreach ($marketSegmentData as $value) {
            $modelSegment = new MarketBrands();
            $modelSegment->market_id = $data['market_id'];
            $modelSegment->brand_id = $value;
            if ($modelSegment->validate(false)) {
                if ($modelSegment->save(false)) {
//                $model = new MarketBrands();
                    $model = MarketBrands::findOne([$modelSegment->id]);
                    $model->reorder_id = $modelSegment->id;
                    $model->save(false);
                }
            } else {
                $flag = 0;
            }
        }
        if ($flag == 1) {
            $this->apiCode = 1;
            $this->apiMessage = Yii::t('app', 'apply_brand');
        } else {
            $this->apiCode = 0;
            if (isset($model->errors) && $model->errors) {
                $this->apiMessage = $model->errors;
            }
        }

        return $this->response();
    }

    public function createBrand($data = array()) {
//        echo '<pre>';
//        print_r($data);exit;
        $this->apiCode = 0;
        $flag = 1;
        $marketSegmentData = $data['brand_id'];
        $marketShareSegmentData = $data['shares'];
        MarketBrands::deleteAll(['market_id'=>$data['market_id'],'category_id'=>$data['category_id']],true);
        foreach ($marketSegmentData as $marketSKey => $value) {
           
            
                $modelSegment = new MarketBrands();
                $modelSegment->market_id = $data['market_id'];
                $modelSegment->category_id = $data['category_id'];
                $modelSegment->brand_id = $value;
                $modelSegment->shares = $marketShareSegmentData[$marketSKey];
                $modelSegment->reorder_id = 0;
                $modelSegment->save(false);
            
        }

        foreach ($data['brand_verietal'] as $brandVerietalKey => $brandVerietalVal) {
            $brandVerietalVal = (array) $brandVerietalVal;
            foreach ($brandVerietalVal as $k => $v) {
                $market_brands_verietals = MarketBrandsVerietals::findOne(['market_id' => $data['market_id'], 'brand_id' => $marketSegmentData[$brandVerietalKey], 'category_id' => $data['category_id'], 'verietal_id' => $v->id]);
                if ($market_brands_verietals) {
                    $market_brands_verietals->shares = $v->share;
                    $market_brands_verietals->save(false);
                } else {
                    $modelVerietals = new MarketBrandsVerietals();
                    $modelVerietals->market_id = $data['market_id'];
                    $modelVerietals->brand_id = $marketSegmentData[$brandVerietalKey];
                    $modelVerietals->verietal_id = $v->id;
                    $modelVerietals->category_id = $data['category_id'];
                    $modelVerietals->shares = $v->share;
                    if ($modelVerietals->save(false)) {
                        
                    }
                }
            }
        }

        if ($flag == 1) {
            $this->apiCode = 1;
            $this->apiMessage = Yii::t('app', 'apply_strategy');
        } else {
            $this->apiCode = 0;
            if (isset($model->errors) && $model->errors) {
                $this->apiMessage = $model->errors;
            }
        }

        return $this->response();
    }

//    public function createBrand($data = array()) {
//        $this->apiCode = 0;
//        $flag=1;
//        $marketSegmentData = $data['brand_id'];
//        $marketShareSegmentData = $data['shares'];
//        
//        
//        $previousData = MarketBrands::find()->select('brand_id')->andWhere(['market_id' => $data['market_id']])->asArray()->all();
//        $previousDataId = MarketBrands::find()->select('id')->andWhere(['market_id' => $data['market_id']])->asArray()->all();
//        
//        $previous_array;
//        
//        foreach ($previousData as $key=>$value){
//            $previous_array[$key] = $value['brand_id'];
//        } 
////        echo '<pre>';
////        print_r($previous_array);
////        print_r($marketSegmentData);
//////        exit;
//         
//        if(!empty($previous_array)){
//            $deleted_array = array_diff($previous_array, $marketSegmentData);
////            echo '<pre>';
////            print_r($deleted_array);exit;
//            if(!empty($deleted_array)){
//            foreach ($deleted_array as $key=>$d_id){
//            $model = new MarketBrands;
//            $model->deleteAll(['market_id' =>  $data['market_id'],'brand_id'=>$d_id],true);
//            }
//            }
//            
//            $insert_array = array_diff($marketSegmentData,$previous_array);
//            if(!empty($insert_array)){
//            foreach ($insert_array as $key=>$i_id){
//                $model = new MarketBrands;
//                $model->brand_id = $i_id;
//                $model->market_id = $data['market_id'];
//                $model->shares = $marketShareSegmentData[$key];
//                $model->reorder_id = 0;
//                if($model->save(false)){
//                    $modelNew =  MarketBrands::findOne([$model->id]);
//                    $modelNew->reorder_id = $model->id;
//                    $modelNew->save(false);
//                }
//            }
//            }
//            $update_array = array_diff($previous_array, $deleted_array);
//            if(!empty($update_array)){
//                foreach ($update_array as $key=>$i_id){
//                    $model = MarketBrands::findOne($previousDataId[$key]);
//                    $model->brand_id = $i_id;
//                    $model->category_id = $data['category_id'];
//                    $model->market_id = $data['market_id'];
//                    $model->shares = $marketShareSegmentData[$key];
//                    $model->save(false);
//                }
//            }
//            
////           $update = array_intersect($previous_array, $marketSegmentData);
////           foreach ($update as $key=>$u_id){
////                $modelUpadte =  MarketBrands::findOne(['market_id' => $data['market_id'],'brand_id'=>$u_id]);
////                $modelUpadte->
////           }
//           
//        }else{
//        foreach ($marketSegmentData as $marketSKey=>$value) {
//            $modelSegment = new MarketBrands();
//            $modelSegment->market_id = $data['market_id'];
//            $modelSegment->brand_id = $value;
//            $modelSegment->shares = $marketShareSegmentData[$marketSKey];
//            $modelSegment->reorder_id = 0;
//            if ($modelSegment->validate(false)) {
//                if($modelSegment->save(false)){
////                $model = new MarketBrands();
//                $model =  MarketBrands::findOne([$modelSegment->id]);
//                $model->reorder_id = $modelSegment->id;
//                $model->save(false);
//                }
//            }else{
//                $flag = 0;
//            }
//            
//            //Manage Market Verietal
//            $brand_data = $data['brand_id'];
//            foreach ($data['brand_verietal'] as $brandVerietalKey=>$brandVerietalVal){
//                echo '<pre>';
//                print_r($brandVerietalVal);exit;
//                    $modelVerietals = new MarketBrandsVerietals();
//                    $modelSegment->market_id = $data['market_id'];
//                    $modelSegment->brand_id = $brand_data[$brandVerietalKey];
//                    $modelSegment->verietal_id = '';
//                    $modelSegment->category_id = '';
//                    $modelSegment->shares = '';
//                    $modelSegment->save(false);
//            }
//        }
//        }
//        if($flag == 1){
//              $this->apiCode = 1;
//              $this->apiMessage = Yii::t('app','apply_brand');
//        }else{
//            $this->apiCode = 0;
//            if (isset($model->errors) && $model->errors) {
//                $this->apiMessage = $model->errors;
//            } 
//        }
//  
//        return $this->response();
//    }

    public function createMarketProduct($data = array()) {

        $this->apiCode = 0;
        $flag = 1;
        $selected_product = $data['selected_product'];

        //top self product
        $catalogModel = new CataloguesSearch();
        $catalogFilter = array(
            'top_shelf' => 1
        );

        $catalogDataProvider = $catalogModel->search($catalogFilter); //top shelf =1
        //MarketCategoryProduct::deleteAll(['category_id' => $data['category_id'], 'market_id' => $data['market_id']]);
        if ($selected_product) {
            foreach ($catalogDataProvider->allModels As $key => $catalogDataVal) {
                if (!empty($catalogDataVal['id'])) {
                    $market_brand_repository = MarketCategoryProduct::findOne(['category_id' => $data['category_id'], 'market_id' => $data['market_id'], 'product_id' => $catalogDataVal['id'], 'deleted_by' => null]);

                    if ($market_brand_repository == NULL) {
                        $new_market_brand_repository = new MarketCategoryProduct();
                        if (in_array($catalogDataVal['id'], $selected_product)) {
                            $new_market_brand_repository->is_inserted = 1;
                        } else {
                            $new_market_brand_repository->is_inserted = 0;
                        }
                        $new_market_brand_repository->product_id = $catalogDataVal['id'];
                        $new_market_brand_repository->category_id = $data['category_id'];
                        $new_market_brand_repository->market_id = $data['market_id'];
                        $new_market_brand_repository->save(false);
                    } else {
                        if (in_array($catalogDataVal['id'], $selected_product)) {
                            $market_brand_repository->is_inserted = 1;
                        } else {
                            $market_brand_repository->is_inserted = 0;
                        }
                        $market_brand_repository->product_id = $catalogDataVal['id'];
                        $market_brand_repository->category_id = $data['category_id'];
                        $market_brand_repository->market_id = $data['market_id'];
                        $market_brand_repository->update(false);
                    }
                }
            }

            if ($flag == 1) {
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'apply_strategy');
            } else {
                $this->apiCode = 0;
                if (isset($model->errors) && $model->errors) {
                    $this->apiMessage = $model->errors;
                }
            }

            return $this->response();
        }
    }

}
