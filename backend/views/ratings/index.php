<?php
use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\CommonHelper;
use yii\helpers\Url;

$this->title = 'Ratings';
$this->params['breadcrumbs'][] = $this->title;
$formUrl = Url::to(['ratings/index']);
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    <?= Html::encode($this->title) ?>
                </h3>
                <div class="row pull-right">
                    <div class="col-md-3">
                        <?php  if(CommonHelper::checkPermission('Brands.Create')){ ?>
                        <?= Html::a('New Ratings', ['create'], ['class' => 'btn btn-primary']) ?>
                        <?php } ?>
                    </div>
                    <div class="col-md-9">
                        <?= Html::beginForm($formUrl, 'get', ['data-pjax' => '', 'id' => 'search-users']); ?>
                        <div class="filter-search dataTables_filter clearfix">
                            <div class="row">
                                <div class="col-md-8">
                                    <?= Html::input('text', 'search', isset($filters['search']) ? $filters['search'] : '', ['class' => 'form-control','placeholder'=>'Search','id' => 'brands-text']) ?>
                                </div>
                                <div class="col-md-4">
                                    <?= Html::dropDownList('limit', isset($filters['limit']) ? $filters['limit'] : '' ,Yii::$app->params['limit'],  ['class' => 'form-control','id' => 'brands-limit']) ?>
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
                          'rating',
                          'type',
                        [
                           'class' => 'yii\grid\ActionColumn',
                           'header' => 'Actions',
                           'template' => '{view}{update} {delete}',
                           'buttons' => [
                               'view' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['ratings/view/'.$model['id']],['title'=>'View']);
                                },
                               'update' => function ($url, $model) {
                                 return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['ratings/update/'.$model['id']],['title'=>'Update']);
                                },                                
                               'delete' => function ($url, $model) {
                                  return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['ratings/delete/'.$model['id']],['data-method'=>'post','data-confirm'=>'Are you sure want to delete this Ratings?','title'=>'Delete']);
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
    $("body").on("change", "#brands-text,#brands-limit",function(event){
        $('#search-users').submit();
    });
</script>