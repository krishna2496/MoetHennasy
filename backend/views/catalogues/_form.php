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
                            <?= $form->field($model, 'catalogueImage')->fileInput() ?>  
                        </div>
                        <?php if (isset($model->id) && $model->id) { ?>
                            <div class="col-md-3">
                                <img class="img-responsive" style="width:200px;height: 150px" src="<?php echo CommonHelper::getImage(UPLOAD_PATH_CATALOGUES_IMAGES . $model->image); ?>"/>        
                            </div>
                        <?php } ?>
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
                            <?= $form->field($model, 'product_category_id')->textInput(['maxlength' => true]) ?>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'product_sub_category_id')->textInput(['maxlength' => true]) ?>                 
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
                        <?= Html::a('Cancel', ['catalogues/index'], ['class' => 'btn pull-right mw-md btn-inverse']) ?>
                    <?php } else { ?>
                        <?= Html::a('Reset', ['catalogues/create'], ['class' => 'btn pull-right mw-md btn-inverse']) ?>
                    <?php } ?>
                </div>
                <div class="col-xs-6">
                    <?= Html::submitButton('OK', ['class' => 'btn btn-primary pull-left mw-md']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>







