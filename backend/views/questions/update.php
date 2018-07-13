<?php

use yii\helpers\Html;

$this->title = 'Update Feedback Question';
$this->params['breadcrumbs'][] = ['label' => 'Market', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title
?>
<div class="questions-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
