<?php

namespace backend\controllers;


use Yii;
use common\models\Markets;
use common\models\MarketSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\repository\MarketRepository;
use common\repository\MarketSegmentsRepository;
use common\helpers\CommonHelper;
use common\models\MarketSegments;
use common\models\MarketSegmentData;
use common\models\Rules;
use common\models\RulesSearch;
use common\models\MarketRules;
use common\models\BrandsSearch;
use common\models\MarketBrands;
use common\repository\MarketRulesRepository;

class ApplyController extends MarketController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => \common\components\AccessRule::className(),
                ],
                'rules' => [
                    [
                        'actions' => ['test','rules','brands','re-order'],
                        'allow' => true,
                        'roles' => ['&'],
                    ],
                   
                ],
            ],
          
        ];
    }
   public function actionTest(){
        $cat = MarketBrands::find()->asArray()->all();
       
        foreach ($cat as $key=>$value){         
//            echo $value['id'];exit;
          $c = MarketBrands::findOne($value['id']);
          $c->reorder_id = $value['id'];
          $c->save(false);
        }
    }
    
    public function actionRules($id){      
       $filters = Yii::$app->request->queryParams;
      
       if (($model = Markets::findOne($id)) !== null) {
        $title=$model->title;
        
        $searchModel = new RulesSearch();
       
        $model = new MarketRules();
        $selected = [];
        $market_segment_id ='';
       
        if(Yii::$app->request->post('MarketRules')){
            $postData=Yii::$app->request->post('MarketRules');
            $market_segment_id=$postData['market_segment_id'];
            $_SESSION['apply_rule_segment_id'] = $market_segment_id;
            $ruleModel = MarketRules::find()->select('rule_id')->andWhere(['market_id' => $id,'market_segment_id' => $market_segment_id ])->asArray()->all();
        }else{
            $request = Yii::$app->request;
            $ruleModel = MarketRules::find()->select('rule_id')->andWhere(['market_id' => $id])->asArray()->all();
            if ($request->isPjax) {
                if(isset($filters['id']) && ($filters['id'] != '') && ($filters['id'] != 0)){
                    $id = $filters['id'];
                }
            $ruleModel = MarketRules::find()->select('rule_id')->andWhere(['market_id' => $id,'market_segment_id' => $_SESSION['apply_rule_segment_id']])->asArray()->all();
            }
        }
        
        if($ruleModel){
            foreach ($ruleModel as $key=>$value){
                  $selected[$key]  = $value['rule_id']; 
             }
        }
        $data['market_id'] = $id;
        $markets = new MarketRepository();
        $marketData = $markets->marketList($data);
        $segmentData = array();
        if ($marketData['status']['success'] == 1) {
                foreach ($marketData['data']['markets'][0]['marketSegmentData'] as $key => $value) {
                    $segmentData[$value['marketSegment']['id']] = $value['marketSegment']['title'];
                }
        }
      
        if(Yii::$app->request->post('selection')) {
           
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post('selection');
            $rules = $data;
            $ruleData['market_id'] = $id;
            $ruleData['rule_id'] = $rules;
            $ruleData['market_segment_id'] =  $_SESSION['apply_rule_segment_id'] ;
            $marketRepository = new MarketRulesRepository;
            $returnData = $marketRepository->createRule($ruleData);
            if($returnData['status']['success'] == 1)
            {  
                parent::userActivity('create_markets_rules',$description='');
                Yii::$app->session->setFlash('success', $returnData['status']['message']);
                return $this->redirect(['market/index']);
            } else {
                 Yii::$app->session->setFlash('danger', $returnData['status']['message']);
            }
        }
        
        if(!isset($filters['limit'])){
            $filters['limit'] = Yii::$app->params['pageSize'];
        }
        
        $dataProvider = $searchModel->search($filters);
        $dataProvider->pagination->pageSize = $filters['limit'];
        return $this->render('/market/apply_rules', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filters' => $filters,
            'rules' => $selected,
            'market_segment_id' => $market_segment_id,
            'title' => $title,
            'segmentData' => $segmentData,
            'market_id' => $id
        ]);
        
       }else{
            throw new NotFoundHttpException('The requested page does not exist.');
       }
    }
    
    public function actionBrands($id){
        
        if (($model = Markets::findOne($id)) !== null) {
        $title=$model->title;
        
        $searchModel = new BrandsSearch();
        $filters =array();
        $model = new MarketBrands();
        $selected = [];
        $ruleModel = MarketBrands::find()->select(['brand_id','reorder_id'])->andWhere(['market_id' => $id])->orderBy(['reorder_id'=>SORT_ASC])->asArray()->all();
//        echo '<pre>';
//        print_r($ruleModel);exit;
        if($ruleModel){
            foreach ($ruleModel as $key=>$value){
                  $selected[$key]  = $value['brand_id']; 
             }
        }

        $data['market_id'] = $id;
        
        if(Yii::$app->request->post('limit')){
            $filters['limit'] = Yii::$app->request->post('limit');
        }
        if(Yii::$app->request->post('search')){
            $filters['search'] = Yii::$app->request->post('search');
        }
        
     
         if(Yii::$app->request->post('selectedBrand')) {
      
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post('selectedBrand');
         
            $rules = explode(',', $data);
            $ruleData['market_id'] = $id;
            $ruleData['brand_id'] = $rules;
           
            $marketRepository = new \common\repository\MarketBrandsRepository;
            $returnData = $marketRepository->createBrand($ruleData);
            if($returnData['status']['success'] == 1)
            {  
                parent::userActivity('create_markets_brands',$description='');
                Yii::$app->session->setFlash('success', $returnData['status']['message']);
                return $this->redirect(['market/index']);
            } else {
                 Yii::$app->session->setFlash('danger', $returnData['status']['message']);
            }
        }
        
        if(!isset($filters['limit'])){
            $filters['limit'] = Yii::$app->params['pageSize'];
        }
       
        $dataProvider = $searchModel->search($filters);
        $dataProvider->pagination->pageSize = $filters['limit'];
        
        return $this->render('/market/apply_brand', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filters' => $filters,
            'rules' => $selected,
            'title' => $title,
            'market_id' => $id,
            'selected' => $selected
        ]);
        
       }else{
            throw new NotFoundHttpException('The requested page does not exist.');
       }
    }
    
    public function actionReOrder(){
        $data = \yii::$app->request->post();
        $current_id = $data['current_id'];
        $replaced_id= $data['replaced_id'];
        $market_id = $data['market_id'];
        
        $currentCat = MarketBrands::findOne(['brand_id' =>$current_id,'market_id' => $market_id]);
        $replaced = MarketBrands::findOne(['brand_id' =>$replaced_id,'market_id' =>$market_id]);       
        
        $current_re= $currentCat->reorder_id;
       $replace_re =  $replaced->reorder_id;
        
        $currentCat->reorder_id = $replace_re;
        $replaced->reorder_id =  $current_re;
        
//        echo '<pre>';
//        print_r($currentCat);exit;
         $currentCat->save(false);
        $replaced->save(false);
    }

}
