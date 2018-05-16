<?php

use yii\helpers\Html;

$this->title = 'Update Brands';
$this->params['breadcrumbs'][] = ['label' => 'Brands', 'url' => ['index']];
$this->params['breadcrumbs'][] = '$this->title';
?>
<div class="brands-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
