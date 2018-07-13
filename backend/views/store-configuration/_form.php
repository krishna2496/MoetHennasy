<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\CommonHelper;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;

$formUrl = Url::to(['store-configuration/save-data']);
$uploadShelf = Url::to(['store-configuration/save-image']);
//$reviewUrl = Url::to(['store-configuration/review-store']);
$secondFormUrl = Url::to(['store-configuration/save-product-data']);
$noOfSelves = isset($_SESSION['config']['products']) ? isset($_SESSION['config']['products']) : '1';
$ratio = isset($_SESSION['config']['ratio']) ? $_SESSION['config']['ratio'] : 5.5;
if (isset($_SESSION['config']['products'])) {
    $products = json_encode($_SESSION['config']['products'], true);
}
$session = Yii::$app->session;

?>
<script type="text/javascript">
    var isUpdate = '<?php echo $is_update; ?>';
   
    var brandThumbId = '<?php echo $brandThumbId ?>';
    var productArry = [];
    var productObject = {};
    var reviewFlag = '<?php echo $reviewFlag; ?>';
</script>
<div class="row">
    <div class="col-sm-12 stepwizard-content-section">
        <form id="tabForm">
            <input type="hidden" value="0" name="first" id="first"> 
            <input type="hidden" value="0" name="second" id="second"> 
            <input type="hidden" value="0" name="third" id="third"> 
        </form>
        <div class="box box-default">
            <div id="validationWizard" class="basic-wizard">
                <div class="box-header with-border">
                    <h3 class="box-title">Instruction/introduction texte. Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit. Duis sed odio sit amet nibh vulputate cursus a sit amet mauris.</h3>
                    <div class="stepwizard">
                        <ul class="stepwizard-row setup-panel list-unstyled list-inline">

                            <li class="stepwizard-step" id="3">
                                <a href="#vtab1" data-toggle="tab" id="tab1" type="button" class="btn btn-success btn-default btn-circle" onclick="hideShowDiv(this.id)">
                                    <span class="stepnum">1</span> <span class="stepname">CREATE YOUR DISPLAY</span>
                                </a>
                            </li>
                            <li class="stepwizard-step">
                                <a href="#vtab1" data-toggle="tab" id="tab2" type="button" class="btn btn-default btn-circle" disabled="disabled" onclick="hideShowDiv(this.id)" >
                                    <span class="stepnum">2</span> <span class="stepname">DEFINE PRODUCTS</span>
                                </a>
                            </li>
                            <li class="stepwizard-step">
                                <a href="#vtab1" data-toggle="tab" id="tab3" type="button" class="btn btn-default btn-circle" disabled="disabled"onclick="hideShowDiv(this.id)">
                                    <span class="stepnum">3</span> <span class="stepname">CONFIGURATION</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <form id="firstForm"> 
                    <div class="box-body">

                        <div class="setup-content" >
                            <div class="panel-body" id="tabs">
                                <!--<div class="tab-pane active" id="vtab1">-->
                                <div class="adv-form-element-sec active" id="vtab1">
                                    <div class="row">
                                        <div class="col-sm-7">
                                            <div class="frame-design" id="frame-design">
                                                <div class="frame-title text-center">														
                                                    <h3>Display: <span id="getName"><?= ( isset($_SESSION['config']['display_name']) && ($is_update == 1)) ? $_SESSION['config']['display_name'] : '' ?></span></h3>
                                                </div>
                                                <div class="frame-content" id="div-frame-content">
                                                    <div class="top-bg bg-border">
                                                        <ul class="brand-drop ">

                                                            <li class="active">
                                                                <h6>BRAND 1</h6>
                                                                <div id="brandImageHolder">Select Brand</div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="farme-start-content">
                                                        <div class="top-inner-bg bg-border">
                                                            <span class="top-in-lt"></span>
                                                            <span class="top-in-rt"></span>
                                                        </div>
                                                        <div class="left-bg bg-border">
                                                            <span class="left-tp"></span>
                                                            <span class="left-bt"></span>
                                                        </div>
                                                        <div class="right-bg bg-border">
                                                            <span class="right-tp"></span>
                                                            <span class="right-bt"></span>
                                                        </div>
                                                        <div class="bottom-bg bg-border">
                                                            <span class="bottom-lt"></span>
                                                            <span class="bottom-rt"></span>
                                                        </div>
                                                        <!-- frame divide section -->
                                                        <div class="frame-mid-section" id="canvas-generator"></div>
                                                        <!--End frame divide section-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>	

                                        <?php include_once("tabs-step-1.php"); ?>
                                        <?php include_once("tabs-step-2.php"); ?>
<?php include_once("tabs-step-3.php"); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div><!-- #validationWizard -->

                </form>	
            </div>
        </div>
    </div>
</div>
<div class="modal fade in" id="modalReview"></div><!-- /.modal-dialog -->
<script type="text/javascript">

    var rackFromURL = '<?php echo $formUrl ?>';
    var rackProductFromURL = '<?php echo $secondFormUrl ?>';
    var numOfSelves = '<?php echo $noOfSelves ?>';
    var uploadSelves = '<?php echo $uploadShelf ?>';
    var maxStar = '<?php echo yii::$app->params['star_max_size'][0] ;?>';
    if (isUpdate == '1') { 
        var reviewStoreUrl = '<?= Url::to(['store-configuration/review-store/' . $configId]); ?>';
        $("#ex6SliderVal").val('<?= isset($_SESSION['config']['no_of_shelves']) ?  $_SESSION['config']['no_of_shelves'] :''; ?>');
        $("#hex6SliderVal").val('<?= isset($_SESSION['config']['height_of_shelves']) ? $_SESSION['config']['height_of_shelves'] : ''; ?>');
        $("#wex6SliderVal").val('<?= isset($_SESSION['config']['width_of_shelves']) ? $_SESSION['config']['width_of_shelves'] : ''; ?>');
        $("#dex6SliderVal").val('<?= isset($_SESSION['config']['depth_of_shelves']) ? $_SESSION['config']['depth_of_shelves'] : ''; ?>');
    }
    var questionUrl = '<?= Url::to(['store-configuration/feedback/' . $configId]); ?>';
    $('#tab2').click(function (event) {
        if ($(this).attr('disabled')) {
            return false;
        } else {

        }
    });

    $('#tab1').click(function (event) {
        if ($(this).attr('disabled')) {
            return false;
        }
    });

    $('#tab3').click(function (event) {
        if ($(this).attr('disabled')) {
            return false;
        }
    });

</script>

<?php
/*
yii\bootstrap\Modal::begin([
    //'headerOptions' => ['id' => 'modalHeader'],
    'id' => 'modalReview',
    'size' => 'modal-lg',
    //keeps from closing modal with esc key or by clicking out of the modal.
    // user must click cancel or X to close
    'clientOptions' => ['keyboard' => true]
]);
echo "<div id='content'></div>";
yii\bootstrap\Modal::end();
*/
?>