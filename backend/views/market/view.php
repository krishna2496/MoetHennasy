<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use common\helpers\CommonHelper;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Market', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$updateUrl = Url::to(['market/update/'.$model->id]);
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
                        'title',
                        [
                            'label' => 'Market segment',
                            'attribute' => 'marketSegment.title'
                        ],
                      
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>    