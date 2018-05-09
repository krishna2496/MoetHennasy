<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use common\helpers\CommonHelper;
use common\repository\StoreRepository;
use yii\data\ArrayDataProvider;
use common\models\StoresSearch;

class StoresController extends BaseApiController
{
    public $modelClass = 'common\models\Stores';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'ruleConfig' => [
                'class' => \common\components\AccessRule::className(),
            ],
            'rules' => [
                [
                    'actions' => ['create','list-stores'],
                    'allow' => true,
                    'roles' => ['&'],
                ],
            ],
        ];
        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['create']);
        unset($actions['list-stores']);
        return $actions;
    }

    public function actionCreate()
    {
        $currentUser = CommonHelper::getUser();
        $data = array();
        $data['id'] = Yii::$app->request->post('id');
        $data['name'] = Yii::$app->request->post('name');
        $data['storeImage'] = $data['photo'] = Yii::$app->request->post('photo');
        $data['market_id'] = Yii::$app->request->post('market_id');
        $data['market_segment_id'] = Yii::$app->request->post('market_segment_id');
        $data['address1'] = Yii::$app->request->post('address1');
        $data['address2'] = Yii::$app->request->post('address2');
        $data['country_id'] = Yii::$app->request->post('country_id');
        $data['city_id'] = Yii::$app->request->post('city_id');
        $data['province_id'] = Yii::$app->request->post('province_id');
        $data['assign_to'] = $currentUser->id;
        $data['store_manager_first_name'] = Yii::$app->request->post('store_manager_first_name');
        $data['store_manager_last_name'] = Yii::$app->request->post('store_manager_last_name');
        $data['store_manager_phone_code'] = Yii::$app->request->post('store_manager_phone_code');
        $data['store_manager_phone_number'] = Yii::$app->request->post('store_manager_phone_number');
        $data['store_manager_email'] = Yii::$app->request->post('store_manager_email');
        $data['latitude'] = Yii::$app->request->post('latitude');
        $data['longitude'] = Yii::$app->request->post('longitude');
        $data['comment'] = Yii::$app->request->post('comment');
        $storeRepository = new StoreRepository;
        if($data['id']){
            $returnData = $storeRepository->updateStore($data);
        } else {
            $returnData = $storeRepository->createStore($data);
        }
        return $returnData;
    }
    
    public function actionListStores(){
        $currentUser = CommonHelper::getUser();
        
        $data = array();
        $data['pageNumber'] = Yii::$app->request->get('pageNumber');
        $data['serachText'] = Yii::$app->request->get('serachText');
        $data['cityIdArray'] = Yii::$app->request->get('cityIdArray');
        $data['provinceIdArray'] = Yii::$app->request->get('provinceIdArray');
        $data['marketIdArray'] = Yii::$app->request->get('marketIdArray');
        $data['sort'] = Yii::$app->request->get('sort');
        $data['storeID'] = Yii::$app->request->get('storeID');
        $data['assignTo']=$currentUser->id;
        if(!isset($data['limit'])){
            $limit = Yii::$app->params['pageSize'];
        }
         $data['per-page'] = Yii::$app->params['pageSize'];
         $data['limit']=Yii::$app->params['pageSize'];  
         $data['page']=1;
         
        if(isset($data['pageNumber']) && ($data['pageNumber'] != '')){
             $data['page'] = $data['pageNumber']; 
        }
     
        if(isset($data['sort']) && ($data['sort'] != '')){
            if($data['sort'] == 'StoreName A-Z'){
                 $data['sort']='name';
            }
            if($data['sort'] == 'StoreName Z-A'){
                 $data['sort']='-name';
            }
            if($data['sort'] == 'CityName A-Z'){
                 $data['sort']='cityId';
            }
            if($data['sort'] == 'CityName Z-A'){
                 $data['sort']='-cityId';
            }
            if($data['sort'] == 'Visit Old to new'){
                 $data['sort']='id';
            }
            if($data['sort'] == 'Visit new to Old'){
                 $data['sort']='-id';
            }
            if($data['sort'] == 'distance A-Z'){
                 $data['sort']='distance';
            }
             if($data['sort'] == 'distance Z-A'){
                 $data['sort']='-distance';
            }
        }
        $_GET['sort'] = $data['sort'];
        $_GET['page'] = $data['page'];
        $storeRepository = new StoreRepository;
        $resultStoreList=$storeRepository->storeList($data);
        $storeList = array();
        if($resultStoreList['status']['success'] == 1){
            if($resultStoreList['data']['stores']){
                foreach ($resultStoreList['data']['stores'] as $key => $value) {
                    
                    $storeLatitude=$value['latitude'];
                    $storeLongitude=$value['longitude'];               
                    $userLatitude=$value['user']['latitude'];
                    $userLongitude=$value['user']['longitude'];
                    $photo=$value['photo'];
                    $userImage=$value['user']['profile_photo'];
                    unset($value['photo']);
                    unset($value[$value['user']['profile_photo']]);
                    $value['photo']=CommonHelper::getPath('upload_url').UPLOAD_PATH_STORE_IMAGES.$photo;
                    $value['user']['profile_photo']=CommonHelper::getPath('upload_url').UPLOAD_PATH_USER_IMAGES.$userImage;
                    $temp = $value;
                    $temp['assignTo'] = isset($value['user']['first_name']) ? $value['user']['first_name'].' '.$value['user']['last_name'] : '';
                    $temp['market'] = isset($value['market']['title']) ? $value['market']['title'] : '';
                    $temp['marketSegment'] = isset($value['marketSegment']['title']) ? $value['marketSegment']['title'] : '';
                    $temp['cityId'] = isset($value['city']['name']) ? $value['city']['name'] : '';
                    $temp['distance']= $this->distance($userLatitude, $userLongitude, $storeLatitude, $storeLongitude);
                    $storeList[] = $temp;
                }
            }
        }
         if(isset($data['pageNumber']) && ($data['pageNumber'] == 0)){
             $limit =count($storeList); 
        }
         $dataProvider = new ArrayDataProvider([
            'allModels' => $storeList,
            'pagination' => [
                'pageSize' => $limit
            ],
            'sort' => [
                'attributes' =>
                [
                    'id',
                    'cityId',
                    'distance',
                    'name',
                    'market',
                    'marketSegment',
                    'address1',
                    'assignTo',
                   
                ],          
            ]
             
        ]);
        $return['status']=$resultStoreList['status'];
        $return['data']['store']=$dataProvider->getModels();
        $return['total_count']=$dataProvider->getTotalCount();  
        $return['isApi']=1; 
        return $return;
        
    }
    public function distance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
          $latFrom = deg2rad($latitudeFrom);
          $lonFrom = deg2rad($longitudeFrom);
          $latTo = deg2rad($latitudeTo);
          $lonTo = deg2rad($longitudeTo);

          $lonDelta = $lonTo - $lonFrom;
          $a = pow(cos($latTo) * sin($lonDelta), 2) + pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
          $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

          $angle = atan2(sqrt($a), $b);
          return $angle * $earthRadius;
    }
}
