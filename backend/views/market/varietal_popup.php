<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\CommonHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span></button>
    <h4 class="modal-title">Varietals Strategy (<?= $brand_name ?>)</h4>
</div>
<input type="hidden" value="" id="selectedBrandsKey" name="selectedBrandsKey" value="" />
<input type="hidden" value="" id="selectedVal" name="selectedBrandsKey" value="" />
<div class="modal-body" style="height: 300px;overflow-y: auto;">
    <div id="varietalMessageBox"></div>

    <?=
    GridView::widget([
        'dataProvider' => $productVarietalDataProvider,
        'layout' => '<div class="table-responsive">{items}</div><div class="row"><div class="col-sm-7"><div class="dataTables_paginate paging_simple_numbers">{pager}</div></div></div>',
        //'layout' => '<div class="table-responsive">{items}</div><div class="row"><div class="col-sm-5">{summary}</div><div class="col-sm-7"><div class="dataTables_paginate paging_simple_numbers">{pager}</div></div></div>',
        'tableOptions' => ['id' => 'table-draggable_popup', 'class' => "table table-striped table-bordered", 'style' => "margin-bottom: 0px !important;"],
        'rowOptions' => function($model) {
            return ['data-num' => $model['id']];
        },
        'columns' => [
                ['class' => 'yii\grid\SerialColumn', 'contentOptions' => ['style' => 'width:10%'],],
                [
                'label' => 'Name',
                'attribute' => 'name',
                'contentOptions' => ['style' => 'width:75%'],
            ],
                [
                'label' => 'Share (%)',
                'format' => 'raw',
                'contentOptions' => ['style' => 'width:15%'],
                'value' => function ($model, $key)use($productVarietalDataProvider) {
                    $shares = $productVarietalDataProvider->allModels[$key]['shares'];
                    return '<input type="text" id="varietalShares[]" data-id="' . $productVarietalDataProvider->allModels[$key]['id'] . '" name="varietalShares[]" value="' . $shares . '" class="form-control numericOnly" style="text-align:center;" onkeyup="checkTotalVal()"/>';
                }
            ],
        ],
    ]);
    ?>
    <table class="table table-striped table-bordered">
        <tbody>
            <tr data-key="0">
                <td style="width:65%"></td>
                <td style="width:20%;text-align: right;padding-top: 15px;font-weight: bold;">Total</td>
                <td style="width:15%"><input type="text" id="totalVarietalShares" name="totalVarietalShares" value="0" readOnly disabled class="form-control numericOnly" style="text-align:center;" /></td>
            </tr>
        </tbody>
    </table>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var brand_id = '<?php echo $brand_id; ?>';
        $("#selectedBrandsKey").val(brand_id);
        new_order_product = [];
        $("#table-draggable_popup").tableDnD({
            onDragClass: "myDragClass",
            onDrop: function (table, row)
            {
                var i = 1;
                $('#table-draggable_popup td:first-child').each(function () {
                    $(this).html(i++);
                });
                $('#table-draggable_popup tr').each(function () {
                    new_order_product.push($(this).data('num'));
                });
                console.log(new_order_product);
                update_order_popup(new_order_product);
                new_order_product = [];
            },
            dragHandle: ".dragHandle"
        });
        function update_order_popup(data) {
            orderUrl = '<?php echo CommonHelper::getPath('admin_url'); ?>apply/order-update-varietal/';
            market_id = '<?php echo $market_id ?>';
            brand_id = '<?php echo $brand_id; ?>';
            category_id = '<?php echo $categoryId; ?>';
            brand_id = $("#selectedBrandsKey").val();
            $("body").addClass('loader-enable');
            $.ajax({
                type: "POST",
                url: orderUrl,
                cache: false,
                data: {data: data, market_id: market_id, category_id: category_id, brand_id: brand_id},
                success: function (result)
                {
                    if (result == 1) {

                    } else {

                        $("body").removeClass('loader-enable');
                    }
                }
            });
        }
        checkTotalVal();
    });
    //change total share value
    function checkTotalVal() {
        var shareToStore = 0;
        $.each($('[name="varietalShares[]"]'), function (key, value) {
            shareToStore = shareToStore + parseInt($(value).val());
            $('#totalVarietalShares').val(shareToStore);
        });
    }
    //check for validation
    function storeCurrentVarietalShares() {
        var $totalVarietalShare = 0;
        var finalArray = [];
        $.each($('[name="varietalShares[]"]'), function (key, value) {

            finalArray[key] = {'id': parseInt($(value).attr('data-id')), 'share': parseInt($(value).val())};
            $totalVarietalShare = $totalVarietalShare + parseInt($(value).val());

        });
        if ($totalVarietalShare == 100 && $('#totalVarietalShares').val() == 100) {
            $('#varietalShareObject_' + $('#selectedBrandsKey').val()).val(JSON.stringify(finalArray));
            $('#verietal-modal').modal('hide');
            $('#varietalMessageBox').html("");
        } else {
            $.each($('[name="varietalShares[]"]'), function (key, value) {
                $(this).parent().parent().css("background-color", "#d24737");
                $(this).parent().parent().css("color", "white");
            });
            $('#varietalMessageBox').html("<div class='alert alert-danger'><button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>Total share should be equals to 100.</div>");
        }
    }
</script>
<div class="modal-footer">
    <?= \yii\helpers\Html::a('Save', null, ['class' => 'btn btn-primary', 'onClick' => 'storeCurrentVarietalShares();']); ?>
</div>