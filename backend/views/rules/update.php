<?php

use yii\helpers\Html;

$this->title = 'Update Rules';
$this->params['breadcrumbs'][] = ['label' => 'Rules', 'url' => ['index']];
$this->params['breadcrumbs'][] = '$this->title';
?>
<div class="rules-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
