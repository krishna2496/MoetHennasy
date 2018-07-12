<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\CommonHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Market Brands';
$this->params['breadcrumbs'][] = ['label' => 'Market', 'url' => ['/market']];
$this->params['breadcrumbs'][] = $this->title;
$formUrl = Url::to(['market/brands']);
?>
<div class="row">
  
<?php $form = ActiveForm::begin(['method' => 'post', 'options' => ['data-pjax' => '', 'id' => 'w1']]); ?>
    <div class="row" id="isDisplay">
        <div class="col-xs-12">
            <div class="box">
                 <div class="box-header">
                <h2>
<?= $title; ?>
                </h2>
            </div>
                <div class="box-body">
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

    </script>