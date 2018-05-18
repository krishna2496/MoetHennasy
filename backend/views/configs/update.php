<?php

use yii\helpers\Html;

$this->title = 'Update Configs';
$this->params['breadcrumbs'][] = ['label' => 'Configs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title
?>
<div class="configs-update">
    
    <?= $this->render('_form', [
        'model' => $model,
        'id'=>$id
    ]) ?>

</div>
