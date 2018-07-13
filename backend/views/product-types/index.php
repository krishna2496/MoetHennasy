<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\CommonHelper;
use yii\helpers\Url;

$this->title = 'Product Types';
$this->params['breadcrumbs'][] = $this->title;
$formUrl = Url::to(['product-types/index']);
?>
<div class="row" style="margin-top:30px">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    <?= Html::encode($this->title) ?>
                </h3>
                <div class="row pull-right">
               <div class="col-md-4">
                        <?php  if(CommonHelper::checkPermission('Market.Create')){ ?>
                        <?= Html::a('New Product Type', ['create'], ['class' => 'btn btn-primary']) ?>
                        <?php } ?>
                    </div>
                    <div class="col-md-8">
                        <?= Html::beginForm($formUrl, 'get', ['data-pjax' => '', 'id' => 'search-users']); ?>
                        <div class="filter-search dataTables_filter clearfix">
                            <div class="row">
                                <div class="col-md-8">
                                    <?= Html::input('text', 'search', isset($filters['search']) ? $filters['search'] : '', ['class' => 'form-control', 'placeholder' => 'Search', 'id' => 'user-text']) ?>
                                </div>
                                <div class="col-md-4">
                                    <?= Html::dropDownList('limit', isset($filters['limit']) ? $filters['limit'] : '', Yii::$app->params['limit'], ['class' => 'form-control', 'id' => 'user-limit']) ?>
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
                            ['class' => 'yii\grid\SerialColumn'],
                        'title',
                            [
                            'class' => 'yii\grid\ActionColumn',
                            'header' => 'Actions',
                            'template' => ' {update} {delete}',
                            'buttons' => [
//                                'view' => function ($url, $model) {
//                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['product-types/view/' . $model['id']]);
//                                },
                                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['product-types/update/' . $model['id']],['title'=>'Update']);
                                },
                                'delete' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['product-types/delete/'. $model['id']], ['data-method' => 'post', 'data-confirm' => 'Are you sure want to delete this prodcuct type ?','title'=>'Delete']);
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
    $("body").on("change", "#user-text,#user-limit", function (event) {
        $('#search-users').submit();
    });
</script>