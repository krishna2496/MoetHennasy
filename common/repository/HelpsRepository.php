<?php
namespace common\repository;

use Yii;
use common\helpers\CommonHelper;
use common\models\Helps;


class HelpsRepository extends Repository
{
      public function listing($data = array()) {
        $this->apiCode = 1;
        $query = Helps::find();

        if(isset($data['serachText']) && ($data['serachText'] != '')){        
            $data['search']=$data['serachText'];
        }
        
       if(isset($data['category_id']) && ($data['category_id'] != '')){        
            $query->andWhere(['category_id' => $data['category_id']]);
        }
        if(isset($data['search']) && $data['search']){
            $search = trim($data['search']);       
            $query->andWhere([
                'or',
                ['like', 'question', $search],
                ['like', 'answer', $search],
            ]);
        }
       
        $result=$query->asArray();
        $data = array();
        $data['helps'] = $query->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }

    public function createQuestions($data = array()) {
     
        $this->apiCode = 0;
        $model = new Helps;
        $model->category_id=$data['category_id'];
        $model->question=$data['question'];
        $model->answer=$data['answer'];
        if ($model->validate()) {
            if ($model->save(false)) {
                $returnData = array();
                $returnData['helps'] = $model;            
                $this->apiData = $returnData;
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'created_successfully', [Yii::t('app', 'catalogues')]);
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
        $model = Helps::findOne($data['id']);
        if(!$model){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
       $model->question=$data['question'];
       $model->answer=$data['answer'];
        if ($model->validate()) {
            if ($model->save(false)) {
                $returnData = array();
                $returnData['helps'] = $model;
                $this->apiData = $returnData;
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'updated_successfully', [Yii::t('app', 'catalogues')]);
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