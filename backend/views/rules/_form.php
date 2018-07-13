<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\CommonHelper;

?>

<div class="market-segments-form">
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
                        <div class="col-md-12">
                          <?= $form->field($model, 'type')->textInput(['maxlength' => true,'readonly' => !$model->isNewRecord]) ?>
                        </div>
                        <?php 
                        if($model->isNewRecord){ ?>
                        <div class="col-md-12">
                             <?= $form->field($model, 'product_fields')->textInput(['maxlength' => true]) ?>
                        </div>
                        <?php } ?>
                         <div class="col-md-12">
                               <?= $form->field($model, 'detail')->textarea(['maxlength' => true]) ?>
                         </div>
                        
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-8">
                                    <?= $form->field($model, 'ruleImage')->fileInput() ?>   
                                </div>
                                <?php if (isset($model->id) && $model->id) { ?>
                                    <div class="col-md-3">
                                        <img class="img-responsive" style="width:100px;height: 100px" src="<?php echo CommonHelper::getImage(UPLOAD_PATH_RULES_IMAGES . $model->image); ?>"/>               
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                       
                    </div>
                </div>
            </div>
            <div class="row" align="center">
                <div class="col-xs-6">
                    <?php if (isset($model->id) && $model->id) { ?>
                        <?= Html::a('Cancel', ['rules/index'], ['class' => 'btn pull-right mw-md btn-inverse']) ?>
                    <?php } else { ?>
                        <?= Html::a('Reset', ['rules/create'], ['class' => 'btn pull-right mw-md btn-inverse']) ?>
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
