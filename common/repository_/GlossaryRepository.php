<?php

namespace common\repository;

use Yii;
use common\helpers\CommonHelper;
use common\models\Glossary;

class GlossaryRepository extends Repository {

    public function listing($data = array()) {
        $this->apiCode = 1;
        $query = Glossary::find();

        if (isset($data['search']) && $data['search']) {
            $search = trim($data['search']);
            $query->andWhere([
                'or',
                    ['like', 'title', $search],
                    ['like', 'description', $search],
                  ]);
        }

        $data = array();
        $data['glossary'] = $query->asArray()->orderBy(['title'=>SORT_ASC])->all();
     
        $this->apiData = $data;
        return $this->response();
    }

    public function createGlossary($data = array()) {
        $this->apiCode = 0;
        $model = new Glossary();
        $model->title = $data['title'];
        $model->description = $data['description'];
        if ($model->validate()) {
            if ($model->save(false)) {
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'created_successfully', [Yii::t('app', 'glossary')]);
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

    public function upadateGlossary($data = array()) {
        $this->apiCode = 0;
        $model = Glossary::findOne($data['id']);
        if (isset($data['title'])) {
            $model->title = $data['title'];
        }
        if (isset($data['description'])) {
            $model->description = $data['description'];
        }
        
        if ($model->validate()) {
            if ($model->save(false)) {
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'updated_successfully', [Yii::t('app', 'glossary')]);
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
