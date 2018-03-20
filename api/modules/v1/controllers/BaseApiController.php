<?php

namespace api\modules\v1\controllers;

use yii\rest\ActiveController;
use Yii;
use common\components\MoetQueryParamAuth;


class BaseApiController extends ActiveController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => MoetQueryParamAuth::className(),
        ];
        return $behaviors;
    }
}


