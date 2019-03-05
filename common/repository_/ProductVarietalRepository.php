<?php
namespace common\repository;

use Yii;
use common\helpers\CommonHelper;
use common\models\ProductVarietal;


class ProductVarietalRepository extends Repository
{
    public function listing($data = array()) {
        $this->apiCode = 1;
        $query = ProductVarietal::find();
        
        if(isset($data['search']) && $data['search']){
            $data['search'] = trim($data['search']);
            $query->andWhere(['like','name',$data['search']]);
        }
        
        if(isset($data['except_id']) && $data['except_id']){
        	$query->andWhere(['!=','id',$data['except_id']]);
        }

        $data = array();
        $data['productVarietal'] = $query->orderBy(['name' => yii::$app->params['defaultSorting']])->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }

    public function createProductVarietal($data = array()){
        $this->apiCode = 0;
        $model = new ProductVarietal;
        $model->name = $data['name'];
        if($model->validate()) {
            if($model->save(false)) {
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'created_successfully', [Yii::t('app', 'product_varietal')]);
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

    public function upadateProductVarietal($data = array()){
        $this->apiCode = 0;
        $model = ProductVarietal::findOne($data['id']);
        if(isset($data['name'])) {
            $model->name = $data['name'];
        }
        if($model->validate()) {
            if($model->save(false)) {
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'updated_successfully', [Yii::t('app', 'product_varietal')]);
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