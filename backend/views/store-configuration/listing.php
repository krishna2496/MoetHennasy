<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\CommonHelper;
use yii\helpers\Url;

$this->title = 'Store Configuration';
$this->params['breadcrumbs'][] = $this->title;
$formUrl = Url::to(['store-configuration/listing']);
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
<?= Html::encode($this->title) ?>
                </h3>
                <div class="row pull-right">
                    <div class="col-md-4">
                      
                            <?= Html::a('New Store Configuration', ['store-configuration/index/'.$id], ['class' => 'btn btn-primary']) ?>

                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-md-6">
<?= Html::beginForm($formUrl, 'get', ['data-pjax' => '', 'id' => 'search-catalogue']); ?>
                        <div class="filter-search dataTables_filter clearfix">
                            <div class="row">

                                <div class="col-md-8">
                                    <?= Html::dropDownList('limit', isset($filters['limit']) ? $filters['limit'] : '' ,Yii::$app->params['limit'],  ['class' => 'form-control','id' => 'user-limit']) ?>
                                </div>

                            </div>
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
                       
                           
                            'config_name',
                 
                            [
                            'class' => 'yii\grid\ActionColumn',
                            'header' => 'Actions',
                            'template' => '{update} {delete}',
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