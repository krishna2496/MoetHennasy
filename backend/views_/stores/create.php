<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Stores */

$this->title = 'Create Store';
$this->params['breadcrumbs'][] = ['label' => 'Stores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stores-create">

    <?= $this->render('_form', [
        'model' => $model,
        'markets' => $markets,
        'countries' => $countries,
    ]) ?>

</div>
