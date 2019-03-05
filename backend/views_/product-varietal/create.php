<?php

use yii\helpers\Html;

$this->title = 'Create Product Varietal';
$this->params['breadcrumbs'][] = ['label' => 'Product Varietal', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-varietal-create">
    <?= $this->render('_form', [
        'model' => $model,
        'varietalList' => $varietalList,
    ]) ?>

</div>
