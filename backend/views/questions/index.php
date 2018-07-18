<?php
use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\CommonHelper;
use yii\helpers\Url;

$this->title = 'Feedback Questions';
$this->params['breadcrumbs'][] = $this->title;
$formUrl = Url::to(['questions/index']);
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    <?= Html::encode($this->title) ?>
                </h3>
                <div class="row pull-right">
                    <div class="col-md-5">
                        <?php  if(CommonHelper::checkPermission('Questions.Create')){ ?>
                        <?= Html::a('Add Feedback Question', ['create'], ['class' => 'btn btn-primary']) ?>
                        <?php } ?>
                    </div>
                    <div class="col-md-7">
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
                       
                       [
                             'attribute' => 'question',
                             'label' =>'Feedback question',  
                       ],
                        [
                            'attribute' => 'response_type',
                            'value' => function ($model) {
                               
                                 return  Yii::$app->params['response_type'][$model['response_type']];
                             },
                        ],
                        [
                           'class' => 'yii\grid\ActionColumn',
                           'header' => 'Actions',
                           'template' => '{view} {update} {delete}',
                           'buttons' => [
                               'view' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['questions/view/'.$model['id']],['title'=>'View']);
                                },
                               'update' => function ($url, $model) {
                                    $addLink = isset($filters['setParentID']) ? '/'.$model['parent_user_id'] : '';
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['questions/update/'.$model['id']],['title'=>'Update']);
                                },                                
                               'delete' => function ($url, $model) {
                                    $addLink = isset($filters['setParentID']) ? '/'.$model['parent_user_id'] : '';
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['questions/delete/'.$model['id']],['data-method'=>'post','data-confirm'=>'Are you sure want to delete this Questions?','title'=>'Delete']);
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