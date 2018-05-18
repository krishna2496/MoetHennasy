<?php
namespace common\repository;

use Yii;
use common\helpers\CommonHelper;
use common\models\Rules;


class RulesRepository extends Repository
{
      public function listing($data = array()) {
        $this->apiCode = 1;
        $query = Rules::find();

        if(isset($data['search']) && $data['search']){
        	$data['search'] = trim($data['search']);
        	if(isset($data['search']) && $data['search']){
                        $search = trim($data['search']);       
                        $query->andWhere([
                            'or',
                            ['like', 'type', $search],
                            ['like', 'product_fields', $search],
                            ['like', 'detail', $search],
                        ]);
                }
        
                
        }

        $data = array();
        $data['rules'] = $query->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }

    public function createRules($data = array()){
        $this->apiCode = 0;
        $model = new Rules;
        $model->type = $data['type'];
        $model->product_fields = $data['product_fields'];
        $model->detail = $data['detail'];
        if($model->validate()) {
            if($model->save(false)) {
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'created_successfully', [Yii::t('app', 'rules')]);
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

    public function upadateRules($data = array()){
        $this->apiCode = 0;
        $model = Rules::findOne($data['id']);
        $model->type = $data['type'];
        $model->product_fields = $data['product_fields'];
        $model->detail = $data['detail'];
        if($model->validate()) {
            if($model->save(false)) {
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'updated_successfully', [Yii::t('app', 'rules')]);
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