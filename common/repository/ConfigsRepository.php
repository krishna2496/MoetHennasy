<?php
namespace common\repository;

use Yii;
use common\helpers\CommonHelper;
use common\models\Configs;


class ConfigsRepository extends Repository
{
      public function listing($data = array()) {
        $this->apiCode = 1;
        $query = Configs::find();

        if((isset($data['store_id'])) && ($data['store_id'] != '')){
             $query->andWhere(['store_id' => $data['store_id']]);
        }
        if(isset($data['search']) && $data['search']){
            $search = trim($data['search']);       
            $query->andWhere([
                'or',
                ['like', 'value', $search],
             
            ]);
        }
       
        $result=$query->asArray();
        $data = array();
        $data['configs'] = $query->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }

    public function createConfigs($data = array()) {
     
        $this->apiCode = 0;
        $model = new Configs;
        $model->value=$data['value'];
        $model->store_id=$data['store_id'];
        if ($model->validate()) {
            if ($model->save(false)) {
                $returnData = array();
                $returnData['configs'] = $model;            
                $this->apiData = $returnData;
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'created_successfully', [Yii::t('app', 'configs')]);
            } else {
                $this->apiCode = 0;
                $this->apiMessage = Yii::t('app', 'Something went wrong.');
            }
        } else {
            $this->apiCode = 0;
            if (isset($model->errors) && $model->errors) {
                $this->apiMessage = $model->errors;
            }
        }

        return $this->response();
    }

    public function updateConfigs($data = array()) {

        $this->apiCode = 0;
        $model = Configs::findOne($data['id']);
        if(!$model){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $model->value=$data['value'];
     
        if ($model->validate()) {
            if ($model->save(false)) {
                $returnData = array();
                $returnData['configs'] = $model;
                $this->apiData = $returnData;
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'updated_successfully', [Yii::t('app', 'configs')]);
            } else {
                $this->apiCode = 0;
                $this->apiMessage = Yii::t('app', 'Something went wrong.');
            }
        } else {
            $this->apiCode = 0;
            if (isset($model->errors) && $model->errors) {
                $this->apiMessage = $model->errors;
            }
        }

        return $this->response();
    }
}