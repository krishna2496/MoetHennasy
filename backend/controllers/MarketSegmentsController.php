<?php

namespace backend\controllers;

use Yii;
use common\models\MarketSegments;
use common\models\MarketSegmentsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\repository\MarketSegmentsRepository;

/**
 * MarketSegmentsController implements the CRUD actions for MarketSegments model.
 */
class MarketSegmentsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => \common\components\AccessRule::className(),
                ],
                'rules' => [
                    [
                        'actions' => ['index','create','update','view','delete'],
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

    /**
     * Lists all MarketSegments models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MarketSegmentsSearch();
        $filters = Yii::$app->request->queryParams;
        if(!isset($filters['limit'])){
            $filters['limit'] = Yii::$app->params['pageSize'];
        }
        
        $dataProvider = $searchModel->search($filters);
        $dataProvider->pagination->pageSize = $filters['limit'];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filters' => $filters,
        ]);
    }

    /**
     * Displays a single MarketSegments model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MarketSegments model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MarketSegments();
        $model->scenario = 'create';
          if(Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
           
            $data = Yii::$app->request->post('MarketSegments');
       
            $marketSegmentsRepository = new MarketSegmentsRepository;
            $returnData = $marketSegmentsRepository->createMarketSegment($data);
            if($returnData['status']['success'] == 1)
            {
                Yii::$app->session->setFlash('success', $returnData['status']['message']);
                return $this->redirect(['index']);
            } else {
                 Yii::$app->session->setFlash('danger', $returnData['status']['message']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing MarketSegments model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';
        if(Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post('MarketSegments');
            $data['id'] = $id;
            $marketSegmentsRepository = new MarketSegmentsRepository;
            $returnData = $marketSegmentsRepository->updateMarketSegment($data);
            if($returnData['status']['success'] == 1)
            {
                Yii::$app->session->setFlash('success', $returnData['status']['message']);
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('danger', $returnData['status']['message']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing MarketSegments model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if($this->findModel($id)->delete()){
            Yii::$app->session->setFlash('success', Yii::t('app', 'Market segment deleted successfully'));
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the MarketSegments model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MarketSegments the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MarketSegments::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
