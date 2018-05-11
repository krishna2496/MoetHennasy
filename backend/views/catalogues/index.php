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
                    <div class="col-md-1"></div>
                    <div class="col-md-8">
<?= Html::beginForm($formUrl, 'get', ['data-pjax' => '', 'id' => 'search-catalogue']); ?>
                        <div class="filter-search dataTables_filter clearfix">
                            <div class="row">
                                <div class="col-md-5">
<?= Html::input('text', 'search', isset($filters['search']) ? $filters['search'] : '', ['class' => 'form-control', 'placeholder' => 'Search', 'id' => 'user-text']) ?>
                                </div>

                                <div class="col-md-4">
<?= Html::dropDownList('market_id', isset($filters['market_id']) ? $filters['market_id'] : '', $market, ['class' => 'form-control', 'id' => 'user-market', 'prompt' => 'Select Market']) ?>
                                </div>
                                <div class="col-md-3">
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
                        'id',
                            [
                            'label' => 'Image',
                            'format' => 'html',
                            'attribute' => 'image',
                            'headerOptions' => ['class' => 'column-name-image'],
                            'value' => function($model, $index, $dataColumn) {
                                return '<div><img  style="width:100px;height:100px;border:100%" src="' . CommonHelper::getImage(UPLOAD_PATH_CATALOGUES_IMAGES . $model['image']) . '"></div>';
                            }
                        ],
                        'sku',
                        'ean',
                            [
                            'label' => 'Market',
                            'attribute' => 'marketName',
                            'value' => 'market.title'
                        ],
                            [
                            'class' => 'yii\grid\ActionColumn',
                            'header' => 'Actions',
                            'template' => '{view} {update} {delete}',
                            'buttons' => [
                                'view' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['catalogues/view/' . $model['id']]);
                                },
                                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['catalogues/update/' . $model['id']]);
                                },
                                'delete' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['catalogues/delete/' . $model['id']], ['data-method' => 'post', 'data-confirm' => 'Are you sure want to delete this catalogues?']);
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
    $("body").on("change", "#user-type,#user-text,#user-limit,#user-market", function (event) {
       $('#search-catalogue').submit();
    });
</script>