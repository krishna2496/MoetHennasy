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

    public function behaviors()
    {
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'ruleConfig' => [
                'class' => \common\components\AccessRule::className(),
            ],
            'rules' => [
                [
                    'actions' => ['login'],
                    'allow' => true,
                    'roles' => ['?'],
                ],
                [
                    'actions' => ['logout'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];

        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'logout' => ['post'],
                'login' => ['post'],
            ],
        ];

        return $behaviors;
    }

    public function actionLogin()
    {
        $response = array();
    	if (CommonHelper::getUser()) {
            return true;
        }

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


