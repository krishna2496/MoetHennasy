<?php

namespace backend\controllers;

use Yii;
use common\models\Helps;
use common\models\HelpsSearch;
use common\models\HelpCategories;
use common\repository\HelpsRepository;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class HelpsController extends BaseBackendController
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

    public function actionIndex($id)
    {
        $category=HelpCategories::findOne($id);
        if($category){
        $filters = Yii::$app->request->queryParams;
        if(!isset($filters['limit'])){
            $filters['limit'] = Yii::$app->params['pageSize'];
        }       
        $filters['category_id']=$id;
        $searchModel = new HelpsSearch();
        $dataProvider = $searchModel->search($filters);
        $dataProvider->pagination->pageSize = $filters['limit'];
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filters'=>$filters,
             'id' => $id
        ]);
        }else{
            throw new NotFoundHttpException('The requested page does not exist.'); 
        }     
    }

    public function actionView($id , $categoryId = '')
    {
      
        return $this->render('view', [
            'model' => $this->findModel($id),
            'categoryId' => $categoryId
          
        ]);
    }

    public function actionCreate($id)
    {
        $model = new Helps();
        if(Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post('Helps');
           $data['answer']=trim($data['answer']);
          
            $data['category_id']=Yii::$app->request->post('category_id');
            $userRepository = new HelpsRepository;
            $returnData = $userRepository->createQuestions($data);
            if($returnData['status']['success'] == 1)
            {
                return $this->redirect(['helps/index/'.$id]);
            } else {
                Yii::$app->session->setFlash('danger', $returnData['status']['message']);
            }
        }
        
        return $this->render('create', [
            'model' => $model,
            'id'=>$id
        ]);
    }

    public function actionUpdate($id , $categoryId = '')
    {      
       
        $model = $this->findModel($id);
        
        if(Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            
            $data = Yii::$app->request->post('Helps');
            $marketRepository = new HelpsRepository;
            $data['id'] = $id;
            $returnData = $marketRepository->updateQuestions($data);
           
            if($returnData['status']['success'] == 1)
            {
                parent::userActivity(array('Update Categories'));
                Yii::$app->session->setFlash('success', $returnData['status']['message']);
                return $this->redirect(['helps/index/'.$categoryId]);
            } else {
                Yii::$app->session->setFlash('danger', $returnData['status']['message']);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'id'=>$categoryId
        ]);
    }

    public function actionDelete($id , $categoryId = '')
    {
      
        $this->findModel($id)->delete();

        return $this->redirect(['helps/index/'.$categoryId]);
    }

    protected function findModel($id)
    {
        if (($model = Helps::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
