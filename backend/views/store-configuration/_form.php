<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\CommonHelper;
use yii\helpers\Url;

$formUrl = Url::to(['store-configuration']);
?>
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
        margin-top: 0px !important;
        margin-bottom: 0px !important;
        position: relative;
        width: 500px;
        margin: 10px auto;
        border: 2px solid black;
        border-bottom: 1px solid black;
    }

    [id^=canvas-container]:last-child {
        border-bottom: 2px solid black;
    }

    [id^=canvas-container].over {
        box-shadow: 0 0 5px 1px black;
    }

    [id^=real-canvas] {
        padding: 2px;
        padding-right: 4px; 
        padding-bottom: 5px;
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


                        <div class="tab-content">

                            <div class="tab-pane active" id="vtab1">
                                <form id="firstForm">
                                    <div class="row" style="margin-top:10px">
                                        <section class="col-md-7">
                                            <div class="box">
                                                <div class="box-header">
                                                    <b>  Display 1 :
                                                        <span id="getName"></span></b>
                                                </div>
                                                <div class="box-body">

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
                                                        <input type="text" id="dispaly_name" name="dispaly_name" class="form-control" placeholder="Name of your display"/>
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
                                                                <div class="col-md-8">
                                                                    <label>Number of shelves*</label>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <input type="text" value="<?= yii::$app->params['shelfConfig']['1'] ?>" id="ex6SliderVal" name="num_of_shelves" class="form-control"/>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <input id="ex6" type="text" data-slider-min="<?= yii::$app->params['num_of_seleves']['min'] ?>" data-slider-max="<?= yii::$app->params['num_of_seleves']['max'] ?>" data-slider-step="1" data-slider-value="<?= yii::$app->params['shelfConfig']['1'] ?>"/>
                                                                    <!--<span id="ex6CurrentSliderValLabel">Current Slider Value: <span id="ex6SliderVal">3</span></span>-->
                                                                </div>
                                                            </div><hr>

                                                            <div class="row " style="margin-top:5px">
                                                                <div class="col-md-8">
                                                                    <label>Height of shelves*</label>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <input type="text" value="<?= yii::$app->params['shelfConfig']['0'] ?>" id="hex6SliderVal" name="num_of_shelves" class="form-control"/>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <input id="hex6" type="text" data-slider-min="<?= yii::$app->params['height_of_seleves']['min'] ?>" data-slider-max="<?= yii::$app->params['height_of_seleves']['max'] ?>" data-slider-step="1" data-slider-value="<?= yii::$app->params['shelfConfig']['0'] ?>"/>
                                                                    <!--<span id="ex6CurrentSliderValLabel">Current Slider Value: <span id="ex6SliderVal">3</span></span>-->
                                                                </div>
                                                            </div><hr>

                                                            <div class="row " style="margin-top:5px">
                                                                <div class="col-md-8">
                                                                    <label>Width of shelves*</label>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <input type="text" value="<?= yii::$app->params['shelfConfig']['0'] ?>" id="wex6SliderVal" name="num_of_shelves" class="form-control"/>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <input id="wex6" type="text" data-slider-min="<?= yii::$app->params['width_of_seleves']['min'] ?>" data-slider-max="<?= yii::$app->params['width_of_seleves']['max'] ?>" data-slider-step="1" data-slider-value="<?= yii::$app->params['shelfConfig']['0'] ?>"/>
                                                                    <!--<span id="ex6CurrentSliderValLabel">Current Slider Value: <span id="ex6SliderVal">3</span></span>-->
                                                                </div>
                                                            </div><hr>

                                                            <div class="row " style="margin-top:5px">
                                                                <div class="col-md-8">
                                                                    <label>Depth of shelves*</label>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <input type="text" value="<?= yii::$app->params['shelfConfig']['3'] ?>" id="dex6SliderVal" name="num_of_shelves" class="form-control"/>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <input id="dex6" type="text" data-slider-min="<?= yii::$app->params['depth_of_seleves']['min'] ?>" data-slider-max="<?= yii::$app->params['depth_of_seleves']['max'] ?>" data-slider-step="1" data-slider-value="<?= yii::$app->params['shelfConfig']['3'] ?>"/>
                                                                    <!--<span id="ex6CurrentSliderValLabel">Current Slider Value: <span id="ex6SliderVal">3</span></span>-->
                                                                </div>
                                                            </div><hr>
                                                        </div>

                                                    </div>


                                                    <div class="row" style="margin-top:10px">
                                                        <label>Brands</label><hr>
                                                        <div class="row">

                                                            <?php
                                                            if (!empty($brand)) {
                                                                foreach ($brand as $key => $value) {
                                                                    ?>
                                                                    <div class="col-md-6">

                                                                        <div class="col-md-2"> <input class="role<?php echo $value['id']; ?>" name="permissionscheck[]" value="<?php echo $value['name']; ?>" type="checkbox"></div><div class="col-md-6"><?php echo $value['name']; ?></div>

                                                                    </div>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane" id="vtab2">

                            </div>

                            <div class="tab-pane" id="vtab3">


                            </div>


                        </div>


                        <ul class="pager wizard">
                            <li class="previous disabled"><a href="javascript:void(0)">Previous</a></li>
                            <li class="next"><a href="javascript:void(0)">Next</a></li>
                        </ul>

                    </div><!-- #validationWizard -->

                </div><!-- panel-body -->
            </div><!-- panel -->
        </div>
    </div>
</div>

<script>
    $(function () {
        var mySlider = $("#ex6").slider();
        $("#ex6").on("slide", function (slideEvt) {
            $("#ex6SliderVal").val(slideEvt.value);
        });

        $('#ex6SliderVal').keyup(function () {
            var v = $("#ex6SliderVal").val();
            $('#ex6').slider('setValue', v);
        });

        var mySlider = $("#hex6").slider();
        $("#hex6").on("slide", function (slideEvt) {
            $("#hex6SliderVal").val(slideEvt.value);
        });

        $('#hex6SliderVal').keyup(function () {
            var v = $("#hex6SliderVal").val();
            $('#hex6').slider('setValue', v);
        });

        var mySlider = $("#wex6").slider();
        $("#wex6").on("slide", function (slideEvt) {
            $("#wex6SliderVal").val(slideEvt.value);
        });

        $('#wex6SliderVal').keyup(function () {
            var v = $("#wex6SliderVal").val();
            $('#wex6').slider('setValue', v);
        });

        var mySlider = $("#dex6").slider();
        $("#dex6").on("slide", function (slideEvt) {
            $("#dex6SliderVal").val(slideEvt.value);
        });

        $('#dex6SliderVal').keyup(function () {
            var v = $("#dex6SliderVal").val();
            $('#edx6').slider('setValue', v);
        });


    });
    $("#dispaly_name").on('keyup', function () {

        var name = $("#dispaly_name").val();
        $("#getName").text(name);
    })
    jQuery(document).ready(function () {
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
                return false;
            },
            onNext: function (tab, navigation, index) {
                var $valid = jQuery('#firstForm').valid();
                if (!$valid) {

                    $validator.focusInvalid();
                    return false;
                }
            }
        });

    });

    $("body").on("click", "#create", function () {
        check_flag = true;
        $(".height-rack").each(function () {
            if ($(this).val() == "" || $(this).val() == null) {
                check_flag = false;
            }
        });


        if (check_flag == false) {
            alert("Please enter height of all rack(s)");
        } else {
            a = [];
            //how many rack -2
            len = parseInt($("#canvas-value").val());

            for (i = 0; i < len; i++) {
                temp_height = $("#height-value-" + i + "").val();
                a.push('<div id="canvas-container-' + i + '" style="width:500px; height:' + parseInt(temp_height) + 'px"><canvas id="real-canvas-' + i + '" style="width:500px; height:' + parseInt(temp_height) + 'px" width="500" height="' + parseInt(temp_height) + '"></canvas></div>');
            }
            $("#canvas-generator").html(a);
            $(".add-height").hide();
            $(".height-input").hide();
            $('#save-restore').show();
            for (i = 0; i < len; i++) {
                $("#real-canvas-" + i + "").RackCanvas(i);
            }
        }
    });
    $("#add").click(function () {
        if ($("#canvas-value").val() == "") {
            alert("Please enter minimum 1 rack");
        } else {
            html = [];
            len = parseInt($("#canvas-value").val());
            html.push("<br><label>Please enter height of all the racks</label>");
            for (i = 0; i < len; i++) {
                html.push('<br><input type="number" class="form-control height-rack" id="height-value-' + i + '" pattern="\d*" maxlength="1" size="1">')
            }
            html.push('<br><input type="button" class="btn btn-info text-center" value="Create" id="create">')
            $(".height-input").html(html);
        }

    });

</script>