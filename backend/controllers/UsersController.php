<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use common\models\UserSearch;
use common\models\ParentRolePermission;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use common\repository\RoleRepository;
use common\repository\UserRepository;
use common\repository\UploadRepository;
use common\repository\MarketRepository;
use common\helpers\CommonHelper;

class UsersController extends BaseBackendController
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
                    [
                        'actions' => ['ajax-get-users'],
                        'allow' => true,
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

    public function actionIndex($id = '')
    {
        $currentUser = CommonHelper::getUser();
        
        //roles
        $roles = array();
        $parentRoleModel = new ParentRolePermission();
        $parentRoles = $parentRoleModel->getAllParentPermission();
        if(isset($parentRoles[$currentUser->role_id])){
            $roles = $parentRoles[$currentUser->role_id];
        }

        $filters = Yii::$app->request->queryParams;
        if(!isset($filters['limit'])){
            $filters['limit'] = Yii::$app->params['pageSize'];
        }

        //markets
        $marketFilter = array();
        if($currentUser->role_id != Yii::$app->params['superAdminRole']){
            $marketFilter['user_id'] = $currentUser->id;
        }
        $markets = array();
        $marketRepository = new MarketRepository();
        $marketsData = $marketRepository->marketList($marketFilter);
        if($marketsData['status']['success'] == 1){
            $markets = CommonHelper::getDropdown($marketsData['data']['markets'], ['id', 'title']);
        }


        $hasChild = false;
        if($currentUser->role_id != Yii::$app->params['superAdminRole']){
            $roleId = $currentUser->role_id;
            $filters['parent_user_id'] = $currentUser->id;
            $hasChild = true;
            if($id){
                $userDetail = $this->findModel($id);
                if($userDetail->parent_user_id == $currentUser->id){
                    $roleId = $userDetail->role_id;
                    $filters['parent_user_id'] = $id;
                    $filters['setParentID'] = true;
                    $hasChild = false;
                    unset($filters['id']);
                }
            }
        }
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($filters);
        $dataProvider->pagination->pageSize = $filters['limit'];

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'filters' => $filters,
            'hasChild' => $hasChild,
            'roles' => $roles,
            'markets' => $markets,
        ]);
    }

    public function actionCreate()
    {
        $currentUser = CommonHelper::getUser();
        $userList = array();
        $roles = array();
        $parentUserClass = 'hideParentUser'; 
        
        $model = new User();
        $model->scenario = 'create';

        //roles
        $parentRoleModel = new ParentRolePermission();
        $parentRoles = $parentRoleModel->getAllParentPermission();
        if(isset($parentRoles[$currentUser->role_id])){
            $roles = $parentRoles[$currentUser->role_id];
        }

        //markets
        $marketFilter = array();
        if($currentUser->role_id != Yii::$app->params['superAdminRole']){
            $marketFilter['user_id'] = $currentUser->id;
        }
        $markets = array();
        $marketRepository = new MarketRepository();
        $marketsData = $marketRepository->marketList($marketFilter);
        if($marketsData['status']['success'] == 1){
            $markets = CommonHelper::getDropdown($marketsData['data']['markets'], ['id', 'title']);
        }

        if(Yii::$app->request->post()) {          
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post('User');
            $data['device_type'] = Yii::$app->params['deviceType']['web'];
            $data['profile_photo'] = '';
            if(UploadedFile::getInstance($model,'userImage')) {
                $fileData = array();
                $fileData['files'][0] = UploadedFile::getInstance($model,'userImage');
                $fileData['type'] = 'profile';
                $uploadUrl = CommonHelper::getPath('upload_url').$fileData['type'].'/';
                $uploadRepository = new UploadRepository;
                $uploadData = $uploadRepository->store($fileData);
                if($uploadData['status']['success'] == 1){
                    $data['userImage'] = $data['profile_photo'] = str_replace($uploadUrl,"",$uploadData['data']['uploadedFile'][0]['name']);
                } else {
                    return $this->redirect(['index']);
                    Yii::$app->session->setFlash('danger', $uploadData['status']['message']);
                }
            }

            $userRepository = new UserRepository;
            $returnData = $userRepository->createUser($data);
            if($returnData['status']['success'] == 1)
            {
                parent::userActivity('create_users',$description='');
                Yii::$app->session->setFlash('success', $returnData['status']['message']);
                return $this->redirect(['index']);
            } else {
                if($model->role_id != Yii::$app->params['marketAdministratorRole']){
                    $userListeFilter = array();
                    $userListeFilter['role_id'] = $model->role_id;
                    $resultUserList = $this->actionAjaxGetUsers($userListeFilter);
                    if($resultUserList['status']['success'] == 1){
                        $userList = CommonHelper::getDropdown($resultUserList['data']['users'], ['id', ['first_name','last_name']]);
                    }
                    $parentUserClass = '';
                }
                Yii::$app->session->setFlash('danger', $returnData['status']['message']);
            }
        }


        return $this->render('create', [
            'model' => $model,
            'roles' => $roles,
            'parentUserClass' => $parentUserClass,
            'userList' => $userList,
            'markets' => $markets,
        ]);
    }

    public function actionAjaxGetUsers($data = array()){
        $userRepository = new UserRepository;
        $isJson = 1;
        if($data) {
            $isJson = 0;
        } else {
            $data = Yii::$app->request->post();
        }

        $currentUser = CommonHelper::getUser();

        $parentRoleModel = new ParentRolePermission();
        $parentRoles = $parentRoleModel->getParentRole();

        if($currentUser->role_id != Yii::$app->params['superAdminRole']){
            if($data['role_id'] == Yii::$app->params['salesManagerRole']){
                $data['id'] = $currentUser->id;
            } else {
                $data['parent_user_id'] = $currentUser->id;
            }
        }

        $data['role_id'] = isset($parentRoles[$data['role_id']]) ? $parentRoles[$data['role_id']] : '';

        $returnData = $userRepository->userList($data);
        if($isJson){
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        }
        return $returnData;
    }

    public function actionUpdate($id, $parentId = '')
    {
        $parentUpdate = false;
        $currentUser = CommonHelper::getUser();
        if(!$parentId && $currentUser->role_id != Yii::$app->params['superAdminRole']){
            $parentId = $currentUser->id;
        } else {
            $parentUpdate = true;
            if($currentUser->role_id != Yii::$app->params['superAdminRole']){
                $this->findModel($parentId,$currentUser->id);
            }
        }

        $userList = array();
        $roles = array();
        $parentUserClass = 'hideParentUser'; 
        $userRepository = new UserRepository;
        
        $model = $this->findModel($id,$parentId);
        $model->scenario = 'update';

        //roles
        $parentRoleModel = new ParentRolePermission();
        $parentRoles = $parentRoleModel->getAllParentPermission();
        if(isset($parentRoles[$currentUser->role_id])){
            $roles = $parentRoles[$currentUser->role_id];
        }

        //markets
        $marketFilter = array();
        if($currentUser->role_id != Yii::$app->params['superAdminRole']){
            $marketFilter['user_id'] = $currentUser->id;
        }
        $markets = array();
        $marketRepository = new MarketRepository();
        $marketsData = $marketRepository->marketList($marketFilter);
        if($marketsData['status']['success'] == 1){
            $markets = CommonHelper::getDropdown($marketsData['data']['markets'], ['id', 'title']);
        }

        if($model->role_id != Yii::$app->params['marketAdministratorRole']){
            $userListeFilter = array();
            $userListeFilter['role_id'] = $model->role_id;
            $resultUserList = $this->actionAjaxGetUsers($userListeFilter);
            if($resultUserList['status']['success'] == 1){
                $userList = CommonHelper::getDropdown($resultUserList['data']['users'], ['id', ['first_name','last_name']]);
            }
            $parentUserClass = '';
        }

        if(Yii::$app->request->post()) {
            $oldImagePath = CommonHelper::getPath('upload_path').UPLOAD_PATH_USER_IMAGES.$model->profile_photo;
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post('User');
            $data['id'] = $id;
            $data['device_type'] = Yii::$app->params['deviceType']['web'];
            
            $data['profile_photo'] = '';
            if(UploadedFile::getInstance($model,'userImage')) {
                $fileData = array();
                $fileData['files'][0] = UploadedFile::getInstance($model,'userImage');
                $fileData['type'] = 'profile';
                $uploadUrl = CommonHelper::getPath('upload_url').$fileData['type'].'/';
                $uploadRepository = new UploadRepository;
                $uploadData = $uploadRepository->store($fileData);
                if($uploadData['status']['success'] == 1){
                    $data['profile_photo'] = str_replace($uploadUrl,"",$uploadData['data']['uploadedFile'][0]['name']);
                    if(file_exists($oldImagePath)){
                        @unlink($oldImagePath);
                    }
                } else {
                    return $this->redirect(['index']);
                    Yii::$app->session->setFlash('danger', $uploadData['status']['message']);
                }
            }

            $userRepository = new UserRepository;
            $returnData = $userRepository->updateUser($data);
            if($returnData['status']['success'] == 1)
            {
                parent::userActivity('update_users',$description='');
                Yii::$app->session->setFlash('success', $returnData['status']['message']);
                if($parentUpdate){
                    return $this->redirect(['users/index/'.$parentId]);    
                }
                return $this->redirect(['index']);
            } else {
                if($model->role_id != Yii::$app->params['marketAdministratorRole']){
                    $userListeFilter = array();
                    $userListeFilter['role_id'] = $model->role_id;
                    $resultUserList = $this->actionAjaxGetUsers($userListeFilter);
                    if($resultUserList['status']['success'] == 1){
                        $userList = CommonHelper::getDropdown($resultUserList['data']['users'], ['id', ['first_name','last_name']]);
                    }
                    $parentUserClass = '';
                }
                Yii::$app->session->setFlash('danger', $returnData['status']['message']);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'roles' => $roles,
            'parentUserClass' => $parentUserClass,
            'userList' => $userList,
            'markets' => $markets,
        ]);
    }

    public function actionView($id, $parentId = '')
    {
        $currentUser = CommonHelper::getUser();
        $isUpdateParent = false;
        if(!$parentId && $currentUser->role_id != Yii::$app->params['superAdminRole']){
            $parentId = $currentUser->id;
        } else {
            $isUpdateParent = true;
            if($currentUser->role_id != Yii::$app->params['superAdminRole']){
                $this->findModel($parentId,$currentUser->id);
            }
        }
        parent::userActivity('view_user',$description='');
        return $this->render('view', [
            'model' => $this->findModel($id,$parentId),
            'parentId' => $parentId,
            'isUpdateParent' => $isUpdateParent,
        ]);
    }

    public function actionDelete($id, $parentId = '')
    {   
        $model = $this->findModel($id);
        if($model->delete()){
            Yii::$app->session->setFlash('success', Yii::t('app', 'deleted_successfully', [Yii::t('app', 'user')]));
            parent::userActivity('delete_user',$description='');
            if($parentId){
                return $this->redirect(['users/index/'.$parentId]);    
            }
        }else{
            Yii::$app->session->setFlash('danger', $model['errors']['title'][0]);
        }
        return $this->redirect(['index']);
    }

    protected function findModel($id,$parentID = '')
    {
        $currentUser = CommonHelper::getUser();
        $query = User::find()
            ->andWhere(['!=','role_id',Yii::$app->params['superAdminRole']])
            ->andWhere(['id' => $id]);

        if($parentID){
            $query->andWhere(['parent_user_id' => $parentID]);
        }

        $model = $query->one();
        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
