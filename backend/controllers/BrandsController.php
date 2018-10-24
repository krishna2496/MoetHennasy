<?php

namespace backend\controllers;

use Yii;
use common\models\Brands;
use common\models\BrandsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use common\helpers\CommonHelper;
use common\repository\UploadRepository;
use yii\filters\AccessControl;
use common\repository\BrandRepository;

class BrandsController extends BaseBackendController
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
                        'actions' => ['re-order','index','create','update','view','delete','test'],
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
        $searchModel = new BrandsSearch();
        $dataProvider = $searchModel->search($filters);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filters' => $filters,
        ]);
    }

    public function actionView($id)
    {
        parent::userActivity('view_brand',$description='');
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Brands();

        if(Yii::$app->request->post()) 
        {
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post('Brands');
            $data['image'] = '';
            
            if(UploadedFile::getInstance($model,'brandImage')) 
            {
                $fileData = array();
                $fileData['files'][0] = UploadedFile::getInstance($model,'brandImage');
                $fileData['type'] = 'brands';
                $uploadUrl = CommonHelper::getPath('upload_url').$fileData['type'].'/';
                $uploadRepository = new UploadRepository();
                $uploadData = $uploadRepository->store($fileData);
                if($uploadData['status']['success'] == 1){
                    $data['brandImage'] = $data['image'] = str_replace($uploadUrl,"",$uploadData['data']['uploadedFile'][0]['name']);
                } else {
                    return $this->redirect(['index']);
                    Yii::$app->session->setFlash('danger', $uploadData['status']['message']);
                }
            }
            $brandRepository = new BrandRepository();
            $brandData = $brandRepository->createBrand($data); 
            if($brandData['status']['success'] == 1)
            {   parent::userActivity('create_brand',$description='');
                Yii::$app->session->setFlash('success', $brandData['status']['message']);
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('danger', $brandData['status']['message']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->post())
        {          
            $oldImagePath = CommonHelper::getPath('upload_path').UPLOAD_PATH_BRANDS_IMAGES.$model->image;
            
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post('Brands');
            $data['id'] = $id;
            
            if(UploadedFile::getInstance($model,'brandImage')) 
            {
                $fileData = array();
                $fileData['files'][0] = UploadedFile::getInstance($model,'brandImage');
                $fileData['type'] = 'brands';
                $uploadUrl = CommonHelper::getPath('upload_url').$fileData['type'].'/';
                $uploadRepository = new UploadRepository();
                $uploadData = $uploadRepository->store($fileData);
                if($uploadData['status']['success'] == 1)
                {
                    $data['brandImage'] = $data['image'] = str_replace($uploadUrl,"",$uploadData['data']['uploadedFile'][0]['name']);
                    if(file_exists($oldImagePath)){
                        @unlink($oldImagePath);
                    }
                }
                else 
                {
                    return $this->redirect(['index']);
                    Yii::$app->session->setFlash('danger', $uploadData['status']['message']);
                }
            }
            $brandRepository = new BrandRepository();
            $brandData = $brandRepository->upadateBrand($data); 
            if($brandData['status']['success'] == 1)
            {   parent::userActivity('update_brand',$description='');
                Yii::$app->session->setFlash('success', $brandData['status']['message']);
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('danger', $brandData['status']['message']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {  
        $model = $this->findModel($id);
    
        $ImagePath = CommonHelper::getPath('upload_path').UPLOAD_PATH_BRANDS_IMAGES.$model->image;
        
        if($model->delete())
        {
            if(file_exists($ImagePath))
            {
                @unlink($ImagePath);
            }
            parent::userActivity('delete_brand',$description='');
            Yii::$app->session->setFlash('success', Yii::t('app', 'deleted_successfully', [Yii::t('app', 'brand')]));
        }
        else
        {
            Yii::$app->session->setFlash('danger', $model['errors']['title'][0]);
        }
        
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Brands::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
