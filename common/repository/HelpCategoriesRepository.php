<?php
namespace common\repository;

use Yii;
use common\helpers\CommonHelper;
use common\models\HelpCategories;


class HelpCategoriesRepository extends Repository
{
      public function listing($data = array()) {
        $this->apiCode = 1;
        $query = HelpCategories::find()->with(['questions']);

        if(isset($data['serachText']) && ($data['serachText'] != '')){        
            $data['search']=$data['serachText'];
        }
        
        if(isset($data['search']) && $data['search']){
            $search = trim($data['search']);       
            $query->andWhere([
                'or',
                ['like', 'title', $search],
            ]);
        }
       
        $result=$query->asArray();
        $data = array();
        $data['help_categories'] = $query->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }

    public function createCategories($data = array()) {

        $this->apiCode = 0;
        $model = new HelpCategories;
        if(isset($data['title']) && ($data['title'] != '')){
        $model->title = $data['title'];
        }
        if ($model->validate()) {
            if ($model->save(false)) {
                $returnData = array();
                $returnData['categories'] = $model;            
                $this->apiData = $returnData;
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'created_successfully', [Yii::t('app', 'category')]);
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

    public function updateCategories($data = array()) {

        $this->apiCode = 0;
        $model = HelpCategories::findOne($data['id']);
        if(!$model){
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $model->title = $data['title'];
        
        if ($model->validate()) {
            if ($model->save(false)) {
                $returnData = array();
                $returnData['catalogue'] = $model;
                $this->apiData = $returnData;
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'updated_successfully', [Yii::t('app', 'category')]);
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