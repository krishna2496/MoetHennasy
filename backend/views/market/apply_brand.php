<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\CommonHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Market Brands';
$this->params['breadcrumbs'][] = ['label' => 'Market', 'url' => ['/market']];
$this->params['breadcrumbs'][] = $this->title;
$formUrl = Url::to(['market/brands/2']);
?>
<div class="row">
    <div class="row" id="isDisplay">
        <div class="col-xs-12">
            <div class="box">
                 
                <div class="box-body">
                    <div class="box-header">
                        <h2>
                        <?= $title; ?>
                        </h2>
                     <div class="row pull-right">
                        <div class="col-md-12">
                            <?= Html::beginForm($formUrl, 'get', ['data-pjax' => '', 'id' => 'search-users']); ?>
                        <div class="filter-search dataTables_filter clearfix">
                            <?= Html::dropDownList('limit', isset($filters['limit']) ? $filters['limit'] : '' ,Yii::$app->params['limit'],  ['class' => 'form-control','id' => 'user-limit']) ?>
                        </div>
                             <?= Html::endForm(); ?>
                        </div>
                    </div>
                    </div>
<?php $form = ActiveForm::begin(['method' => 'post', 'options' => ['data-pjax' => '', 'id' => 'w1']]); ?>
    
<?=
GridView::widget([
    'dataProvider' => $dataProvider,
    'layout' => '<div class="table-responsive">{items}</div><div class="row"><div class="col-sm-5">{summary}</div><div class="col-sm-7"><div class="dataTables_paginate paging_simple_numbers">{pager}</div></div></div>',
    'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
            'class' => 'yii\grid\CheckboxColumn',
            'checkboxOptions' => function($model) use ($rules) {
                return ['value' => $model['id'], 'checked' => (in_array($model['id'], $rules)) ? true : ''
                ];
            },
        ],
      'name'
    ],
]);
?>
                </div>
                <div class="row">

                    <input type="hidden" value="" name="selection" id="selection"/>
                
                    <div class="col-md-6 isDisplay">
<?= Html::Button('Ok', ['class' => 'btn btn-primary pull-left mw-md auto_fill', 'style' => 'margin-top:25px;margin-bottom:20px;margin-left:20px']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>
</div>
    <script type="text/javascript">
        $("#maketSegmentId").on('change',function(){
            $("#w0").submit();
        });
      
        $('.select-on-check-all').on('ifChecked', function (event) {
            $('input[name="selection[]"]').iCheck('check');

        });

        $('.select-on-check-all').on('ifUnchecked', function (event) {
            $('input[name="selection[]"]').iCheck('uncheck');

        });

        $(".auto_fill").on('click', function () {

            var favorite = [];
            $.each($("input[name='selection[]']:checked"), function () {

                favorite.push($(this).val());

            });


            var product = favorite.join(",");

            $("#selection").val(product);
            if ($("#w1 input:checkbox:checked").length > 0)
            {

                $("#w1").submit();
                return true;
            } else {
                alert("Please select at least one Rule");
                return false;
            }
        });
        
        $("body").on("change", "#user-limit",function(event){
        $('#search-users').submit();
    });

    </script>