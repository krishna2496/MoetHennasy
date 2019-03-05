<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Rule Administration';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="market-contacts-index">
<div class="widget" id="add-equipment">
                <?=
                $this->render('_form', [
                    'markets' => $markets,
                    'brands' => $brands,
                    'productCategory' => $productCategory,
                    'model' => $model
                ]);
                ?>
            </div>
   <div class="widget" id="view-equipment">
                <?=
                $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'markets' => $markets,
                    'brands' => $brands,
                    'productCategory' => $productCategory,
                    'model' => $model
                ]);
                ?>
   </div>
    
</div>
