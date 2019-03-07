<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\CommonHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

// echo '<pre>';
//print_r($productVarietalDataProvider);exit;
$this->title = 'Market Brands';
$this->params['breadcrumbs'][] = ['label' => 'Markets', 'url' => ['/market']];
$this->params['breadcrumbs'][] = $this->title;
$formUrl = Url::to(['apply/brands/' . $brandId . '/' . $market_id]);
$new_array = [];
$data = $dataProvider->allModels;
foreach ($selected as $key => $value) {
    foreach ($data as $k => $v)
        if ($v['id'] == $value) {
            $new_array[] = $v;
            unset($data[$k]);
        }
}

$dataProvider->allModels = array_merge($new_array, $data);
$count = count($dataProvider->allModels);
$totalBranSharesCount = array_sum($selectedShares);
$allData = $dataProvider->allModels;
$sharesData= array();
//echo '<pre>';
//print_r($finalViertalArry);exit;
//foreach ($allData as $k => $v){
//    foreach ($v['marketBrands'] as $key=>$val){
//     
//     if($val['market_id'] == $market_id && $val['category_id'] == $brandId)
//        $sharesData[$v['id']] = $val['shares'];
//    }
//}
?>
<script>
    productObject = {};
    productArry = [];
    selectedBrand = [<?php echo '"' . implode('","', $selected_product) . '"' ?>];
