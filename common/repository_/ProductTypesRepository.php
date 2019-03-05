<?php
namespace common\repository;

use Yii;
use common\helpers\CommonHelper;
use common\models\ProductTypes;


class ProductTypesRepository extends Repository
{
    public function listing($data = array()) {
        $this->apiCode = 1;
        $query = ProductTypes::find();

        if(isset($data['search']) && $data['search']){
            $data['search'] = trim($data['search']);
            $query->andWhere(['like','title',$data['search']]);
        }
        $data = array();
        $data['productTypes'] = $query->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }

    public function createProductTypes($data = array()){
      
        $this->apiCode = 0;
        $model = new ProductTypes;
        $model->title = $data['title'];
       
        if($model->validate()) {
            if($model->save(false)) {
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'created_successfully', [Yii::t('app', 'product_type')]);
            } else {
                $this->apiCode = 0;
                $this->apiMessage = Yii::t('app', 'Something went wrong.');
            }
        } else {
            $this->apiCode = 0;
            if(isset($model->errors) && $model->errors){
                $this->apiMessage = $model->errors;
            }
        }

        return $this->response();
    }

    public function updateProductTypes($data = array()){
        $this->apiCode = 0;
        $model = ProductTypes::findOne($data['id']);
        if(isset($data['title'])) {
            $model->title = $data['title'];
        }
      
        if($model->validate()) {
            if($model->save(false)) {
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'updated_successfully', [Yii::t('app', 'product_type')]);
            } else {
                $this->apiCode = 0;
                $this->apiMessage = Yii::t('app', 'Something went wrong.');
            }
        } else {
            $this->apiCode = 0;
            if(isset($model->errors) && $model->errors){
                $this->apiMessage = $model->errors;
            }
        }

        return $this->response();
    }

}