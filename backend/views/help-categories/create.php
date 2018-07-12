<?php

use yii\helpers\Html;

$this->title = 'Create Help Categorie';
$this->params['breadcrumbs'][] = ['label' => 'Help Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="help-categories-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
