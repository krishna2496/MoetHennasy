<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\CommonHelper;
use yii\helpers\Url;

$this->title = 'Market';
$this->params['breadcrumbs'][] = $this->title;
$formUrl = Url::to(['market/index']);
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
                        <?php  if(CommonHelper::checkPermission('Market.Create')){ ?>
                        <?= Html::a('New Market', ['create'], ['class' => 'btn btn-primary']) ?>
                        <?php } ?>
                    </div>
                    <div class="col-md-10">
                        <?= Html::beginForm($formUrl, 'get', ['data-pjax' => '', 'id' => 'search-users']); ?>
                        <div class="filter-search dataTables_filter clearfix">
                            <div class="row">
                                <div class="col-md-1"> </div>
                                <div class="col-md-6">
                                    <?= Html::input('text', 'search', isset($filters['search']) ? $filters['search'] : '', ['class' => 'form-control','placeholder'=>'Search','id' => 'user-text']) ?>
                                </div>
                                 <div class="col-md-4">
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
                        'title:ntext',
                          [
                            'label' => 'Market segment',
                            'value' => function($model, $index, $dataColumn) {
                            $array;                              
                            foreach($model['marketSegmentData'] as $key=>$value){
                                $array[]=$value['marketSegment']['title'];
                            }                         
                            return implode(',',$array);
                        },
                         
                        ],
                          
                        [
                           'class' => 'yii\grid\ActionColumn',
                           'header' => 'Actions',
                           'template' => '{view} {update} {delete}',
                           'buttons' => [
                               'view' => function ($url, $model) use ($filters) {
                                  return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['market/view/'.$model['id']]);
                                },
                               'update' => function ($url, $model) use ($filters) {
                                     return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['/market/update/'.$model['id']]);
                                },                                
                               'delete' => function ($url, $model) use ($filters) {
                                  return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['market/delete/'.$model['id']],['data-method'=>'post','data-confirm'=>'Are you sure want to delete this market segment?']);
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