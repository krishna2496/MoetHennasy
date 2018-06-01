<?php

use yii\helpers\Html;

$this->title = 'Update Rules';
$this->params['breadcrumbs'][] = ['label' => 'Rules', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update Rule';
?>
<div class="rules-update">
    <?= $this->render('_form', [
          'model' => $model,
        'market'=>$market,
        'brand' => $brand,
        'product' => $product,
        'productSubCatData' => $productSubCatData,
        'productTypeData' => $productTypeData
    ]) ?>

</div>
