<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\CommonHelper;
use yii\helpers\Url;

$this->title = 'Store Configuration';
$this->params['breadcrumbs'][] = $this->title;
$formUrl = Url::to(['store-configuration/listing/'.$id]);
?>
<style>
    .grid-view .table .list-image {
    border-radius: 100%;
    height: 70px;
    width: 80px;
    overflow: hidden;
    display: inline-block;
    vertical-align: middle;
    margin-right: 20px;
    clear: both;
}
.grid-view .table .list-image img{
    height: 81px;
    width: 100px;
    vertical-align: middle;
    margin-left: 0px;
    margin-top: -8px
}
    </style>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    <?= Html::encode($this->title) ?>
                </h3>
                <div class="row">
                    <div class="col-md-8"></div>
                    <div class="col-md-2">
                      <?php 
                      if($canCreateNewConfig == 1){
                      ?>
                            <?= Html::a('Add Store Configuration', ['store-configuration/index/'.$id], ['class' => 'btn btn-primary']) ?>
                      <?php } ?>
                    </div>
                    <div class="col-md-2">
                    <?= Html::beginForm($formUrl, 'get', ['data-pjax' => '', 'id' => 'search-catalogue']); ?>
                        <div class="filter-search dataTables_filter clearfix">
                            <?= Html::dropDownList('limit', isset($filters['limit']) ? $filters['limit'] : '' ,Yii::$app->params['limit'],  ['class' => 'form-control','id' => 'user-limit']) ?>
                        </div>
<?= Html::endForm(); ?>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <?=
                GridView::widget([
                    'dataProvider' => $dataProvider,
                    'layout' => '<div class="table-responsive">{items}</div><div class="row"><div class="col-sm-5">{summary}</div><div class="col-sm-7"><div class="dataTables_paginate paging_simple_numbers">{pager}</div></div></div>',
                    'columns' => [
                            [
                            'class' => 'yii\grid\SerialColumn'],
                           
                            [
                                'label' => 'Configuration',   
                                'format' => 'html',
                                'attribute' => 'config_name', 
                                'headerOptions' => ['class' => 'column-name-image'],
                                'value' => function($model,$index,$dataColumn) {
                                    return '<div class="list-image"><img src="' . CommonHelper::getImage(UPLOAD_PATH_STORE_CONFIG_IMAGES.$model['shelf_thumb']) . '"></div>'.$model['config_name'].'</div>';
                                }
                            ],
                           
                            [
                                'label' => 'Rating',
                                'attribute' => 'star_ratings',
                                'value' => function($model,$index,$dataColumn) {
                                if($model['star_ratings'] == ''){
                                    return '-';
                                }
                                     return $model['star_ratings'];
                                }
                            ],
                            [
                            'class' => 'yii\grid\ActionColumn',
                            'header' => 'Actions',
                            'template' => '{update}',
                            'buttons' => [
                                
                                'update' => function ($url, $model)use($id) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['store-configuration/update-config/'.$id.'/'. $model['id']]);
                                },
                                'delete' => function ($url, $model)use($id) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['store-configuration/delete/'.$id.'/'. $model['id']], ['data-method' => 'post', 'data-confirm' => 'Are you sure want to delete this catalogues?']);
                                },
                            ],
                        ],
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $("body").on("change", "#user-type,#product_id,#user-text,#user-limit,#user-market,#brand_market", function (event) {
       $('#search-catalogue').submit();
    });
</script>