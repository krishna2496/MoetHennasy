<?php

namespace backend\controllers;

use Yii;
use common\models\Ratings;
use common\models\RatingsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\repository\RatingsRepository;


/**
 * RatingsController implements the CRUD actions for Ratings model.
 */
class RatingsController extends BaseBackendController {

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

    public function actionIndex() {
        $filters = Yii::$app->request->queryParams;
        if (!isset($filters['limit'])) {
            $filters['limit'] = Yii::$app->params['pageSize'];
        }
        $searchModel = new RatingsSearch();
        $dataProvider = $searchModel->search($filters);
        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'filters' => $filters,
        ]);
    }

    public function actionView($id) {
        parent::userActivity('view_ratings', $description = '');
        return $this->render('view', [
                'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate() {
        $filters['limit'] = Yii::$app->params['pageSize'];
        $searchModel = new RatingsSearch();
        $dataProvider = $searchModel->search($filters);
        $totalCount = $dataProvider->getTotalCount();

        $model = new Ratings();

        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post('Ratings');

            $repository = new RatingsRepository();
            $ratingData = $repository->createRatings($data);
            if ($ratingData['status']['success'] == 1) {
                parent::userActivity('create_ratings', $description = '');
                Yii::$app->session->setFlash('success', $ratingData['status']['message']);
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('danger', $ratingData['status']['message']);
            }
        }

        return $this->render('create', [
                'model' => $model,
                'totalCount' => $totalCount + 1
        ]);
    }

    public function actionUpdate($id) {

        $model = $this->findModel($id);

        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post('Ratings');
            $data['id'] = $id;
            $repository = new RatingsRepository();
            $ratingData = $repository->upadateRatings($data);
            if ($ratingData['status']['success'] == 1) {
                parent::userActivity('update_ratings', $description = '');
                Yii::$app->session->setFlash('success', $ratingData['status']['message']);
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('danger', $ratingData['status']['message']);
            }
        }

        return $this->render('update', [
                'model' => $model,
        ]);
    }

    public function actionDelete($id) {
        $model = $this->findModel($id);
       
        if ($model->rating > Yii::$app->params['star_min_size']['min_size']) {
           $id=Ratings::find()->max('id');
           if($model->id == $id){
           if ($model->delete()) {
                parent::userActivity('delete_ratings', $description = '');
                Yii::$app->session->setFlash('success', Yii::t('app', 'deleted_successfully', [Yii::t('app', 'ratings')]));
           }
           }else{
                Yii::$app->session->setFlash('danger', Yii::t('app', Yii::t('app', 'cant_delete')));  
           }
        } else { 
            Yii::$app->session->setFlash('danger', Yii::t('app', Yii::t('app', 'delete_rating')));
        }
        return $this->redirect(['index']);
    }

    protected function findModel($id) {
        if (($model = Ratings::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
