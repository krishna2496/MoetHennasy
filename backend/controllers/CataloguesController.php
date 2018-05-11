<?php

namespace backend\controllers;

use Yii;
use common\models\Catalogues;
use common\models\CataloguesSearch;
use common\repository\MarketRepository;
use yii\web\Controller;
use yii\web\UploadedFile;
use common\repository\CataloguesRepository;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\repository\UploadRepository;
use common\repository\BrandRepository;
use common\helpers\CommonHelper;

class CataloguesController extends BaseBackendController
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
        $searchModel = new CataloguesSearch();
        $dataProvider = $searchModel->search($filters);
        $dataProvider->pagination->pageSize = $filters['limit'];
        
        $marketSearch =new MarketRepository();
        $market=$marketSearch->marketList();
        if($market['status']['success'] == 1){
            $market = CommonHelper::getDropdown($market['data']['markets'], ['id', 'title']);
        }
        
        $brand =new BrandRepository();
        $brand=$brand->listing(); 
        if($brand['status']['success'] == 1){
            $brand = CommonHelper::getDropdown($brand['data']['brand'], ['id', 'name']);
        }
        
        parent::userActivity(array('View Catalogues'),$description='View Catalogues');
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'market' => $market,
            'filters'=>$filters,
            'brand' => $brand
        ]);
    }

    public function actionView($id)
    {
        
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Catalogues();
        $model->scenario = 'create';
        $marketSearch =new MarketRepository();
        $market=$marketSearch->marketList();
        if($market['status']['success'] == 1){
            $market = CommonHelper::getDropdown($market['data']['markets'], ['id', 'title']);
        }
        
        $brand =new BrandRepository();
        $brand=$brand->listing(); 
        if($brand['status']['success'] == 1){
            $brand = CommonHelper::getDropdown($brand['data']['brand'], ['id', 'name']);
        }

        if(Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post('Catalogues');  
            $data['image'] = '';
            if(UploadedFile::getInstance($model,'catalogueImage')) {
                $fileData = array();
                $fileData['files'][0] = UploadedFile::getInstance($model,'catalogueImage');
                $fileData['type'] = 'catalogues';
                $uploadUrl = CommonHelper::getPath('upload_url').$fileData['type'].'/';
                $uploadRepository = new UploadRepository;
                $uploadData = $uploadRepository->store($fileData);
                if($uploadData['status']['success'] == 1){
                    $data['catalogueImage'] = $data['image'] = str_replace($uploadUrl,"",$uploadData['data']['uploadedFile'][0]['name']);
                } else {
                    return $this->redirect(['index']);
                    Yii::$app->session->setFlash('danger', $uploadData['status']['message']);
                }
            }
            $userRepository = new CataloguesRepository;
            $returnData = $userRepository->createCatalogue($data);
            if($returnData['status']['success'] == 1)
            {
                parent::userActivity(array('Create Catalogues'),$description='Create Catalogues');
                Yii::$app->session->setFlash('success', $returnData['status']['message']);
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('danger', $returnData['status']['message']);
            }
        }
        
        return $this->render('create', [
            'model' => $model,
            'market'=>$market,
            'brand' => $brand
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $marketSearch =new MarketRepository();
        $market=$marketSearch->marketList();
      
        if($market['status']['success'] == 1){
            $market = CommonHelper::getDropdown($market['data']['markets'], ['id', 'title']);
        }
        
        $brand =new BrandRepository();
        $brand=$brand->listing(); 
        if($brand['status']['success'] == 1){
            $brand = CommonHelper::getDropdown($brand['data']['brand'], ['id', 'name']);
        }
        
        if ($model->load(Yii::$app->request->post())) {
            $oldImagePath = CommonHelper::getPath('upload_path').UPLOAD_PATH_CATALOGUES_IMAGES.$model->image;
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post('Catalogues');
            $data['id'] = $id;
            $data['image'] = '';
            if(UploadedFile::getInstance($model,'catalogueImage')) {
                $fileData = array();
                $fileData['files'][0] = UploadedFile::getInstance($model,'catalogueImage');
                $fileData['type'] = 'catalogues';
                $uploadUrl = CommonHelper::getPath('upload_url').$fileData['type'].'/';
                $uploadRepository = new UploadRepository;
                $uploadData = $uploadRepository->store($fileData);
                if($uploadData['status']['success'] == 1){
                    parent::userActivity(array('Update Catalogues'));
                    $data['image'] = str_replace($uploadUrl,"",$uploadData['data']['uploadedFile'][0]['name']);
                    if(file_exists($oldImagePath)){
                        @unlink($oldImagePath);
                    }
                } else {
                    return $this->redirect(['index']);
                    Yii::$app->session->setFlash('danger', $uploadData['status']['message']);
                }
            }

            $userRepository = new CataloguesRepository;
            $returnData = $userRepository->updateCatalogue($data);
            if($returnData['status']['success'] == 1)
            {
                parent::userActivity(array('Update Catalogues'),$description='Update Catalogues');
                Yii::$app->session->setFlash('success', $returnData['status']['message']);
               
                return $this->redirect(['index']);
            } else {
               Yii::$app->session->setFlash('danger', $returnData['status']['message']);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'market'=>$market,
            'brand' => $brand
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        parent::userActivity(array('Delete Catalogues'),$description='Catalogues Deleted');
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Catalogues::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
