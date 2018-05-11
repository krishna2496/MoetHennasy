<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Catalogues */

$this->title = 'Create Catalogues';
$this->params['breadcrumbs'][] = ['label' => 'Catalogues', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catalogues-create">


    <?= $this->render('_form', [
        'model' => $model,
        'market' => $market,
        'brand' => $brand
    ]) ?>

</div>
