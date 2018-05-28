<?php

use yii\helpers\Html;

$this->title = 'Create Product Types';
$this->params['breadcrumbs'][] = ['label' => 'Product Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-types-create">
   <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
