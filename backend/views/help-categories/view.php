<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use common\helpers\CommonHelper;
$this->title='Help Categories';
$updateUrl = Url::to(['help-categories/update/'.$model->id]);
$this->params['breadcrumbs'][] = ['label' => 'Questions', 'url' => ['index']];
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
                         'title',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div> 
