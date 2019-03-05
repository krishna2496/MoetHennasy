<?php

namespace common\repository;

use Yii;
use common\helpers\CommonHelper;
use common\models\Markets;
use common\models\MarketSegmentData;
use common\models\MarketContacts;

class MarketContactRepository extends Repository {

    public function listing($data = array()) {

        $this->apiCode = 1;
        $query = MarketContacts::find()
            ->joinWith(['marketSegment', 'market']);

        if (isset($data['search']) && $data['search']) {
            $search = $data['search'];
            $query->andWhere([
                'or',
                    ['like', 'markets.title', $search],
                    ['like', 'market_segments.title', $search],
                    ['like', 'email', $search],
                    ['like', 'phone', $search],
                    ['like', 'address', $search],
            ]);
        }

        if (isset($data['market_segment_id']) && $data['market_segment_id']) {
            $query->andWhere(['market_contacts.market_segment_id' => $data['market_segment_id']]);
        }

        if (isset($data['market_id']) && $data['market_id']) {
            $query->andWhere(['market_contacts.market_id' => $data['market_id']]);
        }

        $data = array();
        $data['contacts'] = $query->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }

    public function createContact($data = array()) {

        $this->apiCode = 0;
        $model = new MarketContacts;
        $model->market_segment_id = $data['market_segment_id'];
        $model->address = $data['address'];
        $model->phone = $data['phone'];
        $model->email = $data['email'];
        $model->market_id = $data['market_id'];

        if ($model->validate()) {
            if ($model->save(false)) {
                $returnData = array();
                $returnData['contacts'] = $model;
                $this->apiData = $returnData;
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'created_successfully', [Yii::t('app', 'Contacts')]);
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

    public function updateContact($data = array()) {
        $this->apiCode = 0;
        $model = MarketContacts::findOne($data['id']);
        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        if (isset($data['market_segment_id'])) {
            $model->market_segment_id = $data['market_segment_id'];
        }
        if (isset($data['address'])) {
            $model->address = $data['address'];
        }
        if (isset($data['phone'])) {
            $model->phone = $data['phone'];
        }
        if (isset($data['email'])) {
            $model->email = $data['email'];
        }
        if (isset($data['market_id'])) {
            $model->market_id = $data['market_id'];
        }

        if ($model->validate()) {
            if ($model->save(false)) {
                $returnData = array();
                $returnData['contacts'] = $model;
                $this->apiData = $returnData;
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'updated_successfully', [Yii::t('app', 'Contacts')]);
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
