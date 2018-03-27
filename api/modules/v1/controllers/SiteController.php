<?php

namespace api\modules\v1\controllers;
use Yii;
use common\repository\UserRepository;
use common\helpers\CommonHelper;
use yii\filters\AccessControl;

class SiteController extends BaseApiController
{
    public $modelClass = 'common\models\User';

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
                    'actions' => ['login', 'request-password-reset', 'reset-password'],
                    'allow' => true,
                ],
                [
                    'actions' => ['logout', 'index', 'update-device-token'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];
        return $behaviors;
    }

    public function actionLogin()
    {
        $data = array();
        $data['username'] = Yii::$app->request->post('username');
        $data['password'] = Yii::$app->request->post('password');
        $data['deviceType'] = Yii::$app->request->post('deviceType');
        $data['deviceToken'] = Yii::$app->request->post('deviceToken');

        $userRepository = new UserRepository;
        $returnData =$userRepository->login($data);
        return $returnData;
    }

    public function actionRequestPasswordReset()
    {
        $data = array();
        $data['email'] = Yii::$app->request->post('email');

        $userRepository = new UserRepository;
        $returnData =$userRepository->requestPasswordReset($data);
        return $returnData;
    }

    public function actionResetPassword()
    {
        $data = array();
        $data['token'] = Yii::$app->request->post('token');
        $data['password'] = Yii::$app->request->post('password');

        $userRepository = new UserRepository;
        $returnData = $userRepository->resetPassword($data);
        return $returnData;
    }

    public function actionLogout(){
        $userRepository = new UserRepository;
        $returnData = $userRepository->logout();
        return $returnData;
    }

    public function actionUpdateDeviceToken(){
        $data = array();
        $data['deviceType'] = Yii::$app->request->post('deviceType');
        $data['deviceToken'] = Yii::$app->request->post('deviceToken');

        $userRepository = new UserRepository;
        $returnData = $userRepository->updateDeviceToken($data);
        return $returnData;
    }
}


