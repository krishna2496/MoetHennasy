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
        if (isset($data['country_id']) && $data['country_id']) {
        	$query->andWhere(['country_id'=>$data['country_id']]);
        }
        $data = array();
        $data['cities'] = $query->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }

    public function provinces($data = array()) {
        $this->apiCode = 1;
        $query = Province::find();
        if (isset($data['city_id']) && $data['city_id']) {
        	$query->andWhere(['city_id'=>$data['city_id']]);
        }
        $data = array();
        $data['provinces'] = $query->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }
    
    public function market($data = array()){
    $this->apiCode = 1;
    $id=$data['id'];
    $data=array();
    
    $marketData=Markets::find()->where(['id'=>$id])->asArray()->one();
    $marketSegmentData=MarketSegmentData::find()->andWhere(['market_id'=>$id])->asArray()->all();
    
    foreach($marketSegmentData as $key=>$value){
        $marketSegmentDataArry[]=$value['market_segment_id'];
    }
    $marketSegmentDataIds= implode(',',$marketSegmentDataArry);
 
    $marketSegmentDetail=MarketSegments::find()->where("id IN($marketSegmentDataIds) ")->asArray()->all();
    $data['market']=$marketData;
    $data['market']['marketSegment']=$marketSegmentDetail;
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
        
        $returnData['market'] = $this->market(array('id'=>$marketId));
        if( $returnData['market']['status']['success'] == 1 ){
            $returnData['market']=$returnData['market']['data']['market'];
        }
        $this->apiData = $returnData;
        return $this->response();
       
    }
}