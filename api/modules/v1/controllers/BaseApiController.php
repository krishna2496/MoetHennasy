<?php

namespace api\modules\v1\controllers;

use yii\rest\ActiveController;
use Yii;
use common\components\CustomQueryParamAuth;


class BaseApiController extends ActiveController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CustomQueryParamAuth::className(),
        ];
        return $behaviors;
    }
}


