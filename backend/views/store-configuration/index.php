<?php

use yii\helpers\Html;

$this->title = 'Store Configuration';
$this->params['breadcrumbs'][] = ['label' => 'Store Configuration', 'url' => ['store-configuration/listing/'.$store_id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="glossary-create">

    <?= $this->render('_form', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'brand' => $brand,
                'store_id' => $store_id,
                'is_update'=>$is_update,
                'configId' => $configId,
                'brandThumbId' => $brandThumbId,
                'reviewFlag' => $reviewFlag,
                'brandBackground' => $brandBackground
                
    ]) ?>

</div>