</script>
<div class="row">
    <div class="col-xs-12" id="isDisplay">

        <?php Pjax::begin(['id' => 'brands', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'POST']]) ?>  
        <div class="box">

            <div class="box-body">
                <div class="box-header">
                    <h2>
                        <?= $title; ?>
                        <div class="pull-right">
                            <?= \yii\helpers\Html::a('Back', ['market/index'], ['class' => 'btn btn-primary']); ?>
                        </div>
                    </h2>
                    <div class="row pull-right">
                        <div class="col-md-12">
                            <div class="filter-search dataTables_filter clearfix">
                                <div class="row">
                                    <?= Html::beginForm($formUrl, 'post', ['data-pjax' => '', 'id' => 'search-users']); ?>
                                    <div class="col-md-12">
                                        <?php // echo Html::input('text', 'search', isset($filters['search']) ? $filters['search'] : '', ['class' => 'form-control', 'placeholder' => 'Search', 'id' => 'user-text']) ?> 
                                        <!--<span id="searchClear" class="glyphicon glyphicon-remove"></span>-->
                                    </div>

                                    <?= Html::endForm(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?= Html::beginForm($formUrl, 'post', ['data-pjax' => '', 'id' => 'w1']); ?>

                <?=
                GridView::widget([
                    'dataProvider' => $dataProvider,
                    'layout' => '<div class="table-responsive">{items}</div><div class="row"><div class="col-sm-5">{summary}</div><div class="col-sm-7"><div class="dataTables_paginate paging_simple_numbers">{pager}</div></div></div>',
                    'tableOptions' => ['id' => 'table-draggable', 'class' => "table table-striped table-bordered"],
                    'rowOptions'=>function($model){
                                return ['data-num' => $model['id']];
                         },
                    'columns' => [
                            ['class' => 'yii\grid\SerialColumn', 'contentOptions' => ['style' => 'width:10%'],],
                            [
                            'label' => 'Name',
                            'attribute' => 'name',
                            'contentOptions' => ['style' => 'width:20%'],
                        ],
                        
                            [
                            'label' => 'Share',
                            'format' => 'raw',
                            'contentOptions' => ['style' => 'width:10%'],
                            
                            'value' => function ($model, $key) use ($dataProvider,$finalViertalArry) {
                               
                                $currentShareValue = isset($model['shares']) ? $model['shares'] : 0;
                                $vierntal_val = isset($finalViertalArry[$model['id']]) ? json_encode($finalViertalArry[$model['id']], JSON_NUMERIC_CHECK) : '';
                                return '<input type="text" id="shares[]" data-id="' . $model['id'] . '" name="shares[]" value="' . $currentShareValue . '" pattern="[0-9]+" class="form-control numericOnly" style="text-align:center;" />' .
                                    '<input type="hidden" id="sharesId[]" data-id="' . $model['id'] . '" name="sharesId[]" value="' . $model['id'] . '"/>'
                                    . '<input type="hidden" id="varietalShareObject_' .  $model['id'] . '" data-id="' . $model['id'] . '" name="varietalShareObject[]" value=' . $vierntal_val . '>';
                            }
                        ],
                            [
                            'label' => '',
                            'format' => 'raw',
                            'contentOptions' => ['style' => 'width:60%'],
                            'value' => function ($model, $key)use($count, $dataProvider, $selected) {
                                
                                return \yii\helpers\Html::a('Manage Varietal', null, ['class' => 'btn btn-primary manage-varietal', 'onClick' => 'changeCurrentVariatalPoupId(this)', 'data-id' => $model['id'], 'data-url' =>CommonHelper::getPath('admin_url').'apply/modal-content/','data-target' => "#verietal-modal", 'style' => 'width:150px', 'data-toggle' => "modal"]);
                            }
                        ],
                    ],
                ]);
                ?>
                <table class="table table-striped table-bordered">
                    <tbody>
                        <tr data-key="0">
                            <td style="width:10%" style="display: none"></td>
                            <td style="width:20%;text-align: right;padding-top: 15px;font-weight: bold;">Total</td>
                            <td style="width:10%"><input type="text" id="totalShares" name="totalShares" value="<?= $totalBranSharesCount ?>" readOnly disabled class="form-control numericOnly" style="text-align:center;" /></td>
                            <td style="width:60%"></td>
                        </tr>
                    </tbody>
                </table>
                <script type="text/javascript">
                    function changeCurrentVariatalPoupId(elem) {
                        var getjsonvarietalData = $('#varietalShareObject_' + $(elem).attr('data-id')).attr('value');
                        var jsonvarietalData = '';

                        try {
                            jsonvarietalData = JSON.parse(getjsonvarietalData);
                        } catch (e) {
                            jsonvarietalData = '';
                        }

                        $.each($('[name="varietalShares[]"]'), function (key, value) {
                            if (jsonvarietalData) {
                                try {
                                    $(value).val(jsonvarietalData[key].share);
                                } catch (e) {
                                    $(value).val(0);
                                }
                            } else {
                                $(value).val(0);
                            }
                        });
                        $('#selectedBrandsKey').val($(elem).attr('data-id'));


                    }
                    
                    $(document).ready(function () {
                        new_order = [];
                        new_order_top_shelf = [];
                        $("#table-draggable").tableDnD({
                            onDrop: function (table, row)
                            {
                                var i = 1;
                                $('#table-draggable td:first-child').each(function () {
                                    $(this).html(i++);
                                });
                                $('#table-draggable tr').each(function () {
                                     new_order.push($(this).data('num'));

                                });
                               
                                update_order(new_order);
                                new_order = [];
                            }
                        });
                        
                        $("#table-draggable-top").tableDnD({
                            onDrop: function (table, row)
                            {
                                var i = 1;
                                $('#table-draggable-top td:first-child').each(function () {
                                    $(this).html(i++);
                                });
                                $('#table-draggable-top tr').each(function () {
                                     new_order_top_shelf.push($(this).data('num'));

                                });
                               
                                update_order_top_shelf(new_order_top_shelf);
                                new_order_top_shelf = [];
                            }
                        });
                        
                        //
                        function update_order(data) {
                          
                            orderUrl = '<?php echo CommonHelper::getPath('admin_url'); ?>apply/order-update-brand/';
                            market_id = '<?php echo $market_id ?>';
                            category_id = '<?php echo $brandId; ?>';
                            $("body").addClass('loader-enable');
                            $.ajax({
                                type: "POST",
                                url: orderUrl,
                                cache: false,
                                data: {data: data,market_id:market_id,category_id:category_id},
                                success: function (result)
                                {
                                    if(result == 1){
                                       
                                    }else{
                                        
                                        $("body").removeClass('loader-enable');
                                    }
                                }
                            });
                        }
                        //update order top shelf
                        function update_order_top_shelf(data) {
                          
                            orderUrl = '<?php echo CommonHelper::getPath('admin_url'); ?>apply/order-update-top-shelf/';
                            market_id = '<?php echo $market_id ?>';
                            category_id = '<?php echo $brandId; ?>';
                            $("body").addClass('loader-enable');
                            $.ajax({
                                type: "POST",
                                url: orderUrl,
                                cache: false,
                                data: {data: data,market_id:market_id,category_id:category_id},
                                success: function (result)
                                {
                                    if(result == 1){
                                       
                                    }else{
                                        
                                        $("body").removeClass('loader-enable');
                                    }
                                }
                            });
                        }


                    });

                    $("#searchClear").on('click', function () {
                        $(this).prev().val('');
                        $('#search-users').submit();
                    });
                </script>

                <div class="box">
                    <h3 style="margin-bottom: 15px">Top Shelf Product</h3>
                    <?=
                    GridView::widget([
                        'dataProvider' => $catalogDataProvider,
                        'layout' => '<div class="table-responsive">{items}</div><div class="row"><div class="col-sm-5">{summary}</div><div class="col-sm-7"><div class="dataTables_paginate paging_simple_numbers">{pager}</div></div></div>',
                        'tableOptions' => ['id' => 'table-draggable-top', 'class' => "table table-striped table-bordered"],
                        'rowOptions'=>function($model){
                                return ['data-num' => $model['id']];
                         },
                        'columns' => [
                                [
                                'class' => 'yii\grid\SerialColumn'
                            ],
                                [
                                'class' => 'yii\grid\CheckboxColumn',
                                'header' => Html::checkBox('selection_all', false, [
                                    'class' => 'select-on-check-all',
                                    'value' => '00'
                                ]),
                                'checkboxOptions' => function($catalogModel) {
                                    return ['value' => $catalogModel['id']
                                    ];
                                },
                            ],
//                                    'id',
//                                    'top_order_id',
                                [
                                'label' => 'Product Name',
                                'attribute' => 'brandName',
                                'value' => 'long_name',
                            ],
                                [
                                'label' => 'Brand',
                                'attribute' => 'brandName',
                                'value' => 'brand.name',
                            ],
                                [
                                'label' => 'Variental',
                                'attribute' => 'variental',
                                'value' => 'variental.name',
                            ]
                        ],
                    ]);
                    ?>
                    <div class="row">
                        <div class="col-md-6 isDisplay">
<?= Html::Button('Save', ['class' => 'btn btn-primary pull-left mw-md auto_fill', 'style' => 'margin-top:25px;margin-bottom:20px;margin-left:20px;', 'disabled' => 'disabled']) ?>
                        </div>
                    </div>

                </div>

<?= Html::endForm(); ?>
            </div>

        </div>

    </div>



    <script type="text/javascript">
        var category_id = '<?php echo $brandId; ?>';
        var market_id = '<?php echo $market_id ?>';
        $("#maketSegmentId").on('change', function () {
            $("#w0").submit();
        });

        $('.select-on-check-all').on('ifChecked', function (event) {

            $('input[name="selection[]"]').iCheck('check');
            $(".auto_fill").removeAttr('disabled');
        });

        $('.select-on-check-all').on('ifUnchecked', function (event) {
            $('input[name="selection[]"]').iCheck('uncheck');
            $(".auto_fill").removeAttr('disabled');
        });

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
            
            if ($('#totalShares').val() == 100) {
                $(".auto_fill").removeAttr('disabled');
            }
            //OPEN Popup modal
            $('.verietal-modal').on('show.bs.modal', function (event)
            {
                
                var brand_id = $(event.relatedTarget).attr('data-id');
                var url = $(event.relatedTarget).attr('data-url');
                var pre_val = $('#varietalShareObject_'+brand_id).val();
                newUrl = url+market_id+'/'+category_id+'/'+brand_id;
                $('.verietal-modal-content').load(newUrl, function ()
                {
                   if(pre_val){
                    var obj = jQuery.parseJSON(pre_val);
                    $.each(obj, function( index, value ) {
                        $("[data-id = "+value.id+"]").val(value.share)
                        
                       $.each(value, function( i, v ) {
                         
                        });
                    });
                   
                     $("#selectedVal").val(obj);
                     }
                    moet.hideLoader();
                });
                setTimeout(function () {
                    $('.modal-backdrop').css('z-index', 0);
                }, 10);
            });
            //open modal end
        });

        $('input[name="selection[]"]').on('ifUnchecked', function (event) {
            $(".auto_fill").removeAttr('disabled');
            productArry.push($(this).val());
        });

        $('input[name="selection[]"]').on('ifChecked', function (event) {
            $(".auto_fill").removeAttr('disabled');
            selectedBrand.push($(this).val());

        });

        $('input[name="selection[]"]').on('ifUnchecked', function (event) {
            popedValue = selectedBrand.indexOf($(this).val());
            selectedBrand.splice(popedValue, 1);
        });
