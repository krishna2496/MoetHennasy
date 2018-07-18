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
<div class="col-sm-5 pull-right" id="tab-step-1">
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
                            
					<div class="form-group">
						<label for="dispaly_name">Name Your Display</label>
                                                <input type="hidden" name="ratio" value="5.5" id="ratio">
                                                <input type="text" id="dispaly_name" name="display_name" class="form-control" placeholder="Name of the display 1" required autocomplete="off" autofocus="true"/>
					</div>
					<div class="frame-chose-option">
						<div class="box box-default shelfs-store">
							<div class="box-header with-border">
								<h3 class="box-title">SHELF's store</h3>
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-2x fa-angle-up"></i>
									</button>
								</div>
								<!-- /.box-tools -->
							</div>
							<!-- /.box-header -->
							<div class="box-body">
								<div class="form-group">
									<div class="label-text">
										<label>Number of shelves <sup class="error-text">*</sup></label> <span class="info-icon"><i class="fa fa-info-circle"></i></span>
										<span class="slider-input size-label">
											<input type="text" class="form-control" id="ex6SliderVal" name="no_of_shelves" value="<?=  yii::$app->params['shelfConfig']['1']; ?>" required="" />
										</span>
									</div>
									<div class="slider-content">
										<span class="start-val"><?= yii::$app->params['no_of_shelves']['min'] ?></span>
										<input id="ex6" class="slider" type="text" data-slider-min="<?= yii::$app->params['no_of_shelves']['min'] ?>" data-slider-max="<?= yii::$app->params['no_of_shelves']['max'] ?>" data-slider-step="1" data-slider-value="<?= yii::$app->params['shelfConfig']['1']; ?>" data-slider-id="golden" />
										<span class="end-val"><?= yii::$app->params['no_of_shelves']['max'] ?></span>
									</div>
								</div>
								<div class="form-group">																			
									<div class="label-text">
										<label>Height of shelves<sup class="error-text">*</sup></label> <span class="info-icon"><i class="fa fa-info-circle"></i></span>
										<span class="slider-input size-label">																				
											<input type="text" class="form-control" id="hex6SliderVal" name="height_of_shelves" value="<?=  yii::$app->params['shelfConfig']['0']; ?>" required="" />
											<span class="size-label">cm</span>
										</span>
									</div>
									<div class="slider-content">
										<span class="start-val"><?= yii::$app->params['height_of_shelves']['min'] ?> cm</span>
										<input id="hex6" class="slider" type="text" data-slider-min="<?= yii::$app->params['height_of_shelves']['min'] ?>" data-slider-max="<?= yii::$app->params['height_of_shelves']['max'] ?>" data-slider-step="1" data-slider-value="<?=  yii::$app->params['shelfConfig']['0']; ?>" data-slider-id="golden" />
										<span class="end-val"><?= yii::$app->params['height_of_shelves']['max'] ?> cm</span>
									</div>
								</div>

								<div class="form-group">
									<div class="label-text">
										<label>width of shelves<sup class="error-text">*</sup></label> <span class="info-icon"><i class="fa fa-info-circle"></i></span>
										<span class="slider-input size-label">
											<input type="text" class="form-control" id="wex6SliderVal" name="width_of_shelves" value="<?= yii::$app->params['shelfConfig']['0']; ?>" required="" />
											<span class="size-label">cm</span>
										</span>
									</div>
									<div class="slider-content">
										<span class="start-val"><?= yii::$app->params['width_of_shelves']['min'] ?> cm</span>
										<input id="wex6" class="slider" type="text" data-slider-min="<?= yii::$app->params['width_of_shelves']['min'] ?>" data-slider-max="<?= yii::$app->params['width_of_shelves']['max'] ?>" data-slider-step="1" data-slider-value="<?=  yii::$app->params['shelfConfig']['0']; ?>" data-slider-id="golden" />
										<span class="end-val"><?= yii::$app->params['width_of_shelves']['max'] ?> cm</span>
									</div>
								</div>
								<div class="form-group">
									<div class="label-text">
										<label>Depth of shelves<sup class="error-text">*</sup></label> <span class="info-icon"><i class="fa fa-info-circle"></i></span>
										<span class="slider-input size-label">
											<input type="text" class="form-control" id="dex6SliderVal" name="depth_of_shelves" value="<?= yii::$app->params['shelfConfig']['3']; ?>" required="" />
											<span class="size-label">cm</span>
										</span>
									</div>
									<div class="slider-content">
										<span class="start-val"><?= yii::$app->params['depth_of_shelves']['min'] ?> cm</span>
										<input id="dex6" class="slider" type="text" data-slider-min="<?= yii::$app->params['depth_of_shelves']['min'] ?>" data-slider-max="<?= yii::$app->params['depth_of_shelves']['max'] ?>" data-slider-step="1" data-slider-value="<?=  yii::$app->params['shelfConfig']['3']; ?>" data-slider-id="golden" />
										<span class="end-val"><?= yii::$app->params['depth_of_shelves']['max'] ?> cm</span>
									</div>
								</div>
							</div>
							<!-- /.box-body -->
						</div>

						<div class="box box-default collapsed-box brand-option">
							<div class="box-header with-border">
								<h3 class="box-title">Brands</h3>
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-2x fa-angle-down"></i>
									</button>
								</div>
								<!-- /.box-tools -->
							</div>
							<!-- /.box-header -->
							<div class="box-body">
								<label class="barnd-select-msg">Select the brands present on the shelf <sup class="text-red">*</sup> </label>
								<ul class="brand-list list-unstyled">
									<?php
									if (!empty($brand))
									{
										foreach ($brand as $key => $value) {
											?>
											<li>
												<div class="checkbox"> 
													<label>
														<input class="role<?php echo $value['id']; ?>" name="brands[]" value="<?php echo $value['id']; ?>" type="checkbox">
		<?php echo $value['name']; ?>
													</label>
												</div>
											</li>
											<?php
										}
									}
									?>
								</ul>
							</div>
							<!-- /.box-body -->
						</div>
					</div>
					<div class="submit-fl wizard">
						<button class="btn reset-btn">Reset</button>
						<button class="next btn">Save</button>
					</div>
				
			</div>
			<!-- /.box-body -->
		</div>
	</div>
	<!-- End Frame Filter section -->
</div>