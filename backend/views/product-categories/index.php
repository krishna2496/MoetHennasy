<?php
use common\helpers\CommonHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

$formUrl = Url::to(['product-categories/index']);
$this->title = 'Product Categories';
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
                        <?= Html::beginForm($formUrl, 'get', ['data-pjax' => '', 'id' => 'search-categories']); ?>
                        <div class="filter-search dataTables_filter clearfix">
                            <div class="row">  
                                <div class="col-md-2"></div>
                                <div class="col-md-3">
                                    <?php  if(CommonHelper::checkPermission('Product-Categories.Create')){ ?>
                                     
<!--                                        Html::a('Add Product Category', ['create'], ['class' => 'btn btn-primary', 'disabled' => 'disabled'])
                                        Html::a('Add Product Category', ['create'], ['class' => 'btn btn-primary', 'disabled' => 'disabled'])-->
                                        
                                        
                                    <button class="btn btn-primary" disabled="true">Add Product Category</button>
                                    <?php } ?>
                                </div>
                                <div class="col-md-5">
                                    <?= Html::input('text', 'search', isset($filters['search']) ? $filters['search'] : '', ['class' => 'form-control','placeholder'=>'Search','id' => 'categories-text']) ?>
                                    <span id="searchclear" class="glyphicon glyphicon-remove"></span>
                                </div>
                                <div class="col-md-2">
                                    <?= Html::dropDownList('limit', isset($filters['limit']) ? $filters['limit'] : '' ,Yii::$app->params['limit'],  ['class' => 'form-control','id' => 'categories-limit']) ?>
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
                           'template' => '{update}',
                           'buttons' => [
                               'view' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['product-categories/view/'.$model['id']],['title'=>'View']);
                                },
                               'update' => function ($url, $model) {
                                    $addLink = isset($filters['setParentID']) ? '/'.$model['parent_user_id'] : '';
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['product-categories/update/'.$model['id']],['title'=>'Update']);
                                },                                
                               'delete' => function ($url, $model) {
                                    $addLink = isset($filters['setParentID']) ? '/'.$model['parent_user_id'] : '';
                                    return Html::a('<span class="glyphicon glyphicon-trash" ></span>', ['product-categories/delete/'.$model['id']],['data-method'=>'post','data-confirm'=>'Are you sure want to delete this category?','title'=>'Delete']);
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
    $("body").on("change", "#categories-text,#categories-limit",function(event){
        $('#search-categories').submit();
    });
</script>
