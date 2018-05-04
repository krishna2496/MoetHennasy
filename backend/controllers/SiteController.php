<?php
namespace backend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use common\models\LoginForm;
use common\models\User;
use common\models\PasswordResetRequestForm;
use common\models\ResetPasswordForm;
use common\models\ParentRolePermission;
use common\helpers\CommonHelper;
use common\repository\UploadRepository;
use common\repository\UserRepository;

class SiteController extends BaseBackendController
{
    /**
     * {@inheritdoc}
     */
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
                        'actions' => ['login', 'error', 'request-password-reset', 'reset-password'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index','edit-profile'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        $this->layout = 'auth';
        if (CommonHelper::getUser()) {
            return $this->goHome();
        }

        $responseUser = array();

        $model = new LoginForm();
        if(Yii::$app->request->post()){
            $data = Yii::$app->request->post('LoginForm');
            $data['deviceType'] = Yii::$app->params['deviceType']['web'];
            $data['deviceToken'] = '';
            $data['loginType'] = 'Desktop.Site.Login';
            $userRepository = new UserRepository;
            $returnData = $userRepository->login($data);
            if($returnData['status']['success'] == 1){

                $authKey = $returnData['data']['user']['auth_key'];
                $cookies = Yii::$app->response->cookies;
                $cookies->add(new \yii\web\Cookie([
                    'name' => 'auth_key',
                    'value' => $authKey,
                    'expire' => time() + 86400 * 365 * 20,
                ]));
                return $this->redirect(['site/index']);

            } else {
                $model->load(Yii::$app->request->post());
                Yii::$app->session->setFlash('danger', $returnData['status']['message']);
            }
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        $userRepository = new UserRepository;
        $userRepository->logout();
        Yii::$app->user->logout();
        setcookie('auth_key', null, -1, '/');

        return $this->goHome();
    }

    public function actionRequestPasswordReset()
    {
        $this->layout = 'auth';
        if (CommonHelper::getUser()) {
            return $this->goHome();
        }

        $model = new PasswordResetRequestForm();

        if(Yii::$app->request->post()){
            $data = Yii::$app->request->post('PasswordResetRequestForm');

            $userRepository = new UserRepository;
            $returnData = $userRepository->requestPasswordReset($data);
            if($returnData['status']['success'] == 1){
                Yii::$app->session->setFlash('success', $returnData['status']['message']);
                return $this->redirect(['site/login']);
            } else {
                $model->load(Yii::$app->request->post());
                Yii::$app->session->setFlash('error', $returnData['status']['message']);
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        $this->layout = 'auth';
        if (CommonHelper::getUser()) {
            return $this->goHome();
        }

        try {
            $model = new ResetPasswordForm($token);
            if(Yii::$app->request->post()){
                $data = Yii::$app->request->post('ResetPasswordForm');
                $userRepository = new UserRepository;
                $returnData = $userRepository->resetPassword($data);
                if($returnData['status']['success'] == 1){
                    Yii::$app->session->setFlash('success', $returnData['status']['message']);
                } else {
                    Yii::$app->session->setFlash('error', $returnData['status']['message']);
                }
                return $this->redirect(['site/login']);
            }
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return $this->render('resetPassword', [
            'model' => $model,
            'token' => $token,
        ]);
    }

    public function actionEditProfile(){
        $currentUser = CommonHelper::getUser();
        $userList = array();
        $roles = array();
        $parentUserClass = 'hideParentUser'; 
        $userRepository = new UserRepository;

        $model = User::findOne($currentUser->id);
        if(!$model){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $model->scenario = 'update';
        $parentRoleModel = new ParentRolePermission();
        $parentRoles = $parentRoleModel->getAllParentPermission();
        if(isset($parentRoles[$currentUser->role_id])){
            $roles = $parentRoles[$currentUser->role_id];
        }

        if(Yii::$app->request->post()) {
            $oldImagePath = CommonHelper::getPath('upload_path').UPLOAD_PATH_USER_IMAGES.$model->profile_photo;
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post('User');
            $data['id'] = $model->id;
            $data['device_type'] = Yii::$app->params['deviceType']['web'];
            $data['role_id'] = $model->role_id;
            $data['status'] = $model->status;
            
            $data['profile_photo'] = '';
            if(UploadedFile::getInstance($model,'userImage')) {
                $fileData = array();
                $fileData['files'][0] = UploadedFile::getInstance($model,'userImage');
                $fileData['type'] = 'profile';
                $uploadUrl = CommonHelper::getPath('upload_url').$fileData['type'].'/';
                $uploadRepository = new UploadRepository;
                $uploadData = $uploadRepository->store($fileData);
                if($uploadData['status']['success'] == 1){
                    $data['profile_photo'] = str_replace($uploadUrl,"",$uploadData['data']['uploadedFile'][0]['name']);
                    if(file_exists($oldImagePath)){
                        @unlink($oldImagePath);
                    }
                } else {
                    return $this->redirect(['index']);
                    Yii::$app->session->setFlash('danger', $uploadData['status']['message']);
                }
            }

            $userRepository = new UserRepository;
            $returnData = $userRepository->updateUser($data,'profile');
            if($returnData['status']['success'] == 1)
            {
                Yii::$app->session->setFlash('success', $returnData['status']['message']);
                return $this->redirect(['edit-profile']);
            } else {
                Yii::$app->session->setFlash('danger', $returnData['status']['message']);
            }
        }

        return $this->render('profile', [
            'model' => $model,
            'roles' => $roles,
            'parentUserClass' => $parentUserClass,
            'userList' => $userList,
        ]);
    }
}
