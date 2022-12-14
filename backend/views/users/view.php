<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use common\helpers\CommonHelper;

$this->title = $model->first_name.' '.$model->last_name;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$updateUrl = Url::to(['users/update/'.$model->id]);
if($isUpdateParent) {
    $updateUrl = Url::to(['users/update/'.$model->id.'/'.$parentId]);
}
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
                        'username',
                        'email:email',
                        'first_name',
                        'last_name',
                        'role.title',
                        [
                            'attribute'=>'profile_photo',
                            'value'=>CommonHelper::getImage(UPLOAD_PATH_USER_IMAGES . $model->profile_photo),
                            'format' => ['image',['width'=>'100']],
                        ],
                        [
                            'label' => 'Market',
                            'attribute' => 'market.title'
                        ],
                        [                      // the owner name of the model
                            'label' => 'Status',
                            'value' => isset(Yii::$app->params['status'][$model->status]) ? Yii::$app->params['status'][$model->status] : '',
                        ],
                        'phone',
                        'company_name',
                        'address',
                        'latitude',
                        'longitude',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>    