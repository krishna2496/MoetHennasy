<?php

namespace api\modules\v1\controllers;
use Yii;
use common\models\LoginForm;
use common\helpers\CommonHelper;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class SiteController extends BaseApiController
{
    public $modelClass = 'common\models\User';

    public function actionLogin()
    {
        $response = array();
        $model = new LoginForm();
        $model->username = Yii::$app->request->post('username');
        $model->password = Yii::$app->request->post('password');
        $authKey = $model->login();
        if($authKey) {
            $response['username'] = $model->username;
            $response['authKey'] = $authKey;
        }

        if(isset($model->errors['password'][0])){
            $response['code'] = 403;
            $response['message'] = $model->errors['password'][0];
        }
        return $response;
    }
}


