<?php

use common\helpers\CommonHelper;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Stores';
$this->params['breadcrumbs'][] = $this->title;
$formUrl = Url::to(['stores/index']);

$urlData = explode('?', Yii::$app->getRequest()->getUrl());
$queryString = '';
if(isset($urlData[1]) && $urlData[1]){
    $queryString = '?'.$urlData[1];
}
//echo '<pre>';
//print_r($dataProvider->totalCount);exit;
?>
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    <?= Html::encode($this->title) ?>
                </h3>
                <div class="row">
                    <div class="col-md-3">
                        <div class="row addmargin">
                            <div class="col-md-5">
                                <?php  if(CommonHelper::checkPermission('Stores.Create')){ ?>
                                <?= Html::a('New Store', ['create'], ['class' => 'btn btn-primary']) ?>
                                <?php } ?>
                            </div>
                            <div class="col-md-4">
                                 <?php  if(CommonHelper::checkPermission('Stores.Export')){
                                $class = '';
                                if($dataProvider->totalCount == 0){
                                    $class = 'disabled';
                                }
                                ?>
                                <?= Html::a('Export', ['export'.$queryString], ['class' => 'btn btn-primary'.' '.$class.'']) ?>
                                <?php } ?>
                            </div>
                            <div class="col-md-3"></div>
                        </div>
                    </div>
                    <div class="col-md-9"></div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-2">
                                    <?= Html::beginForm($formUrl, 'get', ['data-pjax' => '', 'id' => 'search-stores']); ?>
                                    <div class="filter-search dataTables_filter clearfix">
                                    <?= Html::input('text', 'search', isset($filters['search']) ? $filters['search'] : '', ['class' => 'form-control','placeholder'=>'Search','id' => 'store-text']) ?>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <?= Html::dropDownList('market_id', isset($filters['market_id']) ? $filters['market_id'] : '' ,$markets,  ['class' => 'form-control select2', 'id' => 'store-markets','prompt' => 'Select Market']) ?>
                                </div>
                                <div class="col-md-2">
                                    <?= Html::dropDownList('market_segment_id', isset($filters['market_segment_id']) ? $filters['market_segment_id'] : '' ,$marketSegments,  ['class' => 'form-control select2', 'id' => 'store-market_segment','prompt' => 'Select Cluster']) ?>
                                </div> 
                                <div class="col-md-2">
                                    <?= Html::dropDownList('country_id', isset($filters['country_id']) ? $filters['country_id'] : '' ,$countries,  ['class' => 'form-control select2', 'id' => 'store-country','prompt' => 'Select Country']) ?>
                                </div>
                                <div class="col-md-2">
                                    <?= Html::dropDownList('city_id', isset($filters['city_id']) ? $filters['city_id'] : '' ,$cities,  ['class' => 'form-control select2', 'id' => 'store-city','prompt' => 'Select City']) ?>
                                </div>
                                <div class="col-md-2">
                                    <?= Html::dropDownList('limit', isset($filters['limit']) ? $filters['limit'] : '' ,Yii::$app->params['limit'],  ['class' => 'form-control','id' => 'store-limit']) ?>
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
                           'template' => '{view} {update} {delete}{congiguration}',
                           'buttons' => [
                               'view' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['stores/view/'.$model['id']],['title'=>'View']);
                                },
                               'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['stores/update/'.$model['id']],['title'=>'Update']);
                                },                                
                               'delete' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['stores/delete/'.$model['id']],['data-method'=>'post','data-confirm'=>'Are you sure want to delete this store?','title'=>'Delete']);
                                },
                               
                                'congiguration' => function ($url, $model) {
                                   return Html::a('Configuration', ['/store-configuration/listing/'.$model['id']], ['class'=>'btn btn-primary','style'=>'margin-left: 15px;']);
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