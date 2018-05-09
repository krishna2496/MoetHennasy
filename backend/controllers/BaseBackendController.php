<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use common\controllers\BaseController;
use yii\web\BadRequestHttpException;
use common\helpers\CommonHelper;
use common\models\ActionLog;
use yii\helpers\Url;

class BaseBackendController extends BaseController {

    public function beforeAction($action) {
        if (!Yii::$app->getRequest()->validateCsrfToken()) {
            Yii::$app->controller->enableCsrfValidation = false;
            Yii::$app->session->setFlash('error', Yii::t("app", "Unable to verify your data submission."));
            $url = Url::to();
            $this->redirect($url)->send();
            exit;
        }
        return parent::beforeAction($action);
    }

    protected function userActivity($actionId = array(),$descrition='') {
        $currentUser = CommonHelper::getUser();
      
        foreach ($actionId as $value){
           $actionLog = new ActionLog();
           $actionLog->action_type=$value;
           $actionLog->date=date('Y-m-d');
           $actionLog->time=date('H:m:s');
           if($descrition != ''){
           $actionLog->description=$descrition;
           }else{
           $actionLog->description=$value;
           }
           $actionLog->user=$currentUser['id'];
           if($actionLog->validate()){
               $actionLog->save(false);
           }
        }
    }

}
