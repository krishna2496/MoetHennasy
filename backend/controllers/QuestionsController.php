<?php

namespace backend\controllers;

use Yii;
use common\models\Questions;
use common\models\QuestionsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\repository\QuestionsRepository;
/**
 * QuestionsController implements the CRUD actions for Questions model.
 */
class QuestionsController extends BaseBackendController
{
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
    
    public function actionIndex()
    {
        $filters = Yii::$app->request->queryParams;
        if(!isset($filters['limit'])){
            $filters['limit'] = Yii::$app->params['pageSize'];
        }
        $searchModel = new QuestionsSearch();
        $dataProvider = $searchModel->search($filters);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filters' => $filters,
        ]);
    }

    public function actionView($id)
    {
        parent::userActivity('view_questions',$description='');
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Questions();

        if(Yii::$app->request->post()) {          
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post('Questions');
            $questionsRepository = new QuestionsRepository();
            $questionsData = $questionsRepository->createQuestions($data); 
            if($questionsData['status']['success'] == 1)
            {   
                parent::userActivity('create_questions',$description='');
                Yii::$app->session->setFlash('success', $questionsData['status']['message']);
                return $this->redirect(['index']);
                
            } else {
                Yii::$app->session->setFlash('danger', $questionsData['status']['message']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->post()) {          
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post('Questions');
            $data['id'] = $id;
            $questionsRepository = new QuestionsRepository();
            $questionsData = $questionsRepository->updateQuestions($data); 
            if($questionsData['status']['success'] == 1)
            {   parent::userActivity('update_questions',$description='');
                Yii::$app->session->setFlash('success', $questionsData['status']['message']);
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('danger', $questionsData['status']['message']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        if($this->findModel($id)->delete()){
            parent::userActivity('delete_questions',$description='');
            Yii::$app->session->setFlash('success', Yii::t('app', 'deleted_successfully', [Yii::t('app', 'helps')]));
            return $this->redirect(['index']);
        }
        
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Questions::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
