<?php

namespace backend\controllers;

use Yii;
use common\models\Rules;
use common\models\RulesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\repository\UploadRepository;
use common\repository\RulesRepository;
use common\helpers\CommonHelper;
use yii\web\UploadedFile;


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
            
            $data['image'] = '';
            if(UploadedFile::getInstance($model,'ruleImage')) {
                $fileData = array();
                $fileData['files'][0] = UploadedFile::getInstance($model,'ruleImage');
                $fileData['type'] = 'rules';
                $uploadUrl = CommonHelper::getPath('upload_url').$fileData['type'].'/';
                $uploadRepository = new UploadRepository;
                $uploadData = $uploadRepository->store($fileData);
                if($uploadData['status']['success'] == 1){
                    $data['ruleImage'] = $data['image'] = str_replace($uploadUrl,"",$uploadData['data']['uploadedFile'][0]['name']);
                } else {
                    return $this->redirect(['index']);
                    Yii::$app->session->setFlash('danger', $uploadData['status']['message']);
                }
            }
            
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
            $data['image'] = '';
            if(UploadedFile::getInstance($model,'ruleImage')) {
                $oldImagePath = CommonHelper::getPath('upload_path').UPLOAD_PATH_RULES_IMAGES.$model->image;
                $fileData = array();
                $fileData['files'][0] = UploadedFile::getInstance($model,'ruleImage');
                $fileData['type'] = 'rules';
                $uploadUrl = CommonHelper::getPath('upload_url').$fileData['type'].'/';
                $uploadRepository = new UploadRepository;
                $uploadData = $uploadRepository->store($fileData);
                if($uploadData['status']['success'] == 1){
                    $data['image'] = str_replace($uploadUrl,"",$uploadData['data']['uploadedFile'][0]['name']);
                    if(file_exists($oldImagePath)){
                        @unlink($oldImagePath);
                    }
                } else {
                    return $this->redirect(['index']);
                    Yii::$app->session->setFlash('danger', $uploadData['status']['message']);
                }
            }
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
        $model = $this->findModel($id);
    
        $ImagePath = CommonHelper::getPath('upload_path').UPLOAD_PATH_RULES_IMAGES.$model->image;
        
        if($model->delete())
        {
            if(file_exists($ImagePath))
            {
                @unlink($ImagePath);
            }
            parent::userActivity('delete_rule',$description='');
            Yii::$app->session->setFlash('success', Yii::t('app', 'deleted_successfully', [Yii::t('app', 'rules')]));
        }
        else
        {
            Yii::$app->session->setFlash('danger', $model['errors']['title'][0]);
        }
        
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
