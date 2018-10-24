<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\CommonHelper;
use yii\helpers\Url;

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
$formUrl = Url::to(['catalogues/index']);
//echo '<pre>';
//print_r($dataProvider);exit;
$count = count($dataProvider->allModels);
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
<?= Html::encode($this->title) ?>
                </h3>
                <div class="row pull-right">
                    <div class="col-md-2">
                        <?php if (CommonHelper::checkPermission('Catalogues.Create')) { ?>
                            <?= Html::a('Add Product', ['create'], ['class' => 'btn btn-primary']) ?>
<?php } ?>
                    </div>
                   
                    <div class="col-md-10">
<?= Html::beginForm($formUrl, 'get', ['data-pjax' => '', 'id' => 'search-catalogue']); ?>
                        <div class="filter-search dataTables_filter clearfix">
                            <div class="row">
                                <div class="col-md-3">
<?= Html::input('text', 'search', isset($filters['search']) ? $filters['search'] : '', ['class' => 'form-control', 'placeholder' => 'Search', 'id' => 'user-text']) ?>
 <span id="searchclear" class="glyphicon glyphicon-remove"></span>
                                </div>

                                 <div class="col-md-3">
<?= Html::dropDownList('brand_id', isset($filters['brand_id']) ? $filters['brand_id'] : '', $brand, ['class' => 'form-control select2', 'id' => 'brand_market', 'prompt' => 'Select Brand']) ?>
                                </div>
                                <div class="col-md-4">
<?= Html::dropDownList('product_id', isset($filters['product_id']) ? $filters['product_id'] : '', $product, ['class' => 'form-control select2', 'id' => 'product_id', 'prompt' => 'Select Product Category']) ?>
                                </div>
                                <div class="col-md-2">
                                    <?= Html::dropDownList('limit', isset($filters['limit']) ? $filters['limit'] : '' ,Yii::$app->params['limit'],  ['class' => 'form-control','id' => 'user-limit']) ?>
                                </div>

                            </div>
                        </div>
<?= Html::endForm(); ?>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <?=
                GridView::widget([ 
                    'dataProvider' => $dataProvider,
                    'layout' => '<div class="table-responsive">{items}</div><div class="row"><div class="col-sm-5">{summary}</div><div class="col-sm-7"><div class="dataTables_paginate paging_simple_numbers">{pager}</div></div></div>',
                    'columns' => [
                            [
                            'class' => 'yii\grid\SerialColumn'],
                        [
                            'label'=>'Image',
                            'attribute'=>'image',
                            'contentOptions' => ['style' => 'text-align: center;'],
                            'headerOptions' => ['style' => 'text-align: center;'],
                            'value'=>  function ($model) {
                                if($model['image']){
                                    return  CommonHelper::getPath("upload_url").'catalogues/'.$model['image'];
                                }
                                else{
                                     return  CommonHelper::getPath("upload_url").'no-image.png'; 
                                }
                            },
                            'format' => ['image',['height'=>'64px']],
                        ],
                              
                        [
                            'label'=>'sort',
                            'attribute'=>'reorder_id',
                            'format' => 'raw',
                            'label' => 'Order',
                            'value'=>  function ($model,$key)use($count,$dataProvider) {
                             
                            $arrow = '';
                            if($key != 0){
                                    //up
                                    $up_first_id =$dataProvider->allModels[$key-1]['reorder_id'];
                                    $current_id =$model['reorder_id'];
                                    $arrow.='<a class="fa fa-arrow-up" href="javascript:void(0)" onclick="reorder('.$current_id.','.$up_first_id.')" title="up" style="cursor:pointer;"></a>';
                                    $arrow.=' ';
                            }if($key != $count-1){
                                    
                                    $current_id = $up_first_id =$model['reorder_id'];
                                    $down_key =$dataProvider->allModels[$key+1]['reorder_id'];
                                    $arrow.='<a class="fa fa-arrow-down" href="javascript:void(0)"  onclick="reorder('.$current_id.','.$down_key.')" title="down" style="cursor:pointer;"></a>';
                              
                            }
                            return $arrow;
                            },
//                            'format' => ['image',['height'=>'64px']],
                        ],
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
                            'class' => 'yii\grid\ActionColumn',
                            'header' => 'Actions',
                            'template' => '{view} {update} {delete}',
                            'buttons' => [
                                'view' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['catalogues/view/' . $model['id']],['title'=>'View']);
                                },
                                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['catalogues/update/' . $model['id']],['title'=>'Update']);
                                },
                                'delete' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['catalogues/delete/' . $model['id']], ['data-method' => 'post', 'data-confirm' => 'Are you sure want to delete this catalogues?','title'=>'Delete']);
                                },
                            ],
                        ],
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $("body").on("change", "#user-type,#product_id,#user-text,#user-limit,#user-market,#brand_market", function (event) {
       $('#search-catalogue').submit();
    });
    var baseUrl = "<?php echo Yii::$app->request->baseUrl; ?>";
    function reorder(current_id,replaced_id){
        url = baseUrl+"/catalogues/re-order";
        $.ajax({
                        type: 'POST',
                        url: url,
                        data:{current_id:current_id,replaced_id:replaced_id},
                        success: function (data)
                        {
//                            alert(data);
//                            return false;
                           location.reload();
                        }
        });
    }
</script>