<?php
use common\helpers\CommonHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

$formUrl = Url::to(['product-varietal/index']);
$this->title = 'Product Varietal';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    <?= Html::encode($this->title) ?>
                </h3>
                <div class="row">
                    <div class="col-md-3">
                        
                    </div>
                    <div class="col-md-9 pull-right">
                        <?= Html::beginForm($formUrl, 'get', ['data-pjax' => '', 'id' => 'search-varietal']); ?>
                        <div class="filter-search dataTables_filter clearfix">
                            <div class="row">  
                                <div class="col-md-2"></div>
                                <div class="col-md-3">
                                    <?php  if(CommonHelper::checkPermission('Product-Varietal.Create')){ ?>
                                    <?= Html::a('Add Product Varietal', ['create'], ['class' => 'btn btn-primary btn-disabled']) ?>
                                    <?php } ?>
                                </div>
                                <div class="col-md-5">
                                    <?= Html::input('text', 'search', isset($filters['search']) ? $filters['search'] : '', ['class' => 'form-control','placeholder'=>'Search','id' => 'varietal-text']) ?>
                                    <span id="searchclear" class="glyphicon glyphicon-remove"></span>
                                </div>
                                <div class="col-md-2">
                                    <?= Html::dropDownList('limit', isset($filters['limit']) ? $filters['limit'] : '' ,Yii::$app->params['limit'],  ['class' => 'form-control','id' => 'varietal-limit']) ?>
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
                        [
                            'label'=>'Name',
                            'attribute'=>'name',
                            'contentOptions'=>[ 'style'=>'width: 80%'],
                        ],
                        [
                           'class' => 'yii\grid\ActionColumn',
                            'contentOptions'=>[ 'style'=>'width: 10%'],
                           'header' => 'Actions',
                           'template' => '{update} {delete}',
                           'buttons' => [
                               'view' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['product-varietal/view/'.$model['id']],['title'=>'View']);
                                },
                               'update' => function ($url, $model) {
                                    $addLink = isset($filters['setParentID']) ? '/'.$model['parent_user_id'] : '';
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['product-varietal/update/'.$model['id']],['title'=>'Update']);
                                },                                
                               'delete' => function ($url, $model) {
                                    $addLink = isset($filters['setParentID']) ? '/'.$model['parent_user_id'] : '';
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['product-varietal/delete/'.$model['id']],['data-method'=>'post','data-confirm'=>'Are you sure want to delete this varietal?','title'=>'Delete']);
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
    $("body").on("change", "#categories-text,#categories-limit,#varietal-limit",function(event){
        $('#search-varietal').submit();
    });
</script>
