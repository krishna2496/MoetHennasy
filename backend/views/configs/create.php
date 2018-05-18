<?php

use yii\helpers\Html;


$this->title = 'Create Configs';
$this->params['breadcrumbs'][] = ['label' => 'Configs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="configs-create">
    <?= $this->render('_form', [
        'model' => $model,
        'id'=>$id
    ]) ?>

</div>
