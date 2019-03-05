<?php

use yii\helpers\Html;

$this->title = 'Update Product Type';
$this->params['breadcrumbs'][] = ['label' => 'Product Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title ;
?>
<div class="product-types-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
