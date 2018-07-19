<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Catalogues */

$this->title = 'Create Product';
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catalogues-create">


    <?= $this->render('_form', [
        'model' => $model,
        'market' => $market,
        'brand' => $brand,
        'product' => $product,
        'productSubCatData'=>$productSubCatData,
        'productTypeData' => $productTypeData
    ]) ?>

</div>
