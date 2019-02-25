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
$formUrl = Url::to(['apply/brands/' .$brandId.'/'.$market_id]);
//echo '<prE>';
////print_r($selected);
//echo '<br>';
////print_r($dataProvider->allModels);
////exit;
$new_array = [];
$data = $dataProvider->allModels;
foreach ($selected as $key=>$value){
    foreach ($data as $k=>$v)
        if($v['id'] == $value){
           $new_array[] =$v;
           unset($data[$k]);
        }
//        $data[$k]['reorder_id',]
}
$dataProvider->allModels = array_merge($new_array,$data);
$count = count($dataProvider->allModels);
$totalBranSharesCount = array_sum($selectedShares);
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
                            <div class="pull-right">
                            <?= \yii\helpers\Html::a( 'Add Brand', null,['class' => 'btn btn-primary', 'data-toggle'=>"modal", 'data-target'=>"#modal-default"]);?> &nbsp;
                            <?= \yii\helpers\Html::a( 'Back', ['market/index'],['class' => 'btn btn-primary']);?>
                            </div>
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
                                ['class' => 'yii\grid\SerialColumn','contentOptions' => ['style' => 'width:10%'],],
                            [
                                'label' => 'Name',
                                'attribute' => 'name',
                                'contentOptions' => ['style' => 'width:20%'],
                            ],
                            [
                                'label' => 'Share',
                                'format' => 'raw',
                                'contentOptions' => ['style' => 'width:10%'],
                                'value'=>  function ($model,$key)use($count,$dataProvider,$selected,$selectedShares,$finalViertalArry) {
                                        $currentShareValue = isset($selectedShares[$dataProvider->allModels[$key]['id']]) ? $selectedShares[$dataProvider->allModels[$key]['id']] : 0;
                                        $vierntal_val = isset($finalViertalArry[$dataProvider->allModels[$key]['id']]) ? json_encode($finalViertalArry[$dataProvider->allModels[$key]['id']],JSON_NUMERIC_CHECK) :'';
                                    return '<input type="text" id="shares[]" data-id="'.$dataProvider->allModels[$key]['id'].'" name="shares[]" value="'.$currentShareValue.'" pattern="[0-9]+" class="form-control numericOnly" style="text-align:center;" />'.
                                            '<input type="hidden" id="sharesId[]" data-id="'.$dataProvider->allModels[$key]['id'].'" name="sharesId[]" value="'.$dataProvider->allModels[$key]['id'].'"/>'
                                            . '<input type="hidden" id="varietalShareObject_'.$dataProvider->allModels[$key]['id'].'" data-id="'.$dataProvider->allModels[$key]['id'].'" name="varietalShareObject[]" value="'.$vierntal_val.'">';
                                }
                            ],
                            [
                                'label' => '',
                                'format' => 'raw',
                                'contentOptions' => ['style' => 'width:60%'],
                                'value'=>  function ($model,$key)use($count,$dataProvider,$selected) {
                                    return \yii\helpers\Html::a( 'Manage Varietal', null,['class' => 'btn btn-primary manage-varietal', 'onClick'=>'changeCurrentVariatalPoupId(this)','data-id'=>$dataProvider->allModels[$key]['id'], 'data-target'=>"#verietal-modal", 'style'=>'display:block;width:150px', 'data-toggle'=>"modal"]);
                                }
                            ], 
                                     /*[
                            'label'=>'sort',
                            'attribute'=>'reorder_id',
                            'format' => 'raw',
                            'label' => 'Order',
                            'value'=>  function ($model,$key)use($count,$dataProvider,$selected) {
//                                echo '<pre>';
//                                print_r($model);exit;
                              $enable = 'cursor:pointer';
                                if(!in_array($model['id'], $selected)){
                                    $enable = "cursor:pointer;display:none";
                                }
                            $arrow = '';
                            if($key != 0){
                                    //up
                                    if(!in_array($dataProvider->allModels[$key-1]['id'], $selected)){
                                      $enable = "cursor:pointer;display:none";   
                                    }
                                    $up_first_id =$dataProvider->allModels[$key-1]['id'];
                                    $current_id =$model['id'];
                                    $arrow.='<a class="fa fa-arrow-up" href="javascript:void(0)" onclick="reorder('.$current_id.','.$up_first_id.')" title="up" style="'.$enable.'"></a>';
                                    $arrow.=' ';
                            }
                            if($key != $count-1){
                                    if(!in_array($dataProvider->allModels[$key+1]['id'], $selected)){
                                      $enable = "cursor:pointer;display:none";   
                                    }
                                    $current_id = $up_first_id =$model['id'];
                                    $down_key =$dataProvider->allModels[$key+1]['id'];
                                    $arrow.='<a class="fa fa-arrow-down" href="javascript:void(0)"  onclick="reorder('.$current_id.','.$down_key.')" title="down" style="'.$enable.'"></a>';
                              
                            }
                            return $arrow;
                            },
//                            'format' => ['image',['height'=>'64px']],
                        ],*/
                        ],
                    ]);
                    ?>
                    <table class="table table-striped table-bordered">
                        <tbody>
                            <tr data-key="0">
                                <td style="width:10%"></td>
                                <td style="width:20%;text-align: right;padding-top: 15px;font-weight: bold;">Total</td>
                                <td style="width:10%"><input type="text" id="totalShares" name="totalShares" value="<?=$totalBranSharesCount?>" readOnly disabled class="form-control numericOnly" style="text-align:center;" /></td>
                                <td style="width:60%"></td>
                            </tr>
                        </tbody>
                    </table>
                    <script type="text/javascript">
                        function changeCurrentVariatalPoupId(elem){
                            $.each( $('[name="varietalShares[]"]'), function( key, value ) {
                                $(value).val(0);
                            });
                            $('#selectedBrandsKey').val($(elem).attr('data-id'));
                        }
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
                        $("#searchClear").on('click',function(){
                            $(this).prev().val(''); 
                            $('#search-users').submit();
                        });
                    </script>

                </div>
                <div class="row">
                <div class="col-md-6 isDisplay">
                        <?= Html::Button('Save', ['class' => 'btn btn-primary pull-left mw-md auto_fill', 'style' => 'margin-top:25px;margin-bottom:20px;margin-left:20px;', 'disabled' => 'disabled']) ?>
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
              $(".auto_fill").removeAttr('disabled');
        });

        $('.select-on-check-all').on('ifUnchecked', function (event) {
            $('input[name="selection[]"]').iCheck('uncheck');
            $(".auto_fill").removeAttr('disabled');
        });

        $(".auto_fill").on('click', function () {
            var $isNotFullSetShare = 1;
            var $totalShare = 0;
           
            $.each( $('[name="shares[]"]'), function(key, value ) {
             
                var $jsonVal = $('#varietalShareObject_'+$(value).attr('data-id')).val();
                try {
                    $jsonVal = JSON.parse($jsonVal);
                } catch (e) {
                    $jsonVal = '';
                }
                var $vertielShareCount = 0;
                if($jsonVal != ''){
                    $.each( $jsonVal, function( vkey, vvalue ) {
                        $vertielShareCount += (vvalue.share != '') ? vvalue.share : 0;
                    });
                }
                if($(value).val() != ''){
                    if($vertielShareCount != 100 && parseInt($(value).val()) > 0){
                        $isNotFullSetShare = 0;
                       
                    }
                }
                $totalShare = $totalShare + parseInt($(value).val());
            });
         
            if($isNotFullSetShare == 0){
                alert('Brand and selected brand verietal share should be equal to 100.');
            }else{
                 $("#w1").submit();
            }
            /*selectedBrand  = selectedBrand.filter(Boolean);
            console.log(selectedBrand);
            if (selectedBrand!=undefined && selectedBrand.length > 0)
            {
                $("#selectedBrand").val(selectedBrand);
                $("#w1").submit();
                return true;
            } else {
                alert("Please select at least one Brand");
                return false;
            }*/
        });



    </script>
    <?php Pjax::end(); ?>
