<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use common\controllers\BaseController;
use yii\web\BadRequestHttpException;
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
}