//auto_fill_data

        $(".auto_fill").on('click', function () {
            var $isNotFullSetShare = 1;
            var $totalShare = 0;

            $.each($('[name="shares[]"]'), function (key, value) {

                var $jsonVal = $('#varietalShareObject_' + $(value).attr('data-id')).val();
                try {
                    $jsonVal = JSON.parse($jsonVal);
                } catch (e) {
                    $jsonVal = '';
                }
                var $vertielShareCount = 0;
                if ($jsonVal != '') {
                    $.each($jsonVal, function (vkey, vvalue) {
                        $vertielShareCount += (vvalue.share != '') ? vvalue.share : 0;
                    });
                }
                if ($(value).val() != '') {
                    if ($vertielShareCount != 100 && parseInt($(value).val()) > 0) {
                        $isNotFullSetShare = 0;

                    }
                }
                $totalShare = $totalShare + parseInt($(value).val());
                if ($vertielShareCount != 100) {
                    $(this).parent().parent().addClass('danger');
                }
            });

               

            if ($isNotFullSetShare == 0) {
                alert('Brand and selected brand verietal share should be equal to 100.');
            } else {
                if (selectedBrand == '') {
                    alert("Please Select At Least One Product");
                } else {
                    $("#w1").submit();
                }
            }
        });
    </script>
