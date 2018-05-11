<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Helps */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Helps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="helps-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
      
        <?= Html::a('Update', ['helps/update/'.$model->id.'/'.$categoryId], ['class' => 'btn btn-primary']) ?>
        
        <?= Html::a('Delete', ['helps/delete/'.$model->id.'/'.$categoryId], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           
            'category_id',
            'question:ntext',
            'answer:ntext',
            
        ],
    ]) ?>

</div>
