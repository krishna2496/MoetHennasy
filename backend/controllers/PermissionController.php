<?php

namespace backend\controllers;

use Yii;
use common\models\Permission;
use common\models\PermissionSearch;
use common\models\Role;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\helpers\CommonHelper;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\data\ArrayDataProvider;
use common\repository\PermissionRepository;
use common\repository\RoleRepository;

class PermissionController extends BaseBackendController
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
                        'actions' => ['index','create','update','delete','matrix','matrix-listing','roles-matrix-listing','parent-matrix'],
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
        $permissions = array();
        $data = Yii::$app->request->queryParams;
        $permissionRepository = new PermissionRepository;
        $returnData = $permissionRepository->listing($data);
        if($returnData['status']['success'] == 1){
            $permissions = $returnData['data']['permissions'];
        }
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $permissions,
            'pagination' =>  [ 
                'pageSize' => Yii::$app->params['pageSize'],
            ],
            'sort' => [
                'attributes' => [
                    'permission_label',
                    'permission_title',
                ],
            ],
        ]);
      
        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionCreate()
    {
        $model = new Permission();

        $permissions = Permission::find()->all();
        $listPermissions = ArrayHelper::map($permissions,'id','permission_title');
        $listPermissions[0] = "-Select-";

        //sorting array as 0 key added in bottom
        ksort($listPermissions);
        asort($listPermissions);
        if(Yii::$app->request->post()) {
            $data = Yii::$app->request->post('Permission');
            $userRepository = new PermissionRepository;
            $returnData = $userRepository->create($data);
            if($returnData['status']['success'] == 1)
            { 
                parent::userActivity('create_permission',$description='');
                Yii::$app->session->setFlash('success', $returnData['status']['message']);
                return $this->redirect(['index']);
            } else {
                $model->load(Yii::$app->request->post());
                Yii::$app->session->setFlash('danger', $returnData['status']['message']);
            }
        }
      
        return $this->render('create', [
            'model' => $model,
            'listPermissions' => $listPermissions,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $permissions = Permission::find()->all();
        $listPermissions = ArrayHelper::map($permissions,'id','permission_title');
        $listPermissions[0] = "-Select-";

        //sorting array as 0 key added in bottom
        ksort($listPermissions);
        asort($listPermissions);
        if(Yii::$app->request->post()) {
            $data = Yii::$app->request->post('Permission');
            $data['id'] = $id;
            $userRepository = new PermissionRepository;
            $returnData = $userRepository->update($data);
            if($returnData['status']['success'] == 1)
            {   
                parent::userActivity('update_permission',$description='');
                Yii::$app->session->setFlash('success', $returnData['status']['message']);
                return $this->redirect(['index']);
            } else {
                $model->load(Yii::$app->request->post());
                Yii::$app->session->setFlash('danger', $returnData['status']['message']);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'listPermissions' => $listPermissions,
        ]);
    }

    public function actionDelete($id)
    {
        $data = array();
        $data['id'] = $id;
        $userRepository = new PermissionRepository;
        $returnData = $userRepository->delete($data);
        if($returnData['status']['success'] == 1)
        {
            parent::userActivity('delete_permission',$description='');
            Yii::$app->session->setFlash('success', $returnData['status']['message']);
        } else {
            Yii::$app->session->setFlash('danger', $returnData['status']['message']);
        }
        return $this->redirect(['index']);
    }

    public function actionMatrix()
    {
        $data = array();
        $data = Yii::$app->request->post('permissionscheck');
        $permissionRepository = new PermissionRepository;
        $returnData = $permissionRepository->insertRole($data);
        if($returnData['status']['success'] == 1)
        {   
            Yii::$app->session->setFlash('success', $returnData['status']['message']);
        } else {
            Yii::$app->session->setFlash('danger', $returnData['status']['message']);
        }
        return $this->redirect(['matrix-listing']);
    }

    public function actionMatrixListing()
    {
        $permissions = array();
        $data = Yii::$app->request->queryParams;
        $permissionRepository = new PermissionRepository;
        $returnData = $permissionRepository->listing($data);
        if($returnData['status']['success'] == 1){
            $permissions = $returnData['data']['permissions'];
        }

        $rolesRepository = new RoleRepository;
        $roles = $rolesRepository->listing();
        $roleLabels = array();
        if($roles['status']['success'] == 1){
            $roleLabels = $roles['data']['roles'];
        }

        $objPermissions = new Permission();
        $permissionLabels = $objPermissions->getPermissionTree($permissions,$parentId=0);

        $permissionRepository = new PermissionRepository;
        $rolePermissionData = $permissionRepository->selectRolePermission();
        $checkedValArray = array();
        if($rolePermissionData['status']['success'] == 1){
            if($rolePermissionData['data']){
                foreach ($rolePermissionData['data']['permission'] as $key=>$value)
                {
                    $checkedValArray[]= $value['role_id'].",".$value['permission_id']; 
                }
            }
        }

        return $this->render('permissionMatrix', [
            'permissionLabels' => $permissionLabels,
            'roleLabels' => $roleLabels,
            'checkedValArray' => $checkedValArray,
            'permissionCount' => count($permissionLabels)
        ]);
    }

    public function actionParentMatrix()
    {
        $data = array();
        $data = Yii::$app->request->post('permissionscheck');
        $permissionRepository = new PermissionRepository;
        $returnData = $permissionRepository->insertParentRolePermission($data);
        if($returnData['status']['success'] == 1)
        {
            Yii::$app->session->setFlash('success', $returnData['status']['message']);
        } else {
            Yii::$app->session->setFlash('danger', $returnData['status']['message']);
        }
        return $this->redirect(['roles-matrix-listing']);
    }

    public function actionRolesMatrixListing()
    {
        $roleLabels = Role::find()->asArray()->all(); 

        $permissionRepository = new PermissionRepository;
        $rolePermissionData = $permissionRepository->selectParentRolePermission();
        $checkedValArray = array();
        if($rolePermissionData['status']['success'] == 1){
            if($rolePermissionData['data']){
                foreach ($rolePermissionData['data']['permission'] as $key=>$value)
                {
                    $checkedValArray[]= $value['parent_role_id'].",".$value['child_role_id']; 
                }
            }
        }

        return $this->render('parentPermissionMatrix', [
            'roleLabels' => $roleLabels,
            'checkedValArray' => $checkedValArray,
            'permissionCount' => count($roleLabels)
        ]);
    }

    protected function findModel($id)
    {
        //Type Casting
        $id = intval($id);
        
        if (($model = Permission::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
