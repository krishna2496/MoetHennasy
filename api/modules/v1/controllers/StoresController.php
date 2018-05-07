<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use common\helpers\CommonHelper;
use common\repository\StoreRepository;

class StoresController extends BaseApiController
{
    public $modelClass = 'common\models\Stores';

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
                    'actions' => ['create'],
                    'allow' => true,
                    'roles' => ['&'],
                ],
            ],
        ];
        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['create']);
        return $actions;
    }

    public function actionCreate()
    {
        $currentUser = CommonHelper::getUser();
        $data = array();
        $data['id'] = Yii::$app->request->post('id');
        $data['name'] = Yii::$app->request->post('name');
        $data['storeImage'] = $data['photo'] = Yii::$app->request->post('photo');
        $data['market_id'] = Yii::$app->request->post('market_id');
        $data['market_segment_id'] = Yii::$app->request->post('market_segment_id');
        $data['address1'] = Yii::$app->request->post('address1');
        $data['address2'] = Yii::$app->request->post('address2');
        $data['country_id'] = Yii::$app->request->post('country_id');
        $data['city_id'] = Yii::$app->request->post('city_id');
        $data['province_id'] = Yii::$app->request->post('province_id');
        $data['assign_to'] = $currentUser->id;
        $data['store_manager_first_name'] = Yii::$app->request->post('store_manager_first_name');
        $data['store_manager_last_name'] = Yii::$app->request->post('store_manager_last_name');
        $data['store_manager_phone_code'] = Yii::$app->request->post('store_manager_phone_code');
        $data['store_manager_phone_number'] = Yii::$app->request->post('store_manager_phone_number');
        $data['store_manager_email'] = Yii::$app->request->post('store_manager_email');
        $data['latitude'] = Yii::$app->request->post('latitude');
        $data['longitude'] = Yii::$app->request->post('longitude');
        $data['comment'] = Yii::$app->request->post('comment');
        $storeRepository = new StoreRepository;
        if($data['id']){
            $returnData = $storeRepository->updateStore($data);
        } else {
            $returnData = $storeRepository->createStore($data);
        }
        return $returnData;
    }
}
