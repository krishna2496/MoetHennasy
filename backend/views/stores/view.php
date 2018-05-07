<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use common\helpers\CommonHelper;

$updateUrl = Url::to(['stores/update/'.$model->id]);
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Stores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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
                        'name',
                        [
                            'attribute'=>'photo',
                            'value'=>CommonHelper::getImage(UPLOAD_PATH_STORE_IMAGES . $model->photo),
                            'format' => ['image',['width'=>'100']],
                        ],
                        [
                            'label' => 'Market',
                            'attribute' => 'market.title',
                        ],
                        [
                            'label' => 'Market Segment',
                            'attribute' => 'marketSegment.title',
                        ],
                        'address1:ntext',
                        'address2:ntext',
                        [
                            'label' => 'Province',
                            'attribute' => 'province.name',
                        ],
                        [
                            'label' => 'City',
                            'attribute' => 'city.name',
                        ],
                        [
                            'label' => 'Country',
                            'attribute' => 'country.name',
                        ],
                        
                        'latitude',
                        'longitude',
                        'comment:ntext',
                        [
                            'label' => 'Assign To',
                            'value' => $model->user['first_name'].' '.$model->user['last_name']
                        ],
                        'store_manager_first_name',
                        'store_manager_last_name',
                        'store_manager_email:email',
                        'store_manager_phone_code',
                        'store_manager_phone_number',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div> 
