<?php

use yii\helpers\Html;
$this->title = 'Update Market Segments';
$this->params['breadcrumbs'][] = ['label' => 'Market Segments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title

/* @var $this yii\web\View */
/* @var $model common\models\MarketSegments */
?>
<div class="market-segments-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
