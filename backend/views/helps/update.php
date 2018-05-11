<?php

use yii\helpers\Html;

$this->title = 'Update Helps';
$this->params['breadcrumbs'][] = ['label' => 'Helps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title
?>
<div class="helps-update">
    <?= $this->render('_form', [
        'model' => $model,
           'id'=>$id
    ]) ?>

</div>
