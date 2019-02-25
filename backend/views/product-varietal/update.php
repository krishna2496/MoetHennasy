<?php

use yii\helpers\Html;

$this->title = 'Update Product Varietal';
$this->params['breadcrumbs'][] = ['label' => 'Product Varietal', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-categories-update">
    <?= $this->render('_form', [
        'model' => $model,
        'varietalList' => $varietalList,
    ]) ?>

</div>
