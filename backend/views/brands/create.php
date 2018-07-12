<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Brands */

$this->title = 'Create Brand';
$this->params['breadcrumbs'][] = ['label' => 'Brands', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="brands-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
