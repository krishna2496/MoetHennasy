<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\CommonHelper;
use yii\helpers\Url;

$this->title = 'Stores';
$this->params['breadcrumbs'][] = $this->title;
$formUrl = Url::to(['stores/index']);

$urlData = explode('?', Yii::$app->getRequest()->getUrl());
$queryString = '';
if(isset($urlData[1]) && $urlData[1]){
    $queryString = '?'.$urlData[1];
}
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
                        <div class="row">
                            <?php  if(CommonHelper::checkPermission('Stores.Create')){ ?>
                                <div class="col-md-6">
                                    <?= Html::a('New Store', ['create'], ['class' => 'btn btn-primary']) ?>
                                </div>
                            <?php } ?>
                            <?php  if(CommonHelper::checkPermission('Stores.Export')){ ?>
                                <div class="col-md-6">
                                    <?= Html::a('Export', ['export'.$queryString], ['class' => 'btn btn-primary']) ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-md-10">
                        <?= Html::beginForm($formUrl, 'get', ['data-pjax' => '', 'id' => 'search-stores']); ?>
                        <div class="filter-search dataTables_filter clearfix">
                            <div class="row">
                                <div class="col-md-2">
                                    <?= Html::input('text', 'search', isset($filters['search']) ? $filters['search'] : '', ['class' => 'form-control','placeholder'=>'Search','id' => 'store-text']) ?>
                                </div>
                                <div class="col-md-2">
                                    <?= Html::dropDownList('market_id', isset($filters['market_id']) ? $filters['market_id'] : '' ,$markets,  ['class' => 'form-control', 'id' => 'store-markets','prompt' => 'Select Market']) ?>
                                </div>
                                <div class="col-md-2">
                                    <?= Html::dropDownList('market_segment_id', isset($filters['market_segment_id']) ? $filters['market_segment_id'] : '' ,$marketSegments,  ['class' => 'form-control', 'id' => 'store-market_segment','prompt' => 'Select Cluster']) ?>
                                </div> 
                                <div class="col-md-2">
                                    <?= Html::dropDownList('country_id', isset($filters['country_id']) ? $filters['country_id'] : '' ,$countries,  ['class' => 'form-control', 'id' => 'store-country','prompt' => 'Select Country']) ?>
                                </div>
                                <div class="col-md-2">
                                    <?= Html::dropDownList('city_id', isset($filters['city_id']) ? $filters['city_id'] : '' ,$cities,  ['class' => 'form-control', 'id' => 'store-city','prompt' => 'Select City']) ?>
                                </div>
                                <div class="col-md-2">
                                    <?= Html::dropDownList('limit', isset($filters['limit']) ? $filters['limit'] : '' ,Yii::$app->params['limit'],  ['class' => 'form-control','id' => 'store-limit']) ?>
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
                        ['class' => 'yii\grid\SerialColumn',
                         
                            ],
                        
                        'name',
                        'market',
                        [
                            'label' =>'Market Cluster',
                            'attribute' => 'marketSegment',
                        ],
                        
                        'address1',
                        'assignTo',
                        [
                           'class' => 'yii\grid\ActionColumn', 'contentOptions'=>[ 'style'=>'width: 15%'],     
                           'header' => 'Actions',
                           'template' => '{view} {update} {delete}{config}',
                           'buttons' => [
                               'view' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['stores/view/'.$model['id']]);
                                },
                               'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['stores/update/'.$model['id']]);
                                },                                
                               'delete' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['stores/delete/'.$model['id']],['data-method'=>'post','data-confirm'=>'Are you sure want to delete this store?']);
                                },
                                'config' => function ($url, $model) {
                                   return Html::a('Config', ['configs/index/'.$model['id']], ['class'=>'btn btn-primary','style'=>'margin-left: 15px;']);
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
    $("body").on("change", "#store-text,#store-limit,#store-market_segment,#store-country,#store-city",function(event){
        $('#search-stores').submit();
    });
    $("body").on("change", "#store-markets",function(event){
        $('#store-market_segment').val('');
        $('#search-stores').submit();
    });
</script>