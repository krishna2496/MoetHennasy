<?php
use yii\widgets\Pjax;
use yii\grid\GridView;
?>

<div class="col-sm-5 pull-right" id="tab-step-2">
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
				<form class="frame-filt-form">
					<div class="frame-chose-option">
						<div class="box box-default shelfs-store">
							<div class="box-header with-border">
								<h3 class="box-title">PRODUCTS SKUs</h3>
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-2x fa-angle-up"></i>
									</button>
								</div>
								<!-- /.box-tools -->
							</div>
							<!-- /.box-header -->
							
							<div class="box-body">
								<label class="barnd-select-msg">Select the products that will be present on the display<sup class="text-red">*</sup>:</label>
<?php Pjax::begin(['id' => 'employee', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'POST']]) ?>
								<?=
								GridView::widget([
									'dataProvider' => $dataProvider,
									'layout' => '<div class="table-responsive product-table">{items}</div><div class="row"><div class="col-sm-12"><div class="dataTables_paginate paging_simple_numbers">{pager}</div></div></div>',
									'columns' => [
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
													$checked = ($model['top_shelf'] == 1)?'checked':'';
													return '<input disabled type="checkbox" class="toggle" '.$checked.' data-toggle="toggle" data-on="YES" data-off="NO">';
												}
											]
										],
									]);
                                                                                        ?>
                                            <script type="text/javascript">
                                                
						$(document).ready(function () {
							$('input[name="selection[]"]').each(function (skey, sval) {
								var sobj = {};
								sobj["sel"] = false;
								sobj["shelf"] = false;
								if (typeof (productObject[$(sval).val()]) === 'undefined')
								{
									productObject[$(sval).val()] = sobj;
								}
								if (typeof (productObject[$(sval).val()]) !== 'undefined' && productObject[$(sval).val()]["sel"] === true)
								{
									$('input[type="checkbox"][value="' + $(sval).val() + '"]').attr('checked', true).iCheck('update');
								}
							});
						});

						$('.select-on-check-all').on('ifChecked', function (event) {
							$('input[name="selection[]"]').iCheck('check');
						});

						$('.select-on-check-all').on('ifUnchecked', function (event) {
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
					</script>					
							  <?php Pjax::end(); ?>
                                                          </div> <!-- /.box-body -->
						</div>
					</div>
					<div class="submit-fl wizard">
						<button class="btn reset-btn">Reset</button>
						<button class="next btn">Save</button>
					</div>
				</form>
			</div>
			<!-- /.box-body -->
		</div>
	</div>
	<!-- End Frame Filter section -->
</div>
