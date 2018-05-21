<?php
namespace common\repository;

use Yii;
use common\helpers\CommonHelper;
use common\models\Cities;
use common\models\Countries;
use common\models\Province;
use common\models\User;
use common\models\Markets;
use common\models\MarketSegments;
use common\models\MarketSegmentData;

class MasterDataRepository extends Repository
{
    public function countries($data = array()) {
        $this->apiCode = 1;
        $query = Countries::find();

        $data = array();
        $data['countries'] = $query->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }

    public function cities($data = array()) {
        $this->apiCode = 1;
        $query = Cities::find();
        if (isset($data['province_id']) && $data['province_id']) {
        	$query->andWhere(['province_id'=>$data['province_id']]);
        }
        $data = array();
        $data['cities'] = $query->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }

    public function provinces($data = array()) {
        $this->apiCode = 1;
        $query = Province::find();
        if (isset($data['country_id']) && $data['country_id']) {
        	$query->andWhere(['country_id'=>$data['country_id']]);
        }
        $data = array();
        $data['provinces'] = $query->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }
    
    public function market($data = array()){
        $this->apiCode = 1;
        $marketRepository = new MarketRepository();
        $markets = $marketRepository->marketList($data);
        $marektData = array();
        if($markets['status']['success'] == 1 ){
            if($markets['data']['markets']){
                foreach ($markets['data']['markets'] as $key => $value) {
                    $temp = array();
                    $temp['id'] = $value['id'];
                    $temp['title'] = $value['title'];
                    $temp['description'] = $value['description'];
                    if($value['marketSegmentData']){
                        foreach ($value['marketSegmentData'] as $childKey => $childValue) {
                            $newTemp = array();
                            $newTemp['id'] = isset($childValue['marketSegment']['id']) ? $childValue['marketSegment']['id'] : 0;
                            $newTemp['title'] = isset($childValue['marketSegment']['title']) ? $childValue['marketSegment']['title'] : '';
                            $newTemp['description'] = isset($childValue['marketSegment']['description']) ? $childValue['marketSegment']['description'] : '';
                            $temp['marketSegment'][] = $newTemp;
                        }
                    }
                    $marektData[] = $temp;
                }
            }
        }
        
        $data['markets']=$marektData;
        $this->apiData = $data;
        return $this->response();
    }
    
     public function listing($data = array()) {
        $this->apiCode = 1;
        $returnData = array();
        
        $user = Yii::$app->user->identity;
        $model = User::findOne($user->id);
        $marketId= isset($model->market_id)? $model->market_id : 0;
        $returnData['countries'] = $this->countries();
        if( $returnData['countries']['status']['success'] == 1 ){
            $returnData['countries']=$returnData['countries']['data']['countries'];
        }
       
        $returnData['cities'] = $this->cities();
        if( $returnData['cities']['status']['success'] == 1 ){
            $returnData['cities']=$returnData['cities']['data']['cities'];
        }
        $returnData['provinces'] = $this->provinces();
        if( $returnData['provinces']['status']['success'] == 1 ){
            $returnData['provinces']=$returnData['provinces']['data']['provinces'];
        }
        
        $returnData['markets'] = $this->market($data);
        if( $returnData['markets']['status']['success'] == 1 ){
            $returnData['markets']=$returnData['markets']['data']['markets'];
        }
        $this->apiData = $returnData;
        return $this->response();
       
    }
}