<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\CommonHelper;
use yii\helpers\Url;

$this->title = 'Stores';
$this->params['breadcrumbs'][] = $this->title;
$formUrl = Url::to(['stores/index']);
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
                        <?php  if(CommonHelper::checkPermission('Stores.Create')){ ?>
                        <?= Html::a('New Store', ['create'], ['class' => 'btn btn-primary']) ?>
                        <?php } ?>
                    </div>
                    <div class="col-md-10">
                        <?= Html::beginForm($formUrl, 'get', ['data-pjax' => '', 'id' => 'search-stores']); ?>
                        <div class="filter-search dataTables_filter clearfix">
                            <div class="row">
                                <div class="col-md-3">
                                    <?= Html::input('text', 'search', isset($filters['search']) ? $filters['search'] : '', ['class' => 'form-control','placeholder'=>'Search','id' => 'user-text']) ?>
                                </div>
                                <div class="col-md-3">
                                    <?= Html::dropDownList('market_id', isset($filters['market_id']) ? $filters['market_id'] : '' ,$markets,  ['class' => 'form-control', 'id' => 'user-type','prompt' => 'Select Market']) ?>
                                </div>
                                <div class="col-md-3">
                                    <?= Html::dropDownList('market_id', isset($filters['market_id']) ? $filters['market_id'] : '' ,$markets,  ['class' => 'form-control', 'id' => 'user-type','prompt' => 'Select User Type']) ?>
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
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,

                    'layout'=>'<div class="table-responsive">{items}</div><div class="row"><div class="col-sm-5">{summary}</div><div class="col-sm-7"><div class="dataTables_paginate paging_simple_numbers">{pager}</div></div></div>',

                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'name',
                        'market',
                        'marketSegment',
                        'address1',
                        'assignTo',
                        [
                           'class' => 'yii\grid\ActionColumn',
                           'header' => 'Actions',
                           'template' => '{view} {update} {delete} {manageUser}',
                           'buttons' => [
                               'view' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['stores/view/'.$model['id']]);
                                },
                               'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['stores/update/'.$model['id']]);
                                },                                
                               'delete' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['stores/delete/'.$model['id']],['data-method'=>'post','data-confirm'=>'Are you sure want to delete this user?']);
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $("body").on("change", "#user-type,#user-text,#user-limit",function(event){
        $('#search-stores').submit();
    });
</script>