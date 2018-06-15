<?php

namespace backend\controllers;

use Yii;
use common\models\StoreConfiguration;
use common\models\StoreConfigurationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\helpers\CommonHelper;
use common\repository\MarketBrandsRepository;
use common\repository\CataloguesRepository;
use common\repository\QuestionsRepository;
use common\repository\MarketRulesRepository;
use common\repository\MarketRepository;

/**
 * StoreConfigurationController implements the CRUD actions for StoreConfiguration model.
 */
class StoreConfigurationController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all StoreConfiguration models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StoreConfigurationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $currentUser = CommonHelper::getUser();
        $marketId = '';
        if (isset($currentUser->market_id) && ($currentUser->market_id != '')) {
            $marketId = $currentUser->market_id;
        }

        $returnData = array();
        $repository = new MarketBrandsRepository();
        if ($marketId != '') {
            $data['market_id'] = $marketId;
            $returnData = $repository->listing($data);
            $brandId = array();
            if ($returnData['status']['success'] == 1) {
                if (!empty($returnData['data']['market_brands'])) {
                    
                    foreach ($returnData['data']['market_brands'] as $key => $value) {
                        
                        $returnData['data']['marketBrands'][$key]['id'] = $value['brand']['id'];
                        $returnData['data']['marketBrands'][$key]['name'] = $value['brand']['name'];
                        $returnData['data']['marketBrands'][$key]['image'] = isset($value['brand']['image']) ? CommonHelper::getPath('upload_url').UPLOAD_PATH_BRANDS_IMAGES.$value['brand']['image'] : '';
                        $returnData['data']['marketBrands'][$key]['market_id'] = $value['market_id'];
                        $brandId[$key] = $value['brand_id'];
                    }
                }
            }

            $product = array();
            if (!empty($brandId)) {
                $productData['brand_id'] = $brandId;
                $productRepository = new CataloguesRepository();
                $product = $productRepository->listing($productData);
                if($product['status']['success'] == 1){
                     foreach ($product['data']['catalogues'] as $key => $value) {
                         $image =$product['data']['catalogues'][$key]['image'];
                        unset($product['data']['catalogues'][$key]['image']);
                        unset($product['data']['catalogues'][$key]['market']);
                        unset($product['data']['catalogues'][$key]['brand']);
                        unset($product['data']['catalogues'][$key]['productType']);
                         $product['data']['catalogues'][$key]['image'] = isset($image) && ($image != '') ? CommonHelper::getPath('upload_url').UPLOAD_PATH_CATALOGUES_IMAGES.$image :'';
                    } 
                }
                $returnData['data']['catalogues'] = $product['data']['catalogues'];
            }
        }
        unset($returnData['data']['market_brands']);
        
        $brand = array();
        if($returnData['status']['success'] == 1){
        $brand=$returnData['data']['marketBrands'];
        }
       
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'brand' => $brand
        ]);
    }

    /**
     * Displays a single StoreConfiguration model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new StoreConfiguration model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new StoreConfiguration();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing StoreConfiguration model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing StoreConfiguration model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the StoreConfiguration model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StoreConfiguration the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StoreConfiguration::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
