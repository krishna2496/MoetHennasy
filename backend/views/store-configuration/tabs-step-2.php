<?php

use yii\widgets\Pjax;
use yii\grid\GridView;

//echo '<pre>';
//print_r($topDataProvider);exit;
?>
<div class="col-sm-5 pull-right" id="tab-step-2">
    <!-- Frame Filter section -->
    <div class="frame-filter-section">
        <div class="box filter-collapse-panle">
            <!-- collapsed-box -->
            <div class="box-header with-border">
                <h3 class="box-title">Display <i class="fa fa-info-circle"></i></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-up fa-3x"></i></button>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <form class="frame-filt-form">
                    <div class="frame-chose-option">
                        <div class="box box-default shelfs-store">
                            <div class="box-header with-border">
                                <h3 class="box-title">TOP SHELF</h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-2x fa-angle-up"></i>
                                    </button>
                                </div>
                                <!-- /.box-tools -->
                            </div>
                            <!-- /.box-header -->

                            <div class="box-body">
                                <label class="barnd-select-msg">Select the products that will be present on the display on top shelf<sup class="text-red">*</sup>:</label>
                                <?php Pjax::begin(['id' => 'topShelf', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'POST']]) ?>
                                <?=
                                GridView::widget([
                                    'dataProvider' => $topDataProvider,
                                    'layout' => '<div class="table-responsive product-table">{items}</div><div class="row"><div class="col-sm-12"><div class="dataTables_paginate paging_simple_numbers">{pager}</div></div></div>',
                                    'columns' => [
                                            [
                                            'class' => 'yii\grid\CheckboxColumn', 'headerOptions' => ['class' => 'top_shelf_all'], 'contentOptions' => ['class' => 'top_shelf'], 'checkboxOptions' => function($model) {
                                                return ['value' => $model['id'], 'selection' => true, 'class' => 'checked','productWidth'=>$model['width']];
                                            },
                                        ],
                                            [
                                            'label' => 'Product',
                                            'attribute' => 'short_name',
                                            'value' => 'short_name'
                                        ],
                                         [
                                            'label' => 'Product type',
                                            'attribute' => 'product_type_name',
                                            'value' => 'product_type_name'
                                        ],
//											'market_share',
                                        [
                                            'label' => 'WSP',
                                            'attribute' => 'price',
                                        ],
                                            [
                                            'label' => 'Product Category',
                                            'attribute' => 'product_category_name',
                                            'value' => 'product_category_name'
                                        ],
//                                            [
//                                            'attribute' => 'top_shelf',
//                                            'format' => 'raw',
//                                            'value' => function($model) {
//                                                $checked = ($model['top_shelf'] == 1) ? 'checked' : '';
//                                                return '<input disabled type="checkbox" class="toggle" ' . $checked . ' data-toggle="toggle" data-on="YES" data-off="NO">';
//                                            }
//                                        ]
                                    ],
                                ]);
                                ?>
                                <script type="text/javascript">

                                    $(document).ready(function () {

                                        $('.top_shelf input[name="selection[]"]').each(function (skey, sval) {
                                            var sobj = {};
                                            sobj["sel"] = false;
                                            sobj["shelf"] = true;
                                            if (typeof (productObject[$(sval).val()]) === 'undefined')
                                            {
                                                productObject[$(sval).val()] = sobj;
                                            }
                                            if (typeof (productObject[$(sval).val()]) !== 'undefined' && productObject[$(sval).val()]["sel"] === true)
                                            {
                                                $('.top_shelf input[type="checkbox"][value="' + $(sval).val() + '"]').attr('checked', true).iCheck('update');
                                            }
                                        });

                                        $('.top_shelf_all .select-on-check-all').on('ifChecked', function (event) {
                                            $('.top_shelf input[name="selection[]"]').iCheck('check');
                                        });

                                        $('.top_shelf_all .select-on-check-all').on('ifUnchecked', function (event) {
                                            $('.top_shelf input[name="selection[]"]').iCheck('uncheck');
                                        });
                                        $('.top_shelf input[name="selection[]"]').on('ifChecked', function (event) {

                                            if (typeof (productObject[$(this).val()]) !== 'undefined')
                                            {    
                                                topProductWidthArry.push($(this).attr('productWidth'));
                                                productArry.push($(this).val());
                                                var id = $(this).val();
//								var switchValue = $("div#" + id).attr("dvalue");
//								var switchFlag = (switchValue == "1") ? true : false;
                                                productObject[$(this).val()]['sel'] = true;
//								productObject[$(this).val()]['shelf'] = switchFlag;
                                            }
                                            console.log(productObject);
                                        });
                                        $('.top_shelf input[name="selection[]"]').on('ifUnchecked', function (event) {
                                            popedValue = productArry.indexOf($(this).val());
                                            productArry.splice(popedValue, 1);
                                            if (typeof (productObject[$(this).val()]) !== 'undefined')
                                            {
                                                popedWidthValue =  topProductWidthArry.indexOf($(this).attr('productWidth'));
                                                topProductWidthArry.splice(popedWidthValue, 1);
                                                
                                             
                                                
                                                var id = $(this).val();
                                                productObject[$(this).val()]['sel'] = false;
                                            }
                                            console.log(productObject);
                                        });
                                    });
//                                                    $('.top_shelf input[name="status_41[]"]').on('switchChange.bootstrapSwitch', function (event, state) {
//							var id = $(this).closest('div.idDiv').attr("id");
//							if ($(this).bootstrapSwitch('state')) {
//								productObject[id]['shelf'] = true;
//								$("div#" + id).attr("dvalue", "1")
//							} else {
//								productObject[id]['shelf'] = false;
//								$("div#" + id).attr("dvalue", "0")
//							}
//						});					
                                </script>					
