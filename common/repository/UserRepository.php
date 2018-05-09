<?php
namespace common\repository;

use Yii;
use common\models\LoginForm;
use common\models\PasswordResetRequestForm;
use common\models\ResetPasswordForm;
use common\models\User;
use common\helpers\CommonHelper;
use common\components\Email;
use common\repository\PermissionRepository;

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
            $returnData = array();
            $returnData['user'] = $loginData;
            $returnData['user']['profile_photo'] = $loginData->profile_photo ? CommonHelper::getPath('upload_url').UPLOAD_PATH_USER_IMAGES.$loginData->profile_photo : '';
            $returnData['role'] = $loginData->role;
            $returnData['market'] = $loginData->market;
            $this->apiData = $returnData;

            //check login access
            $permissionRepository = new PermissionRepository;
            if(isset($data['loginType']) && !$permissionRepository->checkLoginPermission($data['loginType'],$loginData->role_id)){
                $this->logout();
                $this->apiCode = 0;
                $this->apiData = array();
                $this->apiMessage = Yii::t('app', 'You are not allowed to perform this action.');
            }
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
        $user = Yii::$app->user->identity;
        $model = User::findOne($user->id);
        if($model){
            $model->auth_key = '';
            $model->save(false);
            Yii::$app->user->logout();
            $this->apiCode = 1;
            $this->apiMessage = Yii::t('app', 'Logout sucessfully.');
        } else {
             $this->apiMessage = Yii::t('app', 'Something went wrong.');
        }
        return $this->response();
    }

    public function updateDeviceToken($deviceInfo = array())
    {
        $this->apiCode = 0;
        $user = Yii::$app->user->identity;

        $userModel = User::findOne($user->id);
        $userModel->device_type = $deviceInfo['deviceType'];
        $userModel->device_token = $deviceInfo['deviceToken'];
                
        if($userModel->save(false)){
            $this->apiCode = 1;
            $this->apiMessage = Yii::t('app', 'Device info stored sucessfully.');
        }else{
            $this->apiCode = 0;
            $this->apiMessage = Yii::t('app', 'Fail to store user.');
        }

        return $this->response();
    }

    public function userList($data = array()){
        $this->apiCode = 1;
        $query = User::find()->joinWith(['role','market'])->andWhere(['!=','role_id',Yii::$app->params['superAdminRole']]);
        if(isset($data['role_id']) && $data['role_id']){
            $query->andWhere(['=','users.role_id',$data['role_id']]);
        }
        if(isset($data['parent_user_id']) && $data['parent_user_id']){
            $query->andWhere(['=','users.parent_user_id',$data['parent_user_id']]);
        }
        if(isset($data['id']) && $data['id']){
            $query->andWhere(['=','users.id',$data['id']]);
        }
        if(isset($data['update_id']) && $data['update_id']){
            $query->andWhere(['!=','users.id',$data['update_id']]);
        }
        if(isset($data['market_id']) && $data['market_id']){
            $query->andWhere(['=','users.market_id',$data['market_id']]);
        }
        if(isset($data['search']) && $data['search']){
            $data['search'] = trim($data['search']);
            $nameArray = explode(' ', $data['search']);
            $firstName = $nameArray[0];
            $lastName = isset($nameArray[1]) ? $nameArray[1] : $nameArray[0];
            $query->andWhere([
                'or',
                    ['like', 'first_name', $firstName],
                    ['like', 'last_name', $lastName],
                    ['like', 'username', $data['search']],
                    ['like', 'email', $data['search']],
            ]);
        }
        $data = array();
        $data['users'] = $query->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }

    public function createUser($data = array()){
        $this->apiCode = 0;
        $userModel = new User;
        $userModel->scenario = 'create';
        $userModel->username = strtolower($data['username']);
        $userModel->email = $data['email'];
        $userModel->first_name = $data['first_name'];
        $userModel->last_name = $data['last_name'];
        $userModel->status = $data['status'];
        $userModel->role_id = $data['role_id'];
        $userModel->device_type = $data['device_type'];
        $userModel->phone = $data['phone'];
        $userModel->address = $data['address'];
        $userModel->market_id = $data['market_id'];
        $userModel->company_name = $data['company_name'];
        if(isset($data['profile_photo']) && $data['profile_photo']){
            $userModel->profile_photo = $data['profile_photo'];
        }
        if(isset($data['device_token'])) {
            $userModel->device_token = $data['device_token'];
        }
        if(isset($data['parent_user_id'])) {
            $userModel->parent_user_id = $data['parent_user_id'];
        }
        if($userModel->validate()) {
            $password = CommonHelper::generateRandomString(6);
            $userModel->setPassword($password);
            $userModel->generateAuthKey();

            if($userModel->save(false)) {
                $mail = new Email();
                $mail->email = $userModel->email;

                $siteUrl = CommonHelper::getPath('site_url');
                $userString = array();
                $userString[] = $userModel->first_name;
                $userString[] = $userModel->last_name;
                $mail->body = Yii::$app->controller->renderPartial('mail');
                $mail->setFrom = Yii::$app->params['supportEmail'];
                $mail->subject = 'Create User';
                $mail->set("USERNAME", $userModel->username);
                $mail->set("NAME", implode(' ', $userString));
                if (isset($password)) {
                    $mail->set("PASSWORD", $password);
                }
                $mail->send();

                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'created_successfully', [Yii::t('app', 'user')]);
            } else {
                $this->apiCode = 0;
                $this->apiMessage = Yii::t('app', 'Something went wrong.');
            }
        } else {
            $this->apiCode = 0;
            if(isset($userModel->errors) && $userModel->errors){
                $this->apiMessage = $userModel->errors;
            }
        }

        return $this->response();
    }

    public function updateUser($data = array(), $type='user'){
        $this->apiCode = 0;
        $userModel = new User;
        $userModel = User::findOne($data['id']);
        if(!$userModel){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        if(isset($data['username'])) {
            $userModel->username = strtolower($data['username']);
        }
        if(isset($data['email'])) {
            $userModel->email = $data['email'];
        }
        if(isset($data['first_name'])) {
            $userModel->first_name = $data['first_name'];
        }
        if(isset($data['last_name'])) {
            $userModel->last_name = $data['last_name'];
        }
        if(isset($data['status'])) {
            $userModel->status = $data['status'];
        }
        if(isset($data['role_id'])) {
            $userModel->role_id = $data['role_id'];
        }
        if(isset($data['role_id'])) {
            $userModel->device_type = $data['device_type'];
        }
        if(isset($data['phone'])) {
            $userModel->phone = $data['phone'];
        }
        if(isset($data['address'])) {
            $userModel->address = $data['address'];
        }
        if(isset($data['profile_photo']) && $data['profile_photo']){
            $userModel->profile_photo = $data['profile_photo'];
        }
        if(isset($data['new_password'])) {
            $userModel->new_password = $data['new_password'];
        }
        if(isset($data['confirm_password'])) {
            $userModel->confirm_password = $data['confirm_password'];
        }
        if(isset($data['device_token'])) {
            $userModel->device_token = $data['device_token'];
        }
        if(isset($data['parent_user_id'])) {
            $userModel->parent_user_id = $data['parent_user_id'];
        }
        if(isset($data['latitude'])) {
            $userModel->latitude = $data['latitude'];
        }
        if(isset($data['longitude'])) {
            $userModel->longitude = $data['longitude'];
        }
        if(isset($data['market_id'])) {
            $userModel->market_id = $data['market_id'];
        }
        if(isset($data['company_name'])){
            $userModel->company_name = $data['company_name'];
        }
        if($userModel->validate()) {
            if($userModel->new_password) {
                $userModel->setPassword($userModel->new_password);
                $userModel->generateAuthKey();
            }

            if($userModel->save(false)) {
                $returnData = array();
                $returnData['user'] = $userModel;
                $returnData['user']['profile_photo'] = $userModel->profile_photo ? CommonHelper::getPath('upload_url').UPLOAD_PATH_USER_IMAGES.$userModel->profile_photo : '';
                $returnData['role'] = $userModel->role;
                $returnData['market'] = $userModel->market;
                $this->apiData = $returnData;
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'updated_successfully', [Yii::t('app', $type)]);
            } else {
                $this->apiCode = 0;
                $this->apiMessage = Yii::t('app', 'Something went wrong.');
            }
        } else {
            $this->apiCode = 0;
            if(isset($userModel->errors) && $userModel->errors){
                $this->apiMessage = $userModel->errors;
            }
        }

        return $this->response();
    }

    public function getLoginUserDetail(){
        $currentUser = CommonHelper::getUser();
        unset($currentUser->created_by);
        unset($currentUser->updated_by);
        unset($currentUser->deleted_by);
        unset($currentUser->created_at);
        unset($currentUser->updated_at);
        unset($currentUser->deleted_at);
        unset($currentUser->password_hash);
        unset($currentUser->password_reset_token);
        $this->apiCode = 1;
        $returnData = array();
        $returnData['user'] = $currentUser;
        $returnData['user']['profile_photo'] = $currentUser->profile_photo ? CommonHelper::getPath('upload_url').UPLOAD_PATH_USER_IMAGES.$currentUser->profile_photo : '';
        $returnData['role'] = $currentUser->role;
        $returnData['market'] = $currentUser->market;
        $this->apiData = $returnData;
        return $this->response();
    }

    public function changePassword($data){
        $this->apiCode = 0;
        $currentUser = CommonHelper::getUser();
        if(Yii::$app->security->validatePassword($data['oldPassword'], $currentUser->password_hash)){
            $userModel = User::findOne($currentUser->id);
            $userModel->setPassword($data['newPassword']);
                    
            if($userModel->save(false)){
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'Password changed sucessfully.');
            }else{
                $this->apiMessage = Yii::t('app', 'Something went wrong.');
            }
        } else {
            $this->apiMessage = Yii::t('app', 'You have entered wrong Password.');
        }
        return $this->response();
    }
}