<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use common\helpers\CommonHelper;

$updateUrl = Url::to(['catalogues/update/'.$model->id]);
$this->title = $model->sku;
$this->params['breadcrumbs'][] = ['label' => 'Catalogues', 'url' => ['index']];
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
                       'sku',
            'ean',
            [
                'attribute'=>'image',
                'value'=>CommonHelper::getImage(UPLOAD_PATH_CATALOGUES_IMAGES . $model->image),
                'format' => ['image',['width'=>'100']],
            ],
            'short_name',
            'long_name',
            'short_description:ntext',
           
            [
                            'label' => 'Brands',
                            'attribute' => 'brand.name',
            ],
            [
                            'label' => 'Product category',
                            'attribute' => 'productCategory.name',
            ],
            [
                            'label' => 'Product sub category',
                            'attribute' => 'productSubCategory.name',
            ],
            'product_type_id',
            [
                            'label' => 'Market',
                            'attribute' => 'market.title',
            ],
            'width',
            'height',
            'length',
            'scale',
            'manufacturer',
            [
                            'label' => 'box_only',
                            'value' => Yii::$app->params['catalogue_status'][$model->box_only],
            ],
            'market_share',
            'price',
            [
                            'label' => 'top_shelf',
                            'value' => Yii::$app->params['catalogue_status'][$model->top_shelf],
            ],
              ],
                ]) ?>
            </div>
        </div>
    </div>
</div> 
