<?php

use yii\helpers\Html;

$this->title = 'Create Ratings';
$this->params['breadcrumbs'][] = ['label' => 'Ratings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ratings-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
