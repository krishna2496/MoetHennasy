<?php
namespace common\repository;

use Yii;
use common\helpers\CommonHelper;
use common\models\ProductCategories;


class ProductCategoryRepository extends Repository
{
    public function listing($data = array()) {
        $this->apiCode = 1;
        $query = ProductCategories::find()->with(['parentCategory']);

        if(isset($data['search']) && $data['search']){
            $data['search'] = trim($data['search']);
            $query->andWhere(['like','name',$data['search']]);
        }

        if(isset($data['except_id']) && $data['except_id']){
        	$query->andWhere(['!=','id',$data['except_id']]);
        }
        
        if(isset($data['parent_id']) && $data['parent_id']){
            	$query->andWhere(['=','parent_id',$data['parent_id']]);
        }

        $data = array();
        $data['productCategories'] = $query->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }

    public function createProductCategory($data = array()){
        $this->apiCode = 0;
        $model = new ProductCategories;
        $model->name = $data['name'];
        $model->parent_id = $data['parent_id'];
        if($model->validate()) {
            if($model->save(false)) {
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'created_successfully', [Yii::t('app', 'product_categories')]);
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

    public function upadateProductCategory($data = array()){
        $this->apiCode = 0;
        $model = ProductCategories::findOne($data['id']);
        if(isset($data['name'])) {
            $model->name = $data['name'];
        }
        if(isset($data['parent_id'])) {
        	$model->parent_id = $data['parent_id'];
        }
        if($model->validate()) {
            if($model->save(false)) {
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'updated_successfully', [Yii::t('app', 'product_categories')]);
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