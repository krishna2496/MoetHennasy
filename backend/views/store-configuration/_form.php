<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\CommonHelper;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
//use kartik\switchinput\SwitchInput;

$formUrl = Url::to(['store-configuration/save-data']);
$secondFormUrl = Url::to(['store-configuration/save-product-data']);
if(isset($_SESSION['config']['products'])){
    $products = json_encode($_SESSION['config']['products'],true);
}
$session = Yii::$app->session;
?>
<script>
    var productArry = [];
    var productObject = {};
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
								<a href="#vtab1" data-toggle="tab" id="tab1" type="button" class="btn btn-success btn-circle" onclick="hideShowDiv(this.id)">
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

				<div class="box-body">
					<form id="firstForm"> 
						<div class="setup-content" >
							<div class="panel-body" id="tabs">
								<!--<div class="tab-pane active" id="vtab1">-->
								<div class="adv-form-element-sec active" id="vtab1">
									<div class="row">
										<div class="col-sm-7">
											<div class="frame-design">
												<div class="frame-title text-center">														
													<h3>Display 1: <span id="getName"><?=$session->get('config')['display_name'] !='' ? $session->get('config')['display_name'] : '[Name of the display]';?></span></h3>
												</div>
												<div class="frame-content" id="div-frame-content">
													<div class="top-bg bg-border">
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
									</div>
								</div>
							</div>
						</div>
					</form>		
				</div><!-- #validationWizard -->

			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var rackWidthValue = '<?php echo yii::$app->params['rackWidth'][0] ?>';
	var rackFromURL = '<?php echo $formUrl ?>';
	var rackProductFromURL = '<?php echo $secondFormUrl ?>';
</script>