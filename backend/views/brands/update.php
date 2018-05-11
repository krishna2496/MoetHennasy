<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Brands */

$this->title = 'Update Brands';
$this->params['breadcrumbs'][] = ['label' => 'Brands', 'url' => ['index']];
$this->params['breadcrumbs'][] = '$this->title';
?>
<div class="brands-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
