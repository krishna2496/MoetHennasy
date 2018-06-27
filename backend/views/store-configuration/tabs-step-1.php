<div class="col-sm-5 pull-right" id="tab-step-1">
	<!-- Frame Filter section -->
	<div class="frame-filter-section">
		<div class="box filter-collapse-panle">
			<!-- collapsed-box -->
			<div class="box-header with-border">
				<h3 class="box-title">Display 1 <i class="fa fa-info-circle"></i></h3>
				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-down fa-3x"></i></button>
				</div>
				<!-- /.box-tools -->
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				<form class="frame-filt-form">
					<div class="form-group">
						<label for="dispaly_name">Name Your Display</label>																
						<input type="text" id="dispaly_name" name="display_name" class="form-control" placeholder="Name of the display 1" required/>
					</div>
					<div class="frame-chose-option">
						<div class="box box-default shelfs-store">
							<div class="box-header with-border">
								<h3 class="box-title">SHELF's store</h3>
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-2x fa-angle-down"></i>
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
											<input type="text" class="form-control" id="ex6SliderVal" name="num_of_shelves" value="<?= $session->get('config')['num_of_shelves'] != '' ? $session->get('config')['num_of_shelves'] : yii::$app->params['shelfConfig']['1']; ?>" required="" />
										</span>
									</div>
									<div class="slider-content">
										<span class="start-val">0</span>
										<input id="ex6" class="slider" type="text" data-slider-min="<?= yii::$app->params['num_of_shelves']['min'] ?>" data-slider-max="<?= yii::$app->params['num_of_shelves']['max'] ?>" data-slider-step="1" data-slider-value="<?= $session->get('config')['num_of_shelves'] != '' ? $session->get('config')['num_of_shelves'] : yii::$app->params['shelfConfig']['1']; ?>" data-slider-id="golden" />
										<span class="end-val">50</span>
									</div>
								</div>
								<div class="form-group">																			
									<div class="label-text">
										<label>Height of shelves<sup class="error-text">*</sup></label> <span class="info-icon"><i class="fa fa-info-circle"></i></span>
										<span class="slider-input size-label">																				
											<input type="text" class="form-control" id="hex6SliderVal" name="height_of_shelves" value="<?= $session->get('config')['height_of_shelves'] != '' ? $session->get('config')['height_of_shelves'] : yii::$app->params['shelfConfig']['0']; ?>" required="" />
											<span class="size-label">cm</span>
										</span>
									</div>
									<div class="slider-content">
										<span class="start-val">0 cm</span>
										<input id="hex6" class="slider" type="text" data-slider-min="<?= yii::$app->params['height_of_shelves']['min'] ?>" data-slider-max="<?= yii::$app->params['height_of_shelves']['max'] ?>" data-slider-step="1" data-slider-value="<?= $session->get('config')['height_of_shelves'] != '' ? $session->get('config')['height_of_shelves'] : yii::$app->params['shelfConfig']['0']; ?>" data-slider-id="golden" />
										<span class="end-val">50 cm</span>
									</div>
								</div>

								<div class="form-group">
									<div class="label-text">
										<label>width of shelves<sup class="error-text">*</sup></label> <span class="info-icon"><i class="fa fa-info-circle"></i></span>
										<span class="slider-input size-label">
											<input type="text" class="form-control" id="wex6SliderVal" name="width_of_shelves" value="<?= $session->get('config')['width_of_shelves'] != '' ? $session->get('config')['width_of_shelves'] : yii::$app->params['shelfConfig']['0']; ?>" required="" />
											<span class="size-label">cm</span>
										</span>
									</div>
									<div class="slider-content">
										<span class="start-val">0 cm</span>
										<input id="wex6" class="slider" type="text" data-slider-min="<?= yii::$app->params['width_of_shelves']['min'] ?>" data-slider-max="<?= yii::$app->params['width_of_shelves']['max'] ?>" data-slider-step="1" data-slider-value="<?= $session->get('config')['width_of_shelves'] != '' ? $session->get('config')['width_of_shelves'] : yii::$app->params['shelfConfig']['0']; ?>" data-slider-id="golden" />
										<span class="end-val">50 cm</span>
									</div>
								</div>
								<div class="form-group">
									<div class="label-text">
										<label>Depth of shelves<sup class="error-text">*</sup></label> <span class="info-icon"><i class="fa fa-info-circle"></i></span>
										<span class="slider-input size-label">
											<input type="text" class="form-control" id="dex6SliderVal" name="depth_of_shelves" value="<?= $session->get('config')['depth_of_shelves'] != '' ? $session->get('config')['depth_of_shelves'] : yii::$app->params['shelfConfig']['3']; ?>" required="" />
											<span class="size-label">cm</span>
										</span>
									</div>
									<div class="slider-content">
										<span class="start-val">0 cm</span>
										<input id="dex6" class="slider" type="text" data-slider-min="<?= yii::$app->params['depth_of_shelves']['min'] ?>" data-slider-max="<?= yii::$app->params['depth_of_shelves']['max'] ?>" data-slider-step="1" data-slider-value="<?= $session->get('config')['depth_of_shelves'] != '' ? $session->get('config')['depth_of_shelves'] : yii::$app->params['shelfConfig']['3']; ?>" data-slider-id="golden" />
										<span class="end-val">50 cm</span>
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
						<button class="next btn">OK</button>
					</div>
				</form>
			</div>
			<!-- /.box-body -->
		</div>
	</div>
	<!-- End Frame Filter section -->
</div>