<?php Pjax::end(); ?>
</div>
<!--<div class="modal fade" id="modal-default" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Select Product Varietal</h4>
            </div>
            <div class="modal-body">
<?= Html::dropDownList('limit', isset($filters['limit']) ? $filters['limit'] : '', $brands, ['class' => 'form-control', 'id' => 'user-limit']) ?>
            </div>
            <div class="modal-footer">
<?= \yii\helpers\Html::a('Add Brand', null, ['class' => 'btn btn-primary', 'data-toggle' => "modal", 'data-target' => "#modal-default"]); ?>
            </div>
        </div>
    </div>
</div>-->

<div class="modal fade verietal-modal" id="verietal-modal" style="display: none;">
    <div class="modal-dialog" style="overflow-y: initial !important;">
        <div class="modal-content verietal-modal-content">

        </div>
    </div>
</div>
<script>
  

    $("body").on("change", "#user-text,#user-limit", function (event) {
        $('#search-users').submit();
    });

    var baseUrl = "<?php echo Yii::$app->request->baseUrl; ?>";
    function reorder(current_id, replaced_id) {
        url = baseUrl + "/apply/re-order";
        market_id = '<?php echo $market_id ?>';
        $.ajax({
            type: 'POST',
            url: url,
            data: {current_id: current_id, replaced_id: replaced_id, market_id: market_id},
            success: function (data)
            {
                location.reload();
            }
        });
    }
</script>