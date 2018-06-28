<?php
use yii\widgets\Pjax;
use yii\grid\GridView;
use kartik\switchinput\SwitchInput;

?>
<script>
 
</script>
<div class="col-sm-5 pull-right" id="tab-step-2">
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
					<div class="frame-chose-option">
						<div class="box box-default shelfs-store">
							<div class="box-header with-border">
								<h3 class="box-title">PRODUCTS SKUs</h3>
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-2x fa-angle-down"></i>
									</button>
								</div>
								<!-- /.box-tools -->
							</div>
							<!-- /.box-header -->
							<?php Pjax::begin(['id' => 'employee', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'POST']]) ?>
							<div class="box-body">
								<label class="barnd-select-msg">Select the products that wil be present on the display<sup class="text-red">*</sup>:</label>

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
                                                                                                    $checked = '';
                                                                                                    if($model['top_shelf'] == 1){
                                                                                                       $checked = 'checked'; 
                                                                                                    }
                                                                                                    return '<input disabled type="checkbox" class="toggle" '.$checked.' data-toggle="toggle" data-on="YES" data-off="NO">';
//													$value = ($model['top_shelf'] == 0) ? false : true;
//													$dvalue = ($model['top_shelf'] == 0) ? "0" : "1";
//													return '<div class="idDiv" dvalue="' . $dvalue . '" id="' . $model["id"] . '">' . SwitchInput::widget([
//														'name' => 'status_41[]',
//														'value' => $value,
//														'pluginOptions' => [
//															'onText' => 'Yes',
//															'offText' => 'No'
//														]
//													]) . '</div>';
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
								
								//                                                                    
							});
							console.log(productObject);
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
						//productArry.forEach(function(item){
						 //$("input[type=checkbox][value=" +item+ "]").attr("checked", "true")
						 //});

					</script>
					
								<!--
								<table id="example1" class="table table-hover product-table">
									<thead>
										<tr>
											<th>
												<div class="checkbox">
													<label><input type="checkbox"></label>
												</div>
											</th>
											<th>Products</th>
											<th>Product Types</th>
											<th>Market share</th>
											<th>WPS</th>
											<th>Product Category</th>
											<th>Top shelf</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>
												<div class="checkbox">
													<label><input type="checkbox"></label>
												</div>
											</td>
											<td>
												Product1
											</td>
											<td>Eg.nectar</td>
											<td>1-10</td>
											<td>Num</td>
											<td>Campagne</td>
											<td><input type="checkbox" class="toggle" checked data-toggle="toggle" data-on="YES" data-off="NO"></td>
										</tr>
										<tr>
											<td>
												<div class="checkbox">
													<label><input type="checkbox"></label>
												</div>
											</td>
											<td>
												Product1
											</td>
											<td>Eg.nectar</td>
											<td>1-10</td>
											<td>Num</td>
											<td>Campagne</td>
											<td><input type="checkbox" class="toggle" checked data-toggle="toggle" data-on="YES" data-off="NO"></td>
										</tr>
										</tfoot>
								</table>
								-->
							</div>
							<!-- /.box-body -->
                                                        <?php Pjax::end(); ?>
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
<?php /*
										
	<section class="col-md-5" id="tab2Content">
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
					<?php /* Pjax::begin(['id' => 'employee', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'POST']]) ?>

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
								//                                                                    
							});
							console.log(productObject);
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
						//productArry.forEach(function(item){
						 //$("input[type=checkbox][value=" +item+ "]").attr("checked", "true")
						 //});

					</script>
					<?php Pjax::end(); ?>
				</div>

			</div>
		</div>
	</section>
 */?>