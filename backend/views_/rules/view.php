<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use common\helpers\CommonHelper;
$this->title='Rules';
$updateUrl = Url::to(['rules/update/'.$model->id]);
$this->params['breadcrumbs'][] = ['label' => 'Rules', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->type;
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
                            'type',
                            'product_fields',
                            'detail',
                         [
                'attribute'=>'image',
                'value'=>CommonHelper::getImage(UPLOAD_PATH_RULES_IMAGES . $model->image),
                'format' => ['image',['width'=>'100']],
            ],
                    ],
                   
                ]) ?>
            </div>
        </div>
    </div>
</div> 
