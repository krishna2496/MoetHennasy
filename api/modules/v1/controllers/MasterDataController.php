<?php

namespace api\modules\v1\controllers;
use Yii;
use common\repository\MasterDataRepository;
use common\helpers\CommonHelper;
use yii\filters\AccessControl;

class MasterDataController extends BaseApiController
{
    public $modelClass = 'common\models\Cities';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'ruleConfig' => [
                'class' => \common\components\AccessRule::className(),
            ],
            'rules' => [
                [
                    'actions' => ['masters'],
                    'allow' => true,
                    'roles' => ['&'],
                ],
                ],
        ];
        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['masters']);
        return $actions;
    }

    public function actionMasters()
    {
        $currentUser = CommonHelper::getUser();
        $data['user_id'] = $currentUser->id;
        $masterDataRepository = new MasterDataRepository;
        $returnData = $masterDataRepository->listing($data);
        return $returnData;
    }
    }


