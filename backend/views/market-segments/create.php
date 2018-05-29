<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MarketSegments */

$this->title = 'Create Market Clusters';
$this->params['breadcrumbs'][] = ['label' => 'Market Clusters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="market-segments-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
