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
        <?php $form = ActiveForm::begin(['method' => 'post', 'options' => ['data-pjax' => '', 'id' => 'w0']]); ?>

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
                                <?= Html::submitButton('Ok', ['class' => 'btn btn-primary pull-left mw-md']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box-body isDisplay">

            <h3 class="box-title">
                Products
            </h3>
                <div class="row pull-right">
                    
                    <div class="col-md-12">
                     
                        <div class="filter-search dataTables_filter clearfix">
                            <div class="row">
                              
                                <div class="col-md-12">
                                       <?= $form->field($model, 'limit')->dropDownList(Yii::$app->params['limit'], ['id' => 'brands-limit'])->label(false); ?>  
                                    
                                </div>
                            </div>
                        </div>
                   
                    </div>
                </div>
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
                                return ['value' => $model['id'], 'selection' => true, 'class' => 'checked'];
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
        <?php $form = ActiveForm::begin(['action' => ['rule-administration/auto-fill'], 'method' => 'post', 'options' => ['data-pjax' => '', 'id' => 'w2']]); ?>
        <div class="row">
            <?php if (isset($filters['market_id'])) { ?>
                <input type="hidden" value="<?= $filters['market_id'] ?>" name="market_id"/>
                <?php
            }
            if (isset($filters['brand_id']) && ($filters['brand_id'] != '')) {
                $brand = implode(',', $filters['brand_id']);
                ?>
                <input type="hidden" value="<?= $brand ?>" name="brand_id"/>
                <?php
            }
            if (isset($filters['product_category_id'])) {
                ?>
                <input type="hidden" value="<?= $filters['product_category_id'] ?>" name="product_category_id"/>
                <?php
            }
            if (isset($filters['market_cluster_id'])) {
                ?>
                <input type="hidden" value="<?= $filters['market_cluster_id'] ?>" name="market_cluster_id"/>
            <?php } ?>
            <input type="hidden" value="" name="selection" id="selection"/>
            <div class="col-md-12 isDisplay">
                <?= Html::Button('Auto Fill', ['class' => 'btn btn-primary pull-left mw-md auto_fill', 'style' => 'margin-top:25px']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <script>
        $("body").on("change", "#brands-text,#brands-limit",function(event){
        $('#w0').submit();
    });
        $(document).ready(function () {
            $('.select2').select2();
        });
<?php
if (isset($filters['selection'])) {
    foreach ($filters['selection'] as $key => $value) {
        ?>
                $("input[type=checkbox][value=" +<?= $value ?> + "]").attr("checked", "true");

        <?php
    }
}
?>
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

        $(".auto_fill").on('click', function () {
            var favorite = [];
            $.each($("input[name='selection[]']:checked"), function () {

                favorite.push($(this).val());

            });


            var product = favorite.join(",");
            $("#selection").val(product);
            $("#w2").submit();
        });

        $('.select-on-check-all').on('ifChecked', function (event) {
            $('input[name="selection[]"]').iCheck('check');

        });

        $('.select-on-check-all').on('ifUnchecked', function (event) {
            $('input[name="selection[]"]').iCheck('uncheck');

        });

<?php if ($isDisplay == 0) { ?>
    <!-- $('.isDisplay').hide(); -->
<?php } else { ?>
    <!-- $('.isDisplay').show(); -->
<?php } ?>

        $('#w2').on('beforeSubmit', function (e) {
            if ($("#w0 input:checkbox:checked").length > 0)
            {
                return true;
            } else {
                alert("Please Select at least one product");
            }
            return false;

        });

</script>
<?php Pjax::end(); ?>
</div>
<!--    <script>
       
        var checkboxArryNew = [];
        $(document).on('ready pjax:click', function () {
      
            $.each($("input[name='selection[]']:checked"), function () {
                checkboxArryNew.push($(this).val());
                   
            });
          
        });

        $(document).on('ready pjax:end', function () {
         alert("end");
                    alert(checkboxArryNew);
            $.each(checkboxArryNew, function (i, val) {
                $("input[type=checkbox][value=" +val+ "]").attr("checked", "true");
                   
            });


        });
        </script>-->





