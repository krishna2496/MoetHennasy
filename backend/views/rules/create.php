<?php

use yii\helpers\Html;

$this->title = 'Create Rule';
$this->params['breadcrumbs'][] = ['label' => 'Rules', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rules-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
