<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\CommonHelper;
use yii\helpers\Url;

$this->title = 'Rules';
$this->params['breadcrumbs'][] = $this->title;
$formUrl = Url::to(['rules/index']);
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    <?= Html::encode($this->title) ?>
                </h3>
                <div class="row pull-right">
                  
                    <div class="col-md-12">
                        <?= Html::beginForm($formUrl, 'get', ['data-pjax' => '', 'id' => 'search-users']); ?>
                        <div class="filter-search dataTables_filter clearfix">
                            <div class="row">
                                <div class="col-md-8">
                                <?= Html::input('text', 'search', isset($filters['search']) ? $filters['search'] : '', ['class' => 'form-control', 'placeholder' => 'Search', 'id' => 'user-text']) ?>
                                <span id="searchclear" class="glyphicon glyphicon-remove"></span>
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
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,

                    'layout'=>'<div class="table-responsive">{items}</div><div class="row"><div class="col-sm-5">{summary}</div><div class="col-sm-7"><div class="dataTables_paginate paging_simple_numbers">{pager}</div></div></div>',

                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                          'type',
                          'product_fields',
                          'detail',
                        [
                           'class' => 'yii\grid\ActionColumn',
                           'header' => 'Actions',
                           'template' => '{view} {update}',
                           'buttons' => [
                               'view' => function ($url, $model){
                                  return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['rules/view/'.$model['id']],['title'=>'View']);
                                },
                               'update' => function ($url, $model) {
                                  return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['rules/update/'.$model['id']],['title'=>'Update']);
                                },                                
                               'delete' => function ($url, $model) {
                                  return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['rules/delete/'.$model['id']],['data-method'=>'post','data-confirm'=>'Are you sure want to delete this rule','title'=>'Delete']);
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
        $('#search-users').submit();
    });
</script>