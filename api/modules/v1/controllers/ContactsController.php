<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\helpers\CommonHelper;
use common\repository\MarketContactRepository;
use common\repository\MarketRepository;

class ContactsController extends BaseApiController {

    public $modelClass = 'common\models\Helps';

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'ruleConfig' => [
                'class' => \common\components\AccessRule::className(),
            ],
            'rules' => [
                    [
                    'actions' => ['index', 'listing'],
                    'allow' => true,
                    'roles' => ['&'],
                ],
            ],
        ];
        return $behaviors;
    }

    public function actions() {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['listing']);
        return $actions;
    }

    public function actionIndex() {

        $data = array();
        $data['market_segment_id'] = Yii::$app->request->get('marketSegmentId');
        $data['market_id'] = Yii::$app->request->get('marketId');
        $repository = new MarketContactRepository();

        $returnData = $repository->listing($data);
        return $returnData;
    }

    public function actionListing() {

        $data = array();
        $data['market_segment_id'] = Yii::$app->request->get('marketSegmentId');
        $data['market_id'] = Yii::$app->request->get('marketId');
        $repository = new MarketRepository();
        $resultStoreList = $repository->marketList($data);
        if (isset($data['market_segment_id']) && ($data['market_segment_id'] != '')) {
            $storeList['data']['markets'] = array();
            if ($resultStoreList['status']['success'] == 1) {

                if ($resultStoreList['data']['markets']) {
                    foreach ($resultStoreList['data']['markets'] as $key => $value) {

                        $storeList['data']['markets'][$key]['id'] = $value['id'];
                        $storeList['data']['markets'][$key]['title'] = $value['title'];
                        $storeList['data']['markets'][$key]['description'] = $value['description'];
                        $storeList['data']['markets'][$key]['created_by'] = $value['created_by'];
                        $storeList['data']['markets'][$key]['updated_by'] = $value['updated_by'];
                        $storeList['data']['markets'][$key]['deleted_by'] = $value['deleted_by'];
                        $storeList['data']['markets'][$key]['created_at'] = $value['created_at'];
                        $storeList['data']['markets'][$key]['updated_at'] = $value['updated_at'];
                        $storeList['data']['markets'][$key]['deleted_at'] = $value['deleted_at'];
                        $isDisplay = 0;
                        $storeList['data']['markets'][$key]['marketSegmentData'] = array();
                        foreach ($value['marketSegmentData'] as $key1 => $value1) {
                            if ($value1['market_segment_id'] == $data['market_segment_id']) {
                                $isDisplay = 1;
                                $storeList['data']['markets'][$key]['marketSegmentData'][] = $value1;
                            }
                        }
                        if ($isDisplay == 0) {
                            unset($storeList['data']['markets'][$key]);
                        }
                    }
                     $storeList['data']['markets'][$key]['user'] = $value['user'];
                }
            }
        }else{
            $storeList = $resultStoreList;
        }
        return $storeList;
    }

}
