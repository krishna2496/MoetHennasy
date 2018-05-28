<?php

namespace backend\controllers;

use Yii;
use common\models\MarketContacts;
use common\models\Markets;
use common\models\MarketContactsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\repository\MarketRepository;
use common\helpers\CommonHelper;
use common\repository\MarketContactRepository;

class MarketContactsController extends BaseBackendController {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => \common\components\AccessRule::className(),
                ],
                'rules' => [
                        [
                        'actions' => ['index', 'create', 'update', 'view', 'delete'],
                        'allow' => true,
                        'roles' => ['&'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex($id, $contactId = null) {

        $data['market_id'] = $id;
        $markets = new MarketRepository();
        $marketData = $markets->marketList($data);

        if ($marketData) {
            $filters = Yii::$app->request->queryParams;

            if (!isset($filters['limit'])) {
                $filters['limit'] = Yii::$app->params['pageSize'];
            }

            if ($marketData['status']['success'] == 1) {
                $segmentData = array();
                foreach ($marketData['data']['markets'][0]['marketSegmentData'] as $key => $value) {
                    $segmentData[$value['marketSegment']['id']] = $value['marketSegment']['title'];
                }
            }

            $searchModel = new MarketContactsSearch();
            if ($contactId) {
                $model = $this->findModel($contactId);
            } else {
                $model = new MarketContacts();
            }
            if (Yii::$app->request->post()) {
                $postData = Yii::$app->request->post();

                $data['market_segment_id'] = $postData['MarketContacts']['market_segment_id'];
                $data['market_id'] = $postData['market_id'];
                $data['address'] = $postData['MarketContacts']['address'];
                $data['phone'] = $postData['MarketContacts']['phone'];
                $data['email'] = $postData['MarketContacts']['email'];

                $repository = new MarketContactRepository();
                if ($contactId) {
                    $model = $this->findModel($contactId);
                    $data['id'] = $contactId;
                    $contactData = $repository->updateContact($data);
                    $activity = 'update_contact';
                } else {
                    $model->load(Yii::$app->request->post());
                    $contactData = $repository->createContact($data);
                    $activity = 'create_contact';
                }
                if ($contactData['status']['success'] == 1) {
                    parent::userActivity($activity, $description = '');
                    Yii::$app->session->setFlash('success', $contactData['status']['message']);
                    return $this->redirect(['market-contacts/index/' . $data['market_id']]);
                } else {
                    Yii::$app->session->setFlash('danger', $contactData['status']['message']);
                }
            }
            $filters['market_id'] = $id;
            $dataProvider = $searchModel->search($filters);
            $dataProvider->pagination->pageSize = $filters['limit'];

            return $this->render('create', [
                    'searchModel' => $searchModel,
                    'model' => $model,
                    'dataProvider' => $dataProvider,
                    'segmentData' => $segmentData,
                    'market_id' => $id,
                    'filters' => $filters
            ]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionView($id, $contactId = null) {
        return $this->render('view', [
                'model' => $this->findModel($contactId),
                'market_id' => $id
        ]);
    }

    public function actionDelete($id, $contactId = null) {
        $this->findModel($contactId)->delete();
        parent::userActivity('delete_market_contact',$description='');
        Yii::$app->session->setFlash('success', Yii::t('app', 'deleted_successfully', [Yii::t('app', 'Contact')]));
        return $this->redirect(['market-contacts/index/'.$id]);
    }

    protected function findModel($id) {
        if (($model = MarketContacts::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
