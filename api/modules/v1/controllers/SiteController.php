<?php

namespace api\modules\v1\controllers;
use Yii;
use common\models\LoginForm;
use backend\models\PasswordResetRequestForm;
use common\helpers\CommonHelper;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\ResetPasswordForm;

class SiteController extends BaseApiController
{
    public $modelClass = 'common\models\User';

    public function actionLogin()
    {
        $this->apiCode = 0;
        $model = new LoginForm();
        $model->username = Yii::$app->request->post('username');
        $model->password = Yii::$app->request->post('password');
        $loginData = $model->login();
        if($loginData) {
            $this->apiCode = 1;
            $data = array();
            $data['user'] = $loginData;
            $this->apiData = $data;
        }
        if(isset($model->errors) && $model->errors){
            $this->apiMessage = $model->errors;
        }
        return $this->response();
    }

    public function actionRequestPasswordReset()
    {
        $this->apiCode = 0;
        $model = new PasswordResetRequestForm();
        $model->email = Yii::$app->request->post('email');
        if($model->validate()){
            $resetToken = $model->sendEmail();
            if ($resetToken) {
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'Check your email for further instructions.');
            } else {
                $this->apiMessage = Yii::t('app', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        if(isset($model->errors) && $model->errors){
            $this->apiMessage = $model->errors;
        }
        return $this->response();
    }

    public function actionResetPassword()
    {
        $this->apiCode = 0;
        $model = new ResetPasswordForm(Yii::$app->request->post('token'));
        $model->password = Yii::$app->request->post('password');
        if ($model->validate()) {
            if($model->resetPassword()){
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'New password saved.');
            } else {
                 $this->apiMessage = Yii::t('app', 'Wrong password reset token.');
            }
        }

        if(isset($model->errors) && $model->errors){
            $this->apiMessage = $model->errors;
        }
        return $this->response();
    }
}


