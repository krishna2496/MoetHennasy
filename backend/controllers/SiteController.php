<?php
namespace backend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\helpers\CommonHelper;
use common\models\PasswordResetRequestForm;
use common\models\ResetPasswordForm;
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
                        'actions' => ['logout', 'index'],
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
        if (CommonHelper::getUser()) {
            return $this->goHome();
        }

        $responseUser = array();

        $model = new LoginForm();
        if(Yii::$app->request->post()){
            $data = Yii::$app->request->post('LoginForm');
            $data['deviceType'] = Yii::$app->params['deviceType']['web'];
            $data['deviceToken'] = '';
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
        setcookie('auth_key', null, -1, '/');

        return $this->goHome();
    }

    public function actionRequestPasswordReset()
    {
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
}
