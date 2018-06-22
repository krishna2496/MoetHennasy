<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\CommonHelper;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\switchinput\SwitchInput;

$formUrl = Url::to(['store-configuration/save-data']);
$secondFormUrl = Url::to(['store-configuration/save-product-data']);
?>
<script>
    var productArry = [];
    var productObject = {};
</script>
<style type="text/css">

    .box-shelf {
        position: relative !important;
        border-radius: 3px !important;
        background: #ffffff !important;
        border: 0px solid #d2d6de !important;
        margin-bottom: 10px !important;
        width: 100% !important;
        box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.1) !important;
    }
    .box-shelf .box-header{
        border-bottom: 1px solid #947549;
    }
    .shelf-color{
        color:#947549 !important;  
        font-size: 14px !important;
        font-weight: bolder;
    }
    .box {
        position: relative;
        border-radius: 3px;
        background: #ffffff;
        border: 1px solid #d2d6de;
        margin-bottom: 20px;
        width: 100%;
        box-shadow: 9px 9px 9px rgba(0, 0, 0, 0.1);
        padding: 9px !important;
    }
    .box.box-danger {
        border-color: #947549;
    }
    /*     #canvas-generator {
        background-image: url("images/bar.png");
        background-repeat: no-repeat;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    } */

    #ex6 .slider-selection {
        background:red;
    }
    [id^=canvas-container] {
        /*        margin-top: 0px !important;
                margin-bottom: 0px !important;*/
        margin:auto !important;
        position: relative;
        /*        width: 500px;
                margin: 10px auto;*/
        border: 2px solid black;
        border-bottom: 1px solid black;
    }
    #main-rack{
        padding-right: 50px;
        padding-left: 50px;
        padding-bottom: 20px;
        padding-top: 20px;
        /*background-color: green;*/
        width: 650px;
        height:auto;

        /*border: 10px solid red;*/
    }
    [id^=main-rack]:last-child {
        padding-bottom: 80px !important;
    }/*
    */     [id^=main-rack]:first-child {
        padding-top: 80px !important;
    }

    /*    [id^=canvas-container]:last-child {
            border-bottom: 20px solid black;
        }
        [id^=canvas-container]:first-child {
            border-top: 20px solid black;
        }*/

    [id^=canvas-container].over {
        box-shadow: 0 0 5px 1px black;
    }

    [id^=real-canvas] {
        /*        padding: 2px;
                padding-right: 4px; 
                padding-bottom: 5px;*/
    }

    #images img.img_dragging {
        opacity: 0.4;
    }

    [draggable] {
        -moz-user-select: none;
        -khtml-user-select: none;
        -webkit-user-select: none;
        user-select: none;
        /* Required to make elements draggable in old WebKit */
        -khtml-user-drag: element;
        -webkit-user-drag: element;
        cursor: move;
    }
    .deleteBtn{
        height: 30px;
        width: 30px;
    }
    .custom-menu {
        display: none;
        z-index: 1000;
        position: absolute;
        overflow: hidden;
        border: 1px solid #CCC;
        white-space: nowrap;
        font-family: sans-serif;
        background: #FFF;
        color: #333;
        border-radius: 5px;
    }

    .custom-menu li {
        padding: 8px 12px;
        cursor: pointer;
    }

    .custom-menu li:hover {
        background-color: #DEF;
    }
    .box-margin{
        margin-bottom: 8px;
    }
    .brand-margin{
        margin-bottom: 5px !important;
    }
    label.error {
        color: #B94A48;
        margin-top: 2px;
    }


