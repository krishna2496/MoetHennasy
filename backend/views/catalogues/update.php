<?php

use yii\helpers\Html;

$this->title = 'Update Catalogues';
$this->params['breadcrumbs'][] = ['label' => 'Catalogues', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catalogues-update">
    <?= $this->render('_form', [
        'model' => $model,
        'market'=>$market,
        'brand' => $brand,
        'product' => $product,
        'productSubCatData' => $productSubCatData
    ]) ?>

</div>
