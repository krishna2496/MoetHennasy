<?php

namespace backend\controllers;

use Yii;
use common\models\Rules;
use common\models\RulesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\repository\RulesRepository;


/**
 * RulesController implements the CRUD actions for Rules model.
 */
class RulesController extends BaseBackendController
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
        $searchModel = new RulesSearch();
        $dataProvider = $searchModel->search($filters);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filters' => $filters,
        ]);
    }

    public function actionView($id)
    {   parent::userActivity('view_rule',$description='');
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Rules();

        if(Yii::$app->request->post()) {          
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post('Rules');
            $rulesRepository = new RulesRepository();
            $rulesData = $rulesRepository->createRules($data); 
            if($rulesData['status']['success'] == 1)
            {   parent::userActivity('create_rule',$description='');
                Yii::$app->session->setFlash('success', $rulesData['status']['message']);
                return $this->redirect(['index']);
                
            } else {
                Yii::$app->session->setFlash('danger', $rulesData['status']['message']);
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
            $data = Yii::$app->request->post('Rules');
            $data['id'] = $id;
            $rulesRepository = new RulesRepository();
            $rulesData = $rulesRepository->upadateRules($data); 
            if($rulesData['status']['success'] == 1)
            {   parent::userActivity('update_rule',$description='');
                Yii::$app->session->setFlash('success', $rulesData['status']['message']);
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('danger', $rulesData['status']['message']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        parent::userActivity('delete_rule',$description='');
        Yii::$app->session->setFlash('success', Yii::t('app', 'deleted_successfully', [Yii::t('app', 'rules')]));
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Rules::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
