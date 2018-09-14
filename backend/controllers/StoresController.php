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
use backend\controllers\StoresAjaxController;

class StoresController extends StoresAjaxController
{
    var $user = '';    
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->user = CommonHelper::getUser();
    }
    
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
                        'actions' => ['index','create','update','view','delete','export'],
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
        $currentUser = $this->user;
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
        
        $countries = $cities = array();
        $masterDataRepository = new MasterDataRepository();
        $countriesData = $masterDataRepository->countries();
        if($countriesData['status']['success'] == 1){
            $countries = CommonHelper::getDropdown($countriesData['data']['countries'], ['id', 'name']);
        }
 
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

    public function actionExport()  
    {
        $currentUser = $this->user;

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

        $searchModel = new StoresSearch();
        $dataProvider = $searchModel->search($filters);
        $allModels = $dataProvider->allModels;
        if($allModels){
            $exportArray[0] = ['No','Name','Market','Market Segment','Address1','Address2','Assign To','Province','City','Country','Grading','Comment','Store Manager Name','Store Manager Email','Store Manager Phone Number'];
            $i = 1;
            foreach ($allModels as $key => $value) {
              
                $temp = array();
                $temp['no'] = $i;
                $temp['name'] = $value['name'];
                $temp['market'] = $value['market'];
                $temp['marketSegment'] = $value['marketSegment'];
                $temp['address1'] = $value['address1'];
                $temp['address2'] = $value['address2'];
                $temp['assignTo'] = isset($value['user']['first_name']) ? $value['user']['first_name'].' '.$value['user']['last_name'] : '';
                $temp['province'] = isset($value['province']['name']) ? $value['province']['name'] : '';
                $temp['city'] = isset($value['city']['name']) ? $value['city']['name'] : '';
                $temp['country'] = isset($value['country']['name']) ? $value['country']['name'] : '';
                $temp['grading'] = (isset($value['grading']) && $value['grading'] != '') ? \yii::$app->params['store_grading'][$value['grading']] : '';
                $temp['comment'] = $value['comment'];
                $temp['storeManagerName'] = $value['store_manager_first_name'].' '.$value['store_manager_last_name'];
                $temp['storeManagerEmail'] = $value['store_manager_email'];
                $temp['storeManagerPhone'] = $value['store_manager_phone_code'].' '.$value['store_manager_phone_number'];
                $exportArray[$i] = $temp;
                $i++;
            }
            CommonHelper::exportFileAsCsv('stores.csv',$exportArray);
        }
    }

    public function actionView($id, $parentId = '')
    {
        $currentUser = $this->user;
        $this->checkUserAccess($currentUser, $parentId); 
        parent::userActivity('view_store',$description='');
        return $this->render('view', [
            'model' => $this->findModel($id,$parentId),
        ]);
    }

    public function actionCreate()
    {   
        $currentUser = $this->user;

        $marketFilter = $markets= $countries =array();
        if($currentUser->role_id != Yii::$app->params['superAdminRole']){
            $marketFilter['user_id'] = $currentUser->id;
        }
          
        $marketRepository = new MarketRepository();
        $marketsData = $marketRepository->marketList($marketFilter);
        if($marketsData['status']['success'] == 1){
            $markets = CommonHelper::getDropdown($marketsData['data']['markets'], ['id', 'title']);
        }
      
        $masterDataRepository = new MasterDataRepository();
        $countriesData = $masterDataRepository->countries();
        if($countriesData['status']['success'] == 1){
            $countries = CommonHelper::getDropdown($countriesData['data']['countries'], ['id', 'name']);
        }
        
        $model = new Stores();

        if (Yii::$app->request->post()) 
        {
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post('Stores');
            
            $is_create = 1;
            if($currentUser->role_id != Yii::$app->params['superAdminRole'])
            {
                $assign_to = !empty($data['assign_to'])?$data['assign_to']:'';
                
                $userObj = new User;
                $childUser = $userObj->getAllChilds(array($currentUser->id));
                $childUser[] = $currentUser->id;
                
                if(!empty($childUser) && !in_array($assign_to, $childUser))
                {
                    $is_create = 2;
                }
            }
            
            if($is_create == 1)
            {
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
                $address1 = isset($data['address1']) ? $data['address1'] : '';
                $address2 = isset($data['address2']) ? $data['address2'] : '';
                $countryName = isset($countries[$data['country_id']]) ? $countries[$data['country_id']] : '';
                $map = Yii::$app->placesSearch->text($address1.' '.$address2.' '.$countryName);           
                $data['latitude'] = $data['longitude']='';
                if(strtolower($map->status) == 'ok'){
                     $data['latitude'] = $map->results[0]->geometry->location->lat;
                     $data['longitude'] = $map->results[0]->geometry->location->lng;
                }

                $returnData = $storeRepository->createStore($data);
                if($returnData['status']['success'] == 1)
                {
                    parent::userActivity('create_store',$description='');
                    Yii::$app->session->setFlash('success', $returnData['status']['message']);
                    return $this->redirect(['index']);
                } else {

                    Yii::$app->session->setFlash('danger', $returnData['status']['message']);
                }
            }
            else
            {
                Yii::$app->session->setFlash('danger', 'Please select proper Assign To user.');
            }
        }

        return $this->render('create', [
            'model' => $model,
            'markets' => $markets,
            'countries' => $countries,
        ]);
    }

    public function actionUpdate($id, $parentId = '')
    {
        $currentUser = $this->user;
        $this->checkUserAccess($currentUser, $parentId);
        $model = $this->findModel($id,$parentId);
        $oldImagePath = CommonHelper::getPath('upload_path').UPLOAD_PATH_STORE_IMAGES.$model->photo;
        //markets
        $marketFilter = $markets =$countries = array();
        if($currentUser->role_id != Yii::$app->params['superAdminRole']){
            $marketFilter['user_id'] = $currentUser->id;
        }
        $marketRepository = new MarketRepository();
        $marketsData = $marketRepository->marketList($marketFilter);
        if($marketsData['status']['success'] == 1){
            if ($marketsData['data']['markets']) {
                $markets = CommonHelper::getDropdown($marketsData['data']['markets'], ['id', 'title']);
            }
        }
        //countries
        $masterDataRepository = new MasterDataRepository();
        $countriesData = $masterDataRepository->countries();
        if($countriesData['status']['success'] == 1){
            $countries = CommonHelper::getDropdown($countriesData['data']['countries'], ['id', 'name']);
        }

        if (Yii::$app->request->post())
        {
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post('Stores');
            
            $is_create = 1;
            if($currentUser->role_id != Yii::$app->params['superAdminRole'])
            {
                $assign_to = !empty($data['assign_to'])?$data['assign_to']:'';
                $userObj = new User;
                $childUser = $userObj->getAllChilds(array($currentUser->id));
                $childUser[] = $currentUser->id;
                if(!empty($childUser) && !in_array($assign_to, $childUser))
                {
                    $is_create = 2;
                }
            }
            
            if($is_create == 1)
            {
                $data['id'] = $id;
                $address1 = isset($data['address1']) ? $data['address1'] : '';
                $address2 = isset($data['address2']) ? $data['address2'] : '';
                $countryName = isset($countries[$data['country_id']]) ? $countries[$data['country_id']] : '';

                $map = Yii::$app->placesSearch->text($address1.' '.$address2.' '.$countryName);           
                $data['latitude'] = $data['longitude']='';
                if(strtolower($map->status) == 'ok'){
                     $data['latitude'] = $map->results[0]->geometry->location->lat;
                     $data['longitude'] = $map->results[0]->geometry->location->lng;
                }

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
                    if(isset($data['photo']) && $data['photo'] && (file_exists($oldImagePath))){
                            @unlink($oldImagePath);
                    }
                    parent::userActivity('update_store',$description='');
                    Yii::$app->session->setFlash('success', $returnData['status']['message']);
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('danger', $returnData['status']['message']);
                }
            }
            else
            {
                Yii::$app->session->setFlash('danger', 'Please select proper Assign To User.');
            }
        }

        return $this->render('update', [
            'model' => $model,
            'markets' => $markets,
            'countries' => $countries,
        ]);
    }

    public function actionDelete($id, $parentId='')
    {
        $currentUser = $this->user;
        $this->checkUserAccess($currentUser, $parentId);
        $model = $this->findModel($id,$parentId);
        if($model->delete())
        {
            Yii::$app->session->setFlash('success', Yii::t('app', 'deleted_successfully', [Yii::t('app', 'store')]));
            parent::userActivity('delete_store',$description='');
            return $this->redirect(['index']);
        }
    }

    protected function findModel($id, $parentID = '')
    {
        $currentUser = $this->user;
        $query = Stores::find()->andWhere(['id' => $id]);
        if($parentID && $currentUser->role_id != Yii::$app->params['superAdminRole'])
        {
            $userObj = new User;
            $childUser = $userObj->getAllChilds(array($currentUser->id));
            $childUser[] = $currentUser->id;
            $query->andWhere(['assign_to' => $childUser]);
        }

        $model = $query->one();
        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    protected function checkUserAccess($currentUser,$parentId){
        if(!$parentId && $currentUser->role_id != Yii::$app->params['superAdminRole']){
            $parentId = $currentUser->id;
        }
        else 
        {
            if($currentUser->role_id != Yii::$app->params['superAdminRole']){
                $this->findModel($parentId,$currentUser->id);
            }
        }
    }
    
    protected function allData(){
        
    }
}