<?php Pjax::end(); ?>
                            </div> <!-- /.box-body -->
                        </div>
                    </div>
                    <div class="frame-chose-option">
                        <div class="box box-default shelfs-store">
                            <div class="box-header with-border">
                                <h3 class="box-title">PRODUCTS SKUs</h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-2x fa-angle-up"></i>
                                    </button>
                                </div>
                                <!-- /.box-tools -->
                            </div>
                            <!-- /.box-header -->

                            <div class="box-body">
                                <label class="barnd-select-msg">Select the products that will be present on the display<sup class="text-red">*</sup>:</label>
                                <?php Pjax::begin(['id' => 'employee', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'POST']]) ?>
                                <?=
                                GridView::widget([
                                    'dataProvider' => $dataProvider,
                                    'layout' => '<div class="table-responsive product-table">{items}</div><div class="row"><div class="col-sm-12"><div class="dataTables_paginate paging_simple_numbers">{pager}</div></div></div>',
                                    'columns' => [
                                            [
                                            'class' => 'yii\grid\CheckboxColumn', 'headerOptions' => ['class' => 'other_all'], 'contentOptions' => ['class' => 'other'], 'checkboxOptions' => function($model) {
                                                return ['value' => $model['id'], 'selection' => true, 'class' => 'checked','productWidth'=>$model['width']];
                                            },
                                        ],
                                            [
                                            'label' => 'Product',
                                            'attribute' => 'short_name',
                                            'value' => 'short_name'
                                        ],
                                            [
                                            'label' => 'Product type',
                                            'attribute' => 'product_type_name',
                                            'value' => 'product_type_name'
                                        ],
//											'market_share',
                                        [
                                            'label' => 'WSP',
                                            'attribute' => 'price',
                                        ],
                                            [
                                            'label' => 'Product Category',
                                            'attribute' => 'product_category_name',
                                            'value' => 'product_category_name'
                                        ],
//                                            [
//                                            'attribute' => 'top_shelf',
//                                            'format' => 'raw',
//                                            'value' => function($model) {
//                                                $checked = ($model['top_shelf'] == 1) ? 'checked' : '';
//                                                return '<input disabled type="checkbox" class="toggle" ' . $checked . ' data-toggle="toggle" data-on="YES" data-off="NO">';
//                                            }
//                                        ]
                                    ],
                                ]);
                                ?>
                                <script type="text/javascript">

                                    $(document).ready(function () {
                                        $('.other input[name="selection[]"]').each(function (skey, sval) {
                                            var sobj = {};
                                            sobj["sel"] = false;
                                            sobj["shelf"] = false;
                                            if (typeof (productObject[$(sval).val()]) === 'undefined')
                                            {
                                                productObject[$(sval).val()] = sobj;
                                            }
                                            if (typeof (productObject[$(sval).val()]) !== 'undefined' && productObject[$(sval).val()]["sel"] === true)
                                            {
                                                $('.other input[type="checkbox"][value="' + $(sval).val() + '"]').attr('checked', true).iCheck('update');
                                            }
                                        });

                                        $('.other_all .select-on-check-all').on('ifChecked', function (event) {
                                            $('.other input[name="selection[]"]').iCheck('check');
                                        });

                                        $('.other_all .select-on-check-all').on('ifUnchecked', function (event) {
                                            $('.other input[name="selection[]"]').iCheck('uncheck');
                                        });
                                        $('.other input[name="selection[]"]').on('ifChecked', function (event) {

                                            if (typeof (productObject[$(this).val()]) !== 'undefined')
                                            {
                                                bottomProductWidthArry.push($(this).attr('productWidth'));
                                                productArry.push($(this).val());
                                                bottomProductArry.push($(this).val());
                                                
                                              
                                                
                                                var id = $(this).val();
                                                productObject[$(this).val()]['sel'] = true;
                                            }
                                            console.log(productObject);
                                        });
                                        $('.other input[name="selection[]"]').on('ifUnchecked', function (event) {
                                            popedValue = productArry.indexOf($(this).val());
                                            productArry.splice(popedValue, 1);
                                            
                                            popedBottomValue = bottomProductArry.indexOf($(this).val());
                                            bottomProductArry.splice(popedBottomValue, 1);
                                            
                                            popedWidthValue =  bottomProductWidthArry.indexOf($(this).attr('productWidth'));
                                            bottomProductWidthArry.splice(popedWidthValue, 1);
                                          
                                            
                                            
                                          
                                              
                                            if (typeof (productObject[$(this).val()]) !== 'undefined')
                                            {
                                                var id = $(this).val();
//								var switchValue = $("div#" + id).attr("dvalue");
//								var switchFlag = (switchValue == "1") ? true : false;
                                                productObject[$(this).val()]['sel'] = false;
//								productObject[$(this).val()]['shelf'] = switchFlag;
                                            }
                                            console.log(productObject);
                                        });

                                    });

//						$('.other input[name="status_41[]"]').on('switchChange.bootstrapSwitch', function (event, state) {
//							var id = $(this).closest('div.idDiv').attr("id");
//							if ($(this).bootstrapSwitch('state')) {
//								productObject[id]['shelf'] = true;
//								$("div#" + id).attr("dvalue", "1")
//							} else {
//								productObject[id]['shelf'] = false;
//								$("div#" + id).attr("dvalue", "0")
//							}
//						});					
                                </script>					
<?php Pjax::end(); ?>
                            </div> <!-- /.box-body -->
                        </div>
                    </div>
                    <div class="submit-fl wizard">
                        <button class="btn reset-btn">Reset</button>
                        <button class="next btn">Save</button>
                    </div>
                </form>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
    <!-- End Frame Filter section -->
</div>
