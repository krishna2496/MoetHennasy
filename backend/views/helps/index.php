<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\CommonHelper;
use yii\helpers\Url;

$this->title = 'Helps';
$this->params['breadcrumbs'][] = $this->title;
$formUrl = Url::to(['helps/index/'.$id]);
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
                        <?php  if(CommonHelper::checkPermission('Helps.Create')){ ?>
                        <?= Html::a('New Questions', ['helps/create/'.$id], ['class' => 'btn btn-primary']) ?>
                        <?php } ?>
                    </div>
                    <div class="col-md-10">
                        <?= Html::beginForm($formUrl, 'get', ['data-pjax' => '', 'id' => 'search-users']); ?>
                        <div class="filter-search dataTables_filter clearfix">
                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="col-md-6">
            <?= Html::input('text', 'search', isset($filters['search']) ? $filters['search'] : '', ['class' => 'form-control', 'placeholder' => 'Search', 'id' => 'user-text']) ?>
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
                          'question:ntext',
                         
                        [
                           'class' => 'yii\grid\ActionColumn',
                           'header' => 'Actions',
                           'template' => '{view} {update} {delete}',
                           'buttons' => [
                               'view' => function ($url, $model) use ($id){
                                  return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['helps/view/'.$model['id'].'/'.$id]);
                                },
                               'update' => function ($url, $model)use ($id) {
                                  return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['helps/update/'.$model['id'].'/'.$id]);
                                },                                
                               'delete' => function ($url, $model)use ($id) {
                                  return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['helps/delete/'.$model['id'].'/'.$id],['data-method'=>'post','data-confirm'=>'Are you sure want to delete this Question and Answer?']);
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