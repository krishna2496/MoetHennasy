<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\StoreConfiguration */

$this->title = 'Create Store Configuration';
$this->params['breadcrumbs'][] = ['label' => 'Store Configurations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="store-configuration-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