</style>
<div class="market-segments-form">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body panel-body-nopadding">

                    <!-- BASIC WIZARD -->
                    <div id="validationWizard" class="basic-wizard">

                        <ul class="nav nav-pills nav-justified nav-disabled-click">
                            <li class="active"><a href="#vtab1" data-toggle="tab">CREATE YOUR DISPLAY</a></li>
                            <li><a href="#vtab2" data-toggle="tab">DIFINE PRODUCTS</a></li>
                            <li><a href="#vtab3" data-toggle="tab">CONFIGURATION</a></li>
                        </ul>


                        <div class="tab-content" id="tabs">

                            <div class="tab-pane active" id="vtab1">
                                <form id="firstForm"> 
                                    <div class="row" style="margin-top:10px">
                                        <section class="col-md-7">
                                            <div class="box">
                                                <div class="box-header">
                                                    <b>  Display 1 :
                                                        <span id="getName"></span>
                                                    </b>
                                                </div>
                                                <div class="box-body">
                                                    <div class="row">
                                                        <!--<div class="col-md-1"></div>-->

                                                        <div class="col-md-10" id="canvas-generator">safasf safsa safsaf safsaf sa safsaf safsf safsafsf</div>

                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                        <section class="col-md-5">
                                            <div class="box box-danger">
                                                <div>
                                                    <span><b style="text-align:center"> Display </b></span>
                                                </div>
                                                <div class="box-body with-borde">
                                                    <div class="box-margin">
                                                        <label>Name your display</label>
                                                        <input type="text" id="dispaly_name" name="display_name" class="form-control" placeholder="Name of your display" required/>
                                                    </div>
                                                    <div class="box box-shelf">
                                                        <div class="box-header with-border">
                                                            <h3 class="box-title shelf-color " >SHELF'S STORE</h3>
                                                            <div class="box-tools pull-right">
                                                                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                                                                    <i class="fa fa-minus"></i></button>
                                                            </div>
                                                        </div>
                                                        <div class="box-body" style="">

                                                            <div class="row " style="margin-top:5px">
                                                                <div class="form-group">
                                                                    <div class="col-md-8">
                                                                        <label>Number of shelves*</label>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <input type="text" value="<?= yii::$app->params['shelfConfig']['1'] ?>" id="ex6SliderVal" name="num_of_shelves" class="form-control" required=""/>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <input id="ex6" type="text" data-slider-min="<?= yii::$app->params['num_of_seleves']['min'] ?>" data-slider-max="<?= yii::$app->params['num_of_seleves']['max'] ?>" data-slider-step="1" data-slider-value="<?= yii::$app->params['shelfConfig']['1'] ?>"/>
                                                                        <!--<span id="ex6CurrentSliderValLabel">Current Slider Value: <span id="ex6SliderVal">3</span></span>-->
                                                                    </div>
                                                                </div>
                                                            </div><hr>

                                                            <div class="row " style="margin-top:5px">
                                                                <div class="form-group">
                                                                    <div class="col-md-8">
                                                                        <label>Height of shelves*</label>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <input type="text" value="<?= yii::$app->params['shelfConfig']['0'] ?>" id="hex6SliderVal" name="height_of_shelves" class="form-control" required="" />
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <input id="hex6" type="text" data-slider-min="<?= yii::$app->params['height_of_seleves']['min'] ?>" data-slider-max="<?= yii::$app->params['height_of_seleves']['max'] ?>" data-slider-step="1" data-slider-value="<?= yii::$app->params['shelfConfig']['0'] ?>"/>
                                                                        <!--<span id="ex6CurrentSliderValLabel">Current Slider Value: <span id="ex6SliderVal">3</span></span>-->
                                                                    </div>
                                                                </div>
                                                            </div><hr>

                                                            <div class="row " style="margin-top:5px">
                                                                <div class="form-group">
                                                                    <div class="col-md-8">
                                                                        <label>Width of shelves*</label>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <input type="text" value="<?= yii::$app->params['shelfConfig']['0'] ?>" id="wex6SliderVal" name="width_of_shelves" class="form-control" required="" />
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <input id="wex6" type="text" data-slider-min="<?= yii::$app->params['width_of_seleves']['min'] ?>" data-slider-max="<?= yii::$app->params['width_of_seleves']['max'] ?>" data-slider-step="1" data-slider-value="<?= yii::$app->params['shelfConfig']['0'] ?>"/>
                                                                        <!--<span id="ex6CurrentSliderValLabel">Current Slider Value: <span id="ex6SliderVal">3</span></span>-->
                                                                    </div>
                                                                </div>
                                                            </div><hr>

                                                            <div class="row " style="margin-top:5px">
                                                                <div class="form-group">
                                                                    <div class="col-md-8">
                                                                        <label>Depth of shelves*</label>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <input type="text" value="<?= yii::$app->params['shelfConfig']['3'] ?>" id="dex6SliderVal" name="depth_of_shelves" class="form-control" required="" />
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <input id="dex6" type="text" data-slider-min="<?= yii::$app->params['depth_of_seleves']['min'] ?>" data-slider-max="<?= yii::$app->params['depth_of_seleves']['max'] ?>" data-slider-step="1" data-slider-value="<?= yii::$app->params['shelfConfig']['3'] ?>"/>
                                                                        <!--<span id="ex6CurrentSliderValLabel">Current Slider Value: <span id="ex6SliderVal">3</span></span>-->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="box box-shelf">
                                                        <div class="box-header with-border">
                                                            <h3 class="box-title shelf-color " >Brands</h3>
                                                            <div class="box-tools pull-right">
                                                                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                                                                    <i class="fa fa-minus"></i></button>
                                                            </div>
                                                        </div><div class="box-body" style="">
                                                            <div class="row">

                                                                <?php
                                                                if (!empty($brand)) {
                                                                    foreach ($brand as $key => $value) {
                                                                        ?>
                                                                        <div class="col-md-6 brand-margin">

                                                                            <div class="col-md-2"> <input class="role<?php echo $value['id']; ?>" name="brands[]" value="<?php echo $value['id']; ?>" type="checkbox"></div><div class="col-md-10"><?php echo $value['name']; ?></div>

                                                                        </div>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane" id="vtab2">
                                <form id="secondForm"> 
                                    <div class="row" style="margin-top:10px">
                                        <section class="col-md-6">
                                            <div class="box">
                                                <div class="box-header">
                                                    <b>  Display 1 : 
                                                        <span id="getName"></span>
                                                    </b>
                                                </div>
                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="col-md-1"></div>

                                                        <div class="col-md-10" id="canvas-generator2">safasf safsa safsaf safsaf sa safsaf safsf safsafsf</div>

                                                        <div class="col-md-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                        <section class="col-md-6">
                                            <div class="box box-danger">
                                                <div>
                                                    <span><b style="text-align:center"> Display </b></span>
                                                </div>
                                                <div class="box-body with-borde">

                                                    <div class="box box-shelf">
                                                        <div class="box-header with-border">
                                                            <h3 class="box-title shelf-color " >PRODUCTS SKU</h3>
                                                            <div class="box-tools pull-right">
                                                                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                                                                    <i class="fa fa-minus"></i></button>
                                                            </div>
                                                        </div>
                                                        <?php Pjax::begin(['id' => 'employee', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'POST']]) ?>

                                                        <div class="box-body">
                                                            <?=
                                                            GridView::widget([
                                                                'dataProvider' => $dataProvider,
                                                                'layout' => '<div class="table-responsive">{items}</div><div class="row"><div class="col-sm-12"><div class="dataTables_paginate paging_simple_numbers">{pager}</div></div></div>',
                                                                'columns' => [
                                                                        [
                                                                        'class' => 'yii\grid\SerialColumn'],
                                                                        [
                                                                        'class' => 'yii\grid\CheckboxColumn', 'checkboxOptions' => function($model) {
                                                                            return ['value' => $model['id'], 'selection' => true, 'class' => 'checked'];
                                                                        },
                                                                    ],
                                                                        [
                                                                        'label' => 'Product',
                                                                        'attribute' => 'short_name',
                                                                        'value' => 'short_name'
                                                                    ],
                                                                        [
                                                                        'label' => 'Product type',
                                                                        'attribute' => 'productType',
                                                                        'value' => 'productType.title'
                                                                    ],
                                                                    'market_share',
                                                                        [
                                                                        'label' => 'Product Category',
                                                                        'attribute' => 'productCategory',
                                                                        'value' => 'productCategory'
                                                                    ],
                                                                        [
                                                                        'attribute' => 'top_shelf',
                                                                        'format' => 'raw',
                                                                        'value' => function($model) {
                                                                            $value = ($model['top_shelf'] == 0) ? false : true;
                                                                            $dvalue = ($model['top_shelf'] == 0) ? "0" : "1";
                                                                            return '<div class="idDiv" dvalue="' . $dvalue . '" id="' . $model["id"] . '">' . SwitchInput::widget([
                                                                                    'name' => 'status_41[]',
                                                                                    'value' => $value,
                                                                                    'pluginOptions' => [
                                                                                        'onText' => 'Yes',
                                                                                        'offText' => 'No'
                                                                                    ]
                                                                                ]) . '</div>';
                                                                        }
                                                                    ]
                                                                ],
                                                            ]);
                                                            ?>
                                                        </div>
                                                        <script type="text/javascript">
                                                            $(document).ready(function () {
                                                                $('input[name="selection[]"]').each(function (skey, sval) {
                                                                    var sobj = {};
                                                                    sobj["sel"] = false;
                                                                    sobj["shelf"] = 'undefined';
                                                                    if (typeof (productObject[$(sval).val()]) === 'undefined')
                                                                    {
                                                                        productObject[$(sval).val()] = sobj;
                                                                    }
                                                                    if (typeof (productObject[$(sval).val()]) !== 'undefined' && productObject[$(sval).val()]["sel"] === true)
                                                                    {
                                                                        $('input[type="checkbox"][value="' + $(sval).val() + '"]').attr('checked', true).iCheck('update');
                                                                    }
                                                                    if ((productObject[$(sval).val()] !== 'undefined') && productObject[$(sval).val()]["shelf"] === true)
                                                                    {
                                                                        $('div#' + $(sval).val() + ' input[name="status_41[]"]').bootstrapSwitch('state', true);
//                                                                         $('div#'+$(sval).val()+' input[name="status_41[]"]').bootstrapSwitch('toggleState', true);
                                                                    }
                                                                    if ((productObject[$(sval).val()] !== 'undefined') && productObject[$(sval).val()]["shelf"] === false)
                                                                    {
                                                                        $('div#' + $(sval).val() + ' input[name="status_41[]"]').bootstrapSwitch('state', false);
//                                                                         $('div#'+$(sval).val()+' input[name="status_41[]"]').bootstrapSwitch('toggleState', true);
                                                                    }
//                                                                    if(productObject[$(sval).val()]["shelf"] === false)
//                                                                    {                                                                            
//                                                                 
//                                                                   console.log($(sval).val());
//                                                                         $('div#'+$(sval).val()+' input[name="status_41[]"]').bootstrapSwitch('toggleState', false);
//                                                                    }
//                                                                    if(productObject[$(sval).val()]["shelf"] === false)
//                                                                    {                                                                            
//                                                                   
//                                                                         $('div#'+$(sval).val()+' input[name="status_41[]"]').bootstrapSwitch('toggleState', false, false);
//                                                                    }
                                                                    //productArry.push({$(sval).val()});
                                                                });
                                                                console.log(productObject);
                                                            });

                                                            $('.select-on-check-all').on('ifChecked', function (event) {

                                                                $('input[name="selection[]"]').iCheck('check');

                                                            });

                                                            $('.select-on-check-all').on('ifUnchecked', function (event) {
                                                                alert(1);
                                                                $('input[name="selection[]"]').iCheck('uncheck');


                                                            });
                                                            $('input[name="selection[]"]').on('ifChecked', function (event) {

                                                                if (typeof (productObject[$(this).val()]) !== 'undefined')
                                                                {
                                                                    productArry.push($(this).val());
                                                                    var id = $(this).val();
                                                                    var switchValue = $("div#" + id).attr("dvalue");
                                                                    var switchFlag = (switchValue == "1") ? true : false;
                                                                    productObject[$(this).val()]['sel'] = true;
                                                                    productObject[$(this).val()]['shelf'] = switchFlag;
                                                                }
                                                            });
                                                            $('input[name="selection[]"]').on('ifUnchecked', function (event) {
                                                                popedValue = productArry.indexOf($(this).val());
                                                                productArry.splice(popedValue, 1);
                                                                if (typeof (productObject[$(this).val()]) !== 'undefined')
                                                                {
                                                                    var id = $(this).val();
                                                                    var switchValue = $("div#" + id).attr("dvalue");
                                                                    var switchFlag = (switchValue == "1") ? true : false;
                                                                    productObject[$(this).val()]['sel'] = false;
                                                                    productObject[$(this).val()]['shelf'] = switchFlag;
                                                                }
                                                            });
                                                            $('input[name="status_41[]"]').on('switchChange.bootstrapSwitch', function (event, state) {
                                                                var id = $(this).closest('div.idDiv').attr("id");
                                                                if ($(this).bootstrapSwitch('state')) {
                                                                    productObject[id]['shelf'] = true;
                                                                    $("div#" + id).attr("dvalue", "1")
                                                                } else {
                                                                    productObject[id]['shelf'] = false;
                                                                    $("div#" + id).attr("dvalue", "0")
                                                                }
                                                            });
                                                            /*productArry.forEach(function(item){
                                                             $("input[type=checkbox][value=" +item+ "]").attr("checked", "true")
                                                             });*/

                                                        </script>
                                                        <?php Pjax::end(); ?>

                                                    </div>

                                                </div>
                                            </div>
                                        </section>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane" id="vtab3">


                            </div>


                        </div>


                        <ul class="pager wizard">
                            <li class="previous disabled"><a href="javascript:void(0)">Reset</a></li>
                            <li class="next"><a href="javascript:void(0)">Ok</a></li>
                        </ul>

                    </div><!-- #validationWizard -->

                </div><!-- panel-body -->
            </div><!-- panel -->
        </div>
    </div>
</div>

<script type="text/javascript">
    getRack();

    $(function () {
        var mySlider = $("#ex6").slider();
        $("#ex6").on("slide", function (slideEvt) {
            $("#ex6SliderVal").val(slideEvt.value);
            getRack();
        });

        $('#ex6SliderVal').keyup(function () {
            var v = $("#ex6SliderVal").val();
            $('#ex6').slider('setValue', v);
            getRack();
        });

        var mySlider = $("#hex6").slider();
        $("#hex6").on("slide", function (slideEvt) {
            $("#hex6SliderVal").val(slideEvt.value);
            getRack();
        });

        $('#hex6SliderVal').keyup(function () {
            var v = $("#hex6SliderVal").val();
            $('#hex6').slider('setValue', v);
            getRack();
        });

        var mySlider = $("#wex6").slider();
        $("#wex6").on("slide", function (slideEvt) {
            $("#wex6SliderVal").val(slideEvt.value);
            getRack();
        });

        $('#wex6SliderVal').keyup(function () {
            var v = $("#wex6SliderVal").val();
            $('#wex6').slider('setValue', v);
            getRack();
        });

        var mySlider = $("#dex6").slider();
        $("#dex6").on("slide", function (slideEvt) {
            $("#dex6SliderVal").val(slideEvt.value);
            getRack();
        });

        $('#dex6SliderVal').keyup(function () {
            var v = $("#dex6SliderVal").val();
            $('#edx6').slider('setValue', v);
            getRack();
        });


    });

    function getRack() {
        var noOfShelves = parseInt($("#ex6SliderVal").val());

        var rackHeight = parseInt($("#hex6SliderVal").val());
        var rackWidth = parseInt($("#wex6SliderVal").val());
        var rackDepth = parseInt($("#dex6SliderVal").val());
        var rackRatio = getRatio(rackWidth);

        var newRackHeight = Math.round((rackRatio * rackHeight) / (noOfShelves));
        var newRackWidth = Math.round(rackRatio * rackWidth);
        var newRackDepth = Math.round((rackRatio * rackDepth) / (noOfShelves));

        rack = [];

        for (i = 0; i < noOfShelves; i++) {
            rack.push('<div id="main-rack"><div id="canvas-container-' + i + '" style="width:' + parseInt(newRackWidth) + 'px; height:' + parseInt(newRackHeight) + 'px"><canvas id="real-canvas-' + i + '" style="width:' + parseInt(newRackWidth) + 'px; height:' + parseInt(newRackHeight) + 'px" width="' + parseInt(newRackWidth) + 'px" height="' + parseInt(newRackHeight) + ';"></canvas></div></div>');
        }

        $("#canvas-generator").html(rack);
        for (i = 0; i < noOfShelves; i++) {
            $("#real-canvas-" + i + "").RackCanvas(i);
        }
    }

    function getRatio(width) {
        var widthValue = '<?php echo yii::$app->params['rackWidth'][0] ?>';
        var ratio = (parseInt(widthValue) / parseInt(width));
        return ratio;
    }

    $("#dispaly_name").on('keyup', function () {
        var name = $("#dispaly_name").val();
        $("#getName").text(name);
    });

    jQuery(document).ready(function () {
        $("#tabs").tabs({active: 2});
        // Basic Wizard
        jQuery('#basicWizard').bootstrapWizard();

        // Progress Wizard
        $('#progressWizard').bootstrapWizard({
            'nextSelector': '.next',
            'previousSelector': '.previous',
            onNext: function (tab, navigation, index) {

                var $total = navigation.find('li').length;
                var $current = index + 1;
                var $percent = ($current / $total) * 100;
                jQuery('#progressWizard').find('.progress-bar').css('width', $percent + '%');
            },
            onPrevious: function (tab, navigation, index) {
                var $total = navigation.find('li').length;
                var $current = index + 1;
                var $percent = ($current / $total) * 100;
                jQuery('#progressWizard').find('.progress-bar').css('width', $percent + '%');
            },
            onTabShow: function (tab, navigation, index) {
                var $total = navigation.find('li').length;
                var $current = index + 1;
                var $percent = ($current / $total) * 100;
                jQuery('#progressWizard').find('.progress-bar').css('width', $percent + '%');
            }
        });

        // Disabled Tab Click Wizard
        jQuery('#disabledTabWizard').bootstrapWizard({
            tabClass: 'nav nav-pills nav-justified nav-disabled-click',
            onTabClick: function (tab, navigation, index) {
                return false;
            }
        });

        // With Form Validation Wizard
        var $validator = jQuery("#firstForm").validate({

            highlight: function (element) {
                jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
            },
            success: function (element) {
                jQuery(element).closest('.form-group').removeClass('has-error');
            }
        });

        jQuery('#validationWizard').bootstrapWizard({
            tabClass: 'nav nav-pills nav-justified nav-disabled-click',
            onTabClick: function (tab, navigation, index) {

                return true;
            },
            onNext: function (tab, navigation, index) {
                if (index == 1) {
                    if ($("#firstForm input:checkbox:checked").length <= 0)
                    {
                        alert("Please Select at least one brand");
                        return false;
                    }
                }
                if (index == 2) {
                    if (productArry == '')
                    {
                        alert("Please Select at least one product");
                        return false;
                    }
                }
                var $valid = jQuery('#firstForm').valid();
                if (!$valid) {

                    $validator.focusInvalid();
                    return false;
                } else {
                    if (index == 1) {
                        url = '<?php echo $formUrl ?>';
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: $("#firstForm").serialize(),
                            success: function (data) {
                                $.pjax.reload({container: '#employee'});

                            }
                        });

                    }
                    if (index == 2) {
                        url = '<?php echo $secondFormUrl ?>';
                       
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data:{productObject : productObject},
                            success: function (data) {
                                alert(data);
                                return false;
//                                $.pjax.reload({container: '#employee'});

                            }
                        });
                        return false;
                    }
                }
            }
        });

    });

</script>