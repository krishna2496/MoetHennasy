<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Helps */

$this->title = 'Create Helps';
$this->params['breadcrumbs'][] = ['label' => 'Helps', 'url' => ['helps/index/'.$id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="helps-create">
    <?= $this->render('_form', [
        'model' => $model,
        'id'=>$id
    ]) ?>

</div>
