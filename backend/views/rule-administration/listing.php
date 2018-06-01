<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use common\helpers\CommonHelper;
use yii\widgets\Pjax;
use yii\helpers\Url;

$formUrl = Url::to(['rule-administration/index']);
?>
<div class="product">
<?php Pjax::begin(['id' => 'pjaxCustomers', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'POST']]); ?>
<div class="view-customer" id="view-customer">
    <?php $form = ActiveForm::begin(['method' => 'post', 'options' => ['data-pjax' => '']]); ?>

    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    Rule Administration
                </h3>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?php
                            if (isset($filters['market_id']) && ($filters['market_id'] != '')) {
                                $model->market_id = $filters['market_id'];
                            }
                            if (isset($filters['brand_id']) && ($filters['brand_id'] != '')) {
                                $model->brand_id = $filters['brand_id'];
                            }
                            if (isset($filters['product_category_id']) && ($filters['product_category_id'] != '')) {
                                $model->product_category_id = $filters['product_category_id'];
                            }
                            if (isset($filters['product_id']) && ($filters['product_id'] != '')) {
                                $model->product_id = $filters['product_id'];
                            }
                            ?>
                            <?= $form->field($model, 'market_id')->dropDownList($markets, ['prompt' => 'Select One', 'id' => 'market_id']); ?>  
                        </div> 

                        <div class="col-md-6">
                            <?= $form->field($model, 'market_cluster_id')->dropDownList($brands, ['prompt' => 'Select One', 'id' => 'market_segment_id']); ?>  
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'brand_id')->dropDownList($brands, ['multiple' => 'multiple', 'prompt' => 'Select Brand', 'class' => 'form-control select2']); ?>  
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'product_category_id')->dropDownList($productCategory, ['prompt' => 'Select One']); ?>  
                        </div>
                    </div>
                    <div class="row" align="center" style="margin-top:20px">
                        <div class="col-xs-6">
                            <?= Html::submitButton('OK', ['class' => 'btn btn-primary pull-left mw-md']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box-body">
<?php 
                          print_r($filters);
?>
        <div class="col-md-12">
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'layout' => '<div class="table-responsive">{items}</div><div class="row"><div class="col-sm-5">{summary}</div><div class="col-sm-7"><div class="dataTables_paginate paging_simple_numbers">{pager}</div></div></div>',
                'columns' => [
                        [
                        'class' => 'yii\grid\SerialColumn'],
                        [
          'class' => 'yii\grid\CheckboxColumn', 'checkboxOptions' => function($model) {
                return ['value' => $model['id'],'selected' =>true ,'class' => 'class_checkbox'];
            },
      ],
                    'sku',
                        [
                        'label' => 'Product Type Id',
                        'attribute' => 'productType.title',
                    ],
                    'market_share',
                        [
                        'label' => 'top_shelf',
                        'value' => function ($model) {

                            $top_self = $model['top_shelf'];
                            return yii::$app->params['catalogue_status'][$top_self];
                        },
                    ],
                        [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'Actions',
                        'template' => '{update}',
                        'buttons' => [
                            'update' => function ($url, $model) {
                                return Html::a('<span class="fa fa-pencil margin-10 edit"></span>', ['/rule-administration/product/' . $model['id']], ['title' => 'Update', 'data-pjax' => 0]);
                            },
                        ],
                    ],
                ],
            ]);
            ?>
              </div>

    </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
    $(document).ready(function () {
        $('.select2').select2();
    });

    function getMarketSegments(data) {
        var str = "<option value>Select Cluster</option>";
        moet.ajax("<?php echo CommonHelper::getPath('admin_url') ?>stores/ajax-get-segment", data, 'post').then(function (result) {
            if (result.status.success == 1) {
                if (result.data.segments.length > 0) {
                    $.each(result.data.segments, function (key, value) {
                        var selectedSegment = '';
<?php if (isset($filters['market_cluster_id']) && ($filters['market_cluster_id'] != '')) { ?>
                            if (value.marketSegment.id == '<?php echo $filters['market_cluster_id']; ?>') {
                                var selectedSegment = 'selected';
                            }
<?php } ?>
                        str += "<option value=" + value.marketSegment.id + " " + selectedSegment + ">" + value.marketSegment.title + "</option>";
                    });
                }
            }
            $('#market_segment_id').html(str);
        }, function (result) {
            alert('Fail');
        });
    }


    $("body").on("change", "#market_id", function (event) {
        var market_id = parseInt($('#market_id').val());
        var data = {market_id: market_id};
        getMarketSegments(data);

    });
<?php if (isset($filters['market_id']) && ($filters['market_id'] != '')) { ?>
        var data = {market_id: '<?php echo $filters['market_id']; ?>'};

        getMarketSegments(data);
<?php } ?>

    $('.select-on-check-all').on('ifChecked', function (event) {
        $('input[name="selection[]"]').iCheck('check');

    });

    $('.select-on-check-all').on('ifUnchecked', function (event) {
        $('input[name="selection[]"]').iCheck('uncheck');

    });

</script>
<?php Pjax::end(); ?>
</div>

