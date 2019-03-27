<?php
namespace common\repository;

use Yii;
use common\helpers\CommonHelper;
use common\models\ProductVarietal;
use common\models\MarketBrandsVerietals;

class ProductVarietalRepository extends Repository
{
    public function listing($data = array()) {
       
        $this->apiCode = 1;
        $query = ProductVarietal::find()->joinWith(['marketBrandsVerietals' => function (\yii\db\ActiveQuery $query) use($data) {
        return $query
//            ->andWhere(['=', 'market_brands_verietals.market_id', $data['market_id']])
//            ->andWhere(['=', 'market_brands_verietals.category_id', $data['category_id']])
//            ->andWhere(['=', 'market_brands_verietals.brand_id', $data['brand_id']])
//             ->orWhere(['=', 'market_brands_verietals.brand_id', ''])
            
            ->andWhere(['or',
                ['market_brands_verietals.market_id'=> $data['market_id']],
                ['market_brands_verietals.market_id'=>NULL],
                ['market_brands_verietals.market_id'=>0],
            ])
            
            ->andWhere(['or',
                ['market_brands_verietals.category_id'=> $data['category_id']],
                ['market_brands_verietals.category_id'=>NULL],
                ['market_brands_verietals.market_id'=>0],
            ])
            
            ->andWhere(['or',
                ['market_brands_verietals.brand_id'=> $data['brand_id']],
                ['market_brands_verietals.brand_id'=>NULL],
                ['market_brands_verietals.market_id'=>0],
            ])
            ->orderBy('(CASE
                   WHEN (reorder_id IS NOT NULL)
                       THEN reorder_id
                       ELSE market_brands_verietals.id
                END) ASC');
           
            }]);

        $data = array();
        $data['productVarietal'] = $query->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }
    
    public function listingVariental($data = array()) {
       
        $this->apiCode = 1;
        $query = ProductVarietal::find();
         if(isset($data['search']) && $data['search']){
            $data['search'] = trim($data['search']);
            $query->andWhere(['like','name',$data['search']]);
        }
        $data = array();
        $data['productVarietal'] = $query->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }
    
    public function productVariental($data = array()) {
       
        $this->apiCode = 1;
        $query = \common\models\Catalogues::find()->andWhere(['product_category_id'=> $data['category_id'],'brand_id' =>  $data['brand_id'],'top_shelf' => 0]);
        $data = array();
        $data['catalogue'] = $query->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }
    
    public function listingNew($data = array()) {
        $this->apiCode = 1;
        $query = MarketBrandsVerietals::find()->joinWith('productVeriatal');
        
        if(isset($data['search']) && $data['search']){
            $data['search'] = trim($data['search']);
            $query->andWhere(['like','name',$data['search']]);
        }
        
        if(isset($data['except_id']) && $data['except_id']){
        	$query->andWhere(['!=','id',$data['except_id']]);
        }

        $data = array();
//        $data['productVarietal'] = $query->orderBy(['name' => yii::$app->params['defaultSorting']])->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }
    
    public function listingMarketBrandVriental($data = array()) {
        $this->apiCode = 1;
        $query = MarketBrandsVerietals::find()->andWhere(['market_id' => $data['market_id'],'category_id'=> $data['category_id'],'brand_id' =>  $data['brand_id']]);
        
            
        $data = array();
        $data['productVarietal'] = $query->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }

    public function createProductVarietal($data = array()){
        $this->apiCode = 0;
        $model = new ProductVarietal;
        $model->name = $data['name'];
        if($model->validate()) {
            if($model->save(false)) {
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'created_successfully', [Yii::t('app', 'product_varietal')]);
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

    public function upadateProductVarietal($data = array()){
        $this->apiCode = 0;
        $model = ProductVarietal::findOne($data['id']);
        if(isset($data['name'])) {
            $model->name = $data['name'];
        }
        if($model->validate()) {
            if($model->save(false)) {
                $this->apiCode = 1;
                $this->apiMessage = Yii::t('app', 'updated_successfully', [Yii::t('app', 'product_varietal')]);
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