<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use common\helpers\CommonHelper;

$this->title ='Helps';
$this->params['breadcrumbs'][] = ['label' => 'Helps', 'url' => ['helps/index/'.$model->category_id]];
$this->params['breadcrumbs'][] = 'View Helps';
$updateUrl = Url::to(['helps/update/'.$model->id]);
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    <?= Html::encode($this->title) ?>
                </h3>
                <?= Html::a('Update', $updateUrl , ['class' => 'btn btn-primary pull-right']) ?>
            </div>
            <div class="box-body">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                         'question:ntext',
                       [ 
                           'attribute' => 'answer',
                         'format' =>'html'
                     ]
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>    