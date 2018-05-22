<?php
namespace common\repository;

use Yii;
use common\helpers\CommonHelper;
use common\models\StoresResponseType;


class StoresResponseTypeRepository extends Repository
{
      public function listing($data = array()) {
        $this->apiCode = 1;
        $query = StoresResponseType::find();

        
        $data = array();
        $data['response_type'] = $query->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }

    public function createQuestions($data = array()){
      
        $this->apiCode = 0;
    
        $store_id=$data['store_id'];
        $config_id= $data['config_id'];
        foreach ($data['questions'] as $key => $value) {
          
                    $model = new StoresResponseType;
                    
                     $model->question_id = $value['id'];
                     $model->store_id = $store_id;
                      $model->config_id = $config_id;
                       $model->answer = $value['response_type'];
                     
                        if ($model->save(false)) {
                             $this->apiCode = 1;
                            $this->apiMessage = Yii::t('app', 'updated_successfully', [Yii::t('app', 'answer')]);
                            
                        }
                     else {
                       $this->apiCode = 0;
                         $this->apiMessage = Yii::t('app', 'Something went wrong.');
                    }
        }
                

        return $this->response();
    }

    public function upadateQuestions($data = array()){
        $this->apiCode = 0;
        $model = StoresResponseType::findOne($data['id']);
    
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