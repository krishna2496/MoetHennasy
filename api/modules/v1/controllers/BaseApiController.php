<?php

namespace api\modules\v1\controllers;

use yii\rest\ActiveController;
use Yii;
use common\components\MoetQueryParamAuth;


class BaseApiController extends ActiveController
{
	protected $apiData    = '';
    protected $apiCode    = 0;
    protected $apiMessage = '';
    protected $isApi = 1;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => MoetQueryParamAuth::className(),
        ];
        return $behaviors;
    }
}


