<?php
namespace common\repository;

use Yii;
use common\helpers\CommonHelper;
use common\models\Brands;


class BrandRepository extends Repository
{
      public function listing($data = array()) {
        $this->apiCode = 1;
        $query = Brands::find();

        if(isset($data['search']) && $data['search']){
        	$data['search'] = trim($data['search']);
        	$query->andWhere(['like','name',$data['search']]);
        }

        $data = array();
        $data['brand'] = $query->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }

    public function createBrand($data = array()){
      
        $this->apiCode = 0;
        $model = new Brands;
        $model->name = $data['name'];
        $model->image = $data['image'];
       
        if($model->validate()) {
            if($model->save(false)) {
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'created_successfully', [Yii::t('app', 'brand')]);
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

    public function upadateBrand($data = array()){
        $this->apiCode = 0;
        $model = Brands::findOne($data['id']);
        if(isset($data['name'])) {
        	$model->name = $data['name'];
        }
        if($model->validate()) {
            if($model->save(false)) {
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'updated_successfully', [Yii::t('app', 'brand')]);
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