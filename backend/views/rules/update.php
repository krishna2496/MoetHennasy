<?php

use yii\helpers\Html;

$this->title = 'Update Rule';
$this->params['breadcrumbs'][] = ['label' => 'Rules', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update Rule';
?>
<div class="rules-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