</div>
<div class="modal fade" id="modal-default" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Select Product Brand</h4>
            </div>
            <div class="modal-body">
                <?= Html::dropDownList('limit', isset($filters['limit']) ? $filters['limit'] : '' ,$brands,  ['class' => 'form-control','id' => 'user-limit']) ?>
            </div>
            <div class="modal-footer">
                <?= \yii\helpers\Html::a( 'Add Brand', null,['class' => 'btn btn-primary', 'data-toggle'=>"modal", 'data-target'=>"#modal-default"]);?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="verietal-modal" style="display: none;">
    <div class="modal-dialog" style="overflow-y: initial !important;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Select Product Brand</h4>
            </div>
            <input type="hidden" value="" id="selectedBrandsKey" name="selectedBrandsKey" value="" />
            <div class="modal-body" style="height: 451px;overflow-y: auto;">
                <?=
                    GridView::widget([
                        'dataProvider' => $productVarietalDataProvider,
                        'layout' => '<div class="table-responsive">{items}</div><div class="row"><div class="col-sm-5">{summary}</div><div class="col-sm-7"><div class="dataTables_paginate paging_simple_numbers">{pager}</div></div></div>',
                        'columns' => [
                                ['class' => 'yii\grid\SerialColumn','contentOptions' => ['style' => 'width:10%'],],
                            [
                                'label' => 'Name',
                                'attribute' => 'name',
                                'contentOptions' => ['style' => 'width:20%'],
                            ],
                            [
                                'label' => 'Share',
                                'format' => 'raw',
                                'contentOptions' => ['style' => 'width:10%'],
                                'value'=>  function ($model,$key)use($count,$productVarietalDataProvider,$selected) {
                                    return '<input type="text" id="varietalShares[]" data-id="'.$productVarietalDataProvider->allModels[$key]['id'].'" name="varietalShares[]" value="0" class="form-control numericOnly" style="text-align:center;" />';
                                }
                            ],
                        ],
                    ]);
                    ?>
                    <table class="table table-striped table-bordered">
                        <tbody>
                            <tr data-key="0">
                                <td style="width:10%"></td>
                                <td style="width:20%;text-align: right;padding-top: 15px;font-weight: bold;">Total</td>
                                <td style="width:10%"><input type="text" id="totalVarietalShares" name="totalVarietalShares" value="0" readOnly disabled class="form-control numericOnly" style="text-align:center;" /></td>
                            </tr>
                        </tbody>
                    </table>
            </div>
            <div class="modal-footer">
                <?= \yii\helpers\Html::a( 'Save', null,['class' => 'btn btn-primary', 'onClick'=>'storeCurrentVarietalShares();']);?>
            </div>
        </div>
    </div>
