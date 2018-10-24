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
use common\repository\ProductCategoryRepository;
use common\repository\ProductTypesRepository;

class CataloguesController extends BaseBackendController {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => \common\components\AccessRule::className(),
                ],
                'rules' => [
                        [
                        'actions' => ['re-order','test','index', 'create', 'update', 'view', 'delete'],
                        'allow' => true,
                        'roles' => ['&'],
                    ],
                        [
                        'actions' => ['product-sub-category'],
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
    public function actionTest(){
        $cat = Catalogues::find()->asArray()->all();
//        echo '<prE>';
//        print_r($cat);exit;
        foreach ($cat as $key=>$value){         
//            echo $value['id'];exit;
          $c = Catalogues::findOne($value['id']);
          $c->reorder_id = $value['id'];
          $c->save(false);
        }
    }
    
    public function actionReOrder(){
        $data = \yii::$app->request->post();
        $current_id = $data['current_id'];
        $replaced_id= $data['replaced_id'];
        
        $currentCat = Catalogues::findOne(['reorder_id' =>$current_id]);
      
        $currentCat->reorder_id = $replaced_id;
       
         
        $replaced = Catalogues::findOne(['reorder_id' =>$replaced_id]);
        $replaced->reorder_id = $current_id;
        
//        echo '<pre>';
//        print_r($currentCat);exit;
         $currentCat->save(false);
        $replaced->save(false);
    }
    
    public function actionIndex() {
        $filters = Yii::$app->request->queryParams;

        if (!isset($filters['limit'])) {
            $filters['limit'] = Yii::$app->params['pageSize'];
        }
        $searchModel = new CataloguesSearch();
        $dataProvider = $searchModel->search($filters);
        $dataProvider->pagination->pageSize = $filters['limit'];
        $returnData  = $this->allData();
        
        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'market' => $returnData['marketList'],
                'filters' => $filters,
                'brand' => $returnData['brandList'],
                'product' => $returnData['productCategory']
        ]);
    }

    public function actionView($id) {
        parent::userActivity('view_catalogue', $description = '');
        return $this->render('view', [
                'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate() {
        $model = new Catalogues();
        $model->scenario = 'create';
        $productSubCatData = array();
        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post('Catalogues');
            $data['image'] = '';
            if (UploadedFile::getInstance($model, 'catalogueImage')) {
                $fileData = array();
                $fileData['files'][0] = UploadedFile::getInstance($model, 'catalogueImage');
                $fileData['type'] = 'catalogues';
                $uploadUrl = CommonHelper::getPath('upload_url') . $fileData['type'] . '/';
                $uploadRepository = new UploadRepository;
                $uploadData = $uploadRepository->store($fileData);
                if ($uploadData['status']['success'] == 1) {
                    $data['catalogueImage'] = $data['image'] = str_replace($uploadUrl, "", $uploadData['data']['uploadedFile'][0]['name']);
                } else {
                    return $this->redirect(['index']);
                    Yii::$app->session->setFlash('danger', $uploadData['status']['message']);
                }
            }
            $userRepository = new CataloguesRepository;
            $returnData = $userRepository->createCatalogue($data);
            if ($returnData['status']['success'] == 1) {
                parent::userActivity('create_catalogue', $description = '');
                Yii::$app->session->setFlash('success', $returnData['status']['message']);
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('danger', $returnData['status']['message']);
            }
        }
        $returnData = $this->allData();

        return $this->render('create', [
                'model' => $model,
                'market' => $returnData['marketList'],
                'brand' => $returnData['brandList'],
                'product' => $returnData['productCategory'],
                'productSubCatData' => $productSubCatData,
                'productTypeData' => $returnData['productType']
        ]);
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $product_category_id =  $model['product_category_id'];          
        if ($model->load(Yii::$app->request->post())) {
            $oldImagePath = CommonHelper::getPath('upload_path') . UPLOAD_PATH_CATALOGUES_IMAGES . $model->image;
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post('Catalogues');
            $data['id'] = $id;
            $data['image'] = '';
            if (UploadedFile::getInstance($model, 'catalogueImage')) {
                $fileData = array();
                $fileData['files'][0] = UploadedFile::getInstance($model, 'catalogueImage');
                $fileData['type'] = 'catalogues';
                $uploadUrl = CommonHelper::getPath('upload_url') . $fileData['type'] . '/';
                $uploadRepository = new UploadRepository;
                $uploadData = $uploadRepository->store($fileData);
                if ($uploadData['status']['success'] == 1) {
                    $data['image'] = str_replace($uploadUrl, "", $uploadData['data']['uploadedFile'][0]['name']);
                    if (file_exists($oldImagePath)) {
                        @unlink($oldImagePath);
                    }
                } else {
                    return $this->redirect(['index']);
                    Yii::$app->session->setFlash('danger', $uploadData['status']['message']);
                }
            }

            $userRepository = new CataloguesRepository;
            $returnData = $userRepository->updateCatalogue($data);
            if ($returnData['status']['success'] == 1) {
                parent::userActivity('update_catalogue', $description = '');
                Yii::$app->session->setFlash('success', $returnData['status']['message']);

                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('danger', $returnData['status']['message']);
            }
        }
        $returnData = $this->allData($product_category_id);
        return $this->render('update', [
                'model' => $model,
                'market' => $returnData['marketList'],
                'brand' => $returnData['brandList'],
                'product' => $returnData['productCategory'],
                'productSubCatData' => $returnData['productSubCat'],
                'productTypeData' => $returnData['productType']
        ]);
    }

    public function actionProductSubCategory($data = array()) {
        $data = Yii::$app->request->post();
        $product = new ProductCategoryRepository();
        $filter['parent_id'] = $data['product_id'];
        $product = $product->listing($filter);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $product;
    }

    public function actionDelete($id) {
        $model = $this->findModel($id);

        $ImagePath = CommonHelper::getPath('upload_path') . UPLOAD_PATH_CATALOGUES_IMAGES . $model->image;

        if ($model->delete()) {
            if (file_exists($ImagePath)) {
                @unlink($ImagePath);
            }
            parent::userActivity('delete_catalogue', $description = '');
            Yii::$app->session->setFlash('success', Yii::t('app', 'deleted_successfully', [Yii::t('app', 'catalogues')]));
        } else {
            Yii::$app->session->setFlash('danger', $model['errors']['title'][0]);
        }

        return $this->redirect(['index']);
    }

    protected function findModel($id) {
        if (($model = Catalogues::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function allData($product_category_id = 0) {
        $market = $brand = $productData = $productTypeData = $productSubCatData = array();

        $marketSearch = new MarketRepository();
        $market = $marketSearch->marketList();
        if ($market['status']['success'] == 1) {
            $market = CommonHelper::getDropdown($market['data']['markets'], ['id', 'title']);
        }

        $brand = new BrandRepository();
        $brand = $brand->listing();
        if ($brand['status']['success'] == 1) {
            $brand = CommonHelper::getDropdown($brand['data']['brand'], ['id', 'name']);
        }

        $product = new ProductCategoryRepository();
        $product = $product->listing();
        if ($product['status']['success'] == 1) {
            $productData = CommonHelper::getDropdown($product['data']['productCategories'], ['id', 'name']);
        }

        $productType = new ProductTypesRepository();
        $productType = $productType->listing();
        if ($productType['status']['success'] == 1) {
            $productTypeData = CommonHelper::getDropdown($productType['data']['productTypes'], ['id', 'title']);
        }
        $product = new ProductCategoryRepository();
        $filterData['parent_id'] =$product_category_id;
        $product = $product->listing($filterData);

        if ($product['status']['success'] == 1) {
            $productSubCatData = CommonHelper::getDropdown($product['data']['productCategories'], ['id', 'name']);
        }
        
        $returnArry = array(
            'marketList' => $market,
            'brandList' => $brand,
            'productCategory' => $productData,
            'productType' => $productTypeData,
            'productSubCat' => $productSubCatData,
        );

        return $returnArry;
    }

}
