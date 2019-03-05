<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use common\models\Stores;
use common\models\StoresSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use common\helpers\CommonHelper;
use common\repository\MarketRepository;
use common\repository\MasterDataRepository;
use common\repository\MarketSegmentsRepository;
use common\repository\UserRepository;
use common\repository\StoreRepository;
use common\repository\UploadRepository;


class StoresAjaxController extends BaseBackendController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => \common\components\AccessRule::className(),
                ],
                'rules' => [
                    [
                        'actions' => ['index','create','update','view','delete','export-data'],
                        'allow' => true,
                        'roles' => ['&'],
                    ],
                    [
                        'actions' => ['ajax-get-segment','ajax-get-user','ajax-get-city','ajax-get-province'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }



    public function actionAjaxGetSegment()
    {
        $data = Yii::$app->request->post();
        $marketRepository = new MarketRepository();
        $returnData = $marketRepository->segmentList($data);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $returnData;
    }

    public function actionAjaxGetUser()
    {
        $currentUser = CommonHelper::getUser();
        $data = Yii::$app->request->post();
        if($currentUser->role_id != Yii::$app->params['superAdminRole']){
            $userObj = new User;
            $childUser = $userObj->getAllChilds(array($currentUser->id));
            $childUser[] = $currentUser->id;
            $data['parent_user_id'] = $childUser;
        }
        $userRepository = new UserRepository;
        $returnData = $userRepository->userList($data);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $returnData;
    }

    public function actionAjaxGetCity()
    {
        $data = Yii::$app->request->post();
        $userRepository = new MasterDataRepository;
        $returnData = $userRepository->cities($data);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $returnData;
    }

    public function actionAjaxGetProvince()
    {
        $data = Yii::$app->request->post();
        $userRepository = new MasterDataRepository;
        $returnData = $userRepository->provinces($data);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $returnData;
    }
    
    

}
