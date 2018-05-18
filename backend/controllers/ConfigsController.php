<?php

namespace backend\controllers;

use Yii;
use common\models\Configs;
use common\models\ConfigsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Stores;
use yii\filters\AccessControl;
use common\repository\ConfigsRepository;
/**
 * ConfigsController implements the CRUD actions for Configs model.
 */
class ConfigsController extends BaseBackendController
{
    /**
     * @inheritdoc
     */
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

    /**
     * Lists all Configs models.
     * @return mixed
     */
    public function actionIndex($id)
    {
//        exit("sdg");
        $store=Stores::findOne($id);
        if($store){
        $filters = Yii::$app->request->queryParams;
        if(!isset($filters['limit'])){
            $filters['limit'] = Yii::$app->params['pageSize'];
        }       
        $filters['store_id']=$id;
        $searchModel = new ConfigsSearch();
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

    public function actionView($id, $storeId = '')
    {
        parent::userActivity('view_configs',$description='');
        return $this->render('view', [
            'model' => $this->findModel($id),
            'storeId' => $storeId
          
        ]);
    }

    public function actionCreate($id)
    {
        $model = new Configs();
        if(Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post('Configs');
            $data['store_id']=Yii::$app->request->post('store_id');
            $repository = new ConfigsRepository;
            $returnData = $repository->createConfigs($data);
            if($returnData['status']['success'] == 1)
            {   parent::userActivity('create_configs',$description='');
                Yii::$app->session->setFlash('success', $returnData['status']['message']);
                return $this->redirect(['configs/index/'.$id]);
            } else {
                Yii::$app->session->setFlash('danger', $returnData['status']['message']);
            }
        }
        
        return $this->render('create', [
            'model' => $model,
            'id'=>$id
        ]);
    }

    public function actionUpdate($id, $storeId = '')
    {
        $model = $this->findModel($id);
        
        if(Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            
            $data = Yii::$app->request->post('Configs');
            $repository = new ConfigsRepository;
            $data['id'] = $id;
            $returnData = $repository->updateConfigs($data);
           
            if($returnData['status']['success'] == 1)
            {   
                parent::userActivity('update_configs',$description='');
                Yii::$app->session->setFlash('success', $returnData['status']['message']);
                return $this->redirect(['configs/index/'.$storeId]);
            } else {
                Yii::$app->session->setFlash('danger', $returnData['status']['message']);
            }
        }
      
        return $this->render('update', [
            'model' => $model,
            'id'=>$storeId
        ]);
    }

    public function actionDelete($id,$storeId = '')
    {  
        $this->findModel($id)->delete();
        parent::userActivity('delete_configs',$description='');
        Yii::$app->session->setFlash('success', Yii::t('app', 'deleted_successfully', [Yii::t('app', 'configs')]));
        return $this->redirect(['configs/index/'.$storeId]);
    }

    protected function findModel($id)
    {
        if (($model = Configs::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
