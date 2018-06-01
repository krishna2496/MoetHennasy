<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\CommonHelper;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = 'Rules';
$this->params['breadcrumbs'][] = $this->title;
$formUrl = Url::to(['rules/index']);
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            
            <div class="box-body">
                <?php Pjax::begin(['id' => 'countries']) ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,

                    'layout'=>'<div class="table-responsive">{items}</div><div class="row"><div class="col-sm-5">{summary}</div><div class="col-sm-7"><div class="dataTables_paginate paging_simple_numbers">{pager}</div></div></div>',

                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                          'sku',
                          'product_type_id',
                          'market_share',
                            'top_shelf',
                        [
                           'class' => 'yii\grid\ActionColumn',
                           'header' => 'Actions',
                           'template' => '{view} {update} {delete}',
                           'buttons' => [
                               'view' => function ($url, $model){
                                
                                },
                               'update' => function ($url, $model) {
                                
                                },                                
                               'delete' => function ($url, $model) {
                             
                                }, 
                             ],
                        ],
                    ],
                ]); ?>
                <?php Pjax::end() ?>
            </div>
        </div>
    </div>
</div>
