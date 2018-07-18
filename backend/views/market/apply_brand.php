<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\CommonHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

$this->title = 'Market Brands';
$this->params['breadcrumbs'][] = ['label' => 'Market', 'url' => ['/market']];
$this->params['breadcrumbs'][] = $this->title;
$formUrl = Url::to(['market/brands/' . $market_id]);
?>
<script>
    productObject = {};
    productArry = [];
    selectedBrand = [<?php echo '"' . implode('","', $selected) . '"' ?>];
</script>
<div class="row">
    <div class="row" id="isDisplay">
        <?php Pjax::begin(['id' => 'brands', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'POST']]) ?>  
        <?= Html::beginForm($formUrl, 'post', ['data-pjax' => '', 'id' => 'w1']); ?>
        <div class="col-xs-12">
            <div class="box">

                <div class="box-body">
                    <div class="box-header">
                        <h2>
                            <?= $title; ?>
                        </h2>
                        <div class="row pull-right">
                            <div class="col-md-12">
                                <div class="filter-search dataTables_filter clearfix">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <?php echo Html::input('text', 'search', isset($filters['search']) ? $filters['search'] : '', ['class' => 'form-control', 'placeholder' => 'Search', 'id' => 'user-text']) ?> 
                                        </div>
                                        <div class="col-md-4">
                                            <?= Html::dropDownList('limit', isset($filters['limit']) ? $filters['limit'] : '', Yii::$app->params['limit'], ['class' => 'form-control', 'id' => 'user-limit']) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input name="selection" value="" type="hidden" id="selection"/>

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
                    <script type="text/javascript">
                        $(document).ready(function () {
                            brandThumbId = 0;

                            $('input[name="selection[]"]').each(function (skey, sval) {

                                productObject[$(sval).val()] = false;
                                console.log(selectedBrand);
                                $(selectedBrand).each(function (bkey, bvalue) {
                                    if ($(sval).val() == bvalue) {
                                        productObject[$(sval).val()] = true;
                                    }
                                });

                                if (typeof (productObject[$(sval).val()]) !== 'undefined' && productObject[$(sval).val()] == false)
                                {
                                    $('input[type="checkbox"][value="' + $(sval).val() + '"]').attr('checked', false).iCheck('update');
                                }
                                if (typeof (productObject[$(sval).val()]) !== 'undefined' && productObject[$(sval).val()] === true)
                                {
                                    $('input[type="checkbox"][value="' + $(sval).val() + '"]').attr('checked', true).iCheck('update');
                                }

                            });
                        });

                        $('input[name="selection[]"]').on('ifUnchecked', function (event) {
                            productArry.push($(this).val());
                        });

                        $('input[name="selection[]"]').on('ifChecked', function (event) {

                            selectedBrand.push($(this).val());
                            console.log(selectedBrand);
                        });

                        $('input[name="selection[]"]').on('ifUnchecked', function (event) {
                            popedValue = selectedBrand.indexOf($(this).val());
                            selectedBrand.splice(popedValue, 1);
                        });

                    </script>

                </div>
                <div class="row">

                    <input type="hidden" value="" name="selectedBrand" id="selectedBrand"/>

                    <div class="col-md-6 isDisplay">
                        <?= Html::Button('Ok', ['class' => 'btn btn-primary pull-left mw-md auto_fill', 'style' => 'margin-top:25px;margin-bottom:20px;margin-left:20px']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?= Html::endForm(); ?>
    <script type="text/javascript">
        $("#maketSegmentId").on('change', function () {
            $("#w0").submit();
        });

        $('.select-on-check-all').on('ifChecked', function (event) {
            $('input[name="selection[]"]').iCheck('check');

        });

        $('.select-on-check-all').on('ifUnchecked', function (event) {
            $('input[name="selection[]"]').iCheck('uncheck');

        });

        $(".auto_fill").on('click', function () {
            if (selectedBrand!=undefined && selectedBrand.length > 0)
            {
                $("#selectedBrand").val(selectedBrand);
                $("#w1").submit();
                return true;
            } else {
                alert("Please select at least one Brand");
                return false;
            }
        });



    </script>
    <?php Pjax::end(); ?>
</div>
<script>
    $("body").on("change", "#user-text,#user-limit", function (event) {
        $('#w1').submit();
    });
</script>