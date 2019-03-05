<?php

use yii\helpers\Html;

$this->title = 'Create Feedback Question';
$this->params['breadcrumbs'][] = ['label' => 'Questions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="questions-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
