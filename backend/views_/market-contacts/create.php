<?php

use yii\helpers\Html;
use yii\grid\GridView;


$this->title = 'Market Contacts';
$this->params['breadcrumbs'][] = ['label' => 'Market', 'url' => ['/market']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="market-contacts-index">
<div class="widget" id="add-equipment">
                <?=
                $this->render('_form', [
                    'searchModel' => $searchModel,
                    'model' => $model,
                    'dataProvider' => $dataProvider,
                    'segmentData' => $segmentData,
                    'market_id' =>$market_id,
                    'filters' => $filters
                   
                ]);
                ?>
            </div>
   
            <div class="widget" id="view-equipment">
                <?=
                $this->render('index', [
                    'searchModel' => $searchModel,
                    'model' => $model,
                    'dataProvider' => $dataProvider,
                    'segmentData' => $segmentData,
                    'market_id' =>$market_id,
                    'filters' => $filters
                ]);
                ?>
            </div>
    
</div>
