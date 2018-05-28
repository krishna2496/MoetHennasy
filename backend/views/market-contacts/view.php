<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use common\helpers\CommonHelper;

$this->title = $model->market_segment_id;
$this->params['breadcrumbs'][] = ['label' => 'Market Contacts', 'url' => ['index/'.$market_id]];
$this->params['breadcrumbs'][] = $model->market_segment_id;
$updateUrl = Url::to(['market-contacts/index/'.$market_id.'/'.$model->id]);
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
               
                <?= Html::a('Update', $updateUrl , ['class' => 'btn btn-primary pull-right']) ?>
            </div>
            <div class="box-body">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                      
                        [
                            'label' => 'Market segment',
                            'attribute' => 'marketSegment.title'
                        ],
                      
                        'address:ntext',
                        'phone',
                        'email:email',
                      
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>    