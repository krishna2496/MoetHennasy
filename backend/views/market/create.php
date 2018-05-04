<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MarketSegments */

$this->title = 'Create Market';
$this->params['breadcrumbs'][] = ['label' => 'Market', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="market-segments-create">
    <?= $this->render('_form', [
        'model' => $model,
        'marketSegmentList' =>$marketSegmentList,
    ]) ?>
</div>
