<?php

use yii\helpers\Html;
$this->title = 'Update Market Clusters';
$this->params['breadcrumbs'][] = ['label' => 'Market Clusters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title

/* @var $this yii\web\View */
/* @var $model common\models\MarketSegments */
?>
<div class="market-segments-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
