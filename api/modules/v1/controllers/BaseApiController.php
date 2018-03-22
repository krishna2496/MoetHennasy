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

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => MoetQueryParamAuth::className(),
        ];
        return $behaviors;
    }

    protected function response(){
        if(!$this->apiData){
        	$this->apiData = new \stdClass();
        }
        
        $response['status']['success'] = $this->apiCode;
        $response['status']['message'] = $this->getError($this->apiMessage);
        $response['data']    = $this->apiData;

        return $response;
    }

    private function getError($errors){
    	if(is_array($errors)){
        	foreach ($errors as $key => $value) {
        		if(is_array($value)){
        			return $this->getError($value);
        		} else {
        			return $value;
        		}
        	}
        } else {
        	return $errors;
        }
    }
}


