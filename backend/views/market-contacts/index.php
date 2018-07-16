<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\CommonHelper;
use yii\helpers\Url;

$this->title = 'Market Contacts List';
$formUrl = Url::to(['market-contacts/index/' . $market_id]);
?>
<div class="row" style="margin-top:30px">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    <?= Html::encode($this->title)?>
                </h3>
                <div class="row pull-right">

                    <div class="col-md-12">
                        <?= Html::beginForm($formUrl, 'get', ['data-pjax' => '', 'id' => 'search-users']); ?>
                        <div class="filter-search dataTables_filter clearfix">
                            <div class="row">

                                <div class="col-md-5">
                                    <?= Html::dropDownList('market_segment_id', isset($filters['market_segment_id']) ? $filters['market_segment_id'] : '', $segmentData, ['class' => 'form-control', 'id' => 'user-type', 'prompt' => 'Select market segment']) ?>
                                </div>
                                <div class="col-md-4">
                                    <?= Html::input('text', 'search', isset($filters['search']) ? $filters['search'] : '', ['class' => 'form-control', 'placeholder' => 'Search', 'id' => 'user-text']) ?>
                                </div>
                                <div class="col-md-3">
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
                            [
                            'label' => 'Market Cluster',
                            'attribute' => 'marketSegment.title',
                        ],
                        'phone',
                        'email',
                            [
                            'class' => 'yii\grid\ActionColumn',
                            'header' => 'Actions',
                            'template' => '{view} {update} {delete}',
                            'buttons' => [
                                'view' => function ($url, $model) use ($market_id) {
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['market-contacts/view/' . $market_id . '/' . $model['id']],['title'=>'View']);
                                },
                                'update' => function ($url, $model) use($market_id) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['market-contacts/index/' . $market_id . '/' . $model['id']],['title'=>'Update']);
                                },
                                'delete' => function ($url, $model) use ($market_id) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['market-contacts/delete/' . $market_id . '/' . $model['id']], ['data-method' => 'post', 'data-confirm' => 'Are you sure want to delete this category?','title'=>'Delete']);
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
    $("body").on("change", "#user-type,#user-text,#user-limit", function (event) {
        $('#search-users').submit();
    });
</script>