<?php

use yii\helpers\Html;

$this->title = 'Create Glossary';
$this->params['breadcrumbs'][] = ['label' => 'Glossaries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="glossary-create">

    <?= $this->render('_form', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'brand' => $brand,
                
    ]) ?>

</div>
