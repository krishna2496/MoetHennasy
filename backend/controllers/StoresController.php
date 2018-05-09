<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use common\models\Stores;
use common\models\StoresSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use common\helpers\CommonHelper;
use common\repository\MarketRepository;
use common\repository\MasterDataRepository;
use common\repository\MarketSegmentsRepository;
use common\repository\UserRepository;
use common\repository\StoreRepository;
use common\repository\UploadRepository;

class StoresController extends BaseBackendController
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
                        'actions' => ['list-stores','index','create','update','view','delete'],
                        'allow' => true,
                        'roles' => ['&'],
                    ],
                    [
                        'actions' => ['ajax-get-segment','ajax-get-user','ajax-get-city','ajax-get-province'],
                        'allow' => true,
                        'roles' => ['@'],
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
     return $this->redirect(['stores/list-stores']);
    }
 
    public function actionListStores()
    {
        $currentUser = CommonHelper::getUser();

        //filters
        $filters = Yii::$app->request->queryParams;
        if(!isset($filters['limit'])){
            $filters['limit'] = Yii::$app->params['pageSize'];
        }
        if($currentUser->role_id != Yii::$app->params['superAdminRole']){
            $userObj = new User;
            $childUser = $userObj->getAllChilds(array($currentUser->id));
            $childUser[] = $currentUser->id;
            $filters['assign_to'] = $childUser;
        }

        //markets
        $marketFilter = array();
        if($currentUser->role_id != Yii::$app->params['superAdminRole']){
            $marketFilter['user_id'] = $currentUser->id;
        }
        $markets = $marketSegments = array();
        $marketRepository = new MarketRepository();
        $marketsData = $marketRepository->marketList($marketFilter);
        if($marketsData['status']['success'] == 1){
            if($marketsData['data']['markets']){
                $markets = CommonHelper::getDropdown($marketsData['data']['markets'], ['id', 'title']);
                foreach ($marketsData['data']['markets'] as $key => $value) {
                    if($value['marketSegmentData'] && isset($filters['market_id']) && $value['id'] == $filters['market_id']){
                        foreach ($value['marketSegmentData'] as $segmentKey => $segmentValue) {
                            if(isset($segmentValue['marketSegment']['id'])){
                                $marketSegments[$segmentValue['marketSegment']['id']] = $segmentValue['marketSegment']['title'];
                            }
                        }
                    }
                }
            }
        }

        //countries
        $countries = array();
        $masterDataRepository = new MasterDataRepository();
        $countriesData = $masterDataRepository->countries();
        if($countriesData['status']['success'] == 1){
            $countries = CommonHelper::getDropdown($countriesData['data']['countries'], ['id', 'name']);
        }

        //cities
        $cities = array();
        $citiesData = $masterDataRepository->cities($filters);
        if($citiesData['status']['success'] == 1){
            $cities = CommonHelper::getDropdown($citiesData['data']['cities'], ['id', 'name']);
        }


        $searchModel = new StoresSearch();
        $dataProvider = $searchModel->search($filters);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'markets' => $markets,
            'filters' => $filters,
            'marketSegments' => $marketSegments,
            'countries' => $countries,
            'cities' => $cities,
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
        $currentUser = CommonHelper::getUser();

        //markets
        $marketFilter = array();
        if($currentUser->role_id != Yii::$app->params['superAdminRole']){
            $marketFilter['user_id'] = $currentUser->id;
        }
        $markets = array();
        $marketSegments = array();
        $marketRepository = new MarketRepository();
        $marketsData = $marketRepository->marketList($marketFilter);
        if($marketsData['status']['success'] == 1){
            $markets = CommonHelper::getDropdown($marketsData['data']['markets'], ['id', 'title']);
        }

        //countries
        $countries = array();
        $masterDataRepository = new MasterDataRepository();
        $countriesData = $masterDataRepository->countries();
        if($countriesData['status']['success'] == 1){
            $countries = CommonHelper::getDropdown($countriesData['data']['countries'], ['id', 'name']);
        }

        $model = new Stores();

        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post('Stores');
            $data['photo'] = '';
            if(UploadedFile::getInstance($model,'storeImage')) {
                $fileData = array();
                $fileData['files'][0] = UploadedFile::getInstance($model,'storeImage');
                $fileData['type'] = 'stores';
                $uploadUrl = CommonHelper::getPath('upload_url').$fileData['type'].'/';
                $uploadRepository = new UploadRepository;
                $uploadData = $uploadRepository->store($fileData);
                if($uploadData['status']['success'] == 1){
                    $data['storeImage'] = $data['photo'] = str_replace($uploadUrl,"",$uploadData['data']['uploadedFile'][0]['name']);
                } else {
                    return $this->redirect(['index']);
                    Yii::$app->session->setFlash('danger', $uploadData['status']['message']);
                }
            }

            $storeRepository = new StoreRepository;
            $returnData = $storeRepository->createStore($data);
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
            'markets' => $markets,
            'countries' => $countries,
        ]);
    }

    public function actionUpdate($id)
    {
        $currentUser = CommonHelper::getUser();
        $model = $this->findModel($id);
        $oldImagePath = CommonHelper::getPath('upload_path').UPLOAD_PATH_STORE_IMAGES.$model->photo;
        //markets
        $marketFilter = array();
        if($currentUser->role_id != Yii::$app->params['superAdminRole']){
            $marketFilter['user_id'] = $currentUser->id;
        }
        $markets = array();
        $marketRepository = new MarketRepository();
        $marketsData = $marketRepository->marketList($marketFilter);
        if($marketsData['status']['success'] == 1){
            if ($marketsData['data']['markets']) {
                $markets = CommonHelper::getDropdown($marketsData['data']['markets'], ['id', 'title']);
            }
        }

        //countries
        $countries = array();
        $masterDataRepository = new MasterDataRepository();
        $countriesData = $masterDataRepository->countries();
        if($countriesData['status']['success'] == 1){
            $countries = CommonHelper::getDropdown($countriesData['data']['countries'], ['id', 'name']);
        }

        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post('Stores');
            $data['id'] = $id;
            if(UploadedFile::getInstance($model,'storeImage')) {
                $fileData = array();
                $fileData['files'][0] = UploadedFile::getInstance($model,'storeImage');
                $fileData['type'] = 'stores';
                $uploadUrl = CommonHelper::getPath('upload_url').$fileData['type'].'/';
                $uploadRepository = new UploadRepository;
                $uploadData = $uploadRepository->store($fileData);
                if($uploadData['status']['success'] == 1){
                    $data['photo'] = str_replace($uploadUrl,"",$uploadData['data']['uploadedFile'][0]['name']);
                } else {
                    return $this->redirect(['index']);
                    Yii::$app->session->setFlash('danger', $uploadData['status']['message']);
                }
            }

            $storeRepository = new StoreRepository;
            $returnData = $storeRepository->updateStore($data);
            if($returnData['status']['success'] == 1)
            {
                if(isset($data['photo']) && $data['photo']){
                    if(file_exists($oldImagePath)){
                        @unlink($oldImagePath);
                    }
                }
                Yii::$app->session->setFlash('success', $returnData['status']['message']);
                return $this->redirect(['index']);
            } else {

                Yii::$app->session->setFlash('danger', $returnData['status']['message']);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'markets' => $markets,
            'countries' => $countries,
        ]);
    }

    public function actionDelete($id)
    {
        if($this->findModel($id)->delete()){
            Yii::$app->session->setFlash('success', Yii::t('app', 'deleted_successfully', [Yii::t('app', 'store')]));

            return $this->redirect(['index']);
        }
    }

    public function actionAjaxGetSegment()
    {
        $data = Yii::$app->request->post();
        $marketRepository = new MarketRepository();
        $returnData = $marketRepository->segmentList($data);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $returnData;
    }

    public function actionAjaxGetUser()
    {
        $currentUser = CommonHelper::getUser();
        $data = Yii::$app->request->post();
        if($currentUser->role_id != Yii::$app->params['superAdminRole']){
            $userObj = new User;
            $childUser = $userObj->getAllChilds(array($currentUser->id));
            $childUser[] = $currentUser->id;
            $data['parent_user_id'] = $childUser;
        }
        $userRepository = new UserRepository;
        $returnData = $userRepository->userList($data);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $returnData;
    }

    public function actionAjaxGetCity()
    {
        $data = Yii::$app->request->post();
        $userRepository = new MasterDataRepository;
        $returnData = $userRepository->cities($data);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $returnData;
    }

    public function actionAjaxGetProvince()
    {
        $data = Yii::$app->request->post();
        $userRepository = new MasterDataRepository;
        $returnData = $userRepository->provinces($data);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $returnData;
    }

    protected function findModel($id)
    {
        if (($model = Stores::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
