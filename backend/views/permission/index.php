<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\CommonHelper;
use yii\helpers\Url;

$formUrl= Url::to(['permission/index']);
$this->title = 'Permissions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    <?= Html::encode($this->title) ?>
                </h3>
                <div class="row  pull-right">
                    <div class="col-md-2">
                        <?php if(CommonHelper::checkPermission('Permission.Create')){ ?>
                        <?php echo Html::a('Create', ['create'], ['class' => 'btn btn-success']) ?>
                        <?php } ?>
                    </div>
                    <div class="col-md-10">
                        <?= Html::beginForm($formUrl, 'get', ['data-pjax' => '', 'id' => 'search-users']); ?>
                        <div class="filter-search dataTables_filter clearfix">
                        <?= Html::dropDownList('limit', isset($filters['limit']) ? $filters['limit'] : '' ,Yii::$app->params['limit'],  ['class' => 'form-control','id' => 'permission-limit']) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <?php 
                //hide and unhide action coloumn based on conditions
                if (CommonHelper::checkPermission('Permission.Update') || CommonHelper::checkPermission('Permission.Delete')) {
                        $isActionColoum=1; // return true;
                } else {
                        $isActionColoum=0; // return false;
                }?>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'layout'=>'<div class="table-responsive">{items}</div><div class="row"><div class="col-sm-5">{summary}</div><div class="col-sm-7"><div class="dataTables_paginate paging_simple_numbers">{pager}</div></div></div>',
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'permission_label',
                        'permission_title',
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'visible' =>$isActionColoum,
                            'header'=> 'Action',
                            'headerOptions' => [
                                'style' => 'color:#004FA3'
                            ],
                            'template' => '{update} {delete}', 
                            'buttons'=>[
                                'update'=>function ($url,$model) { 
                                    if(CommonHelper::checkPermission('Permission.Update')){
                                        
                                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['/permission/update/'.$model['id']],[ 'title' => 'Update']);
                                    } 
                                },
                                'delete'=>function ($url,$model) { 
                                    if(CommonHelper::checkPermission('Permission.Delete')){

                                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/permission/delete/'.$model['id']],['data-method'=>'post','data-confirm'=>Yii::t("app", "delete_confirm"), 'title' => 'Delete']);
                                    } 
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
    $("body").on("change", "#permission-limit", function (event) {
         $('#search-users').submit();
    });
</script>