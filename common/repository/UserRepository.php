<?php
namespace common\repository;
use common\models\LoginForm;
use common\models\PasswordResetRequestForm;
use common\models\ResetPasswordForm;
use common\models\User;
use Yii;

class UserRepository extends Repository
{
    public function login($data)
    {
        $model = new LoginForm();
        $model->username = $data['username'];
        $model->password = $data['password'];
        $model->device_type = $data['deviceType'];
        $model->device_token = $data['deviceToken'];
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

    public function requestPasswordReset($data){
        $this->apiCode = 0;
        $model = new PasswordResetRequestForm();
        $model->email = $data['email'];
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

    public function resetPassword($data){
        $this->apiCode = 0;
        $model = new ResetPasswordForm($data['token']);
        $model->password = $data['password'];
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

    public function logout()
    {
        $this->apiCode = 0;
        $headers = Yii::$app->request->headers;
        $accessToken = $headers->get('authToken');
        $model = User::findIdentityByAccessToken($accessToken);
        if($model){
            $model->auth_key = '';
            $model->save(false);
            $this->apiCode = 1;
            $this->apiMessage = Yii::t('app', 'Logout sucessfully.');
        } else {
             $this->apiMessage = Yii::t('app', 'Something went wrong.');
        }
        return $this->response();
    }
}