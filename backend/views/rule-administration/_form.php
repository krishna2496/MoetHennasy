    <?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\CommonHelper;
?>
<div class="catalogues-form">
    <div class="row">
        <div class="col-xs-12">
            <?php $form = ActiveForm::begin(); ?>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= Html::encode($this->title) ?>
                    </h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'sku')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'ean')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="row">
                         <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-8">
                                   <?= $form->field($model, 'catalogueImage')->fileInput() ?>   
                                </div>
                                <?php if(isset($model->id) && $model->id) { ?>
                                <div class="col-md-3">
                                   <img class="img-responsive" style="width:100px;height: 100px" src="<?php echo CommonHelper::getImage(UPLOAD_PATH_CATALOGUES_IMAGES . $model->image); ?>"/>               
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        
                       
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'long_name')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'short_name')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                               <?= $form->field($model, 'brand_id')->dropDownList($brand, ['prompt' => 'Select One']); ?>    
                        </div>

                        <div class="col-md-6">
                             <?= $form->field($model, 'product_category_id')->dropDownList($product, ['prompt' => 'Select One','id'=>'user-role_id']); ?>    
                         

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                             <?= $form->field($model, 'product_sub_category_id')->dropDownList($productSubCatData, ['prompt' => 'Select One','id'=>'user-parent_user_id']); ?> 
                               
                        </div>    
                         <div class="col-md-6">
                             <?= $form->field($model, 'product_type_id')->dropDownList($productTypeData, ['prompt' => 'Select One','id'=>'user_user_id']); ?> 
                               
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'market_id')->dropDownList($market, ['prompt' => 'Select Status']); ?> 

                        </div>                
                        <div class="col-md-6">
                            <?= $form->field($model, 'width')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'height')->textInput(['maxlength' => true]) ?>
                        </div>

                        <div class="col-md-6">
                            <?= $form->field($model, 'scale')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'length')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'manufacturer')->textInput(['maxlength' => true]) ?>
                        </div> 
                    </div>
                    <div class="row">

                        <div class="col-md-6">
                            <?= $form->field($model, 'box_only')->dropDownList(Yii::$app->params['catalogue_status'], ['prompt' => 'Select One']); ?>              
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'market_share')->textInput(['maxlength' => true]) ?>    
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-6">
                            <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-6">            
                            <?= $form->field($model, 'top_shelf')->dropDownList(Yii::$app->params['catalogue_status'], ['prompt' => 'Select One']); ?>       
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" align="center">
                <div class="col-xs-6">
                    <?php if (isset($model->id) && $model->id) { ?>
                        <?= Html::a('Cancel', ['rule-administration/index'], ['class' => 'btn pull-right mw-md btn-inverse']) ?>
                    <?php } else { ?>
                        <?= Html::a('Reset', ['catalogues/create'], ['class' => 'btn pull-right mw-md btn-inverse']) ?>
                    <?php } ?>
                </div>
                <div class="col-xs-6">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-primary pull-left mw-md']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
	$("body").on("change", "#user-role_id",function(event){
		
		var product_id = parseInt($('#user-role_id').val());
                var data = {product_id : product_id};
		var str = "<option value>Select One</option>";
		moet.ajax("<?php echo CommonHelper::getPath('admin_url')?>catalogues/product-sub-category",data,'post').then(function(result){
                
			if(result.status.success == 1) {
				if(result.data.productCategories.length > 0) {
					$.each(result.data.productCategories, function(key, value){
						str += "<option value="+value.id+">"+value.name+"</option>";
					});
				}
			}
                       
			$('#user-parent_user_id').html(str);
			
		},function(result){
			alert('Fail');
		});
	});
</script>






