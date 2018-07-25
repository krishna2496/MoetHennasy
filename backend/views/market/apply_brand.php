<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\CommonHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

$this->title = 'Market Brands';
$this->params['breadcrumbs'][] = ['label' => 'Markets', 'url' => ['/market']];
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
       
        <div class="col-xs-12">
            <div class="box">

                <div class="box-body">
                    <div class="box-header">
                        <h2>
                            <?= $title; ?>
                            <?= \yii\helpers\Html::a( 'Back', Yii::$app->request->referrer,['class' => 'btn btn-primary pull-right']);?>
                        </h2>
                        <div class="row pull-right">
                            <div class="col-md-12">
                                <div class="filter-search dataTables_filter clearfix">
                                    <div class="row">
                                        <?= Html::beginForm($formUrl, 'post', ['data-pjax' => '', 'id' => 'search-users']); ?>
                                        <div class="col-md-8">
                                            <?php echo Html::input('text', 'search', isset($filters['search']) ? $filters['search'] : '', ['class' => 'form-control', 'placeholder' => 'Search', 'id' => 'user-text']) ?> 
                                            <span id="searchClear" class="glyphicon glyphicon-remove"></span>
                                        </div>
                                        <div class="col-md-4">
                                            <?= Html::dropDownList('limit', isset($filters['limit']) ? $filters['limit'] : '', Yii::$app->params['limit'], ['class' => 'form-control', 'id' => 'user-limit']) ?>
                                        </div>
                                        <?= Html::endForm(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
 <?= Html::beginForm($formUrl, 'post', ['data-pjax' => '', 'id' => 'w1']); ?>
                    <input name="selection" value="" type="hidden" id="selection"/>
                    <input type="hidden" value="" name="selectedBrand" id="selectedBrand"/>
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'layout' => '<div class="table-responsive">{items}</div><div class="row"><div class="col-sm-5">{summary}</div><div class="col-sm-7"><div class="dataTables_paginate paging_simple_numbers">{pager}</div></div></div>',
                        'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                [
                                'class' => 'yii\grid\CheckboxColumn',
                                    'header' => Html::checkBox('selection_all', false, [
                                    'class' => 'select-on-check-all',
                                     'value' =>'00'
                                ]),
                                'checkboxOptions' => function($model) use ($rules) {
                                    return ['value' => $model['id']
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
                        $("#searchClear").on('click',function(){
                            $(this).prev().val(''); 
                            $('#search-users').submit();
                        });
                    </script>

                </div>
                <div class="row">
                <div class="col-md-6 isDisplay">
                        <?= Html::Button('Save', ['class' => 'btn btn-primary pull-left mw-md auto_fill', 'style' => 'margin-top:25px;margin-bottom:20px;margin-left:20px']) ?>
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
        selectedBrand  = selectedBrand.filter(Boolean)
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
        $('#search-users').submit();
    });
    
    
</script>