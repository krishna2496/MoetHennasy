<?php
use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\CommonHelper;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\bootstrap\Tabs;

$this->title = 'Configs';
$this->params['breadcrumbs'][] = ['label' => 'Stores', 'url' => ['/stores']];
$this->params['breadcrumbs'][] = $this->title;

$formUrl = Url::to(['configs/index/'.$id]);
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
                        <?php  if(CommonHelper::checkPermission('Configs.Create')){ ?>
                            <?= Html::a('Back', ['stores/index'], ['class' => 'btn btn-primary']) ?>
                        <?php } ?>
                    </div>
                    <div class="col-md-2">
                        <?php  if(CommonHelper::checkPermission('Configs.Create')){ ?>
                            <?= Html::a('Add Config', ['configs/create/'.$id], ['class' => 'btn btn-primary']) ?>
                        <?php } ?>
                    </div>
                    <div class="col-md-7">
                        <?= Html::beginForm($formUrl, 'get', ['data-pjax' => '', 'id' => 'search-users']); ?>
                        <div class="filter-search dataTables_filter clearfix">
                            <div class="row">
                                <div class="col-md-7">
                                    <?= Html::input('text', 'search', isset($filters['search']) ? $filters['search'] : '', ['class' => 'form-control','placeholder'=>'Search','id' => 'brands-text']) ?>
                                    <span id="searchclear" class="glyphicon glyphicon-remove"></span>
                                </div>
                                <div class="col-md-5">
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
                        'value',
                        [
                           'class' => 'yii\grid\ActionColumn',
                           'header' => 'Actions',
                           'template' => '{view} {update} {delete} {review}',
                           'buttons' => [
                               'view' => function ($url, $model) use ($id) {
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['configs/view/'.$model['id'].'/'.$id]);
                                },
                               'update' => function ($url, $model) use ($id) {
                                       return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['configs/update/'.$model['id'].'/'.$id]);
                                },                                
                               'delete' => function ($url, $model) use ($id) {
                                   return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['configs/delete/'.$model['id'].'/'.$id],['data-method'=>'post','data-confirm'=>'Are you sure want to delete this config?']);
                                },
                                'review' => function ($url, $model) use ($id) {
                                    return Html::a('Review', ['configs/review/'.$model['id'].'/'.$id], ['class'=>'filter-search btn btn-primary']);
                                 
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
 $(function(){
    $('#modelButton').click(function(){
        var id=$(this).attr('modalConfig');
        $("#config_id").val(id);
        $('.modal').modal('show')
            .find('#modelContent')
            .load($(this).attr('value'));
    });
    
});

</script>