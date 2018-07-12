<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\CommonHelper;
use yii\helpers\Url;

$this->title = 'Catalogues';
$this->params['breadcrumbs'][] = $this->title;
$formUrl = Url::to(['catalogues/index']);
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
<?= Html::encode($this->title) ?>
                </h3>
                <div class="row pull-right">
                    <div class="col-md-2">
                        <?php if (CommonHelper::checkPermission('Catalogues.Create')) { ?>
                            <?= Html::a('New Catalogue', ['create'], ['class' => 'btn btn-primary']) ?>
<?php } ?>
                    </div>
                   
                    <div class="col-md-10">
<?= Html::beginForm($formUrl, 'get', ['data-pjax' => '', 'id' => 'search-catalogue']); ?>
                        <div class="filter-search dataTables_filter clearfix">
                            <div class="row">
                                <div class="col-md-3">
<?= Html::input('text', 'search', isset($filters['search']) ? $filters['search'] : '', ['class' => 'form-control', 'placeholder' => 'Search', 'id' => 'user-text']) ?>
                                </div>

                                 <div class="col-md-4">
<?= Html::dropDownList('brand_id', isset($filters['brand_id']) ? $filters['brand_id'] : '', $brand, ['class' => 'form-control', 'id' => 'brand_market', 'prompt' => 'Select Brand']) ?>
                                </div>
                                <div class="col-md-3">
<?= Html::dropDownList('product_id', isset($filters['product_id']) ? $filters['product_id'] : '', $product, ['class' => 'form-control', 'id' => 'product_id', 'prompt' => 'Select Product Category']) ?>
                                </div>
                                <div class="col-md-2">
                                    <?= Html::dropDownList('limit', isset($filters['limit']) ? $filters['limit'] : '' ,Yii::$app->params['limit'],  ['class' => 'form-control','id' => 'user-limit']) ?>
                                </div>

                            </div>
                        </div>
<?= Html::endForm(); ?>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <?=
                GridView::widget([
                    'dataProvider' => $dataProvider,
                    'layout' => '<div class="table-responsive">{items}</div><div class="row"><div class="col-sm-5">{summary}</div><div class="col-sm-7"><div class="dataTables_paginate paging_simple_numbers">{pager}</div></div></div>',
                    'columns' => [
                            [
                            'class' => 'yii\grid\SerialColumn'],
                       
                           
                        'sku',
                        'ean',
                           
                            [
                            'label' => 'Brand',
                            'attribute' => 'brandName',
                            'value' => 'brand.name'
                        ],
                            'price',
                            [
                            'class' => 'yii\grid\ActionColumn',
                            'header' => 'Actions',
                            'template' => '{view} {update} {delete}',
                            'buttons' => [
                                'view' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['catalogues/view/' . $model['id']],['title'=>'View']);
                                },
                                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['catalogues/update/' . $model['id']],['title'=>'Update']);
                                },
                                'delete' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['catalogues/delete/' . $model['id']], ['data-method' => 'post', 'data-confirm' => 'Are you sure want to delete this catalogues?','title'=>'Delete']);
                                },
                            ],
                        ],
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $("body").on("change", "#user-type,#product_id,#user-text,#user-limit,#user-market,#brand_market", function (event) {
       $('#search-catalogue').submit();
    });
</script>