<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use common\helpers\CommonHelper;
use common\repository\MarketBrandsRepository;
use common\repository\CataloguesRepository;
use common\repository\QuestionsRepository;
use common\repository\MarketRulesRepository;
use common\repository\MarketRepository;
use yii\data\ArrayDataProvider;

class StoresConfigController extends BaseApiController {

    public $modelClass = 'common\models\Brands';

    public function behaviors() {
        $behaviors = parent::behaviors();

        return $behaviors;
    }

    public function actions() {
        $actions = parent::actions();

        unset($actions['brand-list']);
        return $actions;
    }

    public function actionBrandList() {

        $currentUser = CommonHelper::getUser();
        $marketId = '';
        if (isset($currentUser->market_id) && ($currentUser->market_id != '')) {
            $marketId = $currentUser->market_id;
        }

        $returnData = array();
        $repository = new MarketBrandsRepository();
        if ($marketId != '') {
            $data['market_id'] = $marketId;
            $returnData = $repository->listing($data);
            $brandId = array();
            if ($returnData['status']['success'] == 1) {
                if (!empty($returnData['data']['market_brands'])) {
                    
                    foreach ($returnData['data']['market_brands'] as $key => $value) {
                        
                        $returnData['data']['marketBrands'][$key]['id'] = $value['brand']['id'];
                        $returnData['data']['marketBrands'][$key]['name'] = $value['brand']['name'];
                        $returnData['data']['marketBrands'][$key]['image'] = isset($value['brand']['image']) ? CommonHelper::getPath('upload_url').UPLOAD_PATH_BRANDS_IMAGES.$value['brand']['image'] : '';
                        $returnData['data']['marketBrands'][$key]['market_id'] = $value['market_id'];
                        $brandId[$key] = $value['brand_id'];
                    }
                }
            }

            $product = array();
            if (!empty($brandId)) {
                $productData['brand_id'] = $brandId;
                $productRepository = new CataloguesRepository();
                $product = $productRepository->listing($productData);
                if($product['status']['success'] == 1){
                     foreach ($product['data']['catalogues'] as $key => $value) {
                         $image =$product['data']['catalogues'][$key]['image'];
                        unset($product['data']['catalogues'][$key]['image']);
                        unset($product['data']['catalogues'][$key]['market']);
                        unset($product['data']['catalogues'][$key]['brand']);
                        unset($product['data']['catalogues'][$key]['productType']);
                         $product['data']['catalogues'][$key]['image'] = isset($image) && ($image != '') ? CommonHelper::getPath('upload_url').UPLOAD_PATH_CATALOGUES_IMAGES.$image :'';
                    } 
                }
                $returnData['data']['catalogues'] = $product['data']['catalogues'];
            }
        }
        unset($returnData['data']['market_brands']);
        return $returnData;
    }

    public function actionQuestionList(){
        $question = new QuestionsRepository();
        $list =  $question->listing();
        return $list;
    }
    
    public function actionMarketRuleList(){
        $question = new MarketRepository();
        $list =  $question->marketList();
        $marketSegmentData = $list['data']['markets'][0]['marketSegmentData'];
        unset($list['data']['markets']);
        $list['data']['marketSegmentData'] = $marketSegmentData;
        return $list;
    }
}
