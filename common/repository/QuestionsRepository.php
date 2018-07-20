<?php
namespace common\repository;

use Yii;
use common\helpers\CommonHelper;
use common\models\Questions;


class QuestionsRepository extends Repository
{
      public function listing($data = array()) {
        $this->apiCode = 1;
        $query = Questions::find();

        if(isset($data['search']) && $data['search']){
            $search = trim($data['search']);       
            $query->andWhere([
                'or',
                ['like', 'question', $search],
//                ['like', 'answer', $search],
            ]);
        }
       
        $result=$query->asArray();
        $data = array();
        $data['questions'] = $query->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }

    public function createQuestions($data = array()) {
        $this->apiCode = 0;
        $model = new Questions;
        $model->question=$data['question'];
        $model->response_type=$data['response_type'];
        
        if ($model->validate()) {
            if ($model->save(false)) {
                $returnData = array();
                $returnData['questions'] = $model;            
                $this->apiData = $returnData;
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'created_successfully', [Yii::t('app', 'helps')]);
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

    public function updateQuestions($data = array()) {

       $this->apiCode = 0;
       $model = Questions::findOne($data['id']);
       if(!$model){
            throw new NotFoundHttpException('The requested page does not exist.');
       }
       if(isset($data['question'])) {
       $model->question=$data['question'];
       }
       $model->response_type=$data['response_type'];
        if ($model->validate()) {
            if ($model->save(false)) {
                $returnData = array();
                $returnData['questions'] = $model;
                $this->apiData = $returnData;
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'updated_successfully', [Yii::t('app', 'helps')]);
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