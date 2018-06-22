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
use common\repository\StoreConfigRepository;

class StoresConfigController extends BaseApiController {

    public $modelClass = 'common\models\Brands';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'ruleConfig' => [
                'class' => \common\components\AccessRule::className(),
            ],
            'rules' => [
                [
                    'actions' => ['brand-list','question-list','market-rule-list','configuration','listing','rating'],
                    'allow' => true,
                    'roles' => ['&'],
                ],
            ],
        ];
        return $behaviors;
    }

    public function actions() {
        $actions = parent::actions();

        unset($actions['brand-list']);
        unset($actions['question-list']);
        unset($actions['market-rule-list']);
        unset($actions['market-rule-list']);
        unset($actions['configuration']);
        unset($actions['listing']);
        unset($actions['rating']);
        
        return $actions;
    }

    public function actionBrandList() {

        $currentUser = CommonHelper::getUser();
        $marketId = '';
        if (isset($currentUser->market_id) && ($currentUser->market_id != '')) {
            $marketId = $currentUser->market_id;
        }

        $returnDatas = array();
        $repository = new MarketBrandsRepository();

        if ($marketId != '') {
            $data['market_id'] = $marketId;
            $returnData = $repository->listing($data);


            $brandId = array();
            if ($returnData['status']['success'] == 1) {
                if (!empty($returnData['data']['market_brands'])) {

                    foreach ($returnData['data']['market_brands'] as $key => $value) {
                        $image = $value['brand']['image'];

                        unset($value['brand']['created_by']);
                        unset($value['brand']['updated_by']);
                        unset($value['brand']['created_by']);
                        unset($value['brand']['deleted_by']);
                        unset($value['brand']['created_at']);
                        unset($value['brand']['updated_at']);
                        unset($value['brand']['deleted_at']);
                        $value['brand']['image'] = isset($value['brand']['image']) ? CommonHelper::getPath('upload_url') . UPLOAD_PATH_BRANDS_IMAGES . $image : '';


                        $product = $value['brand']['product'];

                        foreach ($product as $key1 => $value1) {
                            $imageProduct = $value1['image'];
                            $box_only = $value1['box_only'];
                            $top_shelf = $value1['top_shelf'];
                            unset($value['brand']['product'][$key1]['image']);
                            unset($value['brand']['product'][$key1]['top_shelf']);
                            unset($value['brand']['product'][$key1]['box_only']);
                            $value['brand']['product'][$key1]['image'] = isset($imageProduct) && ($imageProduct != '') ? CommonHelper::getPath('upload_url') . UPLOAD_PATH_CATALOGUES_IMAGES . $imageProduct : '';
                            $value['brand']['product'][$key1]['top_shelf'] = \yii::$app->params['catalogue_status'][$top_shelf];
                            $value['brand']['product'][$key1]['box_only'] = \yii::$app->params['catalogue_status'][$box_only];
                        }
                        $returnDatas['marketBrands'][$key] = $value['brand'];
                    }
                }
            }
        }

        return $returnDatas;
    }

    public function actionQuestionList() {
        $question = new QuestionsRepository();
        $list = $question->listing();
        return $list;
    }

    public function actionMarketRuleList() {
        $question = new MarketRepository();
        $list = $question->marketList();
        $dataArry = array();
        $marketSegmentData = $list['data']['markets'][0]['marketSegmentData'];
        unset($list['data']['markets']);
        foreach ($marketSegmentData as $key => $value) {
            $marketRules = $value['marketSegment']['marketRules'];
            $dataArry['marketSegmentData'][$key]['id'] = $value['id'];
            $dataArry['marketSegmentData'][$key]['market_id'] = $value['market_id'];
            $dataArry['marketSegmentData'][$key]['market_segment_id'] = $value['market_segment_id'];
            $dataArry['marketSegmentData'][$key]['title'] = $value['marketSegment']['title'];
            $dataArry['marketSegmentData'][$key]['description'] = $value['marketSegment']['description'];
            $rulesArrry = array();
            $rules = $value['marketSegment']['marketRules'];
            foreach ($rules as $key1 => $value1) {
                $rulesArrry[$key1] = $value1['rules'];
            }
            $dataArry['marketSegmentData'][$key]['marketRules'] = $rulesArrry;
        }
        return $dataArry;
    }

    public function actionConfiguration() {

        $data = Yii::$app->request->post('config');
     
        $configData = json_decode($data ,true);
    
        $storeConfig = new StoreConfigRepository();
        if(isset($configData['config_id']) && ($configData['config_id'] != '')){
        $returnData = $storeConfig->updateConfig($configData);
        }else{
        $returnData = $storeConfig->createConfig($configData);
        }
        return $returnData;
    }
    
    public function actionListing() {
        $storeConfig = new StoreConfigRepository();
        $returnData = $storeConfig->listing($data = array());
        return $returnData;
    }
    
    public function actionRating() {
        $data = Yii::$app->request->post();
        
        $storeConfig = new StoreConfigRepository();
        
        $returnData = $storeConfig->createRating($data);
        return $returnData;
    }
    

}
