<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\CommonHelper;
use yii\helpers\Url;

$this->title = 'Help Categories';
$this->params['breadcrumbs'][] = $this->title;
$formUrl = Url::to(['help-categories/index']);
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    <?= Html::encode($this->title) ?>
                </h3>
                <div class="row pull-right">
                    <div class="col-md-4">
                        <?php  if(CommonHelper::checkPermission('HelpCategories.Create')){ ?>
                        <?= Html::a('New Category', ['create'], ['class' => 'btn btn-primary']) ?>
                        <?php } ?>
                    </div>
                    <div class="col-md-5">
                        <?= Html::beginForm($formUrl, 'get', ['data-pjax' => '', 'id' => 'search-users']); ?>
                        <div class="filter-search dataTables_filter clearfix">
                            <?= Html::input('text', 'search', isset($filters['search']) ? $filters['search'] : '', ['class' => 'form-control', 'placeholder' => 'Search', 'id' => 'user-text']) ?>
                        </div>
                    </div>
                        <?= Html::endForm(); ?>
                    <div class="col-md-3">
                        <?= Html::dropDownList('limit', isset($filters['limit']) ? $filters['limit'] : '' ,Yii::$app->params['limit'],  ['class' => 'form-control','id' => 'user-limit']) ?>
                    </div>
                </div>
                </div>
            </div>
            <div class="box-body">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,

                    'layout'=>'<div class="table-responsive">{items}</div><div class="row"><div class="col-sm-5">{summary}</div><div class="col-sm-7"><div class="dataTables_paginate paging_simple_numbers">{pager}</div></div></div>',

                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                          'title',
                          
                        [
                           'class' => 'yii\grid\ActionColumn',
                           'header' => 'Actions',
                           'template' => '{update} {delete}{question}',
                           'buttons' => [
//                               'view' => function ($url, $model) {
//                                  return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['help-categories/view/'.$model['id']],['title'=>'View']);
//                                },
                               'update' => function ($url, $model) {
                                  return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['/help-categories/update/'.$model['id']],['title'=>'Update']);
                                },                                
                               'delete' => function ($url, $model) {
                                  return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['help-categories/delete/'.$model['id']],['data-method'=>'post','data-confirm'=>'Are you sure want to delete this category?','title'=>'View']);
                                }, 
                                'question' => function ($url, $model) {
                                   return Html::a('Add Questions', ['helps/index/'.$model['id']], ['class'=>'filter-search btn btn-primary']);
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