<?php

namespace api\modules\v1\controllers;
use Yii;
use common\repository\UserRepository;
use common\repository\UploadRepository;
use common\helpers\CommonHelper;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

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
                    'actions' => ['logout', 'index', 'update-device-token','upload','user-data','change-password','edit-profile'],
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
        $data['loginType'] = 'Mobile.Site.Login';

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

    public function actionUpload(){
        $data = array();
        $data['type'] = Yii::$app->request->post('type');
        $data['files'] = UploadedFile::getInstancesByName('file');
        $uploadRepository = new UploadRepository;
        $returnData = $uploadRepository->store($data);
        return $returnData;
    }

    public function actionUserData(){
        $userRepository = new UserRepository;
        $returnData = $userRepository->getLoginUserDetail();
        return $returnData;
    }

    public function actionChangePassword(){
        $data = Yii::$app->request->post();
        $userRepository = new UserRepository;
        $returnData = $userRepository->changePassword($data);
        return $returnData;
    }

    public function actionEditProfile(){
        $currentUser = CommonHelper::getUser();
        $data = array();
        $data['id'] = $currentUser->id;
        $data['email'] = Yii::$app->request->post('email');
        $data['phone'] = Yii::$app->request->post('phone');
        $data['address'] = Yii::$app->request->post('address');
        $data['first_name'] = Yii::$app->request->post('first_name');
        $data['last_name'] = Yii::$app->request->post('last_name');
        $data['latitude'] = Yii::$app->request->post('latitude');
        $data['longitude'] = Yii::$app->request->post('longitude');
        $data['profile_photo'] = Yii::$app->request->post('profile_photo');
        $userRepository = new UserRepository;
        $returnData = $userRepository->updateUser($data,'profile');
        return $returnData;
    }
}


