<?php

namespace api\modules\v1\controllers;

class CountryController extends BaseApiController
{
    public $modelClass = 'api\modules\v1\models\Country';

    /*
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        return $actions;
    }*/

    public function actionIndex1()
    {
    	echo '<pre>';print_r('ads');exit;
    }
}


