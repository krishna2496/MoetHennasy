<?php

use yii\helpers\Html;
$this->title = 'Update Help Categorie';
$this->params['breadcrumbs'][] = ['label' => 'Update Help Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="help-categories-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
