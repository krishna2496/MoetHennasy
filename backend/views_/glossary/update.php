<?php

use yii\helpers\Html;

$this->title = 'Update Glossary';
$this->params['breadcrumbs'][] = ['label' => 'Glossaries', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="glossary-update">
  <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
