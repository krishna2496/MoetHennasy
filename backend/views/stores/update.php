<?php

use yii\helpers\Html;

$this->title = 'Update Stores';
$this->params['breadcrumbs'][] = ['label' => 'Stores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stores-update">
    <?= $this->render('_form', [
        'model' => $model,
        'markets' => $markets,
        'countries' => $countries,
    ]) ?>

</div>
