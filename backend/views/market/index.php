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
                                <div class="col-md-7">
                                    <?php echo Html::input('text', 'search', isset($filters['search']) ? $filters['search'] : '', ['class' => 'form-control','placeholder'=>'Search','id' => 'user-text'])?> 
                                    <span id="searchclear" class="glyphicon glyphicon-remove"></span>
                                </div>
                                 <div class="col-md-5">
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
                        [
                            'label' => 'Title',
                            'attribute' => 'title',
                            'contentOptions' => ['style' => 'width:20%'],
                        ],
                          [
                            'label' => 'Market Cluster',
                            'value' => function($model, $index, $dataColumn) {
                            $array=array();                              
                            foreach($model['marketSegmentData'] as $key=>$value){
                                $array[]=$value['marketSegment']['title'];
                            }                         
                            return implode(',',$array);
                        },
                            'contentOptions' => ['style' => 'width:40%'],    
                         
                        ],
                          
                        [
                           'class' => 'yii\grid\ActionColumn',
                           'header' => 'Actions',
                           'template' => '{view} {update} {delete} {contact}{rules} {category}',
                            'contentOptions' => ['style' => 'width:40%'],
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
                                     return Html::a('Apply Rules', ['apply/rules/'.$model['id']], ['class'=>'btn btn-primary']);
                                 
                                },
                                    /*
                                'brand' => function ($url, $model) use ($filters) {
                                     return Html::a('Apply Brand', ['apply/brands/'.$model['id']], ['class'=>'btn btn-primary']);
                                },*/
                                'category' => function ($url, $model) use ($filters) {
                                     return Html::a('Manage Strategy', null, ['class'=>'btn btn-primary', 'data-id'=>$model['id'], 'data-toggle'=>"modal", 'onClick'=>('$("[name=\'selectedMarkets\']").val("'.$model['id'].'");$("[id=\'marketName\']").text("'.$model['title'].'");'), 'data-target'=>"#modal-default"]);
                                },
                               
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-default" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Select Product Categories (<span id="marketName"></span>)</h4>
            </div>
            <input type="hidden" value="" id="selectedMarkets" name="selectedMarkets" />
            <div class="modal-body" style="text-align: center;">
                <?php 
                    $output = '';
//                    admin_url
                    foreach ($categories as $categoryKey=>$category){
                        $url = CommonHelper::getPath('admin_url');
                        $output .= Html::a($category['name'], null, ['class'=>'btn btn-primary','data-id'=>$category['id'], 'onClick'=>'location.href="'.$url.'apply/brands/'.$category['id'].'/"+$("[name=\'selectedMarkets\']").val();', 'data-toggle'=>"modal", 'data-target'=>"#modal-default"]); 
                        $output .= '&nbsp;&nbsp;&nbsp;';
                    }
                    echo $output;
                ?>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $("body").on("change", "#user-type,#user-text,#user-limit",function(event){
      
        $('#search-users').submit();
    });
</script>