</div>
<script>
    function storeCurrentVarietalShares(){
        var $totalVarietalShare = 0;
        var finalArray = [];
        $.each( $('[name="varietalShares[]"]'), function( key, value ) {
            finalArray[key] = {'id':parseInt($(value).attr('data-id')),'share':parseInt($(value).val())};
            $totalVarietalShare = $totalVarietalShare + parseInt($(value).val());
        });
        if($totalVarietalShare == 100 && $('#totalVarietalShares').val() == 100 ){
            $('#varietalShareObject_'+$('#selectedBrandsKey').val()).val(JSON.stringify(finalArray));
            $('#verietal-modal').modal('hide');
        }else{
            alert('Total shares should be equal to 100.');
        }
    }
    $("body").on("change", "#user-text,#user-limit", function (event) {
        $('#search-users').submit();
    });   
       var baseUrl = "<?php echo Yii::$app->request->baseUrl; ?>";
     function reorder(current_id,replaced_id){
        url = baseUrl+"/apply/re-order";
        market_id = '<?php echo $market_id ?>';
        $.ajax({
                        type: 'POST',
                        url: url,
                        data:{current_id:current_id,replaced_id:replaced_id,market_id:market_id},
                        success: function (data)
                        {
//                            alert(data);
//                            return false;
                           location.reload();
                        }
        });
    }
</script>