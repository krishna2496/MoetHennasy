<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\CommonHelper;
use yii\helpers\Url;
?>

<div class="catalogues-form">
    <div class="row">
        <div class="col-xs-12">
            <?php $form = ActiveForm::begin(); ?>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= Html::encode($this->title."  - ". $model->getMarketName($market_id)) ?>
                    </h3>    
                    <?= \yii\helpers\Html::a( 'Back', Yii::$app->request->referrer,['class' => 'btn btn-primary pull-right']);?>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="col-md-12">
                             <?= $form->field($model, 'market_segment_id')->dropDownList($segmentData,['prompt' => 'Select One']) ?>
                            </div>
                               <input type="hidden" value="<?= $market_id?>" name="market_id"/>
                              <div class="col-md-12">
                              <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
                              </div>
  <div class="col-md-12">
                             <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
  </div>
                        </div>
                        <div class="col-md-6">
                             <?= $form->field($model, 'address')->textarea(['rows' => 6]) ?>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="row" align="center">
                <div class="col-xs-6">
                    <?php if (isset($model->id) && $model->id) { ?>
                        <?= Html::a('Cancel', ['market-contacts/index/'.$market_id], ['class' => 'btn pull-right mw-md btn-inverse']) ?>
                    <?php } else { ?>
                        <?= Html::a('Reset', ['market-contacts/index/'.$market_id], ['class' => 'btn pull-right mw-md btn-inverse']) ?>
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






