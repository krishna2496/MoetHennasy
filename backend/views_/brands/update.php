<?php

use yii\helpers\Html;

$this->title = 'Update Brand';
$this->params['breadcrumbs'][] = ['label' => 'Brands', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update Brand';
?>
<div class="brands-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
