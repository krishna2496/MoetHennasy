<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\CommonHelper;
use yii\helpers\Url;

$this->title = 'Markets';
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
                    <div class="col-md-3">
                        <?php  if(CommonHelper::checkPermission('Market.Create')){ ?>
                        <?= Html::a('Add Market', ['create'], ['class' => 'btn btn-primary']) ?>
                        <?php } ?>
                    </div>
                    <div class="col-md-9">
                        <?= Html::beginForm($formUrl, 'get', ['data-pjax' => '', 'id' => 'search-users']); ?>
                        <div class="filter-search dataTables_filter clearfix">
                            <div class="row">
                                <div class="col-md-8">
                                    <?php echo Html::input('text', 'search', isset($filters['search']) ? $filters['search'] : '', ['class' => 'form-control','placeholder'=>'Search','id' => 'user-text'])?> 
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
                            'label' => 'Market Cluster',
                            'value' => function($model, $index, $dataColumn) {
                            $array=array();                              
                            foreach($model['marketSegmentData'] as $key=>$value){
                                $array[]=$value['marketSegment']['title'];
                            }                         
                            return implode(',',$array);
                        },
                         
                        ],
                          
                        [
                           'class' => 'yii\grid\ActionColumn',
                           'header' => 'Actions',
                           'template' => '{view} {update} {delete} {contact}  {rules}  {brand}',
                           'buttons' => [
    
                               'view' => function ($url, $model) use ($filters) {
                                  return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['market/view/'.$model['id']],['title' => 'View']);
                                },
                               'update' => function ($url, $model) use ($filters) {
                                     return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['/market/update/'.$model['id']],['title' => 'Update']);
                                },                                
                               'delete' => function ($url, $model) use ($filters) {
                                  return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['market/delete/'.$model['id']],['data-method'=>'post','data-confirm'=>'Are you sure want to delete this market?','title' => 'Delete']);
                                },
                                'contact' => function ($url, $model) use ($filters) {
                                   return Html::a('<span class="glyphicon glyphicon-phone"></span>', ['market-contacts/index/'.$model['id']],['title' => 'Contact']);
                                },
                                'rules' => function ($url, $model) use ($filters) {
                                     return Html::a('Apply Rules', ['market/rules/'.$model['id']], ['class'=>'btn btn-primary']);
                                 
                                },
                                'brand' => function ($url, $model) use ($filters) {
                                     return Html::a('Apply Brand', ['market/brands/'.$model['id']], ['class'=>'btn btn-primary']);
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