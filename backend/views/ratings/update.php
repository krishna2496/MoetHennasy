<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Ratings */

$this->title = 'Update Ratings';
$this->params['breadcrumbs'][] = ['label' => 'Ratings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ratings-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
